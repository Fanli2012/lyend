@extends('admin.layouts.app')
@section('title', '角色修改')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_admin_role'); ?>">角色列表</a><span lay-separator="">/</span>
    <a href=""><cite>角色修改</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <input style="display:none;" name="id" type="text" id="id" value="<?php echo $id; ?>">
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>角色名称
      </label>
      <div class="layui-input-inline">
        <input id="name" name="name" value="<?php echo $post->name; ?>" lay-verify="required" placeholder="在此输入角色名称" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        角色描述
      </label>
      <div class="layui-input-inline">
        <input id="desc" name="desc" value="<?php echo $post->desc; ?>" lay-verify="" placeholder="" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        排序
      </label>
      <div class="layui-input-inline">
        <input id="listorder" name="listorder" value="<?php echo $post->listorder; ?>" lay-verify="" placeholder="" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-inline">
          <input type="radio" value='0' name="status" <?php if ($post->status == 0) { echo 'checked'; } ?> title="启用" />
          <input type="radio" value='1' name="status" <?php if ($post->status == 1) { echo 'checked'; } ?> title="禁用" />
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