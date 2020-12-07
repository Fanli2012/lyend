@extends('admin.layouts.app')
@section('title', '菜单添加')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_menu'); ?>">菜单列表</a><span lay-separator="">/</span>
    <a href=""><cite>菜单添加</cite></a>
  </span>
</div>

<div class="layui-fluid" style="padding:15px;">
  <form method="post" action="" role="form" enctype="multipart/form-data" class="layui-form">{{ csrf_field() }}
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>上级
      </label>
      <div class="layui-input-inline">
        <select name="parent_id" id="parent_id">
			<option value="0">顶级菜单</option>
			<?php if ($menu) { foreach ($menu as $row) { ?>
			<?php if ($parent_id <> 0 && $row["id"] == $parent_id) { ?>
			<option selected value="<?php echo $row["id"]; ?>"><?php for ($i=0;$i<$row["deep"];$i++) { echo "—"; } echo $row["name"]; ?></option>
			<?php }else{ ?>
			<option value="<?php echo $row["id"]; ?>"><?php for ($i=0;$i<$row["deep"];$i++) { echo "—"; } echo $row["name"]; ?></option>
			<?php } } } ?>
		</select>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>名称
      </label>
      <div class="layui-input-inline">
        <input id="name" name="name" value="" lay-verify="required" placeholder="在此输入菜单名称" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">
        <font color="red">*</font>操作方法
      </label>
      <div class="layui-input-inline">
        <input id="action" name="action" value="" lay-verify="required" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">参数</label>
      <div class="layui-input-inline">
        <input id="data" name="data" value="" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">图标</label>
      <div class="layui-input-inline">
        <input id="icon" name="icon" value="" lay-verify="" placeholder="" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">(Layui字体图标，如layui-icon layui-icon-heart-fill)</div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">备注</label>
      <div class="layui-input-inline">
        <input id="desc" name="desc" value="" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">排序</label>
      <div class="layui-input-inline">
        <input id="listorder" name="listorder" value="50" lay-verify="" placeholder="" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-inline">
		<select name="status" id="status" lay-filter="">
            <option selected="selected" value="0">正常</option>
            <option value="1">隐藏</option>
        </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">类型</label>
        <div class="layui-input-inline">
		<select name="type" id="type" lay-filter="">
            <option value="0">只作为菜单</option>
            <option selected="selected" value="1">权限认证+菜单</option>
        </select>
        </div>
        <div class="layui-form-mid layui-word-aux">注意：“权限认证+菜单”表示加入后台权限管理，纯碎是菜单项请不要选择此项。</div>
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