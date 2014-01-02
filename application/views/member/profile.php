<body>
<div class='form_table'>
    <form action='<?=site_url('admin/user/profile_save');?>' method='post' enctype="multipart/form-data">
        <table width='100%' border="1" cellpadding="0" cellspacing="0">
            <tr>    
                <td align='center' height='220' width='200'>
                    <table>
                        <?php if(!$user_profile['photo']):?>
                        <tr>
                            <td>头像</td>
                        </tr>
                        <?php else:?>
                        <tr>
                            <td>
                                <img src='<?=$user_profile['photo']?>' height='220'/>
                            </td>
                        </tr>
                        <?php endif;?>
                    </table>
                </td>
                <td>
                    <table height='220' width='100%' border="1" cellpadding="1" cellspacing="2">
                        <tr>
                            <th>姓名</th>
                            <td><input type='text' name='profile[name]' value='<?=$user_profile['name']?>' /></td>
                            <th>性别</th>
                            <td>
                                <label for='male'>男</label>
                                <input type='radio' name='profile[sex]' id='male' value='1' <?=$user_profile['sex'] == 1 ? 'checked=true' : ''?>/>
                                <label for='female'>女</label>
                                <input type='radio' name='profile[sex]' value='0' id='female' <?=$user_profile['sex'] == 0 ? 'checked=true' : ''?>/>
                            </td>
                        </tr>
                        <tr>
                            <th>联系电话</th>
                            <td><input type='text' name='profile[mobile]' value='<?=$user_profile['mobile']?>' /></td>
                            <th>电子邮件</th>
                            <td><input type='text' name='profile[email]' value='<?=$user_profile['email']?>' /></td>
                        </tr>

                        <tr> 
                            <td colspan='100'>
                            <label for='imgfile'>头像上传:</label>
                            <input type='file' name='imgfile' id='imgfile'/> 
                            <input type='hidden' name='user_id' value='<?=$user_id?>' />
                            <input type='submit' value='保存' />
                            </td>
                        </tr>
                    </table>        
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>