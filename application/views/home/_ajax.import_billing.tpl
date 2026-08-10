<div class="modal-dialog modal-lg">
	<form method="POST" enctype="multipart/form-data" class="modal-content" id="billing_import_form_{$uid}">
		<div class="modal-header">
			<h5 class="modal-title mb-0"><i class="bx bx-import me-1"></i> Import giao dịch từ Google Sheet</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="alert alert-info py-2 d-flex align-items-start mb-3">
				<i class="bx bx-info-circle me-2 mt-1"></i>
				<small>
					Chia sẻ Google Sheet tới <strong>service account</strong> của hệ thống trước.
					Khối / Phòng / GĐ được suy tự động theo <strong>Mã nhân viên</strong>.
					Loại giao dịch lấy từ cột "Loại" (map trong ⚙) — không map thì dùng Loại mặc định.
					Luôn bấm <strong>Xem trước</strong> để kiểm tra rồi mới ghi.
				</small>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-6 mb-2">
					<label class="form-label mb-1">Spreadsheet ID</label>
					<input type="text" class="form-control required" name="spreadsheetId" placeholder="ID trong URL Google Sheet"
						toId="bi_sheet_{$uid}" value="{$saved_spreadsheet|escape}" onChange="$Core.billingImport.loadSheets(this,event)">
				</div>
				<div class="col-12 col-md-6 mb-2">
					<label class="form-label mb-1">Sheet (tab)</label>
					<div class="input-group">
						<select name="sheet_name" class="form-select required" id="bi_sheet_{$uid}">
							<option value="">Chọn sheet</option>
						</select>
						<button type="button" class="btn btn-outline-default" title="Cấu hình map cột"
							onClick="$Core.billingImport.openConfig(this,event)"><i class="bx bx-cog"></i></button>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-6 col-md-4 mb-2">
					<label class="form-label mb-1">Dòng bắt đầu</label>
					<input type="number" class="form-control required" name="start_row" value="2" min="1">
				</div>
				<div class="col-6 col-md-4 mb-2">
					<label class="form-label mb-1">Loại mặc định <small class="text-muted">(nếu cột trống)</small></label>
					<select name="billing_type" class="form-select">
						<option value="">— Lấy từ cột "Loại" —</option>
						{$billing_type_options}
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 d-flex align-items-end">
					<button type="button" class="btn btn-outline-primary w-100" onClick="$Core.billingImport.preview(this,event)">
						<i class="bx bx-search-alt me-1"></i> Xem trước
					</button>
				</div>
			</div>
			<input type="hidden" name="is_preview" value="1">
			<div class="billing-import-report mt-2"></div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary bi-btn-run disabled" disabled onClick="$Core.billingImport.confirmRun(this,event)">
				<i class="bx bx-save me-1"></i> Ghi vào hệ thống
			</button>
		</div>
	</form>
</div>
