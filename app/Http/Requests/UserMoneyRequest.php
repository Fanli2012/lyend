<?php

namespace App\Http\Requests;

class UserMoneyRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer|gt:0',
        'user_id' => 'required|integer|gt:0',
        'money' => 'required|regex:/^\d{0,10}(\.\d{0,2})?$/',
        'desc' => 'required|max:100',
        'user_money' => 'regex:/^\d{0,10}(\.\d{0,2})?$/',
        'type' => 'required|between:0,1',
        'add_time' => 'required|integer|gt:0',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'id.gt' => 'ID格式不正确',
        'user_id.required' => '用户ID不能为空',
        'user_id.integer' => '用户ID必须是数字',
        'user_id.gt' => '用户ID格式不正确',
        'money.required' => '金额不能为空',
        'money.regex' => '金额格式不正确',
        'desc.required' => '描述不能为空',
        'desc.max' => '描述格式不正确',
        'user_money.regex' => '余额格式不正确',
        'type.required' => '类型不能为空',
        'type.between' => '类型：0增加,1减少',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
        'add_time.gt' => '添加时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['user_id', 'money', 'desc', 'type'],
        'edit' => ['user_id', 'money', 'desc', 'type'],
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