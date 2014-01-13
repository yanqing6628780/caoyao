<script type="text/javascript">
function changePassword(){
    LoadAjaxPage('change_password_view', "", 'myModal',"修改密码")
}
</script>
<body class="bg_white">
<div class='container-fluid'>
    <div class='row-fluid'>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="icon-align-justify"></i>                                  
                    </span>
                    <h5>个人资料</h5>
                </div>
                <div class="widget-content nopadding">
                    <form id='profile' class="form-horizontal" action='<?=site_url('admin/user_admin/profile_save');?>' method='post' enctype="multipart/form-data">
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
                            <input type='submit' class="btn btn-inverse btn-large" value='保存'/>
                            <button href="#myModal" data-toggle="modal" class="btn btn-danger btn-large" onclick='changePassword()' ><i class="icon-refresh icon-white"></i> 修改密码</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3>Modal header</h3>
    </div>
    <div class="modal-body">
    </div>
</div>
</body>
</html>