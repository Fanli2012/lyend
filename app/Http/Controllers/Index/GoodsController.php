<?php

namespace App\Http\Controllers\Index;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\GoodsLogic;
use App\Http\Model\GoodsModel;

class GoodsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new GoodsLogic();
    }

    //列表
    public function index()
    {
        $where = [];
        $title = '商品列表';
        $keywords = '';
        $description = '';

        // page参数不能为1
        if (isset($_GET['page']) && $_GET['page'] == 1) {
            Helper::http404();
        }
        $uri = $_SERVER["REQUEST_URI"]; //获取当前url的参数
        $key = request('key', null);
        if ($key) {
            $arr_key = logic('Article')->getArrByString($key);
            if (!$arr_key) {
                Helper::http404();
            }

            //分类id
            if (isset($arr_key['f']) && $arr_key['f'] > 0) {
                $type_id = $arr_key['f'];
                $where[] = ['type_id', '=', $type_id];

                $post = logic('GoodsType')->getOne(['id' => $arr_key['f']]);
                if (!$post) {
                    Helper::http404();
                }
                $title = $post->name . '-' . sysconfig('CMS_WEBNAME');
                if ($post->seotitle) {
                    $title = $post->seotitle;
                }
                $keywords = $post->keywords;
                $description = $post->description;
                $assign_data['post'] = $post;

                //面包屑导航
                $assign_data['bread'] = $this->get_goods_type_path($type_id);
            }
        }

        $where[] = ['status', '=', 0];
        $posts = cache("index_goods_index_posts_" . md5($uri));
        if (!$posts) {
            $posts = $this->getLogic()->getPaginate($where, ['id', 'desc'], GoodsModel::filterTableFields('goods', ['content']), 18);
            cache(["index_goods_index_posts_" . md5($uri) => $posts], 7200);
        }

        $page = $posts->render();
        $page = preg_replace('/key=[a-z0-9]+&amp;/', '', $page);
        $page = preg_replace('/&amp;key=[a-z0-9]+/', '', $page);
        $page = preg_replace('/\?page=1"/', '"', $page);
        $assign_data['page'] = $page;
        $list = $posts->toArray();
        $assign_data['list'] = $list;
        if (!$list['data']) {
            Helper::http404();
        }

        //推荐商品
        $relate_tuijian_list = cache("index_goods_detail_relate_tuijian_list_$key");
        if (!$relate_tuijian_list) {
            $where_tuijian['status'] = 0;
            $where_tuijian['tuijian'] = 1;
            if (isset($type_id)) {
                $where_tuijian['type_id'] = $type_id;
            }
            $relate_tuijian_list = logic('Goods')->getAll($where_tuijian, ['update_time', 'desc'], GoodsModel::filterTableFields('goods', ['content']), 5);
            cache(["index_goods_detail_relate_tuijian_list_$key" => $relate_tuijian_list], 2592000);
        }
        $assign_data['relate_tuijian_list'] = $relate_tuijian_list;

        //随机商品
        $relate_rand_list = cache("index_goods_detail_relate_rand_list_$key");
        if (!$relate_rand_list) {
            $where_rand['status'] = 0;
            if (isset($type_id)) {
                $where_rand['type_id'] = $type_id;
            }
            $relate_rand_list = logic('Goods')->getAll($where_rand, 'rand()', GoodsModel::filterTableFields('goods', ['content']), 5);
            cache(["index_goods_detail_relate_rand_list_$key" => $relate_rand_list], 2592000);
        }
        $assign_data['relate_rand_list'] = $relate_rand_list;

        //seo设置
        $assign_data['title'] = $title;
        $assign_data['keywords'] = $keywords;
        $assign_data['description'] = $description;

        return view('index.goods.index', $assign_data);
    }

    //详情
    public function detail()
    {
        if (!check_is_number(request('id', null))) {
            Helper::http404();
        }
        $id = request('id');

        $post = cache("index_goods_detail_$id");
        if (!$post) {
            $where['id'] = $id;
            $post = $this->getLogic()->getOne($where);
            if (!$post) {
                Helper::http404();
            }
            cache(["index_goods_detail_$id" => $post], 2592000);

        }
        $assign_data['post'] = $post;

        //最新文章
        $relate_zuixin_list = cache("index_goods_detail_relate_zuixin_list_$id");
        if (!$relate_zuixin_list) {
            $where_zuixin[0] = ['status', '=', 0];
            $where_zuixin[1] = ['type_id', '=', $post->type_id];
            $where_zuixin[2] = ['id', '<', $id];
            $relate_zuixin_list = logic('Goods')->getAll($where_zuixin, ['update_time', 'desc'], GoodsModel::filterTableFields('goods', ['content']), 5);
            if (!$relate_zuixin_list) {
                unset($where_zuixin[2]);
                $relate_zuixin_list = logic('Goods')->getAll($where_zuixin, ['update_time', 'desc'], GoodsModel::filterTableFields('goods', ['content']), 5);
            }
            cache("index_goods_detail_relate_zuixin_list_$id", $relate_zuixin_list, 2592000);
        }
        $assign_data['relate_zuixin_list'] = $relate_zuixin_list;

        //随机文章
        $relate_rand_list = cache("index_goods_detail_relate_rand_list_$id");
        if (!$relate_rand_list) {
            $where_rand['status'] = 0;
            $where_rand['type_id'] = $post->type_id;
            $relate_rand_list = logic('Goods')->getAll($where_rand, 'rand()', GoodsModel::filterTableFields('goods', ['content']), 5);
            cache("index_goods_detail_relate_rand_list_$id", $relate_rand_list, 2592000);
        }
        $assign_data['relate_rand_list'] = $relate_rand_list;

        //面包屑导航
        $assign_data['bread'] = $this->get_goods_type_path($post->type_id);

        return view('index.goods.detail', $assign_data);
    }

    /**
     * 递归获取面包屑导航
     * @param  [int] $type_id
     * @return [string]
     */
    public function get_goods_type_path($type_id)
    {
        global $temp;

        $row = model('GoodsType')->getOne(['id' => $type_id], 'name,parent_id,id');

        $temp = '<a href="' . route('index_goods_index_key', array('key' => 'f' . $row->id)) . '">' . $row->name . "</a> > " . $temp;

        if ($row->parent_id > 0) {
            $this->get_goods_type_path($row->parent_id);
        }

        return $temp;
    }

}