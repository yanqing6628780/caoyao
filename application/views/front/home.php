<body>
<div class="container-fluid">
	<div class="row">
        <div class="col-sm-4 col-md-2">
            <div id="date-picker"></div>
        </div>
        <div class="col-sm-8 col-md-10">                   
            <div id="book-table"></div>
        </div>
    </div>
</div>
<div class="container">
    <div class="alert alert-info">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url("home/book_save")?>">
            <div class="form-group">
                <label class="col-md-4 control-label">预约人</label>
                <div class="col-md-4">
                    <input class="form-control" type='text' name="name" value='' datatype="*" nullmsg="请输入名称！"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">预约人电话</label>
                <div class="col-md-4">
                    <input class="form-control" type='text' name="phone" value='' datatype="m" nullmsg="请输入电话！"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">预约时间</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">日期</span>
                        <input type="text" readonly class="form-control date-picker" name="book_date[date]" value='' datatype="*" nullmsg="请输入日期！">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">时间</span>
                        <select class="form-control" name="book_date[time][]">
                            <optgroup label="上午">
                                <?php for ($i= $config['bh_am_start']; $i <= $config['bh_am_end']; $i++) { 
                                    echo "<option value=".$i.">".$i."</option>";
                                } ?>
                            </optgroup>
                            <optgroup label="下午">
                                <?php for ($i= $config['bh_pm_start']; $i <= $config['bh_pm_end']; $i++) { 
                                    echo "<option value=".$i.">".$i."</option>";
                                } ?>
                            </optgroup>
                        </select>
                        <span class="input-group-addon">时</span>
                        <select class="form-control" name="book_date[time][]">
                            <?php 

                            for ($i=0; $i < 60/$config['time_interval']; $i++) { 
                                echo "<option value=".$i*$config['time_interval'].">".$i*$config['time_interval']."</option>";
                            } 
                            ?>
                        </select>
                        <span class="input-group-addon">分</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-4 col-md-8">
                    <input type='button' id="btn_sub" class="btn btn-success btn-lg" value='保存'/>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
<?php $this->load->view('front/js'); ?>
<script type="text/javascript">
$(function () {
    var now = new Date();
    load_book_table(now.toLocaleDateString())
    var date_picker = $('#date-picker').datepicker({
        language:'zh-CN',
        format: 'yyyy-mm-dd',
        startDate: new Date()
    }).on('changeDate', function(ev){
        load_book_table(ev.date.toLocaleDateString());
        book_datepicker.datepicker('update', ev.date.toLocaleDateString());
    });

    var book_datepicker = $(".date-picker").datepicker({
        language: 'zh-CN',
        format: "yyyy-m-d",
        autoclose: true,
        startDate: new Date()
    });

    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            setTimeout('$(".Validform_msg").hide()', 1500);
            if(response.status == "y"){
                load_book_table(book_datepicker.val());
                date_picker.datepicker('update', book_datepicker.val());
                form.resetForm();
            }
        }
    });
})
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