<div class="row goods_list">
<?php foreach ($result as $key => $value):?>
	<div class="col-sm-4 col-md-3">
		<div class="media">
			<a class="pull-left" href="#">
				<img width="100" src="<?=$value['picture'] ? $value['picture'] : 'http://placehold.it/100x100/999999'?>" alt="...">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><?=$value['product_name']?></h4>
				<p>单价: <span><?=$value['unit_price']?></span>元</p>
				<?php 
				if( $necessities_scheme_id = $this->session->userdata('necessities_scheme_id') ){ 
					$nec_rs = $this->RSTR_mdl->get_necessities($necessities_scheme_id, $value['id']);
				?>
				<?php if($nec_rs): ?>
				<p>必买款: 
					<?php foreach ($nec_rs as $row): ?>
						<span class="badge bg-warning"><?=$row['values']?></span>
					<?php endforeach ?>
				</p>
				<?php endif; ?>
				<?php } ?>
			</div>
			<div class="clear btn-box">
				<a class="pull-right btn btn-info"  href="<?=site_url('goods/id/'.$value['id']);?>" role="button" data-toggle="modal" data-target="#myModal">下订单</a>
				<a class="pull-left" title="<?=$value['sum_score']?>分" href="<?=site_url('goods/rating/'.$value['id']);?>" role="button" data-toggle="modal" data-target="#myModal2">
					<input value="<?=$value['sum_score']?>" type="number" class="rating" data-size="xs" data-disabled="true" data-show-clear="false" data-show-caption="false"/>
				</a>
			</div>
		</div>
	</div>
<?php endforeach; ?>
</div>
<link rel="stylesheet" href="<?=base_url('css/star-rating.min.css')?>">
<style>
.rating-xs {font-size: 1.5em;}
</style>
<script type="text/javascript" src="<?=base_url('js/star-rating.min.js')?>"></script>
<script type="text/javascript">
$(function () {
	$('.rating').rating();
})
</script>