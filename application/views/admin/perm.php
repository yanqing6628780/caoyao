<body class="bg_white">
<div class='container-fluid'>
    <div class="row-fluid">
        <div class="span12">        	
			<form id="perm_from" action="<?php echo site_url('admin/user_admin/permissions')?>" method="post">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<input type="checkbox" id="title-checkbox" name="title-checkbox" />
					</span>
					<h5>角色权限</h5>
					<div class="navbar-form pull-left">
						<span>角色</span>
						<select name='role'>
						    <?php foreach($roles as $key => $row):?>
						        <option value='<?=$row->id?>' <?php if($row->id == $current_role){echo "selected";}?> ><?=$row->cnname?></option>
						    <?php endforeach;?>
						</select>
						<input type="submit" name="show" value="显示权限"  class="btn btn-primary"/>
						<input type="button" name="save" value="保存"  class="btn btn-primary" onclick="savePerm()"/>
					</div>
				</div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped with-check">
						<thead>
							<tr>
								<th><i class="icon-resize-vertical"></i></th>
								<th>权限名称</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($perms as $key => $perm){ ?>
							<tr>
								<td><input type="checkbox" name="perms[]" value="<?php echo $perm['action_code']?>" <?php if($perm['hasperm']){echo 'checked';}?> /></td>
								<td><?php echo $perm['name']?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>							
				</div>
			</div>
			</form>
        </div>
    </div>
</div>
<script type="text/javascript">
function savePerm()
{
    $.ajax({
        type: "POST",
        url: 'perms_save',
        dataType: 'json',
        data: $("#perm_from").serialize(),
        success: function(respone){
        	if(respone.success){
        		alert(respone.msg)
        	}
        }
    });
}
</script>
</body>
</html>