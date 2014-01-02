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
function editSave()
{
    var data = $('form').serialize()
    $.ajax({
        type: "POST",
        url: siteUrl('admin/member/edit_save'),
        dataType: 'json',
        data: data,
        success: function(respone){
            if(respone.success){
                alert( '修改成功' );
                location.reload(true)
            }else{
                $('#username_span').text(respone.errors.username);
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
                <td><input type='text' value='<?php echo $member->username ?>' name='username' id='username' onkeyup='checkUsername(this)' disableautocomplete autocomplete="off" /><span id='username_span' class='red'>*</span></td>
            </tr>
            <tr>
                <td class='label'>名字:</td>
                <td><input type='text' name='profile[name]' value='<?php echo $profile->name ?>' /></td>
            </tr>
            <tr>
                <td class='label'>联系电话:</td>
                <td><input type='text' name='profile[tel]' value='<?php echo $profile->tel ?>' /><span id='tel_span' class='red'>*</span></td>
            </tr>
            <tr>
                <td class='label'>联系地址:</td>
                <td>
                    <textarea name='profile[address]'><?php echo $profile->address ?></textarea><span id='address_span' class='red'>*</span>
                </td>
            </tr>
            <tr>
                <td class='label'>结账方式:</td>
                <td>
                    <select name="profile[checkout_way]">
                        <option <?php echo option_select(1, $profile->checkout_way) ?> value="1">周结</option>
                        <option <?php echo option_select(2, $profile->checkout_way) ?> value="2">月结</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='label'>信用额度限制:</td>
                <td><input type='text' name='profile[credit]' value='<?php echo $profile->credit ?>' /></td>
            </tr>
            <tr>
                <td class='label'>已用信用额度限制:</td>
                <td><input type='text' name='profile[used_credit]' value='<?php echo $profile->used_credit ?>' /></td>
            </tr>
            <tr>
                <td class='label'>保险赠送:</td>
                <td>
                    <table>
                        <?php foreach($insurances as $key => $value):?>
                        <tr>
                            <td>

                                <input type="checkbox" id="checkbox_<?php echo $value->id;?>" name="profile[give_insurance][]" value="<?php echo $value->id;?>" <?php if (in_array($value->id, $profile->give_insurance)){echo 'checked';} ?> >
                            </td>
                            <td>
                                <label for="checkbox_<?php echo $value->id;?>"><?php echo $value->name;?></label>
                            </td>
                            <td><span class="color_1"><?php echo formatAmount($value->price);?>元/份</span></td>
                            <td><span class="underline"><a title="<?php echo $value->caption;?>" href="javascript:void(0)" class="poshytip_green color_2">保险说明</a></span></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan='2' align='center'>
                    <input id="save" type='button' value='保存' onclick='editSave()'/>
                    <input type='hidden' name='user_id' value='<?php echo $user_id; ?>'/>
                    <input type='hidden' name='old_username' value='<?php echo $member->username ?>'/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
