@extends('admin.layouts.app')
@section('title', '操作记录列表')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href=""><cite>操作记录列表</cite></a>
  </span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
  <form action="<?php echo route('admin_log'); ?>" method="get" class="layui-form layui-col-md12">
    搜索：
      <div class="layui-input-inline">
        <select name="type">
          <option value="">请选择模块</option>
          <option value="1">fladmin</option>
          <option value="2">index</option>
          <option value="3">api</option>
          <option value="0">未知</option>
        </select>
      </div>
    <div class="layui-inline">
      <input type="text" id="keyword" name="keyword" placeholder="请输入关键字" autocomplete="off" class="layui-input">
    </div>
    <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon layui-icon-search"></i></button>
  </form>
</div>
<div class="layui-fluid">
  <div style="padding-top:15px;padding-bottom:5px;">
    <a href="<?php echo route('admin_log'); ?>?type=1" class="layui-btn">后台日志</a>
    <a href="<?php echo route('admin_log'); ?>?type=2" class="layui-btn layui-btn-normal">前台日志</a>
    <a href="<?php echo route('admin_log'); ?>?type=3" class="layui-btn layui-btn-warm">API日志</a>
    <a onclick="confirm_prompt('<?php echo route('admin_log_clear'); ?>')" href="javascript:;" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-reduce-circle"></i>清空记录</a>
  </div>

  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
			<th>ID</th>
			<th>模块</th>
			<th>操作者</th>
			<th>操作记录</th>
			<th>来源</th>
			<th>IP地址</th>
			<th>操作时间</th>
			<th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
			<td><?php echo $v->id; ?></td>
			<td><a href="<?php echo route('admin_log'); ?>?type=<?php echo $v->type; ?>"><?php echo $v->type_text; ?></a></td>
			<td><a href="<?php echo route('admin_log'); ?>?login_id=<?php echo $v->login_id; ?>"><?php if (!empty($v->login_name)) { echo $v->login_name; } else { echo '未登录'; } ?></a></td>
			<td style="width:300px;word-wrap:break-word;white-space:normal;word-break:break-all;">【<a href="<?php echo route('admin_log'); ?>?http_method=<?php echo $v->http_method; ?>"><?php echo $v->http_method; ?></a>】<a href="<?php echo $v->url; ?>" target="_blank"><?php echo $v->url; ?></a> <?php if ($v->content) { echo ' - ' . htmlentities($v->content, ENT_QUOTES, "UTF-8"); } ?></td>
			<td style="width:300px;word-wrap:break-word;white-space:normal;word-break:break-all;"><a href="<?php echo $v->http_referer; ?>" target="_blank"><?php echo $v->http_referer; ?></a></td>
			<td><a href="<?php echo route('admin_log'); ?>?ip=<?php echo $v->ip; ?>"><?php echo $v->ip; ?></a> <a href="https://www.baidu.com/s?wd=<?php echo $v->ip; ?>" target="_blank">查看</a></td>
			<td><?php echo date('Y-m-d H:i:s', $v->add_time); ?></td>
			<td><a onclick="confirm_prompt('<?php echo route('admin_log_del'); ?>?id=<?php echo $v->id; ?>')" href="javascript:;">删除</a></td>
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
  });
</script>
@endsection