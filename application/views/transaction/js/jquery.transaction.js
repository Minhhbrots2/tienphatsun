$Core.transaction = $.extend($Core.global.transaction, {
	list: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field');
				if(_field=='transaction_type'){
					$_adata[_field] = $('.js__select-transaction_type:checked').val();
				} else {
					$_adata[_field] = $(_elem).val();
				}
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=list', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#'+'holder_transactions').html(respJson.html);
			$('.js__block-filter-type-list').html(respJson.html_filter_list);
		},'json');
	},
	do_search: function(_this, e){
		$Core.transaction.list();
	},
	view: function(_this, e){
		e.preventDefault();
		var transaction_id = $(_this).attr('transaction_id'),
			$_adata = {'transaction_id':transaction_id};
			
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=transaction&act=view', $_adata, function(respJson){
			$(_this).removeClass('clicked');
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			$Core.helper.load_list_notes(transaction_id, 'Transaction', {});
		}, 'json')
		return false;
	},
	print: function(_this, e){
		e.preventDefault();
		var transaction_id = $(_this).attr('transaction_id'),
			$_adata = {'transaction_id':transaction_id};
			
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=transaction&act=print', $_adata, function(respJson){
			$(_this).removeClass('clicked');
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	printThis : (_this, e) => {
		e.preventDefault();
		var _modal = $(_this).closest('.modal'),
			_uid = $(_this).attr('uid'),
			_options = {
				mode:'iframe',
				strict: undefined
			};
		$('.printArea_'+_uid).printArea(_options);
		$('.btn-close', _modal).trigger('click');
		return false;
	},
});