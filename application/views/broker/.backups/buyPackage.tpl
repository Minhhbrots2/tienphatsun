<link rel="stylesheet" type="text/css" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$upd_version}" media="all" />
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/html2canvas.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.js?v={$upd_version}"></script>
{assign var = more_information value= $oneProfile.more_information}
<div class="content-wrapper">
    <section class="section-py bg-body first-section-pt">
		<div class="container">
			<div class="row">
				<div class="col-xxl-8 mx-auto">
					<form action="/payment-2.html" method="post">
						{if $payment_method eq 'pay'}
						<div class="row">
							<div class="col-lg-8">								
								<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
									<a href="{$clsISO->getLink('pricing')}" class="d-flex align-items-center rounded-pill btn btn-outline-default px-4 py-1"><i class="bx bx-undo fs-5"></i>Quay lại</a>
									<h1 class="fw-bold text-dark">Chuyển khoản</h1>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="bg-white rounded-2 mb-4">
									<div class="card-body py-4">
										<h4 class="mb-4 fs-5 text-dark">Hướng dẫn nạp tiền qua chuyển khoản nhanh</h4>
										<div class="border text-dark rounded-2">
											<div class="d-flex flex-wrap">
												<div class="col-12 col-md-7 px-0">
													<div class="card-body bg-lighter border-right">
														<div class="form-row form-group align-items-center mb-3 box_copy">
															<label for="" class="col-6 mb-1">Số tài khoản thụ hưởng</label>
															<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$oneProfile.code}"><i class='bx bx-copy' ></i>Sao chép</a></div>
															<div class="col-12"><div class="mb-0 fw-bold">{$oneProfile.code}</div></div>
														</div>
														<div class="form-row form-group align-items-center mb-3">
															<label for="" class="col-12 mb-1">Ngân hàng thụ hưởng</label>
															<div class="col-12">
																<div class="mb-0 fw-bold">{$clsConfiguration->getValue('bank_name_MOC')}</div>
															</div>
														</div>
														<div class="form-row form-group align-items-center mb-3 box_copy">
															<label for="" class="col-6 mb-1">Tài khoản thụ hưởng</label>
															<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$clsConfiguration->getValue('bank_number_MOC')}"><i class='bx bx-copy' ></i>Sao chép</a></div>
															<div class="col-12">
																<div class="mb-0 fw-bold">{$clsConfiguration->getValue('bank_number_MOC')}</div>
															</div>
														</div>
														<div class="form-row form-group align-items-center mb-3">
															<label for="" class="col-12">Tên tài khoản thụ hưởng</label>
															<div class="col-12 mb-1">
																<div class="mb-0 fw-bold">{$oneProfile.code} {$clsConfiguration->getValue('bank_user_name_MOC')}</div>
															</div>
														</div>
														<div class="form-row form-group align-items-center mb-3">
															<label for="" class="col-12 mb-1">Số tiền</label>
															<div class="col-12">
																<div class="mb-0 fw-bold">{$clsISO->priceFormat($price)}đ</div>
															</div>
														</div>
														<div class="form-row form-group align-items-center mb-3 box_copy">
															<label for="" class="col-6 mb-1">Nội dung chuyển khoản</label>
															<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="abcxyz"><i class='bx bx-copy' ></i>Sao chép</a></div>
															<div class="col-12">
																<div class="mb-0 fw-bold">abcxyz</div> 
															</div>
														</div>
													</div>
													
												</div>
												<div class="col-12 col-md-5 px-0">
													<div class="card-body">
														<h5 class="fs-16 text-center">Hoặc quét mã QR bằng ứng dụng ngân hàng</h5>
														<img src="https://api.vietqr.io/image/970436-0541000181590-O1C1OtW.jpg?accountName={$clsConfiguration->getValue('bank_user_name_MOC')}&amount={$price}&addInfo=Thanh%20toan" alt="" class="w-100 h-auto" width="100" height="100">
													</div>													
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4">					
								<div class="card text-dark rounded-2 no-shadow">
									<div class="card-body py-4">
										<h4 class="mb-4 fs-5 text-dark">Thông tin hỗ trợ</h4>
										<div class="mb-3">
											Khi cần hỗ trợ trong quá trình chuyển khoản, vui lòng liên hệ
										</div>
										<div class="d-flex justify-content-between align-items-center fs-16 mb-3">
											<span class="">Email</span>
											<a href="mailto:{$clsConfiguration->getValue('company_email')}" class="cursor-pointer px-2">{$clsConfiguration->getValue('company_email')}</a>
										</div>
										<div class="d-flex justify-content-between align-items-center fs-16 mb-3">
											<span class="">Điện thoại</span>
											<span><a href="tel:{$clsConfiguration->getValue('company_phone')}" class="cursor-pointer px-2">{$clsConfiguration->getValue('company_phone')}</a> <a href="https://zalo.me/{$clsConfiguration->getValue('company_phone')}" class="cursor-pointer"><img src="{$URL_IMAGES}/zalo_chat.png" width="25px"> </a></span>
										</div>
									</div>
								</div>					
							</div>
						</div>
						{else}
						{/if}
					</form>
				</div>
			</div>
		</div>
	</section>
</div>