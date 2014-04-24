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
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form class="form-horizontal">
            <div class='form-body'>
                <div class="form-group">
                    <label class="col-md-3 control-label">密码</label>
                    <div class="col-md-4">
                        <input type='password' name='password' value='123456'/>
                        <span id='password_span' class='help-block'>*默认密码:123456</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">密码确认</label>
                    <div class="col-md-4">
                        <input type='password' name='confirm_password' value='123456'/>
                        <span id='confirm_password_span' class='help-block'>*默认密码:123456</span>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input class="btn green btn-lg" id="save" type='button' value='重置' onclick='editSave()'/>
                    <input type='hidden' name='username' value='<?php echo $username ?>'/>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
