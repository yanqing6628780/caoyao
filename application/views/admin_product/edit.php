<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
            <div class="form-body">                
                <div class="form-group">
                    <label class="col-md-3 control-label">产品名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="product_name" value='<?=$row['product_name']?>' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">单价</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="unit_price" value='<?=$row['unit_price']?>' datatype="*" nullmsg="请输入价格！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">条码</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="barcode" value='<?=$row['barcode']?>' datatype="*" nullmsg="请输入条码！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">小类</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <select datatype="*" class="form-control" name="small_class_id">
                            <option value="">请选择分类</option>
                            <?php foreach ($small_classes as $key => $value): ?>
                            <option value="<?=$value['id']?>" <?=option_select($value['id'], $row['small_class_id'])?> ><?=$value['small_class_name']?></option>
                            <?php endforeach ?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">颜色</label>
                    <div class="col-md-9">
                        <input class="form-control" id="color_attr" type='text' name="color" value='<?=$row['color']?>' datatype="*" nullmsg="请输入颜色！"/>
                        <span class="help-block">可手动输入或者直接下拉选择</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">尺码</label>
                    <div class="col-md-9">
                        <input class="form-control" id="size_attr" type='text' name="size" value='<?=$row['size']?>'/>
                        <span class="help-block">可手动输入或者直接下拉选择</span>
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
            }
            $('#product_view').click();
        }
    });

    $("#color_attr").select2({
        tags: ["红", "绿", "蓝", "黄", "粉"]
    });    
    $("#size_attr").select2({
        tags: ["S", "M", "L", "XL"]
    });
})
</script>