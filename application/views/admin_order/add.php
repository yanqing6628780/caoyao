<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label"><?=$is_coupon ? "优惠卷" : "抽奖" ?>名称</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="coupon_name" value='' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
                <?php if($is_coupon){?>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">优惠卷金额</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input class="form-control" type='text' name="coupon_value" value='' datatype="n" nullmsg="请输入金额！"/>
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                </div>
                <?php }?>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">开始时间</label>
                    <div class="col-md-4">
                        <input class="form-control date-picker" type='text' name="work_date_start" value='' datatype="*" nullmsg="请输入时间！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">结束时间</label>
                    <div class="col-md-4">
                        <input class="form-control date-picker" type='text' name="work_date_end" value='' datatype="*" nullmsg="请输入时间！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">发行量</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="coupon_circulation" value='' placeholder='100' datatype="n" nullmsg="请输入数量！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">限制获取数量</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="get_limit" value='1' datatype="n" nullmsg="请输入数量！"/>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <input type='hidden' name="is_coupon" value='<?=$is_coupon?>'/>
                </div>
            </div>
        </form>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/datepicker.css" />

<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
$(function () {
    DatePicker.init1();
    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:4,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){            
                if(confirm('是否继续添加')){
                    form.resetForm();
                }else{
                    $('#myModal').modal('hide');
                    $('#lottery_view').click();
                }
            }
        }
    });    
})
</script>