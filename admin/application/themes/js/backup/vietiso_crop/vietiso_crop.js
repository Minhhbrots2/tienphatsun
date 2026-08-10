/*
** Create by quyet spring - thiembv@vietiso.com
** Date Create: 1/14/2013 11:54:01 AM 
*/
function getmaxzindex(){
	var maxindex = 0;
	$('div').each(function(){
		var zindex = parseInt($(this).css('z-index'));
		if(zindex>maxindex) maxindex=zindex;
	});
	return maxindex+100;
}
$('.close_pop').live('click',function(){
	var id = $(this).closest('.frmCropPop').attr('id');
	$('#'+id).remove();
	$('#isoblanketpop_'+id).remove();
	return false;
});
function makeCropPopup(width,height,content,name){
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
			 zIndex: getmaxzindex()  
		  }).appendTo(document.body);
		$('<div id="'+name+'" class="frmCropPop">').appendTo(document.body).html(content);
		$('#'+name)
		.css('position','fixed')
		.css('width',width)
		.css('height',height)
		.css("left",($(window).width()-$('#'+name).width())/2 + "px")
		.css("top",($(window).height()-$('#'+name).height())/2-20 + "px")
		.css("z-index",getmaxzindex()+100)
		.show().find('.required:first').focus();
		$(window).resize(function(){
			$('#'+name).css("left", ($(window).width()-$('#'+name).width())/2 + "px")
					   .css("top", ($(window).height()-$('#'+name).height())/2+'px')
		});
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
	$('#'+name).draggable({containment:"body",cancel:".form",handle:'.headCropPop'}).find('.headCropPop').css('cursor','move');
}
function showCropCoords(obj){
	$('#x_axis_1').val(parseInt(obj.x));
	$('#y_axis_1').val(parseInt(obj.y));
	$('#x_axis_2').val(parseInt(obj.x2));
	$('#y_axis_2').val(parseInt(obj.y2));
	$('#t_width').val(parseInt(obj.w)+30);
	$('#t_height').val(parseInt(obj.h)+26);
}
function func_crop_image_src(){
	var path_crop_js = url_js+'/vietiso_crop/jquery.Jcrop.min.js';
	if($('.jcrop-holder').length<1){			
		$.getScript(path_crop_js,function(){
			$('#imgage_crop_src').Jcrop({
				onSelect: showCropCoords,
				onChange: showCropCoords
			});
		});
	}
}
function func_resize_image_scr(){
	$('#imgage_resize_src').resizable({
		resize: function(event, ui) {
			$('#resize_width').val($(this).width());
			$('#resize_height').val($(this).height());
        }
	});
}
function makeCropTab(){
	$('.tabs_CropPop a').each(function(index){
		$(this).attr('tabs_croppop','crop_tabbox'+index);
	});
	$('.crop_tabbox').each(function(index){
		$(this).attr('id','crop_tabbox'+index);
	});
	$('.preview_CropPop').each(function(index){
		$(this).attr('id','preview_crop_tabbox'+index);
	});
	$('.tabs_CropPop a').live('click',function(){
		var _this = $(this);
		/* Load func follow Tabs*/
		if(_this.hasClass('resize')){
			func_resize_image_scr();
		}
		if(_this.hasClass('crop')){
			func_crop_image_src();
		}
		if(_this.hasClass('rotate')){
			func_rotate_image_src();
		}
		/* End func*/
		$('.tabs_CropPop a').removeClass('current');
		var type = _this.attr('_type');
		$('#clickToProccess').val(type);
		_this.addClass('current');
		$('.crop_tabbox:visible').hide();
		$('.preview_CropPop:visible').hide();
		$('#'+_this.attr('tabs_croppop')).show();
		$('#preview_'+_this.attr('tabs_croppop')).show();
	});
}
(function($){
	$.fn.vietisocrop = function(options){
		var defaults = {
			allow_upload: 0,
			preview_el: '',
			hidden_el:'',
			only_crop : 0
		};
		var opt = $.extend(defaults, options);
		
		if(opt.only_crop){
			var _this = $(this);
			var image_src = _this.attr('data-source');
			var clsTable = _this.attr('clsTable');
			var pvalTable = _this.attr('pvalTable');
			var path_root= url_js +'/vietiso_crop';
			var path_crop_js = path_root+'/jquery.Jcrop.min.js';
			// Generate HTML
			var html = '';
			html +='<div class="headCropPop">';
			html +='<a class="closeEv close_pop" href="javascript:void();" title="Đóng">&nbsp;</a>';
			html +='<h3>VietISO Image Crop</h3>';
			html +='</div>';
			html +='<form action="" method="post" enctype="multipart/form-data" id="frmFormCropBox">';
			html +='<div class="contentCropPop">';
			html +='<div class="leftCropPop">';
			html +='<ul class="tabs_CropPop">';
			html +='<li><a class="resize" _type="resize" href="javascript:void();">Resize</a></li>';
			html +='<li><a class="crop current" _type="crop" href="javascript:void();">Crop</a></li>';
			html +='<li><a class="rotate" _type="rotate" href="javascript:void();">Rotate</a></li>';
			html +='</ul>';
			html +='<div class="content_tabs_CropPop">';
			
			/* Crop Tab 1*/
			html +='<div class="crop_tabbox">';
			html +='<div class="crop_line">';
			html +='<label>Width</label>';
			html +='<input class="crop_txt" type="text" id="resize_width" name="resize_width" value="" />';
			html +='</div>';
			html +='<div class="crop_line">';
			html +='<label>Height</label>';
			html +='<input class="crop_txt" type="text" id="resize_height" name="resize_height" value="" />';
			html +='</div>';
			html +='</div>';
			/* End crop Tab 1*/
			/* Crop Tab 2*/
			html +='<div class="crop_tabbox">';
			html +='<div class="crop_line">';
			html +='<label>X</label>';
			html +='<input class="crop_txt" type="text" id="x_axis" name="crop_x" value="" />';
			html +='</div>';
			html +='<div class="crop_line">';
			html +='<label>Y</label>';
			html +='<input class="crop_txt" type="text" id="y_axis" name="crop_y" value="" />';
			html +='</div>';
			html +='<div class="crop_line">';
			html +='<label>Width</label>';
			html +='<input class="crop_txt" type="text" id="t_width" name="crop_width" value="" />';
			html +='</div>';
			html +='<div class="crop_line">';
			html +='<label>Height</label>';
			html +='<input class="crop_txt" type="text" id="t_height" name="crop_height" value="" />';
			html +='</div>';
			html +='</div>';
			/* End crop Tab 2*/
			
			/* Crop Tab 3*/
			html +='<div class="crop_tabbox">';
			html +='<div class="crop_line">';
			html +='<label>Angle</label>';
			html +='<input class="crop_txt" type="text" id="rotate_angle" name="rotate_angle" value="0" />';
			html +='</div>';
			html +='<div id="slider-range-min"></div>';
			html +='</div>';
			
			/*End Crop Tab 3*/
			html +='</div>';
			html +='</div>';
			html +='<div class="rightCropPop">';
			html +='<div id="preview_CropPop">';
			html +='<img id="imgTemp" src="'+image_src+'" >'		
			html +='</div>';
			html +='<div class="modal-footer">';
			html +='<button class="btn btn-primary" type="resize" clsTable="'+clsTable+'" pval="'+pvalTable+'" id="clickToProccess">Apply &amp; save</button>';
			html +='</div>';
			html +='</div>';
			html +='</div>';
			html +='<input type="hidden" name="image_src" value="'+image_src+'" />';
			html +='</form>';
			makeCropPopup($(window).width()/1.5,'auto',html,'frmCropBox');
			makeCropTab();
			func_calulation_size_image_first();
			/*this.click(function(){
			});*/
			$('#clickToProccess').live('click',function(){
				var _this = $(this);
				var adata = {
					'clsTable' : _this.attr('clsTable'),
					'pvalTable': _this.attr('pval'),
					'crop_type': _this.val()
				};
				$('#frmFormCropBox').ajaxSubmit({
					type:'POST',
					url: path_ajax_script+'/index.php?mod=media&act=crop',
					data : adata,
					success: function(html){
						alert(html);
						$('#frmFormCropBox a.close_pop').trigger('click');
					}
				});
				return false;
			});
		}
		/* End Crop */
		
		if(opt.allow_upload){
			var html='';
			html +='<div class="headCropPop">';
			html +='<a class="closeEv close_pop" href="javascript:void();" title="Đóng">&nbsp;</a>';
			html +='<h3>VietIso Image Uploader</h3>';
			html +='</div>';
			html +='<form action="" method="post" enctype="multipart/form-data" id="frmFormCropBox">';
			html +='<div class="contentCropPop">';
			html +='<input type="file" name="image" id="AddFileAttachUpload" style="display:none">';
			html +='<div class="dw2d">';
			html +='<div class="wrap">';
			html +='<a href="javascript:void();" class="btn fl btn-primary" id="selectFileUpload"><i class="icon-picture icon-white"></i> <span>Chọn ảnh từ máy tính</span></a>';
			html +='<span class="appendtxt">Không có hình ảnh được chọn!</span>';
			html +='<div class="progress"><div class="percent"></div></div>';
			html +='</div>';
			html +='</div>';
			html +='</form>';
			makeCropPopup('95%','90%',html,'frmCropBox');
			/* Select file */
			$('#selectFileUpload').click(function(){
				$('#AddFileAttachUpload').click();
				return false;
			});
			/* Run Upload */
			$('#AddFileAttachUpload').live('change',function(){
				var path_crop_js = url_js+'/vietiso_crop/jquery.Jcrop.min.js';
				var path_submit_form = url_js+'/jquery.form.js';
				$.getScript(path_crop_js, function() {
					var _array = $('#AddFileAttachUpload')[0].files;
					if(_array[0]['type']=='image/jpeg' || _array[0]['type']=='image/png' || _array[0]['type']=='image/gif'){
						$(".appendtxt").html(_array[0]['name']);
						$('.progress').show();
						$.getScript(path_submit_form,function() {
							$('#frmFormCropBox').ajaxSubmit({
								type:'POST',
								url: path_ajax_script+'/index.php?mod=media&act=ajUploader',
								uploadProgress: function(event,position,total,percentComplete){
									$('.percent').css({'width':percentComplete+'%'});
								},
								success: function(html){
									var htm = '';
									htm +='<div class="contentCropPop">';
									htm +='<div class="help_cropPop">Để cắt ảnh này, kéo vùng bên dưới rồi nhấp vào "Crop"</div>';
									htm +='<input class="crop_txt" type="hidden" id="x_axis_1" name="crop_x_1" value="" />';
									htm +='<input class="crop_txt" type="hidden" id="y_axis_1" name="crop_y_1" value="" />';
									htm +='<input class="crop_txt" type="hidden" id="x_axis_2" name="crop_x_2" value="" />';
									htm +='<input class="crop_txt" type="hidden" id="y_axis_2" name="crop_y_2" value="" />';
									
									htm +='<input class="crop_txt" type="hidden" id="t_width" name="crop_width" value="" />';
									htm +='<input class="crop_txt" type="hidden" id="t_height" name="crop_height" value="" />';
									htm +='<input type="hidden" name="image_src" value="'+html+'" />';
									htm +='<img id="imgage_crop_src" src="'+html+'" style="max-height:400px;max-width:980px" />'		
									htm +='</div>';
									htm +='<div class="modal-bottom">';
									htm +='<fieldset class="submit-buttons">';
									htm +='<button class="btn btn-success mr10 corner4px" value="crop" id="clickNotCrop"> Not crop & save</button>';
									htm +='<button class="btn btn-primary mr10 corner4px" value="crop" id="clickToProccess"><img align="absmiddle"  src="/ucp/isocms/themes/js/vietiso_crop/images/crop.png"> Crop & save image</button>';
									htm +='<button class="btn btn-danger close_pop closeEv corner4px">Cancel</button>';
									htm +='</fieldset>';
									htm +='</div>';
									$('#frmFormCropBox').html(htm);
									func_crop_image_src();
									// Run action.
									$('#clickToProccess').live('click',function(){
										var _this = $(this);
										var adata = {'crop_type': _this.val()};
										$('#frmFormCropBox').ajaxSubmit({
											type:'POST',
											url: path_ajax_script+'/index.php?mod=media&act=editor_image_src',
											data : adata,
											success:function(html){
												var htm = html.replace(' ','');
												$(opt.hidden_el).val(htm);
												$(opt.preview_el).attr('src',htm);
												$('#frmCropBox .closeEv').trigger('click');
											}
										});
										return false;
									});
									$('#clickNotCrop').click(function(){
										var htm = html.replace(' ','');
										$(opt.hidden_el).val(htm);
										$(opt.preview_el).attr('src',htm);
										$('#frmCropBox .closeEv').trigger('click');
										return false;
									});
								}
							});
						});
					}else{
						alert('Not format !');
						return false;     
					}
				});
				return false;
			});
		}
	}
})(jQuery);
$().ready(function(){
	$('.clickToCrop').live('click',function(e){
		var _this = $(this);
		_this.vietisocrop({'only_crop':1});
	});
	$('.vietISO_upload').live('click',function(){
		var _this = $(this);
		_this.vietisocrop({'allow_upload':1,'preview_el':'.image img','hidden_el':'.hidden_src'});
	});
});
