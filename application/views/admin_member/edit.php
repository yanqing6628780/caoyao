<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='user_edit' class="form-horizontal" action="<?php echo site_url('admin/member/edit_save')?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">门店名称</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[name]' value='<?=$profile->name?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">联系电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[phone]' value='<?=$profile->phone?>'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">联系人</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[contact]' value='<?=$profile->contact?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">传真</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[fax]' value='<?=$profile->fax?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">地址</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name='profile[address]' value='<?=$profile->address?>'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">分公司</label>
                    <div class="col-md-4">
                        <select name="profile[branch_id]" class="form-control">
                            <?php foreach ($branches as $key => $value): ?>
                                <option value="<?=$value['id'] ?>" <?=option_select($value['id'], $profile->branch_id) ?> ><?=$value['branch_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='submit' class="btn blue btn-lg" value='保存'/>
                    <input type='hidden' name="user_id" value="<?=$user_id?>" />
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function () {
    var form = $("#user_edit").Validform({
        tiptype:3,
        ajaxPost:true,
        callback:function(data){
            $('#member_view').click();
        }
    });
})
</script>