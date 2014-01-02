<script type="text/javascript">
$(function(){
    /*提示框*/
    $('.poshytip_green').poshytip({
        className: 'tip-green',
        alignTo: 'target',
        offsetX: 10,
        offsetY : -30,
        showTimeout:100
    });
})
function FormSubmit(obj)
{
    var _this = $(obj);
    var fields = _this.parents('form').serializeArray();

    //提交添加用户数据
    $.ajax({
        type: "POST",
        url: siteUrl('admin/member/add_save'),
        dataType: 'json',
        data: fields,
        success: function(respone){
            if(respone.success){
                alert('添加用户成功')
                location.reload(true)
                _this.parents('form')[0].reset();
            }else{
                $('#username_span').text(respone.errors.username);
                $('#password_span').text(respone.errors.password);
                $('#confirm_password_span').text(respone.errors.confirm_password_span);
                $('#tel_span').text(respone.errors.tel);
                $('#address_span').text(respone.errors.address);
            }
        }
    });

}

function checkUsername(obj)
{
    $('#save').attr('disabled', true);
    $.ajax({
        type: "POST",
        url: siteUrl('admin/member/username_check'),
        dataType: 'json',
        data: {username: $(obj).val()},
        success: function(respone){
            if(respone.result){
                $('#username_span').text('用户名可用');
                $('#save').attr('disabled', false);
            }else{
                $('#username_span').text('*用户名已存在');
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
                <td class='label' width='100'>用户名:</td>
                <td><input type='text' value='' name='username' id='username' onkeyup='checkUsername(this)' disableautocomplete autocomplete="off" /><span id='username_span' class='red'>*</span></td>
            </tr>
            <tr>
                <td class='label'>密码:</td>
                <td><input type='password' name='password' value='123456'/><span id='password_span' class='red'>*默认密码:123456</span></td>
            </tr>
            <tr>
                <td class='label'>密码确认:</td>
                <td><input type='password' name='confirm_password' value='123456'/><span id='confirm_password_span' class='red'>*默认密码:123456</span></td>
            </tr>
            <tr>
                <td class='label'>名字:</td>
                <td><input type='text' name='profile[name]' value='' /></td>
            </tr>
            <tr>
                <td class='label'>联系电话:</td>
                <td><input type='text' name='profile[tel]' value='123' /><span id='tel_span' class='red'>*</span></td>
            </tr>
            <tr>
                <td class='label'>联系地址:</td>
                <td>
                    <textarea name='profile[address]'>霜城</textarea><span id='address_span' class='red'>*</span>
                </td>
            </tr>
            <tr>
                <td class='label'>结账方式:</td>
                <td>
                    <select name="profile[checkout_way]">
                        <option value="1">周结</option>
                        <option value="2" selected="true">月结</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='label'>信用额度限制:</td>
                <td><input type='text' name='profile[credit]' value='50000' /></td>
            </tr>
            <tr>
                <td class='label'>保险赠送:</td>
                <td>
                    <table>
                        <?php foreach($insurances as $key => $value):?>
                        <tr>
                            <td><input type="checkbox" id="checkbox_<?php echo $value->id;?>" name="profile[give_insurance][]" value="<?php echo $value->id;?>"></td>
                            <td>
                                <label for="checkbox_<?php echo $value->id;?>"><?php echo $value->name;?></label>
                            </td>
                            <td><span class="color_1"><?php echo formatAmount($value->price);?>元/份</span></td>
                            <td><span class="underline"><a class="color_2 poshytip_green" href="#" title="<?php echo $value->caption;?>">保险说明</a></span></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan='2' align='center'>
                    <input id="save" type='button' value='保存' onclick='FormSubmit(this)'/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
