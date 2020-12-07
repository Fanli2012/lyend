<?php

namespace App\Http\Requests;

class MenuRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'parent_id' => 'integer',
        'action' => 'required|max:50|alpha_dash',
        'data' => 'max:50',
        'type' => 'integer|between:0,1',
        'name' => 'required|max:50',
        'icon' => 'max:50',
        'desc' => 'max:250',
        'listorder' => 'integer',
        'status' => 'between:0,1',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'parent_id.integer' => '父级ID必须是数字',
        'action.required' => '方法不能为空',
        'action.alpha_dash' => '方法格式不正确',
        'action.max' => '方法不能超过50个字符',
        'data.max' => '额外参数不能超过50个字符',
        'type.integer' => '菜单类型必须是数字',
        'type.between' => '菜单类型，1：权限认证+菜单；0：只作为菜单',
        'name.required' => '名称不能为空',
        'name.max' => '名称不能超过50个字符',
        'icon.max' => '菜单图标不能超过50个字符',
        'desc.max' => '备注不能超过250个字符',
        'listorder.integer' => '排序必须是数字',
        'status.between' => '状态，1显示，0不显示',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['parent_id', 'action', 'data', 'type', 'name', 'icon', 'desc', 'listorder', 'status'],
        'edit' => ['parent_id', 'action', 'data', 'type', 'name', 'icon', 'desc', 'listorder', 'status'],
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