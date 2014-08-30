<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i><?=$row['small_class_restrictions_name']?></div>
    </div>
    <div class="portlet-body form">
        <?php if($ids): ?>
            <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."scheme_edit_save")?>">
                <div class="form-body">
                    <?php foreach ($small_classes as $key => $value) {?>                                        
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=$value['small_class_name']?>(百分比%)</label>
                        <div class="col-md-4 controls">
                            <div class="input-group spinner" data-trigger="spinner">
                                <div class="spinner-buttons input-group-btn">
                                    <button type="button" class="btn spin-down btn-success" data-spin="down">
                                    <i class="glyphicon glyphicon-minus"></i>
                                    </button>
                                </div>
                                <input name=" <?=$value['scheme_id'] ? "scheme_ids[".$value['scheme_id']."]" : "small_class[".$value['id']."]" ?> " value="<?=$value['percentage'] ? $value['percentage'] : 0?>" class="spinner-input form-control" type="text" data-min="0">
                                <div class="spinner-buttons input-group-btn">
                                    <button type="button" class="btn spin-up btn-danger" data-spin="up">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <div class="form-actions fluid">
                    <div class="col-md-offset-3 col-md-9">
                        <a id="save_necessaries" class="btn blue button-next" href="javascript:;">保存 <i class="m-icon-swapright m-icon-white"></i></a>
                        <input type='hidden' name="id" value='<?=$id?>'/>
                    </div>
                </div>
            </form>
        <?php else: ?>
        <form id="fisrt_form" class="form-horizontal" action="<?php echo site_url($controller_url."necessaries_edit")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-6 controls">
                        <select name="class_id[]" id="optgroup" multiple="multiple">
                            <?php foreach ($small_classes as $key => $value) {?>                                        
                                <option value="<?=$value['id']?>" <?=$value['selected']?> ><?=$value['small_class_name']?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <a id="save_next" class="btn blue button-next" href="javascript:;">下一步 <i class="m-icon-swapright m-icon-white"></i></a>
                    <input type='hidden' name="id" value='<?=$id?>'/>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/jquery-multi-select/css/multi-select.css" />
<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?=site_url('js/jquery.spinner.min.js')?>"></script>
<script type="text/javascript">
$(function () {
    $('#save_next').click(function(event) {
        LoadPageContentBody('<?=site_url($controller_url."scheme_edit")?>',$("#fisrt_form").serialize());
    });
    var form = $("#editForm").Validform({
        btnSubmit: '#save_necessaries',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            LoadPageContentBody('<?=site_url($controller_url."scheme_edit")?>',{id: <?=$row['id']?>});
        }
    });

    $('#optgroup').multiSelect({
        selectableHeader: "<div class='custom-header'>产品列表</div>",
        selectionHeader: "<div class='custom-header'>必需品</div>"
    });

    $(".spinner").spinner('delay', 200);
})
</script>