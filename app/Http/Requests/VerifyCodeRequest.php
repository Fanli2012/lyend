<?php

namespace App\Http\Requests;

class VerifyCodeRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer|gt:0',
        'code' => 'required|max:10|integer',
        'type' => 'required|integer|between:0,9',
        'mobile' => 'required|regex:/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/',
        'status' => 'integer|between:0,1',
        'expire_time' => 'required|integer|egt:0',
        'captcha' => 'required',
        'add_time' => 'required|integer|gt:0',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'id.gt' => 'ID格式不正确',
        'code.required' => '验证码不能为空',
        'code.integer' => '验证码格式不正确',
        'code.max' => '验证码不能超过10个字符',
        'type.required' => '验证码类型不能为空',
        'type.integer' => '验证码类型格式不正确',
        'type.between' => '0通用，注册，1:手机绑定业务验证码，2:密码修改业务验证码',
        'mobile.required' => '手机号不能为空',
        'mobile.max' => '手机号不能超过20个字符',
        'status.integer' => '验证码状态格式不正确',
        'status.between' => '0:未使用 1:已使用',
        'captcha.required' => '验证码不能为空',
        'expire_time.required' => '过期时间不能为空',
        'expire_time.integer' => '过期时间格式不正确',
        'expire_time.gt' => '过期时间格式不正确',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
        'add_time.gt' => '添加时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['code', 'type', 'mobile', 'add_time', 'expire_time'],
        'edit' => ['code', 'type', 'mobile'],
        'del' => ['id'],
        'get_smscode_by_smsbao' => ['mobile', 'type'],
        'check' => ['code', 'mobile', 'type'],
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