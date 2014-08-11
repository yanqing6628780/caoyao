<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">分公司名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="branch_name" value='' datatype="*" nullmsg="请输入名！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">联系人</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="branch_contact" value='' datatype="*" nullmsg="请输入联系人！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="branch_phone" value='' datatype="*" nullmsg="请输入电话！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">传真</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="branch_fax" value='' datatype="*" nullmsg="请输入传真！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">地址</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="branch_address" value='' datatype="*" nullmsg="请输入地址！"/>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function () {
    DatePicker.init1();
    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:4,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){            
                if(confirm('是否继续添加')){
                    form.resetForm();
                    $('#branch_view').click();
                }else{
                    $('#myModal').modal('hide');
                    $('#branch_view').click();
                }
            }
        }
    });    
})
</script>