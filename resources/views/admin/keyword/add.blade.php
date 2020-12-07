@extends('admin.layouts.app')
@section('title', '关键词添加')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_keyword'); ?>">内链关键词列表</a><span lay-separator="">/</span>
    <a href=""><cite>内链关键词修改</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>内链关键词
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input id="name" name="name" value="" lay-verify="required" placeholder="在此输入关键词" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>链接网址
      </label>
      <div class="layui-input-inline">
        <input id="url" name="url" value="http://" lay-verify="required" placeholder="" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">(请用绝对地址)</div>
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