<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\GoodsTypeModel;
use App\Http\Logic\GoodsTypeLogic;

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

    public function index(Request $request)
    {
        //参数
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        $data['parent_id'] = $request->input('parent_id', 0);
        $where = array();

        $res = $this->getLogic()->getList($where, array('listorder', 'asc'), '*', $offset, $limit);

        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    public function detail(Request $request)
    {
        //参数
        if (!check_is_number($request->input('id', null))) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        $id = $request->input('id');

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
            return $this->getLogic()->add($_POST);
        }
    }

    //修改
    public function edit(Request $request)
    {
        if (!check_is_number($request->input('id', null))) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        $id = $request->input('id');

        if (Helper::isPostRequest()) {
            unset($_POST['id']);
            $where['id'] = $id;

            return $this->getLogic()->edit($_POST, $where);
        }
    }

    //删除
    public function del(Request $request)
    {
        if (!check_is_number($request->input('id', null))) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        $id = $request->input('id');

        if (Helper::isPostRequest()) {
            $where['id'] = $id;

            return $this->getLogic()->del($where);
        }
    }
}