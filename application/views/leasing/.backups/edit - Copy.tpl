<form class="frmIssue" id="frmIssue" method="post" enctype="multipart/form-data">
	<div class="container-xxl flex-grow-1 pt-2 container-p-y">
		<div class="row">
			<div class="col-12 col-xl-10 offset-lg-1">
				<div class="d-flex align-items-center justify-content-between{if $deviceType ne 'phone'} pb-2{/if}">
					<h4 class="fw-bold mb-lg-0 mb-xs-2">{if $action eq 'edit'}Cập nhật cho thuê{else}Đăng cho thuê{/if}</h4>
				</div>
				<div class="row">
					<div class="col-lg-8">						
						<div class="card rounded-1 no-shadow border">
							<div class="card-body">
								{if $deviceType ne 'phone'}
								{else}
								<div class="d-flex align-items-center justify-content-between mb-2">
									<div class="p_left">
										<h3 class="mb-0 fs-5">Thông tin cơ bản</h3>
									</div>
									<div class="p_right">
										<button type="button" data-bs-toggle="modal" data-bs-target="#modalImage" class="d-flex align-items-center btn btn-outline-primary">
											{$clsISO->makeIcon('bx-image-add', 'Hình ảnh')}
										</button>
										<div class="modal fade" id="modalImage" tabindex="-1" aria-modal="true">
											<div class="modal-dialog modal-dialog-centered">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Hình ảnh</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
														{assign var = toId value = $clsISO->getUniqid()}
														<input type="hidden" name="total_images" value="{$total_images}" />
														<input type="file" name="images[]" multiple class="d-none select_file_{$toId}" accept="image/*" onChange="$Core.leasing.do_upload(this, event)" />
														<p class="text-muted">Lưu ý: Ảnh đầu tiên sẽ là ảnh đại diện của tin. Bạn có thể tải lên tối đa 20 ảnh, mỗi ảnh không quá 2MB..</p>
														<div class="we-filedrop rounded-1" ondragover="return false"> 
															<a href="javascript:void(0)" class="d-block mb-2 cursor-pointer" toId="{$toId}" onclick="$Core.leasing.select_file(this,event);">
																<svg class="mb-1" width="60" height="60" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path d="M118.42 75.84C118.43 83.2392 116.894 90.5589 113.91 97.33H16.09C12.8944 90.0546 11.3622 82.1579 11.6049 74.2154C11.8477 66.2728 13.8593 58.4844 17.4932 51.4177C21.1271 44.3511 26.2918 38.1841 32.6109 33.3662C38.93 28.5483 46.2443 25.2008 54.0209 23.5676C61.7976 21.9345 69.8406 22.0568 77.564 23.9257C85.2873 25.7946 92.4965 29.363 98.6661 34.3709C104.836 39.3787 109.81 45.6999 113.228 52.8739C116.645 60.0478 118.419 67.8937 118.42 75.84Z" fill="#F2F2F2"></path>
																	<path d="M5.54 97.33H126.37" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M97 97.33H49.91V34.65C49.91 34.3848 50.0154 34.1305 50.2029 33.9429C50.3904 33.7554 50.6448 33.65 50.91 33.65H84.18C84.6167 33.6541 85.0483 33.7445 85.4499 33.9162C85.8515 34.0878 86.2152 34.3372 86.52 34.65L96.02 44.15C96.3321 44.4533 96.5811 44.8153 96.7527 45.2151C96.9243 45.615 97.0152 46.0449 97.02 46.48L97 97.33Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
																	<path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" fill="white"></path>
																	<path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path>
																	<path d="M88.97 52.42H77.33V40.77L88.97 52.42Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M27.32 65.49V70.6" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
																	<path d="M29.88 68.04H24.76" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
																	<path d="M110.49 32.5601V39.9901" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
																	<path d="M114.2 36.27H106.77" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
																	<path d="M34.07 14.58V25.59" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
																	<path d="M39.57 20.08H28.57" stroke="#D7D7D7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
																	<path d="M67 115.86V67.12" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path>
																	<path d="M55.5 78.61L67 67.12L78.5 78.61" fill="white"></path>
																	<path d="M55.5 78.61L67 67.12L78.5 78.61" stroke="#63666A" stroke-width="1" stroke-miterlimit="10"></path>
																</svg>
																<p class="mb-0 text-muted">Bấm để chọn ảnh cần tải lên</p> 
															</a>
															<div class="imageList d-grid flex-wrap gap-3">
															{if isset($more_information.images) && !empty($more_information.images)}
																{foreach from=$more_information.images item = image}
																<div class="item bg-lightest">
																	<img src="{$image}" />
																	<input type="hidden" name="images[]" value="{$image}" />
																	<a class="delete" src="{$image}" onClick="$Core.leasing.delete(this, event)">x</a> 
																</div>
																{/foreach}
															{/if}
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								{/if}						
								<div class="form-group form-row row mb-2">
									<div class="col-sm-6">
										<label class="col-form-label text-main">Cho thuê mã căn</label>
										<input type="text" required="true" onChange="$Core.leasing.check_stock_code(this, event)" placeholder="Nhập mã căn" name="stock_code" maxlength="255" charet="UTF-8" class="form-control no-focus" value="{if $action eq 'edit'}{$oneLeasing.stock_code}{/if}" />
										<span class="form-text">Hệ thống sẽ tự động lấy thông tin chi tiết</span>	
									</div>
									<div class="col-sm-6">
										<label class="col-form-label text-main">Giá thuê(đ)</label>
										<!--  price-In numberonly -->
										<input type="text" required="true" placeholder="3.000.000" name="price" class="form-control price-In numberonly no-focus" value="{if $action eq 'edit'}{$oneLeasing.price}{/if}" />	
									</div>
								</div>
								<div class="form-group form-row mb-2">
									<label class="col-form-label">Tiêu đề</label>
									<input type="text" placeholder="Căn siêu đẹp 3PN sẵn sổ, full đồ tuyệt đẹp tòa S2.08" name="title" maxlength="80" charet="UTF-8" class="form-control no-focus" value="{if $action eq 'edit'}{$oneLeasing.title}{/if}" required/>
								</div>
								<div class="form-group mb-2">
									<label class="col-form-label">Tùy chọn che mã</label>
									<div class="d-flex gap-1 flex-wrap">
									{if !empty($list_property)}
										{foreach from=$list_property item = _oItem}
										<label class="we-radio" for="rdo_{$_oItem.property_id}">
											<input type="radio" id="rdo_{$_oItem.property_id}" name="hide_code"{if $more_information.hide_code eq $_oItem.property_id} checked{/if} value="{$_oItem.property_id}">
											<span>{$_oItem.title}</span>
										</label>
										{/foreach}
									{/if}
									</div>
								</div>
								<div class="form-group mb-2">
									<label class="col-form-label">Điểm nổi bật</label>
									<textarea id="textarea" class="form-control no-focus" maxlength="250" rows="4" name="content" placeholder="Nhập mô tả chung về bất động sản của bạn. Ví dụ: Khu nhà có vị trí thuận lợi, gần công viên, gần trường học ... ">{if $action eq 'edit'}{$more_information.content}{/if}</textarea>
									<div class="form-text">Tối đa 250 ký tự</div>
								</div>
								<div class="form-group form-row row mb-2">
									<div class="col-sm-4">
										<label class="col-form-label">Phí dịch vụ</label>
										<select name="fee_included" class="form-control no-focus form-select">
											{$clsProperty->getSelectByProperty('_FEE_SERVICES_TYPE',$oneLeasing.fee_included)}
										</select>
									</div>
									<div class="col-sm-4">
										<label class="col-form-label">Nội thất</label>
										<div class="clearfix"></div>
										<select name="interior_id" class="form-control no-focus form-select">
											{$clsProperty->getSelectByProperty('_INTERIOR_TYPE',$oneLeasing.interior_id)}
										</select>
									</div>
									<div class="col-sm-4">
										<label class="col-form-label">Đồ cơ bản</label>
										<div class="clearfix"></div>
										<select name="base_utensils_id" class="form-control no-focus form-select">
											{$clsProperty->getSelectByProperty('_BASE_UTENSILS_LEASING',$more_information.base_utensils_id)}
										</select>
									</div>
								</div>								
								<div class="form-group mb-2">
									<label class="col-form-label">Thanh toán</label>
									<div class="d-flex gap-1 flex-wrap">
									{if !empty($list_payment_leasing)}
										<div class="d-flex gap-1 flex-wrap" id="lst_pay">											
											{assign var=check_selected value=0}
											{assign var=index value=0}
											{foreach from=$list_payment_leasing name=i item = _oItem}
												{if $more_information.txt_rental_term eq $_oItem.title}
													{assign var=check_selected value=1}
												{/if}
												{if $smarty.foreach.i.last}
													{assign var=index value=$smarty.foreach.i.index}
												{/if}
												<label class="we-radio" for="rdo_{$_oItem.property_id}" data-index="{$smarty.foreach.i.index}">
													<input type="radio" id="rdo_{$_oItem.property_id}" name="txt_rental_term" {if $more_information.txt_rental_term eq $_oItem.title || (empty($more_information.txt_rental_term) && $smarty.foreach.i.last)} checked{/if} value="{$_oItem.title}">
													<span>{$_oItem.title}</span>
												</label>
											{/foreach}
											{if $more_information.txt_rental_term ne "" && $check_selected eq 0}
												<label class="we-radio" for="rdo_{$index + 1}" data-index="{$index + 1}">
													<input type="radio" id="rdo_{$index + 1}" name="txt_rental_term" checked value="{$more_information.txt_rental_term}">
													<span>{$more_information.txt_rental_term}</span>
												</label>
											{/if}
										</div>
										<button class="d-flex align-items-center btn btn-primary ml-4" type="button" onClick="$Core.leasing.openAddPayOther(this,{$oneLeasing.leasing_id})" id="btn_addPayOther">{$clsISO->makeIcon('bx-plus', '&nbsp;Thêm')}</button>
									{/if}
									</div>
								</div>							
								<div class="form-group form-row row mb-2 d-none">
									<div class="col-sm-4">
										<label class="col-form-label">Tình trạng</label>
										<div class="clearfix"></div>
										<select name="status_leasing_id" class="form-control no-focus form-select">
											{$clsProperty->getSelectByProperty('_STATUS_LEASING',$more_information.status_leasing_id)}
										</select>
									</div>
									<div class="col-sm-4">
										<label class="col-form-label">Thanh toán điện</label>
										<div class="clearfix"></div>
										<select name="payment_electric_id" class="form-control no-focus form-select">
											{$clsProperty->getSelectByProperty('_PAYMENT_ELECTRIC_LEASING',$more_information.payment_electric_id)}
										</select>
									</div>
									<div class="col-sm-4">
										<label class="col-form-label">Thanh toán nước</label>
										<div class="clearfix"></div>
										<select name="payment_water_id" class="form-control no-focus form-select">
											{$clsProperty->getSelectByProperty('_PAYMENT_WATER_LEASING',$more_information.payment_water_id)}
										</select>
									</div>
								</div>						
								<div class="form-group form-row row mb-2">
									<div class="col-sm-4">
										<label class="col-form-label">Thời gian vào được</label>
										<div class="clearfix"></div>
										<div class="form-group box_inp_datepicker">
											<input type="text" class="input_datepicker form-control" placeholder="dd/mm/yyyy" name="rental_period" value="{$more_information.rental_period}">
										</div>
									</div>
									<div class="col-sm-4 d-none">
										<label class="col-form-label">Hạn hđ ký với chủ nhà tới</label>
										<div class="clearfix"></div>
										<input type="text" class="datepicker form-control" placeholder="dd/mm/yyyy" name="validity_period" value="{$more_information.validity_period}">
									</div>
									<div class="col-sm-8">
										<label class="col-form-label">Thời hạn thuê</label>
										<div class="d-flex gap-1 flex-wrap">
										{if !empty($list_rental_term_leasing)}
											{foreach from=$list_rental_term_leasing name=i item = _oItem}
											<label class="we-radio" for="rdo_{$_oItem.property_id}">
												<input type="radio" id="rdo_{$_oItem.property_id}" name="rental_term_leasing_id" {if $more_information.rental_term_leasing_id eq $_oItem.property_id || $smarty.foreach.i.first} checked{/if} value="{$_oItem.property_id}">
												<span>{$_oItem.title}</span>
											</label>
											{/foreach}
										{/if}
										</div>
									</div>
								</div>	
								<hr>							
								<div class="form-group form-row mt-3">
									<div class="d-flex gap-2 align-items-center mb-2">
										<label class="col-form-label">Tiện ích căn hộ</label>
										<div class="btn-group d-flex" role="group" aria-label="Thiết bị">
											<input onchange="$Core.leasing.checkUtilities(this)" gid="{$gid}" type="radio" class="btn-check" name="check_utilities" id="check_yes" value="1" autocomplete="off" {if $more_information.check_utilities eq 1}checked{/if}>
											<label class="btn btn-outline-default" for="check_yes">Có</label>
											<input onchange="$Core.leasing.checkUtilities(this)" gid="{$gid}" type="radio" class="btn-check" name="check_utilities" id="check_no" value="0" autocomplete="off" {if $more_information.check_utilities eq 0}checked{/if}>
											<label class="btn btn-outline-default" for="check_no">Không</label>
										</div>
									</div>
									<div class="box_lst_utilities w-100 {if $more_information.check_utilities eq 0}d-none{/if}" id="box_lst_utilities">
										{foreach from=$array_decvice_leasing item=deviceParent}
											{assign var=deviceChild value=$clsProperty->getItems('_DEVICE', $deviceParent.property_id, "property_id,title")}
											<input type="hidden" name="deviceParent[]" value="{$deviceParent.property_id}">
											
											<div class="widget-block widget-block-lg rounded-2 leasing_device-block collapsed">
												<div class="widget-header d-flex align-items-center justify-content-between no-upper mb-0">
													<div class="cursor-pointer text-upper d-inline-block" onclick="$Core.helper.toggle_block(this,event)">
														{$deviceParent.title}</div>
													<div class="d-flex align-items-center">
														<a onClick="$Core.leasing.checkall(this,event)" tp="checkall" title="Chọn tất cả" 
															class="cusor-pointer">Tất cả</a>
														<span class="mx-1">/</span>
														<a onClick="$Core.leasing.checkall(this,event)" tp="uncheckall" title="Bỏ chọn tất cả" 
															class="cusor-pointer">Bỏ chọn</a>
													</div>
												</div>
												<div class="widget-content">
													<div class="d-flex gap-1 mb-2 flex-wrap">
														{foreach from=$deviceParent.list_child item = _oItem}
														<label class="we-checkbox" for="chk_{$_oItem.property_id}">
															<input type="checkbox" ref_id="{$ref_id}" id="chk_{$_oItem.property_id}" name="device[{$deviceParent.property_id}][]"{if $clsISO->checkInArray($device_leasing[$deviceParent.property_id],$_oItem.property_id)} checked{/if} class="chk_leasing_device" value="{$_oItem.property_id}">
															<span>{$_oItem.title}</span>
														</label>
														{/foreach}
													</div>
												</div>
											</div>
										{/foreach}
									</div>									
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4 mb-4">
						{if $deviceType ne 'phone'}
							<div class="card rounded-1 no-shadow border mb-3">
								<div class="card-body">
									<h3 class="mb-2 fs-5">Hình ảnh tin đăng</h3>
									<p class="text-muted">Lưu ý: Ảnh đầu tiên sẽ là ảnh đại diện của tin. Bạn có thể tải lên tối đa 20 ảnh, mỗi ảnh không quá 2MB..</p>
									{assign var = toId value = $clsISO->getUniqid()}
									<input type="hidden" name="total_images" value="{$total_images}" />
									<input type="file" name="images[]" multiple class="d-none select_file_{$toId}" accept="image/*" onChange="$Core.leasing.do_upload(this, event)" />
									<div class="we-filedrop rounded-1" ondragover="return false"> 
										<a href="javascript:void(0)" class="d-block mb-2 cursor-pointer" toId="{$toId}" onclick="$Core.leasing.select_file(this,event);">
											<svg class="mb-1" width="60" height="60" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M118.42 75.84C118.43 83.2392 116.894 90.5589 113.91 97.33H16.09C12.8944 90.0546 11.3622 82.1579 11.6049 74.2154C11.8477 66.2728 13.8593 58.4844 17.4932 51.4177C21.1271 44.3511 26.2918 38.1841 32.6109 33.3662C38.93 28.5483 46.2443 25.2008 54.0209 23.5676C61.7976 21.9345 69.8406 22.0568 77.564 23.9257C85.2873 25.7946 92.4965 29.363 98.6661 34.3709C104.836 39.3787 109.81 45.6999 113.228 52.8739C116.645 60.0478 118.419 67.8937 118.42 75.84Z" fill="#F2F2F2"></path>
												<path d="M5.54 97.33H126.37" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M97 97.33H49.91V34.65C49.91 34.3848 50.0154 34.1305 50.2029 33.9429C50.3904 33.7554 50.6448 33.65 50.91 33.65H84.18C84.6167 33.6541 85.0483 33.7445 85.4499 33.9162C85.8515 34.0878 86.2152 34.3372 86.52 34.65L96.02 44.15C96.3321 44.4533 96.5811 44.8153 96.7527 45.2151C96.9243 45.615 97.0152 46.0449 97.02 46.48L97 97.33Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
												<path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" fill="white"></path>
												<path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path>
												<path d="M88.97 52.42H77.33V40.77L88.97 52.42Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M27.32 65.49V70.6" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
												<path d="M29.88 68.04H24.76" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
												<path d="M110.49 32.5601V39.9901" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
												<path d="M114.2 36.27H106.77" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
												<path d="M34.07 14.58V25.59" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
												<path d="M39.57 20.08H28.57" stroke="#D7D7D7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
												<path d="M67 115.86V67.12" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path>
												<path d="M55.5 78.61L67 67.12L78.5 78.61" fill="white"></path>
												<path d="M55.5 78.61L67 67.12L78.5 78.61" stroke="#63666A" stroke-width="1" stroke-miterlimit="10"></path>
											</svg>
											<p class="mb-0 text-muted">Bấm để chọn ảnh cần tải lên</p> 
										</a>
										<div class="imageList image_list_news d-grid gap-2">
										{if isset($more_information.images) && !empty($more_information.images)}
											{foreach from=$more_information.images item = image}
											<div class="item bg-lightest">
												<img src="{$image}" />
												<input type="hidden" name="images[]" value="{$image}" />
												<a class="delete" src="{$image}" onClick="$Core.leasing.delete(this, event)">x</a>
											</div>
											{/foreach}
										{/if}
										</div>
									</div>
								</div>
							</div>
						{/if}
						<div class="card rounded-1 no-shadow border">
							<div class="card-body">
								<h3 class="mb-2 fs-5">Thông tin liên hệ</h3>
								<div class="form-group form-row row mb-2">
									<div class="col-sm-6 col-md-6">
										<label class="col-form-label">Họ tên</label>
										<input type="text" placeholder="Nhập tên" required="true" value="{if $action eq 'edit'}{$oneLeasing.contact_name}{else}{$clsProfile->getFullName($profile_id, $oneProfile)}{/if}" onClick="this.select()" name="contact_name" maxlength="255" class="form-control no-focus" />
									</div>
									<div class="col-sm-6 col-md-6">
										<label class="col-form-label">Điện thoại</label>
										<input type="text" class="form-control no-focus" required="true" value="{if $action eq 'edit'}{$oneLeasing.contact_phone}{else}{$oneProfile.phone}{/if}" name="contact_phone" maxlength="255" placeholder="Điện thoại"/>
									</div>
								</div>
								<div class="form-group form-row row mb-2">
									<div class="col-sm-6 col-md-6">
										<label class="col-form-label">Họ tên chủ nhà</label>
										<input type="text" placeholder="Nhập tên" value="{if $action eq 'edit'}{$more_information.host_name}{/if}" onClick="this.select()" name="host_name" maxlength="255" class="form-control no-focus"/>
									</div>
									<div class="col-sm-6 col-md-6">
										<label class="col-form-label">Điện thoại chủ nhà</label>
										<input type="text" class="form-control no-focus" value="{if $action eq 'edit'}{$more_information.host_phone}{/if}" name="host_phone" maxlength="255" placeholder="Điện thoại"/>
									</div>
								</div>
								<div class="alert alert-danger fs-13 mb-0">Thông tin này ko hiển thị bên ngoài, mục đích là để bạn tiện liên hệ với chủ nhà thật. Bạn có thể bỏ qua nếu không cần</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<div class="sticky-bottom zindex-1">
			<div class="row">
				<div class="col-12 col-xl-10 offset-lg-1">
					<div class="row">
						<div class="col-lg-8">
							<div class="buttons rounded-bottom border mt-n1 bg-white p-3">
								<input type="hidden" name="hid" value="hid" />
								<input type="hidden" name="leasing_id" value="{$oneLeasing.leasing_id}" />
								<div class="d-flex align-items-center justify-content-between">
									<a href="{$PCMS_URL}/ct/" class="d-flex align-items-center btn btn-outline-default">
										{$clsISO->makeIcon('bx-chevron-left', 'Quay lại')}
									</a>
									<button{if $action eq 'add'} disabled{/if} type="submit" class="d-flex align-items-center btn btn-primary text-white">
										<span>{if $action eq 'edit'}Cập nhật{else}Đăng tin{/if}</span>
										<i class="bx bx-chevron-right"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		var leasing_id = `{$oneLeasing.leasing_id}`;
		$(document).ready(function(){
			$(".input_datepicker").datepicker({               
				dateFormat: 'dd/mm/yy',
				numberOfMonths: 1,
				showButtonPanel: true,
				changeMonth: true,
				changeYear: true,
				autoclose: true,
				minDate: "+0D",
			});
			setTimeout(() => {
				if($('.leasing_device-block').length){
					$('.leasing_device-block').each((_i, _elem) => {
						console.log(_elem);
						var _block = $(_elem);
						if($('.chk_leasing_device', _block).length){
							var total_checked = 0;
							$('.chk_leasing_device', _block).each((_ii, _chk) => {
								if($(_chk).is(':checked')){
									total_checked += 1;
								}
							});
							
							console.log(total_checked);
							if(total_checked >= 1){
								_block.removeClass('collapsed');
							}
						}
					});
				}
			}, 500);
		});
	</script>
</form>