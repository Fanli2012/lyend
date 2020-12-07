<?php

namespace App\Http\Controllers\Admin;

use App\Common\Librarys\ReturnData;
use App\Common\Helper;
use Illuminate\Http\Request;
use App\Http\Logic\UserRechargeLogic;
use App\Http\Model\UserRechargeModel;

class UserRechargeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new UserRechargeLogic();
    }

    //列表
    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('recharge_sn', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            //用户ID
            if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] > 0) {
                $query->where('user_id', '=', $_REQUEST['user_id']);
            }
            //充值类型：1微信，2支付宝
            if (isset($_REQUEST['pay_type']) && $_REQUEST['pay_type'] > 0) {
                $query->where('pay_type', '=', $_REQUEST['pay_type']);
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);
        //echo '<pre>';print_r($list);exit;
        $assign_data['list'] = $list;

        return view('admin.user_recharge.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }

            success_jump($res['msg'], route('admin_user_recharge'));
        }

        return view('admin.user_recharge.add');
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
            success_jump($res['msg'], route('admin_user_recharge'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.user_recharge.edit', $assign_data);
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
}