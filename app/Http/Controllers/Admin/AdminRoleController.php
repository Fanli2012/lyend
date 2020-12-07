<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\AdminRoleLogic;
use App\Http\Model\AdminRoleModel;

class AdminRoleController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new AdminRoleLogic();
    }

    public function index(Request $request)
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('name', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['listorder', 'asc']);
        //echo '<pre>';print_r($list);exit;
        $assign_data['list'] = $list;

        return view('admin.admin_role.index', $assign_data);
    }


    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_admin_role'));
        }

        return view('admin.admin_role.add');
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

            success_jump($res['msg'], route('admin_admin_role'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.admin_role.edit', $assign_data);
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
        model('Access')->del(['role_id' => $where['id']]);
        success_jump("删除成功");
    }

    //角色权限设置视图
    public function permissions()
    {
        //角色权限设置
        if (Helper::isPostRequest()) {
            $menus = array();
            if ($_POST['menuid'] && $_POST['role_id']) {
                foreach ($_POST['menuid'] as $row) {
                    $menus[] = array(
                        'role_id' => $_POST['role_id'],
                        'menu_id' => $row
                    );
                }
            }

            if (!$menus) {
                error_jump('操作失败');
            }

            //先删除权限
            model('Access')->del(['role_id' => $_POST['role_id']]);
            //添加权限
            $res = model('Access')->add($menus, 2);
            if (!$res) {
                error_jump('操作失败');
            }
            success_jump('操作成功', route('admin_admin_role'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('您访问的页面不存在或已被删除');
        }
        $role_id = $where['role_id'] = request('id');

        $menu = array();
        $access = model('Access')->getAll(['role_id' => $role_id]);
        if ($access) {
            foreach ($access as $k => $v) {
                $menu[] = $v->menu_id;
            }
        }
        //echo '<pre>';print_r($this->get_category());exit;
        $menus = $this->category_tree($this->get_category(), 0);
        if ($menus) {
            foreach ($menus as $k => $v) {
                $menus[$k]['is_access'] = 0;

                if (!empty($menu) && in_array($v['id'], $menu)) {
                    $menus[$k]['is_access'] = 1;
                }
            }
        }

        $assign_data['menus'] = $menus;
        $assign_data['role_id'] = $role_id;

        return view('admin.admin_role.permissions', $assign_data);
    }

    /**
     * 将列表生成树形结构
     * @param int $parent_id 父级ID
     * @param int $deep 层级
     * @return array
     */
    public function get_category($parent_id = 0, $deep = 0)
    {
        $arr = array();

        $cats = object_to_array(model('Menu')->getAll(['parent_id' => $parent_id], ['listorder', 'asc']));
        if ($cats) {
            foreach ($cats as $row)//循环数组
            {
                $row['deep'] = $deep;
                //如果子级不为空
                if ($child = $this->get_category($row['id'], $deep + 1)) {
                    $row['child'] = $child;
                }
                $arr[] = $row;
            }
        }

        return $arr;
    }

    /**
     * 树形结构转成列表
     * @param array $list 数据
     * @param int $parent_id 父级ID
     * @return array
     */
    public function category_tree($list, $parent_id = 0)
    {
        global $temp;
        if (!empty($list)) {
            foreach ($list as $v) {
                $temp[] = array("id" => $v['id'], "deep" => $v['deep'], "name" => $v['name'], "parent_id" => $v['parent_id']);
                //echo $v['id'];
                if (isset($v['child'])) {
                    $this->category_tree($v['child'], $v['parent_id']);
                }
            }
        }

        return $temp;
    }
}