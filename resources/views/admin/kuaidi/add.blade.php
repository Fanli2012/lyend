@extends('admin.layouts.app')
@section('title', '快递添加')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_kuaidi'); ?>">快递列表</a><span lay-separator="">/</span>
    <a href=""><cite>添加快递</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>快递名称
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input autocomplete="off" id="name" name="name" value="" lay-verify="required" placeholder="在此输入标题" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>编码
      </label>
      <div class="layui-input-inline">
        <input id="code" name="code" value="" lay-verify="required" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        金额
      </label>
      <div class="layui-input-inline">
        <input id="money" name="money" value="" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        说明
      </label>
      <div class="layui-input-inline">
        <input id="desc" name="desc" value="" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">电话</label>
      <div class="layui-input-inline">
        <input name="tel" type="text" id="tel" value="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">官网</label>
      <div class="layui-input-inline">
        <input name="website" type="text" id="website" value="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">是否显示</label>
      <div class="layui-input-inline">
        <input type="radio" value="0" name="status" checked title="是" />
		<input type="radio" value="1" name="status" title="否" />
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">排序</label>
      <div class="layui-input-inline">
        <input name="listorder" type="text" id="listorder" value="50" class="layui-input">
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
  });
</script>
@endsection