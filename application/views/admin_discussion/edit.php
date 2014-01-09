<script src="<?php echo base_url()?>js/validform_v5.3.2.js"></script>
<script src="<?php echo base_url()?>js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url()?>js/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
$(function () {
    $(".datetimepicker").datetimepicker()
    var form = $("#editForm").Validform({
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){            
                window.location = "<?php echo site_url($controller_url)?>"
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
                        <h5>编辑讨论</h5>
                    </div>
                    <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
                        <div class="control-group">
                            <label class="control-label">讨论主题</label>
                            <div class="controls">
                                <input type='text' name="title" value='<?=$row['title']?>' datatype="*" sucmsg="" nullmsg="请输入名称！"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">所属会议</label>
                            <div class="controls">
                                <select name="party_id" datatype="*" nullmsg="请选择所属会议！">
                                    <?php foreach ($partys as $key => $value) {?>                                        
                                        <option value="<?=$value['id']?>" <?php option_select($row['party_id'], $value['id'])?> ><?=$value['title']?> 日期:<?=$value['startDate']?>至<?=$value['endDate']?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>                        
                        <div class="form-actions">
                            <input type='submit' name="save" class="btn btn-inverse btn-large" value='保存'/>
                            <input type='hidden' name="id" value="<?=$row['id']?>"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>