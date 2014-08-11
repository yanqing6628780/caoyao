<div class="alert alert-info">
	<h3><small>订单号</small>: <?=$order_num?></h3>
</div>
<table class="table table-striped" >
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
			<td>
				<div class="input-group spinner" data-rowid="<?=$row['id']?>">
					<div class="spinner-buttons input-group-btn">
						<button type="button" class="btn spinner-down btn-success">
						<i class="glyphicon glyphicon-minus"></i>
						</button>
					</div>
					<input value="<?=$row['qty'] ?>" type="text" class="spinner-input form-control" readOnly>
					<div class="spinner-buttons input-group-btn">
						<button type="button" class="btn spinner-up btn-danger">
						<i class="glyphicon glyphicon-plus"></i>
						</button>
					</div>
				</div>
			</td>
			<td>￥<?=formatAmount($row['info']['unit_price']); ?>元</td>
			<td>￥<?=formatAmount($row['info']['unit_price']*$row['qty']); ?>元</td>
			<td><button onclick="del(<?=$row['id'] ?>, <?=$order_id ?>)" class="btn btn-danger">删除</a></button></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<script type="text/javascript" src="<?=site_url('js/spinner.min.js')?>"></script>
<script type="text/javascript">
$(function () {
	$('.spinner').spinner({value:0,min:0});
	$('.spinner').change(function(e,value) {
		var $this = $(this),
			qty = $(this).spinner('value');
		if( qty == 0 && !confirm('确定删除该商品?') ){
			qty = 1;
		}
		LoadAjaxPage(siteUrl('orders/product_update'), {qty: qty, rowid: $this.data('rowid'), order_id: <?=$order_id?>}, 'main');
	});
})
function del(id, order_id) {
	LoadAjaxPage(siteUrl('orders/product_delete/'+ id + '/?order_id=' + order_id), {}, 'main');
}
</script>