$(document).ready(function(){
	loadListSysCategory({'type':type});
	$('#keyword').bind('keyup keydown change',function(){
		var $_this=$(this),
			$_total_rows = $('#tblHolderCategory tr').size();
		if(parseInt($_total_rows) > 1 && $_this.val() != ''){
			var $s = $_this.val();
			$("#tblHolderCategory tr").each(function(){
				$(this).text().search(new RegExp($s,"i"))<0? $(this).hide():$(this).show();
			});
		}else{
			$('#tblHolderCategory tr').each(function(){
				$(this).show();
			});
		}
	});
	$(document).on('click', '.btnCreateCategory,.btnEditCategory,.clickToSaveCategory,.btnDeleteCategory', function(ev){
		var $_this = $(this),
			cat_id = $_this.attr('cat_id');
		if($_this.hasClass('btnDeleteCategory')){
			if(confirm(confirm_delete)){
				var $_adata = {'tp' : 'D','cat_id' : cat_id};
				vietiso_loading(1);
				$.post(path_ajax_script+'/index.php?mod=category&act=ajOpenCategory', $_adata, function(){
					vietiso_loading(0);
					loadListSysCategory({'type':type});
				});
			}
		} else if($_this.hasClass('clickToSaveCategory')){
			var $_adata = {'tp':'S','type':type,'cat_id':cat_id};
			
			var _validated = 0,
				_form = $_this.closest('form');
			if($('input.required,select.required', _form).length){
				$('input.required,select.required', _form).each(function(){
					if($Core.util.isEmpty($(this).val())){
						_validated++;
						$(this).focus();
						return false;
					}
				});
			}
			
			if($('.isoTextArea', _form).length){
				$('.isoTextArea', _form).each(function(){
					var editorId = $(this).attr('id'),
						column = $(this).data('column');
					if(typeof(column) !== 'undefined'){
						$_adata[column] = $Core.util.getTextAreaContent(editorId)
					}
				});
			}
			if(_validated==0){
				vietiso_loading(1);
				_form.ajaxSubmit({
					type:'POST',
					url:path_ajax_script+'/index.php?mod=category&act=ajOpenCategory',
					data:$_adata,
					dataType:'html',
					success:function(html){
						vietiso_loading(0);
						if(html.indexOf('_SUCCESS') >= 0){
							alertify.success(insert_success);
							loadListSysCategory({'type':type});
							$Core.popup.close(_form.closest('.modal'));
						} else if(html.indexOf('_UPDATE_SUCCESS') >= 0){
							loadListSysCategory({'type':type});
							$Core.popup.close(_form.closest('.modal'));
						} else if(html.indexOf('_ERROR') >= 0){
							alertify.error(insert_error);
						} else if(html.indexOf('_EXIST') >= 0){
							alertify.error(insert_error_exist_code);
						}
					}
				});
			}
		}else{
			var cat_id = 0;
			if($_this.hasClass('btnEditCategory')){
				cat_id = $_this.attr('cat_id');
			}
			var $_adata = {
				'tp' : 'F',
				'type' : type,
				'cat_id' : cat_id
			};
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod=category&act=ajOpenCategory', $_adata, function(html){
				vietiso_loading(0);
				makepopup('800px', 'auto', html, 'OpenCategory_'+cat_id);
				$('#OpenCategory').css('top',40);
			});
		}
		return false;
	});
	$_document.on('click', '.btnUpStepTwo' ,function(){
		var _this = $(this);
		var adata = {
			'type'	: type,
			'pvalTable' : _this.attr('cat_id'),
			'direct' : _this.attr('direct')
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/?mod=category&act=moveCategoryUpDown",
			data: adata,
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				loadListSysCategory({'type':type});
			}
		});
		return false;
	});
	$_document.on('click', '.btnMoveStepOne' ,function(){
		var _this = $(this),
			adata = {
			'type'	: type,
			'pvalTable' : _this.attr('cat_id'),
			'direct' : _this.attr('direct')
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/?mod=category&act=moveCategoryTopBottom",
			data: adata,
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				loadListSysCategory({'type':type});
			}
		});
		return false;
	});
});
function loadListSysCategory(options){
	var $_adata = options || {};
	$.post(path_ajax_script+"/?mod=category&act=ajLoadListSysCategory", $_adata, function(html){
		$('#tblHolderCategory').html(html);
	});
}