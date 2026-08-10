var urls = [];
function isoman_callback(isoman_for_id) {
    let name = isoman_for_id;
    if (isoman_for_id == 'image-content') {
        name="image_gallery[]";
    }
    $(".isoman-image.isoman-checked").each(function() {        
        let isoman_url = $(this).attr("isoman_url");
        if (!urls.includes(isoman_url)) {
            urls.push(isoman_url);
            let html = `<div class="item-img" isoman_for_id="`+isoman_for_id+`" isoman_val="" isoman_name="image">
                            <img width="100" height="100" id="isoman_show_`+isoman_for_id+`" src="`+isoman_url+`">
                            <button type="button" class="btn btn-del-image" onClick="$Core.shop.del_photo(this, event)"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                            <input type="hidden" name="`+name+`" value="`+isoman_url+`">
                        </div>`;
                        console.log('isoman_for_id',isoman_for_id);
                        
            console.log('length', $("[isoman_for_id='"+isoman_for_id+"']").length);
            
            $("[isoman_for_id='"+isoman_for_id+"']").parents('.content-image').find('.img-empty').before(html);
        }
    });
}
$('body').on('mouseenter', '.js-box-select-image', function() {
    let changeEl = $(this).find('.btn-exchange');
    if (changeEl.hasClass('d-none')) {
        changeEl.removeClass('d-none');
    }
}).on('mouseleave', '.js-box-select-image', function() {
    let changeEl = $(this).find('.btn-exchange');
    if (!changeEl.hasClass('d-none')) {
        changeEl.addClass('d-none');
    }
});
$Core.shop = {
	open: function(_this, e){
		vietiso_loading(1);
		var shop_id = $(_this).attr('shop_id');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open', {
			'shop_id' : shop_id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_shop');
			if($(".input-tags").length){
				$(".input-tags").selectize({
					delimiter: ",",
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
		});
	},
	select_building: function(_this, e){
		var toId = $(_this).attr('toId');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_building', {
			'block_id' : $(_this).val()
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(html).trigger("chosen:updated");
		});
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			shop_id = $(_this).attr('shop_id'),
			$_adata = {'shop_id':shop_id};
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return flase;
				}
			});
		}
		if($('.isoTextArea', _form).length){
			$('.isoTextArea', _form).each((_i, _elem) => {
				var name = $(_elem).data('field'),
					editorId = $(_elem).attr('id');
				$_adata[name] = $Core.util.getTextAreaContent(editorId);
			})
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
						window.location.reload(true);
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
    del_photo: function(_this, e) {
        let url_image = $(_this).parents('.item-img').find('img').attr('src');
        urls = urls.filter(item => item !== url_image);
        $(_this).parents('.item-img').remove();
    },
    del_photo_menu: function(_this, e) {
        let url_image = $(_this).parents('.item-img').find('img').attr('src');
        urls = urls.filter(item => item !== url_image);
        $(_this).parents('.item-img').remove();
    },
    getForm: function(_this,e) {
        let value = $(_this).val();
        let shop_id = $(_this).data('shop-id');
        let tempate_id = $(_this).data('template-id');
        let data = {
            'setting_id':value,
            'shop_id':shop_id,
            'tempate_id':tempate_id,
        }
        $.ajax({
            url: path_ajax_script+'/index.php?mod='+mod+'&act=template',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(respJson) {
                let html = respJson.html;
                $('.js-form-template').html(html);
                console.log(respJson);
            },
            error: function(xhr, status, error) {
				console.log('Có lỗi xảy ra!!!! => ', error);
			},
            complete: function() {
                toggleIndicatior(0);
            }
        })
    },
    select_file: function(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			folder_id = $(_this).attr('folder_id');
		$('.select_file_'+toId).attr('folder_id', folder_id).trigger('click');
		return false;
	},
}