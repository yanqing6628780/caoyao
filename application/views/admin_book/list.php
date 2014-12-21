<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-sm-4 col-md-3">
        <div id="date-picker" data-date="<?php echo $book_date?>"></div>
        <div class="list-group">
            <?php foreach ($doctors as $key => $value):?>
            <a href="javascript:void(0)" class="list-group-item" onclick="select_docotor(<?php echo $value->id?>)"><?php echo $value->name?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-sm-8 col-md-9">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>预约</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions">
                    <a onclick="add()" class="btn blue"  href="#myModal" data-toggle="modal" ><i class="icon-plus"></i> 添加预约</a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">                    
                        <form class="form-inline" id="search">
                            <div class="form-group">
                                <label class="sr-only" for="exampleInputEmail2">预约人</label>
                                <input type="text" placeholder="预约人" class="form-control" value="" name="q">
                            </div>
                            <input type="button" onclick="query()" class="btn btn-default" value="搜索">
                        </form>
                    </div>
                </div>
                <div class="table-scrollable">
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>预约日期</th>
                                <th>预约人</th>
                                <th>预约电话</th>
                                <th>预约医生</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $key => $row):?>
                            <tr>
                                <td><?=$row['book_date']?></td>
                                <td><?=$row['name']?></td>
                                <td><?=$row['phone']?></td>
                                <td><?=$row['doctor_name']?></td>
                                <td>
                                    <?php if (chk_perm_to_bool('book_edit')): ?>
                                    <button class="btn btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
                                    <?php endif ?>                                    
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var post_data = {doctor_id: 0,book_date: '<?php echo $book_date?>'};
function add(){
    LoadAjaxPage('<?=site_url($controller_url."add/")?>', '', 'myModal','添加')
}
function edit(id){
    LoadAjaxPage('<?=site_url($controller_url."edit/")?>', {id: id}, 'myModal','编辑')
}
function del(id){
    common_del('<?=site_url($controller_url."del")?>', id, '', "#dashboard_view");
}
function query() {
    var formData = $('#search').serialize();
    LoadPageContentBody('<?=site_url($controller_url)?>', formData);
}
$(function() {       
    TableAdvanced.init();

    $date_picker = $('#date-picker');
    
    $date_picker.datepicker({
        language:'zh-CN',
        format: 'yyyy-mm-dd',
    }).on('changeDate', function(ev){
        post_data.book_date = ev.date.toUTCString();
        LoadPageContentBody('<?=site_url($controller_url)?>', post_data);
    });
    $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
});
function select_docotor(id){
    post_data.doctor_id = id;
    LoadPageContentBody('<?=site_url($controller_url)?>', post_data);
};
</script>
<script type="text/javascript" src="<?=base_url()?>js/page.js"></script>