<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"></h3>
    </div>
    <div class="panel-body">
        <div class="form-body">
            <form id='user_edit' class="form-horizontal" action="<?=site_url('admin/member/add_save')?>">
                <div class="form-group">
                    <label class="col-xs-3 col-md-3 control-label">用户名</label>
                    <div class="col-xs-4 col-md-4">
                        <p class="form-control-static text-danger"><?=$this->tank_auth->get_username()?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 col-md-3 control-label">姓名</label>
                    <div class="col-xs-4 col-md-4">
                        <input class="form-control" type='text' name='profile[name]' value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 col-md-3 control-label">性别</label>
                    <div class="col-xs-4 col-md-4">
                        <label class="inline">
                            <input type='radio' name='profile[sex]' value='1' id='male' checked/>男
                        </label>
                        <label class="inline">
                            <input type='radio' name='profile[sex]' value='0' id='female' />女
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 col-md-3 control-label">联系电话</label>
                    <div class="col-xs-4 col-md-4">
                        <input class="form-control" type='text' name='profile[mobile]' value='' datatype="m" sucmsg=" 手机验证通过！" nullmsg="请输入手机号码！" errormsg="请填写正确手机号码！"/>
                    </div>
                </div>
                <div class="form-group fluid">
                    <div class="col-xs-offset-3 col-xs-9 col-md-offset-3 col-md-9">
                        <input id="editProfile_sub" type='button' class="btn blue" value='保存'/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {
    var form = $("#user_edit").Validform({
        btnSubmit: '#editProfile_sub',
        tiptype:3,
        ajaxPost:true,
        callback:function(data){

        }
    });
})
</script>