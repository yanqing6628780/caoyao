<script type="text/javascript">
function add()
{
    LoadAjaxPage('admin/member/add', '', 'editground')
    $("#editground").empty()
    $("#editground").dialog({
        "title": "添加用户"
    })
    $("#editground").dialog("open")
}

function del(id)
{
    if(confirm('确认删除?'))
    {
        $.ajax({
            type: "POST",
            url: siteUrl('admin/member/del'),
            dataType: 'json',
            data: {id: id},
            success: function(respone){
                alert( '删除成功' );
                location.reload(true)
            }
        });
    }
}

function editUser(userId)
{
    LoadAjaxPage('admin/member/edit', {user_id: userId}, 'editground')
    $("#editground").empty()
    $("#editground").dialog({
        "title": "编辑用户"
    })
    $("#editground").dialog("open")
}

function resetPassword(username)
{
    LoadAjaxPage('admin/member/reset_password', {username: username}, 'editground')
    $("#editground").empty()
    $("#editground").dialog({
        "title": "重置密码"
    })
    $("#editground").dialog("open")
}
</script>
<body>
<div id='page'>
    <div class="tool_bar">
        <form method='post'>
            <table width='100%' border="0" cellpadding="0" cellspacing="5">
                <tr>
                    <td>
                        <?php if(checkPermission2('member_edit')):?>
                        <input class="btn2"  type="button" value="添加用户" onclick="add()">
                        <?php endif;?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div id='workground'>
        <table class='data_list' width='100%' border="0" cellpadding="2" cellspacing="0">
            <thead class='list-head'>
                <tr>
                    <th>用户名</th>
                    <th>姓名</th>
                    <th>联系电话</th>
                    <th>赠送保险</th>
                    <th>结账方式</th>
                    <th>信用额度</th>
                    <th>操作</th>
                </tr>
            </thead>
            <?php foreach($members as $key => $row):?>
            <tbody class='list'>
                <tr class='sep-row'>
                    <td colspan="100"></td>
                </tr>
                <tr>
                    <td><?=$row['username']?></td>
                    <td><?=$row['name']?></td>
                    <td><?=$row['tel']?></td>
                    <td><?=$row['give_insurance']?></td>
                    <td><?=$row['checkout_way']?></td>
                    <td><?echo $row['used_credit'].'/'.$row['credit']?></td>
                    <td>
                        <?php if(checkPermission2('member_edit')):?>
                        <a class="underline color_1" href="javascript:editUser(<?=$row['id']?>)">编辑</a>
                        <a class="underline color_1" href="javascript:resetPassword('<?=$row['username']?>')">重置密码</a>
                        <a class="underline color_1" href="javascript:del(<?=$row['id']?>)">删除</a>
                        <?php endif;?>
                        <input type='hidden' value='<?=$row['id']?>' name='userId'/>
                    </td>
                </tr>
            </tbody>
            <?php endforeach;?>
        </table>
        <div class='toolbar mt5'>
            <div class='page r'>
                <?=$page_links;?>
            </div>
        </div>
    </div>
    <div id='editground'></div>
</div>
</body>
</html>
