<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\Helper;
use App\Common\Librarys\ReturnData;
use Illuminate\Http\Request;
use App\Http\Logic\BonusLogic;
use App\Http\Model\BonusModel;

class BonusController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new BonusLogic();
    }

    //列表
    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('name', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            //状态：0可用，1不可用
            if (isset($_REQUEST["status"]) && $_REQUEST["status"] != '') {
                $query->where('status', '=', $_REQUEST["status"]);
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);
        $assign_data['list'] = $list;

        return view('admin.bonus.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            if (isset($_POST['start_time']) && $_POST['start_time'] != '') {
                $_POST['start_time'] = strtotime($_POST['start_time']);
            }
            if (isset($_POST['end_time']) && $_POST['end_time'] != '') {
                $_POST['end_time'] = strtotime($_POST['end_time']);
            }
            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_bonus'));
        }

        return view('admin.bonus.add');
    }

    //修改
    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $_POST['id'];
            unset($_POST['id']);

            if (isset($_POST['start_time']) && $_POST['start_time'] != '') {
                $_POST['start_time'] = strtotime($_POST['start_time']);
            }
            if (isset($_POST['end_time']) && $_POST['end_time'] != '') {
                $_POST['end_time'] = strtotime($_POST['end_time']);
            }
            $res = $this->getLogic()->edit($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_bonus'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        //时间戳转日期格式
        if ($post->start_time == 0) {
            $post->start_time = '';
        } else {
            $post->start_time = date('Y-m-d H:i:s', $post->start_time);
        }
        if ($post->end_time == 0) {
            $post->end_time = '';
        } else {
            $post->end_time = date('Y-m-d H:i:s', $post->end_time);
        }

        $assign_data['post'] = $post;

        return view('admin.bonus.edit', $assign_data);
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