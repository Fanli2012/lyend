@extends('admin.layouts.app')
@section('title', '友情链接添加')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_friendlink'); ?>">友情链接列表</a><span lay-separator="">/</span>
    <a href=""><cite>添加友情链接</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>链接名称
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input autocomplete="off" id="name" name="name" value="" lay-verify="required" placeholder="在此输入名称" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>链接网址
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input autocomplete="off" id="url" name="url" value="http://" lay-verify="required" placeholder="" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">(请用绝对地址)</div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        所属的组
      </label>
      <div class="layui-input-inline">
        <input id="group_id" name="group_id" value="0" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">跳转方式</label>
        <div class="layui-input-inline">
          <input type="radio" value="0" name="target" checked title="_blank" />
          <input type="radio" value="1" name="target" title="_self" />
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