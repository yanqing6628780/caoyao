<script type="text/javascript">
function editSave()
{
    var data = $('form').serialize()
    $.ajax({
        type: "POST",
        url: siteUrl('admin/member/reset_password'),
        dataType: 'json',
        data: data,
        success: function(respone){
            if(respone.success){
                alert( '重置密码成功' );
            }else{
                $('#password_span').text(respone.errors.password);
                $('#confirm_password_span').text(respone.errors.confirm_password);
            }
        }
    });
}
</script>
<body>
<div class='form_table'>
    <form>
        <table width='100%' border="1" cellpadding="2" cellspacing="2">
            <tr>
                <td class='label'>密码:</td>
                <td><input type='password' name='password' value='123456'/><span id='password_span' class='red'>*默认密码:123456</span></td>
            </tr>
            <tr>
                <td class='label'>密码确认:</td>
                <td><input type='password' name='confirm_password' value='123456'/><span id='confirm_password_span' class='red'>*默认密码:123456</span></td>
            </tr>
            <tr>
                <td colspan='2' align='center'>
                    <input id="save" type='button' value='重置' onclick='editSave()'/>
                    <input type='hidden' name='username' value='<?php echo $username ?>'/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
