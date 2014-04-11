<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>添加图文</div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."news_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">标题</label>
                    <div class="col-md-4">
                        <div>
                            <input class="form-control" type='text' name="title" value='' datatype="*"/>
                        </div>
                        <span class="text-danger"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">封面</label>
                    <div class="col-md-4">
                        <div class=" input-group input-group-fixed">
                            <div class="input-group-btn"> 
                                <span class="btn green fileinput-button">
                                <i class="icon-paper-clip"></i> 
                                <span>上传</span>
                                <input type="file" name="files" id="upload" class="default">
                                </span>
                            </div>
                            <input class="form-control" datatype='*' type="text" id="picurl" name="picurl" placeholder="http://" value="" >
                        </div>
                        <span class="text-danger help-block" id="uploadstatus"></span>
                        <div class="thumbnail help-block col-md-4"><img class="img-responsive" src="" alt=""></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">摘要</label>
                    <div class="col-md-4">
                        <textarea class="form-control" type='text' name="description" ></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">正文</label>
                    <div class="col-sm-9">
                        <textarea id="ck_editor" class="form-control" name="content" value=""></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">原文链接</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="url" value=''/>
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
<script type="text/javascript" src="<?=base_url()?>assets/plugins/ckeditor/ckeditor.js"></script>  
<script type="text/javascript">
$(function () {
    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:2,
        ajaxPost:true,
        beforeSubmit:  function() {
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
        },
        callback:function(response){
            if(response.status == "y"){
                $('#wechat_appmsg_view').click();
            }else{
                alert(response.info)
            }
        }
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

    CKEDITOR.replace( 'ck_editor',{
        filebrowserUploadUrl: '<?=site_url("files/ckUpload/")?>',
        on: {
            instanceReady: function() {
                this.dataProcessor.htmlFilter.addRules( {
                    elements: {
                        img: function( el ) {
                            if ( !el.attributes.class )
                                el.attributes.class = 'img-responsive';
                        }
                    }
                } );            
            }
        }
    });
})
</script>