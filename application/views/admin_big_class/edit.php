<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">分类名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="big_class_name" value='<?=$row["big_class_name"]?>' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <input type='hidden' name="id" value='<?=$id?>'/>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function () {
    DatePicker.init1();
    var form = $("#editForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){
                $('#myModal').modal('hide');
                $('#category_view').click();
            }
        }
    });
})
</script>