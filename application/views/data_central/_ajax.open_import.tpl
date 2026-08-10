<div class="modal-dialog modal-sm">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Import dữ liệu</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">	
				<div class="form-group mb-2">
					<label for="title" class="form-label mb-1">Spreadsheet Id</label>
					<input type="text" class="form-control required form-field" name="spreadsheetId" 
						placeholder="spreadsheetId" toId="sheet_name_{$uid}" value="" onChange="$Core.data_central.get_sheets(this,event)">
				</div>
				<div class="form-group mb-2">
					<label for="title" class="form-label mb-1">Sheet name</label>
					<div class="input-group">
						<select name="sheet_name" class="form-select required" 
							onChange="$Core.data_central.open_config(this, event)" id="sheet_name_{$uid}">
							<option value="">Chọn sheet</option>
						</select>
						<button class="btn btn-icon btn-outline-default" onClick="$Core.data_central.open_config(this, event)">
							<i class="bx bx-cog"></i>
						</button>
					</div>
				</div>
				<div class="form-group mb-2">
					<label for="title" class="form-label mb-1">Dòng bắt đầu</label>
					<input type="number" class="form-control required form-field" 
						name="start_row" placeholder="Bắt đầu từ dòng" value="2" />
				</div>
				<div class="form-group mb-2">
					<label for="title" class="form-label mb-1">Dự án</label>
					<select name="project_id" class="form-select w-100">
						<option value="0">Chọn dự án</option>
						{$clsProject->getSelectOptions($project_id)}
					</select>
				</div>
				<div class="form-group">
					<label for="title" class="form-label mb-1">Tag</label>
					<input type="text" class="form-control form-field input-tags" 
						name="tag" placeholder="Tags" value="">
				</div>
				<div class="d-flex py-2">
					<small class="text-muted">Tùy chọn thêm</small>
				</div/>
				<div class="form-group mb-2">
					<div class="form-check">
						<input type="checkbox" class="form-check-input" checked id="crawl_stock" value="1" name="is_crawl_stock">
						<label class="form-check-label" for="crawl_stock">Thu thập thông tin căn hộ</label>
					</div>
				</div>
				<div class="form-group">
					<div class="form-check">
						<input type="checkbox" id="regex_{$uid}" class="form-check-input" value="1" name="is_regex_stock">
						<label class="form-check-label" for="regex_{$uid}">Bóc tách mã căn bằng Regex</label>
					</div>
					<div class="pl-4">
						<label class="form-label mb-1">Mẫu Regex</label>
						<input type="text" class="form-control form-field" name="regex_stock" placeholder="Mẫu regex" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" onClick="$Core.data_central.import_data(this,event)" 
					agency_id="{$agency_id}" data-type="{$action}">Import</button>
			</div>
		</div>
	</form>
</div>