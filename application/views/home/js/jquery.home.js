"use strict";
$(function(){
	_autoload();
	if($('.isodaterangepicker').length > 0) {
		$('.isodaterangepicker').daterangepicker({
			timePicker: false,
			"drops": "auto",
			"autoApply": true,
			alwaysShowCalendars: true,
			startDate: moment().startOf('today'),
			endDate: moment().endOf('today'),
			ranges: {
			   'Hôm nay': [moment(), moment()],
			   'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   '7 ngày qua': [moment().subtract(6, 'days'), moment()],
			   '30 ngày qua': [moment().subtract(29, 'days'), moment()],
			   'Tháng này': [moment().startOf('month'), moment().endOf('month')],
			   'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			opens: (deviceType=='phone'?'center':'right'),
			locale: {format: 'DD/MM/YYYY'}
		})
	}
	$('.js__stock-cell-focus').hover(function(){
		var code = $(this).attr('code'),
			floor = $(this).attr('floor');
		$('.js__stock-cell-focus').removeClass('stock-highlight');
		$(`td[floor='${floor}'].js__stock-cell-focus`).addClass('stock-highlight');
		$(`td[code='${code}'].js__stock-cell-focus, th[code='${code}'].js__stock-cell-focus`).addClass('stock-highlight');
	});
	$_document.on('click', '.awe__post-like-action', function(e){
		e.preventDefault();
		var _this = $(this), news_id = _this.attr('news_id');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=like', {
			'news_id' : news_id
		}, function(html){
			if(html.indexOf('_invalid') >= 0){} else {
				_this.replaceWith(html);
			}
		});
		return false;
	});
	$_document.on('click', '.awe__share-like-action', function(e){
		e.preventDefault();
		var _this = $(this), share_id = _this.attr('share_id'), _type = _this.attr('_type') || "pop";
		if(_type == 'home') {
			var _parent = _this.closest(".post_item");
		}else{
			var _parent = _this.closest(".item_detail")
		}
		console.log(_parent);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=share_like', {
			'share_id' : share_id,_type:_type
		}, function(respJson){
			if(respJson.result) {
				if(_type == 'pop') {
					$(".awe__share-like_"+share_id).replaceWith(respJson.html);
				}
				$(".awe__share-like_short_"+share_id).replaceWith(respJson.html_short);
				if($(".txt_like_"+share_id).length > 0) {
					$(".txt_like_"+share_id).html(respJson.html_like);	
				}				
			}
		},"json");
		return false;
	});
	if($(".ajax_more").length > 0) {
		$(".ajax_more").each((index, elm) => {
			load_ajax_more($(elm).find(".load_ajax_more"));
		});		
	}
	$_document.on('change', '.upload_image_slide', function(ev){
		var _this = $(this),
			uid = _this.attr('id'), 
			files = _this.prop('files');
		if (files && files.length) {
			for(var i = 0; i<files.length; i++){
				var filename = files[i].name,
					extension = filename.substr(filename.lastIndexOf('.') + 1);
				if(extension == "heic"){
					heic2any({
						blob: ev.target.files[i],
						toType: "image/jpg",
						quality: 0.7
					}).then(function (resultBlob) {
						var url = window.URL.createObjectURL(resultBlob);
						let dataTransfer = new DataTransfer();
						let file = new File(
							[resultBlob], filename.replace('.'+extension,'')+".jpg",
							{type:"image/jpeg", lastModified:new Date().getTime()}
						);
						dataTransfer.items.add(file);
						var formData = new FormData();
						formData.append('uid', uid);
						formData.append('image', dataTransfer.files[0]);
						$Core.slide.do_upload_slide(uid, formData);
					}).catch(function (err) {
						$Core.alert.error(err.message);
					});
				} else {
					var formData = new FormData();
					formData.append('uid', uid);
					formData.append('image', files[i]);
					$Core.slide.do_upload_slide(uid, formData);
				}
			}
			_this.val("");
		}
	});
	$_document.on('change', '.upload_sp_file', function(ev){
		var _this = $(this),
			_form = $(this).closest('form'),
			to_field = _this.attr('to_field'),
			billing_id = _this.attr('billing_id');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=upload_sp_file",
			data: {'billing_id':billing_id,'to_field':to_field},
			success: function(html){
				$Core.util.toggleIndicatior(0);
				load_billing_sold(billing_id, {});
			}
		});
	});
	$_document.on("click","input.checkAll",function(){
		if($(this).is(":checked")){
			$("input.checkitem").each(function(i,elm){
				$(elm).prop("checked",true);
			});
			$("#btn_delete_all").show();
		}else{
			$("input.checkitem").each(function(i,elm){
				$(elm).prop("checked",false);
			});
			$("#btn_delete_all").hide();
		}
	});
	$_document.on("click","input.checkitem",function(){
		var countItem = $("input.checkitem").length;
		var countItemChecked = $("input.checkitem:checked").length;
		if($(this).is(":checked")){
			if(countItem == countItemChecked){
				$("input.checkAll").prop("checked",true);
			}
			$("#btn_delete_all").show();
		}else{
			$("input.checkAll").prop("checked",false);
			if(countItemChecked == 0){
				$("#btn_delete_all").hide();
			}
		}
	});
	if($('.awe__cat_docs').length){
		setTimeout(() => {
			var outerContent = $('.awe__cat_docs'),
				innerContent = $('.awe__cat_docs .item_option.active'),
				innerWidthContent = innerContent.outerWidth(),
				offsetLeft = innerContent.offset().left;
			outerContent.scrollLeft((offsetLeft - innerWidthContent));
		},500);
	}
	if($('.owl_document').length > 0) {
		$('.owl_document').owlCarousel({
			loop:false,
			margin:10,
			responsiveClass:true,
			navText: ["<i class='bx bx-chevron-left' ></i>","<i class='bx bx-chevron-right' ></i>"], 
			responsive:{
				0:{items:4, nav:false,},
				600:{items:4, nav:false,},
				1000:{items:6, nav:false,}
			}
		});
	}
	if($('.owl_images').length > 0) {
		$('.owl_images').owlCarousel({
			loop:false,
			margin:10,
			responsiveClass:true,
			navText: ["<i class='bx bx-chevron-left'></i>",
				"<i class='bx bx-chevron-right'></i>"
			], 
			responsive:{
				0:{items:1, nav:false},
				600:{items:2, nav:false},
				992:{items:3, nav:false},
				1200:{items:4, nav:false}
			}
		});
	}
	if($('.owl_block').length > 0) {
		var _max = (deviceType == 'phone') ? 1 : 6,
			_total = $(this).find('.item_block').length;
		$('.owl_block').owlCarousel({
			loop:(_total > _max ? true : false),
			margin:10,
			responsiveClass:true,
			navText: [
				"<i class='bx bx-chevron-left'></i>",
				"<i class='bx bx-chevron-right'></i>"
			], responsive:{
				0:{items:1.5, nav:false},
				600:{items:2, nav:false},
				992:{items:3, nav:false},
				1140:{items:4, nav:false},
				1360:{items:5, nav:false},
				1600:{items:6, nav:true}
			}
		});
	}
	if($('.owl_building').length > 0) {
		var _max = (deviceType == 'phone') ? 1 : 5,
			_total = $(this).find('.item_building').length
		$('.owl_building').owlCarousel({
			margin:10,
			loop:(_total > _max ? true : false),
			responsiveClass:true,
			navText: ["<i class='bx bx-chevron-left'></i>",
				"<i class='bx bx-chevron-right' ></i>"
			], responsive:{
				0:{items:1.5, nav:false},
				600:{items:2, nav:false},
				992:{items:3, nav:false},
				1140:{items:4, nav:false},
				1360:{items:5, nav:false},
				1600:{items:6, nav:true}
			}
		});
	}
	if($('.owl_news').length > 0) {
		$('.owl_news').owlCarousel({
			loop:true,
			margin:10,
			responsiveClass:true,
			navText: ["<i class='bx bx-chevron-left' ></i>","<i class='bx bx-chevron-right' ></i>"], 
			nav:true,
			autoplay:true,
			autoplayTimeout:3000,
			autoplayHoverPause:true,
			responsive:{
				0:{items:1,},
				678:{items:2.2,},
				1200:{items:3,},
				1400:{items:4,}
			}
		});
	}	
	if($('.owl_honor:not(.installed)').length > 0) {
		$('.owl_honor:not(.installed)').each((_i, _elem) => {
			var _id = $(_elem).attr('id');
			$(`#${_id}`).addClass('installed').owlCarousel({
				loop:false,
				margin:10,
				responsiveClass:true,
				navText: ["<i class='bx bx-chevron-left'></i>"
					,"<i class='bx bx-chevron-right'></i>"], 
				nav:true,
				autoplay:false,
				autoplayTimeout:3000,
				autoplayHoverPause:true,
				responsive:{
					0:{items:2.5},
					980:{items:3},
					1400:{items:4}
				}
			}); 
		});
	}
	if($('.tinyContentx').length > 0) {
		$('.tinyContentx').readmore({
			speed: 75, 
			maxHeight: 75
		});	
	}
	if($(".drag_project").length > 0) {
		$(".drag_project").sortable({
			connectWith: ".drag_project",
			handle: ".btn_drag",
      		items: '.project-item',
			forcePlaceholderSize: true,
			revert: false,
			update: function (event, ui) {
				var orderNo = $(this).sortable('toArray'),
					area_id = $(this).closest(".drag_project").attr("area_id"),
				$_adata = {"orderNo":orderNo,"area_id":area_id};
				$.post(path_ajax_script+"/index.php?mod=ajax&sub=helper&act=save_order",$_adata,function(respJson){	
				},"json");
			}
		}).disableSelection();
	}
	if(MOD == 'home' && ACT == 'billing'){
		$Core.global.billing.sticky_fixed();
		$(window).on('resize', () => {
			$Core.global.billing.sticky_fixed();
		});
	}
	if(MOD == 'home' && ACT == 'share'){
		if($("#holder_report_chart").length > 0) {
			$Core.share.load_report_share({show:"home_share"});	
		}
		if($("#holder_report_top_share").length > 0) {
			$Core.share.load_report_top_share({});	
		}		
	}
	if($("#list_menu_active.computer").length > 0) {
		$Core.mobile.loadMenu();
	}
	$_document.on('change','.check_all[type=checkbox]', function(){
		var table = $(this).closest("table");
		$('.chkitem[type=checkbox]',table).prop('checked', $(this).prop('checked'));
		$Core.gratitude.setList($(this));
	});
	$_document.on('change', '.chkitem', function(){
		$Core.gratitude.setList($(this));
	});
	/** Recieved data from NodeJs */
	/*setTimeout(() => {
		if($(".item_media_dissemination").length > 0) {
			$(".item_media_dissemination").each((_i, _elm) => {
				$(_elm).trigger("click");
				return false;
			})
		}
	},1000);*/
	// Tiến độ (tab tien-do): timeline tháng/ngày — click tháng mở ngày + xem đợt đầu; click ngày xem đợt đó
	$(document).on('click.tdmonth', '.tien-do-wrap .pt-item', function(){
		var $item = $(this), pi = $item.attr('data-pi');
		$('.tien-do-wrap .pt-item').removeClass('active open');
		$('.tien-do-wrap .pt-item .pt-chev').removeClass('bx-chevron-up').addClass('bx-chevron-down');
		$item.addClass('active');
		$('.tien-do-wrap .pt-subwrap').addClass('d-none');
		var $sub = $item.next('.pt-subwrap');
		if($sub.length){
			$item.addClass('open').find('.pt-chev').removeClass('bx-chevron-down').addClass('bx-chevron-up');
			$sub.removeClass('d-none');
		}
		$('.tien-do-wrap .pt-sub').removeClass('active');
		$sub.find('.pt-sub').first().addClass('active');
		$('.tien-do-wrap .td-panel').addClass('d-none');
		$('.tien-do-wrap .td-panel[data-pi="'+pi+'"]').removeClass('d-none');
	});
	$(document).on('click.tdday', '.tien-do-wrap .pt-sub', function(e){
		e.stopPropagation();
		var pi = $(this).attr('data-pi');
		$('.tien-do-wrap .pt-sub').removeClass('active'); $(this).addClass('active');
		$('.tien-do-wrap .td-panel').addClass('d-none');
		$('.tien-do-wrap .td-panel[data-pi="'+pi+'"]').removeClass('d-none');
	});
});
function load_more_table(_this) {
	$(_this).closest(".box_loadMore").find("table tr").removeClass("d-none");
	$(_this).remove();
}
function load_ajax_more(_this) {
	let devicetype = $(_this).data("devicetype"),
		department_id = $(_this).data("department_id"),
		type = $(_this).data("type"),
		page = $(_this).attr("page"),
		per_page = $(_this).data("per_page"),
		url = $(_this).data("url");
	$.post(url, {
		'deviceType' : devicetype,
		'department_id' : department_id,
		'page' : page,
		'per_page' : per_page,
	}, function(respJson){
		if(type == "load") {
			$(_this).closest(".ajax_more").find(".load_ajax_more").html(respJson.html);
		}else{
			$(_this).closest(".ajax_more").find(".load_ajax_more").append(respJson.html);
		}	
		$(_this).closest(".ajax_more").find(".load_more").attr("page",parseInt(page)+1); 
		if(page * per_page < respJson.total_record){
			$(_this).closest(".ajax_more").find(".load_more").removeClass("d-none");
		}else{
			$(_this).closest(".ajax_more").find(".load_more").addClass("d-none");
		}
	},"json");
}
function pop_save_news(_this, e){
	e.preventDefault();
	var _validated = 0,
		_form = $(_this).closest('form'),
		news_id = $(_this).attr('news_id'),
		$_adata = {'news_id':news_id};
	if($('select.required:visible,input.required:visible', _form).length){
		$('select.required:visible,input.required:visible', _form).each((_i, _elem) => {
			if($Core.util.isEmpty($(_elem).val())){
				_validated++;
				$(_elem).focus();
				return false;
			}
		});
	}
	if($('.isoTextArea', _form).length){
		$('.isoTextArea', _form).each((_i, _elem) => {
			var name= $(_elem).data('name'),
				editorId = $(_elem).attr('id');
			$_adata[name] = $Core.util.getTinyMCEContent(editorId);
		});
	}
	if(_validated==0){
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=pop_save_news",
			data: $_adata,
			success: function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					window.location.reload(true);
				} else if(html.indexOf('_error') >= 0){
					swal ( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại","error" );
				} else if(html.indexOf('_duplicated') >= 0){
					swal ( "Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại","error" );
				} 
			}
		});
	}
	return false;
}
function loadGratitude(_this,e) {
	var toId = $(_this).attr("toId");
	if($(_this).is(":checked")){
		$("#"+toId).removeClass("d-none");
	}else{
		$("#"+toId).addClass("d-none");
	}
}
function delete_news(_this, e){
	e.preventDefault();
	var news_id = $(_this).attr('news_id');
	$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa tin này?', function(){
		$.post('/index.php?mod=home&act=delete_news', {
			'news_id' : news_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_success') >= 0){
				window.location.reload();
			}
		});
	});
	return false;
}
function _autoload(){
	if($('.ajax:not(.loaded):visible').length){
		$('.ajax:not(.loaded):visible').each((_i, _elem) => {
			var url = $(_elem).data('url'),
				gId = $(_elem).getAttr('gId', ""),
				$_adata = $(_elem).data('options') || {};
			$_adata['gId'] = gId;
			if($(_elem).hasClass('js__block-report-today')){
				var department_id = $('.js__handle-department').val();
				$_adata['department_id'] = department_id;
			}
			if($(_elem).hasClass('billing_filter')){
				var parent = $(_elem).closest(".dashboard-panel-item"),
					time_type = $(".js_choose-time.active",parent).attr("holderg");				
				$_adata['time_type'] = time_type;
			}
			if($(_elem).hasClass('box_target_sale')){
				var parent = $(_elem).closest(".target_sale");		
				$(".search_field",parent).each(function(index, elm){
					var field = $(elm).attr("name");
					$_adata[field] = $(elm).val();
				});				
			}	
			if($(_elem).hasClass("report_billing")) {
				var _form = $(_elem).closest("form");
				$("input[type='text'],input[type='date'],input:checked,select",_form).each(function(index, elm){
					var field = $(elm).attr("name");
					$_adata[field] = $(elm).val();
				});
			}	
			if($(_elem).hasClass("search_group")) {
				var toId = $(_elem).attr("toId");
				$(".field_item",$("#"+toId)).each(function(index, elm){
					var field = $(elm).attr("name");
					$_adata[field] = $(elm).val();
				});
				console.log($_adata);
			}
			if($(_elem).hasClass("change_time")) {
				var gId = $(_elem).attr("gId");
				$(".field_item",$("#"+gId)).each(function(index, elm){
					var field = $(elm).attr("name");
					$_adata[field] = $(elm).val();
				});
				console.log($_adata);
			}	
			if($(_elem).hasClass("holder_revenue_reports") || $(_elem).hasClass("search_global")) {
				$(".search_field").each(function(index, elm){
					var field = $(elm).attr("name");
					$_adata[field] = $(elm).val();
				});
			}
			$.post(url, $_adata, function(respJson){
				let $html = respJson.html;
				if($html.indexOf('_empty') >= 0){
					if($(_elem).hasClass("home_shared")){
						$(".home_block_shared").hide();
					}
					$(_elem).remove();
				} else {
					$(_elem).addClass('loaded').html($html);
					if(respJson.drawchart == 1){
						if(typeof(respJson.multichart) != 'undefined' && respJson.multichart==1){
							$Core.chart.canvas_multi(respJson.uid,respJson.barChartData);
						} else {
							$Core.chart.canvas(respJson.uid,respJson.barChartData);
						}
					}
					if($(_elem).hasClass('dashboard_sfs_report') && $(".table-sort").length){
						$(".table-sort").tableSortable({
							cmp:(a,b) => $Core.report.toNumber(a) < $Core.report.toNumber(b) ? -1 : 1
						});
					}
					if($(_elem).hasClass("home_events")){
						$(".home_block_score").hide();
						$(".home_block_events").show();
					}
					if($(_elem).hasClass('billing_calendar')){
						$(".number_total").text(respJson.total);
					}
					if($(_elem).hasClass('commission_person')){
						$(".total_commission").text(respJson.total);
						$(".total_commission").attr('total', respJson.total);
					}
					if($(_elem).hasClass("change_time") && respJson.txt_time != "") {
						var toId = $(_elem).attr("toId");
						$("#"+toId).text(respJson.txt_time);
					}
					if($(_elem).hasClass('box_target_sale')){
						var parent = $(_elem).closest(".target_sale");		
						$(".title_box_target",parent).text(respJson.title_box);				
					}
				}
				if(respJson.callback){
					eval(respJson.callback);
				}
			},'json');
		});
	}
}
function open_filter(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid'),
		holderG = $(_this).attr('holderG');
	$Core.util.toggleIndicatior(1);
	$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_filter', {
		'uid' : uid,
		'holderG' : holderG
	}, function(html){
		$Core.util.toggleIndicatior(0);
		$Core.popup.open('auto','auto', html, 'open_filter');
	});
	return false;
}
function set_options(_this, e){
	e.preventDefault();
	var uid = $(_this).attr('uid'),
		holderG = $(_this).getAttr('holderG', '_month');
	if(holderG == '_year'){
		var y = $(_this).attr('y'),
			options = {'year':y, 'holderG':holderG};
	} else if(holderG == '_department'){
		var _dropdown_parent = $(_this).closest(".dropdown-menu"),
			department_id = $(_this).attr('department_id'),
			options = {'department_id':department_id};
		$(".dropdown-item.active",_dropdown_parent).removeClass("active");
		$(_this).addClass("active");
	} else {
		var m = $(_this).attr('m'), 
			y = $(_this).attr('y'),
			options = {'month':m, 'year':y, 'holderG':holderG};
	}
	$('[data-bind='+uid+']')
		.removeClass('loaded')
		.data('options', options );
	_autoload();
	return false;
}
function load_post_more(_this, e){
	e.preventDefault();
	var cat_id = $(_this).attr('cat_id'),
		total_record = $(_this).attr('total_record'),
		total_loaded = $(_this).attr('total_loaded'),
		order_no = $('.awe__post-item:last').attr('order_no');
	$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_more', {
		'cat_id' : cat_id,
		'total_record' : total_record,
		'total_loaded' : total_loaded,
		'order_no' : order_no
	}, function(respJson){
		$('.awe__post-item:last').after(respJson.html);
		$(_this).attr('total_loaded', respJson.total_loaded);
		if(parseInt(respJson.total_loaded, 10) >= total_record){
			$(_this).parent().remove();
		}
		if($('.awe__post-description:not(.collapsed)').length){
			$('.awe__post-description:not(.collapsed)').each((_i, _elem) => {
				var _height = $(_elem).outerHeight();
				if(_height > 200){
					$(_elem).addClass('collapsed').append('<a class="awe__link-more">Xem thêm...</a>');
				}
			});
		}
	}, 'json');
	return false;
}
function open_confirm_deposit(_this, e){
	e.preventDefault();
	var billing_id = $(_this).attr('billing_id'),
		$_adata = {'billing_id':billing_id};
	$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_confirm_deposit', $_adata, function(respJson){
		$Core.popup.open('auto','auto', respJson.html, respJson.uid);
	},'json');
	return false;
}
function copyContentToClipboard(_this,e) {
	var toId = $(_this).attr("toId"),
		content = document.getElementById(toId);
	if (!content) {
		$Core.swal.error("Thông báo", 'Không tìm thấy nội dung để sao chép!');
		return;
	}
	var range = document.createRange();
	// Đảm bảo content không phải null
	range.selectNode(content); 
	var selection = window.getSelection();
	selection.removeAllRanges();
	selection.addRange(range);
	try {
		document.execCommand('copy');
	} catch (err) {
		$Core.swal.error("Thông báo", 'Copy không thành công.');
	}
	$(_this)
		.tooltip('hide')
		.attr("data-bs-original-title","Đã copy")
		.tooltip('fixTitle')
        .tooltip('show');
	// Bỏ chọn để tránh lỗi
	selection.removeAllRanges(); 
}
function view_billing(_this, e){
	e.preventDefault();
	var tabfocus = $(_this).getAttr('tabfocus', 1),
		billing_id = $(_this).attr('billing_id'),
		$_adata = {'tabfocus':tabfocus, 'billing_id':billing_id};
	if(!$(_this).hasClass('clicked')){
		$(_this).addClass('clicked');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=view_billing', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(_this).removeClass('clicked');
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			if($(`.logs_${billing_id}`).length) 
				$Core.global.billing.load_logs(billing_id, {});
			// load_billing_sold(billing_id, {});
			$Core.helper.load_list_notes(billing_id, 'Billing', {});
			$Core.member.load_list_files(billing_id, 'Billing', {});
			if(respJson.callback) eval(respJson.callback);
		},'json');
	}
	return false;
}
function delete_billing(_this, e){
	e.preventDefault();
	var billing_id = $(_this).attr('billing_id'), 
		$_adata = {'billing_id':billing_id};
	$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa giao dịch này?', function(){
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete_billing', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_success') >= 0){
				window.location.reload();
			} else {
				swal( "Oops","Đã xảy ra lỗi!","error");
			}
		});
	});
	return false;
}
function cancel_billing(_this, e){
	e.preventDefault();
	var billing_id = $(_this).attr('billing_id'), 
		$_adata = {'billing_id':billing_id};
	$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn hủy giao dịch này?', function(){
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=cancel_billing', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_success') >= 0){
				window.location.reload();
			} else {
				$Core.swal.error("Oops","Đã xảy ra lỗi!");
			}
		});
	});
	return false;
}
function load_billing_sold(billing_id, options){
	var $_adata = options || {};
	$_adata['billing_id'] = billing_id;
	$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_billing_sold', $_adata, function(html){
		$Core.util.toggleIndicatior(0);
		$('.load_billing_sold_'+billing_id).html(html);
	});
}
function select_sp_file(_this, e){
	e.preventDefault();
	var toId = $(_this).attr('toId'),
		to_field = $(_this).attr('to_field'),
		modal = $(_this).closest('.modal');
	$('.upload_file_'+toId, modal).attr('to_field', to_field).trigger('click');
	return false;
}
function toggleItem(_this, e){
	e.preventDefault();
	var toClass = $(_this).attr('toClass');
	if(!$(_this).hasClass('clicked')){
		$(_this).addClass('clicked').text('Hiển thị rút gọn');
		$('.toggleView').removeClass('d-none');
	} else {
		$(_this).removeClass('clicked').text('Hiển thị thêm');
		$('.toggleView').addClass('d-none');
	}
	return false;
}
function save_quick_notes(_this, e){
	e.preventDefault();
	var editorId = $(_this).attr('editorId'),
		billing_id = $(_this).attr('billing_id'),
		quick_notes = $Core.util.getTextAreaContent(editorId);
	$Core.util.toggleIndicatior(1);
	$.post('/index.php?mod=home&act=save_quick_notes', {
		'billing_id' : billing_id,
		'quick_notes' : quick_notes
	}, function(html){
		$Core.util.toggleIndicatior(0);
	});
	return false;
}
function confirm_billing(_this, e){
	e.preventDefault();
	var field = $(_this).attr('field'),
		billing_id = $(_this).attr('billing_id');
	$.confirm({
		boxWidth: '50%',
		closeIcon: true,
		title: 'Xác nhận!',
		content: 'Bạn chắc chắn muốn duyệt giao dịch này?',
		buttons: {
			unapproved : {
				text: 'Không', 
				btnClass: 'w-50 btn-outline-primary',
				action: function(){
					$Core.util.toggleIndicatior(1);
					$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=confirm_billing', {
						'field' : field,
						'value' : 2,
						'billing_id' : billing_id
					}, function(html){
						$Core.util.toggleIndicatior(0);
						$(_this).remove();
						$('#'+field).html('<span class="status border-label-dark bg-label-border-label-dark">Không duyệt</span>');
						list_billing_logs(billing_id, {});
					});
				},
			},
			approved: {
				text: 'Có',
				btnClass: 'w-50 btn-outline-success',
				action: function(){
					$Core.util.toggleIndicatior(1);
					$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=confirm_billing', {
						'field' : field,
						'value' : 1,
						'billing_id' : billing_id
					}, function(html){
						$Core.util.toggleIndicatior(0);
						$(_this).remove();
						$('#'+field).html('<span class="status border-label-success bg-label-success">Đã duyệt</span>');
						list_billing_logs(billing_id, {});
					});
				},
			}	 
		}
	});
	return false;
}
$Core.home = {
	open_euro: function(_this, e){
		e.preventDefault();
		var row = $(_this).attr('row'),
			total_score = $(_this).attr('total_score');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_euro', {
			'row' : row,
			'total_score' : total_score
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.html.indexOf('_error') >= 0){
				$Core.alert.error("Lỗi !");
			} else {
				$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			}
		}, 'json');
		return false;
	},
	toggle_item: function(_this, e){
		e.preventDefault();
		var _box = $(_this).closest('.box_tab');
		if($(_this).hasClass('clicked')){
			$('.awe__doc-item-hidden', _box).addClass('d-none');
			$(_this).removeClass('clicked').html('<i class="bx bx-chevron-down"></i> Xem thêm');
		} else {
			$('.awe__doc-item-hidden', _box).removeClass('d-none');
			$(_this).addClass('clicked').html(`<i class="bx bx-chevron-up"></i> Rút gọn`);
		}
		return false;
	}
}
$Core.crm = {
	load_data_month: function(_this, e){
		e.preventDefault();
		var month = $(_this).attr('month');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_data_month_desktop', {
			'month' : month
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('.oxGryDemjr').removeClass('active');
			$(_this).parent().addClass('active');
			$('.hPImzYKEjR').html(html);
		});
		return false;
	}
};
$Core.share = $.extend($Core.global.share, {
	handle_date: function(_this, e){
		var _form = $(_this).closest('form'),
			_input = document.querySelector('.js__share-end_date-field');
		_input.showPicker();
	}, load_honor_more: (_this, e) => {
		e.preventDefault();
		var share_type = $(_this).attr('share_type'),
			page = $(_this).attr('page'),
			total_record = $(_this).attr('total_record'),
			total_loaded = $(_this).attr('total_loaded'),
			reg_date = $('.awe__share-item:last').attr('reg_date');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_honor_more', {
			'share_type' : share_type,
			'page' : page,
			'total_record' : total_record,
			'total_loaded' : total_loaded,
			'reg_date' : reg_date
		}, function(respJson){
			$('.awe__share-item:last').after(respJson.html);
			$(_this).attr('page',respJson.page);
			if(parseInt(respJson.total_page) < respJson.page){
				$(_this).parent().remove();
			}
		}, 'json');
		return false;
	}, load_more: (_this, e) => {
		e.preventDefault();
		var share_type = $(_this).attr('share_type'),
			total_record = $(_this).attr('total_record'),
			total_loaded = $(_this).attr('total_loaded'),
			reg_date = $('.awe__share-item:last').attr('reg_date');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_share_more', {
			'share_type' : share_type,
			'total_record' : total_record,
			'total_loaded' : total_loaded,
			'reg_date' : reg_date
		}, function(respJson){
			$('.awe__share-item:last').after(respJson.html);
			$(_this).attr('total_loaded', respJson.total_loaded);
			if($('.awe__comment-autoload').length){
				$('.awe__comment-autoload').each((_i, _elem) => {
					var share_id = $(_elem).attr('table_id'),
						clsTable = $(_elem).attr('clsTable');
					$(_elem).removeClass('awe__comment-autoload');
					$Core.news.load_comments(share_id,clsTable,{'action':'reload'});
				});
			}
			if(parseInt(respJson.total_loaded, 10) >= total_record){
				$(_this).parent().remove();
			}
		}, 'json');
		return false;
	}, gen_image: (_this, e) => {
		e.preventDefault();
		var $_adata = {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=share&act=gen_image', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			setTimeout(() => {
				var $fh_line_1 = $('.fh_line_1').hide();
				$fh_line_1.show().arctext({radius: 300, dir:-1});
			}, 500);
		}, 'json');
		return false;
	}, do_gen_image: (_this, e) => {
		e.preventDefault();
		var _uid = $(_this).attr('uid'),
			_form = $(_this).closest('form');
		html2canvas(document.querySelector("#html_content_"+_uid), {
			useCORS: true,
			logging: false,
			allowTaint: true,
			removeContainer: false,
			ignoreElements: function (el) {
				return $(el).is('.leaflet-top, .leaflet-bottom');
			}
		}).then((canvas) => {
			var imagedata = canvas.toDataURL('image/png'),
				imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=share&act=do_gen_image', {
				imgdata	: imgdata
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_error') >= 0){
					$Core.swal.error("Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
				} else {
					$Core.popup.close(_form.closest('.modal'));
					console.log(respJson);
				}
			},'json');
		});
		return false;
	}, load_report_share: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=report&act=load_report_share', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#holder_report_chart').html(respJson.html);
		},"json");
	}, load_report_top_share: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=report&act=load_report_top_share', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#holder_report_top_share').html(respJson.html);
		},"json");
	}, 
});
$Core.worktime = {
	open: function(_this, e){
		e.preventDefault();
		var worktime_id = $(_this).attr('worktime_id'),
			$_adata = {'worktime_id':worktime_id};
		//alert("x");
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=worktime&act=open', $_adata, function(respJson){
				$Core.util.toggleIndicatior(0);
				$(_this).removeClass('clicked');
				if(respJson.result.indexOf('error') >= 0){
					$Core.messager.alert('Thông báo', 'Bạn đã thêm thông báo của ngày hôm này !');
				} else {
					$Core.popup.open('auto','auto', respJson.html, respJson.uid);
				}
			},'json');
		}
		return false;
	},
	do_search: function (_this, e){
		e.preventDefault();
		$Core.worktime.list({});
		return false;
	},
	set_date: function(_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			uid = $(_this).attr('uid');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=worktime&act=set_date', {
			'tp' : tp,
			'date' : $('#'+uid).val()
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+uid).val(html).trigger('change');
		});
		return false;
	},
	select_date: function(_this, e){
		var date_id = $(_this).val();
		$Core.worktime.list({'date_id':date_id});
	},
	list: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=worktime&act=list', $_adata, function(respJson){
			$('.holder_worktimes').html(respJson.html);
			$('.total_record').text(respJson.total_record);
			if(parseInt(respJson.total_page) > 1){
				$('#pager_worktime').pagination({
					listStyle:"pagination justify-content-center",
					currentPage: respJson.current_page, 
					itemsOnPage: respJson.per_page,
					items: respJson.total_record,
					cssStyle: 'light-theme',
					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
					onPageClick : function(pageNumber){
						$Core.worktime.list($.extend(options, {'page':pageNumber}));
					}
				});
			}
		},'json');
	},
	set_worktime: function(_this, e){
		var gid = $(_this).attr('gid');
		if($(_this).is(':checked')){
			$('.'+gid).removeAttr('disabled');
		} else {
			$('.'+gid).attr('disabled', true);
		}
	},
	save: function(_this, e){
		e.preventDefault();
		var worktime_id = $(_this).attr('worktime_id'),
			$_adata = {'worktime_id':worktime_id},
			_validated = 0, _form = $(_this).closest('form');
		if($('select.required,input.required,textarea.required', _form).length){
			$('select.required,input.required,textarea.required', _form).each((_i, _elem) => {
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
				url: PCMS_URL + "/index.php?mod="+MOD+"&sub=worktime&act=save",
				data: $_adata,
				success: function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$Core.worktime.list({});
						$Core.alert.success("Cập nhật báo cáo thành công!");
						$('.btn-close',_form).trigger('click');
					} else if(html.indexOf('_error') >= 0){
						$Core.swal.error("Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} else if(html.indexOf('_duplicated') >= 0){
						$Core.swal.error("Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại");
					} 
				}
			});
		}
		return false;
	}
};
$Core.slide = {
	open: function(_this, e){
		e.preventDefault();
		var slide_id = $(_this).attr('slide_id'),
			$_adata = {'slide_id':slide_id};
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=open_slide', $_adata, function(respJson){
				$Core.util.toggleIndicatior(0);
				$(_this).removeClass('clicked');
				$Core.popup.open('auto','auto', respJson.html, respJson.uid);
				$Core.slide.load_content(respJson.uid, {
					'slide_id':respJson.slide_id,
					'slide_type':respJson.slide_type
				}, true);
				$(".input-tags").selectize({
					delimiter: ",",
					persist: false,
					preload: true,
					valueField: 'text',
					labelField: 'text',
					searchField: 'text',
					create: function (input) {
						return {
							value: input,
							text: input,
						};
					},
					load: function(query, callback) {
						var self = $(this);
						$.ajax({
							url:'/index.php?mod='+MOD+'&sub=course&act=search_tag',
							type: 'GET',
							dataType:'json',
							cache: true,
							error: function() {
								callback();
							},
							success: function(res) {
								callback(res);
							}
						});
					}
				});
			},'json');
		}
		return false;
	},
	delete: function(_this, e){
		e.preventDefault();
		var slide_id = $(_this).attr('slide_id'),
			$_adata = {'slide_id':slide_id};
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$.post('/index.php?mod=home&sub=course&act=delete_slide', $_adata, function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success')>=0){
					$Core.slide.list({});
					$Core.alert.success("Xóa thành công !");
				}
			});
		});
		return false;
	},
	list: function(options){
		var $_adata = options || {};
		if(!$_adata.hasOwnProperty('page')){
			var current_page = $('input[name=current_page]').val();
			$_adata['page'] = current_page;
		}
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if(typeof(field) != 'undefined'){
					$_adata[field] = $(_elem).val();
				}
			});
		}
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=list_slide', $_adata, function(respJson){
			$('.holder_slide').html(respJson.html);
			if(parseInt(respJson.total_page) > 1){
				$('#pager_slide').pagination({
					listStyle:"pagination justify-content-center",
					currentPage: respJson.current_page, 
					itemsOnPage: respJson.per_page,
					items: respJson.total_record,
					cssStyle: 'light-theme',
					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
					onPageClick : function(pageNumber){
						$Core.slide.list($.extend(options, {'page':pageNumber}));
					}
				});
			}
		},'json');
	},
	do_search: function (_this, e){
		e.preventDefault();
		$Core.slide.list({});
		return false;
	},
	view: function(_this, e){
		e.preventDefault();
		var www = $(window).width(),
			layoutM = $('.layout-menu').outerWidth(false),
			slide_id = $(_this).attr('slide_id'),
			$_adata = {'www':www, 'slide_id':slide_id};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=view_slide', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(_this).removeClass('clicked');
			$Core.popup.openfull(www/2,respJson.html,respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				if($('.slideshow').length){
					$('.slideshow').owlCarousel({
						loop:false,
						nav: false,
						lazyLoad:false,
						dots:true,
						margin:3,
						autoplay:false,
						responsiveClass:true,
						responsive:{
							0:{items:1},
							1200:{items:1},
						}
					});
				}
			});
		},'json');
		return false;
	},
	set_content(_this, e){
		var uid = $(_this).attr('uid'),
			_form = $(_this).closest('form'),
			_slide_id = $(_this).attr('slide_id'),
			_slide_type = $('input[name=slide_type]:checked',_form).val();
		$Core.slide.load_content(uid, {'slide_type':_slide_type,'slide_id':_slide_id});
	},
	load_content: function(uid, options, hide_loading=false){
		var $_adata = options || {};
		$_adata['uid'] = uid;
		!1==hide_loading && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=load_content', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$('#loadconentdoc_'+uid).html(html);
		});
	},
	do_upload_slide: function(uid, formData){
		$Core.util.toggleIndicatior(1);
		$.ajax({
			url: "/index.php?mod="+MOD+"&sub=course&act=upload_image_slide",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					var tmp = html.split('|||');
					if($('#imageList_'+uid+' .item').length){
						$('#imageList_'+uid+' .item:last').after(tmp[1]);
					} else {
						$('#imageList_'+uid).html(tmp[1]);
					}
				}
			}
		});
	},
	upload_slide: function(_this, e){
		e.preventDefault();
		var uid = $(_this).attr('uid');
		$('#'+uid).trigger('click');
		return false;
	},
	re_upload_slide: function (_this, e){
		e.preventDefault();
		var _uid = $(_this).attr('uid'),
			_src = $(_this).attr('src');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=delete_image_slide', {
			'image' : _src
		}, function(respJson){
			$('.we-filedrop__image[uid='+_uid+']').remove();
			$('.we-filedrop[uid='+_uid+']').removeClass('d-none');
		});
		return false;
	},
	save: function(_this, e){
		e.preventDefault();
		var slide_id = $(_this).attr('slide_id'),
			$_adata = {'slide_id':slide_id},
			_validated = 0, _form = $(_this).closest('form');
		if($('select.required,input.required,textarea.required', _form).length){
			$('select.required,input.required,textarea.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if($('.isoTextArea', _form).length && _validated==0){
			$('.isoTextArea', _form).each((_i, _elem) => {
				var name= $(_elem).data('name'),
					editorId = $(_elem).attr('id'),
					contentEditor = $Core.util.getTinyMCEContent(editorId);
				if($Core.util.isEmpty(contentEditor)){
					_validated++;
					$Core.alert.error("Lỗi! Bạn chưa nhập vào nội dung.");
				} else {
					$_adata[name] = contentEditor;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&sub=course&act=pop_save_slide",
				data: $_adata,
				success: function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						_form.resetForm();
						$Core.slide.list({});
						$('.close_pop', _form).trigger('click');
						$Core.swal.success("Thông báo","Thành công!");
					} else if(html.indexOf('_error') >= 0){
						$Core.swal.error("Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} else if(html.indexOf('_duplicated') >= 0){
						$Core.swal.error("Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại");
					} 
				}
			});
		}
		return false;
	}
};
$Core.report = $.extend($Core.global.report, {
	toNumber: function(num){
		num = num.replaceAll('.','');
		num = num.replaceAll(',','');
		num = num.replaceAll('%','');
		return parseFloat(num);
	},
	do_search: function (_this, e){
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
					url: '/index.php?mod='+MOD+'&sub=report&act=load_date_range',
					data: {'month':_month,'year':_year},
					dataType: "json",
					async : false,
					success: function(respJson){
						$Core.util.toggleIndicatior(0);
						$('.js__search-date_type-field').val('30days');
						$('.js__search-start_date-field').val(respJson.start_date);
						$('.js__search-end_date-field').val(respJson.end_date);
					}
				});
			} else if($(_this).hasClass('js__search-date_type-field')){
				var _date_type = $(_this).val();
				$.ajax({
					method: "POST",
					url: '/index.php?mod='+MOD+'&sub=report&act=load_time_range',
					data: {'date_type' : _date_type},
					dataType: "json",
					async : false,
					success: function(respJson){
						$('.js__search-start_date-field').val(respJson.start_date);
						$('.js__search-end_date-field').val(respJson.end_date);
					}
				});
			}
			if($('.holder_reports').length)
				$Core.report.load_reports({});
			if($('.holder_total_reports').length) 
				$Core.report.load_total_reports({});
			if($('.holder_chart_reports').length) 
				$Core.report.load_chart_reports();
			if($('.holder_today_reports').length){
				$Core.report.load_today_reports();
			}
		}
		if($('.holder_sfs_reports').length){
			$Core.report.load_sfs_reports({});
		}
		if($('.holder_chart_top_reports').length){
			$Core.report.load_chart_top_reports();
		}
		if($('.holder_reports_login').length){
			$Core.report.load_reports_login();
		}
		if($('.holder_revenue_reports').length){
			$('.holder_revenue_reports').removeClass("loaded");
			_autoload();
		}
	}, 
	load_reports: function(options){
		var $_adata = {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&sub=report&act=list_reports', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_reports').html(respJson.html);
			$('.total_record').html(respJson.total_record);
		},'json');
	},
	load_total_reports: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$.post('/index.php?mod='+MOD+'&sub=report&act=load_total_reports', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_total_reports').html(respJson.html);
		},'json');
	},
	load_chart_reports: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$.post('/index.php?mod='+MOD+'&sub=report&act=load_chart_reports', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_chart_reports').html(respJson.html);
			$Core.chart.canvas(respJson.uid,respJson.barChartData);
		},'json');
	},
	load_sfs_reports: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=home&sub=report&act=load_sfs_report', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_sfs_reports').html(respJson.html);
			if($(".table-sort").length){
				$(".table-sort").tableSortable({
					cmp:(a,b) => $Core.report.toNumber(a) < $Core.report.toNumber(b) ? -1 : 1
				});
				setTimeout(() => {
					$('.js__th-sort-clickable')
						.trigger('click')
						.trigger('click');
				}, 1000);
			}
		},'json');
	},
	load_today_reports: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=home&sub=report&act=load_report_today', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_today_reports').html(respJson.html);
		},'json');
	},
	load_chart_top_reports: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$.post('/index.php?mod='+MOD+'&sub=report&act=load_chart_top_reports', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_chart_top_reports').html(respJson.html);
			$Core.chart.canvas(respJson.uid,respJson.barChartData);
			if($('.holder_chart_profs_reports').length > 0){
				$('.holder_chart_profs_reports').html(respJson.html_profs);
				$Core.chart.canvas(`profs_${respJson.uid}`,respJson.barChartProfs);
			}
		},'json');	
	},
	do_change: function(_this, e){
		var params = {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var _field = $(_elem).data('field');
				params[_field] = $(_elem).val();
			});
		}
		$Core.report.load_chart_reports(params);
	},
	handle_click: function(_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			type_id = $(_this).attr('type_id');
		if(tp == 'top_reports'){
			$Core.report.load_chart_top_reports({'type_id':type_id});
		} else if(tp == 'chart_reports') {
			$Core.report.load_chart_reports({'type_id':type_id});
		}
		return false;
	},
	agent_log : function () {
		var $_adata = {};
		if($('.filter_agency_updated_log_field').length){
			$('.filter_agency_updated_log_field').each((_i, _elem) => {
				var _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=report&act=agent_log', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$("#list_agent_log").html(respJson.html);
		},"json");
	},
	load_reports_login: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$.post('/index.php?mod='+MOD+'&sub=report&act=load_reports_login', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_reports_login').html(respJson.html);
		},'json');
	},
	loadTimeLogin : function(_this, e){
		e.preventDefault();
		var profile_id = $(_this).data("profile_id"),
			$_adata = {"profile_id":profile_id};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}		
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&sub=report&act=loadTimeLogin ', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
});
$Core.course = {
	do_search: function(_this, e){
		e.preventDefault();
		$Core.course.list({});
		return false;
	},
	list: function (options, hide_loading = false){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		if(hide_loading==false){
			$Core.util.toggleIndicatior(1);
		}
		$.post('/index.php?mod='+MOD+'&sub=course&act=load_courses', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_courses').html(respJson.html);
			$('.total_record').html(respJson.total_record);
			if(parseInt(respJson.total_page) > 1){
				$('#pager').pagination({
					listStyle:"pagination justify-content-center",
					items: respJson.total_record,
					itemsOnPage: respJson.per_page,
					currentPage: respJson.current_page, 
					cssStyle: 'light-theme',
					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
					onPageClick : function(pageNumber){
						$Core.course.list($.extend(options, {'page':pageNumber}), false);
					}
				});
			}
		},'json');
	}, open: function(_this, e){
		e.preventDefault();
		var course_id = $(_this).attr('course_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=view_course', {
			'course_id' : course_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.open('auto', 'auto',respJson.html,respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				var _height = $('#content_'+respJson.uid).outerHeight(false);
				if(_height > 150){
					$('#content_'+respJson.uid).readmore({speed: 75, maxHeight: 150 });
				}
				$Core.news.load_comments(course_id, 'Course', {'action' : 'reload'});
			});
		}, 'json');
		return false;
	}, openJoin: function(_this, e){
		e.preventDefault();
		var course_id = $(_this).attr('course_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=view_join_course', {
			'course_id' : course_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(__w*1/2,respJson.html,respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				var _height = $('#content_'+respJson.uid).outerHeight(false);
				if(_height > 150){
					$('#content_'+respJson.uid).readmore({speed: 75, maxHeight: 150 });
				}
				$Core.news.load_comments(course_id, 'Course', {'action' : 'reload'});
			});
		}, 'json');
		return false;
	}, checkin: function(_this, e){
		var toId = $(_this).attr('toId'),
			holderG = $(_this).attr('holderG'),
			openFrom = $(_this).attr('openFrom'),
			course_id = $(_this).attr('course_id');
		if($(_this).hasClass('checked_in')){
			$Core.alert.error("<i class=\"bx bx-check-double\"></i> Bạn đã check-In sự kiện !");
		} else {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=checkin', {
				'tp' : "_item",
				'toId' : toId,
				'holderG' : holderG,
				'openFrom' : openFrom,
				'course_id' : course_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				if(openFrom == '_desktop'){
					$(".home_events").removeClass("loaded");
					_autoload();
				} else {
					$('.course_report_'+toId).html(html);
					$Core.course.load_reports(course_id, {});
				}
			});
		}
	}, checkin_all: function(_this, e){
		var faq_id = $(_this).attr('faq_id'),
			is_checked_in = $(_this).is(':checked') ? 1: 0;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=checkin', {
			'tp' : "_all",
			'faq_id' : faq_id,
			'is_checked_in' : is_checked_in
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(parseInt(is_checked_in)==1){
				$('.checkin_course_'+faq_id).prop('checked', true);
			} else {
				$('.checkin_course_'+faq_id).prop('checked', false);
			}
		});
	}, open_course : function(_this,e){
		e.preventDefault();
		var type = $(_this).data("type"),
			course_id = $(_this).data("course_id"),
			_tp = $(_this).attr("_tp"),
			_form = $(_this).closest("form"),
			formData = new FormData();
		formData.append("_tp",_tp);	
		formData.append("type",type);	
		formData.append("course_id",course_id);	
		if(_tp == "event") {
//			var cat_id = $(_this).attr("cat_id");
//			formData.append("cat_id",cat_id);
		}
		var check = 1;
		if(type == "add" || type == "edit"){			
			$("input.required,select.required",_form).each(function(index,elm){
				if($(elm).val() == '' || $(elm).val() == 0){
					$(elm).addClass("error").focus();
					$Core.util.toggleIndicatior(0);
					check = 0;
					console.log($(elm).val());
					return false;
				}else{
					$(elm).removeClass("error")
				}
			});
			$(".form-field",_form).each(function(index,elm){
				var field = $(elm).attr("name");
				if(field == "fileUpload"){
					formData.append(field,$(elm).prop('files')[0]);
				}else if(field == "content"){	
					var editorId = $(elm).attr('id');
					formData.append(field,$Core.util.getTinyMCEContent(editorId));					
				}else{
					formData.append(field,$(elm).val());	
				}				
			});
			var is_all_staff = $("input[name='is_all_staff']:checked",_form).val();
			formData.append("is_all_staff",is_all_staff);	
		}
		if(check == 0){
			return false;
		}
		$Core.util.toggleIndicatior(1);
		$.ajax({
			url: "/index.php?mod="+MOD+'&sub=course&act=open_course',
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			dataType: "json",
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				if(type == 'open'){
					var __w = $(window).width();
					$Core.popup.open("auto","auto",respJson.html,respJson.uid);
				}else if(type == 'add' || type == 'edit'){
					if(respJson.result){
						$Core.alert.success('Thành công !');
						_form.closest('.modal').modal("hide");
						$("#lstEvent").removeClass("loaded");
						if(_tp == "event") {
							$Core.course.list({"cat_id":respJson.cat_id});	
						}else{
							$Core.course.list({});
						}
						_autoload();
					}else{
						$Core.swal.error("Oops",respJson.msg);
					}
				}
			}
		});
		return false;
	}, delete: (_this, e) => {
		e.preventDefault();
		var course_id = $(_this).data('course_id'),
			$_adata = {'course_id':course_id};
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$.post('/index.php?mod=home&sub=course&act=delete_course', $_adata, function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success')>=0){
					$Core.course.list({});
					$Core.alert.success("Xóa thành công !");
				}
			});
		});
		return false;
	}, loadImage : (_this,e) => {
		var _form = $(_this).closest("form");
		if (e.target.files.length) {
			const src = URL.createObjectURL(e.target.files[0]);
			$(".preview_image",_form).attr("src",src).show();
		}
	}, loadFile : (_this,e) => {
		var _form = $(_this).closest("form");
		if (e.target.files.length) {
			const src = URL.createObjectURL(e.target.files[0]);
			// console.log(e.target.files[0]);
			$("input[name='file']",_form).val(e.target.files[0].name);
		}
	}, select_image: (_this, e) => {
		e.preventDefault();
		e.stopPropagation();
		var toId = $(_this).attr('toId');
		console.log($('#upload_image_'+toId));
		$('#upload_image_'+toId).trigger('click');
		return false;
	}, upload_image : (_this, ev) => {
		ev.preventDefault();
		var _form = $(_this).closest('form'),
			type = $(_this).data('type'),
			toId = $(_this).attr('toId'),
			openFrom = $(_this).attr('openFrom'),
			course_id = $(_this).data('course_id'),
			files = $(_this).prop('files');
		if (files && files.length) {
			var filename = files[0].name,
				extension = filename.substr(filename.lastIndexOf('.') + 1),
				params = {'type' : type, 'course_id':course_id};
			if(extension == "heic"){
				heic2any({
					blob: ev.target.files[0],
					toType: "image/jpg",
					quality: 0.7
				}).then(function (resultBlob) {
					var url = window.URL.createObjectURL(resultBlob);
					let container = new DataTransfer(),
						file = new File(
							[resultBlob], filename.replace('.'+extension,'')+".jpg",
							{type:"image/jpeg", lastModified:new Date().getTime()}
						);
					container.items.add(file);
					ev.target.files = container.files;
					$Core.util.toggleIndicatior(1);
					_this.closest("form").ajaxSubmit({
						type: "POST",
						url: "/index.php?mod="+MOD+"&sub=course&act=upload_image",
						data: $.extend({'is_heic': 1}, params),
						async: false,
						dataType: "html",
						success: function(html) {
							$Core.util.toggleIndicatior(0);
							if(openFrom.indexOf('_pop') >= 0){
								var tmp = html.split('|||');
								$('.course_report_'+uid).html(tmp[1]);
								$Core.course.load_reports(course_id, {});
							} else {
								if($(".home_events").length > 0){
									$(".home_events").removeClass("loaded");
								}
								_autoload();
							}
						}
					});
				}).catch(function (err) {
					$Core.alert.alert(err.message);
				});
			} else {
				var formData = new FormData();
				formData.append('type', type);
				formData.append('openFrom', openFrom);
				formData.append('course_id', course_id);
				formData.append('image', files[0]);
				$Core.util.toggleIndicatior(1);
				$.ajax({
					method: "POST",
					url: "/index.php?mod="+MOD+"&sub=course&act=upload_image",
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function (html) {
						$Core.util.toggleIndicatior(0);
						if(openFrom == '_pop'){
							var tmp = html.split('|||');
							$('.course_report_'+toId).html(tmp[1]);
							$Core.course.load_reports(course_id, {});
						}
						$('.home_events').removeClass("loaded");
						_autoload();
					}
				});
			}
		}
	}, load_reports: (course_id, options) => {
		var $_adata = options || {};
		$_adata['course_id'] = course_id;
		$.post('/index.php?mod=home&sub=course&act=load_reports', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.msg.indexOf('_success')>=0){
				if(respJson._MEDIA_DISSEMINATION == 1){
					$('.tbody_report_staffs_'+course_id).html(respJson.html_report_staffs);
					$('.tbody_unreport_staffs_'+course_id).html(respJson.html_unreport_staffs);
				} else {
					$('.tbody_checkin_staffs_'+course_id).html(respJson.html_checkin_staffs);
				}
			}
		}, 'json');
	}, load_report_course: () => {
		var _form = $("#frm_report_ns_club"), 
			start_date = $("input[name=start_date]",_form).val(),
			end_date = $("input[name=end_date]",_form).val(),
			$_adata = {};
		if(end_date < start_date) {
			end_date = start_date;
			$("input[name=end_date]",_form).val(start_date);
		}
		$("input[name=start_date]",_form).attr("max",end_date);
		$("input[name=end_date]",_form).attr("min",start_date);
		$_adata['start_date'] = start_date;
		$_adata['end_date'] = end_date;
		$.post('/index.php?mod=home&sub=course&act=load_report_course', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$("#lst_ns_club").html(html)
		});
	}, load_block: (_this, e) => {
		var block_id = $(_this).attr('block_id'),
			toId = $(_this).attr("toId"),
			project_id = $(_this).val();
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_block', {
			'block_id' : block_id,	
			'project_id' : project_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if($('#'+toId).hasClass('iso-select2')){
				$('#'+toId).html(html).trigger('change');
			} else {
				$('#'+toId).html(html);
			}
		});
	}, open_report: (_this, e) => {
		e.preventDefault();
		var course_id = $(_this).attr("course_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=course&act=open_report', {"course_id":course_id}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
		},'json');
		return false;
	}, save_report: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			course_id = $(_this).attr("course_id"),
			_validated=0;
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					var label = $(_elem).data("label");
					alertify.error(label + " không để trống!");
					return false;
				}
			});
		}
		if(_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : '/index.php?mod=home&sub=course&act=save_report',
				data : {'course_id':course_id},
				dataType:'html',
				success : function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_error') >= 0){
						$Core.swal.error("Oops","Lỗi. Xin vui lòng thử lại");
					}else {
						$Core.swal.success("Thông báo","Báo cáo thành công!");
					}
					$(".btn-close").trigger("click");
					if($(".home_events.ajax.loaded").length > 0) {
						$(".home_events.ajax.loaded").removeClass("loaded");
						_autoload();
					}
				}
			});
		}
		return false;
	}
};
$Core.birthday = {
	set_time: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			holderG = $(_this).attr('holderG'),
			options = {'holderG':holderG}; 
		$('.js__birthday-tab-link').removeClass('active');
		$(_this).addClass('active');
		$('[data-bind='+toId+']')
			.removeClass('loaded')
			.data('options', options );
		_autoload();
		return false;
	}
}
$Core.stock = {
	load_more: (_this, e) => {
		e.preventDefault();
		var page = $(_this).attr('page');
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.stock.load_stock(project_id, {'page':page}, "more");
		}
		return false;
	}, load_stock: (project_id, options, type = "append") => {
		var $_adata = options || {};
		$_adata['project_id'] = project_id;
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field'),
					field_type = $(_elem).attr('type');
				if(field_type=='checkbox'){
					var _class = $(_elem).data('class');
					$_adata[field] = $Core.util.getCheckBoxValueByClass(_class);
				} else {
					$_adata[field] = $(_elem).val();
				}
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=project&act=load_stock',$_adata,function(respJson){
			$Core.util.toggleIndicatior(0);
			if(parseInt(respJson.total_page) >= (parseInt(respJson.current_page)+1)){
				$('.showmorethisresult').removeClass('d-none').removeClass('clicked');
			} else {
				$('.showmorethisresult').addClass('d-none');
			}
			if(type == 'more'){
				$(`.holder_stock_${project_id} tr.p_row:last`).after(respJson.html);
			}else{
				$('.total_stock').text(respJson.total_record);
				$(`.holder_stock_${project_id}`).html(respJson.html);
			}
			$('.js__search-TCBG-select-list').html(respJson.html_options_TCBG).multiselect('rebuild');
			$('.showmorethisresult').attr('page', parseInt(respJson.current_page)+1);
		},'json');
	}, load_stock_new: (project_id, options, type = "append") => {
		var $_adata = options || {};
		$_adata['project_id'] = project_id;
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field'),
					field_type = $(_elem).attr('type');
				if(field_type=='checkbox'){
					var _class = $(_elem).data('class');
					$_adata[field] = $Core.util.getCheckBoxValueByClass(_class);
				} else {
					$_adata[field] = $(_elem).val();
				}
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=project&act=load_stock_new',$_adata,function(respJson){
			$Core.util.toggleIndicatior(0);
			if(parseInt(respJson.total_page) >= (parseInt(respJson.current_page)+1)){
				$('.showmorethisresult').removeClass('d-none').removeClass('clicked');
			} else {
				$('.showmorethisresult').addClass('d-none');
			}
			if(type == 'more'){
				$(`.holder_stock_${project_id} tr.p_row:last`).after(respJson.html);
			}else{
				$('.total_stock').text(respJson.total_record);
				$(`.holder_stock_${project_id}`).html(respJson.html);
			}
			$('.js__search-TCBG-select-list').html(respJson.html_options_TCBG).multiselect('rebuild');
			$('.showmorethisresult').attr('page', parseInt(respJson.current_page)+1);
		},'json');
	},load_more_new: (_this, e) => {
		e.preventDefault();
		var page = $(_this).attr('page');
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.stock.load_stock_new(project_id, {'page':page}, "more");
		}
		return false;
	}, load_range: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			list_block_ids = $(_this).val(),
			project_id = $(_this).attr('project_id');
		$Core.stock.load_stock(project_id, {});
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=project&act=load_range',{
			'project_id' : project_id,
			'list_block_ids' : list_block_ids
		},function(html){
			$('#'+toId).html(html).multiselect('rebuild');
		});
	}, clear_search: (_this, e) => {
		e.preventDefault();
		var _group = $(_this).closest('.btn-group'),
			_dropdown = $('.dropdown-menu', _group),
			_text = $('.multiselect-selected-text', _group).attr('text'),
			_max_range = (block_type==_BLOCK_TYPE_LOWFLOOR_SALE) ? 150 : 150,
			_divider = (block_type==_BLOCK_TYPE_LOWFLOOR_SALE) ? 1000000000 : 1000000;
		if(_group.hasClass('js__block-price-list')){
			$('input[name=price_min]').val(0);
			$('input[name=price_max]').val(_max_range*_divider);
		} else {
			$('input[name=area_min]').val(0);
			$('input[name=area_max]').val(500);
		}
		$('.multiselect-selected-text', _group).text(_text);
		$('.dropdown-toggle', _group).dropdown('toggle');
		$Core.stock.load_stock(project_id, {});
		return false;
	}, start_search: (_this, e) => {
		e.preventDefault();
		var _group = $(_this).closest('.btn-group'),
			_dropdown = $('.dropdown-menu', _group),
			_text = $('.multiselect-selected-text', _group).attr('text'),
			_max_range = (block_type==_BLOCK_TYPE_LOWFLOOR_SALE) ? 150 : 150,
			_subfix = (block_type==_BLOCK_TYPE_LOWFLOOR_SALE) ? 'tỷ' : 'triệu',
			_divider = (block_type==_BLOCK_TYPE_LOWFLOOR_SALE) ? 1000000000 : 1000000;
		if(_group.hasClass('js__block-price-list')){
			var _min_price = $('input[name=price_min]').val(),
				_max_price = $('input[name=price_max]').val(),
				_min = $Core.util.toNumber(_min_price)/_divider,
				_max = $Core.util.toNumber(_max_price)/_divider;
			if(parseInt(_min) == 0 && parseInt(_max) == _max_range){
				$('.multiselect-selected-text', _group).text(_text);
			} else {
				$('.multiselect-selected-text', _group).text(`${_min}-${_max} ${_subfix}`);
			}
		} else if(_group.hasClass('js__block-area-list')){
			var _min = $('input[name=area_min]').val(),
				_max = $('input[name=area_max]').val();
			if(parseInt(_min) == 0 && parseInt(_max) == 500){
				$('.multiselect-selected-text', _group).text(_text);
			} else {
				$('.multiselect-selected-text', _group).text(`${_min}-${_max} m2`);
			}
		}
		$('.dropdown-toggle', _group).dropdown('toggle');
		$Core.stock.load_stock(project_id, {});
		return false;
	}, do_search: (_this, e) => {
		$Core.stock.load_stock(project_id, {});
	}, hide_stock_cross : (_this, e) => {
		e.preventDefault();
		var building_id = $(_this).attr('building_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=project&act=hide_stock_cross',{
			'building_id' : building_id
		},function(html){
			window.location.reload();
		});
		return false;
	}, toggle_floor_empty: (_this, e) => {
		e.preventDefault();
		$(_this).toggleClass('clicked');
		var isToggled = $(_this).hasClass('clicked') ? 1 : 0;
		$('.table-stock > tbody > tr:not(.tr_hidden)').each((_i, _tr) => {
			var total_stocks = 0;
			$(_tr).find('td').each((_ii, _td) => {
				if ($(_td).is(':has(a)')) {
					total_stocks ++;
				}
			});
			if(total_stocks == 0 && isToggled == 1){
				$(_tr).not('[class^="nohover"]').addClass('d-none is_hidden');
				$(_tr).find('td[class^="td_hidden_row"]').each((_ii, _td) => {
					var rowspan = $(_td).attr('rowspan'),
						floor = $(_td).attr("floor"),
						th_rowspan_hidden = $('.tr_hidden_'+floor).length,
						rowspan_tr_hidden = parseInt(rowspan);
						rowspan_tr_hidden -= 1;	
					var tr_hidden = $("tr."+floor+".d-none").length;
					/*console.log(floor + "------"+"tr.d-none."+floor+"--------" + tr_hidden);*/
					$(_td).prop('rowspan',rowspan_tr_hidden).attr('rowspan_ex', parseInt(rowspan));
					/*console.log(floor + "------[" + total_stocks + "]------" + rowspan_tr_hidden);*/
				});
			} else {
				$(_tr).find('td[class^="td_hidden_row"]').each((_ii, _td) => {
					var rowspan = $(_td).attr('rowspan_ex'),
						floor = $(_td).attr("floor");	
					$(_td).prop('rowspan',parseInt(rowspan));
					/*console.log(floor + "------" + rowspan);*/
				});
				if($(_tr).hasClass('is_hidden')) {
					$(_tr).removeClass('d-none is_hidden');	
				}				
			}
			if(isToggled == 1){
				$(_this).html(`<i class="fa fa-eye-slash"></i> Hiện tầng trống`);
			} else {
				$(_this).html(`<i class="fa fa-eye"></i> Ẩn tầng trống`);
			}
		});
		return false;
	}, config_block : (_this, e) => {
		e.preventDefault();
		var block_id = $(_this).attr('block_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=project&act=config_block',{
			'block_id' : block_id
		},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, do_config_block : (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form'),
			block_id = $(_this).attr('block_id');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: '/index.php?mod='+MOD+'&sub=project&act=do_config_block',
			data: {'block_id' : block_id},
			dataType: 'html',
			success: function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					$Core.alert.success('Thành công !');
				} else {
					$Core.alert.error('Đã xảy ra lỗi !');
				}
			}
		});
		return false;
	}, do_crawl: (_this, e) => {
		e.preventDefault();
		var block_id = $(_this).attr('block_id'),
			resource = $(_this).attr('resource');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=project&act=do_crawl',{
			'block_id' : block_id,
			'resource' : resource
		},function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.swal.success('Thông báo', `Cập nhật thành công ${respJson.total_updated} căn hộ`);
		}, 'json');
		return false;
	}, setBgSold: (_this, e) => {
		e.preventDefault();
		var _type = $(_this).data("type");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=project&act=setBgSold',{
			'type' : _type
		},function(respJson){
			$Core.util.toggleIndicatior(0);
			$(".btn_bg_sold").removeClass("active");
			$(_this).addClass("active");
			if(_type == "dark") {
				$(".bg_sold").addClass("dark").removeClass("light");
			}else{
				$(".bg_sold").addClass("light").removeClass("dark");
			}
		});
		return false;
	},
}
$Core.billing = $.extend($Core.global.billing, {
	init: () => {
		$_document.on('click', '.dropdown-button', e => {
			e.stopPropagation();
			const b = $(e.currentTarget), o = b.offset(),
				  m = b.siblings('.dropdown-menu').clone()
					  .addClass('dropdown-menu-floating')
					  .css({position:'absolute',display:'block'})
					  .appendTo('body');
			$('.dropdown-menu-floating').not(m).remove();
			m.css({
				top:o.top+b.outerHeight(),
				left: m.hasClass('dropdown-menu-start') 
					? o.left : o.left + b.outerWidth() - m.outerWidth(),
				zIndex:1000
			});
			$_document.one('click',() => m.remove() );
		});
	}, load_block: (_this, e) => {
		var _form = $(_this).closest("form"),
			block_id = $(_this).attr('block_id'),
			project_id = $(_this).val();
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=load_block', {
			'block_id' : block_id,	
			'project_id' : project_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('select[name=block_id]',_form).html(html);
			var total_option = $('select[name=block_id] option',_form).length;
			if(project_id == _PROJECT_OTHER_ID || total_option < 2) {
				$('select[name=block_id]',_form).removeClass("required");
			}else{
				$('select[name=block_id]',_form).addClass("required");
			}
		});
	}, sync_commission: (_this, e) => {
		e.preventDefault();
		var billing_id = $(_this).attr('billing_id'),
			$_adata = {'billing_id': billing_id};
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod=acc&sub=commission&act=sync', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
		});
		return false;
	}, edit_inline_field: (_this, options) => {
		var $_adata = options || {},
			p_id= $(_this).attr('p_id'),
			p_field= $(_this).attr('p_field'),
			p_cell = $(_this).closest('.InputCRMHandler');
		p_cell.html('<img src="'+URL_IMAGES+'/ripple-loading.svg" />');
		$.post(path_ajax_script+'/index.php?mod=home&act=load_edit_inline_field', $_adata, function(html){
			p_cell.html(html);
			setTimeout(() => {
				$('.edit_profile_field_'+p_field+'_'+p_id).focus();
			}, 500);
		});
		return false;
	}, save_edit_inline_field: (_this, e) => {
		e.preventDefault();
		var _body = $(_this).closest('.modal-body')
			,p_id= $(_this).attr('p_id'),
			p_field= $(_this).attr('p_field'),
			p_cell = $(_this).closest('.InputCRMHandler'),
			p_value = $('.edit_profile_field_'+p_field+'_'+p_id, _body).val();
		//alert(p_value); return false;
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod=home&act=load_edit_inline_field', {
			'p_id' : p_id,
			'p_field' : p_field,
			'p_value' : p_value,
			'p_action' : '_save'
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_error_code') >= 0){
				$Core.messager.alert('Thông báo', 'Mã nhân viên đã tồn tại !');
			} else if(html.indexOf('_error_email') >= 0){
				$Core.messager.alert('Thông báo', 'Địa chỉ email này đã tồn tại !');
			} else {
				p_cell.html(html);
			}
		});
		return false;
	}, cancel_edit_inline_field: (_this, e) => {
		e.preventDefault();
		var p_id= $(_this).attr('p_id'),
			p_field= $(_this).attr('p_field'),
			p_cell = $(_this).closest('.InputCRMHandler');
		p_cell.html('<img src="'+URL_IMAGES+'/ripple-loading.svg" />');
		$.post(path_ajax_script+'/index.php?mod=home&act=load_edit_inline_field', {
			'p_id' : p_id,
			'p_field' : p_field,
			'p_action' : '_cancel'
		}, function(html){
			p_cell.html(html);
		});
		return false;
	}, autosave_inline_field: (_this, options) => {
		var $_adata = options || {},
			p_id= $(_this).attr('p_id'),
			p_field= $(_this).attr('p_field'),
			p_value = $(_this).val();
		$_adata['p_value'] = $.trim(p_value);
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=autosave_inline_field', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
		});
		return false;
	}, open_sp_file: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field'),
			$_adata = {'p_id':p_id, 'p_field':p_field, 'toId':toId};
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=open_sp_file', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, add_sp_file: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field'),
			$_adata = {'toId':toId, 'p_id':p_id, 'p_field':p_field};
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=add_sp_file', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			if($('.tr_'+p_field+'_'+toId+':last').length){
				$('.tr_'+p_field+'_'+toId+':last').after(html);
			} else {
				$('.holder_'+p_field+'_'+toId).html(html);
			}
		});
		return false;
	}, save_sp_file: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			form = $(_this).closest('form'),
			toId = $(_this).attr('toId'),
			p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field');
		if($('input.required,select.required', form).length){
			$('input.required,select.required', form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			form.ajaxSubmit({
				type: 'POST',
				url: '/index.php?mod='+MOD+'&act=save_sp_file',
				data: {'p_id':p_id, 'p_field':p_field},
				dataType: 'html',
				success: function(html){
					$Core.util.toggleIndicatior(0);
					$Core.popup.close(form.closest('.modal'));
					if(!$Core.util.isEmpty(toId)){
						var tmp = html.split('|||');
						$('#'+p_field+'_'+toId).html(tmp[1]);
					} else {
						load_billing_sold(p_id, {});
					}
					$Core.alert.success('Thành công !');
				}
			});
		}
		return false;
	}, filter_by_changed: (_this, e) => {
		var _filter_by = $(_this).val(),
			_project_id = $('select[name=project_id]').getAttr('project_id', 0),
			_staff_id = $('select[name=staff_id]').getAttr('staff_id', 0),
			_dept_id = $('select[name=dept_id]').getAttr('dept_id', 0);
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=get_filter_by', {
			'filter_by' : _filter_by,
			'project_id': _project_id,
			'staff_id': _staff_id,
			'dept_id': _dept_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('select[name=project_id]').html(respJson.html_projects);
			var $_select_staff = $('select[name=staff_id]').selectize(),
				$_selectize_staff = $_select_staff[0].selectize,
				$_select_dept = $('select[name=dept_id]').selectize(),
				$_selectize_dept = $_select_dept[0].selectize;
			/** Staff */
			$_selectize_staff.clear();
			$_selectize_staff.clearOptions();
			$_selectize_staff.addOption(respJson.arr_staffs);
			$_selectize_staff.refreshOptions(false);
			if(typeof(_staff_id) !== 'undefined' && parseInt(_staff_id, 10) > 0){
				$_selectize_staff.addItem(_staff_id);
			}
			/** Department */
			var originalTrigger = $_selectize_dept.trigger;
			$_selectize_dept.trigger = function(){};
			$_selectize_dept.clear();
			$_selectize_dept.clearOptions();
			$_selectize_dept.addOption(respJson.arr_departments);
			$_selectize_dept.refreshOptions(false);
			if(typeof(_dept_id) !== 'undefined' && parseInt(_dept_id, 10) > 0){
				$_selectize_dept.addItem(_dept_id);
			}
			$_selectize_dept.trigger = originalTrigger;
		},'json');
	}, handle_dep_changed: (_this, e) => {
		var department_id = $(_this).val(),
			staff_id = $(_this).getAttr('staff_id', 0);
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=booking&act=load_staff', {
			'staff_id' : staff_id,
			'department_id' : department_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var $_selectize = $('select[name=staff_id]')[0].selectize;
			$_selectize.settings.render = {
				option: (item, escape) => {
					return `<div class="d-flex align-items-center">
						<img class="avatar avatar-xxs rounded-pill me-1" src="${item.image}">
						<span class="text-dark">${item.text}</span>
					</div>`;
				}, item: (item, escape) => {
					return `<div>
						<img class="avatar avatar-xxs rounded-pill me-1" src="${item.image}">
						<span class="text-dark">${escape(item.text)}</span>
					</div>`;
				}
			};
			$_selectize.clear();
			$_selectize.clearOptions();
			$_selectize.setupTemplates();
			$_selectize.addOption(respJson);
			$_selectize.refreshOptions(false);
		}, 'json');
	}, select_image: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			toId = $(_this).attr('toId');
		$('#'+toId).attr('uid', uid).trigger('click');
		return false;
	}, delete_sp_file: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			p_field = $(_this).attr('p_field');
		$('.tr_'+p_field+'_'+uid).remove();
		return false;
	}, upload_image: (_this, e) => {
		var _tp = $(_this).attr('tp'),
			_uid = $(_this).attr('uid'), 
			_p_id = $(_this).attr('p_id'), 
			_p_field = $(_this).attr('p_field'),
			_form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);	
		_form.ajaxSubmit({
			type : 'POST',
			url : '/index.php?mod=ajax&sub=helper&act=uploadImage',
			data : {'tp':_tp},
			dataType:'html',
			success : function(html){
				_form.clearForm();
				_form.resetForm();
				$Core.util.toggleIndicatior(0);
				$('.'+_p_field+'_'+_uid).val(html);
			}
		});	
	}, upload_clipboard: (_this, e) => {
		for (var i = 0 ; i < e.clipboardData.items.length ; i++) {
			var item = e.clipboardData.items[i];
			if (item.type.indexOf("image") != -1) {
				$Core.billing.do_upload_clipboard(_this, item.getAsFile());
			}
		}
	}, do_upload_clipboard: (_this, file) => {
		var formData = new FormData(),
			_uid = $(_this).attr('uid'),
			_p_id = $(_this).attr('p_id'),
			_p_field = $(_this).attr('p_field');
		formData.append('p_id', _p_id); 
		formData.append('p_field', _p_field); 
		formData.append('image', file); 
		$Core.util.toggleIndicatior(1);
		$.ajax({
			url: "/index.php?mod=ajax&sub=helper&act=uploadImage",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				$('.'+_p_field+'_'+_uid).val(html);
			}
		});
	}, update_field: (_this, e) => {
		e.preventDefault();
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			var billing_id = $(_this).attr('billing_id'),
				open_from = $(_this).attr('open_from'),
				_form = $(_this).closest('form');
			// alert(open_from); return false;
			$Core.util.toggleIndicatior(1);	
			_form.ajaxSubmit({
				type : 'POST',
				url : '/index.php?mod=home&act=update_field',
				data : {'billing_id':billing_id},
				dataType:'json',
				success : function(respJson){
					$Core.util.toggleIndicatior(0);
					$(_this).removeClass('clicked');
					if(open_from == "_mobile"){
						$('.btn-close', _form).trigger('click');
					} else {
						$('.bs-webui-popover').webuiPopover('hideAll');
					}
					$('.agree_date_'+billing_id).html(respJson.agree_date);
					$('.estimate_date_'+billing_id).html(respJson.estimate_date);
					$('.contract_date_'+billing_id).html(respJson.contract_date);
				}
			});	
		}
		return false;
	}, add_info: (_this, e) => {
		e.preventDefault();
		var billing_id = $(_this).attr('billing_id'),
			$_adata = {'billing_id' : billing_id};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=add_info', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
		},'json');
		return false;
	}, add_co_sale: (_this, e) => {
		$('#co_sale_list .co-sale-row.d-none').first().removeClass('d-none');
		if(!$('#co_sale_list .co-sale-row.d-none').length){
			$('.btn_add_co_sale').prop('disabled', true);
		}
		return false;
	}, remove_co_sale: (_this, e) => {
		var $row = $(_this).closest('.co-sale-row'),
			sel = $row.find('select')[0];
		if(sel && sel.selectize){ sel.selectize.clear(); }
		$row.find('.co-ratio').val('');
		$row.addClass('d-none');
		$('.btn_add_co_sale').prop('disabled', false);
		$Core.billing.update_co_sale_hint();
		return false;
	}, update_co_sale_hint: () => {
		var sum = 0;
		$('#co_sale_list .co-sale-row:not(.d-none) .co-ratio').each(function(){
			sum += parseFloat($(this).val()) || 0;
		});
		var main = 100 - sum;
		if(main < 0){ main = 0; }
		$('.co_main_ratio').text(main);
	}, save_info: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			billing_id = $(_this).attr('billing_id'),
			uid = $(_this).attr('uid'),
			is_ignore_confirmed = $(_this).attr('is_ignore_confirmed');
		if($('select.required,input.required,textarea.required', _form).length){
			$('select.required,input.required,textarea.required', _form).each((_i, _elem) => {
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
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=upd_billing_sold",
				data: {'billing_id':billing_id,'is_ignore_confirmed':is_ignore_confirmed,'uid':uid},
				dataType: "JSON",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.msg.indexOf('_success') >= 0){
						$Core.popup.close(_form.closest('.modal'));
					} else if(respJson.msg.indexOf('_confirm_changed') >= 0){
						$Core.popup.open('auto','auto',respJson.html,respJson.uid);
					} else if(respJson.msg.indexOf('_error') >= 0){
						$Core.swal.error("Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} 
				}
			});
		}
		return false;
	}, toggle_rescheduling_reason: (_this,e) => {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			contract_date = $(_this).val(),
			value_old = $(_this).attr("value_old"),
			toCls = $(_this).attr("toCls"),
			time_now = new Date();		
		contract_date = new Date(contract_date);
		if(value_old != "" && contract_date.getTime() > time_now.getTime()) {
			$("."+toCls,_form).removeClass("d-none");
		}else{
			$("."+toCls,_form).addClass("d-none");
			$("textarea[name=rescheduling_reason]",_form).val("");
		}
	}, set_action_visible: (_this, e) => {
		var is_action_visible = $(_this).is(':checked') ? 1 : 0;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=upd_action_visible', {
			'is_action_visible' : is_action_visible
		}, function(html){
			if(html.indexOf('_success') >= 0){
				window.location.reload();
			} else {
				$Core.swal.error("Thông báo", "Đã xảy ra lỗi !");
			}
		});
	}, do_checked : (_this, e) => {
		if($(_this).hasClass('chk_all')){
			var _checked = $(_this).is(':checked') ? 1 : 0;
			$('.chk_item').prop('checked', _checked);
			$('.js__dropdown-action').prop('disabled', !_checked);
		} else {
			var _checkall = 1, _total_checked =0;
			$('.chk_item').each((_i, _elem) => {
				if(!$(_elem).is(':checked')){
					_checkall = 0;
				} else {
					_total_checked += 1;
				}
			});
			$('.chk_all').prop('checked', _checkall);
			$('.js__dropdown-action').prop('disabled', (_total_checked > 0) ? false : true);
		}
	}, do_action: (_this, e) => {
		e.preventDefault();
		var $_this = $(_this),
			$_form = $(_this).closest('form'),
			action = $(_this).attr('action'),
			list_id = $Core.util.getCheckBoxValueByClass('chk_item');
		if(!$Core.util.isEmpty(action) && !$Core.util.isEmpty(list_id)){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=do_action', {
				'action' : action,
				'list_id' : list_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				window.location.reload(true);
			},'json');
		}	
		return false;
	},
});
$Core.dashboard = $.extend($Core.global.sop, $Core.global.dashboard, {
	toggleRow: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('.open').removeClass('open');
		$('.kZnqTyguZE').addClass('d-none');
		$(_this).addClass('open');
		$('#'+toId).removeClass('d-none');
		return false;
	}, reload: (_this, e) => {
		var options = {},
			gId = $(_this).attr('gId'),
			name = $(_this).attr('name');
		if(name== 'date_type'){
			var date_type = $(_this).val(),
				year = $("select[name='year'][gId='"+gId+"']").val();
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=dashboard&act=load_month', {
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
				_autoload();
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
				_autoload();
			} else {
				$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=dashboard&act=load_month', {
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
					_autoload();
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
			$('.ajax[gId='+gId+']').data('options',params);
			$('.ajax[gId='+gId+']').removeClass('loaded');
			_autoload();
		}
		if(SUB == "dashboard" && ACT == "sale") {
			$Core.dashboard_sale.load_total_dashboard();
		}
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
					class="form-control js__booking_date search_field" data-field="date" onChange="$Core.dashboard.handle_booking_changed(this, event)" />`).trigger('change');
			} else if(_value == 'month'){
				var month = $Core.util.plz(today.getMonth() + 1),
					year = today.getFullYear();
				$('.js__booking_date').replaceWith(`<input gId="${_gId}" type="month" value="${year}-${month}" data-field="month" class="form-control js__booking_date search_field" onChange="$Core.dashboard.handle_booking_changed(this, event)" />`).trigger('change');
			} else if(_value == 'week'){
				var _week = $Core.util.getISOWeekNumber(),
					week = $Core.util.plz(_week),
					year = today.getFullYear();
				$('.js__booking_date').replaceWith(`<input gId="${_gId}" type="week" value="${year}-W${week}" data-field="week" class="form-control js__booking_date search_field" onChange="$Core.dashboard.handle_booking_changed(this, event)"/>`).trigger('change');
			} else if(_value == 'year'){
				var _start_year = 2023, 
					_end_year = today.getFullYear(),
					html_select = `<select gId="${_gId}" class="form-control form-select js__booking_date search_field" data-field="year" onChange="$Core.dashboard.handle_booking_changed(this, event)">`;
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
	}, open_booking : (_this, e) => {
		e.preventDefault();
		var more = {},
			holderG = $(_this).attr('holderG'),
			status_id = $(_this).attr('status_id'),
			query_string = $(_this).attr('query_string');
		if(holderG == 'staff'){
			var staff_id = $(_this).attr('staff_id');
			more['staff_id'] = staff_id;
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=developer&act=open_booking', {
			'holderG' : holderG,
			'status_id' : status_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$Core.dashboard.load_booking(respJson.uid, $.extend(more, {
				'holderG':holderG, 
				'status_id':status_id,
				'query_string' : query_string
			}), false);
		}, 'json');
		return false;
	}, load_booking: (uid, options, isLoading=true) => {
		var $_adata = options || {};
		isLoading && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=developer&act=load_report_booking', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(`.holder_${uid}`).html(respJson.html);
			$(`.total_records_${uid}`).text(respJson.total_record);
			if(parseInt(respJson.total_page) > 0 && parseInt(respJson.total_record) > 0){
				$(`.pager_${uid}`).pagination({
					listStyle:"pagination justify-content-center",
					currentPage: respJson.current_page, 
					itemsOnPage: respJson.per_page,
					items: respJson.total_record,
					cssStyle: 'light-theme',
					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
					hrefTextPrefix: 'javascript:void(0);',
					onPageClick : function(pageNumber){
						$Core.dashboard.load_booking(uid, $.extend($_adata,{'page':pageNumber}));
					}
				});
			} else {
				$(`.pager_${uid}`).empty();
			}
		}, 'json');
	}, open_config_project: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=dashboard&act=open_config_project', {
			'gId' : $(_this).attr('gId')
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, save_config_project : (_this, e) => {
		e.preventDefault();
		var _validated = 0, 
			_gId = $(_this).attr('gId'),
			_form = $(_this).closest('form');
		if($('select.required:visible', _form).length){
			$('select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).trigger('open');
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&sub=dashboard&act=save_config_project",
				data: {},
				dataType:"json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					$('.btn-close', _form).trigger('click');
					var $_select_project = $(`select[name=project_id][gId=${_gId}]`).selectize(),
						$_selectize_project = $_select_project[0].selectize,
						$_select_block = $(`select[name=block_id][gId=${_gId}]`).selectize(),
						$_selectize_block = $_select_block[0].selectize;
					/** Project */
					if(typeof(respJson.project_id) !== 'undefined' && parseInt(respJson.project_id, 10) > 0){
						$_selectize_project.addItem(respJson.project_id);
					}
					/** Block */
					var params = {};
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
			});
		}
		return false;
	}, open_clb : (_this, e) => {
		e.preventDefault();
		var clb_id = $(_this).attr('clb_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=dashboard&act=open_clb', {
			'clb_id' : clb_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, handle_ranking_dept: (_this, e) => {
		e.preventDefault();
		var gId = $(_this).attr('gId'),
			date_type = $(_this).attr('date_type'),
			params = $(`.ajax[gId=${gId}]`).data('options');
		params['gId'] = gId;
		params['date_type'] = date_type;
		$(`.ranking__dept-link[gId=${gId}]`).removeClass('active');
		$(_this).addClass('active');
		$(`.ajax[gId=${gId}]`).data('options',params);
		$(`.ajax[gId=${gId}]`).removeClass('loaded');
		_autoload();
		return false;
	}, open_full: (_this, e) => {
		e.preventDefault();
		var tp = $(_this).attr('tp');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=dashboard&act=open_full', {
			'tp' : tp
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			_autoload();
		}, 'json');
		return false;
	}, open_report_stock_hug: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=dashboard&act=open_report_stock_hug', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
	}, load_stock_admin: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest("form");
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&sub=dashboard&act=load_stock_admin",
			dataType:"json",
			success: function(respJson){
				$("#table_agent_admin tbody").html(respJson.html);
			}
		});
	}, load_profile: (_this, e) => {
		e.preventDefault();
		var department_id = $(_this).val(),
			gid = $(_this).attr("gid");
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=dashboard&act=load_profile', {
			department_id:department_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(html != "") {
				$("#profile_"+gid).html(html).show();
			}else{
				$("#profile_"+gid).hide();
			}
			$Core.dashboard.reload($(_this),e)
		},"html");
	}, handle_person_chart : (_this, e) => {
		e.preventDefault();
		var params = {},
			gId = $(_this).attr('gId'),
			type_id = $(_this).attr('type_id');
		params['type_id'] = type_id;
		$('.mztvVEqTks').removeClass('btn-outline-primary');
		$(_this).addClass('btn-outline-primary');
		$('.ajax[gId='+gId+']').data('options', params).removeClass('loaded');
		_autoload();
	}, addTargetSales: (_this,e) => {
		e.preventDefault();
		var action = $(_this).attr("action"),
			_parent = $(_this).closest(".target_sale"),
			$_adata = {"action":action};
		if(action == "_SAVE") {
			var _modal = $(_this).closest(".modal"),
				_form = $(_this).closest("form");
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL+'/index.php?mod=home&sub=dashboard&act=addTargetSales',
				data: $_adata,
				dataType:"json",
				success: function(respJson){
					$Core.util.toggleIndicatior(0);
					if(respJson.result) {
						alertify.success(respJson.msg);
						$(".btn-close",_modal).trigger("click");
						$(".box_target_sale").removeClass("loaded");
						_autoload();	
					}else{
						alertify.error(respJson.msg);
						setTimeout(function(){
							window.location.reload();
						},500);
					}
				}
			});
		}else if(action == "_OPEN"){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod=home&sub=dashboard&act=addTargetSales', $_adata, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			}, 'json');
		}
		return false;
	}, loadTargetType: (_this,e) => {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			_type = $(_this).val();
		if(_type == 1) {
			$("input.input_value",_form).addClass("price-In").val(0);
		}else{
			$("input.input_value",_form).removeClass("price-In").addClass("numberOnly").val(0);
		}
	}
});
$Core.euro = {
	open: (_this, e) => {
		e.preventDefault();
		var overtime_id = $(_this).attr('overtime_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open', {
			'overtime_id' : overtime_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
}
$Core.tool = {
	init: () => {
		$_document.on('keyup', '.iso_search_field', function(){
			var _this = $(this),
				toClass = _this.attr('toClass'),
				searchTxt = _this.val().toLowerCase();	
			$('.'+toClass).each((_i, _elem) => {
				var have_result, lstToClass = $(_elem).find('.iso_search_item');
				lstToClass.each((_ii, _elem2) => {
					var _text = $(_elem2).text();
					if(_text.toLowerCase().indexOf(searchTxt) != -1){
						have_result=1;
					}
				});	
				if(have_result==1){
					$(_elem).show();	 	
				}else{
					$(_elem).hide();
				}
			});
		});
		if($('.js__dropdown-rss_slider').length){
			$('.js__dropdown-rss_slider').each((_i, _elem) => {
				var _rsSlider,
					_id = $(_elem).attr('id'),
					_target = $(_elem).data('target');
				$('#'+_id).on('shown.bs.dropdown', function(){
					var _values = [0,50,75,100,125,150,200,250,300],
						_min = $('input[name='+_target+'_min]').val(),
						_max = $('input[name='+_target+'_max]').val();
					if(_target=='price'){
						_min = $Core.util.shortNumber(_min);
						_max = $Core.util.shortNumber(_max);
						_values = [0,10,15,20,25,30,35,40,45,50];
					} else {
						_min = parseInt(_min);
						_max = parseInt(_max);
					}
					_rsSlider = new rSlider({
						target: '#'+_target+'-range',
						values: _values,
						range: true,
						labels: true,
						tooltip: false,
						scale: true,
						set: [_min, _max],
						onChange: function (vals) {
							var _tmp = vals.split(','),
								_min_value = _tmp[0],
								_max_value = _tmp[1];
							if(_target == 'price'){
								_min_value = $Core.chart.formatPrice(_min_value*1000000000);
								_max_value = $Core.chart.formatPrice(_max_value*1000000000);
							}
							$('input[name='+_target+'_min]').val(_min_value);
							$('input[name='+_target+'_max]').val(_max_value);
						}
					});
				}).on('hidden.bs.dropdown', function(){
					_rsSlider.destroy();
				});
			});
		}
	}, clear_search: (_this, e) => {
		e.preventDefault();
		var _group = $(_this).closest('.btn-group'),
			_dropdown = $(_this).closest('.dropdown-menu'),
			_text = $('.select-text-content', _group).attr('text');
		if(_group.hasClass('js__block-block-list')){
			$('input[name=block_id]').val(0);
			$('.js__dropdown-list-range').empty();
			$('.js__dropdown-list').addClass('d-none');
			$('.js__dropdown-list-block').removeClass('d-none');
		} else {
			if($('.form-check-input', _dropdown).length){
				$('.form-check-input', _dropdown).prop('checked', false);
			}
		}
		$('.dropdown-toggle', _group).dropdown('toggle');
		$('.dropdown-toggle', _group).removeClass('is-active');
		$('.select-text-content', _group).attr('title',"").text(_text);	
		$Core.stock.load_stock(project_id, {});
		return false;
	}, do_search: (_this, e) => {
		e.preventDefault();
		var _group = $(_this).closest('.btn-group'),
			_dropdown = $(_this).closest('.dropdown-menu'),
			_text = $('.select-text-content', _group).attr('text');
		if($('.form-check-input', _dropdown).length){
			var titles = new Array();
			$('.form-check-input:checked', _dropdown).each((_i, _elem) => {
				var title = $(_elem).attr('title');
				titles.push(title);
			});
			if(titles.length > 0){
				$('.select-text-content', _group).attr('title',titles.join(',')).text(titles.join(','));
			} else {
				$('.select-text-content', _group).attr('title',"").text(_text);
			}
		}
		$('.dropdown-toggle', _group).dropdown('toggle');
		$('.dropdown-toggle', _group).addClass('is-active');
		$Core.stock.load_stock(project_id, {});
		return false;
	}, select_block: (_this, e) => {
		e.preventDefault();
		var _block_id = $(_this).attr('block_id'),
			_block_title = $(_this).attr('title'),
			_group = $(_this).closest('.btn-group');
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&sub=project&act=load_range_dropdown', {
			'block_id' : _block_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('input[name=block_id]').val(_block_id);
			$('.js__dropdown-list').addClass('d-none');
			$('.js__dropdown-list-range').removeClass('d-none').html(respJson.html);
			$('.select-text-content', _group).text(_block_title);
		}, 'json');
		return false;
	}, go_back: (_this, e) => {
		e.preventDefault();
		var _group = $(_this).closest('.btn-group'),
			_text = $('.select-text-content', _group).attr('text');
		$('input[name=block_id]').val(0);
		$('.select-text-content', _group).text(_text);
		$('.js__dropdown-list').addClass('d-none');
		$('.js__dropdown-list-range').empty();
		$('.js__dropdown-list-block').removeClass('d-none');
		return false;
	},
}
$Core.project = {
	toggle_row_stock: (_this, e) => {
		e.preventDefault();
		var _table = $(_this).closest("table"),
			toCls = $(_this).attr('toCls'),
			rowspan = parseInt($(".td_hidden_row_"+toCls).attr("rowspan")),
			rowspan_ex = parseInt($(".td_hidden_row_"+toCls).attr("rowspan_ex"));
		$(_this).find("i").toggleClass("rotate-180");
		if($(_this).hasClass("hide")) {
			$(".tr_hidden_"+toCls,_table).removeClass("d-none");
			$(".td_hidden_row_"+toCls).attr("rowspan",rowspan+3);
			$(".td_hidden_row_"+toCls).attr("rowspan_ex",rowspan_ex+3);
		}else{
			$(".tr_hidden_"+toCls,_table).addClass("d-none");
			$(".td_hidden_row_"+toCls).attr("rowspan",rowspan-3);	
			$(".td_hidden_row_"+toCls).attr("rowspan_ex",rowspan_ex-3);		
		}
		$(_this).toggleClass("hide");
		return false;
	}, toggle_info: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$(_this).toggleClass('show-info');
		$('#'+toId).toggleClass('d-none');
		return false;
	}, toggle_dropdown: (_this, e) => {
		e.preventDefault();
		$('.js__dropdown-stock').dropdown('toggle');
		return false;
	}, tab_click: (_this, e) => {
		e.preventDefault();
		var uri = $(_this).attr('uri'),
			slug = $(_this).attr('slug');
		$Core.util.popstate(`${uri}#${slug}`);
		return false;
	}, open_map: (_this, e) => {
		e.preventDefault();
		var block_id = $(_this).attr('block_id'),
			building_id = $(_this).attr('building_id');
		$Core.util.toggleIndicatior(1);
		$.post(`${path_ajax_script}/index.php?mod=${MOD}&sub=project&act=get_map`, {
			'block_id' : block_id,
			'building_id' : building_id
		}, (respJson) => {
			$Core.util.toggleIndicatior(0);
			if(respJson.img_layout) {
				$Core.popup.open('auto','auto', respJson.html, respJson.uid);
				$('#'+respJson.uid).on('shown.bs.modal', function(){
					setTimeout(function(){
						var _width = $("#map_"+respJson.uid).width(),
							_height = $("#map_"+respJson.uid).height();
						$Core.project.render_stock_map(block_id,building_id,_width,_height,respJson.uid);
					},300);
				});
			}
		}, 'json');
		return false;
	}, render_stock_map: (block_id, building_id,imgWidth,imgHeight,to_map) => {
		var $_adata = {
			"block_id" : block_id,
			"building_id" : building_id,
			"imgWidth" : imgWidth,
			"imgHeight" : imgHeight,
			"to_map" : to_map,
		};
		$.post(`${path_ajax_script}/index.php?mod=${MOD}&sub=project&act=render_stock_map`, $_adata, (respJson) => {
			if(respJson.msg.indexOf('_success') >= 0){
				$("#map_"+to_map).append(respJson.html_shapes);
				var centerX = imgWidth / 2, 
					centerY = imgHeight / 2,
					scale = imgWidth/1000;
				$(".item_tooltip .box_code",$("#map_"+to_map)).css({"padding":"calc(3px * "+scale+") calc(5px * "+scale+")","text-shadow":"calc(2px * "+scale+") calc(1px * "+scale+") black"});
				$(".item_tooltip .body_tooltip",$("#map_"+to_map)).css({"padding":"calc(5px * "+scale+")"});
				$(".item_tooltip",$("#map_"+to_map)).css({"width":"calc(130px * "+scale+")","border-radius":"calc(8px * "+scale+")"});
				$(".item_tooltip .box_code",$("#map_"+to_map)).css({"font-size":"calc(16px * "+scale+")"});
				$(".item_tooltip .box_text",$("#map_"+to_map)).css({"font-size":"calc(9px * "+scale+")"});
				$(".item_tooltip .text-value",$("#map_"+to_map)).css({"font-size":"calc(9px * "+scale+")"});
				$(".body_tooltip .form-row",$("#map_"+to_map)).css({"margin-right":"calc(-5px * "+scale+")","margin-left":"calc(-5px * "+scale+")"});
				$(".body_tooltip .form-row>.col, .form-row>[class*=col-]",$("#map_"+to_map)).css({"padding-right":"calc(5px * "+scale+")","padding-left":"calc(5px * "+scale+")"});
				$(".item_tooltip .text-price",$("#map_"+to_map)).css({"font-size":"calc(14px * "+scale+")","padding":"calc(3px * "+scale+")","border-radius":"calc(5px * "+scale+")","margin-top":"calc(3px * "+scale+")","text-shadow":"calc(2px * "+scale+") calc(1px * "+scale+") black"});
				if(!respJson.is_shape_render) {
					var radius = Math.min($("#map_"+to_map).width(), $("#map_"+to_map).height()) / 2.5;
					var numItems = $(".item_tooltip",$("#map_"+to_map)).length;
					$(".item_tooltip", $("#map_"+to_map)).each((index,elm) => {
						var angle = (index / numItems) * (2 * Math.PI); // Góc tính theo radian
						var posX = centerX + radius * Math.cos(angle) - 60*scale; // Điều chỉnh vị trí theo kích thước phần tử
						var posY = centerY + radius * Math.sin(angle) - 120*scale;
						var left = posX*100/imgWidth;
						var top = posY*100/imgHeight;
						$(elm).attr({"left":left,"top":top});
						$(elm).css({left: left + '%',top: top + '%'});
					});
				}
				var mySVG = $(`#map_${to_map}`).connect(`#map_${to_map}`);
				$(".item_tooltip", $("#map_"+to_map)).each((index,elm) => {
					var toId = $(elm).attr('toid');
					mySVG.drawLine({
						left_node:`.item_drag_${toId}`,
						right_node:`.item_tooltip_${toId}`,
						horizantal_gap:1,
						error:true,
						width:2*scale,
						style:'solid',
						status: 'accepted'
					});
				});
			}
		}, 'json');
	}, update_shape : (_modal) => {
		var shapes_render = {},
			block_id = $("input[name='block_id']",_modal).val(),
			building_id = $("input[name='building_id']",_modal).val();
		$(".item_tooltip",_modal).each(function(index,elm){
			var stock_id = $(elm).attr("stock_id"),
				left = $(elm).attr("left"),
				top = $(elm).attr("top"),
				code = $(elm).attr("code");
			shapes_render[stock_id] = {
				"code" : code,
				"top" : top,
				"left" : left,
			}
		});
		$.post(`${path_ajax_script}/index.php?mod=${MOD}&sub=project&act=update_shape_render`, {
			'block_id' : block_id,
			'building_id' : building_id,"shapes_render":shapes_render
		}, (respJson) => {
		}, 'json');
	}, dragabled : (_this,e) => {
		e.preventDefault();
		var _modal = $(_this).closest(".modal"),
			to_map = _modal.attr("id");
		console.log(to_map);
		if($(_this).hasClass("edit")) {
			$(_this).removeClass("edit").html(`<i class="bx bx-pencil"><i>`);
			$(".item_tooltip", _modal).draggable("destroy");
			$(".item_tooltip", $("#map_"+to_map)).removeClass("edit");
		}else{
			$(_this).addClass("edit").html(`<i class="bx bx-block"><i>`);
			$(".item_tooltip", $("#map_"+to_map)).addClass("edit");
			$("canvas",_modal).remove();
			var mySVG = $(`#map_${to_map}`).connect(`#map_${to_map}`);
			var containerWidth = $("#map_"+to_map).width();
			var containerHeight = $("#map_"+to_map).height();
			var scale = containerWidth/1000;
			$(".item_tooltip", $("#map_"+to_map)).each((index,elm) => {
				var toId = $(elm).attr('toid'),
					id = $(elm).attr('id');
				mySVG.drawLine({
					left_node:`.item_drag_${toId}`,
					right_node:`.item_tooltip_${toId}`,
					horizantal_gap:1,
					error:true,
					width:1*scale,
					style:'solid',
					status: 'accepted'
				});
				$(elm).draggable({
					drag: function(event, ui) {	
						mySVG.redrawLines();
						// Tính toán tọa độ theo phần trăm dựa trên vị trí hiện tại
						var percentX = (ui.position.left / containerWidth) * 100;
						var percentY = (ui.position.top  / containerHeight) * 100;
						console.log(percentX,percentY);
						$(elm).css({"left":percentX,"top":percentY});
						$(elm).attr("left",percentX);
						$(elm).attr("top",percentY);						  
					},
					stop: function(event, ui) {
						// Cập nhật hiển thị tọa độ với 2 chữ số thập phân
						$Core.project.update_shape($("#"+to_map));
					}
				});
			});
		}				
	}, download_map : (_this,e) => {
		e.preventDefault();
		var _modal = $(_this).closest(".modal"),
			elm_map = $(".map_stock_fh",_modal)[0];
			$(".btn_drag",_modal).css("opacity","0");
			$(_this).css("opacity","0");
			// Render element thành canvas với html2canvas
		html2canvas(elm_map, {
			useCORS: true,          // Hỗ trợ tải ảnh từ nguồn khác nếu cần
			backgroundColor: null,   // Để nền trong suốt nếu muốn
			scale: 5                 // Tùy chỉnh scale cho độ phân giải
		}).then(function(canvas) {
			// Chuyển canvas thành data URL (PNG)
			var imageData = canvas.toDataURL("image/png");
			// Đổi đầu data URL để trình duyệt có thể tải về file
			var newData = imageData.replace(/^data:image\/png/, "data:application/octet-stream");
			// Tạo một phần tử <a> để kích hoạt download
			var link = document.createElement("a");
			link.download = "capture.png";   // Tên file khi tải xuống
			link.href = newData;
			// Tự động "click" vào link để tải file xuống
			link.click();
			$(".btn_drag",_modal).css("opacity","1");
			$(_this).css("opacity","1");
		});
	}, render_back_button: (project_id) => {
		if (document.getElementById("backButton")) return;
		var button = L.DomUtil.create('button', 'btn leaflet-back');
		button.id = "backButton";
		button.innerText = 'Quay lại';
		button.onclick = function () {
			$('.loading').stop('false', true).fadeIn();
			currentImage.setOpacity(0.3);
			drawnItems.clearLayers();
			map.removeLayer(drawnItems);
			map.removeLayer(currentImage);
			map.removeControl(currentMiniMap);
			var imgHome = new Image();
			imgHome.onload = () => { 
				var natWidth = imgHome.naturalWidth,
					natHeight = imgHome.naturalHeight,
					imageBounds = [[0,0], [natHeight, natWidth]],
					maxBounds = [[0,0], [natHeight+50, natWidth+50]];
				currentImage = L.imageOverlay(image_maps.project, imageBounds).addTo(map);
				map.fitBounds(imageBounds, {animate:false, duration:0});
				map.setMaxBounds(imageBounds); 
				currentImage.setOpacity(1);
				currentMiniMap = new L.Control.MiniMap(L.imageOverlay(image_maps.project, imageBounds),{
					toggleDisplay: true, 
					position: 'bottomleft',
					zoomLevelOffset: -1,
					width: 120,
					height: 80,
				}).addTo(map);
				$('.loading').stop('false', true).fadeOut();
				$Core.project.get_shapes(project_id, {}, drawnItems);
			};
			imgHome.src = image_maps.project;
			map.addLayer(drawnItems);
			document.getElementById("backButton").remove();
		};
		var control = L.control({ position: 'topleft' });
		control.onAdd = function () { 
			return button; 
		};
		control.addTo(map);
	}, get_shapes: (project_id, options, drawnItems) => {
		var $_adata = options || {};
		$_adata['project_id'] = project_id;
		$.post(`${path_ajax_script}/index.php?mod=${MOD}&sub=project&act=get_shapes`, $_adata, (respJson) => {
			if(respJson.msg.indexOf('_success') >= 0){
				$('.total_stock').text(respJson.total_record);
				drawnItems.clearLayers();
				$Core.project._allMapLayers = [];
				respJson.shapes.forEach(function(shape) {
					// Điểm định vị (circlemarker = pin 1 căn) LUÔN qua renderer dùng chung — kể cả ở
					// chế độ chọn phân khu; và cấp căn (stock) cũng vậy (màu trạng thái + tooltip giàu + popover).
					// Chỉ cấp phân khu (block, polygon/rectangle) mới chạy tô màu + tooltip + drill-down bên dưới.
					if(shape.shape_type === 'circlemarker' || respJson.holderG != 'block'){
						$Core.project.render_stock_layer(shape, respJson, map, drawnItems);
						return;
					}
					var layer, coordinates = shape.coordinates;
					if(respJson.holderG == 'block'){
						var style = {weight:2, fillOpacity : 0.5};
					} else {
						if(shape.status_id == respJson._STOCK_STATUS_SOLD_ID && 1==2){
							var style = {weight:0, 
								fillOpacity : 0.6, 
								color: 'rgb(155,25,49)', 
								fillColor : 'rgb(155,25,49)', 
								dashArray: '8,4'
							};
						}
						var style = {weight:1, 
							fillOpacity : 0.6, 
							color: 'rgb(4,92,55)', 
							fillColor : 'rgb(10,175,106)', 
							dashArray: '8,4'
						};
						if(_AGENCY_CNCN_ID !== undefined && shape.agency_id == _AGENCY_CNCN_ID){
							var style = {weight:0, 
								fillOpacity : 0.6, 
								color: 'rgb(110 15 131)', 
								fillColor : 'rgb(110 15 131)', 
								dashArray: '8,4'
							};
						}
					}
					if (shape.shape_type === 'polygon') {
						layer = L.polygon(coordinates, style);
						layer.setStyle({ className: 'animated-border' });
					} else if (shape.shape_type === 'rectangle') {
						layer = L.rectangle(coordinates, style);
						layer.setStyle({ className: 'animated-border' });
					}
					if(!layer){ return; } // bỏ qua shape không phải polygon/rectangle (vd điểm định vị) — tránh crash trắng bản đồ
					if(respJson.holderG == 'block'){
						layer.bindTooltip(`Phân khu ${shape.name}`, {
							permanent: (deviceType=='phone' ? false : false),
							direction: 'center',
							className : 'leaflet-tooltip'
						});
						layer.on('mouseover',function () {
							this.setStyle({ fillColor: '#265AA4', fillOpacity: 0.7 });
						});
						layer.on('mouseout', function() {
							this.setStyle({ fillColor: '#265AA4', fillOpacity: 0.5 });
						});
					} else {
						layer.bindTooltip(`${shape.stock_code}`, {
							permanent: false,
							direction: 'center',
							className : 'leaflet-tooltip'
						});
					}
					layer.on('click', (e) => {
						if(respJson.holderG == 'block'){
							$('.loading').stop('false', true).fadeIn();
							currentImage.setOpacity(0.3);
							drawnItems.clearLayers();
							map.removeLayer(drawnItems);
							map.removeLayer(currentImage);
							map.removeControl(currentMiniMap);
							var imgDetail = new Image();
							imgDetail.onload = () => { 
								var natWidth = imgDetail.naturalWidth,
									natHeight = imgDetail.naturalHeight,
									imageBounds = [[0,0], [natHeight, natWidth]],
									maxBounds = [[-50,-50], [natHeight+50, natWidth+50]];  
								currentImage = L.imageOverlay(image_maps[shape.block_id], imageBounds).addTo(map);
								map.fitBounds(imageBounds, {animate: false, duration: 0 });
								//map.flyToBounds(imageBounds, {animate: false, duration: 0 });
								map.setMaxBounds(imageBounds); 
								currentImage.setOpacity(1);
								currentMiniMap = new L.Control.MiniMap(L.imageOverlay(image_maps[shape.block_id], imageBounds),{
									toggleDisplay: true, 
									position: 'bottomleft',
									zoomLevelOffset: -1,
									width: 120,
									height: 80,
								}).addTo(map);
								$('.loading').stop('false', true).fadeOut();
								$Core.project.get_shapes(project_id, {'block_id' : shape.block_id}, drawnItems);
							};
							imgDetail.src = image_maps[shape.block_id];
							map.addLayer(drawnItems);
							$Core.project.render_back_button(project_id);
						} else {
							if(!$Core.util.isEmpty(shape.stock_id)){
								$Core.helper.open_stock(shape.stock_id);
							}
						}
					});
					layer.holderG = respJson.holderG;
					layer.block_id = shape.block_id;
					drawnItems.addLayer(layer);
				});
				// Viewport culling: gắn moveend (idempotent) + render pin trong khung nhìn
				if(typeof map !== 'undefined' && map){
					map.off('moveend', $Core.project.render_visible_markers_debounced).on('moveend', $Core.project.render_visible_markers_debounced);
				}
				$Core.project.render_visible_markers();
			}
		}, 'json');
	}, get_shapes_upgraded: (project_id, options, drawnItems) => {
		var $_adata = options || {};
		$_adata['project_id'] = project_id;
		$('.loading').stop('false', true).fadeIn();
		$.post(`${path_ajax_script}/index.php?mod=${MOD}&sub=project&act=get_shapes_upgraded`, $_adata, (respJson) => {
			$('.loading').stop('false', true).fadeOut();
			if(respJson.msg.indexOf('_success') >= 0){
				$('.total_stock').text(respJson.total_record);
				drawnItems.clearLayers();
				$Core.project._allMapLayers = [];
				respJson.shapes.forEach(function(shape) {
					// Bảng hàng tiles: giao renderer dùng chung (màu trạng thái + tooltip giàu + popover).
					// map để null vì biến map của nhánh tiles là cục bộ; circlemarker sẽ gắn vào drawnItems.
					$Core.project.render_stock_layer(shape, respJson, null, drawnItems);
				});
				// Viewport culling: gắn moveend (idempotent) + render pin trong khung nhìn
				if(typeof map !== 'undefined' && map){
					map.off('moveend', $Core.project.render_visible_markers_debounced).on('moveend', $Core.project.render_visible_markers_debounced);
				}
				$Core.project.render_visible_markers();
			}
		}, 'json');
	}, render_stock_layer: function(shape, respJson, map, drawnItems){
		// Mọi shape_type -> 1 pin "giọt nước" hiển thị GIÁ tại tâm shape 
		// KHÔNG addLayer ngay: đẩy vào _allMapLayers, viewport culling render pin trong khung nhìn.
		var coordinates = shape.coordinates, center = null;
		if(shape.shape_type === 'circlemarker'){
			center = (coordinates && coordinates.center) ? coordinates.center : coordinates;
		} else if(shape.shape_type === 'polygon'){
			try { center = L.polygon(coordinates).getBounds().getCenter(); } catch(e){ center = null; }
		} else if(shape.shape_type === 'rectangle'){
			try { center = L.rectangle(coordinates).getBounds().getCenter(); } catch(e){ center = null; }
		}
		if(!center) return;
		// Độc quyền = đại lý (_AGENCY_FH_ID) -> pin đỏ; còn lại vàng
		var isDq = (typeof(_AGENCY_FH_ID) !== 'undefined' && shape.agency_id == _AGENCY_FH_ID);
		// Giá: tỷ, 2 chữ số (price_vat thô VND / 1e9)
		var priceLabel = '';
		if(shape.price_vat !== undefined && parseFloat(shape.price_vat) > 0){
			priceLabel = (parseFloat(shape.price_vat) / 1e9).toFixed(2);
		}
		var layer = $Core.project._make_price_pin(center, priceLabel, isDq);
		layer.stock_id = shape.stock_id;
		layer.bindTooltip(`${shape.stock_code || ''}`, { permanent:false, direction:'center', className:'leaflet-tooltip' });
		layer.on('click', function(e){
			if(!$Core.util.isEmpty(shape.stock_id)){ $Core.helper.open_stock(shape.stock_id); }
		});
		layer._passFilter = true;
		layer._onMap = false; // viewport culling sẽ add pin trong khung nhìn
		if(!$Core.project._allMapLayers) $Core.project._allMapLayers = [];
		$Core.project._allMapLayers.push(layer);
	}, _make_price_pin: function(latlng, priceLabel, isDq){
		// Pin giọt nước (divIcon) hiển thị giá — CSS .lf-price-pin (border-radius 50% 50% 50% 0 xoay -45°)
		return L.marker(latlng, {
			icon: L.divIcon({
				className: 'lf-price-pin-wrap',
				html: '<div class="lf-price-pin' + (isDq ? ' lf-price-pin--dq' : '') + '"><span>' + (priceLabel || '') + '</span></div>',
				iconSize: [36, 44], iconAnchor: [18, 44]
			})
		});
	}, render_visible_markers: function(){
		// Viewport culling: chỉ add/remove pin theo khung nhìn (bắt buộc cho hàng ngàn pin)
		if(typeof map === 'undefined' || !map || typeof drawnItems === 'undefined' || !drawnItems) return;
		var layers = $Core.project._allMapLayers || [], b;
		try { b = map.getBounds().pad(0.25); } catch(e){ return; }
		for(var i = 0; i < layers.length; i++){
			var m = layers[i];
			if(!m || !m.getLatLng) continue;
			var show = m._passFilter && b.contains(m.getLatLng());
			if(show && !m._onMap){ drawnItems.addLayer(m); m._onMap = true; }
			else if(!show && m._onMap){ drawnItems.removeLayer(m); m._onMap = false; }
		}
	}, render_visible_markers_debounced: function(){
		if($Core.project._cullTimer) clearTimeout($Core.project._cullTimer);
		$Core.project._cullTimer = setTimeout(function(){ $Core.project.render_visible_markers(); }, 80);
	}, render_block_tabs: function(blocks, activeId){
		// State B: dựng thanh tab phân khu — mỗi tab là 1 phân khu CÓ bản đồ (tiles_link hoặc ảnh)
		var $wrap = $('.js__block-tabs');
		if(!$wrap.length || !blocks || !blocks.length) return;
		$Core.project._map_blocks = blocks; // lưu để click tra cứu config
		var html = '';
		console.log(blocks);
		blocks.forEach(function(b){
			var isActive = (b.block_id == activeId);
			html += `<button type="button" class="btn btn-sm js__block-tab me-2 mb-2 ${isActive ? 'btn-primary' : 'btn-outline-default'}" data-block-id="${b.block_id}"><i class="bx bx-map-alt"></i> ${b.title || ('Phân khu ' + b.block_id)}</button>`;
		});
		$wrap.html(html).show();
		$wrap.off('click.blocktab').on('click.blocktab', '.js__block-tab', function(){
			var bid = parseInt($(this).attr('data-block-id'), 10) || 0;
			var blk = ($Core.project._map_blocks || []).filter(function(x){ return x.block_id == bid; })[0];
			if(blk) $Core.project.init_block_map(blk);
		});
	}, init_block_map: function(block){
		// Dựng LẠI bản đồ cho 1 phân khu (tiles hoặc ảnh) + nạp shapes phân khu đó
		if(!block) return;
		var block_id = parseInt(block.block_id, 10) || 0;
		if(!block_id) return;
		$('.js__block-tab').removeClass('btn-primary').addClass('btn-outline-default');
		$(`.js__block-tab[data-block-id="${block_id}"]`).removeClass('btn-outline-default').addClass('btn-primary');
		$('.js__map-empty').remove();
		$('#map').show();
		$('.loading').stop(true, true).fadeIn();
		// Huỷ map cũ trước khi dựng lại (CRS/tiles khác nhau giữa các phân khu)
		if(typeof map !== 'undefined' && map){ try{ map.remove(); }catch(e){} }
		currentImage = null; currentMiniMap = null;
		var _loadShapes = function(){
			$('.loading').stop(true, true).fadeOut();
			$Core.project.get_shapes(project_id, {'block_id': block_id}, drawnItems);
		};
		if(parseInt(block.is_tiles, 10) === 1){
			// === Tiles mode ===
			var center = block.center_point;
			if(typeof center === 'string'){ try{ center = JSON.parse(center); }catch(e){ center = null; } }
			if(!center || !Array.isArray(center)) center = [0,0];
			var mz = block.max_zoom || 9, tms = (parseInt(block.tms_enable, 10) === 1);
			map = new L.map('map', {
				minZoom: 1, maxZoom: mz, center: center, zoom: block.curr_zoom || 3,
				fullscreenControl: true, fullscreenControlOptions: { position: 'topleft' }
			});
			L.tileLayer(block.tiles, {
				noWrap: true, tileSize: 256,
				attribution: '<a href="'+PCMS_URL+'">© '+BRAND_NAME+'</a>',
				maxZoom: mz, tms: tms
			}).addTo(map);
			try {
				new L.Control.MiniMap(
					L.tileLayer(block.tiles, { maxZoom: mz, minZoom: 1, noWrap: true, tms: tms }),
					{ toggleDisplay: true, minimized: false, position: 'bottomleft', width: 120, height: 80 }
				).addTo(map);
			} catch(e){}
			drawnItems = new L.FeatureGroup();
			map.addLayer(drawnItems);
			_loadShapes();
		} else {
			// === Image mode (phân khu dạng ảnh) ===
			var mapMaxZoom = 8, dev = (window.innerWidth <= 575) ? 'phone' : 'desktop',
				mapMinZoom = (dev == 'phone') ? 2 : 5, mapMinRes = Math.pow(2, mapMaxZoom) * 1.0,
				crs = L.CRS.Simple;
			crs.scale = function(z){ return Math.pow(2, z) / mapMinRes; };
			crs.zoom = function(s){ return Math.log(s * mapMinRes) / Math.LN2; };
			map = L.map('map', {
				crs: crs, attributionControl: false, boxZoom: false, doubleClickZoom: true,
				dragging: true, keyboard: false, maxBoundsViscosity: 1.0, maxZoom: mapMaxZoom,
				minZoom: mapMinZoom, scrollWheelZoom: true, tap: true, touchZoom: true,
				zoomControl: false, zoomSnap: 0
			});
			L.control.zoom({ position: 'topright' }).addTo(map);
			drawnItems = new L.FeatureGroup();
			map.addLayer(drawnItems);
			var imgUrl = (typeof image_maps !== 'undefined' && image_maps) ? image_maps[block_id] : '';
			if(!imgUrl){ _loadShapes(); return; }
			var img = new Image();
			img.onload = function(){
				var iw = img.naturalWidth, ih = img.naturalHeight,
					ib = [[0,0],[ih,iw]], mb = [[-1000,-1000],[ih+1000,iw+1000]];
				currentImage = L.imageOverlay(imgUrl, ib).addTo(map);
				map.fitBounds(ib, {animate:false});
				map.setMaxBounds(mb);
				try {
					currentMiniMap = new L.Control.MiniMap(L.imageOverlay(imgUrl, ib), {
						toggleDisplay:true, position:'bottomleft', zoomLevelOffset:-1, width:120, height:80
					}).addTo(map);
				} catch(e){ currentMiniMap = null; }
				_loadShapes();
			};
			img.onerror = function(){ _loadShapes(); };
			img.src = imgUrl;
		}
	}, show_map_empty: function(){
		// State C: không có ảnh dự án lẫn phân khu -> báo "Mặt bằng đang được hoàn thiện"
		$('.loading').stop(true, true).fadeOut();
		$('#map').hide();
		var $c = $('.map-container');
		if(!$c.length || $('.js__map-empty').length) return;
		$c.append('<div class="js__map-empty text-center text-muted d-flex flex-column justify-content-center align-items-center" style="position:absolute;inset:0;">'
			+ '<i class="bx bx-map-alt" style="font-size:3rem"></i>'
			+ '<h5 class="mt-2 mb-1">Mặt bằng đang được hoàn thiện</h5>'
			+ '<p class="mb-0">Dự án chưa có bản đồ mặt bằng. Vui lòng xem ở dạng bảng.</p>'
			+ '</div>');
	}, getCentroid: function(latlngs) {
		let latSum = 0, lngSum = 0, count = latlngs.length;
		latlngs.forEach(latlng => {
			latSum += latlng.lat;
			lngSum += latlng.lng;
		});
		return L.latLng(latSum / count, lngSum / count);
	}, draw_shapes: (block_id, building_id, options, map, drawnItems) => {
		var $_adata = options || {};
		$_adata['block_id'] = block_id;
		$_adata['building_id'] = building_id;
		$.post(`${path_ajax_script}/index.php?mod=${MOD}&sub=project&act=draw_shapes`, $_adata, (respJson) => {
			if(respJson.msg.indexOf('_success') >= 0){
				drawnItems.clearLayers();
				var map_configs = respJson.map_configs;
				// console.log(map_configs);
				$.each(respJson.shapes, function(_i, shape) {
					var layer, coordinates = shape.coordinates,
						style = {weight:0, 
							fillOpacity : 0.6, 
							color: 'rgb(4,92,55)', 
							fillColor : 'rgb(10,175,106)', 
							dashArray: '8,4'
						};
					if (shape.shape_type === 'polygon' || shape.shape_type == 'rectangle') {
						if(shape.shape_type === 'polygon'){
							layer = L.polygon(coordinates, style);
						} else if(shape.shape_type === 'rectangle'){
							layer = L.rectangle(coordinates, style);
						}
						if(typeof(map_configs.enable_tooltip_position) !== 'undefined' 
							&& map_configs.enable_tooltip_position == 1){
							layer.addTo(map);
							if(typeof(shape.latlng) === 'undefined'){
								var latlng = layer.getBounds().getCenter();
							} else {
								var latlng = shape.latlng;
							}
							var direction = 'top',
								marker = L.marker(latlng, {
								draggable: false,
								opacity: 0 // Ẩn marker
							}).addTo(map);
							if(typeof(shape.direction) !== 'undefined'){
								direction = shape.direction;
							}
							marker.bindTooltip(`<div class="p-0">
								<h3 class="text-center fs-14 fw-bold mb-1">${shape.stock_code}</h3>
								<hr class="my-1" />
								<div class="d-flex text-upper align-items-center gap-2">
									<span>Loại: ${shape.bedroom}</span>
									<span>Hướng: ${shape.home_direction}</span>
								</div>
								<div class="d-flex text-upper align-items-center gap-2">
									<span>DTTIM: ${shape.DT_Tim}m<sup>2</sup></span>
									<span>DTXD: ${shape.DT_TT}m<sup>2</sup></span>
								</div>
								<hr class="my-1" />
								<div class="d-flex text-upper align-items-center">
									<span>Giá: ${shape.dg_price} tỷ</span>
								</div>
							</div>`, {
								permanent: true, 
								direction: direction, 
								interactive: true 
							}).openTooltip();
							var centroid = $Core.project.getCentroid(layer.getLatLngs()[0]),
								polyline = L.polyline([centroid, latlng], { 
								color: '#a04123', 
								weight: 1,
								opacity: 0.8,
								arrowheads: {
									size: '20px',
									fill: true,
									polygon: false,
									marker: true
								}
							}).addTo(map);
						} else if(typeof(map_configs.show_tooltip) !== 'undefined' 
							&& map_configs.show_tooltip == 1){
							layer.bindTooltip(`<div class="p-1">
								<h3 class="text-center fs-14 fw-bold mb-1">${shape.stock_code}</h3>
								<hr class="my-1" />
								<div class="d-flex text-upper align-items-center gap-2">
									<span>Loại: ${shape.bedroom}</span>
									<span>Hướng: ${shape.home_direction}</span>
								</div>
								<div class="d-flex text-upper align-items-center gap-2">
									<span>DTTIM: ${shape.DT_Tim}m<sup>2</sup></span>
									<span>DTXD: ${shape.DT_TT}m<sup>2</sup></span>
								</div>
								<hr class="my-1" />
								<div class="d-flex text-upper align-items-center">
									<span>Giá: ${shape.dg_price} tỷ</span>
								</div>
							</div>`, {
								permanent: false, 
								direction: 'top'
							});
						}
						layer.on('click', (e) => {
							if(deviceType=='phone'){
								if(!$Core.util.isEmpty(shape.stock_id)){
									$Core.helper.open_stock(shape.stock_id);
								}
							} else {
								fetch(`${path_ajax_script}/index.php?mod=${MOD}&sub=project
									&act=load_stock_popover&stock_id=${shape.stock_id}`)
									.then(response => response.text())
									.then(data => {
										// Gỡ a[href] ẩn
										$(`.js__webui-popover_${shape.stock_id}`).remove();
										// Tạo một a[href] ẩn trên bản đồ
										let popupDiv = $(`<a class="js__webui-popover_${shape.stock_id}"></a>`);
										$('body').append(popupDiv);
										// Định vị popover theo vị trí click
										popupDiv.css({
											position: 'absolute',
											left: (e.originalEvent.pageX) + 'px',
											top: (e.originalEvent.pageY) + 'px',
										});
										// Kích hoạt WebUI Popover
										popupDiv.webuiPopover('destroy').webuiPopover({
											arrow : false,
											width: 350,
											content: data,
											trigger: 'click',
											placement: 'auto', // Tự động chọn hướng hiển thị
											animation: 'pop',
											closeable: false,
											multi: false,
											delay: 300,
											padding: true,
											backdrop: false
										}).webuiPopover('show');
									});
							}
						});
						drawnItems.addLayer(layer);
					} else if(shape.shape_type == 'circlemarker'){						
						if(shape.is_dq !== undefined && shape.is_dq == 1) {
							var color= "#f5e10a";
						}else{
							var color= '#00FF00';
						}
						layer = createPulsingCircle(coordinates.center, {
							radius: 5,          // Bán kính gốc
							color: color,        // Viền gốc
							fillColor: color,    // Màu nền
							pulseColor: color // Viền ngoài hiệu ứng
						});
						layer.on('click', async (e) => {
							try {
								let response = await fetch(`${path_ajax_script}/index.php?mod=${MOD}
									&sub=project&act=load_pop_stock&stock_id=${shape.stock_id}`);
								let data = await response.text();
								layer.bindPopup(data, {minWidth:350}).openPopup();
							} catch (err) {
								console.error("Lỗi khi load hình:", err);
							}
						});
					}
					function createPulsingCircle(latlng, options) {
						// CircleMarker chính
						let layer = L.circleMarker(latlng, {
							radius: options.radius || 6,
							color: options.color || 'blue',
							weight: options.weight || 2,
							fillColor: options.fillColor || 'blue',
							fillOpacity: options.fillOpacity || 1,
							stroke : options.fillOpacity || false,
						}).addTo(map);
						// CircleMarker viền ngoài (tạo hiệu ứng)
						let pulseCircle = L.circleMarker(latlng, {
							radius: (options.radius || 6) * 1.4,  // Viền ngoài lớn hơn
							color: options.pulseColor || 'rgba(0, 0, 255, 0.5)',
							weight: 2,
							fillColor: 'transparent',
							fillOpacity: 0
						}).addTo(map);
						let growing = true;  // Biến kiểm soát hiệu ứng
//						setInterval(() => {
//							let radius = pulseCircle.getRadius();
//							if (growing) {
//								pulseCircle.setRadius(radius + 1);
//								if (radius >= layer.getRadius() * 1.5) growing = false;
//							} else {
//								pulseCircle.setRadius(radius - 1);
//								if (radius <= layer.getRadius() * 1.5) growing = true;
//							}
//						}, 200); // Thời gian update (ms)
						return pulseCircle;
					}
				});
			}
		}, 'json');
	}, open_model: function(_this,e) {
		e.preventDefault();
		var _type = $(_this).attr("_type"),
			project_id = $(_this).attr("project_id"),
			block_id = $(_this).attr("block_id"),
			building_id = $(_this).attr("building_id"),
			$_adata = {"project_id":project_id, "block_id" : block_id, "building_id" : building_id,"_type":_type};
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=home&sub=project&act=open_model', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			var _width = __w - (__w - 230);
			$Core.popup.openfull(_width,respJson.html,respJson.uid);
			$("#"+respJson.uid).on("shown.bs.modal",function(){
				$('[data-fancybox]',$("#"+respJson.uid)).fancybox({
				  	buttons: ["download", "thumbs","zoom", "close"],
					afterShow: function(instance, current) {
					// Lấy nút download từ thanh toolbar của Fancybox
					var $downloadBtn = instance.$refs.toolbar.find('[data-fancybox-download]');
					// Lấy URL ảnh hiện tại từ slide được hiển thị
					var src = current.src;
					// Nếu URL chứa "drive.google.com", xử lý chuyển đổi link nếu cần
					if (src.indexOf("drive.google.com") !== -1) {
					  // Nếu URL là dạng thumbnail có tham số id (ví dụ: ?id=...)
					  if (src.indexOf("thumbnail") !== -1) {
						var idMatch = src.match(/[?&]id=([^&]+)/);
						if (idMatch && idMatch[1]) {
						  src = "https://drive.google.com/uc?export=download&id=" + idMatch[1];
						}
					  } else {
						// Nếu URL chứa dạng "/d/FILE_ID/", sử dụng regex để trích xuất FILE_ID
						var regex = /\/d\/([\w-]+)\//;
						var match = src.match(regex);
						if (match && match[1]) {
						  src = "https://drive.google.com/uc?export=download&id=" + match[1];
						}
					  }
					}
					// Cập nhật lại thuộc tính href của nút download với URL đã chuyển đổi
					if ($downloadBtn.length) {
					  $downloadBtn.attr("href", src);
					}
				  }
				});
				console.log("sss");
			});
		},'json');
		return false;
	}, save_docs:  (_this,e) => {
		e.preventDefault();
		var sheet_id = $(_this).attr("sheet_id"),
			docs_id = $(_this).attr("docs_id"),
			$_adata = {"sheet_id":sheet_id,"docs_id":docs_id};
		if($(_this).hasClass("saved")) {
			$(_this).removeClass("saved");
			$_adata["action"] = "unsave";
		}else{
			$(_this).addClass("saved");
			$_adata["action"] = "save";
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=home&sub=project&act=save_docs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);			
		},'json');
		return false;
	}, dragBlock : (_this,e) => {
		e.preventDefault();
		var _parent = $(_this).closest(".project_highfloor");
		$(".btn_drag",_parent).toggleClass("d-none");
		if($(_this).hasClass("edit")) {
			$(_this).removeClass("edit").html(`<i class="bx bx-pencil"><i>`);
		}else{
			$(_this).addClass("edit").html(`<i class="bx bx-block"><i>`);
		}				
	}, chooseListStock: (_this, e) => {
		e.preventDefault();
		$('.'+'modal_stock_picker').modal('show');
		return false;
	},
	show_price : (_this,e) => {
		e.preventDefault();
		var _type = $(_this).data("type"),
			text = $(_this).data("text"),
			_parent = $(_this).closest(".dropdown");
		$(".dropdown-toggle",_parent).text(text);
		$(".dropdown-item.active",_parent).removeClass("active");
		$(_this).addClass("active");
		$(".stock_show").each(function(index,elm){
			if(_type == "price_full") {
				var total_price_early = $(elm).data("total_price_early"),
					total_price_progress = $(elm).data("total_price_progress"),
					total_price_bank = $(elm).data("total_price_bank");
				$price = "";
				if(total_price_early != "") {
					$price += `TTS: ${total_price_early}<br>`;	
				}
				if(total_price_progress != "") {
					$price += `TTTĐ: ${total_price_progress}<br>`;	
				}
				if(total_price_bank != "") {
					$price += `Vay: ${total_price_bank}<br>`;	
				}
				$(".price_show",$(elm)).html($price);
			}else if(_type == "price_full_m2") {
				var price_tts_m2 = $(elm).data("price_tts_m2"),
					price_tttd_m2 = $(elm).data("price_tttd_m2"),
					price_vay_m2 = $(elm).data("price_vay_m2");
				$price = "";
				if(price_tts_m2 != "") {
					$price += `TTS: ${price_tts_m2}<br>`;	
				}
				if(price_tttd_m2 != "") {
					$price += `TTTĐ: ${price_tttd_m2}<br>`;	
				}
				if(price_vay_m2 != "") {
					$price += `Vay: ${price_vay_m2}<br>`;	
				}
				$(".price_show",$(elm)).html($price);
			}else{
				var $price = $(elm).data(_type);
				$(".price_show",$(elm)).html($price);
			}
		});
	}
}
$Core.document = {
	list: (options = {}) => {
		var $_adata = options || {};
		let newUrl = window.location.pathname;
		let paramsFirst = true;
		if(!$_adata.hasOwnProperty('page')){
			var current_page = $('input[name=current_page]').val();
			$_adata['page'] = current_page;
		}
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if(typeof(field) != 'undefined'){
					if (options != null && options.hasOwnProperty(field)) {
						$('[data-field='+field+']').val(options[field]);
					} else {
						$_adata[field] = $(_elem).val();
					}
				}
			});
		}
		if (!$_adata.hasOwnProperty('cat_id')) { 
			let activeCategoryEl = $('.category_item.active');
			if (activeCategoryEl.length > 0) {
				let elCategoryLink = activeCategoryEl.find('.category_link');
				let cat_id = elCategoryLink.data('cat-id');
				if (typeof cat_id != 'undefined') {
					$_adata['cat_id'] = cat_id;
				}
			} else {
				$_adata['cat_id'] = '';
			}
		}
		if (!$_adata.hasOwnProperty('tag_id')) {
			let activeTagEl = $('.tag_document_item.active');
			if (activeTagEl.length > 0) {
				let tagArr = [];
				activeTagEl.each(function(index, value) {
					let tag_id = $(this).data('tag-id');
					if (typeof tag_id != 'undefined') {
						tagArr.push(tag_id);
					} 
				});
				if (tagArr.length > 0) {
					$_adata['tag_id'] = tagArr.join(',');
				}
			} else {
				$_adata['tag_id'] = '';
			}
		}
		for (let key in $_adata) {
			if ($_adata.hasOwnProperty(key) && $_adata[key].toString().length > 0) {
				let sign = '&';
				if (paramsFirst) {
					sign = '?';
					paramsFirst = false;
				}
				newUrl += sign + key + '='+ $_adata[key];
			}
		}
		window.history.pushState({path:newUrl}, '', newUrl);
		$.ajax({
			url: PCMS_URL+'/index.php?mod='+MOD+'&sub=project&act=list_document',
			method: 'POST',
			data: $_adata,
			dataType: 'json',
			beforeSend: function() {
				$Core.util.toggleIndicatior(1);
				$('html, body').animate({ scrollTop: 0 });
			},
			success: function(respJson) {
				$('.holder_slide').html(respJson.html);
				if(parseInt(respJson.total_page) > 0 && parseInt(respJson.total_record) > 0){
					$('#pager_slide').pagination({
						listStyle:"pagination justify-content-center",
						currentPage: respJson.current_page, 
						itemsOnPage: respJson.per_page,
						items: respJson.total_record,
						cssStyle: 'light-theme',
						prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
						nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
						hrefTextPrefix: 'javascript:void(0);',
						onPageClick : function(pageNumber){
							$Core.document.list($.extend($_adata, {'page':pageNumber}));
						}
					});
				} else {
					$('#pager_slide').hide();
				}
			},
			error: function(xhr, status, error) {
				console.log('Có lỗi xảy ra!!!! => ', error);
			},
			complete: function() {
				$Core.util.toggleIndicatior(0);
			}
		});
	}, clickCategory: (_this, e) => {
		e.preventDefault();
		let categoryEl = $(_this).parent('.category_item');
		if (categoryEl.hasClass('active')) {
			// click bỏ lọc
			$('.category_item').removeClass('active');
		} else {
			// click để lọc
			$('.category_item').removeClass('active');
			categoryEl.addClass('active');
		}
		$('input[name=current_page]').val(1);
		$Core.document.list();
	}, clickTag: (_this, e) => {
		e.preventDefault();
		if ($(_this).hasClass('active')) {
			// click bỏ lọc
			$(_this).removeClass('active');
		} else {
			// click để lọc
			// $('.tag_document_item').removeClass('active');
			$(_this).addClass('active');
		}
		$('input[name=current_page]').val(1);
		$Core.document.list();
	}, clickform: (_this, e) => {
		e.preventDefault();
		$('input[name=current_page]').val(1);
		$Core.document.list();
	}, clickToggleCat: (_this, e) => {
		e.stopPropagation();
        let submenu = $(_this).siblings(".submenu");
        submenu.slideToggle(200);
        // Đổi [+] <-> [-]
        $(_this).text($(_this).text() === "[+]" ? "[-]" : "[+]");
	}
}
$Core.dashboard_sale = {
	load_total_dashboard : () => {
		var $_adata = {};
		if($(".search_field").length > 0) {
			$(".search_field").each(function(index, elm){
				var field = $(elm).attr("name");
				$_adata[field] = $(elm).val();
			});
		}			
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&sub=dashboard&act=load_total_dashboard', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if($(".total_billing").length > 0) {
				$(".total_billing").text(respJson.total_billing);
			}
			if($(".total_sale").length > 0) {
				$(".total_sale").text(respJson.total_sale);
			}
			if($(".total_customer").length > 0) {
				$(".total_customer").text(respJson.total_customer);
			}
			if($(".total_followups").length > 0) {
				$(".total_followups").text(respJson.total_followups);
			}
			if($(".total_share_customer").length > 0) {
				$(".total_share_customer").text(respJson.total_share_customer);
			}
			if($(".total_share").length > 0) {
				$(".total_share").text(respJson.total_share);
			}
			if($(".total_share_waiting").length > 0) {
				$(".total_share_waiting").text(respJson.total_share_waiting);
			}
		},"json");
	},
	reload: (_this, e) => {
		var options = {},
			toId = $(_this).attr('toId'),
			name = $(_this).attr('name');
		var params = $.extend(options, {'toId':toId});
			if($(_this).hasClass('js__handle-department')){
				var department_id = $(_this).val(),
					date_id = $('.js__input-report-date').val(),
					s_params = {'department_id':department_id};
				$('.js__block-report-today').data('options',s_params).removeClass('loaded');
			}
			if($('select[toId='+toId+'],input[type=text][toId='+toId+']').length){
				$('select[toId='+toId+'],input[type=text][gId='+toId+']').each((_i, _elem) => {
					var p_name = $(_elem).attr('name'),
						p_value = $(_elem).val();
					if(!$Core.util.isEmptyZero(p_value)){
						params[p_name] = p_value;
					}
				});
			}
			if($(`input[type=radio][toId=${toId}]`).length){
				$(`input[type=radio][toId=${toId}]`).each((_i, _elem) => {
					var p_name = $(_elem).attr('name'),
						p_value = $(`input[name=${p_name}]:checked`).val();
					params[p_name] = p_value;
				});
			}
			$('.ajax[toId='+toId+']').data('options',params);
			$('.ajax[toId='+toId+']').removeClass('loaded');
			_autoload();
	}
}