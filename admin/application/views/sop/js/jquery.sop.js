$(function(){
	setTimeout(() => {
		if($('#'+'tableCall').length){
			var __www = $('#'+'tableCall').outerWidth(false);
			$('#'+'tableCall').width(__www+200);
			$('.freeze-table').freezeTable({
				'columnNum': 4,
				'scrollable': true,
				'columnKeep': false,
			});
		}
	},1000);
	$_document.on('change', '.checkAll:checkbox,.checkitem:checkbox', function(){
		var _this = $(this), _total_checked = 0;
		if(_this.hasClass('checkAll')){
			if(_this.is(':checked')){
				$('#tableCall .checkitem:checkbox').prop('checked', true);
				_total_checked = $('#tableCall .checkitem:checked').length;
			} else {
				$('#tableCall .checkitem:checkbox').prop('checked', false);
			}
		} else {
			var _checkall = true;
			$('#tableCall .checkitem:checkbox').each((_i, _elem) => {
				if($(_elem).is(':checked')){
					_total_checked++;
				} else {
					_checkall = false;
				}
			});
			$
			$('.checkAll:checkbox').prop('checked', _checkall);
		}
		$('.total_selected').text(_total_checked);
		if(_total_checked > 0) {
			$('.do_action').removeClass('d-none');
		} else {
			$('.do_action').addClass('d-none');
		}	
	});
	$(document).on('click','[data-bs-dismiss="modal"]',function(){
		console.log('sdfdf');
//		$(this).closest('.modal').fadeOut();
	});
	
});
$Core.sop = {
	open_sop: function(_this, e){
		e.preventDefault();
		var sop_id = $(_this).getAttr('sop_id', 0),
			stock_id = $(_this).getAttr('stock_id', 0);
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open', {
			'sop_id' : sop_id,
			'stock_id' : stock_id
		}, function(respJson){
			console.log(respJson); 
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			if($('.slideshow').length){
				$('.slideshow').owlCarousel({
					loop:false,
					nav: false,
					lazyLoad:false,
					dots:true,
					margin:3,
					autoplay:false,
					responsiveClass:true,
					responsive:{
						0:{items:1},
						1200:{items:1}, 
					}
				});
			}
		}, 'json');
		return false;
	},
	approve_sop: function (_this, e){
		e.preventDefault(); 
		var action = $(_this).data('action'),
			_total_checked = 0, sop_ids = [], $_adata={}, notes='';
		$_adata['action'] = action;
		if($(_this).hasClass('disabled')){
			return false;
		}
		if(action == "approve_multi"){
			if($('#tableCall .sop_item:checked').length){
				$('#tableCall .sop_item:checked').each((_i, _elem) => { 
					_total_checked++;
					sop_ids.push($(_elem).val()); 
				});			
			}
			$_adata['sop_id'] = sop_ids;
		}else if(action == "noapprove_multi" || action == "noapprove"){
			_total_checked = 1;
			var _form = $(_this).closest('form');
			sop_ids = $("input[name='sop_ids']", _form).val(); 
			notes = $("textarea[name=notes]", _form).val();
			$_adata['notes'] = notes;
			$_adata['sop_id'] = sop_ids;
		}else{
			_total_checked = 1;
			sop_id = $(_this).data('sop_id');
			$_adata['sop_id'] = sop_id;
		}
		if(_total_checked > 0){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=approve_sop', $_adata, function(html){
				if(html.indexOf('_success') >= 0){
					alertify.success("Success !");
					setTimeout(function(){
						window.location.reload();
					},500)
				}else{
					alertify.error("Error !");
				}
			});
		} else {
			$Core.alert.error("Bạn chưa chọn danh sách bảng hàng chuyển nhượng !!!");
		}
		return false;
	},
	show_notes: function (_this,e){
		e.preventDefault(); 
		var action = $(_this).data('action'),
			_total_checked = 0, sop_ids = [];
		if($(_this).hasClass('disabled')) {
			return false;
		}
		if(action == "noapprove_multi"){
			if($('.sop_item:checked').length){
				$('.sop_item:checked').each((_i, _elem) => { 
					_total_checked++;
					sop_ids.push($(_elem).val()); 
				});
			}
		}else{
			_total_checked = 1;
			sop_ids = $(_this).data('sop_id');
		}
		if(_total_checked > 0){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=show_notes', { 
				'action':action,
				'sop_id' : sop_ids
			}, function(html){
				if(html != ''){
					$Core.popup.open('auto', 'auto', html, 'open_notes');
				}
			});
		} else {
			$Core.alert.error("Bạn chưa chọn danh sách bảng hàng chuyển nhượng !!!");
		}
	},	
	verified: function(_this, e){
		var sop_id = $(_this).attr('sop_id'),
			is_verified = $(_this).is(':checked') ? 1: 0;
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=verified', {
			'sop_id' : sop_id,
			'is_verified' : is_verified
		}, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_success') >= 0){
				if(is_verified == 1){
					$Core.alert.success('Xác minh thành công!');	
				}else{
					$Core.alert.success('Hủy xác minh thành công!');
				}
			} else {
				$Core.alert.success('Xác minh thất bại!');
			}
		});
	},
};

function delete_sop(_this, e){
	e.preventDefault(); 
	var sop_id = $(_this).data('sop_id');
	if (confirm('Bạn có chắc chắn muốn xoá?')) {
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete_sop', {
		'sop_id' : sop_id
	}, function(html){
		if(html.indexOf('_success') >= 0){
			alertify.success("Success !");
			setTimeout(function(){
				window.location.reload();
			},1000)
		}else{
			alertify.error("Error !");
		}
	});
	}
	
	return false;
}