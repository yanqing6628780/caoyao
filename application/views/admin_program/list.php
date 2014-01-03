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
                    <div class="navbar-form pull-left">
                        <h5>议程列表</h5>
                        <div class="buttons">
                            <a href="<?=site_url($controller_url."add")?>" class="btn btn-small" target="right"><i class="icon-plus"></i> 添加议程</a>
                        </div>   
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <table class='table table-bordered data-table'>
                        <thead>
                            <tr>
                                <th>议程名</th>
                                <th>开始时间</th>
                                <th>结束时间</th>
                                <th>所属大会</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $key => $row):?>
                            <tr>
                                <td><?=$row['title']?></td>
                                <td><?=$row['startTime']?></td>
                                <td><?=$row['endTime']?></td>
                                <td><?=$row['party_title']?></td>
                                <td>
                                    <a href="<?=site_url($controller_url."edit/".$row['id'])?>" traget="right" class="btn btn-primary"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                    <button class="btn btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
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
