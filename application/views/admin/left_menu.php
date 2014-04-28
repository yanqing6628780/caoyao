<?php if(checkPermission2('user_view') || checkPermission2('perm_admin') || checkPermission2('role_view')):?>
<li class="start">
	<a href="javascript:;">
		<i class="icon-home"></i> 
		<span class="title">系统管理</span>
		<span class="selected"></span>
		<span class="arrow open"></span>
	</a>
	<ul class="sub-menu">
		<?php if(checkPermission2('user_view')):?>
		<li><a id="users_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/users')?>" target="right">后台用户管理</a></li>
		<?php endif;?>
		<?php if(checkPermission2('perm_admin')):?>
		<li><a id="perm_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/permissions')?>" target="right">权限管理</a></li>
		<?php endif;?>
		<?php if(checkPermission2('role_view')):?>
		<li><a id="roles_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/roles')?>" target="right">角色管理</a></li>
		<li><a id="member_view" class="ajaxify" href="<?php echo site_url('admin/member/')?>" target="right">会员管理</a></li>
		<?php endif;?>
	</ul>
</li>
<?php endif;?>
<li class="last active">
	<a href="javascript:;">
		<i class="icon-cogs"></i> 
		<span class="title">内容管理</span>
		<span class="selected"></span>
		<span class="arrow"></span>
	</a>
	<ul class="sub-menu">
		<li><a id="category_view" class="ajaxify" href="<?php echo site_url('admin/category/')?>" target="right">信息分类管理</a></li>
		<li><a id="info_view" class="ajaxify" href="<?php echo site_url('admin/info/')?>" target="right">信息管理</a></li>
	</ul>
</li>