<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\AdminModel;

class LoginController extends CommonController
{
    //页面跳转
    public function jump()
    {
        return view('admin.login.jump');
    }

    //404页面
    public function page404()
    {
        return view('admin.login.404');
    }

    /**
     * 登录页面
     */
    public function index()
    {
        if (Helper::isPostRequest()) {
            // 验证码验证
            $validator = Validator::make($_POST, [
                'captcha' => ['required', 'captcha'],
            ], [
                'captcha.required' => '验证码不能为空',
                'captcha.captcha' => '验证码错误',
            ]);
            if ($validator->fails()) {
                error_jump($validator->errors()->first());
            }

            $res = logic('Admin')->login($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg'], route('admin_login'));
            }
            $admin_info = $res['data'];
            $_SESSION['admin_info'] = $admin_info;
            success_jump($res['msg'], route('admin'));
        }
        if (isset($_SESSION['admin_info'])) {
            header("Location: " . route('admin'));
            exit;
        }

        return view('admin.login.index');
    }

    //退出登录
    public function logout()
    {
        session_unset();
        session_destroy();// 退出登录，清除session
        success_jump('退出成功', route('home'));
    }

    //密码恢复
    public function recoverpwd()
    {
        $data["name"] = "admin888";
        $data["pwd"] = "21232f297a57a5a743894a0e4a801fc3";

        if (DB::table('admin')->where('id', 1)->update($data)) {
            success_jump('密码恢复成功', route('admin_login'));
        }

        error_jump('密码恢复失败', route('home'));
    }

    /**
     * 判断用户名是否存在
     */
    public function userexists()
    {
        $where['name'] = "";
        if (isset($_POST["name"]) && !empty($_POST["name"])) {
            $where['name'] = $_POST["name"];
        } else {
            return 0;
        }

        return DB::table("admin")->where($where)->count();
    }

    //测试
    public function test()
    {
        //管理员菜单
        /* for ($x=1; $x<=103; $x++)
        {
            DB::table('access')->insert(['role_id' => 1, 'menu_id' => $x]);
        } */

        echo json_encode($_REQUEST);
        exit;
    }
}