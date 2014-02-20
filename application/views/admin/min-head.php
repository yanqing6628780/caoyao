<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/data-tables/DT_bootstrap.css" />
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/data-tables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/data-tables/DT_bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>
jQuery(document).ready(function() {       
	$('table.table').dataTable( {
		"aoColumnDefs": [
			{"bSortable": false, "aTargets": [ 0 ] }
		],
		"aaSorting": [[1, 'asc']],
		"aLengthMenu": [
			[5, 15, 20, -1],
			[5, 15, 20, "All"] // change per page values here
		],
		// set the initial value
		"iDisplayLength": 2,
	});
});
</script>
<!-- BEGIN PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
	<!-- BEGIN PAGE TITLE & BREADCRUMB-->
	<h3 class="page-title"><?php echo $title?></h3>
	<!-- END PAGE TITLE & BREADCRUMB-->
	</div>
</div>
<!-- END PAGE HEADER-->