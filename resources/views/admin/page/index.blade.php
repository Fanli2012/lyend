@extends('admin.layouts.app')
@section('title', '单页面列表')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href=""><cite>单页管理</cite></a>
  </span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
  <form action="" method="get" class="layui-form layui-col-md12">
    搜索：
    <div class="layui-inline">
      <input type="text" id="keyword" name="keyword" placeholder="请输入关键字" autocomplete="off" class="layui-input">
    </div>
    <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon layui-icon-search"></i></button>
  </form>
</div>
<div class="layui-fluid">
  <div style="padding-top:15px;padding-bottom:5px;">
    <a href="{{ route('admin_page_add') }}" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-add-circle"></i>增加一个页面</a>
  </div>

  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
          <th>ID</th>
		  <th>页面名称</th>
		  <th>别名</th>
		  <th>更新时间</th>
		  <th>管理</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
		  <td><?php echo $v->id; ?></td>
		  <td><a href="{{ route('admin_page_edit') }}?id=<?php echo $v->id; ?>"><?php echo $v->title; ?></a></td>
		  <td><?php echo $v->filename; ?></td>
		  <td><?php echo date('Y-m-d', $v->update_time); ?></td>
		  <td><a target="_blank" href="/page/<?php echo $v->filename; ?>.html">预览</a>&nbsp;<a href="{{ route('admin_page_edit') }}?id=<?php echo $v->id; ?>">修改</a>&nbsp;<a onclick="confirm_prompt('{{ route('admin_page_del') }}?id=<?php echo $v->id; ?>')" href="javascript:;">删除</a></td>
		</tr>
        <?php } } ?>
      </tbody>
    </table>
    <div class="backpages">{{ $list->links() }}</div>
  </div>
</div>
@endsection