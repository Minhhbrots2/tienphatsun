$Core.order = {
	updateStatus: function(_this, e){
		var order_id = $(_this).attr("order_id");
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=updateStatus', {
			'order_id' : order_id
		}, function(respJson){
			vietiso_loading(0);
			if(respJson.result){
				$(_this).prop("disabled",true);
				$(_this).closest("tr").find(".txt_status").html(`<span class="text-success">Đã thanh toán</span>`);
				$(_this).closest("tr").find(".txt_status_date").html(respJson.status_date);
			}else{
				alertify.error("ERROR!");
				window.reload();
			}
		},"json");
	},
}