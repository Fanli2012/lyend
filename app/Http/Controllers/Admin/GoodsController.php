<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\GoodsLogic;
use App\Http\Model\GoodsModel;

class GoodsController extends BaseController
{
    public $goods_type_list; //商品分类
    public $goods_brand_list; //商品品牌

    public function __construct()
    {
        parent::__construct();
        //商品分类
        $this->goods_type_list = model('GoodsType')->tree_to_list(model('GoodsType')->list_to_tree());
        //商品品牌
        $this->goods_brand_list = model('GoodsBrand')->getAll([], ['listorder', 'asc'], 'id,name');
    }

    public function getLogic()
    {
        return new GoodsLogic();
    }

    //列表
    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"]) && $_REQUEST["keyword"] != '') {
                $query->where('title', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            if (isset($_REQUEST["type_id"]) && $_REQUEST["type_id"] > 0) {
                $query->where('type_id', '=', $_REQUEST['type_id']);
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['update_time', 'desc'], GoodsModel::filterTableFields('goods', ['content']));
        $assign_data['list'] = $list;
        //echo '<pre>';print_r($list);exit;
        //分类列表
        $assign_data['goods_type_list'] = $this->goods_type_list;

        return view('admin.goods.index', $assign_data);
    }

    public function add()
    {
        if (Helper::isPostRequest()) {
            $_POST['add_time'] = $_POST['update_time'] = time(); //添加&更新时间
            $_POST['user_id'] = $this->admin_info['id']; // 发布者id

            if (empty($_POST["description"])) {
                if (!empty($_POST["content"])) {
                    $_POST['description'] = cut_str($_POST["content"]);
                }
            } //description
            //关键词
            if (!empty($_POST["keywords"])) {
                $_POST['keywords'] = str_replace("，", ",", $_POST["keywords"]);
            } else {
                if (!empty($_POST["title"])) {
                    $title = $_POST["title"];
                    $title = str_replace("，", "", $title);
                    $title = str_replace(",", "", $title);
                    $_POST['keywords'] = get_participle($title);//标题分词
                }
            }
            if (isset($_POST['keywords']) && !empty($_POST['keywords'])) {
                $_POST['keywords'] = mb_strcut($_POST['keywords'], 0, 60, 'UTF-8');
            }
            //促销时间
            if (isset($_POST['promote_start_date']) && $_POST['promote_start_date'] != '') {
                $_POST['promote_start_date'] = strtotime($_POST['promote_start_date']);
            } else {
                unset($_POST['promote_start_date']);
            }
            if (isset($_POST['promote_end_date']) && $_POST['promote_end_date'] != '') {
                $_POST['promote_end_date'] = strtotime($_POST['promote_end_date']);
            } else {
                unset($_POST['promote_end_date']);
            }
            if (!(isset($_POST['promote_price']) && $_POST['promote_price'] > 0)) {
                unset($_POST['promote_price']);
            }
            //商品图片
            if (!empty($_POST['goods_img'])) {
                $goods_img = $_POST['goods_img'];
                $_POST['goods_img'] = $_POST['goods_img'][0];
            }

            $res = $this->getLogic()->add(array_filter($_POST));
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            //添加商品图片
            if (isset($goods_img)) {
                foreach ($goods_img as $k => $v) {
                    logic('GoodsImg')->add(['url' => $v, 'goods_id' => $res['data'], 'add_time' => $_POST['add_time']]);
                }
            }
            success_jump($res['msg'], route('admin_goods'));
        }

        //商品添加到哪个栏目下
        $assign_data['type_id'] = request('type_id', 0);
        //分类列表
        $assign_data['goods_type_list'] = $this->goods_type_list;
        //品牌列表
        $assign_data['goods_brand_list'] = $this->goods_brand_list;

        return view('admin.goods.add', $assign_data);
    }

    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $_POST['id'];
            unset($_POST['id']);

            $_POST['update_time'] = time();//更新时间
            $_POST['user_id'] = $this->admin_info['id']; // 修改者ID

