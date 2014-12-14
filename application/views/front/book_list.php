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
                <td <?=$row['name'] ? '': 'class="text-danger"'?> ><?=$row['name'] ? $row['name'] : '无预约'?></td>
                <td <?=$row['name'] ? '': 'class="text-danger"'?> ><?=$row['phone'] ? str_pad(substr($row['phone'], -4), 11, '*', STR_PAD_LEFT) : '无预约';?></td>
                <td>
                    <?php if(!$row['id']): ?>
                    <button data-toggle="modal" data-target="#myModal" onclick="book('<?=$row['book_date']?>')" class="btn btn-success" type="button">预约</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>