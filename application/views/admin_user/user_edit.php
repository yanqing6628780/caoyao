<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='user_edit' class="form-horizontal" action="<?php echo site_url('admin/user_admin/user_add_save')?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">用户名</label>
                    <div class="col-md-4">
                        <span class="help-block"><?=$user->username?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">用户角色</label>
                    <div class="col-md-4">
                        <select name='role_id' id='role'>
                            <?php foreach($roles as $key => $row):?>
                                <option value='<?=$row->id?>' <?php if($user->role_id ==$row->id){echo "selected='true'";}?>><?=$row->cnname?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">姓名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[name]' value='<?=$profile->name?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">性别</label>
                    <div class="col-md-4">
                        <label class="radio-inline">
                            <input type='radio' name='profile[sex]' value='1' id='male' <?=$profile->sex == 1 ? 'checked=true' : ''?>/>男
                        </label>
                        <label class="radio-inline">
                            <input type='radio' name='profile[sex]' value='0' id='female' <?=$profile->sex == 0 ? 'checked=true' : ''?>/>女
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">联系电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[mobile]' value='<?=$profile->mobile?>' datatype="m" sucmsg=" 手机验证通过！" nullmsg="请输入手机号码！" errormsg="请填写正确手机号码！"/>
                    </div>
                </div>
                <div class="form-group last">
                    <label class="col-md-3 control-label">电子邮件</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[email]' value='<?=$profile->email?>' />
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' class="btn blue btn-lg" value='保存' onclick='edit_user_save()'/>
                    <input type='hidden' name='user_id' value='<?=$user_id?>'/>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
function edit_user_save()
{
    var data = $('#user_edit').serialize()
    $.ajax({
        type: "POST",
        url: 'admin/user_admin/user_edit_save',
        dataType: 'json',
        data: data,
        success: function(respone){
            alert( respone.msg );
            $('#myModal').modal('hide');
            $('#users_view').click();
        }
    });
}
</script>
