<?php

namespace App\Http\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderModel extends BaseModel
{
    //订单

    protected $table = 'order';
    public $timestamps = false;
    protected $hidden = array();
    protected $guarded = array(); //$guarded包含你不想被赋值的字段数组。

    //订单未删除
    const ORDER_UNDELETE = 0;
    //订单状态:0生成订单,1已取消(客户触发),2无效(管理员触发),3完成订单
    const ORDER_STATUS_GENERATE = 0;
    const ORDER_STATUS_CANCEL = 1;
    const ORDER_STATUS_INVALID = 2;
    const ORDER_STATUS_COMPLETE = 3;
    //订单状态描述
    public static $order_status_desc = array(
        self::ORDER_STATUS_GENERATE => '生成订单',
        self::ORDER_STATUS_CANCEL => '已取消',
        self::ORDER_STATUS_INVALID => '无效',
        self::ORDER_STATUS_COMPLETE => '交易成功'
    );

    //订单支付状态:0未付款,1已付款
    const ORDER_PAY_STATUS_UNPAY = 0;
    const ORDER_PAY_STATUS_PAY = 1;

    const ORDER_REFUND_STATUS_NORETURN = 0; //无退货

    //订单配送情况:0未发货,1已发货,2已收货
    const ORDER_SHIPPING_STATUS_NOSHIP = 0;
    const ORDER_SHIPPING_STATUS_SHIP = 1;
    const ORDER_SHIPPING_STATUS_RECEIVE = 2;

    const ORDER_UN_COMMENT = 0;//未评价
    const ORDER_IS_COMMENT = 1;//是否评论，1已评价

    public function getDb()
    {
        return DB::table($this->table);
    }

    /**
     * 列表
     * @param array $where 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @param int $offset 偏移量
     * @param int $limit 取多少条
     * @return array
     */
    public function getList($where = array(), $order = '', $field = '*', $offset = 0, $limit = 15)
    {
        $model = $this->getDb()->where('delete_time', self::ORDER_UNDELETE);
        if ($where) {
            $model = $model->where($where);
        }

        $res['count'] = $model->count();
        $res['list'] = array();

        if ($res['count'] > 0) {
            if ($field) {
                if (is_array($field)) {
                    $model = $model->select($field);
                } else {
                    $model = $model->select(\DB::raw($field));
                }
            }
            if ($order) {
                $model = parent::getOrderByData($model, $order);
            }
            if ($offset) {
            } else {
                $offset = 0;
            }
            if ($limit) {
            } else {
                $limit = 15;
            }

            $res['list'] = $model->skip($offset)->take($limit)->get();
        }

        return $res;
    }

    /**
     * 分页，用于前端html输出
     * @param array $where 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @param int $limit 每页几条
     * @param int $page 当前第几页
     * @return array
     */
    public function getPaginate($where = array(), $order = '', $field = '*', $limit = 15)
    {
        $res = $this->getDb()->where('delete_time', self::ORDER_UNDELETE);

        if ($where) {
            $res = $res->where($where);
        }
        if ($field) {
            if (is_array($field)) {
                $res = $res->select($field);
            } else {
                $res = $res->select(\DB::raw($field));
            }
        }
        if ($order) {
            $res = parent::getOrderByData($res, $order);
        }
        if ($limit) {
        } else {
            $limit = 15;
        }

        return $res->paginate($limit);
    }

    /**
     * 查询全部
     * @param array $where 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @param int $limit 取多少条
     * @return array
     */
    public function getAll($where = array(), $order = '', $field = '*', $limit = '', $offset = '')
    {
        $res = $this->getDb()->where('delete_time', self::ORDER_UNDELETE);

        if ($where) {
            $res = $res->where($where);
        }
        if ($field) {
            if (is_array($field)) {
                $res = $res->select($field);
            } else {
                $res = $res->select(\DB::raw($field));
            }
        }
        if ($order) {
            $res = parent::getOrderByData($res, $order);
        }
        if ($offset) {
            $res = $res->skip($offset);
        }
        if ($limit) {
            $res = $res->take($limit);
        }

        $res = $res->get();

        return $res;
    }

