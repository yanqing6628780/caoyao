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
            </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
<?php $this->load->view('front/js'); ?>
<script type="text/javascript">
$(function () {
    var date_picker = $('#date-picker').datepicker({
        language:'zh-CN',
        format: 'yyyy-mm-dd',
        startDate: new Date()
    }).on('changeDate', function(ev){
        load_book_table(ev.date.toUTCString());
    });

    load_book_table(date_picker.datepicker('getDate').toUTCString());

    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            setTimeout('$("#Validform_msg").hide()', 2500);
            load_book_table(date_picker.datepicker('getDate').toUTCString());
            form.resetForm();
            $('#myModal').modal('hide');
        }
    });
})
function book(date){
    $('#book_date').val(date);
}
function load_book_table (date) {
    $.ajax({
        url: '<?=site_url("home/book_list")?>',
        type: 'POST',
        dataType: 'html',
        data: {book_date: date},
    })
    .done(function(html) {
        $('#book-table').html(html)
    })
}
</script>
</body>
</html>