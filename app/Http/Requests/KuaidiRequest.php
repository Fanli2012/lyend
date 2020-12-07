<?php

namespace App\Http\Requests;

class KuaidiRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'name' => 'required|max:30',
        'code' => 'required|max:20',
        'money' => 'required|regex:/^\d{0,10}(\.\d{0,2})?$/',
        'country' => 'max:20',
        'desc' => 'max:150',
        'tel' => 'max:60',
        'website' => 'max:60',
        'listorder' => 'integer',
        'status' => 'between:0,1',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'name.required' => '快递公司名称不能为空',
        'name.max' => '快递公司名称不能超过30个字符',
        'code.required' => '公司编码不能为空',
        'code.max' => '公司编码不能超过20个字符',
        'money.required' => '快递费不能为空',
        'money.regex' => '快递费只能带2位小数的数字',
        'country.max' => '国家编码不能超过20个字符',
        'desc.max' => '说明不能超过150个字符',
        'tel.max' => '电话不能超过60个字符',
        'website.max' => '官网不能超过60个字符',
        'listorder.integer' => '排序必须是数字',
        'status.between' => '是否显示，0显示',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['name', 'code', 'money', 'country', 'desc', 'tel', 'website', 'listorder', 'status'],
        'edit' => ['name', 'code', 'money', 'country', 'desc', 'tel', 'website', 'listorder', 'status'],
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