<div class="modal-dialog modal-ipad">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-md-3 col-form-label">Tên mốc / đợt<span class="text-red">*</span></label>
					<div class="col-md-9">
						<input type="text" id="{$clsISO->getUniqid()}" autocomplete="off" class="form-control required"
							placeholder="VD: Ký VBTT, Đợt 2, Bàn giao..." name="name" list="ptg-milestone-names" value="{$oneOption.name}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-form-label">Mốc thời gian</label>
					<div class="col-md-9">
						<label class="radio-inline"><input type="radio" name="date_mode" value="days" {if $oneOption.date_mode eq 'days' or $oneOption.date_mode eq ''}checked{/if} onchange="$Core.price_sheets.toggle_date_mode(this)" /> Sau đợt trước (số ngày)</label>
						<label class="radio-inline"><input type="radio" name="date_mode" value="fixed" {if $oneOption.date_mode eq 'fixed'}checked{/if} onchange="$Core.price_sheets.toggle_date_mode(this)" /> Ngày cố định</label>
						<label class="radio-inline"><input type="radio" name="date_mode" value="estimated" {if $oneOption.date_mode eq 'estimated'}checked{/if} onchange="$Core.price_sheets.toggle_date_mode(this)" /> Dự kiến (text)</label>
					</div>
				</div>
				<div class="form-group date-mode-group date-mode-days" {if $oneOption.date_mode neq '' and $oneOption.date_mode neq 'days'}style="display:none"{/if}>
					<label class="col-md-3 col-form-label">Số ngày sau đợt trước</label>
					<div class="col-md-9">
						<div class="input-group-suffix">
							<input type="number" min="0" step="1" onClick="this.select()" class="form-control numberonly"
								placeholder="VD: 7" name="payment_days" value="{$oneOption.payment_days}" />
							<span class="suffix">ngày</span>
						</div>
						<small class="text-muted">Ngày của đợt liền trước + số ngày này. Đợt đầu tiên thì "trước" chính là ngày đặt cọc.</small>
					</div>
				</div>
				<div class="form-group date-mode-group date-mode-fixed" {if $oneOption.date_mode neq 'fixed'}style="display:none"{/if}>
					<label class="col-md-3 col-form-label">Ngày cố định</label>
					<div class="col-md-9">
						<input type="text" class="form-control datepicker" placeholder="dd/mm/yy" name="fixed_date" value="{if $oneOption.fixed_date}{$clsISO->convertTimeToText($oneOption.fixed_date)}{/if}" />
						<small class="text-muted">VD: Đợt 2 ngày 31/03/2026 — sẽ hiển thị nguyên ngày này không phụ thuộc đợt trước.</small>
					</div>
				</div>
				<div class="form-group date-mode-group date-mode-estimated" {if $oneOption.date_mode neq 'estimated'}style="display:none"{/if}>
					<label class="col-md-3 col-form-label">Dự kiến (text)</label>
					<div class="col-md-9">
						<input type="text" class="form-control" placeholder="VD: T6/2026, QII/2028, Sau khi ký HĐMB..." name="estimated_text" value="{$oneOption.estimated_text}" />
						<small class="text-muted">VD: "Ký HĐMB (dự kiến T6/2026)", "Bàn giao (dự kiến QII/2028)".</small>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-form-label">Số tiền</label>
					<div class="col-md-9">
						<label class="radio-inline"><input type="radio" name="amount_mode" value="percent" {if $oneOption.amount_mode eq 'percent' or $oneOption.amount_mode eq ''}checked{/if} onchange="$Core.price_sheets.toggle_amount_mode(this)" /> Tỷ lệ %</label>
						<label class="radio-inline"><input type="radio" name="amount_mode" value="fixed" {if $oneOption.amount_mode eq 'fixed'}checked{/if} onchange="$Core.price_sheets.toggle_amount_mode(this)" /> Số tiền cố định</label>
					</div>
				</div>
				<div class="form-group amount-mode-group amount-mode-percent" {if $oneOption.amount_mode neq '' and $oneOption.amount_mode neq 'percent'}style="display:none"{/if}>
					<label class="col-md-3 col-form-label">Tỷ lệ thanh toán</label>
					<div class="col-md-9">
						<div class="input-group-suffix">
							<input type="number" onClick="this.select()" class="form-control numberonly" name="payment_rate" value="{$oneOption.payment_rate}" />
							<span class="suffix">%</span>
						</div>
					</div>
				</div>
				<div class="form-group amount-mode-group amount-mode-fixed" {if $oneOption.amount_mode neq 'fixed'}style="display:none"{/if}>
					<label class="col-md-3 col-form-label">Số tiền cố định</label>
					<div class="col-md-9">
						<div class="input-group-suffix">
							<input type="text" class="form-control price-In" placeholder="VD: 50,000,000" name="payment_amount" value="{if $oneOption.payment_amount}{$oneOption.payment_amount}{/if}" />
							<span class="suffix">đ</span>
						</div>
						<small class="text-muted">VD: Đặt cọc 50.000.000 đ (không tính theo %).</small>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-form-label">KPBT</label>
					<div class="col-md-9">
						<label class="checkbox-inline">
							<input type="hidden" name="include_kpbt" value="0" />
							<input type="checkbox" name="include_kpbt" value="1" {if $oneOption.include_kpbt}checked{/if} /> Bao gồm phí KPBT
						</label>
						<small class="text-muted">VD: "Bàn giao" thường nộp 25% + KPBT, hoặc chỉ KPBT. KPBT khai báo ở header PTG: {if $maintenance_rate}<strong>{$maintenance_rate}%</strong>{else}<em class="text-danger">chưa nhập</em>{/if}</small>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-form-label">Thuế VAT</label>
					<div class="col-md-9">
						<div class="input-group-suffix">
							<input type="text" onClick="this.select()" class="form-control numberonly" name="tax_rate" value="{$oneOption.tax_rate}" />
							<span class="suffix">%</span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="$Core.price_sheets.pop_save_option(this, event)"
					price_sheet_id="{$price_sheet_id}" stock_type="{$stock_type}" price_plan_id="{$price_plan_id}" option_id="{$option_id}">Lưu lại</button>
				{if empty($option_id)}
				<button type="button" class="btn btn-info pull-right mr-half" onClick="$Core.price_sheets.pop_save_option(this, event)" data-stay="1"
					price_sheet_id="{$price_sheet_id}" stock_type="{$stock_type}" price_plan_id="{$price_plan_id}" option_id="{$option_id}"><i class="fa fa-plus"></i> Lưu & Thêm tiếp</button>
				{/if}
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>
<datalist id="ptg-milestone-names">
	<option value="Đặt cọc"></option>
	<option value="Đặt cọc giữ chỗ"></option>
	<option value="Ký VBTT"></option>
	<option value="Đợt 1"></option>
	<option value="Đợt 2"></option>
	<option value="Đợt 3"></option>
	<option value="Đợt 4"></option>
	<option value="Đợt 5"></option>
	<option value="Ký HĐMB"></option>
	<option value="Sau khi ký HĐMB"></option>
	<option value="Bàn giao"></option>
	<option value="HTLS (Hỗ trợ lãi suất)"></option>
	<option value="Chiết khấu"></option>
	<option value="Cấp sổ"></option>
</datalist>
