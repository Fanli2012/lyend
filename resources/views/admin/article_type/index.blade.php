@extends('admin.layouts.app')
@section('title', '文章栏目列表')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="{{ route('admin_article_type') }}"><cite>文章栏目管理</cite></a>
  </span>
</div>
<div class="layui-fluid">
  <div style="padding-top:15px;padding-bottom:5px;">
    <a href="{{ route('admin_article_type_add') }}" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-add-circle"></i>增加顶级栏目</a>
    <a href="{{ route('admin_article_add') }}" class="layui-btn"><i class="layui-icon layui-icon-add-circle"></i>发布文章</a>
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
		<td><a href="{{ route('admin_article') }}?type_id=<?php echo $row["id"]; ?>"><?php for ($i=0; $i < $row["deep"]; $i++) { echo "— "; } echo $row["name"]; ?></a></td><td><?php echo $row["filename"]; ?></td><td><?php echo date('Y-m-d',$row["add_time"]); ?></td>
		<td><a href="{{ route('admin_article_add') }}?type_id=<?php echo $row["id"]; ?>">发布文章</a> | <a href="{{ route('admin_article_type_add') }}?parent_id=<?php echo $row["id"]; ?>">增加子类</a> | <a href="{{ route('admin_article_type_edit') }}?id=<?php echo $row["id"]; ?>">更改</a> | <a onclick="confirm_prompt('{{ route('admin_article_type_del') }}?id=<?php echo $row["id"]; ?>')" href="javascript:;">删除</a></td>
		</tr>
        <?php } } ?>
      </tbody>
    </table>
  </div>
</div>
@endsection