<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">顾客姓名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="vch_booker" value='<?=$row['vch_booker']?>' datatype="*"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="vch_tel" value='<?=$row['vch_tel']?>' datatype="*"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">桌数</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="table_nums" value='<?=$row['table_nums']?>' datatype="n"/>
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
    var form = $("#editForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){
                $('#myModal').modal('hide');
                $('#order_view').click();
            }else{
                alert(response.info)
            }
        }
    });
})
</script>