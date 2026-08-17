<script type="text/javascript">
	var project_id = '{$pvalTable}';
	var map_la = '{$more_information.map_la}';
	var map_lo = '{$more_information.map_lo}';
</script>
<div class="ui-layout">
<header class="pe-hero">
	<div class="pe-hero__nav">
		<a href="{$PCMS_URL}/index.php?mod={$mod}&act=overview&project_id={$pvalTable}" class="btn btn-default pe-hero__back">{$core->makeIcon('angle-left mr-5')}<span>Quay lại</span></a>
	</div>
	<div class="pe-hero__main">
		<div class="pe-hero__heading">
			{if $pvalTable gt '0'}
				<h1 class="pe-hero__title">{$oneItem.title|escape}</h1>
				{if $oneItem.code}<span class="pe-hero__code">{$oneItem.code|escape}</span>{/if}
			{else}
				<h1 class="pe-hero__title">Thêm dự án</h1>
			{/if}
		</div>
		<div class="pe-hero__actions">
			<button type="button" onClick="$Core.project.open_setting_table_update(this, event)"
				data-project-id="{$pvalTable}"
				class="btn btn-default">{$core->makeIcon('cloud-upload mr-3')}<span>Tạo bảng hàng thấp tầng</span></button>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<form class="form-upload d-none" method="post" action="" enctype="multipart/form-data">
	<input type="file" name="attachment" class="selectFile" />
