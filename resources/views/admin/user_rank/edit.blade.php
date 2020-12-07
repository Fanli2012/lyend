@extends('admin.layouts.app')
@section('title', '会员等级修改')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_user_rank'); ?>">会员等级列表</a><span lay-separator="">/</span>
    <a href=""><cite>会员等级修改</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <input style="display:none;" name="id" type="text" id="id" value="<?php echo $id; ?>">
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>等级名称
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input id="title" name="title" value="<?php echo $post->title; ?>" lay-verify="required" placeholder="在此输入名称" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>等级
      </label>
      <div class="layui-input-inline">
        <input id="rank" name="rank" value="<?php echo $post->rank; ?>" lay-verify="required" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>折扣
      </label>
      <div class="layui-input-inline">
        <input id="discount" name="discount" value="<?php echo $post->discount; ?>" lay-verify="required" placeholder="折扣0-100整数" class="layui-input">
      </div>
    </div>
	<div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label"><font color="red">*</font>积分</label>
      <div class="layui-input-inline" style="width:150px;">
        <input type="text" id="min_points" name="min_points" value="<?php echo $post->min_points; ?>" placeholder="该等级的最低积分" lay-verify="required" class="layui-input">
      </div>
      <div class="layui-form-mid">-</div>
      <div class="layui-input-inline" style="width:150px;">
        <input type="text" id="max_points" name="max_points" value="<?php echo $post->max_points; ?>" placeholder="该等级的最高积分" lay-verify="required" class="layui-input">
      </div>
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