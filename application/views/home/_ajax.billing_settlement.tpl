<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-ipad-xl">
	<form class="modal-content">
		<input type="hidden" name="billing_id" value="{$oneBilling.billing_id|escape}">
		<input type="hidden" name="_r_base" value="{$st_r_base|escape}">
		{* cval THÔ (chưa fallback sang giá bán) — để JS tính chi phí công ty cho GD không dòng sale:
		   commission_value do admin nhập, chưa nhập (=0) thì back office để 0, không lấy giá bán *}
		<input type="hidden" name="_cval_raw" value="{$st_more.commission_value|escape}">
		<input type="hidden" name="_t_rate" value="{$st_more.commission|escape}">
		{* JS cần biết loại giao dịch: mặc định 50% hoa hồng sale chỉ áp cho bán nội bộ *}
		<input type="hidden" name="_deal_type" value="{$st_deal_type|escape}">
		<div class="modal-header">
			<h5 class="modal-title mb-0">
				<i class="bx bx-calculator me-1"></i> Quyết toán giao dịch
				{if $st_is_channel eq 1}<span class="badge bg-label-warning ms-2">PTĐT</span>{else}<span class="badge bg-label-primary ms-2">Sale nội bộ</span>{/if}
				{if $st_is_settled eq 1}<span class="badge bg-label-success ms-2">Đã quyết toán</span>{/if}
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">

			<div class="card shadow-none border mb-3">
				<div class="card-body p-3">
					<div class="row g-2 small">
						<div class="col-6 col-md-3">
							<div class="text-muted">Mã căn</div>
							<div class="fw-semibold">{$oneBilling.stock_code|escape}</div>
						</div>
						<div class="col-6 col-md-5">
							<div class="text-muted">Dự án</div>
							<div class="fw-semibold">{$project_title|escape}</div>
						</div>
						<div class="col-6 col-md-2">
							<div class="text-muted">Mức phí trả sales</div>
							<div class="fw-semibold">{$st_more.commission|escape}%</div>
						</div>
						<div class="col-6 col-md-2">
							<div class="text-muted">Giá trị bán</div>
							<div class="fw-semibold">{$oneBilling.totalgrand|number_format:0:",":"."}</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row g-3">
				<div class="col-12 col-lg-7">
					<div class="card shadow-none border h-100">
						<div class="card-header py-2"><h6 class="mb-0">Giảm trừ</h6></div>
						<div class="card-body p-3">
							<div class="mb-2">
								<label class="form-label mb-1 small">Tổng tiền giảm trừ</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control text-end price-In numberonly js__st-money" 
										name="total_deduction" value="{$st_more.total_deduction|escape}">
									<span class="input-group-text">đ</span>
								</div>
							</div>
							<div class="mb-2">
								<div class="form-row">
									<div class="col-6">
										<label class="form-label mb-1 small">Tỷ lệ sales chịu</label>
										<div class="input-group input-group-merge">
											<input type="text" class="form-control text-end js__st-pct" name="total_deduction_percent_sales" 
												value="{$st_more.total_deduction_percent_sales|escape}">
											<span class="input-group-text">%</span>
										</div>
									</div>
									<div class="col-6">
										<label class="form-label mb-1 small" title="Máy tự tính sẵn — gõ đè nếu cần">Sales chịu</label>
										<div class="input-group input-group-merge">
											<input type="text" class="form-control text-end numberonly js__st-amt js__st-money" name="total_deduction_sales" 
												value="{if $st_whole.deduction_sales}{$st_whole.deduction_sales|number_format:0:",":"."}{/if}" data-typed="{if $st_more.total_deduction_sales ne ''}1{else}0{/if}">
											<span class="input-group-text">đ</span>
										</div>
									</div>
								</div>
							</div>
							<div class="mb-2">
								<label class="form-label mb-1 small">Công ty chịu</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control text-end price-In numberonly js__st-money" 
										name="total_deduction_company" value="{$st_more.total_deduction_company|escape}">
									<span class="input-group-text">đ</span>
								</div>
							</div>
							<div class="mb-0">
								<label class="form-label mb-1 small">Công ty chịu trừ hoa hồng</label>
								<div class="input-group input-group-merge">
									{* Sửa được: công thức AB−AD−AE chỉ là gợi ý, thực tế admin nhập số khác — sổ gốc có ca
										   A-24-04 ghi 38.820.654 trong khi AB−AD ra 27.500.000. *}
									<input type="text" autocomplete="off" class="form-control text-end numberonly js__st-amt js__st-out-af"
										name="total_deduction_company_commission" onClick="this.select();"
										value="{if $st_whole.deduction_company_commission}{$st_whole.deduction_company_commission|number_format:0:",":"."}{/if}"
										data-typed="{if $st_more.total_deduction_company_commission ne ''}1{else}0{/if}">
									<span class="input-group-text">đ</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-5">
					<div class="card shadow-none border h-100">
						<div class="card-header py-2"><h6 class="mb-0">Hoa hồng sales <span class="text-muted small fw-normal">— tổng cả căn</span></h6></div>
						<div class="card-body p-3">
							<div class="mb-3">
								<label class="form-label mb-1 small">Tỷ lệ truy thu khách hàng</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control text-end js__st-pct" name="recovery_rate_customer" 
										value="{$st_more.recovery_rate_customer|escape}">
									<span class="input-group-text">%</span>
								</div>
							</div>
							<div class="alert alert-secondary py-2 mb-3">
								<small>% hoa hồng của <strong>từng sale</strong> nhập ở bảng bên dưới — mỗi người có thể một mức khác nhau.</small>
							</div>
							<div class="d-flex justify-content-between align-items-center mb-1">
								<span class="small text-muted">Số tiền hoa hồng sales</span>
								<span class="fw-semibold js__st-out-aj">{$st_total_sales_amount|number_format:0:",":"."}</span>
							</div>
							<div class="d-flex justify-content-between align-items-center mb-1">
								<span class="small text-muted">Trừ hoa hồng sales</span>
								<span class="fw-semibold js__st-out-al">{$st_total_sales_deduct|number_format:0:",":"."}</span>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<span class="small">Sales nhận sau truy thu</span>
								<span class="fw-bold text-primary js__st-out-am">{$st_total_sales_net|number_format:0:",":"."}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="card shadow-none border">
						<div class="card-header py-2 d-flex align-items-center justify-content-between">
							<h6 class="mb-0">Từng sale</h6>
							<span class="badge bg-label-primary">Tổng doanh số sau giảm trừ: <span class="js__st-out-ag-total">{$st_total_realized|number_format:0:",":"."}</span> đ</span>
						</div>
						<div class="card-body p-3">
							<div class="table-responsive">
								<table class="table table-sm mb-0 align-middle st-table">
									<thead><tr class="text-nowrap">
										<th>Sale</th>
										<th class="text-end">Tỷ lệ chia</th>
										<th class="text-end">Doanh số sau giảm trừ</th>
										{* Chỉ giao dịch bán nội bộ mới có hoa hồng sale. Đo sổ gốc: AI > 0 ở 233/320 dòng nội bộ,
										   nhưng chỉ 1/83 dòng PTĐT và 0/6 dòng F2 — PTĐT là bán cho CTV nên tiền đi theo vai
										   trò PTĐT chứ không qua % hoa hồng sale. *}
										{if $st_deal_type eq 'sale'}
										<th class="text-end w-px-125">% hoa hồng</th>
										<th class="text-end">Số tiền hoa hồng</th>
										<th class="text-end">Trừ hoa hồng</th>
										<th class="text-end">Nhận sau truy thu</th>
										{/if}
									</tr></thead>
									<tbody>
										{foreach from=$st_rows item=_r}
										<tr class="text-nowrap" data-ratio="{$_r.share_ratio|escape}" data-sid="{$_r.billing_sale_id|escape}">
											<td>
												{$_r.seller_name|escape}
												{if $_r.is_primary eq 1}<span class="badge bg-label-info ms-1">Sale chính</span>{/if}
											</td>
											<td class="text-end">{$_r.share_ratio|escape}%</td>
											<td class="text-end fw-semibold js__st-row-ag">{$_r.calc.realized|number_format:0:",":"."}</td>
											{if $st_deal_type eq 'sale'}
											<td>
												<div class="input-group input-group-sm input-group-merge">
													<input type="text" class="form-control text-end js__st-row-ai" name="share[{$_r.billing_sale_id|escape}][ai]" value="{$_r.sales_commission_rate|escape}" placeholder="50">
													<span class="input-group-text">%</span>
												</div>
											</td>
											<td class="text-end js__st-row-aj">{$_r.calc.sales_amount|number_format:0:",":"."}</td>
											<td class="text-end js__st-row-al">{$_r.calc.sales_deduct|number_format:0:",":"."}</td>
											<td class="text-end fw-semibold text-primary js__st-row-am">{$_r.calc.sales_net|number_format:0:",":"."}</td>
											{/if}
										</tr>
										{/foreach}
									</tbody>
								</table>
							</div>
							<div class="alert alert-warning py-2 mt-3 mb-0 d-none js__st-warn-ai">
								<i class="bx bx-error me-1"></i>
								<small>Có dòng đang để <strong>% hoa hồng lớn hơn 100%</strong>. Kiểm tra lại kẻo gõ nhầm — sheet gốc từng có ô ghi 1388,89% do nhập sai.</small>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="card shadow-none border">
						<div class="card-header py-2">
							<h6 class="mb-0">
								{if $st_is_channel eq 1}Cộng tác viên theo từng dòng{else}Quản lý &amp; cộng tác viên theo từng sale{/if}
								<span class="text-muted small fw-normal">
									{if $st_is_channel eq 1}— giao dịch của Ban Phát triển đối tác, hoa hồng đi theo vai trò PTĐT bên dưới{else}— co-sale khác phòng thì mỗi người một quản lý riêng{/if}
								</span>
							</h6>
						</div>
						<div class="card-body p-3">
							{foreach from=$st_rows item=_r name=rrow}
							<div class="border rounded p-3{if !$smarty.foreach.rrow.last} mb-3{/if}">
								<div class="d-flex align-items-center mb-2">
									<span class="fw-semibold">{$_r.seller_name|escape}</span>
									<span class="badge bg-label-secondary ms-2">{$_r.share_ratio|escape}%</span>
									{if $_r.is_primary eq 1}<span class="badge bg-label-info ms-1">Sale chính</span>{/if}
								</div>
								{* TPKD/GĐKD chỉ ăn trên giao dịch bán nội bộ — đo sổ gốc: 213/320 dòng nội bộ có TPKD
								   nhưng 0/83 dòng PTĐT và 0/6 dòng F2. Điều kiện cũ ("không phải PTĐT") để lọt F2. *}
								{if $st_deal_type eq 'sale'}
								<div class="row mb-2 g-2">
									<div class="col-12 col-md-6">
										<label class="form-label mb-1 text-nowrap" title="Trưởng phòng Kinh doanh">TPKD</label>
										<select data-placeholder="Chọn TPKD" class="form-control iso-selectizeSync" name="share[{$_r.billing_sale_id|escape}][tpkd]"
											data-width="100%" data-allow-clear="true">
											<option value="0">Chọn TPKD</option>
											{if !empty($st_staffs)}{foreach from=$st_staffs item=_oSt}
											<option{if $_r.head_of_dep_id eq $_oSt.profile_id} selected="selected"{/if} value="{$_oSt.profile_id|escape}">{$_oSt.full_name|escape}</option>
											{/foreach}{/if}
										</select>
									</div>
									<div class="col-12 col-md-6">
										<label class="form-label mb-1 text-nowrap" title="Giám đốc Kinh doanh">GĐKD</label>
										<select data-placeholder="Chọn GĐKD" class="form-control iso-selectizeSync" name="share[{$_r.billing_sale_id|escape}][gdkd]"
											data-width="100%" data-allow-clear="true">
											<option value="0">Chọn GĐKD</option>
											{if !empty($st_staffs)}{foreach from=$st_staffs item=_oSt}
											<option{if $_r.sale_dir_id eq $_oSt.profile_id} selected="selected"{/if} value="{$_oSt.profile_id|escape}">{$_oSt.full_name|escape}</option>
											{/foreach}{/if}
										</select>
									</div>
								</div>
							{* Tiền hoa hồng quản lý: tra bậc thang theo TỔNG doanh số của người đó trong cả
							   THÁNG KÝ, rồi nhân với doanh số của CHÍNH dòng chia này (V6/V7).
							   Để TRỐNG = dùng số bậc thang (hiện mờ trong ô); GÕ VÀO = đè cho riêng
							   giao dịch này. Cùng cơ chế đã dùng cho "Sales chịu", "Tiền CTV", "Tiền đại lý".
							   ⚠ Tháng chưa đóng thì đây là số TẠM: giao dịch còn lại của tháng chưa phát sinh
							   nên tổng còn thiếu, bậc có thể lên. *}
							<div class="row g-2 mt-1">
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 text-nowrap">Tỷ lệ</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end js__st-pct" name="share[{$_r.billing_sale_id|escape}][tpkd_rate]"
											value="{$_r.tpkd_calc.rate|escape}"
											title="Luỹ kế tháng của TPKD: {$_r.tpkd_calc.cumulative|number_format:0:",":"."} đ">
										<span class="input-group-text">%</span>
									</div>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 text-nowrap">Số tiền</label>
									<div class="input-group input-group-merge">
										{* KHÔNG price-In — plugin đó ghi "0" vào ô rỗng lúc focusout, biến ô xoá trắng thành
											   "đè bằng 0". Cùng lý do đã bỏ ở ô "Sales chịu" và ô tiền CTV. *}
											<input type="text" class="form-control text-end numberonly js__st-amt" name="share[{$_r.billing_sale_id|escape}][tpkd_amount]"
												value="{if $_r.tpkd_calc.amount ne ''}{$_r.tpkd_calc.amount|number_format:0:",":"."}{/if}" data-typed="{if $_r.tpkd_calc.manual}1{else}0{/if}">
										<span class="input-group-text">đ</span>
									</div>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 text-nowrap">Tỷ lệ</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end js__st-pct" name="share[{$_r.billing_sale_id|escape}][gdkd_rate]"
											value="{$_r.gdkd_calc.rate|escape}"
											title="Luỹ kế tháng của GĐKD: {$_r.gdkd_calc.cumulative|number_format:0:",":"."} đ">
										<span class="input-group-text">%</span>
									</div>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 text-nowrap">Số tiền</label>
									<div class="input-group input-group-merge">
										{* KHÔNG price-In — plugin đó ghi "0" vào ô rỗng lúc focusout, biến ô xoá trắng thành
											   "đè bằng 0". Cùng lý do đã bỏ ở ô "Sales chịu" và ô tiền CTV. *}
											<input type="text" class="form-control text-end numberonly js__st-amt" name="share[{$_r.billing_sale_id|escape}][gdkd_amount]"
												value="{if $_r.gdkd_calc.amount ne ''}{$_r.gdkd_calc.amount|number_format:0:",":"."}{/if}" data-typed="{if $_r.gdkd_calc.manual}1{else}0{/if}">
										<span class="input-group-text">đ</span>
									</div>
								</div>
							</div>
								{/if}
								{* PTĐT chính là bán cho CTV nên khối này chỉ hiện ở đó. Bán nội bộ và bán F2 không
								   qua CTV. Ẩn thì các ô không được gửi lên, _billing_set_if_posted bỏ qua nên
								   dữ liệu cũ (nếu có) vẫn còn nguyên. *}
								{if $st_deal_type eq 'channel'}
								<div class="row g-2">
									<div class="col-12 col-md-4">
										<label class="form-label mb-1 text-nowrap" title="Cộng tác viên">CTV</label>
										<input type="text" class="form-control" name="share[{$_r.billing_sale_id|escape}][ctv_name]" value="{$_r.ctv_name|escape}" placeholder="Tên CTV">
									</div>
									<div class="col-6 col-md-4">
										<label class="form-label mb-1">Tỷ lệ</label>
										<div class="input-group input-group-merge">
											<input type="text" class="form-control text-end js__st-ctv-rate" name="share[{$_r.billing_sale_id|escape}][ctv_rate]" value="{$_r.ctv_calc.rate|escape}">
											<span class="input-group-text">%</span>
										</div>
									</div>
									<div class="col-6 col-md-4">
										<label class="form-label mb-1 text-nowrap" title="Máy tự tính sẵn — gõ đè nếu cần">Số tiền</label>
										<div class="input-group input-group-merge">
											{* KHÔNG price-In — xem ghi chú ở ô "Sales chịu" *}
											<input type="text" class="form-control text-end numberonly js__st-amt js__st-ctv-amount" name="share[{$_r.billing_sale_id|escape}][ctv_amount]" 
												value="{if $_r.ctv_calc.amount}{$_r.ctv_calc.amount|number_format:0:",":"."}{/if}" data-sid="{$_r.billing_sale_id|escape}" data-typed="{if $_r.ctv_calc.manual}1{else}0{/if}">
											<span class="input-group-text">đ</span>
										</div>
										<small class="text-muted js__st-ctv-hint" data-sid="{$_r.billing_sale_id|escape}"></small>
									</div>
								</div>
								{/if}
							</div>
							{/foreach}
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="card shadow-none border">
						<div class="card-header py-2">
							<h6 class="mb-0">Đại lý{if $st_is_channel eq 1} &amp; Ban Phát triển đối tác{/if}
								<span class="text-muted small fw-normal">— tính theo CĂN, không theo người bán</span>
							</h6>
						</div>
						<div class="card-body p-3">
							<div class="row g-2">
								<div class="col-6 col-md-2">
									<label class="form-label mb-1 small">Tỷ lệ</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end js__st-agency-rate" name="agency_rate" value="{$st_agency_calc.rate|escape}">
										<span class="input-group-text">%</span>
									</div>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small text-nowrap" title="Máy tự tính sẵn — gõ đè nếu cần">Số tiền</label>
									<div class="input-group input-group-merge">
										{* KHÔNG price-In — xem ghi chú ở ô "Sales chịu" *}
										<input type="text" class="form-control text-end numberonly js__st-amt js__st-money js__st-agency-amount" name="agency_amount" 
											value="{if $st_agency_calc.amount}{$st_agency_calc.amount|number_format:0:",":"."}{/if}" data-typed="{if $st_agency_calc.manual}1{else}0{/if}">
										<span class="input-group-text">đ</span>
									</div>
									<small class="text-muted js__st-agency-hint"></small>
								</div>
							</div>
							{* Vai trò cấp GIAO DỊCH — ăn trên TỔNG doanh số cả căn, không theo từng dòng chia.
							   PTĐT tra bậc thang (gợi ý hiện mờ trong ô); Giám đốc dự án và TP GDDA thì tỷ lệ
							   theo quyết định từng dự án nên không có bậc — nhập tay. *}
							{if $st_is_channel eq 1}
							<div class="row g-2 mt-1 align-items-end">
								<div class="col-12 col-md-6">
									<label class="form-label mb-1 small">Chuyên viên PTĐT</label>
									<select data-placeholder="Chọn CV PTĐT" class="form-control iso-selectizeSync" name="channel_exec_id" data-width="100%" data-allow-clear="true">
										<option value="0">Chọn CV PTĐT</option>
										{if !empty($st_staffs)}{foreach from=$st_staffs item=_oSt}
										<option{if $st_bill_roles.channel_exec.id eq $_oSt.profile_id} selected="selected"{/if} value="{$_oSt.profile_id|escape}">{$_oSt.full_name|escape}</option>
										{/foreach}{/if}
									</select>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small">Tỷ lệ</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end js__st-pct" name="channel_exec_rate"
											value="{$st_bill_roles.channel_exec.rate|escape}">
										<span class="input-group-text">%</span>
									</div>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small text-nowrap" title="Máy tự tính sẵn — gõ đè nếu cần">Số tiền</label>
									<div class="input-group input-group-merge">
										{* KHÔNG price-In — xem ghi chú ở ô "Sales chịu" *}
										<input type="text" class="form-control text-end numberonly js__st-amt" name="channel_exec_amount"
											value="{if $st_bill_roles.channel_exec.amount ne ''}{$st_bill_roles.channel_exec.amount|number_format:0:",":"."}{/if}" data-typed="{if $st_bill_roles.channel_exec.manual}1{else}0{/if}">
										<span class="input-group-text">đ</span>
									</div>
								</div>
							</div>
							<div class="row g-2 mt-1 align-items-end">
								<div class="col-12 col-md-6">
									<label class="form-label mb-1 small">Trưởng phòng PTĐT</label>
									<select data-placeholder="Chọn TP PTĐT" class="form-control iso-selectizeSync" name="channel_manager_id" data-width="100%" data-allow-clear="true">
										<option value="0">Chọn TP PTĐT</option>
										{if !empty($st_staffs)}{foreach from=$st_staffs item=_oSt}
										<option{if $st_bill_roles.channel_manager.id eq $_oSt.profile_id} selected="selected"{/if} value="{$_oSt.profile_id|escape}">{$_oSt.full_name|escape}</option>
										{/foreach}{/if}
									</select>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small">Tỷ lệ</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end js__st-pct" name="channel_manager_rate"
											value="{$st_bill_roles.channel_manager.rate|escape}">
										<span class="input-group-text">%</span>
									</div>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small text-nowrap" title="Máy tự tính sẵn — gõ đè nếu cần">Số tiền</label>
									<div class="input-group input-group-merge">
										{* KHÔNG price-In — xem ghi chú ở ô "Sales chịu" *}
										<input type="text" class="form-control text-end numberonly js__st-amt" name="channel_manager_amount"
											value="{if $st_bill_roles.channel_manager.amount ne ''}{$st_bill_roles.channel_manager.amount|number_format:0:",":"."}{/if}" data-typed="{if $st_bill_roles.channel_manager.manual}1{else}0{/if}">
										<span class="input-group-text">đ</span>
									</div>
								</div>
							</div>
							<div class="row g-2 mt-1 align-items-end">
								<div class="col-12 col-md-6">
									<label class="form-label mb-1 small">Giám đốc PTĐT</label>
									<select data-placeholder="Chọn GĐ PTĐT" class="form-control iso-selectizeSync" name="channel_director_id" data-width="100%" data-allow-clear="true">
										<option value="0">Chọn GĐ PTĐT</option>
										{if !empty($st_staffs)}{foreach from=$st_staffs item=_oSt}
										<option{if $st_bill_roles.channel_director.id eq $_oSt.profile_id} selected="selected"{/if} value="{$_oSt.profile_id|escape}">{$_oSt.full_name|escape}</option>
										{/foreach}{/if}
									</select>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small">Tỷ lệ</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end js__st-pct" name="channel_director_rate"
											value="{$st_bill_roles.channel_director.rate|escape}">
										<span class="input-group-text">%</span>
									</div>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small text-nowrap" title="Máy tự tính sẵn — gõ đè nếu cần">Số tiền</label>
									<div class="input-group input-group-merge">
										{* KHÔNG price-In — xem ghi chú ở ô "Sales chịu" *}
										<input type="text" class="form-control text-end numberonly js__st-amt" name="channel_director_amount"
											value="{if $st_bill_roles.channel_director.amount ne ''}{$st_bill_roles.channel_director.amount|number_format:0:",":"."}{/if}" data-typed="{if $st_bill_roles.channel_director.manual}1{else}0{/if}">
										<span class="input-group-text">đ</span>
									</div>
								</div>
							</div>
							{/if}
							{* Hai vai trò dự án ăn trên MỌI loại giao dịch, kể cả bán F2 — sổ ghi tiền cho
							   Giám đốc dự án ở 32/44 căn khối F1. Không ẩn theo loại giao dịch nữa. *}
							<div class="row g-2 mt-1 align-items-end">
								<div class="col-12 col-md-6">
									<label class="form-label mb-1 small">Giám đốc dự án</label>
									<select data-placeholder="Chọn GĐ dự án" class="form-control iso-selectizeSync" name="project_director_id" data-width="100%" data-allow-clear="true">
										<option value="0">Chọn GĐ dự án</option>
										{if !empty($st_staffs)}{foreach from=$st_staffs item=_oSt}
										<option{if $st_bill_roles.project_director.id eq $_oSt.profile_id} selected="selected"{/if} value="{$_oSt.profile_id|escape}">{$_oSt.full_name|escape}</option>
										{/foreach}{/if}
									</select>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small">Tỷ lệ</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end js__st-pct" name="project_director_rate"
											value="{$st_bill_roles.project_director.rate|escape}">
										<span class="input-group-text">%</span>
									</div>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small text-nowrap" title="Máy tự tính sẵn — gõ đè nếu cần">Số tiền</label>
									<div class="input-group input-group-merge">
										{* KHÔNG price-In — xem ghi chú ở ô "Sales chịu" *}
										<input type="text" class="form-control text-end numberonly js__st-amt" name="project_director_amount"
											value="{if $st_bill_roles.project_director.amount ne ''}{$st_bill_roles.project_director.amount|number_format:0:",":"."}{/if}" data-typed="{if $st_bill_roles.project_director.manual}1{else}0{/if}">
										<span class="input-group-text">đ</span>
									</div>
								</div>
							</div>
							<div class="row g-2 mt-1 align-items-end">
								<div class="col-12 col-md-6">
									<label class="form-label mb-1 small">TP GDDA</label>
									<select data-placeholder="Chọn TP GDDA" class="form-control iso-selectizeSync" name="project_manager_id" data-width="100%" data-allow-clear="true">
										<option value="0">Chọn TP GDDA</option>
										{if !empty($st_staffs)}{foreach from=$st_staffs item=_oSt}
										<option{if $st_bill_roles.project_manager.id eq $_oSt.profile_id} selected="selected"{/if} value="{$_oSt.profile_id|escape}">{$_oSt.full_name|escape}</option>
										{/foreach}{/if}
									</select>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small">Tỷ lệ</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end js__st-pct" name="project_manager_rate"
											value="{$st_bill_roles.project_manager.rate|escape}">
										<span class="input-group-text">%</span>
									</div>
								</div>
								<div class="col-6 col-md-3">
									<label class="form-label mb-1 small text-nowrap" title="Máy tự tính sẵn — gõ đè nếu cần">Số tiền</label>
									<div class="input-group input-group-merge">
										{* KHÔNG price-In — xem ghi chú ở ô "Sales chịu" *}
										<input type="text" class="form-control text-end numberonly js__st-amt" name="project_manager_amount"
											value="{if $st_bill_roles.project_manager.amount ne ''}{$st_bill_roles.project_manager.amount|number_format:0:",":"."}{/if}" data-typed="{if $st_bill_roles.project_manager.manual}1{else}0{/if}">
										<span class="input-group-text">đ</span>
									</div>
								</div>
							</div>
							<div class="alert alert-secondary py-2 mt-3 mb-0">
								<small>Tiền CTV và đại lý <strong>ghi nhận riêng</strong>, không trừ vào tiền sale nhận.</small>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="card shadow-none border">
						<div class="card-header py-2 d-flex justify-content-between align-items-center">
							<h6 class="mb-0">Chi phí công ty <span class="text-muted small fw-normal">— tính theo CĂN, trả về phòng ban</span></h6>
							<div class="d-flex align-items-center gap-2">
								<span class="small text-muted">Tổng <strong class="js__st-bo-total">{$st_bo_total|number_format:0:",":"."}</strong> đ</span>
								{if $st_can_config eq 1}
								<button type="button" class="btn btn-icon btn-sm btn-outline-default" title="Thiết lập tỷ lệ chi phí công ty"
									onClick="$Core.backofficeRate.open(this,event)"><i class="bx bx-cog"></i></button>
								{/if}
							</div>
						</div>
						<div class="card-body p-3">
							<div class="row g-2">
							{foreach from=$st_bo_rows item=_bo}
								<div class="col-6 col-md-4 col-xl-2">
									<label class="form-label mb-1 d-block text-truncate" title="{$_bo.label|escape} — {$_bo.rate|escape}% (sửa được)">
										{$_bo.label|escape} <span class="text-muted js__st-bo-rate" data-key="{$_bo.key|escape}">{$_bo.rate|escape}</span>%</label>
									<div class="input-group input-group-merge">
										{* KHÔNG price-In — xem ghi chú ở ô "Sales chịu" *}
										<input type="text" autocomplete="off" class="form-control text-end numberonly js__st-amt js__st-bo"
											name="bo_{$_bo.key|escape}_amount" value="{$_bo.amount|number_format:0:",":"."}"
											data-key="{$_bo.key|escape}" data-rate="{$_bo.rate|escape}" data-parent="{$_bo.rate_parent|escape}"
											{* data-typed: người đã gõ đè thì recalc KHÔNG được ghi lại. Số đè đã lưu cũng phải
											   mang cờ này, nếu không mở modal ra là bị tính đè mất. *}
											data-typed="{if $_bo.manual}1{else}0{/if}"
											onClick="this.select();">
										<span class="input-group-text">đ</span>
									</div>
								</div>
							{/foreach}
							</div>
							<div class="alert alert-secondary py-2 mt-3 mb-0">
								<small>Ban điều hành <strong>1%</strong> và quỹ Back Office <strong>0,5%</strong> đều tính trên
									doanh số sau giảm trừ của <strong>cả căn</strong>. Quỹ Back Office chia tiếp cho các phòng theo
									tỷ lệ ghi trên mỗi ô — <strong>không cộng thêm</strong> vào 1,5% đó. Tiền về <strong>phòng</strong>,
									không chia sẵn cho từng người.</small>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class="card shadow-none border">
						<div class="card-header py-2"><h6 class="mb-0">Thưởng nóng <span class="text-muted small fw-normal">— chỉ theo dõi, không vào công thức</span></h6></div>
						<div class="card-body p-3">
							<div class="row g-2">
								<div class="col-12 col-md-6">
									<label class="form-label mb-1 small">Thưởng nóng khách hàng</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end price-In numberonly" name="hot_bonus_customer" value="{$st_more.hot_bonus_customer|escape}">
										<span class="input-group-text">đ</span>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label mb-1 small">Thưởng nóng CTV / Đại lý</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control text-end price-In numberonly" name="hot_bonus_agency" value="{$st_more.hot_bonus_agency|escape}">
										<span class="input-group-text">đ</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary" onclick="$Core.billingSettlement.save(this,event)">
				<i class="bx bx-save me-1"></i> Lưu quyết toán
			</button>
		</div>
	</form>
</div>
