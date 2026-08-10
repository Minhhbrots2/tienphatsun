$(function(){
	_autoload();
	if(ACT == 'default'){		
		var keyword = $("input[name='keyword']").val();
		$Core.broker.list_broker({page:1,keyword:keyword})
	} else if(ACT == "detail"){	
		$Core.broker.load_sales(member_id,{'perPage':perPage,'page':1,'keyword':""});
		
	}
	if($(".content_scroll").length > 0) {
		var content_scroll = $(".content_scroll").offset().top;
		$(window).scroll(function(){
			if(isScrolledIntoView('#showmorethisresult') && $Core.broker.alreadyScroll == 0){
				$Core.broker.alreadyScroll = 1;
				$('.showmorethisresult').removeClass('d-none').trigger('click');
			}
			if($(".box_content_profile").length > 0) {
				if ($(window).scrollTop() >= content_scroll - 50) {
					$(".box_content_profile").addClass("fixed top-0 zindex-2");
				} else {
					$(".box_content_profile").removeClass("fixed top-0 zindex-2");
				}
			}
		});
	}
	
	if($("#owl-achievements").length > 0){
		$('#owl-achievements').owlCarousel({
			loop:true,
			margin:20,
			responsiveClass:true,
			navText:["<i class='bx bx-chevron-left'></i>","<i class='bx bx-chevron-right'></i>"],
			responsive:{
				0:{
					items:1,
					nav:true,
					loop:false,
				},
				575:{
					items:2,
					nav:true,
					loop:false,
				},
				992:{
					items:3,
					nav:true,
				}
				
			}
		});
	}
	if($("#owl-certificate").length > 0){
		$('#owl-certificate').owlCarousel({
			loop:true,
			margin:20,
			responsiveClass:true,
			navText:["<i class='bx bx-chevron-left'></i>","<i class='bx bx-chevron-right'></i>"],
			dots:false,
			responsive:{
				0:{
					items:1,
					nav:true,
				},
				575:{
					items:1.2,
					nav:true,
				},
				992:{
					items:1.5,
					nav:true,
				}
				
			}
		});
	}
	if($("#owl-da").length > 0){
		$('#owl-da').owlCarousel({
			loop:true,
			margin:20,
			responsiveClass:true,
			navText:["<i class='bx bx-chevron-left'></i>","<i class='bx bx-chevron-right'></i>"],
			dots:false,
			responsive:{
				0:{
					items:1,
					nav:true,
				},
				575:{
					items:1.5,
					nav:true,
				},
				992:{
					items:2,
					nav:true,
				}
				
			}
		});
	}
	if($("#swiper-review").length > 0){
		var swiper = new Swiper("#swiper-review", {
			loop: true,
			effect: "coverflow",
			grabCursor: true,
			centeredSlides: true,
			slidesPerView: "auto",
			coverflowEffect: {
				rotate: 50,
				stretch: 0,
				depth: 100,
				modifier: 1,
				slideShadows: true,
			},
			navigation: {
			nextEl: ".swiper-next",
			prevEl: ".swiper-prev",
		  },
		});
	}
	
	if($("#menu_detail").length > 0) {
		$Core.broker.element_fixed($("#menu_detail"));	
		var headerHeight = $("#menu_detail").outerHeight();
		const observer = new IntersectionObserver(entries => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					const id = entry.target.id;
					$(".menu_tab li a.active").removeClass("active");
					$('#'+id+'-tab').addClass('active');
				}
			});
		}, 												  
	  	{
			root: null,       // viewport
			threshold: 0,    // hiển thị = 0% section active
			rootMargin: `-${headerHeight}px 0px -80% 0px`
		});
		$('.item_tab').each(function() {
			observer.observe(this);
		});
	}
});
$Core.broker = {
	lastScroll : 0,
	element_fixed : function(elm_fixed){
		var parent_width = $(elm_fixed).parent().width();
		$(window).on('scroll',function() {
			var scroll = $(window).scrollTop();
			$(elm_fixed).css("width",parent_width+"px");
			if(scroll > 350) {
				$(elm_fixed).addClass('menu_fixed');
			} else {
				$(elm_fixed).removeClass('menu_fixed');
			}
			$Core.broker.lastScroll = scroll;
//			==============
			/*$('.item_tab').each(function() {
				var top = $(this).offset().top - 100;
				var bottom = top + $(this).outerHeight();
				if (scroll >= top && scroll < bottom) {
					var id = $(this).attr('id');
					$('.menu_tab ul li a').removeClass('active');
					$('#'+id+'-tab').addClass('active');
				}
			});*/
//			==============
			
		});
		
	},
	scrollTo: function (_this,e){
		e.preventDefault();
		var target = $(_this).data("href");
		$(".menu_tab li a.active").removeClass("active");
		$(_this).addClass("active");
		$('html, body').animate({
			scrollTop: $(''+target).offset().top - 200
		}, 1);
	},
	
	alreadyScroll : 0,
	load_more: function(_this, e){
		e.preventDefault();
		var page = $(_this).attr('page');
		var keyword = $("input[name='keyword']").val();
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.broker.list_broker({'page':page,'keyword':keyword}, "more");
		}
		return false;
	},
	list_broker: function(options, action="append"){
		var $_adata = options || {};
		
        $.post(PCMS_URL+'/broker/list.cfg', $_adata, function(respJson){
			$('.showmorethisresult')
				.addClass('d-none')
				.removeClass('clicked');
			$(".total-stock").text(respJson.total_record);
			if(respJson.html.indexOf('empty') >=0) {
				$Core.broker.alreadyScroll = 1;
				if($('.holder_broker .awe__broker-item').length == 0 || action == 'search'){
					$('.holder_broker').html(respJson.html);	
				}				
			}else{
				if(parseInt(respJson.total_record) <= parseInt(respJson.per_page)){
					$Core.broker.alreadyScroll = 1;
				} else {
					$Core.broker.alreadyScroll = 0;
				}
				if($('.holder_broker .awe__broker-item').length && action=="more"){
					$('.holder_broker .awe__broker-item:last').after(respJson.html);
				} else {
					$('.holder_broker').html(respJson.html);
				}
				$('.showmorethisresult').attr('page', parseInt(respJson.current_page)+1);
			}
        }, 'json');
	},
	searchKey: function (_this,e){
		e.preventDefault();
		if(e.keyCode == 13){
			var $_adata = {},
			_form = $(_this).closest("form");
			var keyword = $('input[name="keyword"]',_form).val();
			$_adata["keyword"] = keyword;
			$_adata["page"] = 1;
			$Core.broker.list_broker($_adata,"search");
		}
		
	},
	editInlineField: function(_this, options){
		var $_adata = options || {},
			p_id= $(_this).attr('p_id'),
			p_field= $(_this).attr('p_field'),
			p_element= $(_this).attr('p_element'),
			p_cell = $(_this).closest('.InputCRMHandler');
		if(p_element == 'textarea'){
			p_cell = $(_this).closest(".card-body").find(".content_about");
		} 
		console.log(p_field,p_cell);	
		p_cell.html('<img src="'+URL_IMAGES+'/ripple-loading.svg" />');
		$.post(path_ajax_script+'/index.php?mod=broker&act=load_edit_inline_field', $_adata, function(html){
			p_cell.html(html);
			if(p_element == 'textarea'){
				$(_this).addClass("d-none");
			}
			setTimeout(() => {
				$('.edit_profile_field_'+p_field+'_'+p_id).focus();
			}, 500);
		}); 
		return false;
	},
	save_edit_inline_field: function (_this, e){
		e.preventDefault();
		var _body = $(_this).closest('.content_profile')
			,p_id= $(_this).attr('p_id'),
			p_field= $(_this).attr('p_field'),
			p_cell = $(_this).closest('.InputCRMHandler'),
			p_element= $(_this).attr('p_element'),
			p_value = $('.edit_profile_field_'+p_field+'_'+p_id, _body).val();
		if(p_element == 'textarea'){
			p_cell = $(_this).closest(".content_about");		
			if($('.isoTextArea', p_cell).length){
				$('.isoTextArea', p_cell).each((_i, _elem) => {
					var name= $(_elem).data('name'),
						editorId = $(_elem).attr('id');
					p_value = $Core.util.getTinyMCEContent(editorId);
				});
			} 
		}
		//alert(p_value); return false;
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod=broker&act=load_edit_inline_field', {
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
				$("a[p_field='"+p_field+"']").removeClass("d-none");
			}
		});
		return false;
	},
	cancel_edit_inline_field: function(_this, e){
		e.preventDefault();
		var p_id= $(_this).attr('p_id'),
			p_field= $(_this).attr('p_field'),
			p_element= $(_this).attr('p_element'),
			p_cell = $(_this).closest('.InputCRMHandler');
		if(p_element == 'textarea'){
			p_cell = $(_this).closest(".card-body").find(".content_about"); 
		}
		p_cell.html('<img src="'+URL_IMAGES+'/ripple-loading.svg" />');
		$.post(path_ajax_script+'/index.php?mod=broker&act=load_edit_inline_field', {
			'p_id' : p_id,
			'p_field' : p_field,
			'p_action' : '_cancel'
		}, function(html){
			p_cell.html(html);
			$("a[p_field='"+p_field+"']").removeClass("d-none");
		});
		return false;
	},
	load_list_files: function (for_id, clsTable, options){
		var $_adata = options || {};
		$_adata['for_id'] = for_id;
		$_adata['clsTable'] = clsTable;
		$.post('/index.php?mod=broker&act=load_list_files', $_adata, function(respJson){
			$('.holder_files_'+for_id).html(respJson.html);
		}, 'json');
	},
	ms_save_file: function (_this, e){
		e.preventDefault();
		var _validated = 0,
			$_form = $(_this).closest('form'),
			for_id = $(_this).attr('for_id'),
			clsTable = $(_this).attr('clsTable');
		if($('input.required,textarea.required', $_form).length){
			$('input.required,textarea.required', $_form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			$_form.ajaxSubmit({
				type: 'POST',
				url: '/index.php?mod=broker&act=ms_save_file',
				data: {'for_id':for_id, 'clsTable':clsTable},
				dataType: 'html',
				success: function (html) {
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$_form[0].reset();
						$Core.broker.load_list_files(for_id, clsTable, {});
					}else{
						$Core.messager.alert("Error!",'Upload failed !');
					}
				}
			});
		}
		return false;
	},
	save_profile_pfield: function(_this, e) {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			profile_id = $(_this).attr('profile_id');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: "POST",
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_profile_pfield",
			data: { 'profile_id': profile_id },
			dataType: "json",
			success: function(respJson) {
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_success') >= 0){
					if($('.'+respJson.p_field+'_'+profile_id).length){
						$('.'+respJson.p_field+'_'+profile_id).text(respJson.p_value);
					}
					$Core.messager.alert('Thông báo', 'Cập nhật thành công!');
				} else {
					$Core.messager.alert('Thông báo', 'Cập nhật thất bại!');
				}
			}
		});
		return false;
	},
	search: function (profile_id, _this){
		var $_adata = {};
		$_adata['perPage'] = $(_this).data("per_page");
		$_adata['page'] = 1;
		$_adata['keyword'] = $(_this).val();
		$Core.broker.load_sales(profile_id,$_adata);
	},
	load_sales: function(profile_id, options){
		var $_adata = options || {};
		$_adata['profile_id'] = profile_id;
		$.post('/index.php?mod=broker&act=load_list_billing', $_adata, function(respJson){
			$("#LstBillingSales").html(respJson.html);
			let total_number = respJson.total_number;
			var txt_showing = "";
			if(total_number > 0){
				txt_showing = "Hiển thị "+respJson.start+" đến "+respJson.number_to+" trên tổng "+total_number+" giao dịch";
			}
			$("#text_showing_sales").text(txt_showing);
			/*$('#pagination-container').pagination({
				items: total_number,
				itemsOnPage: $_adata['perPage'],
				prevText: "&laquo;",
				nextText: "&raquo;",
				onPageClick: function (pageNumber) {
					$Core.broker.load_list_billing(profile_id,{page:pageNumber,perPage:$_adata['perPage'],keyword:$_adata['keyword']})
				}
			});*/
		}, 'json');
	},
	load_level_logs: function(profile_id, options){
		var $_adata = options || {};
		$_adata['profile_id'] = profile_id;
		$.post('/index.php?mod=broker&act=load_level_logs', $_adata, function(respJson){
			$('.holder_level_logs_'+profile_id).html(respJson.html);
		}, 'json');
	},
	load_login_logs: function(profile_id, options){
		var $_adata = options || {};
		$_adata['profile_id'] = profile_id;
		$.post('/index.php?mod=broker&act=load_login_logs', $_adata, function(respJson){
			$('.holder_login_logs_'+profile_id).html(respJson.html);
		}, 'json');
	},
	follow_broker: function(_this,broker_id){
		var $_adata ={};
		$_adata['broker_id'] = broker_id;
		$.post('/index.php?mod=broker&act=follow_broker', $_adata, function(res){
			if(res.result){
				if($(_this).hasClass("followed")){
					$(_this).removeClass("followed").attr("data-bs-original-title","Theo dõi").tooltip('hide');	
					if($(_this).hasClass("follow_detail")){
						$(_this).find("span").text("Theo dõi");
					}
				}else{
					$(_this).addClass("followed").attr("data-bs-original-title","Bỏ theo dõi").tooltip('hide');
					if($(_this).hasClass("follow_detail")){
						$(_this).find("span").text("Bỏ theo dõi");
					}
				}
			}
		}, 'json');		
	}, 
	addVideo: function(_this,profile_id){
		var $_adata ={};
		$_adata['profile_id'] = profile_id;
		var link_file = $(_this).closest(".box_form").find("input[name='link_video']").val();
		$_adata['link_file'] = link_file;
		if(link_file != ''){
			$.post('/index.php?mod=broker&act=addVideo', $_adata, function(html){
				if(html != ""){
					console.log(html);
					$('#box_video').html(html);	
				}
				
			}, 'html');
		}
		
	}, 
	uploadImage :function(_this,e){ 
		var toId = $(_this).attr('toId'),
			toImg = $(_this).attr('toImg'),
			profile_id = $(_this).attr('profile_id');
		let type = $(_this).data('type');
		$('#'+toId).val("").trigger('click');
		$('#'+toId).off().change(function(){
			let files = $('#'+toId).prop('files');
			if (files && files.length) {
				var formData = new FormData();
				formData.append('type',type);
				for (var i = 0; i < files.length; i++) {
					formData.append('images[]',files[i]);
				}
				formData.append('profile_id',profile_id);
				var params = {"type":type, 'toImg':toImg}
				$Core.broker.image_upload(formData,params);				
			}
		});
	},
	image_upload: function(formData, params){
		$Core.util.toggleIndicatior(1); 
		$.ajax({
			url: "/index.php?mod="+MOD+"&act=uploadImage",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					var tmp = html.split('|||');
					if(params.type == 'avatar'){
						$('#'+params.toImg).attr("src",tmp[1]); 
					}else if(params.type == 'banner'){
						console.log(params.toImg,tmp[1]);
						$('#'+params.toImg).css("color","red"); 
						$('#'+params.toImg).css("background-image","url("+tmp[1]+")"); 
					}else{
						if($('#'+params.toImg+' .item').length > 0){
							$('#'+params.toImg+' .item:last-child').after(tmp[1]);
						} else {
							$('#'+params.toImg).html(tmp[1]);
						}
					}
					
				}
			}
		});
	},
	upgrade_package: function(_this, e){
		e.preventDefault();
		var package_id = $(_this).data('package_id'),
			time_package = $(_this).attr("time_package");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=upgrade_package', {
				package_id:package_id,
				time_package:time_package
			}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result){
				window.location.href = respJson.redirect_link;
			}else{
				alertify.error("ERROR !");
			}
		}, 'json');
		return false;
	},
	trial_package: function(_this, e){
		e.preventDefault();
		var package_id = $(_this).data('package_id'),
			package_name = $(_this).data('package_name'),
			day_trial = $(_this).data('day_trial');
		$Core.messager.confirm('Xác nhận', 'Bạn muốn dùng thử gói '+package_name+' '+day_trial+' ngày?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=trial_package', {
				'package_id' : package_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result){
					// Kích thoạt hiển thị popup cảm ơn ở đây
					$("#trialSuccess").find(".title_package").text("Gói dùng thử "+package_name+" "+day_trial+" ngày đã được kích hoạt");
					$("#trialSuccess").modal('show');
					setTimeout(function(){
						window.location.reload();
					},3000);
				}else{
					alertify.error("ERROR !");
				}
			}, 'json');
		});
		
		return false;
	},
	help_upgrade_package : function(_this,type){
		$Core.util.toggleIndicatior(1);
		var package_id = $(_this).data('package_id'),
			time_package = $(_this).attr("time_package");
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=help_upgrade_package',{
				"package_id":package_id,
				"time_package":time_package
			}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('500','500', respJson.html, respJson.uid);
		}, 'json');
	},
	formAddField : function(_this,type){
		$Core.util.toggleIndicatior(1);
		var field_id = $(_this).data("field_id"),
			field = $(_this).data("field");
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=formAddField',{"type":type,"field_id":field_id,"field":field}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('500','500', respJson.html, respJson.uid);
		}, 'json');
	},
	AddField : function(_this,type){
		var _form = $(_this).closest("form");
		var title = $("input[name=title]",_form).val();
		var field_id = $("input[name=field_id]",_form).val();
		var image_hidden = $("input[name=image_hidden]",_form).val();
		var field = $(_this).data("field");
		var formData = new FormData();
		let files = $("input[name=image]",_form).prop('files');	
		var star = _form.find(".chk_star:checked").val();	
		var check = true;
		_form.find(".required").each(function(index, elm){
			if($(elm).val() == ""){
				$(elm).addClass("error");
				check = false;
			}else{
				$(elm).removeClass("error");
			}
		});
		if(!check){
			alertify.error("Không bỏ trống các trường có dấu *");
			return false;
		}
		/*if(field == "history_sale" && !$Core.broker.check_stock_code(_form.find("input[name='stock_code']"))) {
			return false;
		}*/
		if(_form.find('.form_field').length){
			_form.find('.form_field').each((_i, _elem) => {
				var field = $(_elem).attr('name');
				formData.append(field,$(_elem).val());
			});
		}
		
		if (files && files.length) {
			formData.append('imgdata',files[0]);
			console.log(files[0]);
		}
		formData.append('type',type);
		formData.append('field_id',field_id);		
		formData.append('field',field);	
		formData.append('star',star);	
		$Core.util.toggleIndicatior(1); 
		$.ajax({
			url: PCMS_URL+'/index.php?mod='+MOD+'&act=formAddField',
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			dataType: "json",
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				if(respJson.result){
					if(field_id != ""){
						alertify.success("Cập nhật thành công!");						
					}else{
						alertify.success("Thêm mới thành công!");
					}		
					
					$("#lst_"+field).html(respJson.html);	
					$(_this).closest(".modal").modal("hide");
				}else{
					alertify.error("ERROR !");
				}				
			}
		});
	},
	deleteField : function(_this,type){
		if(confirm("Bạn có chắc chắn muốn xoá bản ghi này?")){
			$Core.util.toggleIndicatior(1);
			var field_id = $(_this).data("field_id"),
				field = $(_this).data("field");
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=formAddField',{"type":"delete","field_id":field_id,"field":field}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result){
					alertify.success("Xoá thành công!");	
					$("#lst_"+field).html(respJson.html);	
				}else{
					alertify.error("ERROR !");
				}	
			}, 'json');
		}		
	},
	open_meta : function(_this,type){
		$Core.util.toggleIndicatior(1);
		var meta_id = $(_this).data("meta_id"),
			gId = $(_this).attr("gId");
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_meta',{"meta_id":meta_id,"gId":gId}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('500','500', respJson.html, respJson.uid);
		}, 'json');
	},
	addMeta : function(_this,type){
		var _form = $(_this).closest("form"),
			gId = $(_this).attr("gId"),
			meta_id = $("input[name=meta_id]",_form).val(),
			_validated = 0,
			$_adata = {};
		_form.find(".required").each(function(index, elm){
			if($(elm).val() == ""){
				$(elm).addClass("error");
				++_validated;
			}else{
				$(elm).removeClass("error");
			}
		});
		if(_validated > 0){
			alertify.error("Không bỏ trống các trường có dấu *");
			return false;
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
		if(_validated == 0) {
			$Core.util.toggleIndicatior(1); 
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&act=add_meta',
				data: $_adata,
				dataType: 'json',
				success: function (respJson) {
					$Core.util.toggleIndicatior(0);
					if(respJson.result){
						if(meta_id > 0){
							alertify.success("Cập nhật thành công!");						
						}else{
							alertify.success("Thêm mới thành công!");
						}	
						console.log(gId);
						$("#"+gId).removeClass("loaded");
						_autoload();
						$(_this).closest(".modal").modal("hide");
					}else{
						alertify.error("ERROR !");
					}	
				}
			});
		}
		
	},
	delete_meta : function(_this,type){
		$Core.util.toggleIndicatior(1);
		var meta_id = $(_this).data("meta_id"),
			gId = $(_this).attr("gId");
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete_meta', {'meta_id':meta_id,'gId':gId}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result){
					$("#"+gId).removeClass("loaded");
					_autoload();
					alertify.success("Xóa thành công!");
				}else{
					alertify.error("Lỗi!");
				}
			}, 'json');
		});	
	},
	check_stock_code: function(_this, e){
		var stock_code = $(_this).val();
		var checkValid = true;
		if(!$Core.util.isEmpty(stock_code)){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod=leasing&act=check_stock_code', {
				'stock_code' : stock_code
			}, function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_invalid') >= 0){
					checkValid = false;
					alertify.error("Mã căn không tồn tại!");
					$(_this).addClass("error");
					return false;
				}else{
					$(_this).removeClass("error");
				}
				
			});
		}
		return checkValid;
	},
	copyToClipboard: function(_this, e) {
		e.preventDefault();
		/* Get the text field */
		var copyText = $(_this).data('link');
		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText);
		$Core.broker.setTooltip($(_this), 'Đã sao chép link',"Sao chép link");
		return false;
	},
	setTooltip: function (_this, msg_click,msg_def) {
	  	$(_this).attr('data-bs-original-title', msg_click)
		.tooltip('show');
		setTimeout(function() {
			$(_this).tooltip('hide').attr('data-bs-original-title', msg_def);
	  	}, 1000);
	},
	hideTooltip: function (_this,msg_def) {
	  	setTimeout(function() {
			$(_this).tooltip('hide').attr('data-bs-original-title', msg_def);
	  	}, 1000);
	},
	close_pop: function(_this, e){
		e.preventDefault();
		$(_this).closest(".modal").modal('hide').removeAll();
		return false;
	},
	file_explorer : function(_this, e) {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			toImg = $(_this).attr('toImg'),
			uid = $(_this).attr('uid'),
			params = {'toImg':toImg};

		$('#select_image_'+uid).val("").click();
		return false;
	},
	upload_image : function(_this, e) {
		e.preventDefault();
		var toImg = $(_this).attr('toImg'),
			params = {'toImg':toImg};

		let file, files = $(_this).prop('files');
		if (files && files.length) {
			file = files[0];
			console.log(file);
			if (/^image\/\w+/.test(file.type)) {
				$Core.broker.file_upload(file, params);
			}
		}
		return false;
	},
	file_upload : function(file, params){
		console.log(file);
		var URL = window.URL || window.webkitURL,
			imgdata = URL.createObjectURL(file),
			filename = $.trim(file.name),
			$_adata = params || {};
		$_adata['filename'] = filename;
		$_adata['imgdata'] = imgdata;

		$.post('/index.php?mod='+MOD+'&act=open_cropper', $_adata, function(respJson){
			toggleIndicatior(0);
			$Core.popup.open('500','500', respJson.html, 'open_cropper_'+respJson.uid);
			$('#open_cropper_'+respJson.uid).on('shown.bs.modal', function(){
				var $cropper = $('#'+'cropper_'+respJson.uid),
					$cropper_width = $('#'+'cropper-width-'+respJson.uid),
					$cropper_height = $('#'+'cropper-height-'+respJson.uid),
					options = {
						aspectRatio: (4 / 4),
						crop: function(e) {
							$cropper_width.val(Math.round(e.detail.height));
							$cropper_height.val(Math.round(e.detail.width));
						}
					},
					originalImageURL = $cropper.attr('src'),
					uploadedImageName = 'cropped.png',
					uploadedImageType = 'image/png',
					uploadedImageURL, $target;
				$cropper.cropper(options);
				$('.ui-cropper-tool').on('click', function(e){
					$Core.util.stopEventHandler(e);
					var $_this = $(this),
						data = $_this.data(),
						cropper = $cropper.data('cropper'),
						$_form = $_this.closest('form');
					if (cropper && data.method) {
						data = $.extend({}, data); // Clone a new one
						if (typeof data.target !== 'undefined') {
							$target = $(data.target);
							if (typeof data.option === 'undefined') {
								try {
									data.option = JSON.parse($target.val());
								} catch (e) {
									console.log(e.message);
								}
							}
						}
						cropped = cropper.cropped;
						switch (data.method) {
							case 'rotate':
								if (cropped && options.viewMode > 0) {
									$cropper.cropper('clear');
								}
								break;
							case 'getCroppedCanvas':
								if (uploadedImageType === 'image/jpeg') {
									if (!data.option) {
										data.option = {};
									}
									data.option.fillColor = '#fff';
								}
								break;
						}
						var result = $('#cropper_'+respJson.uid).cropper(data.method, data.option);
						switch (data.method) {
							case 'rotate':
								if (cropped && options.viewMode > 0) {
								  $cropper.cropper('crop');
								}
								break;
							case 'scaleX':
							case 'scaleY':
								$(this).data('option', -data.option);
								break;
							case 'getCroppedCanvas':
								if (result) {
									var imagedata = result.toDataURL(uploadedImageType),
										imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, ""),
										$_bdata = params || {};
									$_bdata['imgdata'] = imgdata;
									$_bdata['filename'] = filename;
									$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=upload_avatar', $_bdata, function(html){
										$cropper.cropper('destroy');
										$('#'+'frmIssue')[0].reset();
										$('.btn-close',$_form).trigger('click');
										if(html.indexOf('_error') >= 0){
											$Core.messager.alert(__['Message'], __['Image upload error']);
										} else {
											var tmp = html.split('|||');
											$('#'+params.toImg).attr('src', tmp[1]);
											$(".avatar_profile").attr('src', tmp[1]);
										}
									});
									return false;
								}
								break;
							case 'destroy':
								if (uploadedImageURL) {
									URL.revokeObjectURL(uploadedImageURL);
									uploadedImageURL = '';
									$image.attr('src', originalImageURL);
								}
								break;
						}
						if (typeof result === 'object' && result !== cropper && $target) {
							try {
								$target.value = JSON.stringify(result);
							} catch (e) {
								console.log(e.message);
							}
						}
					}
					return false;
				});
			});
		},"json");
	},
	handle_price_duration: function(_this, e){
		var time_package = $("input[name='time_package']:checked").val(),
			txt_time = '<sub class="h6 text-body pricing-duration mt-auto mb-1">/1 tháng</sub>';
		if(time_package == "price_year") {
			txt_time = '<sub class="h6 text-body pricing-duration mt-auto mb-1">/12 tháng</sub>';
		} else if(time_package == "price_6month"){
			txt_time = '<sub class="h6 text-body pricing-duration mt-auto mb-1">/6 tháng</sub>';
		}
		$('.price-toggle').each((_i, _elem) => {
			var dg_price = $(_elem).attr(time_package);
			if(dg_price == 0) {
				$(_elem).html("Miễn phí");
			}else{
				$(_elem).html(dg_price+txt_time);
			}
		});
		$(".btn_upgrade").attr("time_package",time_package);
	},
	loadPayMent: function(_this, e){
		var parent = $(_this).closest(".lst-pay");
		var payment_method = $("input[name='payment_method']:checked",parent).val();
		if(payment_method == "pay") {
			$("#txt_payment").text("Chuyển khoản Ngân hàng / Quét mã QR");
		}else{
			$("#txt_payment").text("Tiền mặt");
		}
	},
	buyPackage: function(_this, e){
		e.preventDefault();
		var parent = $(_this).closest(".lst-pay");
		form.ajaxSubmit({
			method: "POST",
			url: PCMS_URL+"/payment/buy.cfg",
			dataType : 'json',
			success: function (respJson) {
				form.resetForm();
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_success') >= 0){
					$('.video-player').html(respJson.html);
				}
			}
		});
	},
	cancel_payment: function(_this, e){
		e.preventDefault();
		var order_id = $(_this).attr('order_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn hủy giao dịch này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/payment-cancel.cfg', {'order_id':order_id}, function(respJson){
				$Core.util.toggleIndicatior(0);
				if(respJson.result){
					window.location.href = respJson.url
				}else{
					window.reload();
				}
			}, 'json');
		});
	},
	copyCode: function(_this, e) {
		e.preventDefault();
		var copyCode = $(_this).data("value");
		navigator.clipboard.writeText(copyCode);
		return false;
	},
	getStatusOrder: function() {
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=getStatusOrder', {'order_id':order_id}, function(respJson){
			$Core.util.toggleIndicatior(0);
			console.log(respJson);
			if(respJson.status != 1){
				setTimeout(function () {
					$Core.broker.getStatusOrder();
				}, 3000);
			}else{
				if(ACT == "detailOrder") {
					$("#status_order").removeClass("bg-warning").addClass("bg-success").text(`Đã thanh toán`);	
					$("#status_date").text(respJson.status_date).parent().removeClass("d-none");	
				}else{
					$("#status_order").removeClass("bg-warning").addClass("bg-success").text(`Thành công`);
					$("#box_cancel").remove();
				}
				
			}
		}, 'json');
	},
	tab: function (_this,event) {
		$(_this).removeClass("active");
	},
	save_template :function (_this,e) {
		e.preventDefault();
		var template_id = $(_this).attr("template_id");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=save_template', {'template_id':template_id}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result) {
				alertify.success(respJson.msg);
				setTimeout(function(){
					window.location.href = respJson.url;
				},500)
			}else{
				alertify.error("ERROR!");
				window.location.reload();
			}
		}, 'json');
	},
	showHideBirthday :function (_this,e) {
		e.preventDefault();
		var is_show_birthday = $(_this).val();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=showHideBirthday', {'is_show_birthday':is_show_birthday}, function(respJson){
			$Core.util.toggleIndicatior(0);
		}, 'json');
	},
	view_more: function(_this,e){
		e.preventDefault();
		var parent = $(_this).closest(".content_post"),
			content = parent.find(".content_full").text();
		parent.html(content);
	}
}
function _autoload(){
	if($('.ajax:not(.loaded)').length){
		$('.ajax:not(.loaded)').each((_i, _elem) => {
			var url = $(_elem).data('url'),
				gId = $(_elem).attr('id'),
				$_adata = $(_elem).data('options') || {};	
			$_adata['gId'] = gId;
			$.post(url, $_adata, function(respJson){
				let $html = respJson.html;
				if($html.indexOf('_empty') >= 0){
					if($(_elem).hasClass("home_shared")){
						$(".home_block_shared").hide();
					}
					$(_elem).remove();
				} else {
					$(_elem).addClass('loaded').html($html);
				}
				if(respJson.callback){
					eval(respJson.callback);
				}
			},'json');
		});
	}
}
