<?php

namespace App\Http\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 微信自定义菜单
 * 1、自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。
 * 2、一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。
 * 3、创建自定义菜单后，菜单的刷新策略是，在用户进入公众号会话页或公众号profile页时，如果发现上一次拉取菜单的请求在5分钟以前，就会拉取一下菜单，如果菜单有更新，就会刷新客户端的菜单。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。
 */
class WeixinMenuModel extends BaseModel
{
    protected $table = 'weixin_menu';
    public $timestamps = false;
    protected $hidden = array();
    protected $guarded = array(); //$guarded包含你不想被赋值的字段数组。

    const IS_SHOW = 0;

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

    //获取微信自定义菜单
    public static function getWeixinMenuJson()
    {
        $where['pid'] = 0;
        $where['is_show'] = self::IS_SHOW;
        $list = self::where($where)->orderBy('listorder', 'asc')->get();

        $res = '';
        if ($list) {
            foreach ($list as $k => $v) {
                $child = self::where(array('pid' => $list[$k]->id, 'is_show' => self::IS_SHOW))->orderBy('listorder', 'asc')->get();

                if ($child) {
                    $temp_child = '';
                    foreach ($child as $key => $value) {
                        if ($value->type == 'click') {
                            $temp_child[] = array(
                                'type' => $value->type,
                                'name' => $value->name,
                                'key' => $value->key
                            );
                        } elseif ($value->type == 'view') {
                            $temp_child[] = array(
                                'type' => $value->type,
                                'name' => $value->name,
                                'url' => $value->key
                            );
                        } elseif ($value->type == 'miniprogram') {
                            $temp_child[] = array(
                                'type' => $value->type,
                                'name' => $value->name,
                                'url' => $value->key,
                                'appid' => $value->appid,
                                'pagepath' => $value->pagepath
                            );
                        }
                    }

                    $res[] = array(
                        'name' => $value->name,
                        'sub_button' => $temp_child
                    );
                } else {
                    if ($v->type == 'click') {
                        $res[] = array(
                            'type' => $v->type,
                            'name' => $v->name,
                            'key' => $v->key
                        );
                    } elseif ($v->type == 'view') {
                        $res[] = array(
                            'type' => $v->type,
                            'name' => $v->name,
                            'url' => $v->key
                        );
                    } elseif ($v->type == 'miniprogram') {
                        $res[] = array(
                            'type' => $v->type,
                            'name' => $v->name,
                            'url' => $v->key,
                            'appid' => $v->appid,
                            'pagepath' => $v->pagepath
                        );
                    }
                }
            }
        }

        return json_encode($res);
    }
}