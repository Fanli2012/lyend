<?php

namespace App\Http\Controllers\Index;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Controllers\Controller;

class MailController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // 发送邮件
        \Illuminate\Support\Facades\Mail::send('emails.test', ['name' => '范例'], function ($message) {
            $to = '277023115@qq.com';
            $message->to($to)->subject('邮件测试');
        });
        // 返回的一个错误数组，利用此可以判断是否发送成功
        if (count(Mail::failures()) < 1) {
            echo '发送邮件成功，请查收';
        } else {
            echo '发送邮件失败，请重试';
        }

        // 发送纯文本邮件
        \Illuminate\Support\Facades\Mail::raw('你好，我是PHP程序！对活动而言，最重要的是曝光量，只有更多的分享与传播，才能让更多的人参与进来，活动效果才越好，对技术上的要求就是能够提供尽可能好的用户体验。', function ($message) {
            $to = '277023115@qq.com';
            $message->to($to)->subject('纯文本信息邮件测试');
        });
    }

}