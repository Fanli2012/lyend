<?php

namespace App\Http\Controllers\Index;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\ArticleModel;

class SearchController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    //详情
    public function detail()
    {
        $keyword = request('keyword', null);
        if (!$keyword) {
            Helper::http404();
        }

        $where[] = array('title', 'like', '%' . $keyword . '%');
        $where[] = array('status', '=', 0);
        $where[] = array('add_time', '<', time());
        $posts = logic('Article')->getPaginate($where, ['update_time', 'desc'], ArticleModel::filterTableFields('article', ['content']));

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

        //最新
        $where2[] = array('status', '=', 0);
        $where2[] = array('add_time', '<', time());
        $relate_zuixin_list = logic('Article')->getAll($where2, ['id', 'desc'], ArticleModel::filterTableFields('article', ['content']), 5);
        $assign_data['relate_zuixin_list'] = $relate_zuixin_list;

        //推荐
        $where3[] = array('status', '=', 0);
        $where3[] = array('tuijian', '=', 1);
        $where3[] = array('litpic', '<>', '');
        $where3[] = array('add_time', '<', time());
        $relate_tuijian_list = logic('Article')->getAll($where3, ['id', 'desc'], ArticleModel::filterTableFields('article', ['content']), 5);
        $assign_data['relate_tuijian_list'] = $relate_tuijian_list;
        //搜索词
        $assign_data['keyword'] = $keyword;
        return view('index.search.detail', $assign_data);
    }

}