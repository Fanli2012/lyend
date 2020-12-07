@extends('admin.layouts.app')
@section('title', '人工充值')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href=""><cite>人工充值</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
	<input name="user_id" value="<?php echo $user->id; ?>" type="text" style="display:none;" />
    <div class="layui-form-item">
      <div class="layui-form-mid">
	    当前充值用户：<?php if ($user->user_name) { echo $user->user_name; } else { echo $user->mobile; } ?>，账户余额<font color="red"><?php echo $user->money; ?></font>元
		<br>说明：正数为增加，负数为扣除
	  </div>
	</div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>充值金额
      </label>
      <div class="layui-input-inline">
        <input id="money" name="money" value="" lay-verify="required" placeholder="" type="text" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>说明/备注
      </label>
      <div class="layui-input-inline">
        <input id="desc" name="desc" value="人工充值" lay-verify="required" placeholder="" type="text" class="layui-input">
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