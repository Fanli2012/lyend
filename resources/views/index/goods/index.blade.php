<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"><meta http-equiv="Cache-Control" content="no-siteapp" /><meta http-equiv="Cache-Control" content="no-transform" />
<title><?php echo $title; if ($list['current_page'] != 1) { echo '-第' . $list['current_page'] . '页'; } ?></title><meta name="keywords" content="<?php echo $keywords; ?>" /><meta name="description" content="<?php echo $description; ?>" /><link rel="stylesheet" href="<?php echo http_host(); ?>/css/style.css" media="all"><script type="text/javascript" src="<?php echo http_host(); ?>/js/jquery.min.js"></script><script type="text/javascript" src="<?php echo http_host(); ?>/js/public.js"></script></head><body><script>site();</script>
@include('index.common.header')<div id="top_generic_ad"><?php echo get_ad_code('top_generic_ad'); ?></div>

<div class="box mt10">
<?php if ($list['data']) { ?><ul class="goodsul" id="goods_list">
<?php foreach ($list['data'] as $k=>$v) { ?>
<li><a class="wrap" href="<?php echo route('index_goods_detail', array('id'=>$v->id)); ?>"><img src="<?php echo $v->litpic; ?>" alt="<?php echo $v->title; ?>">
<p class="title"><?php echo $v->title; ?></p>
<p class="desc"><?php echo $v->description; ?></p>
<div class="item-prices green"><div class="item-link">立即<br>抢购</div><div class="item-info"><div class="price"><i>¥</i><em class="J_actPrice"><span class="yen"><?php echo ceil($v->price); ?></span></em></div>
<div class="dock"><div class="dock-price"><del class="orig-price">¥<?php echo $v->market_price; ?></del></div><div class="prompt"><div class="sold-num"><em><?php echo $v->click; ?></em> 人气</div></div></div>
</div></div>
</a></li>
<?php } ?><div class="cl"></div></ul><?php } else { ?><div style="text-align:center;padding:20px;">暂无记录</div><?php } ?>

<div class="bootstrap-front-pagination"><?php echo $page; ?><div class="cl"></div></div>
</div><!-- box end -->

@include('index.common.footer')</body></html>