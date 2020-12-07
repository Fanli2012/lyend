<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\Helper;
use App\Common\Librarys\ReturnData;
use Illuminate\Http\Request;
use App\Http\Logic\LogLogic;
use App\Http\Model\LogModel;

class LogController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new LogLogic();
    }

    public function index(Request $request)
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (!empty($_REQUEST["keyword"])) {
                $query->where('login_name', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('ip', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('url', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('content', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            //用户ID
            if (isset($_REQUEST['login_id'])) {
                $query->where('login_id', $_REQUEST["login_id"]);
            }
            //IP
            if (isset($_REQUEST['ip'])) {
                $query->where('ip', $_REQUEST["ip"]);
            }
            //模块
            if (isset($_REQUEST['type']) && $_REQUEST['type'] !== '') {
                $query->where('type', $_REQUEST["type"]);
            }
            //请求方式
            if (isset($_REQUEST['http_method'])) {
                $query->where('http_method', $_REQUEST["http_method"]);
            }
        };

        $list = $this->getLogic()->getPaginate($where, array('id', 'desc'));
        $data['list'] = $list;
        return view('admin.log.index', $data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_log'));
        }

        return view('admin.log.add');
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
            success_jump($res['msg'], route('admin_log'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.log.edit', $assign_data);
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

    //清空
    public function clear()
    {
        // 截断表
        DB::statement('truncate table `fl_log`');
        success_jump('操作成功');
    }
}