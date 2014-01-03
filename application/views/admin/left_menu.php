<div id="sidebar">
    <ul>
        <li class="active submenu">
            <a href="#"><i class="icon icon-th-list"></i> <span>系统管理</span> <span class="label"></span></a>
            <ul>
                <?php if(checkPermission2('user_view')):?>
                <li><a href="<?php echo site_url('admin/user_admin/users')?>" target="right">后台登录用户管理</a></li>
                <?php endif;?>
                <?php if(checkPermission2('perm_admin')):?>
                <li><a href="<?php echo site_url('admin/user_admin/permissions')?>" target="right">权限管理</a></li>
                <?php endif;?>
                <?php if(checkPermission2('role_view')):?>
                <li><a href="<?php echo site_url('admin/user_admin/roles')?>" target="right">角色管理</a></li>
                <?php endif;?>
            </ul>
        </li>
        <li class="submenu">
            <a href="#"><i class="icon icon-th-list"></i> <span>内容管理</span> <span class="label"></span></a>
            <ul>
                <?php if(checkPermission2('party_view')):?>
                <li><a href="<?php echo site_url('admin/party/')?>" target="right">会议管理</a></li>
                <?php endif;?>
                <li><a href="<?php echo site_url('admin/program/')?>" target="right">议程管理</a></li>
            </ul>
        </li>
    </ul>
</div>