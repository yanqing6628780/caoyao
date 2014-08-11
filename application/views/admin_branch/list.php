<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions">
                    <a onclick="add()" class="btn blue"  href="#myModal" data-toggle="modal" ><i class="icon-plus"></i> 添加</a>
                </div>
            </div>
            <div class="portlet-body">
                <table class='table table-striped table-bordered table-hover' id="sample_1">
                    <thead>
                        <tr>
                            <th>分公司名</th>
                            <th>联系人</th>
                            <th>电话</th>
                            <th>传真</th>
                            <th>地址</th>
                            <th>管理地区</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><?=$row['branch_name']?></td>
                            <td><?=$row['branch_contact']?></td>
                            <td><?=$row['branch_phone']?></td>
                            <td><?=$row['branch_fax']?></td>
                            <td><?=$row['branch_address']?></td>
                            <td><?=$row['regions']?></td>
                            <td>
                                <?php if (checkPermission2('branch_edit')): ?>
                                <a  href="#myModal" data-toggle="modal"  onclick="add_region(<?=$row['id']?>)" class="btn yellow"> <i class="icon-pencil icon-white"></i> 添加地区</a>
                                <a  href="#myModal" data-toggle="modal"  onclick="edit(<?=$row['id']?>)" class="btn green"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                <button class="btn btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
                                <?php endif ?>                                    
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
function add_region(id){
    LoadAjaxPage('<?=site_url($controller_url."add_region/")?>', {id: id}, 'myModal','添加地区')
}
function add(){
    LoadAjaxPage('<?=site_url($controller_url."add/")?>', '', 'myModal','添加')
}
function edit(id){
    LoadAjaxPage('<?=site_url($controller_url."edit/")?>', {id: id}, 'myModal','编辑')
}
function del(id, table){
    common_del('<?=site_url($controller_url."del")?>', id, table, "#branch_view");
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
