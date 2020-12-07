@extends('admin.layouts.app')
@section('title', '广告管理')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_ad'); ?>"><cite>广告管理</cite></a>
  </span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
  <form action="<?php echo route('admin_ad'); ?>" method="get" class="layui-form layui-col-md12">
    搜索：
    <div class="layui-inline">
      <input type="text" id="keyword" name="keyword" placeholder="请输入关键字" autocomplete="off" class="layui-input">
    </div>
    <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon layui-icon-search"></i></button>
  </form>
</div>
<div class="layui-fluid">
  <div style="padding-top:15px;padding-bottom:5px;">
    <a href="<?php echo route('admin_ad_add'); ?>" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-add-circle"></i>添加广告</a>
  </div>

  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
			<th>ID</th>
			<th>名称</th>
			<th>标识</th>
			<th>是否限时</th>
			<th>结束时间</th>
			<th>管理</th>
		</tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
			<td><?php echo $v->id; ?></td>
			<td><?php echo $v->name; ?></td>
			<td><?php echo $v->flag; ?></td>
			<td><?php if ($v->is_expire == 0) { echo '不限时间'; } else { echo '限时标记'; } ?></td>
			<td><?php if ($v->end_time > 0) { echo date('Y-m-d', $v->end_time); } ?></td>
			<td><a href="<?php echo route('admin_ad_edit'); ?>?id=<?php echo $v->id; ?>">修改</a> | <a onclick="confirm_prompt('<?php echo route('admin_ad_del'); ?>?id=<?php echo $v->id; ?>')" href="javascript:;">删除</a></td>
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