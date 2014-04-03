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
                <table class='table table-striped table-bordered table-hover Ctable'>
                    <thead>
                        <tr>
                            <th>名称</th>
                            <th>做法</th>
                            <th>单价</th>
                            <th>数量</th>
                            <th>小计</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><?=$row['vch_dishname']?></td>
                            <td><?=$row['vch_print_memo']?></td>
                            <td><?=formatAmount($row['num_price'])?></td>
                            <td><?=$row['num_num']?></td>
                            <td><?=formatAmount($row['num_price']*$row['num_num'])?></td>
                            <td>
                                <a class="btn red orderGoodDel" onclick="delOrderGood(this, <?=$row['id']?>, '<?=generate_verify_code(array($row['id'], $row['ch_bookno'], $row['num_num']))?>')"><i class="icon-remove icon-white"></i> 删除</a>

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
var oTable
jQuery(document).ready(function() {       
    oTable = TableAdvanced.initCT();
});
function delOrderGood (obj, id, code) {
    var _this = $(obj);
    var nRow = _this.parents('tr')[0];

    if(confirm('确认删除?'))
    {
        $.ajax({
            type: "POST",
            url: '<?=site_url("admin/order/del_order_good")?>',
            dataType: 'json',
            data: {id: id, code: code},
            success: function(respone){
                if(respone.success){                
                    oTable.fnDeleteRow(nRow);
                }else{
                    alert( '删除失败' );
                }
            }
        });
    }
}
</script>
