<div class="modal-dialog">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Import dữ liệu</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Bảng tính</label>
				<div class="input-group">
					<select uid="{$uid}" class="form-control form-select w-px-100 max-w-px-100" name="by">
						<option value="ID">ID</option>
						<option value="URL">URL</option>
					</div>
					<input type="text" class="form-control required required_crawl" placeholder="Nhập bảng tính" name="spreadsheetId" />
					<button type="button" uid="{$uid}" onClick="$Core.marketing.crawl(this, event)" 
						class="btn btn-default btn-icon border-end"><i class="bx bx-play"></i></button>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-12 col-md-6">
					<label class="form-label mb-1">Chọn tháng</label>
					<input type="month" uid="{$uid}" class="form-control required" name="month" />
				</div>
				<div class="col-12 col-md-6">
					<label class="form-label mb-1">Tên sheet</label>
					<div class="input-group">
						<select uid="{$uid}" class="form-control form-select required" name="sheet_name">
							<option value="">Chọn bảng tính</option>
						</select>
						<button type="button" uid="{$uid}" tp="{$tp}" onClick="$Core.marketing.open_config(this, event)" 
						class="btn btn-default btn-icon border-end"><i class="bx bx-cog"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" onClick="$Core.marketing.do_import(this, event)" 
				class="btn btn-primary" tp="{$tp}" uid="{$uid}">Cập nhật</button>
		</div>
	</form>
</div>
