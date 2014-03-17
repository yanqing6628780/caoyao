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
		<li><a class="ajaxify" href="<?php echo site_url('admin/user_admin/permissions')?>" target="right">权限管理</a></li>
		<?php endif;?>
		<?php if(checkPermission2('role_view')):?>
		<li><a id="roles_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/roles')?>" target="right">角色管理</a></li>
		<?php endif;?>
	</ul>
</li>
<?php if(checkPermission2('wechat_admin')):?>	
<li class="active">
	<a href="javascript:;">
		<i class="icon-linux"></i> 
		<span class="title">微信管理</span>
		<span class="selected"></span>
		<span class="arrow"></span>
	</a>
	<ul class="sub-menu">
		<li><a id="wechat_config_view" class="ajaxify" href="<?php echo site_url('admin/wechat/config')?>" target="right">配置</a></li>
		<li><a id="wechat_msgsend_view" class="ajaxify" href="<?php echo site_url('admin/wechat/msgsend/')?>" target="right">群发信息</a></li>
		<li><a id="wechat_autoreply_view" class="ajaxify" href="<?php echo site_url('admin/wechat/autoreply')?>" target="right">自动回复</a></li>
	</ul>
</li>
<?php endif;?>
<li class="last">
	<a href="javascript:;">
		<i class="icon-cogs"></i> 
		<span class="title">内容管理</span>
		<span class="selected"></span>
		<span class="arrow"></span>
	</a>
	<ul class="sub-menu">
		<li><a id="order_view" class="ajaxify" href="<?php echo site_url('admin/order/')?>" target="right">订单管理</a></li>
		<li><a id="lottery_view" class="ajaxify" href="<?php echo site_url('admin/lottery/')?>" target="right">抽奖管理</a></li>
		<li><a id="coupon_view" class="ajaxify" href="<?php echo site_url('admin/coupon/')?>" target="right">优惠卷管理</a></li>
	</ul>
</li>