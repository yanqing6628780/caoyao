<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title"></h3>
    </div>
    <div class="panel-body">
        <div class="form-body">
            <form id="editPswForm" class="form-horizontal" method="post" action="<?=site_url('/member/reset_password')?>" role="form">
                <div class="form-group">
                    <label for="login" class="col-xs-3 col-md-3 control-label">新密码</label>
                    <div class="col-xs-7 col-md-7">
                        <input class="form-control" type='password' name='password' value='' datatype="*6-16"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-xs-3 col-md-3 control-label">确认密码</label>
                    <div class="col-xs-7 col-md-7">
                        <input class="form-control" type='password' name='confirm_password' value='' datatype="*" recheck="password" errormsg="您两次输入的密码不一致！"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-3 col-xs-9">
                        <button class="btn btn-danger" id="editPsw_sub" type='button'>修改密码</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {
    var form = $("#editPswForm").Validform({
        btnSubmit: '#editPsw_sub',
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            if(response.status == 'y'){
                form.resetForm();
            }
        }
    });
})
</script>