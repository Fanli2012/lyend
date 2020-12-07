<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\MenuLogic;
use App\Http\Model\MenuModel;

class MenuController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new MenuLogic();
    }

    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('name', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            if (isset($_REQUEST["status"]) && $_REQUEST["status"] != '') {
                $query->where('status', '=', $_REQUEST["status"]);
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);
        //echo '<pre>';var_dump($list->total());exit;
        $assign_data['list'] = $list;

        return view('admin.menu.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            //添加超级管理员权限
            logic('Access')->add(['role_id' => 1, 'menu_id' => $res['data']]);
            success_jump($res['msg'], route('admin_menu'));
        }

        if (!empty($_GET['parent_id'])) {
            $parent_id = $_GET['parent_id'];
        } else {
            $parent_id = 0;
        }
        $menu = model('Menu')->category_tree(model('Menu')->get_category());

        $assign_data['menu'] = $menu;
        $assign_data['parent_id'] = $parent_id;

        return view('admin.menu.add', $assign_data);
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
            success_jump($res['msg'], route('admin_menu'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;
        $assign_data['menu'] = model('Menu')->category_tree(model('Menu')->get_category());

        return view('admin.menu.edit', $assign_data);
    }

    //删除
    public function del()
    {
        if (!check_is_number(request('id', null))) {
            error_jump('删除失败！请重新提交');
        }
        $where['id'] = request('id'); //角色ID

        $res = $this->getLogic()->del($where);
        if ($res['code'] != ReturnData::SUCCESS) {
            error_jump($res['msg']);
        }
        //删除权限
        model('Access')->del($where);
        success_jump("删除成功");
    }
}