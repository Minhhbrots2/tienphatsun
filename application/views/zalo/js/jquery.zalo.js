$(function(){
	var alreadyScroll = 0;
	$(window).scroll(function(){
		if(isScrolledIntoView('#showmorethisresult') && $Core.tool.scrolled == 0){
			$Core.tool.scrolled = 1;
			$('.showmorethisresult').removeClass('d-none').trigger('click');
		}
	});
});
$Core.zalo = $.extend($Core.global.zalo, {
	scrolled : !1,
	manager_group: (_this, e) => {
		e.preventDefault();
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=manager_group', {}, function(respJson){
			var _www = $(window).width(),
				_mimus = _www > 1360 ? _www*3/4 : _www*2/3;
			$Core.popup.openfull(_mimus, respJson.html, respJson.uid);
			$Core.zalo.load_manager_group({});
		}, 'json');
		return false;
	}, open_msg: (_this, e) => {
		e.preventDefault();
		var send_type = $(_this).attr('send_type'),
			msg_id = $(_this).getAttr('msg_id', 0);
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_msg', {
			'send_type' : send_type,
			'msg_id' : msg_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, load_send_recipients: (_this, e) => {
		var uid = $(_this).attr('uid'),
			send_type = $(_this).val(),
			msg_id = $(_this).attr('msg_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_send_recipients', {
			'uid' : uid,
			'msg_id' : msg_id,
			'send_type' : send_type,
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(`.holder_recipients_${uid}`).html(respJson.html);
		}, 'json');
	}, save_msg: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			msg_id = $(_this).getAttr('msg_id', 0);
		if($('input.required', _form).length){
			$('input.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&act=save_msg',
				data: {'msg_id':msg_id},
				success: function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$('.btn-close', _form).trigger('click');
						$Core.swal.success("Thông báo", "Đăng ký lịch thành công!");
						$Core.zalo.load_msg({});
					} else if(html.indexOf('_duplicated') >= 0){
						$Core.swal.error("Thông báo", "Đăng ký bị trùng với cuộc họp khác!");
					} else if(html.indexOf('_invalid') >= 0){
						$Core.swal.error("Thông báo", "Đăng ký thời gian không hợp lệ!");
					}
				}
			});
		}
		return false;
	}, select_file: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid');
		$(`.select_file_${uid}`).trigger('click');
		return false;
	}, do_upload: function(_this, e){
		$Core.util.toggleIndicatior(1);
		var _uid = $(_this).attr('uid'),
			_form = $(_this).closest('form');
		_form.ajaxSubmit({
			method: "POST",
			url: `${PCMS_URL}/index.php?mod=${MOD}&act=upload_image`,
			dataType : 'json',
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				if(respJson.msg.indexOf('_success') >= 0){
					if($(`.imageList_${_uid} .item`).length){
						$(`.imageList_${_uid} .item:last`).after(respJson.html);
					} else {
						$(`.imageList_${_uid}`).html(respJson.html);
					}
				}
			}
		});
	}, delete_image: (_this, e) => {
		e.preventDefault();
		$Core.messager.confirm('Message', 'Bạn chắc chắn muốn xóa hình ảnh này', function(){
			$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=delete_image`, {
				'src' : $(_this).attr('src')
			}, function(){
				$(_this).closest('.item').remove();
			});
		});
		return false;
	}, sw_status_repeat: (_this, e) => {
		var gId = $(_this).attr('gId'),
			leftHeight = $('.leftCol').height() + 30;
		// console.log(leftHeight);
		// $('.rightCol').css({'height':leftHeight, 'max-height': leftHeight});
		if($(_this).val() == '_repeat'){
			$(`.${gId}`).removeClass('d-none');
		} else {
			$(`.${gId}`).addClass('d-none');
		}
	}, load_msg: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_msg', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_message').html(respJson.html);
			$('.total_results').text(respJson.total_record);
		}, 'json');
	}, do_send_msg : (_this, e) => {
		e.preventDefault();
		var msg_id = $(_this).attr('msg_id');
		$Core.swal.confirm("Thông báo", "Bạn có chắc chắn muốn thực hiện", () => {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=do_send_msg', {
				'msg_id' : msg_id
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$(`.status_${msg_id}`).html(respJson.html_status);
			}, 'json');
		});
		return false;
	}, set_status_msg: (_this, e) => {
		e.preventDefault();
		var msg_id = $(_this).attr('msg_id');
		$Core.swal.confirm("Thông báo", "Bạn có chắc chắn muốn thực hiện", () => {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=set_status_msg', {
				'msg_id' : msg_id
			}, function(respJson){
				if(respJson.msg.indexOf('_success') >= 0){
					$Core.util.toggleIndicatior(0);
					$(_this).replaceWith(respJson.html);
					$(`.status_${msg_id}`).html(respJson.html_status);
				} else {
					$Core.swal.error('Thông báo', 'Đã xảy ra lỗi!');
				}
			}, 'json');
		});
		return false;
	}, delete_msg: (_this, e) => {
		e.preventDefault();
		var msg_id = $(_this).attr('msg_id');
		$Core.swal.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function(){
			$.post(`${PCMS_URL}/index.php?mod=${MOD}&act=delete_msg`, {
				'msg_id' : msg_id,
			}, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.global.zalo.load_msg({});
			});
		});
		return false;
	}, load_chatlogs: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_chatlogs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_chatlogs').html(respJson.html);
			setTimeout(() => {
				// $Core.zalo.load_chatlogs({});
			}, 10000);
		}, 'json');
	}, reload: (_this, e) => {
		e.preventDefault();
		$Core.zalo.load_chatlogs({});
		return false;
	}, block_group: (_this, e) => {
		e.preventDefault();
		var group_id = $(_this).attr('group_id'),
			id_group = $(_this).attr('id_group');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=block_group', {
			'group_id' : group_id,
			'id_group' : id_group,
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$Core.zalo.load_chatlogs({});
		});
		return false;
	}, delete_chatlogs: (_this, e) => {
		e.preventDefault();
		$Core.messager.confirm("Thông báo", "Bạn chắc chắn muốn xóa", () => {
			var chatlog_id = $(_this).attr('chatlog_id');
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=delete_chatlogs', {
				'chatlog_id' : chatlog_id
			}, function(html){
				$Core.util.toggleIndicatior(0);
				$Core.zalo.load_chatlogs({});
			});
		});
		return false;
	}, load_zalo_group: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_zalo_group', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_zalo_group').html(respJson.html);
		}, 'json');
	}, open_group: (_this, e) => {
		e.preventDefault();
		var group_id = $(_this).getAttr('group_id', ""),
			id_group = $(_this).attr('id_group'),
			name_group = $(_this).attr('name_group'),
			$_adata = {
				'group_id':group_id,
				'id_group':id_group,
				'name_group':name_group
			};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=open_group', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, save_group: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			group_id = $(_this).attr('group_id');
		if($('input.required', _form).length){
			$('input.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/index.php?mod='+MOD+'&act=save_group',
				data: {'group_id':group_id},
				success: function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$('.btn-close', _form).trigger('click');
						$Core.swal.success("Thông báo", "Đăng ký lịch thành công!");
						$Core.tool.load_zalo_group({});
					} else if(html.indexOf('_duplicated') >= 0){
						$Core.swal.error("Thông báo", "Đăng ký bị trùng với cuộc họp khác!");
					} else if(html.indexOf('_invalid') >= 0){
						$Core.swal.error("Thông báo", "Đăng ký thời gian không hợp lệ!");
					}
				}
			});
		}
		return false;
	},
});
