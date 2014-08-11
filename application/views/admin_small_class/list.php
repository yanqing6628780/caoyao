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
                    <a onclick="LoadPageContentBody('<?php echo site_url('admin/big_class/')?>')" class="btn green" ><i class="icon-globe"></i> 大类管理</a>
                </div>
            </div>
            <div class="portlet-body">
                <table class='table table-striped table-bordered table-hover' id="sample_1">
                    <thead>
                        <tr>
                            <th>分类名</th>
                            <th>所属大类</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><?=$row['small_class_name']?></td>
                            <td><?=$this->category_mdl->get_big_class($row['big_class_id'])->big_class_name?></td>
                            <td>
                                <?php if (checkPermission2('category_edit')): ?>
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
function add(){
    LoadAjaxPage('<?=site_url($controller_url."add/")?>', '', 'myModal','添加')
}
function edit(id){
    LoadAjaxPage('<?=site_url($controller_url."edit/")?>', {id: id}, 'myModal','编辑')
}
function del(id, table){
    common_del('<?=site_url($controller_url."del")?>', id, table, "#category2_view");
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
