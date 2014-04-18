<div id="myApp">
	<div class="row">
		<div class="col-md-12"><h3 class="page-title">添加关键字</h3></div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption"><i class="icon-reorder"></i>填写</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<form id="wechat_autoreply" class="form-horizontal" action="<?=site_url('admin/wechat/autoreply_save')?>">
							<input class="form-control" id="msgtype" name="type" type="hidden" value="<?=$row['msgtype']?>" />
							<div class="form-group">
								<label class="col-sm-2 control-label">关键字</label>
								<div class="col-sm-10">
									<input type="text" name="keyword" class="form-control" value="<?=$row['keyword']?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">内容</label>
								<div class="col-sm-10">
									<div class="tabbable-custom nav-justified">
				                        <ul class="nav nav-tabs nav-justified">
				                           <li onclick="selectType('text')" class="<?=$row['msgtype'] == 'text' ? 'active' : ''?>"><a data-toggle="tab" href="#tab_1">文本</a></li>
				                           <li onclick="selectType('news')" class="<?=$row['msgtype'] == 'news' ? 'active' : ''?>"><a data-toggle="tab" href="#tab_2">图文</a></li>
				                        </ul>
				                        <div class="tab-content">
				                        	<div id="tab_1" class="tab-pane <?=$row['msgtype'] == 'text' ? 'active' : ''?>">
				                        		<div class="form-group">
				                        			<label class="col-sm-2 control-label">文本内容</label>
				                        			<div class="col-sm-10">
				                        				<textarea class="form-control" id="content" name="content" id="" datatype='*'  cols="30" rows="5" <?=$row['msgtype'] == 'news' ? 'ignore="ignore"' : '' ?> ><?=$row['msgtype'] == 'text' ? $row['content'] : '' ?></textarea>
				                        			</div>
				                        		</div>
				                        	</div>
				                        	<div id="tab_2" class="tab-pane <?=$row['msgtype'] == 'news' ? 'active' : ''?>">
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
								                    <?php foreach($news_result as $key => $value):?>
								                        <tr>
								                        	<td><label><input type="radio" name="news_id" class="news_id" value="<?=$value['id']?>" datatype='*' <?=$row['msgtype'] == 'text' ? 'ignore="ignore"' : ''?> <?=isset($row['news_id']) ? radio_check($value['id'], $row['news_id']) : ''?> ></label></td>
								                            <td><div style="width:100px"><img class="img-responsive" src="<?=$value['picurl']?>" alt="<?=$value['title']?>"></div></td>
								                            <td><?=$value['title']?></td>
								                            <td><?=$value['description']?></td>
								                            <td><?=$value['is_mult'] ? '是' : '否'?></td>
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
							        <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
							        <input type='hidden' name="id" value='<?=$row['id']?>'/>
							    </div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function selectType (type) {
	$('#msgtype').val(type);
	switch(type){
		case 'news':
			$('#content').attr('ignore', 'ignore');
			$('.news_id').removeAttr('ignore');
			break;
		case 'text':
			$('.news_id').attr('ignore', 'ignore');
			$('#content').removeAttr('ignore');
			break;
	}
}
$(function () {
    var form = $("#wechat_autoreply").Validform({
    	btnSubmit:"#btn_sub",
        tiptype:3,
        ajaxPost:true,
        callback:function(resp){
        	$('#wechat_autoreply_view').click();
        	setTimeout(function () {        		
            	$('#Validform_msg').hide();
        	}, 1500)
        }
    });
})
</script>