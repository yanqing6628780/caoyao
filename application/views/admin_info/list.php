<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions">
                    <button onclick="copy()" class="btn yellow"><i class="icon-copy"></i> 复制到免费信息</button>
                    <a onclick="add()" class="btn blue"  href="#myModal" data-toggle="modal" ><i class="icon-plus"></i> 添加</a>
                </div>
            </div>
            <div class="portlet-body">
                <form id="search" class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="sr-only">分类</label>
                        <select class="form-control" id="category" onchange="selectCategory(this)" name="table">
                            <option value="">请选择分类</option>
                            <option value="free_info" <?=option_select('free_info', $table)?> >免费信息</option>
                            <?php foreach ($categories as $key => $value): ?>
                            <option value="<?=$value['table']?>" <?=option_select($value['table'], $table)?> ><?=$value['name']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="sr-only">分类</label>
                        <input name="q" type="text" value="<?=$q?>" class="form-control" placeholder="关键字">
                    </div>
                    <input type="button" value="搜索" class="btn btn-default" onclick="infoQuery()" >
                </form>
                <hr>
                <div class="row">
                    <div class="col-md-12">    
                        <form id="dataForm">                                
                        <table class='table table-striped table-bordered table-hover'>
                            <thead>
                                <tr>
                                    <th width="80"><input id="checkboxes" type="checkbox"></th>
                                    <th width="80">类型</th>
                                    <th>地址</th>
                                    <th>单位</th>
                                    <th>电话</th>
                                    <th width="80">姓名</th>
                                    <th>手机</th>
                                    <th width="100">代理品牌</th>
                                    <th>时限</th>
                                    <th>价格</th>
                                    <th width="200">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($result as $key => $row):?>
                                <tr>
                                    <td><input type="checkbox" name="id[]" value="<?=$row['id']?>"></td>
                                    <td><?=$row['type']?></td>
                                    <td><?=$row['address']?></td>
                                    <td><?=$row['company']?></td>
                                    <td><?=$row['tel']?></td>
                                    <td><?=$row['name']?></td>
                                    <td><?=$row['mobile']?></td>
                                    <td><?=$row['actingbrand']?></td>
                                    <td><?=$row['time_limit']?></td>
                                    <td><?=$row['price']?></td>
                                    <td>
                                        <?php if (checkPermission2('info_edit')): ?>                                            
                                        <a  href="#myModal" data-toggle="modal"  onclick="edit(<?=$row['id']?>)" class="btn green"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                        <button class="btn btn-danger" onclick='del(<?=$row['id']?>, "<?=$table?>")'><i class="icon-remove icon-white"></i> 删除</button>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                        </form>
                    </div>
                </div>
                <?php if ($table): ?>                    
                <ul class="bootpag pagination">
                    <li class="prev <?=$current_page==1 ? 'disabled' : '' ?>" data-lp="<?=$current_page?>"><a class="ajaxify" href="<?=site_url($controller_url.'?table='.$table.'&page='.($current_page-1))?>"><icon class="icon-angle-left"></icon></a></li>
                    <?php for ($i=1; $i <= $page; $i++){ ?>    
                    <li data-lp="<?=$i?>" class="<?=$i == $current_page ? 'disabled' : '' ?>"><a class="ajaxify" href="<?=site_url($controller_url.'?table='.$table.'&page='.$i.'&q='.$q)?>"><?=$i?></a></li>
                    <? } ?>
                    <li class="next <?=$current_page==$page ? 'disabled' : '' ?>" data-lp="<?=$current_page?>"><a class="ajaxify" href="<?=site_url($controller_url.'?table='.$table.'&page='.($current_page+1))?>"><icon class="icon-angle-right"></icon></a></li>
                </ul>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function selectCategory(obj) {
    var value = $(obj).val();
    LoadPageContentBody('<?=site_url($controller_url)?>', {table: value});
}
function add(){
    LoadAjaxPage('<?=site_url($controller_url."add/")?>', "", 'myModal','添加');
}
function edit(id){
    LoadAjaxPage('<?=site_url($controller_url."edit/")?>', {id: id, table: '<?=$table?>'}, 'myModal','编辑');
}
function copy(){
    var checkBoxData = $('#dataForm').serializeArray();
    console.log(checkBoxData);
    $.ajax({
        type: "POST",
        url: '<?=site_url($controller_url."copy_to_free")?>',
        dataType: 'json',
        data: {id: checkBoxData, table: '<?=$table?>'},
        success: function(response){
            if(response.success){
                alert('复制成功');
            }else{
                alert('复制失败');
            }
        }
    });
}
function del(id, table){
    if(confirm('确认删除?'))
    {
        $.ajax({
            type: "POST",
            url: '<?=site_url($controller_url."del")?>',
            dataType: 'json',
            data: {id: id, table: table},
            success: function(respone){
                if(respone.success){                
                    alert( '删除成功' );
                    infoQuery();
                }else{
                    alert( '删除失败' );
                }
            }
        });
    }
}
function infoQuery() {
    var formData = $('#search').serialize();
    LoadPageContentBody('<?=site_url($controller_url)?>', formData);
}
$(function () {
    $('#category').select2();
    $('.pagination').on('click', ' li > a.ajaxify', function (e) {
        e.preventDefault();
        var url = $(this).attr("href");
        LoadPageContentBody(url);
    });
    $('#checkboxes').bind('click', function(event) {
        var checked = $(this).is(":checked");
        var tbodyCheckBox = $(this).parents('table').find(':checkbox');
        tbodyCheckBox.each(function() {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }
        });
    });
})
</script>
