function file_explorer(_this, e) {
    e.preventDefault();
    var toId = $(_this).attr('toId'),
		toImg = $(_this).attr('toImg'),
		profile_id = $(_this).attr('profile_id'),
        params = {"profile_id":profile_id, 'toImg':toImg};

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
	open_assign_package: function(_this){
		var profile_id = $(_this).attr('data-profile_id'),
			mp_id = $(_this).attr('data-mp_id') || 0;
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=member&act=open_assign_package', {
			'profile_id': profile_id, 'mp_id': mp_id
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'assign_package');
		}, 'json');
		return false;
	},
	save_member_package: function(_this, e){
		if(e) e.preventDefault();
		var $btn = $(_this), $_form = $btn.closest('form'),
			profile_id = $btn.attr('data-profile_id'),
			mp_id = $btn.attr('data-mp_id');
		var _invalid = $('.required', $_form).filter(function(){ return $Core.util.isEmpty($(this).val()) || $(this).val()=='0'; });
		if(_invalid.length){ $Core.alert.error('Chưa chọn gói.'); _invalid.first().focus(); return false; }
		vietiso_loading(1);
		$_form.ajaxSubmit({
			type:'POST', url: path_ajax_script+'/index.php?mod=member&act=save_member_package',
			data: { 'profile_id': profile_id, 'mp_id': mp_id }, dataType:'json',
			success: function(respJson){
				vietiso_loading(0);
				if(respJson.msg && respJson.msg.indexOf('_success') >= 0){
					$Core.alert.success('Đã lưu!');
					$Core.popup.close($_form.closest('.modal'));
					$Core.member.reload_member_packages(profile_id);
				} else { $Core.alert.error('Lưu thất bại!'); }
			}
		});
		return false;
	},
	delete_member_package: function(_this){
		if(!confirm('Xóa gói này khỏi lịch sử?')) return false;
		var profile_id = $(_this).attr('data-profile_id'),
			mp_id = $(_this).attr('data-mp_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod=member&act=delete_member_package', {
			'profile_id': profile_id, 'mp_id': mp_id
		}, function(){
			vietiso_loading(0);
			$Core.member.reload_member_packages(profile_id);
		}, 'json');
		return false;
	},
	reload_member_packages: function(profile_id){
		// Trang list không có khung "Gói tài khoản" (.holder_member_packages) → reload để nhãn MF trên dòng cập nhật
		if(!$('.holder_member_packages').length){
			window.location.reload();
			return;
		}
		$.post(path_ajax_script+'/index.php?mod=member&act=load_member_packages', {'profile_id': profile_id}, function(html){
			$('.holder_member_packages').html(html);
		});
	},
	// Chọn thời hạn nhanh (1/3/6/12 tháng) → tự điền start_date/end_date + giá theo catalog gói;
	// chọn "Tự nhập ngày" thì không đụng gì
	apply_package_duration: function(_this){
		var months = parseInt($(_this).val(), 10);
		if(!months){ return; }
		var $form = $(_this).closest('form'),
			$start = $form.find('input[name="start_date"]'),
			$end = $form.find('input[name="end_date"]');
		var start = $Core.member._parse_dmy($start.val());
		if(!start){
			start = new Date();
			$start.val($Core.member._format_dmy(start));
		}
		var end = new Date(start.getTime());
		end.setMonth(end.getMonth() + months);
		$end.val($Core.member._format_dmy(end));
		$Core.member.fill_package_price($form, months);
	},
	// Đổi gói khi đã chọn thời hạn → tính lại giá theo gói mới
	package_changed: function(_this){
		var $form = $(_this).closest('form'),
			months = parseInt($form.find('select[name="duration_preset"]').val(), 10);
		if(months){ $Core.member.fill_package_price($form, months); }
	},
	// Điền "Giá đã thu" theo giá catalog của gói đang chọn + số tháng (giá 0/thiếu → để trống, admin nhập tay)
	fill_package_price: function($form, months){
		var $opt = $form.find('select[name="package_id"] option:selected');
		if(!$opt.length){ return; }
		var price = parseInt($opt.attr('data-price-' + months) || '0', 10),
			$price = $form.find('input[name="price_paid"]');
		$price.val(price > 0 ? $Core.member._format_thousand(price) : '');
	},
	_format_thousand: function(n){
		return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
	},
	_parse_dmy: function(s){
		if(!s){ return null; }
		var m = String(s).trim().match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
		if(!m){ return null; }
		var d = new Date(parseInt(m[3], 10), parseInt(m[2], 10) - 1, parseInt(m[1], 10));
		return isNaN(d.getTime()) ? null : d;
	},
	_format_dmy: function(d){
		var p = function(n){ return (n < 10 ? '0' : '') + n; };
		return p(d.getDate()) + '/' + p(d.getMonth() + 1) + '/' + d.getFullYear();
	},
	handle_role: function(_this, e){
		var gId = $(_this).attr('gId'),
			role_id = $(_this).val();
		if(role_id == _MEMBER_PARKAGE_FREE_ID){
			$('#'+gId).addClass('d-none');
		} else {
			$('#'+gId).removeClass('d-none');
		}
	},
	send_email: function(_this, e){
		e.preventDefault();
		var source = $(_this).data('source'),
			email_type = $(_this).attr('email_type'),
			profile_id = $(_this).attr('profile_id');
		$Core.alert.confirm('Thông báo', 'Bạn có chắc chắn muốn gửi e-mail cho người này?', () => {
			$Core.util.toggleIndicatior(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=send_email', {
				'profile_id' : profile_id,
				'source' : source,
				'email_type' : email_type
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
	},
	open_permiss: function(_this, e){
		var profile_id = $(_this).attr('profile_id'),
			source = $(_this).attr('data-source') || 'MOC';
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_permiss', {
			'profile_id' : profile_id,
			'source' : source
		}, function(respJson){
			$Core.popup.open('auto', 'auto', respJson.html, 'open_permiss');
		}, 'json');
	},
	storage: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			profile_id = $(_this).attr('profile_id'),
			source = $(_this).attr('data-source') || 'MOC';
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_permiss',
			data: {'profile_id':profile_id,'source':source},
			dataType:'html',
			success : function(resp){
				vietiso_loading(0);
				if(resp && resp.indexOf('_success') >= 0){
					$Core.alert.success('Đã lưu phân quyền!');
					$Core.popup.close(_form.closest('.modal'));
				} else {
					$Core.alert.error('Lưu thất bại!');
				}
			}
		});
		return false;
	},
	add_employ: function(_this, e){
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
	},
	add_point: function(_this, type){
		var _form = $(_this).closest('form'),
			profile_id = $(_this).attr('profile_id');
			vietiso_loading(1);
			var formData = new FormData();
			formData.append("profile_id",profile_id);
			formData.append("type",type);
			if(type == 'add'){
				if(_form.find('.form_field').length){
					_form.find('.form_field').each((_i, _elem) => {
						var field = $(_elem).attr('name');
						formData.append(field,$(_elem).val());
					});
				}
			}
			$.ajax({
				url: path_ajax_script+'/index.php?mod='+mod+'&act=add_point',
				method: "POST",
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				dataType: "json",
				success: function (respJson) {
					vietiso_loading(0);
					if(respJson.result){
						if(type == 'open'){
							$Core.popup.open('500','500', respJson.html, respJson.uid);
						}else{
							alertify.success(respJson.msg);
							$("#total_point_"+profile_id).text(respJson.total_point);
							$Core.popup.close(_form.closest('.modal'));
						}
					}else{
						alertify.error("ERROR!");
					}							
				}
			});
		
		return false;
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
				$Core.member.image_upload(formData,params);				
			}
		});
	},
	image_upload: function(formData, params){
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
	},
	addVideo: function(_this,profile_id){
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
			profile_id = $(_this).data("profile_id");
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=formAddField',{"type":type,"field_id":field_id,"field":field,"profile_id":profile_id}, function(respJson){
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
		var profile_id = $(_this).data("profile_id");
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
		
		console.log(formData);
		if (files && files.length) {
			formData.append('imgdata',files[0]);
			console.log(files[0]);
		}
		formData.append('type',type);
		formData.append('field_id',field_id);		
		formData.append('field',field);	
		formData.append('profile_id',profile_id);	
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
	},
	deleteField : function(_this,type){
		if(confirm("Bạn có chắc chắn muốn xoá bản ghi này?")){
			vietiso_loading(1);
			var field_id = $(_this).data("field_id"),
				field = $(_this).data("field"),
				profile_id = $(_this).data("profile_id");
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=formAddField',{"type":"delete","field_id":field_id,"field":field,"profile_id":profile_id}, function(respJson){
				vietiso_loading(0);
				if(respJson.result){
					alertify.success("Xoá thành công!");	
					$("#lst_"+field).html(respJson.html);	
				}else{
					alertify.error("ERROR !");
				}	
			}, 'json');
		}		
	},
	load_logs: function (options){
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
	},
	load_logs_point: function (options){
		var $_adata = options || {};
		$_adata['user_id'] = profile_id;
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_logs_point', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_logs_point').html(respJson.html);
		},'json');
	},
};

