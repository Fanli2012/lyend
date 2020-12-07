<?php

namespace App\Http\Controllers\Index;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Logic\AdLogic;
use App\Http\Model\AdModel;

class AdController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new AdLogic();
    }

    //详情
    public function detail()
    {
        $id = request('id');
        $where = function ($query) use ($id) {
            $query->where('id', '=', $id)->orWhere('flag', '=', $id);
        };
		
        $post = cache("index_ad_detail_$id");
        if (!$post) {
            $time = time();
            $post = $this->getLogic()->getOne($where);
            if (!$post) {
                exit('not found');
            }
            if ($post->is_expire == 1 && $post->end_time < $time) {
                exit('expired');
            }
            cache(["index_ad_detail_$id" => $post], 2592000);
        }

        $assign_data['post'] = $post;
        if (Helper::is_mobile_access()) {
            if ($post->content_wap) {
                exit($post->content_wap);
            }
        }
        exit($post->content);
    }

}