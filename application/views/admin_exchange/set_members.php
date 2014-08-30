<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box blue'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>参会人员设置</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions"></div>
            </div>
            <div class="portlet-body form">
                <form id='addForm' class="form-horizontal" action="<?=site_url($controller_url."members_save")?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">名称</label>
                            <div class="col-md-4">
                                <p class="form-control-static"><?=$row['exchange_fair_name']?> <span class="label label-info">时间：<?=$row['start_time']?>至<?=$row['end_time']?></span></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">参与人</label>
                            <div class="col-md-4 controls">
                                <select name="user_id[]" id="optgroup" multiple="multiple">
                                    <?php foreach ($members as $key => $member) {?>                                        
                                        <option value="<?=$member['user_id']?>"  <?=$member['state'] ? 'selected' : ''?> ><?=$member['cnname'] ? $member['cnname'] : $member['username']?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-offset-3 col-md-9">
                                    <a id="save_next" class="btn blue button-next" href="javascript:;">下一步 <i class="m-icon-swapright m-icon-white"></i></a>
                                    <input type='hidden' name="exchange_id" value="<?=$row['id']?>"/>
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
            LoadPageContentBody('<?=site_url($controller_url."set_members_scheme")?>',{id: <?=$row['id']?>})
        }
    });

    $('#optgroup').multiSelect({
        selectableHeader: "<div class='custom-header'>会员列表</div>",
        selectionHeader: "<div class='custom-header'>参会人员</div>"
    });
});
</script>