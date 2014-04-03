<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."verify")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">订单类型</label>
                    <div class="col-md-4">
                        <select name="write_type">
                            <option value="0">预订单</option>
                            <option value="1">直接下单</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">预订台号</label>
                    <div class="col-md-4">
                        <?php for ($i=1; $i <= $table_nums; $i++) { ?>
                                <select class="tableSelect form-control" name="tables[]">
                                <?php foreach ($tables as $key => $row): ?>
                                    <option value="<?=$row['ch_tableno']?>"><?=$row['vch_tablename']?>--<?=$row['ch_areano']?></option>
                                <?php endforeach ?>
                                </select>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">市别</label>
                    <div class="col-md-4">
                        <select class="tableSelect form-control" name="bustype">
                        <?php foreach ($bustype as $key => $row): ?>
                            <option value="<?=$row['ch_bustype']?>"><?=$row['vch_name']?></option>
                        <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <input type='hidden' name="ch_bookno" value='<?=$ch_bookno?>'/>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function () {
    var form = $("#editForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){
                $('#myModal').modal('hide');
                $('#order_view').click();
            }
        }
    });
})
</script>