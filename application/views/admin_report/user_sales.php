<div class="row">
    <div class="col-md-12">
        <form id="chart_form" role="form" class="form-inline">
            <div class="form-group col-lg-2">
                <label for="exampleInputEmail2" class="sr-only">订货会</label>
                <select name="exchange_id" id="exchange" class="form-control">
                    <?php foreach ($exchanges as $key => $item) {?> 
                        <option value="<?=$item['id']?>"><?=$item['exchange_fair_name']?></option>
                    <?php }?>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="exampleInputEmail2" class="sr-only">门店</label>
                <select name="user_id" id="user_id" class="form-control">
                    <?php foreach ($branches as $key => $item) {?> 
                    <optgroup label="<?=$item['branch_name'] ?>">
                        <?php foreach ($item['users'] as $key => $value): ?>
                        <option value="<?=$value['user_id']?>"><?=$value['name']?></option>
                        <?php endforeach ?>
                    </optgroup>
                    <?php }?>
                </select>
            </div>
            <button id="chart_query" class="btn btn-default" type="button">查询</button>
         </form>      
    </div>  
</div>
<div class="row">
    <div class="col-md-6">
        <div id="container"></div>      
    </div>	
    <div class="col-md-6">
		<div id="list"></div>		
	</div>
</div>

<script type="text/javascript">
$(function () {
    $('#exchange').select2();
    $('#user_id').select2();

    var chart;
    var colors = Highcharts.getOptions().colors;
    var options = {
        chart: {
            renderTo: 'container',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '订单分类销售额'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b><br/>',
            valuePrefix: '￥',
            valueDecimals: 2,
            shared: true
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.2f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: '销售额',
            data: []
        }]
    }

    function resquest_data () {
        var form_data = $('#chart_form').serialize();
        $.getJSON('<?=site_url("admin/report/get_user_sales_data") ?>', form_data, function(json){
            options.series[0].data = json.data;
            chart = new Highcharts.Chart(options);
        });
        $.ajax({
            url: '<?=site_url("admin/report/get_user_sales_list") ?>',
            type: 'POST',
            dataType: 'html',
            data: form_data,
            success: function (resp) {
                $('#list').html(resp);
            }
        });
    }

    $('#chart_query').click(function(event) {
        resquest_data();
    });

});
</script>