<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>搜索结果_<?php echo sysconfig('CMS_WEBNAME'); ?></title>
<link rel="stylesheet" href="/css/bootstrap.min.css"><link rel="stylesheet" href="/css/style.css"><script src="/js/jquery.min.js"></script><script src="/js/bootstrap.min.js"></script></head><body>

@include('home.common.header')
<div class="container">
<div class="row row-offcanvas row-offcanvas-right">
<div class="col-xs-12 col-sm-9">
<div class="bread"><a href="/"><?php echo sysconfig('CMS_INDEXNAME'); ?></a> > <?php echo '搜索结果'; ?></div>
<h1 class="page-header">搜索结果</h1>

<?php if(!empty($posts)){foreach($posts as $row){ ?><div class="list"><?php if(!empty($row['litpic'])){ ?><a class="limg" href="<?php echo get_front_url(array("id"=>$row['id'],"catid"=>$row['typeid'],"type"=>'content')); ?>"><img alt="<?php echo $row['title']; ?>" src="<?php echo $row['litpic']; ?>"></a><?php } ?>
<strong class="tit"><a href="<?php echo get_front_url(array("id"=>$row['id'],"catid"=>$row['typeid'],"type"=>'content')); ?>"><?php echo $row['title']; ?></a></strong><p><?php echo mb_strcut($row['description'],0,150,'UTF-8'); ?>..</p>
<div class="info"><span class="fl"><?php $taglist=taglist($row['id']);if($taglist){foreach($taglist as $row){ ?><a href="<?php echo get_front_url(array("tagid"=>$row['id'],"type"=>'tags')); ?>"><?php echo $row['tag']; ?></a><?php }} ?><em><?php echo date("m-d H:i",$row['pubdate']); ?></em></span><span class="fr"><em><?php echo $row['click']; ?></em>人阅读</span></div><div class="cl"></div></div><?php }} ?><div id="lad2"><script>ljs2();</script></div>
</div><!--/.col-xs-12.col-sm-9-->

<div class="col-xs-12 col-sm-3 sidebar-offcanvas" id="sidebar"><div class="panel panel-info">
  <div class="panel-heading">热门推荐</div>
  <div class="list-group"><?php $posts=arclist(array("row"=>5,"orderby"=>'rand()'));if($posts){foreach($posts as $row){ ?>
  <a class="list-group-item" href="<?php echo get_front_url(array("id"=>$row['id'],"catid"=>$row['typeid'],"type"=>'content')); ?>"><?php echo $row['title']; ?></a><?php }} ?>
  </div>
</div></div><!--/.sidebar-offcanvas--></div><!--/row-->

</div><!-- /.container -->
@include('home.common.footer')</body></html>