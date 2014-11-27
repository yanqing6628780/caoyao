<?php echo $this->load->view('admin/table_head'); ?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>用户列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions">
                    <?php if(checkPermission2('member_edit')):?> 
                    <a class="btn blue" href="#myModal" data-toggle="modal" onclick='addUser()'><i class="icon-plus"></i> 添加用户</a>
                    <?php endif;?>
                </div>
            </div>
            <div class="portlet-body">
                <table class='table table-striped table-bordered table-hover' id="sample_1">
                    <thead>
                        <tr>
                            <th>用户名</th>
                            <th>门店名</th>
                            <th>联系人</th>
                            <th>电话</th>
                            <th>传真</th>
                            <th>地址</th>
                            <th>所属分公司</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($users as $key => $row):?>
                        <tr>
                            <td><?=$row['username']?></td>
                            <td><?=$row['profile']->name?></td>
                            <td><?=$row['profile']->contact?></td>
                            <td><?=$row['profile']->phone?></td>
                            <td><?=$row['profile']->fax?></td>
                            <td><?=$row['profile']->address?></td>
                            <td><?=$row['profile']->branch_name?></td>
                            <td>
                                <?php if(checkPermission2('user_edit')):?>                                    
                                <button href="#myModal" data-toggle="modal" class="btn btn-small btn-primary" onclick='editUser(<?=$row['id']?>)'> <i class="icon-pencil icon-white"></i> 编辑</button>
                                <?php endif;?>
                                <?php if($row['id'] != 1):?>
                                <?php if(checkPermission2('user_edit')):?>
                                <button class="btn btn-small btn-danger" onclick='del(<?=$row['id']?>, "<?=$row['code']?>")'><i class="icon-remove icon-white"></i> 删除</button>
                                <?php endif;?>
                                <?php endif;?>
                                <button href="#myModal" data-toggle="modal" class="btn btn-small btn-inverse" onclick='resetPassword("<?=$row['username']?>")' ><i class="icon-refresh icon-white"></i> 重置密码</button>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
function addUser(userId){
    LoadAjaxPage('admin/member/add', "", 'myModal',"添加用户");
}
function editUser(userId){
    LoadAjaxPage('admin/member/edit', {user_id: userId}, 'myModal',"编辑");
}

function del(id, code){
    common_del("admin/member/del", id, code);
}

function resetPassword(username)
{
    LoadAjaxPage('admin/member/reset_password', {username: username}, 'myModal',"修改密码")
}
</script>
