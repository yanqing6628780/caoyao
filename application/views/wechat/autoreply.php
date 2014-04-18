<?php $this->load->view('admin/table_head');?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet box light-grey">
			<div class="portlet-title">
				<div class="caption"><i class="icon-reorder"></i>列表</div>
				<div class="actions">
                    <a onclick="add()" class="btn blue" ><i class="icon-plus"></i> 添加关键字</a>
				</div>
			</div>
			<div class="portlet-body">
				<table class='table table-striped table-bordered table-hover Ctable' id="sample_1">
                    <thead>
                        <tr>
                            <th>消息类型</th>
                            <th width="250">关键字</th>
                            <th width="400">回复内容</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><?=$row['msgtype']?></td>
                            <td><?=$row['keyword']?></td>
                            <td><?=$row['reply_data']?></td>
                            <td>
                                <a class="btn green" onclick="edit(<?=$row['id']?>, 0)"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                <a class="btn red" onclick="del(<?=$row['id']?>)"><i class="icon-remove icon-white"></i> 删除</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
function add(){
    LoadPageContentBody('<?=site_url($controller_url."autoreply_edit/")?>');
}
function edit(id){
    LoadPageContentBody('<?=site_url($controller_url."autoreply_edit/")?>', {id: id});
}
function del(id, code){
    common_del('<?=site_url($controller_url."autoreply_del")?>', id, code, '#wechat_autoreply_view');
}
</script>