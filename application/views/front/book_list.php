<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="text-danger"><?php echo $doctor?> <small><?php echo $cn_book_date?> 预约时间表</small></h4>  
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class='table table-striped table-bordered table-hover'>
                <thead>
                    <tr>
                        <th>预约日期</th>
                        <th>预约人</th>
                        <th>预约电话</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($result as $key => $row):?>
                    <tr>
                        <td><?=$row['book_date']?></td>
                        <td <?=$row['name'] ? '': 'class="text-danger"'?> ><?=$row['name'] ? str_pad(substr_ext($row['name'], 0, 1), 5, '*', STR_PAD_RIGHT) : '无预约'?></td>
                        <td <?=$row['name'] ? '': 'class="text-danger"'?> ><?=$row['phone'] ? str_pad(substr($row['phone'], -4), 11, '*', STR_PAD_LEFT) : '无预约';?></td>
                        <td>
                            <?php if(!$row['id']): ?>
                            <button data-toggle="modal" data-target="#myModal" onclick="book_dialog('<?=$row['book_date']?>')" class="btn btn-success" type="button">预约</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>