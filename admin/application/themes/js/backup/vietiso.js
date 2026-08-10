(function($){
 	$.fn.extend({
 		isoswitchvalue: function(options){
			var defaults = {
				_value: 1,
				_selector: ''
			};
			var options =  $.extend(defaults,options);
			/**/
            return this.each(function(){
				var $_this = $(this);
				var $_opts = options;
				$_this.addClass(($_opts._value==1?'on':'off'));
				$_this.append('<span class="on">PUBLIC</span>');
				$_this.append('<span class="off">PRIVATE</span>');
				$_this.append('<input type="hidden" name="'+$_opts._selector+'" class="'+$_opts._selector+'" value="'+($_opts._value == '' ? '0' : $_opts._value)+'">');
				$_this.click(function(){
					if($_this.hasClass('on')){
						$_this.removeClass('on').addClass('off');
						$('input[name='+$_opts._selector+']').val(0);
						$('#prv_status').show();$('#pub_status').hide();
					}else{
						$_this.removeClass('off').addClass('on');
						$('input[name='+$_opts._selector+']').val(1);
						$('#prv_status').hide();$('#pub_status').show();
					}
				});
			}); 	
		}
	});
})(jQuery);
(function($){
	$.fn.extend({
		isoswitchclose: function(options){
			var defaults = {
				_value: 1,
				_selector: ''
			};
			var options =  $.extend(defaults,options);
			/**/
			return this.each(function(){
				var $_this = $(this);
				var $_opts = options;
				$_this.addClass(($_opts._value==1?'on':'off'));
				$_this.append('<span class="on">ON</span>');
				$_this.append('<span class="off">OFF</span>');
				$_this.append('<input type="hidden" name="'+$_opts._selector+'" class="'+$_opts._selector+'" value="'+($_opts._value == '' ? '0' : $_opts._value)+'">');
				$_this.click(function(){
					if($_this.hasClass('on')){
						$_this.removeClass('on').addClass('off');
						$('input[name='+$_opts._selector+']').val(0);
						$('#prv_isclose').show();$('#pub_isclose').hide();
					}else{
						$_this.removeClass('off').addClass('on');
						$('input[name='+$_opts._selector+']').val(1);
						$('#prv_isclose').hide();$('#pub_isclose').show();
					}
				});
			});
		}
	});
})(jQuery);
(function($){
 	$.fn.extend({
 		isodatepicker: function() {
            return this.each(function(){
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
(function($){
 	$.fn.extend({
 		isopriceformat: function() {
            return this.each(function(){
				$(this).priceFormat({centsLimit: ''});
			}); 	
		}
	});
})(jQuery);
(function($){
 	$.fn.extend({
 		placeholder: function() {
            return this.each(function() {
                var $_val = $(this).attr('_text');
				if($_val==''){
					$(this).val($_val);
				}
                $(this).focus(function(){
                    if($(this).val() == $_val) {
                        $(this).val('');
                    } 
                });
                $(this).blur(function(){
                    if($(this).val() == '') {
                        $(this).val($_val);
                    }
                });
    		});
    	}
	});
})(jQuery);

$().ready(function(){
	$(".chosen-select").chosen({
		max_selected_options: 5,
		width:'100%'
	});
	$(document).on('click', '.editInlineImage', function(ev){
		var $g = $(this).attr("g");
		$("#"+$g+"_file").trigger('click');
		return false;
	});
	$(document).on('change', '.editInlineImageFile', function(ev){
		var $_this = $(this);
		var F = $_this[0].files;
		var S = F[0]['size'];
		if(S > 2000000){
			alert('Dung lượng ảnh quá lớn. Dung lượng tối đa <= 2MB');
		}else{
			var $g = $(this).attr("g"); 
			vietiso_loading(1);
			$(this).closest("form").ajaxSubmit({ 
				type:'POST', 
				url: path_ajax_script+"/index.php?mod=ajax&act=uploadImage",
				success: function(html){
					htm = html.replace(' ','');
					if(htm!='0'){	
						$("#"+$g+"_hidden").val(htm);	
						$("#"+$g+"_image").attr('src',htm);		
					} 
					else{
						alertify.error("Ảnh chưa được upload do lỗi định dạng!");
					}
				}
			});	
			vietiso_loading(0);
		}
		return false;
	});
	$('.price').priceFormat({
		prefix:'US $',
		centsLimit:''
	});
	/*$(window).load(function(){
		console.log('Hided !');
		$('#ajax_loading').hide();
	});*/
	$('textarea:visible').each(function(){
		var $_this = $(this);
		var $_val = $_this.val();
		if($_val!=''){
			$_val = $_val.replace(/<br\s?\/?>/g,"\n");
			$_this.val($_val);
		}
	});
	$('#selectFile').live('click',function(){
		$("#AddFileAttach").click();
	}); 
	$('#AddFileAttach').live('change',function(){
		$('.frmform').ajaxSubmit({ 
			type:'POST', 
			url: path_ajax_script+"/index.php?mod=ajax&act=uploadImage",
			success: function(html){
				htm = html.replace(' ','');
				if(htm!=''){	
					$('#img_src_hidden').val(htm);	
					$('#img_src').attr('src',htm);		
				} 
			} 
		});	
	});
	$('.lbl').each(function(){
		var $_this = $(this);
		if($_this.find('input[type="checkbox"]').attr('checked')=='checked'){
			$_this.addClass('lblchecked');
		}
	});
	$('.lbl').live('change',function(){
		var $_this = $(this);
		if($_this.find('input[type="checkbox"]').attr('checked')=='checked' || $_this.attr('checked')){
			$_this.addClass('lblchecked');
			$_this.removeClass('lblcheck');
		}else{
			$_this.addClass('lblcheck');
			$_this.removeClass('lblchecked');
		}
	});
	$('.selectbox input[type=checkbox]').change(function(){
		var $_this=$(this);
		if($_this.attr('checked')=='checked'){
			$_this.parent().addClass('checked');
		}else{
			$_this.parent().removeClass('checked');
		}
	});
	$('ul.single input[type=checkbox]').live('change',function(){
		var $_this=$(this);
		if($_this.attr('checked')=='checked' || $_this.attr('checked')){
			$('input[type=checkbox]').removeAttr('checked');
			$_this.attr('checked',true);
		}
	});
	// Iso Tabs
	if($("#clienttabs").length>0){
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
					/* ------- // -------- */
					if($(this).find('a').attr('submit')=='_NOT'){
						$('.submit-buttons').hide();
					}else{
						$('.submit-buttons').show();
					}
					/* ------- // -------- */
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
	$('a.scroll_top').click(function(){
		if($('body').scrollTop() > 0)
			$('body').animate({scrollTop: 0}, 500, 'easeOutBounce');
		return false; 
	});
	$('#searchbtn').click(function(){
		$('#forums').submit();
	});
	$('.confirm_delete').live('click',function(){
		var $_this = $(this);
		if(confirm(confim_delete)){
			window.location.href = $_this.attr('href');
		}
		return false;
	});
	// CheckBox
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
	$(".checkall_checkbox").live("click",function(){
		var $_this = $(this);
		var group = $_this.attr("group");
		if($_this.is(":checked")){
			$(".checkitem_checkbox[group='"+group+"']").attr("checked","checked");
		}else{
			$(".checkitem_checkbox[group='"+group+"']").removeAttr("checked");
		}
	});
	$(".checkitem_checkbox").live("click",function(e){
		var group = $(this).attr("group");
		var check_all=1;
		$(".checkitem_checkbox[group='"+group+"']").each(function(){
			var $_this = $(this);
			if($_this.attr('checked')=='checked' || $_this.attr('checked')){
							
			}else{
				check_all=0;	
			}
		});
		//console.log(check_all);
		if(check_all==0){
			$(".checkall_checkbox[group='"+group+"']").removeAttr("checked");	
		}else{
			$(".checkall_checkbox[group='"+group+"']").attr("checked","checked");
		}			
	});
	$("select[class='isoYearSlb']").live("change",function(){
		var $_this = $(this);
		var $group = $_this.attr("group");
		if($_this.val()!='0'){
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/?mod=ajax&act=ajLoadMonthFromYear",
				data:{'year':$_this.val()},
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					$("select[class='isoMonthSlb'][group='"+$group+"']").html(html).removeAttr("disabled");
				}
			}); 
		}else{
			$("select[class='isoMonthSlb'][group='"+$group+"']").attr("disabled","disabled");
			$("select[class='isoDaySlb'][group='"+$group+"']").attr("disabled","disabled");
		}
	});
	$("select[class='isoMonthSlb']").live("change",function(){
		var $_this = $(this);
		var $group = $_this.attr("group");
		if($_this.val()!='0'){
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/?mod=ajax&act=ajLoadDayFromMonth",
				data:{'month':$_this.val(),"year":$("select[class='isoYearSlb'][group='"+$group+"']").val()},
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					$("select[class='isoDaySlb'][group='"+$group+"']").html(html).removeAttr("disabled");
				}
			}); 
		}else{
			$("select[class='isoDaySlb'][group='"+$group+"']").attr("disabled","disabled");
		}
	});
	$('.deleteItemImage').live('click',function(){
		if(confirm("Bạn chắc chắn muốn xóa ảnh này?")){
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
					$("#isoman_show_image").attr("src","");
					$("#isoman_hidden_image").val("");
					_this.remove();
				}
			});
		}
		return false;
	});
	$(document).on('click', '.btn-delete-all', function(ev){ 
		var $_this = $(this);
		var $listID = getCheckBoxValueByClass('chkitem');
		var $clsTable = $_this.attr('clsTable');
		if($listID==''){   
			alertify.error('Bạn chưa chọn những menu cần xóa !');  
			return false;
		}
		else{   
			if(confirm(confirm_delete)){    
				vietiso_loading(1);
				$.ajax({     
					type: "POST",
					url: path_ajax_script+'/?mod=home&act=ajDeleteMultiItem',
					data: {
						"listID":$listID.join('|'),
						"clsTable":$clsTable
					},
					dataType: "html",
					success: function(html){
						window.location.reload();
					}    
				});
			}
			return false;  
		}  
		return false; 
	});
});
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
	if($("#isotabs").length>0){
		$('#isotabs li').each(function(tbs){
			$(this).attr('id','isotab'+tbs).addClass('tab').find('a').attr('data','#isotabs'+tbs);
		});
		$('.isotabbox').each(function(tbs){
			$(this).attr('id','isotab'+tbs+'box');
			$(this).attr('data',tbs);
		});
		$("#isotabs .tab:first").addClass('tabselected');
		$(".isotabbox:not(:first)").css("display","none");
		$("#isotabs .tab").live('click',function(){
			var tabid = $(this).attr("id");
			$("#isotabs .tab").removeClass("tabselected");
			$("#isotabs #"+tabid).addClass("tabselected");
			$(".isotabbox").hide();
			$("#"+tabid+"box").show();
			return false;
		});
	}
	return false;
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
	}
	else{  
		$('.btn-delete-all').hide(); 
	}

	$('#list_selected_chkitem').val($list_id);
	if($check_all==0){
		$('#check_all').removeAttr('checked');
	}
	else{
		$('#check_all').attr('checked','checked');
	}
};
function setListHotel(){
	var check_all=1;
	var list_selected_chkitem='';
	$('input[class=chkitem]').each(function(){
		var _this=$(this);
		if(_this.attr('checked')=='checked' || _this.attr('checked')){
			list_selected_chkitem+=_this.val()+'|';			
		}else{
			check_all=0;	
		}
	});
	$('#list_selected_hotel_chkitem').val(list_selected_chkitem);
	if(check_all==0){
		$('#check_all').removeAttr('checked');
	}else{
		$('#check_all').attr('checked','checked');
	}	
};
function vietiso_loading($show){
	if($show==1){ $('#ajax_loading').show();}
	if($show==0){ $('#ajax_loading').hide();}
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
function getCheckBoxValueByClass(classname){
	var names = [];
	$('.'+classname+':checked').each(function() { 
		names.push(this.value);
	});
	return names;
}
function _reload(){
	$('#searchbtn').trigger('click');
	return false;
}
function zCheckAll(oForm) {
	$('#'+oForm).find('input[type="checkbox"]').attr("checked","checked");
}
function zUncheckAll(oForm) {
	$('#'+oForm).find('input[type="checkbox"]').removeAttr("checked");
}