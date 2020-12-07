<?php

namespace App\Http\Logic;

use Validator;
use App\Common\Librarys\ReturnData;
use App\Http\Model\AdminModel;
use App\Http\Requests\AdminRequest;

class AdminLogic extends BaseLogic
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModel()
    {
        return new AdminModel();
    }

    public function getValidate($data, $scene_name)
    {
        //数据验证
        $validate = new AdminRequest();
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

        if (!isset($data['add_time'])) {
            $data['add_time'] = $data['update_time'] = time();
        }

        $validator = $this->getValidate($data, 'add');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        //判断用户名
        if (isset($data['name']) && !empty($data['name'])) {
            if ($this->getModel()->getOne(['name' => $data['name'], 'delete_time' => 0])) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '用户名已经存在');
            }
        }

        //判断手机号码
        if (isset($data['mobile']) && !empty($data['mobile'])) {
            if ($this->getModel()->getOne(['mobile' => $data['mobile'], 'delete_time' => 0])) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '手机号码已经存在');
            }
        }

        //判断邮箱
        if (isset($data['email']) && !empty($data['email'])) {
            if ($this->getModel()->getOne(['email' => $data['email'], 'delete_time' => 0])) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '邮箱已经存在');
            }
        }

        $data['pwd'] = md5($data['pwd']);

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

        if (!isset($data['update_time'])) {
            $data['update_time'] = time();
        }

        //数据验证
        $admin_request = new AdminRequest();
        $validator = Validator::make($data, $admin_request->edit_rules, $admin_request->getSceneRulesMessages());
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $record = $this->getModel()->getOne($where);
        if (!$record) {
            return ReturnData::create(ReturnData::RECORD_NOT_EXIST);
        }

        //判断用户名
        if (isset($data['name']) && !empty($data['name'])) {
            $where2 = function ($query) use ($data, $record) {
                $query->where('delete_time', '=', 0);
                $query->where('name', '=', $data['name']);
                $query->where('id', '<>', $record->id); //排除自身
            };
            if ($this->getModel()->getOne($where2)) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '用户名已经存在');
            }
        }

        //判断手机号码
        if (isset($data['mobile']) && !empty($data['mobile'])) {
            $where2 = function ($query) use ($data, $record) {
                $query->where('delete_time', '=', 0);
                $query->where('mobile', '=', $data['mobile']);
                $query->where('id', '<>', $record->id); //排除自身
            };
            if ($this->getModel()->getOne($where2)) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '手机号码已经存在');
            }
        }

        //判断邮箱
        if (isset($data['email']) && !empty($data['email'])) {
            $where2 = function ($query) use ($data, $record) {
                $query->where('delete_time', '=', 0);
                $query->where('email', '=', $data['email']);
                $query->where('id', '<>', $record->id); //排除自身
            };
            if ($this->getModel()->getOne($where2)) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '邮箱已经存在');
            }
        }

        if (isset($data['pwd']) && !empty($data['pwd'])) {
            $data['pwd'] = md5($data['pwd']);
        } else {
            unset($data['pwd']);
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
     * 登录
     * @param string $data ['name'] 用户名
     * @param string $data ['pwd'] 密码
     * @return array
     */
    public function login($data)
    {
        // 验证数据
        $rules = [
            'name' => 'required|max:30',
            'pwd' => 'required|min:6|max:18',
        ];
        $messages = [
            'name.required' => '用户名不能为空',
            'name.max' => '用户名不能超过30个字符',
            'pwd.required' => '密码不能为空',
            'pwd.min' => '密码格式不正确',
            'pwd.max' => '密码格式不正确',
        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $name = $data['name'];
        $pwd = md5($data['pwd']);

        //用户名/邮箱/手机登录
        $map1 = [
            ['name', '=', $name],
            ['pwd', '=', $pwd],
            ['delete_time', '=', 0],
        ];
        $map2 = [
            ['email', '=', $name],
            ['pwd', '=', $pwd],
            ['delete_time', '=', 0],
        ];
        $map3 = [
            ['mobile', '=', $name],
            ['pwd', '=', $pwd],
            ['delete_time', '=', 0],
        ];

        $admin = $this->getModel()->where($map1)->orWhere($map2)->orWhere($map3)->first();
        if (!$admin) {
            return ReturnData::create(ReturnData::FAIL, null, '账号或密码错误');
        }

        //更新登录时间
        $this->getModel()->edit(['login_time' => time()], ['id' => $admin->id]);
        $admin->status_text = $this->getModel()->getStatusTextAttr($admin);
        $admin->role_id_text = $this->getModel()->getRoleIdTextAttr($admin);
        return ReturnData::create(ReturnData::SUCCESS, $admin->toArray());
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