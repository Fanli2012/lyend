@extends('admin.layouts.app')
@section('title', '优惠券修改')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_bonus'); ?>">优惠券列表</a><span lay-separator="">/</span>
    <a href=""><cite>优惠券修改</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <input style="display:none;" name="id" type="text" id="id" value="<?php echo $id; ?>">
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>名称
      </label>
      <div class="layui-input-block" style="width:300px;">
        <input autocomplete="off" id="name" name="name" value="<?php echo $post->name; ?>" lay-verify="required" placeholder="在此输入名称" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>金额
      </label>
      <div class="layui-input-inline">
        <input autocomplete="off" id="money" name="money" value="<?php echo $post->money; ?>" lay-verify="required" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>满多少使用
      </label>
      <div class="layui-input-inline">
        <input autocomplete="off" id="min_amount" name="min_amount" value="<?php echo $post->min_amount; ?>" lay-verify="required" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>数量
      </label>
      <div class="layui-input-inline">
        <input id="num" name="num" value="<?php echo $post->num; ?>" lay-verify="required" placeholder="" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">(-1表示不限)</div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label"><font color="red">*</font>期限</label>
      <div class="layui-input-inline">
        <input autocomplete="off" id="start_time" name="start_time" lay-verify="required" placeholder="开始时间" type="text" value="{{ $post->start_time }}" class="layui-input">
      </div>
	  <div class="layui-form-mid">-</div>
      <div class="layui-input-inline">
        <input autocomplete="off" id="end_time" name="end_time" lay-verify="required" placeholder="结束时间" type="text" value="{{ $post->end_time }}" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否可用</label>
        <div class="layui-input-inline">
          <input type="radio" value="0" name="status" <?php if ($post->status == 0) { echo 'checked'; } ?> title="是" />
          <input type="radio" value="1" name="status" <?php if ($post->status == 1) { echo 'checked'; } ?> title="否" />
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
        elem: '#start_time'
        ,type: 'datetime'
    });
    laydate.render({
        elem: '#end_time'
        ,type: 'datetime'
    });
  });
</script>
@endsection