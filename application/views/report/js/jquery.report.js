$(function(){

	$Core.report.init();

	$Core.report._autoload();

	/* Page Tổng quan Check-in: init riêng khi có holder */
	if($('#holder_checkin_overview').length){ $Core.report_checkin.init(); }

});

$Core.report = {

	_autoload: () => {

		if(ACT != "report_stock_lowfloor" && ACT != "report_group" && ACT != "stock_sold" && ACT != "report_checkin") {

			$Core.report.load_content_share({});

		}

		if($('.ajax:not(.loaded)').length){

			$('.ajax:not(.loaded)').each((_i, _elem) => {

				var url = $(_elem).data('url'),

					$_adata = $(_elem).data('options') || {};

				if($(_elem).hasClass("search_private")) {

					var _group_search = $(_elem).closest(".group_search");

					if($('.search_box',_group_search).length){

						$('.search_box',_group_search).each((_i, _elem) => {

							var field = $(_elem).data('field');

							$_adata[field] = $(_elem).val();

						});

					}

				}else if($(_elem).hasClass("search_group")) { 

					if($('.search_box').length){

						$('.search_box').each((_i, _elem) => {

							var field = $(_elem).data('field');

							$_adata[field] = $(_elem).val();

						});

					}

				}else if($(_elem).hasClass("search_KPI")) { 

					var sort_by, sort_type = '';

					if($('.sortable').length){

						$('.sortable').each((__i, __elem) => {

							if($(__elem).hasClass('asc') || $(__elem).hasClass('desc')){

								sort_by = $(__elem).data('field');

								sort_type = $(__elem).hasClass('desc') ? 'desc' : 'asc';

								$_adata['sort_by'] = sort_by;

								$_adata['sort_type'] = sort_type;

								return false;

							}

						});

					}

					if($('.search_field').length){

						$('.search_field').each((_i, _elem) => {

							var field = $(_elem).data('field');

							$_adata[field] = $(_elem).val();

						});

					}

				} else{

					if($('.search_field').length){

						$('.search_field').each((_i, _elem) => {

							var field = $(_elem).data('field');

							$_adata[field] = $(_elem).val();

						});

					}

				}

				if($(_elem).hasClass('report_sales_group')){

					if($('.search_sales_group_field').length){

						$('.search_sales_group_field').each((_i, _elem) => {

							var field = $(_elem).data('field');

							$_adata[field] = $(_elem).val();

						});

					}

				}

				if($('.search_global').length){

					$('.search_global').each((_i, _elem) => {

						var field = $(_elem).data('field');

						$_adata[field] = $(_elem).val();

					});

				}

				if($(_elem).hasClass('share_group') ){

					if($_adata["department_id"] == 0) {

						$(_elem).closest(".box_share_group").addClass("d-none");

						return false;

					}else{

						$(_elem).closest(".box_share_group").removeClass("d-none");

					}					

				}

				if($Core.util.isEmpty(url)) return false;

				$.post(url, $_adata, function(respJson){

					$(_elem).addClass('loaded').html(respJson.html);

					if($(_elem).hasClass('report_briefs')){

						$('.report_targets').html(respJson.html_targets);

					}

					if($(_elem).hasClass('load_sfs_report')){

						if($(".table-sort").length){

							$(".table-sort").tableSortable({

								cmp:(a,b) => $Core.util.toNumber(a) < $Core.util.toNumber(b) ? -1 : 1

							});

							setTimeout(() => {

								$('.js__th-sort-clickable').trigger('click').trigger('click');

							}, 1000);

						}

					}

					if($(_elem).hasClass('load_title_page') && respJson.title_page != ""){

						var toTitle = $(_elem).attr("toTitle");

						$("#"+toTitle).text(respJson.title_page);

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

					if($(_elem).hasClass("search_KPI") && $(".txt_time").length > 0 && respJson.txt_time != ""){

						$(".txt_time").text(respJson.txt_time);

						$(".title_box_KPI").html(respJson.title_page);

					}

				},'json');

			});

		}

	}, init: () => {

		if(MOD=='report' && ACT == 'billing'){

			$Core.report.load_report_billings({});

		} else if(MOD=='report' && ACT == 'stock_resource'){

			$Core.report.load_stock_resource_logs({});

		} else if(MOD=='report' && ACT == 'report_group' && 1==2){

			$Core.report.load_calendar('_desktop');

		} else if(MOD=='report' && ACT == 'sales_has_trans'){

			$Core.report.load_report_sales_has_trans('_desktop');

		} else if(MOD == 'report' && ACT == 'top_sales'){

			$Core.report.load_report_top_sales();

		} else if(MOD == 'report' && ACT == 'report_department'){

			$Core.report.load_content_share({});

		}

	}, set_month: (_this, e) => {

		e.preventDefault();

		var tp = $(_this).attr('tp'),

			uid = $(_this).attr('uid');

		$.post(PCMS_URL+'/index.php?mod=kpi&act=set_month', {

			'tp' : tp,

			'month' : $('#'+uid).val()

		}, function(html){

			$Core.util.toggleIndicatior(0);

			$('#'+uid).val(html).trigger('change');

		});

		return false;

	}, select_month: (_this, e) => {

		var month = $(_this).val();

		$Core.report.load_content_worktime({'month':month});

	}, toNumber: (num) => {

		num = num.replaceAll('.','');

		num = num.replaceAll(',','');

		num = num.replaceAll('%','');

		console.log(num);

		return Number(num);

	}, load_content_worktime: (options) => {

		var $_adata = options || {},

			sort_type = $('input[name=sort_type]').val();

		$_adata['sort_type'] = sort_type;

		$Core.util.toggleIndicatior(1);

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_content_worktime', $_adata, function(html){

			$Core.util.toggleIndicatior(0);

			$('#'+'holder_worktime').html(html);

		});

	}, do_sort: (_this, e) => {

		e.preventDefault();

		var sort_type = 'desc';

		if(MOD == 'report'){

			if(ACT  == 'report_department' || ACT == 'report_group'){

				if($(_this).hasClass('desc')){

					$(_this).removeClass('desc').addClass('asc');

				} else if($(_this).hasClass('asc')) {

					$(_this).removeClass('asc').addClass('desc');

				} else {

					$(_this).addClass('desc');

				}

				$(`.sortable`).not($(_this)).removeClass('asc').removeClass('desc');

				var _elem = (ACT == 'report_department') ? 'search_KPI' : 'report_sales_group';

				$('.'+_elem).removeClass("loaded");

				$Core.report._autoload();

			} else if(ACT == 'report_agent') {

				if($(_this).hasClass('desc')){

					$(_this).removeClass('desc').addClass('asc');

				} else if($(_this).hasClass('asc')) {

					$(_this).removeClass('asc').addClass('desc');

				} else {

					$(_this).addClass('desc');

				}

				$(`.sortable`).not($(_this)).removeClass('asc').removeClass('desc');

				$Core.report.load_report_agent();

			}

		} else {

			$('input[name=sort_type]').val(sort_type);

			$Core.report.load_content_worktime();	

		}

		return false;

	}, toggleRow: (_this, e) => {

		e.preventDefault();

		var toId = $(_this).attr('toId');

		$('.open').removeClass('open');

		$('.kZnqTyguZE').addClass('d-none');

		$(_this).addClass('open');

		$('#'+toId).removeClass('d-none');

		return false;

	}, load_content_share: (options) => {

		var $_adata = options || {};

		if(MOD == 'report' && ACT == 'report_department'){

			if($('.search_field_dep').length){

				$('.search_field_dep').each((_i, _elem) => {

					var field = $(_elem).data('field');

					$_adata[field] = $(_elem).val();

				});

			}

			if($('.search_global').length){

				$('.search_global').each((_i, _elem) => {

					var field = $(_elem).data('field');

					$_adata[field] = $(_elem).val();

				});

			}

		}else{

			if($('.search_field').length){

				$('.search_field').each((_i, _elem) => {

					var field = $(_elem).data('field');

					$_adata[field] = $(_elem).val();

				});

			}

		}

		$Core.util.toggleIndicatior(1);

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_content_share', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('#holder_report_share').html(respJson.html);

			var doubleClick = false;

			if($(".table-sort").length){

				$(".table-sort").tableSortable({

					cmp:(a,b) => $Core.report.toNumber(a) < $Core.report.toNumber(b) ? -1 : 1

				});

			}

		},"json");

	}, load_report_share: (options) => {

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				$_adata[field] = $(_elem).val();

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_share', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('#holder_report_chart').html(respJson.html);

		},"json");

	}, do_share_search : (_this, e) => {

		e.preventDefault();

		$Core.report.load_content_share({});

		$Core.report.load_report_share({});

		return false;

	}, load_report_sales: (options) => {

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				$_adata[field] = $(_elem).val();

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_sales', $_adata, function(html){

			$Core.util.toggleIndicatior(0);

			$('#holder_report_sales').html(html);

			if($(".table-sort").length){

				$(".table-sort").tableSortable({

					cmp:(a,b) => $Core.report.toNumber(a) < $Core.report.toNumber(b) ? -1 : 1

				});

			}

		});

	}, load_report_target_sales: (options) => {

		var $_adata = options || {};

		if(MOD == 'report' && ACT == 'report_department'){

			if($('.search_field_dep').length){

				$('.search_field_dep').each((_i, _elem) => {

					var field = $(_elem).data('field');

					$_adata[field] = $(_elem).val();

				});

			}

		}else{

			if($('.search_field').length){

				$('.search_field').each((_i, _elem) => {

					var field = $(_elem).data('field');

					$_adata[field] = $(_elem).val();

				});

			}

		}

		$Core.util.toggleIndicatior(1);

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_target_sales', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('#holder_report_share').html(respJson.html);

			var doubleClick = false;

			if($(".table-sort").length){

				$(".table-sort").tableSortable({

					cmp:(a,b) => $Core.report.toNumber(a) < $Core.report.toNumber(b) ? -1 : 1

				});

			}

		},"json");

	}, do_target_search : (_this, e) => {

		e.preventDefault();

		$Core.report.load_report_target_sales({});

		return false;

	}, do_action: (_this, e) => {

		e.preventDefault();

		var stt_color = $(_this).attr('stt_color'),

			billing_type = $(_this).attr('billing_type');

		$('.lst_STATUS_BOOKING').css('background-color','rgb(255,255,255)');

		$Core.report.load_report_sales({'billing_type':billing_type});

		return false;

	}, load_sale_month: (options) => {

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				$_adata[field] = $(_elem).val();

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_sale_month', $_adata, function(html){

			$Core.util.toggleIndicatior(0);

			$('#'+'holder_report_sale_month').html(html);

		});

	},

	/* Thống kê căn bán*/ 

	select_block: (_this, e) => {

		var project_id = $(_this).val(),

			toId = $(_this).attr('toId');

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=select_block', {

			'project_id' : project_id

		}, function(html){

			$Core.util.toggleIndicatior(0);

			if(ACT == "report_price_stock") {

				$('#'+toId).html(html).multiselect('rebuild');

				$('#slb_Building_Id').empty().multiselect('rebuild');

				$Core.report.load_report_price_stock();

			}else{

				$('#'+toId).html(html);

				$('#slb_Building_Id').empty();

				$Core.report.do_stock_search(_this, e);

			}

			

		});

	}, select_building: (_this, e) => {

		var toId = $(_this).attr('toId');

		var is_multi = 0;

		if(ACT == "report_price_stock") {

			is_multi = 1;

		}

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=select_building', {

			'block_id' : $(_this).val(),'is_multi':is_multi

		}, function(html){

			$Core.util.toggleIndicatior(0);

			if(ACT == "report_price_stock") {

				$('#'+toId).html(html).multiselect('rebuild');

				$Core.report.load_report_price_stock();

			}else{

				$('#'+toId).html(html);

				$Core.report.do_stock_search(_this, e);

			}

		});

	}, load_stock_MOC_chart : (type, e) => {

		var $_adata = {'type' : type};

		if(type != 'user_new' 

			&& type != "user_view_stock_sale" 

			&& type != "user_view_stock_fh" 

			&& type != "date_access_log"){

			$Core.util.toggleIndicatior(1);

		}		

		if(type == 'view_moc'){

			$_adata['time_type'] = $("input[name='time_type_view_MOC']:checked").val();	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_stock_MOC_chart', $_adata, function(barChartData){

				$Core.util.toggleIndicatior(0);

				$Core.chart.canvas('chartViewMOC',barChartData);

			}, 'json');

		}else if(type == 'sales_moc'){

			$_adata['time_type'] = $("input[name='time_type_sales_MOC']:checked").val();	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_stock_MOC_chart', $_adata, function(barChartData){

				$Core.util.toggleIndicatior(0);

				$Core.chart.canvas('chartSalesMOC',barChartData);

			}, 'json');

		}else if(type == 'access_user_logs'){

			$_adata['time_type'] = $("input[name='time_top_user_MOC']:checked").val();	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(html){

				$Core.util.toggleIndicatior(0);				

				$("#chartUserTopMOC").html(html);

			});

		}else if(type == 'access_url_logs'){			

			$_adata['month'] = $("select[name='month_top_url']").val();	

			$_adata['year'] = $("select[name='year_top_url']").val();	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#chartPageTopUrl").html(respJson.html);

			}, 'json');

		}else if(type == 'user_new'){

			var $keyword = $("input[name='search_user_new']").val();

			if($keyword != "" && e.keyCode != 13){

				return false;

			}

			$_adata['keyword'] = $keyword;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#listUserNew").html(respJson.html);

			},"json");

		}else if(type == 'user_view_stock_sale'){

			var $keyword = $("input[name='search_user_view_stock_sale']").val();

			if($keyword != "" && e.keyCode != 13){

				return false;

			}

			$_adata['keyword'] = $keyword;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#list_user_view_stock_sale").html(respJson.html);

			},"json");

		}else if(type == 'user_view_stock_fh'){

			var $keyword = $("input[name='search_user_view_stock_fh']").val();

			if($keyword != "" && e.keyCode != 13){

				return false;

			}

			$_adata['keyword'] = $keyword;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#list_user_view_stock_fh").html(respJson.html);

			},"json");

		}else if(type == 'date_access_log'){		

			var $date = $("input[name='date_access_log']").val();

			$_adata['date'] = $date;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#list_date_access_log").html(respJson.html);

			},"json");

		}else if(type == 'date_access_log_url'){		

			var $date = $("input[name='date_access_log_url']").val();

			$_adata['date'] = $date;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$Core.chart.canvas('chartViewAccessLogMOC',respJson.barChartData);

			},"json");

		}else if(type == 'date_access_transaction'){		

			var $date = $("input[name='date_access_transaction']").val();

			$_adata['date'] = $date;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$Core.chart.canvas('chartViewAccessTransactionLogMOC',respJson.barChartData);

			},"json");

		}else if(type == 'list_stock_DQ'){	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#list_stock_DQ").html(respJson.html);

			},"json");

		}else if(type == 'agent'){		

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#list_agent").html(respJson.html);

			},"json");

		} else if(type == 'agent_log'){		

			var $date = $("input[name='date_agent_log']").val();

			$_adata['date'] = $date;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#list_agent_log").html(respJson.html);

			},"json");

		} else if(type=='user_access_MOC'){

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$('#'+'user_access_MOC').html(respJson.html);

				setTimeout(() => {

					$Core.chart.canvas_multi('chartMOC'+respJson.uid,respJson.barChartData);

				}, 100);

			},"json");

		} else if(type=='service_MOC'){

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MOC', $_adata, function(respJson){

				$('#'+'service_MOC').html(respJson.html);

				setTimeout(() => {

					$Core.chart.canvas_multi('chartServiceMOC'+respJson.uid,respJson.barChartData);

				}, 100);

			},"json");

		}

	}, load_report_order: (type, e) => {

		var $_adata = {'type' : type};		

		console.log(type);

		if(type == 'NUMBER_CHART'){

			var parent = $("#loadChartNumber");

			$_adata['month'] = $("select[name='month']",parent).val();

			$_adata['year'] = $("select[name='year']",parent).val();

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_order', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$Core.chart.canvas_multi('chartNumber',respJson.barChartData);

			}, 'json');

		}else if(type == 'REVENUE_CHART') {

			var parent = $("#loadChartRevenue");

			$_adata['month'] = $("select[name='month']",parent).val();

			$_adata['year'] = $("select[name='year']",parent).val();

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_order', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				console.log(respJson.barChartData);

				$Core.chart.canvas('chartRevenue',respJson.barChartData);

			}, 'json');

		}else if(type == 'MEMBER_TRIAL') {

			var parent = $("#loadListTrial");

			var $keyword = $("input[name='keyword']",parent).val();

			console.log($keyword);

			if($keyword != "" && e.keyCode != 13){

				return false;

			}

			$_adata['keyword'] = $keyword;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_order', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#listMemberTrial").html(respJson.html);

			},"json");

		}else if(type == 'MEMBER_PRO') {

			var parent = $("#loadListPro");

			var $keyword = $("input[name='keyword']",parent).val();

			console.log($keyword);

			if($keyword != "" && e.keyCode != 13){

				return false;

			}

			$_adata['keyword'] = $keyword;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_order', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#listMemberPro").html(respJson.html);

			},"json");

		}else if(type == 'MEMBER') {

			var parent = $("#loadListMember");

			var $keyword = $("input[name='keyword']",parent).val();

			var package_id = $("input[name='package_id']:checked",parent).val();

			console.log($keyword);

			if($keyword != "" && e.keyCode != 13){

				return false;

			}

			$_adata['keyword'] = $keyword;	

			$_adata['package_id'] = package_id;	

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_order', $_adata, function(respJson){

				$Core.util.toggleIndicatior(0);

				$("#listMember").html(respJson.html);

			},"json");

		}

	}, load_report_MOC : () => {

		if($('.load_report_MWF').length){

			$('.load_report_MWF').each((_i, _elem) => {

				var $_adata = $(_elem).data('options') || {};

				$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_MWF', $_adata, function(respJson){

					$(_elem).html(respJson.html);

				},"json");

			});

		}

	}, load_report_project : () => {

		if($('#chart_building').length){

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_building_chart', {}, function(respJson){

				$('#chart_building').html(respJson.html);

				// $Core.chart.canvas(respJson.uid,respJson.barChartData);

				eval(respJson.callback);

			}, "json");

		}

	}, load_report_Min_Max : () => {

		if($('.load_report_MWF').length){

			$('.load_report_MWF').each((_i, _elem) => {

				var $_adata = $(_elem).data('options') || {};

				$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_Min_Max', $_adata, function(respJson){

					if(respJson.html == ""){

						$(_elem).closest(".box_building").remove();

					}

					$(_elem).html(respJson.html);

					if(respJson.total_stock > 0){

						$(_elem).closest(".box_building").addClass("order-1");

						$(_elem).closest(".box_building").find(".number_stock").text(`(Còn `+respJson.total_stock+` căn)`);

					}else{

						

						$(_elem).closest(".box_building").addClass("order-2");

					}

					

				},"json");

			});

		}

	}, open_import_logs: (_this, e) => {

		e.preventDefault();

		var agency_id = $(_this).attr('agency_id');

		$Core.util.toggleIndicatior(1);

		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=open_import_logs', {

			'agency_id' : agency_id

		}, function(respJson){

			$Core.util.toggleIndicatior(0);

			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);

		}, 'json');

		return false;

	}, autosearch: (_this, e) => {

		$Core.report.load_report_billings({});

	}, load_month: (_this, e) => {

		var gId = $(_this).attr('gId'),

			year = $(_this).val();

		$.post(PCMS_URL+'/index.php?mod=home&sub=dashboard&act=load_month', {

			'year' : year,

		}, function(html){	

			$('select[name=month][gId='+gId+']').html(html);

			if(ACT == "billing_score") {

				$Core.report.load_report_billings_score({});	

			}else{

				$Core.report.autosearch(_this, e);		

			}

			

		});	

	}, load_report_billings: (options) => {

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var name = $(_elem).attr('name');

				if(typeof(name) !== 'undefined'){

					$_adata[name] = $(_elem).val();

				}

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_billings', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('#'+'holder_report_billings').html(respJson.html);

			$('.sum').html(respJson.sum);

			$('.total_record').text(respJson.total_record);

			if(parseInt(respJson.total_record) > 0){

				$('.freeze-table').freezeTable('update');

			}

		}, 'json');

	}, load_report_billings_score: (options) =>{

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var name = $(_elem).attr('name');

				if(typeof(name) !== 'undefined'){

					$_adata[name] = $(_elem).val();

				}

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_billings_score', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('#'+'holder_report_billings_score').html(respJson.html);

		}, 'json');

		return false;

	}, load_block: (_this, e) => {

		var block_id = $(_this).attr('block_id'),

			toId = $(_this).attr("toId"),

			project_id = $(_this).val();

		$Core.util.toggleIndicatior(1);

		$.post(path_ajax_script+'/index.php?mod=home&act=load_block', {

			'block_id' : block_id,	

			'project_id' : project_id

		}, function(html){

			$Core.util.toggleIndicatior(0);

			if($('#'+toId).hasClass('iso-select2')){

				$('#'+toId).html(html).trigger('change');

			} else {

				$('#'+toId).html(html);

			}

			$Core.report.load_report_billings_score()

		});

	}, load_block_by_type: (_this, e) => {

		var block_type = $(_this).attr('block_type'),

			toId = $(_this).attr("toId"),

			project_id = $(_this).val();

		$Core.util.toggleIndicatior(1);

		$.post(path_ajax_script+'/index.php?mod=home&act=load_block', {

			'block_type' : block_type,	

			'project_id' : project_id

		}, function(html){

			$Core.util.toggleIndicatior(0);

			$('#'+toId).html(html).trigger('change');

		});

	}, do_sr_search: (_this, e) => {

		$Core.report.load_stock_resource_logs({});

	}, load_stock_resource_logs: (options) => {

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var name = $(_elem).attr('name');

				$_adata[name] = $(_elem).val();

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_stock_resource_logs', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('#'+'holder_stock_resource_logs').html(respJson.html);

			$('.total_record').text(respJson.total_record);

			if(parseInt(respJson.total_record) > 0){

				$('#tableReport').freezeTable({

					'columnNum': 1,

					'scrollable': false,

					'columnKeep': true,

				});

			}

		}, 'json');

	}, load_stock_resource_logs_chart : (type, event) => {

		var $_adata = {'type' : type};

		if(type == 'ViewUserSearch') {

			$_adata['date'] = $("input[name='ViewUserSearch']").val();

		}else{

			$_adata['date'] = $("input[name='ViewLogSearch']").val();

		}

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_stock_resource_logs_chart', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			if(type == 'ViewLogSearch'){

				$Core.chart.canvas_multi('chartViewLogSearch',respJson.barChartData);

			}else if(type == 'ViewUserSearch') {

				$Core.chart.canvas_multi('chartViewUserSearch',respJson.barChartData);

			}

		}, 'json');

	}, loadMonth : (_this, event) => {

		var date_type = '_month',

			year = $(_this).val(),

			type = $(_this).data("type"),

			gId  = $(_this).attr("gId");

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_month', {

			'year' : year,

		}, function(html){

			$('select[data-type=MONTH][gId='+gId+']').html(html);

			$Core.report.load_loyalty(type, {}); 

		});

	}, load_loyalty : (type, options) => {

		var $_adata = options || {};

		$_adata["type"] = type;

		if(type == '_TOTAL') {

			var month = $("select[name='month_total']").val();

			var year = $("select[name='year_total']").val();

			$_adata['month'] = month;

			$_adata['year'] = year;			

		}else if(type == '_LIST' || type == '_PAGE') {

			var parent = $("#loadListEmploy");

			var keyword = $("input[name='keyword']",parent).val();

			var department_id = $("select[name='department_id']",parent).val();

			var month = $("select[name='month_list']").val();

			var year = $("select[name='year_list']").val();

			$_adata['month'] = month;

			$_adata['year'] = year;	

			$_adata['keyword'] = keyword;

			$_adata['department_id'] = department_id;

		}

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_list_profile', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			if(type == '_TOTAL') {

				$("#box_depart_point").html(respJson.html);

			}

			if(type == '_TOP') {

				$("#listTopEmploy").html(respJson.html);

			}else if(type == '_LIST' || type == '_PAGE'){

				$("#listEmploy").html(respJson.html);

				$(".number_point").text(respJson.total_record);

				if(parseInt(respJson.total_page) > 1) {

					if(type == '_LIST') {

						$("#loadListEmploy").find("input[name='page']").val(1);

						$('#pagination-container').pagination({

							items: parseInt(respJson.total_page),

							itemsOnPage: respJson.perPage,

							prevText: "&laquo;",

							nextText: "&raquo;",

							currentPage : parseInt(respJson.current_page),

							onPageClick: function (pageNumber) {

								$("#loadListEmploy").find("input[name='page']").val(pageNumber);

								$_adata['page'] = pageNumber;

								$Core.report.load_loyalty("_PAGE",$_adata);

							}

						});

					}					

				}else{

					$('#pagination-container').html("");

				}

			}else if(type == '_PAGE'){

				$("#listEmploy").html(respJson.html);

			}

		}, 'json');

	}, loadUser : (status, event) => {

		var keyword = $("#keySearchUser").val();

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_user', {

			'status' : status,

			'keyword' : keyword,

		}, function(html){

			$('#list_user').html(html);

		});

	}, searchUser : (_this,e) => {

		e.preventDefault();

		var status = $(_this).closest(".head_user").find("input[name='status']:checked").val();

		$Core.report.loadUser(status, event);

	}, activeUser : (_this,e) => {

		e.preventDefault();

		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn xác thực tài khoản này?', function(){

			var member_id = $(_this).data("member_id");

			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=active_user', {

				'member_id' : member_id,

			}, function(respJson){

				if(respJson.result) {

					$Core.report.loadUser('unactive', event);

				}else{

					alertify.error("ERROR");

				}

			},"json");

		});

	}, loadStaff : (_this,e) => {

		e.preventDefault();

		var group_id = $(_this).val(),

			toId = $(_this).attr("toId");

		$.post(PCMS_URL+'/index.php?mod=home&sub=report&act=loadStaffGroup', {

			'group_id' : group_id,

		}, function(html){

			$("#"+toId).html(html);

			$Core.report.do_group_search(_this,e)

		});

	}, do_group_search: (_this, e) => {

		var params = {};

		if($(_this).hasClass('search_sales_group_field')){

			$('.report_sales_group').removeClass('loaded');

			$Core.report._autoload();

		} else {

			if($(_this).hasClass('js__search-year-field') || $(_this).hasClass('js__search-month-field')){

				var _year = $('.js__search-year-field').val(),

					_month = $('.js__search-month-field').val();

				$Core.util.toggleIndicatior(1);

				$.ajax({

					method: "POST",

					url: '/index.php?mod=home&sub=report&act=load_date_range',

					data: {'month':_month,'year':_year},

					dataType: "json",

					async : false,

					success: function(respJson){

						$Core.util.toggleIndicatior(0);

						$('.js__search-date_type-field').val(0);

						$('.js__search-start_date-field').val(respJson.start_date);

						$('.js__search-end_date-field').val(respJson.end_date);

					}

				});

			}

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				params[field] = $(_elem).val();

			});

			if($('.ajax').length){

				$('.ajax').each((_i, _elem) => {

					$(_elem).removeClass('loaded');

				});

				$Core.report._autoload();

			}

		}

	}, do_search_group: (_this, e) => {

		if($(_this).hasClass('js__search-year-field')){

			$Core.util.toggleIndicatior(1);

			$.ajax({

				type : 'POST',

				url : `${PCMS_URL}/index.php?mod=home&sub=dashboard&act=load_month`,

				data : {'date_type' : "_month", 'year' : $(_this).val()},

				async : false,

				dataType : 'html',

				success: function(html){

					$Core.util.toggleIndicatior(0);

					$('.js__search-month-field').html(html);

				}

			});

		}

		if($('.ajax.loaded').length){

			$('.ajax.loaded').each((_i, _elem) => {

				$(_elem).removeClass('loaded');

			});

			$Core.report._autoload();

		}

	}, set_button: (_this, e) => {

		e.preventDefault();

		var tp = $(_this).data('tp');

		$('.apcfHDsTFN').removeClass('active');

		$(_this).addClass('active');

		$Core.report.load_calendar('_desktop','_all');

		return false;

	}, load_calendar: (holderG, typeHoldder='_all') => {

		$Core.util.toggleIndicatior(1);

		var $_adata = {'holderG':holderG,'typeHoldder':typeHoldder},

			group_id = $("select[data-field=group_id]").val(),

			staff_id = $("select[data-field=staff_id]").val();

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_calendar', $_adata, function(html){

			$Core.util.toggleIndicatior(0);

			var oSettings = {}, doubleClick = false;

			if(holderG=='_desktop'){

				$('.desktop_calendar').html(html);

				var _height = (deviceType == 'phone') ? 200 : 295,

					tp = $('.apcfHDsTFN.active').data('tp'),

					query_string = '&tp='+tp;

				if(group_id > 0){

					query_string += '&group_id='+group_id;

				}

				if(staff_id > 0){

					query_string += '&staff_id='+staff_id;

				}

				oSettings = {

					selectable: true,

					unselectAuto: false,

					contentHeight: _height,

					header: {left: '', center: 'title', right: 'prev,next'},

					events:PCMS_URL+"/index.php?mod="+MOD+"&act=load_cell_calendar"+query_string,

					eventRender : function(event, element, view){

						element.find(".fc-event-title").remove();

						if(parseInt(event.number) > 0){

							var html = '<div class="fc-event-badge">'

								+' <span class="badge">'+event.number+'</span>'

							+'</div>';

							element.append(html);

						}

					}, eventClick: function(event, jsEvent, view){

						var date_id = $.fullCalendar.formatDate(event.start, "dd/MM/yyyy");

						if(tp=='customer'){

							$('input[name=reg_date]').val($Core.util.toYMD(date_id));

//							$Core.crm.load_customers('_desktop',{});

						} else {

							$Core.crm.load_desktop_followups({

								'holderG':'_desktop',

								'date_id':date_id,

								'typeHolder':'_calendar'

							});

						}

					}, dayClick: function(date, jsEvent, view){

						var date_id = $.fullCalendar.formatDate(date, "dd/MM/yyyy");

						if(tp=='customer'){

							$('input[name=reg_date]').val($Core.util.toYMD(date_id));

							// $Core.crm.load_customers('_desktop', {});

						} else {

							$Core.crm.load_desktop_followups({

								'holderG':'_desktop',

								'date_id':date_id,

								'typeHolder':'_calendar'

							});

						}

					}

				};

				$('#calendar_desktop').fullCalendar(oSettings);

			} else {

				$('#app').html(html);

				oSettings = {

					header: {

						left: 'prev,next today myCustomButton',

						center: 'title',

						right: 'month,basicWeek,basicDay'

					},

					selectable: true,

					contentHeight: 680,

					events:PCMS_URL+"/index.php?mod=crm&act=load_followups_month&holderG="+holderG+'&typeHoldder='+typeHoldder,

					eventRender : function(event, element, view){

						element.find(".fc-event-title").remove();

						var date_id = $.fullCalendar.formatDate(event.start, "dd-MM-yyyy");

						if(parseInt(event.number) > 0){

							var html = '<div class="fc-event-custom">'

							+'	<span class="badge badge-important">'+event.number+'</span>'

							+'	<div class="fc-event-list">'+event.htmlList+'</div>'

							+'</div>';

							element.append(html);

						}

						element.bind('dblclick', function() {

							$('.fc-event-skin').popover('hide');

							$Core.crm.open_followups(date_id,'popup');

						});

						element.popover({

							trigger: 'click',

							title: event.title+'<button onclick="$(this).closest(\'div.popover\').popover(\'hide\');" type="button" class="close" aria-hidden="true">×</button>',

							content: event.htmlTable,

							width: '300',

							multi: false,

							closeable: true,

							delay: 300,

							placement : function (context, source){

								var position = $(source).position();

								if (position.left > 515) {

									return "left";

								} else if (position.left < 515) {

									return "right";

								} else if (position.top < 110){

									return "bottom";

								} else {

									return "top";

								}

							},

							html : true,

							container: 'body'

						}).on("show.bs.popover", function() {

							$('div.popover').each(function(){

								$(this).popover('hide');

							});

							return $(this).data("bs.popover").tip().css({

								maxWidth: "600px"

							});

						});

					},

					dayClick: function(date, jsEvent, view){

						if(!doubleClick) {

							doubleClick = true;

							setTimeout(() => {

								doubleClick=false;

							}, 500);

						}else {

							var date_id = $.fullCalendar.formatDate(date, "dd-MM-yyyy");

							$Core.crm.open_followups(date_id,'popup');

						}

					}

				};

				$('#calendar').fullCalendar(oSettings);

			}

		});

	}, load_activity_log: (options) => {

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				$_adata[field] = $(_elem).val();

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post('/index.php?mod='+MOD+'&act=load_activity_log', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('.holder_reports_activity_log').html(respJson.html);

			$('.js__search-tbl-field').html(respJson.html_options);

			$('#pager').html("");

			if(parseInt(respJson.total_page) > 1){

				$('#pager').pagination({

					listStyle:"pagination justify-content-center flex-wrap",

					items: respJson.total_record,

					displayedPages: 3,

					itemsOnPage: respJson.per_page,

					currentPage: respJson.current_page, 

					cssStyle: 'light-theme',

					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',

					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',

					onPageClick : function(pageNumber){

						$Core.report.load_activity_log($.extend(options, {'page':pageNumber}));

					}

				});

			}

		},'json');

	}, load_report_price_stock: (options) => {

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				$_adata[field] = $(_elem).val();

			});

		}

		$.post('/index.php?mod='+MOD+'&act=load_report_price_stock', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('.holder_reports_price_stock').html(respJson.html);

		},'json');

	}, do_change: (_this, e) => {

		var field = $(_this).data('field'),

			call_from = $(_this).attr('call_from');

		if(field== 'date_type'){

			var year = $('.slb_Year').val();

			$.post('/index.php?mod=home&sub=dashboard&act=load_month', {

				'year' : year,

				'date_type' : $(_this).val()

			}, function(html){

				$Core.util.toggleIndicatior(0);

				$('.slb_Month').html(html);

			});

		} else if(field=='year'){

			var date_type = $('select[name=date_type]').val();

			$.post('/index.php?mod=home&sub=dashboard&act=load_month', {

				'date_type' : date_type,

				'year' : $(_this).val()

			}, function(html){

				$Core.util.toggleIndicatior(0);

				$('.slb_Month').html(html);

			});

		}

		clearTimeout(_timeOut);

		_timeOut = setTimeout(() => {

			if(call_from == 'top_sales'){

				$Core.report.load_report_top_sales();

			} else {

				$Core.report.load_report_sales_has_trans();

			}

		}, 500);

	}, load_report_top_sales : (options) => {

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				$_adata[field] = $(_elem).val();

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post('/index.php?mod='+MOD+'&act=load_report_top_sales', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('.holder_report_top_sales').html(respJson.html);

		},'json');

	}, load_report_sales_has_trans: (options) => {

		var $_adata = options || {};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				$_adata[field] = $(_elem).val();

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post('/index.php?mod='+MOD+'&act=load_report_sales_has_trans', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('.holder_reports_sales_has_trans').html(respJson.html);

		},'json');

	}, reload: (_this, e) => {

		var options = {},

			gId = $(_this).attr('gId'),

			group_id = $(_this).getAttr('group_id', ""),

			name = $(_this).attr('name');

		if(name== 'date_type'){

			var date_type = $(_this).val(),

				year = $('select[name=year][gId='+gId+']').val();

			$.post(PCMS_URL+'/index.php?mod=home&sub=dashboard&act=load_month', {

				'date_type' : date_type,'year':year

			}, function(html){

				$('select[name=month][gId='+gId+']').html(html);

				var params = $.extend(options, {'gId':gId});

				if($('select[gId='+gId+'],input[gId='+gId+']').length){

					$('select[gId='+gId+'],input[gId='+gId+']').each((_i, _elem) => {

						var p_name = $(_elem).attr('name'),

							p_value = $(_elem).val();

						if(!$Core.util.isEmptyZero(p_value)){

							params[p_name] = p_value;

						}

					});

				}

				$('.ajax[gId='+gId+']').data('options',params);

				$('.ajax[gId='+gId+']').removeClass('loaded');

				$Core.report._autoload();

			});

		} else if(name== 'year'){

			var date_type = '_month',

				year = $(_this).val();

			if($('select[name=date_type][gId='+gId+']').length){

				date_type = $('select[name=date_type][gId='+gId+']').val();

			}

			if(date_type == '_quater' || date_type == '_half'){

				var params = $.extend(options, {'gId':gId});

				if($('select[gId='+gId+'],input[gId='+gId+']').length){

					$('select[gId='+gId+'],input[gId='+gId+']').each((_i, _elem) => {

						var p_name = $(_elem).attr('name'),

							p_value = $(_elem).val();

						if(!$Core.util.isEmptyZero(p_value)){

							params[p_name] = p_value;

						}

					});

				}

				$('.ajax[gId='+gId+']').data('options',params);

				$('.ajax[gId='+gId+']').removeClass('loaded');

				$Core.report._autoload();

			} else {

				$.post(PCMS_URL+'/index.php?mod=home&sub=dashboard&act=load_month', {

					'date_type' : date_type,

					'year' : year,

				}, function(html){

					$('select[name=month][gId='+gId+']').html(html);

					if($('select[name=quarter][gId='+gId+']').length) {

						$('select[name=quarter][gId='+gId+']').val("");	

					}					

					var params = $.extend(options, {'gId':gId});

					if($('select[gId='+gId+'],input[gId='+gId+']').length){

						$('select[gId='+gId+'],input[gId='+gId+']').each((_i, _elem) => {

							var p_name = $(_elem).attr('name'),

								p_value = $(_elem).val();

							if(!$Core.util.isEmptyZero(p_value)){

								params[p_name] = p_value;

							}

						});

					}

					$('.ajax[gId='+gId+']').data('options',params);

					$('.ajax[gId='+gId+']').removeClass('loaded');

					$Core.report._autoload();

				});

			}

		} else {

			var params = $.extend(options, {'gId':gId});

			if($(_this).hasClass('js__handle-department')){

				var department_id = $(_this).val(),

					date_id = $('.js__input-report-date').val(),

					s_params = {'department_id':department_id};

				$('.js__block-report-today').data('options',s_params).removeClass('loaded');

			}

			if($('select[gId='+gId+'],input[type=text][gId='+gId+']').length){

				$('select[gId='+gId+'],input[type=text][gId='+gId+']').each((_i, _elem) => {

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

			if($(_this).closest(".group_search").length > 0) {

				var _group_search = $(_this).closest(".group_search");

				if($('.search_box',_group_search).length){

					$('.search_box',_group_search).each((_i, _elem) => {

						var field = $(_elem).data('field');

						params[field] = $(_elem).val();

					});

				}

			}

			$('.ajax[gId='+gId+']').data('options',params);

			$('.ajax[gId='+gId+']').removeClass('loaded');

			$Core.report._autoload();

		}

	}, view_stock_dq: (_this, e) => {

		e.preventDefault();

		var block_id = $(_this).attr('block_id');

		$Core.util.toggleIndicatior(1);

		$.post(path_ajax_script+'/index.php?mod=report&sub=project&act=view_stock_dq', {

			'block_id' : block_id

		}, function(respJson){

			$Core.util.toggleIndicatior(0);

			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);

		}, 'json');

		return false;

	}, getCurrentWeek: () => {

        const date = new Date();

        // ISO week calculation

        date.setHours(0, 0, 0, 0);

        date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);

        const week1 = new Date(date.getFullYear(), 0, 4);

        const weekNumber = 1 + Math.round(((date - week1) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7 );

        const year = date.getFullYear();

        return year + '-W' + String(weekNumber).padStart(2, '0');

    }, handle_booking_changed: (_this, e) => {

		var _gId = $(_this).attr('gId'),

			_field = $(_this).data('field'),

			_value = $(_this).val();

		if(_field.indexOf('date_type') >= 0){

			var today = new Date();

			if(_value == 'date'){

				var day = $Core.util.plz(today.getDate()),

					month = today.getMonth() + 1,

					year = today.getFullYear();

				$('.js__booking_date').replaceWith(`<input gId="${_gId}" type="date" value="${year}-${month}-${day}" 

					class="form-control js__booking_date search_field" data-field="date" onChange="$Core.report.do_share_search(this, event)" />`).trigger('change');

			} else if(_value == 'month'){

				var month = $Core.util.plz(today.getMonth() + 1),

					year = today.getFullYear();

				$('.js__booking_date').replaceWith(`<input gId="${_gId}" type="month" value="${year}-${month}" data-field="month" class="form-control js__booking_date search_field" onChange="$Core.report.do_share_search(this, event)" />`).trigger('change');

			} else if(_value == 'week'){

				var _wk = $Core.report.getCurrentWeek();

				$('.js__booking_date').replaceWith(`<input gId="${_gId}" type="week" value="${_wk}" data-field="week" class="form-control js__booking_date search_field" onChange="$Core.report.do_share_search(this, event)"/>`).trigger('change');

			} else if(_value == 'year'){

				var _start_year = 2023, 

					_end_year = today.getFullYear(),

					html_select = `<select gId="${_gId}" class="form-control form-select js__booking_date search_field" data-field="year" onChange="$Core.report.do_share_search(this, event)">`;

				for(var i = _start_year; i <= _end_year; i++){

					html_select += `<option${i==_end_year ? ' selected':''} value="${i}">Năm ${i}</option>`;

				}

				html_select += `</select>`;

				$('.js__booking_date').replaceWith(html_select).trigger('change');

			}

		} else if(_field.indexOf('project_id') >= 0){

			$.post(PCMS_URL+'/index.php?mod=ajax&act=load_block', {

				'project_id' : _value

			}, function(respJson){

				var $_select = $(`select[gId=${_gId}][name=block_id]`).selectize(),

					$_selectize = $_select[0].selectize;

				$_selectize.clear();

				$_selectize.clearOptions();

				$_selectize.addOption(respJson);

				$_selectize.refreshOptions(false);

				$_selectize.trigger( "change" );

			}, 'json');

		} else {

			var params = {};

			if(_field.indexOf('role_type') >= 0 && $(_this).attr('is_dir_project') == 1) {

				var role_type = $(`input[name=role_type][gId=${_gId}]:checked`).val();

				$Core.util.toggleIndicatior(1);

				$.post(PCMS_URL+'/index.php?mod=booking&act=load_project_dir', {

					'role_type' : role_type

				}, function(respJson){

					$Core.util.toggleIndicatior(0);

					var $_select_project = $(`select[name=project_id][gId=${_gId}]`).selectize(),

						$_selectize_project = $_select_project[0].selectize;

					$_selectize_project.clear();

					$_selectize_project.clearOptions();

					$_selectize_project.addOption(respJson.arr_projects);

					$_selectize_project.refreshOptions(false);

					if(typeof(respJson.project_id) !== 'undefined' && parseInt(respJson.project_id, 10) > 0){

						$_selectize_project.addItem(respJson.project_id);

					}

				}, 'json');

			}

			if($(`.search_field[gId=${_gId}]`).length){

				$(`.search_field[gId=${_gId}]`).each((_i, _elem) => {

					var _field = $(_elem).data('field');

					if(_field=='date_type' || _field == 'role_type'){

						params[_field] = $(`[data-field=${_field}]:checked`).val();

					} else {

						params[_field] = $(_elem).val();

					}

				});

			}

			$(`.ajax[gId=${_gId}]`).data('options', params).removeClass('loaded');

			_autoload();

		}

	}, load_share_waiting: (_this, e) => {

		e.preventDefault();

		e.stopPropagation();

		var $_adata = { 'is_report' : 1};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				$_adata[field] = $(_elem).val();

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post(path_ajax_script+'/index.php?mod=home&act=load_share_waiting', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			var _www = $(window).width();

			$Core.popup.openfull(_www-480,respJson.html,respJson.uid);

			$('.modal-backdrop').remove();

			$Core.crm.load_activity(customer_id, {});

		}, 'json');

		return false;

	}, open_share: (_this, e) => {

		e.preventDefault();

		var share_id = $(_this).attr('share_id');

		$Core.util.toggleIndicatior(1);

		$.post('/index.php?mod=home&act=open_share_detail', {

			'share_id' : share_id

		}, function(respJson){

			$Core.util.toggleIndicatior(0);

			$Core.popup.open('auto','auto',respJson.html,respJson.uid);

			$('#'+respJson.uid).on('shown.bs.modal', function(){

				var _modal = $(this);

				$('#gallery-grid-'+share_id,_modal).imagesGrid({

					cells:4,

					images: respJson.list_images,

					align: true

				});

			});

		}, 'json');

		return false;

	}, view_staff : (_this, e) => {

		var $_adata = $(_this).data('options') || {};

		$Core.util.toggleIndicatior(1);

		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=view_staff', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);

		}, 'json');

	}, load_pop_stock: (_this, e) => {

		e.preventDefault();

		var DT_TT = $(_this).attr("DT_TT"),

			type_id = $(_this).attr("type_id"),

			$_adata = {'DT_TT':DT_TT,'type_id':type_id};

		if($('.search_field').length){

			$('.search_field').each((_i, _elem) => {

				var field = $(_elem).data('field');

				$_adata[field] = $(_elem).val();

			});

		}

		$Core.util.toggleIndicatior(1);

		$.post(path_ajax_script+'/index.php?mod='+MOD+'&sub=project&act=load_pop_stock', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			if(respJson.html != "") {

				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);

			}else{

		

			}

		}, 'json');

		return false;

	}, _do : (_this, e) => {

		e.preventDefault();

		var _field = $(_this).data('field');

		if(_field == 'year'){

			$.post(path_ajax_script+'/index.php?mod=home&sub=dashboard&act=load_month', {

				'date_type' : '_month',

				'year' : $(_this).val()

			}, function(html){

				$('#'+'slb_Month').html(html);

			});

		}

		$Core.report.load_sales_agent({});

	}, load_sales_agent : (options) => {

		var $_adata = options || {};

		$('.search_field').each((_i, _elem) => {

			var _field = $(_elem).data('field');

			$_adata[_field] = $(_elem).val();

		});

		$Core.util.toggleIndicatior(1);

		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_sales_agent', $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$('.holder_sales_agent').html(respJson.html);

		}, 'json');

	}, load_sold_stock_7days : (options) => {

		var $_adata = options || {};

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_sold_stock_7days', $_adata, function(respJson){

			$('.holder_chart_stock_sold_7days').html(respJson.html);

			if(respJson.callback) eval(respJson.callback);

			$Core.chart.canvas(respJson.uid, respJson.barChartData);

		}, 'json');

	}, load_report_agent: (options) => {

		var sort_by, sort_type = '',

			$_adata = options || {};

		if($('.sortable').length){

			$('.sortable').each((__i, __elem) => {

				if($(__elem).hasClass('asc') || $(__elem).hasClass('desc')){

					sort_by = $(__elem).data('field');

					sort_type = $(__elem).hasClass('desc') ? 'desc' : 'asc';

					$_adata['sort_by'] = sort_by;

					$_adata['sort_type'] = sort_type;

					return false;

				}

			});

		}

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_report_agent', $_adata, function(respJson){

			$('.holder_report_agent').html(respJson.html);

			if(respJson.callback) eval(respJson.callback);

		}, 'json');

	},

	load_report_stock_sold : ()	=> 	{

		var $_adata = {};

		$(".search_field").each(function(index, elm){

			let name = $(elm).attr("name"),

				_value = $(elm).val();

			$_adata[name] = _value;

		});

		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub='+SUB+'&act=load_report_stock_sold', $_adata, function(respJson){

			$('#list_stock_sold').html(respJson.html);

			$(".total-record").text(respJson.total)

		}, 'json');

	},

	load_search_project : ()	=> 	{
		var $_adata = {};
		$(".search_field").each(function(index, elm){
			let name = $(elm).attr("name"),
				_value = $(elm).val();
			$_adata[name] = _value;
		});
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_search_project', $_adata, function(respJson){
			$('.holder_search_project').html(respJson.html);
		}, 'json');

	},

	/* Check-in overview → xem $Core.report_checkin (namespace riêng bên dưới) */

}

