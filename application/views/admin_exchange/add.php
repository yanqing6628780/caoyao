<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">订货会名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="exchange_fair_name" value='' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">描述</label>
                    <div class="col-md-4">
                        <textarea class="form-control" name="description"  rows="6" datatype="*" nullmsg="请输入名称！"></textarea>
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
                    $('#exchange_view').click();
                }else{
                    $('#myModal').modal('hide');
                    $('#exchange_view').click();
                }
            }
        }
    });    
})
</script>