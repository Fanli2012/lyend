<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\ArticleTypeModel;
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

    public function index(Request $request)
    {
        $where = array();
        //参数
        $limit = request('limit', 10);
        $offset = request('offset', 0);
        if (request('parent_id', null) != null) {
            $where[] = ['parent_id', '=', request('parent_id')];
        }
        if (request('is_part', null) != null) {
            $where[] = ['is_part', '=', request('is_part')];
        }
        $res = $this->getLogic()->getList($where, array('listorder', 'asc'), '*', $offset, $limit);

        if ($res['count'] > 0) {
            foreach ($res['list'] as $k => $v) {

            }
        }

        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    public function detail()
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
    public function add()
    {
        if (Helper::isPostRequest()) {
            return $this->getLogic()->add($_POST);
        }
    }

    //修改
    public function edit()
    {
        if (!check_is_number(request('id', null))) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        $id = request('id');

        if (Helper::isPostRequest()) {
            unset($_POST['id']);
            $where['id'] = $id;
            return $this->getLogic()->edit($_POST, $where);
        }
    }

    //删除
    public function del()
    {
        if (!check_is_number(request('id', null))) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        $id = request('id');

        if (Helper::isPostRequest()) {
            $where['id'] = $id;
            return $this->getLogic()->del($where);
        }
    }
}