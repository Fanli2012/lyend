<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\ArticleModel;
use App\Http\Logic\ArticleLogic;

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

    public function index()
    {
		$where = array();
        //参数
        $limit = request('limit', 10);
        $offset = request('offset', 0);
        if (request('type_id', null) != null) {
            $where[] = ['type_id', '=', request('type_id')];
        }
        $where[] = ['status', '=', 0];

        $res = $this->getLogic()->getList($where, array('id', 'desc'), ArticleModel::$common_field, $offset, $limit);
        if ($res['count'] > 0) {
            foreach ($res['list'] as $k => $v) {

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
        $where['status'] = 0;

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
            $_POST['user_id'] = $this->login_info->id;

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