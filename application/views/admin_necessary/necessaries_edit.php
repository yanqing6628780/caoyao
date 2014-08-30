<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i><?=$row['necessities_scheme_name']?></div>
    </div>
    <div class="portlet-body form">
        <?php if($product_ids): ?>
            <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."necessaries_edit_save")?>">
                <div class="form-body">
                    <?php foreach ($products as $key => $value) {?>                                        
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=$value['product_name']?></label>
                        <div class="col-md-9 controls">
                            <table class="table table-bordered">
                                 <thead>
                                    <tr>
                                        <th>颜色</th>
                                        <th>数量<span class="text-danger">(数量设置为0的款式会从方案中删除)</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($value['attrs'] as $attr): ?>
                                    <tr>
                                        <td><?=$attr['values']?></td>
                                        <td>
                                            <div class="input-group spinner" data-trigger="spinner">
                                                <div class="spinner-buttons input-group-btn">
                                                    <button type="button" class="btn spin-down btn-success" data-spin="down">
                                                    <i class="glyphicon glyphicon-minus"></i>
                                                    </button>
                                                </div>
                                                <input name=" <?=$attr['necessities_id'] ? "necessities_ids[".$attr['necessities_id']."]" : "color[".$value['id']."][".$attr['id']."]" ?> " value="<?=$attr['MQP'] ? $attr['MQP'] : 0?>" class="spinner-input form-control" type="text" data-min="0">
                                                <div class="spinner-buttons input-group-btn">
                                                    <button type="button" class="btn spin-up btn-danger" data-spin="up">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                               </tbody>
                            </table>
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
                        <select name="product_id[]" id="optgroup" multiple="multiple">
                            <?php foreach ($products as $key => $value) {?>                                        
                                <option value="<?=$value['id']?>" <?=$value['selected']?> ><?=$value['product_name']?></option>
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
        LoadPageContentBody('<?=site_url($controller_url."necessaries_edit")?>',$("#fisrt_form").serialize());
    });
    var form = $("#editForm").Validform({
        btnSubmit: '#save_necessaries',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            LoadPageContentBody('<?=site_url($controller_url."necessaries_edit")?>',{id: <?=$row['id']?>});
        }
    });

    $('#optgroup').multiSelect({
        selectableHeader: "<div class='custom-header'>产品列表</div>",
        selectionHeader: "<div class='custom-header'>必需品</div>"
    });

    $(".spinner").spinner('delay', 200);
})
</script>