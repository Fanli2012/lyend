<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\GoodsTypeLogic;
use App\Http\Model\GoodsTypeModel;

class GoodsTypeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new GoodsTypeLogic();
    }

    public function index()
    {
        $list = model('GoodsType')->tree_to_list(model('GoodsType')->list_to_tree());
        $assign_data['list'] = $list;

        return view('admin.goods_type.index', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $_POST['add_time'] = $_POST['update_time'] = time(); //添加时间、更新时间

            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }

            success_jump($res['msg'], route('admin_goods_type'));
        }

        $parent_id = request('parent_id', 0);
        if ($parent_id != 0) {
            if (preg_match('/[0-9]*/', $parent_id)) {
            } else {
                error_jump('参数错误');
            }
            $assign_data['parent_goods_type'] = model('GoodsType')->getOne(['id' => $parent_id], 'id,name');
        }
        $assign_data['parent_id'] = $parent_id;

        return view('admin.goods_type.add', $assign_data);
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
            success_jump($res['msg'], route('admin_goods_type'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.goods_type.edit', $assign_data);
    }

    //删除
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
        //判断该分类下是否有商品，如果有把该分类下的商品也一起删除
        if (model('Goods')->getCount(['type_id' => $id]) > 0) {
            if (!model('Goods')->del(['type_id' => $id])) {
                error_jump('分类下的商品删除失败');
            }
            success_jump('删除成功');
        }
        success_jump('删除成功');
    }
}