$(document).ready(function(){});
$Core.log = {
	view_history_connect_moc : (_this,e) => {
		e.preventDefault();
		var member_id = $(_this).attr("member_id"),
			$_adata = {"member_id":member_id};
		$Core.util.toggleIndicatior(0);
		$.post('/index.php?mod='+MOD+'&act=view_history_connect_moc', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			var _w = $(window).width();
			$Core.popup.openfull(_w*2/3,respJson.html,respJson.uid);
		},'json');
	},
	load_logs_search: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_search_logs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_logs').html(respJson.html);
			$('.total_record').html(respJson.total_record);
			$('#pager_search_logs').replaceWith('<div id="pager_search_logs"></div>');
			if(parseInt(respJson.total_page) > 1){
				var _displayedPages = (deviceType=='phone') ? 2 : 5;
				$('#pager_search_logs').pagination({
					listStyle:"pagination justify-content-center",
					currentPage: respJson.current_page, 
					itemsOnPage: respJson.per_page,
					items: respJson.total_record,
					displayedPages: _displayedPages,
					cssStyle: 'light-theme',
					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
					onPageClick : function(pageNumber){
						$Core.log.load_logs($.extend(options, {'page':pageNumber}));
					}
				});
			}
		},'json');
	}, do_search_key: (_this, e) => { 
		e.preventDefault();
		$Core.log.load_logs_search({});
		return false;
	}, load_logs: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_sale_logs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_logs').html(respJson.html);
			$('.total_record').html(new Intl.NumberFormat().format(respJson.total_record));
			$('#pager_sale_logs').replaceWith('<div id="pager_sale_logs"></div>');
			if(parseInt(respJson.total_page) > 1){
				var _displayedPages = (deviceType=='phone') ? 2 : 5;
				$('#pager_sale_logs').addClass('mt-2').pagination({
					listStyle:"pagination justify-content-center",
					currentPage: respJson.current_page, 
					itemsOnPage: respJson.per_page,
					items: respJson.total_record,
					displayedPages: _displayedPages,
					cssStyle: 'light-theme',
					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
					onPageClick : function(pageNumber){
						$Core.log.load_logs($.extend(options, {'page':pageNumber}));
					}
				});
			}
		},'json');
	}, do_search: (_this, e) => {
		e.preventDefault();
		$Core.log.load_logs({});
		return false;
	}, load_login_logs: (options) => {
		var $_adata = {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_login_logs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_logs').html(respJson.html);
			$('.total_record').html(respJson.total_record);
		},'json');
	}, do_login_search: (_this, e) => {
		e.preventDefault();
		$Core.log.load_login_logs({});
		return false;
	}, open_report: (_this, e) => {
		e.preventDefault();
		$.post('/index.php?mod='+MOD+'&act=open_report', {}, function(respJson){
			var _w = $(window).width();
			$Core.util.toggleIndicatior(0);
			$Core.popup.openfull(_w*2/3,respJson.html,respJson.uid);
			$Core.log.load_table_report(respJson.uid, {'search_type':'all'});
		},'json');
		return false;
	}, do_reload: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			form = $(_this).closest('form'),
			search_type = $('input[name=search_type]:checked').val();
		$Core.log.load_table_report(uid, {'search_type':search_type});
		return false;
	}, load_table_report: (uid, options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_table_report', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#tbody_'+uid).html(respJson.html);
		},'json');
	},
}