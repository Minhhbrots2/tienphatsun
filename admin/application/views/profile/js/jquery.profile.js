function file_explorer(_this, e) {
    e.preventDefault();
    var toId = $(_this).attr('toId'),
		toImg = $(_this).attr('toImg'),
		member_id = $(_this).attr('member_id'),
        params = {"member_id":member_id, 'toImg':toImg};
    $('#'+toId).val("").click();
    $('#'+toId).change(function(){
        let file, files = $(this).prop('files');
        if (files && files.length) {
            file = files[0];
            if (/^image\/\w+/.test(file.type)) {
                file_upload(file, params);
            }
        }
    });
    return false;
}
function file_upload(file, params){
    var URL = window.URL || window.webkitURL,
        imgdata = URL.createObjectURL(file),
        filename = $.trim(file.name),
        $_adata = params || {};
    $_adata['filename'] = filename;
    $_adata['imgdata'] = imgdata;
    
    $.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_cropper', $_adata, function(html){
        toggleIndicatior(0);
        $Core.popup.open('auto', 'auto', html, 'cropper-dialog');
        var $cropper = $('#'+'cropper'),
            $cropper_width = $('#'+'cropper-width'),
            $cropper_height = $('#'+'cropper-height'),
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
                cropper = $cropper.data('cropper');
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
                var result = $('#cropper').cropper(data.method, data.option);
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
                            $.post(path_ajax_script+'/index.php?mod='+mod+'&act=upload_avatar', $_bdata, function(html){
                                $('#'+'frmCropper')[0].reset();
                                $cropper.cropper('destroy');
                                $Core.popup.close($('#'+'cropper-dialog'));
                                if(html.indexOf('_error') >= 0){
                                    $Core.messager.alert(__['Message'], __['Image upload error']);
                                } else {
                                    var tmp = html.split('|||');
                                    $('#'+params.toImg).attr('src', tmp[1]);
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
}
function add_bank(_this, e){
	e.preventDefault();
	$Core.util.toggleIndicatior(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_bank', {}, function(html){
		$Core.util.toggleIndicatior(0);
		if($('.holder_banks .bank-item').length){
			$('.holder_banks .bank-item:last').after(html);
		} else {
			$('.holder_banks').html(html);
		}
	});
	return false;
}
function remove_bank(_this, e){
	e.preventDefault();
	var _form = $(_this).closest('form'),
		_bank = $(_this).closest('div.bank-item', _form);
	
	_bank.remove();
	if($('.holder_banks .bank-item').length == 0){
		$('#'+'add_branch_bank').trigger('click');
	}
	return false;
}
function open_password(_this, e){
	e.preventDefault();
	var profile_id = $(_this).attr('profile_id');
	$Core.util.toggleIndicatior(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_password', {
		'profile_id' : profile_id
	}, function(html){
		$Core.util.toggleIndicatior(0);
		
	});
	return false;
}
$Core.member = {
	send_email: function(_this, e){
		e.preventDefault();
		var profile_id = $(_this).attr('profile_id');
		$Core.alert.confirm('Thông báo', 'Bạn có chắc chắn muốn gửi e-mail cho người này?', () => {
			$Core.util.toggleIndicatior(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=send_email', {
				'profile_id' : profile_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_success') >= 0){
					$Core.alert.success('Thành công!');
				} else {
					$Core.alert.error('Thất bại!');
				}
			});
		});
		return false;
	}, open_permiss: function(_this, e){
		var profile_id = $(_this).attr('profile_id');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_permiss', {
			'profile_id' : profile_id
		}, function(respJson){
			$Core.popup.open('auto', 'auto', respJson.html, 'open_permiss');
		}, 'json');
	}, storage: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			profile_id = $(_this).attr('profile_id'),
			profile_type = $(_this).attr('profile_type');
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_permiss',
			data: {'profile_id':profile_id,'profile_type':profile_type},
			dataType:'html',
			success : function(respJson){
				vietiso_loading(0);
				$Core.popup.close(_form.closest('.modal'));
			}
		});	
		return false;
	}, add_employ: function(_this, e){
		e.preventDefault();
		if(confirm("Bạn có chắc chắn muốn chuyển công tác viên này thành nhân viên")){
			var _form = $(_this).closest('form'),
			profile_id = $(_this).attr('profile_id'),
			profile_type = $(_this).attr('profile_type');
			vietiso_loading(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=add_employ',
				data: {'profile_id':profile_id,'profile_type':profile_type},
				dataType:'html',
				success : function(respJson){
					vietiso_loading(0);
					$Core.popup.close(_form.closest('.modal'));
				}
			});	
		}
		
		return false;
	}, uploadImage :function(_this,e){ 
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
				$Core.member.image_upload(formData,params);				
			}
		});
	}, image_upload: function(formData, params){
		vietiso_loading(1);
		$.ajax({
			url: path_ajax_script+"/index.php?mod="+mod+"&act=uploadImage",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function (html) {
				vietiso_loading(0);
				if(html.indexOf('_success') >= 0){
					var tmp = html.split('|||');
					if(params.type == "images"){
						if($('#'+params.toImg+' .item').length > 0){
							$('#'+params.toImg+' .item:last-child').after(tmp[1]);
						} else {
							$('#'+params.toImg).html(tmp[1]);
						}
					}else if(params.type == 'avatar' || params.type == 'banner'){
						$('#'+params.toImg).attr("src",tmp[1]); 
					}
					
				}
			}
		});
	}, addVideo: function(_this,profile_id){
		var $_adata ={};
		$_adata['profile_id'] = profile_id;
		var link_file = $(_this).closest(".box_form").find("input[name='link_video']").val();
		$_adata['link_file'] = link_file;
		if(link_file != ''){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=addVideo', $_adata, function(html){
				if(html != ""){
					console.log(html);
					$('#box_video').html(html);	
				}
				
			}, 'html');
		}
	},
	formAddField : function(_this,type){
		vietiso_loading(1);
		var field_id = $(_this).data("field_id"),
			field = $(_this).data("field"),
			member_id = $(_this).data("member_id");
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=formAddField',{"type":type,"field_id":field_id,"field":field,"member_id":member_id}, function(respJson){
			vietiso_loading(0);
			console.log(respJson);
			$Core.popup.open('500','500', respJson.html, respJson.uid);
		}, 'json');
	},
	AddField : function(_this,type){
		var _form = $(_this).closest("form");
		var title = $("input[name=title]",_form).val();
		var field_id = $("input[name=field_id]",_form).val();
		var image_hidden = $("input[name=image_hidden]",_form).val();
		var field = $(_this).data("field");
		var member_id = $(_this).data("member_id");
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
		formData.append('member_id',member_id);	
		formData.append('star',star);	
		vietiso_loading(1); 
		$.ajax({
			url: path_ajax_script+'/index.php?mod='+mod+'&act=formAddField',
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			dataType: "json",
			success: function (respJson) {
				vietiso_loading(0);
				if(respJson.result){
					if(field_id != ""){
						alertify.success("Cập nhật thành công!");						
					}else{
						alertify.success("Thêm mới thành công!");
					}		
					
					$("#lst_"+field).html(respJson.html);
					$Core.popup.close(_form.closest('.modal'));
				}else{
					alertify.error("ERROR !");
				}				
			}
		});
	}, deleteField : (_this,type) => {
		if(confirm("Bạn có chắc chắn muốn xoá bản ghi này?")){
			vietiso_loading(1);
			var field_id = $(_this).data("field_id"),
				field = $(_this).data("field"),
				member_id = $(_this).data("member_id");
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=formAddField',{
				"type":"delete",
				"field_id":field_id, 
				"field":field, 
				"member_id":member_id
			}, function(respJson){
				vietiso_loading(0);
				if(respJson.result){
					alertify.success("Xoá thành công!");	
					$("#lst_"+field).html(respJson.html);	
				}else{
					alertify.error("ERROR !");
				}	
			}, 'json');
		}		
	}, load_logs:  (options) => {
		var $_adata = options || {};
		$_adata['user_id'] = profile_id;
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_sale_logs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_logs').html(respJson.html);
			$('.total_record').html(respJson.total_record);
			/*if(parseInt(respJson.total_page) > 1){
				$('#pager_sale_logs').addClass('mt-2').pagination({
					listStyle:"pagination justify-content-center",
					currentPage: respJson.current_page, 
					itemsOnPage: respJson.per_page,
					items: respJson.total_record,
					displayedPages: 5,
					cssStyle: 'light-theme',
					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
					onPageClick : function(pageNumber){
						$Core.member.load_logs($.extend(options, {'page':pageNumber}));
					}
				});
			}*/
		},'json');
	}, load_logs_point:  (options) => {
		var $_adata = options || {};
		$_adata['user_id'] = profile_id;
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_logs_point', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_logs_point').html(respJson.html);
		},'json');
	}, updateGender:  (_this,e) => {
		e.preventDefault();
		var profile_id = $(_this).attr("profile_id"),
			gender_id = $(_this).val(),
			$_adata = {"p_id":profile_id, 'p_field': 'gender_id', 'p_value':gender_id};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=upd_field', $_adata, function(respJson){
			vietiso_loading(0);
		},'json');
	}, update_field : (_this, e) => {
		var p_id = $(_this).attr("p_id"),
			p_field = $(_this).attr('p_field'),
			p_value = $(_this).val(),
			$_adata = {"p_id":p_id, p_field:p_field, p_value : p_value};
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=upd_field', $_adata, function(html){
			vietiso_loading(0);
		});
	}
};

