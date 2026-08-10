<div class="modal-dialog modal-dialog-centered">
	<form method="POST" action="#" enctype="multipart/form-data" class="modal-content" onsubmit="return false;">
		<div class="modal-header">
			<h5 class="modal-title">Import dữ liệu chấm công</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-3">
				<label class="form-label mb-1">Chọn thời gian</label>
				<div class="clearfix"></div>
				<div class="btn-group d-flex xs:w-100" role="group" aria-label="Thời gian">
					<input type="radio" uid="{$uid}" onChange="$Core.attendance.do_change(this, event)" 
						class="btn-check" name="date_type" id="week_{$uid}" value="week">
					<label data-toggle="ripple" class="btn btn-outline-default" for="week_{$uid}">Theo tuần</label>
					<input type="radio" class="btn-check" uid="{$uid}" name="date_type" id="month_{$uid}" 
						onChange="$Core.attendance.do_change(this, event)" value="month" checked="checked">
					<label data-toggle="ripple" class="btn btn-outline-default" for="month_{$uid}">Theo tháng</label>
				</div>
			</div>
			<div class="form-group form-row mb-3">
				<div class="col-12 col-md-6">
					<label class="form-label mb-1">Chọn tháng</label>
					<div class="clearfix"></div>
					<div class="{$uid} w-100">
						<input type="month" name="date_value" value="{$smarty.now|date_format:'%Y-%m'}" class="form-control required" />
					</div>
				</div>
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Văn phòng</label>
					<select class="form-control iso-select2 required" data-width="100%" name="office_id">
						<option value="0">Chọn văn phòng</option>
						{if !empty($arr_offices)}
							{foreach from=$arr_offices item = _oI}
							<option value="{$_oI.setting_id}">{$_oI.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Chọn câú hình</label>
					<div class="clearfix"></div>
					<select class="form-control iso-select2 required" data-width="100%" name="config_id">
						<option value="0">Chọn cấu hình File</option>
						{if !empty($attendance_import_configs)}
							{foreach from=$attendance_import_configs key = _OK item = _oI}
							<option value="{$_OK}">{$_oI.config_name}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-12 col-md-6">
					<label class="form-label mb-1">Bảng tính</label>
					<div class="input-group">
						<input type="file" class="form-control required" placeholder="Nhập bảng tính" name="fileimport" />
						<button type="button" gId="{$uid}" onClick="$Core.attendance.open_config(this, event)" 
							class="btn btn-default btn-icon border-end"><i class="bx bx-cog"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" onClick="$Core.attendance.do_import(this, event)" 
				class="btn btn-primary" uid="{$uid}">Cập nhật</button>
		</div>
	</form>
</div>
