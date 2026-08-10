<div class="modal-dialog{if $deviceType eq 'phone'}  modal-dialog-scrollable{/if} modal-dialog-centered modal-xxl">
	<form method="post" class="modal-content" id="frmIssue" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title text-fs-20">Công cụ tính khoản vay {$oneStock.ms_code}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-12 col-lg-6 mb-3 mb-lg-0 left__pop">
					<div class="form-group mb-2">
						<label class="form-label mb-0">Giá trị nhà đất</label>
						<div class="d-flex gap-2 align-items-center">
							<div class="form-group w-{$col_w1}">
								<input type="range" uid="{$uid}" class="form-range js__slider_price_{$uid}" toId="js__input_price_{$uid}" onChange="$Core.calculator.set_change(this, event)" min="1" max="20" value="{$total_price_vat}" step="0.1">
							</div>
							<div class="input-group input-group-merge w-{$col_w2}">
								<input type="number" class="form-control calc_field js__input_price_{$uid}" toId="js__slider_price_{$uid}" onChange="$Core.calculator.do_calculator(this, event)" value="{$total_price_vat}" uid="{$uid}" id="price" onClick="this.select()" name="price" placeholder="Giá trị nhà đất">
								<span class="input-group-text cursor-pointer">tỷ</span>
							</div>
						</div>
					</div>
					<div class="form-group mb-2">
						<div class="d-flex align-items-center justify-content-between form-label mb-0">
							<div  class="d-flex align-items-ccenter gap-1">Tỷ lệ vay <i data-bs-toggle="tooltip" class="bx bx-help-circle text-fs-15" title="Điều chỉnh tỷ lệ khoản vay dựa trên giá trị dự án"></i></div>
							<div class="total-price">
								<span id="viewTongTienVay_{$uid}" class="fs-12 text-muted">{$total_price_ratio}</span> tỷ
							</div>
						</div>
						<div class="d-flex gap-2 align-items-center">
							<div class="form-group w-{$col_w1}">
								{if !empty($arr_field_configs)}
								<input type="range" class="form-range js__slider_ratio_{$uid}" toId="js__input_ratio_{$uid}" onChange="$Core.calculator.set_change(this, event)" min="1" max="100" value="{$def_configs.ratio}" step="0.5">
								{else}
								<input type="range" class="form-range js__slider_ratio_{$uid}" toId="js__input_ratio_{$uid}" onChange="$Core.calculator.set_change(this, event)" min="1" max="100" value="{$def_configs.ratio}" step="0.5">
								{/if}
							</div>
							<div class="input-group input-group-merge w-{$col_w2}">
								<input type="number" uid="{$uid}" class="form-control calc_field js__input_ratio_{$uid}" toId="js__slider_ratio_{$uid}" onClick="this.select()" onChange="$Core.calculator.do_calculator(this, event)" value="{$def_configs.ratio}" id="ratio" name="ratio" placeholder="Tỷ lệ vay">
								<span class="input-group-text cursor-pointer">%</span>
							</div>
						</div>
					</div>
					<div class="form-group mb-2">
						<label class="form-label mb-0">Thời hạn vay (năm)</label>
						<div class="d-flex gap-2 align-items-center">
							<div class="form-group w-{$col_w1}">
								<input type="range" class="form-range js__slider_year_{$uid}" toId="js__input_year_{$uid}" 
								onChange="$Core.calculator.set_change(this, event)" min="1" max="40" value="35">
							</div>
							<div class="input-group input-group-merge w-{$col_w2}">
								<input type="number" uid="{$uid}" class="form-control calc_field js__input_year_{$uid}" toId="js__slider_year_{$uid}" onClick="this.select()" onChange="$Core.calculator.do_calculator(this, event)" id="year" name="year" value="{$def_configs.year}" placeholder="Thời hạn vay (năm)">
								<span class="input-group-text cursor-pointer">N</span>
							</div>
						</div>
					</div>
					<div class="divider text-start">
						<div class="divider-text text-main fw-bold">LÃI SUẤT ƯU ĐÃI</div>
					</div>
					<div class="form-group mb-2">
						<label class="form-label mb-0">Ngân hàng</label>
						<select onChange="$Core.calculator.set_bank_v2(this, event)" class="form-control form-select iso-selectize" 
						data-width="100%" disabled>
							{foreach from=$list_banks item = _oB}
							<option{if $_oB.id eq $def_configs.id} selected{/if} 
									   preferentialRate="{$_oB.preferentialRate}" 
									   preferentialTime="{$_oB.preferentialTime}" 
									   introMonthsUnit="{$_oB.introMonthsUnit}" 
									   rate="{$_oB.rate}" 
									   value="{$_oB.id}">{$_oB.name}</option>
							{/foreach}
						</select>
					</div>
					<div class="form-group mb-2">
						<label class="form-label mb-0" for="giatri">Lãi suất ưu đãi</label>
						<div class="d-flex gap-2 align-items-center">
							<div class="form-group w-{$col_w1}">
								<input type="range" class="form-range js__slider_introRate_{$uid}" step="0.1" toId="js__input_introRate_{$uid}" 
								onChange="$Core.calculator.set_change(this, event)" min="0" max="40" value="{$def_configs.introRate}">
							</div>
							<div class="input-group input-group-merge w-{$col_w2}">
								<input type="number" uid="{$uid}" class="form-control calc_field js__input_introRate_{$uid}" 
								id="introRate" name="introRate" step="0.1" toId="js__slider_introRate_{$uid}" onClick="this.select()" 
								onChange="$Core.calculator.do_calculator(this, event)" value="{$def_configs.introRate}" 
								placeholder="Thời hạn vay (năm)" >
								<span class="input-group-text cursor-pointer">%</span>
							</div>
						</div>
					</div>
					<div class="form-group mb-2">
						<label class="form-label mb-0">Thời gian ưu đãi</label>
						<div class="d-flex gap-2 align-items-center">
							<div class="form-group w-{$col_w1}">
								<input type="range" class="form-range js__slider_introMonths_{$uid}" toId="js__input_introMonths_{$uid}" onChange="$Core.calculator.set_change(this, event)" min="0" max="60" value="{$def_configs.introMonths}">
							</div>
							<div class="input-group w-{$col_w2}">
								<input type="number" uid="{$uid}" class="form-control calc_field js__input_introMonths_{$uid}" 
								toId="js__slider_introMonths_{$uid}" onChange="$Core.calculator.do_calculator(this, event)" name="introMonths" 
								value="{$def_configs.introMonths}" placeholder="Nhập thời gian" id="introMonths" onClick="this.select()">
								<select class="form-control form-select" uid="{$uid}" name="introMonthsUnit" onChange="$Core.calculator.do_calculator(this, event)">
									<option value="_MONTH" {if $def_configs.introMonthsUnit eq '_MONTH'}selected{/if}>Tháng</option>
									<option value="_YEAR" {if $def_configs.introMonthsUnit eq '_YEAR'}selected{/if}>Năm</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group mb-2">
						<label class="form-label mb-0" for="giatri">Lãi suất sau ưu đãi</label>
						<div class="d-flex ga-2 align-items-center">
							<div class="form-group w-{$col_w1}">
								<input type="range" class="form-range js__slider_rate_{$uid}" step="0.1" toId="js__input_rate_{$uid}" 
								onChange="$Core.calculator.set_change(this, event)" min="0" max="40" value="{$def_configs.rate}">
							</div>
							<div class="input-group input-group-merge w-{$col_w2}">
								<input type="number" uid="{$uid}" class="form-control js__input_rate_{$uid} calc_field" toId="js__slider_rate_{$uid}" onClick="this.select()" onChange="$Core.calculator.do_calculator(this, event)" id="rate" value="{$def_configs.rate}" name="rate" placeholder="Nhập lãi suất" step="0.1">
								<span class="input-group-text cursor-pointer">%</span>
							</div>
						</div>
					</div>
					<div class="divider text-start">
						<div class="divider-text text-main fw-bold">PHƯƠNG THỨC TÍNH LÃI</div>
					</div>
					<div class="row mb-2">
						<div class="col-6">
							<div class="form-check">
								<input type="radio" uid="{$uid}" name="paymentMethod" class="form-check-input calc_field" 
								onChange="$Core.calculator.do_calculator(this, event)" id="equal_principal_{$uid}" checked value="1">
								<label class="form-check-label" for="equal_principal_{$uid}">Dư nợ giảm dần </label>
							</div>
						</div>
						<div class="col-6">
							<div class="form-check">
								<input type="radio" uid="{$uid}" name="paymentMethod" class="form-check-input calc_field" 
								onChange="$Core.calculator.do_calculator(this, event)" id="annuity_{$uid}" value="2">
								<label class="form-check-label" for="annuity_{$uid}">Đều hàng tháng</label>
							</div>
						</div>
					</div>
					<div class="form-group mb-2">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="form-check my-2">
							<input onChange="$Core.calculator.set_active(this, event)" class="form-check-input" name="early_payment_fee_status" toId="settle_period" type="checkbox" id="{$gId}" value="1">
							<label class="form-check-label text-upper" for="{$gId}">Thanh toán trước hạn</label>
						</div>
						<div id="block_{$gId}" class="d-none flex-column">
							<div class="form-group mb-2">
								<label class="form-label mb-0">Phí trả nợ trước hạn</label>
								<div class="clearfix"></div>
								<strong id="viewPhiTraNoTruocHan_{$uid}" class="text-main">0.00</strong> {$clsISO->getRate()}
							</div>
							<div class="form-group mb-2">
								<label class="form-label mb-0">Thời gian dự tính thanh toán</label>
								<div class="d-flex gap-2 align-items-center">
									<div class="form-group w-{$col_w1}">
										<input type="range" uid="{$uid}" id="range_{$gId}" class="form-range js__slider_js__input_settle_period_{$uid}" toId="js__input_settle_period_{$uid}" onChange="$Core.calculator.set_change(this, event)" min="0" max="480" value="0">
									</div>
									<div class="input-group w-{$col_w2}">
										<input type="number" class="form-control js__input_settle_period_{$uid} calc_field" onClick="this.select()" onChange="$Core.calculator.do_calculator(this, event)" value="0" uid="{$uid}" toId="js__slider_settle_period_{$uid}" id="settle_period" name="settle_period" placeholder="Nhập số">
										<select uid="{$uid}" class="form-control form-select" name="settle_period_unit" onChange="$Core.calculator.do_calculator(this, event)">
											<option selected value="_MONTH">Tháng</option>
											<option value="_YEAR">Năm</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="form-label mb-1" for="giatri">Phí thanh toán trước hạn</label>
								<div class="d-flex gap-2 align-items-center">
									<div class="form-group w-{$col_w1}">
										<input type="range" uid="{$uid}" id="range_{$gId}" class="form-range js__slider_early_payment_fee_rate_{$uid}" toId="js__input_early_payment_fee_rate_{$uid}" onChange="$Core.calculator.set_change(this, event)" min="0" max="100" value="0">
									</div>
									<div class="input-group input-group-merge w-{$col_w2}">
										<input type="number" class="form-control js__input_early_payment_fee_rate_{$uid} calc_field" onClick="this.select()" onChange="$Core.calculator.do_calculator(this, event)" 
										value="0" uid="{$uid}" toId="js__slider_early_payment_fee_rate_{$uid}" min="0" max="100" id="early_payment_fee_rate" name="early_payment_fee_rate" placeholder="Nhập số">
										<span class="input-group-text cursor-pointer">%</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group mb-2">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="form-check my-2">
							<input class="form-check-input" onChange="$Core.calculator.set_active(this, event)" 
							toId="gracePeriod" type="checkbox" id="{$gId}">
							<label class="form-check-label text-upper" for="{$gId}">Ân hạn nợ gốc</label>
						</div>
						<div id="block_{$gId}" class="d-none ga-2 align-items-center">
							<div class="form-group boxrange w-{$col_w1}">
								<input type="range" class="form-range js__slider_gracePeriod_{$uid}" id="range_{$gId}" toId="js__input_gracePeriod_{$uid}" onChange="$Core.calculator.set_change(this, event)" min="0" max="480" value="0">
							</div>
							<div class="input-group w-{$col_w2}">
								<input type="number" uid="{$uid}" class="form-control js__input_gracePeriod_{$uid} calc_field" 
								toId="js__slider_gracePeriod_{$uid}" onChange="$Core.calculator.do_calculator(this, event)" 
								id="gracePeriod" value="0" name="gracePeriod" onClick="this.select()" placeholder="Nhập số...">
								<select uid="{$uid}" class="form-control form-select" name="gracePeriodUnit" onChange="$Core.calculator.do_calculator(this, event)">
									<option selected="selected" value="_MONTH">Tháng</option>
									<option value="_YEAR">Năm</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group mb-2">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="form-check my-2">
							<input onChange="$Core.calculator.set_active(this, event)" toId="interestRateSupportPeriod" 
							class="form-check-input" type="checkbox" id="{$gId}">
							<label class="form-check-label text-upper" for="{$gId}">Hỗ trợ lãi suất</label>
						</div>
						<div id="block_{$gId}" class="d-none ga-2 align-items-center">
							<div class="form-group w-{$col_w1}">
								<input type="range" class="form-range js__slider_interestRateSupportPeriod_{$uid}" uid="{$uid}" 
								toId="js__input_interestRateSupportPeriod_{$uid}" onChange="$Core.calculator.set_change(this, event)" 
								id="range_{$gId}" min="0" max="480" value="0">
							</div>
							<div class="input-group w-{$col_w2}">
								<input type="number" class="form-control js__input_interestRateSupportPeriod_{$uid} calc_field" 
								onChange="$Core.calculator.do_calculator(this, event)" onClick="this.select()" value="0"  uid="{$uid}" 
								toId="js__slider_interestRateSupportPeriod_{$uid}" id="interestRateSupportPeriod" placeholder="Nhập số" 
								name="interestRateSupportPeriod" >
								<select uid="{$uid}" class="form-control form-select" name="interestRateSupportPeriodUnit" onChange="$Core.calculator.do_calculator(this, event)">
									<option selected value="_MONTH">Tháng</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-6 right__pop position-relative">
					<div class="d-flex flex-column justify-content-between position-relative zindex-2 h-100">
						<div class="top">
							<div class="border border-primary bg-lighter p-3 mb-3 rounded-2">
								<div class="viewLabelLai mb-2">Thanh toán tháng đầu</div>
								<h5 id="viewLaiThangDau_{$uid}" class="text-primary mn-2">0.000.000</h5>
								<div id="viewSubQuote_{$uid}" class="text-muted">Tỉ lệ vay 0% - 0 năm - 0.0%/năm</div>
							</div>
							<div class="form-row">
								<div class="col-12 col-md-7 mb-2 mb-lg-0" id="boxchart_{$uid}"></div>
								<div class="col-12 col-md-5">
									<div class="d-flex align-items-ccenter gap-0 mb-2 flex-column xs:flex-row xs:justify-content-between">
										<span class="fw-bold">Cần trả trước</span>
										<span class="text-fs-18 xs:text-fs-16 viewCanTraTruoc" id="viewCanTraTruoc_{$uid}">0.000</span>
									</div>
									<div class="d-flex align-items-ccenter gap-0 mb-2 flex-column xs:flex-row xs:justify-content-between">
										<span class="fw-bold">Gốc cần trả</span>
										<span class="text-fs-18 xs:text-fs-16 viewGocCanTra" id="viewGocCanTra_{$uid}">0.000</span>
									</div>
									<div class="d-flex align-items-ccenter gap-0 mb-2 flex-column xs:flex-row xs:justify-content-between">
										<span class="fw-bold">Lãi cần trả</span>
										<span class="text-fs-18 xs:text-fs-16 viewLaiCanTra" id="viewLaiCanTra_{$uid}">0.000</span>
									</div>
									<div class="d-flex align-items-ccenter gap-0 mb-2 flex-column xs:flex-row xs:justify-content-between">
										<span class="fw-bold">Tổng tiền & lãi ưu đãi</span>
										<span class="text-fs-18 xs:text-fs-16 viewTongTienLaiPromo" id="viewTongTienLaiPromo_{$uid}">0.000</span>
									</div>
									<div class="d-flex align-items-ccenter gap-0 mb-2 flex-column xs:flex-row xs:justify-content-between">
										<span class="fw-bold">Tổng tiền & lãi sau ưu đãi</span>
										<span class="text-fs-18 xs:text-fs-16 viewTongTienLaiSauPromo" id="viewTongTienLaiSauPromo_{$uid}">0.000</span>
									</div>
									<div class="d-flex align-items-ccenter gap-0 mb-2 flex-column xs:flex-row xs:justify-content-between">
										<span class="fw-bold">Tổng tiền</span>
										<span class="text-fs-18 xs:text-fs-16 viewTongTien" id="viewTongTien_{$uid}">0.000</span>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex flex-column w-100 py-2">
							<div id="html_MonthlyPayment_{$uid}" class="d-none">
								{$html_MonthlyPayment}
							</div>
							<div class="alert alert-danger mb-2 text-fs-12">Lưu ý: Công cụ tính toán này chỉ hỗ trợ cho việc ước tính khoản vay, 
							không phải là sự đảm bảo về khoản vay của {$smarty.const.BRAND_NAME}.</div>
							<div class="d-flex align-items-center justify-content-center">
								<button type="button" data-toggle="ripple" onClick="$Core.calculator.view(this, event)" uid="{$uid}" 
								class="btn btn-block btn-outline-primary"><strong>Xem bảng chi tiết</strong></button>
								{if $deviceType eq 'phone'}
								<button type="button" data-toggle="ripple" class="btn btn-icon btn-outline-default ml-2" data-bs-dismiss="modal" 
								title="Đóng"><i class="bx bx-x"></i></button>
								{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	input[type="range"]{
		width:calc(100% - 20px);
	}
	.right__pop:after{
		content: "";
		position: absolute;
		right: -3px; top: -16px;
		width: calc(100% + 6px);
		height: calc(100% + 32px);
		background: rgba(245, 131, 33, 0.1);
		z-index:1;
	}
	.viewCanTraTruoc {
		color: rgba(54, 162, 235, 1);
		font-weight: bold;
	}
	.viewGocCanTra {
		color: rgba(255, 206, 86, 1);
		font-weight: bold;
	}
	.viewLaiCanTra {
		color: rgba(255, 99, 132, 1);
		font-weight: bold;
	}
	.viewTongTien {
		color: #ec8922;
		font-weight: bold;
	}
	.viewTongTienLaiPromo{
		color: #027f84;
		font-weight: bold;
	}
	.viewTongTienLaiSauPromo{
		color: #18027a;
		font-weight: bold;
	}
</style>
{/literal}