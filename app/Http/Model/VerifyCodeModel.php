<?php

namespace App\Http\Model;

use App\Common\Sms;
use App\Common\Helper;
use App\Common\Librarys\ReturnData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//验证码
class VerifyCodeModel extends BaseModel
{
    protected $table = 'verify_code';
    public $timestamps = false;
    protected $hidden = array();
    protected $guarded = array(); //$guarded包含你不想被赋值的字段数组。

    const STATUS_UNUSE = 0;
    const STATUS_USE = 1;                                                       //验证码已被使用

    const TYPE_GENERAL = 0;                                                     //通用
    const TYPE_REGISTER = 1;                                                    //用户注册业务验证码
    const TYPE_CHANGE_MOBILE = 2;                                              //修改/绑定手机号码
    const TYPE_CHANGE_PASSWORD = 3;                                             //密码修改业务验证码
    const TYPE_VERIFYCODE_LOGIN = 4;                                            //验证码登录

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
     * 验证码校验
     * @param int $code 验证码
     * @param string $mobile 手机号
     * @param int $type 请求用途
     * @return array
     */
    public function isVerify($where)
    {
        $where2 = $where;
        $where3 = function ($query) use ($where) {
            $query->where($where);
            $query->where('status', '=', self::STATUS_UNUSE);
            $query->where('expire_time', '>', time());
        };
        $res = $this->getOne($where);
        if ($res) {
            $this->setVerifyCodeUse($where2);
        }
        return $res;
    }

    /**
     * 验证码设置为已使用
     * @param int $code 验证码
     * @param string $mobile 手机号
     * @param int $type 请求用途
     * @return array
     */
    public function setVerifyCodeUse($where)
    {
        return $this->edit(array('status' => self::STATUS_USE), $where);
    }

    //生成验证码
    public function getVerifyCodeBySmsbao($mobile, $type, $text = '')
    {
        $data['code'] = rand(1000, 9999);
        $data['type'] = $type;
        $data['mobile'] = $mobile;
        $data['status'] = self::STATUS_UNUSE;
        //30分钟有效
        $time = time();
        $data['expire_time'] = $time + 60 * 30;
        $data['add_time'] = $time;

        //1分钟后再试
        $where3 = function ($query) use ($type, $mobile, $time) {
            $query->where('type', '=', $type);
            $query->where('mobile', '=', $mobile);
            $query->where('add_time', '>', ($time - 60));
        };
        if ($this->getOne($where3)) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, '请稍后再试');
        }

        if ($text == '') {
            $text = '【' . sysconfig('CMS_WEBNAME') . '】您的验证码是' . $data['code'] . '，有效期30分钟。';
        }
        //短信发送验证码
        //$smsbao = new Smsbao('whhmk', 'whhmk8888');
        $smsbao = new Smsbao('zhanghao', 'mima');
        $res = $smsbao->sms($text, $mobile);
        if ($res['code'] != ReturnData::SUCCESS) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $res['msg']);
        }
        //添加验证码记录
        $this->add($data);

        return ReturnData::create(ReturnData::SUCCESS, array('code' => $data['code']));
    }

    //生成验证码
    public function getVerifyCodeByYunpian($mobile, $type, $text = '')
    {
        $data['code'] = rand(1000, 9999);
        $data['type'] = $type;
        $data['mobile'] = $mobile;
        $data['status'] = self::STATUS_UNUSE;
        //30分钟有效
        $time = time();
        $data['expire_time'] = $time + 60 * 30;
        $data['add_time'] = $time;

        //短信发送验证码
        if ($text != '') {
            $text = '【' . sysconfig('CMS_WEBNAME') . '】您的验证码是' . $data['code'] . '，有效期30分钟。';
        }

        Sms::sendByYp($text, $data['mobile']);

        $this->add($data);

        return ReturnData::create(ReturnData::SUCCESS, array('code' => $data['code']));
    }

}