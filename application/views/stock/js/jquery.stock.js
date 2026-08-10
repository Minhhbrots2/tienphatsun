$_document.ready(() => {
	$Core.stock.init();
	
});
$Core.stock = {
	init: () => {
		$Core.stock.load_stock({});
	}, select_block: (_this, e) => {
		var project_id = $(_this).val(),
			toId = $(_this).attr('toId');
		$.post(PCMS_URL+'/index.php?mod=tool&act=load_block', {
			'project_id' : project_id,
			'stock_type' : _BLOCK_TYPE_HIGHLEVEL_SALE
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html).multiselect('rebuild');
			$('#'+'slb_Building_Id').empty().multiselect('rebuild');
			$Core.stock.do_search(_this, e);
		});
	}, select_building: (_this, e) => {
		var toId = $(_this).attr('toId');
		$.post(PCMS_URL+'/index.php?mod=tool&act=load_building', {
			'list_block_ids' : $(_this).val()
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html);
			$('#'+toId).multiselect('rebuild');
			$Core.stock.do_search(_this, e);
		});
	}, clear_search: (_this, e) => {
		e.preventDefault();
		var _group = $(_this).closest('.btn-group'),
			_dropdown = $('.dropdown-menu', _group),
			_text = $('.multiselect-selected-text', _group).attr('text');
		$('input[name=price_min]').val(0*1000000000);
		$('input[name=price_max]').val(50*1000000000);
		$('.dropdown-toggle', _group).dropdown('toggle');
		$('.multiselect-selected-text', _group).text(_text);
		$Core.stock.do_search(_this, e);
		return false;
	}, start_search: (_this, e) => {
		e.preventDefault();
		var _group = $(_this).closest('.btn-group'),
			_dropdown = $('.dropdown-menu', _group),
			_text = $('.multiselect-selected-text', _group).attr('text');
		var _min_price = $('input[name=price_min]').val(),
			_max_price = $('input[name=price_max]').val(),
			_min = $Core.util.toNumber(_min_price)/1000000000,
			_max = $Core.util.toNumber(_max_price)/1000000000;
		if(parseInt(_min) == 0 && parseInt(_max) == 50){
			$('.multiselect-selected-text', _group).text(_text);
		} else {
			$('.multiselect-selected-text', _group).text(`${_min}-${_max} tỷ`);
		}
		$('.dropdown-toggle', _group).dropdown('toggle');
		$Core.stock.do_search(_this, e);
		return false;
	}, do_search: (_this, e) => {
		e.preventDefault();
		$Core.stock.load_stock({});
		return false;
	}, load_stock: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var name = $(_elem).data('field');
				$_adata[name] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=load_stock`, $_adata, (respJson) => {
			$Core.util.toggleIndicatior(0);
			$('.holder_stock').html(respJson.html);
			
			setTimeout(function(){
				$Core.stock.updateStickyLeft();
			},500);
		}, 'json');
	}, updateStickyLeft: (numSticky = 2) => {
		if($(".table-stock").length > 0) {
			$(".table-stock").each(function(index,elm){
				const $firstHeadRow = $("thead tr:nth-child(2)",$(elm));
				if (!$firstHeadRow.length) return;
				// lấy width thực tế của các cột sticky
				let colWidths = [];
				$firstHeadRow.children().each(function(i,elm) {
					if (i < numSticky) {
						colWidths[i] = $(elm).outerWidth();
					}
				});
				// áp left cho mọi hàng
				let firt_width = 0;
				$(elm).find("tr",$(elm)).each(function(i_tr,elm_tr) {
					let left = 0;
					if($(elm_tr).hasClass("rowspan")) {	
						 firt_width = 0;
					}
					$(this).children().each(function(i,elm_td) {
						if (i < numSticky) {
							$(elm_td).addClass("sticky-col").css("left", (left+firt_width) + "px");							
							if($(elm_tr).hasClass("rowspan")) {	
								firt_width += colWidths[i];
							}
							left += (colWidths[i] + firt_width);
						}
					});
				});
			});
		}
	}
}