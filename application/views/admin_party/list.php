<script type="text/javascript">
function del(id){
    if(confirm('确认删除?'))
    {
        $.ajax({
            type: "POST",
            url: '<?=site_url($controller_url."del")?>',
            dataType: 'json',
            data: {id: id},
            success: function(respone){
                alert( '删除成功' );
                location.reload(true)
            }
        });
    }
}
</script>
<body class="bg_white">
<div class='container-fluid'>
    <div class="row-fluid">
        <div class="span12">
            <div class='widget-box'>
                <div class="widget-title">
                    <h5>会议列表</h5>
                    <div class="navbar-form pull-left">
                        <div class="buttons">
                            <?php if(checkPermission2('party_edit')):?>
                            <a href="<?=site_url($controller_url."add")?>" class="btn btn-small" target="right"><i class="icon-plus"></i> 添加会议</a>
                            <?php endif;?>
                        </div>   
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <table class='table table-bordered data-table'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>名称</th>
                                <th>开始时间</th>
                                <th>结束时间</th>
                                <th>地点</th>
                                <th width="50">负责人</th>
                                <th width="60">是否使用讨论模块</th>
                                <th width="60">是否使用投票模块</th>
                                <th width="60">是否使用抽奖模块</th>
                                <th>邀请码(右键另存为)</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $key => $row):?>
                            <tr class="tip-top" data-original-title="简介:<?=$row['content']?>">
                                <td><?=$row['id']?></td>
                                <td><?=$row['title']?></td>
                                <td><?=$row['startDate']?></td>
                                <td><?=$row['endDate']?></td>
                                <td><?=$row['site']?></td>
                                <td><?=$row['name']?></td>
                                <td><?=$row['isDiscussion'] ? "是" : "否" ?></td>
                                <td><?=$row['isVote'] ? "是" : "否" ?></td>
                                <td><?=$row['isLottery'] ? "是" : "否" ?></td>
                                <td>
                                    <img src="http://chart.apis.google.com/chart?chs=500x500&cht=qr&chld=L|0&chl=<?=$row['inviteCode']?>" alt="QR code" width="80" height="80"/>
                                </td>
                                <td>
                                    
                                    <?php if(checkPermission2('party_edit')):?>
                                    <p>                                
                                        <a class="btn btn-primary" href="<?=site_url($controller_url."edit/".$row['id'])?>" traget="right"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                        <a class="btn btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</a>                                    
                                    </p>
                                    <?php endif;?>
                                    <p> 
                                        <a class="btn btn-info" href="<?=site_url($controller_url."add_customer/".$row['id'])?>"><i class="icon-plus icon-white"></i> 会议参与人</a>
                                    </p>
                                    <div class="btn-group">
                                        <a href="<?=site_url("admin/program/?party=".$row['id'])?>" class="btn btn-small btn-inverse" target="right">查看议程</a>
                                        <?php if($row['isDiscussion']): ?>
                                        <a href="<?=site_url("admin/discussion/?party=".$row['id'])?>" class="btn btn-small btn-info" target="right">查看讨论主题</a>
                                        <?php endif;?>
                                        <?php if($row['isVote']): ?>
                                        <a href="<?=site_url("admin/vote/?party=".$row['id'])?>" class="btn btn-small btn-success" target="right">查看投票</a>
                                        <?php endif;?>
                                        <?php if($row['isLottery']): ?>
                                        <a href="<?=site_url("admin/lottery/?party=".$row['id'])?>" class="btn btn-small btn-danger" target="right">查看抽奖</a>
                                        <?php endif;?>
                                        <a href="<?=site_url("admin/sign/?party=".$row['id'])?>" class="btn btn-small" target="right">查看签到</a>
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
</div>
<div id="myModal" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3>Modal header</h3>
    </div>
    <div class="modal-body">
    </div>
</div>
</body>
</html>
