<?php

namespace App\Http\Logic;

use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\ArticleModel;
use App\Http\Requests\ArticleRequest;
use Validator;

class ArticleLogic extends BaseLogic
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModel()
    {
        return new ArticleModel();
    }

    public function getValidate($data, $scene_name)
    {
        //数据验证
        $validate = new ArticleRequest();
        return Validator::make($data, $validate->getSceneRules($scene_name), $validate->getSceneRulesMessages());
    }

    //列表
    public function getList($where = array(), $order = '', $field = '*', $offset = '', $limit = '')
    {
        $res = $this->getModel()->getList($where, $order, $field, $offset, $limit);

        if ($res['list']) {
            foreach ($res['list'] as $k => $v) {
                $res['list'][$k] = $this->getDataView($v);
            }
        }

        return $res;
    }

    //分页html
    public function getPaginate($where = array(), $order = '', $field = '*', $limit = '')
    {
        $res = $this->getModel()->getPaginate($where, $order, $field, $limit);

        if ($res->count() > 0) {
            foreach ($res as $k => $v) {
                $res[$k] = $this->getDataView($v);
            }
        }
        return $res;
    }

    //全部列表
    public function getAll($where = array(), $order = '', $field = '*', $limit = '')
    {
        $res = $this->getModel()->getAll($where, $order, $field, $limit);

        if ($res) {
            foreach ($res as $k => $v) {
                $res[$k] = $this->getDataView($v);
            }
        }
        return $res;
    }

    //详情
    public function getOne($where = array(), $field = '*')
    {
        $res = $this->getModel()->getOne($where, $field);
        if (!$res) {
            return [];
        }

        $res = $this->getDataView($res);
        $this->getModel()->setIncrement($where, 'click', 1); //点击量+1

        return $res;
    }

    //添加
    public function add($data = array(), $type = 0)
    {
        if (empty($data)) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }

        //标题最多150个字符
        if (isset($data['title']) && !empty($data['title'])) {
            $data['title'] = mb_strcut($data['title'], 0, 150, 'UTF-8');
            $data['title'] = trim($data['title']);
        }
        //SEO标题最多150个字符
        if (isset($data['seotitle']) && !empty($data['seotitle'])) {
            $data['seotitle'] = mb_strcut($data['seotitle'], 0, 150, 'UTF-8');
            $data['seotitle'] = trim($data['seotitle']);
        }
        //关键词最多60个字符
        if (isset($data['keywords']) && !empty($data['keywords'])) {
            $data['keywords'] = mb_strcut($data['keywords'], 0, 60, 'UTF-8');
            $data['keywords'] = trim($data['keywords']);
        }
        //描述最多240个字符
        if (isset($data['description']) && !empty($data['description'])) {
            $data['description'] = mb_strcut($data['description'], 0, 240, 'UTF-8');
            $data['description'] = trim($data['description']);
        }
        //添加时间、更新时间
        $time = time();
        if (!(isset($data['add_time']) && !empty($data['add_time']))) {
            $data['add_time'] = $time;
        }
        if (!(isset($data['update_time']) && !empty($data['update_time']))) {
            $data['update_time'] = $time;
        }

        $validator = $this->getValidate($data, 'add');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $data['add_time'] = $data['update_time'] = time();//添加、更新时间
        $res = $this->getModel()->add($data, $type);
        if (!$res) {
            return ReturnData::create(ReturnData::FAIL);
        }

        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    //修改
    public function edit($data, $where = array())
    {
        if (empty($data)) {
            return ReturnData::create(ReturnData::SUCCESS);
        }

        //标题最多150个字符
        if (isset($data['title']) && !empty($data['title'])) {
            $data['title'] = mb_strcut($data['title'], 0, 150, 'UTF-8');
            $data['title'] = trim($data['title']);
        }
        //SEO标题最多150个字符
        if (isset($data['seotitle']) && !empty($data['seotitle'])) {
            $data['seotitle'] = mb_strcut($data['seotitle'], 0, 150, 'UTF-8');
            $data['seotitle'] = trim($data['seotitle']);
        }
        //关键词最多60个字符
        if (isset($data['keywords']) && !empty($data['keywords'])) {
            $data['keywords'] = mb_strcut($data['keywords'], 0, 60, 'UTF-8');
            $data['keywords'] = trim($data['keywords']);
        }
        //描述最多240个字符
        if (isset($data['description']) && !empty($data['description'])) {
            $data['description'] = mb_strcut($data['description'], 0, 240, 'UTF-8');
            $data['description'] = trim($data['description']);
        }
        //更新时间
        if (!(isset($data['update_time']) && !empty($data['update_time']))) {
            $data['update_time'] = time();
        }

        $validator = $this->getValidate($data, 'edit');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $res = $this->getModel()->edit($data, $where);
        if (!$res) {
            return ReturnData::create(ReturnData::FAIL);
        }

        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    //删除
    public function del($where)
    {
        if (empty($where)) {
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }

        $validator = $this->getValidate($where, 'del');
        if ($validator->fails()) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        $record = $this->getModel()->getOne($where);
        if (!$record) {
            return ReturnData::create(ReturnData::RECORD_NOT_EXIST);
        }

        $res = $this->getModel()->del($where);
        if (!$res) {
            return ReturnData::create(ReturnData::FAIL);
        }
        return ReturnData::create(ReturnData::SUCCESS, $res);
    }

    /**
     * 数据获取器
     * @param array $data 要转化的数据
     * @return array
     */
    private function getDataView($data = array())
    {
        return getDataAttr($this->getModel(), $data);
    }

    // 获取文章内容的第一张图片，并缩略图保存
    public function getBodyFirstPic($content)
    {
        $res = '';
        $imagepath = $this->getfirstpic($content);
        if ($imagepath) {
            // 获取文章内容的第一张图片
            $imagepath = '.' . $imagepath;

            // 获取后缀名
            preg_match_all("/\/(.+)\.(gif|jpg|jpeg|bmp|png)$/iU", $imagepath, $out, PREG_PATTERN_ORDER);

            $saveimage = './uploads/' . date('Y/m', time()) . '/' . basename($imagepath, '.' . $out[2][0]) . '-lp.' . $out[2][0];
            //判断目录是否存在
            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . date('Y/m', time()))) {
                Helper::createDir($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . date('Y/m', time()));
            }

            // //生成缩略图，按照原图的比例生成一个最大为240*180的缩略图
            \Intervention\Image\Facades\Image::make($imagepath)->resize(sysconfig('CMS_IMGWIDTH'), sysconfig('CMS_IMGHEIGHT'))->save($saveimage);
            // 缩略图路径
            $res = '/uploads/' . date('Y/m', time()) . '/' . basename($imagepath, '.' . $out[2][0]) . '-lp.' . $out[2][0];
        }

        return $res;
    }

    // 按二维数组的某一字段长度排序
    public function arrStringLenSort($arr, $field)
    {
        $res = [];
        foreach ($arr as $key => $value) {
            $arr[$key]['len'] = strlen($value[$field]);
        }

        $len_arr = array_column($arr, 'len');
        array_multisort($len_arr, SORT_ASC, $arr);

        return $arr;
    }

    /**
     * 为文章内容添加内链, 排除alt title <a></a>直接的字符替换
     *
     * @param string $body
     * @return string
     */
    public function replaceKeyword($body)
    {
        //暂时屏蔽超链接
        $body = preg_replace("#(<a(.*))(>)(.*)(<)(\/a>)#isU", '\\1-]-\\4-[-\\6', $body);
        $body = preg_replace_callback("/title=\"(.*)\"/isU", function ($matches) {
            return 'title="' . urlencode($matches[1]) . '"';
        }, $body);
        $body = preg_replace_callback("/alt=\"(.*)\"/isU", function ($matches) {
            return 'alt="' . urlencode($matches[1]) . '"';
        }, $body);

        $posts = cache("keyword_list", null);
        if (!$posts) {
            $posts = object_to_array(model("keyword")->getAll());
            cache(["keyword_list" => $posts], 2592000);
        }
        if (!$posts) {
            return $body;
        }
        $body = str_replace('\"', '"', $body);

        $posts = $this->arrStringLenSort($posts, 'name');

        foreach ($posts as $key => $value) {
            $body = preg_replace("#" . preg_quote($value['name']) . "#isU", '<a href="' . $value['url'] . '"><u>' . $value['name'] . '</u></a>', $body, 1);
        }

        //恢复超链接
        $body = preg_replace("#(<a(.*))-\]-(.*)-\[-(\/a>)#isU", '\\1>\\3<\\4', $body);
        $body = preg_replace_callback("/title=\"(.*)\"/isU", function ($matches) {
            return 'title="' . urldecode($matches[1]) . '"';
        }, $body);
        $body = preg_replace_callback("/alt=\"(.*)\"/isU", function ($matches) {
            return 'alt="' . urldecode($matches[1]) . '"';
        }, $body);
        return $body;
    }

    /**
     * 删除非站内链接
     *
     * @access public
     * @param  string $body 内容
     * @param  array $allow_urls 允许的超链接
     * @return string
     */
    public function replacelinks($body, $allow_urls = array())
    {
        $host_rule = join('|', $allow_urls);
        $host_rule = preg_replace("#[\n\r]#", '', $host_rule);
        $host_rule = str_replace('.', "\\.", $host_rule);
        $host_rule = str_replace('/', "\\/", $host_rule);
        $arr = '';

        preg_match_all("#<a([^>]*)>(.*)<\/a>#iU", $body, $arr);

        if (is_array($arr[0])) {
            $rparr = array();
            $tgarr = array();

            foreach ($arr[0] as $i => $v) {
                if ($host_rule != '' && preg_match('#' . $host_rule . '#i', $arr[1][$i])) {
                    continue;
                } else {
                    $rparr[] = $v;
                    $tgarr[] = $arr[2][$i];
                }
            }

            if (!empty($rparr)) {
                $body = str_replace($rparr, $tgarr, $body);
            }
        }
        $arr = $rparr = $tgarr = '';
        return $body;
    }

    /**
     * 获取文本中首张图片地址
     * @param  [type] $content
     * @return [type]
     */
    public function getfirstpic($content)
    {
        if (preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $content, $matches)) {
            $file = $_SERVER['DOCUMENT_ROOT'] . $matches[3][0];

            if (file_exists($file)) {
                return $matches[3][0];
            }
        }

        return false;
    }

    /**
     * 字符串转成数组
     * @param  [string] $key
     * @return [array]
     */
    public function getArrByString($key)
    {
        $res = array();

        if (!$key) {
            return $res;
        }

        preg_match_all('/[a-z]+/u', $key, $letter);
        preg_match_all('/[0-9]+/u', $key, $number);
        if (count($letter[0]) != count($number[0])) {
            return [];
        }

        foreach ($letter[0] as $k => $v) {
            $res[$v] = $number[0][$k];
        }

        return $res;
    }

    /**
     * 网址推送给百度
     * @param  [array] $urls 要推送的链接
     * @return [array]
     */
    public function push_url_to_baidu_search_engine($urls, $token = '43NVPDOFqd1wlkWy')
    {
        /* $urls = array(
            'http://www.example.com/1.html',
            'http://www.example.com/2.html',
        ); */
        if (empty($urls)) {
            return ReturnData::create(ReturnData::FAIL, null, '参数错误');
        }

        foreach ($urls as $row) {
            $one_url = array();
            $one_url[] = $row;
            $parse_url = parse_url($row); //解析 URL
            $api_url = 'http://data.zz.baidu.com/urls?site=' . $parse_url['host'] . '&token=' . $token;
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $api_url,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => implode("\n", $one_url),
                CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
            );
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
        }

        $result = json_decode($result, true);
        if (isset($result['success']) && $result['success'] > 0) {
            return ReturnData::create(ReturnData::SUCCESS, $result);
        }

        return ReturnData::create(ReturnData::FAIL, null, $result['message']);
    }

}