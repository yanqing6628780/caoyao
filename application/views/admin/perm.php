<?php echo $this->load->view('admin/table_head'); ?>
<form id="perm_from" action="<?php echo site_url('admin/user_admin/permissions')?>" method="post">
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
        	<div class="portlet-title">
        	    <div class="caption"><i class="icon-globe"></i>权限列表</div>
        	    <div class="tools">
        	        <a class="collapse" href="javascript:;"></a>
        	    </div>
        	    <div class="actions">
        	    	<div class="btn-group">
						<input type="button" name="show" value="显示权限"  class="btn blue" onclick="showPerm()" />
						<input type="button" name="save" value="保存"  class="btn green" onclick="savePerm()"/>
					</div>
					<div class="btn-group">
						<select id="role" name='role'>
						    <?php foreach($roles as $key => $row):?>
						        <option value='<?=$row->id?>' <?php if($row->id == $current_role){echo "selected";}?> ><?=$row->cnname?></option>
						    <?php endforeach;?>
						</select>
					</div>
        	    </div>
        	</div>
			<div class="portlet-body">
				<table class='table table-striped table-bordered table-hover' id="Ptable">
					<thead>
						<tr>
							<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#Ptable .checkboxes" /></th>
							<th>权限名称</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($perms as $key => $perm){ ?>
						<tr>
							<td><input type="checkbox" class="checkboxes" name="perms[]" value="<?php echo $perm['action_code']?>" <?php if($perm['hasperm']){echo 'checked';}?> /></td>
							<td><?php echo $perm['name']?>(<?php echo $perm['action_code']?>)</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>							
			</div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
jQuery(document).ready(function() {       
    jQuery('#Ptable .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
        jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }
        });
        jQuery.uniform.update(set);
    });
});
function showPerm()
{
	var role = $('#role').val();
    $.ajax({
        type: "GET",
        cache: false,
        url: siteUrl('admin/user_admin/permissions?role='+ role),
        dataType: "html",
        success: function (res) {
        	pageContentBody.html(res);
            App.initAjax();
        },
        async: false
    });
}
function savePerm()
{
    $.ajax({
        type: "POST",
        url: siteUrl('admin/user_admin/perms_save'),
        dataType: 'json',
        data: $("#perm_from").serialize(),
        success: function(respone){
        	if(respone.success){
        		alert(respone.msg)
        	}
        }
    });
}
jQuery(document).ready(function() {   
	$('#role').select2();
})
</script>