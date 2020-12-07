<?php

namespace App\Http\Requests;

use App\Rules\IsMobileRule;

class UserRequest extends BaseRequest
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer|gt:0',
        'parent_id' => 'integer|gte:0',
        'mobile' => ['regex:/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/'],
        'email' => 'email',
        'nickname' => 'max:30',
        'user_name' => ['required', 'regex:/^[-_a-zA-Z0-9]{3,18}$/i'],
        'password' => ['required', 'regex:/^[-_a-zA-Z0-9]{6,18}$/i'],
        'pay_password' => ['required', 'regex:/^[-_a-zA-Z0-9]{6,18}$/i'],
        'head_img' => 'max:250',
        'sex' => 'between:0,2',
        'birthday' => 'date_format:"Y-m-d"',
        'money' => ['gte:0', 'regex:/^\d{0,10}(\.\d{0,2})?$/'],
        'commission' => ['gte:0', 'regex:/^\d{0,10}(\.\d{0,2})?$/'],
        'consumption_money' => ['gte:0', 'regex:/^\d{0,10}(\.\d{0,2})?$/'],
        'frozen_money' => ['gte:0', 'regex:/^\d{0,10}(\.\d{0,2})?$/'],
        'point' => 'integer|gte:0',
        'user_rank' => 'integer|max:2',
        'user_rank_points' => 'integer|gte:0',
        'address_id' => 'integer|gte:0',
        'openid' => 'max:128',
        'unionid' => 'max:128',
        'refund_account' => 'max:30',
        'refund_name' => 'max:20',
        'signin_time' => 'integer|gte:0',
        'group_id' => 'integer|gte:0',
        'status' => 'between:0,2',
        'add_time' => 'required|integer|gte:0',
        'update_time' => 'required|integer|gte:0',
        'login_time' => 'integer|gte:0',
        'delete_time' => 'integer|gte:0',
        're_password' => ['required', 'regex:/^[-_a-zA-Z0-9]{6,18}$/i|confirmed:password'],
        'captcha' => 'required',
        'smscode' => 'required',
        'smstype' => 'required|integer|gte:0',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID不能为空',
        'id.integer' => 'ID必须是数字',
        'id.gt' => 'ID格式不正确',
        'parent_id.integer' => '推荐人ID必须是数字',
        'parent_id.gte' => '推荐人ID格式不正确',
        'mobile.regex' => '手机号码格式不正确',
        'email.email' => '邮箱格式不正确',
        'nickname.max' => '昵称不能超过30个字符',
        'user_name.required' => '用户名不能为空',
        'user_name.regex' => '用户名格式不正确',
        'password.required' => '密码不能为空',
        'password.regex' => '密码6-18位',
        'pay_password.required' => '支付密码不能为空',
        'pay_password.regex' => '支付密码6-18位',
        'head_img.max' => '头像格式不正确',
        'sex.between' => '性别格式不正确',
        'money.gte' => '用户余额格式不正确',
        'money.regex' => '用户余额格式不正确',
        'commission.gte' => '累积佣金格式不正确',
        'commission.regex' => '累积佣金格式不正确',
        'consumption_money.gte' => '累计消费金额格式不正确',
        'consumption_money.regex' => '累计消费金额格式不正确',
        'frozen_money.gte' => '用户冻结资金格式不正确',
        'frozen_money.regex' => '用户冻结资金格式不正确',
        'point.integer' => '用户能用积分必须是数字',
        'point.gte' => '用户能用积分格式不正确',
        'user_rank.integer' => '用户等级必须是数字',
        'user_rank.max' => '用户等级格式不正确',
        'user_rank_points.integer' => '会员等级积分必须是数字',
        'user_rank_points.gte' => '会员等级积分格式不正确',
        'address_id.integer' => '收货地址ID必须是数字',
        'address_id.gte' => '收货地址ID格式不正确',
        'openid.max' => 'openid格式不正确',
        'unionid.max' => 'unionid格式不正确',
        'refund_account.max' => '退款账户不能超过30个字符',
        'refund_name.max' => '退款姓名不能超过20个字符',
        'signin_time.integer' => '签到时间格式不正确',
        'signin_time.gte' => '签到时间格式不正确',
        'group_id.integer' => '分组ID必须是数字',
        'group_id.gte' => '分组ID格式不正确',
        'status.between' => '用户状态：0正常，1待审，2锁定',
        'add_time.required' => '添加时间不能为空',
        'add_time.integer' => '添加时间格式不正确',
        'add_time.gte' => '添加时间格式不正确',
        'update_time.required' => '更新时间不能为空',
        'update_time.integer' => '更新时间格式不正确',
        'update_time.gte' => '更新时间格式不正确',
        'login_time.integer' => '登录时间格式不正确',
        'login_time.gte' => '登录时间格式不正确',
        'delete_time.integer' => '删除时间格式不正确',
        'delete_time.gte' => '删除时间格式不正确',
        're_password.required' => '确认密码不能为空',
        're_password.regex' => '确认密码格式不正确',
        're_password.confirm' => '密码与确认密码不一致',
        'captcha.required' => '图形验证码不能为空',
        'smscode.required' => '短信验证码不能为空',
        'smstype.required' => '短信验证码类型不能为空',
        'smstype.integer' => '短信验证码类型格式不正确',
        'smstype.gte' => '短信验证码类型格式不正确',
    ];

    //场景验证规则
    protected $scene = [
        'add' => ['parent_id', 'mobile', 'email', 'nickname', 'user_name', 'password', 'head_img', 'sex', 'birthday', 'openid', 'status', 'add_time'],
        'register' => ['parent_id', 'mobile', 'email', 'nickname', 'user_name', 'password', 'head_img', 'sex', 'add_time'],
        'wx_register' => ['parent_id', 'mobile', 'email', 'nickname', 'user_name', 'head_img', 'sex', 'birthday', 'openid', 'add_time'],
        'user_password_update' => ['password'],
        'user_pay_password_update' => ['pay_password'],
        'del' => ['id'],
        'pc_mobile_reg' => ['mobile', 'password', 're_password', 'smscode', 'smstype'],
        'pc_email_reg' => ['email', 'password', 're_password', 'smscode', 'smstype'],
        'pc_resetpwd' => ['mobile', 'password', 're_password', 'smscode', 'smstype'],
        'login' => ['user_name', 'password'],
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