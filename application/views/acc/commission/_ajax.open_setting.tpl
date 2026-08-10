{literal}
<style>
	.cy-year{border:1px solid #d9dee3;border-radius:.375rem}
	.cy-year>summary{cursor:pointer;padding:.5rem .75rem;font-weight:600}
	.cy-year[open]>summary{border-bottom:1px solid #d9dee3;margin-bottom:.25rem}
	.cy-year-sub{font-weight:400;font-size:.82rem;color:#8592a3;margin-left:.5rem}
	.cy-year-body{padding:.5rem .75rem .75rem}
	.cy-row{display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem}
	.cy-row-label{flex:0 0 46px;color:#697a8d;font-size:.85rem; white-space: nowrap;}
	.cy-row .form-control{flex:1 1 auto}
	.cy-tab{flex:0 0 170px!important}
	.cy-crawl{flex:0 0 auto;white-space:nowrap}
</style>
{/literal}
<div class="modal-dialog modal-dialog-centered modal-lg">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cấu hình Google Sheet crawl hoa hồng</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="alert alert-warning py-2 mb-3">
				Mỗi <strong>năm</strong> chọn <strong>một kiểu</strong>: <em>Cả năm</em> (1 file gộp) hoặc <em>Theo quý</em> (4 file rời) — không dùng cả hai để tránh ghi đè số liệu.
				Bật kích hoạt để crawl tự động hằng đêm; kỳ đã trả xong thì tắt.
			</div>
			{foreach from=$list_years key=_y item=_yr}
			<details class="cy-year mb-2"{if $_y eq $current_year} open{/if}>
				<summary class="cy-year-head">
					<span class="cy-year-title">Năm {$_y|escape}</span>
					<span class="cy-year-sub">{if $_yr.mode eq 'quarter'}Theo quý{else}Cả năm{/if}</span>
				</summary>
				<div class="cy-year-body">
					<div class="cy-mode mb-2">
						<span class="me-2 text-muted">Kiểu dữ liệu:</span>
						<label class="me-3"><input type="radio" name="commission_years[{$_y}][mode]" value="year"{if $_yr.mode ne 'quarter'} checked{/if} onChange="$Core.commission.toggle_mode(this, event)"> Cả năm (1 file)</label>
						<label><input type="radio" name="commission_years[{$_y}][mode]" value="quarter"{if $_yr.mode eq 'quarter'} checked{/if} onChange="$Core.commission.toggle_mode(this, event)"> Theo quý (4 file)</label>
					</div>
					<div class="cy-blk cy-blk-year"{if $_yr.mode eq 'quarter'} style="display:none"{/if}>
						<div class="cy-row">
							<span class="cy-row-label">Cả năm</span>
							<input type="hidden" name="commission_years[{$_y}][year][is_active]" value="0">
							<label class="switch" title="Kích hoạt">
								<input value="1" type="checkbox" name="commission_years[{$_y}][year][is_active]"{if $_yr.year.is_active eq 1} checked{/if}>
								<span class="slider round"></span>
							</label>
							<input type="text" class="form-control" placeholder="spreadsheetId" name="commission_years[{$_y}][year][spreadsheetId]" value="{$_yr.year.spreadsheetId|escape}">
							<input type="text" class="form-control cy-tab" placeholder="Tên tab (tùy chọn)" name="commission_years[{$_y}][year][sheet_name]" value="{$_yr.year.sheet_name|escape}">
							<button type="button" class="btn btn-outline-default cy-crawl" quarter_id="Y{$_y}" spreadsheetId="{$_yr.year.spreadsheetId|escape}" 
								sheet_name="{$_yr.year.sheet_name|escape}" onClick="$Core.commission.crawl(this, event)"><i class="bx bx-play"></i> Crawl</button>
						</div>
					</div>
					<div class="cy-blk cy-blk-quarter"{if $_yr.mode ne 'quarter'} style="display:none"{/if}>
						{foreach from=$_yr.q key=_q item=_slot}
						<div class="cy-row">
							<span class="cy-row-label">Q{$_q}</span>
							<input type="hidden" name="commission_years[{$_y}][q][{$_q}][is_active]" value="0">
							<label class="switch" title="Kích hoạt">
								<input value="1" type="checkbox" name="commission_years[{$_y}][q][{$_q}][is_active]"{if $_slot.is_active eq 1} checked{/if}>
								<span class="slider round"></span>
							</label>
							<input type="text" class="form-control" placeholder="spreadsheetId" name="commission_years[{$_y}][q][{$_q}][spreadsheetId]" value="{$_slot.spreadsheetId|escape}">
							<input type="text" class="form-control cy-tab" placeholder="Tên tab (tùy chọn)" name="commission_years[{$_y}][q][{$_q}][sheet_name]" value="{$_slot.sheet_name|escape}">
							<button type="button" class="btn btn-outline-default cy-crawl" quarter_id="Q{$_q}_{$_y}" spreadsheetId="{$_slot.spreadsheetId|escape}" 
								sheet_name="{$_slot.sheet_name|escape}" onClick="$Core.commission.crawl(this, event)"><i class="bx bx-play"></i> Crawl</button>
						</div>
						{/foreach}
					</div>
				</div>
			</details>
			{/foreach}
			<hr>
			<div class="d-flex align-items-center gap-2 mb-2">
				<span class="text-muted text-nowrap">Thêm năm:</span>
				<input type="number" class="form-control cy-newyear" placeholder="Năm" name="new_period_year" value="">
				<select class="form-control cy-newtype" name="new_period_type">
					<option value="Y">Cả năm — 1 file</option>
					<option value="Q">Theo quý — 4 file</option>
				</select>
			</div>
			<div class="d-flex algin-items-center justify-content-end">
				<small class="text-muted">Bấm Cập nhật để tạo thẻ năm mới.</small>
			</div>
		</div>
		<input type="hidden" name="submit" value="Update" />
		<div class="modal-footer border-top pt-3">
			<button type="button" data-toggle="ripple" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" data-toggle="ripple" class="btn btn-primary" onClick="$Core.commission.update_setting(this, event)">Cập nhật</button>
		</div>
	</form>
</div>