</form>
<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="pe-layout">
		<aside class="pe-rail">
			<div class="pe-rail__search">
				{$core->makeIcon('search')}
				<input type="text" id="pe-search" class="form-control" placeholder="Tìm nhóm cấu hình..." autocomplete="off" aria-label="Tìm nhóm cấu hình" />
			</div>
			<nav class="pe-rail__nav" aria-label="Nhóm cấu hình dự án">
				<a href="#pe-info" class="pe-rail__item is-active" data-slug="pe-info"><i class="bx bx-info-circle"></i><span>Thông tin chung</span></a>
				<a href="#pe-specs" class="pe-rail__item" data-slug="pe-specs"><i class="bx bx-ruler"></i><span>Thông số &amp; tổng quan</span></a>
				<a href="#pe-media" class="pe-rail__item" data-slug="pe-media"><i class="bx bx-image"></i><span>Hình ảnh &amp; màu sắc</span></a>
				<a href="#pe-sheets" class="pe-rail__item" data-slug="pe-sheets"><i class="bx bx-spreadsheet"></i><span>Bảng hàng &amp; quản trị</span></a>
				<a href="#pe-booking" class="pe-rail__item" data-slug="pe-booking"><i class="bx bx-user-check"></i><span>Booking &amp; admin dự án</span></a>
				<a href="#pe-utilities" class="pe-rail__item" data-slug="pe-utilities"><i class="bx bx-map-pin"></i><span>Tiện ích dự án</span></a>
				<a href="#pe-sop" class="pe-rail__item" data-slug="pe-sop"><i class="bx bx-book-content"></i><span>Nội dung trang dự án</span></a>
				<a href="#pe-vr" class="pe-rail__item" data-slug="pe-vr"><i class="bx bx-street-view"></i><span>VR360 &amp; Tiles</span></a>
			</nav>
		</aside>
		<div class="pe-groups">
			{* ---------- 1. Thong tin chung ---------- *}
			<section class="pe-group" id="pe-info" data-label="Thông tin chung" data-keyword="ma code ten tieu de loai hinh chu dau tu tinh thanh pho khu vuc dia chi link">
				<header class="pe-group__head">
					<span class="pe-group__icon"><i class="bx bx-info-circle"></i></span>
					<div class="pe-group__meta">
						<h2 class="pe-group__title">Thông tin chung</h2>
						<p class="pe-group__desc">Mã, tên, loại hình và vị trí hành chính của dự án.</p>
					</div>
				</header>
				<div class="pe-group__body">
					<div class="pe-grid pe-grid--3">
						<div class="pe-field">
							<label class="pe-field__label">{$core->get_Lang('Code')} <b class="pe-field__req">*</b></label>
							<input type="text" class="form-control required" name="iso-code"
								value="{if $pvalTable gt '0'}{$oneItem.code|escape}{/if}" required maxlength="255"
								placeholder="Tên viết tắt dự án" />
						</div>
						<div class="pe-field">
							<label class="pe-field__label">{$core->get_Lang('Title')} <b class="pe-field__req">*</b></label>
							<input type="text" class="form-control required" name="iso-title"
								value="{if $pvalTable gt '0'}{$oneItem.title|escape}{/if}" required maxlength="255"
								placeholder="{$core->get_Lang('Title')}" />
						</div>
						<div class="pe-field">
							<label class="pe-field__label">Loại hình <b class="pe-field__req">*</b></label>
							<select name="block_type[]" multiple="true" class="form-control iso-select2 required">
								{$clsProperty->getSelectByPropertyV2('_BLOCK_TYPE',$block_type_arrs,'Loại hình')}
							</select>
						</div>
						<div class="pe-field">
							<label class="pe-field__label">Chủ đầu tư</label>
							<select class="form-control iso-select2" name="investor_id">
								{$clsProperty->getSelectByProperty('_INVESTOR',$more_information.investor_id)}
							</select>
						</div>
						<div class="pe-field">
							<label class="pe-field__label">Tỉnh</label>
							<select class="form-control iso-select2" name="city_id">
								{$clsCity->makeSelectOption($smarty.const._DEFAULT_COUNTRY,$more_information.city_id)}
							</select>
						</div>
						<div class="pe-field">
							<label class="pe-field__label">Khu vực</label>
							<select class="form-control iso-select2" name="iso-area_id">
								{$clsSetting->getOptionOrigin('_AREA',0,$oneItem.area_id)}
							</select>
						</div>
					</div>
					<div class="pe-grid pe-grid--2">
						<div class="pe-field">
							<label class="pe-field__label">{$core->get_Lang('Address')} <b class="pe-field__req">*</b></label>
							<input type="text" class="form-control required" name="address"
								value="{if $pvalTable gt '0'}{$more_information.address|escape}{/if}" required
								maxlength="255" placeholder="{$core->get_Lang('Address')}" />
						</div>
						<div class="pe-field">
							<label class="pe-field__label">Link</label>
							<input class="form-control" name="iso-link"
								value="{if !empty($oneItem.link)}{$oneItem.link|escape}{else}{$clsClassTable->getLink($pvalTable)}{/if}"
								maxlength="255" />
						</div>
					</div>
					{* live an mo ta + noi dung nhung van giu trong DOM de submit du key *}
					<div class="form-group d-none">
						<label class="col-form-label">{$core->get_Lang('Description')}</label>
						<textarea class="form-control" cols="255" rows="4"
							name="iso-intro">{if $pvalTable gt '0'}{$oneItem.intro|escape}{/if}</textarea>
					</div>
					<div class="form-group d-none">
						<label class="col-form-label">{$core->get_Lang('Content')}</label>
						<textarea class="form-control isoTextArea" id="{$clsISO->getUniqid()}"
							name="iso-content">{if $pvalTable gt '0'}{$oneItem.content|escape}{/if}</textarea>
					</div>
				</div>
			</section>
			{* ---------- 2. Thong so & tong quan ---------- *}
			<section class="pe-group" id="pe-specs" data-label="Thông số và tổng quan" data-keyword="dien tich gia quy mo mat do xay dung so toa can ho phap ly ban giao tong von thong so tong quan">
				<header class="pe-group__head">
					<span class="pe-group__icon"><i class="bx bx-ruler"></i></span>
					<div class="pe-group__meta">
						<h2 class="pe-group__title">Thông số &amp; tổng quan</h2>
						<p class="pe-group__desc">Các thông số hiển thị ở trang chi tiết: diện tích, mức giá, quy mô, pháp lý...</p>
					</div>
				</header>
				<div class="pe-group__body">
					<div class="pe-grid pe-grid--3">
						{foreach name=i from=$list_meta_fields key=field item=_oF}
							<div class="pe-field">
								<label class="pe-field__label">{$_oF.title}</label>
								<input type="text" placeholder="{$_oF.placeholder}"
									name="more_info_field[{$field}]"
									class="form-control{if $field eq 'dg_price'} price-In numberonly{/if}"
									value="{if !empty($more_information.$field)}{$more_information.$field|escape}{/if}" />
							</div>
						{/foreach}
					</div>
					<div class="pe-divider">
						<span>Thông số tổng quan (tự đặt tên)</span>
						<button type="button" class="btn btn-default btn-sm" onClick="add_property(this, event)"
							_openFrom="_project" project_id="{$pvalTable}" _holderG="_attrs">+ Thêm dòng</button>
					</div>
					<table width="100%" class="table table-vertical mb-0 table-stripped pe-table">
						<thead>
							<tr>
								<th width="5%"></th>
								<th width="35%">Tên thuộc tính</th>
								<th width="55%">Giá trị</th>
								<th width="5%"></th>
							</tr>
						</thead>
						<tbody class="tbody_attrs connectedSortable ui-sortable">
							{if !empty($more_information.attrs)}
								{foreach from = $more_information.attrs name=i key= uid item = _Item}
									<tr id="{$uid}" class="tr_attrs">
										<td class="text-center">
											<div class="mySortableHandler">{$core->makeIcon('arrows')}</div>
										</td>
										<td class="text-left">
											<input class="form-control title_field_{$uid}"
												name="attrs[{$uid}][title]" placeholder="Nhập tiêu đề"
												value="{$_Item.title|escape}" type="text" />
										</td>
										<td class="text-left">
											<input class="form-control content_field_{$uid}"
												name="attrs[{$uid}][content]" placeholder="Nhập giá trị"
												value="{$_Item.content|escape}" type="text" />
										</td>
										<td class="text-center">
											<a title="Xóa" href="javascript:void(0);" class="btn btn-default"
												uid="{$uid}"
												onClick="delete_property(this, event)">{$core->makeIcon('trash')}</a>
										</td>
									</tr>
								{/foreach}
							{else}
								{assign var = uid value = $clsISO->getUniqid()}
								<tr id="{$uid}" class="tr_attrs">
									<td class="text-center">
										<div class="mySortableHandler">{$core->makeIcon('arrows')}</div>
									</td>
									<td class="text-left">
										<input class="form-control title_field_{$uid}"
											name="attrs[{$uid}][title]" placeholder="Nhập tiêu đề"
											type="text" />
									</td>
									<td class="text-left">
										<input class="form-control content_field_{$uid}"
											name="attrs[{$uid}][content]" placeholder="Nhập giá trị"
											type="text" />
									</td>
									<td class="text-center">
										<a title="Xóa" href="javascript:void(0);" class="btn btn-default"
											uid="{$uid}"
											onClick="delete_property(this, event)">{$core->makeIcon('trash')}</a>
									</td>
								</tr>
							{/if}
						</tbody>
					</table>
				</div>
			</section>
			{* ---------- 3. Hinh anh & mau sac ---------- *}
			<section class="pe-group" id="pe-media" data-label="Hình ảnh và màu sắc" data-keyword="anh dai dien logo mat bang tong the mau nen mau chu">
				<header class="pe-group__head">
					<span class="pe-group__icon"><i class="bx bx-image"></i></span>
					<div class="pe-group__meta">
						<h2 class="pe-group__title">Hình ảnh &amp; màu sắc</h2>
						<p class="pe-group__desc">Ảnh đại diện, logo, mặt bằng tổng thể và màu nhận diện của dự án. Bấm vào ảnh để thay đổi.</p>
					</div>
				</header>
				<div class="pe-group__body">
					<div class="pe-media">
						<div class="pe-media__item">
							<div class="pe-media__head">
								<span class="pe-field__label">{$core->get_Lang('Image')}</span>
								<button type="button" class="btn btn-default btn-xs ajOpenDialog"
									isoman_for_id="image" isoman_val="{$oneItem.image|escape}"
									isoman_name="image">{$core->get_Lang('Change')}</button>
							</div>
							<div class="pe-media__preview ajOpenDialog" title="Bấm để thay đổi ảnh"
								isoman_for_id="image" isoman_val="{$oneItem.image|escape}" isoman_name="image">
								<input type="hidden" id="isoman_hidden_image" name="isoman_url_image" value="{$oneItem.image|escape}" />
								<img id="isoman_show_image" src="{$oneItem.image|escape}" alt="" />
								<span class="pe-media__overlay"><i class="bx bx-camera"></i></span>
							</div>
						</div>
						<div class="pe-media__item">
							<div class="pe-media__head">
								<span class="pe-field__label">{$core->get_Lang('Logo')}</span>
								<button type="button" class="btn btn-default btn-xs ajOpenDialog"
									isoman_for_id="logo" isoman_val="{$more_information.logo|escape}"
									isoman_name="logo">{$core->get_Lang('Change')}</button>
							</div>
							<div class="pe-media__preview ajOpenDialog" title="Bấm để thay đổi ảnh"
								isoman_for_id="logo" isoman_val="{$more_information.logo|escape}" isoman_name="logo">
								<input type="hidden" id="isoman_hidden_logo" name="isoman_url_logo" value="{$more_information.logo|escape}" />
								<img id="isoman_show_logo" src="{$more_information.logo|escape}" alt="" />
								<span class="pe-media__overlay"><i class="bx bx-camera"></i></span>
							</div>
						</div>
						{* live an banner nhung giu input de submit du key *}
						<div class="pe-media__item d-none">
							<div class="pe-media__head">
								<span class="pe-field__label">{$core->get_Lang('Banner')}</span>
								<button type="button" class="btn btn-default btn-xs ajOpenDialog"
									isoman_for_id="banner" isoman_val="{$more_information.banner|escape}"
									isoman_name="banner">{$core->get_Lang('Change')}</button>
							</div>
							<div class="pe-media__preview ajOpenDialog" title="Bấm để thay đổi ảnh"
								isoman_for_id="banner" isoman_val="{$more_information.banner|escape}" isoman_name="banner">
								<input type="hidden" id="isoman_hidden_banner" name="isoman_url_banner" value="{$more_information.banner|escape}" />
								<img id="isoman_show_banner" src="{$more_information.banner|escape}" alt="" />
								<span class="pe-media__overlay"><i class="bx bx-camera"></i></span>
							</div>
						</div>
						<div class="pe-media__item">
							<div class="pe-media__head">
								<span class="pe-field__label">Mặt bằng tổng thể</span>
								<button type="button" class="btn btn-default btn-xs ajOpenDialog"
									isoman_for_id="layout" isoman_val="{$more_information.layout|escape}"
									isoman_name="layout">{$core->get_Lang('Change')}</button>
							</div>
							<div class="pe-media__preview ajOpenDialog" title="Bấm để thay đổi ảnh"
								isoman_for_id="layout" isoman_val="{$more_information.layout|escape}" isoman_name="layout">
								<input type="hidden" id="isoman_hidden_layout" name="isoman_url_layout" value="{$more_information.layout|escape}" />
								<img id="isoman_show_layout" src="{$more_information.layout|escape}" alt="" />
								<span class="pe-media__overlay"><i class="bx bx-camera"></i></span>
							</div>
						</div>
					</div>
					<div class="pe-divider"><span>Màu nhận diện</span></div>
					<div class="pe-grid pe-grid--2">
						<div class="pe-field">
							<label class="pe-field__label">Màu nền</label>
							<input type="color" name="bgcolor" value="{$more_information.bgcolor}" class="form-control pe-color" />
						</div>
						<div class="pe-field">
							<label class="pe-field__label">Màu chữ</label>
							<input type="color" name="textcolor" value="{$more_information.textcolor}" class="form-control pe-color" />
						</div>
					</div>
				</div>
			</section>
			{* ---------- 4. Bang hang & quan tri ---------- *}
			<section class="pe-group" id="pe-sheets" data-label="Bảng hàng và quản trị" data-keyword="bang hang spreadsheet google sheet cao tang thap tang cho thue admin">
				<header class="pe-group__head">
					<span class="pe-group__icon"><i class="bx bx-spreadsheet"></i></span>
					<div class="pe-group__meta">
						<h2 class="pe-group__title">Bảng hàng &amp; quản trị</h2>
						<p class="pe-group__desc">Google Spreadsheet bảng hàng và nhân sự phụ trách theo từng loại hình.</p>
					</div>
				</header>
				<div class="pe-group__body">
					<div class="pe-sheets">
						<div class="pe-sheets__head">
							<span>Loại hình</span>
							<span>Google Spreadsheet ID</span>
							<span>Nhân sự phụ trách</span>
						</div>
						{foreach from=$list_groups key= _oG item = _oI}
							{assign var = _oField value = $_oI.field}
							<div class="pe-sheets__row">
								<span class="pe-sheets__name">{$_oI.title}</span>
								<input type="text" name="{$_oI.field}" value="{$more_information.$_oField|escape}"
									class="form-control" placeholder="Spreadsheet ID" />
								<select class="form-control iso-select2" name="list_admin_id[{$_oG}][]" multiple="true">
									{if !empty($list_admins)}
										{foreach from=$list_admins item = _admin}
											<option{if $clsISO->checkInArray($_oI.list_admins, $_admin.profile_id)}
												selected{/if} value="{$_admin.profile_id}">{$_admin.full_name|escape}</option>
										{/foreach}
									{/if}
								</select>
							</div>
						{/foreach}
					</div>
					{* live an cau hinh cot bang hang *}
					<div class="d-none">
						<div class="pe-divider"><span>Cột hiển thị bảng hàng tìm kiếm</span></div>
						<div class="pe-grid pe-grid--2">
							<button class="btn btn-default" onClick="$Core.project.open_config_column(this,event)" type="button"
								block_type="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}"
								project_id="{$pvalTable}">{$core->makeIcon('cog mr-3')}Cấu hình cột — Cao tầng</button>
							<button class="btn btn-default" onClick="$Core.project.open_config_column(this,event)" type="button"
								block_type="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}"
								project_id="{$pvalTable}">{$core->makeIcon('cog mr-3')}Cấu hình cột — Thấp tầng</button>
						</div>
					</div>
				</div>
			</section>
			{* ---------- 5. Booking & admin du an ---------- *}
			<section class="pe-group" id="pe-booking" data-label="Booking và admin dự án" data-keyword="booking nhan booking admin du an phu trach">
				<header class="pe-group__head">
					<span class="pe-group__icon"><i class="bx bx-user-check"></i></span>
					<div class="pe-group__meta">
						<h2 class="pe-group__title">Booking &amp; admin dự án</h2>
						<p class="pe-group__desc">Dự án có nhận booking hay không và ai là admin phụ trách.</p>
					</div>
				</header>
				<div class="pe-group__body">
					<div class="pe-grid pe-grid--2">
						<div class="pe-field">
							<label class="pe-field__label">Nhận booking</label>
							<div class="pe-pills">
								<label class="pe-pill">
									<input{if isset($more_information.is_booking) && $more_information.is_booking eq '1'}
										checked="checked" {/if} name="is_booking" value="1" type="radio" />
									<span>Có</span>
								</label>
								<label class="pe-pill">
									<input{if isset($more_information.is_booking) && $more_information.is_booking eq '0'}
										checked="checked" {/if} name="is_booking" value="0" type="radio" />
									<span>Không</span>
								</label>
							</div>
						</div>
						<div class="pe-field">
							<label class="pe-field__label">Admin dự án</label>
							<select data-placeholder="Admin dự án" multiple="true" name="project_admins[]"
								class="form-control iso-select2">
								<option value="0">--Chọn--</option>
								{foreach from=$list_profile item=_oProfile key=key name=i}
								<option{if $clsISO->checkInArray($arr_project_admins, $_oProfile.profile_id)}
									selected{/if} value="{$_oProfile.profile_id}">{$_oProfile.full_name|escape}</option>
									{/foreach}
							</select>
						</div>
					</div>
					{* live an cac cau hinh nay nhung giu trong DOM de gia tri khong bi mat khi luu *}
					<div class="d-none">
						<label>
							<input
								{if $more_information.project_has_block eq '1' || !isset($more_information.project_has_block) || $pvalTable eq '0'}
								checked="checked" {/if} name="project_has_block" value="1" type="radio" />
							Có phân khu
						</label>
						<label>
							<input{if $more_information.project_has_block eq '0' and $pvalTable gt '0'}
								checked="checked" {/if} name="project_has_block" value="0" type="radio" />
							Không có phân khu
						</label>
						<label>
							<input
								{if $more_information.has_block eq '1' || !isset($more_information.has_block) || $pvalTable eq '0'}
								checked="checked" {/if} name="has_block" value="1" type="radio" />
							Phân khu map: Có
						</label>
						<label>
							<input{if $more_information.has_block eq '0' and $pvalTable gt '0'}
								checked="checked" {/if} name="has_block" value="0" type="radio" />
							Phân khu map: Không
						</label>
						{foreach from=$list_domains key=key item=domain}
							<label>
								<input name="site_manager_ids[]" value="{$key}" type="checkbox"
									{if $clsISO->checkItemInArray($key,$more_information.site_manager_ids)}
									checked="checked" {/if} />
								{$domain.title|escape}
							</label>
						{/foreach}
					</div>
				</div>
			</section>
			{* ---------- 6. Tien ich du an ---------- *}
			<section class="pe-group" id="pe-utilities" data-label="Tiện ích dự án" data-keyword="tien ich noi khu ngoai khu utilities">
				<header class="pe-group__head">
					<span class="pe-group__icon"><i class="bx bx-map-pin"></i></span>
					<div class="pe-group__meta">
						<h2 class="pe-group__title">Tiện ích dự án</h2>
						<p class="pe-group__desc">Tiện ích hiển thị trên trang giới thiệu dự án, lọc được theo phân khu.</p>
					</div>
					<div class="pe-group__actions">
						<select class="form-control input-sm filterUtilitiesBlock" project_id="{$pvalTable}"
							onChange="$Core.utilities.filter(this,event)">
							<option value="0">Tất cả phân khu</option>
							{if !empty($list_blocks)}
								{foreach from=$list_blocks item=_oBlockFilter}
									<option value="{$_oBlockFilter.property_id}">{$_oBlockFilter.title|escape}</option>
								{/foreach}
							{/if}
						</select>
						<button type="button" class="btn btn-default btn-sm" project_id="{$pvalTable}"
							utilities_id="" onClick="$Core.utilities.open(this,event)">+ Thêm tiện ích</button>
					</div>
				</header>
				<div class="pe-group__body">
					<div class="holderUtilities">
						Loading...
					</div>
				</div>
			</section>
			{* ---------- 7. Noi dung trang du an (SOP) ---------- *}
			<section class="pe-group pe-group--sop" id="pe-sop" data-label="Nội dung trang dự án" data-keyword="sop noi dung trang gioi thieu section khoi">
				<header class="pe-group__head">
					<span class="pe-group__icon"><i class="bx bx-book-content"></i></span>
					<div class="pe-group__meta">
						<h2 class="pe-group__title">Nội dung trang dự án</h2>
						<p class="pe-group__desc">Các khối nội dung (section) của trang giới thiệu dự án — đổi thứ tự bằng nút mũi tên.</p>
					</div>
					<div class="pe-group__actions">
						<button type="button" class="btn btn-default btn-sm" onClick="$Core.project.open_sop(this, event)"
							sop_id="0" project_id="{$pvalTable}">+ Thêm khối nội dung</button>
					</div>
				</header>
				<div class="pe-group__body">
					<div class="box_list_sop">
						{if !empty($list_sops)}
							{section name=i loop=$list_sops}
								{assign var = _sop_Id value = $list_sops[i].id}
								{assign var = _more_information value = $list_sops[i].more_information}
								<div class="pe-sop item_sop {$_sop_Id} {if $smarty.section.i.first}item_first{/if} {if $smarty.section.i.last}item_last{/if}">
									<header class="pe-sop__head">
										<span class="pe-sop__index">{$smarty.section.i.iteration}</span>
										<h3 class="pe-sop__title">{$_more_information.title|escape}</h3>
										<div class="pe-sop__tools">
											<a href="javascript:void(0);" class="btn btn-icon btn-xs btn-default"
												onclick="$Core.project.open_sop(this, event)" sop_id="{$_sop_Id}"
												project_id="{$pvalTable}" data-toggle="tooltip"
												title="Nhấn lưu trước khi thay đổi">{$core->makeIcon('cog')}</a>
											<a href="javascript:void(0);" class="btn btn-icon btn-xs btn-default"
												onclick="$Core.project.delete_sop(this)" sop_id="{$_sop_Id}"
												project_id="{$pvalTable}" data-toggle="tooltip"
												title="Nhấn lưu trước khi thay đổi">{$core->makeIcon('trash')}</a>
											<a href="javascript:void(0);" onclick="$Core.project.move_sop(this, 'up')"
												class="btn btn-icon btn-xs btn-default btn_moveup {if $smarty.section.i.first}d-none{/if}"
												sop_id="{$_sop_Id}" project_id="{$pvalTable}" data-toggle="tooltip"
												title="Nhấn lưu trước khi thay đổi vị trí">{$core->makeIcon('arrow-up')}</a>
											<a href="javascript:void(0);" onclick="$Core.project.move_sop(this, 'down')"
												class="btn btn-icon btn-xs btn-default btn_movedown {if $smarty.section.i.last}d-none{/if}"
												sop_id="{$_sop_Id}" project_id="{$pvalTable}" data-toggle="tooltip"
												title="Nhấn lưu trước khi thay đổi vị trí">{$core->makeIcon('arrow-down')}</a>
											{if $_more_information.field_type eq '_list' || $_more_information.field_type eq '_utilities'}
												<button type="button" onClick="{if $_more_information.field_type eq '_utilities'}$Core.project.open_data_picker(this){else}$Core.project.open_sop_item(this){/if}"
													sop_item_id="0" sop_id="{$_sop_Id}" project_id="{$pvalTable}"
													class="btn btn-default btn-xs">{$core->get_Lang('Addnew')}</button>
											{/if}
										</div>
									</header>
									{* live an toan bo tuy chon anh/mau cua SOP - giu trong DOM de submit du key *}
									<div class="pe-sop__options d-none">
										<label class="pe-check">
											<input type="checkbox" name="sop[{$_sop_Id}][is_image]" value="1"
												{if $_more_information.is_image eq '1'}checked{/if} />
											<span>Hiển thị hình ảnh</span>
										</label>
										<label class="pe-check">
											<input type="checkbox" name="sop[{$_sop_Id}][is_background]" value="1"
												{if $_more_information.is_background eq '1'}checked{/if} />
											<span>Dùng ảnh làm nền</span>
										</label>
										<label class="pe-sop__opt">
											<span class="pe-field__label">Màu nền</span>
											<input type="color" name="sop[{$_sop_Id}][background_color]"
												value="{$_more_information.background_color}" />
										</label>
										<label class="pe-sop__opt">
											<span class="pe-field__label">Vị trí ảnh</span>
											<select class="form-control input-sm" name="sop[{$_sop_Id}][position]">
												<option {if $_more_information.position eq 'left'}selected{/if} value="left">Trái</option>
												<option {if $_more_information.position eq 'right'}selected{/if} value="right">Phải</option>
												<option {if $_more_information.position eq 'center'}selected{/if} value="center">Giữa</option>
											</select>
										</label>
										<div class="pe-sop__opt pe-sop__opt--grow">
											<span class="pe-field__label">{$core->get_Lang('Image')}</span>
											<div class="input-group">
												<input type="text" class="form-control input-sm" name="sop[{$_sop_Id}][image]"
													placeholder="Chọn hình ảnh làm icon"
													id="isoman_url_sop_image_{$_sop_Id}"
													value="{$_more_information.image|escape}">
												<div class="input-group-btn">
													<button type="button" class="btn btn-default btn-sm ajOpenDialog"
														isoman_for_id="sop_image_{$_sop_Id}"
														isoman_val="{$_more_information.image|escape}"
														isoman_name="sop_image_{$_sop_Id}">{$core->makeIcon('image')}</button>
												</div>
											</div>
										</div>
									</div>
									<div class="pe-sop__content">
										<textarea class="isoTextArea form-control"
											name="sop[{$_sop_Id}][content]" id="mce_sop_{$_sop_Id}" cols="255"
											rows="10">{$_more_information.content|escape}</textarea>
									</div>
									{if $_more_information.field_type ne '_textarea'}
										<div class="pe-sop__note">Mỗi một block gồm một danh sách các item — bấm <strong>Thêm mới</strong> ở góc phải để thêm.</div>
										<div class="holder_list_sop holder_list_sop_{$_sop_Id}"
											project_id="{$pvalTable}" sop_id="{$_sop_Id}">
											Loading...
										</div>
									{/if}
								</div>
							{/section}
						{/if}
					</div>
				</div>
			</section>
			{* ---------- 8. VR360 & Tiles ---------- *}
			<section class="pe-group" id="pe-vr" data-label="VR360 và Tiles" data-keyword="vr360 vr tiles zoom bound tms map tiles trung tam">
				<header class="pe-group__head">
					<span class="pe-group__icon"><i class="bx bx-street-view"></i></span>
					<div class="pe-group__meta">
						<h2 class="pe-group__title">VR360 &amp; Tiles</h2>
						<p class="pe-group__desc">Link tham quan VR360 và cấu hình bản đồ tiles riêng của dự án.</p>
					</div>
				</header>
				<div class="pe-group__body">
					<div class="pe-grid pe-grid--2">
						<div class="pe-field">
							<label class="pe-field__label">VR360</label>
							<input class="form-control" name="vr_link" value="{$more_information.vr_link|escape}" maxlength="255" placeholder="Link VR360" />
						</div>
						<div class="pe-field">
							<label class="pe-field__label">Nguồn VR360</label>
							<input class="form-control" name="vr_source" value="{$more_information.vr_source|escape}" maxlength="255" />
						</div>
					</div>
					<div class="pe-advanced">
						<a class="pe-advanced__toggle" data-toggle="collapse" href="#peTiles" role="button">
							<i class="bx bx-chevron-right"></i>
							Cấu hình tiles nâng cao
							<span class="pe-advanced__hint">— chỉ dùng cho dự án có bản đồ tiles riêng</span>
						</a>
						<div class="collapse{if $more_information.is_tiles eq '1' or $more_information.tiles_link} in{/if}" id="peTiles">
							<div class="pe-advanced__body">
								<div class="pe-switches">
									<label class="pe-switch">
										<input type="hidden" name="is_tiles" value="0" />
										<span class="switch small">
											<input type="checkbox" name="is_tiles"
												{if $more_information.is_tiles eq '1'} checked{/if} value="1">
											<span class="slider round"></span>
										</span>
										<span>Sử dụng tiles</span>
									</label>
									<label class="pe-switch">
										<input type="hidden" name="is_map_tiles" value="0" />
										<span class="switch small">
											<input type="checkbox" name="is_map_tiles"
												{if $more_information.is_map_tiles eq '1'} checked{/if} value="1">
											<span class="slider round"></span>
										</span>
										<span>Map tiles</span>
									</label>
								</div>
								<div class="pe-field">
									<label class="pe-field__label">Link tiles</label>
									<input name="tiles_link" class="form-control" value="{$more_information.tiles_link|escape}" maxlength="255" />
								</div>
								<div class="pe-grid pe-grid--4">
									<div class="pe-field">
										<label class="pe-field__label">Max zoom</label>
										<input type="number" class="form-control" name="max_zoom" value="{$more_information.max_zoom|escape}" maxlength="255" />
									</div>
									<div class="pe-field">
										<label class="pe-field__label">Vị trí trung tâm</label>
										<input class="form-control" name="center_point" value="{$more_information.center_point|escape}" maxlength="255" />
									</div>
									<div class="pe-field">
										<label class="pe-field__label">Vùng bound</label>
										<input class="form-control" name="max_bound" value="{$more_information.max_bound|escape}" maxlength="255" />
									</div>
									<div class="pe-field">
										<label class="pe-field__label">TMS</label>
										<select class="form-control" name="tms_enable">
											<option{if $more_information.tms_enable eq '0'} selected{/if} value="0">NO</option>
											<option{if $more_information.tms_enable eq '1'} selected{/if} value="1">YES</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<p class="pe-empty" id="pe-search-empty">Không có nhóm cấu hình nào khớp từ khóa đang tìm.</p>
			{* ---------- Cac khoi an (giu nguyen tu ban cu de tuong thich JS/submit) ---------- *}
			<div class="ui-card d-none">
				<div class="ui-card__section">
					<header class="ui-stack ui-stack--wrap mb-3">
						<h2 class="ui-stack-item ui-stack-item--fill ui-heading">1. Thuộc tính</h2>
						<div class="ui-stack-item">
							<button type="button" class="btn btn-default add_property"
								onClick="add_property(this, event)" _openFrom="_project"
								project_id="{$pvalTable}">+ Thêm</button>
						</div>
					</header>
					<div class="ui-type-container">
						<table width="100%" class="table table-vertical mb-0 table-stripped">
							<thead>
								<tr>
									<th width="5%">No.</th>
									<th width="35%">Tên thuộc tính</th>
									<th width="55%">Giá trị</th>
									<th>HOT</th>
									<th width="5%"></th>
								</tr>
							</thead>
							<tbody class="tbody_property no_group connectedSortable ui-sortable">
								{if !empty($list_props)}
									{foreach from = $list_props name=i item = _Item}
										{assign var = uid value = $clsISO->getUniqid()}
										<tr id="{$uid}" class="tr_property no_group">
											<td class="text-center">
												<div class="mySortableHandler">{$core->makeIcon('arrows')}</div>
											</td>
											<td class="text-left">
												<input type="hidden" name="properties[{$uid}][id]" value="{$_Item.id}" />
												<input type="hidden" name="properties[{$uid}][lock]" value="{$_Item.lock}" />
												<input class="form-control title_field_{$uid}"
													name="properties[{$uid}][title]" placeholder="Nhập tiêu đề"
													value="{$_Item.title|escape}" type="text" />
											</td>
											<td class="text-left">
												<input class="form-control content_field_{$uid}"
													name="properties[{$uid}][content]" placeholder="Nhập giá trị"
													value="{$_Item.content|escape}" type="text" />
											</td>
											<td class="text-center">
												<label class="switch">
													<input type="checkbox" name="properties[{$uid}][is_hot]" value="1"
														{if $_Item.is_hot eq '1'} checked{/if}>
													<span class="slider round"></span>
												</label>
											</td>
											<td class="text-center">
												<div class="btn-group">
													<button type="button" class="btn btn-xs btn-default dropdown-toggle"
														data-toggle="dropdown">
														<i class="icon-cog"></i>
														<span class="caret"></span>
													</button>
													<ul class="dropdown-menu"
														style="right:0px !important;left:auto; min-width:130px">
														<li><a title="Tải File" href="javascript:void(0);" uid="{$uid}"
																onClick="select_file(this, event)">{$core->makeIcon('upload', 'Tải File')}</a>
														</li>
														<li><a title="Xóa" href="javascript:void(0);" uid="{$uid}"
																onClick="delete_property(this, event)">{$core->makeIcon('trash', 'Xóa')}</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
									{/foreach}
								{else}
									{assign var = uid value = $clsISO->getUniqid()}
									<tr id="{$uid}" class="tr_property no_group">
										<td class="text-center">
											<div class="mySortableHandler">{$core->makeIcon('arrows')}</div>
										</td>
										<td class="text-left">
											<input type="hidden" name="properties[{$uid}][id]" value="0" />
											<input type="hidden" name="properties[{$uid}][lock]" value="0" />
											<input class="form-control title_field_{$uid}"
												name="properties[{$uid}][title]" placeholder="Nhập tiêu đề"
												type="text" />
										</td>
										<td class="text-left">
											<input class="form-control content_field_{$uid}"
												name="properties[{$uid}][content]" placeholder="Nhập giá trị"
												type="text" />
										</td>
										<td class="text-center">
											<label class="switch">
												<input type="checkbox" name="properties[{$uid}][is_hot]" value="1">
												<span class="slider round"></span>
											</label>
										</td>
										<td class="text-center">
											<div class="btn-group">
												<button class="btn btn-xs btn-default dropdown-toggle" type="button"
													data-toggle="dropdown">
													<i class="icon-cog"></i>
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu"
													style="right:0px !important;left:auto; min-width:130px">
													<li><a title="Tải File" href="javascript:void(0);" uid="{$uid}"
															onClick="select_file(this, event)">{$core->makeIcon('upload', 'Tải File')}</a>
													</li>
													<li><a title="Xóa" href="javascript:void(0);" uid="{$uid}"
															onClick="delete_property(this, event)">{$core->makeIcon('trash', 'Xóa')}</a>
													</li>
												</ul>
											</div>
										</td>
									</tr>
								{/if}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="ui-card d-none">
				<div class="ui-card__section">
					<header class="ui-stack ui-stack--wrap mb-3">
						<h2 class="ui-stack-item ui-stack-item--fill ui-heading">Banner căn hộ độc quyền</h2>
						<div class="ui-stack-item">
							<button type="button" class="ui-button ui-button--link" project_id="{$pvalTable}"
								banner_stock_id=""
								onClick="$Core.global.project.open_banner(this,event)">Thêm</button>
						</div>
					</header>
					<div class="ui-type-container">
						<div class="holderBannerStock">
							Loading...
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="pe-savebar">
		<div class="pe-savebar__left">
			{if $pvalTable gt '0'}<a class="btn btn-warning" onClick="delete_globe(this)" clsTable="Slide"
				pval_id="{$pvalTable}" pkey="{$pkeyTable}"
				return_url="{$PCMS_URL}/index.php?mod={$mod}&message=DeletedSuccess">{$core->get_Lang('Delete')}</a>{/if}
		</div>
		<div class="pe-savebar__right">
			{if $pvalTable eq '0'}
				<a class="btn btn-default" href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Calcel')}</a>
			{/if}
			<input value="Update" name="submit" type="hidden">
			{$saveBtn} {$saveList}
			{if !$saveBtn}
				<button type="submit" name="button" value="_EDIT" class="btn btn-primary">{$core->get_Lang('Save')}</button>
			{/if}
		</div>
	</div>
