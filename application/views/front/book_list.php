<table class='table table-striped table-bordered table-hover table-responsive'>
    <thead>
        <tr>
            <th>预约日期</th>
            <th>预约人</th>
            <th>预约电话</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($result as $key => $row):?>
        <tr>
            <td><?=$row['book_date']?></td>
            <td><?=$row['name']?></td>
            <td><?=str_pad(substr($row['phone'], -4), 11, '*', STR_PAD_LEFT);?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>