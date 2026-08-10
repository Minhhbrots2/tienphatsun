<link rel="stylesheet" type="text/css" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$upd_version}" media="all" />
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/html2canvas.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.js?v={$upd_version}"></script>
{assign var = more_information value= $oneProfile.more_information}
<div class="container-xxl flex-grow-1 container-p-y">
	<div class="row">
		<div class="{if $payment_method eq 'pay'}col-xxl-8{else}col-xxl-10{/if} mx-auto">
			<form action="/payment-2.html" method="post">
				{if $payment_method eq 'pay'}
				<div class="row">
					<div class="col-lg-8">								
						<div class="d-flex flex-wrap justify-content-between{if $deviceType eq 'phone'} flex-column align-items-start mb-2{else} align-items-center mb-2{/if}">
							<a href="{$clsISO->getLink('payment')}" class="d-flex align-items-center rounded-pill btn btn-outline-default px-4 py-1"><i class="bx bx-undo fs-5"></i>Quay lại</a>
							<h1 class="fw-bold text-dark text-left mb-0">Chuyển khoản</h1>
						</div>
					</div>
					<div class="col-lg-4">	
						<div class="d-flex mb-2 justify-content-end">
							<ul class="steps">
								<li class="step step-success">
									<div class="step-content">
										<span class="step-circle">1</span>
										<span class="step-text text-nowrap">Chọn gói</span>
									</div>
								</li>
								<li class="step step-success">
									<div class="step-content">
										<span class="step-circle">2</span>
										<span class="step-text text-nowrap">Chọn thanh toán</span>
									</div>
								</li>
								<li class="step step-active">
									<div class="step-content">
										<span class="step-circle">3</span>
										<span class="step-text text-nowrap">Chuyển khoản</span>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-lg-8">
						<div class="accordion" id="faqs">	
							<div class="accordion-item mb-2">
								<h2 class="card-body accordion-header rounded-2 overflow-hidden">
									<div type="button" class="accordion-button fs-5 bg-white text-dark py-0" data-bs-toggle="collapse" data-bs-target="#pay1" aria-expanded="true" aria-controls="pay1">Hướng dẫn nạp tiền qua chuyển khoản nhanh</div>
								</h2>
								<div id="pay1" class="accordion-collapse collapse show" data-bs-parent="#faqs">
									<div class="accordion-body">										
										<div class="bg-white rounded-2">
											<div class="card-body pt-0">
												<div class="w-full d-flex lg:gap-4 align-items-center justify-content-start mb-3">
													<img alt="" loading="lazy" width="31.5" height="31.5" decoding="async" data-nimg="1" class="h-8" style="color:transparent" src="{$URL_IMAGES}/icons/lamp.svg">
													<p class="text-xs ml-2 mb-0">Mở App Ngân hàng bất kỳ để <b class="text-sm">quét mã VietQR</b> hoặc<!-- --> <b class="text-sm">chuyển khoản</b> chính xác<!-- --> <!-- -->số tiền<!-- --> bên dưới</p>
												</div>
												<div class="border text-dark rounded-2">
													<div class="d-flex flex-wrap">
														<div class="col-12 col-md-7 px-0">
															<div class="card-body bg-lighter border-right">
																<div class="form-group d-flex align-items-center mb-3">
																	<div class="img_bank me-2">
																		<img alt="Girl in a jacket" loading="lazy" width="30" height="30" decoding="async" data-nimg="1" class="rounded-full m-auto self-center" style="color:transparent" src="https://img.bankhub.dev/rounded/mbbank.png">
																	</div>
																	<div class="">
																		<label for="" class="col-12 mb-1">Ngân hàng</label>
																		<div class="col-12">
																			<div class="mb-0 fw-bold">{$clsConfiguration->getValue('bank_name_MOC')}</div>
																		</div>
																	</div>
																</div>
																<div class="form-row form-group align-items-center mb-3">
																	<label for="" class="col-12 mb-1">Chủ tài khoản</label>
																	<div class="col-12">
																		<div class="mb-0 fw-bold">{$data_response.accountName}</div>
																	</div>
																</div>
																<div class="form-row form-group align-items-center mb-3 box_copy">
																	<label for="" class="col-6 mb-1">Số tài khoản</label>
																	<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_response.accountNumber}"><i class='bx bx-copy' ></i>Sao chép</a></div>
																	<div class="col-12"><div class="mb-0 fw-bold">{$data_response.accountNumber}</div></div>
																</div>
																<div class="form-row form-group align-items-center mb-3">
																	<label for="" class="col-6 mb-1">Số tiền</label>
																	<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_response.amount}"><i class='bx bx-copy' ></i>Sao chép</a></div>
																	<div class="col-12">
																		<div class="mb-0 fw-bold">{$clsISO->priceFormat($data_response.amount)}đ</div>
																	</div>
																</div>
																<div class="form-row form-group align-items-center mb-3 box_copy">
																	<label for="" class="col-6 mb-1">Nội dung</label>
																	<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_response.description}"><i class='bx bx-copy' ></i>Sao chép</a></div>
																	<div class="col-12">
																		<div class="mb-0 fw-bold">{$data_response.description}</div> 
																	</div>
																</div>
															</div>
														</div>
														<div class="col-12 col-md-5 px-0">
															<div class="card-body">
																<h5 class="fs-16 text-center">Hoặc quét mã QR bằng ứng dụng ngân hàng</h5>
																<img src="https://img.vietqr.io/image/{$data_response.bin}-{$data_response.accountNumber}-vietqr_pro.jpg?addInfo={$data_response.description}&amount={$data_response.amount}" class="w-100 h-auto" width="100" height="100">
															</div>
														</div>
													</div>
												</div>
												<div class="d-flex justify-content-end mt-2" id="box_cancel">
													<button data-toggle="ripple" class="btn btn-sm btn-outline-default rounded-2 text-upper" onClick="$Core.broker.cancel_payment(this,event)" name="cancel" order_id="{$order_id}" type="button">Huỷ</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion-item mb-2">
								<h2 class="card-body accordion-header rounded-2 overflow-hidden">
									<div type="button" class="accordion-button fs-5 bg-white text-dark collapsed py-0" data-bs-toggle="collapse" data-bs-target="#pay2" aria-expanded="true" aria-controls="pay2">Chuyển khoản thông thường</div>
								</h2>
								<div id="pay2" class="accordion-collapse collapse" data-bs-parent="#faqs">
									<div class="accordion-body">	
										<div class="bg-white rounded-2">
											<div class="card-body pt-0">
												<div class="w-full d-flex lg:gap-4 align-items-center justify-content-start mb-3">
													<img alt="" loading="lazy" width="31.5" height="31.5" decoding="async" data-nimg="1" class="h-8" style="color:transparent" src="{$URL_IMAGES}/icons/lamp.svg">
													<p class="text-xs ml-2 mb-0">Xin vui lòng chuyển khoản vào tài khoản Ngân hàng của MyOceanCity dưới đây.<br>
													Hãy ghi nhớ Mã đơn hàng <span class="fw-bold text-dark">{$data_response.description}</span> của bạn để tiện đối chiếu nếu cần.</p>
												</div>
												<div class="border text-dark rounded-2">
													<div class="d-flex flex-wrap">
														<div class="col-12 col-md-12 px-0">
															<div class="card-body bg-lighter">
																<div class="form-group d-flex align-items-center mb-3">
																	<div class="img_bank me-2">
																		<img alt="Girl in a jacket" loading="lazy" width="30" height="30" decoding="async" data-nimg="1" class="rounded-full m-auto self-center" style="color:transparent" src="https://img.bankhub.dev/rounded/mbbank.png">
																	</div>
																	<div class="">
																		<label for="" class="col-12 mb-1">Ngân hàng</label>
																		<div class="col-12">
																			<div class="mb-0 fw-bold">{$clsConfiguration->getValue('bank_name_MOC')}</div>
																		</div>
																	</div>
																</div>
																<div class="form-row form-group align-items-center mb-3">
																	<label for="" class="col-12 mb-1">Chủ tài khoản</label>
																	<div class="col-12">
																		<div class="mb-0 fw-bold">{$clsConfiguration->getValue('bank_user_name_MOC')}</div>
																	</div>
																</div>
																<div class="form-row form-group align-items-center mb-3 box_copy">
																	<label for="" class="col-6 mb-1">Số tài khoản</label>
																	<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$clsConfiguration->getValue('bank_number_MOC')}"><i class='bx bx-copy' ></i>Sao chép</a></div>
																	<div class="col-12"><div class="mb-0 fw-bold">{$clsConfiguration->getValue('bank_number_MOC')}</div></div>
																</div>
																<div class="form-row form-group align-items-center mb-3">
																	<label for="" class="col-6 mb-1">Số tiền</label>
																	<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_response.amount}"><i class='bx bx-copy' ></i>Sao chép</a></div>
																	<div class="col-12">
																		<div class="mb-0 fw-bold">{$clsISO->priceFormat($data_response.amount)}đ</div>
																	</div>
																</div>
																<div class="form-row form-group align-items-center mb-3 box_copy">
																	<label for="" class="col-6 mb-1">Nội dung</label>
																	<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_response.description}"><i class='bx bx-copy' ></i>Sao chép</a></div>
																	<div class="col-12">
																		<div class="mb-0 fw-bold">{$data_response.description}</div> 
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">					
						<div class="card text-dark sticky rounded-2 no-shadow">
							<div class="card-body py-4">
								<div class="pb-3 border-bottom mb-4">
									<div class="order_status d-flex justify-content-between align-items-center mb-3">
										<h4 class="mb-0 fs-5 text-dark">Trạng thái</h4>
										<span class="bg-warning text-white px-3 py-1 rounded-pill fs-11" id="status_order">Chờ thanh toán</span>
									</div>
									<div class="order_status d-flex justify-content-between align-items-center">
										<span class="mb-0 text-dark fs-16">Xem order</span>
										<a href="{$clsOrder->getLinkOrder($order_id)}" class="text-main2 fs-16"><i class='bx bx-link me-2'></i>{$order_code}</a>
									</div>
								</div>
								
								<h4 class="mb-4 fs-5 text-dark">Thông tin hỗ trợ</h4>
								<div class="mb-3">
									Khi cần hỗ trợ trong quá trình chuyển khoản, vui lòng liên hệ
								</div>
								<div class="d-flex justify-content-between align-items-center mb-2">
									<span class="text-muted">Email</span>
									<a href="mailto:{$data_configs.email_support_MOC}" class="cursor-pointer px-2">{$data_configs.email_support_MOC}</a>
								</div>
								<div class="d-flex justify-content-between align-items-center">
									<span class="text-muted">Điện thoại</span>
									<span><a href="tel:{$data_configs.phone_support_MOC}" class="cursor-pointer px-1">{$data_configs.phone_support_MOC}</a> 
									<a href="https://zalo.me/{$data_configs.phone_support_MOC}" class="cursor-pointer"><img src="{$URL_IMAGES}/zalo_chat.png" class="w-px-20"></a></span>
								</div>
							</div>
						</div>					
					</div>
				</div>				
				{else}
				<div class="row">
					<div class="col-lg-7">								
						<div class="d-flex flex-wrap justify-content-between {if $deviceType eq 'phone'}flex-column align-items-start mb-2 {else}align-items-center mb-3{/if}">
							<a href="{$clsISO->getLink('payment')}" class="d-flex align-items-center rounded-pill btn btn-outline-default px-4 py-1"><i class="bx bx-undo fs-5"></i>Quay lại</a>
						</div>
					</div>
					<div class="col-lg-7">
						<div class="bg-white rounded-2 {if $deviceType ne 'phone'}h-100{/if}">
							<div class="card-body py-4">
								<h4 class="title_box mb-4 text-upper fs-5 pb-3 position-relative text-main2">Thanh toán tiền mặt</h4>
								<div class="alert alert-primary alert-dismissible mb-4 fs-16" role="alert">
									<p class="mb-2">Xin vui lòng thanh toán bằng tiền mặt tại văn phòng <strong>Công ty cổ phần thương mại & dịch vụ Future Homes</strong> tại địa chỉ:</p>
									<p class="mb-0 fs-18 fw-bold">{$clsConfiguration->getValue('company_address')}</p>
								</div>
								<div class="rounded-2 overflow-hidden">
									<iframe class="w-100" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d450.4738045526025!2d105.95229338743493!3d20.99068566090938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135af493039eae1%3A0x1903245b385bf65a!2zMjExIEjhuqNpIMOCdSAyLCBWaW5ob21lcyBPY2VhbiBQYXJrLCBHaWEgTMOibSwgSMOgIE7hu5lpLCBWaeG7h3QgTmFt!5e1!3m2!1svi!2s!4v1723562433364!5m2!1svi!2s" width="600" height="{if $deviceType eq 'phone'}200{else}450{/if}" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-5">					
						<div class="card-body py-5 bg-lighter text-dark rounded-2 mb-4 {if $deviceType ne 'phone'}h-100{/if}">
							<h4 class="mb-4 text-upper fs-5 text-main2">Thông tin gói</h4>
							<div class="d-flex flex-wrap justify-content-between align-items-center fs-5 mb-2">
								<span class="">Mã đơn hàng</span>
								<span class="fw-semibold">{$order_code}</span>
							</div>
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
									<p class="fs-14 mb-0" id="txt_payment">Tiền mặt</p>
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
							<div class="bg-lighter p-3 rounded-2">
								<div class="box_method">
									<h5 class="text-dark mb-2">Thông tin hỗ trợ</h5>
									<div class="d-flex justify-content-between align-items-center fs-14 mb-2">
										<span class="text-muted">Email</span>
										<a href="mailto:{$data_configs.company_phone}" class="cursor-pointer px-2">{$data_configs.company_phone}</a>
									</div>
									<div class="d-flex justify-content-between align-items-center fs-14 mb-2">
										<span class="text-muted">Điện thoại</span>
										<span><a href="tel:{$data_configs.company_phone}" class="cursor-pointer px-2">{$clsConfiguration->getValue('company_phone')}</a> <a href="https://zalo.me/{$data_configs.company_phone}" class="cursor-pointer"><img src="{$URL_IMAGES}/zalo_chat.png" class="w-px-20"></a></span>
									</div>
								</div>
							</div>
						</div>					
					</div>
				</div>
				{/if}
			</form>
		</div>
	</div>
</div>
<script>
	var order_id = `{$order_id}`;
</script>
{literal}
<script>
	$(document).ready(function(){
		$Core.broker.getStatusOrder();
	});
</script>
{/literal}