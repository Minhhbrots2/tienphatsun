<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-md-12 col-xxl-10 offset-xxl-1">
			<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between py-2">
				<div class="d-flex flex-column">
					<h4 class="fw-bold mb-0">Thu chi tiền mặt</h4>
					<span class="text-muted">Danh sách thu/chi tiền mặt</span>
				</div>
				{if $deviceType eq 'phone' && $clsISO->checkPermission('cash_book_import')}
				<div class="btn-group">
					<button data-bs-toggle="dropdown" class="btn btn-icon btn-outline-default dropdown-toggle"></button>
					<div class="dropdown-menu dropdown-menu-end">
						<a class="dropdown-item cursor-pointer" onClick="$Core.cash_book.open_import(this, event)">
							<i class="bx bx-import" style="transform:translateY(-2px);"></i> Import
						</a>
						<hr class="dropdown-divider" />
						<a class="dropdown-item cursor-pointer" onClick="$Core.cash_book.open_setting(this, event)">
							<i class="bx bx-cog" style="transform:translateY(-2px);"></i> Cấu hình
						</a>
					</div>
				</div>
				{/if}
				<div class="d-flex flex-wrap gap-1 align-items-center bg-grayter rounded-2 p-2{if $deviceType eq 'phone'} mt-2{else} mt-n2{/if}">
					<div class="btn-group xs:w-100 text-nowrap" role="group" aria-label="Hiển thị">
					{if !empty($group_company_arrs)}
						{foreach from=$group_company_arrs item = _oI}
						<input type="radio" class="btn-check rdo__company"{if $_oI.setting_id eq $smarty.const._GROUP_COMPANY_FH_ID} checked{/if} 
							name="company_id" value="{$_oI.setting_id}" id="rdo_{$_oI.setting_id}_company" onChange="$Core.cash_book.do_search(this, event)">
						<label class="btn btn-outline-default{if $_oI.setting_id eq $smarty.const._GROUP_COMPANY_FH_ID} active{/if}"
							data-toggle="ripple" title="{$_oI.title}" for="rdo_{$_oI.setting_id}_company">{$_oI.title}</label>
						{/foreach}
					{/if}
					</div>
					<select class="form-control xs:flex-fill form-select search_field w-px-125" data-field="type" 
						onChange="$Core.cash_book.do_search(this, event)">
						<option value="_all">Chọn khoản</option>
						<option value="THUCTHU">Khoản Thu</option>
						<option value="THUCCHI">Khoản Chi</option>
					</select>
					<input type="text" class="form-control xs:flex-fill w-px-200 isodaterangepicker search_field" 
						data-field="cash_date" start_date="2025-01-01" end_date="{$smarty.now|date_format:'%Y-%m-%d'}" 
						placeholder="Khoảng thời gian" onChange="$Core.cash_book.do_search(this, event)" />
					{if $deviceType ne 'phone' && $clsISO->checkPermission('cash_book_import')}
					<div class="btn-group">
						<button data-bs-toggle="dropdown" class="btn btn-icon btn-outline-default dropdown-toggle"></button>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item cursor-pointer" onClick="$Core.cash_book.open_import(this, event)">
								<i class="bx bx-import" style="transform:translateY(-2px);"></i> Import
							</a>
							<hr class="dropdown-divider" />
							<a class="dropdown-item cursor-pointer" onClick="$Core.cash_book.open_setting(this, event)">
								<i class="bx bx-cog" style="transform:translateY(-2px);"></i> Cấu hình
							</a>
						</div>
					</div>
					{/if}
				</div>
			</div>
			<div class="card">
				<div class="card-header"></div>
				<div class="card-body">
					<div class="table-wrapper overflow-x-auto mb-2">
						<table class="table rounded-1 overflow-hidden table-bordered">
							<thead><tr>
								<th class="text-center bg-main text-white h-px-35" colspan="4">
									<strong class="text-fs-18">BÁO CÁO TỒN QUỸ TIỀN MẶT</strong>
								</th>
							</tr>
							<tr>
								<th class="text-center h-px-35 fw-bold">Tồn đầu kỳ</th>
								<th class="text-center h-px-35 fw-bold">Thu trong kỳ</th>
								<th class="text-center h-px-35 fw-bold">Chi trong kỳ</th>
								<th class="text-center h-px-35 fw-bold">Tồn cuối kỳ</th>
							</tr></thead>
							<tbody><tr>
								<td class="text-center">
									<strong class="total_opening_balance text-fs-16 xs:text-fs-14">0.00</strong> 
									{$clsISO->getRate()}
								</td>
								<td class="text-center">
									<strong class="total_receipt_amount text-fs-16 xs:text-fs-14">0.00</strong> 
									{$clsISO->getRate()}
								</td>
								<td class="text-center">
									<strong class="total_payment_amount text-fs-16 xs:text-fs-14">0.00</strong> 
									{$clsISO->getRate()}
								</td>
								<td class="text-center">
									<strong class="total_closing_balance text-fs-16 xs:text-fs-14">0.00</strong> 
									{$clsISO->getRate()}
								</td>
							</tr></tbody>
						</table>
					</div>
					<div class="table-container overflow-x-auto no-shadow">
						<table cellpadding="0" cellspacing="0" class="table">
							<thead><tr>
								<th class="align-center w-px-50 h-px-35 bg-lighter">No.</th>
								<th class="align-center w-px-125 h-px-35 bg-lighter text-right">Ngày</th>
								<th class="align-center h-px-35 bg-lighter">Loại</th>
								<th class="align-center h-px-35 bg-lighter text-right">Số tiền</th>
								<th class="align-center h-px-35 bg-lighter">Ghi chú</th>
							</tr></thead>
							<tbody id="holder_cash_book">
								{section name=i loop=$list_preloaders}
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></i></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></i></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></i></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></i></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></i></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script>
	// $(() => { $Core.cash_book.list({}); });
</script>
{/literal}