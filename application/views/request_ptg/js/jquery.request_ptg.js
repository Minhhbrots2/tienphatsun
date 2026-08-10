$(function(){
	$Core.report._autoload();
});
$Core.report = {
	_autoload: function(){
		if($('.ajax:not(.loaded)').length){
			$('.ajax:not(.loaded)').each((_i, _elem) => {
				var url = $(_elem).data('url'),
					$_adata = $(_elem).data('options') || {};
				if($Core.util.isEmpty(url)) return false;
				$.post(url, $_adata, function(respJson){
					$(_elem).addClass('loaded').html(respJson.html);
					if($(_elem).hasClass('load_sfs_report')){
						if($(".table-sort").length){
							$(".table-sort").tableSortable({
								cmp:(a,b) => $Core.util.toNumber(a) < $Core.util.toNumber(b) ? -1 : 1
							});
							setTimeout(() => {
								$('.js__th-sort-clickable')
									.trigger('click')
									.trigger('click');
							}, 1000);
						}
					}
					if(typeof(respJson.draw_chart) !== 'undefined' && respJson.draw_chart == 1){
						if(typeof(respJson.multi_chart) !== 'undefined' && respJson.multi_chart==1){
							$Core.chart.canvas_multi(respJson.uid,respJson.barChartData);
						} else {
							$Core.chart.canvas(respJson.uid,respJson.barChartData);
						}
					}
					if(respJson.callback){
						eval(respJson.callback);
					}
				},'json');
			});
		}
	},
}