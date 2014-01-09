<body>
<div class='row-fluid'>
    <div class="span12">
        <div class="widget-box">
            <div class="widget-content nopadding">
                <form id='user_add' class="form-horizontal" action="<?php echo site_url('admin/member/add_save')?>">
                    <div class="control-group">
                        <label class="control-label">用户名</label>
                        <div class="controls">
                            <input type='text' name="username" value='<?=generate_username($num_rows)?>' datatype="n" ajaxurl="member/username_check" sucmsg="用户名验证通过！" nullmsg="请输入用户名！" readonly/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">密码</label>
                        <div class="controls">
                            <input type='password' name="password" value='' datatype="*6-16"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">确认密码</label>
                        <div class="controls">
                            <input type='password' name="confirm_password" value='' datatype="*" recheck="password" errormsg="您两次输入的密码不一致！"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">姓名</label>
                        <div class="controls">
                            <input type='text' name='profile[name]' value='' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">性别</label>
                        <div class="controls">
                            <label class="inline">
                                <input type='radio' name='profile[sex]' value='1' id='male' checked/>男
                            </label>
                            <label class="inline">
                                <input type='radio' name='profile[sex]' value='0' id='female' />女
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">联系电话</label>
                        <div class="controls">
                            <input type='text' name='profile[mobile]' value='' datatype="m" sucmsg=" 手机验证通过！" nullmsg="请输入手机号码！" errormsg="请填写正确手机号码！"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">公司</label>
                        <div class="controls">
                            <input type='text' name='profile[company]' value='' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">部门</label>
                        <div class="controls">
                            <input type='text' name='profile[department]' value='' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">职位</label>
                        <div class="controls">
                            <input type='text' name='profile[jobs]' value='' />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">职称</label>
                        <div class="controls">
                            <input type='text' name='profile[job_title]' value='' />
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type='submit' class="btn btn-inverse btn-large" value='保存'/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url()?>js/validform_v5.3.2.js"></script>
<script type="text/javascript">
$(function () {
    var form = $("#user_add").Validform({
        tiptype:3,
        ajaxPost:true,
        callback:function(data){
            if(confirm('是否继续添加')){
                form.resetForm()
            }else{
                location.reload(true)
            }
        }
    });
})
</script>
</body>
</html>
