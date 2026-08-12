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

$Core = (function($, window, document, undefined) {	

	return {

		alert: {

			error: function(msg){

				alertify.error(msg);

			},

			success: function(msg){

				alertify.success(msg);

			},

			confirm: function(title, message, onOk) {

				var onClose = function(){

					if($('#modal').is(":visible")){

						$('#modal').modal("hide");

					}

				};

				modal('#modal-confirm', {'title': title, 'message': message});

				$("#modal-confirm-ok").unbind().on('click', onOk).one('click', onClose);

			}

		},

		util: {

			stopEventHandler: function(e){

                e.preventDefault();

                e.stopImmediatePropagation();

            },

			toggleIndicatior: function(type){

				if(type==1){ $('#ajax_loading').fadeIn();}

				if(type==0){ $('#ajax_loading').fadeOut();}

			},

			delay: function(callback, ms) {

			  var timer = 0;

			  return function() {

				var context = this, 

					args = arguments;

				clearTimeout(timer);

				timer = setTimeout(() => {

					callback.apply(context, args); 

				}, ms || 0);

			  }

			},

			convertTextBr: function(a) {

                return a.replace(/<br\s?\/?>/g, "\n");

            },

            nl2br: function(a) {

                var breakTag = '<br>';

                var b = a.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');

                breakTag = '<br />';

                var c = b.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');

                return c;

            },

            br2nl: function(a) {

                var b = a.replace(/<br\s?\/?>/g, "\n");

                return b.replace(/(<([^>]+)>)/ig, "");

            },

			popstate: function(name) {

				window.history.pushState(null, null, name);

				return false;

            },

			getmaxzindex: function(){

				var maxindex = 0;

				$('div').each(function(){

					var zindex = parseInt($(this).css('z-index'));

					if(zindex>maxindex) maxindex=zindex;

				});

				return maxindex;

			},

			getTextAreaContent: function(id) {

                if ($("#" + id).length)

                    return tinyMCE.get(id).getContent();

                else

                    return '';

            },

			getTinyMCEContent: function(id) {

                return tinymce.activeEditor.getContent();

            },

            setTextAreaContent: function(id, data) {

                tinyMCE.get(id).setContent(data);

                return false;

            },

			getCheckBoxAttrByClass: function(classname, attr){

				var names = [];

				$('.'+classname+':checked').each((_i, _elem) => { 

					names.push($(_elem).attr(attr));

				});

				return names;

			},

			getCheckBoxValueByClass: function(classname){

				var names = [];

				$('.'+classname+':checked').each((_i, _elem) => { 

					names.push($(_elem).val());

				});

				return names;

			},

            IsEmail: function(email) {

                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                return regex.test(email);

            },

            IsPhone: function(a) {

                var filter = /^[0-9-+]+$/;

                if (filter.test(a)) {

                    return 1;

                } else {

                    return 0;

                }

            },

			isEmpty: function(value) {

				if(!value || 0 === value.length){

					return true;

				}

				if(typeof(value) == 'number' || typeof(value) == 'boolean'){ 

					return false; 

				}

				if(typeof(value) == 'undefined' || value === '0' || value === null || value=='undefined'){

					return true; 

				}

				if(typeof(value.length) != 'undefined'){

					return value.length == 0;

				}

				var count = 0;

				for(var i in value){

					if(data.hasOwnProperty(i)){

						count ++;

					}

				}

				return count == 0;

			},

			isEmptyZero: function(value) {

				if(!value || 0 === value.length){

					return true;

				}

				if(typeof(value) == 'number' || typeof(value) == 'boolean'){ 

					return false; 

				}

				if(typeof(value) == 'undefined' || value === null || value=='undefined'){

					return true; 

				}

				if(typeof(value.length) != 'undefined'){

					return value.length == 0;

				}

				var count = 0;

				for(var i in value){

					if(data.hasOwnProperty(i)){

						count ++;

					}

				}

				return count == 0;

			},

            isInt: function(n) {

                return Number(n) === n && n % 1 === 0;

            },

            isFloat: function(n) {

                return Number(n) === n && n % 1 !== 0;

            },

            IsNaN: function(n) {

                Number.isNaN(Number(n));

            },

			parseNumber: function(num){

				return parseInt(num) < 10 ? '0'+parseInt(num) : num;

			},

			randomString: function(length){

				var text = "",

				possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

				for (var i = 0; i < length; i++){

					text += possible.charAt(Math.floor(Math.random() * possible.length));

				}

				return text;

			},

			resortitem: function(classname) {

                classname = classname.replace('.', '');

                $("." + classname).each(function(e) {

                    $(this).text(e + 1);

                });

            },

			getUniqid: () => {

				// timestamp base36 (10 ký tự) + random base36 (12 ký tự)

				const ts = Date.now().toString(36).padEnd(10, '0'); // luôn đủ 10

				const rand = Math.random().toString(36).substring(2).padEnd(12, '0'); // luôn đủ 12

				return (ts + rand).substring(0, 22);

				// return Date.now() + ((Math.random()*100000).toFixed());

			},

		},

		popup: {

			close: function(name) {

				var _id = name.attr("id");

				if ($("#isoblanketpop_" + _id).length) {

					$("#isoblanketpop_" + _id).remove();

				}

				/* delete all events */

				name.remove();

			},

			open: function (width,height,content,name,className){

				if($('#'+name).length > 0){

				}else{

					$('<div id="isoblanketpop_'+name+'">').css({

						 position: 'fixed',

						 top: 0, 

						 left: 0,

						 height: $_document.height(), 

						 width: '100%',

						 opacity: 0.3, 

						 backgroundColor: 'black',

						 zIndex : '3',

					}).appendTo(document.body).addClass("stacked d-none");

					var html = '<div id="'+name+'" class="modal animated bounceInDown in '+className+'" style="display:none" tabindex="-1" role="dialog" aria-hidden="false">'+content+'</div>';

					$(document.body).append(html);

					var $thisPop = $('#'+name);

					var $overflow = 'auto';

					if($(window).width()<768){

						width = '100vw';

						$overflow = 'auto';

					}

					$thisPop.css('position','fixed')

						.css('overflow',$overflow)

						.css('z-index', '4')

						.css("top",($(window).height()-$('#'+name).height())/2 + "px")

						.stop(false, true).fadeIn();

					if($thisPop.hasClass("right-0")) {

						$thisPop.css({"right":"0","left":"unset"})

					}else{

						$thisPop.css("left",($(window).width()-$('#'+name).width())/2 + "px")

					}

					

					$thisPop.find('input').on('keydown',function(e){

						var keyCode = e.keyCode || e.which;

						if(keyCode===13){

							$(this).closest('.modal-form').find('.submitClick').click();

							return false;

						}else if(keyCode===27){

							$(this).closest('.modal-form').find('.close_pop').click(); 

							return false;

						}

					});

				}

			},

			openfull: function(width,content,name,backdrop='static'){

				var backdrop = backdrop || 0,

					__www = $(window).width();

				if($('#'+name).length > 0){

					var _modal = $('#'+name);

					_modal.stop(false,true).modal('show');

				}else{

					$(document.body).append(content);

					var _modal = $('#'+name);

					if($(window).width()<768){

						width = 0;

					}

					_modal.find('.modal-dialog').css('width',__www-width);

					_modal.find('.modal-dialog').css('max-width',__www-width);

					_modal.modal('show');

				}

			},

		}	

	}

})(jQuery, window, document);

