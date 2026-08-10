<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" style="max-width: 600px">
	{assign var = uid_file value = $clsISO->getUniqid()}
	<form class="d-none" enctype="multipart/form-data">
		<input id="select_file_{$uid_file}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" uid="{$uid_file}" onchange="$Core.incentive.upload_image(this,event)" charset="UTF-8" name="image">
	</form>
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			{if !empty($table_id)}
				<h5 class="modal-title" id="modalTopTitle">Sửa chương trình</h5>
			{else}
				<h5 class="modal-title" id="modalTopTitle">Thêm mới chương trình</h5>
			{/if}
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="form-group mb-2">
				<label class="w-100 form-label mb-1">Tiêu đề</label>
				<input type="text" class="form-control required" name="title" maxlength="255" 
				placeholder="Nhập tiêu đề" value="{if $table_id gt '0'}{$oneItem.title}{/if}">
			</div>
			<div class="form-group mb-2">
				<label for="nameSlideTop" class="form-label">Hình ảnh</label>
				<div class="input-group">
					<input type="text" placeholder="Nhập ảnh..." name="image" value="{$oneItem.image}" class="form-control" tp="sheet_price" uid="{$uid_file}" id="image_{$uid_file}" maxlength="255">
					<button type="button" toid="select_file_{$uid_file}" uid="{$uid_file}" onclick="$Core.incentive.select_image(this, event)" stock_id="103047" tp="sheet_price" class="btn btn-outline-default"><i class="fa fa-upload"></i> <span>Chọn ảnh</span></button>
				</div>
			</div>
			<div class="form-row">
				<div class="col-6">
					<div class="form-group mb-2">
						<label class="w-100 form-label mb-1">Từ</label>
						<input type="datetime-local" class="form-control required" name="start_time" placeholder="dd/mm/yyyy H:i" value="{if $table_id gt '0'}{$clsISO->formatDate($oneItem.start_time,5)}{/if}">
					</div>
				</div>
				<div class="col-6">
					<div class="form-group mb-2">
						<label class="w-100 form-label mb-1">Đến</label>
						<input type="datetime-local" class="form-control required" name="end_time" placeholder="dd/mm/yyyy H:i" value="{if $table_id gt '0'}{$clsISO->formatDate($oneItem.end_time,5)}{/if}">
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<label class="switch">
					<input type="checkbox" name="is_active"{if !empty($oneItem.is_active)} checked{/if} value="0">
					<span class="slider round"></span>
				</label>
				<span>Hiển thị</span>
			</div>
			<div class="d-flex gap-2 align-items-center">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" onClick="$Core.incentive.save(this, event)" table_id="{$table_id}" 
				class="btn btn-primary">Lưu lại</button>
			</div>
		</div>
	</form>
</div>