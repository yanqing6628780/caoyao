<form id="order_products">
<button type="button" class="btn btn-warning" onclick="update_order_products()">更新数量</button>
<a data-trigger="ajax" href="<?=site_url('orders/my')?>" data-target="#main" class="btn btn-info navbar-btn" type="button">返回</a>   
<table class="clear table table-striped" >
	<thead>
		<tr>
			<th>#</th>
			<th>商品条码</th>
			<th>宝贝名称</th>
			<th>规格</th>
			<th>数量</th>
			<th>单价</th>
			<th>小计</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($products as $key => $row):?>
		<tr>
			<td><?=$key+1?></td>
			<td><?=$row['info']['barcode']; ?></td>
			<td><?=$row['info']['product_name']; ?></td>
			<td>
				<p><b>颜色</b>:<?=$this->attr_mdl->get_attr_values($row['first_attribute_values_id'])?></p>
				<p><b>尺码</b>:<?=$this->attr_mdl->get_attr_values($row['second_attribute_values_id'])?></p>
			</td>
			<td class="col-md-3 ">
				<div class="input-group spinner" data-trigger="spinner">
					<div class="spinner-buttons input-group-btn">
						<button type="button" class="btn spin-down btn-success" data-spin="down">
						<i class="glyphicon glyphicon-minus"></i>
						</button>
					</div>
					<input data-rowid="<?=$row['id']?>" name="order_product[<?=$row['id']?>]" class="col-md-3 form-control" type="text" value="<?=$row['qty'] ?>" data-min="0">
					<div class="spinner-buttons input-group-btn">
						<button type="button" class="btn spin-up btn-danger" data-spin="up">
						<i class="glyphicon glyphicon-plus"></i>
						</button>
					</div>
				</div>
			</td>
			<td>￥<?=formatAmount($row['info']['unit_price']); ?>元</td>
			<td>￥<?=formatAmount($row['info']['unit_price']*$row['qty']); ?>元</td>
			<td><button onclick="del(<?=$row['id'] ?>)" class="btn btn-danger">删除</a></button></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<button type="button" class="btn btn-warning" onclick="update_order_products()">更新数量</button>
<a data-trigger="ajax" href="<?=site_url('orders/my')?>" data-target="#main" class="btn btn-info navbar-btn" type="button">返回</a>    
<input name="order_id" value="<?=$order_id?>" type="hidden">
<input name="order_num" value="<?=$order_num?>" type="hidden">
<input name="product_id" value="<?=$product_id?>" type="hidden">
</form>
<link rel="stylesheet" href="<?=site_url('css/bootstrap-spinner.css')?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?=site_url('js/jquery.spinner.min.js')?>"></script>
<script type="text/javascript">
$(function () {
	$(".spinner")
		.spinner('delay', 200)
		.spinner('changed', function(e, newVal, oldVal){
			console.log(e);
			console.log($(this));
			if( newVal == 0 ){
				if(confirm('确定删除该商品?')){
					del($(this).data('rowid'))
				}else{
					$(this).val(1);
				}
			}
			// var data = {qty: newVal, rowid: $(this).data('rowid'), order_id: <?=$order_id?>, order_num: <?=$order_num?>, order_id: <?=$order_id?>, product_id: <?=$product_id?>};
			// LoadAjaxPage(siteUrl('orders/product_update'), data, 'main');
		});	
})
function update_order_products () {
	$.scojs_message.options.delay = 1500;
	$.ajax({
	    type: "POST",
	    url: siteUrl('orders/product_update'),
	    dataType: 'json',
	    data: $('#order_products').serialize(),
	    success: function(respone){
	        if(respone.success){
	        	$.scojs_message('更新成功', $.scojs_message.TYPE_OK);
	        }
	    }
	});
	return false;
}
function del(id) {
	LoadAjaxPage(siteUrl('orders/product_delete/'+ id + '/?order_id=' + <?=$order_id ?>+ '&product_id=' + <?=$product_id ?>), {}, 'main');
}
</script>