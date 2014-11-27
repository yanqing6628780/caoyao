<table class="table table-striped" >
	<thead>
		<tr>
			<th>图片</th>
			<th>宝贝名称</th>
			<th>类别</th>
			<th>款式</th>
			<th>单价</th>
			<th>数量</th>
			<th>小计</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($order_products as $key => $value): ?>
		<tr>
			<td><img width="100" src="<?=$value['info']['picture'] ? $value['info']['picture'] : 'http://placehold.it/100x100/999999'?>" alt="..."></td>
			<td><?=$value['info']['product_name']; ?></td>
			<td><?=$value['info']['small_class_name']; ?></td>
			<td><?=$value['style_num']; ?></td>
			<td>￥<?=formatAmount($value['info']['unit_price']); ?>元</td>
			<td><?=$value['sum_qty']; ?></td>
			<td>￥<?=formatAmount($value['info']['unit_price']*$value['sum_qty']); ?>元</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>