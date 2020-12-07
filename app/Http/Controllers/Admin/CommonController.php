<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public $admin_info;

    public function __construct()
    {
        parent::__construct();

		// 添加管理员操作记录
		$this->operation_log_add();
    }

	// 添加管理员操作记录
	public function operation_log_add()
    {
		$time = time();
		// 记录操作
        if ($this->admin_info) {
            $data['login_id'] = $this->admin_info['id'];
            $data['login_name'] = $this->admin_info['name'];
        }
        $data['type'] = 1;
        $data['ip'] = request()->ip();
        $data['url'] = mb_strcut(request()->url(), 0, 255, 'UTF-8');
        $data['http_method'] = request()->method();
        $data['domain_name'] = mb_strcut($_SERVER['SERVER_NAME'], 0, 60, 'UTF-8');
        if ($data['http_method'] != 'GET') { $data['content'] = mb_strcut(json_encode(request()->toArray(), JSON_UNESCAPED_SLASHES), 0, 255, 'UTF-8'); }
		if (!empty($_SERVER['HTTP_REFERER'])) { $data['http_referer'] = mb_strcut($_SERVER['HTTP_REFERER'], 0, 255, 'UTF-8'); }
        $data['add_time'] = $time;
        logic('Log')->add($data);
    }
}
