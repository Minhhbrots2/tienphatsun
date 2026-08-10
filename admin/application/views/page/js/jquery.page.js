$().ready(function(){
	var $timer = '';
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
$Core.loan_interest = {
	add_item_loan_interest : function (_this,e) {
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&act=add_item_loan_interest',
			data:{},	
			dataType:'html',	
			success:function(html){
				$("#list_loan_interest").append(html);
				vietiso_loading(0);
			}
		});
	},	
	setTitleItem: function (_this,e) {
		e.preventDefault();
		var bank_name = $(_this).val();
		$(_this).closest(".item_bank").find(".title_item").text(bank_name);
	},
	delete_item_bank: function (_this,e) {
		e.preventDefault();
		if(confirm("Bạn có chắc chắn muốn xoá thông tin lãi suất ngân hàng này?")) {
			$(_this).closest(".item_bank").remove();
		}
	}
}
$Core.performance = {
	add_item_performance : function (_this,e) {
		e.preventDefault();
		var type = $(_this).data("type"),
			property= $(_this).data("property"),
			uid1 = $(_this).data("uid1");
		$.ajax({
			type:'POST',
			url:path_ajax_script+'/index.php?mod='+mod+'&act=add_item_performance',
			data:{type:type,property:property,uid1:uid1},	
			dataType:'html',	
			success:function(html){
				vietiso_loading(0);
				if(type == "parent") {
					$("#list_performance").append(html);
				}else{
					$(_this).closest(".group_item").append(html);	
				}
			}
		});
	},
	delete_item_performance : function (_this,e) {
		e.preventDefault();
		var type = $(_this).data("type");
		if(confirm("Bạn có chắc chắn muốn xoá?")) {
			$(_this).closest("."+type).remove();
		}
	},
}
$Core.report = {
	open: function(_this, e){
		e.preventDefault();
		vietiso_loading(1);
		var tp = $(_this).attr('tp'),
			field_id = $(_this).getAttr('field_id', ""),
			group_id = $(_this).getAttr('group_id', "");
		$.post(`${path_ajax_script}/index.php?mod=${mod}&act=open_field`, {
			'tp' : tp,
			'field_id' : field_id,
			'group_id' : group_id
		}, function(html){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', html, 'open_field');
		});
		return false;
	},
	save: function(_this, e){
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			_tp = $(_this).attr('tp'),
			field_id = $(_this).attr('field_id'),
			group_id = $(_this).attr('group_id');
		if($('input.required', _form).length){
			$('input.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					if($(_elem).hasClass('select2')){
						_validated++;
						$(_elem).trigger('chosen:open');
						event.stopPropagation();
						return false;
					} else {
						_validated++;
						$(_elem).focus();
						alert(_validated);
						return false;
					}
				}
			});
		}
		if(_validated==0){
			vietiso_loading(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: `${path_ajax_script}/index.php?mod=${mod}&act=pop_save_field`,
				data: {'tp':_tp, 'field_id':field_id, 'group_id':group_id},
				dataType: 'html',
				success: function(html){
					vietiso_loading(0);
					if(html.indexOf('_error') >= 0){
						$Core.alert.error("Error !");
					} else if(html.indexOf('_success') >= 0){
						window.location.reload();
					}
				}
			});
		}
		return false;
	}
}
$Core.page = {
	addContact: function (_this,e) {
		e.preventDefault();
		var toId = $(_this).attr("toId");
		$("#"+toId).append(`<div class="col-10 col-md-6 mb-2 contact_more">
			<div class="d-flex align-items-end">
				<div class="flex-fill">
					<label>Liên hệ</label>												
					<input type="text" class="form-control property_keys" name="contacts[`+toId+`][contact][]" value="" placeholder="Nhập liên hệ">
				</div>
				<a class="btn btn-icon text-danger" href="javascript:void(0)" onClick="$Core.page.deleteContact(this,event)" toId="`+toId+`"><i class="fa fa-trash mr-2" aria-hidden="true"></i></a>
			</div>
		</div>`);
	},
	deleteContact: function (_this,e) {
		e.preventDefault();
		$(_this).closest(".contact_more").remove();
	},
	deleteItem: function (_this,e) {
		e.preventDefault();
		$(_this).closest(".item_property").remove();
	},
	addProperty : function(_this,e){
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=addProperty', {}, function(respJson){
			vietiso_loading(0);
			$(_this).before(respJson.html);
		},"json");
	},
}