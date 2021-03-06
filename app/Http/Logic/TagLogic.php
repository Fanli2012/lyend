<?php

namespace App\Http\Logic;

use App\Common\Librarys\ReturnData;
use App\Http\Model\TagModel;
use App\Http\Requests\TagRequest;
use Validator;

class TagLogic extends BaseLogic
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModel()
    {
        return new TagModel();
    }

    public function getValidate($data, $scene_name)
    {
        //数据验证
        $validate = new TagRequest();
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

        //关键词
        if (isset($data["keywords"])) {
            $data['keywords'] = str_replace("，", ",", $data["keywords"]);
        } else {
            unset($data['keywords']);
        }

        //添加时间、更新时间
        $time = time();
        if (!(isset($data['add_time']) && !empty($data['add_time']))) {
            $data['add_time'] = $time;
        }
        if (!(isset($data['update_time']) && !empty($data['update_time']))) {
            $data['update_time'] = $time;
        }

        $validator = $this->getValidate($data, 'add');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        //判断名称
        if (isset($data['name']) && !empty($data['name'])) {
            if ($this->getModel()->getOne(['name' => $data['name']])) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '名称已经存在');
            }
        }

        //判断别名
        if (isset($data['filename']) && !empty($data['filename'])) {
            if ($this->getModel()->getOne(['filename' => $data['filename']])) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '别名已经存在');
            }
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

        //关键词
        if (isset($data["keywords"])) {
            $data['keywords'] = str_replace("，", ",", $data["keywords"]);
        } else {
            unset($data['keywords']);
        }

        //更新时间
        if (!(isset($data['update_time']) && !empty($data['update_time']))) {
            $data['update_time'] = time();
        }

        $validator = $this->getValidate($data, 'edit');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $record = $this->getModel()->getOne($where);
        if (!$record) {
            return ReturnData::create(ReturnData::RECORD_NOT_EXIST);
        }

        //判断名称
        if (isset($data['name']) && !empty($data['name'])) {
            $where2 = function ($query) use ($data, $record) {
                $query->where('name', '=', $data['name']);
                $query->where('id', '<>', $record->id); //排除自身
            };
            if ($this->getModel()->getOne($where2)) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '名称已经存在');
            }
        }

        //判断别名
        if (isset($data['filename']) && !empty($data['filename'])) {
            $where2 = function ($query) use ($data, $record) {
                $query->where('filename', '=', $data['filename']);
                $query->where('id', '<>', $record->id); //排除自身
            };
            if ($this->getModel()->getOne($where2)) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '别名已经存在');
            }
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