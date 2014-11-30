<?php echo $this->load->view('admin/table_head'); ?>
<form id="roles" action="<?php echo site_url('admin/user_admin/roles')?>" method="post">
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>     	
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i>角色列表</div>
				<div class="tools">
				    <a class="collapse" href="javascript:;"></a>
				</div>
				<?php if(chk_perm_to_bool('role_edit')):?>
				<div class="actions">
					<div class="btn-group">
						<span>父角色</span>
						<select name='role_parent'>
							<option value='0'>None</option>
						    <?php foreach($roles as $key => $row):?>
						        <option value='<?=$row->id?>'><?=$row->cnname?></option>
						    <?php endforeach;?>
						</select>
					</div>
					<div class="btn-group">
						<input type="text" name="role_name" value="" class="input-small" placeholder="角色英文名"/>
						<input type="text" name="role_cnname" value="" class="input-small"  placeholder="角色中文名"/>
					</div>
					<div class="btn-group">
						<input type="button" name="add" value="添加角色"  class="btn blue" onclick="addRoles()" />
						<input type="button" name="delete" value="删除选中的角色"  class="btn green" onclick="delRoles()"/>
					</div>
				</div>
				<?php endif;?>
			</div>
			<div class="portlet-body">
				<table class='table table-striped table-bordered table-hover' id="sample_1">
					<thead>
						<tr>
							<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
							<th>ID</th>
							<th>父ID</th>
							<th>角色英文名</th>
							<th>角色中文名</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($roles as $role){ ?>
						<tr>
							<td><input type="checkbox" class="checkboxes" name="checkbox[]" value="<?php echo $role->id?>"/></td>
							<td><?php echo $role->id?></td>
							<td><?php echo $role->parent_id?></td>
							<td><?php echo $role->name?></td>
							<td><?php echo $role->cnname?></td>
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
    TableAdvanced.init();
});
function addRoles()
{
    $.ajax({
        type: "POST",
        url:  "<?php echo site_url('admin/user_admin/add_roles') ?>",
        dataType: 'json',
        data: $("#roles").serialize(),
        success: function(respone){
        	$('#roles_view').click();
        }
    });
}
function delRoles()
{
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('admin/user_admin/del_roles') ?>",
        dataType: 'json',
        data: $("#roles").serialize(),
        success: function(respone){
        	if(respone.success){
        		$('#roles_view').click();
        	}
        }
    });
}
</script>