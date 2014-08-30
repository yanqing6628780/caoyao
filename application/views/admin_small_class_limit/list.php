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
                <table class='table table-striped table-bordered table-hover'>
                    <thead>
                        <tr>
                            <th>方案名称</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><?=$row['small_class_restrictions_name']?></td>
                            <td>
                                <?php if (checkPermission2('necessary_edit')): ?>
                                <a  onclick="scheme_edit(<?=$row['id']?>)" class="btn green"> <i class=" icon-list-ol icon-white"></i> 方案设置</a>
                                <a  href="#myModal" data-toggle="modal"  onclick="edit(<?=$row['id']?>)" class="btn green"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                <button class="btn btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
                                <?php endif ?>                                    
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="bootpag pagination">
                            <li class="prev <?=$current_page==1 ? 'disabled' : '' ?>" data-lp="<?=$current_page?>">
                                <a class="ajaxify" href="<?=site_url($controller_url.'?page='.($current_page-1))?>">
                                    <icon class="icon-angle-left"></icon>
                                </a>
                            </li>
                            <?php for ($i=1; $i <= $page; $i++){ ?>    
                            <li data-lp="<?=$i?>" class="<?=$i == $current_page ? 'disabled' : '' ?>">
                                <a class="ajaxify" href="<?=site_url($controller_url.'?page='.$i)?>"><?=$i?></a>
                            </li>
                            <? } ?>
                            <li class="next <?=$current_page==$page ? 'disabled' : '' ?>" data-lp="<?=$current_page?>">
                                <a class="ajaxify" href="<?=site_url($controller_url.'?page='.($current_page+1))?>">
                                <icon class="icon-angle-right"></icon>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
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
    common_del('<?=site_url($controller_url."del")?>', id, table, "#small_class_view");
}
function scheme_edit(id){
    LoadPageContentBody('<?=site_url($controller_url."scheme_edit")?>', {id: id})
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
<script type="text/javascript" src="<?=base_url()?>js/page.js"></script>
