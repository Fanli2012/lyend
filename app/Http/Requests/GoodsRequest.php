<?php

namespace App\Http\Requests;

class GoodsRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer|gt:0',
        'type_id' => 'required|integer|gte:0',
        'tuijian' => 'integer|gte:0',
        'click' => 'integer|gte:0',
        'title' => 'required|max:150',
        'seotitle' => 'max:150',
        'keywords' => 'max:60',
        'description' => 'max:250',
        'sell_point' => 'max:150',
        'litpic' => 'required|max:150',
        'goods_img' => 'required|max:150',
        'sn' => 'required|max:60',
        'price' => 'required|regex:/^\d{0,10}(\.\d{0,2})?$/|lte:market_price',
        'market_price' => 'required|regex:/^\d{0,10}(\.\d{0,2})?$/',
        'cost_price' => 'regex:/^\d{0,10}(\.\d{0,2})?$/',
        'shipping_fee' => 'regex:/^\d{0,10}(\.\d{0,2})?$/',
        'goods_number' => 'integer|between:0,999999',
        'sale' => 'integer|gte:0',
        'warn_number' => 'integer|between:1,99',
        'goods_weight' => 'regex:/^\d{0,10}(\.\d{0,2})?$/',
        'point' => 'integer|between:1,999999',
        'comment_number' => 'integer|gte:0',
        'promote_price' => 'regex:/^\d{0,10}(\.\d{0,2})?$/|lte:price',
        'promote_start_date' => 'integer|gte:0',
        'promote_end_date' => 'integer|gte:0|gt:promote_start_date',
        'brand_id' => 'integer|gte:0',
        'user_id' => 'integer|gte:0',
        'listorder' => 'integer|gte:0',
        'status' => 'between:0,1,2,3',
        'shop_id' => 'integer|gte:0',
        'update_time' => 'required|integer|gt:0',
        'add_time' => 'required|integer|gt:0',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'id.gt' => 'ID格式不正确',
        'type_id.required' => '栏目ID不能为空',
        'type_id.integer' => '栏目ID必须是数字',
        'type_id.gte' => '栏目ID格式不正确',
        'tuijian.integer' => '推荐等级必须是数字',
        'tuijian.gte' => '推荐等级格式不正确',
        'click.integer' => '点击量必须是数字',
        'click.gte' => '点击量格式不正确',
        'title.required' => '标题不能为空',
        'title.max' => '标题不能超过150个字符',
        'seotitle.max' => 'SEO标题不能超过150个字符',
        'keywords.max' => '关键词不能超过60个字符',
        'description.max' => '描述不能超过250个字符',
        'sell_point.max' => '卖点描述不能超过150个字符',
        'litpic.max' => '缩略图不能超过150个字符',
        'goods_img.required' => '商品图片不能为空',
        'goods_img.max' => '商品的实际大小图片不能超过150个字符',
        'sn.required' => '货号不能为空',
        'sn.max' => '货号不能超过60个字符',
        'price.required' => '产品价格不能为空',
        'price.regex' => '产品价格只能带2位小数的数字',
        'price.lte' => '原价必须大于产品价格',
        'market_price.required' => '原价不能为空',
        'market_price.regex' => '原价格式不正确，原价只能带2位小数的数字',
        'cost_price.regex' => '成本价格格式不正确，成本价格只能带2位小数的数字',
        'shipping_fee.regex' => '运费格式不正确，运费只能带2位小数的数字',
        'goods_number.integer' => '库存必须是数字',
        'goods_number.between' => '库存只能0-999999',
        'sale.integer' => '销量必须是数字',
        'sale.gte' => '销量格式不正确',
        'warn_number.integer' => '商品报警数量必须是数字',
        'warn_number.between' => '商品报警数量只能1-99',
        'goods_weight.regex' => '重量格式不正确，重量只能带2位小数的数字',
        'point.integer' => '购买该商品时每笔成功交易赠送的积分数量必须是数字',
        'point.between' => '购买该商品时每笔成功交易赠送的积分数量只能1-999999',
        'comment_number.integer' => '评论次数必须是数字',
        'comment_number.gte' => '评论次数格式不正确',
        'promote_price.regex' => '促销价格格式不正确，促销价格只能带2位小数的数字',
        'promote_price.lte' => '促销价格必须小于产品价格',
        'promote_start_date.integer' => '促销价格开始日期必须是数字',
        'promote_start_date.gte' => '促销价格开始日期格式不正确',
        'promote_end_date.integer' => '促销价格结束日期必须是数字',
        'promote_end_date.gte' => '促销价格结束日期格式不正确',
        'promote_end_date.gt' => '促销价格开始日期必须小于结束时间',
        'brand_id.integer' => '商品品牌ID必须是数字',
        'brand_id.gte' => '商品品牌ID格式不正确',
        'user_id.integer' => '发布者ID必须是数字',
        'user_id.gte' => '发布者ID格式不正确',
        'listorder.integer' => '排序必须是数字',
        'listorder.gte' => '排序格式不正确',
        'status.between' => '商品状态 0正常 1已删除 2下架 3申请上架',
        'shop_id.integer' => '店铺ID必须是数字',
        'shop_id.gte' => '店铺ID格式不正确',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
        'add_time.gt' => '添加时间格式不正确',
        'update_time.required' => '更新时间不能为空',
        'update_time.integer' => '更新时间格式不正确',
        'update_time.gt' => '更新时间格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['type_id', 'tuijian', 'click', 'title', 'seotitle', 'keywords', 'description', 'sell_point', 'litpic', 'goods_img', 'sn', 'price', 'market_price', 'cost_price', 'shipping_fee', 'goods_number', 'sale', 'warn_number', 'goods_weight', 'point', 'comment_number', 'promote_price', 'promote_start_date', 'promote_end_date', 'brand_id', 'user_id', 'listorder', 'status', 'update_time', 'add_time'],
        'edit' => ['type_id', 'tuijian', 'click', 'title', 'seotitle', 'keywords', 'description', 'sell_point', 'litpic', 'goods_img', 'sn', 'price', 'market_price', 'cost_price', 'shipping_fee', 'goods_number', 'sale', 'warn_number', 'goods_weight', 'point', 'comment_number', 'promote_price', 'promote_start_date', 'promote_end_date', 'brand_id', 'user_id', 'listorder', 'status', 'update_time'],
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