<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='user_add' class="form-horizontal" action="<?=site_url('admin/member/add_save')?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">用户名</label>
                    <div class="col-md-4">
                        <input class="form-control"  type='text' name="username" value='' datatype="*" ajaxurl="<?=site_url('admin/member/username_check')?>" sucmsg="用户名验证通过！" nullmsg="请输入用户名！"/>
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
                    <label class="col-md-3 control-label">门店名称</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[name]' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">联系电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[phone]' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">联系人</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[contact]'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">传真</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[fax]' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">地址</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[address]'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">分公司</label>
                    <div class="col-md-4">
                        <select name="profile[branch_id]" class="form-control">
                            <?php foreach ($branches as $key => $value): ?>
                                <option value="<?=$value['id'] ?>"><?=$value['branch_name'] ?></option>
                            <?php endforeach ?>
                        </select>
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
            if(data.status == "y"){            
                if(confirm('是否继续添加')){
                    form.resetForm()
                    $('#member_view').click();
                }else{
                    $('#member_view').click();
                }
            }
        }
    });
})
</script>
