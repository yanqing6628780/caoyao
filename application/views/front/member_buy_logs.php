<table class="table">
    <thead>
        <tr>
            <th>序号</th>
            <th>行业</th>
            <th>信息</th>
            <th>购买时间</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($buy_logs): ?>
        <?php foreach ($buy_logs as $key => $value): ?>            
        <tr>
            <td><?=$key+1?></td>
            <td><?=$value->category?></td>
            <td><?=$value->info->company?></td>
            <td><?=$value->buy_time?></td>
        </tr>
        <?php endforeach ?>
        <?php else: ?>
        <tr>
            <td colspan="100">没有购买记录</td>
        </tr>
        <?php endif ?>
    </tbody>
</table>

