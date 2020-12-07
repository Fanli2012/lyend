<?php

namespace App\Http\Requests;

class BonusRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer|gt:0',
        'name' => 'required|max:60',
        'money' => 'required|regex:/^\d{0,10}(\.\d{0,2})?$/|gt:0',
        'min_amount' => 'required|regex:/^\d{0,10}(\.\d{0,2})?$/|gte:money',
        'start_time' => 'required|integer|gt:0',
        'end_time' => 'required|integer|gte:start_time',
        'num' => 'required|integer|between:-1,999999999',
        'point' => 'integer|gte:0',
        'status' => 'between:0,1',
        'add_time' => 'required|integer|gt:0',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'id.gt' => 'ID格式不正确',
        'name.required' => '优惠券名称不能为空',
        'name.max' => '优惠券名称不能超过60个字符',
        'money.required' => '优惠券金额不能为空',
        'money.regex' => '优惠券金额格式不正确',
        'money.gt' => '优惠券金额必须大于0',
        'min_amount.required' => '满多少使用不能为空',
        'min_amount.regex' => '满多少使用格式不正确',
        'min_amount.gte' => '金额必须小于最低金额',
        'start_time.required' => '开始时间不能为空',
        'start_time.integer' => '开始时间格式不正确',
        'start_time.gt' => '开始时间格式不正确',
        'end_time.required' => '结束时间不能为空',
        'end_time.integer' => '结束时间格式不正确',
        'end_time.gte' => '开始时间必须小于结束时间',
        'num.required' => '优惠券数量不能为空',
        'num.integer' => '优惠券数量必须是数字',
        'num.between' => '优惠券数量格式不正确',
        'point.integer' => '兑换优惠券所需积分必须是数字',
        'point.gte' => '兑换优惠券所需积分格式不正确',
        'status.between' => '状态：0可用，1不可用',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
        'add_time.gt' => '添加时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['name', 'money', 'min_amount', 'start_time', 'end_time', 'num', 'point', 'status', 'add_time'],
        'edit' => ['name', 'money', 'min_amount', 'start_time', 'end_time', 'num', 'point', 'status'],
        'del' => ['id'],
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