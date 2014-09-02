<form id="good_form">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">
		<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
	</button>
	<h4 class="modal-title" id="myModalLabel"><?=$row['product_name']?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-9">
			<div class="media">
				<a class="pull-left" href="#">
					<img width="300" src="<?=$row['picture'] ? $row['picture'] : 'http://placehold.it/300x200/999999'?>" alt="...">
				</a>
				<div class="media-body">
					<h3 class="media-heading"></h3>
					<h4>				
						<p>单价: <span class='text-danger'><?=$row['unit_price']?> 元</span></p>
						<p>描述: </p>	
					</h4>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<h4>相关产品</h4>
			<div style="max-height:263px;overflow:auto">
			<?php 
			foreach ($rel_products as $key => $pid): 
				if($pid != $row['id']): $rel_row = $this->product_mdl->get_product_info($pid);
			?>
			<img data-target="#rel_product" data-href="<?=site_url('goods/id/'.$pid.'?view=rel');?>" onclick="show_modal(this)"  src="<?=$rel_row->picture ? $rel_row->picture : 'http://placehold.it/200x200/999999'?>" title="<?=$rel_row->product_name?>" alt="<?=$rel_row->product_name?>" class="img-responsive img-rounded center-block">
			<?php endif;endforeach; ?>
			</div>
		</div>
	</div>
	<?php if($colors): ?>
	<table class="table table-striped table-bordered" style="margin-top:10px">
		<thead>
			<tr>
				<th>#</th>
				<?php foreach ($colors as $key => $value):?>
				<?php $nec_row = $this->RSTR_mdl->get_necessities($necessities_scheme_id, $row['id'], $value['id']);  ?>
				<th>
					<h5 style="margin:0"><?=$value['values']?> <?php if($nec_row): ?>: 至少购买 <span class="label label-danger"><?=$nec_row['MQP'] ?></span> 件 <?php endif ?> </h5>
				</th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($sizes as $key => $value):?>
			<tr>
				<td><?=$value['values']?></td>
				<?php foreach ($colors as $k => $color):?> 	
				<td>
					<div class="input-group spinner" data-trigger="spinner">
						<div class="spinner-buttons input-group-btn">
							<button type="button" class="btn spin-down btn-success" data-spin="down">
							<i class="glyphicon glyphicon-minus"></i>
							</button>
						</div>
						<input class="spinner-input form-control" name="color[<?=$color['id']?>][<?=$value['id']?>]" type="text" data-min="0" value="<?=$this->order_mdl->get_qty($order_id, $row['id'], $color['id'], $value['id']) ?>">
						<div class="spinner-buttons input-group-btn">
							<button type="button" class="btn spin-up btn-danger" data-spin="up">
							<i class="glyphicon glyphicon-plus"></i>
							</button>
						</div>
					</div>
				</td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
	<div class="row">
		<div class="col-md-3">		
			<h3><small>数量:</small><span id="total_qty" class="text-danger">0</span></h3>
		</div>
		<div class="col-md-3">	
			<h3><small>合计:</small><span id="total" class="text-danger">0</span></h3>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
	<button id="save_order" type="button" class="btn btn-primary">保存订单</button>
	<!-- <button id="add_to_cart" type="button" class="btn btn-primary">加入购物车</button> -->
	<input name="price" type="hidden" value="<?=$row['unit_price']?>">
	<input name="id" type="hidden" value="<?=$row['id']?>">
	<input name="name" type="hidden" value="<?=$row['product_name']?>">
</div>
</form>
<link rel="stylesheet" href="<?=site_url('css/bootstrap-spinner.css')?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?=site_url('js/jquery.spinner.min.js')?>"></script>
<script type="text/javascript" src="<?=site_url('js/jquery.money.js')?>"></script>
<script type="text/javascript">
$(function () {
	var $inputs = $('#good_form').find('.spinner-input');
	var $total_qty = $('#total_qty');
	var $total = $('#total');
	var price = <?=$row['unit_price']?>;
	$(".spinner")
		.spinner('delay', 200)
		.spinner('changed', function(e, newVal, oldVal){
			sum();
		});

	$('#save_order').click(function(event) {
		var data = $('#good_form').serialize();
		$.ajax({
	        type: "POST",
	        url: siteUrl('goods/to_order'),
	        data: data,
	        dataType: "json",
	        success: function (response) {
        		alert(response.info);
	        }
	    });
	});

	sum();

	// 计算出当前价格和件数
	function sum() {
		var total=0,
			total_qty=0;
		$.each($inputs, function(index, el) {
			total_qty +=  parseInt(el.value);
		});
		total = price*total_qty;

		$total_qty.text(total_qty);
		$total.money(total,{ commas: true, symbol: "￥" });
	}
})
</script>