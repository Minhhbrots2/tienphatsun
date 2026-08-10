$(function(){
	if(MOD=='tool' && ACT == 'calendar'){
		$Core.calendar.init();
	}
});
$(document).on("click",".item_doc",function(){
	$(".item_doc").removeClass("active");
	$(this).addClass("active");
})
$(document).click(function (e){	
	var container = $(".form_search");
	if (!container.is(e.target) && container.has(e.target).length === 0 ) {
		$(".box_search_suggestion").removeClass('show');
	}
});
$Core.docs = {	
	open_doc: function(_this, e){
		e.preventDefault();
		var cat_id = $(_this).data('cat_id'),
			doc_id = $(_this).data('doc_id'),
			$_adata = {'cat_id':cat_id, 'doc_id':doc_id};	
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_doc', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				var _modal = $(this);
				if($(".attachments").length > 0) {
					$('#attachments_'+respJson.uid).MultiFile({
						list: '#MultiFile-preview_'+respJson.uid
					});
				}
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
		}, 'json');
		return false;
	},
	loadRole: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest("form"),
			department_id = $(_this).val(),
			role_ids = $("select[name='role_ids[]']",_form).val(),
			toId = $(_this).attr("toId"),
			$_adata = {'department_id':department_id, 'role_ids':role_ids};	
