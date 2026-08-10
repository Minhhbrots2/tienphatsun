$(function(){
	$Core.api_log.init();
});
$Core.api_log = {
	init: () => {
		if(act=='default'){
			$Core.api_log.load_log({'action':action});
		}
	},
	sync_data_stock: function (_this,e) {
		e.preventDefault();
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=sync_data_stock', {}, function(respJson){
			vietiso_loading(0);
			if(respJson.result) {
				$Core.alert.success(respJson.msg);	
				$Core.api_log.load_log({});
			}else{
				$Core.alert.error(respJson.msg);	
			}
		}, 'json');
		return false;
	},
	sync_data_sop: function (_this,e) {
		e.preventDefault();
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=sync_data_sop', {}, function(respJson){
			vietiso_loading(0);
			if(respJson.result) {
				$Core.alert.success(respJson.msg);	
				$Core.api_log.load_log({"action":respJson.action});
			}else{
				$Core.alert.error(respJson.msg);	
			}
		}, 'json');
		return false;
	},
	load_log: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i,_elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_log', $_adata, function(respJson){
			$('.holder_logs').html(respJson.html);
		}, 'json');
	}
}