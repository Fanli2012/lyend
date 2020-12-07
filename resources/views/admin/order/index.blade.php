@extends('admin.layouts.app')
@section('title', '订单列表')

@section('content')
<script language="javascript" type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_order'); ?>"><cite>订单列表</cite></a>
  </span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
  <form action="<?php echo route('admin_order'); ?>" method="get" class="layui-form layui-col-md12">
    搜索：
    <div class="layui-inline">
      <input type="text" id="keyword" name="keyword" placeholder="" autocomplete="off" class="layui-input">
    </div>
    <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="">查询</button>
    <button type="button" class="layui-btn layui-btn-danger" onclick="output_excel()">导出EXCEL</button>
  </form>
</div>
<div class="layui-fluid">
  <div class="layui-form">
    <table class="layui-table">
      <thead>
        <tr>
			<th>订单编号SN-ID</th>
			<th>支付信息</th>
			<th>收货人</th>
			<th>订单状态</th>
			<th>来源</th>
			<th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($list) { foreach ($list as $k => $v) { ?>
        <tr>
            <td><a href="<?php echo route('admin_order_detail'); ?>?id=<?php echo $v->id; ?>"><?php echo $v->order_sn; ?></a>, 金额：<?php echo $v->order_amount; ?><br>编号:<?php echo $v->id; ?> 下单时间：<?php echo date('Y-m-d H:i:s', $v->add_time); ?></td>
			<td><?php if ($v->pay_money) { echo '支付金额：' . $v->pay_money; } ?><?php if ($v->trade_no) { echo ', 流水号：' . $v->trade_no; } ?> <?php if ($v->pay_name) { echo '<br><font color="green">' . $v->pay_name.'</font>, '; } ?><?php if ($v->pay_time) { echo '支付时间：' . date('Y-m-d H:i:s', $v->pay_time); } ?></td>
			<td><?php echo $v->name . '[TEL:' . $v->mobile . ']'; ?><br><?php echo $v->province_name; ?><?php echo $v->city_name; ?><?php echo $v->district_name; ?></td>
			<td><?php if ($v->order_status_text == '待发货') { echo '<font color="red">' . $v->order_status_text . '</font>'; } else { echo $v->order_status_text; } ?></td>
			<td><?php echo $v->place_type_text; ?></td>
			<td><a href="<?php echo route('admin_order_detail'); ?>?id=<?php echo $v->id; ?>">详情</a></td>
		</tr>
        <?php } } ?>
      </tbody>
    </table>
    <div class="backpages">{{ $list->links() }}</div>
  </div>
</div>

<script>
layui.use(['jquery', 'form', 'layer', 'laydate'], function () {
	var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
	var layer = layui.layer;
	var laydate = layui.laydate;
    
    //……
    //但是，如果你的HTML是动态生成的，自动渲染就会失效
    //因此你需要在相应的地方，执行下述方法来手动渲染，跟这类似的还有 element.init();
    form.render();
});

function output_excel()
{
    layer.open({
        title: '导出EXCEL',
        area: ['460px', '300px'],
        shadeClose: true, //开启遮罩关闭
        content: '<form id="output-excel" action="<?php echo route('admin_order_output_excel'); ?>" method="get"><div class="form-inline"><div class="form-group"><label for="min_addtime">　　时间：</label><input size="15" onclick="WdatePicker({el:this,dateFmt:\'yyyy-MM-dd HH:mm:ss\'})" type="text" class="form-control" id="min_addtime" name="min_addtime" placeholder="开始时间"> - <input size="15" onclick="WdatePicker({el:this,dateFmt:\'yyyy-MM-dd HH:mm:ss\'})" type="text" class="form-control" id="max_addtime" name="max_addtime" placeholder="结束时间"></div></div><div class="form-inline mt10"><div class="form-group"><label for="num">导出数量：</label><input size="6" type="text" class="form-control" id="num" name="num" value="100" placeholder=""></div></div><div class="form-inline mt10"><div class="form-group"><label for="status">订单状态：</label><select id="status" class="form-control" name="status"><option value ="0">全部</option><option value ="1">待付款</option><option value="2">待发货</option><option value="3">待收货</option><option value="4">交易成功</option><option value="5">退款中</option></select></div></div><div class="form-inline mt10"><div class="form-group"><label for="name">　收货人：</label><input size="10" type="text" class="form-control" id="name" name="name" placeholder=""></div></div><div class="form-inline mt10"><div class="form-group"><label for="order_sn">　订单号：</label><input size="20" type="text" class="form-control" id="order_sn" name="order_sn" placeholder=""></div></div></form>'
        ,btn: ['导出', '取消']
        ,yes: function (index, layero) {
            $('#output-excel').submit();
            layer.close(index);
        }
        ,btn2: function (index, layero) {
            
        }
        ,cancel: function(){
            //右上角关闭回调
        }
    });
}
</script>
@endsection