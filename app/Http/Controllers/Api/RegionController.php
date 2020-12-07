<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\RegionModel;
use App\Http\Logic\RegionLogic;

class RegionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new RegionLogic();
    }

    public function index(Request $request)
    {
        //参数
        $where['parent_id'] = $request->input('id', 86);

        $res = $this->getLogic()->getAll($where);

        /* if ($res['count'] > 0) {
            foreach ($res['list'] as $k=>$v) {

            }
        } */

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

}