@extends('admin.layouts.app')
@section('title', '文章列表')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_article_type'); ?>">栏目管理</a><span lay-separator="">/</span>
    <a href="<?php echo route('admin_article'); ?>"><cite>文章列表</cite></a><span lay-separator="">|</span>
    <a href="<?php echo route('admin_article'); ?>?status=1">未审核文章</a>
  </span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
  <form action="<?php echo route('admin_article'); ?>" method="get" class="layui-form layui-col-md12">
    搜索：
      <div class="layui-input-inline">
        <select name="type_id">
          <option value="0">请选择栏目</option>
          <?php if ($article_type_list) { foreach ($article_type_list as $row) { ?><option value="<?php echo $row["id"]; ?>"><?php for($i=0;$i<$row["deep"];$i++){echo "—";}echo $row["name"]; ?></option><?php }} ?>
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
    <a href="<?php echo route('admin_article_add'); ?><?php if (!empty($_GET["id"])) { echo '?type_id=' . $_GET["id"]; }?>" class="layui-btn layui-btn-danger"><i class="layui-icon layui-icon-add-circle"></i>发布文章</a>
  </div>

  <div class="layui-row">
    <table class="layui-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>选择</th>
          <th>文章标题</th>
          <th>更新时间</th>
          <th>栏目</th><th>点击</th><th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
          <td><?php echo $v->id; ?></td>
          <td><input name="arcID" type="checkbox" value="<?php echo $v->id; ?>"></td>
          <td><a href="<?php echo route('admin_article_edit'); ?>?id=<?php echo $v->id; ?>"><?php echo $v->title; ?></a> <?php if (!empty($v->litpic)) { echo "<small style='color:red'>[图]</small>"; } if ($v->tuijian == 1) { echo "<small style='color:#22ac38'>[荐]</small>"; } ?> </td>
          <td><?php echo date('Y-m-d H:i', $v->update_time); ?></td>
          <td><a href="<?php echo route('admin_article'); ?>?type_id=<?php echo $v->type_id; ?>"><?php echo $v->type_id_text; ?></a></td><td><?php echo $v->click; ?></td><td><a target="_blank" href="/p/<?php echo $v->id; ?>">预览</a>&nbsp;<a href="<?php echo route('admin_article_edit'); ?>?id=<?php echo $v->id; ?>">修改</a>&nbsp;<a onclick="confirm_prompt('<?php echo route('admin_article_del'); ?>?id=<?php echo $v->id; ?>')" href="javascript:;">删除</a></td>
        </tr>
        <?php } } ?>
        <tr>
            <td colspan="8" class="layui-text">
            <a href="javascript:selAll('arcID')">反选</a>&nbsp;
            <a href="javascript:delArc()">删除</a>&nbsp;
            <a href="javascript:tjArc()">特荐</a>
            </td>
        </tr>
      </tbody>
    </table>
    <div class="backpages">{{ $list->links() }}</div>
  </div>
</div>

<script>
//推荐文章
function tjArc(aid)
{
	var checkvalue=getItems();
	
	if(checkvalue=='')
	{
		alert('必须选择一个或多个文档！');
		return;
	}
	
	if(confirm("确定要推荐吗"))
	{
		location="<?php echo route('admin_article_recommendarc'); ?>?id="+checkvalue;
	}
	else
	{
		
	}
}

//批量删除文章
function delArc(aid)
{
	var checkvalue=getItems();
	if (checkvalue=='') {
		alert('必须选择一个或多个文档！');
		return;
	}
	if(confirm("确定删除吗")) {
		location="<?php echo route('admin_article_del'); ?>?id="+checkvalue;
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