<?php $this->load->view('admin/table_head');?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet box light-grey">
			<div class="portlet-title">
				<div class="caption"><i class="icon-reorder"></i>列表</div>
				<div class="actions">
				    <a onclick="addMembers()" class="btn blue" ><i class="icon-plus"></i> 添加图文</a>
				</div>
			</div>
			<div class="portlet-body">
				<table class='table table-striped table-bordered table-hover Ctable' id="sample_1">
                <thead>
                    <tr>
                        <th>食通天卡号</th>
                        <th>微信会员卡号</th>
                        <th>姓名</th>
                        <th>电话</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($result as $key => $row):?>
                    <tr>
                        <td><?=$row['cardNo']?></td>
                        <td><?=$row['wechat_cardno']?></td>
                        <td><?=$row['name']?></td>
                        <td><?=$row['tel']?></td>
                        <td>
                            <?php if ( empty($row['cardNo']) ): ?>                                
                            <a href="#myModal" data-toggle="modal" class="btn green" onclick="bindMember(<?=$row['id']?>)"> <i class="icon-pencil icon-white"></i> 绑定食通天</a>
                            <?php endif ?>
                            <a class="btn yellow" onclick="unbindMember(<?=$row['id']?>)"> <i class="icon-pencil icon-white"></i> 解除食通天绑定</a>
                            <a class="btn red" onclick="delMember(<?=$row['id']?>)"><i class="icon-remove icon-white"></i> 删除</a>
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
function bindMember(id){
    LoadAjaxPage('<?=site_url($controller_url."members_edit/")?>', {id: id, action: 'bind'}, 'myModal','编辑')
}
function delMember(id, code){
    common_del('<?=site_url($controller_url."members_del")?>', id, code, '#wechat_member_view');
}
function unbindMember(id){
    $.ajax({
        url: '<?=site_url($controller_url."members_unbind")?>',
        type: 'POST',
        dataType: 'json',
        data: {id: id},
        success: function (response) {
            if(response.status == "y") {
                alert('解绑完成');
                $('#wechat_member_view').click();
            }else {
                alert('解绑失败');
            }
        }
    });
}
</script>