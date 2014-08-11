<body>
<?php $this->load->view('front/nav'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2 col-md-1 sidebar">
			<ul class="nav nav-sidebar">
				<?php foreach ($small_classes as $key => $value): ?>					
				<li><a data-trigger="ajax" href="<?=site_url('goods/?class='.$value['id'])?>" data-target="#main" ><?=$value['small_class_name']?></a></li>
				<?php endforeach ?>
			</ul>
		</div>
		<div id="main" class="col-sm-10 col-sm-offset-2 col-md-11 col-md-offset-1 main">
			
		</div>
	</div>
</div>
<?php $this->load->view('front/footer'); ?>
<?php $this->load->view('front/js'); ?>
</body>
</html>