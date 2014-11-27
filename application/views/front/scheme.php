
<div class="row">
	<div class="col-md-6">
		<div id="amount"></div>		
	</div>	
	<div class="col-md-6">
		<div id="order_class"></div>		
	</div>
</div>
<script type="text/javascript">
$(function () {

	Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
		return {
			radialGradient: {
				cx: 0.5,
				cy: 0.3,
				r: 0.7
			},
			stops: [
				[0, color],
				[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken 
			] 
		}; 
	});

	var pieData = [];
	<?php foreach ($RSTR['sc_limits'] as $key => $value):?>
	pieData.push([
		"<?=$value['small_class_name']?>",<?=$value['percentage']?>
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
			data: pieData
		}]
	});

	var colors = Highcharts.getOptions().colors;
	var categories = ['订单金额', '方案要求金额'];
	var data = [{y: <?=$row['total']?>,color: colors[0]}, {y: <?=$RSTR['amount']?>,color: colors[2]}];
	$('#amount').highcharts({
		chart: {
			type: 'bar'
		},
		title: {
			text: '订货方案金额'
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
		legend: {enabled:false},
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