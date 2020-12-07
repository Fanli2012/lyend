@extends('admin.layouts.app')
@section('title', '数据库备份')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href=""><cite>数据库备份</cite> <small class="badge"><?php echo count($list); ?> 条</small></a>
  </span>
</div>
<div class="layui-fluid">
  <div class="layui-row">
    <table class="layui-table">
      <thead>
        <tr>
            <th>选择</th>
			<th>表名</th>
			<th>数据量</th>
			<th>数据大小</th>
			<th>创建时间</th>
			<th>备份状态</th>
			<th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php $backup_path = base_path() . DIRECTORY_SEPARATOR . 'database_backup' . DIRECTORY_SEPARATOR; if ($list) { foreach ($list as $k=>$v) { ?><tr>
		<td><input name="arcID" type="checkbox" value="<?php echo $v["name"]; ?>"></td>
		<td><?php echo $v["name"]; ?></td>
		<td><?php echo $v["rows"]; ?></td>
		<td><?php echo format_bytes($v["data_length"]); ?></td>
		<td><?php echo $v["create_time"]; ?></td>
		<td><?php $filename = $backup_path . $v["name"] . '.sql'; if (file_exists($filename)) { echo '<font color="green">已备份</font>'; } else { echo '<font color="red">未备份</font>'; } ?></td>
		<td><a href="<?php echo route('admin_database_optimize'); ?>?tables=<?php echo $v["name"]; ?>">优化表</a> <a href="<?php echo route('admin_database_repair'); ?>?tables=<?php echo $v["name"]; ?>">修复表</a> <a href="<?php echo route('admin_database_tables_backup'); ?>?tables=<?php echo $v["name"]; ?>">备份</a> </td>
		</tr><?php } } ?>
		<tr>
			<td colspan="8" class="layui-text">
			<a href="javascript:selAll('arcID')" class="coolbg">反选</a>&nbsp;
			<a href="javascript:backup_database()" class="coolbg">立即备份</a>&nbsp;
			<a href="javascript:optimize()" class="coolbg">优化表</a>&nbsp;
			<a href="javascript:repair()" class="coolbg">修复表</a>&nbsp;
			<!-- <a href="javascript:recovery()" class="coolbg">数据还原</a> -->
			</td>
		</tr>
      </tbody>
    </table>
  </div>
</div>

<script>
//备份数据库
function backup_database()
{
	var checkvalue = getItems();
	if (checkvalue=='') {
		alert('请选择要备份的表');
		return;
	}

	if (confirm("确定要执行此操作吗")) {
		location = "<?php echo route('admin_database_tables_backup'); ?>?tables=" + checkvalue;
	}
}
//优化表
function optimize()
{
	var checkvalue = getItems();
	if (checkvalue=='') {
		alert('请选择要优化的表');
		return;
	}
	location = "<?php echo route('admin_database_optimize'); ?>?tables=" + checkvalue;
}
//修复表
function repair()
{
	var checkvalue = getItems();
	if (checkvalue == '') {
		alert('请选择要修复的表');
		return;
	}
	location = "<?php echo route('admin_database_repair'); ?>?tables=" + checkvalue;
}
//数据还原
function recovery()
{
	var checkvalue = getItems();
	if (checkvalue=='') {
		alert('请选择要还原的表');
		return;
	}
	
	if (confirm("确定要执行此操作吗")) {
		location = "{:url('export')}?tables=" + checkvalue;
	}
}

layui.use(['jquery', 'form'], function () {
  var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
  //……
  //但是，如果你的HTML是动态生成的，自动渲染就会失效
  //因此你需要在相应的地方，执行下述方法来手动渲染，跟这类似的还有 element.init();
  form.render();
});
</script>
@endsection