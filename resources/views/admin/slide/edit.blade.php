@extends('admin.layouts.app')
@section('title', '轮播图修改')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_slide'); ?>">轮播图列表</a><span lay-separator="">/</span>
    <a href=""><cite>轮播图修改</cite></a>
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
      <label class="layui-form-label">
        <font color="red">*</font>链接网址
      </label>
      <div class="layui-input-inline">
        <input autocomplete="off" id="url" name="url" value="<?php echo $post->url; ?>" lay-verify="required" placeholder="" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">(请用绝对地址)</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">跳转方式</label>
        <div class="layui-input-inline">
          <input type="radio" value="0" name="target" <?php if ($post->target == 0) { echo 'checked'; } ?> title="_blank" />
          <input type="radio" value="1" name="target" <?php if ($post->target == 1) { echo 'checked'; } ?> title="_self" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否显示</label>
        <div class="layui-input-inline">
          <input type="radio" value="0" name="status" <?php if ($post->listorder == 0) { echo 'checked'; } ?> title="是" />
          <input type="radio" value="1" name="status" <?php if ($post->listorder == 1) { echo 'checked'; } ?> title="否" />
        </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        排序
      </label>
      <div class="layui-input-inline">
        <input id="listorder" name="listorder" value="<?php echo $post->listorder; ?>" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        所属的组
      </label>
      <div class="layui-input-inline">
        <input id="group_id" name="group_id" value="<?php echo $post->group_id; ?>" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>图片
      </label>
      <div class="layui-input-inline" style="width:400px;">
        <input lay-verify="required" name="pic" type="text" id="pic" value="<?php echo $post->pic; ?>" class="layui-input">
      </div>
      <div class="layui-input-inline">
        <input type="button" class="layui-btn" onclick="upImage();" value="选择图片">
      </div>
      <div class="layui-input-inline">
        <img style="margin-left:20px;<?php if (empty($post->pic) || !is_image_format($post->pic)) { echo "display:none;"; } ?>" src="<?php if (is_image_format($post->pic)) { echo $post->pic; } ?>" width="120" height="80" id="picview" name="picview">
      </div>
    </div>
<!-- 配置文件 --><script type="text/javascript" src="/plugin/flueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 --><script type="text/javascript" src="/plugin/flueditor/ueditor.all.min.js"></script>
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
			$('#pic').val(arg[0].src);
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