<?php

namespace App\Http\Requests;

class ArticleRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'type_id' => 'required|integer',
        'tuijian' => 'integer',
        'click' => 'required|integer',
        'title' => 'required|max:150',
        'writer' => 'max:20',
        'source' => 'max:30',
        'litpic' => 'max:100',
        'seotitle' => 'max:150',
        'keywords' => 'max:60',
        'description' => 'max:250',
        'status' => 'between:0,1',
        'user_id' => 'integer',
        'shop_id' => 'integer',
        'add_time' => 'required|integer',
        'update_time' => 'required|integer',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'type_id.required' => '栏目ID不能为空',
        'type_id.integer' => '栏目ID格式不正确',
        'tuijian.integer' => '推荐等级必须是数字',
        'click.integer' => '点击量必须是数字',
        'title.required' => '标题不能为空',
        'title.max' => '标题不能超过150个字符',
        'writer.max' => '作者不能超过20个字符',
        'source.max' => '来源不能超过30个字符',
        'litpic.max' => '缩略图不能超过150个字符',
        'seotitle.max' => 'SEO标题不能超过150个字符',
        'keywords.max' => '关键词不能超过60个字符',
        'description.max' => '描述不能超过250个字符',
        'status.between' => '审核状态：0正常，1未审核',
        'user_id.integer' => '发布者ID必须是数字',
        'shop_id.integer' => '店铺ID必须是数字',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
        'update_time.required' => '更新时间不能为空',
        'update_time.integer' => '更新时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['type_id', 'tuijian', 'click', 'title', 'writer', 'source', 'litpic', 'keywords', 'seotitle', 'description', 'status', 'user_id', 'shop_id', 'add_time', 'update_time'],
        'edit' => ['type_id', 'tuijian', 'click', 'title', 'writer', 'source', 'litpic', 'keywords', 'seotitle', 'description', 'status', 'user_id', 'shop_id', 'add_time', 'update_time'],
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