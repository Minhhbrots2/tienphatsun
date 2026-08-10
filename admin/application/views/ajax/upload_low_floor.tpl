<div class="modal-dialog modal-standard" style="max-width:500px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>Cấu hình</strong></h3>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label required">Cấu trúc mã căn hộ</label>
					<input id="inputor" style="height:34px" onchange="$(this).val($(this).val().replace('%',''))" title="Gõ % để lựa chọn" data-toggle="tooltip" class="form-control disabled-resize-y required" placeholder="[MaDay][CanHo]" name="config_stock[stock_template]" value="{$config_stock.stock_template}" />
				</div>
				<div class="form-group">
					<label class="col-form-label required">Spreadsheet dự án</label>
					<div class="form-row">
						<div class="col-md-5">
							<input type="text" class="form-control required spreadsheetId_{$uid}" data-label="Spreadsheet ID" onclick="this.select();" name="config_stock[spreadsheet_id]" value="{$config_stock.spreadsheet_id}" placeholder="Spreadsheet ID">
						</div>
						<div class="col-md-7">
							<div class="input-group">
								<input type="hidden" gid="{$uid}" name="config_stock[sheet_id]" value="{$config_stock.sheet_id}" class="sheet_id_{$uid}">
								<input type="text" gid="{$uid}" class="form-control sheet_name required sheet_name_{$uid}" data-label="Sheet name" onclick="this.select();" name="config_stock[sheet_name]" value="{$config_stock.sheet_name}" placeholder="SHEET_1" readonly>
								<div class="input-group-btn">
									<button type="button" onclick="$Core.project.open_sheet(this, event)" gid="{$uid}" uid="{$uid}" stock_type="178" class="btn btn-default" title="Chọn sheet">
										<i class="fa fa-cog"></i> Chọn sheet
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="text-right col-form-label">Cấu hình số căn hộ (nếu có)</label>
					<small>VD: căn số 13 chuyển thành căn số 12A</small>
					<div class="form-row">
						{if !empty($config_stock.convert_from)}
							{assign var=convertTo value=$config_stock.convert_to }
							{foreach from=$config_stock.convert_from item=convert_from key=key}
								<div class="col-md-12">
									<div class="d-flex align-items-center justify-content-center convert_item">
										<input type="text" class="form-control" placeholder="VD:13" name="config_stock[convert_from][]" value="{$convert_from}" >
										<i class="fa fa-arrow-right ml-2 mr-2" aria-hidden="true"></i>
										<input type="text" class="form-control" placeholder="VD:12A" name="config_stock[convert_to][]" value="{$convertTo[$key]}" >
										<button type="button" class="btn btn-add-convert ml-2" onclick="$Core.project.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="add"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
										<button type="button" class="btn btn-delete-convert ml-2 d-none" onclick="$Core.project.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="delete"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
									</div>
								</div>
							{/foreach}
						{else}
							<div class="col-md-12">
								<div class="d-flex align-items-center justify-content-center convert_item">
									<input type="text" class="form-control" placeholder="VD:13" name="config_stock[convert_from][]" value="" >
									<i class="fa fa-arrow-right ml-2 mr-2" aria-hidden="true"></i>
									<input type="text" class="form-control" placeholder="VD:12A" name="config_stock[convert_to][]" value="" >
									<button type="button" class="btn btn-add-convert ml-2" onclick="$Core.project.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="add"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
									<button type="button" class="btn btn-delete-convert ml-2 d-none" onclick="$Core.project.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="delete"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
								</div>
							</div>
						{/if}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" class="form-control " name="project_id" value="{$project_id}">
				<button type="submit" class="btn btn-success" onClick="$Core.project.import_data_from_link_doc_gg(this, event)" data-project-id="{$project_id}" action="_SAVE">
					{$core->makeIcon('check', $core->get_Lang('Save'))}
				</button>
				<button type="submit" class="btn btn-primary" onClick="$Core.project.import_data_from_link_doc_gg(this, event)" data-project-id="{$project_id}" action="_CREATE">Lưu & Tạo bảng hàng</button>
			</div>
		</form>
	</div>
</div>