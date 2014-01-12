<body class="bg_white">
<div class='container-fluid'>
    <div class="row-fluid">
        <div class="span12">
            <div class='widget-box'>
                <div class="widget-title">
                    <div class="navbar-form pull-left">
                        <h5>抽奖列表</h5>
                        <div class="buttons">
                            <a href="<?=site_url($controller_url."add")?>" class="btn btn-small" target="right"><i class="icon-plus"></i> 添加抽奖</a>
                        </div>   
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <table class='table table-bordered data-table'>
                        <thead>
                            <tr>
                                <th>抽奖主题</th>
                                <th>奖项</th>
                                <th>所属大会</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $key => $row):?>
                            <tr>
                                <td><?=$row['title']?></td>
                                <td><?=isset($row['content_string']) ? $row['content_string'] : "无" ?></td>
                                <td><?=$row['party_title']?></td>
                                <td>
                                    <p>
                                        <a href="<?=site_url($controller_url."edit/".$row['id'])?>" traget="right" class="btn btn-primary"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                        <button class="btn btn-danger" onclick='del(<?=$row['id']?>, "<?=$row['code']?>")'><i class="icon-remove icon-white"></i> 删除</button>
                                    </p>
                                    <p>
                                        <a href="<?=site_url($controller_url."watchdog/".$row['id'])?>" traget="right" class="btn btn-warning"> <i class="icon-pencil icon-white"></i> 指定中奖人</a>
                                    </p>
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
<script type="text/javascript">
function del(id, code){
    common_del('<?=site_url($controller_url."del")?>', id, code);
}
</script>
</body>
</html>
