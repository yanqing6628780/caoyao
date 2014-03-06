<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">优惠卷名称</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="coupon_name" value='<?=$row['coupon_name']?>' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">优惠卷金额</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input class="form-control" type='text' name="coupon_value" value='<?=$row['coupon_value']?>' datatype="n" nullmsg="请输入金额！" />
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">开始时间</label>
                    <div class="col-md-4">
                        <input class="form-control date-picker" type='text' name="work_date_start" value='<?=$row['work_date_start']?>' datatype="*" nullmsg="请输入时间！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">结束时间</label>
                    <div class="col-md-4">
                        <input class="form-control date-picker" type='text' name="work_date_end" value='<?=$row['work_date_end']?>' datatype="*" nullmsg="请输入时间！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">发行量</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="coupon_circulation" value='<?=$row['coupon_circulation']?>' placeholder='100' datatype="n" nullmsg="请输入数量！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">限制获取数量</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="get_limit" value='<?=$row['get_limit']?>' datatype="n" nullmsg="请输入数量！"/>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <input type='hidden' name="id" value='<?=$id?>'/>
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
    var form = $("#editForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){
                $('#myModal').modal('hide');
                $('#coupon_view').click();
            }else{
                alert(response.info)
            }
        }
    });
})
</script>