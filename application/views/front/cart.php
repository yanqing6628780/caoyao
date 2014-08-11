<table class="table table-striped" >
	<thead>
		<tr>
			<th>#</th>
			<th>宝贝名称</th>
			<th>规格</th>
			<th>数量</th>
			<th>单价</th>
			<th>小计</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		<?php foreach ($contents as $key => $items):?>
		<tr>
			<td><?=$i?></td>
			<td><?php echo $items['name']; ?></td>
			<td>
				<?php foreach ($items['options'] as $option_key => $option_value): ?>
				<p><strong><?=$this->attr_mdl->attr_type_to_cn($option_key); ?>:</strong> <?=$this->attr_mdl->get_attr_values($option_value);?></p>
				<?php endforeach; ?>
			</td>
			<td>
				<div class="input-group spinner" data-rowid="<?=$key?>">
					<div class="spinner-buttons input-group-btn">
						<button type="button" class="btn spinner-down btn-success">
						<i class="glyphicon glyphicon-minus"></i>
						</button>
					</div>
					<input value="<?=$items['qty'] ?>" type="text" class="spinner-input form-control" readOnly>
					<div class="spinner-buttons input-group-btn">
						<button type="button" class="btn spinner-up btn-danger">
						<i class="glyphicon glyphicon-plus"></i>
						</button>
					</div>
				</div>
			</td>
			<td>￥<?=$this->cart->format_number($items['price']); ?></td>
			<td>￥<?=$this->cart->format_number($items['subtotal']); ?></td>
		</tr>
		<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<div class="row">
	<div class="col-sm-7 pull-left"> 
		<a data-trigger="ajax" href="<?=site_url('cart/destroy')?>" data-target="#main" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> 清空购物车</a> 
		<a id="add_to_order" class="btn btn-success"><i class="glyphicon glyphicon-save"></i> 加入订单</a> 
	</div>
	<div class="col-sm-5 pull-right">
		<h4 class="pull-right">合计 :<span class="text-danger">￥<?=$this->cart->format_number($this->cart->total())?></span></h4>
	</div>
</div>
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
		LoadAjaxPage(siteUrl('cart/update'), {qty: qty, rowid: $this.data('rowid')}, 'main');
	});

	$('#add_to_order').click(function(event) {
		$.ajax({
	        type: "GET",
	        url: siteUrl('cart/to_order'),
	        dataType: "json",
	        success: function (resp) {
	            if(resp.success){
	            	LoadAjaxPage(siteUrl('orders'), {}, 'main');
	            }else{
	            	alert('下单失败');
	            }
	        }
	    });
	});
})
</script>