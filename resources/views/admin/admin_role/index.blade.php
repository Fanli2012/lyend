@extends('admin.layouts.app')
@section('title', '角色管理')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_admin_role'); ?>"><cite>角色管理</cite></a>
  </span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
  <form action="<?php echo route('admin_admin_role'); ?>" method="get" class="layui-form layui-col-md12">
    搜索：
    <div class="layui-inline">
      <input type="text" id="keyword" name="keyword" placeholder="请输入关键字" autocomplete="off" class="layui-input">
    </div>
    <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon layui-icon-search"></i></button>
  </form>
</div>
<div class="layui-fluid">
  <div style="padding-top:15px;padding-bottom:5px;">
    <a href="<?php echo route('admin_admin_role_add'); ?>" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-add-circle"></i>添加角色</a>
  </div>

  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
            <th>ID</th>
			<th>角色名称</th>
			<th>角色描述</th>
			<th>状态</th>
			<th>管理</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
            <td><?php echo $v->id; ?></td>
			<td><?php echo $v->name; ?></td>
			<td><?php echo $v->desc; ?></td>
			<td><?php echo $v->status_text; ?></td>
			<td><?php if ($v->id <> 1) { ?><a href="<?php echo route('admin_admin_role_permissions'); ?>?id=<?php echo $v->id; ?>">权限设置</a> | <?php } ?><a href="<?php echo route('admin_admin_role_edit'); ?>?id=<?php echo $v->id; ?>">修改</a><?php if ($v->id <> 1) { ?> | <a onclick="confirm_prompt('<?php echo route('admin_admin_role_del'); ?>?id=<?php echo $v->id; ?>')" href="javascript:;">删除</a><?php } ?></td>
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