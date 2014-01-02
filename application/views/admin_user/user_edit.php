<script type="text/javascript">
function edit_user_save()
{
    var data = $('#user_edit').serialize()
    $.ajax({
        type: "POST",
        url: 'user_edit_save',
        dataType: 'json',
        data: data,
        success: function(respone){
            alert( respone.msg );
            location.reload(true)
        }
    });
}
</script>
<body>
<div class='row-fluid'>
    <div class="span12">
        <div class="widget-box">
            <div class="widget-content nopadding">
                <form id='user_edit' class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label">用户名</label>
                        <div class="controls">
                            <input type='text' value='<?=$user->username?>' readOnly='true'/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">姓名</label>
                        <div class="controls">
                            <input type='text' name='profile[name]' value='<?=$profile->name?>' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">用户角色</label>
                        <div class="controls">
                            <select name='role_id' id='role'>
                                <?php foreach($roles as $key => $row):?>
                                    <option value='<?=$row->id?>' <?php if($user->role_id ==$row->id){echo "selected='true'";}?>><?=$row->cnname?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">性别</label>
                        <div class="controls">
                            <label class="inline">
                                <input type='radio' name='profile[sex]' value='1' id='male' <?=$profile->sex == 1 ? 'checked=true' : ''?>/>男
                            </label>
                            <label class="inline">
                                <input type='radio' name='profile[sex]' value='0' id='female' <?=$profile->sex == 0 ? 'checked=true' : ''?>/>女
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">联系电话</label>
                        <div class="controls">
                            <input type='text' name='profile[mobile]' value='<?=$profile->mobile?>' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">电子邮件</label>
                        <div class="controls">
                            <input type='text' name='profile[email]' value='<?=$profile->email?>' />
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type='button' class="btn btn-inverse btn-large" value='保存' onclick='edit_user_save()'/>
                        <input type='hidden' name='user_id' value='<?=$user_id?>'/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
