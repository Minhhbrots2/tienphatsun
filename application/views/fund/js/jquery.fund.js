$(function(){
	if(ACT == "opscost_dashboard" || ACT == "ops_cost" || SUB == "dashboard") {
		$Core.ops_cost.autoload();
	} else {
		$Core.fund.init();
		$Core.fund.autoload();
	}
	if(SUB == "branch" || SUB == "project" || SUB == "cash_flow") {
		$Core.fund.dashboard._autoload({});
	}
});
$_document.ajaxComplete(function() {
	$Core.fund.init();
});
$Core.fund = {
	init: function(){
		if($('input.autocomplete:not(.ui-autocomplete-input)').length){
			$('input.autocomplete:not(.ui-autocomplete-input)').each((_i, _elem) => {
				var _uid = $(_elem).attr('uid'),
					_url = $(_elem).data('source');
				$(_elem).autocomplete({
					source: _url,
					minLength: 1,
					select: function(event, ui){
						$(`.${_uid}_id`).val(ui.item.id);
					}
				});
			});
		}
	},
	autoload: function(){
		if($('.autoload:not(.loaded)').length){
			$('.autoload:not(.loaded)').each((_i, _elem) => {
				$(_elem).addClass('loaded');
				var _url = $(_elem).data('url'),
					_options = $(_elem).data('options') || {};
				$.post(_url, _options, function(html){
					$(_elem).html(html);
				});
			});
		}
	},
	open_setting: function(_this, e){
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_setting',{}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var _w = $(window).width();
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			$(`#${respJson.uid}`).find('.sortable').sortable({
				connectWith: "ul.list-group",
				placeholder: "ui-state-highlight",
				update: function(event, ui){
					if(!$Core.util.isEmpty(ui.sender)){
						var group = this.id,
							prop_id = $(ui.item).attr('id');
						// console.log(this.id);
						$Core.util.toggleIndicatior(1);
						$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=save_setting`, {
							'group' : group,
							'prop_id' : prop_id
						}, function(html){
							$Core.util.toggleIndicatior(0);
						});
					}
				}
			}).disableSelection();
		}, 'json');
		return false;
	},
	list: function(options, hide_loading=true){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if(typeof(field) !== 'undefined'){
					$_adata[field] = $(_elem).val();
				}
			});
		}
		var gr = $('.js__fund-filter-list:checked').val(),
			bank_account_id = $('.obank.current').attr('bank_account_id');
		$_adata['gr'] = gr;
		$_adata['bank_account_id'] = bank_account_id;
		if(hide_loading == false) $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=list',$_adata,function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_fund').html(respJson.html);
			$('.freeze-table').freezeTable('update');
			if(parseInt(respJson.current_page) == 1){
				$('.briefs').html(respJson.html_briefs);
			}
			$('#pager_fund').addClass('mt-2').pagination({
				listStyle:"pagination justify-content-center",
				currentPage: respJson.current_page, 
				itemsOnPage: respJson.per_page,
				items: respJson.total_record,
				cssStyle: 'light-theme',
				prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
				nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
				onPageClick : function(pageNumber){
					$Core.fund.list($.extend(options, {'page':pageNumber}), false);
				}
			});
		},'json');
	},
	cre_bank_tranfer: (_this, e) => {
		e.preventDefault();
		var fund_id = $(_this).attr('fund_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn chuyển đổi?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=cre_bank_tranfer', {
				'fund_id' : fund_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			}, 'json');
		});
		return false;
	},
	do_bank_transfer: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			fund_id = $(_this).attr('fund_id'),
			$_adata = {'fund_id':fund_id};
		if($('select.required,input.required',_form).length){
			$('select.required,input.required',_form).each((_i,_elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).select2('open');
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=do_bank_transfer",
				data: $_adata,
				dataType:'text',
				success: function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$Core.fund.list({});
						$Core.popup.close(_form.closest('.modal'));
						$Core.swal.error("Thông báo", "Đã tạo chuyển quỹ thành công!");
						
					} else if(html.indexOf('_invalid') >= 0){
						$Core.swal.error("Thông báo", "Bạn chưa chọn tài khoản nguồn và đích khác nhau!");
					} else {
						$Core.swal.error("Thông báo", "Đã xảy ra lỗi!");
					}
				}
			});
		}
		return false;
	},
	export: (_this, e) => {
		e.preventDefault();
		var REQUEST_URI = "",
			gr = $('.js__fund-filter-list:checked').val(),
			bank_account_id = $('.obank.current').attr('bank_account_id');
		
		REQUEST_URI += `?gr=${gr}&bank_account_id=${bank_account_id}`;
		$('.search_field').each((_i, _elem) => {
			var _field = $(_elem).data('field'),
				_value = $(_elem).val();
			REQUEST_URI+= `&${_field}=${_value}`;
		});
		window.open(`/fund/export.html${REQUEST_URI}`, '_blank');
		return false;
	},
	do_search : function(_this, e){
		if($(_this).hasClass('obank')){
			e.preventDefault();
			$('.obank').removeClass('current');
			$(_this).addClass('current');
			$.ajax({
				method: "POST",
				url: '/index.php?mod='+MOD+'&act=storage_sess_bank_account',
				data: {'bank_account_id': $(_this).attr('bank_account_id')},
				dataType: "json",
				async : false,
				success: function(respJson){
					// console.log('Storage session success !');
				}
			});
			$Core.fund.list({}, false);
			return false;
		} else {
			$Core.fund.list({}, false);
		}
	},
	open_manage: function(_this, e){
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_manage',{}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var _w = $(window).width();
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			$Core.fund.load_bank_accounts('BANK_ACCOUNT', {'loading':0});
		}, 'json');
		return false;
	},
	load_bank_accounts : function (property_type, options){
		var $_adata = options || {},
			loading = $_adata.hasOwnProperty('loading') ? $_adata.loading : 1;
		$_adata['property_type'] = property_type;
		$Core.util.toggleIndicatior(loading);	
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_bank_accounts', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$('.holder_setting_property_'+property_type).html(html);
		});
	},
	list_bank_transfer: function(options, hide_loading=true){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if(typeof(field) !== 'undefined'){
					$_adata[field] = $(_elem).val();
				}
			});
		}
		var action = $('.js__fund-filter-list:checked').val();
		$_adata['action'] = action;
		!hide_loading && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=list_bank_transfer',$_adata,function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_bank_transfer').html(respJson.html);
			$('.total_record').text(respJson.total_record);
			$('#pager_bank_transfer').addClass('mt-2').pagination({
				listStyle:"pagination justify-content-center",
				currentPage: respJson.current_page, 
				itemsOnPage: respJson.per_page,
				items: respJson.total_record,
				cssStyle: 'light-theme',
				prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
				nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
				onPageClick : function(pageNumber){
					$Core.fund.list_bank_transfer($.extend(options, {'page':pageNumber}));
				}
			});
		},'json');
	},
	do_search_bank_transfer: function(_this, e){
		$Core.fund.list_bank_transfer({},false);
	},
	open_bank_transfer: function(_this, e){
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		var bank_transfer_id = $(_this).attr('bank_transfer_id');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_bank_transfer',{
			'bank_transfer_id' : bank_transfer_id
		},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			if($('#attachments_'+respJson.uid).length){
				$('#attachments_'+respJson.uid).MultiFile({
					list: '#MultiFile-preview_'+respJson.uid
				});
			}
		},'json');
		return false;
	},
	check_amount_bank_transfer: function(_this, e){
		var form= $(_this).closest('.modal-body'),
			amount = $('input[name=amount]', form).val(),
			bank_account_from = $('select[name=bank_account_from]', form).val();
		if(!$Core.util.isEmpty(amount) && parseInt(bank_account_from) > 0){
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=check_amount_bank_transfer',{
				'amount' : amount,
				'bank_account_from' : bank_account_from
			},function(html){
				if(html.indexOf('_invalid') >= 0){
					$Core.swal.error('Oops', "Số tiền trong tài khoản nguồn không đủ !");
				}
			});
		}
	},
	save_bank_transfer: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			bank_transfer_id = $(_this).attr('bank_transfer_id'),
			$_adata = {'bank_transfer_id':bank_transfer_id};
		
		if($('select.required,input.required',_form).length){
			$('select.required,input.required',_form).each((_i,_elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=pop_save_bank_transfer",
				data: $_adata,
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);	
					if(respJson.msg.indexOf('_success') >= 0){
						$Core.fund.list_bank_transfer({});
						$Core.popup.close(_form.closest('.modal'));
						$Core.alert.success("Thêm chuyển quỹ thành công !");
					} else if(respJson.msg.indexOf('_error') >= 0){
						$Core.swal.error( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} else if(respJson.msg.indexOf('_duplicated') >= 0){
						$Core.swal.error( "Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại" );
					} 
				}
			});
		}
		return false;
	},
	delete_bank_transfer: function(_this, e){
		e.preventDefault();
		var bank_transfer_id = $(_this).attr('bank_transfer_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete_bank_transfer', {
				'bank_transfer_id' : bank_transfer_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.fund.list_bank_transfer({});
			});
		});
		return false;
	},
	open: function(_this, e){
		e.preventDefault();
		var gr = $(_this).attr('gr'),
			fund_id = $(_this).attr('fund_id');
		
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open',{
			'gr' : gr,
			'fund_id' : fund_id
		},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			"THUCCHI"==gr && setTimeout(()=>{
				$_easyUI("#cboProduct_"+respJson.uid).combogrid({
					onSelect:function(i,n){
						$("input[name=person]").val(n.staff_name);
						$("input[name=billing_id]").val(n.billing_id);
						$("input[name=person_address]").val(n.staff_address);
					}
				}
			)},50);
			$('#attachments_'+respJson.uid).MultiFile({
				list: '#MultiFile-preview_'+respJson.uid
			});
		},'json');
		return false;
	}, 
	duplicate: function(_this, e){
		e.preventDefault();
		var gr = $(_this).attr('gr'),
			fund_id = $(_this).attr('fund_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn nhân bản?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=duplicate',{
				'gr' : gr,
				'fund_id' : fund_id
			},function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_success') >= 0){
					var tmp = respJson.msg.split('|||');
					$Core.fund.list({});
					$('body').append(tmp[1]);
					$('.autoclick_'+respJson.duplicate_id).trigger('click').remove();
					$Core.alert.success('Nhân bản thành công !');
				} else {
					$Core.alert.error('Lỗi. Đã có lỗi trong quá trình nhân bản !');
				}
			}, 'json');
		});
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			gr = $(_this).attr('gr'),
			fund_id = $(_this).attr('fund_id'),
			$_adata = {'gr':gr, 'fund_id':fund_id};
		
		if($('select.required,input.required',_form).length){
			$('select.required,input.required',_form).each((_i,_elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=pop_save_fund",
				data: $_adata,
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);	
					if(respJson.msg.indexOf('_success') >= 0){
						$Core.fund.list({});
						$Core.popup.close(_form.closest('.modal'));
						$Core.swal.success("Thông báo", "Thêm phiếu thu/chi thành công !");
					} else if(respJson.msg.indexOf('_error') >= 0){
						$Core.swal.error("Thông báo","Đã xảy ra lỗi hệ thống dữ liệu!");
					} else if(respJson.msg.indexOf('_invalid') >= 0){
						$Core.swal.error("Thông báo","Thời gian nhập vào không hợp lệ!" );
					} 
				}
			});
		}
		return false;
	},
	open_person:(_this,e) => {
		e.preventDefault();
		var gr = $(_this).attr('gr'),
			uid = $(_this).attr('uid');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_person',{
			'gr':gr,
			'uid':uid
		},function(respJson){
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			$('input[name=phone]').change(function(){
				var _phone = $(this).val();
				if(!$Core.util.isEmpty(_phone)){
					$(this).val(_phone.replace(/\s+/,''));
				}
			});
		},'json');
		return false;
	},
	pop_save_person:(_this,e) => {
		e.preventDefault();
		var _validated = 0,
			_uid = $(_this).attr('uid'),
			_form = $(_this).closest('form'),
			$_adata = {};
		if($('select.required,input.required',_form).length){
			$('select.required,input.required',_form).each((_i,_elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=pop_save_person",
				data: $_adata,
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);		
					if(respJson.msg.indexOf('_success') >= 0){
						$('.btn-close',_form).trigger('click');
						var $_select = $('#person_'+_uid).selectize(),
							$_selectize = $_select[0].selectize;
						$_selectize.addOption({'id':respJson.id,'text':respJson.name});
						$_selectize.setValue(respJson.id);
						$('#person_'+_uid).val(respJson.name);
						$('#person_address_'+_uid).val(respJson.address);
					} else if(respJson.msg.indexOf('_error') >= 0){
						$Core.swal.error.fire( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại" );
					} else if(respJson.msg.indexOf('_duplicated') >= 0){
						$Core.swal.error( "Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại" );
					} 
				}
			});
		}
		return false;
	},
	get_client: function(_this, e){
		var uid = $(_this).attr('uid'),
			client_id = $(_this).val();
		console.log(uid);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=get_client',{
			'client_id':client_id
		},function(respJson){
			$('#person_'+uid).val(respJson.name);
			$('#person_address_'+uid).val(respJson.address);
		}, 'json');
	},
	view: function(_this, e){
		e.preventDefault();
		var fund_id = $(_this).attr('fund_id');
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=view', {
			'fund_id' : fund_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	printThis: function(_this, e){
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			options = {
				mode:'iframe',
				strict: undefined,
				//popTitle: "PC001",
			};
		$('.printArea_'+uid).printArea(options);
	},
	delete: function(_this, e){
		e.preventDefault();
		var fund_id = $(_this).attr('fund_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete_fund', {
				'fund_id' : fund_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.fund.list({});
			});
		});
		return false;
	},
	load_report: function( options){
		var $_adata = options || {};
		$.post('/index.php?mod='+MOD+'&act=load_report', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.loadcontent').html(respJson.html);
			$('.report_menu .active').removeClass('active');
			$('.m_link').parent().addClass('active');
			setTimeout(() => {
				$Core.fund._autoload($_adata);
			}, 500);
		}, 'json');
	},
	_autoload: function(options){
		var $_adata = options || {};
		if($('.ajax:not(.loaded)').length){
			$('.ajax:not(.loaded)').each((_i, _elem) => {
				var _url = $(_elem).data('url'),
					_params = $(_elem).data('options') || {};
			$.post(_url, $_adata, function(respJson){
					$(_elem).addClass('loaded').html(respJson.html);
					if(respJson.callback) {
						eval(respJson.callback);
					}
				}, 'json');
			});
		}
	},
	load_content_report: function(_this, gr, cat_id, options){
		var $_adata = options || {};
		$_adata['gr'] = gr;
		$_adata['cat_id'] = cat_id;
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_content_report', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.loadcontent').html(respJson.html);
			$('.report_menu .active').removeClass('active');
			$(_this).parent().addClass('active');
			setTimeout(() => {
				$Core.fund.load_table_report(gr, cat_id, options);
				$Core.fund.load_chart_report(gr, cat_id, options);
			}, 500);
		}, 'json');
	},
	load_table_report(gr, cat_id, options){
		var $_adata = options || {};
		$_adata['gr'] = gr;
		$_adata['cat_id'] = cat_id;
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_table_report', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#table_report_'+gr+"_"+cat_id).html(respJson.html);
			$('#total_amounts_'+gr+'_'+cat_id).html(respJson.total_amount);
			if(parseInt(respJson.total_page)){
				$('#pager_table_report_'+gr+"_"+cat_id).pagination({
					total:respJson.total_record,
					pageSize:respJson.per_page,
					onRefresh : function(pageNumber,pageSize){
						$Core.fund.load_table_report(gr,cat_id,$.extend(options,{
							'page':pageNumber,
							'per_page':pageSize}
						));
					},
					onSelectPage : function(pageNumber,pageSize){
						$Core.fund.load_table_report(gr,cat_id,$.extend(options,{
							'page':pageNumber,
							'per_page':pageSize}
						));
					}
				});
			}
		}, 'json');
	},
	load_chart_report(gr, cat_id, options){
		var $_adata = options || {};
		$_adata['gr'] = gr;
		$_adata['cat_id'] = cat_id;
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_chart_report', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#chart_report_'+gr+"_"+cat_id).html(respJson.html);
			$Core.chart.canvas('chart_'+respJson.uid, respJson.barChartData);
			//$Core.chart.canvas_multi('chartMOC'+respJson.uid,respJson.barChartData);
		}, 'json');
	},
	reload: function(_this, e){
		var gr = $(_this).attr('gr'),
			cat_id = $(_this).attr('cat_id'),
			month = $('select[name=month]').val(),
			year = $('select[name=year]').val();
		if(gr == '_general'){
			$('.ajax').removeClass('loaded');
			$Core.fund._autoload({'month':month, 'year':year});
		} else {
			var options = {'month':month, 'year':year};
			$Core.fund.load_table_report(gr, cat_id, options);
			$Core.fund.load_chart_report(gr, cat_id, options);
		}
	},
	select_file: (_this, e) => {
		var toId = $(_this).attr('toId');
		$('#upload_file_'+toId).trigger('click');
		return false;
	},
	open_import: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form');
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=open_import",
			data: {},
			dataType:'json',
			success: function(respJson){
				$Core.util.toggleIndicatior(0);		
				if(respJson.msg.indexOf('_success') >= 0){
					$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
					_form.clearForm();
					_form.resetForm();
				} else if(respJson.msg.indexOf('_error') >= 0){
					$Core.swal.error( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại" );
				}  
			}
		});
		return false;
	},
	do_import: (_this, e) => {
		e.preventDefault();
		var $_adata = {},
			_validated = 0,
			_uid = $(_this).attr('uid'),
			_form = $(_this).closest('form');
		if($('select.required,input.required',_form).length){
			$('select.required,input.required',_form).each((_i,_elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					if($(_elem).hasClass('select2-hidden-accessible')){
						$(_elem).select2('open');
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
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=do_import",
				data: {'uid' : _uid},
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);		
					if(respJson.msg.indexOf('_success') >= 0){
						$Core.swal.success( "Thông báo",`Đã import thành công +${respJson.total_inserted} records`);
						setTimeout(() => {
							window.location.reload();
						}, 1000);
					} else if(respJson.msg.indexOf('_error_field') >= 0){
						$Core.swal.error( "Thông báo", respJson.html);
					} else if(respJson.msg.indexOf('_error_missing_field') >= 0){
						$Core.swal.error( "Thông báo", respJson.html);
					} else if(respJson.msg.indexOf('_error') >= 0){
						$Core.swal.error( "Thông báo","Quá trình import bị lỗi. Xin vui lòng thử lại" );
					}
				}
			});
		}
		return false;
	},
}
$Core.ops_cost = {		
	autoload: () => {
		if($('.ajax:not(.loaded)').length){
			$('.ajax:not(.loaded)').each((_i, _elem) => {
				var url = $(_elem).data('url'),
					gId = $(_elem).getAttr('gId', ""),
					$_adata = $(_elem).data('options') || {};
				if($('.search_field').length){
					$('.search_field').each((_i, _elem) => {
						var name = $(_elem).attr('name');
						$_adata[name] = $(_elem).val();
					});
				}
				$_adata['gId'] = gId;
				$.post(url, $_adata, function(respJson){
					$(_elem).addClass('loaded').html(respJson.html);
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
				},'json');
			});
		}
	}, do_search : (_this, e) => {
		$Core.ops_cost.list({"type":"list"}, false);
	}, list: (options, hide_loading=true) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if(typeof(field) !== 'undefined'){
					$_adata[field] = $(_elem).val();
				}
			});
		}
		if(hide_loading == false) $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=list_ops_cost',$_adata,function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_ops_cost').html(respJson.html);
			$('#pager_ops_cost').addClass('mt-2').pagination({
				listStyle:"pagination justify-content-center",
				currentPage: respJson.current_page, 
				itemsOnPage: respJson.per_page,
				items: respJson.total_record,
				cssStyle: 'light-theme',
				prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
				nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
				onPageClick : (pageNumber) => {
					$Core.ops_cost.list($.extend(options, {'page':pageNumber}), false);
				}
			});
		},'json');
	}, reloadAll: (_this, e) => {
		e.preventDefault();		
		var gId = $(_this).attr('gId'),
			name = $(_this).attr('name');
		if(name== 'year'){
			var year = $(_this).val();
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod=home&sub=dashboard&act=load_month', {
				'year' : year,
			}, function(html){
				$Core.util.toggleIndicatior(0);
				$('select[name=month][gId='+gId+']').html(html);
			});
		}
		if($(_this).hasClass('js__search-date-field')){
			if($(_this).hasClass('js__search-start_date-field')){
				var _input = document.querySelector('.js__search-end_date-field');
				_input.showPicker();
			}
		} else {
			if($(_this).hasClass('js__search-year-field') 
				|| $(_this).hasClass('js__search-month-field')){
				var _year = $('.js__search-year-field').val(),
					_month = $('.js__search-month-field').val();
				$Core.util.toggleIndicatior(1);
				$.ajax({
					method: "POST",
					url: '/index.php?mod='+MOD+'&sub=dashboard&act=load_date_range',
					data: {'month':_month,'year':_year},
					dataType: "json",
					async : false,
					success: function(respJson){
						$Core.util.toggleIndicatior(0);
						$('.js__search-date_type-field').val('');
						$('.js__search-start_date-field').val(respJson.start_date);
						$('.js__search-end_date-field').val(respJson.end_date);
					}
				});
			} else if($(_this).hasClass('js__search-date_type-field')){
				var _date_type = $(_this).val(),
					$_adata = {'date_type' : _date_type};
				if($('.search_field').length){
					$('.search_field').each((_i, _elem) => {
						var _field = $(_elem).data('field');
						$_adata[_field] = $(_elem).val();
					});
				}
				$.ajax({
					method: "POST",
					url: '/index.php?mod='+MOD+'&sub=dashboard&act=load_time_range',
					data: $_adata,
					dataType: "json",
					async : false,
					success: function(respJson){
						$('.js__search-start_date-field').val(respJson.start_date);
						$('.js__search-end_date-field').val(respJson.end_date);
					}
				});
			}
		}		
		
		$('.ajax:not(.not-reload)').removeClass('loaded');
		setTimeout(function(){
			$Core.ops_cost.autoload();
		},500);
	},
}
$Core.cash_book = {
	do_search: (_this, e) => {
		e.preventDefault();
		$Core.cash_book.list({});
		return false;
	}, list: (options) => {
		var $_adata = options || {},
			company_id = $('.rdo__company:checked').val();
		$_adata['company_id'] = company_id;
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=cash_book&act=list', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#'+'holder_cash_book').html(respJson.html);
			$('.total_opening_balance').text(respJson.total_opening_balance);
			$('.total_receipt_amount').text(respJson.total_receipt_amount);
			$('.total_payment_amount').text(respJson.total_payment_amount);
			$('.total_closing_balance').text(respJson.total_closing_balance);
		}, 'json');
	}, open_setting: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.getJSON('/index.php?mod='+MOD+'&sub=cash_book&act=open_setting', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		},"json");
		return false;
	}, save_setting : (_this, e) => {
		e.preventDefault();
		var _error = 0, 
			_form = $(_this).closest("form");
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error += 1;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_error == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: `${PCMS_URL}/index.php?mod=${MOD}&sub=cash_book&act=save_setting`, 
				data:{},
				dataType:'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.msg.indexOf('_success') >= 0) {
						$(".btn-close", _form).trigger("click");
						$Core.swal.success("Thông báo", "Cài đặt thành công!");
					}else{
						alertify.error(respJson.msg);
					}
				}
			});
		}
		return false;
	}, get_worksheets: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_toId = $(_this).attr('toId'),
			_spreadsheetId = $.trim($(_this).val());
		if($Core.util.isEmpty(_spreadsheetId)){
			$(_this).focus();
		} else {
			$Core.util.toggleIndicatior(1);
			$.post(`${PCMS_URL}/index.php?mod=${MOD}&sub=cash_book&act=get_worksheets`, {
				'spreadsheetId' : _spreadsheetId
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$('#'+_toId).html(respJson.html_options);
			}, 'json');
		}
		return false;
	}, open_config : (_this, e) => {
		e.preventDefault();
		var _gId = $(_this).attr('gId'),
			_holderG = $(_this).attr('holderG'),
			_form = $(_this).closest('form');
		if(_holderG == 'google.sheet'){
			var $block = $(_this).closest('.form-group'),
				spreadsheetId = $('.spreadsheetId', $block).val(),
				sheet_name = $('.sheet_name', $block).val();
			if($Core.util.isEmpty(spreadsheetId) || $Core.util.isEmpty(sheet_name)){
				$Core.swal.error("Thông báo", "Tên File hoặc Tên sheet không được trống");
			} else {
				$Core.util.toggleIndicatior(1);
				$.post('/index.php?mod='+MOD+'&sub=cash_book&act=open_config', {
					'spreadsheetId' : spreadsheetId,
					'sheet_name' : sheet_name,
					'holderG' : _holderG
				}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
				},"json");
			}
		} else {
			if($("input[name='fileImport']",_form)[0].files.length > 0) {
				$Core.util.toggleIndicatior(1);
				_form.ajaxSubmit({
					type: 'POST',
					url: PCMS_URL+'/index.php?mod='+MOD+'&sub=cash_book&act=open_config', 
					data : {'gId' : _gId, 'holderG' : _holderG},
					dataType:'json',
					success: function(respJson){
						$Core.util.toggleIndicatior(0);
						$Core.popup.open('auto','auto',respJson.html,respJson.uid);
					}
				});
			}else{
				$Core.swal.error("Thông báo", "Bạn cần chọn file dữ liệu cần import");
			}	
		}
		return false;
	}, save_config: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_gId = $(_this).attr("gId"),
			_form = $(_this).closest("form");
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_error += 1;
					if($(_elem).hasClass('select2-hidden-accessible')){
						$(_elem).select2('open');
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
				type: 'POST',
				url: `${PCMS_URL}/index.php?mod=${MOD}&sub=cash_book&act=save_config`, 
				data:{'gId':_gId},
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
		}
		return false;
	}, open_import : (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.getJSON(PCMS_URL+'/index.php?mod='+MOD+'&sub=cash_book&act=open_import', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		});
		return false;
	}, do_change : (_this, e) => {
		// e.preventDefault();
		var _form = $(_this).closest('form'),
			spreadsheetId  = $(_this).attr('spreadsheetid'),
			sheet_name = $(_this).attr('sheet_name');
		$('.spreadsheetId', _form).val(spreadsheetId);
		if ($('.sheet_name', _form).find('option[value="' + sheet_name + '"]').length === 0) {
			$('.sheet_name', _form).append(new Option(sheet_name, sheet_name, true, true));
		} else {
			$('.sheet_name', _form).val(sheet_name);
		}
		// return false;
	}, do_import : (_this, e) => {
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
				url: PCMS_URL + "/index.php?mod="+MOD+"&sub=cash_book&act=do_import",
				data: {},
				dataType: "html",
				success: function(html){
					$Core.util.toggleIndicatior(0);
				}
			});
		}
		return false;
	}
};
$Core.fund.dashboard = {
	refresh : (_this, e) => {
		e.preventDefault();
		var gId = $(_this).attr('gId');
		$('#'+gId).removeClass('loaded');
		$Core.fund.dashboard._autoload();
		return false;
	}, load_content: ( _type, options) => {
		var $_adata = options || {};
		$_adata['_type'] = _type;
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&sub=dashboard&act=load_content_detail', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.loadcontent').html(respJson.html);
			$Core.util.popstate(respJson.link);
			setTimeout(() => {
				$Core.fund.dashboard.load_table_report(_type, options);
				$Core.fund.dashboard._autoload($_adata);
			}, 500);
		}, 'json');
	},
	load_content_detail: (_this, _type, options) => {
		$Core.fund.dashboard.load_content(_type,options);		
		$(_this).closest(".report_menu").find(".menu-item.active").removeClass('active');
		$(_this).parent().addClass('active');
	},
	load_table_report : (_type, options) => {
		var $_adata = options || {};
		$_adata['_type'] = _type;
		$.post('/index.php?mod='+MOD+'&sub=dashboard&act=load_table_report', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#table_report').html(respJson.html);
			$('#total_amounts').html(respJson.total_amount);
			if(parseInt(respJson.total_page)){
				$('#pager_table_report').pagination({
					total:respJson.total_record,
					pageSize:respJson.per_page,
					onRefresh : function(pageNumber,pageSize){
						$Core.fund.dashboard.load_table_report(_type,$.extend(options,{
							'page':pageNumber,
							'per_page':pageSize}
						));
					},
					onSelectPage : function(pageNumber,pageSize){
						$Core.fund.dashboard.load_table_report(_type,$.extend(options,{
							'page':pageNumber,
							'per_page':pageSize}
						));
					}
				});
			}
		}, 'json');
	}, load_chart_report: (_type, options) => {
		var $_adata = options || {};
		$_adata['_type'] = _type;
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_chart_report', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#chart_report').html(respJson.html);
			$Core.chart.canvas('chart_'+respJson.uid, respJson.barChartData);
		}, 'json');
	}, load_detail_cash_flow : (_this, e) => {
		e.preventDefault();
		var _type=$(_this).data("type"),
			$_adata = {"_type":_type};
		if($(".search_field").length > 0) {
			$(".search_field").each(function(index, elm){
				var field_name = $(elm).attr("name"),
					field_value = $(elm).val();
				$_adata[field_name] = field_value;
			});
		}
		$_adata['_type'] = _type;
		$.post('/index.php?mod='+MOD+'&sub=cash_flow&act=load_detail_cash_flow', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
	}, load_detail_income_statement : (_this, e) => {
		e.preventDefault();
		var _type=$(_this).data("type"),
			$_adata = {"_type":_type};
		if($(".search_field").length > 0) {
			$(".search_field").each(function(index, elm){
				var field_name = $(elm).attr("name"),
					field_value = $(elm).val();
				$_adata[field_name] = field_value;
			});
		}
		$_adata['_type'] = _type;
		$.post('/index.php?mod='+MOD+'&sub=dashboard&act=load_detail_income_statement', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
	}, _autoload: (options) => {
		var $_adata = options || {};
		if($(".search_field").length > 0) {
			$(".search_field").each(function(index, elm){
				var field_name = $(elm).attr("name"),
					field_value = $(elm).val();
				$_adata[field_name] = field_value;
			});
		}
		if($('.ajax:not(.loaded)').length){
			$('.ajax:not(.loaded)').each((_i, _elem) => {
				var _url = $(_elem).data('url'),
					_params = $(_elem).data('options') || {};
				$_adata = $.extend({},$_adata,_params);
			$.post(_url, $_adata, function(respJson){
					$(_elem).addClass('loaded').html(respJson.html);
					if(respJson.callback) {
						eval(respJson.callback);
					}
					if($(_elem).hasClass("cash_flow")) {
						var toId = $(_elem).attr("toId");
						$(`#${toId}`).html(respJson.html_table);
					}
				}, 'json');
			});
		}
	}, reload: (_this, e) => {
		var gId = $(_this).attr('gId'),
			_type = $(".menu-item.active").attr('gId'),
			$_adata = {};
		if($(".search_field").length > 0) {
			$(".search_field").each(function(index, elm){
				var field_name = $(elm).attr("name"),
					field_value = $(elm).val();
				$_adata[field_name] = field_value;
			});
		}
		$Core.util.toggleIndicatior(1);
		$(`.ajax.loaded[gId='${gId}']`).removeClass("loaded");
		$Core.fund.dashboard._autoload($_adata);
		$Core.util.toggleIndicatior(0);
	},
}