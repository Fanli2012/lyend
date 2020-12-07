<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Logic\UserMoneyLogic;
use App\Http\Model\UserMoneyModel;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;

class UserMoneyController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new UserMoneyLogic();
    }

    //列表
    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('desc', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            if (isset($_REQUEST['user_id'])) {
                $query->where('user_id', '=', $_REQUEST["user_id"]);
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);
        //echo '<pre>';print_r($list);exit;
        $assign_data['list'] = $list;

        return view('admin.user_money.index', $assign_data);
    }

    //后台人工充值
    public function add()
    {
        if (Helper::isPostRequest()) {
            if (!is_numeric($_POST['money']) || $_POST['money'] == 0) {
                error_jump('充值金额格式不正确');
            }

            $user_money_data['type'] = 1; //0增加,1减少
            if (!$_POST['desc']) {
                error_jump('请填写说明/备注');
            }
            $user_money_data['desc'] = $_POST['desc'];
            if ($_POST['money'] > 0) {
                $user_money_data['type'] = 0;
            }

            //增加用户余额及余额记录
            $user_money_data['user_id'] = $_POST['user_id'];
            $user_money_data['money'] = abs($_POST['money']);
            $res = logic('UserMoney')->add($user_money_data);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_user_money'));
        }

        $user = model('User')->getOne(array('id' => request('user_id', 0)), 'user_name,mobile,money,id');
        if (!$user) {
            error_jump('参数错误');
        }
        $assign_data['user'] = $user;

        return view('admin.user_money.add', $assign_data);
    }

    //修改
    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $_POST['id'];
            unset($_POST['id']);

            $res = $this->getLogic()->edit($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_user_money'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.user_money.edit', $assign_data);
    }

    //删除
    public function del()
    {
        if (!check_is_number(request('id', null))) {
            error_jump('删除失败！请重新提交');
        }
        $where['id'] = request('id');

        $res = $this->getLogic()->del($where);
        if ($res['code'] != ReturnData::SUCCESS) {
            error_jump($res['msg']);
        }
        success_jump('删除成功');
    }

    public function change_status()
    {
        if (!check_is_number(request('id', null))) {
            error_jump('删除失败！请重新提交');
        }
        $id = request('id');

        if (!isset($_POST['type'])) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }

        $user_withdraw = model('UserMoney')->getOne(array('id' => $id, 'status' => 0));
        if (!$user_withdraw) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }

        //0拒绝，1成功
        $edit_user_withdraw = array();
        if ($_POST['type'] == 0) {
            $edit_user_withdraw['status'] = 4;
            //增加用户余额及余额记录
            $user_money_data['user_id'] = $user_withdraw->user_id;
            $user_money_data['type'] = 0;
            $user_money_data['money'] = $user_withdraw->money;
            $user_money_data['desc'] = '提现失败-返还余额';
            $user_money = logic('UserMoney')->add($user_money_data);
        } elseif ($_POST['type'] == 1) {
            $edit_user_withdraw['status'] = 2;
        }

        if (!$edit_user_withdraw) {
            return ReturnData::create(ReturnData::FAIL);
        }

        $res = model('UserMoney')->edit($edit_user_withdraw, array('id' => $id));
        if (!$res) {
            return ReturnData::create(ReturnData::FAIL);
        }

        return ReturnData::create(ReturnData::SUCCESS);
    }
}