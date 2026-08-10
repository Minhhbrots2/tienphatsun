$Core.marketing = {
	_MOD : 'marketing',
	_autoload: () => {
		if($('.ajax:not(.loaded)').length){
			$('.ajax:not(.loaded)').each((_i, _elem) => {
				var url = $(_elem).data('url'),
					$_adata = $(_elem).data('options') || {};
				if($('.search_field').length){
					$('.search_field').each((_i, _elem) => {
						let _field = $(_elem).data('field');
						$_adata[_field] = $(_elem).val();
					});
				}
				$.ajax({
					url: url,
					type: 'POST',
					data : $_adata,
					dataType : 'json',
					async: false,
					success: function(respJson){
						let $html = respJson.html;
						$(_elem).addClass('loaded').html($html);
						if(respJson.drawchart == 1){
							if(typeof(respJson.multichart) != 'undefined' && respJson.multichart==1){
								$Core.chart.canvas_multi(respJson.uid,respJson.barChartData);
							} else {
								$Core.chart.canvas(respJson.uid,respJson.barChartData);
							}
						}
						if(respJson.callback){
							eval(respJson.callback);
						}
					}
				});
			});
		}
		$Core.marketing.load_summary({});
		$Core.marketing.load_budget_detail({});
	}, load_regis: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				let _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${$Core.marketing._MOD}&act=load_regis`, $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_regis').html(respJson.html);
		}, 'json');
	}, do_search : (_this, e) => {
		e.preventDefault();
		$Core.marketing.load_regis({});
		return false;
	}, open_regis : (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${$Core.marketing._MOD}&act=open_regis`, {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, add_line: (_this, e) => {
		e.preventDefault();
		var _total_rows = 0,
			_form = $(_this).closest('form');
		if($('.js__tr_marketing', _form).length){
			_total_rows = $('.js__tr_marketing', _form).length;
		}
		$Core.util.toggleIndicatior(1);
		$.post(`${PCMS_URL}/index.php?mod=${$Core.marketing._MOD}&act=add_line`, {
			'_total_rows' : _total_rows
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if($('tr.js__tr_marketing', _form).length){
				$('tr.js__tr_marketing:last', _form).after(respJson.html);
			} else {
				$('tr.js__tr-addline', _form).before(respJson.html);
			}
		}, 'json');
		return false;
	}, delete_line: (_this, e) => {
		e.preventDefault();
		var _total_rows = 0,
			_form = $(_this).closest('form');
		$Core.swal.confirm("Thông báo", "Bạn có chắc chắn muốn thực hiện thao tác này?", function(){
			if($('.js__tr_marketing', _form).length){
				_total_rows = $('.js__tr_marketing', _form).length;
			}
			$(_this).closest('tr.js__tr_marketing').remove();
			if(_total_rows == 1){
				$('.js__add-line', _form).trigger('click');
			}
		});
		return false;
	}, check: (_this, e) => {
		var _error = 0,
			_projects = [],
			_form = $(_this).closest('form');
		if($('tr.js__marketing-row',_form).length){
			$('tr.js__marketing-row',_form).each((_i, _elem) => {
				var project_id = $('.js__select-project', $(_elem)).val();
				if(parseInt(project_id, 10) > 0){
					if($.inArray(project_id, _projects) !== -1){
						$Core.swal.error("Thông báo", "Dự án này đã bị trùng !");
						$(_this).val(0);
						return false;
					} else {
						_projects.push(project_id);
					}
				}
			});
		}
	}, do_regis: (_this, e) => {
		e.preventDefault();
		let _error = 0,
			_form = $(_this).closest('form'),
			_action = $(_this).attr('action'),
			_has_project  = false;
		if ($('tr.js__tr_marketing', _form).length) {
			$('tr.js__tr_marketing', _form).each((_, _tr) => {
				let _has_budget = false;
				const project_id = parseInt($('.js__select-project', $(_tr)).val(), 10);
				$('.js__input-budget', $(_tr)).each((__, _input) => {
					if (!$Core.util.isEmptyZero($(_input).val())) {
						_has_budget = true;
						return false;
					}
				});
				if (!project_id && _has_budget) {
					_error = 1;
					$('.js__select-project', $(_tr)).focus();
					return false;
				}
				if (project_id) {
					_has_project = true;
					if (!_has_budget) {
						// _error = 1;
						$('.js__input-budget:first', $(_tr)).focus();
						return false;
					}
				}
			});
		}
		if (!_has_project) {
			_error = 1;
			$('.js__select-project:first', _form).focus();
			return false;
		}
		if(_error == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: `${PCMS_URL}/index.php?mod=${$Core.marketing._MOD}&act=do_regis`,
				data: {},
				dataType: "json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					const _action_name = (_action == '_add') ? "đăng ký" : "cập nhật";
					if(respJson.msg.indexOf('_success') >= 0){
						$('.btn-close', _form).trigger('click');
						$Core.swal.success('Thông báo', `Bạn đã ${_action_name} ngân sách marketing tháng ${respJson.month} thành công!`);
					} else {	
						$Core.swal.error('Thông báo', `Bạn đã ${_action_name} ngân sách marketing tháng ${respJson.month} không thành công!`);
					}
				}
			});
		}
		return false;
	}, do_change: (_this, e) => {
		let _field = $(_this).data('field');
		if(_field == 'date_type'){
			let _value = $(_this).val();
			if(_value == '_month'){
				let current_year = new Date().getFullYear(),
					current_month = $Core.util.plz(new Date().getMonth()+1);
				$('.js__date-field').replaceWith(`<input data-field="month" type="month" class="js__date-field form-control search_field" 
					value="${current_year}-${current_month}" onChange="$Core.marketing.do_change(this, event)" />`);
			} else if(_value == '_quarter'){
				let html_options = "", 
					current_quater = Math.floor(new Date().getMonth() / 3) + 1;
				for(let i = 1; i<=current_quater; i++){
					html_options+= `<option value="${i}">Quý ${i}</option>`;
				}
				$('.js__date-field').replaceWith(`<select class="js__date-field w-px-150 form-control search_field form-select" 
					data-field="quarter" onChange="$Core.marketing.do_change(this, event)">${html_options}</select>`);
			} else if(_value == '_year'){
				let html_options = "", 
					current_year = new Date().getFullYear();
				for(let i = 2026; i<=current_year; i++){
					html_options+= `<option value="${i}">Năm ${i}</option>`;
				}
				$('.js__date-field').replaceWith(`<select  class="js__date-field w-px-150 form-control search_field form-select" 
					data-field="year" onChange="$Core.marketing.do_change(this, event)">${html_options}</select>`);
			}
			setTimeout(() => {
				$('.ajax.loaded').removeClass('loaded');
				$Core.marketing._autoload();
			}, 500);
		} else {
			$('.ajax.loaded').removeClass('loaded');
			$Core.marketing._autoload();
		}
	}, open: (_this, e) => {
		e.preventDefault();
		return false;
	}, open_import: (_this, e) => {
		e.preventDefault();
		var tp = $(_this).attr('tp');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_import', {
			'tp' : tp
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, open_config : (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			tp = $(_this).attr('tp'),
			sheet_name = $('.sheet_name', _form).val(),
			spreadsheetId = $('.spreadsheetId', _form).val();
		if($Core.util.isEmpty(spreadsheetId) || $Core.util.isEmpty(sheet_name)){
			$Core.swal.error("Thông báo", "Tên File hoặc Tên sheet không được trống");
		} else {
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=open_config', {
				'tp' : tp,
				'by' : by,
				'spreadsheetId' : spreadsheetId,
				'sheet_name' : sheet_name,
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			},"json");
		}
		return false;
	}, save_config: (_this, e) => {
		e.preventDefault();
		var _uid = $(_this).attr("uid"),
			_form = $(_this).closest("form");
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL+'/index.php?mod='+MOD+'&act=save_config', 
			data:{'uid':_uid},
			dataType:'json',
			success: function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result) {
					alertify.success(respJson.msg);
					$(".btn-close", _form).trigger("click");
				}else{
					alertify.error(respJson.msg);
				}
			}
		});
		return false;
	}, crawl: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_uid = $(_this).attr('uid'),
			_form = $(_this).closest('form');
		if($('input.required_crawl', _form).length){
			$('input.required_crawl', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error = 1;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_error == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: `${PCMS_URL}/index.php?mod=${$Core.marketing._MOD}&act=crawl`,
				data: {'uid' : _uid},
				dataType: "json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					$('select[name=sheet_name]').html(respJson.html_worksheets);
				}
			});
		}
		return false;
	}, do_import: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_tp = $(_this).attr('tp'),
			_form = $(_this).closest('form');
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error = 1;
					if($(_elem).hasClass('selectize-control')){
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
		if(_error == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=do_import",
				data: {'tp' : _tp},
				dataType: "html",
				success: function(html){
					$Core.util.toggleIndicatior(0);
				}
			});
		}
		return false;
	}, load_budget_detail : (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				let _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_budget_detail', $_adata, function(respJson){
			$('.holder_tbl_budgets').html(respJson.html);
			if(parseInt(respJson.total_page) > 0){
				$_easyUI('.pager_tbl_budgets').pagination({
					total:respJson.total_record,
					pageSize:respJson.per_page,
					pageNumber : respJson.current_page,
					pageList: [10,20,30,50,100],
					onRefresh : function(pageNumber, pageSize){
						$Core.marketing.load_budget_detail($.extend(options,{'page':pageNumber,'per_page':pageSize}));
					}, onSelectPage : function(pageNumber, pageSize){
						$Core.marketing.load_budget_detail($.extend(options,{'page':pageNumber,'per_page':pageSize}));
					}
				});
			}
		}, 'json');
	}, load_summary : (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				let _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_summary', $_adata, function(respJson){
			$('.holder_summary').html(respJson.html);
		}, 'json');
	},
}