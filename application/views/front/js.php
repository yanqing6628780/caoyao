<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
		</div>
	</div>
</div>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=site_url('js/bootstrap.min.js')?>"></script>
<script type="text/javascript">
var BASEURL = '<?php echo base_url()?>'
function resetPassword()
{
    $("#msgbox1").empty()
    $("#msgbox1").dialog({"title": "重置密码",width:400})
    $("#msgbox1").dialog("open")
    LoadAjaxPage('member/reset_password', {}, 'msgbox1')
}
</script>
<script type="text/javascript" src="<?=base_url('js/validform_v5.3.2.js')?>"></script>
<script type="text/javascript" src="<?=base_url('js/sco.ajax.js')?>"></script>
<script type="text/javascript" src="<?=base_url('js/api.js')?>"></script>
<script type="text/javascript">
$(function () {
	$("#myModal").on("hidden.bs.modal", function() {
	    $(this).removeData("bs.modal");
	});

	$('#search').submit(function(event) {
		var $this = $(this),
			data = $this.serialize();

		LoadAjaxPage(siteUrl('goods/query'), data, 'main');
		return false;
	});
})
</script>