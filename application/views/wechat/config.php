<div class="row">
	<div class="col-md-12">
	<!-- BEGIN PAGE TITLE & BREADCRUMB-->
	<h3 class="page-title">微信配置</h3>
	<!-- END PAGE TITLE & BREADCRUMB-->
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption"><i class="icon-reorder"></i></div>
			</div>
			<div class="portlet-body form">
				<form id="wechat_config" class="form-horizontal form-bordered" action="<?=site_url('admin/wechat/config_save')?>">
					<?php foreach ($config as $key => $row): ?>						
					<div class="form-group">
						<label class="col-sm-2 control-label"><?=$row['label']?></label>
						<div class="col-sm-10">
							<input class="form-control" name="<?=$row['name']?>" type="text" value="<?=$row['value']?>" >
						</div>
					</div>
					<?php endforeach ?>
					<div class="form-actions fluid">
					    <div class="col-md-offset-2 col-md-9">
					        <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
					    </div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function () {
    var form = $("#wechat_config").Validform({
    	btnSubmit:"#btn_sub",
        tiptype:3,
        ajaxPost:true,
        callback:function(data){
        	if(data.status == 'y'){
	            $('#wechat_config_view').click();
        	}
            $('#Validform_msg').hide();
        }
    });
})
</script>