            if (empty($_POST["description"])) {
                if (!empty($_POST["content"])) {
                    $_POST['description'] = cut_str($_POST["content"]);
                }
            } //description
            //关键词
            if (!empty($_POST["keywords"])) {
                $_POST['keywords'] = str_replace("，", ",", $_POST["keywords"]);
            } else {
                if (!empty($_POST["title"])) {
                    $title = $_POST["title"];
                    $title = str_replace("，", "", $title);
                    $title = str_replace(",", "", $title);
                    $_POST['keywords'] = get_participle($title); //标题分词
                }
            }
            if (isset($_POST['keywords']) && !empty($_POST['keywords'])) {
                $_POST['keywords'] = mb_strcut($_POST['keywords'], 0, 60, 'UTF-8');
            }
            //促销时间
            if (isset($_POST['promote_start_date']) && $_POST['promote_start_date'] != '') {
                $_POST['promote_start_date'] = strtotime($_POST['promote_start_date']);
            } else {
                unset($_POST['promote_start_date']);
            }
            if (isset($_POST['promote_end_date']) && $_POST['promote_end_date'] != '') {
                $_POST['promote_end_date'] = strtotime($_POST['promote_end_date']);
            } else {
                unset($_POST['promote_end_date']);
            }
            if (!(isset($_POST['promote_price']) && $_POST['promote_price'] > 0)) {
                unset($_POST['promote_price']);
            }
            //商品图片
            if (!empty($_POST['goods_img'])) {
                $goods_img = $_POST['goods_img'];
                $_POST['goods_img'] = $_POST['goods_img'][0];
            }

            $res = $this->getLogic()->edit(array_filter($_POST), $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            if (isset($goods_img)) {
                model('GoodsImg')->del(array('goods_id' => $where['id']));
                foreach ($goods_img as $k => $v) {
                    logic('GoodsImg')->add(['url' => $v, 'goods_id' => $where['id'], 'add_time' => $_POST['update_time']]);
                }
            }
            success_jump($res['msg'], route('admin_goods'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);

        //时间戳转日期格式
        if ($post->promote_start_date == 0) {
            $post->promote_start_date = '';
        } else {
            $post->promote_start_date = date('Y-m-d H:i:s', $post->promote_start_date);
        }
        if ($post->promote_end_date == 0) {
            $post->promote_end_date = '';
        } else {
            $post->promote_end_date = date('Y-m-d H:i:s', $post->promote_end_date);
        }

        $assign_data['post'] = $post;
        //分类列表
        $assign_data['goods_type_list'] = $this->goods_type_list;
        //品牌列表
        $assign_data['goods_brand_list'] = $this->goods_brand_list;
        //商品图片列表
        $assign_data['goods_img_list'] = model('GoodsImg')->getAll(array('goods_id' => $where['id']), ['listorder', 'asc']);

        return view('admin.goods.edit', $assign_data);
    }

    //删除
    public function del()
    {
        if (!empty($_GET["id"])) {
            $id = $_GET["id"];
        } else {
            error_jump('参数错误');
        }

        if (!DB::table("Goods")->whereIn("id", explode(',', $id))->update(['delete_time' => time()])) {
            error_jump("$id ,删除失败！请重新提交");
        }
        success_jump("$id ,删除成功");
    }

    //商品推荐
    public function recommendarc()
    {
        if (!empty($_GET["id"])) {
            $id = $_GET["id"];
        } else {
            error_jump('参数错误');
        }

        $data['tuijian'] = 1;
        $res = DB::table("Goods")->whereIn("id", explode(',', $id))->update($data);
        if (!$res) {
            error_jump("$id ,推荐失败！请重新提交");
        }
        success_jump("$id ,推荐成功");
    }

    //商品是否存在
    public function goodsexists()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["title"]) && $_REQUEST["title"] != '') {
                $query->where('title', '=', $_REQUEST['title']);
            }
            if (isset($_REQUEST["id"]) && $_REQUEST["id"] > 0) {
                $query->where('id', '<>', $_GET["id"]);
            }
        };
        echo model('Goods')->getCount($where);
    }
}