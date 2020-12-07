<?php

namespace App\Http\Requests;

class TokenRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'token' => 'required|max:128',
        'type' => 'required|integer|between:0,9',
        'uid' => 'required|integer',
        'expire_time' => 'required|integer|gte:0',
        'add_time' => 'required|integer|gte:0',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须为数字',
        'token.required' => 'Token不能为空',
        'token.max' => 'Token不能超过128个字符',
        'type.required' => 'Token类型不能为空',
        'type.integer' => 'Token类型必须是数字',
        'type.between' => '来源：0app,1admin,2weixin,3wap,4pc,5miniprogram',
        'uid.required' => '用户ID不能为空',
        'uid.integer' => '用户ID必须是数字',
        'expire_time.required' => '过期时间不能为空',
        'expire_time.integer' => '过期时间格式不正确',
        'expire_time.gte' => '过期时间格式不正确',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
        'add_time.gte' => '添加时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['token', 'type', 'uid', 'expire_time', 'add_time'],
        'edit' => ['token', 'type', 'uid', 'expire_time'],
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