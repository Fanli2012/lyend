@extends('admin.layouts.app')
@section('title', '订单详情')

@section('content')
<div class="admin-content-box-nav">
  <span class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility:visible;">
    <a href="<?php echo route('admin_order'); ?>">订单列表</a><span lay-separator="">/</span>
    <a href=""><cite>订单详情</cite></a>
  </span>
</div>
<div class="layui-row" style="padding:15px;border-bottom:1px solid #f6f6f6;">
    当前可执行操作：
	<?php if($post->order_status == 0 && $post->shipping_status == 0 && $post->pay_status == 1) { ?><button class="layui-btn layui-btn-normal" onclick="fahuo_layer(<?php echo $post->id; ?>)">发货</button> <?php } ?>
	<?php if($post->order_status == 0 && $post->pay_status == 0) { ?><button class="layui-btn" onclick="change_status(<?php echo $post->id; ?>,2)">设为已付款</button> <?php } ?>
	<?php if($post->order_status == 0 && $post->refund_status == 0 && $post->shipping_status == 1 && $post->pay_status == 1) { ?><button class="layui-btn layui-btn-normal" onclick="change_status(<?php echo $post->id; ?>,4)">设为已收货</button> <?php } ?>
	<?php if($post->order_status == 0 && $post->pay_status == 0) { ?><button class="layui-btn layui-btn-danger" onclick="change_status(<?php echo $post->id; ?>,7)">设为无效</button> <?php } ?>
	<?php if($post->order_status == 3 && $post->refund_status == 1) { ?><button class="layui-btn layui-btn-danger" onclick="change_status(<?php echo $post->id; ?>,8)">设为已退货</button> <?php } ?>
	<button class="layui-btn layui-btn-warm" onclick="javascript:history.back(-1);">返回</button>
</div>

<div class="layui-fluid" style="padding:15px;">
<h2>基本信息</h2>
<!-- 表格开始 -->
<table class="layui-table">
<thead><tr class="info">
<th>订单编号</th>
<th>订单状态</th>
<th>下单人</th>
<th>下单时间</th>
<th>支付方式</th>
<th>支付时间</th>
<th>配送方式</th>
<th>快递单号</th>
<th>发货时间</th>
</tr></thead>
<tbody>
<tr>
    <td><?php echo $post->order_sn; ?></td>
    <td><font color="red"><?php echo $post->order_status_text; ?></font></td>
    <td><?php if($post->user->mobile) { echo $post->user->mobile; } else { echo $post->user->user_name; } ?></td>
    <td><?php echo date('Y-m-d H:i:s', $post->add_time); ?></td>
    <td><?php echo $post->pay_name; ?></td>
    <td><?php if($post->pay_time){echo date('Y-m-d H:i:s', $post->pay_time);} ?></td>
    <td><?php echo $post->shipping_name; ?></td>
    <td><?php echo $post->shipping_sn; ?></td>
    <td><?php if($post->shipping_time){echo date('Y-m-d H:i:s',$post->shipping_time);} ?></td>
</tr>
<tr>
    <td colspan="1">订单来源：<?php echo $post->place_type_text; ?></td>
	<td colspan="8">客户留言：<?php echo $post->message; ?></td>
</tr>
</tbody>
</table><!-- 表格结束 -->

<h2>收货人信息</h2>
<!-- 表格开始 -->
<table class="layui-table">
<thead><tr class="info">
<th>收货人姓名</th>
<th>电话</th>
<th>详细地址</th>
</tr></thead>
<tbody>
<tr>
    <td><?php echo $post->name; ?></td>
    <td><?php echo $post->mobile; ?></td>
    <td><?php echo $post->province_name . $post->city_name . $post->district_name . ' ' . $post->address; ?></td>
</tr>
</tbody>
</table><!-- 表格结束 -->

