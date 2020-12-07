<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    protected $login_info;

    /**
     * 初始化
     * @param void
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        //跨域访问
        if (env('APP_DEBUG') == true) {
            header("Access-Control-Allow-Origin:*");
            // 响应类型
            header("Access-Control-Allow-Methods:GET,POST");
            // 响应头设置
            header("Access-Control-Allow-Headers:x-requested-with,content-type,x-access-token,x-access-appid");
        }

        // 添加操作记录
        $this->operation_log_add($this->login_info);
    }

    // 添加操作记录
    public function operation_log_add($login_info = null)
    {
        $time = time();
        // 记录操作
        if ($login_info) {
            $data['login_id'] = $login_info->id;
            $data['login_name'] = $login_info->user_name;
        }
        $data['type'] = 3;
        $data['ip'] = request()->ip();
        $data['url'] = mb_strcut(url()->full(), 0, 255, 'UTF-8');
        $data['http_method'] = request()->method();
        $data['domain_name'] = mb_strcut($_SERVER['SERVER_NAME'], 0, 60, 'UTF-8');
        if ($data['http_method'] != 'GET') {
            $data['content'] = mb_strcut(json_encode(request()->all(), JSON_UNESCAPED_SLASHES), 0, 255, 'UTF-8');
        }
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $data['http_referer'] = mb_strcut($_SERVER['HTTP_REFERER'], 0, 255, 'UTF-8');
        }
        $data['add_time'] = $time;
        logic('Log')->add($data);
    }
}
