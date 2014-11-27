<div class="row">
	<div class="col-md-6">
		<div id="billboard"></div>		
	</div>	
</div>
<script type="text/javascript">
$(function () {
	var colors = Highcharts.getOptions().colors;
	var categories = [];
	var data = [];
	<?php foreach ($billboard as $key => $value):?>
	data.push({
		y: <?=$value['sum_qty']?>,
		color: colors[<?=$key?>]
	});
	categories.push("<?=$value['info']['product_name']?>");
	<?php endforeach;?>	

	$('#billboard').highcharts({
		chart: {
			type: 'bar'
		},
		title: {
			text: '热销排行榜'
		},
		xAxis: { categories: categories }, 
		yAxis: { title: { text: '销量' } },
		tooltip: {
			formatter: function() {
				var point = this.point,
					s = this.x + ':<b>' + this.y + '件</b><br>';
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
			name: '热销排行榜', 
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