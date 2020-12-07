<?php

namespace App\Http\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuModel extends BaseModel
{
    protected $table = 'menu';
    public $timestamps = false;
    protected $hidden = array();
    protected $guarded = array(); //$guarded包含你不想被赋值的字段数组。

    //状态，0正常，1隐藏
    const MENU_STATUS_NORMAL = 0;
    const MENU_STATUS_DISABLE = 1;
    //状态描述
    public static $menu_status_desc = [
        self::MENU_STATUS_NORMAL => '正常',
        self::MENU_STATUS_DISABLE => '隐藏'
    ];

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
     * 获取器——状态
     * @return string
     */
    public function getStatusTextAttr($data)
    {
        return self::$menu_status_desc[$data->status];
    }

    /**
     * 获取器——菜单类型  1：权限认证+菜单；0：只作为菜单
     * @return string
     */
    public function getTypeTextAttr($data)
    {
        $arr = array(0 => '只作为菜单', 1 => '权限认证+菜单');
        return $arr[$data->type];
    }

    /**
     * 将列表生成树形结构
     * @param int $parent_id 父级ID
     * @param int $deep 层级
     * @return array
     */
    public function get_category($parent_id = 0, $deep = 0)
    {
        $arr = array();

        $cats = model('Menu')->getAll(['parent_id' => $parent_id], ['listorder', 'asc']);
        $cats = object_to_array($cats);
        if ($cats) {
            foreach ($cats as $row)//循环数组
            {
                $row['deep'] = $deep;
                //如果子级不为空
                if ($child = $this->get_category($row["id"], $deep + 1)) {
                    $row['child'] = $child;
                }
                $arr[] = $row;
            }
        }

        return $arr;
    }

    /**
     * 树形结构转成列表
     * @param array $list 数据
     * @param int $parent_id 父级ID
     * @return array
     */
    public function category_tree($list, $parent_id = 0)
    {
        global $temp;
        if (!empty($list)) {
            foreach ($list as $v) {
                $temp[] = array("id" => $v['id'], "deep" => $v['deep'], "name" => $v['name'], "parent_id" => $v['parent_id']);
                //echo $v['id'];
                if (isset($v['child'])) {
                    $this->category_tree($v['child'], $v['parent_id']);
                }
            }
        }

        return $temp;
    }

    //获取后台管理员所具有权限的菜单列表
    public static function getPermissionsMenu($role_id, $pid = 0, $pad = 0)
    {
        $res = [];

        $where['access.role_id'] = $role_id;
        $where['menu.parent_id'] = $pid;
        $where["menu.status"] = 0;

        $menu = object_to_array(DB::table('menu')
            ->join('access', 'access.menu_id', '=', 'menu.id')
            ->select('menu.*', 'access.role_id')
            ->where($where)
            ->orderBy('listorder', 'asc')
            ->get());

        if ($menu) {
            foreach ($menu as $row) {
                $row['deep'] = $pad;

                if ($permissions_menu = self::getPermissionsMenu($role_id, $row['id'], $pad + 1)) {
                    $row['child'] = $permissions_menu;
                }

                $res[] = $row;
            }
        }

        return $res;
    }
}