<table width='100%' border="1" cellpadding="0" cellspacing="0">
    <tr>    
        <td height='220' width='200'>
            <img src='<?=$user_profile['photo']?>' height='200'/>
        </td>
        <td>
            <table width='100%' height='220' border="1" cellpadding="1" cellspacing="2">
                <tr>
                    <th>姓名</th>
                    <td><?=$user_profile['name']?></td>
                    <th>性别</th>
                    <td><?=$user_profile['sex'] ? '男' : '女'?></td>
                </tr>
                <tr>
                    <th>联系电话</th>
                    <td><?=$user_profile['mobile']?></td>
                    <th>电子邮件</th>
                    <td><?=$user_profile['email']?></td>
                </tr>

            </table>        
        </td>
    </tr>
</table>