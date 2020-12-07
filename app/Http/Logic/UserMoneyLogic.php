<?php

namespace App\Http\Logic;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Http\Model\UserMoneyModel;
use App\Http\Requests\UserMoneyRequest;
use Validator;

class UserMoneyLogic extends BaseLogic
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModel()
    {
        return new UserMoneyModel();
    }

    public function getValidate($data, $scene_name)
    {
        //数据验证
        $validate = new UserMoneyRequest();
        return Validator::make($data, $validate->getSceneRules($scene_name), $validate->getSceneRulesMessages());
    }

    //列表
    public function getList($where = array(), $order = '', $field = '*', $offset = '', $limit = '')
    {
        $res = $this->getModel()->getList($where, $order, $field, $offset, $limit);

        if ($res['count'] > 0) {
            foreach ($res['list'] as $k => $v) {
                $res['list'][$k] = $this->getDataView($v);
            }
        }

        return $res;
    }

    //分页html
    public function getPaginate($where = array(), $order = '', $field = '*', $limit = '')
    {
        $res = $this->getModel()->getPaginate($where, $order, $field, $limit);

        if ($res->count() > 0) {
            foreach ($res as $k => $v) {
                $res[$k] = $this->getDataView($v);
                $res[$k]->user = model('User')->getOne(array('id' => $v->user_id));
            }
        }

        return $res;
    }

    //全部列表
    public function getAll($where = array(), $order = '', $field = '*', $limit = '')
    {
        $res = $this->getModel()->getAll($where, $order, $field, $limit);

        if ($res) {
            foreach ($res as $k => $v) {
                $res[$k] = $this->getDataView($v);
            }
        }

        return $res;
    }

    //详情
    public function getOne($where = array(), $field = '*')
    {
        $res = $this->getModel()->getOne($where, $field);
        if (!$res) {
            return false;
        }

        $res = $this->getDataView($res);

        return $res;
    }

    /**
     * 添加一条记录，并增加或减少用户余额，会操作用户余额表，谨慎使用
     * @param int $data ['user_id'] 用户id
     * @param int $data ['type'] 0增加,1减少
     * @param float $data ['money'] 金额
     * @param string $data ['des'] 描述
     * @return array
     */
    public function add($data = array(), $type = 0)
    {
        if (empty($data)) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }

        //添加时间
        if (!(isset($data['add_time']) && !empty($data['add_time']))) {
            $data['add_time'] = time();
        }

        $validator = $this->getValidate($data, 'add');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        if ($data['money'] <= 0) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }

        $user = model('User')->getOne(['id' => $data['user_id']]);
        if (!$user) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, '用户不存在');
        }

        DB::beginTransaction(); //启动事务

        if ($data['type'] == UserMoneyModel::USER_MONEY_INCREMENT) {
            //增加用户余额
            model('User')->setIncrement(array('id' => $data['user_id']), 'money', $data['money']);
        } elseif ($data['type'] == UserMoneyModel::USER_MONEY_DECREMENT) {
            //判断用户余额是否足够
            if ($data['money'] > $user->money) {
                return ReturnData::create(ReturnData::FAIL, null, '余额不足');
            }
            //减少用户余额
            model('User')->setDecrement(array('id' => $data['user_id']), 'money', $data['money']);
        } else {
            DB::rollBack(); //事务回滚
            return ReturnData::create(ReturnData::FAIL);
        }

        $user_money = model('User')->getValue(array('id' => $data['user_id']), 'money'); //用户余额
        $data['user_money'] = $user_money;

        $res = $this->getModel()->add($data, $type);
        if (!$res) {
            DB::rollBack(); //事务回滚
            return ReturnData::create(ReturnData::FAIL);
        }

        DB::commit(); //事务提交
        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    //修改
    public function edit($data, $where = array())
    {
        if (empty($data)) {
            return ReturnData::create(ReturnData::SUCCESS);
        }

        $validator = $this->getValidate($data, 'edit');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $res = $this->getModel()->edit($data, $where);
        if (!$res) {
            return ReturnData::create(ReturnData::FAIL);
        }
        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    //删除
    public function del($where)
    {
        if (empty($where)) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }

        $validator = $this->getValidate($where, 'del');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $record = $this->getModel()->getOne($where);
        if (!$record) {
            return ReturnData::create(ReturnData::RECORD_NOT_EXIST);
        }

        $res = $this->getModel()->del($where);
        if (!$res) {
            return ReturnData::create(ReturnData::FAIL);
        }
        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    /**
     * 数据获取器
     * @param array $data 要转化的数据
     * @return array
     */
    private function getDataView($data = array())
    {
        return getDataAttr($this->getModel(), $data);
    }
}