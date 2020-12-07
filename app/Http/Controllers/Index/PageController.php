<?php

namespace App\Http\Controllers\Index;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\PageLogic;
use App\Http\Model\PageModel;

class PageController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new PageLogic();
    }

    //详情
    public function detail()
    {
        $id = request('id');
        if (!$id) {
            Helper::http404();
        }
        $post = cache("index_page_detail_$id");
        if (!$post) {
            $where = function ($query) use ($id) {
                $query->where('delete_time', '=', 0);
                $query->where('id', '=', $id)->orWhere('filename', '=', $id);
            };
            $post = $this->getLogic()->getOne($where);
            if (!$post) {
                Helper::http404();
            }
            cache(["index_page_detail_$id" => $post], 2592000);
        }

        $assign_data['post'] = $post;
        return view('index.page.detail', $assign_data);
    }

}