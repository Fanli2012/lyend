<div class="topbar">
    <div class="box">
        <div class="sitebar_fl fl">
            <div class="login_before">
            <a href="<?php echo route('index_page_detail', array('id'=>'jianjie')); ?>" rel="nofollow">公司简介</a> | <span>收藏本网页请按Ctrl+D</span></div>
        </div>
        <div class="sitebar_fr fr">
            &nbsp;<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=277023115&amp;site=qq&amp;menu=yes" rel="nofollow">QQ咨询</a>&nbsp;<a href="<?php echo route('index_page_detail', array('id'=>'contact')); ?>" rel="nofollow">联系我们</a>
        </div>
        <div class="cl"></div>
    </div>
</div>

<div class="headertop">
    <div class="box">
        <div class="left">
            <a href="<?php echo http_host(); ?>/"><img class="logo-headerimg" style="display:block;" src="<?php echo http_host(); ?>/images/logo-header.png" width="175" height="84" alt="<?php echo sysconfig('CMS_WEBNAME'); ?>"></a>
        </div>
         <div class="center">
            <form action="<?php echo route('index_search_detail'); ?>" method="get" name="sch-form" target="_blank">
                <div class="sch-contain-box"><input autocomplete="off" type="text" name="keyword" class="sch-text" placeholder="请输入关键词" value=""></div>
                <input type="submit" class="sch-btn" value="搜索">
                <div class="cl"></div>
            </form>
        </div>
        <div class="cl"></div>
    </div>
</div>

<div id="header"><div id="navlink" class="box"><a class="webname" href="<?php echo http_host(); ?>/"><?php echo sysconfig('CMS_WEBNAME'); ?></a><span class="nav"><a <?php if (url()->full() == route('index_article_index_key', array('key' => 'f1'))) { echo 'class="current" '; } ?>href="<?php echo route('index_article_index_key', array('key' => 'f1')); ?>">企业新闻</a><a <?php if (url()->full() == route('index_goods_index_key', array('key' => 'f1'))) { echo 'class="current" '; } ?>href="<?php echo route('index_goods_index_key', array('key' => 'f1')); ?>">产品中心</a><a <?php if (url()->full() == route('index_article_index_key', array('key' => 'f2'))) { echo 'class="current" '; } ?>href="<?php echo route('index_article_index_key', array('key' => 'f2')); ?>">案例中心</a><a <?php if (request()->url() == route('index_page_detail', array('id'=>'about'))) { echo 'class="current" '; } ?>href="<?php echo route('index_page_detail', array('id'=>'about')); ?>">关于我们</a><script>navjs();</script></span><div class="cl"></div></div></div>
