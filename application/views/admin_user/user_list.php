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
                    <?php if(checkPermission2('user_edit')):?> 
                    <a class="btn blue" href="#myModal" data-toggle="modal" onclick='addUser()'><i class="icon-pencil"></i> 添加用户</a>
                    <?php endif;?>
                </div>
            </div>
            <div class="portlet-body">
                <table class='table table-striped table-bordered table-hover' id="sample_1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>用户名</th>
                            <th>姓名</th>
                            <th>用户角色</th>
                            <th>最后登录时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($users as $key => $row):?>
                        <tr>
                            <td><?=$row['id']?></td>
                            <td><?=$row['username']?></td>
                            <td><?=$row['cnname']?></td>
                            <td><?=$row['role']?></td>
                            <td><?=$row['last_login']?></td>
                            <td>
                                <?php if(checkPermission2('user_edit')):?>                                    
                                <a target="_blank" class="btn purple" href='<?=site_url("admin/user_admin/user_lbs/?user_id=".$row['id'])?>'><i class="icon-map-marker icon-white"></i> 地理位置</a>
                                <button href="#myModal" data-toggle="modal" class="btn btn-primary" onclick='editUser(<?=$row['id']?>)'> <i class="icon-pencil icon-white"></i> 编辑</button>
                                <?php endif;?>
                                <?php if($row['id'] != 1):?>
                                <?php if(checkPermission2('user_edit')):?>
                                <button class="btn btn-danger" onclick='delUser(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
                                <?php endif;?>
                                <?php endif;?>
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
function addUser(userId){
    LoadAjaxPage('<?=site_url("admin/user_admin/user_add")?>', "", 'myModal',"添加用户")
}
function editUser(userId){
    LoadAjaxPage('<?=site_url("admin/user_admin/user_edit")?>', {user_id: userId}, 'myModal',"编辑")
}
function delUser(id){
    if(confirm('确认删除?'))
    {
        $.ajax({
            type: "POST",
            url: '<?=base_url()?>admin/user_admin/del_user',
            dataType: 'json',
            data: {id: id},
            success: function(respone){
                alert( '删除成功' );
                $('.page-sidebar #users').click();
            }
        });
    }
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>