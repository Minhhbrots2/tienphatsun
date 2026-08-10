<div class="modal-dialog modal-sm">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Thêm khách hàng</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label for="name" class="form-label mb-1">Tên khách hàng</label>
				<input type="text" id="name" autocomplete="off" name="name" class="form-control required" placeholder="Họ và tên">
			</div>
			<div class="form-group mb-2">
				<label for="address" class="form-label mb-1">Địa chỉ</label>
				<div class="input-group input-group-merge">
					<span class="input-group-text"><i class="bx bx-buildings"></i></span>
					<input type="text" id="address" name="address" class="form-control" placeholder="Địa chỉ">
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Phân loại</label>
				<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
					{foreach from=$arr_type key = _OK item = _OT name = _OI}
					<input type="radio" class="btn-check"{if $smarty.foreach._OI.first} checked{/if} 
						name="_type" id="{$_OK}_{$uid}" value="{$_OK}">
					<label data-toggle="ripple" class="btn btn-outline-default" 
						for="{$_OK}_{$uid}">{$_OT}</label>
					{/foreach}
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="gr" value="{$gr}" />
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn flex-fill btn-primary" uid="{$uid}" onClick="$Core.fund.pop_save_person(this, event)">Lưu</button>
		</div>
	</form>
</div>
