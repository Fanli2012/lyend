<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\PageLogic;

class PageController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new PageLogic();
    }

    //列表
    public function index()
    {
        $where = function ($query) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('title', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('filename', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);

        //echo '<pre>';print_r($list);exit;
        $assign_data['list'] = $list;

        return view('admin.page.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $_POST['add_time'] = $_POST['update_time'] = time();//添加时间、更新时间
            $_POST['click'] = rand(200, 500);//点击

            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_page'));
        }

        return view('admin.page.add');
    }

    //修改
    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $_POST['id'];
            unset($_POST['id']);

            $_POST['update_time'] = time();//更新时间

            $res = $this->getLogic()->edit($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }

            success_jump($res['msg'], route('admin_page'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.page.edit', $assign_data);
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
        success_jump("删除成功");
    }
}