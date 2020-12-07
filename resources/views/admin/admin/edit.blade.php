@extends('admin.layouts.app')
@section('title', '管理员修改')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_admin'); ?>">管理员列表</a><span lay-separator="">/</span>
    <a href=""><cite>管理员修改</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <input style="display:none;" name="id" type="text" id="id" value="<?php echo $id; ?>">
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>用户名
      </label>
      <div class="layui-input-inline">
        <input id="name" name="name" value="<?php echo $post->name; ?>" lay-verify="required" placeholder="在此输入用户名" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        密码
      </label>
      <div class="layui-input-inline">
        <input id="pwd" name="pwd" value="" lay-verify="" type="password" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">为空表示不修改密码</div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        邮箱
      </label>
      <div class="layui-input-inline">
        <input id="email" name="email" value="<?php echo $post->email; ?>" lay-verify="" placeholder="" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>角色
      </label>
      <div class="layui-input-inline">
        <select name="role_id" id="role_id">
			<?php if ($role_list) { foreach ($role_list as $row) { ?>
				<?php if($post->role_id == $row->id) { ?>
				<option selected value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
				<?php } else { ?>
				<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
			<?php } } } ?>
		</select>
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-inline">
          <input type="radio" value='0' name="status" <?php if ($post->status == 0) { echo 'checked'; } ?> title="正常" />
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