@extends('admin.layouts.app')
@section('title', '系统配置参数')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;"><a href="{:url('index')}"><cite>系统配置参数</cite></a></span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
  <form action="{{ route('admin_sysconfig') }}" method="get" class="layui-form layui-col-md12">
    搜索：
    <div class="layui-inline">
      <input type="text" id="keyword" name="keyword" placeholder="请输入关键字" autocomplete="off" class="layui-input">
    </div>
    <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon layui-icon-search"></i></button>
  </form>
</div>
<div class="layui-fluid">
  <div style="padding-top:15px;padding-bottom:5px;">
    <a href="{{ route('admin_sysconfig_add') }}" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-delete"></i>添加参数</a>
    <a href="{{ route('admin_index_upcache') }}" class="layui-btn"><i class="layui-icon layui-icon-praise"></i>更新配置缓存</a>
    <a href="{{ route('admin_sysconfig_other') }}" class="layui-btn layui-btn-normal"><i class=""></i>其它配置</a>
  </div>

  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>参数说明</th>
          <th>参数值</th>
          <th>变量名</th>
          <th width="100px">管理</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
          <td><?php echo $v->id; ?></td>
          <td><?php echo $v->info; ?></td>
          <td><?php echo htmlentities(mb_strcut($v->value, 0, 80, 'utf-8'), ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo $v->varname; ?></td>
          <td><a href="{{ route('admin_sysconfig_edit') }}?id=<?php echo $v->id; ?>">修改</a> | <a
              onclick="confirm_prompt('{{ route('admin_sysconfig_del') }}?id=<?php echo $v->id; ?>')" href="javascript:;">删除</a></td>
        </tr><?php } } ?>
      </tbody>
    </table>
    <div class="backpages">{{ $list->links() }}</div>
  </div>
</div>
@endsection