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
		<li><a id="roles_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/roles')?>" target="right">后台用户角色管理</a></li>
		<li><a id="member_view" class="ajaxify" href="<?php echo site_url('admin/member/')?>" target="right">前台会员管理</a></li>
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
		<li><a id="branch_view" class="ajaxify" href="<?php echo site_url('admin/branch/')?>" target="right">分公司管理</a></li>
		<li><a id="category_view" class="ajaxify" href="<?php echo site_url('admin/big_class/')?>" target="right">大类管理</a></li>
		<li><a id="category2_view" class="ajaxify" href="<?php echo site_url('admin/small_class/')?>" target="right">小类管理</a></li>
		<li><a id="product_view" class="ajaxify" href="<?php echo site_url('admin/product/')?>" target="right">产品管理</a></li>
		<li><a id="product_relate_view" class="ajaxify" href="<?php echo site_url('admin/product/relation')?>" target="right">产品关联</a></li>
		<li><a id="exchange_view" class="ajaxify" href="<?php echo site_url('admin/exchange/')?>" target="right">订货会管理</a></li>
		<li><a id="announce_view" class="ajaxify" href="<?php echo site_url('admin/announce/')?>" target="right">公告管理</a></li>
		<li><a id="necessary_view" class="ajaxify" href="<?php echo site_url('admin/necessary/')?>" target="right">必需品限制方案</a></li>
		<li><a id="small_class_view" class="ajaxify" href="<?php echo site_url('admin/small_class_limit/')?>" target="right">小类限制方案</a></li>
	</ul>
</li>
<li class="last">
	<a href="javascript:;">
		<i class="icon-bar-chart"></i> 
		<span class="title">报表</span>
		<span class="selected"></span>
		<span class="arrow"></span>
	</a>
	<ul class="sub-menu">
		<li><a id="score_report_view" class="ajaxify" href="<?php echo site_url('admin/scoring/report')?>" target="right">产品评分表</a></li>
	</ul>
</li>