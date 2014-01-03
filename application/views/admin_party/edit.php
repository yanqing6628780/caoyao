<script src="<?php echo base_url()?>js/validform_v5.3.2.js"></script>
<script src="<?php echo base_url()?>js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
$(function () {
    $(".datepicker").datepicker()
    var form = $("#editForm").Validform({
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){            
                window.location = "<?php echo site_url($controller_url)?>"
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
                    <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
                        <div class="control-group">
                            <label class="control-label">大会名称</label>
                            <div class="controls">
                                <input type='text' name="title" value='<?=$row['title']?>' datatype="*" sucmsg="" nullmsg="请输入名称！"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">大会简介</label>
                            <div class="controls">
                                <input type='text' name='content' value='<?=$row['title']?>' />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">开始时间</label>
                            <div class="controls">
                                <input type='text' value='<?=$row['startDate']?>' name='startDate' class="datepicker input-small" data-date-format="yyyy-mm-dd" datatype="*" readonly/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">结束时间</label>
                            <div class="controls">
                                <input type='text' name='endDate' value='<?=$row['endDate']?>' class="datepicker input-small" data-date-format="yyyy-mm-dd" datatype="*" readonly/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">大会地点</label>
                            <div class="controls">
                                <input type='text' name='site' value='<?=$row['site']?>'/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">大会负责人</label>
                            <div class="controls">
                                <select name="user_id">
                                    <?php foreach ($users as $key => $user) {?>                                        
                                        <option value="<?=$user['id']?>" <?php option_select($row['user_id'],$user['id'])?>><?=$user['cnname'] ? $user['cnname'] : $user['username'] ?></option>
                                    <?php }?>user
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">是否使用讨论模块</label>
                            <div class="controls">
                                <input type='checkbox' name='isDiscussion' value='1' <?php radio_check($row['isDiscussion'],1)?>/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">是否使用投票模块</label>
                            <div class="controls">
                                <input type='checkbox' name='isVote' value='1' <?php radio_check($row['isVote'],1)?>/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">是否使用抽奖模块</label>
                            <div class="controls">
                                <input type='checkbox' name='isLottery' value='1' <?php radio_check($row['isLottery'],1)?>/>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type='submit' name="save" class="btn btn-inverse btn-large" value='保存'/>
                            <input type='hidden' name="id" value="<?=$row['id']?>"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>