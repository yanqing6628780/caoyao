<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>订单列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
            </div>
            <div class="portlet-body">
                <table class='table table-striped table-bordered table-hover' id="sample_1">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>顾客姓名</th>
                            <th>电话</th>
                            <th>桌数</th>
                            <th>门店</th>
                            <th>总价</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><?=$row['order_sn']?></td>
                            <td><?=$row['customer']?></td>
                            <td><?=$row['mobile']?></td>
                            <td><?=$row['table_num']?></td>
                            <td><?=$row['user_id']?></td>
                            <td><?=$row['total_price']?></td>
                            <td><?=$row['is_paid'] ? '已付款' : '未付款'  ?></td>
                            <td>
                                <a class="btn green" href="#myModal" data-toggle="modal" onclick="editOrder(<?=$row['id']?>)"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                <a class="btn red" onclick='delOrder(<?=$row['id']?>, "<?=generate_verify_code(array($row['order_sn'], $row['mobile'], $row['user_id']))?>")'><i class="icon-remove icon-white"></i> 删除</a>
                                <a class="btn yellow" onclick='verifyOrder(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 审核</a>
                                <a class="btn dark" href="#myModal" data-toggle="modal" onclick='getOrderGoods(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 查看菜单</a>
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
function editOrder(id, is_coupon){
    LoadAjaxPage('<?=site_url($controller_url."edit/")?>', {id: id}, 'myModal','编辑')
}
function delOrder(id, code){
    common_del('<?=site_url($controller_url."del")?>', id, code, '#order_view');
}
function verifyOrder(id) {
    // body...
}
function getOrderGoods(id) {
    LoadAjaxPage('<?=site_url($controller_url."get_order_goods/")?>', {id: id}, 'myModal','菜单')
}
</script>
