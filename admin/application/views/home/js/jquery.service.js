$(function(){
	if($(".sharer-icons").length){		
		$(".sharer-icons").each(function(index){
			var link_share = $(this).data('link_share'),
				title_share = $(this).data('title_share');
			$(this).empty().sharer({
				networks: ["facebook"],
				url : PCMS_URL+link_share,
				title : title_share
			});
		})
	}
	$(window).scroll(function(){
		if(isScrolledIntoView('#showmorethisresult') && $Core.service.alreadyScroll == 0){
			$Core.service.alreadyScroll = 1;
			$('.showmorethisresult').removeClass('d-none').trigger('click');
		}
	});
	if($('.service__checkbox-radio').length){
		$('.service__checkbox-radio').each((_i, _elem) => {
			var total_checked = 0,
				ref_id = $(_elem).attr('ref_id');
			if($('input[type=radio][ref_id='+ref_id+']:checked').length){
				$('#'+ref_id).removeClass('d-none');
			}
		});
	}
	$("seclect.select2").select2();
	if(ACT == 'detail'){
		$Core.news.load_comments(service_id, 'Service', {'action' : 'reload'});
		if($("#box_list_relate").length > 0){
			$('#box_list_relate').owlCarousel({
				loop:true,
				margin:20,
				nav:true,
				dots:false,
				responsiveClass:true,
				navText:[
					"<i class='bx bx-chevron-left'></i>"
					,"<i class='bx bx-chevron-right' ></i>"
				],
				responsive:{
					0:{items:1,},
					575:{items:2,},
					1360:{items:2.2, },
					1400:{items:3,}
				}
			});
		}
		if($(".rate").length > 0){
			$('.rate').click(function () {
				$('.rate').removeClass('rated')
				$(this).prevAll().andSelf().addClass('rated');
				var value = $(this).data("value");
				$("#inp_rate").val(value);
			});
			$('.rate').mouseenter(function () {
				$(this).prevAll().andSelf().addClass('hover');
			});
			$('.rate').mouseleave(function () {
				$(this).prevAll().andSelf().removeClass('hover');
			});
		}
		
	}
});
$Core.service = {
	alreadyScroll : 0,
	load_more: function(_this, e){
		e.preventDefault();
		var page = $(_this).attr('page');
		if(!$(_this).hasClass('clicked')){
			$(_this).addClass('clicked');
			$Core.service.list_service({'page':page}, "more");
		}
		return false;
	},
	searchKeyword : function (_this,e) {
		if(e.keyCode === 13 ){
			$Core.service.load_service({},"loadTab");
			e.preventDefault();
		}
	},
	showTabService: function (_this,e) {
		e.preventDefault();
		var cat_id = $(_this).data("cat_id"),
			_form = $("#form_search"),
			curent_cat_id = $('input[name="cat_id"]',_form).val(),
			total_record = $(_this).attr("total_record");
		if(curent_cat_id != cat_id) {
			$('input[name="cat_id"]',_form).val(cat_id);
			$('input[name="page"]',_form).val(1);
			$Core.service.load_service({},"loadTab");			
		}	
	},	  
	load_service: function(options, action="loadTab"){
		var _form = $("#form_search"),
			$_adata = options || {};
		if($('.search_field',_form).length){
			$('.search_field',_form).each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		var project_id = $("input[name='project_id']:checked",_form).val();
		$_adata["project_id"] = project_id;
		var cat_id = $_adata["cat_id"];
		
//		console.log(cat_id);
		$Core.util.toggleIndicatior(1);
        $.post(PCMS_URL+'/dv/list.cfg', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.check_key_cat == 1) {
				$Core.util.popstate(respJson.url);	
				$("#btn-tab_"+respJson.cat_id).tab("show");
				$('input[name="keyword"]',_form).val("");
				$("#content_"+respJson.cat_id).html(respJson.html);
				$('input[name="cat_id"]',_form).val(respJson.cat_id);
			}else{
				$("#content_"+cat_id).html(respJson.html);
			}
			$Core.util.popstate(respJson.url);	
			$("#current_url").val(respJson.url);
			console.log(action);
			if(parseInt(respJson.total_page) > 1) {
				$('#pagination-container_'+cat_id).pagination({
					items: parseInt(respJson.total_page),
					itemsOnPage: respJson.perPage,
					prevText: "&laquo;",
					nextText: "&raquo;",
					currentPage : parseInt(respJson.current_page),
					onPageClick: function (pageNumber) {
						$_adata['page'] = pageNumber;
						$('input[name="page"]',_form).val(pageNumber);
						$Core.service.load_service($_adata,"pagination")
					}
				});
			}else{
				$('#pagination-container_'+cat_id).html("");
			}
			$Core.service.loadMenu($_adata);
			
        }, 'json');
	},
	loadMenu: function(options){
		var _form = $("#form_search"),
			$_adata = options || {};
		if($('.search_field',_form).length){
			$('.search_field',_form).each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}	
		var project_id = $("input[name='project_id']:checked",_form).val();
		$_adata["project_id"] = project_id;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadMenu', $_adata, function(respJson){
			$(".lstTab_service").html(respJson.html);	
			$('.total-stock').text(respJson.total_service);
			/*if(window.innerWidth <= 991){
				$('.lstTab_service').readmore({
					speed: 75, 
					maxHeight: 112	
				});
			}*/
			
		},"json");
	},
	lowercase: function(_this) {
		let value = $(_this).val();
		value = value.toLocaleLowerCase();
		$(_this).val(value[0].toUpperCase()+value.slice(1));
	},
	close_pop: function(_this, e){
		e.preventDefault();
		var curren_url = $("#current_url").val();
		$Core.util.popstate(curren_url);
		return false;
	},
	select_building: function(_this, e){
		var toId = $(_this).attr('toId'),
			tp = $(_this).getAttr('tp', 'option'),
			action = $(_this).data('action'),
			$_adata = {'tp':tp};
		var block_id = $(_this).val();
			$_adata['block_id'] = block_id;
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_building', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html);
			if(action == 'search'){
				$Core.service.list_service({},'search');
			}			
		});
	},
	select_block: function(_this, e){
		var project_id = $(_this).val(),
			toId = $(_this).attr('toId'),
			action = $(_this).data('action');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_block', {
			'project_id' : project_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html);
			if(action == 'search'){
				$('#slb_Building_Id').empty();
				$Core.service.list_service({},'search');
			}else{
				$('#slb_add_Building_Id,#slb_add_Building_Id,#slb_add_floor_range,select[name="building_id"]').empty().val("");
				$Core.service.loadAddress();
			}			
		});
	},
	select_floorRange: function(_this, e){
		var building_id = $(_this).val(),
			toId = $(_this).attr('toId'),
			action = $(_this).data('action');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_floorRange', {
			'building_id' : building_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html);
			
		});
	},	
	loadStockCode : function(_this,e) {
		e.preventDefault();
		var _form = $(_this).closest("form");
		var block_id = $("select[name='block_id']",_form).val();
		console.log(block_id);
		var building_id = $("select[name='building_id']",_form).val();
		var floor_range = $("select[name='floor_range']",_form).val();
		var stock_code = $("select[name='stock_code']",_form).val();
		var field = $(_this).data("field");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=loadStockCode', {
			'block_id' 	: block_id,
			'building_id' 	: building_id,
			'floor_range' 	: floor_range,
			'stock_code' 	: stock_code,
			'field' 		: field,
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(field == "building_id"){
				$("select[name='floor_range']").html("<option value=''>Tầng</option>");
			}
			$("select[name='"+field+"']").html(html);
			$Core.service.loadAddress();
		});
	},	
	loadAddress : function() {
		var _form = $("#frmAddService");
		var project = $("select[name='project_id']",_form).find("option:selected").text();
		var block = $("select[name='block_id']",_form).find("option:selected").text();
		var building = $("select[name='building_id']",_form).find("option:selected").text();
		var address = "";
		if(building != "" && $("select[name='building_id']",_form).val() != 0) {
			address = "Toà " +building;
		}
		if(block != "" && $("select[name='block_id']",_form).val() != 0) {
			address += ((address != "")?", ":"") + block;
		}
		if(project != "" && $("select[name='project']",_form).val() != 0) {
			address += ((address != "")?", ":"") + project;
		}
		console.log(address);
		$("input[name='address']",_form).val(address);
	},
	addService : function(_this,options){
		var type = $(_this).data("type");
		var $_adata = options || {};
		$_adata['type'] = type;
		if(type == 'add'){
			var _form = $(_this).closest("form");
			let stockCode = $("input[name='stockCode']",_form).val();
			let building_id = $("select[name='building_id']",_form).val();
			let floor_range = $("select[name='floor_range']",_form).val();
			let image = $("input[name='image']",_form).val();
			var check = 1;
			$("select.required,input.required",_form).each(function(index, elm){
				if($(elm).val() == ""){
					$(elm).focus().addClass("is-invalid");
					check = 0;
					return false;
				}else{
					$(elm).removeClass("is-invalid");
				}
			});
			if(check == 0){
				$Core.util.toggleIndicatior(0);
				alertify.error("Các trường có dấu * là bắt buộc!");
				return false;
			}
			if(!$Core.service.checkStockCode(_this)){
				check = 0;
				$Core.util.toggleIndicatior(0);
				return false;
			}
			if($('.add_field',_form).length){
				$('.add_field',_form).each((_i, _elem) => {
					var field = $(_elem).attr('name');
					$_adata[field] = $(_elem).val();
				});
			}
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=addService',$_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result){
				if(type == "open"){
					$Core.popup.open('500','500', respJson.html, respJson.uid);	
					$('#'+respJson.uid).on('shown.bs.modal', function(){
						if($(".input-tags").length){
							$(".input-tags").selectize({
								delimiter: "|",
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
										url:path_ajax_script+'/index.php?mod='+MOD+'&act=search_tag',
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
						}
					});
				}else{
					_form.reset();
					_form.closest(".modal").modal('hide');
					alertify.success("Thành công!");
				}
			}else{
				alertify.error("ERROR!");
			}
			
		}, 'json');
	},
	checkStockCode : function(_this) {
		var _form = $(_this).closest("form");
		var stockCode = $("input[name='stockCode']",_form).val();
		var building_id = $("select[name='building_id']",_form).find("option:selected").text();
		var floor_range = $("select[name='floor_range']",_form).find("option:selected").text();
		var check = 1;
		if(stockCode != "" && building_id != "" && floor_range != ""){
			var stock_code = building_id+floor_range+stockCode;			
			$("input[name='stock_code']",_form).val(stock_code);
			$.ajax({
				'type': "POST",
				'dataType'	:	"html",
				'url'	:	PCMS_URL+'/index.php?mod='+MOD+'&act=check_stock_code',
				'async'	:	false,
				'data'	:	{'stock_code' : stock_code},
				'success'	:	function(html){
					$Core.util.toggleIndicatior(0);
					console.log(html.indexOf('_invalid'));
					if(html.indexOf('_invalid') >= 0){
						alertify.error("Mã căn "+stock_code+" không tồn tại");
						check = 0;
					}
				},
			});
		}else{
			$("input[name='stock_code']",_form).val("");
		}
		return check;
	},
	select_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('.select_file_'+toId).trigger('click');
		return false;
	},
	upload_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('id'),
			form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);
		form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod=ajax&sub=helper&act=uploadImage',
			dataType:'html',
			success : function(image){
				$Core.util.toggleIndicatior(0);
				form.clearForm();
				form.resetForm();
				$('#content_file_'+toId).val(image);
			}
		});	
		return false;
	},
	copyToClipboard: function(_this, e) {
		e.preventDefault();
		var copyText = $(_this).data('link');
		navigator.clipboard.writeText(copyText);
		return false;
	},
	open_service: function(_this, e){
		e.preventDefault();
		var service_id = $(_this).getAttr('service_id', 0),
			return_url = window.location.href;
			
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_service', {
			'service_id' 	: service_id,
			'return_url' 	: return_url,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('500','500', respJson.html, respJson.uid);	
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				$Core.util.popstate(respJson.link);
				$Core.news.load_comments(service_id, 'Service', {'action' : 'reload'});
			});
			
		},"json");
		return false;
	},
	checked: function(_this, e){
		e.preventDefault();
		$(_this).find("input[type='radio']").prop("checked","true").change();
	},
};