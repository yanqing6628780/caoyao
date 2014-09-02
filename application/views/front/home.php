<body>
<?php $this->load->view('front/nav'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<?php foreach ($small_classes as $key => $value): ?>					
				<li><a style="margin-bottom:3px" data-trigger="ajax" href="<?=site_url('goods/?class='.$value['id'])?>" data-target="#main" ><?=$value['small_class_name']?></a></li>
				<?php endforeach ?>
			</ul>
		</div>
		<div id="main" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			
		</div>
	</div>
</div>
<?php $this->load->view('front/footer'); ?>
<?php $this->load->view('front/js'); ?>

</body>
</html>