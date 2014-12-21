<body>
<nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
<!-- We use the fluid option here to avoid overriding the fixed width of a normal container within the narrow content columns. -->
<div class="container">
    <div class="navbar-header">
        <a href="#" class="navbar-brand">LOGO</a>
    </div>
</div>
</nav>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">预约时间表</h3>
        </div>
        <div class="panel-body">
        	<div class="row">
                <div class="col-sm-4 col-md-3">
                    <div id="date-picker"></div>
                    <div id="doctors" class="list-group">
                        <?php foreach ($doctors as $key => $value):?>
                        <a href="javascript:void(0)" class="list-group-item" onclick="select_docotor(<?php echo $value->id?>, this)"><?php echo $value->name?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-sm-8 col-md-9">                   
                    <div id="book-table"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">预约</h4>
            </div>
            <form id='addForm' class="form-horizontal" action="<?php echo site_url("home/book_save")?>">
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-md-4 control-label">预约人</label>
                    <div class="col-md-4">
                        <input autocomplete="off" class="form-control" type='text' name="name" value='' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">预约人电话</label>
                    <div class="col-md-4">
                        <input autocomplete="off" class="form-control" type='text' name="phone" value='' datatype="m" nullmsg="请输入电话！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">预约时间</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' readonly id="book_date" name="book_date" datatype="*" nullmsg="请选择预约时间" value=''/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="btn_sub" >保存</button>
                <input type='hidden' id="doctor_id" name="doctor_id" datatype="*" nullmsg="请选择医生" value=''/>
            </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
<?php $this->load->view('front/js'); ?>
<script type="text/javascript">
var post_data = {doctor_id: 0,book_date: ''},
    date_picker;
$(function () {
    date_picker = $('#date-picker').datepicker({
        language:'zh-CN',
        format: 'yyyy-mm-dd',
        startDate: new Date()
    }).on('changeDate', function(ev){
        load_book_table();
    });

    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            setTimeout('$("#Validform_msg").hide()', 2500);
            load_book_table();
            form.resetForm();
            $('#myModal').modal('hide');
        }
    });

    $('#doctors').find('a:first').click();
})
//预约对话框
function book_dialog(date){
    $('#book_date').val(date);
}
//预约对话框
function select_docotor(id,obj){
    post_data.doctor_id = id;
    $('#doctor_id').val(id);
    $(obj).siblings().removeClass('active');
    $(obj).addClass('active');
    load_book_table();
}
//预约时间加载
function load_book_table () {
    post_data.book_date = date_picker.datepicker('getDate').toUTCString();
    $.ajax({
        url: '<?=site_url("home/book_list")?>',
        type: 'POST',
        dataType: 'html',
        data: post_data,
    })
    .done(function(html) {
        $('#book-table').html(html)
    })
}
</script>
</body>
</html>