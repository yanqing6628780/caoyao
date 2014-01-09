<script type="text/javascript">
function edit_user_save()
{
    var data = $('#user_edit').serialize()
    $.ajax({
        type: "POST",
        url: 'member/edit_save',
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
                            <input type='text' value='<?=$member->username?>' readOnly='true'/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">姓名</label>
                        <div class="controls">
                            <input type='text' name='profile[name]' value='<?=$profile->name?>' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">性别</label>
                        <div class="controls">
                            <label class="inline">
                                <input type='radio' name='profile[sex]' value='1' id='male' <?php radio_check($profile->sex,1);?>/>男
                            </label>
                            <label class="inline">
                                <input type='radio' name='profile[sex]' value='0' id='female' <?php radio_check($profile->sex,0);?> />女
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">联系电话</label>
                        <div class="controls">
                            <input type='text' name='profile[mobile]' value='<?=$profile->mobile?>' datatype="m" sucmsg=" 手机验证通过！" nullmsg="请输入手机号码！" errormsg="请填写正确手机号码！"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">公司</label>
                        <div class="controls">
                            <input type='text' name='profile[company]' value='<?=$profile->company?>' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">部门</label>
                        <div class="controls">
                            <input type='text' name='profile[department]' value='<?=$profile->department?>' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">职位</label>
                        <div class="controls">
                            <input type='text' name='profile[jobs]' value='<?=$profile->jobs?>' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">职称</label>
                        <div class="controls">
                            <input type='text' name='profile[job_title]' value='<?=$profile->job_title?>' />
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
