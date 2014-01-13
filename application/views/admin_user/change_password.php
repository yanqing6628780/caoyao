<script type="text/javascript">
function save()
{
    $.ajax({
        type: "POST",
        url: 'change_password',
        dataType: 'json',
        data: $("#changePassForm").serialize(),
        success: function(respone){
            if(respone.success){
                alert('修改密码成功');
                $('#myModal').modal('hide');
            }else{
                if(respone.errors){
                    $.each(respone.errors, function(index, val) {
                         alert(val);
                    });
                }
            }
        }
    });
}
</script>
<div class='row-fluid'>
    <div class="span12">
        <div class="widget-box">
            <div class="widget-content nopadding">
                <form id='changePassForm' class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label">旧密码</label>
                        <div class="controls">
                            <input type='text' name="old_password" value=''/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">新密码</label>
                        <div class="controls">
                            <input type='text' name='new_password' value='' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">确认新密码</label>
                        <div class="controls">
                            <input type='text' name='confirm_new_password' value='' />
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type='button' class="btn btn-inverse btn-large" value='保存' onclick='save()'/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
