<div class="lst_area_billing h-100 form-row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4" >
	{foreach from=$lstDep item=_oItem key=key name=i}
		{assign var=arr_report_dep value=$arr_report[$key]}
		<div class="col mb-2 flex-fill">
			<div class="item_area_billing h-100 border rounded-3">
				<div class="card-header item_top pb-2" style="background-color: {$_oItem.bgcolor};color: {$_oItem.textcolor}">
					<h4 class="mb-2">{$_oItem.title}</h4>
				</div>
				<div class="card-body item_body p-2 pb-3">
					<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
						<div class="d-flex gap-1 align-items-center">
							<span class="icon_bill icon_total_billing"></span>
							<span class="">Doanh thu</span>
						</div>
						{if !empty($arr_report_dep.credit_amount)}
							<span class="fs-8 fw-semibold">{$clsISO->shortNumber($arr_report_dep.credit_amount,1)}</span>
						{else}
							<span class="fs-8 fw-semibold">0đ</span>
						{/if}
					</div>
					<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
						<div class="d-flex gap-1 align-items-center">
							<span class="icon_bill icon_total_grand"></span>
							<span class="">Chi phí</span>
						</div>
						{if !empty($arr_report_dep.debit_amount)}
							<span class="fs-8 fw-semibold">{$clsISO->shortNumber($arr_report_dep.debit_amount,1)}</span>
						{else}
							<span class="fs-8 fw-semibold">0đ</span>
						{/if}
					</div>
					<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
						<div class="d-flex gap-1 align-items-center">
							<span class="icon_bill icon_ratio"></span>
							<span class="">Lợi nhuận trước thuế</span>
						</div>
						{if !empty($arr_report_dep.total_profit)}
							<span class="fs-8 fw-semibold">{$clsISO->shortNumber($arr_report_dep.total_profit,1)}</span>
						{else}
							<span class="fs-8 fw-semibold">0đ</span>
						{/if}
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex gap-1 align-items-center">
							<span class="icon_bill icon_contract"></span>
							<span class="">Lợi nhuận sau thuế</span>
						</div>
						{if !empty($arr_report_dep.total_profit_last)}
							<span class="fs-8 fw-semibold">{$clsISO->shortNumber($arr_report_dep.total_profit_last,1)}</span>
						{else}
							<span class="fs-8 fw-semibold">0đ</span>
						{/if}
					</div>
				</div>
			</div>
		</div>
	{/foreach}
</div>