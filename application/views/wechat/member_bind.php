<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">微信卡号: <?=$row['wechat_cardno']?><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?=site_url($controller_url."member_bind")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">食通天卡号</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="cardNo" value='<?=$row['cardNo']?>' datatype="*"/>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='绑定'/>
                    <input type='hidden' name="id" value='<?=$row['id']?>'/>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function () {
    var form = $("#editForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:2,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){
                $('#wechat_member_view').click();
            }
        }
    });
})
</script>