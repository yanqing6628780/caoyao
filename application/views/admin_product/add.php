<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">产品名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="product_name" value='' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">单价</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="unit_price" value='' datatype="*" nullmsg="请输入价格！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">条码</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="barcode" value='' datatype="*" nullmsg="请输入条码！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">小类</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <select datatype="*" class="form-control" name="small_class_id">
                            <option value="">请选择分类</option>
                            <?php foreach ($small_classes as $key => $value): ?>
                            <option value="<?=$value['id']?>" ><?=$value['small_class_name']?></option>
                            <?php endforeach ?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">颜色</label>
                    <div class="col-md-9">
                        <input class="form-control" id="color_attr" type='text' name="color" value='红,蓝' datatype="*" nullmsg="请输入颜色！"/>
                        <span class="help-block">可手动输入或者直接下拉选择</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">尺码</label>
                    <div class="col-md-9">
                        <input class="form-control" id="size_attr" type='text' name="size" value='S,M'/>
                        <span class="help-block">可手动输入或者直接下拉选择</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">产品图</label>
                    <div class="col-md-6">
                        <div class=" input-group input-group-fixed">
                            <div class="input-group-btn"> 
                                <span class="btn green fileinput-button">
                                <i class="icon-paper-clip"></i> 
                                <span>上传</span>
                                <input type="file" name="files" id="upload" class="default">
                                </span>
                                <input class="form-control" datatype='*' type="text" id="picurl" name="picture" placeholder="http://" value="" >
                            </div>
                        </div>
                        <span class="text-danger help-block" id="uploadstatus"></span>
                        <div class="thumbnail help-block col-md-4"><img class="img-responsive" src="http://placehold.it/300x200/999999" alt=""></div>
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
<link type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet"/>
<link type="text/css" href="<?=base_url()?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
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
                    $('#product_view').click();
                }else{
                    $('#myModal').modal('hide');
                    $('#product_view').click();
                }
            }
        }
    });    

    $("#color_attr").select2({
        tags: ["红", "绿", "蓝", "黄", "粉"]
    });    
    $("#size_attr").select2({
        tags: ["S", "M", "L", "XL"]
    });

    $('#upload').fileupload({
        url: '<?=site_url("files/imgUpload/?dir=news")?>',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 5000000, // 5 MB
        done: function (e, data) {
            if(data.result.file){
                $('.thumbnail img').attr('src', data.result.file.url);
                $("#uploadstatus").html('上传成功');
                $("#picurl").val(data.result.file.url);
            }
            else if(data.result.error){            
                $("#uploadstatus").html(data.result.error);
                $("#uploadstatus").show();
            }
        }
    });
})
</script>