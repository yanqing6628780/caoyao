<?php if(chk_perm_to_bool('user_view') || chk_perm_to_bool('perm_admin') || chk_perm_to_bool('role_view')):?>
<li class="start">
	<a href="javascript:;">
		<i class="icon-home"></i> 
		<span class="title">用户管理</span>
		<span class="selected"></span>
		<span class="arrow open"></span>
	</a>
	<ul class="sub-menu">
		<?php if(chk_perm_to_bool('user_view')):?>
		<li><a id="users_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/users')?>" target="right">后台用户管理</a></li>
		<?php endif;?>
		<?php if(chk_perm_to_bool('perm_admin')):?>
		<li><a id="perm_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/permissions')?>" target="right">权限管理</a></li>
		<?php endif;?>
		<?php if(chk_perm_to_bool('role_view')):?>
		<li><a id="roles_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/roles')?>" target="right">后台用户角色管理</a></li>
		<?php endif;?>
	</ul>
</li>
<?php endif;?>
<li class="last active open">
	<a href="javascript:;">
		<i class="icon-cogs"></i> 
		<span class="title">系统管理</span>
		<span class="selected"></span>
		<span class="arrow"></span>
	</a>
	<ul class="sub-menu">
		<?php if(chk_perm_to_bool('role_view')):?>
		<li><a id="sys_view" class="ajaxify" href="<?php echo site_url('admin/sys')?>" target="right">系统配置</a>
		<?php endif;?>
	</ul>
</li>