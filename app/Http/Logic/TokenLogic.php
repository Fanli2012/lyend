<?php

namespace App\Http\Logic;

use App\Common\Librarys\ReturnData;
use App\Http\Model\TokenModel;
use App\Http\Requests\TokenRequest;
use Validator;

class TokenLogic extends BaseLogic
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModel()
    {
        return new TokenModel();
    }

    public function getValidate($data, $scene_name)
    {
        //数据验证
        $validate = new TokenRequest();
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

    //添加
    public function add($data = array(), $type = 0)
    {
        if (empty($data)) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }

        $validator = $this->getValidate($data, 'add');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $res = $this->getModel()->add($data, $type);
        if (!$res) {
            return ReturnData::create(ReturnData::FAIL);
        }

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

    /**
     * Token验证
     * @param access_token
     * @return array
     */
    public function checkToken($access_token)
    {
        $token_info = model('Token')->getOne(array('token' => $access_token));
        if (!$token_info) {
            return ReturnData::create(ReturnData::TOKEN_ERROR);
        }
        if ($token_info->expire_time < time()) {
            return ReturnData::create(ReturnData::TOKEN_EXPIRED);
        }
        return ReturnData::create(ReturnData::SUCCESS, $token_info);
    }

    /**
     * 生成Token
     *
     * @param $type
     * @param $user_id
     * @param $data
     *
     * @return string
     */
    public function getToken($uid, $type, $data = array())
    {
        $data = $data ? json_encode($data) : '';
        //支持多账号登录
        if ($token = $this->getModel()->getOne(array('uid' => $uid, 'type' => $type), '*', ['id', 'desc'])) {
            if ($data == $token->data && $token->expire_time > time()) {
                return $token;
            }
        }

        //生成新token
        do {
            $token = md5($type . '-' . $uid . '-' . microtime() . rand(0, 9999));
        } while ($this->getModel()->getOne(array('token' => $token)));

        $expire_time = time() + 3600 * 24 * 30; //Token 30天过期
        $token_data = array(
            'token' => $token,
            'type' => $type,
            'uid' => $uid,
            'data' => $data,
            'expire_time' => $expire_time,
            'add_time' => time()
        );
        $token_id = $this->getModel()->add($token_data);
        if (!$token_id) {
            return false;
        }
        $token_data['id'] = $token_id;

        return $token_data;
    }

}