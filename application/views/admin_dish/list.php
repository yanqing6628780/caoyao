<link type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet"/>
<link type="text/css" href="<?=base_url()?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>菜式列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
            </div>
            <div class="portlet-body">
                <table class='table table-striped table-bordered table-hover' id="sample_1">
                    <thead>
                        <tr>
                            <th width="120">图片</th>
                            <th>菜品号</th>
                            <th>菜品名</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><div style="width:100px"><img class="img-responsive" src="<?=$row['photo'] ? get_image_url($row['photo']) : 'http://placehold.it/100/F0F0F0' ?>" alt=""></div></td>
                            <td><?=$row['ch_dishno']?></td>
                            <td><?=$row['vch_dishname']?></td>
                            <td>
                                <div class="input-group input-group-fixed">
                                    <span class="btn green btn-file">
                                        <span class="btn green fileinput-button">
                                        <i class="icon-paper-clip"></i> 
                                        <span>上传图片</span>
                                        <input type="file" data-dishno="<?=$row['ch_dishno']?>" data-userid="<?=$row['user_id']?>" name="files" class="default imgupload">
                                        </span>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {       
    TableAdvanced.init();

    $('.imgupload').fileupload({
        url: '<?=site_url("files/imgUpload/?dir=dish")?>',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 5000000, // 5 MB
        done: function (e, data) {
            var inputData = $(this).data();
            if(data.result.file){
                $.ajax({
                    url: '<?=site_url("admin/dish/photo_save")?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {user_id: inputData.userid, dishno: inputData.dishno, photo: data.result.file.photo_path},
                    success: function (response) {
                        alert(response.info);
                    }
                })
                $(this).parents('tr').find('img').attr('src', data.result.file.url);
            }
            else if(data.result.error){            
                alert(data.result.error);
            }
        }
    });
});
</script>
