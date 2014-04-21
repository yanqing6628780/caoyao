<link type="text/css" href="<?=base_url()?>assets/css/pages/profile.css" rel="stylesheet" />
<link type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet"/>
<link type="text/css" href="<?=base_url()?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<div class="row profile-account">
    <div class="col-md-3">
      <ul class="ver-inline-menu tabbable margin-bottom-10">
         <li class="active">
            <a href="#tab_1-1" data-toggle="tab"><i class="icon-cog"></i> 门店信息</a> 
            <span class="after"></span>                                    
         </li>
         <li class=""><a href="#tab_2-2" data-toggle="tab"><i class="icon-picture"></i> 改变头像</a></li>
         <li class=""><a href="#tab_3-3" data-toggle="tab"><i class="icon-lock"></i> 修改密码</a></li>
      </ul>
    </div>
    <div class="col-md-9">
      <div class="tab-content">
         <div class="tab-pane active" id="tab_1-1">
            <form role="form" id='profile' class="form-horizontal" action='<?=site_url('admin/user_admin/profile_save');?>' method='post' enctype="multipart/form-data">
               <div class="form-group">
                  <label class="control-label">门店名称</label>
                  <input type="text" class="form-control" name='profile[name]' value='<?=$profile->name?>'>
               </div>
               <div class="form-group">
                  <label class="control-label">经度</label>
                  <input type="text" class="form-control" name='profile[lng]' value='<?=$profile->lng?>'>
               </div>
               <div class="form-group">
                  <label class="control-label">纬度</label>
                  <input type="text" class="form-control" name='profile[lng]' value='<?=$profile->lng?>'>
               </div>
               <div class="form-group">
                  <label class="control-label">地址</label>
                  <input type="text" class="form-control" name='profile[address]' value='<?=$profile->address?>'>
               </div>
               <div class="form-group">
                  <label class="control-label">联系电话</label>
                  <input type="text" class="form-control" name='profile[email]' value='<?=$profile->email?>'>
               </div>
               <div class="margiv-top-10">
                  <a class="btn green" href="#">保存</a>
                  <a class="btn default" href="#">取消</a>
                  <?php if($profile->user_id != 1):?>
                  <a target="_blank" class="btn purple" href='<?=site_url("admin/user_admin/user_lbs/?user_id=".$profile->user_id)?>'><i class="icon-map-marker icon-white"></i> 地理位置</a>
                  <?php endif; ?>
               </div>
            </form>
         </div>
         <div class="tab-pane" id="tab_2-2">
            <p></p>
            <form role="form">
               <div class="form-group">
                  <div data-provides="fileupload" class="margin-top-10 fileupload fileupload-new">
                        <div class="fileupload-preview thumbnail" data-trigger="fileinput" style="width: 200px;">
                            <img src="<?=$profile->photo ? site_url($profile->photo) : 'http://www.placehold.it/310x170/EFEFEF/AAAAAA&amp;text=no+image'?>" alt="">
                        </div>
                        <div class="input-group input-group-fixed">
                            <span class="btn green btn-file">
                                <span class="btn green fileinput-button">
                                <i class="icon-paper-clip"></i> 
                                <span>选择文件</span>
                                <input type="file" name="files" id="avatarupload" class="default">
                                </span>
                            </span>
                            <span class="text-danger" id="uploadstatus"></span>
                        </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="tab-pane" id="tab_3-3">
            <form id="changePswForm">
               <div class="form-group">
                  <label class="control-label">旧密码</label>
                  <input type="password" name="old_password" class="form-control">
               </div>
               <div class="form-group">
                  <label class="control-label">新密码</label>
                  <input type="password" name='new_password' class="form-control">
               </div>
               <div class="form-group">
                  <label class="control-label">确认新密码</label>
                  <input type="password" name='confirm_new_password' class="form-control">
               </div>
               <div class="margin-top-10">
                  <a class="btn green" href="javascript:" onclick="savePassword()">保存</a>
                  <a class="btn default" href="javascript:">取消</a>
               </div>
            </form>
         </div>
      </div>
    </div>
    <!--end col-md-9-->                                   
</div>
<script type="text/javascript">
function savePassword()
{
    $.ajax({
        type: "POST",
        url: '<?=site_url("admin/user_admin/change_password")?>',
        dataType: 'json',
        data: $("#changePswForm").serialize(),
        success: function(respone){
            if(respone.success){
                alert('修改密码成功');
            }else{
                if(respone.errors){
                    $.each(respone.errors, function(index, val) {
                         alert(val);
                    });
                }
            }
        }
    });
}
jQuery(document).ready(function() {
    // Initialize the jQuery File Upload widget:
    $('#avatarupload').fileupload({
        url: '<?=site_url("admin/user_admin/avatar_upload")?>',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 5000000, // 5 MB
        done: function (e, data) {
            if(data.result.file){
                $('.thumbnail img').attr('src', data.result.file.url);
                $("#uploadstatus").html('上传成功');
            }
            else if(data.result.error){            
                $("#uploadstatus").html(data.result.error);
                $("#uploadstatus").show();
            }
        }
    });
});
</script>