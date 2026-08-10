function gen_alias(_this){
	var _title = $(_this).val(),
		$_adata = {'title':_title};
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=gen_alias', $_adata, function(html){
		$('.ipn__alias-menu').val(html);
	});
}
function add_menu(_this){
	var menu_id = $(_this).attr('menu_id'),
		$_adata = {'menu_id':menu_id, 'order_no':$('.link-list-group-item').size()};
	$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_menu', $_adata, function(html){
		$('.ui-empty-state').addClass('hide');
		$('ul.link-list-group').append(html);
		
	});
	return false;
}
function remove_link(_this){
	$(_this).closest('.link-list-group-item').remove();
	if($('.link-list-group-item').length){
		resort_menu_links();
	}
	if($('.link-list-group-item').length==0){
		$('.ui-empty-state').removeClass('hidden');
	}
}
function resort_menu_links(){
	$('.link-list-group-item').each(function(i){
		var __this = $(this),
			uid = __this.attr('uid');
		$('#'+uid+'_id', __this).attr('name','links['+i+'][id]');
		$('#'+uid+'_order_no', __this).attr('name','links['+i+'][order_no]').val(i);
		$('#'+uid+'_title', __this).attr('name','links['+i+'][title]');
		$('#'+uid+'_type', __this).attr('name','links['+i+'][type]');
		$('#'+uid+'_url', __this).attr('name','links['+i+'][url]');
	});
}
function handler_linktype_change(_this){
	var holderG = $(_this).val(),
		uid = $(_this).attr('uid'),
		order_no = $(_this).attr('order_no'),
		alias = $(_this).find('option:selected').attr('alias')

	$('#'+uid+'_alias').val(alias);
	if($.inArray(holderG, ['frontpage','catalog','search','newsall','projectall','serviceall']) == -1){
		var $_adata = {'uid':uid, 'holderG':holderG, 'order_no':order_no};
		$('#'+uid).html('<label class="col-form-label">Loading...</label>');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_handler_linktype', $_adata, function(respJson){
			vietiso_loading(0);
			$('#'+uid).html(respJson.html);
			load_list_search(respJson.url, {'uid':uid}, $('#'+uid).find('.build'));
			$('div.dropdown.mega-dropdown a').on('click', function (event) {
				$(this).parent().toggleClass('open');
			});
			$('body').on('click',function(e){
				if(!$('.dropdown.mega-dropdown').is(e.target) 
				   && $('.dropdown.mega-dropdown').has(e.target).length===0 
				   && $('.open.mega-dropdown').has(e.target).length===0){
					$('.dropdown.mega-dropdown').removeClass('open');
				}else{
					e.stopPropagation();
				}
			});
		},'json');
	} else {
		$('#'+uid).empty();
	}
}
function add_link(_this, pval_id, title, alias){
	var uid = $(_this).attr('uid');
	$('#'+uid+'_id').val(pval_id);
	$('#'+uid+'_alias').val(alias);
	$('#'+uid+'_dropdown').removeClass('open').find('.choosed-single').text(title);
	return false;
}
function load_list_search(url, options, container){
	var $_adata = options || {};
	$.post(url, $_adata, function(respJson){
		container.html(respJson.html)
	},'json');
}
$().ready(function(){
	$_document.on('keyup', '.input-search', $Core.util.delay(function(ev){
		var uid = $(this).data('uid'),
			url = $(this).data('url'),
			$_adata = {'uid':uid, 'keysearch':$(this).val()};
		
		load_list_search(url, $_adata, $('#'+uid).find('.build'));
	},500));
	
	$('div.dropdown.mega-dropdown a').on('click', function (event) {
		$(this).parent().toggleClass('open');
	});
	$('body').on('click',function(e){
		if(!$('.dropdown.mega-dropdown').is(e.target) 
		   && $('.dropdown.mega-dropdown').has(e.target).length===0 
		   && $('.open.mega-dropdown').has(e.target).length===0){
			$('.dropdown.mega-dropdown').removeClass('open');
		}else{
			e.stopPropagation();
		}
	});
	if($('.build:not(.ajax)').length){
		$('.build:not(.ajax)').each(function(){
			var _this = $(this),
				_url = _this.attr('url'),
				_uid = $(this).attr('uid');
			load_list_search(_url, {'uid':_uid}, _this);
		});
	}
});