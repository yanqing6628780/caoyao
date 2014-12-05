<table class='table table-striped table-bordered table-hover table-responsive'>
    <thead>
        <tr>
            <th>号</th>
            <th>预约人</th>
            <th>预约电话</th>
            <th>预约日期</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($result as $key => $row):?>
        <tr>
            <td><?=$key+1?></td>
            <td><?=$row['name']?></td>
            <td><?=str_pad(substr($row['phone'], -4), 11, '*', STR_PAD_LEFT);?></td>
            <td><?=$row['book_date']?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>