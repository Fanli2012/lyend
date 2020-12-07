<?php

namespace App\Http\Requests;

use App\Rules\IsMobileRule;
use App\Rules\IsPasswordRule;

class AdminRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'role_id' => 'required|integer',
        'name' => ['required', 'max:30', 'alpha_dash'],
        'pwd' => 'required|regex:/^[-_a-zA-Z0-9]{6,18}$/i',
        'mobile' => 'min:11|max:11|regex:/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/',
        'email' => 'email',
        'avatar' => 'max:150',
        'status' => 'between:0,2',
        'login_time' => 'integer',
        'add_time' => 'integer',
        'update_time' => 'integer',
        'delete_time' => 'integer',
    ];

    //修改
    public $edit_rules = [
        'role_id' => 'required|integer',
        'name' => ['required', 'max:30', 'alpha_dash'],
        'pwd' => 'regex:/^[-_a-zA-Z0-9]{6,18}$/i',
        'mobile' => 'min:11|max:11|regex:/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/',
        'email' => 'email',
        'avatar' => 'max:150',
        'status' => 'between:0,2',
        'update_time' => 'integer',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'role_id.required' => '角色ID不能为空',
        'role_id.integer' => '角色ID必须是数字',
        'name.required' => '名称不能为空',
        'name.max' => '名称不能超过30个字符',
        'name.alpha_dash' => '名称只能包含字母和数字、下划线及破折号',
        'pwd.required' => '密码不能为空',
        'pwd.min' => '密码6-18个字符',
        'pwd.max' => '密码6-18个字符',
        'pwd.regex' => '密码不能超过18个字符',
        'mobile.min' => '手机号码格式不正确',
        'mobile.max' => '手机号码格式不正确',
        'mobile.regex' => '手机号码格式不正确',
        'email.email' => '邮箱格式不正确',
        'avatar.max' => '头像不能超过150个字符',
        'status.between' => '用户状态 0：正常； 1：禁用 ；2：未验证',
        'login_time.integer' => '登录时间格式不正确',
        'update_time.integer' => '更新时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['role_id', 'name', 'pwd', 'mobile', 'email', 'avatar', 'status', 'login_time', 'add_time', 'update_time'],
        'edit' => ['role_id', 'name', 'pwd', 'mobile', 'email', 'avatar', 'status', 'login_time', 'update_time'],
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