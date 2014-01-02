<body class="bg_white">
<div class='container-fluid'>
    <div class="row-fluid">
        <div class="span12">        	
			<form action="<?php echo site_url('admin/user_admin/roles')?>" method="post">
			<div class="widget-box">
				<div class="widget-title">
					<h5>角色列表</h5>
					<?php if(checkPermission2('role_edit')):?>
					<div class="navbar-form pull-left">
						<span>父角色</span>
						<select name='role_parent'>
							<option value='0'>None</option>
						    <?php foreach($roles as $key => $row):?>
						        <option value='<?=$row->id?>'><?=$row->cnname?></option>
						    <?php endforeach;?>
						</select>
						<input type="text" name="role_name" value="" class="input-small" placeholder="角色英文名"/>
						<input type="text" name="role_cnname" value="" class="input-small"  placeholder="角色中文名"/>
						<input type="submit" name="add" value="添加角色"  class="btn btn-primary"/>
						<input type="submit" name="delete" value="删除选中的角色"  class="btn btn-inverse"/>
					</div>
					<?php endif;?>
				</div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped with-check">
						<thead>
							<tr>
								<th><i class="icon-resize-vertical"></i></th>
								<th>ID</th>
								<th>父ID</th>
								<th>角色英文名</th>
								<th>角色中文名</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($roles as $role){ ?>
							<tr>
								<td><input type="checkbox" name="checkbox[]" value="<?php echo $role->id?>"/></td>
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
			</form>
        </div>
    </div>
</div>
</body>
</html>