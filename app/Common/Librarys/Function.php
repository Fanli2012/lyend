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

/**
 * 转义textarea值
 *
 * @param string $text
 * @return string
 */
function esc_textarea($text)
{
    $safe_text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    return $safe_text;
}

/**
 * Converts a number of special characters into their HTML entities.
 *
 * Specifically deals with: &, <, >, ", and '.
 *
 * $quote_style can be set to ENT_COMPAT to encode " to
 * &quot;, or ENT_QUOTES to do both. Default is ENT_NOQUOTES where no quotes are encoded.
 *
 * @since 5.5.0 `$quote_style` also accepts `ENT_XML1`.
 * @access private
 *
 * @param string $string The text which is to be encoded.
 * @param int|string $quote_style Optional. Converts double quotes if set to ENT_COMPAT,
 *                                    both single and double if set to ENT_QUOTES or none if set to ENT_NOQUOTES.
 *                                    Converts single and double quotes, as well as converting HTML
 *                                    named entities (that are not also XML named entities) to their
 *                                    code points if set to ENT_XML1. Also compatible with old values;
 *                                    converting single quotes if set to 'single',
 *                                    double if set to 'double' or both if otherwise set.
 *                                    Default is ENT_NOQUOTES.
 * @param false|string $charset Optional. The character encoding of the string. Default false.
 * @param bool $double_encode Optional. Whether to encode existing HTML entities. Default false.
 * @return string The encoded text with HTML entities.
 */
function _wp_specialchars($string, $quote_style = ENT_NOQUOTES, $charset = 'UTF-8', $double_encode = false)
{
    $string = (string)$string;

    if (0 === strlen($string)) {
        return '';
    }

    // Don't bother if there are no specialchars - saves some processing.
    if (!preg_match('/[&<>"\']/', $string)) {
        return $string;
    }

    // Account for the previous behaviour of the function when the $quote_style is not an accepted value.
    if (empty($quote_style)) {
        $quote_style = ENT_NOQUOTES;
    } elseif (ENT_XML1 === $quote_style) {
        $quote_style = ENT_QUOTES | ENT_XML1;
    } elseif (!in_array($quote_style, array(ENT_NOQUOTES, ENT_COMPAT, ENT_QUOTES, 'single', 'double'), true)) {
        $quote_style = ENT_QUOTES;
    }

    if (in_array($charset, array('utf8', 'utf-8', 'UTF8'), true)) {
        $charset = 'UTF-8';
    }

    $_quote_style = $quote_style;

    if ('double' === $quote_style) {
        $quote_style = ENT_COMPAT;
        $_quote_style = ENT_COMPAT;
    } elseif ('single' === $quote_style) {
        $quote_style = ENT_NOQUOTES;
    }

    $string = htmlspecialchars($string, $quote_style, $charset, $double_encode);

    // Back-compat.
    if ('single' === $_quote_style) {
        $string = str_replace("'", '&#039;', $string);
    }

    return $string;
}

/**
 * Converts a number of HTML entities into their special characters.
 *
 * Specifically deals with: &, <, >, ", and '.
 *
 * $quote_style can be set to ENT_COMPAT to decode " entities,
 * or ENT_QUOTES to do both " and '. Default is ENT_NOQUOTES where no quotes are decoded.
 *
 * @param string $string The text which is to be decoded.
 * @param string|int $quote_style Optional. Converts double quotes if set to ENT_COMPAT,
 *                                both single and double if set to ENT_QUOTES or
 *                                none if set to ENT_NOQUOTES.
 *                                Also compatible with old _wp_specialchars() values;
 *                                converting single quotes if set to 'single',
 *                                double if set to 'double' or both if otherwise set.
 *                                Default is ENT_NOQUOTES.
 * @return string The decoded text without HTML entities.
 */
