<?php

namespace App\Http\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserModel extends BaseModel
{
    //用户模型

    protected $table = 'user';
    public $timestamps = false;
    protected $hidden = array('password', 'pay_password');
    protected $guarded = []; //$guarded包含你不想被赋值的字段数组。

    //用户表常用字段，不包含密码等敏感信息
    const USER_COMMON_FIELD = 'id,parent_id,mobile,email,nickname,user_name,head_img,sex,birthday,money,commission,consumption_money,frozen_money,point,user_rank,user_rank_points,address_id,openid,refund_account,refund_name,signin_time,group_id,status,add_time,update_time,login_time';

    //用户未删除
    const USER_UNDELETE = 0;
    //用户状态：0正常，1待审，2锁定
    const USER_STATUS_NORMAL = 0;
    const USER_STATUS_UNCHECK = 1;
    const USER_STATUS_LOCKING = 2;
    //状态描述
    public static $user_status_desc = array(
        self::USER_STATUS_NORMAL => '正常',
        self::USER_STATUS_UNCHECK => '待审',
        self::USER_STATUS_LOCKING => '锁定'
    );

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
        $res = $this->getDb();

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
        return self::where($where)->count($field);
    }

    /**
     * 获取最大值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getMax($where, $field)
    {
        return self::where($where)->max($field);
    }

    /**
     * 获取最小值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getMin($where, $field)
    {
        return self::where($where)->min($field);
    }

    /**
     * 获取平均值
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getAvg($where, $field)
    {
        return self::where($where)->avg($field);
    }

    /**
     * 统计总和
     * @param array $where 条件
     * @param string $field 要统计的字段名（必须）
     * @return null
     */
    public function getSum($where, $field)
    {
        return self::where($where)->sum($field);
    }

    /**
     * 查询某一字段的值
     * @param array $where 条件
     * @param string $field 字段
     * @return null
     */
    public function getValue($where, $field)
    {
        return self::where($where)->value($field);
    }

    /**
     * 查询某一列的值
     * @param array $where 条件
     * @param string $field 字段
     * @return null
     */
    public function getPluck($where, $field)
    {
        return self::where($where)->pluck($field);
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
     * 获取关联到用户的角色
     */
    public function user_role()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'id');
    }

    //性别1男2女
    public function getSexTextAttr($data)
    {
        $arr = [0 => '未知', 1 => '男', 2 => '女'];
        return $arr[$data->sex];
    }

    //用户状态：0正常，1待审，2锁定
    public function getStatusTextAttr($data)
    {
        return self::$user_status_desc[$data->status];
    }

    /**
     * 获取器——用户等级文字
     * @param int $value
     * @param array $data
     * @return string
     */
    public function getUserRankTextAttr($data)
    {
        return model('UserRank')->getValue(array('rank' => $data->user_rank), 'title');
    }

    /**
     * 打印sql
     */
    public function toSql($where)
    {
        return $this->getDb()->where($where)->toSql();
    }

}