@extends('admin.layouts.app')
@section('title', '商品信息修改')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_goods'); ?>">商品列表</a><span lay-separator="">/</span>
    <a href=""><cite>商品修改</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <input style="display:none;" name="id" type="text" id="id" value="<?php echo $id; ?>">
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>标题
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input autocomplete="off" id="title" name="title" value="<?php echo $post->title; ?>" lay-verify="required" placeholder="在此输入标题" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-inline">
      <label class="layui-form-label"><font color="red">*</font>货号</label>
      <div class="layui-input-inline">
        <input name="sn" type="text" id="sn" value="<?php echo $post->sn; ?>" lay-verify="required" class="layui-input">
      </div>
      </div>
      <div class="layui-inline">
      <label class="layui-form-label">运费
      </label>
      <div class="layui-input-inline">
        <input name="shipping_fee" type="text" id="shipping_fee" value="<?php echo $post->shipping_fee; ?>" class="layui-input">
      </div>
      </div>
      <div class="layui-inline">
      <label class="layui-form-label">销量
      </label>
      <div class="layui-input-inline">
        <input id="sale" name="sale" type="text" value="<?php echo $post->sale; ?>" class="layui-input">
      </div>
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-inline">
      <label class="layui-form-label"><font color="red">*</font>商品价格</label>
      <div class="layui-input-inline">
        <input name="price" type="text" id="price" value="<?php echo $post->price; ?>" lay-verify="required" class="layui-input">
      </div>
      </div>
      <div class="layui-inline">
      <label class="layui-form-label"><font color="red">*</font>原价
      </label>
      <div class="layui-input-inline">
        <input name="market_price" type="text" id="market_price" value="<?php echo $post->market_price; ?>" class="layui-input">
      </div>
      </div>
      <div class="layui-inline">
      <label class="layui-form-label"><font color="red">*</font>库存
      </label>
      <div class="layui-input-inline">
        <input id="goods_number" name="goods_number" type="text" value="<?php echo $post->goods_number; ?>" class="layui-input">
      </div>
      </div>
      <div class="layui-inline">
      <label class="layui-form-label">浏览次数
      </label>
      <div class="layui-input-inline">
        <input id="click" name="click" type="text" value="<?php echo $post->click; ?>" class="layui-input">
      </div>
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">上架</label>
        <div class="layui-input-inline">
          <input type="radio" value="0" name="status" <?php if ($post->status == 0) { echo 'checked'; } ?> title="是" />
          <input type="radio" value="2" name="status" <?php if ($post->status == 2) { echo 'checked'; } ?> title="否" />
        </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-inline">
      <label class="layui-form-label">活动价
      </label>
      <div class="layui-input-inline">
        <input name="promote_price" type="text" id="promote_price" value="<?php if ($post->promote_price > 0) { echo $post->promote_price; } ?>" lay-verify="" class="layui-input">
      </div>
      </div>
      <div class="layui-inline">
      <label class="layui-form-label">活动开始日期
      </label>
      <div class="layui-input-inline">
        <input name="promote_start_date" type="text" id="promote_start_date" value="<?php echo $post->promote_start_date; ?>" class="layui-input">
      </div>
      </div>
      <div class="layui-inline">
      <label class="layui-form-label">活动结束日期
      </label>
      <div class="layui-input-inline">
        <input id="promote_end_date" name="promote_end_date" type="text" value="<?php echo $post->promote_end_date; ?>" class="layui-input">
      </div>
      </div>
    </div>
    <div class="layui-form-item">
    <label class="layui-form-label">推荐</label>
    <div class="layui-input-inline">
        <select name="tuijian" id="tuijian">
            <?php $tuijian = config('custom.tuijian');
            for ($i=0;$i<count($tuijian);$i++) { if ($i == $post->tuijian) {?><option selected="selected" value="<?php echo $i; ?>"><?php echo $tuijian[$i]; ?></option>
            <?php } else { ?><option value="<?php echo $i; ?>"><?php echo $tuijian[$i]; ?></option><?php } } ?>
        </select>
    </div>
  </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>缩略图
      </label>
      <div class="layui-input-inline" style="width:400px;">
        <input name="litpic" type="text" id="litpic" value="<?php echo $post->litpic; ?>" lay-verify="required" class="layui-input">
      </div>
      <div class="layui-input-inline">
        <input type="button" class="layui-btn" onclick="upImage();" value="选择图片">
      </div>
      <div class="layui-input-inline">
        <img style="margin-left:20px;<?php if(empty($post->litpic) || !is_image_format($post->litpic)){ echo "display:none;"; } ?>" src="<?php if (is_image_format($post->litpic)) {echo $post->litpic;} ?>" width="120" height="80" id="picview" name="picview">
      </div>
    </div>
