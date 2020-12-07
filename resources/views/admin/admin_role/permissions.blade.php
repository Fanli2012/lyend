@extends('admin.layouts.app')
@section('title', '角色权限设置')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href=""><cite>角色权限设置</cite></a><span lay-separator="">/</span>
    <a href="<?php echo route('admin_admin_role'); ?>">角色列表</a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
<form method="post" action="<?php echo route('admin_admin_role_permissions'); ?>" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
<input style="display:none;" name="role_id" type="text" id="role_id" value="<?php echo $role_id; ?>">

<ul class="list-group"><?php if ($menus) { foreach($menus as $row) { ?>
<li class="list-group-item <?php if ($row["deep"] == 0) { echo 'list-group-item-info'; } ?>"><?php echo '<span style="padding-left:'.($row["deep"]*30).'px;"></span>'; ?><input type='checkbox' <?php if(isset($row["is_access"]) && $row["is_access"]==1){echo "checked='checked'";} ?> name='menuid[]' value='<?php echo $row["id"]; ?>' level='<?php echo $row["deep"]; ?>' onclick='javascript:checknode(this);' style="display:inline;"> <?php for($i=0;$i<$row["deep"];$i++){echo "—";}echo $row["name"]; ?></li>
<?php } } ?></ul>

<br><button type="submit" class="layui-btn" value="Submit">提交(Submit)</button><br>
</form>
</div>

<style>
.list-group-item {position: relative;display: block;padding: 10px 15px;margin-bottom: -1px;background-color: #fff;border: 1px solid #ddd;}
.list-group-item:first-child {border-top-left-radius: 4px;border-top-right-radius: 4px;}
.list-group-item-info {color: #31708f;background-color: #d9edf7;}
</style>
<script>
function checknode(obj)
{
	var chk = $("input[type='checkbox']");
	var count = chk.length;
	var num = chk.index(obj);
	var level_top = level_bottom = chk.eq(num).attr('level');
	
	for (var i = num; i >= 0; i--)
	{
		var le = chk.eq(i).attr('level');
		if (le <level_top)
		{
			chk.eq(i).prop("checked", true);
			var level_top = level_top - 1;
		}
	}
	
	for (var j = num + 1; j < count; j++)
	{
		var le = chk.eq(j).attr('level');
		
		if (chk.eq(num).prop("checked"))
		{
			if (le > level_bottom)
			{
				chk.eq(j).prop("checked", true);
			}
			else if (le == level_bottom)
			{
				break;
			}
		}
		else
		{
			if (le >level_bottom)
			{
				chk.eq(j).prop("checked", false);
			}else if(le == level_bottom)
			{
				break;
			}
		}
	}
}
</script>
@endsection