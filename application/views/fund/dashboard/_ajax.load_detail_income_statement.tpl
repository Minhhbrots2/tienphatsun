<div class="modal-dialog modal-ipad-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Báo cáo kết quả hoạt động kinh doanh <small>({$start_date}-{$end_date})</small></h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="card-body py-0 my-3">
			<div class="table-container overflow-auto table_cash_flow" style="max-height: 80vh">
				<table class="table mb-0 table-bordered dragable installed" cellpadding="0" cellspacing="0" width="100%">
					<thead class="position-sticky top-0 zindex-3"><tr>
						<th class="align-center text-center h-px-40 bg-lighter" width="3%">STT</th>
						<th class="align-center h-px-40 bg-lighter">Chỉ tiêu</th>
						<th class="align-center h-px-40 bg-lighter text-left">Mã số</th>
						<th class="align-center h-px-40 text-right" style="background-color: #f4fcf4">Số cuối kỳ</th>
						<th class="align-center h-px-40 text-right" style="background-color: #f9efef">Số đầu kỳ</th>
					</tr></thead>
					<tbody>
						{if !empty($lstReport)}
							{foreach from=$lstReport item=_oItem key=key name=i}
								<tr class="fw-bold text-dark">
									<td class="text-center">{$smarty.foreach.i.iteration}</td>
									<td class="text-nowrap">{$_oItem.title}</td>
									<td class="text-center text-nowrap">{$_oItem.code}</td>
									{if $_oItem.total lt 0}
										<td class="text-right text-danger" style="background-color: #f4fcf4">({$clsISO->formatPrice(abs($_oItem.total))}{$clsISO->getRate()})</td>
									{else}
										<td class="text-right" style="background-color: #f4fcf4">{$clsISO->formatPrice($_oItem.total)}{$clsISO->getRate()}</td>
									{/if}
									{if $_oItem.total_prev lt 0}
										<td class="text-right text-danger" style="background-color: #f4fcf4">({$clsISO->formatPrice(abs($_oItem.total_prev))}{$clsISO->getRate()})</td>
									{else}
										<td class="text-right" style="background-color: #f9efef">{$clsISO->formatPrice($_oItem.total_prev)}{$clsISO->getRate()}</td>
									{/if}
								</tr>							
							{/foreach}
						{/if}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>