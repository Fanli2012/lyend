<?php

namespace App\Http\Requests;

class UserRechargeRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'user_id' => 'required|integer',
        'recharge_sn' => 'required|max:60',
        'money' => ['required', 'regex:/^\d{0,10}(\.\d{0,2})?$/'],
        'pay_time' => 'required|integer',
        'pay_type' => 'integer|between:0,3',
        'pay_money' => ['regex:/^\d{0,10}(\.\d{0,2})?$/'],
        'trade_no' => 'max:60',
        'status' => 'integer|between:0,3',
        'add_time' => 'required|integer',
        'update_time' => 'integer',
        'delete_time' => 'integer',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'user_id.required' => '用户ID不能为空',
        'user_id.integer' => '用户ID必须为数字',
        'recharge_sn.required' => '支付订单号不能为空',
        'recharge_sn.max' => '支付订单号格式不正确',
        'money.required' => '充值金额不能为空',
        'money.regex' => '充值金额只能带2位小数的数字',
        'pay_time.required' => '充值时间不能为空',
        'pay_time.integer' => '充值时间格式不正确',
        'pay_type.integer' => '充值类型必须是数字',
        'pay_type.between' => '充值类型：1微信，2支付宝',
        'pay_money.regex' => '实付金额格式不正确，只能带2位小数的数字',
        'trade_no.max' => '支付流水号格式不正确',
        'status.integer' => '充值状态必须是数字',
        'status.between' => '充值状态：0未处理，1已完成，2失败',
        'add_time.required' => '添加时间必填',
        'add_time.integer' => '添加时间格式不正确',
        'update_time.integer' => '更新时间格式不正确',
        'delete_time.integer' => '删除时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['user_id', 'recharge_sn', 'money'],
        'edit' => ['user_id', 'recharge_sn', 'money'],
        'del' => ['user_id'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //修改为true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * 获取被定义验证规则的错误消息.
     *
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }

    //获取场景验证规则
    public function getSceneRules($name, $fields = null)
    {
        $res = array();

        if (!isset($this->scene[$name])) {
            return false;
        }

        $scene = $this->scene[$name];
        if ($fields != null && is_array($fields)) {
            $scene = $fields;
        }

        foreach ($scene as $k => $v) {
            if (isset($this->rules[$v])) {
                $res[$v] = $this->rules[$v];
            }
        }

        return $res;
    }

    //获取场景验证规则自定义错误信息
    public function getSceneRulesMessages()
    {
        return $this->messages;
    }
}