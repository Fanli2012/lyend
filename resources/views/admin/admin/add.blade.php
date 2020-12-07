@extends('admin.layouts.app')
@section('title', '管理员添加')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_admin'); ?>">管理员列表</a><span lay-separator="">/</span>
    <a href=""><cite>管理员添加</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>用户名
      </label>
      <div class="layui-input-inline">
        <input id="name" name="name" value="" lay-verify="required" placeholder="在此输入用户名" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>密码
      </label>
      <div class="layui-input-inline">
        <input id="pwd" name="pwd" value="" lay-verify="required" type="password" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        邮箱
      </label>
      <div class="layui-input-inline">
        <input id="email" name="email" value="" lay-verify="" placeholder="" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>角色
      </label>
      <div class="layui-input-inline">
        <select name="role_id" id="role_id">
			<?php if ($role_list) { foreach ($role_list as $row) { ?>
			<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
			<?php } } ?>
		</select>
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-inline">
          <input type="radio" value='0' name="status" checked title="正常" />
          <input type="radio" value='1' name="status" title="禁用" />
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