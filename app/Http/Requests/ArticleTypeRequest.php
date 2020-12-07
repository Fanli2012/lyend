<?php

namespace App\Http\Requests;

class ArticleTypeRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'parent_id' => 'integer',
        'name' => 'required|max:30',
        'seotitle' => 'max:150',
        'keywords' => 'max:60',
        'description' => 'max:250',
        'filename' => 'required|max:30|regex:/^[a-z]{1,}[a-z0-9]*$/',
        'templist' => 'max:50|regex:/^[a-z]{1,}[a-z0-9]*$/',
        'temparticle' => 'max:50|regex:/^[a-z]{1,}[a-z0-9]*$/',
        'litpic' => 'max:150',
        'is_part' => 'between:0,1,2',
        'listorder' => 'integer',
        'shop_id' => 'integer',
        'add_time' => 'required|integer',
        'update_time' => 'required|integer',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'parent_id.integer' => '父级ID格式不正确',
        'name.required' => '栏目名称不能为空',
        'name.max' => '栏目名称不能超过30个字符',
        'seotitle.max' => 'SEO标题不能超过150个字符',
        'keywords.max' => '关键词不能超过60个字符',
        'description.max' => '描述不能超过250个字符',
        'filename.required' => '别名不能为空',
        'filename.max' => '别名不能超过30个字符',
        'filename.regex' => '别名格式不正确',
        'templist.max' => '列表页模板不能超过50个字符',
        'templist.regex' => '列表页模板格式不正确',
        'temparticle.max' => '文章页模板不能超过50个字符',
        'temparticle.regex' => '文章页模板格式不正确',
        'litpic.max' => '缩略图不能超过150个字符',
        'is_part.between' => '栏目属性：0列表栏目（允许发布文档），1频道封面（不允许发布文档）',
        'listorder.integer' => '排序必须是数字',
        'shop_id.integer' => '店铺ID必须是数字',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
        'update_time.required' => '更新时间不能为空',
        'update_time.integer' => '更新时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['parent_id', 'name', 'seotitle', 'keywords', 'description', 'filename', 'templist', 'temparticle', 'litpic', 'is_part', 'listorder', 'shop_id', 'add_time', 'update_time'],
        'edit' => ['parent_id', 'name', 'seotitle', 'keywords', 'description', 'filename', 'templist', 'temparticle', 'litpic', 'is_part', 'listorder', 'shop_id', 'update_time'],
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