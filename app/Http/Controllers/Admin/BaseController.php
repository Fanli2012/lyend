<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

class BaseController extends CommonController
{
    public function __construct()
    {
        //判断是否登录
        if (!isset($_SESSION['admin_info'])) {
            header("Location:" . route('admin_page404'));
            exit();
        }
        $this->admin_info = $_SESSION['admin_info'];

        //判断是否拥有权限
        if ($this->admin_info['role_id'] <> 1) {
            $uncheck = array('admin_page404', 'admin_jump', 'admin', 'admin_index_upconfig', 'admin_index_upcache', 'admin_welcome');

            if (!in_array(\Route::currentRouteName(), $uncheck)) {
                $menu_id = DB::table('menu')->where('action', \Route::currentRouteName())->value('id');
                $check = DB::table('access')->where(['role_id' => $_SESSION['admin_info']['role_id'], 'menu_id' => $menu_id])->first();

                if (!$check) {
                    error_jump('你没有权限访问，请联系管理员', route('admin'));
                }
            }
        }
        parent::__construct();
    }

}
