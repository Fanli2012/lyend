<?php

namespace App\Http\Requests;

class UserRankRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer|gt:0',
        'title' => 'required|max:30',
        'min_points' => 'required|integer|gte:0',
        'max_points' => 'required|integer|gte:0|gt:min_points',
        'discount' => 'required|integer|between:0,100',
        'rank' => 'required|integer|gt:0',
        'listorder' => 'integer|gte:0',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'id.gt' => 'ID格式不正确',
        'title.required' => '会员等级名称不能为空',
        'title.max' => '会员等级名称不能超过30个字符',
        'min_points.required' => '等级的最低积分不能为空',
        'min_points.integer' => '等级的最低积分必须是数字',
        'min_points.gte' => '等级的最低积分格式不正确',
        'max_points.required' => '等级的最高积分不能为空',
        'max_points.integer' => '等级的最高积分必须是数字',
        'max_points.gte' => '等级的最高积分格式不正确',
        'max_points.gt' => '最高积分应大于最低积分',
        'discount.required' => '会员等级的商品折扣不能为空',
        'discount.integer' => '会员等级的商品折扣格式不正确',
        'discount.between' => '会员等级的商品折扣格式不正确',
        'rank.required' => '会员等级不能为空',
        'rank.integer' => '会员等级格式不正确',
        'rank.gt' => '会员等级要大于0',
        'listorder.integer' => '排序格式不正确',
        'listorder.gte' => '排序格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['title', 'min_points', 'max_points', 'discount', 'rank', 'listorder'],
        'edit' => ['title', 'min_points', 'max_points', 'discount', 'rank', 'listorder'],
        'del' => ['user_id'],
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