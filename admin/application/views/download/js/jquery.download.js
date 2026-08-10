$().ready(function(){
	// Page News - Mod: News - Act: Category
	if(mod == 'download' && act== 'category') {
		/* NEWS CATEGORY */
		$(document).on('click', '.btnCreateNewsCat', function(ev){
			var $_this = $(this);
			vietiso_loading(1);
			$.ajax({
				type:'POST',
				url:path_ajax_script+'/index.php?mod='+mod+'&act=SiteNewsCategory',
				data : {'downloadcat_id':$_this.attr('data'),'tp':'F'},
				dataType:'html',
				success:function(html){
					vietiso_loading(0);
					makepopupnotresize('32%', 'auto', html, 'pop_NewsCategory');
					$('#pop_NewsCategory').css('top','50px');
					var $editorID = $('.textarea_intro_editor').attr('id');
					$('#'+$editorID).isoTextAreaFix();
				}
			});
			return false;
		});
		$(document).on('click', '.btnEditNewsCat', function(ev){
			var $_this = $(this);
			var $downloadcat_id = $_this.attr('data');
			vietiso_loading(1);
			$.ajax({
				type:'POST',
				url:path_ajax_script+'/index.php?mod='+mod+'&act=SiteNewsCategory',
				data : {'downloadcat_id' : $downloadcat_id,'tp':'F'},
				dataType:'html',
				success:function(html){
					vietiso_loading(0);
					makepopupnotresize('32%', 'auto', html, 'pop_NewsCategory');
					$('#pop_NewsCategory').css('top','50px');
					var $editorID = $('.textarea_intro_editor').attr('id');
					$('#'+$editorID).isoTextAreaFix();
				}
			});
			return false;
		});
		$(document).on('click', '.ClickSubmitCategory', function(ev){
			var $_this = $(this);
			var $_form = $_this.closest('.frmPop');
			var $title = $_form.find('input[name=title]');
			var $parentID = $_form.find('select[name=parent_id]');
			var $editorID = $('.textarea_intro_editor').attr('id');
			if($title.val()==''){
				$title.focus();
				alertify.error(field_is_required);
				return false;
			}
			var adata = {
				'title' 		: 	$title.val(),
				'downloadcat_id' 	: 	$_this.attr('downloadcat_id'),
				'parent_id' 	: 	$parentID.val(),
				'tp' 			: 	'S'
			};
			vietiso_loading(1);
			$.ajax({
				type:'POST',
				url:path_ajax_script+'/index.php?mod='+mod+'&act=SiteNewsCategory',
				data:adata,
				dataType:'html',
				success:function(html){
					if(html.indexOf('_SUCCESS') >= 0){
						window.location.reload(true);
					}
					if(html.indexOf('_ERROR') >= 0){
						alertify.error(insert_error);
					}
					if(html.indexOf('_EXIST') >= 0){
						alertify.error(exist_error);
					}
					vietiso_loading(0);
				}
			});
			return false;
		});
	}
});;