<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use Illuminate\Http\Request;
use App\Http\Logic\ArticleLogic;
use App\Http\Model\ArticleModel;
use App\Http\Model\TagModel;

class ArticleController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new ArticleLogic();
    }

    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('title', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            if (isset($_REQUEST["type_id"]) && $_REQUEST["type_id"] > 0) {
                $query->where('type_id', $_REQUEST["type_id"]);
            }
            if (isset($_REQUEST["status"])) {
                $query->where('status', $_REQUEST["status"]); //未审核过的文章
            } else {
                $query->where('status', 0);
            }
        };

        $list = $this->getLogic()->getPaginate($where, array('update_time', 'desc'), ArticleModel::$common_field);
        //分类列表
        $article_type_list = model('ArticleType')->tree_to_list(model('ArticleType')->list_to_tree());
        $assign_data['article_type_list'] = $article_type_list;

        $assign_data['list'] = $list;

        return view('admin.article.index', $assign_data);
    }

    public function add()
    {
        if (Helper::isPostRequest()) {
            //缩略图
            $litpic = "";
            if (!empty($_POST["litpic"])) {
                $litpic = $_POST["litpic"];
            } else {
                $_POST['litpic'] = "";
            }
            //description
            if (empty($_POST["description"])) {
                if (!empty($_POST["content"])) {
                    $_POST['description'] = cut_str($_POST["content"]);
                }
            }
            $content = "";
            if (!empty($_POST["content"])) {
                $content = $_POST["content"];
            }

            $update_time = time();
            if ($_POST['update_time']) {
                $update_time = strtotime($_POST['update_time']);
            } // 更新时间
            $_POST['add_time'] = $_POST['update_time'] = $update_time;
            $_POST['admin_id'] = $this->admin_info['id']; // 管理员发布者ID

            //关键词
            if (!empty($_POST["keywords"])) {
                $_POST['keywords'] = str_replace("，", ",", $_POST["keywords"]);
            } else {
                if (!empty($_POST["title"])) {
                    $title = $_POST["title"];
                    $title = str_replace("，", "", $title);
                    $title = str_replace(",", "", $title);
                    $_POST['keywords'] = get_participle($title); // 标题分词
                }
            }

            if (isset($_POST['keywords']) && !empty($_POST['keywords'])) {
                $_POST['keywords'] = mb_strcut($_POST['keywords'], 0, 60, 'UTF-8');
            }
            if (isset($_POST["dellink"]) && $_POST["dellink"] == 1 && !empty($content)) {
                $content = logic('Article')->replacelinks($content, array(http_host()));
            } //删除非站内链接
            $_POST['content'] = $content;

            // 提取第一个图片为缩略图
            if (isset($_POST["autolitpic"]) && $_POST["autolitpic"] && empty($litpic)) {
                $litpic = logic('Article')->getBodyFirstPic($content);
                if ($litpic) {
                    $_POST['litpic'] = $litpic;
                }
            }

            $res = $this->getLogic()->add($_POST);
            if ($res['code'] == ReturnData::SUCCESS) {
                //Tag添加
                if (isset($_POST['tags']) && $_POST["tags"] != '') {
                    $tags = $_POST['tags'];
                    $tags = explode(',', str_replace('，', ',', $tags));
                    foreach ($tags as $row) {
                        $tag_id = model('Tag')->getValue(array('name' => $row), 'id');
                        if ($tag_id) {
                            $data2['tag_id'] = $tag_id;
                            $data2['article_id'] = $res['data'];
                            logic('Taglist')->add($data2);
                        }
                    }
                }

                success_jump($res['msg'], route('admin_article'));
            }

            error_jump($res['msg']);
        }

        //文章添加到哪个栏目下
        $assign_data['type_id'] = request('type_id', 0);

        //栏目列表
        $article_type_list = model('ArticleType')->tree_to_list(model('ArticleType')->list_to_tree());
        $assign_data['article_type_list'] = $article_type_list;

        return view('admin.article.add', $assign_data);
    }

    public function edit()
    {
        if (Helper::isPostRequest()) {
            $id = $where['id'] = $_POST['id'];
            unset($_POST['id']);

            $litpic = "";
            if (!empty($_POST["litpic"])) {
                $litpic = $_POST["litpic"];
            } else {
                $_POST['litpic'] = "";
            } //缩略图
            if (empty($_POST["description"])) {
                if (!empty($_POST["content"])) {
                    $_POST['description'] = cut_str($_POST["content"]);
                }
            } //description
            $content = "";
            if (!empty($_POST["content"])) {
                $content = $_POST["content"];
            }

            $update_time = time();
            if ($_POST['update_time']) {
                $update_time = $_POST['add_time'] = strtotime($_POST['update_time']);
            } // 更新时间
            $_POST['update_time'] = $update_time;

            //关键词
            if (!empty($_POST["keywords"])) {
                $_POST['keywords'] = str_replace("，", ",", $_POST["keywords"]);
            } else {
                if (!empty($_POST["title"])) {
                    $title = $_POST["title"];
                    $title = str_replace("，", "", $title);
                    $title = str_replace(",", "", $title);
                    $_POST['keywords'] = get_participle($title); // 标题分词
                }
            }

            if (isset($_POST['keywords']) && !empty($_POST['keywords'])) {
                $_POST['keywords'] = mb_strcut($_POST['keywords'], 0, 60, 'UTF-8');
            }
            if (isset($_POST["dellink"]) && $_POST["dellink"] == 1 && !empty($content)) {
                $content = logic('Article')->replacelinks($content, array(http_host()));
            } //删除非站内链接
            $_POST['content'] = $content;

            // 提取第一个图片为缩略图
            if (isset($_POST["autolitpic"]) && $_POST["autolitpic"] && empty($litpic)) {
                $litpic = $this->getLogic()->getBodyFirstPic($content);
                if ($litpic) {
                    $_POST['litpic'] = $litpic;
                }
            }

            $res = $this->getLogic()->edit($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }

            //Tag添加
            if (isset($_POST['tags']) && $_POST["tags"] != '') {
                $tags = $_POST['tags'];
                $tags = explode(',', str_replace('，', ',', $tags));
                model('Taglist')->del(array('article_id' => $id));
                foreach ($tags as $row) {
                    $tag_id = model('Tag')->getValue(array('name' => $row), 'id');
                    if ($tag_id) {
                        $data2['tag_id'] = $tag_id;
                        $data2['article_id'] = $id;
                        logic('Taglist')->add($data2);
                    }
                }
            }

            success_jump($res['msg'], route('admin_article'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $assign_data['id'] = $where['id'] = request('id');

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        //Tag标签
        $tags = null;
        $taglist = model('Taglist')->getAll(['article_id' => $where['id']]);
        if ($taglist) {
            foreach ($taglist as $k => $v) {
                $tmp[] = model('Tag')->getValue(['id' => $v->tag_id], 'name');
            }
            if (!empty($tmp)) {
                $tags = implode(',', $tmp);
            }
        }
        $assign_data['tags'] = $tags;

        //栏目列表
        $article_type_list = model('ArticleType')->tree_to_list(model('ArticleType')->list_to_tree());
        $assign_data['article_type_list'] = $article_type_list;

        return view('admin.article.edit', $assign_data);
    }

    //删除文章
    public function del()
    {
        if (!empty($_GET["id"])) {
            $id = $_GET["id"];
        } else {
            error_jump("删除失败！请重新提交");
        }

        if (!DB::table("article")->whereIn("id", explode(',', $id))->delete()) {
            error_jump("$id ,删除失败！请重新提交");
        }
        success_jump("$id ,删除成功");
    }


    //重复文章列表
    public function repetarc()
    {
        $data['list'] = DB::table('article')->select(DB::raw('title,count(*) AS count'))->orderBy('count', 'desc')->groupBy('title')->having('count', '>', 1)->get();

        return view('admin.article.repetarc', $data);
    }

    //推荐文章
    public function recommendarc()
    {
        if (!empty($_GET["id"])) {
            $id = $_GET["id"];
        } else {
            error_jump("您访问的页面不存在或已被删除");
        } //if(preg_match('/[0-9]*/',$id)){}else{exit;}

        $data['tuijian'] = 1;

        if (!DB::table("article")->whereIn("id", explode(',', $id))->update($data)) {
            error_jump("$id ,推荐失败！请重新提交");
        }
        success_jump("$id ,推荐成功");
    }

    //检测重复文章数量
    public function articleexists()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["title"])) {
                $query->where('title', $_REQUEST["title"]);
            }

            if (isset($_REQUEST["id"])) {
                $query->where('id', '<>', $_REQUEST["id"]);
            }
        };

        return DB::table("article")->where($where)->count();
    }
}