{if $template_type eq '_modal'}
	<div class="modal-dialog modal-dialog-scrollable">
		<form class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Hoa hồng môi giới tạm tính</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="px-3 mb-2">
				<div class="card bg-main ">
					<div class="card-body">
						<div class="row">
							<div class="col-6 py-3 border-end border-wine">
								<div class="d-flex align-items-cecnter gap-2">
									<div class="avatar avatar-sm flex-shrink-0">
										<img src="{$URL_IMAGES}/icons/reshot-icon-wallet-full-of-money-NRHXA2YE5S.svg" class="rounded" />
									</div>
									<div class="d-flex flex-column text-white">
										<div class="d-flex align-items-center mb-1 text-upper">Đã nhận</div>
										<h3 class="text-fs-18 text-warning mb-1">{$clsISO->shortNumber($_dataPost.total_paid_commissions,2)}</h3>
										<p class="mb-0 text-fs-12">{$_dataPost.total_paid} giao dịch đã trả</p>
									</div>
								</div>
							</div>
							<div class="col-6 py-3">
								<div class="d-flex align-items-cecnter gap-2">
									<div class="avatar avatar-sm flex-shrink-0">
										<img src="{$URL_IMAGES}/icons/reshot-icon-money-BTKWYNM52F.svg" class="rounded" />
									</div>
									<div class="d-flex flex-column text-white">
										<div class="d-flex align-items-center mb-1 text-upper">Chờ nhận</div>
										<h3 class="text-warning text-fs-18 mb-1">{$clsISO->shortNumber($_dataPost.total_wait_commissions,2)}</h3>
										<p class="mb-0 text-fs-12">{$_dataPost.total_wait} giao dịch đang chờ xử lý</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body py-0">
				<div class="d-flex flex-wrap align-items-center p-2 gap-1 gap-lg-2 rounded-3 bg-lighter mb-2">
					<input uid="{$uid}" type="text" class="form-control search_field w-px-150 xs:mw-auto xs:flex-fill" 
					placeholder="Nhập từ khóa" onChange="$Core.global.dashboard.run_search_commission(this, event)" data-field="keyword" />
					<div class="input-group w-px-200 xs:mw-auto xs:flex-fill">
						<select uid="{$uid}" class="form-control form-select search_field" 
							onChange="$Core.global.dashboard.run_search_commission(this, event)" data-field="month">
							<option>Tháng</option>
							{foreach from=$list_months item = _month}
							<option value="{$_month}">Tháng {$_month}</option>
							{/foreach}
						</select>
						<select uid="{$uid}" class="form-control form-select search_field" 
							onChange="$Core.global.dashboard.run_search_commission(this, event)" data-field="year">
							<option value="">Năm</option>
							{foreach from=$list_years item = _year}
							<option value="{$_year}">Năm {$_year}</option>
							{/foreach}
						</select>
					</div>
					<select uid="{$uid}" class="form-control form-select search_field w-px-140" data-field="status_id" 
						onChange="$Core.global.dashboard.run_search_commission(this, event)">
						<option value="">Tất cả</option>
						{$clsProperty->getSelectByProperty("COMMISSION_PAYMENT_STATUS")}
					</select>
				</div>
				{if $is_tab eq '1'}
				<ul class="nav nav-tabs nav-pills nav-fill mb-2" role="tablist">
					<li class="nav-item flex-fill" role="presentation">
						<button type="button" onClick="$Core.global.dashboard.sw_tab_commission(this, event)" 
							class="nav-link js__commission-link active" holderG="_me" role="tab" uid="{$uid}">Sales bán</button>
					</li>
					<li class="nav-item flex-fill" role="presentation">
						<button type="button" onClick="$Core.global.dashboard.sw_tab_commission(this, event)" 
							class="nav-link js__commission-link" holderG="_sales" role="tab" uid="{$uid}">HH Lead</button>
					</li>
				</ul>
				{else}
					<input type="hidden" class="js__commission-link active" holderG="_me" value="_me" />
				{/if}
				<div class="table-container no-shadow text-nowrap overflow-auto">
					<table class="table table-commission dragable mb-0" cellspacing="0" cellpadding="0">
						<thead class="sticky zindex-3 top-0"><tr>
							<th class="align-center bg-lighter text-center w-px-30 h-px-40">No</th>
							<th class="align-center bg-lighter h-px-40">Mã căn</th>
							<th class="align-center bg-lighter text-center h-px-40">Ngày ký HĐMB</th>
							<th class="align-center bg-lighter text-right h-px-40">Tổng tiền</th>
							<th class="align-center bg-lighter text-center w-px-30 h-px-40"></th>
						</tr></thead>
						<tbody class="holder_commission_{$uid}">
							{section name=i loop=$list_preloaders}
							<tr>
								<td><div class="animate-bg w-100 h-px-15 rounded-3"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-3"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-3"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-3"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-3"></div></td>
							</tr>
							{/section}
						</tbody>
						<tfoot class="tfoot_commission_{$uid} sticky zindex-3 bottom-0">
							
						</tfoot>
					</table>
				</div>
			</div>
			<div class="modal-footer py-2 flex-wrap justify-content-between">
				{if !empty($arr_status)}
					<div class="d-flex align-items-center gap-2 xs:w-100 xs:justify-content-center">
						{foreach from=$arr_status item = _oStatus}
							{$_oStatus}
						{/foreach}
					</div>
				{/if}
				<button type="button" class="btn xs:flex-fill xs:w-100 btn-outline-default" data-bs-dismiss="modal">
					<i class="bx bx-x"></i> Đóng lại
				</button>
			</div>
		</form>
	</div>
{else}
	{if !empty($arr_billings)}
		{foreach from=$arr_billings name=i item = _oBilling}
		<tr>
			<td class="align-center text-center{if $smarty.foreach.i.last && $total_billings gte $stand_rows} border-bottom-0{/if}">
				{$smarty.foreach.i.iteration}
			</td>
			<td class="align-center{if $smarty.foreach.i.last && $total_billings gte $stand_rows} border-bottom-0{/if}">
				{$_oBilling.status_name}
				<a class="text-link" billing_id="{$_oBilling.billing_id}">{$_oBilling.stock_code}</a>
			</td>
			 <td class="text-center text-tight{if $smarty.foreach.i.last && $total_billings gte $stand_rows} border-bottom-0{/if}">
				{if $_oBilling.contract_status_id eq $smarty.const._CONTRACT_STATUS_DONE_ID}
					{$clsISO->convertTimeToText($_oBilling.contract_date)}
				{else}
					<span class="text-muted">Chưa ký</span>
				{/if}
			</td> 
			<td class="align-center text-right{if $smarty.foreach.i.last && $total_billings gte $stand_rows} border-bottom-0{/if}">
				{$clsISO->shortNumber($_oBilling.sales_total_amount)}
			</td>
			<td class="align-center text-center">
				<a class="btn btn-sm btn-icon btn-outline-default" call_from="commission" billing_id="{$_oBilling.billing_id}" 
					onClick="$Core.global.dashboard.view_billing(this,event)"><i class="bx bx-link-external text-fs-12"></i></a>
			</td>
		</tr>
		{/foreach}
	{else}
		<tr class="nohover">
			<td class="text-center text-muted border-0" colspan="5">
				<div class="p-3">
					<img src="{$URL_IMAGES}/DataEmpty.svg" class="w-px-75 mb-2" />
					<p class="mb-0">Chưa có giao dịch nào được ghi nhận</p>
				</div>
			</td>
		</tr>
	{/if}
{/if}
<style>
	.pagination{
		margin-bottom:0 !important;
	}
	.table-commission td{
		background:var(--bs-white);
	}
	.table-commission tr th:nth-child(2),
	.table-commission tr td:nth-child(2){
		position:sticky;
		left:41px;
	}
</style>