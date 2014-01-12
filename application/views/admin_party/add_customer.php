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
                        <h5>添加参会人员</h5>
                    </div>
                    <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."customer_save")?>">
                        <div class="control-group">
                            <label class="control-label">大会名称</label>
                            <div class="controls">
                                <input type="text" value="<?=$row['title']?>" readonly>
                                <span class="label label-info">
                                    时间：<?=$row['startDate']?>至<?=$row['endDate']?>
                                </span>
                                <span class="label label-info">
                                    大会地点：<?=$row['site']?>
                                </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">参与人</label>
                            <div class="controls">
                                <select name="user_id[]" id="optgroup" multiple="multiple">
                                    <?php foreach ($members as $key => $member) {?>                                        
                                        <option value="<?=$member['id']?>"  <?php if(in_array($member['id'], $customer_user_ids)){echo "selected";}?> ><?=$member['cnname'] ? $member['cnname'] : $member['username']?></option>
                                    <?php }?>
                                    <option value="" selected></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type='submit' name="save" class="btn btn-inverse btn-large" value='保存'/>
                            <input type='hidden' name="party_id" value="<?=$row['id']?>"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo base_url()?>lib/multi-select/css/multi-select.css" />
<script src="<?php echo base_url()?>js/validform_v5.3.2.js"></script>
<script src="<?php echo base_url()?>lib/multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript">
$(function () {
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

    $('#optgroup').multiSelect({
        selectableHeader: "<div class='custom-header'>会员列表</div>",
        selectedHeader: "<div class='custom-header'>参会人员</div>" 
    })
})
</script>
</body>
</html>