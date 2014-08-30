<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?=site_url($controller_url."edit_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">订货会名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="exchange_fair_name" value='<?=$row["exchange_fair_name"]?>' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">描述</label>
                    <div class="col-md-4">
                        <textarea class="form-control" name="description"  rows="6" datatype="*" nullmsg="请输入名称！"><?=$row["description"]?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">开始日期</label>
                    <div class="col-md-4">
                         <input class="form-control date-picker" type='text' name="start_time" value='<?=$row['start_time']?>' datatype="*" nullmsg="请输入时间！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">结束日期</label>
                    <div class="col-md-4">
                         <input class="form-control date-picker" type='text' name="end_time" value='<?=$row['end_time']?>' datatype="*" nullmsg="请输入时间！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">订货会状态</label>
                    <div class="col-md-4">
                        <label class="radio-inline">
                            <input type="radio" value="1" name='state'  <?=radio_check(1, $row['state']) ?> >开
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="0" name='state' <?=radio_check(0, $row['state']) ?> >关闭
                        </label>
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
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-datepicker/css/datepicker.css" />

<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js" charset="UTF-8"></script>
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
                $('#exchange_view').click();
            }
        }
    });
})
</script>