    /**
     * 获取一条
     * @param array $where 条件
     * @param string $field 字段
     * @return array
     */
    public function getOne($where, $field = '*')
    {
        $res = $this->getDb()->where('delete_time', self::ORDER_UNDELETE);

        if ($where) {
            $res = $res->where($where);
        }
        if ($field) {
            if (is_array($field)) {
                $res = $res->select($field);
            } else {
                $res = $res->select(\DB::raw($field));
            }
        }

        $res = $res->first();

        return $res;
    }

    /**
     * 添加
     * @param array $data 数据
     * @return int
     */
    public function add(array $data, $type = 0)
    {
        if ($type == 2) {
            /**
             * 添加单条数据
             * $data = ['foo' => 'bar', 'bar' => 'foo'];
             * 添加多条数据
             * $data = [
             *     ['foo' => 'bar', 'bar' => 'foo'],
             *     ['foo' => 'bar1', 'bar' => 'foo1'],
             *     ['foo' => 'bar2', 'bar' => 'foo2']
             * ];
             */
            return self::insert($data);
        }
        // 新增单条数据并返回主键值
        return self::insertGetId(parent::filterTableColumn($data, $this->table));
    }

    /**
     * 修改
     * @param array $data 数据
     * @param array $where 条件
     * @return int
     */
    public function edit($data, $where = array())
    {
        return self::where($where)->update(parent::filterTableColumn($data, $this->table));
    }

    /**
     * 删除
     * @param array $where 条件
     * @return bool
     */
    public function del($where)
    {
        return self::where($where)->delete();
    }

    /**
     * 统计数量
     * @param array $where 条件
     * @param string $field 字段
     * @return int
     */
    public function getCount($where, $field = '*')
    {
        return self::where($where)->where('delete_time', self::ORDER_UNDELETE)->count($field);
    }

    /**
     * 获取最大值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getMax($where, $field)
    {
        return self::where($where)->where('delete_time', self::ORDER_UNDELETE)->max($field);
    }

    /**
     * 获取最小值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getMin($where, $field)
    {
        return self::where($where)->where('delete_time', self::ORDER_UNDELETE)->min($field);
    }

    /**
     * 获取平均值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getAvg($where, $field)
    {
        return self::where($where)->where('delete_time', self::ORDER_UNDELETE)->avg($field);
    }

    /**
     * 统计总和
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getSum($where, $field)
    {
        return self::where($where)->where('delete_time', self::ORDER_UNDELETE)->sum($field);
    }

    /**
     * 查询某一字段的值
     * @param array $where 条件
     * @param string $field 字段
     * @return null
     */
    public function getValue($where, $field)
    {
        return self::where($where)->where('delete_time', self::ORDER_UNDELETE)->value($field);
    }

    /**
     * 查询某一列的值
     * @param array $where 条件
     * @param string $field 字段
     * @return null
     */
    public function getPluck($where, $field)
    {
        return self::where($where)->where('delete_time', self::ORDER_UNDELETE)->pluck($field);
    }

    /**
     * 某一列的值自增
     * @param array $where 条件
     * @param string $field 字段
     * @param int $step 默认+1
     * @return null
     */
    public function setIncrement($where, $field, $step = 1)
    {
        return self::where($where)->increment($field, $step);
    }

    /**
     * 某一列的值自减
     * @param array $where 条件
     * @param string $field 字段
     * @param int $step 默认-1
     * @return null
     */
    public function setDecrement($where, $field, $step = 1)
    {
        return self::where($where)->decrement($field, $step);
    }

    //获取订单状态文字:1待付款，2待发货,3待收货,4待评价(确认收货，交易成功),5退款/售后,6已取消,7无效
    public function getOrderStatusTextAttr($data)
    {
        $res = '';
        if ($data->order_status == 0 && $data->pay_status == 0) {
            $res = '待付款';
        } elseif ($data->order_status == 0 && $data->shipping_status == 0 && $data->pay_status == 1) {
            $res = '待发货';
        } elseif ($data->order_status == 0 && $data->refund_status == 0 && $data->shipping_status == 1 && $data->pay_status == 1) {
            $res = '待收货';
        } elseif ($data->order_status == 3 && $data->refund_status == 0) {
            $res = '交易成功';
        } elseif ($data->order_status == 3 && $data->refund_status == 1) {
            $res = '售后中';
        } elseif ($data->order_status == 1) {
            $res = '已取消';
        } elseif ($data->order_status == 2) {
            $res = '无效';
        } elseif ($data->order_status == 3 && $data->refund_status == 2) {
            $res = '退款成功';
        }

        return $res;
    }

