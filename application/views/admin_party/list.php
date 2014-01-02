<script type="text/javascript">
function addUser(userId){
    LoadAjaxPage('user_add', "", 'myModal',"添加会议")
}
function editUser(userId){
    LoadAjaxPage('user_edit', {user_id: userId}, 'myModal',"编辑")
}
function del(id){
    if(confirm('确认删除?'))
    {
        $.ajax({
            type: "POST",
            url: 'del_user',
            dataType: 'json',
            data: {id: id},
            success: function(respone){
                alert( '删除成功' );
                location.reload(true)
            }
        });
    }
}
</script>
<body class="bg_white">
<div class='container-fluid'>
    <div class="row-fluid">
        <div class="span12">
            <div class='widget-box'>
                <div class="widget-title">
                    <div class="navbar-form pull-left">
                        <h5>用户列表</h5>
                        <div class="buttons">
                            <button href="#myModal" data-toggle="modal" onclick='addUser()'><i class="icon-plus"></i> 添加会议</button>
                        </div>   
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <table class='table table-bordered data-table'>
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
                                    <button href="#myModal" data-toggle="modal" class="btn btn-primary" onclick='editUser(<?=$row['id']?>)'> <i class="icon-pencil icon-white"></i> 编辑</button>
                                    <?php endif;?>
                                    <?php if($row['id'] != 1):?>
                                    <?php if(checkPermission2('user_edit')):?>
                                    <button class="btn btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
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
