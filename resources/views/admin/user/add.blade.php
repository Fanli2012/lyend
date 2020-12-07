@extends('admin.layouts.app')
@section('title', '会员添加')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_user'); ?>">会员列表</a><span lay-separator="">/</span>
    <a href=""><cite>会员添加</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>用户名
      </label>
      <div class="layui-input-inline">
        <input id="user_name" name="user_name" value="" lay-verify="required" placeholder="在此输入用户名" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>手机号
      </label>
      <div class="layui-input-inline">
        <input id="mobile" name="mobile" value="" lay-verify="required" placeholder="在此输入手机号" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>密码
      </label>
      <div class="layui-input-inline">
        <input autocomplete="off" id="password" name="password" value="" lay-verify="required" type="password" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        金额
      </label>
      <div class="layui-input-inline">
        <input id="money" name="money" value="" lay-verify="" placeholder="" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>性别
      </label>
      <div class="layui-input-inline">
        <select name="sex" id="sex">
            <option selected value="1">男</option>
            <option value="2">女</option>
		</select>
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">会员等级</label>
        <div class="layui-input-inline">
			<select name="user_rank" id="user_rank">
				<option selected value="0">请选择</option>
				<?php if ($user_rank) { foreach ($user_rank as $k=>$v) { ?>
				<option value="<?php echo $v->rank; ?>"><?php echo $v->title; ?></option>
				<?php } } ?>
			</select>
        </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        父级ID
      </label>
      <div class="layui-input-inline">
        <input id="parent_id" name="parent_id" value="" lay-verify="" placeholder="" type="text" class="layui-input">
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