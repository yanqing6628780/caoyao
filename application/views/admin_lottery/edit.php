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
                        <h5>编辑抽奖</h5>
                    </div>
                    <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
                        <div class="control-group">
                            <label class="control-label">抽奖主题</label>
                            <div class="controls">
                                <input type='text' name="title" value='<?=$row['title']?>' datatype="*" sucmsg="" nullmsg="请输入名称！"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">奖项</label>
                            <div class="controls controls-row">
                                <ol>
                                <?php foreach ($row['content'] as $key => $value) {?>                                        
                                    <li class="text-success">
                                        <?=sprintf("%s(人数:%s)", $key, $value)?>
                                        <input name="content[]" type="hidden" value="<?=$key?>">
                                        <input name="content_num[]" type="hidden" value="<?=$value?>">
                                    </li>
                                <?php }?>
                                </ol>
                            </div>
                            <div class="controls controls-row">
                                <input class="input-large span3" name="content[]" type="text" placeholder="奖项名称">
                                <div class="input-append span2">
                                    <input class="input-mini" name="content_num[]" type="text" placeholder="中奖人数">
                                    <button onclick="content_add(this)" class="btn tip-bottom content_add" data-original-title="增加选项" type="button"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">指定中奖人</label>
                            <div class="controls">
                                <select name="watchdog[]" id="watchDog" multiple="multiple">
                                    <?php foreach ($watchdog as $value) {?>                                        
                                        <?=$value?>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
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
                            <input type='hidden' name="id" value="<?=$row['id']?>"/>
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
                // window.location = "<?php echo site_url($controller_url)?>"
            }
        }
    });

    $('#watchDog').multiSelect({
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