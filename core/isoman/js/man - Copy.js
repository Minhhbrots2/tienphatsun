$(document).ready(function(){
	$("body").live("click",function(){
		$(".isoman-menu").hide();
	});
	$(".ajOpenDialog,.ajOpenDialogIframe").live('click',function(){
		var $_this = $(this);
		var isInIframe = $_this.hasClass("ajOpenDialogIframe")?1:0;
		if(isInIframe){
			var myWindow = window.open("/admin/isomanager/", "ISOMAN", "width=860, height=620");
		}else{
			var adata = {
				"for_id":$_this.attr("isoman_for_id"),
				'isoman_multiple': $_this.attr('isoman_multiple'),
				"isInIframe":isInIframe
			};
			$.ajax({
				type: "POST",
				url:path_ajax_script+'/index.php?act=isoman_load_open_dialog',
				data: adata,
				dataType: "html",
				success: function(html){
					makepopupnotresize('auto','auto',html,'OpenDialog');
					if($.cookie("isoman_last_dir")==''||$.cookie("isoman_last_url")==''){					
						isoman_load_folder($_this.attr("isoman_for_id"),$("#isoman-current-typelist-"+$_this.attr("isoman_for_id")).val(),$("#isoman-current-dir-"+$_this.attr("isoman_for_id")).val(),$("#isoman-current-url-"+$_this.attr("isoman_for_id")).val());
					}else{
						isoman_load_folder($_this.attr("isoman_for_id"),$("#isoman-current-typelist-"+$_this.attr("isoman_for_id")).val(),$.cookie("isoman_last_dir"),$.cookie("isoman_last_url"));
					}
				}
			});
		}
		return false;
	});
	$(".isoman-menu-view").live('click',function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		
		var $_img = $(".isoman-checked").attr("isoman_url").replace($("#isoman-current-domain-"+$isoman_for_id).val(),'');
		var html = '<div class="headPop ui-draggable-handle" style="cursor: move;"> <a href="javascript:void();" class="closeEv close_pop"></a> <b>'+$_img+'</b> </div>';
		html += '<img src="'+$_img+'" style="max-width:800px;" />';
		
		$("#isoman-action-menu").hide();		
		makepopupnotresize('auto','auto',html,'ViewImage');
				
		return false;
	});
	$(".isoman-folder-create,.isoman-rename").live("click",function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		var folder_name = "";
		var folder_name_dir = "";
		var isoman_type = "";
		if($_this.hasClass("isoman-rename")){
			folder_name_dir = $(".isoman-checked").attr("isoman_dir");
			isoman_type = $(".isoman-checked").attr("isoman_type");
		}
		var adata = {
			"for_id":$isoman_for_id,
			"abs_url":$("#isoman-current-abs_url-"+$isoman_for_id).val(),
			"isoman_dir":$("#isoman-current-dir-"+$isoman_for_id).val(),
			"isoman_url":$("#isoman-current-url-"+$isoman_for_id).val(),
			"folder_name_dir":folder_name_dir,
			"isoman_type":isoman_type
		};
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?act=isoman_load_one_folder',
			data: adata,
			dataType: "html",
			success: function(html){ 
				if(folder_name_dir==""){
					makepopupnotresize('auto','auto',html,'LoadCreateFolder');
				}
				else{				
					makepopupnotresize('auto','auto',html,'LoadEditFolder');
				}
				$("#isoman_folder_name").focus();
			}
		});
		return false;
	});
	$("#isoman_folder_name").live("keyup",function(e){
		if(e.which == 13) {
			$(this).closest(".frmPop").find(".isoman-update-folder").click();
			return false;
		}
	});
	$(".isoman-update-folder").live('click',function(){
		var $_this = $(this);
		if($("#isoman_folder_name").val()==""){
			return false;
		}
		var adata = {
			"for_id":$_this.attr("isoman_for_id"),
			"abs_url":$_this.attr("isoman_abs_url"),
			"isoman_dir":$_this.attr("isoman_dir"),
			"isoman_url":$_this.attr("isoman_url"),
			"folder_name":$("#isoman_folder_name").val(),
			"isoman_dir_root":$_this.attr("isoman_dir_root"),
			"isoman_root_name":$_this.attr("isoman_root_name")
		};
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?act=isoman_update_one_folder',
			data: adata,
			dataType: "html",
			success: function(html){ 
				$("#isoman-reload-"+$_this.attr("isoman_for_id")).click();
				$_this.closest(".frmPop").find(".close_pop").click();
			}
		});
		
		return false;
	});
	
	$(".isoman-menu-cut,.isoman-menu-copy").live('click',function(){
		var $_this = $(this);
		var $action = '';
		if($_this.hasClass("isoman-menu-cut")){
			$action = 'cut';
		}
		if($_this.hasClass("isoman-menu-copy")){
			$action = 'copy';
		}
		$.cookie("isoman-action",$action);
		$.cookie("isoman-action-dir",$_this.closest(".frmPop").find(".isoman-checked").attr("isoman_dir"));
		$.cookie("isoman-action-type",$_this.closest(".frmPop").find(".isoman-checked").attr("isoman_type"));
		$(".isoman-menu-paste").show();
		$("#isoman-action").css("left","277.78125px");
	});
	$(".isoman-menu-paste").live('click',function(){
		$paste_isoman_action = $.cookie("isoman-action");
		$paste_isoman_dir = $.cookie("isoman-action-dir");
		$isoman_paste_type = $.cookie("isoman-action-type");
		
		if($paste_isoman_action==''||$paste_isoman_dir=='') return false;
		
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		
		
		var adata = {
			"isoman_dir":$("#isoman-current-dir-"+$isoman_for_id).val(),
			"paste_isoman_dir":$paste_isoman_dir,
			"isoman_paste_type":$isoman_paste_type,
			"isoman_paste_action":$paste_isoman_action
		};
		
		$("#isoman-main-container-body").html('<div class="isoman-throbber isoman-throbber-inline"></div>');
		
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?act=isoman_paste',
			data: adata,
			dataType: "html",
			success: function(html){ 
				$.cookie("isoman-action",'');
				$.cookie("isoman-action-dir",''); 
				$("#isoman-reload-image").click();
			}
		});
	});
	$("#isoman-action").live('click',function(){
		var $_this = $(this);
		$("#isoman-action-menu").show();
		return false;
	});
	$("#isoman-sort").live('click',function(){
		var $_this = $(this);
		$("#isoman-sort-menu").show();
		return false;
	});
	$(".isoman-search").live("keyup",function(e) {
		if(e.which == 13) {
			$(this).closest(".frmPop").find(".isoman-i-refresh").click();
			return false;
		}
	});
	$(".isoman-i-refresh,.isoman-search-action").live('click',function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		isoman_load_folder($isoman_for_id,$("#isoman-current-typelist-"+$isoman_for_id).val(),$("#isoman-current-dir-"+$isoman_for_id).val(),$("#isoman-current-url-"+$isoman_for_id).val());
		return false;
	});
	$(".isoman-upload-file").live('click',function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		$("#isoman-upload-file-"+$isoman_for_id).click();
		return false;
	});
	$(".isoman-upload-file-input").live('change',function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		var isoman_dir = $("#isoman-current-dir-"+$isoman_for_id).val();
		makepopupnotresize('auto','auto','<a class="close_pop"></a><p style="margin-top:-26px">Uploading... Please wait...</p>','UploadingDialog');
		$_this.closest("form").ajaxSubmit({
			type:'POST', 
			url:path_ajax_script+"/index.php?act=isoman_upload_file",
			success:function(html){
				console.log(html);
				$("#isoman-reload-"+$isoman_for_id).attr("cval",html).click();
				if(html.replace(' ','')=='_NOT_VALID'){
					alert("This directory is not allow to upload!");
				}
				$("#UploadingDialog").find(".close_pop").click();
			}
		});
	});
	
	$(".isoman-menu-remove").live('click',function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		$(".isoman-menu").hide();
		
		/*if(confirm("Are you sure to delete?")){}*/
		
		var adata = {
			"isoman_for_id":$isoman_for_id,
			"isoman_dir":$_this.closest(".frmPop").find(".isoman-checked").attr("isoman_dir")
		};
		$("#isoman-main-container-body").html('<div class="isoman-throbber isoman-throbber-inline"></div>');
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?act=isoman_delete_dir',
			data: adata,
			dataType: "html",
			success: function(html){ 
				$_this.closest(".frmPop").find(".isoman-i-refresh").click();
				if(html.replace(' ','')=='_NOT_VALID'){
					alert("Can not delete!");
				}
				if(html.replace(' ','')=='_NOT_VALID_WRITE'){
					alert("Can not delete!!");
				}
				if(html.replace(' ','')=='_NOT_EMPTY'){
					alert("Can not delete folder have some files!");
				}
			}
		});
		
		return false;
	});
	$(".isoman-switch").live('click',function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		$("#isoman-current-typelist-"+$isoman_for_id).val($_this.attr("typelist"));
		$(".isoman-switch").removeClass("isoman-active");
		$_this.addClass("isoman-active");
		$_form.find(".isoman-i-refresh").click();
		return false;
	});
	
	$(".isoman-i-folder,.isoman-open-folder").live('click',function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		
		isoman_load_folder($isoman_for_id,$("#isoman-current-typelist-"+$isoman_for_id).val(),$_this.attr("isoman_dir"),$_this.attr("isoman_url"));
		
		return false;
	});
	$(".isoman-i-checkbox").live('click',function(){
		var $_form = $(this).closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		var $isoman_multiple = $_form.find(".isoman-current-multiple-"+$isoman_for_id).val();
		
		if($("#isoman-current-typelist-"+$isoman_for_id).val()==1){
			$(this).closest(".isoman-grid-row").click();
		}else{
			var $_this = $(this).closest(".isoman-image");
			if($_this.hasClass("isoman-checked")){
				if(!$isoman_multiple){
					$(".isoman-image").removeClass("isoman-checked");
					$_this.removeClass("isoman-checked");
					$("#isoman-action").hide();
					$("#isoman-insert-"+$isoman_for_id).attr("isoman_url","").hide();
				}else{
					$_this.removeClass("isoman-checked");
				}
			}else{
				if(!$isoman_multiple || $isoman_multiple==undefined){
					$(".isoman-image").removeClass("isoman-checked");
					$_this.addClass("isoman-checked");
					$("#isoman-action").show();
					if($_this.attr("isoman_url")!=""){
						$("#isoman-insert-"+$isoman_for_id).attr("isoman_url",$_this.attr("isoman_url")).show();
						$("#isoman-menu-view-"+$isoman_for_id).removeClass("isoman-disabled");
					}else{
						$("#isoman-insert-"+$isoman_for_id).attr("isoman_url","").hide();
						$("#isoman-menu-view-"+$isoman_for_id).addClass("isoman-disabled");
					}
				}else{
					$_this.addClass("isoman-checked");
					$("#isoman-action").show();
					if($_this.attr("isoman_url")!=""){
						$("#isoman-insert-"+$isoman_for_id).show();
						$("#isoman-menu-view-"+$isoman_for_id).removeClass("isoman-disabled");
					}else{
						$("#isoman-insert-"+$isoman_for_id).attr("isoman_url","").hide();
						$("#isoman-menu-view-"+$isoman_for_id).addClass("isoman-disabled");
					}
				}
			}
		}
		//$("#isoman-action-menu").hide();
		return false;
	});
	$(".isoman-grid-row").live('click',function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		
		if($_this.hasClass("isoman-checked")){
			$(".isoman-grid-row").removeClass("isoman-checked");
			$_this.removeClass("isoman-checked");
			$("#isoman-action").hide();
			$("#isoman-insert-"+$isoman_for_id).attr("isoman_url","").hide();
		}else{
			$(".isoman-grid-row").removeClass("isoman-checked");
			$_this.addClass("isoman-checked");
			$("#isoman-action").show();
			if($_this.attr("isoman_url")!=""){
				$("#isoman-insert-"+$isoman_for_id).attr("isoman_url",$_this.attr("isoman_url")).show();
				$("#isoman-menu-view-"+$isoman_for_id).removeClass("isoman-disabled");
			}
			else{
				$("#isoman-insert-"+$isoman_for_id).attr("isoman_url","").hide();
				$("#isoman-menu-view-"+$isoman_for_id).addClass("isoman-disabled");
			}
		}
		$("#isoman-action-menu").hide();
		return false;
	});
	$(".isoman-go-back").live('click',function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		isoman_load_folder($isoman_for_id,$("#isoman-current-typelist-"+$isoman_for_id).val(),$_this.attr("isoman_dir"),$_this.attr("isoman_url"));
		return false;
	});
	$(".isoman-image,.isoman-image-list,.isoman-insert").live('click',function(){
		var $_this = $(this);
		var isparent = $_this.attr("data-isoman-isparent");
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		var isInIframe=$("#isoman-current-isInIframe-"+$isoman_for_id).val();		
		var $isoman_multiple = $_form.find(".isoman-current-multiple-"+$isoman_for_id).val();
		
		if(isparent=='parent' && !$_this.hasClass("isoman-insert")){
			isoman_load_folder($isoman_for_id,$("#isoman-current-typelist-"+$isoman_for_id).val(),$_this.attr("isoman_dir"),$_this.attr("isoman_url"));
		}
		else{
			var $_img = $_this.attr("isoman_url").replace($("#isoman-current-domain-"+$isoman_for_id).val(),'');
			$_img = $_img.replace('http:///','/');
			$_img = $_img.replace('https:///','/');
			if(isInIframe=="1"){
				window.opener.$("#src").val($_img).focus().trigger("change");
			}else{
				if(!$isoman_multiple || $isoman_multiple==undefined){
					$("#"+$isoman_for_id).val($_img);
					$("#isoman_url_"+$isoman_for_id).val($_img);
					$("#isoman_hidden_"+$isoman_for_id).val($_img);
					$("#isoman_show_"+$isoman_for_id).attr("src",$_img);	
				}else{
					isoman_callback($isoman_for_id);
				}		
			}
			$_this.closest(".frmPop").find(".close_pop").click();
		}
		return false;
	});
	$(".isoman-open-path").live("click",function(){
		var $_this = $(this);
		var $_form = $_this.closest(".frmPop");
		var $isoman_for_id = $_form.find(".isoman-current-for_id").val();
		isoman_load_folder($isoman_for_id,$("#isoman-current-typelist-"+$isoman_for_id).val(),$_this.attr("isoman-dir"),$_this.attr("isoman-url"));
		return false;
	});
});
function isoman_load_folder(isoman_for_id,isoman_typelist,isoman_dir,isoman_url,isoman_multiple){
	var isoman_abs_url = $("#isoman-current-abs_url-"+isoman_for_id).val(); 
	$("#isoman-current-dir-"+isoman_for_id).val(isoman_dir);
	$("#isoman-current-url-"+isoman_for_id).val(isoman_url);
	$("#isoman-current-typelist-"+isoman_for_id).val(isoman_typelist);
	$("#isoman-main-container-body").html('<div class="isoman-throbber isoman-throbber-inline"></div>');
	if(isoman_typelist==0){
		$("#isoman-main-container-body").removeClass("isoman-abs-layout").addClass("isoman-flow-layout").css("top","32px");
	}
	else{
		$("#isoman-main-container-body").removeClass("isoman-flow-layout").addClass("isoman-abs-layout").css("top","0");
	}
	var cval = $("#isoman-reload-"+isoman_for_id).attr("cval");
	var adata = {
		"isoman_for_id":isoman_for_id,
		"isoman_dir":isoman_dir,
		"isoman_url":isoman_url,
		"isoman_abs_url":isoman_abs_url,
		"isoman_typelist":isoman_typelist,
		"isoman_search":$("#isoman-search-"+isoman_for_id).val(),
		"isoman_multiple" : isoman_multiple,
		"cval":cval
	};
	$.cookie("isoman_last_dir",isoman_dir);
	$.cookie("isoman_last_url",isoman_url);
	$.ajax({
		type: "POST",
		url:path_ajax_script+'/index.php?act=isoman_load_open_folder',
		data: adata,
		dataType: "html",
		success: function(html){ 
			var htm = html.split("|||");
			$("#isoman-main-container-body").html(htm[0]);
			$("#isoman-path").html(htm[1]);
			$(".isoman-image").each(function(){
				if($(this).attr("isoman_dir")==cval){
					$(this).find(".isoman-i-checkbox").click();
				}
			});
			console.log(cval);
		}
	});
	if($.cookie("isoman-action")!=''&&$.cookie("isoman-action-dir")!=''){
		$(".isoman-menu-paste").show();
		$("#isoman-action").css("left","277.78125px");
	}
	else{
		$(".isoman-menu-paste").hide();
		$("#isoman-action").css("left","196.078125px");
	}
	if(!$(".isoman-checked").length){
		$("#isoman-action").hide();
	}
}
function isoman_selected_files(){
	var $str = '';
	$(".isoman-image").each(function(idx){
		var $_this = $(this);
		if($_this.hasClass('isoman-checked')){
			$str += $_this.attr('isoman_url'); 
			$str += (idx==0)?'':'|';
		}
	});
	return $str;
}
function getmaxzindex(){
	var maxindex = 0;
	$('div').each(function(){
		var zindex = parseInt($(this).css('z-index'));
		if(zindex>maxindex) maxindex=zindex;
	});
	return maxindex;
}
$('.close_pop').live('click',function(){
	if($(this).closest('.frmPop').find(".isInIframe").val()==1){
		window.close();
	}
	else{
		var id = $(this).closest('.frmPop').attr('id');
		$('#'+id).remove();
		$('#isoblanketpop_'+id).remove();
	}
	return false;
});
function makepopupnotresize(width,height,content,name){
	var isInIframe = $(".isInIframe").val();
	if(isInIframe==1){
		if($('#'+name).length > 0){
		}else{
			$('<div id="isoblanketpop_'+name+'">').css({
				 position: 'fixed',
				 top: 0, 
				 left: 0,
				 height: $(document).height(), 
				 width: '100%',
				 opacity: 0.3, 

				 backgroundColor: 'black',
			  }).appendTo(document.body).addClass("stacked");
			$('<div id="'+name+'" class="frmPop">').appendTo(document.body).html(content);
			$('#'+name)
			.css('position','fixed')
			.css('width',width)
			.css('height',height)
			.css("left","20px")
			.css("top","20px")
			.addClass("stacked")
			.show().find('.required:first').focus();
		}
		$('#'+name).find('input').live('keydown',function(e){
			if(e.keyCode==13){
				$(this).closest('.frmPop').find('.submitClick').click();
				return false;
			}
			if(e.keyCode==27){
				$(this).closest('.frmPop').find('.close_pop').click(); 
				return false;
			}
		});
	}
	else{
		if($('#'+name).length > 0){
		}else{
			$('<div id="isoblanketpop_'+name+'">').css({
				 position: 'fixed',
				 top: 0, 
				 left: 0,
				 height: $(document).height(), 
				 width: '100%',
				 opacity: 0.3, 
				 backgroundColor: 'black',
			  }).appendTo(document.body).addClass("stacked");
			$('<div id="'+name+'" class="frmPop">').appendTo(document.body).html(content);
			$('#'+name)
			.css('position','fixed')
			.css('width',width)
			.css('height',height)
			.css("left",($(window).width()-$('#'+name).width())/2 + "px")
			.css("top",($(window).height()-$('#'+name).height())/2-20 + "px")
			.addClass("stacked")
			.show().find('.required:first').focus();
		}
		$('#'+name).find('input').live('keydown',function(e){
			if(e.keyCode==13){
				$(this).closest('.frmPop').find('.submitClick').click();
				return false;
			}
			if(e.keyCode==27){
				$(this).closest('.frmPop').find('.close_pop').click(); 
				return false;
			}
		});
		$('#'+name).draggable({containment:"body",cancel:".form",handle:'.headPop'}).find('.headPop').css('cursor','move');
	}
}