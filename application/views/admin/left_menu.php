<div id="sidebar">
    <ul>
        <?php if(checkPermission2('user_view') or checkPermission2('perm_admin') or checkPermission2('role_view')):?>
        <li class="active submenu">
            <a href="#"><i class="icon icon-th-list"></i> <span>系统管理</span> <span class="label"></span></a>
            <ul>
                <?php if(checkPermission2('user_view')):?>
                <li><a href="<?php echo site_url('admin/member/')?>" target="right">贵宾用户管理</a></li>
                <?php endif;?>
                <?php if(checkPermission2('user_view')):?>
                <li><a href="<?php echo site_url('admin/user_admin/users')?>" target="right">后台用户管理</a></li>
                <?php endif;?>
                <?php if(checkPermission2('perm_admin')):?>
                <li><a href="<?php echo site_url('admin/user_admin/permissions')?>" target="right">权限管理</a></li>
                <?php endif;?>
                <?php if(checkPermission2('role_view')):?>
                <li><a href="<?php echo site_url('admin/user_admin/roles')?>" target="right">角色管理</a></li>
                <?php endif;?>
            </ul>
        </li>
        <?php endif;?>
        <li class="submenu">
            <a href="#"><i class="icon icon-th-list"></i> <span>内容管理</span> <span class="label"></span></a>
            <ul>
                <?php if(checkPermission2('party_view')):?>
                <li><a href="<?php echo site_url('admin/party/')?>" target="right">会议管理</a></li>
                <?php endif;?>
                <li><a href="<?php echo site_url('admin/program/')?>" target="right">议程管理</a></li>
                <li><a href="<?php echo site_url('admin/sign/')?>" target="right">签到管理</a></li>
                <li><a href="<?php echo site_url('admin/discussion/')?>" target="right">讨论管理</a></li>
                <li><a href="<?php echo site_url('admin/vote/')?>" target="right">投票管理</a></li>
                <li><a href="<?php echo site_url('admin/lottery/')?>" target="right">抽奖管理</a></li>
            </ul>
        </li>
    </ul>
</div>