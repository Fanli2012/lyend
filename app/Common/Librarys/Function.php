<?php
// 公共函数文件
/**
 * CURL
 * @param string $url 链接
 * @param array $params 参数
 * @param string $method 请求方式
 * @param array $headers 头部信息
 * @return array
 */
if (!function_exists('curl_request')) {
    function curl_request($url, $params = array(), $method = 'GET', $headers = array())
    {
        $curl = curl_init();

        switch (strtoupper($method)) {
            case 'GET' :
                if (!empty($params)) {
                    $url .= (strpos($url, '?') ? '&' : '?') . http_build_query($params);
                }
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case 'POST' :
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case 'PUT' :
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case 'DELETE' :
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            return false;
        } else {
            // 解决windows 服务器 BOM 问题
            $response = trim($response, chr(239) . chr(187) . chr(191));
            $response = json_decode($response, true);
        }

        curl_close($curl);

        return $response;
    }
}

/**
 * 获取当前URL
 * @param string|true $url URL地址 true 带域名获取
 * @return string
 */
function get_current_url($flag = false)
{
    $url = '';
    $is_cli = (PHP_SAPI == 'cli') ? true : false;
    if ($is_cli) {
        $url = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
    } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
        $url = $_SERVER['HTTP_X_REWRITE_URL'];
    } elseif (isset($_SERVER['REQUEST_URI'])) {
        $url = $_SERVER['REQUEST_URI'];
    } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
        $url = $_SERVER['ORIG_PATH_INFO'] . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
    }

    if ($url && $flag) {
        $url = http_host() . $url;
    }

    return $url;
}

//获取http(s)://+域名
function http_host($flag = false)
{
    $res = '';
    $protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    $res = "$protocol$_SERVER[HTTP_HOST]";
    if ($flag) {
        $res = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; //完整网址
    }

    return $res;
}

/**
 * 截取中文字符串
 * @param string $string 中文字符串
 * @param int $sublen 截取长度
 * @param int $start 开始长度 默认0
 * @param string $code 编码方式 默认UTF-8
 * @param string $omitted 末尾省略符 默认...
 * @return string
 */
function cut_str($string, $sublen = 250, $omitted = '', $start = 0, $code = 'UTF-8')
{
    $string = strip_tags($string);
    $string = str_replace("　", "", $string);
    $string = mb_strcut($string, $start, $sublen, $code);
    $string .= $omitted;
    return $string;
}

//PhpAnalysis获取中文分词
function get_participle($keyword)
{
	require_once(resource_path('org/phpAnalysis/phpAnalysis.php'));
	//import("Vendor.phpAnalysis.phpAnalysis");
	//初始化类
	PhpAnalysis::$loadInit = false;
    $pa = new PhpAnalysis('utf-8', 'utf-8', false);
	//载入词典
	$pa->LoadDict();
	//执行分词
    $pa->SetSource($keyword);
    $pa->StartAnalysis( false );
    $keywords = $pa->GetFinallyResult(',');
	
    return ltrim($keywords, ",");
}

/**
 * 获取二维码
 * @param string $url url链接
 * @param int $size 点的大小：1到10,用于手机端4就可以了
 * @param string $level 纠错级别：L、M、Q、H
 * @return string
 */
function get_erweima($url, $size = 6, $level = 'H')
{
    require_once resource_path('org/phpqrcode/phpqrcode.php');
    ob_start();
    \QRcode::png($url, false, $level, $size);
    $image_string = base64_encode(ob_get_contents());
    ob_end_clean();
    return 'data:image/jpg;base64,' . $image_string;
}

//判断是否是图片格式，是返回true
function is_image_format($url)
{
    $info = pathinfo($url);
    if (isset($info['extension'])) {
        if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') || ($info['extension'] == 'gif') || ($info['extension'] == 'png')) {
            return true;
        } else {
            return false;
        }
    }
}

//通过file_get_content获取远程数据
function http_request_post($url, $data, $type = 'POST')
{
    $content = http_build_query($data);
    $content_length = strlen($content);
    $options = array(
        'http' => array(
            'method' => $type,
            'header' =>
                "Content-type: application/x-www-form-urlencoded\r\n" .
                "Content-length: $content_length\r\n",
            'content' => $content
        )
    );

    $result = file_get_contents($url, false, stream_context_create($options));

    return $result;
}

