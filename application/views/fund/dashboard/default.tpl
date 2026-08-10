<div class="container-xxl flex-grow-1 container-p-y pt-2">

	<div class="w-100 d-flex flex-wrap align-items-center justify-content-between mb-2">

		<div class="lycYJcfXJY mb-2 mb-lg-0">

			<h5 class="fw-bold mb-1 text-fs-20">Tài chính {$smarty.const.BRAND_NAME}</h5>

			<small class="text-muted">Tổng quan tài chính {$smarty.const.BRAND_NAME}</small>

		</div>

		<div class="d-flex flex-wrap algin-items-center gap-2">

			<div class="xs:w-100 d-flex align-items-center justify-content-center gap-2 order-2 order-lg-2">

				<a href="{$PCMS_URL}/tai-chinh/chi-nhanh.html" class="text-link text-nowrap">Chi nhánh</a>|

				<a href="{$PCMS_URL}/tai-chinh/du-an.html" class="text-link text-nowrap">Dự án</a>|

				<a href="{$PCMS_URL}/tai-chinh/dong-tien.html" class="text-link text-nowrap">Dòng tiền</a>

			</div>

			<div class="cBSpMCSbVA order-1 order-lg-2  xs:w-100">					

				{assign var = gId value = $clsISO->getUniqid()}				

				<!--<div class="input-group w-px-250 ox:w-100 d-flex" role="group" aria-label="Lọc">

					<select class="form-control form-select search_field search_month" 

						name="company_id" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)"> 

						<option value="">Đơn vị</option>			

						{foreach from=$list_company item = _oI}

						<option{if $smarty.const._GROUP_COMPANY_FH_ID eq $_oI.setting_id} selected{/if} value="{$_oI.setting_id}">{$_oI.title}</option>

						{/foreach}

					</select>

					<select class="form-control form-select search_field search_month" 

						name="month" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)"> 

						<option value="">Tháng</option>			

						{foreach from=$list_months item = _month}

						<option value="{$_month}">T{$_month}</option>

						{/foreach}

					</select>

					<select class="form-control form-select search_field search_year" name="year" 

						gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)">

						{foreach from=$list_years item = _year}

						<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>

						{/foreach}

					</select>

				</div>-->

				<div class="search d-flex flex-wrap align-items-center gap-1">	

					<div class="input-group w-px-350 flex-fill">

						<select class="form-control form-select search_field search_month" 

							name="company_id" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)"> 

							<option value="">Đơn vị</option>			

							{foreach from=$list_company item = _oI}

							<option{if $smarty.const._GROUP_COMPANY_FH_ID eq $_oI.setting_id} selected{/if} value="{$_oI.setting_id}">{$_oI.title}</option>

							{/foreach}

						</select>

						<select gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" data-field="year" name="year" 

							class="form-control js__search-year-field search_field form-select">

							{foreach from=$list_years item = _year}

								<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>

							{/foreach}

						</select>

						<select gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" data-field="month" name="month" 

							class="form-control js__search-month-field search_field form-select">

							<option value="">Tháng</option>			

							{foreach from=$list_months item = _month}

								<option value="{$_month}">T{$_month}</option>

							{/foreach}

						</select>

						<select gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" data-field="date_type" name="date_type" 

							class="form-control js__search-date_type-field form-select">

							<option value="">Khoảng</option>

							<option value="7days">7 ngày qua</option>

							<option value="15days">15 ngày qua</option>

							<option value="30days">30 ngày qua</option>

						</select>

					</div>

					<div class="input-group w-auto flex-fill">

						<input type="date" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" class="form-control js__search-start_date-field 

						js__search-date-field search_field w-px-125" name="start_date" data-field="start_date" value="{$start_date}" />

						<input type="date" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" class="form-control js__search-end_date-field 

						js__search-date-field search_field w-px-125" name="end_date" data-field="end_date" value="{$end_date}" max="{$smarty.now|date_format:'%Y-%m-%d'}" />

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="form-row row-cols-1 row-col-md-2 row-cols-lg-5 ajax" 

		data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_total_summary" 

		data-options='{ldelim}{rdelim}'>

		{foreach from=$arr_boxs item = _oBox}

		<div class="col mb-2">

			<div class="card fund_box {$_oBox.cls}">

				<div class="card-body">

					<h3 class="text-nowrap text-fs-18 text-black mb-2">{$_oBox.title}</h3>	

					<h5 class="mb-1 text-fs-4 fw-bold text-main">{$clsISO->shortNumber($total_income)}</h5>

					<div class="">

						<span class="text-success fw-bold">

							<i class="bx bx-caret-up"></i> 0% 

						</span>

						<span class="text-muted">so với kỳ trước</span>

					</div>

				</div>

			</div>

		</div>

		{/foreach}

	</div>

	<div class="form-row mb-2">

		<div class="col-12 col-md-8 mb-2 mb-lg-0">

			<div class="card h-100">

				<div class="card-header">

					<div class="d-flex justify-content-between align-items-center">

						<h3 class="text-nowrap fs-18 text-black mb-0">Kết quả kinh doanh</h3>

						<a href="javascript:void(0)" class="btn btn-outline-info btn-sm" onClick="$Core.fund.dashboard.load_detail_income_statement(this,event)" >Chi tiết</a>

					</div>

				</div>

				<div class="card-body ajax" data-options='{ldelim}{rdelim}' 

					data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_nett_profit">

					

				</div>

			</div>

		</div>

		<div class="col-12 col-md-4">

			<div class="card h-100">

				{assign var = boxId value = $clsISO->getUniqid()}

				<div class="card-header">

					<div class="d-flex flex-wrap align-items-center justify-content-between">

						<h3 class="text-nowrap fs-18 text-black mb-0">Tài sản</h3>

						<button gId="{$boxId}" onClick="$Core.fund.dashboard.refresh(this, event)" 

							class="btn btn-sm btn-icon btn-link text-muted">

							<i class="bx bx-refresh"></i>

						</button>

					</div>

				</div>

				<div id="{$boxId}" class="card-body ajax" data-options='{ldelim}{rdelim}' 

					data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_asset_summary">

				</div>

			</div>

		</div>

	</div>

	<div class="form-row mb-2">

		<div class="col-12 col-md-8 mb-2 mb-lg-0">

			<div class="card h-100">

				{assign var = boxId value = $clsISO->getUniqid()}

				<div class="card-header">

					<div class="d-flex justify-content-between align-items-center">

						<h5 class="d-flex align-items-center gap-2 card-title mb-0">

							<div class="icon">💰</div> Tiền mặt & trong ngân hàng

						</h5>

						<button gId="{$boxId}" onClick="$Core.fund.dashboard.refresh(this, event)" 

							class="btn btn-sm btn-icon btn-link text-muted">

							<i class="bx bx-refresh"></i>

						</button>

					</div>

				</div>

				<div id="{$boxId}" class="ajax" data-options='{ldelim}{rdelim}' 

					data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_cash">

					<div class="summary-bar">

						<div class="summary-total">Tổng: <strong>0.00 tỷ đ</strong></div>

						<div class="summary-pill">

							<span class="label">Tiền mặt</span>&nbsp;<span class="value">0.00 tỷ đ</span>

						</div>

						<div class="summary-pill">

							<span class="label">Ngân hàng</span>&nbsp;<span class="value">0.00 tỷ đ</span>

						</div>

						<div class="summary-pill">

							<span class="value">0.00 triệu đ</span>

						</div>

					</div>

					<div class="x-grid">

						<div class="x-col">

							<div class="section-header">

								<div class="section-title">

									<span>💰</span> Tiền mặt

									<span class="section-code">111</span>

								</div>

							</div>

							<div class="item-list">

								<div class="item">

									<div class="item-left">

										<span class="dot dot-green"></span>

										<span class="item-name">

											<div class="animate-bg w-px-150 h-px-15 rounded-2"></div>

										</span>

									</div>

									<span class="item-value">0.0 triệu đ</span>

								</div>

								<div class="item">

									<div class="item-left">

										<span class="dot dot-green"></span>

										<span class="item-name">

											<div class="animate-bg w-px-100 h-px-15 rounded-2"></div>

										</span>

									</div>

									<span class="item-value">0.0 triệu đ</span>

								</div>

								<div class="item">

									<div class="item-left">

										<span class="dot dot-green"></span>

										<span class="item-name">

											<div class="animate-bg w-px-150 h-px-15 rounded-2"></div>

										</span>

									</div>

									<span class="item-value">0.0 triệu đ</span>

								</div>

								<div class="item">

									<div class="item-left">

										<span class="dot dot-green"></span>

										<span class="item-name">

											<div class="animate-bg w-px-150 h-px-15 rounded-2"></div>

										</span>

									</div>

									<span class="item-value">0.0 triệu đ</span>

								</div>

							</div>

							<div class="divider-row">

								<div class="section-title">

								  <span>🏦</span> Ngân hàng

								  <span class="section-code">112</span>

								</div>

								<div class="section-amount">

								  0.00 tỷ đ

								  <a class="arrow-link">→</a>

								</div>

							</div>

						</div>

						<div class="x-col">

							<div class="section-header">

								<div class="section-title">

									<span>🏦</span> Ngân hàng

									<span class="section-code">112</span>

								</div>

								<div class="section-amount">

									0,0 tỷ

									<a class="arrow-link">→</a>

								</div>

							</div>

							<div class="item-list">

								<div class="item">

									<div class="item-left">

										<span class="dot dot-blue"></span>

										<span class="item-name">

											<div class="animate-bg w-px-150 h-px-15 rounded-2"></div>

										</span>

									</div>

									<span class="item-value">0,0 tỷ đ</span>

								</div>

								<div class="item">

									<div class="item-left">

										<span class="dot dot-blue"></span>

										<span class="item-name">

											<div class="animate-bg w-px-100 h-px-15 rounded-2"></div>

										</span>

									</div>

									<span class="item-value">0,0 tỷ đ</span>

								</div>

								<div class="item">

									<div class="item-left">

										<span class="dot dot-blue"></span>

										<span class="item-name">

											<div class="animate-bg w-px-150 h-px-15 rounded-2"></div>

										</span>

									</div>

									<span class="item-value">0,0 triệu đ</span>

								</div>

								<div class="item">

									<div class="item-left">

										<span class="dot dot-blue"></span>

										<span class="item-name">

											<div class="animate-bg w-px-150 h-px-15 rounded-2"></div>

										</span>

									</div>

									<span class="item-value">0,0 triệu đ</span>

								</div>

							</div>

							<!-- Collapsed: Đầu tư -->

							<div class="collapsed-section">

								<div class="section-title">

									<span>📊</span> Đầu tư

									<span class="section-code">121,128</span>

								</div>

								<div class="chevron">

									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" 

										stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">

										<polyline points="6 9 12 15 18 9"/></svg>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="col-12 col-md-4">

			<div class="card card-boder h-100">

				<div class="card-header">

					<div class="d-flex justify-content-between align-items-center gap-2 h-px-30">

						<h3 class="fs-18 lh-base text-black mb-0">Sổ quỹ tiền mặt <br />

							<small class="text-muted">Từ 01/01/2026 tới {$smarty.now|date_format:"%d/%m/%Y"}</small>

						</h3>	

						<span class="text-muted">Tính đến: {$smarty.now|date_format:"%d/%m/%Y"}</span>

					</div>

				</div>

				<div class="card-body ajax" data-options='{ldelim}{rdelim}' 

					data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_cash_book_summary">

				</div>

			</div>

		</div>

	</div>

	<div class="form-row row-cols-1 row-cols-lg-3 mb-2">

		<div class="col mb-2 mb-lg-0">

			{assign var = boxId value = $clsISO->getUniqid()}

			<div class="card h-100">

				<!-- Header -->

				<div class="card-header d-flex align-items-center justify-content-between">

					<div class="d-flex align-items-center gap-2">

						<div class="wallet-icon">

							<!-- Wallet SVG -->

							<svg width="15" height="15" viewBox="0 0 26 26" fill="none">

								<rect x="1" y="7" width="24" height="16" rx="3.5" fill="rgba(255,255,255,0.25)" stroke="rgba(255,255,255,0.7)" stroke-width="1.5"/>

								<path d="M5 7V5.5A3.5 3.5 0 0 1 8.5 2h9A3.5 3.5 0 0 1 21 5.5V7" stroke="rgba(255,255,255,0.65)" stroke-width="1.5" fill="none" stroke-linecap="round"/>

								<circle cx="20" cy="15" r="2" fill="rgba(255,255,255,0.9)"/>

								<line x1="5" y1="13.5" x2="14" y2="13.5" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round"/>

								<line x1="5" y1="18" x2="10" y2="18" stroke="rgba(255,255,255,0.45)" stroke-width="1.5" stroke-linecap="round"/>

							</svg>

						</div>

						<span class="text-fs-18">Doanh Thu</span>

					</div>

					<button gId="{$boxId}" onClick="$Core.fund.dashboard.refresh(this, event)" class="btn btn-sm btn-icon btn-link text-muted">

						<i class="bx bx-refresh"></i>

					</button>

				</div>

				<div id="{$boxId}" class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_income" 

					data-options='{ldelim}{rdelim}'>	

					<div class="metrics-grid gap-2">

						<div class="metric-box green">

							<div class="box-header">

								<div class="box-icon">

									<svg width="34" height="30" viewBox="0 0 34 30" fill="none">

										<rect x="1" y="8" width="32" height="20" rx="5" fill="#bbf7d0" stroke="#22c55e" stroke-width="1.8"/>

										<path d="M6 8V6A5 5 0 0 1 11 1h12a5 5 0 0 1 5 5v2" stroke="#22c55e" stroke-width="1.8" fill="none" stroke-linecap="round"/>

										<circle cx="27" cy="18" r="3" fill="#16a34a"/>

										<line x1="7" y1="16" x2="18" y2="16" stroke="#16a34a" stroke-width="2" stroke-linecap="round"/>

										<line x1="7" y1="21" x2="13" y2="21" stroke="#16a34a" stroke-width="2" stroke-linecap="round" opacity=".55"/>

									</svg>

								</div>

								<span class="box-label">Thực thu</span>

							</div>

							<div class="box-value">0,0 tỷ</div>

							<div class="box-delta green">

								<span class="arrow-up"></span>+0,0%

							</div>

						</div>

						<!-- Dự kiến thu -->

						<div class="metric-box yellow">

							<div class="box-header">

								<!-- Amber bar chart icon -->

								<div class="box-icon">

									<svg width="34" height="30" viewBox="0 0 34 30" fill="none">

										<rect x="2"  y="20" width="7" height="9" rx="2" fill="#fcd34d"/>

										<rect x="13" y="12" width="7" height="17" rx="2" fill="#f59e0b"/>

										<rect x="24" y="6"  width="7" height="23" rx="2" fill="#d97706"/>

									</svg>

								</div>

								<span class="box-label">Dự kiến thu</span>

							</div>

							<div class="box-value">0,0 tỷ</div>

							<div class="box-delta amber">

								<span class="arrow-up"></span>+0,0%

							</div>

						</div>

						<!-- Thu hộ -->

						<div class="metric-box blue">

							<div class="box-header">

								<!-- Blue handshake icon -->

								<div class="box-icon">

									<svg width="36" height="30" viewBox="0 0 36 30" fill="none">

										<!-- left arm -->

										<path d="M1 20 C3 16, 8 13, 13 15 L18 19" stroke="#60a5fa" stroke-width="2.2" stroke-linecap="round" fill="none"/>

										<!-- left fingers -->

										<path d="M13 15 L16 10 C17 8, 19.5 9, 18.5 11.5 L16 15" fill="#bfdbfe" stroke="#60a5fa" stroke-width="1.4"/>

										<path d="M16 10 L18 7 C19 5, 21.5 6, 20.5 8.5 L18.5 11.5" fill="#dbeafe" stroke="#93c5fd" stroke-width="1.4"/>

										<!-- right arm -->

										<path d="M35 20 C33 16, 28 13, 23 15 L18 19" stroke="#3b82f6" stroke-width="2.2" stroke-linecap="round" fill="none"/>

										<!-- right fingers -->

										<path d="M23 15 L20 10 C19 8, 16.5 9, 17.5 11.5 L20 15" fill="#93c5fd" stroke="#3b82f6" stroke-width="1.4"/>

										<path d="M20 10 L18 7 C17 5, 14.5 6, 15.5 8.5 L17.5 11.5" fill="#bfdbfe" stroke="#60a5fa" stroke-width="1.4"/>

										<!-- clasp centre -->

										<ellipse cx="18" cy="19.5" rx="5" ry="4" fill="#dbeafe" stroke="#3b82f6" stroke-width="1.6"/>

										<!-- cuffs -->

										<path d="M1 20 C2 24, 7 27, 12 27 L18 24" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" fill="none"/>

										<path d="M35 20 C34 24, 29 27, 24 27 L18 24" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" fill="none"/>

									</svg>

								</div>

								<span class="box-label">Thu hộ</span>

							</div>

							<div class="box-value">0,0 tỷ</div>

							<div class="box-delta red">

								<span class="arrow-down"></span>−0,0%

							</div>

						</div>

					</div>

					<!-- Bottom bar -->

					<div class="bottom-bar">

						<svg width="26" height="24" viewBox="0 0 26 24" fill="none" style="flex-shrink:0">

							<rect x="0"  y="14" width="7" height="10" rx="2" fill="#34d399"/>

							<rect x="9"  y="6"  width="7" height="18" rx="2" fill="#0d9488"/>

							<rect x="19" y="9"  width="7" height="15" rx="2" fill="#2dd4bf" opacity=".75"/>

						</svg>

						<span class="bottom-label">Doanh thu</span>

						<span class="bottom-value text-fs-24">0,0 tỷ</span>

					</div>

				</div>

			</div>

		</div>

		<div class="col mb-2 mb-lg-0">

			<div class="card card-boder h-100">

				<!--<div class="card-header d-flex justify-content-end">

					<div class="btn-group text-nowrap" role="group" aria-label="Hiá»ƒn thá»‹" computer="">

						{assign var=gId value=$clsISO->getUniqid()}

						<input type="radio" class="btn-check" name="time" onchange="" id="{$gId}" value="TODAY" checked="checked">

						<label data-toggle="ripple" title="HÃ´m nay" class="btn btn-sm btn-outline-default active" for="{$gId}">Hôm nay</label>

						{assign var=gId value=$clsISO->getUniqid()}

						<input type="radio" class="btn-check" name="time" onchange="" id="{$gId}" value="YESTERDAY" >

						<label data-toggle="ripple" title="HÃ´m qua" class="btn btn-sm btn-outline-default active" for="{$gId}">Hôm qua</label>

						{assign var=gId value=$clsISO->getUniqid()}

						<input type="radio" class="btn-check" name="time" onchange="" id="{$gId}" value="WEEK" >

						<label data-toggle="ripple" title="7 ngày" class="btn btn-sm btn-outline-default active" for="{$gId}">7 ngày</label>

						{assign var=gId value=$clsISO->getUniqid()}

						<input type="radio" class="btn-check" name="time" onchange="" id="{$gId}" value="MONTH" >

						<label data-toggle="ripple" title="30 ngày" class="btn btn-sm btn-outline-default active" for="{$gId}">30 ngày</label>

						{assign var=gId value=$clsISO->getUniqid()}

						<input type="radio" class="btn-check" name="time" onchange="" id="{$gId}" value="QUARTER_PREV" >

						<label data-toggle="ripple" title="Quý trước" class="btn btn-sm btn-outline-default active" for="{$gId}">Quý trước</label>

						{assign var=gId value=$clsISO->getUniqid()}

						<input type="radio" class="btn-check" name="time" onchange="" id="{$gId}" value="YEAR_PREV">

						<label data-toggle="ripple" title="Năm trước" class="btn btn-sm btn-outline-default active" for="{$gId}">Năm trước</label>

					</div>

				</div>-->

				<div class="card-body">									

					<div class="card card-boder no-shadow border h-100">

						{assign var = gId value = $clsISO->getUniqid()}

						<div class="card-header d-flex flex-wrap align-items-center justify-content-between">

							<h3 class="text-nowrap fs-18 text-black mb-0">Tổng quan chi phí</h3>	

						</div>

						<div class="card-body">

							<div class="form-row row-cols-1 row-cols-md-2 ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_expense" data-options='{ldelim}{rdelim}'> 

								<div>

									<div class="chartContainer d-flex w-100 h-px-300 justify-content-center align-items-center text-muted text-center">Loading...</div>

								</div>

								<div class="d-flex gap-1 flex-column px-2">

									<div class="d-flex align-items-center justify-content-between gap-2">

										<div class="d-flex align-items-center gap-2">

											<span class="w-px-15 h-px-15 rounded-pill" style="background: #6d78ad"></span>

											<span class="">Chi phí chi nhÃ¡nh</span>

										</div>

										<span class="text-fs-16 fw-bold">72,2  tỷ</span>

									</div>

									<div class="d-flex align-items-center justify-content-between gap-2">

										<div class="d-flex align-items-center gap-2">

											<span class="w-px-15 h-px-15 rounded-pill" style="background: #51cda0"></span>

											<span class="">Chi phí dự án</span>

										</div>

										<span class="text-fs-16 fw-bold">72,2 tỷ</span>

									</div>

									<div class="d-flex align-items-center justify-content-between gap-2">

										<div class="d-flex align-items-center gap-2">

											<span class="w-px-15 h-px-15 rounded-pill" style="background: #df7970"></span>

											<span class="">Chi phí marketing</span>

										</div>

										<span class="text-fs-16 fw-bold">72,2 tỷ</span>

									</div>

									<div class="d-flex align-items-center justify-content-between gap-2">

										<div class="d-flex align-items-center gap-2">

											<span class="w-px-15 h-px-15 rounded-pill" style="background: #060673"></span>

											<span class="">Chi phí khác</span>

										</div>

										<span class="text-fs-16 fw-bold">72,2 tỷ</span>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="col">

			<div class="card h-100">

				<div class="card-header">

					<div class="d-flex flex-wrap align-items-center justify-content-between h-px-30">

						<h3 class="text-nowrap fs-18 text-black mb-0">Dự kiến chi</h3>

						<span class="text-fs-16 badge bg-label-danger rounded-pill fw-bold total_expected_expense">Đang tải...</span>

					</div>

				</div>

				<div class="card-body">

					<div class="table-container overflow-x-auto text-nowrap no-shadow">

						<table cellpadding="0" cellspacing="0" class="table dragable" width="100%">

							<thead><tr>

								<th class="align-center bg-lighter h-px-35" width="120px">Ngày dự chi</th>

								<th class="align-center bg-lighter text-left h-px-35">Tháng</th>

								<th class="align-center bg-lighter bg-lighter h-px-35">Nhóm chi phí</th>

								<th class="align-center bg-lighter h-px-35">Người chi</th>

								<th class="align-center bg-lighter text-left h-px-35">Văn phòng</th>

								<th class="align-center bg-lighter bg-lighter h-px-35">Dự án</th>

								<th class="align-center bg-lighter h-px-35" width="120px">Nhóm đối tác</th>

								<th class="align-center bg-lighter text-left h-px-35">Nội dung</th>

								<th class="align-center bg-lighter text-right bg-lighter h-px-35">Dự chi (VNĐ)</th>

								<th class="align-center bg-lighter bg-lighter h-px-35">Tình trạng</th>

							</tr></thead>

							<tbody id="tbl" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_expected_expense" 

								data-options='{ldelim}{rdelim}'>

								{section loop=$list_preloaders name=i max = 10 }

								<tr>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>

								</tr>

								{/section}

							</tbody>

						</table>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="form-row mb-2">

		<div class="col-12 col-md-6">

			<!-- Tiền về -->

			{assign var = gId value = $clsISO->getUniqid()}

			{$core->getBlock('money_in', ['gid' => $gId])}

			<!-- End -->

		</div>

		<div class="col-12 col-md-6">

			{assign var = gId value = $clsISO->getUniqid()}

			<div class="card h-100">

				<div class="card-header d-flex mb-0 justify-content-between align-items-center">

					<h5 class="card-title mb-0">Chi phí vận hành </h5>

					<a href="/chi-van-hanh.html" data-toggle="ripple" class="btn btn-sm btn-link btn-icon rounded-pill text-muted" title="Chi vận hành">

						<i class="bx bx-link-external text-fs-12"></i>

					</a>

				</div>

				<div class="card-body">

					<div class="form-row ajax" data-url="{$PCMS_URL}/index.php?mod=fund&act=get_opscost_total" gId="{$gId}" data-options='{ldelim}"call_from":"dashboard"{rdelim}'>

						

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

