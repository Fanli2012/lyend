@extends('admin.layouts.app')
@section('title', '首页')

@section('content')
<div class="layui-fluid" style="padding:15px;">
<h1><?php echo sysconfig('CMS_WEBNAME'); ?></h1>
<p>· 欢迎使用<?php echo sysconfig('CMS_WEBNAME'); ?>，简单易用靠谱。<br>
· 采用PHP+Mysql架构，符合网站SEO优化理念、功能全面、安全稳定。</p><br>

<h2>网站基本信息</h2>
域名/IP：<?php echo $_SERVER["SERVER_NAME"]; ?> | <?php echo $_SERVER["REMOTE_ADDR"]; ?><br><br>
我们的联系方式：277023115@qq.com<br><br>
&copy; <?php echo sysconfig('CMS_WEBNAME'); ?> 版权所有
</div>
@endsection