function image_resize($url, $width, $height)
{
    header('Content-type: image/jpeg');

    list($width_orig, $height_orig) = getimagesize($url);
    $ratio_orig = $width_orig / $height_orig;

    if ($width / $height > $ratio_orig) {
        $width = $height * $ratio_orig;
    } else {
        $height = $width / $ratio_orig;
    }

    // This resamples the image
    $image_p = imagecreatetruecolor($width, $height);
    $image = imagecreatefromjpeg($url);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
    // Output the image
    imagejpeg($image_p, null, 100);
}

//清空文件夹
function dir_delete($dir)
{
    //$dir = dir_path($dir);
    if (!is_dir($dir)) return false;
    $handle = opendir($dir); //打开目录

    while (($file = readdir($handle)) !== false) {
        if ($file == '.' || $file == '..') continue;
        $d = $dir . DIRECTORY_SEPARATOR . $file;
        is_dir($d) ? dir_delete($d) : @unlink($d);
    }

    closedir($handle);
    return @rmdir($dir);
}

//读取动态配置
function sysconfig($varname = '')
{
    $sysconfig = cache('sysconfig');
    $res = null;
    if (empty($sysconfig)) {
        cache()->forget('sysconfig');

        $sysconfig = \App\Http\Model\SysconfigModel::orderBy('id')->select('varname', 'value')->get()->toArray();

        cache(['sysconfig' => $sysconfig], \Carbon\Carbon::now()->addMinutes(86400));
    }

    if ($varname != '') {
        foreach ($sysconfig as $row) {
            if ($varname == $row['varname']) {
                $res = $row['value'];
            }
        }
    } else {
        $res = $sysconfig;
    }

    return $res;
}

/**
 * 返回json
 * @param array $data
 */
function echo_json($data = array())
{
    // 返回JSON数据格式到客户端 包含状态信息
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($data));
}

/**
 * 密码加密方法，可以考虑盐值包含时间（例如注册时间），
 * @param string $pass 原始密码
 * @return string 多重加密后的32位小写MD5码
 */
function password_encrypt($pass)
{
    if ('' == $pass) {
        return '';
    }
    $salt = config('password_salt');
    return md5(sha1($pass) . $salt);
}

//判断是否为数字
function check_is_number($data)
{
    if ($data == '' || $data == null) {
        return false;
    }

    if (preg_match("/^\d*$/", $data)) {
        return true;
    }

    return false;
}

//获取表所有字段
function get_table_columns($table, $field = '')
{
    $res = \Illuminate\Support\Facades\Schema::getColumnListing($table);

    if ($field != '') {
        //判断字段是否在表里面
        if (in_array($field, $res)) {
            return true;
        } else {
            return false;
        }
    }

    return $res;
}

//对象转数组
function object_to_array($object)
{
    return json_decode(json_encode($object),true);
    $res = [];
    if (empty($object)) {
        return $res;
    }
    if(is_object($object)) {
        $res = (array)$object;
    }
    if(is_array($object)) {
        foreach($object as $key=>$value) {
            $res[$key] = object_array($value);
        }
    }
    return $res;
}

/**
 * 获取数据属性
 * @param $dataModel 数据模型
 * @param $data 数据
 * @return array
 */
function getDataAttr($dataModel,$data = [])
{
    if(empty($dataModel) || empty($data))
    {
        return false;
    }
    
    foreach($data as $k=>$v)
    {
        $_method_str=ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {
            return strtoupper($match[1]);
        }, $k));
        
        $_method = 'get' . $_method_str . 'TextAttr';
        
        if(method_exists($dataModel, $_method))
        {
			$tmp = $k.'_text';
            $data->$tmp = $dataModel->$_method($data);
        }
    }
    
    return $data;
}

/**
 * 调用服务接口
 * @param $name 服务类名称
 * @param array $config 配置
 * @return object
 */
function service($name = '', $config = [])
{
    static $instance = [];
    $guid = $name . 'Service';
    if (!isset($instance[$guid])) {
        $class = 'App\\Http\\Service\\' . ucfirst($name);
        if (class_exists($class)) {
            $service = new $class($config);
            $instance[$guid] = $service;
        } else {
            throw new Exception('class not exists:' . $class);
        }
    }

    return $instance[$guid];
}

