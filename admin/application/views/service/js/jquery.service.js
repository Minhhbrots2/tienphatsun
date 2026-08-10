$Core.service = {
	open: function(_this, e){
		vietiso_loading(1);
		var service_id = $(_this).attr('service_id');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open', {
			'service_id' : service_id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_service');
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
							url:path_ajax_script+'/index.php?mod='+mod+'&act=search_tag',
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
	},
	select_block: function(_this, e){
		var project_id = $(_this).val(),
			toId = $(_this).attr('toId');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_block', {
			'project_id' : project_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html).trigger("chosen:updated");
			$('#slb_Building_Id').empty().trigger("chosen:updated");
			$Core.service.loadAddress();
		});
	},
	select_building: function(_this, e){
		var toId = $(_this).attr('toId');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_building', {
			'block_id' : $(_this).val()
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html).trigger("chosen:updated");
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
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			service_id = $(_this).attr('service_id'),
			$_adata = {'service_id':service_id};
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=save',
				data : $_adata,
				dataType:'json',
				success : function(respJson){
					vietiso_loading(0);
					_form.clearForm();
					_form.resetForm();
					if(respJson.msg.indexOf('_success') >= 0){
						$Core.alert.success('Thành công !');
						setTimeout(function(){
							window.location.reload(true);
						},500);
					} else {
						$Core.alert.error('Không thành công !');
					}
				}
			});	
		}
		return false;
	},
	select_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('.select_file_'+toId).trigger('click');
		return false;
	},
	select_heic_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('.select_heic_file_'+toId).trigger('click');
		return false;
	},
	upload_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('id'),
			form = $(_this).closest('form');
		vietiso_loading(1);
		form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod=docs&act=upload_file',
			dataType:'json',
			success : function(respJson){
				vietiso_loading(0);
				form.clearForm();
				form.resetForm();
				if(respJson.msg.indexOf('_success') >= 0){
					$('#content_file_'+toId).val(respJson.upload_file);
				} else {
					$Core.alert.error('Không thành công !');
				}
			}
		});	
		return false;
	},
	upload_heic_file: function(){
		
	},
	get_subcategory: function(_this, e){
		var toId = $(_this).attr('toId'),
			parent_id = $(_this).val();
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=get_subcategory', {
			'parent_id' : parent_id
		}, function(html){
			vietiso_loading(0);
			$('#'+toId).html(html).trigger("chosen:updated");
		});
	},
	check_stock_code: function(_this, e){
		var stock_code = $(_this).val();
		if(stock_code != ""){
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=check_stock_code', {
				'stock_code' : stock_code
			}, function(html){
				vietiso_loading(0);
				if(html.indexOf('_invalid') >= 0){
					$("#stock_code").focus();
					alertify.error("Mã căn không tồn tại");
				}
			});
		}
	},
	importData : function(_this,event){
		var _form = $(_this).closest('form');
		console.log('sss');
		vietiso_loading(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=import_file',
			dataType:'html',
			success : function(html){
				vietiso_loading(0);
				_form.clearForm();
				_form.resetForm();
				if(html.indexOf('_success') >= 0){
					window.location.reload();
				} else {
					$Core.alert.error('Không thành công !');
				}
			}
		});	
	}
}