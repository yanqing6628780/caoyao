<body>
<script type="text/javascript">
$(function(){
	
})
</script>
<?php echo $this->load->view('admin/header'); ?>
<?php echo $this->load->view('admin/left_menu'); ?>
<div id="content">
    <iframe width="100%" scrolling="auto" frameborder="false" allowtransparency="true" src="<?php echo site_url('admin/party/')?>" id="content-iframe" name="right"></iframe>
</div>
</body>
</html>
