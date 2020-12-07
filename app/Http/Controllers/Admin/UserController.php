<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\UserLogic;
use App\Http\Model\UserModel;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new UserLogic();
    }

    //列表
    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('mobile', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('nickname', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('user_name', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('email', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            //用户状态
            if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
                $query->where('status', '=', $_REQUEST['status']);
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);
        $assign_data['list'] = $list;

        return view('admin.user.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $res = $this->getLogic()->register(array_filter($_POST));
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump('操作成功', route('admin_user'));
        }
        $assign_data['user_rank'] = model('UserRank')->getAll(array(), ['rank', 'asc']);
        return view('admin.user.add', $assign_data);
    }

    //修改
    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $_POST['id'];
            unset($_POST['id']);

            $res = $this->getLogic()->userInfoUpdate($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump('操作成功', route('admin_user'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.user.edit', $assign_data);
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