<?php

namespace App\Http\Controllers\Index;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\ArticleLogic;
use App\Http\Model\ArticleModel;

class ArticleController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new ArticleLogic();
    }

    //列表
    public function index()
    {
        $where = [];
        $title = '文章列表';
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

                $post = logic('ArticleType')->getOne(['id' => $arr_key['f']]);
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
                $assign_data['bread'] = $this->get_article_type_path($type_id);
            }
        }

        $where[] = ['status', '=', 0];
        $where[] = ['add_time', '<', time()];
        $posts = cache("index_article_index_posts_" . md5($uri));
        if (!$posts) {
            $posts = $this->getLogic()->getPaginate($where, ['id', 'desc'], ArticleModel::filterTableFields('article', ['content']), 15);
            cache("index_article_index_posts_" . md5($uri), $posts, 7200);
        }

        $page = $posts->links();
        $page = preg_replace('/key=[a-z0-9]+&amp;/', '', $page);
        $page = preg_replace('/&amp;key=[a-z0-9]+/', '', $page);
        $page = preg_replace('/\?page=1"/', '"', $page);
        $assign_data['page'] = $page;
        $list = $posts->toArray();
        $assign_data['list'] = $list;
        if (!$list['data']) {
            Helper::http404();
        }

        //推荐文章
        $relate_tuijian_list = cache("index_article_detail_relate_tuijian_list_$key");
        if (!$relate_tuijian_list) {
            $where_tuijian[] = ['status', '=', 0];
            $where_tuijian[] = ['tuijian', '=', 1];
            $where_tuijian[] = ['litpic', '<>', ''];
            $where_tuijian[] = ['add_time', '<', time()];
            if (isset($type_id)) {
                $where_tuijian[] = ['type_id', '=', $type_id];
            }
            $relate_tuijian_list = logic('Article')->getAll($where_tuijian, ['update_time', 'desc'], ArticleModel::filterTableFields('article', ['content']), 5);
            cache("index_article_detail_relate_tuijian_list_$key", $relate_tuijian_list, 2592000);
        }
        $assign_data['relate_tuijian_list'] = $relate_tuijian_list;

        //随机文章
        $relate_rand_list = cache("index_article_detail_relate_rand_list_$key");
        if (!$relate_rand_list) {
            $where_rand[] = ['status', '=', 0];
            $where_rand[] = ['add_time', '<', time()];
            if (isset($type_id)) {
                $where_rand[] = ['type_id', '=', $type_id];
            }
            $relate_rand_list = logic('Article')->getAll($where_rand, 'rand()', ArticleModel::filterTableFields('article', ['content']), 5);
            cache("index_article_detail_relate_rand_list_$key", $relate_rand_list, 2592000);
        }
        $assign_data['relate_rand_list'] = $relate_rand_list;

        //seo设置
        $assign_data['title'] = $title;
        $assign_data['keywords'] = $keywords;
        $assign_data['description'] = $description;

        return view('index.article.index', $assign_data);
    }

    //详情
    public function detail()
    {
        if (!check_is_number(request('id', null))) {
            Helper::http404();
        }
        $id = request('id');

        $post = cache("index_article_detail_$id");
        if (!$post) {
            $where['id'] = $id;
            $post = $this->getLogic()->getOne($where);
            if (!$post) {
                Helper::http404();
            }
            $post->content = $this->getLogic()->replaceKeyword($post->content);
            cache(["index_article_detail_$id" => $post], 2592000);

        }
        $assign_data['post'] = $post;
        //var_dump($post);exit;
        //最新文章
        $relate_zuixin_list = cache("index_article_detail_relate_zuixin_list_$id");
        if (!$relate_zuixin_list) {
            $where_zuixin[1] = ['status', '=', 0];
            $where_zuixin[2] = ['type_id', '=', $post->type_id];
            $where_zuixin[3] = ['id', '<', ($id - 1)];
            $relate_zuixin_list = logic('Article')->getAll($where_zuixin, ['update_time', 'desc'], ArticleModel::filterTableFields('article', ['content']), 5);
            if (!$relate_zuixin_list) {
                unset($where_zuixin[3]);
                $relate_zuixin_list = logic('Article')->getAll($where_zuixin, ['update_time', 'desc'], ArticleModel::filterTableFields('article', ['content']), 5);
            }
            cache(["index_article_detail_relate_zuixin_list_$id" => $relate_zuixin_list], 2592000);
        }
        $assign_data['relate_zuixin_list'] = $relate_zuixin_list;

        //随机文章
        $relate_rand_list = cache("index_article_detail_relate_rand_list_$id");
        if (!$relate_rand_list) {
            $where_rand[] = ['status', '=', 0];
            $where_rand[] = ['type_id', '=', $post->type_id];
            $where_rand[] = ['add_time', '<', time()];
            $relate_rand_list = logic('Article')->getAll($where_rand, 'rand()', ArticleModel::filterTableFields('article', ['content']), 5);
            cache(["index_article_detail_relate_rand_list_$id" => $relate_rand_list], 2592000);
        }
        $assign_data['relate_rand_list'] = $relate_rand_list;

        //面包屑导航
        $assign_data['bread'] = $this->get_article_type_path($post->type_id);

        //上一篇、下一篇
        $assign_data = array_merge($assign_data, $this->getPreviousNextArticle(['article_id' => $id]));

        return view('index.article.detail', $assign_data);
    }

    /**
     * 获取文章上一篇，下一篇
     * @param int $param ['article_id'] 当前文章id
     * @return array
     */
    public function getPreviousNextArticle(array $param)
    {
        $res['previous_article'] = [];
        $res['next_article'] = [];

        $where['id'] = $param['article_id'];
        $post = model('Article')->getOne($where);
        if (!$post) {
            return $res;
        }
        $res['previous_article'] = model('Article')->getOne([['id', '<', $param['article_id']], ['type_id', '=', $post->type_id]], 'id,title,update_time', ['id', 'desc']);
        $res['next_article'] = model('Article')->getOne([['id', '>', $param['article_id']], ['type_id', '=', $post->type_id]], 'id,title,update_time', ['id', 'asc']);
        return $res;
    }

    //详情
    public function imglist()
    {
        return view('index.article.imglist');
    }

    /**
     * 递归获取面包屑导航
     * @param  [int] $type_id
     * @return [string]
     */
    public function get_article_type_path($type_id)
    {
        global $temp;

        $row = model('ArticleType')->getOne(['id' => $type_id], 'name,parent_id,id');

        $temp = '<a href="' . route('index_article_index_key', array('key' => 'f' . $row->id)) . '">' . $row->name . "</a> > " . $temp;

        if ($row->parent_id > 0) {
            $this->get_article_type_path($row->parent_id);
        }

        return $temp;
    }

}