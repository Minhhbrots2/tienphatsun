$().ready(function(){
	var $timer = '';
	// Page News - Mod: News - Act: Default
	if(mod == 'news' && act== 'default') {
		// Duplicate Module News
		$(document).on('click', '.ajDuplicateNews', function(ev){
			var $_this=$(this);	
			if(confirm(confirm_cloning)){	
				vietiso_loading(1);	
				$.ajax({	
					type: "POST",
					url: path_ajax_script+'/?mod='+mod+'&act=ajDuplicateNews',	
					data: {"news_id" : $_this.attr('news_id')},	
					dataType: "html",	
					success: function(html){
						vietiso_loading(0);	
						location.href = html;
					}	
				});	
			}	
			return false;	
		});
	}
	// Page News - Mod: News - Act: Edit
	if(mod == 'news' && act== 'edit') {
		/* RALATED NEWS */
		if($news_id > 0){
			loadListNewsRelated($news_id);
			$(document).on('keyup change','#searchChildNews',function(ev){
				var $_this = $(this);
				if($_this.val()!=''){
					clearTimeout($timer);	
					ajaxGlobalSearch($_this.val(), $news_id);
				}else{
					$("#quickSearchChild").html('');	
				}
			});
			$(document).on('click', '.ajvClick', function(ev){
				var $_this = $(this);
				var $_target_id = $_this.attr('target_id');
				var $_news_id = $_this.attr('news_id');
				vietiso_loading(1);
				$.ajax({
					type:'POST',	
					url:path_ajax_script+'/index.php?mod='+mod+'&act=ajaxChoiceNewsRelated',	
					data:{
						'target_id' : $_target_id,
						"news_id": $_news_id
					},	
					dataType:'html',	
					success:function(html){
						vietiso_loading(0);
						loadListNewsRelated($_target_id);
					}
				});
				return false;
			});
			$(document).on('click', '.ajv_delete', function(ev){
				var $_this = $(this);
				if(confirm(confirm_delete)){
					var $_target_id = $_this.attr('target_id');
					var $news_related_id = $_this.attr('data');
					vietiso_loading(1);
					$.ajax({
						type:'POST',	
						url:path_ajax_script+'/index.php?mod='+mod+'&act=ajaxDeleteNewsRelated',	
						data:{
							'target_id' : $_target_id,
							"news_related_id": $news_related_id
						},	
						dataType:'html',	
						success:function(html){
							loadListNewsRelated($_target_id);
							vietiso_loading(0);
						}
					});
				}
				return false;
			});
			$(document).on('click', '.ajv_move', function(ev){
				var $_this = $(this);
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url:path_ajax_script+'/index.php?mod='+mod+'&act=ajaxMoveNewsRelated',
					data: {'news_related_id': $_this.attr('data'),'direct': $_this.attr('direct')},
					dataType: "html",
					success: function(html){
						loadListNewsRelated($news_id);
						vietiso_loading(0);
					}
				});
				return false;
			});
			
		}
		/* TAG NEWS */
	}
	// Page News - Mod: News - Act: Category
	if(mod == 'news' && act== 'category') {
		/* NEWS CATEGORY */
		if($SiteHasCat_News==1){
			$(document).on('click', '.btnCreateNewsCat', function(ev){
				var $_this = $(this);
				vietiso_loading(1);
				$.ajax({
					type:'POST',
					url:path_ajax_script+'/index.php?mod='+mod+'&act=SiteNewsCategory',
					data : {'newscat_id':$_this.attr('data'),'tp':'F'},
					dataType:'html',
					success:function(html){
						vietiso_loading(0);
						makepopupnotresize('52%', 'auto', html, 'pop_NewsCategory');
						$('#pop_NewsCategory').css('top','50px');
						var $editorID = $('.textarea_intro_editor').attr('id');
						$('#'+$editorID).isoTextAreaFix();
					}
				});
				return false;
			});
			$(document).on('click', '.btnEditNewsCat', function(ev){
				var $_this = $(this);
				var $newscat_id = $_this.attr('data');
				vietiso_loading(1);
				$.ajax({
					type:'POST',
					url:path_ajax_script+'/index.php?mod='+mod+'&act=SiteNewsCategory',
					data : {'newscat_id' : $newscat_id,'tp':'F'},
					dataType:'html',
					success:function(html){
						vietiso_loading(0);
						makepopupnotresize('52%', 'auto', html, 'pop_NewsCategory');
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
				var $intro = tinyMCE.get($editorID).getContent();
				if($title.val()==''){
					$title.focus();
					alertify.error(field_is_required);
					return false;
				}
				var $_adata = {
					'title' 		: 	$title.val(),
					'intro'	  		: 	$intro,
					'newscat_id' 	: 	$_this.attr('newscat_id'),
					'parent_id' 	: 	$parentID.val(),
					'tp' 			: 	'S'
				};
				$_adata['image'] = $('#isoman_url_image').val();
				vietiso_loading(1);
				$.ajax({
					type:'POST',
					url:path_ajax_script+'/index.php?mod='+mod+'&act=SiteNewsCategory',
					data:$_adata,
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
	}
	function ajaxGlobalSearch($keyword, $news_id){
		var adata = {
			'news_id' : $news_id,
			"keyword": $keyword
		};
		vietiso_loading(1);
		$timer = setTimeout(function(){
			$.ajax({
				type:'POST',	
				url:path_ajax_script+'/index.php?mod='+mod+'&act=ajaxGlobalSearch',	
				data:adata,	
				dataType:'html',	
				success:function(html){
					$("#quickSearchChild").html(html);
					vietiso_loading(0);
				}
			});
		},500);
	}
	function loadListNewsRelated($news_id){
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajaxLoadListNewsRelated',
			data:{"news_id":$news_id},	
			dataType:'html',	
			success:function(html){
				$("#lstChildItem").html(html);
				vietiso_loading(0);
			}
		});
	}
});
$Core.news = {
	hander_post_type: function(_this, e){
		var toId = $(_this).attr('toId'),
			post_type = $(_this).val();
		if(post_type=='_whatnew'){
			$('#'+toId).removeClass('d-none');
		} else {
			$('#'+toId).addClass('d-none');
		}
	},
	loadCategory: function(_this,e) {
		e.preventDefault();
		var toId = $(_this).attr("toId"),
			domain_id = $(_this).val(),
			cat_id = $(_this).attr("cat_id");
		vietiso_loading(1);
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&act=loadCategory',
			data:{"cat_id":cat_id,"domain_id":domain_id},	
			dataType:'html',	
			success:function(html){
				vietiso_loading(0);
				$("#"+toId).html(html);	
			}
		});
		return false;
	}
}