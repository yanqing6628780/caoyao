<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">标题</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="title" value='' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">内容</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="content"  rows="6" datatype="*" nullmsg="请输入内容！"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">所属订货会</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <select datatype="*" class="form-control" name="exchange_fair_id">
                            <option value="">请选择订货会</option>
                            <?php foreach ($exchange_fairs as $key => $value): ?>
                            <option value="<?=$value['id']?>" ><?=$value['exchange_fair_name']?></option>
                            <?php endforeach ?>
                        </select>
                        </div>
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
                    $('#announce_view').click();
                }else{
                    $('#myModal').modal('hide');
                    $('#announce_view').click();
                }
            }
        }
    });    
})
</script>