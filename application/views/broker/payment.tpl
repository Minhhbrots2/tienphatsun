<link rel="stylesheet" type="text/css" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$upd_version}" media="all" />
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/html2canvas.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.js?v={$upd_version}"></script>
{assign var = more_information value= $oneProfile.more_information}
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-xxl-10 mx-auto">
			<div class="row align-items-center mb-2">
				<div class="col-12 col-lg-7">
					<div class="d-flex justify-content-start">
						<a href="{$clsISO->getLink('pricing')}" class="d-flex btn btn-outline-default align-items-center rounded-pill px-4 py-1" data-toggle="ripple"><i class="bx bx-undo fs-5"></i> Quay lại</a>
					</div>
				</div>
				<div class="col-lg-5">	
					<div class="d-flex justify-content-end">
						<ul class="steps">
							<li class="step step-success">
								<div class="step-content">
									<span class="step-circle">1</span>
									<span class="step-text text-nowrap">Chọn gói</span>
								</div>
							</li>
							<li class="step step-active">
								<div class="step-content">
									<span class="step-circle">2</span>
									<span class="step-text text-nowrap">Chọn thanh toán</span>
								</div>
							</li>
							<li class="step">
								<div class="step-content">
									<span class="step-circle">3</span>
									<span class="step-text text-nowrap">Chuyển khoản</span>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<form action="{$PCMS_URL}/checkout.html" enctype="multipart/form-data" method="post">
				<div class="row">
					<div class="col-12 col-lg-7 mb-3 mb-lg-0">
						<div class="bg-white rounded-2">
							<div class="card-body py-4">
								<h4 class="title_box mb-3 text-upper fs-5 pb-3 position-relative text-main2">Hình thức thanh toán</h4>
								<div class="lst-pay">
									<label class="item-pay bg-lighter d-flex gap-2 {if $deviceType ne 'phone'}align-items-center{else}align-items-start gap-2{/if} p-3 mb-2 text-dark rounded-2" for="bank">
										<input type="radio" class="radio-pay form-check-input" name="payment_method" id="bank" 
										checked value="pay" onChange="$Core.broker.loadPayMent(this,event)">
										<div class="txt_pay d-flex align-items-center gap-2" style="width: calc(100% - 18px)">
											{if $deviceType ne 'phone'}
											<div class="box_icon_pay p-2 fs-4 border rounded-2"><i class='bx bx-home'></i></div>
											{/if}
											<div class="title_pay {if $deviceType eq 'phone'}fs-14{else}fs-5{/if}">
												<p class="mb-1">Chuyển khoản Ngân hàng / Quét mã QR <span class="text_encourage btn-sm rounded-pill fs-11 text-white bg-main2">Nên dùng</span></p>														
												<p class="text-muted fs-12 mb-0">Chuyển khoản trực tiếp từ ngân hàng đến tài khoản của My Ocean City.</p>
											</div>
										</div>
									</label>
									<label class="d-none item-pay bg-lighter d-flex {if $deviceType ne 'phone'}align-items-center{else}align-items-start gap-2{/if} p-3 text-dark rounded-2" for="money">
										<input type="radio" class="radio-pay form-check-input" name="payment_method" id="money" value="money" onChange="$Core.broker.loadPayMent(this,event)">
										<div class="txt_pay d-flex align-items-center gap-2" style="width: calc(100% - 18px)">
											{if $deviceType ne 'phone'}
											<div class="box_icon_pay p-2 fs-4 border rounded-2 ml-4"><i class='bx bx-credit-card' ></i></div>
											{/if}
											<div class="title_pay {if $deviceType eq 'phone'}fs-14{else}fs-5{/if}">
												<p class="mb-1">Tiền mặt <span class="text_encourage btn-sm rounded-pill fs-11 badge bg-label-secondary">Không khuyến khích</span></p>
												<p class="text-muted fs-12 mb-0">Thanh toán trực tiếp tại Văn Phòng My Ocean City.</p>
											</div>
										</div>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-5">					
						<div class="card-body py-4 bg-lighter text-dark rounded-2">
							<h4 class="mb-4 text-upper fs-5 text-main2">Thông tin gói</h4>
							<div class="d-flex flex-wrap justify-content-between align-items-center fs-5 mb-2">
								<span class="">Gói hội viên</span>
								<span class="fw-semibold">{$package.title} - {$txt_time_package}</span>
								<p class="w-100 text-right text-muted fs-12 mb-0">Dự kiến: {$start_date} - {$end_date}</p>
							</div>
							<div class="d-flex justify-content-between align-items-center fs-5 mb-4">
								<span class="">Số tiền</span>
								<span class="fw-semibold">{$clsISO->priceFormat($price)}đ</span>
							</div>
							<div class="bg-lighter mb-2 p-3 rounded-2">
								<div class="box_method">
									<h5 class="text-dark mb-2">Phương thức thanh toán</h5>
									<p class="fs-14 mb-0" id="txt_payment">Chuyển khoản Ngân hàng / Quét mã QR</p>
								</div>
							</div>
							<div class="bg-lighter mb-4 p-3 rounded-2">
								<div class="box_method">
									<h5 class="text-dark mb-2">Thành viên</h5>
									<div class="d-flex align-items-center fs-14 mb-2"><i class='bx bx-user fs-5 me-1'></i><span class="bg-lighter px-2 py-1 rounded-2 flex-fill">{$oneProfile.full_name}</span></div>
									<div class="d-flex align-items-center fs-14 mb-2"><i class='bx bx-phone fs-5 me-1'></i><span class="bg-lighter px-2 py-1 rounded-2 flex-fill">{$oneProfile.phone}</span></div>
									<div class="d-flex align-items-center fs-14"><i class='bx bx-envelope fs-5 me-1'></i><span class="bg-lighter px-2 py-1 rounded-2 flex-fill">{$oneProfile.email}</span></div>
								</div>
							</div>
							<input type="hidden" name="submit" value="_PAY">
							<div class="form-check mb-3 text-center d-flex justify-content-center gap-2">
							  <input type="checkbox" class="form-check-input" required="true" checked >
							  <label class="form-check-label" for="basic-default-checkbox">Tôi đã đọc và hoàn toàn đồng ý với <a href="javascript:void(0);">các điều khoản thanh toán</a></label>
							</div>
							<div class="d-flex justify-content-center align-items-center mx-auto gap-2">
								<a href="{$clsISO->getLink('pricing')}" class="d-flex btn btn-lg btn-outline-default align-items-center rounded-pill w-40 flex-fill justify-content-center" 
				data-toggle="ripple"><i class="bx bx-undo fs-5"></i> Quay lại</a>
								<button type="submit" class="btn btn-lg rounded-pill btn-primary text-upper w-40 flex-fill" 
								data-toggle="ripple">Tiếp tục</button>
							</div>
							<div class="text-center mt-4">
								<a href="{$smarty.const.SUPPORT_ZALO}" target="_blank" class="text-decoration-underline">
									Hỗ trợ khi gặp vấn đề thanh toán</a>
							</div>
						</div>					
					</div>
				</div>
			</form>
		</div>
	</div>
</div>