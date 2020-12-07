<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\TagLogic;
use App\Http\Model\TagModel;

class TagController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new TagLogic();
    }

    //列表
    public function index()
    {
        $where = array();
        if (!empty($_REQUEST["keyword"])) {
            $where[] = array('name', 'like', '%' . $_REQUEST['keyword'] . '%');
        }
        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);
        $assign_data['list'] = $list;
        return view('admin.tag.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $_POST['add_time'] = $_POST['update_time'] = time(); //添加时间、更新时间
            $_POST['click'] = rand(200, 500); //点击量

            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            //添加Tag下的文章
            $tagarc = "";
            if (!empty($_POST["tagarc"])) {
                $tagarc = str_replace("，", ",", $_POST["tagarc"]);
                if (!preg_match("/^\d*$/", str_replace(",", "", $tagarc))) {
                    $tagarc = "";
                }
            } //Tag文章列表
            if ($tagarc != "") {
                $arr = explode(",", $tagarc);
                foreach ($arr as $row) {
                    $data2['tag_id'] = $res['data'];
                    $data2['article_id'] = $row;
                    logic('Taglist')->add($data2);
                }
            }
            success_jump($res['msg'], route('admin_tag'));
        }

        return view('admin.tag.add');
    }

    //修改
    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $where2['tag_id'] = $_POST['id'];
            unset($_POST['id']);

            $_POST['update_time'] = time(); //更新时间

            $tagarc = "";
            if (!empty($_POST["tagarc"])) {
                $tagarc = str_replace("，", ",", $_POST["tagarc"]);
                if (!preg_match("/^\d*$/", str_replace(",", "", $tagarc))) {
                    $tagarc = "";
                }
            } //Tag文章列表
            unset($_POST["tagarc"]);

            $res = $this->getLogic()->edit($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            //获取该标签下的文章id
            $posts = model('Taglist')->getAll($where2);
            $article_id_list = "";
            if (!empty($posts)) {
                foreach ($posts as $row) {
                    $article_id_list = $article_id_list . ',' . $row->article_id;
                }
            }
            $article_id_list = ltrim($article_id_list, ",");

            if ($tagarc != "" && $tagarc != $article_id_list) {
                model('Taglist')->del($where2);

                $arr = explode(",", $tagarc);
                foreach ($arr as $row) {
                    $data2['tag_id'] = $where2['tag_id'];
                    $data2['article_id'] = $row;
                    logic('Taglist')->add($data2);
                }
            } elseif ($tagarc == "") {
                model('Taglist')->del($where2);
            }
            success_jump($res['msg'], route('admin_tag'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        //获取该标签下的文章id
        $posts = model('Taglist')->getAll(['tag_id' => $where['id']]);
        $article_id_list = "";
        if (!empty($posts)) {
            foreach ($posts as $row) {
                $article_id_list = $article_id_list . ',' . $row->article_id;
            }
        }
        $assign_data['article_id_list'] = ltrim($article_id_list, ",");
        return view('admin.tag.edit', $assign_data);
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
