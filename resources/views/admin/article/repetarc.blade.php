@extends('admin.layouts.app')
@section('title', '重复文档列表')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="">重复文档列表</a><span lay-separator="">/</span>
    <a href="<?php echo route('admin_article'); ?>"><cite>文章列表</cite></a>
  </span>
</div>

<div class="layui-fluid">
  <div style="padding-top:15px;padding-bottom:5px;">
    <a href="<?php echo route('admin_article_add'); ?><?php if (!empty($_GET["id"])) { echo '?type_id='.$_GET["id"]; }?>" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-add-circle"></i>发布文章</a>
  </div>

  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
          <th>文档标题</th>
          <th>重复数量</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
			<td><a href="<?php echo route('admin_article'); ?>?keyword=<?php echo $v->title; ?>"><?php echo $v->title; ?></a></td>
			<td><?php echo $v->count; ?></td>
		</tr>
        <?php } } ?>
      </tbody>
    </table>
  </div>
</div>
@endsection