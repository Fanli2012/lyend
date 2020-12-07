<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\PageModel;
use App\Http\Logic\PageLogic;

class PageController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new PageLogic();
    }

    public function index(Request $request)
    {
        //参数
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);

        $res = $this->getLogic()->getList('', array('listorder', 'asc'), '*', $offset, $limit);

        if ($res['count'] > 0) {
            foreach ($res['list'] as $k => $v) {

            }
        }

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
            //$where['user_id'] = Token::$uid;

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
            //$where['user_id'] = Token::$uid;

            return $this->getLogic()->del($where);
        }
    }
}