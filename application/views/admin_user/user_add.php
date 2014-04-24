<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='user_add' class="form-horizontal" action="<?php echo site_url('admin/user_admin/user_add_save')?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">用户名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="username" value='' datatype="*" ajaxurl="<?=site_url('admin/user_admin/username_check')?>" sucmsg="用户名验证通过！" nullmsg="请输入用户名！" errormsg="请用邮箱或手机号码注册！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">密码</label>
                    <div class="col-md-4">
                        <input class="form-control" type='password' name="password" value='' datatype="*6-16"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">确认密码</label>
                    <div class="col-md-4">
                        <input class="form-control" type='password' name="confirm_password" value='' datatype="*" recheck="password" errormsg="您两次输入的密码不一致！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">用户角色</label>
                    <div class="col-md-4">
                        <select name='role_id' id='role'>
                            <?php foreach($roles as $key => $row):?>
                                <option value='<?=$row->id?>'><?=$row->cnname?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">姓名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[name]' value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">联系电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[mobile]' value=''/>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='submit' class="btn blue btn-lg" value='保存'/>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function () {
    var form = $("#user_add").Validform({
        tiptype:3,
        ajaxPost:true,
        callback:function(data){
            if(confirm('是否继续添加')){
                form.resetForm()
            }else{
                $('#myModal').modal('hide');
                $('#users_view').click();
            }
        }
    });
})
</script>