<form id="good_form">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">
		<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
	</button>
	<h4 class="modal-title" id="myModalLabel"><?=$row['product_name']?></h4>
</div>
<div class="modal-body">
	<div class="media">
		<a class="pull-left" href="#">
			<img src="http://placehold.it/300x200/999999" alt="...">
		</a>
		<div class="media-body">
			<h3 class="media-heading"></h3>
			<h4>				
				<p>单价: <span class='text-danger'><?=$row['unit_price']?> 元</span></p>
				<p>描述: </p>
			</h4>
		</div>
	</div>
	<?php if($colors): ?>
	<table class="table table-striped table-bordered" style="margin-top:10px">
		<thead>
			<tr>
				<th>#</th>
				<?php foreach ($colors as $key => $value):?>
				<th><?=$value['values']?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($sizes as $key => $value):?>
			<tr>
				<td><?=$value['values']?></td>
				<?php foreach ($colors as $k => $v):?>
				<td>
					<div class="input-group spinner">
						<div class="spinner-buttons input-group-btn">
							<button type="button" class="btn spinner-down btn-success">
							<i class="glyphicon glyphicon-minus"></i>
							</button>
						</div>
						<input name="color[<?=$v['id']?>][<?=$value['id']?>]" type="text" class="spinner-input form-control">
						<div class="spinner-buttons input-group-btn">
							<button type="button" class="btn spinner-up btn-danger">
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
<script type="text/javascript" src="<?=site_url('js/spinner.min.js')?>"></script>
<script type="text/javascript">
$(function () {
	$('.spinner').spinner({value:0,min:0});

	$('#add_to_cart').click(function(event) {
		var data = $('#good_form').serialize();
		$.ajax({
	        type: "POST",
	        url: siteUrl('goods/to_cart'),
	        data: data,
	        dataType: "json",
	        success: function (response) {
	        	if(response.success){
	        		alert('成功加入购物车');
	        	}else{
	        		alert('加入购物车失败');
	        	}
	        }
	    });
	});

	$('#save_order').click(function(event) {
		var data = $('#good_form').serialize();
		$.ajax({
	        type: "POST",
	        url: siteUrl('goods/to_order'),
	        data: data,
	        dataType: "json",
	        success: function (response) {
	        	if(response.success){
	        		alert('保存成功');
	        	}else{
	        		alert('保存失败');
	        	}
	        }
	    });
	});
})
</script>