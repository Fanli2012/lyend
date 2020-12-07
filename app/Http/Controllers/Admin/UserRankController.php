<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Logic\UserRankLogic;
use App\Http\Model\UserRankModel;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;

class UserRankController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new UserRankLogic();
    }

    //列表
    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('title', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['listorder', 'asc']);
        //echo '<pre>';print_r($list);exit;
        $assign_data['list'] = $list;

        return view('admin.user_rank.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }

            success_jump($res['msg'], route('admin_user_rank'));
        }

        return view('admin.user_rank.add');
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
            success_jump($res['msg'], route('admin_user_rank'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.user_rank.edit', $assign_data);
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