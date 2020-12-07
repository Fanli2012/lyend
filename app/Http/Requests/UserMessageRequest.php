<?php

namespace App\Http\Requests;

class UserMessageRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'user_id' => 'required|integer',
        'title' => 'max:150',
        'desc' => 'required|max:250',
        'litpic' => 'max:150',
        'type' => 'integer|between:0,9',
        'status' => 'integer|between:0,9',
        'add_time' => 'required|integer',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'user_id.required' => '用户ID不能为空',
        'user_id.integer' => '用户ID必须为数字',
        'title.max' => '标题不能超过150个字符',
        'desc.required' => '描述不能为空',
        'desc.max' => '描述不能超过150个字符',
        'litpic.max' => '缩略图不能超过150个字符',
        'type.integer' => '消息类型必须为数字',
        'type.between' => '系统消息0，活动消息1',
        'status.integer' => '查看状态必须为数字',
        'status.between' => '查看状态：0未查看，1已查看',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['user_id', 'title', 'desc', 'litpic', 'type', 'status'],
        'edit' => ['user_id', 'title', 'desc', 'litpic', 'type', 'status'],
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