/**
 * 调用逻辑接口
 * @param $name 逻辑类名称
 * @param array $config 配置
 * @return object
 */
function logic($name = '', $config = [])
{
    static $instance = [];
    $guid = $name . 'Logic';
    if (!isset($instance[$guid])) {
        $class = 'App\\Http\\Logic\\' . ucfirst($name) . 'Logic';

        if (class_exists($class)) {
            $logic = new $class($config);
            $instance[$guid] = $logic;
        } else {
            throw new Exception('class not exists:' . $class);
        }
    }

    return $instance[$guid];
}

/**
 * 实例化（分层）模型
 * @param $name 模型类名称
 * @param array $config 配置
 * @return object
 */
function model($name = '', $config = [])
{
    static $instance = [];
    $guid = $name . 'Model';
    if (!isset($instance[$guid])) {
        $class = '\\App\\Http\\Model\\' . ucfirst($name) . 'Model';
        if (class_exists($class)) {
            $model = new $class($config);
            $instance[$guid] = $model;
        } else {
            throw new Exception('class not exists:' . $class);
        }
    }

    return $instance[$guid];
}

// 车牌号校验
function is_car_license($license)
{
    if (empty($license)) {
        return false;
    }
    $regular = "/^[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领A-Z]{1}[A-Z]{1}[A-Z0-9]{4}[A-Z0-9挂学警港澳]{1}$/u";
    preg_match($regular, $license, $match);
    if (isset($match[0])) {
        return true;
    }

    return false;
}

/**
 * 格式化文件大小显示
 *
 * @param int $size
 * @return string
 */
function format_bytes($size)
{
    $prec = 3;
    $size = round(abs($size));
    $units = array(
        0 => " B ",
        1 => " KB",
        2 => " MB",
        3 => " GB",
        4 => " TB"
    );
    if ($size == 0) {
        return str_repeat(" ", $prec) . "0$units[0]";
    }
    $unit = min(4, floor(log($size) / log(2) / 10));
    $size = $size * pow(2, -10 * $unit);
    $digi = $prec - 1 - floor(log($size) / log(10));
    $size = round($size * pow(10, $digi)) * pow(10, -$digi);

    return $size . $units[$unit];
}


// ----------其它自定义函数，主要用于真的当前项目----------

/**
 * 操作错误跳转的快捷方法
 * @access protected
 * @param string $msg 错误信息
 * @param string $url 页面跳转地址
 * @param mixed $time 当数字时指定跳转时间
 * @return void
 */
function error_jump($msg = '', $url = '', $time = 3)
{
    if ($url == '' && isset($_SERVER["HTTP_REFERER"])) {
        $url = $_SERVER["HTTP_REFERER"];
    }

    if (!headers_sent()) {
        header("Location:" . route('admin_jump') . "?error=$msg&url=$url&time=$time");
        exit();
    } else {
        $str = "<meta http-equiv='Refresh' content='URL=" . route('admin_jump') . "?error=$msg&url=$url&time=$time" . "'>";
        exit($str);
    }
}

/**
 * 操作成功跳转的快捷方法
 * @access protected
 * @param string $msg 提示信息
 * @param string $url 页面跳转地址
 * @param mixed $time 当数字时指定跳转时间
 * @return void
 */
function success_jump($msg = '', $url = '', $time = 1)
{
    if ($url == '' && isset($_SERVER["HTTP_REFERER"])) {
        $url = $_SERVER["HTTP_REFERER"];
    }

    if (!headers_sent()) {
        header("Location:" . route('admin_jump') . "?message=$msg&url=$url&time=$time");
        exit();
    } else {
        $str = "<meta http-equiv='Refresh' content='URL=" . route('admin_jump') . "?message=$msg&url=$url&time=$time" . "'>";
        exit($str);
    }
}

// 获取API地址
function get_api_url_address()
{
    return http_host() . sysconfig('CMS_API_URL');
}

// 获取静态资源CDN地址，CMS_SITE_CDN_ADDRESS为空表示本地
function get_site_cdn_address()
{
    $res = sysconfig('CMS_SITE_CDN_ADDRESS');
    if ($res) {
        return $res;
    }

    return http_host();
}

// 广告代码的调用方法
function get_ad_code($ad_id)
{
    return '<script type="text/javascript" src="' . url('ad/detail') . '?id=' . $ad_id .'"></script>';
}
