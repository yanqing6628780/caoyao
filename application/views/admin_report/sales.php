<div class="row">
	<div class="col-md-12">
		<div id="container"></div>		
	</div>	
</div>

<script type="text/javascript">
$(function () {
    var colors = Highcharts.getOptions().colors,
    	name = '销售额',
        categories = [],
        data = [];

    function setChart(name, categories, data, color) {
		chart.xAxis[0].setCategories(categories, false);
		chart.series[0].remove(false);
		chart.addSeries({
			name: name,
			data: data,
			color: colors[parseInt(10*Math.random())]
		}, false);
		chart.redraw();
    }

    var	chart = $('#container').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: '销售数据'
            },
            subtitle: {
                text: '点击查看下属门店销售额. 再次点击返回上层.'
            },
            xAxis: {
                categories: []
            },
            yAxis: {
                title: {
                    text: '销售额'
                }
            },
            legend: { enabled: false },
            plotOptions: {
                bar: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
                                var drilldown = this.drilldown;
                                if (drilldown) { // drill down
                                    setChart(drilldown.name, drilldown.categories, drilldown.data);
                                } else { // restore
                                    setChart(name, categories, data);
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        color: colors[0],
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return '￥' + this.y ;
                        }
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    var point = this.point,
                        s = this.x +':<b>￥'+ this.y +'</b><br>';
                    if (point.drilldown) {
                        s += '点击查看 '+ point.category +' 下的门店';
                    } else {
                        s += '点击返回';
                    }
                    return s;
                }
            },
            series: [{
                name: name,
                data: []
            }],
            exporting: {
                enabled: false
            }
        })
        .highcharts(); // return chart

		$.getJSON('<?=site_url("admin/report/get_sales_data") ?>', function(json){
			setChart(name, json.categories,json.data)
			data = json.data;
			categories = json.categories;
		});

});
</script>