<script type="text/javascript">
var _editor;
$(function() {
    //重新实例化一个编辑器，防止在上面的editor编辑器中显示上传的图片或者文件
    _editor = UE.getEditor('ueditorimg');
    _editor.ready(function () {
        //设置编辑器不可用
        _editor.setDisabled('insertimage');
        //隐藏编辑器，因为不会用到这个编辑器实例，所以要隐藏
        _editor.hide();
        //侦听图片上传
        _editor.addListener('beforeInsertImage', function (t, arg) {
            //将地址赋值给相应的input,只取第一张图片的路径
			$('#litpic').val(arg[0].src);
            //图片预览
            $('#picview').attr("src",arg[0].src).css("display","inline-block");
        })
    });
});
//弹出图片上传的对话框
function upImage()
{
    var myImage = _editor.getDialog("insertimage");
	myImage.render();
    myImage.open();
}
</script>
<script type="text/plain" id="ueditorimg"></script>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>商品图片
      </label>
      <div class="layui-input-inline">
        <input type="button" class="layui-btn" onclick="upImage2();" value="选择图片">
      </div>
      <div class="layui-input-inline" id="duotulist" style="width:400px;">
        <?php if ($goods_img_list) { foreach($goods_img_list as $k=>$v) { echo '<img style="margin-left:10px;" src="' . $v->url . '" width="120" height="80"><input name="goods_img[]" type="text" value="' . $v->url . '" style="display:none;">';}} ?>
      </div>
    </div>
<script type="text/javascript">
var _editor2;
$(function() {
    //重新实例化一个编辑器，防止在上面的editor编辑器中显示上传的图片或者文件
    _editor2 = UE.getEditor('ueditordimg');
    _editor2.ready(function () {
        //设置编辑器不可用
        _editor2.setDisabled('insertimage');
        //隐藏编辑器，因为不会用到这个编辑器实例，所以要隐藏
        _editor2.hide();
        //侦听图片上传
        _editor2.addListener('beforeInsertImage', function (t, arg) {
            $("#duotulist").html('');
            for (var i=0;i<arg.length;i++)
            {
                if(i<8)
                {
                    $("#duotulist").append('<img style="margin-left:10px;margin-bottom:10px;" src="'+arg[i].src+'" width="120" height="80"><input name="goods_img[]" type="text" value="'+arg[i].src+'" style="display:none;">');
                }
            }
        })
    });
});
//弹出图片上传的对话框
function upImage2()
{
    var myImage = _editor2.getDialog("insertimage");
	myImage.render();
    myImage.open();
}
</script>
<script type="text/plain" id="ueditordimg"></script>
    <div class="layui-form-item">
    <label class="layui-form-label">商品分类</label>
    <div class="layui-input-inline">
        <select name="type_id" id="type_id">
            <?php if ($goods_type_list) { foreach ($goods_type_list as $row) {
                if($row["id"] == $post->type_id){ ?>
            <option selected="selected" value="<?php echo $row["id"]; ?>"><?php for ($i=0;$i<$row["deep"];$i++) { echo "—"; } echo $row["name"]; ?></option>
                <?php }else{ ?>
            <option value="<?php echo $row["id"]; ?>"><?php for ($i=0;$i<$row["deep"];$i++) { echo "—"; } echo $row["name"]; ?></option>
            <?php } } } ?>
        </select>
    </div>
  </div>
    <div class="layui-form-item">
    <label class="layui-form-label">商品品牌</label>
    <div class="layui-input-inline">
        <select name="brand_id" id="brand_id">
            <option value="0">请选择品牌...</option>
            <?php if ($goods_brand_list) { foreach ($goods_brand_list as $row) {
                if ($row->id == $post->brand_id) { ?>
            <option selected="selected" value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                <?php } else { ?>
            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
            <?php } } } ?>
        </select>
    </div>
  </div>
    <div class="layui-form-item">
      <label class="layui-form-label">卖点说明</label>
      <div class="layui-input-block">
        <input name="sell_point" type="text" id="sell_point" value="<?php echo $post->sell_point; ?>" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">SEO标题</label>
      <div class="layui-input-block">
        <input name="seotitle" type="text" id="seotitle" value="<?php echo $post->seotitle; ?>" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">关键词</label>
      <div class="layui-input-inline">
        <input id="keywords" name="keywords" value="<?php echo $post->keywords; ?>" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">(多个用","分开)</div>
    </div>
    <div class="layui-form-item layui-form-text">
      <label class="layui-form-label">内容摘要</label>
      <div class="layui-input-block">
        <textarea name="description" rows="3" id="description" placeholder="" class="layui-textarea"><?php echo $post->description; ?></textarea>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">内容</label>
      <div class="layui-input-block">
<!-- 加载编辑器的容器 --><script id="container" name="content" type="text/plain"><?php echo $post->content; ?></script>
<!-- 配置文件 --><script type="text/javascript" src="/plugin/flueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 --><script type="text/javascript" src="/plugin/flueditor/ueditor.all.min.js"></script>
<!-- 实例化编辑器 --><script type="text/javascript">var ue = UE.getEditor('container',{maximumWords:100000,initialFrameHeight:320,enableAutoSave:false});</script>
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-input-block">
        <button class="layui-btn" lay-submit="" lay-filter="">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
      </div>
    </div>
  </form>
</div>
<script>
  layui.use(['laydate', 'jquery', 'form'], function () {
    var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
    var laydate = layui.laydate;
    
    //日期时间选择器
    laydate.render({
        elem: '#promote_start_date'
        ,type: 'datetime'
    });
    laydate.render({
        elem: '#promote_end_date'
        ,type: 'datetime'
    });
  });
</script>
@endsection