<?php

namespace App\Http\Requests;

class SlideRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'title' => 'required|max:150',
        'url' => 'max:150',
        'target' => 'integer',
        'group_id' => 'integer',
        'pic' => 'required|max:150',
        'listorder' => 'integer',
        'status' => 'between:0,2',
    ];
    
    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'title.required' => '标题不能为空',
        'title.max' => '标题不能超过150个字符',
        'url.max' => '跳转链接不能超过150个字符',
        'target.integer' => '跳转方式必须是数字',
        'group_id.integer' => '分组ID必须是数字',
        'pic.required' => '图片地址不能为空',
        'pic.max' => '图片地址不能超过150个字符',
        'listorder.integer' => '排序必须是数字',
        'status.between' => '状态 0正常，1禁用',
    ];
    
    //场景验证规则
    protected $scene = [
        'add' => ['title', 'url', 'target', 'group_id', 'pic', 'listorder', 'status'],
        'edit' => ['title', 'url', 'target', 'group_id', 'pic', 'listorder', 'status'],
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
        
        if(!isset($this->scene[$name]))
        {
            return false;
        }
        
        $scene = $this->scene[$name];
        if($fields != null && is_array($fields))
        {
            $scene = $fields;
        }
        
        foreach($scene as $k=>$v)
        {
            if(isset($this->rules[$v])){$res[$v] = $this->rules[$v];}
        }
        
        return $res;
    }
    
    //获取场景验证规则自定义错误信息
    public function getSceneRulesMessages()
    {
        return $this->messages;
    }
}