<?php

namespace App\Common\Librarys;

class ReturnData
{
    //通用
    const SUCCESS = 0;    //操作成功
    const FAIL = 1;    //操作失败

	const HTTP_METHOD_INCORRECT = 400; //HTTP Method不正确
	const NOT_FOUND = 404; //找不到指定的资源
	const UNKNOWN = 500; //未知的服务器错误
	const CALL_LIMIT_EXCEEDED = 503; //调用额度已超出限制
	const INVALID_ARGUMENT = 4000; //请求参数非法
	const REQUEST_EXPIRED = 4200; //请求过期
	const ACCESS_DENIED = 4300; //拒绝访问
	const PROTOCOL_NOT_SUPPORTED = 4600; //协议不支持
	const SERVER_INTERNAL_ERROR = 6000; //服务器内部错误
	const VERSION_NOT_SUPPORTED = 6100; //版本暂不支持
	const INTERFACE_INACCESSIBLE = 6200; //接口暂时无法访问

    const FORBIDDEN = 8001; //权限不足
    const SYSTEM_FAIL = 8002; //系统错误，如数据写入失败之类的
    const PARAMS_ERROR = 8003; //参数错误
    const TOKEN_ERROR = 8005; //Token错误
    const SIGN_ERROR = 8006; //签名错误
    const RECORD_EXIST = 8007; //记录已存在
    const RECORD_NOT_EXIST = 8008; //记录不存在

    const AUTH_FAIL = 9001; //鉴权失败
    const TOKEN_EXPIRED = 9002; //Token失效

    //中文错误详情
    public static $codeTexts = array(
        0 => '操作成功',
        1 => '操作失败',

		400 => 'HTTP Method不正确',
		404 => '找不到指定的资源',
		500 => '未知的服务器错误',
		503 => '调用额度已超出限制',
		4000 => '请求参数非法',
		4200 => '请求过期',
		4300 => '拒绝访问',
		4600 => '协议不支持',
		6000 => '服务器内部错误',
		6100 => '版本暂不支持',
		6200 => '接口暂时无法访问',

        8001 => '权限不足',
        8002 => '系统错误，请联系管理员',
        8003 => '参数错误',
        8005 => 'Token错误',
        8006 => '签名错误',
        8007 => '记录已存在',
        8008 => '记录不存在',

        9001 => '鉴权失败',
        9002 => 'Token失效',
    );

    public static function create($code, $data = null, $msg = '')
    {
        if (empty($msg) && isset(self::$codeTexts[$code])) {
            $msg = self::$codeTexts[$code];
        }

        return self::custom($code, $msg, $data);
    }

    public static function success($data = null, $msg = '')
    {

        if (empty($msg) && isset(self::$codeTexts[self::SUCCESS])) {
            $msg = self::$codeTexts[self::SUCCESS];
        }

        return self::custom(self::SUCCESS, $msg, $data);
    }

    public static function error($code, $data = null, $msg = '')
    {
        if (empty($msg) && isset(self::$codeTexts[$code])) {
            $msg = self::$codeTexts[$code];
        }

        if ($code == self::SUCCESS) {
            $code = self::SYSTEM_FAIL;
            $msg = '系统错误';
        }

        return self::custom($code, $msg, $data);
    }

    public static function custom($code, $msg = '', $data = null)
    {
        return array('code' => $code, 'msg' => $msg, 'data' => $data);
    }

    //判断是否成功
    public static function checkSuccess($data)
    {
        if ($data['code'] == self::SUCCESS) {
            return true;
        }

        return false;
    }

    //获取错误代码对应的文字
    public static function getCodeText($code)
    {
        $res = '';
        if (isset(self::$codeTexts[$code])) {
            $res = self::$codeTexts[$code];
        }

        return $res;
    }
}