<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='user_edit' class="form-horizontal" action="<?php echo site_url('admin/member/edit_save')?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">姓名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[name]' value='<?=$profile->name?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">性别</label>
                    <div class="col-md-4">
                        <label class="inline">
                            <input type='radio' name='profile[sex]' value='1' id='male' <?=$profile->sex ? 'checked' : '' ?> />男
                        </label>
                        <label class="inline">
                            <input type='radio' name='profile[sex]' value='0' id='female' <?=$profile->sex ? '' : 'checked' ?> />女
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">联系电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[mobile]' value='<?=$profile->mobile?>' datatype="m" sucmsg=" 手机验证通过！" nullmsg="请输入手机号码！" errormsg="请填写正确手机号码！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">信用额度</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[credit]' value='<?=$profile->credit?>' datatype="n"/>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='submit' class="btn blue btn-lg" value='保存'/>
                    <input type='hidden' name="user_id" value="<?=$user_id?>" />
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function () {
    var form = $("#user_edit").Validform({
        tiptype:3,
        ajaxPost:true,
        callback:function(data){
            $('#member_view').click();
        }
    });
})
</script>