$().ready(function(){

	initialize();

    $_document.ajaxComplete(function() {

        initialize();

    });

	$_document.on('keydown','input[type=number],.numberonly', function(event) {

        /*Allow: backspace, delete, tab, escape, and enter*/

        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 188 || event.keyCode == 190 ||

            /*Allow: Ctrl+V*/

            (event.keyCode == 86 && event.ctrlKey === true) ||

            /*Allow: Ctrl+C*/

            (event.keyCode == 67 && event.ctrlKey === true) ||

            /*Allow: Ctrl+A*/

            (event.keyCode == 65 && event.ctrlKey === true) ||

            /* Allow: home, end, left, right*/

            (event.keyCode >= 35 && event.keyCode <= 39)) {

            /*let it happen, don't do anything*/

            return;

        } else {

            /*Ensure that it is a number and stop the keypress*/

            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {

                event.preventDefault();

            }

        }

    });

	$(window).load(function(){

		$('#ajax_loading').fadeOut(1600);

	});

	if($('.input-bind__counter').length){

		$('.input-bind__counter').each(function(){

			//$(this).trigger('keyup');

		});

	}

	$_document.on('click', '.SiteClickPublic', function(ev){

		var $_this = $(this),

			$_rel = $_this.attr('rel'),

			pkey = $_this.attr('pkey'),

			clsTable = $_this.attr('clsTable'),

			pvalTable = $_this.attr('sourse_id'),

			toField = $_this.hasAttr('toField') ? $_this.attr('toField') : 'is_online';

		var $_adata = {};

		$_adata['pkey'] = pkey;

		$_adata['toField'] = toField;

		$_adata['clsTable'] = clsTable;

		$_adata['pvalTable'] = pvalTable;

		$_adata['val'] = parseInt($_rel)==0?1:0;

		$_adata['allowDuplicate'] = 1;

		$_this.find('i.fa').attr('class','fa fa-circle-o-notch fa-spin spin');

		$.ajax({

			type: "POST",

			data: $_adata,

			dataType: "html",

			url: path_ajax_script+"/index.php?mod=home&act=saveField",

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

	$_document.on('click', '.close_pop', function(){

		var _thispop = $(this).closest(".modal-form");

		$Core.popup.close(_thispop);

		return false;

	});

	$_document.on('click', '.close:not(.inpopover), button[data-dismiss=modal]', function(){

		var _thispop = $(this).closest(".modal"),

			_id = _thispop.attr("id");

		if(_id !== 'modal'){

			$Core.popup.close(_thispop);

			return false;

		}

	});

	$_document.on('click', '.confirm_delete', function(ev){ 

		var $_this = $(this);

		if(confirm(confim_delete)){

			window.location.href = $_this.attr('href');

		}

		return false;

	});

	$_document.on('click', '.deleteItemImage', function(ev){ 

		if(confirm(confirm_delete)){

			var _this = $(this),

				pvalTable = _this.attr('pvalTable'),

				clsTable =  _this.attr('clsTable'), 

				$_adata = {'pvalTable' : pvalTable,'clsTable': clsTable};

			toggleIndicatior(1);

			$.post(path_ajax_script+'/index.php?mod=home&act=ajDeleteItemImage', $_adata, function(){

				toggleIndicatior(0);

				_this.closest('.image').find('img').attr("src","");

				_this.closest('.image').find('input[name=isoman_url_image]').val("");

				_this.remove();

			});

		}

		return false;

	});

	$_document.on('click', '.btn-delete-all', function (ev) {

		var $_this = $(this),

			clsTable = $_this.attr('clsTable'),

			list_id_selected = getCheckBoxValueByClass('chkitem');

		if($Core.util.isEmpty(list_id_selected)){

			$Core.alert.error('Bạn chưa chọn danh sách xóa !');

			return false;

		} else {

			$Core.alert.confirm("Xác nhận xóa", "Bạn có chắc chắn muốn xóa nội dung này?", () => {

				toggleIndicatior(1);

				var $_adata = {'clsTable':clsTable, 'list_id_selected':list_id_selected.join('|')}

				$.post(path_ajax_script+'/?mod=home&act=ajDeleteMultiItem', $_adata, function(html){

					toggleIndicatior(0);

					if(clsTable == "ProjectMeta") {

						$Core.docs.load_docs({});

					}else{

						window.location.reload(true);

					}

					

				});

			});

		}

		return false;

	});

	$_document.on('click', '.close_Div', function (ev) {

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

	$_document.on('change', '.gotopage', function (ev) {

		var $_this = $(this);

		window.location.href = $_this.val().toString();

	});

	$_document.on('click', '.gotoLink', function (ev) {

		var $_this = $(this);

		window.location.href = $_this.data('url').toString();

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

	$_document.on('click', '#button_send_feedback', function (ev) {

		var $_this = $(this);

		toggleIndicatior(1);

		$.ajax({

			type: "POST",

			url: path_ajax_script + '/?mod=home&act=ajOpenFeedback',

			data: {'tp':'F'},

			dataType: "html",

			success: function (html) {

				toggleIndicatior(0);

				makepopup(600,'',html,'feedbackPop');

			}

		});

		return false;

	});	

	$_document.on('click', '#send_feedback', function (ev) {

		var $_this = $(this);

		var $message_feedback = $('#message_feedback');

		if($message_feedback.val()==''){

			$message_feedback.focus();

			alertify.error(field_is_required);

			return false;

		}

		toggleIndicatior(1);

		$.ajax({

			type: "POST",

			url: path_ajax_script + '/?mod=home&act=ajOpenFeedback',

			data: {'tp':'S','type': $('#type').val(),'REQUEST_URI' : REQUEST_URI,'message': $message_feedback.val()},

			dataType: "html",

			success: function (html) {

				toggleIndicatior(0);

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

	$_document.on('click', '.ajManageSystemNote', function (ev) {

		var $_this = $(this);

		toggleIndicatior(1);

		$.ajax({

			type: "POST",

			url: path_ajax_script + '/?mod=home&act=ajOpenNote',

			data: {'tp':'F'},

			dataType: "html",

			success: function (html) {

				toggleIndicatior(0);

				makepopup(600,'',html,'NotePop');

				setViewTextAreaByClass('textarea');

			}

		});

		return false;

	});

	// System checkbox list.

	$_document.on('change','#check_all[type=checkbox]', function(){

		$('.chkitem[type=checkbox]').prop('checked', $(this).prop('checked'));

		setList();

	});

	$_document.on('change', '.chkitem', function(){

		setList();

	});

	$_document.on('click', '#'+'all_check', function(){

		var rel = $(this).attr('rel'),

			chk = $(this).is(":checked")?1:0;

		$('.'+rel).each(function(){

			if(chk) {

				$(this).attr('checked','checked');

			} else {

				$(this).removeAttr('checked');

			}

		});

	});

	$('input[name=config_value_title]').on('keyup', function(e){

		e.stopImmediatePropagation();

		var _this = $(this),

			clsTable = _this.attr('clsTable'),

			pvalTable = _this.attr('pvalTable'),

			char_lenth = _this.val().length;

		$('.title-counter__charactor').text(char_lenth);

		load_preview_search(clsTable, pvalTable);

	});

	$('textarea[name=config_value_intro]').on('keyup', $Core.util.delay(function(e){

		e.preventDefault();

		var _this = $(this),

			clsTable = _this.attr('clsTable'),

			pvalTable = _this.attr('pvalTable'),

			char_length = _this.val().length;

		$('.description-counter__charactor').text(char_length);

		load_preview_search(clsTable, pvalTable);

	}, 100));

});

!(function($) {

    $.fn.hasAttr = function(name) {

        return this.attr(name) !== undefined;

    };

})(jQuery);

!(function($) {

    $.fn.hasAttr = function(name) {

        return this.attr(name) !== undefined;

    };

})(jQuery);

!(function($) {

    $.fn.getAttr = function(name, def) {

		if(this.hasAttr(name))

			return this.attr(name);

		return def;

    };

})(jQuery);

function toggleIndicatior(status){

	$Core.util.toggleIndicatior(status);

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

	return $Core.util.getCheckBoxValueByClass(classname);

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

	$_document.on('click', '#isotabs .tab', function(ev){

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

	return $Core.util.getmaxzindex();

}

function makepopup(width,height,content,name){

	$Core.popup.open(width,height,content,name);

}

function makepopupnotresize(width,height,content,name){

	if($('#'+name).length > 0){

	}else{

		$('<div id="isoblanketpop_'+name+'">').css({

			 position: 'fixed',

			 top: 0, 

			 left: 0,

			 height: $_document.height(), 

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

function load_preview_search(clsTable, pvalTable){

	var config_link = $('input[name=config_link]').val(),

		config_value_title = $('input[name=config_value_title]').val(),

		config_value_intro = $('textarea[name=config_value_intro]').val(),

		$_adata = {

			'clsTable' : clsTable,

			'pvalTable' : pvalTable,

			'config_link' : config_link,

			'config_value_title' : config_value_title,

			'config_value_intro' : config_value_intro

		};

	$.post(path_ajax_script+'/index.php?mod=home&act=load_preview_search', $_adata, function(html){

		$('.holderPrevSeo').html(html);

	});

}

function setList(){

	var list_selected_chkitem="", number_checked = 0, check_all = 1;

	$('.chkitem[type=checkbox]').each(function(){

		if($(this).is(':checked')){

			number_checked++;

			list_selected_chkitem += $(this).val()+'|';

		} else {

			check_all = 0;

		}

	});

	$('#'+'check_all').prop('checked', check_all);

	$('#'+'list_selected_chkitem').val(list_selected_chkitem);

	if(number_checked > 0){

		$('.btn-delete-all').show(); 

	} else {

		$('.btn-delete-all').hide(); 

	}

};

function modal() {

    /* check if the modal not visible, show it */

    if(!$('#modal').is(":visible")) $('#modal').modal('show');

    /* update the modal-content with the rendered template */

    $('#modal .modal-content:last').html( render_template(arguments[0], arguments[1]) );

    /* initialize modal if the function defined (user logged in) */

}

/** render template */

function render_template(selector, options) {

    var template = $(selector).html();

    Mustache.parse(template);

    var rendered_template = Mustache.render(template, options);

    return html_entity_decode(rendered_template);

}

function html_entity_decode(str) {

	var ta = document.createElement("textarea");

	ta.innerHTML = str.replace(/</g,"&lt;").replace(/>/g,">");

	toReturn = ta.value;

	ta = null;

	return toReturn;

}

function initialize(){

	if($('.multiple_datepicker').length){

		$('.multiple_datepicker').multiDatesPicker({

			minDate: new Date()

		});

	}

	if($('.btn-group-toggle').length){

		$(".btn-group-toggle").each((_i, _elem) => {

			$(_elem).twbsToggleButtons();

		});

	}

	if($('[data-toggle="tooltip"]').length){

		$('[data-toggle="tooltip"]').tooltip({});

	}

	if($('.datepicker:not(.hasDatepicker)').length){

		$('.datepicker:not(.hasDatepicker)').datepicker(crm_datepicker_format);

	}

	if($('.isodatepicker:not(.hasDatepicker)').length){

		$('.isodatepicker:not(.hasDatepicker)').datepicker(crm_datepicker_format);

	}

	if($('.timepicker:not(.ui-timepicker-input)').length){

		$('.timepicker:not(.ui-timepicker-input)').datetimepicker($.extend(crm_datepicker_format, {

			controlType: 'select',

			oneLine: true,

			timeFormat: 'HH:mm TT'

		}));

	}

	if($('textarea.form-control:not(.striped)').length){

		$('textarea.form-control:not(.striped)').each(function(){

			var _this = $(this);

			_this.addClass('striped').val($Core.util.convertTextBr(_this.val()));

		})

	}

	if($(".price-In:not(.priceFormat)").length){

		$(".price-In:not(.priceFormat)").priceFormat({

			thousandsSeparator: '.',

			centsLimit: 0

		});

	}

	$("[data-toggle=ripple]").click(function(a) {

		var i = $(this);

		0 == i.find(".material-ink").length && i.prepend("<div class='material-ink'></div>");

		var t = i.find(".material-ink");

		if (t.removeClass("animate"), !t.height() && !t.width()) {

			var e = Math.max(i.outerWidth(), i.outerHeight());

			t.css({height: e,width: e})

		}

		var r = a.pageX - i.offset().left - t.width() / 2,

			h = a.pageY - i.offset().top - t.height() / 2,

			l = i.data("ripple-color");

		t.css({top: h + "px",left: r + "px",background: l}).addClass("animate")

	});

	/** Timepicker */

    if($('.timepicker:not(.ui-timepicker-input)').length){

        $('.timepicker:not(.ui-timepicker-input)')

			.addClass('ui-timepicker-input').timepicker({

			'step' : 15,

			'timeFormat': 'h:i A',

			'forceRoundTime':true

		});

    }

	 if($('.datepicker:not(.hasDatepicker)').length){

        $('.datepicker:not(.hasDatepicker)').datepicker({

			'dateFormat': 'dd/mm/yy',

			'minDate'	: new Date()

		});

    }

	/* End */

	if($('.textarea_intro_editor:not(.isoTextArea)').length > 0){

		$('.textarea_intro_editor:not(.isoTextArea)').each(function(){

			var editorId = $(this).attr('id');

			$('#'+editorId).addClass('isoTextArea').isoTextArea();

		});

	}

	if($('.isoTextArea:not(.textarea_intro_editor)').length > 0){

		$('.isoTextArea:not(.textarea_intro_editor)').each(function(){

			var editorId = $(this).attr('id');

			$('#'+editorId).addClass('textarea_intro_editor').isoTextArea();

		});

	}

	if($('select.iso-selectize:not(.selectized)').length){

        $('select.iso-selectize:not(.selectized)').addClass('selectized').selectize({

            create: false

        });

    }

	if($('select.iso-select2:not(.select2)').length){

        $('select.iso-select2:not(.select2)').addClass('select2').chosen({

			width:'100%',

			inherit_select_classes: false,

            parser_config : { copy_data_attributes : true } 

        });

    }

	if($('select.form-select2:not(.select2)').length){

		$('select.form-select2:not(.select2)').select2({

			// Some code

		});

	}

    if($(".iso-selectizeNotSearch:not('.selectized')").length){

        $('.iso-selectizeNotSearch:not(.selectized)').each(function(){

            var $_this = $(this),

                ajax_url = $_this.data('url'),

                optgroup = $_this.data('optgroup');

            var options = {};

            $self = $_this.addClass('selectized').selectize($.extend(options, {

                valueField: 'id',

                labelField: 'text',

                searchField: 'text',

                create: false,

                preload: true,

                load: function(query, callback) {

                    var self = $(this);

                    $.ajax({

                        url:ajax_url,

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

            }));

        });

    }

    if($('.iso-selectizeLiveSearch:not(.selectized)').length){

        $('.iso-selectizeLiveSearch:not(.selectized)').each(function(){

            var $_this = $(this),

                ajax_url = $(this).data('url');

            $_this.addClass('selectized').selectize({

                valueField: 'id',

                labelField: 'text',

                searchField: 'text',

                create: false,

                preload: true,

                load: function(query, callback) {

                    if (!query.length) return callback();

                    $.ajax({

                        url:ajax_url,

                        type: 'GET',

                        dataType:'json',

                        data: {'keysearch': query},

                        delay: 250,

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

        });

    }

	if($('.input_mask').length){

		$('.input_mask').each((_i,_elem) => {

			var mask = $(_elem).data('inputmask');

			if(!$Core.util.isEmpty(mask)){

				$(_elem).mask(mask);

			}

		});

	}

}

/** Delete Global */

function delete_globe(_this){

	var pkey = $(_this).attr('pkey'),

		pval_id = $(_this).attr('pval_id'),

		clsTable = $(_this).attr('clsTable'),

		return_url = $(_this).attr('return_url'),

		$_adata = {'pval_id' : pval_id,'pkey' : pkey,'clsTable': clsTable};

	$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn xóa nội dung này?", function(){

		$.post(path_ajax_script+"/index.php?mod=ajax&act=delete_blobe", $_adata, function(html){

			if(html.indexOf('_success') >= 0){

				window.location.href = return_url+'&message=DeleteSuccess';

			}

		});

	});

	return false;

}

/** Property */

function load_list_property(property_type, options){

	var $_adata = options || {};

	$_adata['property_type'] = property_type;

	toggleIndicatior(1);	

	$.post(path_ajax_script+'/index.php?mod=ajax&act=load_list_property', $_adata, function(html){

		toggleIndicatior(0);

		$('.holderPropertyType_'+property_type).html(html);

		$(".TableListProperty_"+property_type+" tbody").sortable({

			connectWith: ".TableListProperty_"+property_type,

			handle: ".mySortableHandler",

			update: function (event, ui) {

				var orderNo = $(this).sortable('toArray'),

				$_adata = {"orderNo":orderNo};

				toggleIndicatior(1);	

				$.post(path_ajax_script+"/index.php?mod=ajax&act=save_property&action=_saveorder",$_adata,function(html){

					toggleIndicatior(0);	

				});

			}

		}).disableSelection();

	});

}

/** Property */

function load_saveorder_menu(menu_id, options){

	var $_adata = options || {};

	$_adata['menu_id'] = menu_id;

	$(".TableListMenu_"+menu_id+"").sortable({

		connectWith: ".TableListMenu_"+menu_id,

		handle: ".mySortableHandler",

		update: function (event, ui) {

			var orderNo = $(this).sortable('toArray'),

			$_adata = {"orderNo":orderNo,"menu_id":menu_id};

			toggleIndicatior(1);	

			$.post(path_ajax_script+"/index.php?mod=ajax&act=save_menu",$_adata,function(html){

				toggleIndicatior(0);	

			});

		}

	}).disableSelection();

}

function open_property(_this){

	var toId = $(_this).getAttr('toId', 'global'),

		_reload = $(_this).getAttr('_reload', '0'),

		for_id = $(_this).getAttr('for_id' , 0),

		parent_id = $(_this).getAttr('parent_id' , 0),

		property_id = $(_this).attr('property_id'),

		property_type = $(_this).attr('property_type'),

		$_adata = {

			'property_id':property_id,

			'property_type':property_type,

			'toId':toId,

			'_reload':_reload,

			'for_id':for_id,

			'parent_id' : parent_id

		};

	toggleIndicatior(1);

	$.post(path_ajax_script+'/index.php?mod=ajax&act=open_property', $_adata, function(respJson){

		toggleIndicatior(0);

		makepopup('auto','auto', respJson.html, 'open_property_'+property_type);

		if(respJson.callback) eval(respJson.callback);

	}, 'json');

	return false;

}

function save_property(_this){

	var toId = $(_this).attr('toId'),

		_reload = $(_this).attr('_reload'),

		$_form = $(_this).closest('form'),

		property_id =  $(_this).attr('property_id'),

		property_type =  $(_this).attr('property_type'),

		$_adata = {'property_id':property_id,'property_type':property_type};

	var _validated = 0;

	if($('.input.required,select.required',$_form).length){

		$('.input.required,select.required',$_form).each((_i, _elem) => {

			if($Core.util.isEmpty($(_elem).val())){

				_validated++;

				$(_elem).focus();

				return false;

			}

		});

	}

	if($('.isoTextArea',$_form).length){

		$('.isoTextArea', $_form).each((_i, _elem) => {

			var name = $(_elem).data('name'),

				editorId = $(_elem).attr('id');

			$_adata[name] = $Core.util.getTextAreaContent(editorId);

		});

	}

	//alert(_validated); return false;

	if(_validated==0){

		toggleIndicatior(1);

		$_form.ajaxSubmit({

			type: 'POST',

			url: path_ajax_script+'/index.php?mod=ajax&act=save_property',

			data: $_adata,

			dataType: 'html',

			success: function (property_id) {

				toggleIndicatior(0);

				if(_reload==1){

					$.post(path_ajax_script+'/index.php?mod=ajax&act=load_select_property', {

						'property_id' : property_id,

						'property_type' : property_type

					}, function(html){

						$('#'+toId).html(html).trigger('change');

					});

				} else {

					if(act == "agency") {

						$Core.property.load_list_agency("_AGENCY",{'loading':0});

					}else{

						load_list_property(property_type,{'loading':0});

					}

				}

				$Core.alert.success('Saved !');

				if(_DEV == "") {

					$Core.popup.close($_form.closest(".modal"));

				}				

			}

		});

	}

	return false;

}

function delete_property(_this){

	var property_id =  $(_this).attr('property_id'),

		property_type =  $(_this).attr('property_type');

	$Core.alert.confirm("Xác nhận xóa", "Bạn có chắc chắn muốn xóa nội dung này", function(){

		toggleIndicatior(1);

		$.post(path_ajax_script+'/index.php?mod=ajax&act=save_property&action=_delete',

			{'property_type':property_type,'property_id':property_id},

			function(html){

				toggleIndicatior(0);

				if(html.indexOf('_invalid') >= 0){

					$Core.alert.error('Errors');

				}else{

					if(act == "agency") {

						$Core.property.load_list_agency("_AGENCY",{});

					}else{

						load_list_property(property_type,{});

					}

				}

				_this.dialog( "close" );

			}

		);

	});

}

function hide_stock_globe(_this, e){

	var to_field = $(_this).attr('to_field'),

		property_id = $(_this).attr('property_id'),

		status = $(_this).is(':checked') ? 1 : 0; 

	toggleIndicatior(1);

	$.post(path_ajax_script+'/index.php?mod=ajax&act=hide_stock_globe',{

		'to_field' : to_field,

		'property_id':property_id,

		'status' : status

	},function(html){

		toggleIndicatior(0);

	});

}

/* End Property */

/** Setting */

function load_list_setting(setting_type, options){

	var $_adata = options || {};

	$_adata['setting_type'] = setting_type;

	toggleIndicatior(1);	

	$.post(path_ajax_script+'/index.php?mod=setting&act=load_list_setting', $_adata, function(html){

		toggleIndicatior(0);

		$('.holderSettingType_'+setting_type).html(html);

		$(".TableListSetting_"+setting_type+" tbody").sortable({

			connectWith: ".TableListSetting_"+setting_type,

			handle: ".mySortableHandler",

			update: function (event, ui) {

				var orderNo = $(this).sortable('toArray'),

				$_adata = {"orderNo":orderNo};

				toggleIndicatior(1);	

				$.post(path_ajax_script+"/index.php?mod="+mod+"&act=save_setting&action=_saveorder", $_adata, function(html){

					toggleIndicatior(0);	

				});

			}

		}).disableSelection();

	});

}

/**End Setting */

function get_select_city(_this){

	var forId = $(_this).attr('forId'),

		country_id = $(_this).val(),

		$_adata = {'country_id': country_id};

	$('#'+forId).html('<option>-- Loading --</option>');

	$.post(path_ajax_script+"/index.php?mod=ajax&act=get_select_city", $_adata, function(html){

		$('#'+forId).html(html);

	});

}

function get_select_district(_this){

	var forId = $(_this).attr('forId'),

		city_id = $(_this).val(),

		$_adata = {'city_id': city_id};

	$('#'+forId).html('<option>-- Loading --</option>');

	$.post(path_ajax_script+"/index.php?mod=ajax&act=get_select_district", $_adata, function(html){

		$('#'+forId).html(html);

	});

}

$Core.global = {

	open_setting: function(_this, e){

		e.preventDefault();

		var mod_page = $(_this).attr('mod_page'),

			$_adata = {'mod_page':mod_page};

		$Core.util.toggleIndicatior(1);

		$.post(path_ajax_script+"/index.php?mod=ajax&act=open_setting", $_adata, function(respJson){

			$Core.util.toggleIndicatior(0);

			$Core.popup.open('auto','auto',respJson.html,'open_setting');

		}, 'json');

		return false;

	},

	save_setting: function(_this, e){

		e.preventDefault();

		var _validated = 0,

			_form = $(_this).closest('form'),

			mod_page = $(_this).attr('mod_page');

		if(_validated==0){

			toggleIndicatior(1);

			_form.ajaxSubmit({

				type: 'POST',

				url: path_ajax_script+'/index.php?mod=ajax&act=save_setting',

				data: {'mod_page':mod_page},

				dataType: 'html',

				success: function (property_id) {

					$Core.util.toggleIndicatior(0);

					$Core.alert.success('Saved !');

					$Core.popup.close(_form.closest(".modal"));

				}

			});

		}

		return false;

	},

	sync_data_stock: function (_this,e) {

		e.preventDefault();

		toggleIndicatior(1);

		$.ajax({

			type: "POST",

			url: path_ajax_script+'/index.php?mod=ajax&sub=api_log&act=sync_data_stock',

			data: {},

			dataType: "json",

			success: function(respJson) {

				toggleIndicatior(0);

				if(respJson.result) {

					$Core.alert.success(respJson.msg);					

				}else{

					$Core.alert.error(respJson.msg);	

				}

			}

		});

	},

	select_checkbox: (_this,e) => {

		e.preventDefault();

		var _type = $(_this).attr("_type"),

			toId = $(_this).attr("toId");

		if(_type == "_all") {

				console.log("sss");

			if($(_this).is(":checked")) {

				$(".chkitem_"+toId).prop("checked",true);

			}else{

				$(".chkitem_"+toId).prop("checked",false);

			}

		}else{

			if($(".chkitem_"+toId+":checked").length == $(".chkitem_"+toId).length) {

				$("#check_all_"+toId).prop("checked",true);

			}else{

				$("#check_all_"+toId).prop("checked",false);

			}

		}

	}

}

$Core.global.project = {	

	open_banner : (_this,e) => {

		e.preventDefault();

		var $_this = $(_this),

			project_id = $_this.attr('project_id'),

			banner_stock_id = $_this.attr('banner_stock_id');

		toggleIndicatior(1);

		$.ajax({

			type: 'POST',

			url: path_ajax_script+'/index.php?mod=ajax&act=ajOpenBannerStock', 

			data:{"project_id":project_id,'banner_stock_id':banner_stock_id}, 

			dataType:'html',

			success: function(html){

				toggleIndicatior(0);

				$Core.popup.open('auto','auto', html, 'Banner_'+project_id);

			}

		});

		return false;

	}, save_banner_stock :  (_this,e) => {

		e.preventDefault();

		var $_this = $(_this),

			project_id = $_this.attr('project_id'),

			banner_stock_id = $_this.attr('banner_stock_id');

			

		var _validated = 0, _form = $_this.closest('form');

		if($('input.required', _form).length){

			$('input.required', _form).each(function(){

				if($Core.util.isEmpty($(this).val())){

					_validated++;

					$(this).focus();

					return false;

				}

			});

		}

		if(_validated == 0){

			toggleIndicatior(1);

			_form.ajaxSubmit({

				type: 'POST',

				url: path_ajax_script+'/index.php?mod=ajax&act=saveBannerStock', 

				data:{"project_id":project_id,'banner_stock_id':banner_stock_id}, 

				dataType:'json',

				success: function(respJson){

					toggleIndicatior(0);

					if(respJson.result) {

						$Core.global.project.loadListBannerStock(project_id, {});

						$Core.popup.close($_this.closest(".modal"));

						alertify.success(respJson.msg);

					}else{

						alertify.error(respJson.msg);

					}

					

				}

			});

		}

		return false;

	}, loadListBannerStock : (project_id, options) => {

		var $_adata = options || {};

		$_adata['project_id'] = project_id;

		toggleIndicatior(1);

		$.post(path_ajax_script+"/?mod=ajax&act=ajLoadListBannerStock", $_adata, function(respJson){

			toggleIndicatior(0);

			$('.holderBannerStock').html(respJson.html);

			console.log(respJson.html);

			$('.'+respJson.uid).freezeTable({

				'columnNum': 1,

				'scrollable': true,

				'columnKeep': false,

			});

		}, 'json')

	}, status_banner_stock : (_this,e) => {

		e.preventDefault();

		var $_this = $(_this),

			project_id = $_this.attr("project_id"),

			banner_stock_id = $_this.attr("banner_stock_id"),

			ms_code = $_this.attr("ms_code"),

			status_id = $_this.is(":checked") ? 1 : 0;

		toggleIndicatior(1);

			$.ajax({

				type: 'POST',

				url: path_ajax_script+'/index.php?mod=ajax&act=saveBannerStock&action=update_status', 

				data:{"project_id":project_id,"banner_stock_id":banner_stock_id,"status_id":status_id,"ms_code":ms_code},

				dataType:'json',

				success: function(respJson){

					toggleIndicatior(0);

				}

			});

		return false;	

	}, deleteBannerStock : (_this,e) => {

		e.preventDefault();

		var $_this = $(_this),

			project_id = $_this.attr("project_id"),

			banner_stock_id = $_this.attr("banner_stock_id");

		$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn xóa?", function(){

			toggleIndicatior(1);

			$.ajax({

				type: 'POST',

				url: path_ajax_script+'/index.php?mod=ajax&act=saveBannerStock&action=delete', 

				data:{"project_id":project_id,"banner_stock_id":banner_stock_id},

				dataType:'json',

				success: function(respJson){

					toggleIndicatior(0);

					if(respJson.result) {

						alertify.success(respJson.msg);

						$Core.global.project.loadListBannerStock(project_id, {});

					}else{

						alertify.error(respJson.msg);

					}

				}

			});

		});

		return false;	

	},

}
function formToObject(form) {
    var data = {};
    var arr = $(form).serializeArray();

    arr.forEach(function (item) {
        setDeep(data, item.name, item.value);
    });

    return data;
}

function setDeep(obj, path, value) {
    var keys = path.replace(/\]/g, '').split('[');
    var cur = obj;

    keys.forEach(function (key, i) {
        if (i === keys.length - 1) {
            cur[key] = value;
        } else {
            if (!cur[key]) {
                cur[key] = isNaN(keys[i + 1]) ? {} : [];
            }
            cur = cur[key];
        }
    });
}

/* =========================================================================
 * $Core.dropdownClone v2 -- dung chung cho moi trang admin co bang cuon ngang.
 * Van de: dropdown-menu trong vung overflow (.dragscroll / .freeze-table)
 * bi cat khi bang tran/cuon. Xu ly: khi dropdown mo (class .open duoc bat),
 * clone menu ra <body> va dinh vi theo nut; menu goc an tam thoi.
 * v2 KHONG phu thuoc su kien show/hidden.bs.dropdown: sau moi click/keyup,
 * doi 1 tick roi dong bo theo class .open (bootstrap.css hien menu bang
 * .open > .dropdown-menu) -> chay dung voi moi co che toggle.
 * Clone duoc don khi dropdown dong, khi vung bang cuon, hoac khi resize.
 * ========================================================================= */
$Core.dropdownClone = {
	container: '.dragscroll, .freeze-table',
	$grp: null,
	$clone: null,
	$srcMenu: null,
	init: function(){
		var self = $Core.dropdownClone;
		// capture-phase: chay truoc moi handler bubble -> khong bi `return false`
		// (stopPropagation) cua Bootstrap data-api nuot mat nhu khi bind bang
		// $(document).on('click'); setTimeout(0) de Bootstrap toggle .open xong
		document.addEventListener('click', function(){ setTimeout(self.sync, 0); }, true);
		document.addEventListener('keyup', function(){ setTimeout(self.sync, 0); }, true);
		// scroll khong bubble -> bat capture phase de dinh ca container AJAX-injected
		document.addEventListener('scroll', function(e){
			if(self.$clone && e.target && e.target !== document && $(e.target).is(self.container)){
				self.forceClose();
			}
		}, true);
		$(window).on('resize', function(){
			if(self.$clone){ self.forceClose(); }
		});
	},
	sync: function(){
		var self = $Core.dropdownClone;
		var $openGrp = $(self.container).find('.btn-group.open, .dropdown.open').first();
		if(!$openGrp.length){ self.close(); return; }
		if(self.$grp && self.$grp[0] === $openGrp[0]){ return; } // clone dang dung cho group nay
		self.open($openGrp);
	},
	open: function($grp){
		var self = $Core.dropdownClone;
		self.close();
		var $menu = $grp.children('.dropdown-menu');
		if(!$menu.length){ return; }
		var rect = $grp[0].getBoundingClientRect();
		var winTop = $(window).scrollTop();
		var winLeft = $(window).scrollLeft();
		var $clone = $menu.clone(true).addClass('dropdown-menu-cloned').appendTo('body');
		// inline de khong phu thuoc rule .dropdown-menu-cloned trong admin.css
		$clone.css({ display: 'block', position: 'absolute', margin: 0, zIndex: 2050 });
		var top = rect.bottom + winTop;
		var left = rect.left + winLeft;
		if($grp.hasClass('dropup')){ top = rect.top + winTop - $clone.outerHeight(); }
		if(rect.left + $clone.outerWidth() > $(window).width() - 8){
			left = rect.right + winLeft - $clone.outerWidth();
		}
		$clone.css({ top: top, left: left });
		self.$grp = $grp;
		self.$clone = $clone;
		self.$srcMenu = $menu.css('display', 'none'); // an menu goc dang bi cat
	},
	close: function(){
		var self = $Core.dropdownClone;
		if(self.$clone){ self.$clone.remove(); self.$clone = null; }
		if(self.$srcMenu){ self.$srcMenu.css('display', ''); self.$srcMenu = null; }
		self.$grp = null;
	},
	forceClose: function(){
		var self = $Core.dropdownClone;
		// dong dropdown goc: Bootstrap 3 hien/an hoan toan theo class .open
		if(self.$grp){ self.$grp.removeClass('open'); }
		self.close();
	}
};
$(function(){ $Core.dropdownClone.init(); });

$Core.chosenDropUp = {
	init: function(){
		var self = $Core.chosenDropUp;
		$(document).on('chosen:showing_dropdown', function(e, params){
			if(params && params.chosen){ self.apply(params.chosen.container); }
		});
		$(document).on('chosen:hiding_dropdown', function(e, params){
			if(params && params.chosen){ params.chosen.container.removeClass('chosen-drop-up'); }
		});
	},
	// vung ma drop con nhin thay: giao cua cac ancestor cat overflow, chan ngoai la viewport
	limits: function($container){
		var box = { top: 0, bottom: $(window).height() };
		$container.parents().each(function(){
			if(this === document.body || this === document.documentElement){ return false; }
			var overflow = $(this).css('overflow-x') + ' ' + $(this).css('overflow-y');
			if(overflow.indexOf('visible') === -1){
				var rect = this.getBoundingClientRect();
				box.top = Math.max(box.top, rect.top);
				box.bottom = Math.min(box.bottom, rect.bottom);
			}
		});
		return box;
	},
	apply: function($container){
		var self = $Core.chosenDropUp;
		var $drop = $container.children('.chosen-drop');
		if(!$drop.length){ return; }
		var $results = $drop.find('.chosen-results');
		var maxResults = parseInt($results.css('max-height'), 10) || 0;
		// winnow_results() do lai danh sach ngay sau event -> cong them phan results con gian duoc
		var dropHeight = $drop.outerHeight() + Math.max(0, maxResults - $results.outerHeight());
		var rect = $container[0].getBoundingClientRect();
		var box = self.limits($container);
		var spaceBelow = box.bottom - rect.bottom;
		var spaceAbove = rect.top - box.top;
		var flip = spaceBelow < dropHeight && spaceAbove > spaceBelow;
		$container.toggleClass('chosen-drop-up', flip);
	}
};
$(function(){ $Core.chosenDropUp.init(); });

/* =========================================================================
 * $Core.resizableBox -- dung chung cho moi vung danh sach co class .ui-resize-y.
 * CSS chi dat max-height de box tu co lai khi it dong, nhung max-height cung
 * chan luon thao tac keo cao them. Xu ly: ngay khi nguoi dung bam vao nut keo
 * o goc duoi-phai, chot chieu cao hien tai thanh height inline roi bo
 * max-height -> tu do keo giong textarea. Delegate tren document nen ap dung
 * duoc ca cho vung duoc nap bang AJAX.
 * ========================================================================= */
$Core.resizableBox = {
	grip: 18, // canh vung goc duoi-phai chua nut keo cua trinh duyet
	init: function(){
		var self = $Core.resizableBox;
		$(document).on('mousedown', '.ui-resize-y', function(e){
			self.unlock(this, e);
		});
	},
	unlock: function(el, e){
		var self = $Core.resizableBox;
		if(el.style.height){ return; } // da bo max-height o lan keo truoc
		var rect = el.getBoundingClientRect();
		if(e.clientX < rect.right - self.grip){ return; }
		if(e.clientY < rect.bottom - self.grip){ return; }
		el.style.height = el.offsetHeight + 'px';
		el.style.maxHeight = 'none';
	}
};
$(function(){ $Core.resizableBox.init(); });
