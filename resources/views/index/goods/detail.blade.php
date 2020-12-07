<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
<title><?php if (!empty($post->seotitle)) { echo $post->seotitle; } else { echo $post->title; } echo '-' . sysconfig('CMS_WEBNAME'); ?></title><meta name="keywords" content="<?php echo $post->keywords; ?>" /><meta name="description" content="<?php echo $post->description; ?>" /><link rel="stylesheet" href="<?php echo http_host(); ?>/css/style.css" media="all"><script type="text/javascript" src="<?php echo http_host(); ?>/js/jquery.min.js"></script><script type="text/javascript" src="<?php echo http_host(); ?>/js/public.js"></script></head><body><script>site();</script>
@include('index.common.header')<div id="top_generic_ad"><?php echo get_ad_code('top_generic_ad'); ?></div>

<div class="box mt10"><div class="fl_640">
<h1 class="arct" style="padding:20px 20px 0 20px;"><?php echo $post->title; ?> <br><span style="color:#f60;font-weight:400;">￥<?php echo $post->price; ?></span> <span style="font-size:16px;color:#aaaaaa;font-weight:400;text-decoration:line-through;">￥<?php echo $post->market_price; ?></span></h1>

<!--商品图片-start-->
<?php if ($post->goods_img_list) { ?>
<div style="padding:10px 0 10px 20px;width:360px;">
    <!-- Swiper -->
    <div class="swiper-container">
        <div class="swiper-wrapper">
        <?php foreach($post->goods_img_list as $k=>$v){ ?>
            <div class="swiper-slide"><a href="javascript:;"><img src="<?php echo $v->url; ?>" alt=""></a></div>
        <?php } ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination swiper-pagination-white"></div>
    </div>
</div>
<?php } ?>
<link rel="stylesheet" href="<?php echo http_host(); ?>/css/swiper.min.css">
<script type="text/javascript" src="<?php echo http_host(); ?>/js/swiper.min.js"></script>
<style>
.swiper-container{width:100%;height:auto;}
.swiper-slide{text-align:center;font-size:18px;background:#fff;}
.swiper-slide img{width:100%;height:360px;}
</style>
<script>
//Swiper轮播
var swiper = new Swiper('.swiper-container', {
    pagination: '.swiper-pagination',
    paginationClickable: true,
    autoHeight: true, //enable auto height
    slidesPerView: 1,
    paginationClickable: true,
    spaceBetween: 30,
    loop: true,
    centeredSlides: true,
    autoplay: 3000,
    autoplayDisableOnInteraction: false
});
</script>
<!--商品图片-end-->
<style>
.title{position: relative;margin: 25px 0 10px 20px;padding-left: 12px;font-size:20px;color: #333;border-left:2px solid #47b751;}
</style>
<h2 class="title">详细介绍</h2>
<div class="content"><?php echo $post->content; ?><div class="dad1"><?php echo get_ad_code('article_title_bottom'); ?></div></div><div class="dad3"><?php echo get_ad_code('left_ad_w640'); ?></div>
</div><!-- fl_640 end -->

<div class="fr_300"><div id="right_ad1"><?php echo get_ad_code('right_ad1'); ?></div>
<div class="side"><div class="stit"><h2>热门推荐</h2><a href="/goodslist/f1" class="more">更多</a><div class="cl"></div></div>
<div class="uli2" id="zxlist"><?php if ($relate_zuixin_list) { foreach ($relate_zuixin_list as $row) { ?><div class="suli"><?php if ($row->litpic) { ?><a class="limg" href="<?php echo route('index_goods_detail', array('id'=>$row->id)); ?>"><img alt="<?php echo $row->title; ?>" src="<?php echo $row->litpic; ?>"></a><?php } ?><a href="<?php echo route('index_goods_detail', array('id'=>$row->id)); ?>"><?php echo $row->title; ?></a><div class="sulii"><span class="time">￥<?php echo $row->price; ?></span> 人气(<?php echo $row->click; ?>)</div><div class="cl"></div></div><?php } } ?></div></div>

<div id="right_ad2"><?php echo get_ad_code('right_ad2'); ?></div>
<div class="side"><div class="stit"><h2>猜你喜欢</h2><div class="cl"></div></div>
<div class="uli2" id="xglist"><?php if ($relate_rand_list) { foreach ($relate_rand_list as $row) { ?><div class="suli"><?php if ($row->litpic) { ?><a class="limg" href="<?php echo route('index_goods_detail', array('id'=>$row->id)); ?>"><img alt="<?php echo $row->title; ?>" src="<?php echo $row->litpic; ?>"></a><?php } ?><a href="<?php echo route('index_goods_detail', array('id'=>$row->id)); ?>"><?php echo $row->title; ?></a><div class="sulii"><span class="time">￥<?php echo $row->price; ?></span> 人气(<?php echo $row->click; ?>)</div><div class="cl"></div></div><?php } } ?></div></div>

<div id="right_ad3"><?php echo get_ad_code('right_ad3'); ?></div></div><!-- fr_300 end --></div><!-- box end -->

@include('index.common.footer')</body></html>