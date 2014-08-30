<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box blue'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>参会人员方案设置</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions"></div>
            </div>
            <div class="portlet-body form">
                <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."members_scheme_save")?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">名称</label>
                            <div class="col-md-4">
                                <p class="form-control-static"><?=$row['exchange_fair_name']?> <span class="label label-info">时间：<?=$row['start_time']?>至<?=$row['end_time']?></span></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">方案设置</label>
                            <div class="col-md-10">
                                <?php foreach ($members as $key => $member): ?>
                                <div class="form-group">
                                    <label class="col-md-1 control-label"><?=$member['cnname'] ? $member['cnname'] : $member['username']?></label>
                                    <div class="col-md-10">
                                        <div class="col-md-7 input-group">
                                            <input placeholder="限制金额" class="form-control" type="text" name="amount[<?=$member['user_id']?>]" value="<?=$member['amount']?>">
                                            <span class="input-group-addon">元</span>
                                        </div>
                                        <div class="col-md-7 input-group">
                                            <select class="form-control scheme" name="necessities_scheme_id[<?=$member['user_id']?>]">
                                                <?php foreach ($necessities_scheme as $key => $value) {?>                                        
                                                    <option <?=option_select($member['necessities_scheme_id'], $value['id']); ?> value="<?=$value['id']?>" ><?=$value['necessities_scheme_name']?></option>
                                                <?php }?>
                                            </select>
                                            <span class="input-group-addon">必需品方案</span>
                                        </div>
                                        <div class="col-md-7 input-group">
                                            <select class="form-control scheme" name="small_class_restrictions_id[<?=$member['user_id']?>]">
                                                <?php foreach ($small_class_restrictions as $key => $value) {?>
                                                    <option <?=option_select($member['small_class_restrictions_id'], $value['id']); ?> value="<?=$value['id']?>" ><?=$value['small_class_restrictions_name']?></option>
                                                <?php }?>
                                            </select>
                                            <span class="input-group-addon">小类限制方案</span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-offset-3 col-md-9">
                                    <a id="save_next" class="btn blue button-next" href="javascript:;">保存 <i class="m-icon-swapright m-icon-white"></i></a>
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
<script type="text/javascript">
jQuery(document).ready(function() {   
    var form = $("#addForm").Validform({
        btnSubmit:'#save_next',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
        }
    });

    $('.scheme').select2();
});
</script>