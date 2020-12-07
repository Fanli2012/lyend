<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\SysconfigLogic;

class SysconfigController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new SysconfigLogic();
    }

    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('info', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
        };

        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);
        //echo '<pre>';print_r($list);exit;
        $assign_data['list'] = $list;

        return view('admin.sysconfig.index', $assign_data);
    }

    //添加参数，视图
    public function add()
    {
        if (Helper::isPostRequest()) {
            $res = $this->getLogic()->add(request()->all());
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            cache()->forget('sysconfig');
            success_jump($res['msg'], route('admin_sysconfig'));
        }
        return view('admin.sysconfig.add');
    }

    //修改参数，视图
    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $_POST['id'];
            unset($_POST['id']);

            $res = $this->getLogic()->edit($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            cache()->forget('sysconfig');
            success_jump($res['msg'], route('admin_sysconfig'));
        }
        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.sysconfig.edit', $assign_data);
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
        cache()->forget('sysconfig');
        success_jump("删除成功");
    }

    //其它配置
    public function other()
    {
        if (Helper::isPostRequest()) {
            $post_data = request()->all();
            foreach ($post_data as $k => $v) {
                model('Sysconfig')->edit(['value' => $v], ['varname' => $k]);
            }
            // 删除缓存数据
            cache()->forget('sysconfig');
            success_jump('操作成功');
        }

        return view('admin.sysconfig.other');
    }
}