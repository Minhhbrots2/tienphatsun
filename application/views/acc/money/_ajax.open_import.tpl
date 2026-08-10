<div class="modal-dialog modal-dialog-centered">
	<form method="POST" action="#" enctype="multipart/form-data" class="modal-content" onsubmit="return false;">
		<div class="modal-header">
			<h5 class="modal-title">Import dữ liệu kế toán</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group form-row mb-2">
				<div class="col-12 col-md-6 mb-2 mb-lg-0">
					<label class="form-label mb-1">Chọn ngày</label>
					<div class="clearfix"></div>
					<div class="{$uid} w-100">
						<input type="date" name="date_value" value="{$smarty.now|date_format:'%Y-%m-%d'}" class="form-control required" />
					</div>
				</div>
				<div class="col-12 col-md-6 ">
					<label class="form-label mb-1">Chọn câú hình</label>
					<div class="clearfix"></div>
					<select class="form-control iso-select2 required" data-width="100%" name="config_id">
						<option value="0">Chọn cấu hình File</option>
						{if !empty($money_import_configs)}
							{foreach from=$money_import_configs key = _OK item = _oI}
							<option value="{$_OK}">{$_oI.config_name}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Bảng tính</label>
				<div class="input-group">
					<input type="file" class="form-control required" placeholder="Nhập bảng tính" name="fileimport" />
					<button type="button" gId="{$uid}" onClick="$Core.money.open_config(this, event)" 
						class="btn btn-default btn-icon border-end"><i class="bx bx-cog"></i></button>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" onClick="$Core.money.do_import(this, event)" 
				class="btn btn-primary" uid="{$uid}">Thực hiện</button>
		</div>
	</form>
</div>
