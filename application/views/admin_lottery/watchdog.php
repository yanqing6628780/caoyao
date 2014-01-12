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
                        <h5><?=$row['title']?>: 指定中奖人</h5>
                    </div>
                    <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."watchdog_save")?>">
                        <?php foreach ($lottery as $key => $item) {?> 
                            <div class="control-group">
                                <label class="control-label"><?=$item['content']?>(人数:<?=$item['num']?>)</label>
                                <div class="controls">
                                    <select name="watchdog[<?=$item['id']?>][]" class="watchdog_select" id="watchDog_<?=$item['id']?>" multiple="multiple">
                                        <?php foreach ($customers as $key => $member) {?>
                                            <option value="<?=$member['user_id']?>" <?php if( isset($item['watchdog']) and in_array($member['user_id'], $item['watchdog']) ){echo "selected";}?> ><?=$member['name']?></option>
                                        <?php }?>
                                        <option value="" selected></option>
                                    </select>
                                </div>
                            </div>
                        <?php }?>
                        <div class="control-group">
                            <label class="control-label">所属会议</label>
                            <div class="controls">
                                <ul class="inline">
                                    <li class="text-error"><?=$row['party']['title']?></li>
                                    <li class="text-error">日期: <?=$row['party']['startDate']?>至<?=$row['party']['endDate']?></li>
                                </ul>
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
<link rel="stylesheet" href="<?php echo base_url()?>lib/multi-select/css/multi-select.css" />
<script src="<?php echo base_url()?>js/validform_v5.3.2.js"></script>
<script src="<?php echo base_url()?>lib/multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript">
$(function () {
    var form = $("#editForm").Validform({
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){            
                window.location = "<?php echo site_url($controller_url)?>"
            }else{
                location.reload();
            }
        }
    });

    $('.watchdog_select').multiSelect({
        selectableHeader: "<div class='custom-header'>参会人员</div>",
        selectedHeader: "<div class='custom-header'>指定中奖人</div>" 
    });
})
function content_add(obj)
{
    var _this = $(obj);
    var string = '<div class="controls controls-row">'
    string += '<input class="input-large span3" name="content[]" type="text" placeholder="奖项名称">'
    string += '<div class="input-append span2">'
    string += '<input class="input-mini" name="content_num[]" type="text" placeholder="中奖人数">'
    string += '<button onclick="content_del(this)" class="btn" type="button"><i class="icon-minus"></i></button>'
    string += '<button onclick="content_add(this)" class="btn" type="button"><i class="icon-plus"></i></button>'
    string += '</div>'
    string += '</div>'
    _this.parents(".control-group").append(string);

}
function content_del(obj)
{
    var _this = $(obj);
    _this.parents(".controls").remove();
}
</script>

</body>
</html>