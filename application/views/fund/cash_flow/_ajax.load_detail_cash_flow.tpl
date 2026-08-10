<div class="modal-dialog modal-ipad-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Báo cáo tài chính {$title_page} <small>({$start_date}-{$end_date})</small></h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="card-body overflow-y-auto py-0 my-3" style="max-height: 80vh">
			<table class="table mb-0" cellpadding="0" cellspacing="0" width="100%">
				<thead class="position-sticky top-0 zindex-3"><tr>
					<th class="align-center text-center h-px-40 bg-lighter" width="3%">STT</th>
					<th class="align-center h-px-40 bg-lighter">Chỉ tiêu</th>
					<th class="align-center h-px-40 bg-lighter text-left">Mã số</th>
					<th class="align-center h-px-40 text-right" style="background-color: #f4fcf4">Số cuối kỳ</th>
					<th class="align-center h-px-40 text-right" style="background-color: #f9efef">Số đầu kỳ</th>
				</tr></thead>
				<tbody>
					{assign var=total value=0}
					{assign var=total_prev value=0}
					{if !empty($lstBuildAccount)}
						{foreach from=$lstBuildAccount item=_oItem key=key name=i}
							{if empty($_oItem.parent_id)}
								{assign var=total value=$total + $_oItem.total}
								{assign var=total_prev value=$total_prev + $_oItem.total_prev}
							{/if}
							<tr class="{if empty($_oItem.parent_id)}fw-bold text-dark{/if}">
								<td>{$_oItem.stt}</td>
								<td>{$_oItem.title}</td>
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
				<tfoot class="position-sticky bottom-0 zindex-3 text-main"><tr>
					<td class="align-center fw-bold h-px-40 bg-lighter text-upper text-center" colspan="3" >Tổng {$title_page}</td>
					{if $total lt 0}
						<td class="align-center fw-bold h-px-40 text-right text-danger" style="background-color: #f4fcf4">({$clsISO->formatPrice(abs($total))}{$clsISO->getRate()})</td>
					{else}
						<td class="align-center fw-bold h-px-40 text-right" style="background-color: #f4fcf4">{$clsISO->formatPrice($total)}{$clsISO->getRate()}</td>
					{/if}
					{if $total_prev lt 0}
						<td class="align-center fw-bold h-px-40 text-right text-danger" style="background-color: #f9efef">({$clsISO->formatPrice(abs($total_prev))}{$clsISO->getRate()})</td>
					{else}
						<td class="align-center fw-bold h-px-40 text-right" style="background-color: #f9efef">{$clsISO->formatPrice($total_prev)}{$clsISO->getRate()}</td>
					{/if}
				</tr></tfoot>
			</table>
		</div>
	</div>
</div>