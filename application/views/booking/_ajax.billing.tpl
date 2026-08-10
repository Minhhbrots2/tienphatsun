<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-ipad-xl">
	<form method="POST" action="#" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Giao dịch {$titlePage}<br />
				<span class="text-muted text-fs-12">Tổng cộng <strong class="text-main">{$total_billings}</strong> giao dịch!</span>
			</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="table-container no-shadow overflow-auto text-nowrap" style="max-height:calc(100vh - 210px)">
				<table cellpadding="0" cellspacing="0" class="table dragable table-bordered mb-0" width="100%">
					<thead class="sticky top-0 zindex-3"><tr>
						<th class="align-center h-px-35 bg-lighter">Mã căn</th>
						<th class="align-center h-px-35 bg-lighter">Ngày cọc</th>
						<th class="align-center h-px-35 bg-lighter">Phòng ban</th>
						<th class="align-center h-px-35 bg-lighter">Sale bán</th>
						<th class="align-center h-px-35 bg-lighter">Doanh số</th>
					</tr></thead>
					<tbody>
						{if !empty($list_billings)}
							{foreach name=i from=$list_billings item = _oBilling}
							{assign var = staff_id value = $_oBilling.staff_id}
							<tr>
								<td class="align-center text-left">
									{if $_oBilling.is_cancel eq '1'}
									<span class="label bg-purple">Hủy</span>
									{/if}
									{if $_oBilling.is_deposit_paid eq '1'}
									<span class="badge bg-label-danger">Đóng 10%</span>
									{/if}
									<a class="text-link" onClick="$Core.global.billing.view_billing(this, event)" billing_id="{$_oBilling.billing_id}">{$_oBilling.stock_code}</a>
									{$_oBilling.billing_source}
								</td>
								<td class="align-center text-center">{$clsISO->convertTimeToText($_oBilling.deposit_date, true)}</td>
								<td class="align-center text-left">{$arr_staffs[$staff_id].department_name}</td>
								<td class="align-center text-left">{$arr_staffs[$staff_id].staff_name}</td>
								<td class="align-center text-center">{$clsISO->formatPrice($_oBilling.totalgrand)}</td>
							</tr>
							{/foreach}
						{else}
							<tr class="nohover">
								<td class="text-center" colspan="5">
									<img src="{$URL_IMAGES}/listing-empty.svg" class="w-px-100" />
									<p class="text-muted">Chưa có giao dịch</p>
								</td>
							</tr>
						{/if}
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
		</div>
	</form>
</div>