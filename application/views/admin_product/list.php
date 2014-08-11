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
                <div class="row">
                    <div class="col-md-12">                    
                        <form id="search" class="form-inline">
                            <div class="form-group">
                                <label for="exampleInputEmail2" class="sr-only">分类</label>
                                <input name="q" type="text" value="<?=$q?>" class="form-control" placeholder="关键字">
                            </div>
                            <input type="button" value="搜索" class="btn btn-default" onclick="infoQuery()" >
                        </form>
                    </div>
                </div>
                <div class="table-scrollable">
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>产品名</th>
                                <th>小类</th>
                                <th>单价</th>
                                <th>颜色</th>
                                <th>尺码</th>
                                <th>条码</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $key => $row):?>
                            <tr>
                                <td><?=$row['id']?></td>
                                <td><?=$row['product_name']?></td>
                                <td><?=$row['small_class']?></td>
                                <td><?=$row['unit_price']?></td>
                                <td><?=$row['color']?></td>
                                <td><?=$row['size']?></td>
                                <td><?=$row['barcode']?></td>
                                <td>
                                    <?php if (checkPermission2('product_edit')): ?>
                                    <a  href="#myModal" data-toggle="modal"  onclick="edit(<?=$row['id']?>)" class="btn green"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                    <button class="btn btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
                                    <?php endif ?>                                    
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="bootpag pagination">
                            <li class="prev <?=$current_page==1 ? 'disabled' : '' ?>" data-lp="<?=$current_page?>">
                                <a class="ajaxify" href="<?=site_url($prev_link)?>">
                                    <icon class="icon-angle-left"></icon>
                                </a>
                            </li>
                            <?php foreach($page_links as $key => $lnk){ ?>    
                            <li data-lp="<?=$key?>" class="<?=$key == $current_page ? 'disabled' : '' ?>">
                                <a class="ajaxify" href="<?=site_url($lnk)?>"><?=$key?></a>
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
    common_del('<?=site_url($controller_url."del")?>', id, table, "#product_view");
}
function infoQuery() {
    var formData = $('#search').serialize();
    LoadPageContentBody('<?=site_url($controller_url)?>', formData);
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
<script type="text/javascript" src="<?=base_url()?>js/page.js"></script>
