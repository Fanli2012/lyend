@extends('admin.layouts.app')
@section('title', '会员列表')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_user'); ?>"><cite>会员列表</cite></a>
  </span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
  <form action="<?php echo route('admin_user'); ?>" method="get" class="layui-form layui-col-md12">
    搜索：
    <div class="layui-inline">
      <input type="text" id="keyword" name="keyword" placeholder="请输入关键字" autocomplete="off" class="layui-input">
    </div>
    <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon layui-icon-search"></i></button>
  </form>
</div>
<div class="layui-fluid">
  <div style="padding-top:15px;padding-bottom:5px;">
    <a href="<?php echo route('admin_user_add'); ?>" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-add-circle"></i>添加会员</a>
    <a href="<?php echo route('admin_user_money'); ?>" class="layui-btn layui-btn-warm">余额记录</a>
    <a href="<?php echo route('admin_user_rank'); ?>" class="layui-btn layui-btn-normal">会员等级</a>
  </div>

  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
            <th>ID</th>
			<th>用户名</th>
			<th>性别</th>
			<th>余额</th>
			<th>积分</th>
			<th>佣金</th>
			<th>注册时间</th>
			<th>状态</th>
			<th>管理</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
            <td><?php echo $v->id; ?></td>
			<td><?php if (!empty($v->head_img)) { ?><img src="<?php echo $v->head_img; ?>" style="width:24px;height:24px;" onerror="this.src='<?php echo http_host(); ?>/images/no_pic.jpg'"><?php } ?> <a href="javascript:;" style="<?php if ($v->login_time > (time() - 300)) { echo 'color:#f60;'; } ?>"><?php if ($v->user_name) { echo $v->user_name; } else { echo $v->mobile; } ?></a></td>
			<td><?php echo $v->sex_text; ?></td>
			<td><?php echo $v->money; ?></td>
			<td><?php echo $v->point; ?></td>
			<td><font color="red"><?php echo $v->commission; ?></font></td>
			<td><?php echo date('Y-m-d H:i:s', $v->add_time); ?></td>
			<td><a href="<?php echo route('admin_user'); ?>?status=<?php echo $v->status; ?>"><?php echo $v->status_text; ?></a></td>
			<td><a href="<?php echo route('admin_user_money_add'); ?>?user_id=<?php echo $v->id; ?>">人工充值</a> | <a href="<?php echo route('admin_user_money'); ?>?user_id=<?php echo $v->id; ?>">余额记录</a> | <a href="<?php echo route('admin_user_edit'); ?>?id=<?php echo $v->id; ?>">修改</a> | <a onclick="confirm_prompt('<?php echo route('admin_user_del'); ?>?id=<?php echo $v->id; ?>')" href="javascript:;">删除</a></td>
		</tr>
        <?php } } ?>
      </tbody>
    </table>
    <div class="backpages">{{ $list->links() }}</div>
  </div>
</div>

<script>
layui.use(['jquery', 'form'], function () {
  var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
  //……
  //但是，如果你的HTML是动态生成的，自动渲染就会失效
  //因此你需要在相应的地方，执行下述方法来手动渲染，跟这类似的还有 element.init();
  form.render();
});
</script>

@endsection