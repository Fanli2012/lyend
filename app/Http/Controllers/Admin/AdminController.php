<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\AdminLogic;
use App\Http\Model\AdminModel;

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new AdminLogic();
    }

    //列表
    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('name', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('mobile', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('email', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            $query->where('delete_time', '=', 0); //未删除
        };
        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);
        //echo '<pre>';print_r($list);exit;
        $assign_data['list'] = $list;
        return view('admin.admin.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_admin'));
        }
        $assign_data['role_list'] = model('AdminRole')->getAll([], ['listorder', 'asc']);
        return view('admin.admin.add', $assign_data);
    }

    //修改
    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $_POST['id'];
            unset($_POST['id']);
            if (!$_POST['pwd']) {
                unset($_POST['pwd']);
            }

            $res = $this->getLogic()->edit($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_admin'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;
        $assign_data['role_list'] = model('AdminRole')->getAll([], ['listorder', 'asc']);

        return view('admin.admin.edit', $assign_data);
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
        success_jump("删除成功");
    }
}