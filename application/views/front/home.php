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
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">预约</h3>
        </div>
        <div class="panel-body">
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
                            <input type="text" readonly class="form-control date-picker" name="book_date[date]" value='<?php echo date('Y-m-d') ?>' datatype="*" nullmsg="请输入日期！">
                        </div>
                        <div class="input-group">
                            <select id="amorpm" class="form-control">
                                <option value="0">上午</option>
                                <option value="1">下午</option>
                            </select>
                            <span class="input-group-addon">时间</span>
                            <select id="am" class="form-control" name="book_date[time][]">
                                <?php for ($i= $config['bh_am_start']; $i <= $config['bh_am_end']; $i++) { 
                                    echo "<option value=".$i.">".$i."</option>";
                                } ?>
                            </select>
                            <select style="display:none" disabled="true" id="pm" class="form-control" name="book_date[time][]">
                                <?php for ($i= $config['bh_pm_start']; $i <= $config['bh_pm_end']; $i++) { 
                                    echo "<option value=".$i.">".$i."</option>";
                                } ?>
                            </select>
                            <span class="input-group-addon">时</span>
                            <select id="am_ti" class="form-control" name="book_date[time][]">
                                <?php 

                                for ($i=0; $i < 60/$config['am_time_interval']; $i++) { 
                                    echo "<option value=".$i*$config['am_time_interval'].">".$i*$config['am_time_interval']."</option>";
                                } 
                                ?>
                            </select>
                            <select style="display:none" disabled="true" id="pm_ti" class="form-control" name="book_date[time][]">
                                <?php 
                                for ($i=0; $i < 60/$config['pm_time_interval']; $i++) { 
                                    echo "<option value=".$i*$config['pm_time_interval'].">".$i*$config['pm_time_interval']."</option>";
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
        book_datepicker.datepicker('update', ev.date);
        load_book_table(book_datepicker.val());
    });

    var book_datepicker = $(".date-picker").datepicker({
        language: 'zh-CN',
        format: "yyyy-m-d",
        autoclose: true,
        startDate: new Date()
    });

    load_book_table(book_datepicker.val());

    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            setTimeout('$(".Validform_msg").hide()', 1500);
            if(response.status == "y"){
                form.resetForm();
                $('#am').show().removeAttr('disabled');
                $('#am_ti').show().removeAttr('disabled');
                $('#pm').hide().attr('disabled', 'true');
                $('#pm_ti').hide().attr('disabled', 'true');
                load_book_table(book_datepicker.val());
                date_picker.datepicker('update', book_datepicker.val());
            }
        }
    });

    $('#amorpm').change(function(event) {
        var v = $(this).val();
        if(v == 1){
            $('#pm').show().removeAttr('disabled');
            $('#pm_ti').show().removeAttr('disabled');
            $('#am').hide().attr('disabled', 'true');
            $('#am_ti').hide().attr('disabled', 'true');
        }else{
            $('#am').show().removeAttr('disabled');
            $('#am_ti').show().removeAttr('disabled');
            $('#pm').hide().attr('disabled', 'true');
            $('#pm_ti').hide().attr('disabled', 'true');
        }
    });
})
function load_book_table (date) {
    console.log(date);
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