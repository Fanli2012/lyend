<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
<title>搜索结果</title><meta name="keywords" content="搜索结果" /><meta name="description" content="搜索结果" /><link rel="stylesheet" href="<?php echo http_host(); ?>/css/style.css" media="all"><script type="text/javascript" src="<?php echo http_host(); ?>/js/jquery.min.js"></script><script type="text/javascript" src="<?php echo http_host(); ?>/js/public.js"></script></head><body><script>site();</script>
@include('index.common.header')<div id="top_generic_ad"><?php echo get_ad_code('top_generic_ad'); ?></div>

<div class="box mt10"><div class="fl_640">
<?php if ($list['data']) { foreach ($list['data'] as $row) { ?><div class="list"><?php if (!empty($row->litpic)) { ?><a class="limg" href="<?php echo route('index_article_detail', array('id'=>$row->id)); ?>"><img alt="<?php echo $row->title; ?>" src="<?php echo $row->litpic; ?>"></a><?php } ?>
<strong class="tit"><a href="<?php echo route('index_article_detail', array('id'=>$row->id)); ?>"><?php echo $row->title; ?></a></strong><p><?php echo mb_strcut($row->description,0,150,'UTF-8'); ?>..</p>
<div class="info"><span class="fl"><em><?php echo date("m-d H:i", $row->update_time); ?></em></span><span class="fr"><em><?php echo $row->click; ?></em>人阅读</span></div><div class="cl"></div></div><?php } } else { echo '<div style="text-align:center;padding:20px;">暂无记录</div>'; } ?><div id="list_left_ad2"><?php echo get_ad_code('list_left_ad2'); ?></div>

<div class="bootstrap-front-pagination"><?php echo $page; ?></div></div><!-- fl_640 end -->

<div class="fr_300"><div id="right_ad1"><?php echo get_ad_code('right_ad1'); ?></div>
<div class="side"><div class="stit"><h2>热门推荐</h2><a href="javascript:getmore({limit:5,tuijian:1,mode:1,orderby:'rand()'});" class="more">换一换</a><div class="cl"></div></div>	
<ul class="uli chs" id="xglist"><?php if ($relate_tuijian_list) { foreach ($relate_tuijian_list as $row) { ?><li><a href="<?php echo route('index_article_detail', array('id'=>$row->id)); ?>"><?php echo $row->title; ?></a></li><?php } } ?></ul><div class="cl"></div></div>

<div id="right_ad2"><?php echo get_ad_code('right_ad2'); ?></div>
<div class="side"><div class="stit"><h2>猜你喜欢</h2><a href="javascript:getmore({limit:5,mode:2,orderby:'rand()'});" class="more">换一换</a><div class="cl"></div></div>
<div class="uli2" id="xglike"><?php if ($relate_zuixin_list) { foreach ($relate_zuixin_list as $row) { ?><div class="suli"><?php if (!empty($row->litpic)) { ?><a class="limg" href="<?php echo route('index_article_detail', array('id'=>$row->id)); ?>"><img alt="<?php echo $row->title; ?>" src="<?php echo $row->litpic; ?>"></a><?php } ?><a href="<?php echo route('index_article_detail', array('id'=>$row->id)); ?>"><?php echo $row->title; ?></a><div class="sulii"><?php if (!empty($row->writer)) { echo '<span class="time">' . $row->writer . '</span>'; } elseif (!empty($row->source)) { echo '<span class="time">' . $row->source . '</span>';} ?> 阅读(<?php echo $row->click; ?>)</div><div class="cl"></div></div><?php } } ?></div></div>

<div id="right_ad3"><?php echo get_ad_code('right_ad3'); ?></div></div><!-- fr_300 end --></div><!-- box end -->
<script>
function getmore(condition)
{
    var url = "<?php echo route('api_article_index'); ?>";
    //var typeid = "";
    $.post(url,condition,function(res){
        if(res.code==0)
        {
            var json = res.data.list; //数组
            var str = '';
            $.each(json, function (index) {
                //循环获取数据
                //var title = json[index].title;
                if(condition.mode==1)
                {
                    str = str + '<li><a href="<?php echo rtrim(route('index_article_detail', array('id'=>1)), "1"); ?>'+json[index].id+'">'+json[index].title+'</a></li>';
                }
                else if(condition.mode==2)
                {
                    var litpic = '';if(json[index].litpic!==''){litpic = '<a class="limg" href="<?php echo rtrim(route('index_article_detail', array('id'=>1)), "1"); ?>'+json[index].id+'"><img alt="'+json[index].title+'" src="'+json[index].litpic+'"></a>';}
                    str = str + '<div class="suli">'+litpic+'<a href="<?php echo rtrim(route('index_article_detail', array('id'=>1)), "1"); ?>'+json[index].id+'">'+json[index].title+'</a><div class="sulii">阅读('+json[index].click+')</div><div class="cl"></div></div>';
                }
            });
            
            if(str!='' && str!=null && condition.mode==1)
            {
                $('#xglist').html(str);
            }
            else if(str!='' && str!=null && condition.mode==2)
            {
                $('#xglike').html(str);
            }
        }
        else
        {
            
        }
    },'json');
}
</script>
@include('index.common.footer')</body></html>