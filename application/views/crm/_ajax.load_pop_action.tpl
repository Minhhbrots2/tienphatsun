<form class="p-2">
	<div class="form-group mb-2">
		<label class="form-label mb-1 text-nowrap">Tình trạng</label>
		<select name="status_id" class="form-control upd_field form-select">
			<option value="0">Tình trạng</option>
			{$clsProperty->getSelectByProperty('CUSTOMER_STATUS', 0)}
		</select>
	</div>
	<div class="form-group form-row mb-2">
		<div class="col-6">
			<label class="form-label mb-1 text-nowrap">Loại hình</label>
			<select name="blocktype_id" class="form-control upd_field form-select">
				<option value="0">Loại hình</option>
				{$clsProperty->getSelectByProperty('_BLOCK_TYPE', 0)}
			</select>
		</div>
		<div class="col-6">
			<label class="form-label mb-1 text-nowrap">Nguồn khách</label>
			<select name="resource_id" class="form-control upd_field form-select">
				<option value="0">Nguồn khách</option>
				{$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES', 0)}
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="form-label mb-1">Người quản lý</label>
		<div class="clearfix"></div>
		<select class="iso-selectizeNotSearch upd_field" name="admin_id" data-width="100%" data-placeholder="Người quản lý" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" data-width="100%"></select>
	</div>
	<hr class="my-2" />
	<div class="alert alert-warning fs-12">
		<u>Lưu ý</u>: Khi nhấp áp dụng sẽ cập nhật những khách hàng đã chọn với field bên trên!
	</div>
	<button type="button" onClick="$Core.crm.do_action(this, event)" class="btn btn-block btn-primary">
		<i class="bx bx-check"></i>
		<span>Áp dụng</span>
	</button>
</form>