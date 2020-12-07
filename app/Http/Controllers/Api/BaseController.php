<?php

namespace App\Http\Controllers\Api;

use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;

class BaseController extends CommonController
{
    public function __construct()
    {
        //Token验证
        $this->checkToken();

        parent::__construct();
    }

    /**
     * Token验证
     * @param access_token
     * @return array
     */
    public function checkToken()
    {
        //哪些方法不需要TOKEN验证
        $uncheck = array(
            'payment/index',
            'sysconfig/index',
            'sysconfig/detail',
            'shop/index',
            'shop/detail',
            'guestbook/add',
            'verifycode/get_mobile_verify_code',
            'verifycode/check',
            'emailverifycode/get_email_verify_code',
            'emailverifycode/check'
        );
        $current_url = explode("/api/", url()->current());
        if (!in_array(strtolower($current_url[1]), $uncheck)) {
            //TOKEN验证
            $access_token = request()->header('AccessToken') ?: request('access_token');
            if (!$access_token) {
                $this->operation_log_add(array());
                return ReturnData::create(ReturnData::TOKEN_ERROR);
            }

            $this->login_info = cache('access_token:' . $access_token);
            if (!$this->login_info) {
                $token_info = logic('Token')->checkToken($access_token);
                if ($token_info['code'] != ReturnData::SUCCESS) {
                    $this->operation_log_add([]);
                    return $token_info;
                }

                //Token对应的用户信息
                $this->login_info = logic('User')->getUserInfo(array('id' => $token_info['data']->uid));
                cache(['access_token:' . $access_token => $this->login_info], 3600); //文件缓存60分钟
            }
        }
    }
}
