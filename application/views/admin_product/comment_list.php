<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i><?=$name ?>评论列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>用户</th>
                                <th>订货会</th>
                                <th>评论</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($comments as $key => $row):?>
                            <tr>
                                <td><?=$row['user_name']?></td>
                                <td><?=$row['exchange_name']?></td>
                                <td><?=$row['comments_content']?></td>
                                <td>
                                    <?php if (checkPermission2('product_edit')): ?>
                                    <button class="btn btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>   
                                    <?php endif; ?>                                
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function del(id, table){
    common_del('<?=site_url($controller_url."comment_del")?>', id, table, "#product_view");
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
<script type="text/javascript" src="<?=base_url()?>js/page.js"></script>
