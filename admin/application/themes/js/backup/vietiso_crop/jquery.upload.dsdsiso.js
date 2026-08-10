/* Jquery ISO Upload Plugin
** Create by quyet spring - quyetnx@vietiso.com
** Date Create: 1/14/2013 11:54:01 AM
*/
(function($){
	$.fn.isoupload = function(options){
		var path_app='';
		var path_upload='';
		var element='';
		options = $.extend(true, {
	        path_app:  null, //path off this plugin
	        path_php:  null, //path of file php
	        path_upload: null, //path contain image uploaded
	        element: null, //return result to element after crop ex #result, .result
	        review: null, //return result to element for review image after crop ex #result, .result
	    }, options);
		this.click(function(){
			var mrg_left=($(window).width()-920)/2;
			var form= '<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="form_is_up">';
			   	form+='<h1>Image uploader <a id="closeForm">Close</a></h1>';
				form+='<input type="file" name="image" value="" id="is_up" class="hiddenFile" placeholder="">';
				form+='<div class="dw2d">';
				form+=' <a href="#" class="btn btn-primary" style="color:#fff;float:left;border-radius:0;" id="triggerImg"><i class="icon-picture icon-white"></i> <span>Chọn ảnh từ máy tính</span></a>';
				form+='<span class="appendtxt">Không có hình ảnh được chọn!</span>';
				form+='<div class="progress"><div class="percent"></div></div>';
				form+='</div></form>';
			$('body').append('<div id="isoOverlay"></div>'+form);
			$('#form_is_up').css({'left':mrg_left});
			return false;
		});
		// Click
		$('#closeForm').live('click', function(){
			$('#form_is_up, #isoOverlay').remove();
			return false;
		});
		$('#triggerImg, .dw2d').live('click',function(){
	        $('.hiddenFile').trigger('click');
	        return false;
	    });
	    // Run Upload File.
	    $('#is_up').live('change',function(){
	    	var path_lib=options.path_app+'/iso_upload';
	    	var path_jcrop=path_lib+'/jquery.Jcrop.min.js';
	    	var path_jform=path_lib+'/jquery.form.js';
	    	$.getScript(path_jcrop, function() {
	    		function showCoords(obj){
			        var x_axis = obj.x;
			        var y_axis = obj.y;
			        var t_width = obj.w;
			        var t_height = obj.h;
			        $('#x_axis').val(x_axis);
			        $('#y_axis').val(y_axis);
			        $('#t_width').val(t_width);
			        $('#t_height').val(t_height);
			        $('#saveImge').show();
			    }
			    var files = $('input#is_up')[0].files;
		        if(files[0]['type']=='image/jpeg'){
		            $(".appendtxt").html(files[0]['name']);
		            $('.progress').show();
		            $.getScript(path_jform, function() {
		            	$('#form_is_up').ajaxSubmit({
			                type:'POST',
			                url: path_ajax_script+'/index.php?mod=ajax&act=upload',
			                uploadProgress: function(event,position,total,percentComplete){
			                    $('.percent').css({'width':percentComplete+'%'});
			                },
			                success: function(html){
								var htm ='<h1>Image uploader <a id="closeForm">Close</a></h1>';
									htm+='<div id="imgBorder">';
									htm+='<img id="imgTemp" src="'+domain_name+html+'" >';
									htm+='</div>';
									htm+='<input type="hidden" id="x_axis" name="x_axis" value="" />';
									htm+='<input type="hidden" id="y_axis" name="y_axis" value="" />';
									htm+='<input type="hidden" id="t_width" name="t_width" value="" />';
									htm+='<input type="hidden" id="t_height" name="t_height" value="" />';
									htm+='<input type="hidden" name="url_img" value="'+domain_name+html+'" />';
									htm+='<center><a href="javascript:void();" id="action_crop"><img src="'+options.path_app+'/iso_upload/crop.png">Crop and save image</a></center>';
			                    $('#form_is_up').html(htm);
			                    $('#imgTemp').Jcrop({
			                        onSelect: showCoords,
			                        onChange: showCoords,
			                        setSelect:   [ 500, 375, 0, 0 ],
			                        aspectRatio: 25 / 22
			                    });
			                    // Run Crop.
								$('#action_crop').live('click',function(){
									$('#form_is_up').ajaxSubmit({
							            type:'POST',
							            url: path_ajax_script+'/index.php?mod=ajax&act=cropimage',
							            success: function(response){
							                $(options.element).val(domain_name+'/uploads/images_upload/'+response);
							                $(options.review).attr('src',domain_name+'/uploads/images_upload/'+response);
							                $('#form_is_up, #isoOverlay').remove();
							            }
							        });
									return false;
								});
			                }
			            });
		            });
		        }else{
		            alert('Chỉ chấp nhận ảnh có định dạng *.jpg !');
		            return false;     
		        }
	    	});
	    	return false;
	    });
	}
})(jQuery);