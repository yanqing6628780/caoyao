<?php $this->load->view('admin/min-head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box green'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>系统配置</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
            </div>
            <div class="portlet-body">
                <form id="editform" class="form-horizontal " action="<?php echo site_url("sys/save")?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">上午经营时间</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">开始</span>
                                    <select name="bh_am_start"  class="form-control" id="am_start">
                                        <?php for ($i=6; $i <= 12; $i++) { 
                                            echo "<option value='".$i."'>".$i.":00</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">结束</span>
                                    <select name="bh_am_end"  class="form-control" id="am_end">
                                        <?php for ($i=7; $i <= 12; $i++) { 
                                            echo "<option value='".$i."'>".$i.":00</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">下午经营时间</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">开始</span>
                                    <select name="bh_pm_start"  class="form-control" id="pm_start">
                                        <?php for ($i=14; $i <= 18; $i++) { 
                                            echo "<option value='".$i."'>".$i.":00</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">结束</span>
                                    <select name="bh_pm_end"  class="form-control" id="pm_end">
                                        <?php for ($i=15; $i <= 18; $i++) { 
                                            echo "<option value='".$i."'>".$i.":00</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">时间间隔</label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <select name="time_interval" class="form-control">
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                    </select>
                                    <span class="input-group-addon">分钟</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-offset-3 col-md-9">
                                    <button onclick="editsave()" id="btn_sub" class="btn green" type="button"><i class="icon-ok"></i> 保存</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function editsave(){
    var data = $('#editform').serialize();
    LoadAjaxPage('<?php echo site_url("admin/sys/config_save")?>', data, 'myModal','编辑');
}
$(function() {       

    $('#am_start').change(function (ev) {
        $('#am_end').empty();
        for (var i = parseInt($(this).val())+1; i <= 12; i++) {
            $('#am_end').append("<option value='"+ i +"'>"+ i +"</option>");
        };
    });

    $('#pm_start').change(function (ev) {
        $('#pm_end').empty();
        for (var i = parseInt($(this).val())+1; i <= 18; i++) {
            $('#pm_end').append("<option value='"+ i +"'>"+ i +"</option>");
        };
    });
});
</script>
