<div class="modal-dialog modal-dialog-centered modal-xxs">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Yêu cầu mua gói khách hàng</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Khách hàng cho dự án</label>
				<input type="text" id="name" name="project_name" class="form-control required" value="" placeholder="Nhập tên dự án">
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Ghi chú</label>
				<textarea class="form-control" placeholder="Ghi chú" name="notes" cols="255" rows="2"></textarea>
			</div>
			<div class="p-2 border-dotted rounded-3 mb-2" style="border-color: #960e28;border-width: 2px">
				<h3 class="text-main mb-2">Gói khách hàng: <strong class="text-main">{$oneItem.title}</strong></h3>
				<p class="mb-0">Số lượng: <strong class="text-main">{$more_information.total_data}</strong> khách hàng.</p>
				<p class="mb-0">Đơn giá: <strong class="text-main">{$clsISO->shortNumber($more_information.price_data,0)}</strong></p> 
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="package_id" value="{$package_id}">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" uid="{$uid}" onClick="$Core.market_data.save_req(this, event)" class="btn btn-primary">Gửi yêu cầu</button>
		</div>
	</form>
</div>