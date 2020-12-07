<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\GoodsModel;
use App\Http\Logic\GoodsLogic;
use App\Http\Logic\GoodsSearchwordLogic;

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

    public function index(Request $request)
    {
        //参数
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        $where = function ($query) use ($request) {
            $query->where('status', GoodsModel::GOODS_STATUS_NORMAL);

            if ($request->input('type_id', null) != null && $request->input('type_id', '') != 0) {
                $query->where('type_id', $request->input('type_id'));
            }

            if ($request->input('tuijian', null) != null) {
                $query->where('tuijian', $request->input('tuijian'));
            }

            if ($request->input('keyword', null) != null) {
                $query->where(function ($query2) use ($request) {
                    $query2->where('title', 'like', '%' . $request->input('keyword') . '%')->orWhere('sn', 'like', '%' . $request->input('keyword') . '%');
                });
            }

            //价格区间搜索
            if ($request->input('min_price', null) != null && $request->input('max_price', null) != null) {
                $query->where('price', '>=', $request->input('min_price'))->where("price", "<=", $request->input('max_price'));
            }

            if ($request->input('brand_id', null) != null) {
                $query->where('brand_id', $request->input('brand_id'));
            }

            //促销商品
            if ($request->input('is_promote', 0) == 1) {
                $timestamp = time();
                $query->where("promote_start_date", "<=", $timestamp)->where('promote_end_date', '>=', $timestamp);
            }
        };

        //var_dump(model('Goods')->where($where)->toSql());exit;

        //关键词搜索
        if ($request->input('keyword', null) != null) {
            //添加搜索关键词
            $goodssearchword = new GoodsSearchwordLogic();
            $goodssearchword->add(array('name' => $request->input('keyword')));
        }

        //排序
        $orderby = ['id', 'desc'];
        if ($request->input('orderby', null) != null) {
            switch ($request->input('orderby')) {
                case 1:
                    $orderby = ['sale', 'desc']; //销量从高到低
                    break;
                case 2:
                    $orderby = ['comments', 'desc']; //评论从高到低
                    break;
                case 3:
                    $orderby = ['price', 'desc']; //价格从高到低
                    break;
                case 4:
                    $orderby = ['price', 'asc']; //价格从低到高
                    break;
                default:
                    $orderby = ['pubdate', 'desc']; //最新
            }
        }

        $res = $this->getLogic()->getList($where, $orderby, model('Goods')->common_field, $offset, $limit);

        if ($res['count'] > 0) {
            foreach ($res['list'] as $k => $v) {
                if (!empty($res['list'][$k]->litpic)) {
                    $res['list'][$k]->litpic = http_host() . $res['list'][$k]->litpic;
                }
            }
        }

        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    public function detail(Request $request)
    {
        //参数
        if (!check_is_number(request('id', null))) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        $id = request('id');

        $where['id'] = $id;

        $res = $this->getLogic()->getOne($where);
        if (!$res) {
            return ReturnData::create(ReturnData::RECORD_NOT_EXIST);
        }

        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    //添加
    public function add(Request $request)
    {
        if (Helper::isPostRequest()) {
            $_POST['user_id'] = Token::$uid;

            return $this->getLogic()->add($_POST);
        }
    }

    //修改
    public function edit(Request $request)
    {
        if (!check_is_number(request('id', null))) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        $id = request('id');

        if (Helper::isPostRequest()) {
            unset($_POST['id']);
            $where['id'] = $id;
            //$where['user_id'] = Token::$uid;

            return $this->getLogic()->edit($_POST, $where);
        }
    }

    //删除
    public function del(Request $request)
    {
        if (!check_is_number(request('id', null))) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        $id = request('id');

        if (Helper::isPostRequest()) {
            $where['id'] = $id;
            //$where['user_id'] = Token::$uid;

            return $this->getLogic()->del($where);
        }
    }
}