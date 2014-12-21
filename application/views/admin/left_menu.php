<li class="start active ">
   <a id="dashboard_view"  class="ajaxify"  href="<?php echo site_url('admin/book')?>">
	   <i class="icon-home"></i> 
	   <span class="title">控制面板</span>
	   <span class="selected"></span>
   </a>
</li>
<?php if(chk_perm_to_bool('sys_admin') || chk_perm_to_bool('user_view') || chk_perm_to_bool('perm_admin') || chk_perm_to_bool('role_view')):?>
<li class="start open">
	<a href="javascript:;">
		<i class="icon-home"></i> 
		<span class="title">系统管理</span>
		<span class="selected"></span>
		<span class="arrow open"></span>
	</a>
	<ul class="sub-menu" style="display:block">
		<?php if(chk_perm_to_bool('sys_admin')):?>
		<li><a id="doctor_view" class="ajaxify" href="<?php echo site_url('admin/doctor/')?>" target="right">医师管理</a></li>
		<li><a id="sys_view" class="ajaxify" href="<?php echo site_url('admin/sys/')?>" target="right">系统配置</a></li>
		<?php endif;?>
		<?php if(chk_perm_to_bool('user_view')):?>
		<li><a id="users_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/users')?>" target="right">后台用户管理</a></li>
		<?php endif;?>
		<?php if(chk_perm_to_bool('role_view')):?>
		<li><a id="roles_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/roles')?>" target="right">后台用户角色管理</a></li>
		<?php endif;?>		
		<?php if(chk_perm_to_bool('perm_admin')):?>
		<li><a id="perm_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/permissions')?>" target="right">权限管理</a></li>
		<?php endif;?>
	</ul>
</li>
<?php endif;?>