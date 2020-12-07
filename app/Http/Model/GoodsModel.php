<?php

namespace App\Http\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodsModel extends BaseModel
{
    //产品模型

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods';

    /**
     * 表明模型是否应该被打上时间戳
     * 默认情况下，Eloquent 期望 created_at 和updated_at 已经存在于数据表中，如果你不想要这些 Laravel 自动管理的数据列，在模型类中设置 $timestamps 属性为 false
     *
     * @var bool
     */
    public $timestamps = false;
    protected $hidden = array();

    //protected $guarded = []; //$guarded包含你不想被赋值的字段数组。
    //protected $fillable = ['name']; //定义哪些字段是可以进行赋值的,与$guarded相反

    /**
     * The connection name for the model.
     * 默认情况下，所有的 Eloquent 模型使用应用配置中的默认数据库连接，如果你想要为模型指定不同的连接，可以通过 $connection 属性来设置
     * @var string
     */
    //protected $connection = 'connection-name';

    //常用字段
    public static $common_field = array(
        'id', 'type_id', 'tuijian', 'click', 'title', 'seotitle', 'description', 'litpic', 'goods_img', 'sn', 'price', 'market_price', 'shipping_fee', 'goods_number', 'sale', 'warn_number', 'spec', 'point', 'comment_number', 'promote_price', 'promote_start_date', 'promote_end_date', 'brand_id', 'listorder', 'add_time', 'update_time'
    );

    //商品未删除
    const GOODS_UNDELETE = 0;
    //商品状态：0正常，1下架，2申请上架
    const GOODS_STATUS_NORMAL = 0;
    const GOODS_STATUS_UNDERCARRIAGE = 1;
    const GOODS_STATUS_APPLY_SHELVES = 2;
    //状态描述
    public static $goods_status_desc = array(
        self::GOODS_STATUS_NORMAL => '正常',
        self::GOODS_STATUS_UNDERCARRIAGE => '下架',
        self::GOODS_STATUS_APPLY_SHELVES => '申请上架'
    );

    /**
     * 获取关联到产品的分类
     */
    public function goods_type()
    {
        return $this->belongsTo(GoodsType::class, 'type_id', 'id');
    }

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
        $model = $this->getDb();
        if ($where) {
            $model = $model->where($where);
        }
        $model = $model->where('delete_time', self::GOODS_UNDELETE);

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
        //return $model->toSql();//打印sql语句
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
        $res = $this->getDb();
        $res = $res->where('delete_time', self::GOODS_UNDELETE);

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
        $res = $this->getDb();
        $res = $res->where('delete_time', self::GOODS_UNDELETE);

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
        $res = $this->getDb();
        $res = $res->where('delete_time', self::GOODS_UNDELETE);

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
        return self::where($where)->where('delete_time', self::GOODS_UNDELETE)->count($field);
    }

    /**
     * 获取最大值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getMax($where, $field)
    {
        return self::where($where)->where('delete_time', self::GOODS_UNDELETE)->max($field);
    }

    /**
     * 获取最小值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getMin($where, $field)
    {
        return self::where($where)->where('delete_time', self::GOODS_UNDELETE)->min($field);
    }

    /**
     * 获取平均值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getAvg($where, $field)
    {
        return self::where($where)->where('delete_time', self::GOODS_UNDELETE)->avg($field);
    }

    /**
     * 统计总和
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getSum($where, $field)
    {
        return self::where($where)->where('delete_time', self::GOODS_UNDELETE)->sum($field);
    }

    /**
     * 查询某一字段的值
     * @param array $where 条件
     * @param string $field 字段
     * @return null
     */
    public function getValue($where, $field)
    {
        return self::where($where)->where('delete_time', self::GOODS_UNDELETE)->value($field);
    }

    /**
     * 查询某一列的值
     * @param array $where 条件
     * @param string $field 字段
     * @return null
     */
    public function getPluck($where, $field)
    {
        return self::where($where)->where('delete_time', self::GOODS_UNDELETE)->pluck($field);
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

    /**
     * 获取器——分类名称
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getTypeIdTextAttr($data)
    {
        return model('GoodsType')->getValue(array('id' => $data->type_id), 'name');
    }

    /**
     * 获取器——审核状态文字
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getStatusTextAttr($data)
    {
        return self::$goods_status_desc[$data->status];
    }

    /**
     * 获取器——商品图片列表
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getGoodsImgListAttr($data)
    {
        $res = model('GoodsImg')->getAll(['goods_id' => $data->id]);
        if (!$res) {
            return array();
        }

        foreach ($res as $k => $v) {
            if ($v->url) {
                $res[$k]->url = (substr($v->url, 0, strlen('http')) === 'http') ? $v->url : get_site_cdn_address() . $v->url;
            }
        }

        return $res;
    }

    /**
     * 获取器——商品价格
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getPriceAttr($data)
    {
        return $this->get_goods_final_price($data);
    }

    /**
     * 获取器——是否促销，0表示不是促销商品
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getIsPromoteAttr($data)
    {
        return $this->bargain_price($data->price, $data->promote_start_date, $data->promote_end_date);
    }

    /**
     * 取得商品最终使用价格
     *
     * @param   string $goods_id 商品编号
     * @param   string $goods_num 购买数量
     *
     * @return  商品最终购买价格
     */
    public function get_goods_final_price($goods)
    {
        $final_price = '0'; //商品最终购买价格
        $promote_price = '0'; //商品促销价格
        $user_price = '0'; //商品会员价格，预留

        //取得商品促销价格列表
        $final_price = $goods->price;

        // 计算商品的促销价格
        if ($goods->promote_price > 0) {
            $promote_price = $this->bargain_price($goods->promote_price, $goods->promote_start_date, $goods->promote_end_date);
        }

        if ($promote_price > 0) {
            $final_price = $promote_price;
        }

        //返回商品最终购买价格
        return floatval($final_price);
    }

    /**
     * 判断某个商品是否正在特价促销期
     *
     * @access  public
     * @param   float $price 促销价格
     * @param   string $start 促销开始日期
     * @param   string $end 促销结束日期
     * @return  float   如果还在促销期则返回促销价，否则返回0
     */
    public function bargain_price($price, $start, $end)
    {
        if ($price <= 0) {
            return 0;
        }

        $time = time();
        if ($time >= $start && $time <= $end) {
            return $price;
        }

        return 0;
    }

    /**
     * 增加或减少商品库存
     *
     * @access  public
     * @param int $goods_id 商品ID
     * @param int $type 1增加库存
     * @return  bool
     */
    public function change_goods_stock($where)
    {
        if (isset($where['type']) && $where['type'] == 1) {
            //增加库存
            return $this->setIncrement(array('id' => $where['goods_id']), 'goods_number', $where['goods_number']);
        }
        //减少库存
        return $this->setDecrement(array('id' => $where['goods_id']), 'goods_number', $where['goods_number']);
    }

}