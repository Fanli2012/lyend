<?php

namespace App\Http\Requests;

class FriendlinkRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'name' => 'required|max:30',
        'url' => 'required|max:150',
        'target' => 'integer',
        'group_id' => 'integer',
        'listorder' => 'integer',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'name.required' => '链接名称不能为空',
        'name.max' => '链接名称不能超过30个字符',
        'url.required' => '跳转链接名称不能为空',
        'url.max' => '跳转链接不能超过150个字符',
        'target.integer' => '跳转方式必须是数字',
        'group_id.integer' => '分组ID必须是数字',
        'listorder.integer' => '排序必须是数字',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['name', 'url', 'target', 'group_id', 'listorder'],
        'edit' => ['name', 'url', 'target', 'group_id', 'listorder'],
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