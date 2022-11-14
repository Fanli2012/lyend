<?php

namespace App\Http\Controllers\Api;

use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;

class UtilController
{
	/**
     * 数据集为JSON字符串
     * @access public
     * @param array $data 数据
     * @param integer $options json参数
     * @return string
     */
    public static function echo_json($data, $options = JSON_UNESCAPED_UNICODE)
    {
		logger('【API返回】：' . json_encode($data));
		exit(json_encode($data, $options));
    }
}