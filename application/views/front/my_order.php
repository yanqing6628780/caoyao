<div class="row">
	<div class="col-md-12">
		<div id="amount"></div>		
	</div>	
</div>
<div class="row">
	<div class="col-md-6">
		<div id="RSTR-pie"></div>		
	</div>	
	<div class="col-md-6">
		<div id="order_class"></div>		
	</div>
</div>

<table class="table table-bordered" >
	<thead>
		<tr>
			<th>订单编号</th>
			<th>总金额</th>
			<th>创建时间</th>
			<th>修改时间</th>
			<th>订单状态</th>
		</tr>
	</thead>
	<tbody>
		<?php if($row): ?>
		<tr>
			<td><?=$row['order_number']; ?></td>
			<td>￥<?=formatAmount($total); ?>元</td>
			<td><?=$row['create_time']; ?></td>
			<td><?=$row['modify_time']; ?></td>
			<td><?=$row['is_pass'] ? '成功下单' : '未提交'; ?></td>
		</tr>
		<?php else: ?>
			<tr>
				<td colspan="100">没有订单数据</td>
			</tr>
		<?php endif?>
	</tbody>
</table>
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
			<th>操作</th>
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
			<td><a data-trigger="ajax" href="<?=site_url('orders/id/'.$row['id'].'/'.$row['order_number'].'/?product_id='.$value['product_id'])?>" data-target="#main" class="btn btn-info" type="button">查看</a></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<script type="text/javascript" src="<?=site_url('js/spinner.min.js')?>"></script>
<script type="text/javascript">
$(function () {
	var pieData = [];
	<?php foreach ($RSTR['sc_limits'] as $key => $value):?>
	pieData.push([
		"<?=$value['small_class_name']?>",<?=$value['percentage']?>
	]);
	<?php endforeach;?>

	var classPieData = [];
	<?php foreach ($order_small_class_sum as $key => $value):?>
	classPieData.push([
		"<?=$value['small_class_name']?>",<?=$value['sum_qty']?>
	]);
	<?php endforeach;?>
	

	var chartOptions = {
		plotBackgroundColor: null,
		plotBorderWidth: null,
		plotShadow: false
	}
	$('#order_class').highcharts({
		chart: chartOptions,
		title: {
			text: '订单各类别所占比例'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.y}件</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					color: '#000000',
					connectorColor: '#000000',
					format: '<b>{point.name}</b>: {point.percentage:.1f} %'
				}
			}
		},
		series: [{
			type: 'pie',
			name: '数量',
			data: classPieData
		}]
	});
	$('#RSTR-pie').highcharts({
		chart: chartOptions,
		title: {
			text: '方案要求类别所占比例'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.y:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					color: '#000000',
					connectorColor: '#000000',
					format: '<b>{point.name}</b>: {point.y:.1f} %'
				}
			}
		},
		series: [{
			type: 'pie',
			name: '占比',
			data: pieData
		}]
	});

	var colors = Highcharts.getOptions().colors;
	var categories = ['订单金额', '方案要求金额'];
	var data = [{y: <?=$total?>,color: colors[0]}, {y: <?=$RSTR['amount']?>,color: colors[1]}];
	$('#amount').highcharts({
		chart: {
			type: 'bar'
		},
		title: {
			text: '金额'
		},
		xAxis: { categories: categories }, 
		yAxis: { title: { text: '金额' } },
		tooltip: {
			formatter: function() {
				var point = this.point,
					s = this.x + ':<b>￥' + this.y + '</b><br>';
				return s;
			}
		},
		plotOptions: {
			column: {
				cursor: 'pointer',
				point: {
					events: {
						click: function() {
							var drilldown = this.drilldown;
							if (drilldown) { 
								setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color); // drill down 
							} else { 
								setChart(name, categories, data); // restore 
							} 
						} 
					} 
				}, 
				dataLabels: {
					enabled: true, 
					color: colors[0], 
					style: { fontWeight: 'bold' }, 
					formatter: function() { return this.y +'%'; }
				} 
			}
		},
		series: [{
			name: '金额', 
			data: data, 
			color: 'white',
			dataLabels: {
				enabled: true,
				color: '#FFFFFF',
				align: 'right',
				style: {
					fontSize: '13px',
					fontFamily: 'Verdana, sans-serif',
					textShadow: '0 0 3px black'
				}
			}
		}]
	});
});
</script>