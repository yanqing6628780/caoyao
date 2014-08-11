<div class="row goods_list">
<?php foreach ($result as $key => $value):?>
	<div class="col-sm-4 col-md-3">
		<div class="media">
			<a class="pull-left" href="#">
				<img src="http://placehold.it/100x100/999999" alt="...">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><?=$value['product_name']?></h4>
				<p>单价: <span><?=$value['unit_price']?></span>元</p>
				<p class="pull-right"><a href="<?=site_url('goods/id/'.$value['id']);?>" class="btn btn-info" role="button" data-toggle="modal" data-target="#myModal">下订单</a></p>
			</div>
		</div>
	</div>
<?php endforeach; ?>
</div>