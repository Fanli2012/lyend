@extends('admin.layouts.app')
@section('title', '商品分类')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_goods_type'); ?>"><cite>商品分类管理</cite></a>
  </span>
</div>
<div class="layui-fluid">
  <div style="padding-top:15px;padding-bottom:5px;">
    <a href="<?php echo route('admin_goods_type_add'); ?>" class="layui-btn"><i class="layui-icon layui-icon-add-circle"></i>增加顶级分类</a>
    <a href="<?php echo route('admin_goods_add'); ?>" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-add-circle"></i>发布商品</a>
  </div>

  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
			<th>ID</th>
			<th>名称</th>
			<th>别名</th>
			<th>更新时间</th>
			<th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $row) { ?>
        <tr id="cat-<?php echo $row["id"]; ?>">
			<td><?php echo $row["id"]; ?></td>
			<td><a href="<?php echo route('admin_goods'); ?>?type_id=<?php echo $row["id"]; ?>"><?php for($i=0;$i<$row["deep"];$i++){echo "— ";}echo $row["name"]; ?></a></td>
			<td><?php echo $row["filename"]; ?></td>
			<td><?php echo date('Y-m-d',$row["add_time"]); ?></td>
			<td><a href="<?php echo route('admin_goods_add'); ?>?type_id=<?php echo $row["id"]; ?>">发布商品</a> | <a href="<?php echo route('admin_goods_type_add'); ?>?parent_id=<?php echo $row["id"]; ?>">增加子类</a> | <a href="<?php echo route('admin_goods_type_edit'); ?>?id=<?php echo $row["id"]; ?>">更改</a> | <a onclick="confirm_prompt('<?php echo route('admin_goods_type_del'); ?>?id=<?php echo $row["id"]; ?>')" href="javascript:;">删除</a></td>
		</tr>
        <?php } } ?>
      </tbody>
    </table>
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