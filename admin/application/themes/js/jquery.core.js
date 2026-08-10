(function($) {
    $.fn.extend({
        isodatepicker: function() {
            return this.each(function() {
                $(this).datepicker({
                    'minDate': new Date(),
                    'dateFormat': "dd-mm-yy",
                    changeMonth: true,
                    changeYear: true
                });
            });
        }
    });
})(jQuery);
(function($) {
    $.fn.extend({
        isopriceformat: function() {
            return this.each(function() {
                $(this).priceFormat({
                    centsLimit: ''
                });
            });
        }
    });
})(jQuery);
$().ready(function(){
	$(window).load(function(){
		$('#ajax_loading').fadeOut(1600);
	});
	$(".price-In").priceFormat({
		thousandsSeparator: '.',
		centsLimit: 0
	});
	if($('.input-bind__couter').length){
		$('.input-bind__couter').each(function(){
			$(this).trigger('keyup');
		});
	}
	if($('.textarea_intro_editor').length > 0){
		$('.textarea_intro_editor').each(function(){
			var $_this = $(this);
			var $editorID = $_this.attr('id');
			$('#'+$editorID).isoTextArea();
		});
	}
	// System event click global.
	$(document).on('click', '#searchbtn, #searchBtn', function(ev){
		ev.preventDefault();
		$('#forums').submit();
	});
	$(document).on('click', '.SiteClickPublic', function(ev){
		var $_this = $(this);
		var $_rel = $_this.attr('rel');
		var adata = {};
		adata['clsTable'] = $_this.attr('clsTable');;
		adata['pkey'] = $_this.attr('pkey');
		adata['pvalTable'] = $_this.attr('sourse_id');
		adata['toField'] = $_this.attr('toField') != undefined ? $_this.attr('toField') : 'is_online';
		adata['val'] = parseInt($_rel)==0?1:0;
		adata['allowDuplicate'] = 1;
		
		$_this.find('i.fa').attr('class','fa fa-circle-o-notch fa-spin spin');
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=home&act=saveField",
			data: adata,
			dataType: "html",
			success: function(html){
				$_this.find('i.fa').attr('class','fa');
				if(parseInt($_rel)==1){
					$_this.find('i.fa').addClass('fa-minus-circle').addClass('red');
					$_this.attr('rel',0);
				}else{
					$_this.find('i.fa').addClass('fa-check-circle').addClass('green');
					$_this.attr('rel',1);
				}
			}
		});
		return false;
	});
	$(document).on('click', '.confirm_delete', function(ev){ 
		var $_this = $(this);
		if(confirm(confim_delete)){
			window.location.href = $_this.attr('href');
		}
		return false;
	});
	$(document).on('click', '.deleteItemImage', function(ev){ 
		if(confirm(confirm_delete)){
			var _this = $(this);
			var adata = {
				'pvalTable' : _this.attr('pvalTable'),
				'clsTable'	: _this.attr('clsTable')
			};
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url:path_ajax_script+'/index.php?mod=home&act=ajDeleteItemImage',
				data: adata,
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					_this.closest('.image').find('img').attr("src","");
					_this.closest('.image').find('input[name=isoman_url_image]').val("");
					_this.remove();
				}
			});
		}
		return false;
	});
	$(document).on('click', '.btn-delete-all', function (ev) {
		var $_this = $(this);
		var $listID = getCheckBoxValueByClass('chkitem');
		var $clsTable = $_this.attr('clsTable');
		if ($listID == '') {
			alertify.error(confirm_delete);
			return false;
		} else {
			if (confirm(confirm_delete)) {
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url: path_ajax_script + '/?mod=home&act=ajDeleteMultiItem',
					data: {"listID": $listID.join('|'),"clsTable": $clsTable},
					dataType: "html",
					success: function (html) {
						window.location.reload();
					}
				});
			}
		}
		return false;
	});
	$(document).on('click', '.close_pop', function (ev) {
		var id = $(this).closest('.frmPop').attr('id');
		$('#'+id).remove();
		$('#isoblanketpop_'+id).remove();
		return false;
	});
	$(document).on('click', '.close_Div', function (ev) {
		var $_this = $(this);
		$_this.closest('.autosugget').stop(false, true).slideUp();
		return false;
	});
	$('a.scroll_top').click(function(){
		if($('body').scrollTop() > 0)
			$('body').animate({scrollTop: 0}, 500, 'easeOutBounce');
		return false; 
	});
	if(mod=='home' && act=='default' && $( ".homecolumn" ).length > 0){
		$( ".homecolumn" ).sortable({ handle : '.widget-header',connectWith: ['.homecolumn'], stop: function() { saveHomeWidgets(); }});
		$( ".homewidget" ).find( ".widget-header" ).prepend( "<span class='ui-icon ui-icon-minusthick'></span>");
		$( ".widget-header .ui-icon" ).click(function() {
			$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
			$( this ).parents( ".homewidget:first" ).find( ".widget-content" ).toggle();
		});
	}
	$(document).on('change', '.gotopage', function (ev) {
		var $_this = $(this);
		window.location.href = $_this.val().toString();
	});
	// End system click global
	
	// Sytem message notification
	if($('#message').length > 0 ){
		var $ok = true;
		if($ok){
			var $message_W = $('#message').outerWidth(false);
			$('#message').animate({'right':10},200).fadeIn().delay(5000).animate({'right':-$message_W},500);
			$ok = false;
		}
	}
	// System tab click
	if($("#clienttabs").length>0){
		makeClientTab();
	}
	if($("#isotabs").length>0){
		makeSystemTab();
	}
	// System send feedback for admin.
	$(document).on('click', '#button_send_feedback', function (ev) {
		var $_this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script + '/?mod=home&act=ajOpenFeedback',
			data: {'tp':'F'},
			dataType: "html",
			success: function (html) {
				vietiso_loading(0);
				makepopup(600,'',html,'feedbackPop');
			}
		});
		return false;
	});	
	$(document).on('click', '#send_feedback', function (ev) {
		var $_this = $(this);
		var $message_feedback = $('#message_feedback');
		if($message_feedback.val()==''){
			$message_feedback.focus();
			alertify.error(field_is_required);
			return false;
		}
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script + '/?mod=home&act=ajOpenFeedback',
			data: {'tp':'S','type': $('#type').val(),'REQUEST_URI' : REQUEST_URI,'message': $message_feedback.val()},
			dataType: "html",
			success: function (html) {
				vietiso_loading(0);
				if(html.indexOf('_SUCCESS') >=0){
					alertify.success('Success !');
					$('#feedbackPop .close_pop').trigger('click');
				}else{
					alertify.success('Error !');
				}
				console.log(html);
			}
		});
		return false;
	});
	// System notes for personal.
	$(document).on('click', '.ajManageSystemNote', function (ev) {
		var $_this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script + '/?mod=home&act=ajOpenNote',
			data: {'tp':'F'},
			dataType: "html",
			success: function (html) {
				vietiso_loading(0);
				makepopup(600,'',html,'NotePop');
				setViewTextAreaByClass('textarea');
			}
		});
		return false;
	});
	// System checkbox list.
	$('#check_all').live('change',function(){
		var _this=$(this);
		var checked=_this.attr('checked');
		if(checked=='checked' || checked){
			$('input[class=chkitem]').attr('checked',true);
			setList();
		}else{
			$('input[class=chkitem]').removeAttr('checked');
			setList();
		}
	});
	$('input[class=chkitem]').live('change',function(){
		setList();
	});
	
	$("#all_check").live('click',function(){
		var rel = $(this).attr('rel');
		var chk = $(this).is(":checked")?1:0;
		$('.'+rel).each(function(){
			if(chk) {
				$(this).attr('checked','checked');
			} else {
				$(this).removeAttr('checked');
			}
		});
	});
});
function saveHomeWidgets(){
	console.log('Moved !');
}
function setSelectOpen(elem) {
    if (document.createEvent) {
        var e = document.createEvent("MouseEvents");
        e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        elem[0].dispatchEvent(e);
    } else if (element.fireEvent) {
        elem[0].fireEvent("onmousedown");
    }
}
function getCheckBoxValueByClass(classname){
	var names = [];
	$('.'+classname+':checked').each(function() { 
		names.push(this.value);
	});
	return names;
}
function makeGlobalTab(tabid){
	$('#'+tabid+'_ul li').each(function(tbs){
		$(this).attr('id','tabs_'+tabid+'_'+tbs)
		.addClass('tabs_child')
		.addClass('tabs_child_'+tabid)
		.find('a').attr('href','javascript:void();');
	});
	$('#'+tabid+'_ul li:first').addClass('tabselected');
	$('.tabboxchild_'+tabid).each(function(tbs){
		$(this).attr('id','showtabs_'+tabid+'_'+tbs);
	});
	$('#'+tabid+'_ul li.tabs_child_'+tabid).live('click',function(){
		var elid = $(this).attr("id");
		$('#'+tabid+'_ul li.tabs_child_'+tabid).removeClass("tabselected");
		$(this).addClass("tabselected");
		$('.tabboxchild_'+tabid).hide();
		$("#show"+elid).show();
		return false;
	});
}
function makeSystemTab(){
	$('#isotabs li').each(function(tbs){
		$(this).attr('id','isotab'+tbs).addClass('tab').find('a').attr('data','#isotabs'+tbs);
	});
	$('.isotabbox').each(function(tbs){
		$(this).attr('id','isotab'+tbs+'box');
		$(this).attr('data',tbs);
	});
	$(".isotabbox").css("display","none");
	$(document).on('click', '#isotabs .tab', function(ev){
		var tabid = $(this).attr("id");
		$("#isotabs .tab").removeClass("tabselected");
		if($("#"+tabid+"box").is(':visible')){
			$("#"+tabid+"box").hide();
		}else{
			$(".isotabbox").hide();
			$("#"+tabid+"box").show();
			$("#isotabs #"+tabid).addClass("tabselected");
		}
		return false;
	});
	return true;
}
function makeClientTab(){
	if(!$("#clienttabs").hasClass('disabled')){
		$('#clienttabs li').each(function(tbs){
			$(this).attr('id','tab'+tbs).addClass('tab').find('a').attr('data','#isotab'+tbs);
		});
		$('.tabbox').each(function(tbs){
			$(this).attr('id','tab'+tbs+'box');
			$(this).attr('data',tbs);
		});
		$(".tabbox").css("display","none");
		var selectedTab;
		$("#clienttabs .tab").live('click',function(){
			if($(this).hasClass('disabled')){return false;}
			if($(this).find('a').attr('isTab')!='0'){
				var elid = $(this).attr("id");
				$(".tab").removeClass("tabselected");
				$("#"+elid).addClass("tabselected");
				if (elid != selectedTab) {
					$(".tabbox").hide();
					$("#"+elid+"box").show();
					selectedTab = elid;
				}
				if($(this).find('a').attr('submit')=='_NOT'){
					$('.submit-buttons').hide();
				}else{
					$('.submit-buttons').show();
				}
				var hs = $(this).find('a').attr('data');
				setTimeout(function(){window.location.hash = hs;},200);
			}
			return false;
		});
		selectedTab = location.hash.substring(1)!=''?location.hash.substring(4):'tab0';
		if($("#"+selectedTab).length==0) selectedTab = 'tab0';
		$("#"+selectedTab).addClass("tabselected");
		$("#"+selectedTab+"box").css("display","");
		if($('#'+selectedTab).find('a').attr('submit')=='_NOT'){
			$('.submit-buttons').hide();
		}else{
			$('.submit-buttons').show();
		}
		if(location.hash.indexOf('iso')!=-1){
			setTimeout(function(){
				window.location.hash = 'iso'+selectedTab;
			},200);
		}
	}
}
function getmaxzindex(){
	var maxindex = 0;
	$('div').each(function(){
		var zindex = parseInt($(this).css('z-index'));
		if(zindex>maxindex) maxindex=zindex;
	});
	return maxindex;
}
function makepopup(width,height,content,name){
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
		.addClass("transition")
		.addClass("fadeInUp")
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
	$('#'+name).draggable({
		containment:"body",
		cancel:".form",
		handle:'.headPop'
	});
	$('#'+name).resizable();
}
function makepopupnotresize(width,height,content,name){
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
		.addClass("transition")
		.addClass("zoomOut")
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
	$('#'+name).draggable({
		containment:"body",
		cancel:".form",
		handle:'.headPop'
	});
}
function reinstance_makepopup(name){
	$('#'+name)
		.css('position','fixed')
		.css('width',width)
		.css('height',height)
		.css("left",($(window).width()-$('#'+name).width())/2 + "px")
		.css("top",($(window).height()-$('#'+name).height())/2-20 + "px")
		.addClass("stacked");
}
function vietiso_loading($show){
	if($show==1){ $('#ajax_loading').fadeIn(400);}
	if($show==0){ $('#ajax_loading').fadeOut(1600);}
}
function checkVaidEmail(email){
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/; 
	return regex.test(email);
}
function checkVaidUrl(url){
	var regex = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
	return regex.test(url);
}
function getStats(id) {
    var body = tinymce.get(id).getBody(), text = tinymce.trim(body.innerText || body.textContent);
    return {
        chars: text.length,
        words: text.split(/[\w\u2019\'-]+/).length
    };
}
function setCounter($_id,$max,$infoShow){
	var optionsCounter = {
		'maxCharacterSize': $max,
		'displayFormat': ''
	};
	$_id.textareaCount(optionsCounter, function(data){
		var result = data.input + '/' + data.max;
			$infoShow.html(result);
	});
}
function setViewTextAreaByClass($class){
	$("."+$class).each(function(){
		$(this).val($(this).val().replace(/<br\s?\/?>/g,"\n"));
	});
}
function _reload(){
	$('#searchbtn').trigger('click');
	return false;
}
function delete_cookie(name) {
	document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
}
function zCheckAll(oForm) {
	$('#'+oForm).find('input[type="checkbox"]').attr("checked","checked");
}
function zUncheckAll(oForm) {
	$('#'+oForm).find('input[type="checkbox"]').removeAttr("checked");
}
function showSeo(_this){
	$(_this).addClass('hidden');
	$('.seo-section').removeClass('hidden');
}
function titleCharsRemainingText(_this){
	var length_max = $(_this).data('length-max'),
		length_value = $(_this).val(),
		length_couter = length_value.length;
	$('.title-counter__charactor').text(length_couter);
}
function descriptionCharsRemainingText(_this){
	var length_max = $(_this).data('length-max'),
		length_value = $(_this).val(),
		length_couter = length_value.length;
	$('.description-counter__charactor').text(length_couter);
}
function setList(){
	var $check_all=1;
	var $list_id ="";
	var $check_count = 0;

	$('input[class=chkitem]').each(function(){
		var $_this=$(this);
		if($_this.attr('checked')=='checked' || $_this.attr('checked')){
			$list_id += $_this.val()+'|';
			$check_count += 1;
		}else{
			$check_all=0;	
		}
	});
	if($check_count > 0){  
		$('.btn-delete-all').show(); 
	} else{  
		$('.btn-delete-all').hide(); 
	}

	$('#list_selected_chkitem').val($list_id);
	if($check_all==0){
		$('#check_all').removeAttr('checked');
	} else{
		$('#check_all').attr('checked','checked');
	}
};