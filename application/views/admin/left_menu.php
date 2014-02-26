<li class="start active">
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
		<li><a class="ajaxify" href="<?php echo site_url('admin/user_admin/permissions')?>" target="right">权限管理</a></li>
		<?php endif;?>
		<?php if(checkPermission2('role_view')):?>
		<li><a id="roles_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/roles')?>" target="right">角色管理</a></li>
		<?php endif;?>
	</ul>
</li>
<li class="last">
	<a href="javascript:;">
		<i class="icon-cogs"></i> 
		<span class="title">内容管理</span>
		<span class="selected"></span>
		<span class="arrow"></span>
	</a>
	<ul class="sub-menu">
		<li><a id="order_view" class="ajaxify" href="<?php echo site_url('admin/order/')?>" target="right">订单管理</a></li>
		<li><a id="lottery_view" class="ajaxify" href="<?php echo site_url('admin/coupon/')?>" target="right">抽奖管理</a></li>
		<li><a id="coupon_view" class="ajaxify" href="<?php echo site_url('admin/coupon/?is_coupon=1')?>" target="right">优惠卷管理</a></li>
	</ul>
</li>