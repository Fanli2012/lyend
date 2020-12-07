<?php

namespace App\Http\Requests;

class SearchwordRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'name' => 'required|max:36',
        'title' => 'max:60',
        'keywords' => 'max:100',
        'description' => 'max:250',
        'click' => 'integer',
        'litpic' => 'max:150',
        'template' => 'max:30',
        'filename' => 'required|max:60|regex:/^[a-z]{1,}[a-z0-9]*$/',
        'status' => 'between:0,2',
        'update_time' => 'required|integer',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'name.required' => '名称不能为空',
        'name.max' => '名称不能超过36个字符',
        'title.max' => 'SEO标题不能超过60个字符',
        'keywords.max' => '关键词不能超过100个字符',
        'description.max' => '描述不能超过250个字符',
        'click.integer' => '点击量必须是数字',
        'litpic.max' => '缩略图不能超过150个字符',
        'template.max' => '模板名称不能超过30个字符',
        'filename.required' => '别名不能为空',
        'filename.max' => '别名不能超过60个字符',
        'filename.regex' => '别名格式不正确',
        'status.between' => '状态，0正常，1禁用',
        'update_time.required' => '添加时间不能为空',
        'update_time.integer' => '添加时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['name', 'title', 'keywords', 'description', 'click', 'litpic', 'template', 'filename', 'status', 'update_time'],
        'edit' => ['name', 'title', 'keywords', 'description', 'click', 'litpic', 'template', 'filename', 'status', 'update_time'],
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