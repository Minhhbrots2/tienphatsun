$(function(){
	if(ACT == 'default') {
		$Core.stock_hug.list({});
	}
	$('.dropdown-stock-search').on('show.bs.dropdown', function () {
		alert("x");
		setTimeout(() => {
			$('.search_keyword_field').select().focus();
		}, 500);
	});
	$_document.on('keyup','.search_keyword_field', function(ev){
		var _keyCode = ev.keyCode || ev.which;
		if(_keyCode==13){
			$Core.stock_hug.list({});
			ev.preventDefault();
			return false;
		}
	});
});
$Core.stock_hug = {
	open : function(_this, e){
		e.preventDefault();
		var stock_hug_id = $(_this).attr('stock_hug_id'),
			$_adata = {'stock_hug_id':stock_hug_id};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			if(respJson.callback) eval(respJson.callback);
		},'json');
		return false;
	},
	crawl : function(_this, e){
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn Crawl dữ liệu từ File Google Sheet?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=crawl', {}, function(respJson){
				$Core.util.toggleIndicatior(0);
				window.location.reload(true);
			},'json');
		});
		return false;
	},
	delete: function(_this, e){
		e.preventDefault();
		var stock_hug_id = $(_this).attr('stock_hug_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xoá?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete', {
				'stock_hug_id' : stock_hug_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				var page = $('input[name=current_page]').val(),
					per_page = $('input[name=per_page]').val();
				$Core.stock_hug.list({'page':page, 'per_page':per_page});
			});
		});
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0, 
			_form = $(_this).closest('form'),
			_holderG = $(_this).attr('holderG'),
			stock_hug_id  = $(_this).attr('stock_hug_id'),
			$_adata = {'stock_hug_id':stock_hug_id};
		if($('select.required,input.required', _form).length){
			$('select.required,input.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					if($(_elem).hasClass('selectized')){
						var $_select = $(_elem).selectize(),
							$_selectize = $_select[0].selectize;
						$_selectize.refreshOptions();
					} else {
						$(_elem).focus();
					}
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save",
				data: $_adata,
				dataType:'html',
				success: function(html){
					$Core.util.toggleIndicatior(0);		
					if(html.indexOf('_success') >= 0){
						if(_holderG=='continue'){
							$Core.alert.success('Thêm mới thành công');
							$('.btn-close', _form).trigger('click');
							$('.create_quick_stock_hug').trigger('click');
						} else {
							var page = $('input[name=current_page]').val(),
								per_page = $('input[name=per_page]').val();
							$Core.stock_hug.list({'page':page, 'per_page':per_page});
							$('.btn-close', _form).trigger('click');
						}
					} else if(html.indexOf('_error') >= 0){
						swal( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại","error");
					} else if(html.indexOf('_duplicated') >= 0){
						swal( "Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại","error");
					} 
				}
			});
		}
		return false;
	},
	hanlde_stock_code: function(_this, e){
		var stock_code = $(_this).val();
		if(!$Core.util.isEmpty(stock_code)){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=get_bedroom_type', {
				'stock_code' : stock_code
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$('select[name=bedroom_type]').val(respJson.bedroom_id);
			}, 'json');
		}
	},
	do_search: function(_this, e){
		var holderG = $(_this).getAttr('holderG', "_search");
		if(holderG == "_reset"){
			$('#'+'frmIssue')[0].reset();
		}
		e.preventDefault();
		$Core.stock_hug.list({});
		return false;
	},
	toggle_search: function(_this, e){
		e.preventDefault();
		var gId = $(_this).attr('gId');
		$('#'+gId).dropdown('toggle');
		$Core.stock_hug.list({});
		return false;
	},
	set_status : function(_this, e){
		e.preventDefault();
		var _field = $(_this).attr('field'),
			_value = $(_this).attr(_field);
		$('select[name='+_field+']').val(_value).trigger('change');
		return false;
	},
	list: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=list', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holderStockHug').html(respJson.html);
			$('.briefStockHug').html(respJson.html_brief);
			$('.total-record').text(respJson.total_record);
			$('input[name=per_page]').val(respJson.per_page);
			$('input[name=current_page]').val(respJson.current_page);
			if(parseInt(respJson.total_page)){
				$('#'+'tableStockHug').freezeTable('update');
				$('#pagerStockHug').pagination({
					total:respJson.total_record,
					pageSize:respJson.per_page,
					pageNumber : respJson.current_page,
					onRefresh : function(pageNumber,pageSize){
						$Core.stock_hug.list($.extend(options, {'page':pageNumber,'per_page':pageSize}));
					}, onSelectPage : function(pageNumber,pageSize){
						$Core.stock_hug.list($.extend(options, {'page':pageNumber,'per_page':pageSize}));
					}
				});
			}
		},'json');
	},
};