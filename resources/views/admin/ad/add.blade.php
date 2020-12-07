@extends('admin.layouts.app')
@section('title', '广告添加')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_ad'); ?>">广告列表</a><span lay-separator="">/</span>
    <a href=""><cite>添加广告</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>名称
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input autocomplete="off" id="name" name="name" value="" lay-verify="required" placeholder="在此输入名称" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item layui-form-text">
      <label class="layui-form-label">描述</label>
      <div class="layui-input-block">
        <textarea name="description" rows="3" id="description" placeholder="" class="layui-textarea"></textarea>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        广告位标识
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input autocomplete="off" id="flag" name="flag" value="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">时间限制</label>
        <div class="layui-input-inline">
          <input type="radio" value='0' name="is_expire" checked title="永不过期" />
          <input type="radio" value='1' name="is_expire" title="在设内时间内有效" />
        </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-inline">
      <label class="layui-form-label">投放开始时间
      </label>
      <div class="layui-input-inline">
        <input name="start_time" type="text" id="start_time" value="<?php echo date('Y-m-d H:i:s'); ?>" class="layui-input">
      </div>
      </div>
      <div class="layui-inline">
      <label class="layui-form-label">投放结束时间
      </label>
      <div class="layui-input-inline">
        <input id="end_time" name="end_time" type="text" value="<?php echo date('Y-m-d H:i:s'); ?>" class="layui-input">
      </div>
      </div>
    </div>
    <div class="layui-form-item layui-form-text">
      <label class="layui-form-label"><font color="red">*</font>广告内容</label>
      <div class="layui-input-block">
        <textarea name="content" rows="5" id="content" placeholder="" class="layui-textarea" lay-verify="required"></textarea>
      </div>
    </div>
    <div class="layui-form-item layui-form-text">
      <label class="layui-form-label">广告内容-移动端</label>
      <div class="layui-input-block">
        <textarea name="content_wap" rows="5" id="content_wap" placeholder="" class="layui-textarea" lay-verify=""></textarea>
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