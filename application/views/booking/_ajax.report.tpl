{if $is_project_block eq '1'}
	{assign var = _PROJECT_NAME value = $clsProperty->getCode($block_id)}
{else}
	{assign var = _PROJECT_NAME value = $clsProject->getCode($project_id)}
{/if}

<div class="row">
	<div class="col-12 col-lg-6 mb-2 mb-lg-0">
		<div class="overflow-x-auto">
			<table cellpadding="0" cellspacing="0" class="table table-report table-bordered">
				<thead><tr>
					<th class="text-center bg-header text-white fs-5 fw-bold h-px-40" colspan="8">BÁO CÁO CỌC TỒN DỰ ÁN {$_PROJECT_NAME}</th>
				</tr>
				<tr>
					<th class="bg-head text-center fw-bold">Tháng</th>
					<th class="bg-head text-center fw-bold">Tổng số</th>
					<th class="bg-head text-center fw-bold fw-bold">Doanh số</th>
					<th class="bg-head text-center fw-bold">ĐQ</th>
					<th class="bg-head text-center fw-bold">Chéo</th>
					<th class="bg-head text-center bg-pink">VBTT</th>
					<th class="bg-head text-center bg-pink">HĐMB</th>
					<th class="bg-head text-center bg-pink">Thu hồi</th>
				</tr></thead>
				<tbody>
					{foreach from=$list_months item = _oMonth}
					<tr>
						<td class="text-center fw-bold">{$_oMonth.month|date_format:"%m/%Y"}</td>
						<td class="text-center text-nowrap fw-bold">
							{$_oMonth.total_month_billings}
							<a onClick="$Core.booking.open_billing(this, event)" class="text-link cursor-pointer" 
							project_id="{$project_id}" billing_type="{$billing_type}" month="{$_oMonth.month|date_format:'%m/%Y'}" holderG="all_fund"><i class='text-fs-12 bx bx-link-external'></i></a>
						</td>
						<td class="text-center text-nowrap fw-bold">{$clsISO->formatPrice($_oMonth.total_month_sales)}</td>
						<td class="text-center text-nowrap fw-bold">{$_oMonth.total_month_f1}
							<a onClick="$Core.booking.open_billing(this, event)" class="text-link cursor-pointer" 
							project_id="{$project_id}" billing_type="{$billing_type}" month="{$_oMonth.month|date_format:'%m/%Y'}" holderG="f1_fund"><i class='text-fs-12 bx bx-link-external'></i></a>
						</td>
						<td class="text-center text-nowrap fw-bold">{$_oMonth.total_month_cross}
							<a onClick="$Core.booking.open_billing(this, event)" class="text-link cursor-pointer" 
							project_id="{$project_id}" billing_type="{$billing_type}" month="{$_oMonth.month|date_format:'%m/%Y'}" holderG="cross_fund"><i class='text-fs-12 bx bx-link-external'></i></a>
						</td>
						<td class="text-center text-nowrap bg-pink">{$_oMonth.total_month_agrees} 
							<a onClick="$Core.booking.open_billing(this, event)" class="text-link cursor-pointer" 
							project_id="{$project_id}" billing_type="{$billing_type}" month="{$_oMonth.month|date_format:'%m/%Y'}" holderG="agree_signed"><i class='text-fs-12 bx bx-link-external'></i></a>
						</td>
						<td class="text-center text-nowrap bg-pink">{$_oMonth.total_month_contracts} 
							<a onClick="$Core.booking.open_billing(this, event)" class="text-link cursor-pointer" 
							project_id="{$project_id}" billing_type="{$billing_type}" month="{$_oMonth.month|date_format:'%m/%Y'}" holderG="contract_signed"><i class='text-fs-12 bx bx-link-external'></i></a>
						</td>
						<td class="text-center bg-pink">0</td>
					</tr>
					{/foreach}
				<tbody>	
				<tfoot><tr>
					<th class="fw-bold text-center bg-head">Tổng</th>
					<th class="fw-bold text-center bg-head">{$total_billings}</th>
					<th class="fw-bold text-center bg-head">{$clsISO->formatPrice($total_sales)}</th>
					<th class="fw-bold text-center bg-head">{$total_f1}</th>
					<th class="fw-bold text-center bg-head">{$total_cross}</th>
					<th class="fw-bold text-center bg-pink bg-head">{$total_agrees}</th>
					<th class="fw-bold text-center bg-pink bg-pink bg-head">{$total_contracts}</th>
					<th class="fw-bold text-center bg-pink bg-head">0</th>
				</tr></tfoot>
			</table>
		</div>
	</div>
	<div class="col-12 col-lg-6">
		<div class="overflow-x-auto">
			<table cellpadding="0" cellspacing="0" class="table table-report table-bordered">
				<thead><tr>
					<th class="text-center bg-header text-white fs-5 fw-bold h-px-40" colspan="4">BÁO CÁO CỌC TỒN DỰ ÁN 
						{$_PROJECT_NAME}
					</th>
				</tr>
				<tr>
					<th width="10%" class="text-center bg-head fw-bold">STT</th>
					<th class="fw-bold bg-head">Nội dung</th>
					<th class="fw-bold bg-head text-center">Số lượng</th>
					<th class="fw-bold bg-head text-center">Số tiền</th>
				</tr>
				<tr>
					<th class="text-center fw-bold bg-pink" colspan="4">Cọc tồn trong Công ty</th>
				</tr></thead>
				<tbody>
					<tr>
						<td class="text-center">1</td>
						<td class="fw-bold">Booking vào {$smarty.const.BRAND_NAME}</td>
						<td class="fw-bold text-main text-center">{$total_bookings}</td>
						<td class="fw-bold text-center">{$clsISO->formatPrice($total_amount_bookings)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">2</td>
						<td class="fst-italic">Khớp cọc chết</td>
						<td class="text-center">{$total_matched_bookings}</td>
						<td class="text-center">{$clsISO->formatPrice($total_amount_matched_bookings)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">3</td>
						<td class="fst-italic">Đã hoàn</td>
						<td class="text-center">{$total_refund_bookings}</td>
						<td class="text-center">{$clsISO->formatPrice($total_amount_refund_bookings)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">4</td>
						<td class="fst-italic">Tiền 10% vào {$smarty.const.BRAND_NAME}</td>
						<td class="text-center">
							{$total_trans_deposit_paid}
							<a onClick="$Core.booking.open_billing(this, event)" holderG="deposit_paid_to_company" class="text-link cursor-pointer" 
							project_id="{$project_id}" billing_type="{$billing_type}" holderG="sold"><i class='text-fs-12 bx bx-link-external'></i></a>
						</td>
						<td class="text-center">{$clsISO->formatPrice($total_amount_trans_deposit_paid)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">5</td>
						<td class="fst-italic">Số tiền đã đi vào CĐT</td>
						<td></td>
						<td class="text-center">0 {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">6</td>
						<td class="fst-italic">Tổng tồn 10%</td>
						<td class="text-center">0</td>
						<td class="text-center">{$clsISO->formatPrice($total_amount_trans_deposit_paid)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="fw-bold bg-head text-center" colspan="2">Tổng số cọc tồn</td>
						<td class="fw-bold bg-head text-main text-center">{$total_balance_bookings}</td>
						<td class="fw-bold bg-head text-center text-main">{$clsISO->formatPrice($total_amount_balance_bookings)} {$clsISO->getRate()}</td>
					</tr>
				<tbody>
				<thead>
					<tr>
						<th class="text-center fw-bold bg-pink" colspan="4">Cọc tồn trong Chủ đầu tư</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center">1</td>
						<td class="fw-bold">Booking vào CĐT</td>
						<td class="fw-bold text-main text-center">{$total_invest_bookings}</td>
						<td class="fw-bold text-main text-center">{$clsISO->formatPrice($total_amount_invest_bookings)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">2</td>
						<td class="fst-italic">Tra soát sang tiến độ 10%</td>
						<td class="text-center">{$total_payment_invest_bookings}</td>
						<td class="text-center">{$clsISO->formatPrice($total_amount_payment_invest_bookings)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">3</td>
						<td class="fst-italic">Khớp BK</td>
						<td class="text-center">{$total_matched_invest_bookings}</td>
						<td class="text-center">{$clsISO->formatPrice($total_amount_matched_invest_bookings)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">4</td>
						<td class="fst-italic">Căn thu hồi</td>
						<td class="text-center">{$total_recall_invest_bookings}</td>
						<td class="text-center">{$clsISO->formatPrice($total_amount_recall_invest_bookings)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">5</td>
						<td class="fst-italic">Đã bán (QĐQ)</td>
						<td class="text-center">{$total_solds}
							<a onClick="$Core.booking.open_billing(this, event)" project_id="{$project_id}" billing_type="{$billing_type}" 
							holderG="f1_fund" class="text-link cursor-pointer"><i class='text-fs-12 bx bx-link-external'></i></a>
						</td>
						<td class="text-center">{$clsISO->formatPrice($total_solds*50000000)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center">6</td>
						<td class="fst-italic">Căn đang ôm</td>
						<td class="text-center">{$total_hold_bookings}</td>
						<td class="text-center">{$clsISO->formatPrice($total_amount_hold_bookings)} {$clsISO->getRate()}</td>
					</tr>
					<tr>
						<td class="text-center bg-head fw-bold" colspan="2">Tổng tồn</td>
						<td class="text-center bg-head fw-bold text-main">{$total_balance_invest_bookings}</td>
						<td class="text-center bg-head fw-bold text-main">{$clsISO->formatPrice($total_amount_balance_invest_bookings)} {$clsISO->getRate()}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
