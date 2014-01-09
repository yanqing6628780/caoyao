<script type="text/javascript">
function save()
{
    $.ajax({
        type: "POST",
        url: siteUrl('admin/user/change_password'),
        dataType: 'json',
        data: $("#changePass").serialize(),
        success: function(respone){
            if(respone.success){
                alert('修改密码成功')
            }else{
                if(respone.errors){
                    $.each(respone.errors, function(index, val) {
                         alert(val)
                    });
                }
            }
        }
    });
}
</script>
<div class='form_table'>
    <form id='changePass'>
        <table width='100%' border="1" cellpadding="1" cellspacing="2">
            <tr>
                <td class='label'>旧密码:</td>
                <td><input type='text' name="old_password" value=''/></td>
            </tr>
            <tr>
                <td class='label'>新密码:</td>
                <td><input type='text' name='new_password' value='' /></td>
            </tr>
            <tr>
                <td class='label'>确认新密码:</td>
                <td>
                    <input type='text' name='confirm_new_password' value='' />
                </td>
            </tr>
            <tr>
                <td class='label'></td>
                <td><input type='button' value='保存' onclick="save()" /></td>
            </tr>
        </table>
    </form>
</div>
