<?php

namespace App\Http\Requests;

class Taglist extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'require|integer',
        'tag_id' => 'require|integer',
        'article_id' => 'require|integer',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID必填',
        'id.integer' => 'ID必须为数字',
        'tag_id.required' => 'Tag ID不能为空',
        'tag_id.integer' => 'Tag ID必须是数字',
        'article_id.required' => '文章ID不能为空',
        'article_id.integer' => '文章ID必须是数字',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['tag_id', 'article_id'],
        'edit' => ['tag_id', 'article_id'],
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