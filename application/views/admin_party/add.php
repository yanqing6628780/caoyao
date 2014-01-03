<script src="<?php echo base_url()?>js/validform_v5.3.2.js"></script>
<script src="<?php echo base_url()?>js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
$(function () {
    $(".datepicker").datepicker()
    var form = $("#addForm").Validform({
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){            
                if(confirm('是否继续添加')){
                    location.reload(true);
                }else{
                    window.location = "<?php echo site_url($controller_url)?>"
                }
            }
        }
    });
})
</script>

<body class="bg_white">
<div class="container-fluid">
    <div class='row-fluid'>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-content nopadding">
                    <div class="widget-title">
                        <span class="icon">
                            <i class="icon-align-justify"></i>                                  
                        </span>
                        <h5>添加会议</h5>
                    </div>
                    <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
                        <div class="control-group">
                            <label class="control-label">大会名称</label>
                            <div class="controls">
                                <input type='text' name="title" value='' datatype="*" sucmsg="" nullmsg="请输入名称！"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">大会简介</label>
                            <div class="controls">
                                <input type='text' name='content' value='' />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">开始时间</label>
                            <div class="controls">
                                <input type='text' name='startDate' class="datepicker" data-date-format="yyyy-mm-dd" datatype="*"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">结束时间</label>
                            <div class="controls">
                                <input type='text' name='endDate' value='' class="datepicker" data-date-format="yyyy-mm-dd" datatype="*"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">大会地点</label>
                            <div class="controls">
                                <input type='text' name='site' value=''/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">大会负责人</label>
                            <div class="controls">
                                <select name="user_id">
                                    <?php foreach ($users as $key => $row) {?>                                        
                                        <option value="<?=$row['id']?>"><?=$row['cnname'] ? $row['cnname'] : $row['username'] ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">是否使用讨论模块</label>
                            <div class="controls">
                                <input type='checkbox' name='isDiscussion' value='1'/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">是否使用投票模块</label>
                            <div class="controls">
                                <input type='checkbox' name='isVote' value='1' />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">是否使用抽奖模块</label>
                            <div class="controls">
                                <input type='checkbox' name='isLottery' value='1' />
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type='submit' name="save" class="btn btn-inverse btn-large" value='保存'/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>