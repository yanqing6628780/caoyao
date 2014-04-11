<div id="myApp">
	<div class="row">
		<div class="col-md-12">
		<!-- BEGIN PAGE TITLE & BREADCRUMB-->
		<h3 class="page-title">群发信息</h3>
		<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption"><i class="icon-reorder"></i></div>
				</div>
				<div class="portlet-body form">
					<?php if (isset($wechat_resp->errcode)): ?>
					<div class="alert alert-danger">
						<p><?=$wechat_resp->errmsg?></p>
					</div>
					<?php else: ?>
					<form id="wechat_msgsend" class="form-horizontal" action="<?=site_url('admin/wechat/msgsend_save')?>">
						<input class="form-control" id="msgtype" name="type" type="hidden" value="text" />
						<div class="form-group">
							<label class="col-sm-2 control-label">access_token</label>
							<div class="col-sm-10">
								<div class="input-group">
									<span class="input-group-btn"><button onclick="getToken()" class="btn yellow" type="button">重新获取token</button></span>
									<input class="form-control" readonly="true" id="access_token" value="<?=$this->session->userdata('wechat_access_token')?>" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">openid</label>
							<div class="col-sm-10">
								<input class="form-control" name="openid" type="text" value="<?=isset($openid)? $openid : '' ?>" readonly="true" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">内容</label>
							<div class="col-sm-10">
								<div class="tabbable-custom nav-justified">
			                        <ul class="nav nav-tabs nav-justified">
			                           <li onclick="selectType('text')" class=""><a data-toggle="tab" href="#tab_1">文本</a></li>
			                           <li onclick="selectType('news')" class=""><a data-toggle="tab" href="#tab_2">图文</a></li>
			                           <li onclick="selectType('news2')" class="active"><a data-toggle="tab" href="#tab_3">已有图文</a></li>
			                        </ul>
			                        <div class="tab-content">
			                        	<div id="tab_1" class="tab-pane">
			                        		<div class="form-group">
			                        			<label class="col-sm-2 control-label">文本内容</label>
			                        			<div class="col-sm-10">
			                        				<input id="content" class="form-control" name="content" datatype='*' />
			                        			</div>
			                        		</div>
			                        	</div>
			                        	<div id="tab_2" class="tab-pane">
			                        		<div class="row">
				                        		<div class="col-md-4">
				                        			<div class="thumbnail">
														<img class="img-responsive" alt="100%x200" src="http://www.placehold.it/400x300/EFEFEF/AAAAAA&text=no+image">
														<div class="caption">
														<p ng:bind="wechat.description"></p>
														</div>
													</div>
				                        		</div>
				                        		<div class="col-md-8">
				                        			<div class="form-group">
				                        				<label class="col-sm-2 control-label">封面</label>
				                        				<div class="col-sm-10">
				                        					<div class=" input-group input-group-fixed">
					                        					<div class="input-group-btn"> 
					                        				        <span class="btn green fileinput-button">
					                        				        <i class="icon-paper-clip"></i> 
					                        				        <span>上传</span>
					                        				        <input type="file" name="files" id="upload" class="default">
					                        				        </span>
				                        				        </div>
				                        				        <input class="form-control" datatype='*' type="text" id="picurl" name="picurl" placeholder="http://" ignore="ignore" >
			                        				        </div>
			                        				    	<span class="text-danger" id="uploadstatus"></span>
				                        				</div>
				                        			</div>
				                        			<div class="form-group">
				                        				<label class="col-sm-2 control-label">标题</label>
				                        				<div class="col-sm-10">
				                        					<input id="title" class="form-control" datatype='*' name="title" ignore="ignore" />
				                        				</div>
				                        			</div>
				                        			<div class="form-group">
				                        				<label class="col-sm-2 control-label">摘要</label>
				                        				<div class="col-sm-10">
				                        					<textarea class="form-control" name="description" value="" ></textarea>
				                        				</div>
				                        			</div>
				                        			<div class="form-group">
				                        				<label class="col-sm-2 control-label">正文</label>
				                        				<div class="col-sm-10">
				                        					<textarea id="ck_editor" class="form-control" name="news_content" value=""></textarea>
				                        				</div>
				                        			</div>
				                        			<div class="form-group">
				                        				<label class="col-sm-2 control-label">链接</label>
				                        				<div class="col-sm-10">
				                        					<input class="form-control" name="url"/>
				                        				</div>
				                        			</div>
				                        		</div>
			                        		</div>
			                        	</div>
			                        	<div id="tab_3" class="tab-pane active">
			                        		<table class='table table-striped table-bordered table-hover Ctable' id="sample_1">
							                    <thead>
							                        <tr>
							                            <th></th>
							                            <th>图片</th>
							                            <th width="250">标题</th>
							                            <th width="400">摘要</th>
							                            <th width="100">多图文</th>
							                        </tr>
							                    </thead>
							                    <tbody>
							                    <?php foreach($news_result as $key => $row):?>
							                        <tr>
							                        	<td><label><input type="radio" name="news_id" value="<?=$row['id']?>" checked></label></td>
							                            <td><div style="width:100px"><img class="img-responsive" src="<?=$row['picurl']?>" alt="<?=$row['title']?>"></div></td>
							                            <td><?=$row['title']?></td>
							                            <td><?=$row['description']?></td>
							                            <td><?=$row['is_mult'] ? '是' : '否'?></td>
							                        </tr>
							                    <?php endforeach;?>
							                    </tbody>
							                </table>			                        		
			                        	</div>
			                        </div>
		                        </div>
							</div>
						</div>
						<div class="form-actions fluid">
						    <div class="col-md-offset-2 col-md-9">
						        <input type='button' id="btn_sub" class="btn blue btn-lg" value='发送'/>
						    </div>
						</div>
					</form>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>
<link type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet"/>
<link type="text/css" href="<?=base_url()?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<script type="text/javascript" src="<?=base_url()?>assets/plugins/ckeditor/ckeditor.js"></script>  
<script type="text/javascript">
function getToken () {
	$.ajax({
		method: 'post',
		dataType: 'json',
		url: '<?=site_url("admin/wechat/reset_access_token")?>',
		success: function(resp) {
			if( !angular.isUndefined(resp.access_token) ){
				$('#access_token').val(resp.access_token);
			}else{
				alert(resp.errmsg);
			}
		}
	})
}
function selectType (type) {
	$('#msgtype').val(type);
	switch(type){
		case 'news':
			$('#content').attr('ignore', 'ignore');
			$('#title').removeAttr('ignore');
			$('#picurl').removeAttr('ignore');
			break;
		case 'text':
			$('#title').attr('ignore', 'ignore');
			$('#picurl').attr('ignore', 'ignore');
			$('#content').removeAttr('ignore');
			break;
		case 'news2':
			$('#title').attr('ignore', 'ignore');
			$('#picurl').attr('ignore', 'ignore');
			$('#content').attr('ignore', 'ignore');
			$('#msgtype').val('news');
			break;
	}
}
$(function () {
    var form = $("#wechat_msgsend").Validform({
    	btnSubmit:"#btn_sub",
        tiptype:2,
        ajaxPost:true,
        beforeSubmit:  function() {
		    for ( instance in CKEDITOR.instances ) {
		        CKEDITOR.instances[instance].updateElement();
		    }
		},
        callback:function(resp){
        	setTimeout(function () {        		
            	$('#Validform_msg').hide();
        	}, 1500)
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
		filebrowserUploadUrl: '<?=site_url("files/ckUpload/")?>'
     });
})
</script>