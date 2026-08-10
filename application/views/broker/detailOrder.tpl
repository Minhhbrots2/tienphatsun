<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row h-100 align-items-center">
		<div class="col-12 col-md-10 col-lg-8 col-xxl-6 mx-auto">
			<div class="card-body py-4 pt-5 bg-lighter text-dark rounded-2">
				<h4 class="mb-4 text-upper fs-3 text-main2 text-center fw-semibold">Thông tin đơn hàng</h4>
				<div class="d-flex flex-wrap justify-content-between mb-2 border-bottom pb-2 {if $deviceType eq 'phone'}align-items-start flex-column{else}align-items-center{/if}">
					<span class="">Mã thanh toán</span>
					<span class="fw-bold">{$order_code}</span>
				</div>
				<div class="d-flex flex-wrap justify-content-between mb-2 border-bottom pb-2 {if $deviceType eq 'phone'}align-items-start flex-column{else}align-items-center{/if}">
					<span class="">Gói hội viên</span>
					<span class="fw-bold">{$oneItem.text_package}</span>
				</div>
				<div class="d-flex justify-content-between mb-4 {if $deviceType eq 'phone'}align-items-start flex-column{else}align-items-center{/if}">
					<span class="">Số tiền</span>
					<span class="fw-bold text-main">{$clsISO->priceFormat($data_bill.amount)}{$data_bill.currency}</span>
				</div>
				<div class="d-flex flex-wrap justify-content-between mb-2 border-bottom pb-2 {if $deviceType eq 'phone'}align-items-start flex-column{else}align-items-center{/if}">
					<span class="">Ngày đăng ký</span>
					<span class="fw-bold">{$oneItem.reg_date}</span>
				</div>
				<div class="d-flex flex-wrap justify-content-between mb-2 border-bottom pb-2 {if $deviceType eq 'phone'}align-items-start flex-column{else}align-items-center{/if}">
					<span class="">Thời gian</span>
					<span class="fw-bold">{$oneItem.start_date} - {$oneItem.due_date}</span>
				</div>
				<div class="d-flex flex-wrap justify-content-between mb-2 border-bottom pb-2 {if $deviceType eq 'phone'}align-items-start flex-column{else}align-items-center{/if} {if $oneItem.status eq 0}d-none{/if}">
					<span class="">Ngày thanh toán</span>
					<span class="fw-bold" id="status_date">{$oneItem.status_date}</span>
				</div>
				<div class="bg-lighter mb-2 p-3 rounded-2">
					<div class="box_method d-flex flex-wrap justify-content-between align-items-center gap-2">
						<h5 class="text-dark mb-0">Phương thức thanh toán</h5>
						<p class="fs-14 mb-0" id="txt_payment">Chuyển khoản Ngân hàng / Quét mã QR</p>
					</div>
				</div>
				<div class="bg-lighter mb-2 p-3 rounded-2">
					<div class="box_method d-flex flex-wrap justify-content-between align-items-center gap-2">
						<h5 class="text-dark mb-0">Trạng thái thanh toán</h5>
						<p class="fs-14 mb-0" id="txt_payment">{$oneItem.txt_status}</p>
					</div>
				</div>
				<div class="bg-lighter mb-4 p-3 rounded-2">
					<div class="box_method">
						<h5 class="text-dark mb-2">Thành viên</h5>
						<div class="d-flex align-items-center fs-14 mb-2"><i class='bx bx-user fs-5 me-1'></i><span class="bg-lighter px-2 py-1 rounded-2 flex-fill text-break">{$oneMember.full_name}</span></div>
						<div class="d-flex align-items-center fs-14 mb-2"><i class='bx bx-phone fs-5 me-1'></i><span class="bg-lighter px-2 py-1 rounded-2 flex-fill text-break">{$oneMember.phone}</span></div>
						<div class="d-flex align-items-center fs-14"><i class='bx bx-envelope fs-5 me-1'></i><span class="bg-lighter px-2 py-1 rounded-2 flex-fill text-break">{$oneMember.email}</span></div>
					</div>
				</div>
			</div>	
			{if $oneItem.status eq 0 && $oneItem.is_cancel eq 0}
			<div class="accordion mt-4" id="faqs">	
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
															<div class="mb-0 fw-bold">{$data_bill.accountName}</div>
														</div>
													</div>
													<div class="form-row form-group align-items-center mb-3 box_copy">
														<label for="" class="col-6 mb-1">Số tài khoản</label>
														<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_bill.accountNumber}"><i class='bx bx-copy' ></i>Sao chép</a></div>
														<div class="col-12"><div class="mb-0 fw-bold">{$data_bill.accountNumber}</div></div>
													</div>
													<div class="form-row form-group align-items-center mb-3">
														<label for="" class="col-6 mb-1">Số tiền</label>
														<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_bill.amount}"><i class='bx bx-copy' ></i>Sao chép</a></div>
														<div class="col-12">
															<div class="mb-0 fw-bold">{$clsISO->priceFormat($data_bill.amount)}đ</div>
														</div>
													</div>
													<div class="form-row form-group align-items-center mb-3 box_copy">
														<label for="" class="col-6 mb-1">Nội dung</label>
														<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_bill.description}"><i class='bx bx-copy' ></i>Sao chép</a></div>
														<div class="col-12">
															<div class="mb-0 fw-bold">{$data_bill.description}</div> 
														</div>
													</div>
												</div>
											</div>
											<div class="col-12 col-md-5 px-0">
												<div class="card-body">
													<h5 class="fs-16 text-center">Hoặc quét mã QR bằng ứng dụng ngân hàng</h5>
													<img src="https://img.vietqr.io/image/{$data_bill.bin}-{$data_bill.accountNumber}-vietqr_pro.jpg?addInfo={$data_bill.description}&amount={$data_bill.amount}" class="w-100 h-auto" width="100" height="100">
												</div>
											</div>
										</div>
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
										Hãy ghi nhớ Mã đơn hàng <span class="fw-bold text-dark">{$data_bill.description}</span> của bạn để tiện đối chiếu nếu cần.</p>
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
														<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_bill.amount}"><i class='bx bx-copy' ></i>Sao chép</a></div>
														<div class="col-12">
															<div class="mb-0 fw-bold">{$clsISO->priceFormat($data_bill.amount)}đ</div>
														</div>
													</div>
													<div class="form-row form-group align-items-center mb-3 box_copy">
														<label for="" class="col-6 mb-1">Nội dung</label>
														<div class="col-6 text-right"><a href="javascript:void()" role="button" title="Sao chép" onClick="$Core.broker.copyCode(this,event)" data-value="{$data_bill.description}"><i class='bx bx-copy' ></i>Sao chép</a></div>
														<div class="col-12">
															<div class="mb-0 fw-bold">{$data_bill.description}</div> 
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
			{/if}
		</div>
	</div>
</div>
<script>
	var order_id = `{$oneItem.order_id}`;
	var status = `{$oneItem.status}`;
</script>
{literal}
<script>
	$(document).ready(function(){
		if(status == 0){
			$Core.broker.getStatusOrder();	
		}		
	});
</script>
{/literal}