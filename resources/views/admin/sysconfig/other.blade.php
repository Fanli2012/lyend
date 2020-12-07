@extends('admin.layouts.app')
@section('title', '其它设置')

@section('content')
<!-- 配置文件 --><script type="text/javascript" src="/plugin/flueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 --><script type="text/javascript" src="/plugin/flueditor/ueditor.all.min.js"></script>

<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="{{ route('admin_sysconfig') }}">系统配置参数</a><span lay-separator="">/</span>
    <a><cite>其它设置</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="{{ route('admin_sysconfig_other') }}" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>网站名称
      </label>
      <div class="layui-input-inline" style="width:300px;">
        <input type="text" name="CMS_WEBNAME" value="<?php echo sysconfig('CMS_WEBNAME'); ?>" autocomplete="off" lay-verify="required" placeholder="在此输入参数变量"
          class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux"></div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red"></font>统计代码
      </label>
      <div class="layui-input-block">
		<textarea name="CMS_SITE_ANALYTICS" rows="3" placeholder="" class="layui-textarea"><?php echo sysconfig('CMS_SITE_ANALYTICS'); ?></textarea>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red"></font>公司名称
      </label>
      <div class="layui-input-inline">
        <input type="text" name="CMS_COMPANY_NAME" value="<?php echo sysconfig('CMS_COMPANY_NAME'); ?>" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red"></font>公司地址
      </label>
      <div class="layui-input-inline">
        <input type="text" name="CMS_COMPANY_ADDRESS" value="<?php echo sysconfig('CMS_COMPANY_ADDRESS'); ?>" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red"></font>公司联系方式
      </label>
      <div class="layui-input-inline">
        <input type="text" name="CMS_COMPANY_CONTACT" value="<?php echo sysconfig('CMS_COMPANY_CONTACT'); ?>" lay-verify="" placeholder="" class="layui-input">
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