    //获取订单状态文字:1待付款，2待发货,3待收货,4待评价(确认收货，交易成功),5退款/售后,6已取消,7无效,8退款成功
    public function getOrderStatusNum($data)
    {
        $res = '';
        if ($data->order_status == 0 && $data->pay_status == 0) {
            $res = 1;
        } elseif ($data->order_status == 0 && $data->shipping_status == 0 && $data->pay_status == 1) {
            $res = 2;
        } elseif ($data->order_status == 0 && $data->refund_status == 0 && $data->shipping_status == 1 && $data->pay_status == 1) {
            $res = 3;
        } elseif ($data->order_status == 3 && $data->refund_status == 0) {
            $res = 4;
        } elseif ($data->order_status == 3 && $data->refund_status == 1) {
            $res = 5;
        } elseif ($data->order_status == 1) {
            $res = 6;
        } elseif ($data->order_status == 2) {
            $res = 7;
        } elseif ($data->order_status == 3 && $data->refund_status == 2) {
            $res = 8;
        }

        return $res;
    }

    /**
     * 获取器——国家名称
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getCountryNameAttr($data)
    {
        if (isset($data->country_id) && $data->country_id > 0) {
            return model('Region')->getValue(array('id' => $data->country_id), 'name');
        }

        return '';
    }

    /**
     * 获取器——省份名称
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getProvinceNameAttr($data)
    {
        if (isset($data->province_id) && $data->province_id > 0) {
            return model('Region')->getValue(array('id' => $data->province_id), 'name');
        }

        return '';
    }

    /**
     * 获取器——城市名称
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getCityNameAttr($data)
    {
        if (isset($data->city_id) && $data->city_id > 0) {
            return model('Region')->getValue(array('id' => $data->city_id), 'name');
        }

        return '';
    }

    /**
     * 获取器——县区名称
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getDistrictNameAttr($data)
    {
        if (isset($data->district_id) && $data->district_id > 0) {
            return model('Region')->getValue(array('id' => $data->district_id), 'name');
        }

        return '';
    }

    //获取发票类型文字：0无发票，1个人，2企业
    public function getInvoiceTextAttr($data)
    {
        $arr = array(0 => '无发票', 1 => '个人', 2 => '企业');
        return $arr[$data->invoice];
    }

    //获取订单来源文字：1pc，2weixin，3app，4wap
    public function getPlaceTypeTextAttr($data)
    {
        $arr = array(0 => '未知', 1 => 'pc', 2 => 'weixin', 3 => 'app', 4 => 'wap', 5 => 'miniprogram');
        return $arr[$data->place_type];
    }

    /**
     * 获取器——订单商品列表
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getGoodsListAttr($data)
    {
        //订单商品列表
        $order_goods = model('OrderGoods')->getAll(array('order_id' => $data->id));
        if (!$order_goods) {
            return array();
        }
        foreach ($order_goods as $k => $v) {
            $order_goods[$k]->refund_status_text = model('OrderGoods')->getRefundStatusAttr($v);
        }

        return $order_goods;
    }

    /**
     * 获取器——下单人用户信息
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getUserAttr($data)
    {
        $user = model('User')->getOne(array('id' => $data->user_id), UserModel::USER_COMMON_FIELD);
        return $user;
    }

    /**
     * 根据订单ID返库存
     * @param int $order_id
     * @return bool
     */
    public function returnStock($order_id)
    {
        $order_goods = model('OrderGoods')->getAll(array('order_id' => $order_id));
        if (!$order_goods) {
            return false;
        }
        foreach ($order_goods as $k => $v) {
            //订单商品直接返库存
            model('Goods')->change_goods_stock(array('goods_id' => $v->goods_id, 'goods_number' => $v->goods_number, 'type' => 1));
        }
        return true;
    }

    /**
     * 订单超时，设为无效
     * @param int $order_id
     * @return bool
     */
    public function orderSetInvalid($order_id)
    {
        $order = $this->edit(array('order_status' => 2, 'note' => '订单超时'), array('id' => $order_id, 'order_status' => 0, 'pay_status' => 0));
        if (!$order) {
            return false;
        }

        //返库存
        $this->returnStock($order_id);
        return true;
    }

}