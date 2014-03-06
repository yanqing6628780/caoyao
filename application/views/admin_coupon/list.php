<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>优惠卷列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions">
                    <a onclick="addCoupon()" class="btn blue"  href="#myModal" data-toggle="modal" ><i class="icon-plus"></i> 添加优惠卷</a>
                </div>
            </div>
            <div class="portlet-body">
                <table class='table table-striped table-bordered table-hover' id="sample_1">
                    <thead>
                        <tr>
                            <th>优惠卷名称</th>
                            <th>优惠卷金额</th>
                            <th>使用期限</th>
                            <th>发行量</th>
                            <th>使用条件</th>
                            <th>限制获取数量</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><?=$row['coupon_name']?></td>
                            <td><?=$row['coupon_value']?></td>
                            <td><?=$row['work_date_start']?> 至 <?=$row['work_date_end']?></td>
                            <td><?=$row['coupon_circulation']?></td>
                            <td><?=$row['use_condition']?></td>
                            <td><?=$row['get_limit']?></td>
                            <td>
                                <a  href="#myModal" data-toggle="modal"  onclick="editCoupon(<?=$row['id']?>)" class="btn green"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                <button class="btn btn-danger" onclick='delCoupon(<?=$row['id']?>, false)'><i class="icon-remove icon-white"></i> 删除</button>
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
function addCoupon(){
    LoadAjaxPage('<?=site_url($controller_url."add/")?>', '', 'myModal','添加优惠卷')
}
function editCoupon(id){
    LoadAjaxPage('<?=site_url($controller_url."edit/")?>', {id: id}, 'myModal','编辑')
}
function delCoupon(id, code){
    common_del('<?=site_url($controller_url."del")?>', id, code, "#coupon_view");
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
