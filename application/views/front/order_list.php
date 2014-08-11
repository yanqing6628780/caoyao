<table class="table table-striped" >
	<thead>
		<tr>
			<th>#</th>
			<th>订单编号</th>
			<th>总金额</th>
			<th>创建时间</th>
			<th>修改时间</th>
			<th>订单状态</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($result as $key => $row):?>
		<tr>
			<td><?=$key+1?></td>
			<td><?=$row['order_number']; ?></td>
			<td>￥<?=formatAmount($row['total']); ?>元</td>
			<td><?=$row['create_time']; ?></td>
			<td><?=$row['modify_time']; ?></td>
			<td><?=$row['is_pass'] ? '成功下单' : '未提交'; ?></td>
			<td><a data-trigger="ajax" href="<?=site_url('orders/id/'.$row['id'].'/'.$row['order_number'])?>" data-target="#main" class="btn btn-info" type="button">查看</a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<script type="text/javascript" src="<?=site_url('js/spinner.min.js')?>"></script>
<script type="text/javascript">
$(function () {

})
</script>