function _wp_specialchars_decode($string, $quote_style = ENT_NOQUOTES)
{
    $string = (string)$string;

    if (0 === strlen($string)) {
        return '';
    }

    // Don't bother if there are no entities - saves a lot of processing.
    if (strpos($string, '&') === false) {
        return $string;
    }

    // Match the previous behaviour of _wp_specialchars() when the $quote_style is not an accepted value.
    if (empty($quote_style)) {
        $quote_style = ENT_NOQUOTES;
    } elseif (!in_array($quote_style, array(0, 2, 3, 'single', 'double'), true)) {
        $quote_style = ENT_QUOTES;
    }

    // More complete than get_html_translation_table( HTML_SPECIALCHARS ).
    $single = array(
        '&#039;' => '\'',
        '&#x27;' => '\'',
    );
    $single_preg = array(
        '/&#0*39;/' => '&#039;',
        '/&#x0*27;/i' => '&#x27;',
    );
    $double = array(
        '&quot;' => '"',
        '&#034;' => '"',
        '&#x22;' => '"',
    );
    $double_preg = array(
        '/&#0*34;/' => '&#034;',
        '/&#x0*22;/i' => '&#x22;',
    );
    $others = array(
        '&lt;' => '<',
        '&#060;' => '<',
        '&gt;' => '>',
        '&#062;' => '>',
        '&amp;' => '&',
        '&#038;' => '&',
        '&#x26;' => '&',
    );
    $others_preg = array(
        '/&#0*60;/' => '&#060;',
        '/&#0*62;/' => '&#062;',
        '/&#0*38;/' => '&#038;',
        '/&#x0*26;/i' => '&#x26;',
    );

    if (ENT_QUOTES === $quote_style) {
        $translation = array_merge($single, $double, $others);
        $translation_preg = array_merge($single_preg, $double_preg, $others_preg);
    } elseif (ENT_COMPAT === $quote_style || 'double' === $quote_style) {
        $translation = array_merge($double, $others);
        $translation_preg = array_merge($double_preg, $others_preg);
    } elseif ('single' === $quote_style) {
        $translation = array_merge($single, $others);
        $translation_preg = array_merge($single_preg, $others_preg);
    } elseif (ENT_NOQUOTES === $quote_style) {
        $translation = $others;
        $translation_preg = $others_preg;
    }

    // Remove zero padding on numeric entities.
    $string = preg_replace(array_keys($translation_preg), array_values($translation_preg), $string);

    // Replace characters according to translation table.
    return strtr($string, $translation);
}

/**
 * Checks for invalid UTF8 in a string.
 *
 * @param string $string The text which is to be checked.
 * @param bool $strip Optional. Whether to attempt to strip out invalid UTF8. Default false.
 * @return string The checked text.
 */
function check_invalid_utf8($string, $strip = false)
{
    $string = (string)$string;

    if (0 === strlen($string)) {
        return '';
    }
    // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- preg_match fails when it encounters invalid UTF8 in $string.
    if (1 === @preg_match('/^./us', $string)) {
        return $string;
    }

    // Attempt to strip the bad chars if requested (not recommended).
    if ($strip && function_exists('iconv')) {
        return iconv('utf-8', 'utf-8', $string);
    }

    return '';
}

/**
 * Properly strip all HTML tags including script and style
 *
 * This differs from strip_tags() because it removes the contents of
 * the `<script>` and `<style>` tags. E.g. `strip_tags( '<script>something</script>' )`
 * will return 'something'. _strip_all_tags will return ''
 *
 * @param string $string String containing HTML tags
 * @param bool $remove_breaks Optional. Whether to remove left over line breaks and white space chars
 * @return string The processed string.
 */
function _strip_all_tags($string, $remove_breaks = false)
{
    $string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
    $string = strip_tags($string);

    if ($remove_breaks) {
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
    }

    return trim($string);
}

/**
 * 清除用户输入或数据库中的字符串
 *
 * @access private
 *
 * @param string $str String to sanitize.
 * @param bool $keep_newlines Optional. Whether to keep newlines. Default: false.
 * @return string Sanitized string.
 */
function _sanitize_text_field($str, $keep_newlines = false)
{
    if (is_object($str) || is_array($str)) {
        return '';
    }

    $str = (string)$str;

    $filtered = check_invalid_utf8($str);

    if (strpos($filtered, '<') !== false) {
        if (false === strpos($filtered, '>')) {
            $filtered = _wp_specialchars($filtered);
        }
        // This will strip extra whitespace for us.
        $filtered = _strip_all_tags($filtered, false);

        // Use HTML entities in a special case to make sure no later
        // newline stripping stage could lead to a functional tag.
        $filtered = str_replace("<\n", "&lt;\n", $filtered);
    }

    if (!$keep_newlines) {
        $filtered = preg_replace('/[\r\n\t ]+/', ' ', $filtered);
    }
    $filtered = trim($filtered);

    $found = false;
    while (preg_match('/%[a-f0-9]{2}/i', $filtered, $match)) {
        $filtered = str_replace($match[0], '', $filtered);
        $found = true;
    }

    if ($found) {
        // Strip out the whitespace that may now exist after removing the octets.
        $filtered = trim(preg_replace('/ +/', ' ', $filtered));
    }

    return $filtered;
}

// 字符串倒叙
function str_reversal($str)
{
    $len = mb_strlen($str);
    $t2 = '';
    for ($i = $len - 1; $i >= 0; $i--) {
        $t2 = $t2 . mb_substr($str, $i, 1, 'utf-8');
    }
    return $t2;
}

// 判断是否是https
function is_https($str)
{
    if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || $_SERVER['SERVER_PORT'] == 443) {
        return true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
        $protocol = 'https://';
        return true;
    } else {
        return false;
    }
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
