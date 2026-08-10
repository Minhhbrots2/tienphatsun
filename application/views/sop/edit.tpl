<form class="d-none" method="post" enctype="multipart/form-data">
	<input type="hidden" name="hid" value="hid" />
	<input type="hidden" name="sop_id" value="{$sop_id}" />
	<input type="hidden" name="_token" value="{$_token}" />
	<input type="file" onChange="$Core.sop.upload_video(this, event)" id="sop__select-video" accept="video/*" name="file_video" />
</form>
<form class="frmIssue" id="frmSubmit" method="post" enctype="multipart/form-data">
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="form-row">
			<div class="col-12 col-lg-6 offset-lg-1 col-xxl-5 offset-xxl-2">
				<div class="card rounded-1 no-shadow border mb-2">
					<div class="card-body">
						{if $deviceType ne 'phone'}
						<h3 class="mb-2 fs-4">
							{if $action eq 'edit'}Cập nhật căn bán{else}Đăng bán{/if}
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
												<input type="file" name="images[]" multiple class="d-none select_file_{$toId}" accept="image/*" onChange="$Core.sop.do_upload(this, event)" />
												<p class="text-muted">Lưu ý: Ảnh đầu tiên sẽ là ảnh đại diện của tin. Bạn có thể tải lên tối đa 20 ảnh, mỗi ảnh không quá 2MB..</p>
												<div class="we-filedrop mb-2 rounded-1" ondragover="return false"> 
													<a href="javascript:void(0)" class="d-block mb-2 cursor-pointer" toId="{$toId}" onclick="$Core.sop.select_file(this,event);">
														{$smarty.const.ICON_UPLOAD}
														<p class="mb-0 text-muted">Bấm để chọn ảnh cần tải lên</p> 
													</a>
													<div class="imageList d-grid flex-wrap gap-3">
													{if isset($more_information.images) && !empty($more_information.images)}
														{foreach from=$more_information.images item = image}
														<div class="item bg-lightest">
															<img src="{$image}" />
															<input type="hidden" name="images[]" value="{$image}" />
															<a class="delete" src="{$image}" onClick="$Core.sop.delete_image(this, event)">x</a> 
														</div>
														{/foreach}
													{/if}
													</div>
												</div>
												<div class="accordion accordion-upload-video" id="video-upload">
													<div class="accordion-item mb-1{if $more_information.video_type eq 'youtube'} active{/if}">
														<h2 class="accordion-header">
															<div type="button" class="accordion-button bg-lighter collapsed" data-bs-toggle="collapse" 
															data-bs-target="#video-youtube" onClick="$Core.sop.set_checked(this, event)" aria-expanded="true" 
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
															data-bs-target="#upload-video" onClick="$Core.sop.set_checked(this, event)" aria-expanded="true" 
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
																		<a href="javascript:void(0);" sop_id="{$sop_id}" class="btn-link text-main fs-11" onClick="$Core.sop.cancel_video(this, event)"><i class="bx bx-x"></i> Xóa video</a>
																		{else}
																		<div class="we-filedrop h-px-200 rounded-1">
																			<div title="Chọn video cần tải nên" onClick="$Core.sop.select_video(this, event)" 
																			toId="sop__select-video" class="pt-5 text-center cursor-pointer">
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
						<div class="form-group d-flex gap-2 mb-1 align-items-center">
							<label class="col-form-label">Bạn là</label>
							<div class="btn-group d-flex gap-2" role="group" aria-label="Bạn là">
								<label class="we-radio" for="sale{$gid}">
									<input type="radio" id="sale{$gid}" onChange="$Core.sop.fireEvent(this, event)" name="is_owner"{if $more_information.is_owner eq '0'} checked{/if} value="0">
									<span>Môi giới</span>
								</label>
								<label class="we-radio" for="owner{$gid}">
									<input type="radio" id="owner{$gid}" onChange="$Core.sop.fireEvent(this, event)" name="is_owner"{if $more_information.is_owner eq '1'} checked{/if} value="1">
									<span>Chính chủ</span>
								</label>
							</div>
						</div>
						<div class="form-group mb-2 form-row row-gap-1">
							<div class="col-12 col-sm-6">
								<label class="col-form-label text-main">* Mã căn</label>
								<div class="relative">
									<input type="hidden" name="stock_id" value="{$oneSop.stock_id}" />
									<input type="text" required="true" onChange="$Core.sop.check_stock_code(this, event)" placeholder="Nhập mã căn" name="stock_code" maxlength="255" charet="UTF-8" class="form-control no-focus {if $action eq 'edit'}is-valid{/if}" value="{if $action eq 'edit'}{$oneSop.stock_code}{/if}" id="stock_code" data-stock_code_hide="1"/>
								</div>
							</div>
							<div class="col-12 col-sm-6 flex-fill">
								<div class="d-flex align-items-center justify-content-between col-form-label">
									<span class="text-main">* Giá bán</span>
									<span class="text-capitalize js-price-text font-medium">
										<span class="js-price-text-view"></span>
									</span>
								</div>
								<input type="text" required="true" onkeyup="$Core.sop._handleInputPrice(this, event)" placeholder="" name="price" class="form-control price-In numberonly no-focus" value="{if $action eq 'edit'}{$oneSop.price}{/if}" data-bind="true"/>
							</div>
							<div class="col-12 flex-fill">								
								<div class="form-group mb-2">
									<label class="col-form-label text-main">* Tiêu đề</label>
									<input type="text" placeholder="Căn siêu đẹp 3PN sẵn sổ, full đồ tuyệt đẹp tòa S2.08" name="title" maxlength="80" charet="UTF-8" class="form-control no-focus required" value="{if $action eq 'edit'}{$oneSop.title}{/if}" />
								</div>
							</div>
							<div class="col-12 flex-fill">	
								<div class="form-group mb-2">
									<label class="col-form-label">Điểm nổi bật</label>
									<textarea id="textarea" class="form-control no-focus" maxlength="250" rows="6" name="content" placeholder="Nhập mô tả chung về bất động sản của bạn. Ví dụ: Khu nhà có vị trí thuận lợi, gần công viên, gần trường học ... ">{$more_information.content}</textarea>
									<div class="form-text">Tối đa 250 ký tự</div>
								</div>
							</div>								
							<div class="col-12 box_sop_type">
								<div class="form-group d-flex flex-wrap column-gap-2 mb-1 align-items-center">
									<label class="col-form-label">Loại hình căn hộ</label>
									{assign var=gid value = $clsISO->getUniqid()}
									<div class="btn-group d-flex gap-2" role="group" aria-label="Loại hình căn hộ">
										{foreach from=$list_sop_type item=_oSopType}
											<label class="we-radio" for="sop_type{$_oSopType.property_id}">
												<input type="radio" id="sop_type{$_oSopType.property_id}" name="sop_type" {if $more_information.sop_type eq $_oSopType.property_id} checked{else if !empty($checkDisabledSopType)}disabled{/if} value="{$_oSopType.property_id}" onChange="$Core.sop.loadSoptype(this,event)" >
												<span>{$_oSopType.title}</span>
											</label>
										{/foreach}
									</div>
								</div>
							</div>
							<div class="col-6 flex-fill">
								<label class="col-form-label text-main">* Dự án</label>
								<select class="form-control no-focus form-select iso-select2 required" onChange="$Core.sop.loadStockCode(this, event,{})" name="project_id" data-placeholder="Dự án" data-width="100%" data-allowclear="true" id="slb_Project_Id" toId="slb_Block_Id" data-field="block_id">
									<option value="">Chọn dự án</option>
									{if !empty($list_project)}
										{foreach name=i from=$list_project item=_oProject}
										<option{if $oneSop.project_id eq $_oProject.project_id} selected{/if} value="{$_oProject.project_id}">{$_oProject.title}</option>
										{/foreach}
									{/if}
								</select>
							</div>
							<div class="col-6 flex-fill box_block">
								<label class="col-form-label text-main">* Phân khu</label>
								<select class="form-control no-focus form-select iso-select2 required" onChange="$Core.sop.loadStockCode(this, event,{})" name="block_id" data-placeholder="Phân khu" data-width="100%" data-allowclear="true" id="slb_Block_Id" toId="slb_Building_Id" data-field="building_id">
									<option value="">Phân khu</option>
									{if !empty($listBlocks)}
										{foreach name=i from=$listBlocks item=block}
										<option {if $oneSop.block_id eq $block.property_id} selected{/if} value="{$block.property_id}">{$block.title}</option>
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
										<option value="{$building.property_id}" {if $oneSop.building_id eq $building.property_id} selected{/if}>{$building.title}</option>
										{/foreach}
									{/if}
								</select>
							</div>
							<div class="col-6 flex-fill box_type_villa box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label">Loại hình thấp tầng</label>
								<select class="form-control no-focus form-select iso-select2" data-field="block_id" name="type_villa" data-placeholder="Loại hình căn hộ" data-width="100%" data-allowclear="true" data-field="sop_type">
									<option value="0">Chọn loại hình</option>
									{foreach from=$list_type_villa item=_oTypeVilla}
										<option value="{$_oTypeVilla.property_id}" {if $more_information.type_villa eq $_oSopType.property_id}selected{/if}>{$_oTypeVilla.title}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-6 flex-fill box_bedroom box_high_level {if $more_information.sop_type ne $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label text-main">* Loại phòng</label>
								<select name="bedroom_id" class="form-control no-focus form-select iso-select2" data-placeholder="Loại phòng">
									<option value="0">--Chọn loại phòng--</option>
									{$clsProperty->getSelectByProperty('_BEDROOM',$oneSop.bedroom_id)}
								</select>	
							</div>
							<div class="col-6 flex-fill box_floor_total box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label">Số tầng</label>
								<input type="text" placeholder="Nhập số" name="floor_total" class="form-control no-focus number" value="{if $action eq 'edit'}{$more_information.floor_total}{/if}" />
							</div>
							<div class="col-6 flex-fill box_floor_range box_high_level {if $more_information.sop_type ne $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
								<label class="col-form-label">Tầng thứ</label>
								<input type="text" placeholder="Nhập số" name="floor_range" class="form-control no-focus number" value="{if $action eq 'edit'}{$oneSop.floor}{/if}" />
							</div>
							<div class="col-6 flex-fill">
								<label class="col-form-label">Số nhà</label>
								<input type="text" placeholder="Nhập số nhà" name="code" class="form-control no-focus" value="{if $action eq 'edit'}{$oneSop.code}{/if}" />
							</div>
							<div class="col-6 flex-fill">
								<label class="col-form-label">Hướng ban công</label>
								<select name="home_direction_id" class="form-control no-focus form-select iso-select2">
									<option value="0">--Chọn hướng--</option>
									{$clsProperty->getSelectByProperty('_DIRECTION',$oneSop.home_direction_id)}
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
									{$clsProperty->getSelectByPropertyV2('_ADVANTAGE_SOP',$more_information.advantage_ids)}
    							</select>
							</div>
						</div>
						<div class="form-group mb-2 form-row row-gap-1 box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
							<div class="col-12 col-md-6 flex-fill">
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
										<input class="form-control no-focus w-px-50 input_no_outer_spin" type="number" placeholder="Nhập số" onChange="$Core.sop.loadNumberRadio(this,event)" data-field="bedroom" name="bedroom_num" value="{if $more_information.bedroom_num gt 5}{$more_information.bedroom_num}{/if}">
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6 flex-fill box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
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
										<input class="form-control no-focus w-px-50 input_no_outer_spin" type="number" placeholder="Nhập số" onChange="$Core.sop.loadNumberRadio(this,event)" data-field="bathroom" name="bathroom_num" value="{if $more_information.bathroom_num gt 5}{$more_information.bathroom_num}{/if}">
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6 box_lowfloor {if $more_information.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
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
										<input class="form-control no-focus w-px-50 input_no_outer_spin" type="number" placeholder="Nhập số" onChange="$Core.sop.loadNumberRadio(this,event)" data-field="balcony" name="balcony_num" value="{if $more_information.balcony_num gt 5}{$more_information.balcony_num}{/if}">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				{if $deviceType ne 'phone'}
				<div class="card rounded-1 no-shadow border mb-2">
					<div class="card-body">
						<h3 class="mb-2 fs-5">Hình ảnh tin đăng</h3>
						<p class="text-muted">Lưu ý: Ảnh đầu tiên sẽ là ảnh đại diện của tin. Bạn có thể tải lên tối đa 20 ảnh, mỗi ảnh không quá 2MB..</p>
						{assign var = toId value = $clsISO->getUniqid()}
						<input type="hidden" name="total_images" value="{$total_images}" />
						<input type="file" name="images[]" multiple class="d-none select_file_{$toId}" accept="image/*" onChange="$Core.sop.do_upload(this, event)" />
						<div class="we-filedrop mb-2 rounded-1" ondragover="return false"> 
							<a href="javascript:void(0)" class="d-block mb-2 cursor-pointer" toId="{$toId}" onclick="$Core.sop.select_file(this,event);">
								{$smarty.const.ICON_UPLOAD}
								<p class="mb-0 text-muted">Bấm để chọn ảnh cần tải lên</p> 
							</a>
							<div class="imageList d-grid gap-2">
							{if isset($more_information.images) && !empty($more_information.images)}
								{foreach from=$more_information.images item = image}
								<div class="item bg-lightest">
									<img src="{$image}" />
									<input type="hidden" name="images[]" value="{$image}" />
									<a class="delete" src="{$image}" onClick="$Core.sop.delete_image(this, event)">x</a>
								</div>
								{/foreach}
							{/if}
							</div>
						</div>
						<div class="accordion accordion-upload-video" id="video-upload">
							<div class="accordion-item mb-1{if $more_information.video_type eq 'youtube'} active{/if}">
								<h2 class="accordion-header">
									<div type="button" class="accordion-button bg-lighter collapsed" data-bs-toggle="collapse" 
									data-bs-target="#video-youtube" onClick="$Core.sop.set_checked(this, event)" aria-expanded="true" 
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
									data-bs-target="#upload-video" onClick="$Core.sop.set_checked(this, event)" aria-expanded="true" 
									aria-controls="upload-video">
										<input type="radio" class="form-check-input mr-2" name="video_type" value="upload"{if $more_information.video_type eq 'upload'} checked{/if} />
										Tải video của bạn
									</div>
								</h2>
								<div id="upload-video" class="accordion-collapse collapse{if $more_information.video_type eq 'upload'} show{/if}" data-bs-parent="#video-upload">
									<div class="accordion-body px-0">
										<div class="video-container mb-2">
											<div class="video-player">
												{if !empty($more_information.video_url)}
												<input type="hidden" name="video_url" value="{$more_information.video_url}" />
												<iframe class="rounded-2 mb-2" src="{$more_information.video_url}" width="100%" height="300px"></iframe>
												<a href="javascript:void(0);" sop_id="{$sop_id}" class="btn-link text-main fs-11" onClick="$Core.sop.cancel_video(this, event)"><i class="bx bx-x"></i> Xóa video</a>
												{else}
												<div class="we-filedrop h-px-250 rounded-1">
													<div title="Chọn video cần tải nên" onClick="$Core.sop.select_video(this, event)" 
													toId="sop__select-video" class="pt-5 text-center cursor-pointer">
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
				<div class="card rounded-1 mb-2 mb-lg-0 no-shadow border">
					<div class="card-body">
						{assign var=gid value = $clsISO->getUniqid()}
						<div class="form-group d-flex gap-2 mb-2 align-items-center">
							<div class="fs-5">Nội thất, thiết bị</div>
							<div class="btn-group d-flex" role="group" aria-label="Thiết bị">
								<input onchange="$Core.sop.set_device(this, event)" gid="{$gid}" type="radio" class="btn-check" name="having_ns" id="having_ns_yes_{$gid}" value="yes" autocomplete="off"{if $more_information.having_ns eq 'yes'} checked{/if}>
								<label class="btn btn-outline-default" data-toggle="ripple" for="having_ns_yes_{$gid}">Có</label>
								<input onchange="$Core.sop.set_device(this, event)" gid="{$gid}" type="radio" class="btn-check" name="having_ns" id="having_ns_no_{$gid}" value="no" autocomplete="off"{if $more_information.having_ns eq 'no'} checked{/if}>
								<label class="btn btn-outline-default" data-toggle="ripple" for="having_ns_no_{$gid}">Không</label>
							</div>
						</div>
						<div class="{$gid}{if $more_information.having_ns eq 'no'} d-none{/if}">
							{if !empty($list_devices)}
								{foreach from = $list_devices item = _oP}
								<div class="widget-block widget-block-lg rounded-2 sop_device-block collapsed">
									<div class="widget-header d-flex align-items-center justify-content-between no-upper mb-0">
										<div class="cursor-pointer text-upper d-inline-block" onclick="$Core.helper.toggle_block(this,event)">
											{$_oP.title}</div>
										<div class="d-flex align-items-center">
											<a onClick="$Core.sop.checkall(this,event)" tp="checkall" title="Chọn tất cả" 
												class="cusor-pointer">Tất cả</a>
											<span class="mx-1">/</span>
											<a onClick="$Core.sop.checkall(this,event)" tp="uncheckall" title="Bỏ chọn tất cả" 
												class="cusor-pointer">Bỏ chọn</a>
										</div>
									</div>
									<div class="widget-content">
										<div class="d-flex gap-1 mb-2 flex-wrap">
											{foreach from=$_oP.list_child item = _oItem}
											<label class="we-checkbox" for="chk_{$_oItem.property_id}">
												<input type="checkbox" ref_id="{$ref_id}" id="chk_{$_oItem.property_id}" name="device_ids[]"{if $clsISO->checkItemInArray($_oItem.property_id,$device_ids)} checked{/if} class="chk_sop_device" value="{$_oItem.property_id}">
												<span>{$_oItem.title}</span>
											</label>
											{/foreach}
										</div>
									</div>
								</div>
								{/foreach}
							{/if}
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-4 col-xxl-3">
				<div class="card rounded-1 no-shadow border mb-2">
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
							<label class="col-form-label">Nhu cầu</label>
							{assign var=ref_id value = $clsISO->getUniqid()}
							<div ref_id="{$ref_id}" class="d-flex sop__checkbox-radio gap-1 flex-wrap">
							{if !empty($list_needs)}
								{foreach from=$list_needs item = _oItem}
								<label class="we-radio" for="rdo_{$_oItem.property_id}">
									<input type="radio" ref_id="{$ref_id}" id="rdo_{$_oItem.property_id}" onChange="$Core.sop.fireEvent(this, event)" name="status_id"{if $oneSop.status_id eq $_oItem.property_id} checked{/if} value="{$_oItem.property_id}">
									<span>{$_oItem.title}</span>
								</label>
								{/foreach}
								<a data-bs-toggle="tooltip" id="{$ref_id}" ref_id="{$ref_id}" onClick="$Core.sop.clear_checked(this, event)" 
								   title="Xóa chọn" class="btn btn-icon d-none btn-default"><i class="bx bx-x"></i></a>
							{/if}
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
						<div class="form-group form-row mb-2">
							<div class="col-12 col-lg-6">
								<label class="col-form-label text-nowrap">Phí chuyển nhượng</label>
								<select name="fee_included" class="form-control no-focus form-select">
									{$clsProperty->getSelectByProperty('_FEE_TYPE',$oneSop.fee_included)}
									<!-- <option{if $oneSop.fee_included eq '1'} selected{/if} value="1">Có bao phí</option>
									<option{if $oneSop.fee_included eq '0'} selected{/if} value="0">Không bao phí</option> -->
								</select>
							</div>
							<div class="col-12 col-lg-6">
								<label class="col-form-label">Nội thất</label>
								<div class="clearfix"></div>
								<select name="interior_id" class="form-control no-focus form-select">
									{$clsProperty->getSelectByProperty('_INTERIOR_TYPE',$oneSop.interior_id)}
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-12 col-lg-12">
								<label class="col-form-label">Pháp lý</label>
								{assign var=ref_id value = $clsISO->getUniqid()}
								<div ref_id="{$ref_id}" class="d-flex gap-1 sop__checkbox-radio flex-wrap">
								{if !empty($list_phaply)}
									{foreach from=$list_phaply item = _oItem}
									<label class="we-radio" for="rdo_{$_oItem.property_id}">
										<input type="radio" ref_id="{$ref_id}" id="rdo_{$_oItem.property_id}" onChange="$Core.sop.fireEvent(this, event)" name="juridical_id"{if $oneSop.juridical_id eq $_oItem.property_id} checked{/if} value="{$_oItem.property_id}">
										<span>{$_oItem.title}</span>
									</label>
									{/foreach}
									<a data-bs-toggle="tooltip" id="{$ref_id}" ref_id="{$ref_id}" onClick="$Core.sop.clear_checked(this, event)" 
									   title="Xóa chọn" class="btn btn-icon d-none btn-default"><i class="bx bx-x"></i></a>
								{/if}
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card rounded-1 no-shadow border">
					<div class="card-body">
						<h3 class="mb-2 fs-5">Thông tin liên hệ</h3>
						<div class="form-group form-row mb-2">
							<div class="col-6 col-md-6">
								<label class="col-form-label">Họ tên</label>
								<input type="text" placeholder="Nhập tên" required="true" value="{if $action eq 'edit'}{$oneSop.contact_name}{else}{$clsProfile->getFullName($profile_id, $oneProfile)}{/if}" onClick="this.select()" name="contact_name" maxlength="255" class="form-control no-focus" required="true" />
							</div>
							<div class="col-6 col-md-6">
								<label class="col-form-label">Điện thoại</label>
								<input type="text" class="form-control no-focus" value="{if $action eq 'edit'}{$oneSop.contact_phone}{else}{$oneProfile.phone}{/if}" name="contact_phone" maxlength="255" placeholder="Điện thoại" required="true" />
							</div>
						</div>
						<div class="form-group mb-2 form-row">
							<div class="col-6 col-md-6">
								<label class="col-form-label">Họ tên chủ nhà</label>
								<input type="text" placeholder="Nhập tên" value="{if $action eq 'edit'}{$more_information.host_name}{/if}" onClick="this.select()" name="host_name" maxlength="255" class="form-control no-focus" />
							</div>
							<div class="col-6 col-md-6">
								<label class="col-form-label">Điện thoại</label>
								<input type="text" class="form-control no-focus" value="{if $action eq 'edit'}{$more_information.host_phone}{/if}" name="host_phone" maxlength="255" placeholder="Điện thoại" />
							</div>
						</div>
						<div class="alert alert-danger fs-13 mb-0">Thông tin này ko hiển thị bên ngoài, mục đích là để bạn tiện liên hệ với chủ nhà thật. Bạn có thể bỏ qua nếu không cần</div>
					</div>
				</div>
			</div>
		</div>
		<div class="sticky-bottom zindex-3" {if $deviceType eq 'phone'}style="bottom:40px"{/if}>
			<div class="form-row">
				<div class="col-12 col-lg-10 offset-lg-1 col-xxl-5 offset-xxl-2">
					<div class="buttons rounded-bottom border mt-n1 bg-white p-3">
						<input type="hidden" name="hid" value="hid" />
						<input type="hidden" name="sop_id" value="{$sop_id}" />
						<div class="d-flex align-items-center justify-content-between">
							<a href="{if !empty($return_url)}{$return_url}{else}{$PCMS_URL}/cn/{/if}" data-toggle="ripple" 
								class="d-flex align-items-center btn btn-outline-default">
								{$clsISO->makeIcon('bx-chevron-left', 'Quay lại')}
							</a>
							<button{if $action eq 'add'} disabled{/if} data-toggle="ripple" type="button" class="d-flex align-items-center btn btn-primary text-white btn-submit"  onclick="$Core.sop.validate(this,event)">
								<span>{if $action eq 'edit'}Cập nhật{else}Đăng bán{/if}</span>
								<i class="bx bx-chevron-right"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	var high_level = `{$smarty.const._TYPE_HIGHLEVEL}`;
</script>
{literal}
<script type="text/javascript">
	$(function(){
		setTimeout(() => {
			if($('.sop_device-block').length){
				$('.sop_device-block').each((_i, _elem) => {
					var _block = $(_elem);
					if($('.chk_sop_device', _block).length){
						var total_checked = 0;
						$('.chk_sop_device', _block).each((_ii, _chk) => {
							if($(_chk).is(':checked')){
								total_checked += 1;
							}
						});
						if(total_checked >= 1){
							_block.removeClass('collapsed');
						}
					}
				});
			}
		}, 500);
	})
</script>
{/literal}