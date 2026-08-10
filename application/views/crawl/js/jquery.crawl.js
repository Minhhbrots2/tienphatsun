$(function(){
	if(ACT == "report_crawl") {
		$Core.crawl.autoload(0);
	}
	if(ACT == "crawl_highfloor" || ACT == "crawl_lowfloor") {
		$Core.crawl.load_total();
	}
});
$Core.crawl = {
	load_total : function(){
		var number_error = $(".not_success").length,
			number_changed = $(".is_changed").length;
		$(".number_error").text(number_error);
		$(".number_changed").text(number_changed);
	},
	open_agency: function (_this){
		var agency_id = $(_this).attr('agency_id'),
			stock_type = $(_this).attr('stock_type'),
			$_adata = {'agency_id':agency_id,'stock_type':stock_type};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_agency', $_adata, function(respJson){
			vietiso_loading(0);
			makepopup('auto','auto', respJson.html, respJson.uid);
			if(respJson.callback) eval(respJson.callback);
		}, 'json');
		return false;
	},
	save_agency: function (_this,e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			agency_id = $(_this).attr("agency_id"),
			stock_type = $(_this).attr("stock_type"),
			$_adata = {'agency_id':agency_id, 'stock_type':stock_type};
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=save_agency',
			data: $_adata,
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				if(respJson.result){
					alertify.success('Success !');
				}else{
					alertify.error('Error!');
				}
				// $Core.popup.close(_form.closest('.modal'));
				// window.location.reload(true);
			}
		});	
		return false;
	},
	handle_status: function (_this,e){
		var agency_id = $(_this).attr("agency_id"),
			block_id = $(_this).attr("block_id"),
			project_id = $(_this).attr("project_id"),
			stock_type = $(_this).attr("stock_type"),
			is_crawl = $(_this).is(":checked") ? 1 : 0;
			$_adata = {
				'agency_id':agency_id, 
				'block_id' : block_id, 
				'project_id' : project_id, 
				'is_crawl': is_crawl, 
				'stock_type': stock_type
			};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=handle_status', $_adata, function(respJson){
			vietiso_loading(0);
			if(respJson.result){
				alertify.success('Success !');
			}else{
				alertify.error('Error!');
			}
		}, 'json');
	},
	do_crawl: (_this, e) => {
		$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn thực hiện?", function(){
			alert("x");
		});
	},
	crawl_agency1: function (_this,e){
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id"),
			block_id = $(_this).attr("block_id"),
			project_id = $(_this).attr("project_id"),
			stock_type = $(_this).attr("stock_type");
		if($(".btn_crawl.pendding").length == 0) {
			$Core.messager.confirm('Xác nhận', 'Bạn có chắc chắn muốn thực hiện?', function(){
//				$Core.util.toggleIndicatior(1);
				$(".loading.d-none",$(_this)).removeClass("d-none");
				$(".fa-play:not(.d-none)",$(_this)).addClass("d-none");
				$(_this).addClass("pendding");
				$(".btn_crawl:not(.pendding)").attr("disabled","true");
				$.post('/index.php?mod=crawl&act=crawl_agency1', {
					'agency_id':agency_id,
					'block_id':block_id,
					'project_id':project_id,
					'stock_type':stock_type,
				}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$(".loading:not(.d-none)",$(_this)).addClass("d-none");
					$(".fa-play.d-none",$(_this)).removeClass("d-none");
					$(".btn_crawl:not(.pendding)").removeAttr("disabled");
					$(_this).removeClass("pendding");
					if(respJson.result){
						alertify.success(respJson.msg);
						setTimeout(function(){
//							window.location.reload();
						},500);
					}else{
						alertify.error(respJson.msg);
					}
				}, 'json');
			});
		}		
		return false;
	},
	crawl_agency: function (_this,e){
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id"),
			block_id = $(_this).attr("block_id"),
			project_id = $(_this).attr("project_id"),
			stock_type = $(_this).attr("stock_type");
		if($(".btn_crawl.pendding").length == 0) {
			$Core.messager.confirm('Xác nhận', 'Bạn có chắc chắn muốn thực hiện?', function(){
//				$Core.util.toggleIndicatior(1);
				$(".loading.d-none",$(_this)).removeClass("d-none");
				$(".fa-play:not(.d-none)",$(_this)).addClass("d-none");
				$(_this).addClass("pendding");
				$(".btn_crawl:not(.pendding)").attr("disabled","true");
				$.post('/index.php?mod=crawl&act=crawl_agency', {
					'agency_id':agency_id,
					'block_id':block_id,
					'project_id':project_id,
					'stock_type':stock_type,
				}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$(".loading:not(.d-none)",$(_this)).addClass("d-none");
					$(".fa-play.d-none",$(_this)).removeClass("d-none");
					$(".btn_crawl:not(.pendding)").removeAttr("disabled");
					$(_this).removeClass("pendding");
					if(respJson.result){
						alertify.success(respJson.msg);
						setTimeout(function(){
//							window.location.reload();
						},500);
					}else{
						alertify.error(respJson.msg);
					}
				}, 'json');
			});
		}		
		return false;
	},
	crawl_agency_lowfloor: function (_this,e){
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id"),
			target_id = $(_this).attr("target_id"),
			stock_type = $(_this).attr("stock_type");
		if($(".btn_crawl.pendding").length == 0) {
			$Core.messager.confirm('Xác nhận', 'Bạn có chắc chắn muốn thực hiện?', function(){
//				$Core.util.toggleIndicatior(1);
				$(".loading.d-none",$(_this)).removeClass("d-none");
				$(".fa-play:not(.d-none)",$(_this)).addClass("d-none");
				$(_this).addClass("pendding");
				$(".btn_crawl:not(.pendding)").attr("disabled","true");
				$.post('/index.php?mod=crawl&sub=lowfloor&act=crawl_agency_lowfloor', {
					'agency_id':agency_id,
					'target_id':target_id,
					'stock_type':stock_type,
				}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$(".loading:not(.d-none)",$(_this)).addClass("d-none");
					$(".fa-play.d-none",$(_this)).removeClass("d-none");
					$(".btn_crawl:not(.pendding)").removeAttr("disabled");
					$(_this).removeClass("pendding");
					if(respJson.result){
						alertify.success(respJson.msg);
						setTimeout(function(){
//							window.location.reload();
						},500);
					}else{
						alertify.error(respJson.msg);
					}
				}, 'json');
			});
		}		
		return false;
	},
	crawl_agency_lowfloor1: function (_this,e){
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id"),
			target_id = $(_this).attr("target_id"),
			stock_type = $(_this).attr("stock_type");
		if($(".btn_crawl.pendding").length == 0) {
			$Core.messager.confirm('Xác nhận', 'Bạn có chắc chắn muốn thực hiện?', function(){
//				$Core.util.toggleIndicatior(1);
				$(".loading.d-none",$(_this)).removeClass("d-none");
				$(".fa-play:not(.d-none)",$(_this)).addClass("d-none");
				$(_this).addClass("pendding");
				$(".btn_crawl:not(.pendding)").attr("disabled","true");
				$.post('/index.php?mod=crawl&sub=lowfloor&act=crawl_agency_lowfloor1', {
					'agency_id':agency_id,
					'target_id':target_id,
					'stock_type':stock_type,
				}, function(respJson){
					$Core.util.toggleIndicatior(0);
					$(".loading:not(.d-none)",$(_this)).addClass("d-none");
					$(".fa-play.d-none",$(_this)).removeClass("d-none");
					$(".btn_crawl:not(.pendding)").removeAttr("disabled");
					$(_this).removeClass("pendding");
					if(respJson.result){
						alertify.success(respJson.msg);
						setTimeout(function(){
//							window.location.reload();
						},500);
					}else{
						alertify.error(respJson.msg);
					}
				}, 'json');
			});
		}		
		return false;
	},
	open_config_update_stock: function (_this){
		var stock_type = $(_this).attr('stock_type'),
			$_adata = {'stock_type':stock_type};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=crawl&act=open_config_update_stock', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	save_config_update_stock : function (_this,e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			stock_type = $(_this).attr('stock_type'),
			$_adata = {'stock_type':stock_type};	
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod=crawl&act=save_config_update_stock",
			data: $_adata,
			dataType: "json",
			success: function(respJson){
				if(respJson.result){
					$('.btn-close', _form).trigger('click');
					alertify.success("Thành công!");
				} else {
					alertify.success("Lỗi!");
				} 
			}
		});	
		return false;
	},
	toggleSwitch : function(_this,e){
		e.preventDefault();
		var key = $(_this).attr("key"),
			action = $(_this).attr("action"),
			_form = $(_this).closest("form");
		if(action == "show") {
			$(".switch_"+key,_form).prop("checked",true);	
		}else{
			$(".switch_"+key,_form).prop("checked",false);
		}		
	},
	callAjaxCrawlAgency : function(elm,$_adata) {
		return new Promise(function (resolve, reject) {
			$(".loading.d-none",$(elm)).removeClass("d-none");
			$(".fa-play:not(.d-none)",$(elm)).addClass("d-none");
			$(elm).addClass("pendding");
			$(".btn_crawl:not(.pendding)").attr("disabled","true");
			var url = '/index.php?mod=crawl&act=crawl_agency';
			if($_adata["stock_type"] == 177) {
				url = '/index.php?mod=crawl&sub=lowfloor&act=crawl_agency_lowfloor'
			}
			currentRequest = $.ajax({
				url : url,
	//			async: false,
				method: "POST",
				dataType: "json",
				data : $_adata,
				success : function(respJson){
					$Core.util.toggleIndicatior(0);
					$(".loading:not(.d-none)",$(elm)).addClass("d-none");
					$(".fa-play.d-none",$(elm)).removeClass("d-none");
					$(".btn_crawl:not(.pendding)").removeAttr("disabled");
					$(elm).removeClass("pendding");
	//				return respJson;
					if(respJson.result){
						alertify.success(respJson.msg);
					}else{
						alertify.error(respJson.msg);
					}
					resolve(respJson);
				},
				error: function (xhr, status, error) {
                    if (status === 'abort') {
                        console.warn('Request bị huỷ:', id);
                    }
                    reject(error);
                }
			});
	   });
	},
	
	open_import_logs: function(_this, e){
		e.preventDefault();
		var agency_id = $(_this).attr('agency_id'),
		target_id = $(_this).attr('target_id'),
		stock_type = $(_this).attr('stock_type');
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=crawl&act=open_import_logs', {
			'agency_id' : agency_id,
			'target_id' : target_id,
			'stock_type' : stock_type,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	isProcessing : true,
	currentRequest : null,
	crawl_general: async function (_this,e){
		$Core.crawl.isProcessing = true;
		if($(_this).hasClass("pendding")) {
			$(_this).removeClass("pendding");
			$(_this).html(`<i class='fa fa-play fs-18'></i> <span>Thực hiện</span>`).attr("title","Thực hiện");
			$Core.crawl.isProcessing = false;
		}else{
			$(_this).addClass("pendding");
			$(_this).html(`<i class='bx bx-stop-circle fs-24'></i> <span>Dừng</span>`).attr("title","Dừng");
		}
		if($('.btn_crawl').length > 0 && $Core.crawl.isProcessing) {
			for(var i=0; i < $('.btn_crawl').length; i++ ) {
				console.log($Core.crawl.isProcessing);
				if (!$Core.crawl.isProcessing) {
					break;
				}
				let button = $('.btn_crawl').eq(i);
				try {
					var agency_id = button.attr("agency_id"),
						block_id = button.attr("block_id"),
						target_id = button.attr("target_id"),
						stock_type = button.attr("stock_type"),
						$_adata = {agency_id:agency_id,block_id:block_id,target_id:target_id,stock_type:stock_type};
					let response = await $Core.crawl.callAjaxCrawlAgency(button,$_adata);
				} catch (error) {
					console.error('Error:', error);
				}
			}
			$Core.crawl.isProcessing = false;
			setTimeout(function(){
				window.location.reload();
			},500);
		}
		return false;
	},
	start_import: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			agency_id = $(_this).attr('agency_id'),
			stock_type = $(_this).attr('stock_type'),
			target_id = $(_this).attr('target_id'),
			spreadsheetId = $(_this).attr('spreadsheetId'),
			$_adata = {'agency_id':agency_id,'stock_type':stock_type, 'spreadsheetId':spreadsheetId, 'target_id':target_id};
		$Core.util.toggleIndicatior(1);
		// console.log($_adata); return false;
		$.post(PCMS_URL+'/index.php?mod=crawl&act=start_import', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, 
	do_import: function(_this, e){
		e.preventDefault();
		var _validated = 0, 
			_modal = $(_this).closest('.modal'),
			_form = $(_this).closest('form'),
			uid = $(_this).attr('uid'),
			stock_type = $(_this).attr('stock_type'),
			agency_id = $(_this).attr('agency_id'),
			project_id = $(_this).attr('project_id'),
			type = $("input[name='type']",_form).val(),
			url = PCMS_URL+'/index.php?mod=crawl&act=do_import',
			opt_ignore_empty = $('input[name=opt_ignore_empty]').is(':checked') ? 1 : 0,
			$_adata = {'uid':uid, 'agency_id':agency_id, 'stock_type':stock_type, 'project_id':project_id, 'opt_ignore_empty':opt_ignore_empty};
		if(stock_type == _STOCK_TYPE_LEASING || stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
			var list_field = new Array();
			if($('.stock_import_field', _form).length){
				$('.stock_import_field', _form).each((_i, _elem) => {
					if(!$Core.util.isEmpty($(_elem).val())){
						list_field.push($(_elem).val());
					}
				});
			}
			if($.inArray('ms_code', list_field) == -1){
				_validated+= 1;
				$Core.alert.error("Bạn chưa chọn mã căn");
				return false;
			}
		}
		if(type != "") {
			url = PCMS_URL+'/index.php?mod=crawl&act=do_copy_agent';
			$_adata['tblData'] = $Core.crawl.spreadsheet.getData();
		}
		if(_validated == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type : 'POST',
				url: url,
				data: $_adata,
				dataType: 'json',
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result){
						alertify.success(respJson.msg);
						setTimeout(function(){
							$(".btn-close",_modal).trigger("click");
//							window.location.reload();
						},500);
					}else{
						alertify.error(respJson.msg);
					}
				}
			});
		}
		return false;
	},
	//upload image
	choose_image : function (_this,e){
		e.preventDefault();
		var _form = $(_this).closest("form");
		$(".file_upload",_form).trigger("click");
	},
	spreadsheet : null,
	start_import_image : function(_this,e){
		e.preventDefault();
		var _form = $(_this).closest("form"),
			agency_id = $(_this).attr('agency_id'),
			target_id = $(_this).attr('target_id'),
			stock_type = $(_this).attr('stock_type'),
			type = $(_this).data('type'),
			$_adata = {'agency_id':agency_id,'type':type,'target_id':target_id,'stock_type':stock_type};
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : PCMS_URL+'/index.php?mod=crawl&act=start_import_image',
			data : $_adata,
			dataType:'json',
			success : function(respJson){
				$Core.util.toggleIndicatior(0);
				$("input[type=file]",_form).val("");
				if(respJson.result){
					$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
					$Core.crawl.spreadsheet = jspreadsheet(document.getElementById('spreadsheet_'+respJson.uid), {
						data:respJson.data,
						columns: respJson.dataHead
					});
					
				}else{
					alertify.error(respJson.msg);
				}				
			}
		});
		return false;
	},
	timer_click: function(_this, e){
		e.preventDefault();
		var time_type = $(_this).data('time'),
			_parent = $(_this).closest(".card_load_time");
		$('input[name=time_type]',_parent).val(time_type);
		$('.js_choose-time',_parent).removeClass('active');
		$(_this).addClass('active');
		$(".ajax.loaded",_parent).removeClass("loaded");
		$Core.crawl.autoload(1);
		return false;
	},
	autoload: function (action){
		if($('.ajax:not(.loaded)').length){
			$('.ajax:not(.loaded)').each((_i, _elem) => {
				var url = $(_elem).data('url'),
					gId = $(_elem).getAttr('gId', ""),
					_parent = $(_elem).closest(".card_load_time"),
					time_type = $('input[name=time_type]',_parent).val(),
					$_adata = $(_elem).data('options') || {};
				if($('.search_field').length){
					$('.search_field').each((_i, _elem) => {
						var name = $(_elem).attr('name');
						$_adata[name] = $(_elem).val();
					});
				}
				$_adata['gId'] = gId;
				$_adata['time_type'] = time_type;
				if(action == 1){
					$Core.util.toggleIndicatior(1);
				}
				$.post(url, $_adata, function(respJson){
					$(_elem).addClass('loaded').html(respJson.html);
					if(action == 1){
						$Core.util.toggleIndicatior(0);
					}
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
	},
	do_search : function(_this, e){
		$Core.ops_cost.list({"type":"list"}, false);
	},	
	open_help: function(_this, e){
		e.preventDefault();
		$.post(PCMS_URL+'/index.php?mod=crawl&act=open_help', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
		return false;
	},
	reload: (_this, e) => {
		$Core.util.toggleIndicatior(1);
		var options = $(_this).data("options") || {},
			gId = $(_this).attr('gId'),
			name = $(_this).attr('name');
		var params = $.extend(options, {'gId':gId});
		if($('select[gId='+gId+'],input:not([type=radio]):not([type=checkbox])[gId='+gId+']').length){
			$('select[gId='+gId+'],input:not([type=radio]):not([type=checkbox])[gId='+gId+']').each((_i, _elem) => {
				var p_name = $(_elem).attr('name'),
					p_value = $(_elem).val();
				if(!$Core.util.isEmptyZero(p_value)){
					params[p_name] = p_value;
				}
			});
		}
		if($(`input[type=radio][gId=${gId}]`).length){
			$(`input[type=radio][gId=${gId}]`).each((_i, _elem) => {
				var p_name = $(_elem).attr('name'),
					p_value = $(`input[name=${p_name}]:checked`).val();
				params[p_name] = p_value;
			});
		}
		$('.ajax[gId='+gId+']').data('options',params);
		$('.ajax[gId='+gId+']').removeClass('loaded');
		$Core.crawl.autoload(0);
		setTimeout(function(){
			$Core.util.toggleIndicatior(0);
		},300);
	}
}
