<div class="form-row">
	<div class="col-12 col-md-6 col-xxl-3 mb-2">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_total_billing"></span>
						<span class="">Tổng giao dịch</span>
					</div>
					<span class="fs-8 fw-semibold">{$total_billings} GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_total_grand"></span>
						<span class="">Tổng doanh số</span>
					</div>
					<span class="fs-8 fw-semibold">{$clsISO->shortNumber($total_grand,1)}</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_primary"></span>
						<span class="">Sơ cấp</span>
					</div>
					<span class="fs-8 fw-semibold">{$total_billings - $total_trans_billings} GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_transfer"></span>
						<span class="">Độc quyền</span>
					</div>
					<span class="fs-8 fw-semibold">{$total_billing_dq} GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_contract"></span>
						<span class="">Đã ký HĐMB</span>
					</div>
					<span class="fs-8 fw-semibold">{$total_registed_hdmb} GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_calendar"></span>
						<span class="">Có lịch ký HĐMB</span>
					</div>
					<span class="fs-8 fw-semibold">{$total_unregisted_hdmb} GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_no_calendar"></span>
						<span class="">Chưa có lịch ký</span>
					</div>
					<span class="fs-8 fw-semibold">{$total_not_schedule_hdmb} GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between ">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_ratio"></span>
						<span class="">Tỷ lệ ký</span>
					</div>
					<span class="fs-8 fw-semibold">{$clsISO->getRateNumber($total_registed_hdmb,$total_billings)}%</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-6 col-xxl-9 mb-2">
		{if !empty($lstDepChild)}
		<div class="lst_area_billing owl owl-carousel h-100" data-md-slide="2.3" data-sm-slide="1.5" data-xs-slide="1.5" data-lg-slide="4" 
			data-dots="1" data-nav="0" data-loop="0" data-margin="10">
			{foreach from=$lstDepChild item=_oItem key=key name=i}
				{if !empty($_oItem.total_billing)}
					{assign var=total_billing value=$_oItem.total_billing}
					{assign var=total_trans_billing value=$_oItem.total_trans_billings}
					{assign var=total_billing_dq value=$_oItem.total_billing_dq}
				{else}
					{assign var=total_billing value=0}
					{assign var=total_trans_billing value=0}
					{assign var=total_billing_dq value=0}
				{/if}
				{assign var=rate value=$clsISO->getRateNumber($_oItem.total_registed_hdmb,$total_billing)}
				<div class="item_area_billing card h-100">
					<div class="card-header item_top pb-2" style="background-color: {$_oItem.bgcolor};color: {$_oItem.textcolor}">
						<h4 class="mb-2">{$_oItem.title}</h4>
						<p class="mb-0 fs-20">{$total_billing} GD</p>
					</div>
					<div class="card-body item_body p-2 pb-3">
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_total_grand"></span>
								<span class="">Tổng doanh số</span>
							</div>
							<span class="fs-8 fw-semibold">{$clsISO->shortNumber($_oItem.totalgrand,1)}</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_primary"></span>
								<span class="">Sơ cấp</span>
							</div>
							<span class="fs-8 fw-semibold">{$total_billing - $total_trans_billing} GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_transfer"></span>
								<span class="">Độc quyền</span>
							</div>
							<span class="fs-8 fw-semibold">{if !empty($total_billing_dq)}{$total_billing_dq}{else}0{/if} GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_contract"></span>
								<span class="">Đã ký HĐMB</span>
							</div>
							<span class="fs-8 fw-semibold">{if !empty($_oItem.total_registed_hdmb)}{$_oItem.total_registed_hdmb}{else}0{/if} GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_calendar"></span>
								<span class="">Có lịch ký HĐMB</span>
							</div>
							<span class="fs-8 fw-semibold">{if !empty($_oItem.total_unregisted_hdmb)}{$_oItem.total_unregisted_hdmb}{else}0{/if} GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_no_calendar"></span>
								<span class="">Chưa có lịch ký</span>
							</div>
							<span class="fs-8 fw-semibold">{if !empty($_oItem.total_not_schedule_hdmb)}{$_oItem.total_not_schedule_hdmb}{else}0{/if} GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between ">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_ratio"></span>
								<span class="">Tỷ lệ ký</span>
							</div>
							<span class="fs-8 fw-semibold">{if !empty($rate)}{$rate}{else}0{/if}%</span>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
		{/if}
	</div>
</div>