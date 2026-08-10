<div class="modal-dialog modal-dialog-centered">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Phản hồi</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="bg-lighter p-3 rounded-2 mb-2">
				<i class='bx bxs-quote-alt-left mt-sm-n1'></i> 
				{$clsFollowUp->getContent($parent_id)}
			</div>
			<div class="form-group mb-0">
				<label class="form-label mb-1">Nôi dung</label>
				<div class="input-group input-group-merge">
					<span class="input-group-text"><i class="bx bx-comment"></i></span>
					<textarea name="content" cols="255" rows="5" class="form-control" 
						placeholder="Nhập nội dung phản hồi">{if $followup_id gt '0'}{$clsFollowUp->getContent($followup_id)}{/if}</textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" parent_id="{$parent_id}" followup_id="{$followup_id}" customer_id="{$customer_id}" onClick="$Core.crm.save_reply(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>