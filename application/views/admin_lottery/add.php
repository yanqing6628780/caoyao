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
                        <h5>添加抽奖</h5>
                    </div>
                    <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
                        <div class="control-group">
                            <label class="control-label">抽奖主题</label>
                            <div class="controls">
                                <input type='text' name="title" value='' datatype="*" nullmsg="请输入名称！"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">奖项</label>
                            <div class="controls controls-row">
                                <input class="input-large span3" name="content[]" type="text" placeholder="奖项名称" datatype="*" nullmsg="请输入奖项！">
                                <div class="input-append span2">
                                    <input class="input-mini" name="content_num[]" type="text" placeholder="中奖人数">
                                    <button onclick="content_add(this)" class="btn tip-bottom content_add" data-original-title="增加选项" type="button"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">所属会议</label>
                            <div class="controls">
                                <select class="select2" name="party_id" id="party_id" datatype="*" nullmsg="请选择所属会议！">
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