<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box blue'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>产品关联设置</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions"></div>
            </div>
            <div class="portlet-body form">
                <form id='addForm' class="form-horizontal" action="<?=site_url($controller_url."relation_save")?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">产品</label>
                            <div class="col-md-4 controls">
                                <select name="product_id[]" id="optgroup" multiple="multiple">
                                    <?php foreach ($group_products as $key => $item) {?> 
                                    <optgroup label="<?=$item['small_class_name'] ?>">
                                        <?php foreach ($item['products'] as $key => $value): ?>
                                        <option value="<?=$value['id']?>" <?=$this->product_mdl->is_relation($id, $value['id']) ? 'selected': '' ; ?> ><?=$value['product_name']?></option>
                                        <?php endforeach ?>
                                    </optgroup>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-offset-3 col-md-9">
                                    <a id="save_next" class="btn blue button-next" href="javascript:;">保存</a>
                                    <input type='hidden' name="id" value="<?=$id?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/jquery-multi-select/css/multi-select.css" />
<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {   
    var form = $("#addForm").Validform({
        btnSubmit:'#save_next',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            $('#product_relate_view').click();
        }
    });

    $('#optgroup').multiSelect({
        selectableHeader: "<div class='custom-header'>产品列表</div>",
        selectionHeader: "<div class='custom-header'>关联产品</div>"
    });
});
</script>