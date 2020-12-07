<?php

namespace App\Http\Requests;

class SysconfigRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'varname' => 'required|max:60|regex:/^CMS_[A-Z_]+$/',
        'info' => 'required|max:100',
        'value' => '',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'varname.required' => '变量名不能为空',
        'varname.max' => '变量名不能超过60个字符',
        'varname.regex' => '变量名格式不正确',
        'info.required' => '变量值不能为空',
        'info.max' => '变量值不能超过100个字符',
        'value.required' => '变量说明不能为空',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['varname', 'info'],
        'edit' => ['varname', 'info'],
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

    /**
     * 变量名验证
     * 参数依次为验证数据，验证规则，全部数据(数组)，字段名
     */
    protected function check_varname($value)
    {
        if (preg_match("/^CMS_[A-Z_]+$/", $value)) {
            return true;
        }

        return '变量名格式不正确';
    }
}