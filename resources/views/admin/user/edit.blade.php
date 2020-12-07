@extends('admin.layouts.app')
@section('title', '会员信息修改')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_user'); ?>">会员列表</a><span lay-separator="">/</span>
    <a href=""><cite>会员信息修改</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <input style="display:none;" name="id" type="text" id="id" value="<?php echo $id; ?>">
    <div class="layui-form-item">
      <label class="layui-form-label">
        昵称
      </label>
      <div class="layui-input-inline">
        <input id="nickname" name="nickname" value="<?php echo $post->nickname; ?>" lay-verify="" placeholder="" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">性别</label>
        <div class="layui-input-inline">
			<select name="sex" id="sex">
				<option<?php if($post->sex == 1){ echo ' selected'; } ?> value="1">男</option>
				<option<?php if($post->sex == 2){ echo ' selected'; } ?> value="2">女</option>
			</select>
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