$(document).ready(function(){
	if(mod=='tour' && act == 'new'){
		$('.clickAddVideoGlobal').click(function(){
			var $_this = $(this);
			var $table_id = $_this.attr('table_id');
			var $type = $_this.attr('type');
			
			var adata = {
				'type' : $type,
				'table_id' : $table_id
			};
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=system_media&act=ajCreateVideos",
				data: adata,
				dataType: "html",
				success: function(html){
					makepopup('860px','auto',html,'cmsbox_BoxCreateVideos');
					vietiso_loading(0);
				}
			});
			return false;
		});
		$('.video_url').live('change',function(){
			var $_this = $(this);
			if($_this.val()!=''){
				var adata = {
					'video_url' : $_this.val()
				};
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/index.php?mod=system_media&act=ajGetInfoVideos",
					data:adata,
					dataType: "html",
					success: function(html){
						var htm = html.split('$$');
						$('#title').val(htm[0]);
						$('#image_src').attr('src',htm[1]);
						$('#thumb_small_src').val(htm[1]);
						$('#thumb_medium_src').val(htm[2]);
						$('#thumb_large_src').val(htm[3]);
						$('#thumb_type').live('change',function(){ $('#image_src').attr('src',htm[$(this).val()]); });
						$('#iframe').val(htm[4]);
						$('#embed_code').val(htm[5]);
						$('#video_site').val(htm[7]);
						vietiso_loading(0);
					}
				});
			}
		});
		$('#ajClickToSaveVideo').live('click',function(){
			var $_this = $(this);
			var $type = $_this.attr('type');
			var $table_id = $_this.attr('table_id');
			
			var $video_url = $('input[name=video_url]').val();
			var $video_site = $('input[name=video_site]').val();
			var $title = $('input[name=title]').val();
			var $thumb_type = $('select[name=thumb_type]').val();
			var $image = $('input[name=image]').val();
			var $thumb_small_src = $('input[name=thumb_small_src]').val();
			var $thumb_medium_src = $('input[name=thumb_medium_src]').val();
			var $thumb_large_src = $('select[input=thumb_large_src]').val();
			var $description = $('textarea[name=description]').val();
			var $iframe = $('textarea[name=iframe]').val();
			var $embed_code = $('textarea[name=embed_code]').val();
			var $video_id = $_this.attr('video_id');
			
			if($video_url=='' || $video_url=='http://'){
				$('input[name=video_url]').focus();
				alertify.error(field_is_required);
				return false;
			}
			if($title==''){
				$('input[name=title]').focus();
				alertify.error(field_is_required);
				return false;
			}
			if($description==''){
				$('textarea[name=description]').focus();
				alertify.error(field_is_required);
				return false;
			}
			if($iframe==''){
				$('textarea[name=iframe]').focus();
				alertify.error(field_is_required);
				return false;
			}
			if($embed_code==''){
				$('textarea[name=embed_code]').focus();
				alertify.error(field_is_required);
				return false;
			}
			var adata = {
				'video_id' : $video_id,
				'type' : $type,
				'table_id' : $table_id,
				'video_site' : $video_site,
				'video_url' : $video_url,
				'title' : $title,
				'thumb_type': $thumb_type,
				'image': $image,
				'thumb_small_src': $thumb_small_src,
				'thumb_medium_src': $thumb_medium_src,
				'thumb_large_src': $thumb_large_src,
				'description' : $description,
				'iframe' : $iframe,
				'embed_code' : $embed_code
			 };
			 vietiso_loading(1);
			 $.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=system_media&act=ajSubmitVideo",
				data:adata,
				dataType: "html",
				success: function(html){
					if(html.indexOf('_SUCCESS')>=0){
						$('#cmsbox_BoxCreateVideos .close_pop').trigger('click');
						alertify.success('insert_success');	
					}
					if(html.indexOf('_EXIST')>=0){
						alertify.success('exist_error');	
					}
					if(html.indexOf('_ERROR')>=0){
						alertify.success('exist_error');	
					}
					vietiso_loading(0);
				}
			});
		});
	}
	// End
});