</form>
</div>
<script>
	var html_select_field_highfloor = `{$html_select_field_highfloor}`;
	var html_select_field_lowfloor = `{$html_select_field_lowfloor}`;
	const modal_icon = `{$modal_icon}`
</script>
{literal}
<script type="text/javascript">
	$(function () {
		$Core.global.project.loadListBannerStock(project_id, {});
		if ($('.ui-sortable').length) {
			$('.ui-sortable').sortable({
				connectWith: ".connectedSortable",
				handle: ".mySortableHandler",
				beforeStop: function (event, ui) {
					var a = ui.item.attr('id'),
							b = $(ui.placeholder).parent('tbody');
					if (b.hasClass('is_group')) {
						var _group_id = b.attr('id');
						ui.item.addClass(_group_id).addClass('is_group').removeClass('no_group').attr('group_id', _group_id);
						$('.title_field_' + a).attr('name', 'groups[' + _group_id + '][' + a +'][title]');
						$('.link_field_' + a).attr('name', 'groups[' + _group_id + '][' + a +'][link]');
					} else {
						var _group_id = ui.item.attr('group_id');
						ui.item.removeClass(_group_id).removeClass('is_group').addClass('no_group')
								.removeAttr('group_id');
						$('.title_field_' + a).attr('name', 'properties[' + a + '][title]');
						$('.link_field_' + a).attr('name', 'properties[' + a + '][link]');
					}
				}
			}).disableSelection();
		}

		/* ---- Rail dieu huong: scrollspy + nhay nhanh + loc nhom ---- */
		var $railItems = $('.pe-rail__item');
		var $groups = $('.pe-group');
		var $search = $('#pe-search');

		if (window.IntersectionObserver) {
			var _spy = new IntersectionObserver(function (entries) {
				$.each(entries, function (_index, _entry) {
					if (!_entry.isIntersecting) {
						return;
					}
					$railItems.removeClass('is-active');
					$('.pe-rail__item[data-slug="' + _entry.target.id + '"]').addClass('is-active');
				});
			}, { rootMargin: '-100px 0px -60% 0px' });
			$groups.each(function () {
				_spy.observe(this);
			});
		}

		$railItems.on('click', function (e) {
			var _top = $('#' + $(this).attr('data-slug')).offset().top - 100;
			e.preventDefault();
			$railItems.removeClass('is-active');
			$(this).addClass('is-active');
			$('html, body').animate({ scrollTop: _top }, 220);
		});

		/* Bo dau tieng Viet de go "hinh anh" van tim ra "Hình ảnh". */
		function plainText(_text) {
			var _value = (_text === undefined || _text === null) ? '' : String(_text);
			_value = _value.toLowerCase();
			if (_value.normalize) {
				_value = _value.normalize('NFD').replace(/[̀-ͯ]/g, '');
			}
			return _value.replace(/đ/g, 'd');
		}

		/* Loc theo nhom: nhom khong khop chi bi an bang CSS, van nam trong form
		   nen gia tri khong bao gio bi mat khi submit. */
		function runSearch() {
			var _term = plainText($search.val()).trim();
			var _visible = 0;
			$groups.each(function () {
				var $group = $(this);
				var _haystack = plainText($group.attr('data-label') + ' ' + $group.attr('data-keyword'));
				var _match = _term === '' || _haystack.indexOf(_term) !== -1;
				$group.toggleClass('is-hidden', !_match);
				$('.pe-rail__item[data-slug="' + $group.attr('id') + '"]').toggleClass('is-empty', !_match);
				if (_match) {
					_visible++;
				}
			});
			$('#pe-search-empty').toggleClass('is-open', _visible === 0);
		}

		$search.on('input', runSearch).on('keydown', function (e) {
			/* Enter trong o tim kiem khong duoc submit ca form. */
			if (e.which === 13) {
				e.preventDefault();
			}
		});

		/* Ctrl+S luu nhu moi man soan thao khac. */
		$(document).on('keydown', function (e) {
			if (!(e.ctrlKey || e.metaKey) || e.which !== 83) {
				return;
			}
			e.preventDefault();
			$('.pe-savebar__right').find('button[type="submit"], input[type="submit"], button[name="button"]').first().click();
		});
	});
</script>
{/literal}