{literal}

<style type="text/css">

	.menu-vertical.bg-menu-theme{

		background-image: unset !important;

	}

	.input-group-date:before{

		top:8px;

	}

	.input-group-date > .isodaterangepicker{

		line-height: 1.83;

	}

	.freeze-table {

        user-select: none;

        -moz-user-select: none;

        -khtml-user-select: none;

        -webkit-user-select: none;

        -o-user-select: none;

	}

	.freeze-table .table{

		margin-bottom:0;

		min-width:1200px;

		max-width:16000px;

	}

	.freeze-table .table th{

		line-height:16px;

		vertical-align:middle;

	}

	@media screen and (min-width:648px){

		.freeze-table .table tr>th:nth-child(3),

		.freeze-table .trBilling td:nth-child(3){

			border-right:1px solid #DDD;

		}

	}

	@media screen and (max-width:648px){

		.freeze-table .table tr>th:nth-child(1),

		.freeze-table .trBilling td:nth-child(1){

			border-right:1px solid #DDD;

		}

	}

	.textbox{

		border-radius:4px;

		-moz-border-radius:4px;

		-webkit-border-radius:4px;

		-khtml-border-radius:4px;

	}

	.ui-autocomplete{

		z-index:9 !important;

		background:var(--bs-white);

		max-height:400px;

		overflow-y:auto;

		border-radius:4px;

		-moz-border-radius:4px;

		-webkit-border-radius:4px;

		-khtml-border-radius:4px;

	}

	.ui-menu-item .ui-menu-item-wrapper{

		padding: 5px 10px !important;

	}

</style>

{/literal}



