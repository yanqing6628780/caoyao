<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">预约人</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="name" value='' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">预约人电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="phone" value='' datatype="*" nullmsg="请输入电话！"/>
                    </div>
                </div>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">预约时间</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-addon">日期</span>
                            <input type="text" readonly class="form-control date-picker" name="book_date[date]" value='' datatype="*" nullmsg="请输入日期！">
                        </div>
                        <div class="input-group">
                            <select id="amorpm" class="form-control">
                                <option value="0">上午</option>
                                <option value="1">下午</option>
                            </select>
                            <span class="input-group-addon">时间</span>
                            <select id="am" class="form-control" name="book_date[time][]">
                                <?php for ($i= $config['bh_am_start']; $i <= $config['bh_am_end']; $i++) { 
                                    echo "<option value=".$i.">".$i."</option>";
                                } ?>
                            </select>
                            <select style="display:none" disabled="true" id="pm" class="form-control" name="book_date[time][]">
                                <?php for ($i= $config['bh_pm_start']; $i <= $config['bh_pm_end']; $i++) { 
                                    echo "<option value=".$i.">".$i."</option>";
                                } ?>
                            </select>
                            <span class="input-group-addon">时</span>
                            <select id="am_ti" class="form-control" name="book_date[time][]">
                                <?php 

                                for ($i=0; $i < 60/$config['am_time_interval']; $i++) { 
                                    echo "<option value=".$i*$config['am_time_interval'].">".$i*$config['am_time_interval']."</option>";
                                } 
                                ?>
                            </select>
                            <select style="display:none" disabled="true" id="pm_ti" class="form-control" name="book_date[time][]">
                                <?php 
                                for ($i=0; $i < 60/$config['pm_time_interval']; $i++) { 
                                    echo "<option value=".$i*$config['pm_time_interval'].">".$i*$config['pm_time_interval']."</option>";
                                } 
                                ?>
                            </select>
                            <span class="input-group-addon">分</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function () {

    $(".date-picker").datepicker({
        language: 'zh-CN',
        format: "yyyy-m-d",
        autoclose: true,
    });

    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            setTimeout('$(".Validform_msg").hide()', 1500);
            if(response.status == "y"){
                if(confirm('是否继续添加')){
                    form.resetForm();
                    $('#am').show().removeAttr('disabled');
                    $('#am_ti').show().removeAttr('disabled');
                    $('#pm').hide().attr('disabled', 'true');
                    $('#pm_ti').hide().attr('disabled', 'true');
                    $('#dashboard_view').click();
                }else{
                    $('#myModal').modal('hide');
                    $('#dashboard_view').click();
                }
            }
        }
    });

    $('#amorpm').change(function(event) {
        var v = $(this).val();
        if(v == 1){
            $('#pm').show().removeAttr('disabled');
            $('#pm_ti').show().removeAttr('disabled');
            $('#am').hide().attr('disabled', 'true');
            $('#am_ti').hide().attr('disabled', 'true');
        }else{
            $('#am').show().removeAttr('disabled');
            $('#am_ti').show().removeAttr('disabled');
            $('#pm').hide().attr('disabled', 'true');
            $('#pm_ti').hide().attr('disabled', 'true');
        }
    });
})
</script>