<div class="modal-dialog modal-xxl">
	<form class="modal-content" id="form_{$uid}" enctype="multipart/form-data">
		<div class="modal-header{if $deviceType ne 'phone'} border-bottom{/if}">
			<h5 class="modal-title">Hiệu suất đầu tư {$oneStock.ms_code}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class=" mt-1 dragscroll mb-2  overflow-auto">
				<table class="table table-iloocal table-computer table-bordered mb-2">
					<tbody>
						<tr class="nohover">
							<td class="text-nowrap text-dark text-center" style="width:40px;background-color:#ffe0a1 !important;">1</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Giá trị căn hộ</td>
							<td class="text-nowrap text-dark fw-bold px-2" style="background-color:#ffe0a1 !important;">{$clsISO->priceFormat($oneStock.total_price_vat)} đ</td>
							<td class="text-nowrap text-dark fw-bold bg-main text-white">{$oneStock.text_stock}</td>
						</tr>
						<tr class="nohover">
							<td class="text-nowrap text-dark text-center" style="width:40px;background-color:#ffe0a1 !important;">2</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">
								<div class="d-flex align-items-center justify-content-between gap-1">
									Vốn tự có 
									<div class="d-flex align-items-center gap-1">
										<input class="form-control w-px-50 input_no_outer_spin text-center px-1" onChange="$Core.performance.loadCapital(this,event)" type="number" step="0.1" name="capital_precent" value="20" uid="{$uid}">%
									</div>
								</div>
							</td>
							<td class="text-nowrap text-dark fw-bold td_capital px-2" style="background-color:#ffe0a1 !important;">{$clsISO->priceFormat($oneStock.capital)} đ</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Theo CSBH T7/2024</td>
						</tr>
						<tr class="nohover">
							<td class="text-nowrap text-dark text-center" style="width:40px;background-color:#ffe0a1 !important;">3</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Vay</td>
							<td class="text-nowrap text-dark fw-bold px-2 td_loan" style="background-color:#ffe0a1 !important;">{$clsISO->priceFormat($oneStock.loan)} đ</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Theo CSBH T7/2024 - vay Techcombank</td>
						</tr>
						<tr class="nohover">
							<td class="text-nowrap text-dark text-center" style="width:40px;background-color:#ffe0a1 !important;">4</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Thời điểm mua</td>
							<td class="text-nowrap text-dark fw-bold p-0" style="background-color:#ffe0a1 !important;">
								<input type="date" name="time_buy" data-type="time_buy" class="form-control-plaintext text-dark fw-bold fs-14 px-2 inp_date" value="{$today}" onchange="$Core.performance.loadTime(this,event)" uid="{$uid}">
							</td>
							<td class="text-nowrap text-dark " style="background-color:#ffe0a1 !important;">Hiện tại</td>
						</tr>
						<tr class="nohover">
							<td class="text-nowrap text-dark text-center" style="width:40px;background-color:#ffe0a1 !important;">5</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Thời điểm nhận bàn giao</td>
							<td class="text-nowrap text-dark fw-bold px-2 time_handover" style="background-color:#ffe0a1 !important;">{$time_handover}</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Sau 1 tháng</td>
						</tr>
						<tr class="nohover">
							<td class="text-nowrap text-dark text-center" style="width:40px;background-color:#ffe0a1 !important;">6</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Thời điểm chốt lời</td>
							<td class="text-nowrap text-dark fw-bold p-0" style="background-color:#ffe0a1 !important;">
								<input type="date" name="time_profit" data-type="time_profit" class="form-control-plaintext text-dark fw-bold fs-14 px-2 inp_date" value="{$time_profit}" onchange="$Core.performance.loadTime(this,event)" uid="{$uid}" min="{$today}">
							</td>
							<td class="text-nowrap text-dark title_profit" style="background-color:#ffe0a1 !important;">Sau 24 tháng chốt lời và bán</td>
						</tr>
						<tr class="nohover">
							<td class="text-nowrap text-dark text-center" style="width:40px;background-color:#ffe0a1 !important;">7</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Thời gian cho thuê</td>
							<td class="text-nowrap text-dark fw-bold p-0" style="background-color:#ffe0a1 !important;">
								<input type="date" name="time_leasing" data-type="time_leasing" class="form-control-plaintext text-dark fw-bold fs-14 px-2 inp_date" value="{$time_leasing}" onchange="$Core.performance.loadTime(this,event)" uid="{$uid}" min="{$today}">
							</td>
							<td class="text-nowrap text-dark title_leasing" style="background-color:#ffe0a1 !important;">Sau 3 tháng kể từ lúc mua</td>
						</tr>
						<tr class="nohover">
							<td class="text-nowrap text-dark text-center" style="width:40px;background-color:#ffe0a1 !important;">8</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Thời điểm hết AHNG</td>
							<td class="text-nowrap text-dark fw-bold p-0" style="background-color:#ffe0a1 !important;">
								<input type="date" name="time_AHNG" data-type="time_AHNG" class="form-control-plaintext text-dark fw-bold fs-14 px-2 inp_date" value="{$time_AHNG}" onchange="$Core.performance.loadTime(this,event)" uid="{$uid}">
							</td>
							<td class="text-nowrap text-dark" style="background-color:#ffe0a1 !important;">Theo CSBH T7/2024</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class=" mt-1 table-wrapper overflow-auto d-flex flex-wrap">
				<table class="table table-iloocal table-computer table-bordered mb-2">
					<thead>
						<tr class="nohover">
							<th class="text-left text-dark text-center" width="5%" style="width:40px;background-color: #c84a4a !important">STT</th>
							<th class="text-left text-dark" width="20%" style="background-color: #c84a4a !important">Dòng tiền</th>
							<th class="text-left text-dark" width="40%" style="background-color: #c84a4a !important">Chi tiết</th>
							<th class="text-center text-dark text-center" width="10%" style="background-color: #c84a4a !important">Giá trị</th>
							<th class="text-center text-dark text-wrap text-center" width="10%" style="background-color: #c84a4a !important">Thời gian (tháng)</th>
							<th class="text-right text-dark" width="15%" style="background-color: #c84a4a !important">Số tiền</th>
						</tr>
					</thead>
					<tbody>
						<tr class="nohover">
							<td class="text-dark text-nowrap text-center" style="width:40px;background-color: #e96363 !important;">I</td>
							<td class="text-dark text-nowrap" style="background-color: #e96363 !important;">Nguồn thu dự kiến</td>
							<td class="text-dark text-nowrap" colspan="4" style="background-color: #e96363 !important;"></td>
						</tr>
						<tr class="nohover tr_input tr_input_revenue">
							<td class="text-dark text-nowrap text-center" style="width:40px" >1</td>
							<td class="text-dark text-wrap">Tăng giá theo thị trường</td>
							<td class="text-dark text-nowrap">Thị trường CHCC có mức tăng giá hàng năm từ 8-12%/năm <br>(kết hợp cùng tăng giá của CĐT)</td>
							<td class="text-dark text-nowrap text-center td_value td_input" contenteditable="true" onblur="$Core.performance.loadPriceTable(this,event)" unit_type="_PERCENT" field="value" field_value="10" type="_REVENUE" uid="{$uid}">10%</td>
							<td class="text-dark text-nowrap text-center price-In td_input td_time time_loan" field="time" field_value="24" type="_REVENUE" onKeyUp="$Core.performance.changeTime(this,event)" onblur="$Core.performance.loadPriceTable(this,event)" uid="{$uid}" max="600" >24</td>
							<td class="text-dark text-nowrap text-right td_price td_input_revenue" price="{$price_loan}">{$clsISO->priceFormat($price_loan)} đ</td>
						</tr>
						{assign var=index_revenue value=2}
						{foreach from=$lst_revenue item=_oRevenue key=_kRevenue name=i}
							{assign var=lstChild value=$_oRevenue.lstChild}
							{foreach from=$lstChild item=_oItem name=j}
								<tr class="nohover tr_input tr_input_revenue">
									{if $smarty.foreach.j.first}
										<td class="text-dark text-nowrap text-center" style="width:40px" rowspan="{$lstChild|@count}" >{$index_revenue}</td>
										<td class="text-dark text-wrap" rowspan="{$lstChild|@count}">{$_oRevenue.title}</td>
									{/if}
									<td class="text-dark text-wrap">{$_oItem.title}</td>
									<td class="text-dark text-nowrap text-center td_value {if $_oItem.unit_type eq '_PERCENT'}price-Float{else}price-In{/if} td_input" contenteditable="true" onblur="$Core.performance.loadPriceTable(this,event)" unit_type="{$_oItem.unit_type}" field="value" field_value="{$_oItem.value}" type="_REVENUE" uid="{$uid}">
										{if $_oItem.unit_type eq "_PERCENT"}
											{$_oItem.value}%
										{else}
											{$clsISO->priceFormat($_oItem.value)} đ
										{/if}
									</td>
									<td class="text-dark text-nowrap text-center price-In td_input td_time" field="time" field_value="{$_oItem.time}" type="_REVENUE" onKeyUp="$Core.performance.changeTime(this,event)" onblur="$Core.performance.loadPriceTable(this,event)" uid="{$uid}" max="600" >{$_oItem.time}</td>
									<td class="text-dark text-nowrap text-right td_price td_input_revenue" gid="{$_kRevenue}" price="{$_oItem.price}">{$clsISO->priceFormat($_oItem.price)} đ</td>
								</tr>
							{/foreach}						
							{math equation= "x+1" x=$index_revenue assign="index_revenue"}
						{/foreach}
						<tr class="nohover tr_input">
							<td class="text-dark text-nowrap fw-bold text-center" colspan="2" style="background-color: #f69898 !important">Dự phòng rủi ro</td>
							<td class="text-dark text-wrap fw-bold title_risk" style="background-color: #f69898 !important">Tính hệ số chỉ đạt 50% kế hoạch đề ra</td>
							<td class="text-dark text-nowrap fw-bold text-center price-In td_input td_value" contenteditable="true" onblur="$Core.performance.loadPriceTable(this,event)" unit_type="_PERCENT" field="value_risk" field_value="50" type="_REVENUE" price="{$price_risk}" uid="{$uid}" style="background-color: #f69898 !important">50%</td>
							<td class="text-dark text-nowrap fw-bold text-center" style="background-color: #f69898 !important"></td>
							<td class="text-dark text-nowrap fw-bold text-right td_price" style="background-color: #f69898 !important">{$clsISO->priceFormat($price_risk)} đ</td>
						</tr>
						<tr class="nohover tr_input">
							<td class="text-dark text-nowrap text-center" style="width:40px" >{$index_revenue}</td>
							<td class="text-dark text-wrap">Tiền cho thuê nhà</td>
							<td class="text-dark text-wrap">Tính tỉ lệ lấp đầy đạt 50% với giá trị cho thuê</td>
							<td class="text-dark text-nowrap text-center td_value td_input price-In" contenteditable="true" onblur="$Core.performance.loadPriceTable(this,event)" unit_type="_MONEY" field="value" field_value="8000000" type="_REVENUE" uid="{$uid}">8.000.000 đ</td>
							<td class="text-dark text-nowrap text-center price-In td_input td_time time_leasing" field="time" field_value="11" type="_REVENUE" onKeyUp="$Core.performance.changeTime(this,event)" onblur="$Core.performance.loadPriceTable(this,event)" uid="{$uid}" max="600" >11</td>
							<td class="text-dark text-nowrap text-right td_price" price="{$price_leasing}">{$clsISO->priceFormat($price_leasing)} đ</td>
						</tr>
						<!--------------------->
						<tr class="nohover">
							<td class="text-dark text-nowrap fw-bold text-center" colspan="5" style="background-color: #f69898 !important">Tổng lợi nhuận sau chốt lời</td>
							<td class="text-dark text-nowrap fw-bold text-right total_price_revenue" price="{$total_price_revenue}" style="background-color: #f69898 !important">{$clsISO->priceFormat($total_price_revenue)} đ</td>
						</tr>
						<!--------------------->
						
						<tr class="nohover">
							<td class="text-dark text-nowrap text-center" style="width:40px;background-color: #e96363 !important">II</td>
							<td class="text-dark text-nowrap" style="background-color: #e96363 !important;">Chi phí bỏ ra</td>
							<td class="text-dark text-nowrap" colspan="4" style="background-color: #e96363 !important;"></td>
						</tr>
						<tr class="nohover">
							<td class="text-dark text-nowrap text-center" style="width:40px" >1</td>
							<td class="text-dark text-wrap" >Vốn tự có</td>
							<td class="text-dark text-wrap">20% giá trị căn hộ</td>
							<td class="text-dark text-nowrap text-center td_percent_capital">20%</td>
							<td class="text-dark text-nowrap text-center"></td>
							<td class="text-dark text-nowrap text-right td_capital" >{$clsISO->priceFormat($oneStock.capital)} đ</td>
						</tr>
						{assign var=index_expense value=2}
						{foreach from=$lst_expense item=_oExpense name=i}
							{assign var=lstChild value=$_oExpense.lstChild}
							{if !empty($lstChild)}
								{foreach from=$lstChild item=_oItem name=j}
									<tr class="nohover tr_input">
										{if $smarty.foreach.j.first}
											<td class="text-dark text-nowrap text-center" style="width:40px" rowspan="{$lstChild|@count}" >{$index_expense}</td>
											<td class="text-dark text-wrap" rowspan="{$lstChild|@count}">{$_oExpense.title}</td>
										{/if}
										<td class="text-dark text-wrap {if !empty($_oItem.is_late_offer)}text-decoration-line-through{/if}">{$_oItem.title}</td>
										<td class="text-dark text-nowrap text-center {if $_oItem.unit_type eq '_MONEY'}price-In{/if} td_input td_value {if !empty($_oExpense.is_interest)}is_interest{/if} {if !empty($_oItem.is_late_offer)}text-decoration-line-through{/if}" contenteditable="true" onblur="$Core.performance.loadPriceTable(this,event)" unit_type="{$_oItem.unit_type}" field="value" field_value="{$_oItem.value}" type="_EXPENSE" uid="{$uid}" >
											{if $_oItem.unit_type eq "_PERCENT"}
												{$_oItem.value}%
											{else}
												{$clsISO->priceFormat($_oItem.value)} đ
											{/if}
										</td>
										
										<td class="text-dark text-nowrap text-center price-In td_input td_time {if !empty($_oExpense.is_interest)}is_interest{/if} {if !empty($_oItem.is_late_offer)}text-decoration-line-through{/if}" contenteditable="true" field="time" field_value="{$_oItem.time}" type="_EXPENSE" onKeyUp="$Core.performance.changeTime(this,event)" onblur="$Core.performance.loadPriceTable(this,event)" uid="{$uid}" max="600" >{$_oItem.time}</td>
										<td class="text-dark text-nowrap text-right td_price">{$clsISO->priceFormat($_oItem.price)} đ</td>
									</tr>
								{/foreach}
							{else}
								<tr class="nohover tr_input">
									<td class="text-dark text-nowrap text-center" style="width:40px">{$index_expense}</td>
									<td class="text-dark text-wrap">{$_oExpense.title}</td>									
									<td class="text-dark text-nowrap text-center"></td>
									<td class="text-dark text-nowrap text-center price-In td_input td_value " contenteditable="true" onblur="$Core.performance.loadPriceTable(this,event)" unit_type="_MONEY" field="value" field_value="0" type="_EXPENSE" uid="{$uid}" >0 đ</td>
									<td class="text-dark text-nowrap text-center" field="time" field_value="" type="_EXPENSE" onKeyUp="$Core.performance.changeTime(this,event)" onblur="$Core.performance.loadPriceTable(this,event)" uid="{$uid}"></td>
									<td class="text-dark text-nowrap text-right td_price">0 đ</td>
								</tr>
							{/if}
							{math equation= "x+1" x=$index_expense assign="index_expense"}
						{/foreach}
						<tr class="nohover tr_input">
							<td class="text-dark text-nowrap text-center" style="width:40px" rowspan="2">{$index_expense}</td>
							<td class="text-dark text-wrap" rowspan="2">Tiền lãi vay từ thời điểm hết HTLS</td>
							<td class="text-dark text-wrap title_interest_first">Lãi suất trong 12 tháng đầu tiên</td>
							<td class="text-dark text-nowrap text-center td_input td_value is_interest " contenteditable="true" onblur="$Core.performance.loadPriceTable(this,event)" unit_type="_PERCENT" field="value" field_value="7.5" type="_EXPENSE" uid="{$uid}">7.5%</td>
							<td class="text-dark text-nowrap text-center price-In td_input td_time is_interest interest_first" field="time" field_value="12" type="_EXPENSE" onkeyup="$Core.performance.changeTime(this,event)" onblur="$Core.performance.loadPriceTable(this,event)" uid="{$uid}" max="600">12</td>
							<td class="text-dark text-nowrap text-right td_price">{$clsISO->priceFormat($interest_first)} đ</td>
						</tr>
						<tr class="nohover tr_input">
							<td class="text-dark text-wrap title_interest_last">Lãi suất trong 12 tháng tiếp theo</td>
							<td class="text-dark text-nowrap text-center td_input td_value is_interest" contenteditable="true" onblur="$Core.performance.loadPriceTable(this,event)" unit_type="_PERCENT" field="value" field_value="9" type="_EXPENSE" uid="{$uid}">9%</td>
							<td class="text-dark text-nowrap text-center price-In td_input td_time is_interest interest_last" field="time" field_value="12" type="_EXPENSE" onkeyup="$Core.performance.changeTime(this,event)" onblur="$Core.performance.loadPriceTable(this,event)" uid="{$uid}" max="600">12</td>
							<td class="text-dark text-nowrap text-right td_price">{$clsISO->priceFormat($interest_last)} đ</td>
						</tr>
					</tbody>
					<tfoot>
						<tr class="nohover">
							<td class="text-dark text-nowrap fw-bold text-center" colspan="5" style="background-color: #f69898 !important">Tổng chi phí bỏ ra</td>
							<td class="text-dark text-nowrap fw-bold text-right total_price_expense" price="{$total_price_expense}" style="background-color: #f69898 !important">{$clsISO->priceFormat($total_price_expense)} đ</td>
						</tr>
						<tr class="nohover">
							<td class="text-dark text-center" colspan="5" style="background-color: #c84a4a !important">TỶ SUẤT ĐẦU TƯ GIỮA LỢI NHUẬN/CHI PHÍ</td>
							<td class="text-dark text-nowrap fw-bold text-right rate_revenue_expense" style="background-color: #c84a4a !important">{$rate_revenue_expense}%</td>
						</tr>
						<tr class="nohover">
							<td class="text-dark text-center" colspan="5" style="width:40px;background-color: #e96363 !important">TỶ SUẤT ĐẦU TƯ GIỮA LỢI NHUẬN/CHI PHÍ /NĂM</td>
							<td class="text-dark text-nowrap fw-bold text-right rate_revenue_expense_1_year" style="width:40px;background-color: #e96363 !important">{$rate_revenue_expense_1_year}%</td>
						</tr>
					</tfoot>
				</table>
			</div>	
		</div>
		<input type="hidden" name="total_price_vat" value="{$oneStock.total_price_vat}">
		<input type="hidden" name="capital" value="{$oneStock.capital}">
		<input type="hidden" name="loan" value="{$oneStock.loan}">
	</form>
</div>