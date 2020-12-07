@extends('admin.layouts.app')
@section('title', '提现申请列表')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_user_withdraw'); ?>"><cite>提现申请列表</cite></a>
  </span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
  <form action="<?php echo route('admin_user_withdraw'); ?>" method="get" class="layui-form layui-col-md12">
    搜索：
    <div class="layui-inline">
      <input type="text" id="keyword" name="keyword" placeholder="请输入关键字" autocomplete="off" class="layui-input">
    </div>
    <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon layui-icon-search"></i></button>
  </form>
</div>
<div class="layui-fluid">
  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
            <th>ID</th>
			<th>用户名</th>
			<th>提现金额</th>
			<th>姓名</th>
			<th>收款方式</th>
			<th>收款账号</th>
			<th>申请时间</th>
			<th>状态</th>
			<th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
            <td><?php echo $v->id; ?></td>
			<td><?php echo $v->user->user_name; ?><?php if ($v->user->mobile) { echo '<br>TEL:'.$v->user->mobile; } ?></td>
			<td><font color="red"><?php echo $v->money; ?></font></td>
			<td><?php echo $v->name; ?></td>
			<td><?php echo $v->method; ?></td>
			<td>账号：<?php echo $v->account; if ($v->bank_name) { echo '<br>银行名称：'.$v->bank_name; } if ($v->bank_place) { echo '<br>开户行：'.$v->bank_place; } ?></td>
			<td><?php echo date('Y-m-d H:i:s', $v->add_time); ?></td>
			<td><?php echo $v->status_text; ?></td>
			<td><?php if ($v->status == 0) { ?><a href="javascript:change_status(<?php echo $v->id; ?>,'1');">成功</a>&nbsp;<a href="javascript:change_status(<?php echo $v->id; ?>,'0');">拒绝</a><?php } ?></td>
		</tr>
        <?php } } ?>
      </tbody>
    </table>
    <div class="backpages">{{ $list->links() }}</div>
  </div>
</div>

<script>
layui.use(['jquery', 'form', 'layer'], function () {
  var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
  var layer = layui.layer;
  //……
  //但是，如果你的HTML是动态生成的，自动渲染就会失效
  //因此你需要在相应的地方，执行下述方法来手动渲染，跟这类似的还有 element.init();
  form.render();
});

function change_status(id, type)
{
    //询问框
    layer.confirm('您确定要执行此操作吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        var url = window.location.href;
		$.ajax({
            type: 'POST',
            url: '<?php echo route('admin_user_withdraw_change_status'); ?>',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: {id:id, type:type},
            success: function(result){
				console.log(result);
               //提示层
				layer.msg(result.msg,{
					time: 2000, //2s后自动关闭
				});
				if (result.code==0) {
					setTimeout(function () { location.href = url; }, 1000);
				}
            },
            error: function(e){
                
            }
		});
	}, function(){
			
	});
}
</script>
@endsection