<script src="<?php echo base_url()?>js/validform_v5.3.2.js"></script>
<script src="<?php echo base_url()?>js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url()?>js/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
$(function () {
    $(".datetimepicker").datetimepicker()
    var form = $("#addForm").Validform({
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){            
                if(confirm('是否继续添加')){
                    location.reload(true);
                }else{
                    window.location = "<?php echo site_url($controller_url)?>"
                }
            }
        }
    });
})
</script>

<body class="bg_white">
<div class="container-fluid">
    <div class='row-fluid'>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-content nopadding">
                    <div class="widget-title">
                        <span class="icon">
                            <i class="icon-align-justify"></i>                                  
                        </span>
                        <h5>添加签到</h5>
                    </div>
                    <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
                        <div class="control-group">
                            <label class="control-label">签到名称</label>
                            <div class="controls">
                                <input type='text' name="title" value='' datatype="*" sucmsg="" nullmsg="请输入名称！"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">开始时间</label>
                            <div class="controls">
                                <input type='text' name='startTime' class="datetimepicker" data-date-format="yyyy-mm-dd hh:ii" datatype="*"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">结束时间</label>
                            <div class="controls">
                                <input type='text' name='endTime' value='' class="datetimepicker" data-date-format="yyyy-mm-dd hh:ii" datatype="*"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">所属会议</label>
                            <div class="controls">
                                <select name="party_id" datatype="*" nullmsg="请选择所属会议！">
                                    <?php foreach ($partys as $key => $row) {?>                                        
                                        <option value="<?=$row['id']?>"><?=$row['title']?> 日期:<?=$row['startDate']?>至<?=$row['endDate']?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>                        
                        <div class="form-actions">
                            <input type='submit' name="save" class="btn btn-inverse btn-large" value='保存'/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>