/* ===== Import nhân sự từ Google Sheet (dán link -> chọn cột -> import) ===== */
function open_import(_this, e){
	e.preventDefault();
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_import', {}, function(html){
		vietiso_loading(0);
		$Core.popup.open('auto', 'auto', html, 'profile_import');
	});
	return false;
}
function read_import(_this, e){
	e.preventDefault();
	var _form = $(_this).closest('form');
	var url = $.trim($('input[name=sheet_url]', _form).val());
	if(!url){ $Core.alert.error('Vui lòng dán link Google Sheet.'); return false; }
	var pass = $.trim($('input[name=default_pass]', _form).val());
	if(!pass){ $Core.alert.error('Vui lòng nhập mật khẩu mặc định cho tài khoản mới.'); return false; }
	vietiso_loading(1);
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=read_import', _form.serialize(), function(resp){
		vietiso_loading(0);
		var tmp = resp.split('|||'),
			body = tmp[1];
		if(resp.indexOf('OK') >= 0){
			$Core.popup.close($('#profile_import'));
			$Core.popup.open('auto', 'auto', body, 'profile_import_map');
		} else {
			$Core.alert.error(body || 'Không đọc được Sheet.')
		}
	});
	return false;
}
function do_import(_this, e){
	e.preventDefault();
	var _form = $(_this).closest('form');
	$Core.alert.confirm('Xác nhận import', 'Bắt đầu import nhân sự vào hệ thống?', function(){
		vietiso_loading(1);
		$.ajax({
			url: path_ajax_script+'/index.php?mod='+mod+'&act=do_import',
			type: 'POST',
			data: _form.serialize(),
			dataType: 'json',
			success: function(resp){
				vietiso_loading(0);
				var cls = (resp && resp.result === '_success') ? 'text-success' : 'text-danger';
				var msg = (resp && resp.message) ? resp.message : 'Không rõ kết quả.';
				$Core.popup.close($('#profile_import_map'));
				var html = '<div class="modal-dialog" style="width:560px;max-width:94vw"><div class="modal-content">'
					+ '<div class="modal-header"><a href="javascript:void(0)" class="closeEv close_pop close"><span>×</span></a><h3 class="modal-title"><strong>Kết quả import</strong></h3></div>'
					+ '<div class="modal-body"><p class="' + cls + '">' + msg + '</p></div>'
					+ '<div class="modal-footer"><button type="button" class="btn btn-primary" onclick="window.location.reload()">Tải lại danh sách</button> <button type="button" class="btn btn-default" onclick="$Core.popup.close($(\'#profile_import_result\'))">Đóng</button></div>'
					+ '</div></div>';
				$Core.popup.open('auto', 'auto', html, 'profile_import_result');
			},
			error: function(){
				vietiso_loading(0);
				$Core.alert.error('Lỗi khi import.');
			}
		});
	});
	return false;
}
