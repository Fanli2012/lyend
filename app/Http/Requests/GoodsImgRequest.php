<?php

namespace App\Http\Requests;

class GoodsImgRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer|gt:0',
        'goods_id' => 'required|integer|gt:0',
        'url' => 'required|max:150',
        'desc' => 'max:150',
        'listorder' => 'integer|gte:0',
        'add_time' => 'required|integer|gte:0',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'id.gt' => 'ID格式不正确',
        'goods_id.required' => '商品ID不能为空',
        'goods_id.integer' => '商品ID必须是数字',
        'goods_id.gt' => '商品ID格式不正确',
        'url.required' => '图片地址不能为空',
        'url.max' => '图片地址不能超过150个字符',
        'desc.max' => '描述不能超过150个字符',
        'listorder.integer' => '排序必须是数字',
        'listorder.gte' => '排序格式不正确',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
        'add_time.gte' => '添加时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['goods_id', 'url', 'desc', 'listorder', 'add_time'],
        'edit' => ['goods_id', 'url', 'desc', 'listorder', 'add_time'],
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