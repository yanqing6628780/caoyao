<script type="text/javascript">
function addUser(userId){
    LoadAjaxPage('member/add', "", 'myModal',"添加用户");
}
function editUser(userId){
    LoadAjaxPage('member/edit', {user_id: userId}, 'myModal',"编辑");
}

function del(id, code){
    common_del("member/del", id, code);
}

function resetPassword(username)
{
    LoadAjaxPage('member/reset_password', {username: username}, 'myModal',"修改密码")
}
</script>
<body class="bg_white">
<div class='container-fluid'>
    <div class="row-fluid">
        <div class="span12">
            <div class='widget-box'>
                <div class="widget-title">
                    <h5>用户列表</h5>
                    <?php if(checkPermission2('user_edit')):?>
                    <div class="navbar-form pull-left">
                        <div class="input-append">
                            <a class="btn btn-small" href="#myModal" data-toggle="modal" onclick='addUser()'><i class="icon-plus"></i> 添加用户</a>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
                <div class="widget-content nopadding">
                    <table class='table table-bordered data-table'>
                        <thead>
                            <tr>
                                <th width="90">头像</th>
                                <th>用户名</th>
                                <th>姓名</th>
                                <th>性别</th>
                                <th>电话</th>
                                <th>公司</th>
                                <th>职位</th>
                                <th>职称</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($users as $key => $row):?>
                            <tr>
                                <td><img width="80" src="<?=$row['photo']?>" class="img-polaroid"></td>
                                <td><?=$row['username']?></td>
                                <td><?=$row['cnname']?></td>
                                <td><?=$row['sex'] ? "男" : "女" ?></td>
                                <td><?=$row['company']?></td>
                                <td><?=$row['department']?></td>
                                <td><?=$row['jobs']?></td>
                                <td><?=$row['job_title']?></td>
                                <td>
                                    <?php if(checkPermission2('user_edit')):?>                                    
                                    <button href="#myModal" data-toggle="modal" class="btn btn-small btn-primary" onclick='editUser(<?=$row['id']?>)'> <i class="icon-pencil icon-white"></i> 编辑</button>
                                    <?php endif;?>
                                    <?php if($row['id'] != 1):?>
                                    <?php if(checkPermission2('user_edit')):?>
                                    <button class="btn btn-small btn-danger" onclick='del(<?=$row['id']?>, "<?=$row['code']?>")'><i class="icon-remove icon-white"></i> 删除</button>
                                    <?php endif;?>
                                    <?php endif;?>
                                    <button href="#myModal" data-toggle="modal" class="btn btn-small btn-inverse" onclick='resetPassword()' ><i class="icon-refresh icon-white"></i> 重置密码</button>
                                    <input type='hidden' value='<?=$row['id']?>' name='userId'/>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3>Modal header</h3>
    </div>
    <div class="modal-body">
    </div>
</div>
</body>
</html>
