<body>
<?php $this->load->view('front/nav'); ?>
<div class="page-container">
	<?php $this->load->view('front/breadcrumbs'); ?>
	<div class="container min-hight">
		<div class="row">
			<div class="col-md-12 blog-item">
				<div class="blog-item-img">
				</div>
				<h2><a href="#"><?=$detail->company?></a></h2>
				<ul>
					<li>电话:<?=$detail->tel?></li>
					<li>联系人:<?=$detail->name?></li>
					<li>手机:<?=$detail->mobile?></li>
					<li>网址:<?=$detail->website?></li>
				</ul>
				<div class="well">
					<h4>地址</h4>
					<?=$detail->address?>
				</div>				
				<ul class="blog-info">
					<li><i class="icon-tags"></i> <?=$detail->type?> <?=$detail->actingbrand?> <?=$detail->province?> <?=$detail->city?> <?=$detail->district?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('front/footer'); ?>
<script type="text/javascript">
$(function () {
	var form = $("#search").Validform({
	    tiptype:1,
	    ajaxPost:false,
	    callback:function(data){

	    }
	});
})
</script>
</body>
</html>