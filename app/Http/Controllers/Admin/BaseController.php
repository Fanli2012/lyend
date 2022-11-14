<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

class BaseController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
        // 未登录
        if (!$this->admin_info) {dd($this->admin_info);
            header("Location:" . route('admin_page404'));
            exit();
        }
        // 当前账号不是超级管理员，判断是否拥有权限
        if (isset($this->admin_info['role_id']) && $this->admin_info['role_id'] != 1) {
            $this->verify_permission();
        }
    }

    // 权限验证
    public function verify_permission()
    {
        //判断是否拥有权限
        if ($this->admin_info['role_id'] <> 1) {
            $route = \Route::currentRouteName();
            // 不需要权限验证的列表
            $uncheck = array('admin_page404', 'admin_jump', 'admin', 'admin_index_upconfig', 'admin_index_upcache', 'admin_welcome');

            if (!in_array($route, $uncheck)) {
                $menu_id = DB::table('menu')->where('action', $route)->value('id');
                // 是否存在该菜单
                if (!$menu_id) {
                    error_jump('你没有权限访问，请联系管理员', route('admin'));
                }
                $check = DB::table('access')->where(['role_id' => $this->admin_info['role_id'], 'menu_id' => $menu_id])->first();
                if (!$check) {
                    error_jump('你没有权限访问，请联系管理员', route('admin'));
                }
            }
        }
    }

}
