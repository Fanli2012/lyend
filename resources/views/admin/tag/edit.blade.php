@extends('admin.layouts.app')
@section('title', 'Tag修改')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_tag'); ?>">Tag列表</a><span lay-separator="">/</span>
    <a href=""><cite>修改Tag</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <input style="display:none;" name="id" type="text" id="id" value="<?php echo $id; ?>">
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>Tag名称
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input autocomplete="off" id="name" name="name" value="<?php echo $post->name; ?>" lay-verify="required" placeholder="在此输入名称" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-inline">
          <input type="radio" value="0" name="status" <?php if ($post->status == 0) { echo 'checked'; } ?> title="正常" />
          <input type="radio" value="1" name="status" <?php if ($post->status == 1) { echo 'checked'; } ?> title="隐藏" />
        </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>别名
      </label>
      <div class="layui-input-inline">
        <input autocomplete="off" id="filename" name="filename" value="<?php echo $post->filename; ?>" lay-verify="required" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        Tag标题
      </label>
      <div class="layui-input-inline">
        <input id="title" name="title" value="<?php echo $post->title; ?>" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        模板名称
      </label>
      <div class="layui-input-inline">
        <input id="template" name="template" value="<?php echo $post->template; ?>" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        缩略图
      </label>
      <div class="layui-input-inline" style="width:400px;">
        <input name="litpic" type="text" id="litpic" value="<?php echo $post->litpic; ?>" class="layui-input">
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
      <label class="layui-form-label">关键词</label>
      <div class="layui-input-inline">
        <input id="keywords" name="keywords" value="<?php echo $post->keywords; ?>" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">(多个用","分开)</div>
    </div>
    <div class="layui-form-item layui-form-text">
      <label class="layui-form-label">描述</label>
      <div class="layui-input-block">
        <textarea name="description" rows="3" id="description" placeholder=""
          class="layui-textarea"><?php echo $post->description; ?></textarea>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">Tag文章列表</label>
      <div class="layui-input-inline" style="width:50%;">
        <textarea name="tagarc" rows="3" id="tagarc" class="layui-textarea"><?php echo $article_id_list; ?></textarea>
      </div>
      <div class="layui-form-mid layui-word-aux">(多个用","分开)</div>
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
  layui.use(['jquery', 'form'], function () {
    var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
  });
</script>
@endsection