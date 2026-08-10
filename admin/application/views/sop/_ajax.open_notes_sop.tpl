<div class="modal-dialog">
	<form method="post" class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Lý do không duyệt căn</strong></h3>
		</div>
		<div class="modal-body">
			<div class="form-group form-row">
				<textarea class="form-control w-100" name="notes" id="notes" cols="30" rows="3"></textarea> 
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="sop_ids" value="{$sop_ids}">
			<button type="button" onClick="$Core.sop.approve_sop(this, event)" data-action="{$action}" 
			stock_id="{$stock_id}" class="btn btn-success">Xác nhận</button> 
			<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Huỷ</button>
		</div>
	</form>
</div>