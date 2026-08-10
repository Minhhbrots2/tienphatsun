<div id="chart{$uid}" style="min-height:350px" class="chartContainer"></div>
<script>
	var uid = `{$uid}`;
	var barChartData = `{$barChartData}`;
</script>
{literal}
<script>
	$(function(){
		console.log(barChartData);
		$Core.chart.canvas('chart'+uid,barChartData);
	});
</script>
{/literal}