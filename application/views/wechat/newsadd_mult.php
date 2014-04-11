<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/jquery-nestable/jquery.nestable.css" />
<div class="row">
    <div class="col-md-12">
        <div class="note note-warning">
            <h4 class="block">多图文说明</h4>
            <p>将左边的单图文拖动到右边,组合成多图文</p>
            <p>注意:多图文默认第一个图文作为封面</p>
            <button type="button" onclick="saveMultNews()" class="btn btn-lg btn-success">保存</button>
         </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="icon-comments"></i>单图文</div>
            </div>
            <div class="portlet-body ">
                <div class="dd" id="nestable_list_1">
                    <ol class="dd-list">
                        <?php foreach ($result as $key => $value): ?>                                
                            <li class="dd-item" data-id="<?=$value['id']?>">
                                <div class="dd-handle" style="height:auto">
                                    <div class="media">
                                      <a class="pull-left" href="#">
                                        <img style="width:64px" class="img-responsive media-object" src="<?=$value['picurl']?>" alt="...">
                                      </a>
                                      <div class="media-body">
                                        <h4 class="media-heading"><?=$value['title']?></h4>
                                        <?=$value['description']?>
                                      </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach ?>
                    </ol>
                </div>
            </div>
       </div>
    </div>
    <div class="col-md-7">
        <div class="portlet box blue">
             <div class="portlet-title">
                 <div class="caption"><i class="icon-comments"></i>多图文</div>
             </div>
             <div class="portlet-body ">
                 <div class="dd" id="nestable_list_2">
                     <div class="dd-empty"></div>
                 </div>
             </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-center"><button type="button" onclick="saveMultNews()" class="btn btn-block btn-success">保存</button></div>
</div>
<link type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet"/>
<link type="text/css" href="<?=base_url()?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery-nestable/jquery.nestable.js"></script>  
<script type="text/javascript">
function saveMultNews(){
    var data = $('#nestable_list_2').data('output');
    var jsonData = {};
    if(data){
        jsonData = window.JSON.parse(data);
    }
    if(jsonData.length > 1){    
        $.ajax({
            url: '<?php echo site_url($controller_url."news_mult_save")?>',
            type: 'POST',
            dataType: 'json',
            data: {ids: data},
            success: function (response) {
                alert(response.info);
                if(response.status == "y"){
                    $('#wechat_appmsg_view').click();
                }
            }
        })
    }else{
        alert('多图文消息必须至少需要两个单图文')
    }
}
$(function () {
    $('#nestable_list_2').data('output', "");

    $('#nestable_list_1').nestable({
        group: 1,
        maxDepth: 1
    })

    // activate Nestable for list 2
    $('#nestable_list_2').nestable({
        group: 1,
        maxDepth: 1
    }).on('change', function (e) {
       var list = $(e.target);

       if (window.JSON) {
           list.data('output', window.JSON.stringify(list.nestable('serialize')) );
       } else {
           list.data('output', 'JSON browser support required for this demo.');
       }
    });

})

</script>