<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
            </div>
            <div class="portlet-body">
                <?php if($exchange_fair_id == FALSE): ?>
                <div class="row">
                    <div class="col-md-12">                    
                        <form id="search" class="form-inline">
                            <div class="form-group">
                                <select name="exchange_id" class="form-control" id="exchange">
                                    <?php foreach ($exchange_fairs as $key => $value): ?>
                                        <option value="<?=$value['id']?>"><?=$value['exchange_fair_name']?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <input type="button" value="搜索" class="btn btn-default" onclick="infoQuery()" >
                        </form>
                    </div>
                </div>
                <?php endif; ?>
                <div class="table-scrollable">
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>订单号</th>
                                <th>买家</th>
                                <th>订货会</th>
                                <th>创建时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $key => $row):?>
                            <tr>
                                <td><?=$row['order_number']?></td>
                                <td><?=$row['name']?></td>
                                <td><?=$row['exchange_fair_name']?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['is_pass'] ? '审核通过' : '未通过' ?></td>
                                <td>
                                    <?php if (checkPermission2('order_edit')): ?>
                                    <a  onclick="edit_save(<?=$row['id']?>)" class="btn green"> <i class="icon-pencil icon-white"></i> 审核</a>
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
function edit_save(id){
    $.ajax({
        type: "POST",
        url: '<?=site_url($controller_url."edit_save")?>',
        dataType: 'json',
        data: {id: id, is_pass: 1},
        success: function(respone){
            if(respone.status == 'y'){                
                LoadPageContentBody('<?=site_url($controller_url)?>', 'exchange_id=<?=$exchange_fair_id?>');
            }else{
                alert( '审核失败' );
            }
        }
    });
    
}
function del(id, table){
    common_del('<?=site_url($controller_url."del")?>', id, table, false);
    LoadPageContentBody('<?=site_url($controller_url)?>', 'exchange_id=<?=$exchange_fair_id?>');
}
function infoQuery() {
    var formData = $('#search').serialize();
    LoadPageContentBody('<?=site_url($controller_url)?>', formData);
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
    $('#exchange').select2();
});
</script>
<script type="text/javascript" src="<?=base_url()?>js/page.js"></script>
