<form class="d-none" method="post" enctype="multipart/form-data">
	<input type="hidden" name="hid" value="hid" />
	<input type="hidden" name="leasing_id" value="{$leasing_id}" />
	<input type="hidden" name="_token" value="{$_token}" />
	<input type="file" onChange="$Core.leasing.upload_video(this, event)" id="leasing__select-video" accept="video/*" name="file_video" />
</form>
<form class="frmIssue" id="frmIssue" method="post" enctype="multipart/form-data">
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="form-row">
			<div class="col-12 col-lg-8 col-xxl-5 offset-xxl-2">					
				<div class="card rounded-1 no-shadow border mb-2">
					<div class="card-body">
						{if $deviceType ne 'phone'}
							<h3 class="mb-2 fs-4">
								{if $action eq 'edit'}Cập nhật cho thuê{else}Đăng cho thuê{/if}
							</h3>
							<p class="text-muted">Thông tin có dấu (*) là bắt buộc.</p>
						{else}
						<div class="d-flex align-items-center justify-content-between mb-2">
							<div class="p_left">
								<h3 class="mb-0 fs-5">Thông tin cơ bản</h3>
							</div>
							<div class="p_right">
								<button type="button" data-bs-toggle="modal" data-bs-target="#modalImage" class="d-flex align-items-center btn btn-outline-primary">
									{$clsISO->makeIcon('bx-image-add', 'Ảnh & Video')}
								</button>
								<div class="modal fade" id="modalImage" tabindex="-1" aria-modal="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Hình ảnh & Video</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												{assign var = toId value = $clsISO->getUniqid()}
												<input type="hidden" name="total_images" value="{$total_images}" />
												<input type="file" name="images[]" multiple class="d-none select_file_{$toId}" accept="image/*" onChange="$Core.leasing.do_upload(this, event)" />
												<p class="text-muted">Lưu ý: Ảnh đầu tiên sẽ là ảnh đại diện của tin. Bạn có thể tải lên tối đa 20 ảnh, mỗi ảnh không quá 2MB..</p>
												<div class="we-filedrop rounded-1 mb-2" ondragover="return false"> 
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
												<div class="accordion accordion-upload-video" id="video-upload">
													<div class="accordion-item mb-1{if $more_information.video_type eq 'youtube'} active{/if}">
														<h2 class="accordion-header">
															<div type="button" class="accordion-button bg-lighter collapsed" data-bs-toggle="collapse" 
															data-bs-target="#video-youtube" onClick="$Core.leasing.set_checked(this, event)" aria-expanded="true" 
															aria-controls="video-youtube">
																<input type="radio" class="form-check-input mr-2" name="video_type" value="youtube"{if $more_information.video_type eq 'youtube'} checked{/if} />
																Thêm video từ Youtube
															</div>
														</h2>
														<div id="video-youtube" class="accordion-collapse collapse{if $more_information.video_type eq 'youtube'} show{/if}" data-bs-parent="#video-upload">
															<div class="accordion-body">
																<input type="text" placeholder="VD: https://www.youtube.com/watch?v=Y-Dw0NpfRug" 
																class="form-control no-focus" name="youtue_url" value="{if !empty($more_information.youtue_url)}{$more_information.youtue_url}{/if}">
															</div>
														</div>
													</div>
													<div class="accordion-item{if $more_information.video_type eq 'upload'} active{/if}">
														<h2 class="accordion-header">
															<div type="button" class="accordion-button bg-lighter collapsed" data-bs-toggle="collapse" 
															data-bs-target="#upload-video" onClick="$Core.leasing.set_checked(this, event)" aria-expanded="true" 
															aria-controls="upload-video">
																<input type="radio" class="form-check-input mr-2" name="video_type" value="upload"{if $more_information.video_type eq 'upload'} checked{/if} />
																Tải video của bạn
															</div>
														</h2>
														<div id="upload-video" class="accordion-collapse collapse{if $more_information.video_type eq 'upload'} show{/if}" data-bs-parent="#video-upload">
															<div class="accordion-body">
																<div class="video-container mb-2">
																	<div class="video-player">
																		{if !empty($more_information.video_url)}
																		<input type="hidden" name="video_url" value="{$more_information.video_url}" />
																		<iframe class="rounded-2 mb-2" src="{$more_information.video_url}" width="100%" height="200px"></iframe>
																		<a href="javascript:void(0);" leasing_id="{$leasing_id}" class="btn-link text-main fs-11" onClick="$Core.leasing.cancel_video(this, event)"><i class="bx bx-x"></i> Xóa video</a>
																		{else}
																		<div class="we-filedrop h-px-200 rounded-1">
																			<div title="Chọn video cần tải nên" onClick="$Core.leasing.select_video(this, event)" 
																			toId="leasing__select-video" class="pt-5 text-center cursor-pointer">
																				{$smarty.const.ICON_UPLOAD}
																				<p class="mb-0 text-muted">Bấm để chọn video cần tải lên</p>
																			</div>
																		</div>
																		{/if}
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
						{assign var=gid value = $clsISO->getUniqid()}
						<div class="form-group d-flex gap-2 mb-1 align-items-center">
							<label class="col-form-label">Bạn là</label>
							<div class="btn-group d-flex gap-2" role="group" aria-label="Bạn là">
								<label class="we-radio" for="sale{$gid}">
									<input type="radio" id="sale{$gid}" onChange="$Core.leasing.fireEvent(this, event)" name="is_owner"{if $more_information.is_owner eq '0'} checked{/if} value="0">
									<span>Môi giới</span>
								</label>
								<label class="we-radio" for="owner{$gid}">
									<input type="radio" id="owner{$gid}" onChange="$Core.leasing.fireEvent(this, event)" name="is_owner"{if $more_information.is_owner eq '1'} checked{/if} value="1">
									<span>Chính chủ</span>
								</label>
							</div>
						</div>
						<div class="form-group mb-2 form-row row-gap-1">							
							<div class="col-6">
								<label class="col-form-label text-main">* Cho thuê mã căn</label>
								<div class="relative">
									<input type="hidden" name="stock_id" value="{$oneLeasing.stock_id}" />
									<input type="text" required="true" onChange="$Core.leasing.check_stock_code(this, event)" placeholder="Nhập mã căn" name="stock_code" maxlength="255" charet="UTF-8" class="form-control no-focus" value="{if $action eq 'edit'}{$oneLeasing.stock_code}{/if}" id="stock_code" data-stock_code_hide="1" />	
								</div>
							</div>
							<div class="col-6">
								<div class="d-flex align-items-center justify-content-between col-form-label">
									<span class="text-main">* Giá thuê</span>
									<span class="text-capitalize js-price-text font-medium">
										<span class="js-price-text-view"></span>
									</span>
								</div>
								<input type="text" required="true" onkeyup="$Core.leasing._handleInputPrice(this, event)" placeholder="3.000.000" name="price" class="form-control price-In numberonly no-focus required" value="{if $action eq 'edit'}{$oneLeasing.price}{/if}" />	
							</div>
							<div class="col-12">
								<div class="form-group mb-2">
									<label class="col-form-label text-main">* Tiêu đề</label>
									<input type="text" placeholder="Căn siêu đẹp 3PN sẵn sổ, full đồ tuyệt đẹp tòa S2.08" name="title" maxlength="80" charet="UTF-8" class="form-control no-focus required" value="{if $action eq 'edit'}{$oneLeasing.title}{/if}"/>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group mb-2">
									<label class="col-form-label">Điểm nổi bật</label>
									<textarea id="textarea" class="form-control no-focus" maxlength="300" rows="5" name="content" placeholder="Nhập mô tả chung về bất động sản của bạn. Ví dụ: Khu nhà có vị trí thuận lợi, gần công viên, gần trường học ... ">{if $action eq 'edit'}{$more_information.content}{/if}</textarea>
									<div class="form-text">Tối đa 300 ký tự</div>
								</div>	
							</div>								
							<div class="col-12 box_sop_type">
								<div class="form-group d-flex gap-2 mb-1 align-items-center">
									<label class="col-form-label">Loại hình căn hộ</label>
									{assign var=gid value = $clsISO->getUniqid()}
									<div class="btn-group d-flex gap-2" role="group" aria-label="Loại hình căn hộ">
										{foreach from=$list_sop_type item=_oSopType}
											<label class="we-radio" for="sop_type{$_oSopType.property_id}">
												<input type="radio" id="sop_type{$_oSopType.property_id}" name="sop_type" {if $more_information.sop_type eq $_oSopType.property_id} checked{else if !empty($checkDisabledSopType)}disabled{/if} value="{$_oSopType.property_id}" onChange="$Core.leasing.loadSoptype(this,event)" >
												<span>{$_oSopType.title}</span>
											</label>
										{/foreach}
									</div>
								</div>
							</div>
						</div>						
						<div class="form-group mb-2 form-row row-gap-1">
							<div class="col-6 flex-fill">
								<label class="col-form-label text-main">* Dự án</label>
								<select class="form-control no-focus form-select iso-select2 required" onChange="$Core.leasing.loadStockCode(this, event,{})" name="project_id" data-placeholder="Dự án" data-width="100%" data-allowclear="true" id="slb_Project_Id" toId="slb_Block_Id" data-field="block_id">
									<option value="">Chọn dự án</option>
									{if !empty($list_project)}
										{foreach name=i from=$list_project item=_oProject}
										<option{if $oneLeasing.project_id eq $_oProject.project_id} selected{/if} value="{$_oProject.project_id}">{$_oProject.title}</option>
										{/foreach}
									{/if}
								</select>
							</div>
							<div class="col-6 flex-fill box_block">
								<label class="col-form-label text-main">* Phân khu</label>
								<select class="form-control no-focus form-select iso-select2 required" onChange="$Core.leasing.loadStockCode(this, event,{})" name="block_id" data-placeholder="Phân khu" data-width="100%" data-allowclear="true" id="slb_Block_Id" toId="slb_Building_Id" data-field="building_id">
									<option value="">Phân khu</option>
									{if !empty($listBlocks)}
										{foreach name=i from=$listBlocks item=block}
										<option {if $oneLeasing.block_id eq $block.property_id} selected{/if} value="{$block.property_id}">{$block.title}</option>
										{/foreach}
									{/if}
								</select>
							</div>
							<div class="col-6 flex-fill box_building">
								<label class="col-form-label lbl_building text-main">* Toà/Dãy</label>
								<select class="form-control no-focus form-select iso-select2 required" data-placeholder="Tòa/Dãy" data-width="100%" name="building_id" data-allowclear="true" id="slb_Building_Id" data-field="floor_range">
									<option value="">Toà/Dãy</option>
									{if !empty($listBuildings)}
										{foreach name=i from=$listBuildings item=building}
										<option value="{$building.property_id}" {if $oneLeasing.building_id eq $building.property_id} selected{/if}>{$building.title}</option>
										{/foreach}
									{/if}
								</select>
							</div>
							<div class="col-6 flex-fill box_type_villa box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label">Loại hình thấp tầng</label>
								<select class="form-control no-focus form-select iso-select2" data-field="block_id" name="type_villa" data-placeholder="Loại hình căn hộ" data-width="100%" data-allowclear="true" data-field="sop_type">
									<option value="0">Chọn loại hình</option>
									{foreach from=$list_type_villa item=_oTypeVilla}
										<option value="{$_oTypeVilla.property_id}" {if $more_information.type_villa eq $_oTypeVilla.property_id}selected{/if}>{$_oTypeVilla.title}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-6 flex-fill box_bedroom box_high_level {if $more_information.sop_type ne $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label text-main">* Loại phòng</label>
								<select name="bedroom_id" class="form-control no-focus form-select iso-select2" data-placeholder="Loại phòng">
									<option value="0">--Chọn loại phòng--</option>
									{$clsProperty->getSelectByProperty('_BEDROOM',$oneLeasing.bedroom_id)}
								</select>	
							</div>
							<div class="col-6 flex-fill box_floor_total box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label">Số tầng</label>
								<input type="text" placeholder="Nhập số" name="floor_total" class="form-control no-focus number" value="{if $action eq 'edit'}{$more_information.floor_total}{/if}" />
							</div>
							<div class="col-6 flex-fill box_floor_range box_high_level {if $more_information.sop_type ne $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label">Tầng thứ</label>
								<input type="text" placeholder="Nhập số" name="floor_range" class="form-control no-focus number" value="{if $action eq 'edit'}{$oneLeasing.floor}{/if}" />
							</div>
							<div class="col-6 flex-fill">
								<label class="col-form-label">Số nhà</label>
								<input type="text" placeholder="Nhập số nhà" name="code" class="form-control no-focus" value="{if $action eq 'edit'}{$oneLeasing.code}{/if}" />
							</div>
							<div class="col-6 flex-fill">
								<label class="col-form-label">Hướng ban công</label>
								<select name="home_direction_id" class="form-control no-focus form-select iso-select2">
									<option value="0">--Chọn hướng--</option>
									{$clsProperty->getSelectByProperty('_DIRECTION',$oneLeasing.home_direction_id)}
								</select>	
							</div>
							<div class="col-6 flex-fill">
								<label class="col-form-label">Diện tích <span class="text-lowercase">(m<sup>2</sup>)</span></label>
								<div class="input-group input-group-merge">
									<input class="form-control no-focus" type="number" name="DT_TT" step="0.01" min="0" value="{$more_information.DT_TT}">
									<span class="input-group-text py-0">m<sup>2</sup></span>
								</div>
							</div>
							<div class="col-6 flex-fill">
								<label class="col-form-label">Ưu điểm căn hộ</label>
								<select class="select_checkbox w-100" multiple="multiple"  name="advantage_ids[]" data-nonSelectedText="Chọn" data-allSelectedText="Tất cả" data-nSelectedText="Lựa chọn" data-selectAllText="Chọn tất cả" data-buttonWidth="100%">
									{$clsProperty->getSelectByPropertyV2('_ADVANTAGE_LEASING',$more_information.advantage_ids)}
    							</select>
							</div>
						</div>
						<div class="form-group mb-2 form-row row-gap-1 box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
							<div class="col-6 flex-fill">
								<label class="col-form-label">Số phòng ngủ</label>
								<div class="d-flex flex-wrap align-items-center gap-2 justify-content-start">
									<div class="d-flex align-items-center gap-2 box_number_bedroom">
										<label class="we-radio" for="bedroom_1">
											<input type="radio" id="bedroom_1" name="bedroom" value="1" 
												   {if $more_information.bedroom_num gt 5}disabled{else if $more_information.bedroom_num eq 1}checked{/if}>
											<span class="lbl_text {if $more_information.bedroom_num gt 5}cursor-not-allowed{/if}">1</span>
										</label>
										<label class="we-radio" for="bedroom_2">
											<input type="radio" name="bedroom" value="2" id="bedroom_2" 
												   {if $more_information.bedroom_num gt 5}disabled{else if $more_information.bedroom_num eq 2}checked{/if}>
											<span class="lbl_text {if $more_information.bedroom_num gt 5}cursor-not-allowed{/if}">2</span>
										</label>
										<label class="we-radio" for="bedroom_3">
											<input type="radio" name="bedroom" value="3" id="bedroom_3" 
												   {if $more_information.bedroom_num gt 5}disabled{else if $more_information.bedroom_num eq 3}checked{/if}>
											<span class="lbl_text {if $more_information.bedroom_num gt 5}cursor-not-allowed{/if}">3</span>
										</label>
										<label class="we-radio" for="bedroom_4">
											<input type="radio" name="bedroom" value="4" id="bedroom_4" 
												   {if $more_information.bedroom_num gt 5}disabled{else if $more_information.bedroom_num eq 4}checked{/if}>
											<span class="lbl_text {if $more_information.bedroom_num gt 5}cursor-not-allowed{/if}">4</span>
										</label>
										<label class="we-radio" for="bedroom_5">
											<input type="radio" name="bedroom" value="5" id="bedroom_5" 
												   {if $more_information.bedroom_num gt 5}disabled{else if $more_information.bedroom_num eq 5}checked{/if}>
											<span class="lbl_text {if $more_information.bedroom_num gt 5}cursor-not-allowed{/if}">5</span>
										</label>
									</div>
									<div class="input-group w-px-100">
										<input class="form-control no-focus w-px-50 input_no_outer_spin" type="number" placeholder="Nhập số" onChange="$Core.leasing.loadNumberRadio(this,event)" data-field="bedroom" name="bedroom_num" value="{if $more_information.bedroom_num gt 5}{$more_information.bedroom_num}{/if}">
									</div>
								</div>
							</div>
							<div class="col-6 flex-fill box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label">Số phòng tắm</label>
								<div class="d-flex flex-wrap align-items-center gap-2 justify-content-start">
									<div class="d-flex align-items-center gap-2 box_number_bathroom">
										<label class="we-radio" for="bathroom_1">
											<input class="position-absolute" type="radio" name="bathroom" value="1" id="bathroom_1" 
												   {if $more_information.bathroom_num gt 5}disabled{else if $more_information.bathroom_num eq 1}checked{/if}>
											<span class="lbl_text {if $more_information.bathroom_num gt 5}cursor-not-allowed{/if}">1</span>
										</label>
										<label class="we-radio" for="bathroom_2">
											<input type="radio" name="bathroom" value="2" id="bathroom_2" 
												   {if $more_information.bathroom_num gt 5}disabled{else if $more_information.bathroom_num eq 2}checked{/if}>
											<span class="lbl_text {if $more_information.bathroom_num gt 5}cursor-not-allowed{/if}">2</span>
										</label>
										<label class="we-radio" for="bathroom_3">
											<input type="radio" name="bathroom" value="3" id="bathroom_3" 
												   {if $more_information.bathroom_num gt 5}disabled{else if $more_information.bathroom_num eq 3}checked{/if}>
											<span class="lbl_text {if $more_information.bathroom_num gt 5}cursor-not-allowed{/if}">3</span>
										</label>
										<label class="we-radio" for="bathroom_4">
											<input type="radio" name="bathroom" value="4" id="bathroom_4" 
												   {if $more_information.bathroom_num gt 5}disabled{else if $more_information.bathroom_num eq 4}checked{/if}>
											<span class="lbl_text {if $more_information.bathroom_num gt 5}cursor-not-allowed{/if}">4</span>
										</label>
										<label class="we-radio" for="bathroom_5">
											<input type="radio" name="bathroom" value="5" id="bathroom_5" 
												   {if $more_information.bathroom_num gt 5}disabled{else if $more_information.bathroom_num eq 5}checked{/if}>
											<span class="lbl_text {if $more_information.bathroom_num gt 5}cursor-not-allowed{/if}">5</span>
										</label>
									</div>
									<div class="input-group w-px-100">
										<input class="form-control no-focus w-px-50 input_no_outer_spin" type="number" placeholder="Nhập số" onChange="$Core.leasing.loadNumberRadio(this,event)" data-field="bathroom" name="bathroom_num" value="{if $more_information.bathroom_num gt 5}{$more_information.bathroom_num}{/if}">
									</div>
								</div>
							</div>
							<div class="col-6 box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label">Số ban công</label>
								<div class="d-flex flex-wrap align-items-center gap-2 justify-content-start">
									<div class="d-flex align-items-center gap-2 box_number_balcony">
										<label class="we-radio" for="balcony_1">
											<input type="radio" name="balcony" value="1" id="balcony_1" 
												   {if $more_information.balcony_num gt 5}disabled{else if $more_information.balcony_num eq 1}checked{/if}>
											<span class="lbl_text {if $more_information.balcony_num gt 5}cursor-not-allowed{/if}">1</span>
										</label>
										<label class="we-radio" for="balcony_2">
											<input type="radio" name="balcony" value="2" id="balcony_2" 
												   {if $more_information.balcony_num gt 5}disabled{else if $more_information.balcony_num eq 2}checked{/if}>
											<span class="lbl_text {if $more_information.balcony_num gt 5}cursor-not-allowed{/if}">2</span>
										</label>
										<label class="we-radio" for="balcony_3">
											<input type="radio" name="balcony" value="3" id="balcony_3" 
												   {if $more_information.balcony_num gt 5}disabled{else if $more_information.balcony_num eq 3}checked{/if}>
											<span class="lbl_text {if $more_information.balcony_num gt 5}cursor-not-allowed{/if}">3</span>
										</label>
										<label class="we-radio" for="balcony_4">
											<input type="radio" name="balcony" value="4" id="balcony_4" 
												   {if $more_information.balcony_num gt 5}disabled{else if $more_information.balcony_num eq 4}checked{/if}>
											<span class="lbl_text {if $more_information.balcony_num gt 5}cursor-not-allowed{/if}">4</span>
										</label>
										<label class="we-radio" for="balcony_5">
											<input type="radio" name="balcony" value="5" id="balcony_5" 
												   {if $more_information.balcony_num gt 5}disabled{else if $more_information.balcony_num eq 5}checked{/if}>
											<span class="lbl_text {if $more_information.balcony_num gt 5}cursor-not-allowed{/if}">5</span>
										</label>
									</div>
									<div class="input-group w-px-100">
										<input class="form-control no-focus w-px-50 input_no_outer_spin" type="number" placeholder="Nhập số" onChange="$Core.leasing.loadNumberRadio(this,event)" data-field="balcony" name="balcony_num" value="{if $more_information.balcony_num gt 5}{$more_information.balcony_num}{/if}">
									</div>
								</div>
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
					</div>
				</div>		
				{if $deviceType ne 'phone'}
					<div class="card rounded-1 no-shadow border mb-3">
						<div class="card-body">

							<h3 class="mb-2 fs-5">Hình ảnh tin đăng</h3>
							<p class="text-muted">Lưu ý: Ảnh đầu tiên sẽ là ảnh đại diện của tin. Bạn có thể tải lên tối đa 20 ảnh, mỗi ảnh không quá 2MB..</p>
							{assign var = toId value = $clsISO->getUniqid()}
							<input type="hidden" name="total_images" value="{$total_images}" />
							<input type="file" name="images[]" multiple class="d-none select_file_{$toId}" accept="image/*" onChange="$Core.leasing.do_upload(this, event)" />
							<div class="we-filedrop rounded-1 mb-2" ondragover="return false"> 
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
								<div class="imageList image_list_newss d-grid gap-2">
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
							<div class="accordion accordion-upload-video" id="video-upload">
								<div class="accordion-item mb-1{if $more_information.video_type eq 'youtube'} active{/if}">
									<h2 class="accordion-header">
										<div type="button" class="accordion-button bg-lighter collapsed" data-bs-toggle="collapse" 
										data-bs-target="#video-youtube" onClick="$Core.leasing.set_checked(this, event)" aria-expanded="true" 
										aria-controls="video-youtube">
											<input type="radio" class="form-check-input mr-2" name="video_type" value="youtube"{if $more_information.video_type eq 'youtube'} checked{/if} />
											Thêm video từ Youtube
										</div>
									</h2>
									<div id="video-youtube" class="accordion-collapse collapse{if $more_information.video_type eq 'youtube'} show{/if}" data-bs-parent="#video-upload">
										<div class="accordion-body">
											<input type="text" placeholder="VD: https://www.youtube.com/watch?v=Y-Dw0NpfRug" 
											class="form-control no-focus" name="youtue_url" value="{if !empty($more_information.youtue_url)}{$more_information.youtue_url}{/if}">
										</div>
									</div>
								</div>
								<div class="accordion-item{if $more_information.video_type eq 'upload'} active{/if}">
									<h2 class="accordion-header">
										<div type="button" class="accordion-button bg-lighter collapsed" data-bs-toggle="collapse" 
										data-bs-target="#upload-video" onClick="$Core.leasing.set_checked(this, event)" aria-expanded="true" 
										aria-controls="upload-video">
											<input type="radio" class="form-check-input mr-2" name="video_type" value="upload"{if $more_information.video_type eq 'upload'} checked{/if} />
											Tải video của bạn
										</div>
									</h2>
									<div id="upload-video" class="accordion-collapse collapse{if $more_information.video_type eq 'upload'} show{/if}" data-bs-parent="#video-upload">
										<div class="accordion-body">
											<div class="video-container mb-2">
												<div class="video-player">
													{if !empty($more_information.video_url)}
													<input type="hidden" name="video_url" value="{$more_information.video_url}" />
													<iframe class="rounded-2 mb-2" src="{$more_information.video_url}" width="100%" height="200px"></iframe>
													<div class="d-flex justify-content-between">
														<a href="javascript:void(0);" leasing_id="{$leasing_id}" class="btn-link text-main fs-11" onClick="$Core.leasing.cancel_video(this, event)"><i class="bx bx-x"></i> Xóa video</a>
													</div>
													{else}
													<div class="we-filedrop h-px-200 rounded-1">
														<div title="Chọn video cần tải nên" onClick="$Core.leasing.select_video(this, event)" 
														toId="leasing__select-video" class="pt-5 text-center cursor-pointer">
															{$smarty.const.ICON_UPLOAD}
															<p class="mb-0 text-muted">Bấm để chọn video cần tải lên</p>
														</div>
													</div>
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
				<div class="card rounded-1 no-shadow border">
					<div class="card-body">
						<div class="form-group form-row mt-3">
							<div class="d-flex gap-2 align-items-center mb-2">
								<div class="fs-5">Nội thất, thiết bị</div>
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
			<div class="col-12 col-lg-3 col-xxl-3">
					<div class="card rounded-1 no-shadow border mb-3 {if $deviceType eq 'phone'}mt-2{/if}">
						<div class="card-body">
							<div class="form-group mb-2">
								<label class="col-form-label">Tin độc quyền</label>
								{assign var=gid value = $clsISO->getUniqid()}
								<div ref_id="{$gid}" class="d-flex sop__checkbox-radio gap-1 flex-wrap">
									<label class="we-radio" for="rdo_1">
										<input type="radio" ref_id="{$gid}" id="rdo_1" name="having_dq"{if $more_information.having_dq eq 1} checked{/if} value="1">
										<span>Độc quyền</span>
									</label>
									<label class="we-radio" for="rdo_0">
										<input type="radio" ref_id="{$gid}" id="rdo_0" name="having_dq"{if $more_information.having_dq eq 0} checked{/if} value="0">
										<span>Không độc quyền</span>
									</label>
								</div>							
							</div>
							<div class="form-group mb-2">
								<label class="col-form-label">Tùy chọn che mã</label>
								<div class="d-flex gap-1 flex-wrap box_hideCode">
								{if $more_information.sop_type eq $smarty.const._TYPE_LOWFLOOR}
									{if !empty($lst_codeLowFloor)}
										{foreach from=$lst_codeLowFloor item = _oItem}
										<label class="we-radio" for="rdo_{$_oItem.property_id}">
											<input type="radio" id="rdo_{$_oItem.property_id}" name="hide_code"{if $more_information.hide_code eq $_oItem.property_id} checked{/if} value="{$_oItem.property_id}">
											<span>{$_oItem.title}</span>
										</label>
										{/foreach}
									{/if}
								{else}
									{if !empty($list_property)}
										{foreach from=$list_property item = _oItem}
										<label class="we-radio" for="rdo_{$_oItem.property_id}">
											<input type="radio" id="rdo_{$_oItem.property_id}" name="hide_code"{if $more_information.hide_code eq $_oItem.property_id} checked{/if} value="{$_oItem.property_id}">
											<span>{$_oItem.title}</span>
										</label>
										{/foreach}
									{/if}
								{/if}
								</div>
							</div>
							<div class="form-group form-row row mb-2">
								<div class="col-6">
									<label class="col-form-label">Phí dịch vụ</label>
									<select name="fee_included" class="form-control no-focus form-select">
										{$clsProperty->getSelectByProperty('_FEE_SERVICES_TYPE',$oneLeasing.fee_included)}
									</select>
								</div>
								<div class="col-6">
									<label class="col-form-label">Nội thất</label>
									<div class="clearfix"></div>
									<select name="interior_id" class="form-control no-focus form-select">
										{$clsProperty->getSelectByProperty('_INTERIOR_TYPE',$oneLeasing.interior_id)}
									</select>
								</div>
							</div>
							<div class="form-group form-row row mb-2">
								<div class="col-6">
									<label class="col-form-label">Đồ cơ bản</label>
									<div class="clearfix"></div>
									<select name="base_utensils_id" class="form-control no-focus form-select">
										{$clsProperty->getSelectByProperty('_BASE_UTENSILS_LEASING',$more_information.base_utensils_id)}
									</select>
								</div>								
								<div class="col-6">
									<label class="col-form-label">Thời gian vào được</label>
									<div class="clearfix"></div>
									<div class="form-group box_inp_datepicker">
										<input type="text" class="input_datepicker form-control" placeholder="dd/mm/yyyy" name="rental_period" value="{$more_information.rental_period}" readonly>
									</div>
								</div>
							</div>	
							<div class="form-group form-row row mb-2">
								<div class="col-12 d-none">
									<label class="col-form-label">Hạn hđ ký với chủ nhà tới</label>
									<div class="clearfix"></div>
									<input type="text" class="datepicker form-control" placeholder="dd/mm/yyyy" name="validity_period" value="{$more_information.validity_period}">
								</div>
								<div class="col-12">
									<label class="col-form-label">Hợp đồng</label>
									<div class="d-flex gap-1 flex-wrap">
									{if !empty($list_rental_term_leasing)}
										{foreach from=$list_rental_term_leasing name=i item = _oItem}
										<label class="we-radio" for="rdo_{$_oItem.property_id}">
											<input type="radio" id="rdo_{$_oItem.property_id}" name="rental_term_leasing_id" {if $oneLeasing.ms_period_id eq $_oItem.property_id} checked{/if} value="{$_oItem.property_id}">
											<span>{$_oItem.title}</span>
										</label>
										{/foreach}
									{/if}
									</div>
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
									<button class="d-flex align-items-center btn btn-outline-default d-none {if $deviceType ne 'phone'}ml-4{else}mt-2{/if}" type="button" onClick="$Core.leasing.openAddPayOther(this,{$oneLeasing.leasing_id})" id="btn_addPayOther">{$clsISO->makeIcon('bx-plus', '&nbsp;Thêm')}</button>
								{/if}
								</div>
							</div>
						</div>
					</div>	
				<div class="card rounded-1 no-shadow border">
					<div class="card-body">
						<h3 class="mb-2 fs-5">Thông tin liên hệ</h3>
						<div class="form-group form-row row mb-2">
							<div class="col-6 col-md-6">
								<label class="col-form-label">Họ tên</label>
								<input type="text" placeholder="Nhập tên" required="true" value="{if $action eq 'edit'}{$oneLeasing.contact_name}{else}{$clsProfile->getFullName($profile_id, $oneProfile)}{/if}" onClick="this.select()" name="contact_name" maxlength="255" class="form-control no-focus" />
							</div>
							<div class="col-6 col-md-6">
								<label class="col-form-label">Điện thoại</label>
								<input type="text" class="form-control no-focus" required="true" value="{if $action eq 'edit'}{$oneLeasing.contact_phone}{else}{$oneProfile.phone}{/if}" name="contact_phone" maxlength="255" placeholder="Điện thoại"/>
							</div>
						</div>
						<div class="form-group form-row row mb-2">
							<div class="col-6 col-md-6">
								<label class="col-form-label">Họ tên chủ nhà</label>
								<input type="text" placeholder="Nhập tên" value="{if $action eq 'edit'}{$more_information.host_name}{/if}" onClick="this.select()" name="host_name" maxlength="255" class="form-control no-focus"/>
							</div>
							<div class="col-6 col-md-6">
								<label class="col-form-label">Điện thoại chủ nhà</label>
								<input type="text" class="form-control no-focus" value="{if $action eq 'edit'}{$more_information.host_phone}{/if}" name="host_phone" maxlength="255" placeholder="Điện thoại"/>
							</div>
						</div>
						<div class="alert alert-danger fs-13 mb-0">Thông tin này ko hiển thị bên ngoài, mục đích là để bạn tiện liên hệ với chủ nhà thật. Bạn có thể bỏ qua nếu không cần</div>
					</div>
				</div>
			</div>
		</div>
		<div class="sticky-bottom zindex-3">
			<div class="form-row">
				<div class="col-12 col-lg-8 offset-lg-2 col-xxl-5 offset-xxl-2">
					<div class="buttons rounded-bottom border mt-n1 bg-white p-3">
						<input type="hidden" name="hid" value="hid" />
								<input type="hidden" name="leasing_id" value="{$oneLeasing.leasing_id}" />
						<div class="d-flex align-items-center justify-content-between">
							<a href="{if !empty($return_url)}{$return_url}{else}{$PCMS_URL}/ct/{/if}" class="d-flex align-items-center btn btn-outline-default">
										{$clsISO->makeIcon('bx-chevron-left', 'Quay lại')}
									</a>
							<button{if $action eq 'add'} disabled{/if} data-toggle="ripple" type="button" class="d-flex align-items-center btn btn-primary text-white btn-submit"  onclick="$Core.leasing.validate(this,event)">
								<span>{if $action eq 'edit'}Cập nhật{else}Đăng tin{/if}</span>
								<i class="bx bx-chevron-right"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		var leasing_id = `{$oneLeasing.leasing_id}`;
		var high_level = `{$smarty.const._TYPE_HIGHLEVEL}`;
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