<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"><meta http-equiv="Cache-Control" content="no-siteapp" /><meta http-equiv="Cache-Control" content="no-transform" />
<title><?php if ($post->seotitle) { echo $post->seotitle; } else { echo $post->title  . '-' . sysconfig('CMS_WEBNAME');} ?></title><meta name="keywords" content="<?php echo $post->keywords; ?>" /><meta name="description" content="<?php echo $post->description; ?>" /><link rel="stylesheet" href="<?php echo http_host(); ?>/css/style.css" media="all"><script type="text/javascript" src="<?php echo http_host(); ?>/js/public.js"></script></head><body><script>site();</script>
@include('index.common.header')<div id="top_generic_ad"><?php echo get_ad_code('top_generic_ad'); ?></div>

<div class="box mt10" style="background-color:#fff;padding:20px 0;"><h1 class="arct" style="text-align:center"><?php echo $post->title; ?></h1>
<div class="content"><?php echo $post->content; ?></div>
<!-- fr_300 end --></div><!-- box end -->@include('index.common.footer')</body></html>