<h2>商品信息</h2>
<!-- 表格开始 -->
<table class="layui-table">
<thead><tr class="info">
<th>商品缩略图</th>
<th>商品名称</th>
<th>商品价格</th>
<th>数量</th>
<th>合计</th>
<th>退货/退款</th>
<th>退货退款理由</th>
</tr></thead>
<tbody>
<?php if ($post->goods_list) { foreach ($post->goods_list as $key => $value) { ?>
<tr>
    <td width="98px"><img src="<?php echo $value->goods_img; ?>" style="width:80px;height:60px;"></td>
    <td><?php echo $value->goods_name; ?></td>
    <td><?php echo $value->goods_price; ?></td>
    <td><?php echo $value->goods_number; ?></td>
    <td><font color="red"><?php echo $value->goods_price * $value->goods_number; ?></font></td>
    <td><?php echo $value->refund_status_text; ?></td>
    <td><?php echo $value->refund_reason; ?></td>
</tr>
<?php } } ?>
</tbody>
</table><!-- 表格结束 -->

<?php if(empty($post->invoice) || $post->invoice != 0){ ?>
<h2>发票信息</h2>
<!-- 表格开始 -->
<table class="layui-table">
<thead><tr class="info">
<th>发票类型</th>
<th>发票抬头</th>
<th>纳税人识别号</th>
</tr></thead>
<tbody>
<tr>
    <td><?php echo $post->invoice_text; ?></td>
    <td><?php echo $post->invoice_title; ?></td>
    <td><?php echo $post->invoice_taxpayer_number; ?></td>
</tr>
</tbody>
</table><!-- 表格结束 -->
<?php } ?>

<h2>费用结算</h2>
<!-- 表格开始 -->
<table class="layui-table">
<thead><tr class="info">
<th>商品总金额</th>
<th>邮费</th>
<th>优惠券</th>
<th>积分</th>
<th>其它费用</th>
<th>应付金额</th>
</tr></thead>
<tbody>
<tr>
    <td><?php echo $post->goods_amount; ?></td>
    <td>+<?php echo $post->shipping_fee; ?></td>
    <td>-<?php echo $post->bonus_money; ?></td>
    <td>-<?php echo $post->integral_money; ?></td>
    <td>-<?php echo $post->discount; ?></td>
    <td><font color="red"><?php echo $post->order_amount; ?></font></td>
</tr>
</tbody>
</table><!-- 表格结束 -->
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
function fahuo_layer(order_id)
{
    //自定页
    layer.open({
        title: '发货管理',
        shadeClose: true, //开启遮罩关闭
        content: '快递方式：<select name="shipping_id" id="shipping_id"><option value="0">无须物流</option><?php if ($kuaidi) { foreach ($kuaidi as $k=>$v) { ?><option value="<?php echo $v->id ?>"><?php echo $v->name ?></option><?php } } ?></select><br>快递单号：<input size="30" type="text" name="shipping_sn" id="shipping_sn" placeholder="">'
        ,btn: ['确认', '取消']
        ,yes: function(index, layero){
            var shipping_id = $("#shipping_id").val();
            var shipping_sn = $("#shipping_sn").val();
            
            if (shipping_id != 0) { if (shipping_sn=='') { layer.msg('请填写快递单号'); return false; } }
            
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: '<?php echo route('admin_order_change_shipping'); ?>',
				headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
				data: {id:order_id,shipping_id:shipping_id,shipping_sn:shipping_sn},
				success: function(result){
					console.log(result);
					if (result.code == 0) {
						layer.msg('操作成功');
						setTimeout(function(){ window.location.reload(); }, 1000);
					} else {
						layer.msg('操作失败');
						return false;
					}
				},
				error: function(e){
					
				}
			});
            
            layer.close(index);
        }
        ,btn2: function(index, layero){
            
        }
        ,cancel: function(){
            //右上角关闭回调
        }
    });
}

function change_status(order_id, status)
{
    //询问框
    layer.confirm('确定要这样操作？', {
        btn: ['确定','取消'] //按钮
    }, function(){
		$.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo route('admin_order_change_status'); ?>',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: {id:order_id, status:status},
            success: function(result){
				console.log(result);
				if (result.code == 0) {
					layer.msg('操作成功');
					setTimeout(function(){ window.location.reload(); }, 1000);
				} else {
					layer.msg('操作失败');
					return false;
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