$().ready(function(){
	// Page Faqs - Mod: Faqs - Act: Edit
	if(mod == 'faqs' && act== 'edit') {
		/* TAG FAQS */
		if($SiteHasTags_Faqs==1){
			$("#txtTag").autocomplete(availableTags, {
				minChars: 1,
				width: 200,
				matchContains: true,
				autoFill: false,
				formatItem: function(row, i, max) {
					return row.name;
				},
				formatResult: function(row) {
					return row.val;
				}
			});
			$('#txtTag').keypress(function(e) {
				var key;
				if (window.event)
					key = window.event.keyCode;
				else
					key = e.which;
				if (key == 13) {
					$('#addTag').trigger('click');
				}
			});
			$(document).on('click', '#addTag', function(ev){
				var newval = $('#txtTag').val();
				if (newval != '') {
					vietiso_loading(1);
					$.ajax({
						type: "POST",
						url: path_ajax_script+'/index.php?mod='+mod+'&act=ajSiteFaqsTags',
						data: {"for_id": pvalTable,"val": $('#txtTag').val(),"type": $type,'tp': 'S'},
						dataType: "html",
						success: function(html) {
							vietiso_loading(0);
							if(html.indexOf('_EXIST') >= 0) {
								alertify.error(exist_error);
							} else {
								$('#listTag').append(html);
								$('#txtTag').val('').focus();
							}
						}
					});
					return false;
				}
			});
			$(document).on('click', '.closeTag', function(ev){
				if(confirm(confirm_delete)){
					var $_this = $(this);
					var id = $_this.attr('id');
					var sp = id.split('-');
					var tag_module_id = sp[1];
					vietiso_loading(1);
					$.ajax({
						type: "POST",
						url: path_ajax_script + '/index.php?mod='+mod+'&act=ajSiteFaqsTags',
						data: {"tag_module_id": tag_module_id,'tp': 'D'},
						dataType: "html",
						success: function(html) {
							vietiso_loading(0);
							$_this.parent().remove();
						}
					});
				}
				return false;
			});
			function stopRKey(evt) {
				var evt = (evt) ? evt : ((event) ? event : null);
				var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
				if ((evt.keyCode == 13) && (node.type == "text")) {
					return false;
				}
			}
			document.onkeypress = stopRKey;
		}
	}
	
	// Page Faqs - Mod: Faqs - Act: Category
	if(mod == 'faqs' && act== 'cat') {
		/* FAQS CATEGORY */
		if($SiteHasCat_FAQ==1){
			$(document).on('click', '.btnCreateFaqsCat', function(ev){
				var $_this = $(this);
				vietiso_loading(1);
				$.ajax({
					type:'POST',
					url:path_ajax_script+'/index.php?mod='+mod+'&act=SiteFaqsCategory',
					data : {'faqcat_id':$_this.attr('data'),'tp':'F'},
					dataType:'html',
					success:function(html){
						vietiso_loading(0);
						makepopupnotresize('52%', 'auto', html, 'box_CreateFAQCat');
						$('#box_CreateFAQCat').css('top','50px');
						var $editorID = $('.textarea_intro_editor').attr('id');
						$('#'+$editorID).isoTextAreaFix();
					}
				});
				return false;
			});
			$(document).on('click', '.btnEditFaqsCategory', function(ev){
				var $_this = $(this);
				vietiso_loading(1);
				$.ajax({
					type:'POST',
					url:path_ajax_script+'/index.php?mod='+mod+'&act=SiteFaqsCategory',
					data : {'faqcat_id':$_this.attr('data'),'tp':'F'},
					dataType:'html',
					success:function(html){
						vietiso_loading(0);
						makepopupnotresize('52%', 'auto', html, 'box_EditFaqsCat');
						$('#box_EditFaqsCat').css('top','50px');
						var $editorID = $('.textarea_intro_editor').attr('id');
						$('#'+$editorID).isoTextAreaFix();
					}
				});
				return false;
			});
			$('.btnSubmitFaqsCategory').live('click',function(){
				var $_this = $(this);
				var $_form = $_this.closest('.frmPop');
				var $title = $_form.find('input[name=title]');
				var $editorID = $('.textarea_intro_editor').attr('id');
				var $intro = tinyMCE.get($editorID).getContent();
				
				if($title.val()==''){
					$title.focus();
					alertify.error(field_is_required);
					return false;
				}
				var adata = {
					'title' 		: 	$title.val(),
					'intro'	  		: 	$intro,
					'faqcat_id' 	: 	$_this.attr('faqcat_id'),
					'tp' 			: 	'S'
				};
				vietiso_loading(1);
				$.ajax({
					type:'POST',
					url:path_ajax_script+'/index.php?mod='+mod+'&act=SiteFaqsCategory',
					data:adata,
					dataType:'html',
					success:function(html){
						vietiso_loading(0);
						if(html.indexOf('_SUCCESS') >= 0){
							window.location.reload(true);
						}else if(html.indexOf('_ERROR') >= 0){
							alertify.error(insert_error);
						}else{
							alertify.error(exist_error);
						}
					}
				});
			});
		}
	}
});