/* ==================== Report Check-in (page report_checkin) — toàn bộ JS của page ==================== */
$Core.report_checkin = {

	_donutChart: null,
	_donutScope: 'office',
	_DONUT_COLORS: ['#696cff','#03c3ec','#71dd37','#ffab00','#ff3e1d','#8592a3','#a8b1bb'],
	_regionMap: {},

	/* Khởi tạo page (gọi 1 lần khi DOM ready nếu có #holder_checkin_overview) */
	init: function(){
		var self = $Core.report_checkin;

		/* Map vùng→phòng cho cascading (DIRECTOR) */
		try {
			var _rm = document.getElementById('checkin_region_map');
			self._regionMap = JSON.parse((_rm && _rm.textContent) || '{}') || {};
		} catch(e){ self._regionMap = {}; }

		var drLocale = {
			format: 'DD/MM/YYYY', applyLabel: 'Áp dụng', cancelLabel: 'Hủy',
			daysOfWeek: ['CN','T2','T3','T4','T5','T6','T7'],
			monthNames: ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12']
		};

		/* daterangepicker desktop.
		   LƯU Ý: daterangepicker gọi callback TRƯỚC updateElement (hide(): callback→updateElement),
		   nên đọc .val() trong callback sẽ ra giá trị CŨ (today). Phải tự set input từ start/end. */
		if($('#checkin_daterange').length && $.fn.daterangepicker){
			$('#checkin_daterange').daterangepicker({
				locale: drLocale, startDate: moment(), endDate: moment(), maxDate: moment()
			}, function(start, end){
				$('#checkin_daterange').val(start.format(drLocale.format) + ' - ' + end.format(drLocale.format));
				self.load_all({});
			});
		}
		/* daterangepicker mobile → sync value sang desktop để collect dùng (cũng set từ start/end) */
		if($('#checkin_daterange_mobile').length && $.fn.daterangepicker){
			$('#checkin_daterange_mobile').daterangepicker({
				locale: drLocale, startDate: moment(), endDate: moment(), maxDate: moment()
			}, function(start, end){
				var v = start.format(drLocale.format) + ' - ' + end.format(drLocale.format);
				$('#checkin_daterange_mobile').val(v); $('#checkin_daterange').val(v);
			});
		}

		/* Cascading Vùng → Phòng (DIRECTOR) */
		$('#checkin_region_filter').on('change', function(){
			self.fill_dept($('#checkin_dept_filter'), $(this).val());
			self.load_all({});
		});
		$('#checkin_region_filter_mobile').on('change', function(){
			self.fill_dept($('#checkin_dept_filter_mobile'), $(this).val());
		});

		/* Filter change (desktop) */
		$('#checkin_dept_filter, #checkin_group_filter').on('change', function(){ self.load_all({}); });

		/* Load lần đầu */
		self.load_overview({});
		self.load_region({});
		self.load_list({});
	},

	/* Nạp options Phòng theo Vùng đã chọn */
	fill_dept: function($sel, regionId){
		var html = '<option value="0">-- Tất cả phòng --</option>';
		var list = $Core.report_checkin._regionMap[regionId] || [];
		for(var i=0;i<list.length;i++){ html += '<option value="'+list[i].id+'">'+list[i].title+'</option>'; }
		$sel.html(html).val('0');
	},

	/* Thu thập filter từ form */
	collect_filters: function(){
		var drDesktop = $('#checkin_daterange').val() || '';
		var drMobile  = $('#checkin_daterange_mobile').val() || '';
		var date_range = drDesktop || drMobile;

		/* Select default value="0" là chuỗi truthy → KHÔNG dùng ||; chọn giá trị >0 (desktop>0 else mobile).
		   Phòng ưu tiên; không chọn phòng thì lấy Vùng (handler tự mở rộng cả subtree). */
		var _pd = parseInt($('#checkin_dept_filter').val() || 0, 10), _pm = parseInt($('#checkin_dept_filter_mobile').val() || 0, 10);
		var _rd = parseInt($('#checkin_region_filter').val() || 0, 10), _rm = parseInt($('#checkin_region_filter_mobile').val() || 0, 10);
		var dept_id = _pd > 0 ? _pd : (_pm > 0 ? _pm : (_rd > 0 ? _rd : (_rm > 0 ? _rm : 0)));
		var _gd = parseInt($('#checkin_group_filter').val() || 0, 10), _gm = parseInt($('#checkin_group_filter_mobile').val() || 0, 10);
		var group_id = _gd > 0 ? _gd : (_gm > 0 ? _gm : 0);

		return { date_range: date_range, department_id: dept_id, group_id: group_id };
	},

	/* Load KPI + donut + Top hoạt động */
	load_overview: function(options){
		var self = $Core.report_checkin;
		var $_adata = $.extend({}, self.collect_filters(), options || {});
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=report&act=load_checkin_overview', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$('#holder_checkin_overview').html(html);
			/* Tách donut+Top xuống holder riêng (dưới bảng Vùng): overview giữ KPI, chart → #holder_checkin_chart */
			var $chart = $('#holder_checkin_overview').find('#checkin_chart_block');
			if($chart.length){ $('#holder_checkin_chart').empty().append($chart); }
			self.init_donut();
		});
	},

	/* Load Check-in mới nhất (full list) */
	load_list: function(options){
		var self = $Core.report_checkin;
		var $_adata = $.extend({}, self.collect_filters(), options || {});
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=report&act=load_checkin_list', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$('#holder_checkin_list').html(html);
		});
	},

	/* Load bảng Theo dõi Check-in theo Vùng/đơn vị */
	load_region: function(options){
		var self = $Core.report_checkin;
		var $_adata = $.extend({}, self.collect_filters(), options || {});
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=report&act=load_checkin_region', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$('#holder_checkin_region').html(html);
		});
	},

	/* Reload tất cả (filter change) */
	load_all: function(options){
		$Core.report_checkin.load_overview(options || {});
		$Core.report_checkin.load_region(options || {});
		$Core.report_checkin.load_list(options || {});
	},

	/* ---------- Donut (ApexCharts) ---------- */

	_parseDonutData: function(scopeId){
		var el = document.getElementById('donut_data_' + scopeId);
		if(!el){ return {labels:[], series:[], percents:[]}; }
		try { return JSON.parse(el.textContent || el.innerHTML) || {labels:[], series:[], percents:[]}; }
		catch(e){ return {labels:[], series:[], percents:[]}; }
	},

	_buildColors: function(data){
		var C = $Core.report_checkin._DONUT_COLORS, result = [];
		for(var i=0; i<data.series.length; i++){
			result.push((data.colors && data.colors[i]) ? data.colors[i] : C[i % C.length]);
		}
		return result;
	},

	_renderLegend: function(data, colors){
		var C = $Core.report_checkin._DONUT_COLORS, html = '';
		for(var i=0; i<data.labels.length; i++){
			var color = colors[i] || C[i % C.length];
			html += '<div class="d-flex align-items-center justify-content-between gap-2 mb-2">' +
				'<div class="d-flex align-items-center gap-2">' +
				'<span class="rounded-pill" style="width:12px;height:12px;background:'+color+';display:inline-block"></span>' +
				'<span class="text-truncate" style="max-width:160px" title="'+data.labels[i]+'">'+data.labels[i]+'</span>' +
				'</div>' +
				'<span class="fw-semibold text-nowrap">'+data.series[i]+' ('+(data.percents[i]||0)+'%)</span>' +
				'</div>';
		}
		$('#checkin_donut_legend').html(html || '<span class="text-muted">Chưa có dữ liệu</span>');
	},

	render_donut: function(scope){
		var self = $Core.report_checkin;
		var data = self._parseDonutData(scope);
		var el   = document.getElementById('checkin_donut_chart');
		if(!el) return;
		if(self._donutChart){ try{ self._donutChart.destroy(); }catch(e){} self._donutChart = null; }
		if(!data.series || data.series.length === 0){
			$(el).html('<div class="text-center text-muted py-4">Chưa có dữ liệu</div>');
			$('#checkin_donut_legend').html('');
			return;
		}
		var colors = self._buildColors(data);
		var opts = {
			chart: { type: 'donut', height: 280, toolbar: { show: false } },
			series: data.series, labels: data.labels, colors: colors,
			legend: { show: false },
			dataLabels: { enabled: true, formatter: function(val){ return val.toFixed(1)+'%'; } },
			tooltip: { y: { formatter: function(v){ return v + ' lượt'; } } },
			plotOptions: { pie: { donut: { size: '65%' } } },
			responsive: [{ breakpoint: 480, options: { chart: { height: 220 } } }]
		};
		try {
			self._donutChart = new ApexCharts(el, opts);
			self._donutChart.render();
		} catch(e){ console.warn('ApexCharts donut error:', e); }
		self._renderLegend(data, colors);
	},

	/* Sau khi partial overview nạp xong: chọn scope mặc định office (fallback dept nếu trống) */
	init_donut: function(){
		var self = $Core.report_checkin;
		var officeData = self._parseDonutData('office');
		var initScope  = (officeData.series && officeData.series.length > 0) ? 'office' : 'dept';
		self._donutScope = initScope;
		$('#donut_btn_office, #donut_btn_dept').removeClass('active');
		$('#donut_btn_' + initScope).addClass('active');
		self.render_donut(initScope);
	},

	/* Toggle donut scope (nút office/dept) */
	set_donut_scope: function(scope){
		var self = $Core.report_checkin;
		self._donutScope = scope;
		$('#donut_btn_office, #donut_btn_dept').removeClass('active');
		$('#donut_btn_' + scope).addClass('active');
		self.render_donut(scope);
	},
	/* Danh sách người (đã/chưa CI) — ENTRY: mở popup. Lưu params để step "Hành trình" quay lại đúng danh sách. */
	load_list_profile_checkin : (_this,e)	=> 	{
		e.preventDefault();
		var self = $Core.report_checkin;
		var $_adata = $.extend({}, self.collect_filters());
		$_adata["_type"] = $(_this).data("type");
		var _dep = parseInt($(_this).data("department") || 0, 10);	// per-row override (bảng vùng → người của đơn vị đó)
		if(_dep > 0){ $_adata["department_id"] = _dep; }
		self._listParams = { _type: $_adata["_type"], department_id: _dep };	// lưu cho nút "Quay lại"
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_list_profile_checkin', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
	},

	/* Thay .modal-content của popup đang mở bằng content mới → chuyển step, GIỮ nguyên modal/backdrop */
	_swapModalContent : function(html){
		var $cur = $('.modal.show').last();
		if(!$cur.length){ return false; }
		var $new = $('<div>').html(html).find('.modal-content').first();
		if(!$new.length){ return false; }
		$cur.find('.modal-content').first().replaceWith($new);
		return true;
	},

	_journeyState : { profile_id: 0, day: 0 },

	/* Nạp step Hành trình (profile + ngày) vào popup; lưu state cho nút ‹ › */
	_fetchJourney : function(pid, day){
		var self = $Core.report_checkin;
		if(!pid){ return; }
		var $_adata = $.extend({}, self.collect_filters());
		$_adata["profile_id"] = pid;
		if(day > 0){ $_adata["day"] = day; }
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_profile_journey', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			self._journeyState = { profile_id: pid, day: parseInt(respJson.day || 0, 10) };
			if(!self._swapModalContent(respJson.html)){ $Core.popup.open('auto', 'auto', respJson.html, respJson.uid); }
		}, 'json');
	},

	/* Click 1 người → step "Hành trình" NGAY TRONG popup danh sách (không mở modal mới) */
	load_profile_journey : (_this,e)	=> 	{
		e.preventDefault();
		$Core.report_checkin._fetchJourney(parseInt($(_this).data("profile") || 0, 10), 0);
	},

	/* Nút ‹ › đổi ngày trong step Hành trình (server chặn ngày tương lai) */
	journey_day : function(delta){
		var st = $Core.report_checkin._journeyState || {};
		if(!st.profile_id || !st.day){ return; }
		var s = '' + st.day;
		var d = new Date(parseInt(s.substr(0,4),10), parseInt(s.substr(4,2),10)-1, parseInt(s.substr(6,2),10));
		d.setDate(d.getDate() + delta);
		$Core.report_checkin._fetchJourney(st.profile_id, d.getFullYear()*10000 + (d.getMonth()+1)*100 + d.getDate());
	},

	/* Nút "Quay lại danh sách check-in" trong step Hành trình → tải lại danh sách vào CÙNG popup */
	back_to_list : function(){
		var self = $Core.report_checkin;
		var p = self._listParams || {};
		var $_adata = $.extend({}, self.collect_filters(), { "_type": (p._type || 'has_checkin') });
		if(p.department_id > 0){ $_adata["department_id"] = p.department_id; }
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_list_profile_checkin', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(!self._swapModalContent(respJson.html)){ $Core.popup.open('auto', 'auto', respJson.html, respJson.uid); }
		}, 'json');
	},

};