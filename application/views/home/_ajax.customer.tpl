<div class="modal-dialog modal-ipad crm-addcus">
	<form method="POST" class="modal-content">
		<div class="modal-header crm-addcus-head">
			<div class="d-flex align-items-center gap-2">
				<span class="crm-addcus-avatar"><i class="bx bx-user-plus"></i></span>
				<h5 class="modal-title mb-0">Thêm khách hàng</h5>
			</div>
			<div class="d-flex align-items-center gap-2">
				<button type="button" openFrom="{$openFrom}" uid="{$uid}" onClick="$Core.global.crm.pop_save_customer(this, event)" 
					class="btn btn-sm btn-primary crm-addcus-head-save"><i class="bx bx-save me-1"></i>Lưu lại</button>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
		</div>
		<div class="modal-body crm-addcus-body">
			<!-- ===== Thông tin cơ bản ===== -->
			 <div class="crm-addcus-panel crm-addcus-panel--basic">
				<div class="form-group form-row">
					<div class="col-12 col-md-6">
						<label class="form-label mb-1">Người phụ trách</label>
						<select class="iso-selectizeImageSearch required" name="admin_id" data-width="100%" data-placeholder="Chọn người phụ trách" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff">
							<option value="{$profile_id}" selected="selected">
								{$clsProfile->getIndentityV2($profile_id, $oneProfile, false)}
							</option>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<label class="form-label mb-1">Chiến dịch <span class="crm-addcus-opt">(tùy chọn)</span></label>
						<div class="clearfix"></div>
						<select name="list_campaign_id[]" id="slb_Campaign_{$uid}" data-placeholder="Chọn chiến dịch" multiple="true" class="form-control iso-selectizeSync" data-width="100%" data-allow-clear="true">
							{if !empty($list_campaigns)}
								{foreach from=$list_campaigns item = _oCampaign}
								<option value="{$_oCampaign.campaign_id}">{$_oCampaign.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</div>
			</div>
			<div class="crm-addcus-panel crm-addcus-panel--basic">
				<div class="crm-addcus-panel-title">
					<span class="crm-addcus-ic crm-addcus-ic--primary">
						<i class="bx bx-user"></i>
					</span> Thông tin cơ bản
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-12 col-md-6">
						<label for="name" class="form-label mb-1">Họ và tên <span class="text-danger">*</span></label>
						<div class="input-group input-group-merge crm-addcus-ig-lg">
							<input type="text" id="name" autocomplete="off" name="name" class="form-control required" placeholder="Nhập họ và tên">
							<span class="input-group-text"><i class="bx bx-user-plus"></i></span>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<label for="phone" class="form-label mb-1">Số điện thoại <span class="text-danger">*</span></label>
						<div class="input-group input-group-merge crm-addcus-ig-lg">
							<input type="text" id="phone" onChange="$Core.helper.format_phone(this, event)" autocomplete="off" name="phone" class="form-control required" placeholder="Nhập số điện thoại">
							<span class="input-group-text"><i class="bx bx-phone"></i></span>
						</div>
					</div>
				</div>
				<div class="mb-2">
					<label for="facebook" class="form-label mb-1">Link Facebook</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bxl-facebook text-primary"></i></span>
						<input type="text" id="facebook" name="facebook" class="form-control" placeholder="https://facebook.com/...">
					</div>
				</div>
				<div>
					<label class="form-label mb-1">Nhu cầu ban đầu</label>
					<textarea name="begin_need" class="form-control autosize" placeholder="Nhập nhu cầu của khách hàng" cols="255" rows="2"></textarea>
				</div>
			</div>
			<!-- ===== Thông tin chi tiết ===== -->
			<div class="crm-addcus-panel crm-addcus-panel--detail">
				<div class="crm-addcus-panel-title crm-addcus-panel-title--muted"><span class="crm-addcus-ic crm-addcus-ic--muted"><i class="bx bx-info-circle"></i></span> Thông tin chi tiết</div>
				<div class="form-row mb-2">
					<div class="col-6">
						<label class="form-label mb-1">Nguồn khách</label>
						<select name="resource_id" class="form-control form-select required">
							<option value="0">Chọn nguồn</option>{$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES',$_ss_storage.resource_id)}
						</select>
					</div>
					<div class="col-6">
						<label class="form-label mb-1">Tình trạng</label>
						<select name="status_id" class="form-control form-select required">
							{$clsProperty->getSelectByProperty('CUSTOMER_STATUS',$_ss_storage.status_id)}
						</select>
					</div>
				</div>
				<div class="form-row mb-2">
					<div class="col-6">
						<label class="form-label mb-1">Loại căn</label>
						<div class="clearfix"></div>
						<select name="list_bedroom_id[]" multiple="true" data-width="100%" data-placeholder="Chọn loại căn" class="form-control iso-select2">
							{if !empty($arr_bedrooms)}
								{foreach from=$arr_bedrooms item = _oI}
								<option{if $clsISO->checkItemInArray($_oI.property_id, $_ss_storage.list_bedroom_id)} selected{/if} value="{$_oI.property_id}">{$_oI.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
					<div class="col-6">
						<label class="form-label mb-1">Loại hình</label>
						<div class="clearfix"></div>
						<select name="blocktype_id" class="form-control form-select">
							{$clsProperty->getSelectByProperty('_BLOCK_TYPE',$_ss_storage.blocktype_id)}
						</select>
					</div>
				</div>
				<div class="form-group mb-2">
					<label class="form-label mb-1">Dự án / Phân khu</label>
					<div class="clearfix"></div>
					<select name="list_block_id[]" data-placeholder="Chọn dự án / phân khu" multiple="true" class="form-control iso-select2" data-width="100%">
						{if !empty($arr_projects)}
							{foreach from=$arr_projects item = _oI}
							<option value="{$_oI.setting_id}">{$_oI.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				
				<div class="d-flex align-items-center gap-2 crm-addcus-fu">
					<label class="switch mb-0">
						<input type="checkbox" name="auto_create_followups" value="1" />
						<span class="slider round"></span>
					</label>
					<span class="fs-13 text-muted">Tạo lịch chăm sóc (FU) tự động</span>
				</div>

				<!-- ===== Thông tin thêm (tùy chọn) ===== -->
				<div class="widget-block collapsed crm-addcus-more">
					<div onClick="$Core.helper.toggle_block(this,event)" class="widget-header">Thông tin thêm <span class="crm-addcus-opt">(tùy chọn)</span></div>
					<div class="widget-content">
						<div class="form-group mb-2">
							<label class="form-label mb-1">Người liên quan</label>
							<div class="clearfix"></div>
							<select class="iso-selectizeImageSearch" name="list_share_id[]" multiple="multiple" data-width="100%" data-placeholder="Người tham gia" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff">
								{$html_assign_def_options}
							</select>
						</div>
						<div class="form-row mb-2">
							<div class="col-12 col-md-6 mb-2 mb-lg-0">
								<label for="email" class="form-label mb-1">Email</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i class="bx bx-envelope"></i></span>
									<input type="text" id="email" name="email" class="form-control" placeholder="example@gmail.com">
								</div>
							</div>
							<div class="col-12 col-md-6">
								<label for="address" class="form-label mb-1">Địa chỉ</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i class="bx bx-buildings"></i></span>
									<input type="text" id="address" name="address" class="form-control" placeholder="Địa chỉ">
								</div>
							</div>
						</div>
						<div class="form-row mb-2">
							<div class="col-12 col-md-6 mb-2 mb-lg-0">
								<label for="tiktok" class="form-label mb-1">Link Tiktok</label>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i class="bx bxl-tiktok"></i></span>
									<input type="text" id="tiktok" name="tiktok" class="form-control" placeholder="Link tiktok">
								</div>
							</div>
							<div class="col-6 col-md-3">
								<label class="form-label mb-1">Quốc gia</label>
								<div class="clearfix"></div>
								<select name="country_id" onchange="$Core.crm.get_select_city(this,event)" toId="slb_City_Id" data-width="100%" class="form-control iso-select2">
									{$clsCountry->makeSelectOption($smarty.const._DEFAULT_COUNTRY)}
								</select>
							</div>
							<div class="col-6 col-md-3">
								<label class="form-label mb-1">Tỉnh/Thành phố</label>
								<select name="city_id" id="slb_City_Id" data-width="100%" data-placeholder="Tỉnh/Thành phố" class="form-control form-select iso-select2">
									{$clsCity->makeSelectOption($smarty.const._DEFAULT_COUNTRY,0)}
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="form-label mb-1">Ghi chú</label>
							<textarea name="notes" class="form-control" cols="255" rows="2"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer crm-addcus-foot">
			<div class="w-100 d-flex align-items-center justify-content-between gap-2">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
				<div class="btn-groups d-flex gap-2">
					<button type="button" openFrom="{$openFrom}" uid="{$uid}" onClick="$Core.global.crm.pop_save_customer(this, event)" class="btn btn-outline-primary js__continue-add">Lưu &amp; thêm</button>
					<button type="button" openFrom="{$openFrom}" uid="{$uid}" onClick="$Core.global.crm.pop_save_customer(this, event)" class="btn btn-primary">Lưu</button>
				</div>
			</div>
		</div>
	</form>
</div>
