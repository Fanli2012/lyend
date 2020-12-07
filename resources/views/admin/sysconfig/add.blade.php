@extends('admin.layouts.app')
@section('title', '参数添加')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="{{ route('admin_sysconfig') }}">系统配置参数</a><span lay-separator="">/</span>
    <a><cite>添加参数</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>参数名称
      </label>
      <div class="layui-input-inline" style="width:300px;">
        <input type="text" name="varname" value="CMS_" autocomplete="off" lay-verify="required" placeholder="在此输入参数变量"
          class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">(必须是大写字母)</div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>参数说明
      </label>
      <div class="layui-input-block">
        <input type="text" name="info" autocomplete="off" lay-verify="required" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item layui-form-text">
      <label class="layui-form-label">参数值</label>
      <div class="layui-input-block">
        <textarea name="value" rows="5" id="value" placeholder="" class="layui-textarea"></textarea>
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