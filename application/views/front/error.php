<body>
<?php $this->load->view('front/nav'); ?>
<div class="page-container">
	<?php $this->load->view('front/breadcrumbs'); ?>
	<div class="container margin-bottom-40">
      <div class="row">
        <div class="col-md-12 page-500">
            <div class="number"><?=$code?></div>
            <div class="details">
                <h3><?=$head?></h3>
                <p><?=$msg?></p>
            </div>
        </div>
      </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
</body>
</html>