<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use Illuminate\Http\Request;
use App\Http\Logic\ArticleTypeLogic;

class ArticleTypeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new ArticleTypeLogic();
    }

    public function index()
    {
        $list = model('ArticleType')->tree_to_list(model('ArticleType')->list_to_tree());
        $assign_data['list'] = $list;

        return view('admin.article_type.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $_POST['add_time'] = $_POST['update_time'] = time(); //添加时间、更新时间
            $_POST['admin_id'] = $this->admin_info['id']; // 管理员发布者ID

            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }

            success_jump($res['msg'], route('admin_article_type'));

        }

        $parent_id = request('parent_id', 0);
        if ($parent_id > 0) {
            $assign_data['parent_article_type'] = model('ArticleType')->getOne(['id' => $parent_id]);
        }
        $assign_data['parent_id'] = $parent_id;

        return view('admin.article_type.add', $assign_data);
    }

    //修改
    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $_POST['id'];
            unset($_POST['id']);

            $_POST['update_time'] = time(); //更新时间

            $res = $this->getLogic()->edit($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_article_type'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.article_type.edit', $assign_data);
    }

    public function del()
    {
        if (!check_is_number(request('id', null))) {
            error_jump('删除失败！请重新提交');
        }
        $id = $where['id'] = request('id');

        if ($this->getLogic()->getOne(['parent_id' => $where['id']])) {
            error_jump('删除失败！请先删除子栏目');
        }
        $res = $this->getLogic()->del($where);
        if ($res['code'] != ReturnData::SUCCESS) {
            error_jump($res['msg']);
        }

        if (model('Article')->getCount(['type_id' => $id]) > 0) //判断该分类下是否有文章，如果有把该分类下的文章也一起删除
        {
            if (!model('Article')->del(['type_id' => $id])) {
                error_jump('栏目下的文章删除失败');
            }
            success_jump('删除成功', route('admin_article_type'));
        }
        success_jump('删除成功', route('admin_article_type'));
    }
}