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
                            <a href="<?=site_url($controller_url."add")?>" class="btn btn-small" target="right"><i class="icon-plus"></i> 添加会议</a>
                        </div>   
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <table class='table table-bordered data-table'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>大会名称</th>
                                <th>开始时间</th>
                                <th>结束时间</th>
                                <th>大会地点</th>
                                <th>大会负责人</th>
                                <th>是否使用讨论模块</th>
                                <th>是否使用投票模块</th>
                                <th>是否使用抽奖模块</th>
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
                                    <?php if(checkPermission2('party_edit')):?>                                    
                                    <a href="<?=site_url($controller_url."edit/".$row['id'])?>" traget="right" class="btn btn-primary"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                    <button class="btn btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
                                    <?php endif;?>
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