//		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_option_role', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$("#"+toId).val("").trigger("change");
			$("#"+toId).html(respJson.html);
		}, 'json');
		return false;
	},	
	save_doc : function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			doc_id = $(_this).data('doc_id'),
			$_adata = {'doc_id':doc_id};
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
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_docs",
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
	},
	removeFile : function(_this,e) {
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa file này?', function(){
			var doc_id = $(_this).attr("doc_id"),
				url = $(_this).data("url");
			$.post('/index.php?mod='+MOD+'&act=delete_file', {
				'doc_id' : doc_id, "url":url
			}, function(respJson){
				if(respJson.result) {
					$(_this).closest(".MultiFile-label").remove();
				}else{
					alertify.error("ERROR!");
				}
			},"json");
		});
	},
	setView : function(_this,e) {
		var view = $(_this).data("type"),
			type = $(_this).data("doc_type"),
			parent = $(_this).closest(".box_view");
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=set_view', {"view":view}, function(respJson){
			$(".btn_view_docs").toggleClass("active");
			if(ACT == "default") {				
				$Core.docs.load_docs({'type':"pin"}); 
				$Core.docs.load_docs({'type':"top"});
			}else{
				$Core.docs.load_docs({'type':type});
			}
			
		}, 'json');
		return false;
	},	
	pin_doc : function(_this,e) {
		var doc_id = $(_this).attr("doc_id"),
			status = $(_this).attr("status");
		status = (status == 1) ? 0 : 1;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=pin_doc', {"doc_id":doc_id,"status":status}, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(respJson.result) {
				$(".document_"+doc_id).text(respJson.text).attr("status",status).toggleClass("active");					
				if(ACT == 'default') {
					var parent_pin = $(_this).closest(".list_docs_pin");					
					$Core.docs.load_docs({'type':"pin"}); 
					/*$(".item_doc_"+doc_id,parent_pin).remove();
					if($(".item_doc_"+doc_id).length == 0) {
						$(".box_doc_pined").hide();
					}*/
				}
			}else{
				alertify.error("ERROR!");
				window.location.reload();
			}
		}, 'json');
		return false;
	},	
	load_docs: function(options){
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		console.log($_adata);
//		$_adata['cat_id'] = cat_id;
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_docs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			
			$('.breadcrumd_name').html(respJson.breadcrumd_name);
			if(respJson.type == ""){
				$('.list_docs').html(respJson.html);
			}else {
				$('.list_docs_'+respJson.type).html(respJson.html);
			}			
			
			if($(".box_doc_"+respJson.type).length > 0) {
				if(respJson.total > 0) {
					$(".box_doc_"+respJson.type).show();
				}else{
					$(".box_doc_"+respJson.type).hide();
				}
			}		
//			console.log(respJson.url);
			$Core.util.popstate(respJson.url);
			if($('[data-fancybox]:not(.loaded)').length > 0) {
				$('[data-fancybox]:not(.loaded)').fancybox({
					buttons : ['zoom','download','close'],
					afterShow : function(instance, current) {
						var _src = current.src;
						if(_src.indexOf('docs.google.com/viewer?url') >= 0){
							var _url = _src.split("url=")[1];
						} else if(_src.indexOf('drive.google.com') >= 0){
							var _gid = _src.split(/id=(.*)\&sz=(.*)/)[1],
								_url = `https://drive.usercontent.google.com/download?id=${_gid}&export=download&authuser=0`;
						} else {
							_url = $.trim(_src);
						}
						$("[data-fancybox-download]").attr('href', _url);
						let _timeout;
						$_document.on('touchstart mousedown', '.fancybox-image', (ev) => {
							_timeout = setTimeout(() => {
								var _link = document.createElement("a"),
									_src = $(this).attr('src');
								if(_src.indexOf('drive.google.com') >= 0){
									var _gid = _src.split(/id=(.*)\&sz=(.*)/)[1],
										_src = `https://drive.usercontent.google.com/download?id=${_gid}&export=download&authuser=0`;
								} else {
									_src = $.trim(_src);
								}
								_link.href = _src;
								_link.download = "";
								_link.click();
							}, 2000);
						});
						$_document.on('touchend mouseup mouseleave', '.fancybox-image', (ev) => {
							clearTimeout(_timeout);
						});
					}, beforeShow: function(instance, current){}
				});
				$('[data-fancybox]:not(.loaded)').addClass("loaded");
			}
				
		},'json');
	},
	delete_doc : function(_this,e) {
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa văn bản này?', function(){
			var doc_id = $(_this).attr("doc_id");
			$.post('/index.php?mod='+MOD+'&act=delete_doc', {
				'doc_id' : doc_id
			}, function(respJson){
				if(respJson.result) {
					$Core.docs.load_docs({});
					alertify.success("Xóa thành công");
				}else{
					alertify.error("ERROR!");
				}
			},"json");
		});
	},
	view_doc : function(_this,e) {
		var doc_id = $(_this).attr("doc_id"),
			view = $(_this).data("view");
		console.log(doc_id);
		if(view == "view_tr") {
			$(_this).find(".lst_image .item_file:first-child").trigger("click");
		}else if(view == "view_td") {
			$(_this).closest("tr").find(".lst_image .item_file:first-child").trigger("click");
		}else if(view == "view_suggest") {
			$(_this).closest(".item_suggest").find(".lst_image .item_file:first-child").trigger("click");
		}else{
			$(_this).closest(".box_item_doc").find(".lst_image .item_file:first-child").trigger("click");
		}
		
	},
	open_folder: function(_this, e){
		e.preventDefault();
		var folder_id = $(_this).data('folder_id'),
			$_adata = {'folder_id':folder_id};	
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_folder', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				var _modal = $(this);
				if($(".attachments").length > 0) {
					$('#attachments_'+respJson.uid).MultiFile({
						list: '#MultiFile-preview_'+respJson.uid
					});
				}

			});
		}, 'json');
		return false;
	},
	check_type: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			uid = $(_this).attr("uid"),
			type = $(_this).val();	
		if(type == 0) {
			$("#department_"+uid,_form).addClass("d-none");
		}else{
			$("#department_"+uid,_form).removeClass("d-none");
		}		
		return false;
	},
	save_folder : function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			folder_id = $(_this).data('folder_id'),
			$_adata = {'folder_id':folder_id};
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
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_folder",
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
	},
	load_folder: function(options){
		var $_adata = options || {},
			type = $_adata['type'] || 0;
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_folder', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(type == 0) {
				$('.list_folder').html(respJson.html);
				if(respJson.total == 0) {
					$(".box_folder_general").hide();
				}
			}else{
				$('.my_list_folder').html(respJson.html);
				if(respJson.total == 0) {
					$(".box_my_folder").hide();
				}
			}							
		},'json');
	},
	redirectFolder: function(_this,e){
		e.preventDefault();
		var link = $(_this).data("href");
		if(link != ""){
			window.location.href = link;
		}
	},	
	search_doc : function(_this,e) {
		e.preventDefault();
		var field = $(_this).data("field");
		if((field == "keyword" && e.keyCode === 13) || field != "keyword" ) {
			var _form = $(_this).closest("form");
			var $_adata = {};
			if($('.search_field').length){
				$('.search_field').each((_i, _elem) => {
					var field = $(_elem).data('field');
					$_adata[field] = $(_elem).val();
				});
			}	
			$Core.util.toggleIndicatior(1);
			if(ACT == "default") {
				$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=search_doc', $_adata, function(respJson){
					window.location.href=respJson.url;
				}, 'json');
			}else{
				$Core.docs.load_docs($_adata);
			}
		}		
		return false;
	},
	search_suggest : $Core.util.delay((_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			keyword = $("input[name=keyword]",_form).val(),
			is_important = $("input[name=is_important]:checked",_form).val(),
			$_adata = {"keyword":keyword,"is_important":is_important};
			if($('.search_field').length){
				$('.search_field').each((_i, _elem) => {
					var field = $(_elem).data('field');
					$_adata[field] = $(_elem).val();
				});
			}
		if(e.keyCode !== 13) {
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=search_suggest', $_adata, function(respJson){
				$(".lst_suggest_docs",_form).html(respJson.html);
				$(".box_search_suggestion",_form).addClass("show")
			}, 'json');
		}else{
			$(".box_search_suggestion",_form).removeClass("show")
		}
		return false;
	},500),
	view_doc_detail: function(_this, e){
		e.preventDefault();
		var doc_id = $(_this).attr('doc_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=view_doc_detail', {
			'doc_id' : doc_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(__w*3/4,respJson.html,respJson.uid);
		}, 'json');
		return false;
	},
}