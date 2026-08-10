$().ready(function(){
	// Load Company Information
	$('.ajLoadAddtionMeta').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadAddtionMeta', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/2,'auto',html,'frmLoadAddtionMeta');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.ajLoadCompanyInfo').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadCompanyInfo', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/1.2,'auto',html,'frmLoadCompanyInfo');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.ajLoadTripValid').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadTripValid', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/2,'auto',html,'frmLoadTripValid');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.ajLoadSocial').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadSocial', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/2,'auto',html,'frmLoadSocial');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.ajLoadConfigEmail').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadConfigEmail', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/2,'auto',html,'frmLoadConfigEmail');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.ajLoadConfigBooking').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadConfigBooking', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/2,'auto',html,'frmLoadConfigBooking');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.ajaxConfigIPMode').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajaxFrmCfgIPMode', 
			dataType:'html',
			success: function(html){
				makepopup(300,'auto',html,'frmLoadConfigBooking');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.ajSaveCfgIPMode').live('click',function(){
		var $_this = $(this);
		var $config_public_mode = $('input[name=config_public_mode]:checked').val();
		var adata = {'config_public_mode' : $config_public_mode};
		/**/
		vietiso_loading(1);
		$.ajax({
			type:'POST',	
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajaxSaveCfgIPMode',
			data:adata,	
			dataType:'html',	
			success:function(html){
				alertify.success('Save setting success!');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.ajLoadConfigSmtp').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajaxFrmConfigSmtp', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/2,'auto',html,'frmLoadConfigSmtp');
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.ajaxSaveFrmConfigSmtp').live('click',function(){
		var $_this = $(this);
		var $_form = $_this.closest('form');
		var $config_email_smtp = $_form.find('input[name=config_email_smtp]');
		var $config_password_smtp = $_form.find('input[name=config_password_smtp]');
		
		if($config_email_smtp.val()==''){
			alertify.error('Error !');
			$config_email_smtp.focus();
			return false;
		}
		if($config_password_smtp.val()==''){
			alertify.error('Error !');
			$config_password_smtp.focus();
			return false;
		}
		/**/
		var adata = {
			'config_email_smtp' : $config_email_smtp.val(),
			'config_password_smtp' : $config_password_smtp.val()
		};
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajaxSaveFrmConfigSmtp', 
			data:adata,
			dataType:'html',
			success: function(html){
				vietiso_loading(0);
				$_this.closest('.frmPop').find('.close_pop').trigger('click');
			}
		});
		return false;
	});
	$('.ajSubmitSetting').live('click',function(){
		var _this = $(this);
		var type = _this.attr('data');
		if(type == 'CompanyInfo') {
			var title=$('#title').val();
			var address=$('#address').val();
			var email=$('#email').val();
			if(title=='' || title==0){
				alertify.error('Bạn phải nhập tên công ty !');
				$('#title').focus().addClass('error');
				return false;
			}
			if(address=='' || address==0){
				alertify.error('Bạn phải nhập địa chỉ công ty !');
				$('#address').focus().addClass('error');
				return false;
			}
			if(email=='' || email==0){
				alertify.error('Bạn phải nhập email công ty !');
				$('#email').focus().addClass('error');
				return false;
			}
		}
		$('#frmSettingInfo').ajaxSubmit({
			type:'POST',	
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajSubmitSaveSetting',
			dataType:'html',	
			success:function(html){
				var htm = html.replace(' ','');
				if(htm=='_SUCCESS'){
					if(type=='CompanyInfo'){loadCompanyInfo();}
					alertify.success('Save setting success!');
					_this.closest('.frmPop').find('.close_pop').click();
				}
			}
		});
		return false;
	});
	// Load Support Online
	$('.ajLoadSupportOnline').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadSupportOnline', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/2,'auto',html,'frmLoadSupportOnline');
				$('#frmLoadSupportOnline').css('top','120px');
				vietiso_loading(0);
				loadOnlineSupport();
			}
		});
		return false;
	});
	$('.clickAddOnlineSupport').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajFormAddOnlineSupport', 
			dataType:'html',
			success: function(html){
				vietiso_loading(0);
				makepopup($(window).width()/3,'',html,'frmAddNewOnlineSupport');
			}
		});
	});
	$('.submitSupportClick').live('click',function(){
		var _this = $(this);
		if($('#support_type').val()==''){
			$('#support_type').focus().addClass('error');
			alertify.error('Bạn chưa chọn loại hỗ trợ !');
			return false;
		}
		if($('#support_title').val()==''){
			$('#support_title').focus().addClass('error');
			alertify.error('Bạn chưa nhập tiêu đề hỗ trợ !');
			return false;
		}
		if($('#support_nick').val()==''){
			$('#support_nick').focus().addClass('error');
			alertify.error('Bạn chưa nhập nick hỗ trợ');
			return false;
		}
		var adata = {
			'online_support_id' : _this.attr('online_support_id'),
			'type'				: $('#support_type').val(),
			'title'				: $('#support_title').val(),
			'nick'				: $('#support_nick').val(),
		};
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajSubmitOnlineSupport', 
			data: adata,
			dataType:'html',
			success: function(html){
				var htm = html.replace(' ','');
				if(htm=='_SUCCESS'){
					loadOnlineSupport();
					alertify.success('Thêm thành công !');
					_this.closest('.frmPop').find('.close_pop').click();
				}
				if(htm=='_UPDATE_SUCCESS'){
					loadOnlineSupport();
					alertify.success('Cập nhật thành công !');
					_this.closest('.frmPop').find('.close_pop').click();
				}
				if(htm=='EXIST'){
					alertify.success('Đã tồn tại !');
				}
				if(htm=='ERROR'){
					alertify.success('Lỗi !');
				}
				vietiso_loading(0);
			}
		});
	});
	$('.clickToEditSupport').live('click',function(){
		var _this=$(this);
		var adata={
			"online_support_id"	:	_this.attr('data')
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadFormEditSupport',
			data: adata,
			dataType: 'html',
			success: function(html){
				vietiso_loading(0);
				 makepopup($(window).width()/3,'',html,'frmFormEditSupport');
			}
		});
		return false;
	});
	$('.clickToMoveSupport').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		 $.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajMoveOnlineSupport', 
			data: {
				'online_support_id':_this.attr('data'),
				'direct':_this.attr('direct')
			},
			dataType:'html',
			success: function(html){
				loadOnlineSupport();
				vietiso_loading(0);
			}
		});
		return false;
	});
	$('.clickToDeleteSupport').live('click',function(){
		if(confirm('Bạn chắc chắn muốn xóa ?')){
			var _this = $(this);
			$('#ajax-indicator').show();
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=ajDeleteOnlineSupport', 
				data: {
					'online_support_id':_this.attr('data')
				},
				dataType:'html',
				success: function(html){
				   loadOnlineSupport();
				}
			});
		}
		return false;
	});
	// Load Config Images
	$('.ajLoadConfigImages').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajShowConfigImages', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/1.6,'auto',html,'frmLoadConfigImages');
				$('#frmLoadConfigImages').css('top','40px');
				vietiso_loading(0);
				loadConfigImages();
			}
		});
		return false;
	});
	$('.clickAddConfigImages').live('click',function(){
		var _this = $(this);
		vietiso_loading(1);
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadNewConfigImages', 
			dataType:'html',
			success: function(html){
				makepopup($(window).width()/2,'',html,'frmAddConfigImages');
				vietiso_loading(0);
			}
		});
	});
	
	$('#submitConfigImages').live('click',function(){
		var _this = $(this);
		$('#ajax-indicator').show();
		if($('#title').val()==''){
			$('#title').focus().addClass('error');
			alertify.error('Bạn chưa nhập tiêu đề !');
			return false;
		}
		if($('#type').val()==''){
			$('#type').focus().addClass('error');
			alertify.error('Bạn chưa nhập thể loại !');
			return false;
		}
		if($('#width').val()==''){
			$('#width').focus().addClass('error');
			alertify.error('Bạn chưa nhập chiều rộng ảnh !');
			return false;
		}
		if($('#height').val()==''){
			$('#height').focus().addClass('error');
			alertify.error('Bạn chưa nhập chiều dài ảnh');
			return false;
		}
		if($('#dimension').val()==''){
			$('#dimension').focus().addClass('error');
			alertify.error('Bạn chưa chọn tỉ lệ ảnh');
			return false;
		}
		var adata = {
			'config_images_id' 	: _this.attr('config_images_id'),
			'type'				: $('#type').val(),
			'title'				: $('#title').val(),
			'parent_id'			: $('#parent_id').val(),
			'dimension'			: $('#dimension').val(),
			'width'				: $('#width').val(),
			'height'			: $('#height').val(),
		};
		$.ajax({
			type: 'POST',
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajSubmitConfigImages', 
			data: adata,
			dataType:'html',
			success: function(html){
				var htm = html.replace(' ','');
				if(htm=='_SUCCESS'){
					alertify.success('Thêm thành công !');
				}
				if(htm=='_UPDATE_SUCCESS'){
					alertify.success('Cập nhật thành công !');
				}
				if(htm=='_EXIST'){
					alertify.error('Đã tồn tại !');
				}
				if(htm=='_ERROR'){
					alertify.success('Lỗi !');
				}
				loadConfigImages();
				_this.closest('.frmPop').find('.close_pop').click();
			}
		});
	});
	$('.clickToEditConfigImages').live('click',function(){
		var _this=$(this);
		var adata={
			"config_images_id"	:	_this.attr('data')
		};
		$('#ajax-indicator').show();
		$.ajax({
			type: "POST",
			url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadFormEditConfigImages',
			data: adata,
			dataType: 'html',
			success: function(html){
				 $('#ajax-indicator').hide(); 
				 makepopup($(window).width()/3,'',html,'frmFormEditConfigImages');
			}
		});
		return false;
	});
	
	$('.clickToDeleteConfigImages').live('click',function(){
		if(confirm('Bạn chắc chắn muốn xóa ?')){
			var _this = $(this);
			$('#ajax-indicator').show();
			$.ajax({
				type: 'POST',
				url: path_ajax_script+'/index.php?mod='+mod+'&act=ajDeleteConfigImages', 
				data: {
					'config_images_id':_this.attr('data')
				},
				dataType:'html',
				success: function(html){
					var htm = html.replace(' ','');
					if(htm=='_SUCCESS'){
						alertify.success('Xóa thành công !');
					}
					if(htm=='_ERROR') {
						alertify.error('Không thể xóa vì còn danh mục con !');
					}
				   loadConfigImages();
				}
			});
		}
		return false;
	});
});
function loadCompanyInfo() {
	$.ajax({
		type:'POST',	
		url:path_ajax_script+'/index.php?mod='+mod+'&act=ajShowCompanyInfo',
		dataType:'html',	
		success:function(html){
			$('.loadCompanyInfo').html(html);
		}
	});
}
function loadOnlineSupport(){
	$.ajax({
		type: 'POST',
		url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadOnlineSupport', 
		dataType:'html',
		success: function(html){
			$('#tblOnlineSupport').html(html);
		}
	});
}
function loadConfigImages(){
	$.ajax({
		type: 'POST',
		url: path_ajax_script+'/index.php?mod='+mod+'&act=ajLoadConfigImages', 
		dataType:'html',
		success: function(html){
			$('#tblConfigImages').html(html);
		}
	});
}