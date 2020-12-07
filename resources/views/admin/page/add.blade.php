@extends('admin.layouts.app')
@section('title', '单页添加')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="{{ route('admin_page') }}">单页列表</a><span lay-separator="">/</span>
    <a href=""><cite>单页添加</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>页面标题
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input autocomplete="off" id="title" name="title" value="" lay-verify="required" placeholder="在此输入标题" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>别名
      </label>
      <div class="layui-input-inline">
        <input autocomplete="off" id="filename" name="filename" value="" lay-verify="required" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        模板文件名
      </label>
      <div class="layui-input-inline">
        <input id="template" name="template" value="page" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        分组ID
      </label>
      <div class="layui-input-inline">
        <input id="group_id" name="group_id" value="0" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        缩略图
      </label>
      <div class="layui-input-inline" style="width:400px;">
        <input name="litpic" type="text" id="litpic" value="" class="layui-input">
      </div>
      <div class="layui-input-inline">
        <input type="button" class="layui-btn" onclick="upImage();" value="选择图片">
      </div>
      <div class="layui-input-inline">
        <img style="margin-left:20px;display:none;" src="" width="120" height="80" id="picview">
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
      <label class="layui-form-label">SEO标题</label>
      <div class="layui-input-block">
        <input name="seotitle" type="text" id="seotitle" value="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">关键词</label>
      <div class="layui-input-inline">
        <input id="keywords" name="keywords" value="" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">(多个用","分开)</div>
    </div>
    <div class="layui-form-item layui-form-text">
      <label class="layui-form-label">描述</label>
      <div class="layui-input-block">
        <textarea name="description" rows="3" id="description" placeholder=""
          class="layui-textarea"></textarea>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">内容</label>
      <div class="layui-input-block">
<!-- 加载编辑器的容器 --><script id="container" name="content" type="text/plain"></script>
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
        elem: '#update_time'
        ,type: 'datetime'
    });
  });
</script>
@endsection