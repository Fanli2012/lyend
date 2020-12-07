<?php

namespace App\Http\Logic;

use App\Common\Librarys\ReturnData;
use App\Http\Model\GoodsModel;
use App\Http\Requests\GoodsRequest;
use Validator;
use App\Http\Model\CommentModel;

class GoodsLogic extends BaseLogic
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModel()
    {
        return new GoodsModel();
    }

    public function getValidate($data, $scene_name)
    {
        //数据验证
        $validate = new GoodsRequest();
        return Validator::make($data, $validate->getSceneRules($scene_name), $validate->getSceneRulesMessages());
    }

    //列表
    public function getList($where = array(), $order = '', $field = '*', $offset = '', $limit = '')
    {
        $res = $this->getModel()->getList($where, $order, $field, $offset, $limit);

        if ($res['count'] > 0) {
            foreach ($res['list'] as $k => $v) {
                $res['list'][$k] = $this->getDataView($v);

                $res['list'][$k]->price = $this->getModel()->get_goods_final_price($v);
                $res['list'][$k]->is_promote = $this->getModel()->bargain_price($v->promote_price, $v->promote_start_date, $v->promote_end_date); //is_promote等于0，说明不是促销商品
                $res['list'][$k]->goods_img_list = $this->getModel()->getGoodsImgListAttr($v);
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

                $res[$k]->price = $this->getModel()->get_goods_final_price($v);
                $res[$k]->is_promote = $this->getModel()->bargain_price($v->promote_price, $v->promote_start_date, $v->promote_end_date); //is_promote等于0，说明不是促销商品
                $res[$k]->goods_img_list = $this->getModel()->getGoodsImgListAttr($v);
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
        $res->price = $this->getModel()->get_goods_final_price($res); //商品最终价格
        $res->is_promote = $this->getModel()->bargain_price($res->promote_price, $res->promote_start_date, $res->promote_end_date); //is_promote等于0，说明不是促销商品
        $res->goods_img_list = $this->getModel()->getGoodsImgListAttr($res);

        //商品评论数
        $where2['comment_type'] = CommentModel::GOODS_COMMENT_TYPE;
        $where2['status'] = CommentModel::SHOW_COMMENT;
        $where2['id_value'] = $res->id;
        $res->goods_comments_num = model('Comment')->getCount($where2);

        $this->getModel()->setIncrement($where, 'click', 1); //点击量+1

        return $res;
    }

    //添加
    public function add($data = array(), $type = 0)
    {
        if (empty($data)) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }

        //标题最多150个字符
        if (isset($data['title']) && !empty($data['title'])) {
            $data['title'] = mb_strcut($data['title'], 0, 150, 'UTF-8');
            $data['title'] = trim($data['title']);
        }
        //SEO标题最多150个字符
        if (isset($data['seotitle']) && !empty($data['seotitle'])) {
            $data['seotitle'] = mb_strcut($data['seotitle'], 0, 150, 'UTF-8');
            $data['seotitle'] = trim($data['seotitle']);
        }
        //关键词最多60个字符
        if (isset($data['keywords']) && !empty($data['keywords'])) {
            $data['keywords'] = mb_strcut($data['keywords'], 0, 60, 'UTF-8');
            $data['keywords'] = trim($data['keywords']);
        }
        //描述最多240个字符
        if (isset($data['description']) && !empty($data['description'])) {
            $data['description'] = mb_strcut($data['description'], 0, 240, 'UTF-8');
            $data['description'] = trim($data['description']);
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

        //判断货号
        if (isset($data['sn']) && !empty($data['sn'])) {
            $where_sn['sn'] = $data['sn'];
            if ($this->getModel()->getOne($where_sn)) {
                return ReturnData::create(ReturnData::FAIL, null, '该货号已存在');
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

        //标题最多150个字符
        if (isset($data['title']) && !empty($data['title'])) {
            $data['title'] = mb_strcut($data['title'], 0, 150, 'UTF-8');
            $data['title'] = trim($data['title']);
        }
        //SEO标题最多150个字符
        if (isset($data['seotitle']) && !empty($data['seotitle'])) {
            $data['seotitle'] = mb_strcut($data['seotitle'], 0, 150, 'UTF-8');
            $data['seotitle'] = trim($data['seotitle']);
        }
        //关键词最多60个字符
        if (isset($data['keywords']) && !empty($data['keywords'])) {
            $data['keywords'] = mb_strcut($data['keywords'], 0, 60, 'UTF-8');
            $data['keywords'] = trim($data['keywords']);
        }
        //描述最多240个字符
        if (isset($data['description']) && !empty($data['description'])) {
            $data['description'] = mb_strcut($data['description'], 0, 240, 'UTF-8');
            $data['description'] = trim($data['description']);
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

        //判断货号
        if (isset($data['sn']) && !empty($data['sn'])) {
            $where2 = function ($query) use ($data, $record) {
                $query->where('sn', '=', $data['sn']);
                $query->where('id', '<>', $record->id); //排除自身
            };
            if ($this->getModel()->getOne($where2)) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, '该货号已存在');
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

        $res = $this->getModel()->edit(array('delete_time' => time()), $where);
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
     * 递归获取面包屑导航
     * @param  [int] $type_id
     * @return [string]
     */
    public function get_goods_type_path($type_id)
    {
        global $temp;

        $row = model('GoodsType')->getOne(['id' => $type_id], 'id,name,parent_id');
        $temp = '<a href="/goodslist/f' . $row->id . '">' . $row->name . "</a> > " . $temp;

        if ($row->parent_id > 0) {
            $this->get_goods_type_path($row->parent_id);
        }

        return $temp;
    }

}