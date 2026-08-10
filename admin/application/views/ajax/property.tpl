{if $action eq '_form'}
<div class="modal-dialog modal-standard">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Code')}</label>
					<div class="col-md-4">
						<input type="text" class="form-control required" placeholder="Mã" name="property_code" value="{if $property_id gt '0'}{$oneProperty.property_code}{/if}">
					</div>
					{if $property_type eq '_GROUPSIZE'}
					<label class="col-md-2 text-right col-form-label">Tiêu đề + số thành viên</label>
					<div class="col-md-4">
						<div Class="input-group">
							<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title" value="{if $property_id gt '0'}{$oneProperty.title}{/if}">
							<input type="number" class="form-control required" placeholder="0" name="size_group" value="{if $property_id gt '0'}{$more_information.size_group}{/if}">
						</div>
					</div>
					{else}
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Name')}</label>
					<div class="col-md-4">
						<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title" value="{if $property_id gt '0'}{$oneProperty.title}{/if}">
					</div>
					{/if}
				</div>
				{if $property_type eq '_ULTILITIES'}
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Link</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Đường dẫn" name="link" value="{if $property_id gt '0'}{$more_information.link}{/if}">
						</div>
						<label class="col-md-2 text-right col-form-label">Thuộc tính</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Thuộc tính" name="attr" value="{if $property_id gt '0'}{$more_information.attr}{/if}">
						</div>
					</div>
					<div class="form-group form-row">
						<label for="" class="col-md-2 text-right col-form-label">Nhóm</label>
						<div class="col-md-4">
							<select class="form-control" name="group" value="{if $property_id gt '0'}{$more_information.group}{/if}">
								{$clsProperty->getSelectSingleProperty('_GROUP_ULTILITIES', 0, $more_information.group)}
							</select>
						</div>
						<label for="" class="col-md-2 text-right col-form-label">Class Icon</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Class Icon" name="icon" value="{if $property_id gt '0'}{$more_information.icon}{/if}">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Permiss</label>
						<div class="col-md-10">
							<div class="form-row">
								{foreach from=$list_roles item = _oText key = _oRole}
								<div class="col-md-4 mb-3">
									<div class="checkbox">
										<input type="checkbox" id="{$_oRole}" name="role[]" class="styled" value="{$_oRole}"{if $clsISO->checkItemInArray($_oRole, $more_information.role)}checked{/if}>
										<label for="{$_oRole}">{$_oText}</label>
									</div>
								</div>
								{/foreach}
							</div>
						</div>
					</div>
				{/if}
				{if $property_type eq '_PACKAGE'}
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">1 tháng</label>
						<div class="col-md-3">
							<input type="number" class="form-control required" placeholder="0" name="price_month" value="{if $property_id gt '0'}{$more_information.price_month}{/if}">
						</div>
						<label class="col-md-2 text-right col-form-label">3 tháng</label>
						<div class="col-md-5">
							<input type="number" class="form-control required" placeholder="0" name="price_3month" value="{if $property_id gt '0'}{$more_information.price_3month}{/if}">
						</div>
					</div>
					<div class="form-group form-row">	
						<label class="col-md-2 text-right col-form-label">6 tháng</label>
						<div class="col-md-3">
							<input type="number" class="form-control required" placeholder="0" name="price_6month" value="{if $property_id gt '0'}{$more_information.price_6month}{/if}">
						</div>
						<label class="col-md-2 text-right col-form-label">12 tháng</label>
						<div class="col-md-5">
							<input type="number" class="form-control required" placeholder="0" name="price_year" value="{if $property_id gt '0'}{$more_information.price_year}{/if}">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Mô tả gói</label>
						<div class="col-md-10">
							<textarea name="package_intro" id="" cols="30" rows="2" class="form-control required" placeholder="Nhập mô tả" style="resize: vertical">{if $property_id gt '0'}{$more_information.package_intro|html_entity_decode}{/if}</textarea>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Hiển thị</label>
						<div class="col-md-3">
							<label class="checkbox-inline mt-2">
								<input type="checkbox" name="show_pricing" value="1" {if $more_information.show_pricing eq 1}checked{/if}>
								<span>Cho phép hiển thị</span>
							</label>
						</div>
						<label class="col-md-2 text-right col-form-label">Dùng thử (số ngày)</label>
						<div class="col-md-5">
							<input type="number" class="form-control required" placeholder="0" name="day_trial" value="{if $property_id gt '0'}{$more_information.day_trial}{/if}">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Điều khoản dùng thử</label>
						<div class="col-md-10">
							<textarea class="form-control isoTextArea" id="{$clsISO->getUniqid()}" cols="255" data-name="trial_terms" rows="5">{if $property_id gt '0'}{$more_information.trial_terms}{/if}</textarea>
						</div>
					</div>
				{/if}
				{if $property_type eq '_DIRECTION' 
					|| $property_type eq '_AGENCY' 
					|| $property_type eq '_TYPE_VILLA' 
					|| $property_type eq '_REPORT_TEMPLATE' 
					|| $property_type eq '_BILLING_TYPE'}
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label required">
							{if $property_type eq '_REPORT_TEMPLATE' || $property_type eq '_BILLING_TYPE'}Tiêu đề mobile{else}Query DB{/if}
						</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title_vn" value="{if $property_id gt '0'}{$oneProperty.title_vn}{/if}">
						</div>
						{if $property_type eq '_REPORT_TEMPLATE'}
						<label class="col-md-2 text-right col-form-label required">Đơn vị</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Nhập đơn vị" name="unit_name" value="{if $property_id gt '0'}{$more_information.unit_name}{/if}">
						</div>
						{/if}
						{if $property_type eq '_BILLING_TYPE'}
						<label class="col-md-2 text-right col-form-label required">ID Nhóm zalo</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="Nhập ID nhóm Zalo" name="group_zalo_id" value="{if $property_id gt '0'}{$more_information.group_zalo_id}{/if}">
						</div>
						{/if}
					</div>
				{elseif $property_type eq '_DEPARTMENT'}
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label required">{$core->get_Lang('Role')}</label>
						<div class="col-md-4">
							<select class="form-control required" name="for_id">
								{$clsProperty->getSelectSingleProperty('_ROLE', 0, $for_id)}
							</select>
						</div>
						<input type="hidden" name="is_business_area" value="0" />
						<label class="col-md-2 text-right col-form-label required">Vùng kinh doanh</label>
						<div class="col-md-4">
							<label class="switch">
								<input type="checkbox" name="is_business_area"{if $more_information.is_business_area eq '1'} checked{/if} value="1">
								<span class="slider round"></span>
							</label>
						</div>
					</div>
				{/if}
				{if $property_type ne '_PRICE_RANGE_SOP' 
					&& $property_type ne '_PRICE_RANGE_LEASING' 
					&& $property_type ne '_GROUP_ULTILITIES' 
					&& $property_type ne '_ULTILITIES' 
					&& $property_type ne '_AREA_RANGE' 
					&& $property_type ne '_CATEGORY_DOCS'}
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label">{$core->get_Lang('BgColor')}</label>
					<div class="col-md-4">
						<input type="color" class="form-control required" placeholder="Màu nền" 
						name="bgcolor" value="{if $property_id gt '0'}{$oneProperty.bgcolor}{/if}">
					</div>
					<label for="" class="col-md-2 text-right col-form-label">{$core->get_Lang('TextColor')}</label>
					<div class="col-md-4">
						<input type="color" class="form-control required" placeholder="Màu chữ" 
						name="textcolor" value="{if $property_id gt '0'}{$oneProperty.textcolor}{/if}">
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Icon')}</label>
					<div class="col-xs-12 col-md-10">
						<div class="input-group w-100">
							<input type="text" class="form-control" name="icon" placeholder="Nhập class icon" id="icon" value="{$oneProperty.icon}">
						</div>
					</div>
				</div>
				{if $property_type ne '_ULTILITIES'}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Image')}</label>
					<div class="col-xs-12 col-md-10">
						<div class="input-group">
							<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh làm icon" id="isoman_url_image" value="{$oneProperty.image}">
							<div class="input-group-btn">
								<button class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="{$oneProperty.image}" isoman_name="image" style="padding:9px 10px">{$core->makeIcon('image')}</button>
							</div>	
						</div>
					</div>
				</div>{/if}
				{/if}
				{if $property_type eq '_PRICE_RANGE_SOP' 
					|| $property_type eq '_PRICE_RANGE_LEASING' 
					|| $property_type eq '_AREA_RANGE'}
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label">Giá trị min</label>
					<div class="col-md-4">
						<input type="text" class="form-control required price-In" placeholder="{if $property_type eq '_PRICE_RANGE_SOP' || $property_type eq '_PRICE_RANGE_LEASING'}1.000.000.000đ{else}50m2{/if}" 
						name="min" value="{if $property_id gt '0'}{$more_information.min}{/if}">
					</div>
					<label for="" class="col-md-2 text-right col-form-label">Giá trị max</label>
					<div class="col-md-4">
						<input type="text" class="form-control required price-In" placeholder="{if $property_type eq '_PRICE_RANGE_SOP' || $property_type eq '_PRICE_RANGE_LEASING'}10.000.000.000đ{else}500m2{/if}" 
						name="max" value="{if $property_id gt '0'}{$more_information.max}{/if}">
					</div>
				</div>
				{/if}
				{if $property_type ne '_CATEGORYSERVICES' 
					&& $property_type ne '_FURNITUREUNIT' 
					&& $property_type ne '_CATEGORYSFURNITURE' 
					&& $property_type ne '_GENDER' 
					&& $property_type ne '_FEATURE_MOC' 
					&& $property_type ne '_PRICE_RANGE_SOP' 
					&& $property_type ne '_PRICE_RANGE_LEASING' 
					&& $property_type ne '_AREA_RANGE' 
					&& $property_type ne '_ULTILITIES' 
					&& $property_type ne '_GROUP_ULTILITIES'}
				<div class="form-group form-row">
					{if $property_type eq "_BEDROOM"}
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('ParentCategory')}</label>
					<div class="col-md-4">
						<select class="form-control" name="parent_id" value="{if $property_id gt '0'}{$oneProperty.title}{/if}">
							{$clsISO->getSelectByPropertyTypeTitle($property_type,$oneProperty.parent_id,"Danh mục cha")}
						</select>
					</div>
					<label class="col-md-2 text-right col-form-label">Phòng tắm + ngủ</label>
					<div class="col-md-4">
						<div Class="input-group d-flex align-items-center">
							<input type="text" class="form-control required numberonly" 
								placeholder="Số phòng tắm" name="bathroom" value="{if $property_id gt '0'}{$more_information.bathroom}{/if}">
							<input type="text" class="form-control required numberonly" 
								placeholder="Số phòng ngủ" name="ms_value" value="{if $property_id gt '0'}{$oneProperty.ms_value}{/if}">
						</div>
					</div>
					{else}
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('ParentCategory')}</label>
					<div class="col-md-10">
						<select class="form-control" name="parent_id" value="{if $property_id gt '0'}{$oneProperty.title}{/if}">
							{$clsISO->getSelectByPropertyTypeTitle($property_type,$oneProperty.parent_id,"Danh mục cha")}
						</select>
					</div>
					{/if}
				</div>
				{/if}
				{if $property_type eq '_DEPARTMENT'}
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Văn phòng</label>
						<div class="col-md-10">
							<select class="form-control" name="office_id">
								{$clsISO->getSelectBySettingTypeTitle("_OFFICE",$more_information.office_id,"Văn phòng")}
							</select>
						</div>
					</div>
				{/if}
				{if $property_type eq '_CATEGORY_DOCS'}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Hiển thị</label>
					<div class="col-md-10">
						<select class="form-control" name="view">
							<option value="grid" {if $more_information.view eq 'grid'}selected{/if}>Lưới</option>
							<option value="list" {if $more_information.view eq 'list'}selected{/if}>Danh sách</option>
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Image')}</label>
					<div class="col-xs-12 col-md-10">
						<div class="input-group">
							<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh đại diện" 
							id="isoman_url_image" value="{$oneProperty.image}">
							<div class="input-group-btn">
								<button class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="{$oneProperty.image}" isoman_name="image" style="padding:9px 10px">{$core->makeIcon('image')}</button>
							</div>	
						</div>
					</div>
				</div>
				{/if}
				{if $property_type eq '_DEPARTMENT'}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Trưởng nhóm/GĐ</label>
					<div class="col-md-10">
						<select class="form-control iso-select2" data-width="100%" name="head_of_dep_id">
							<option value="0">Lựa chọn trưởng nhóm/GĐ</option>
							{if !empty($list_staffs)}
							{foreach from=$list_staffs item = _oStaff}
							<option{if $more_information.head_of_dep_id eq $_oStaff.profile_id} selected{/if} value="{$_oStaff.profile_id}">
								{$_oStaff.code} - {$_oStaff.full_name}</option>
							{/foreach}
							{/if}
						</select>
					</div>
				</div>
				{/if}
				{if $property_type eq '_BILLING_TYPE'}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Giám đốc dự án</label>
					<div class="col-md-10">
						<select class="form-control iso-select2" data-width="100%" name="project_director_id">
							<option value="0">Giám đốc dự án</option>
							{if !empty($list_staffs)}
							{foreach from=$list_staffs item = _oStaff}
							<option{if $more_information.project_director_id eq $_oStaff.profile_id} selected{/if} value="{$_oStaff.profile_id}">{$_oStaff.code} - {$_oStaff.full_name}</option>
							{/foreach}
							{/if}
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Số tiền cọc</label>
					<div class="col-md-10">
						<input type="text" class="form-control price-In" name="deposit" value="{$more_information.deposit}">
					</div>
				</div>				
				{if !empty($list_group_zalo)}<hr />
					{foreach name=k from=$list_group_zalo key = _oK item = _oP}
					<div class="form-group form-row group_zalo">
						<label class="col-md-3 text-right col-form-label required">Nhóm zalo chúc mừng
							{if $smarty.foreach.k.last}
							<a href="javascript:void(0);" onClick="$Core.property.add_group_zalo(this,event)">+Thêm</a>
							{/if}
						</label>
						<div class="col-md-3">
							<select class="form-control iso-select2" data-width="100%" name="group_zalo[{$_oK}][project_id]" onChange="$Core.property.select_block(this,event)" toId="block_{$_oK}">
								<option value="0">Chọn dự án</option>
								{$clsProject->getSelectOptions($_oP.project_id)}
							</select>
						</div>
						<div class="col-md-3">
							<select class="form-control iso-select2" data-width="100%" name="group_zalo[{$_oK}][block_id]" id="block_{$_oK}">
								{$clsProperty->getSelectByPropertyOrigin("_BLOCK",$_oP.project_id,$_oP.block_id,"Phân khu/Block")}
							</select>
						</div>
						<div class="col-md-3">
							<input type="text" class="form-control" onClick="this.select();" name="group_zalo[{$_oK}][group_zalo_id]" value="{$_oP.group_zalo_id}" placeholder="Link folder" maxlength="255">
						</div>
					</div>
					{/foreach}
					<hr />
				{/if}
				{/if}
				<!-- Xác nhận hoa hồng -->
				{if $property_type eq '_TRANSACTION_PROJECT'}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Giám đốc dự án</label>
					<div class="col-md-4">
						<select class="form-control iso-select2" data-width="100%" name="project_director_id">
							<option value="0">Giám đốc dự án</option>
							{if !empty($list_staffs)}
							{foreach from=$list_staffs item = _oStaff}
							<option{if $more_information.project_director_id eq $_oStaff.profile_id} selected{/if} value="{$_oStaff.profile_id}">{$_oStaff.code} - {$_oStaff.full_name}</option>
							{/foreach}
							{/if}
						</select>
					</div>
					<label class="col-md-2 text-right col-form-label">Admin dự án</label>
					<div class="col-md-4">
						<select class="form-control iso-select2" data-width="100%" name="project_admin_id">
							<option value="0">Admin dự án</option>
							{if !empty($list_staffs)}
							{foreach from=$list_staffs item = _oStaff}
							<option{if $more_information.project_admin_id eq $_oStaff.profile_id} selected{/if} value="{$_oStaff.profile_id}">{$_oStaff.code} - {$_oStaff.full_name}</option>
							{/foreach}
							{/if}
						</select>
					</div>
				</div>
				{/if}
				<!-- Xác nhận hoa hồng -->
				{if $property_type eq '_BEDROOM'}
					{if !empty($list_folder_interior_ns)}
						<hr />
						{foreach name=k from=$list_folder_interior_ns key = _oK item = _oP}
						<div class="interior_ns_pa_{$property_id}">
							<div class="form-group form-row group_price_sheets">
								<label class="col-md-2 text-right col-form-label required">Nội thất mẫu PA{$smarty.foreach.k.iteration}</label>
								<div class="col-md-5">
									<input type="text" class="form-control" onClick="this.select();" name="folder_interior_ns[{$_oK}][title]" 
										value="{$_oP.title}" placeholder="Tên folder" maxlength="255">
								</div>
								<div class="col-md-5">
									<input type="text" class="form-control" onClick="this.select();" name="folder_interior_ns[{$_oK}][link]" 
										value="{$_oP.link}" placeholder="Link folder" maxlength="255">
								</div>
							</div>
							<div class="form-group form-row">
								<label class="col-md-2 text-right col-form-label required"></label>
								<div class="col-md-10">
									<input type="text" class="form-control required" placeholder="https://www.youtube.com/watch?v=xxx" 
									name="folder_interior_ns[{$_oK}][video]" onClick="this.select();" value="{$_oP.video}">
								</div>
							</div>
						</div>
						{/foreach}
						<div class="form-group form-row">
							<label class="col-md-2 text-right col-form-label required"></label>
							<div class="col-md-10">
								<a class="btn btn-default" href="javascript:void(0);" property_id="{$property_id}" onClick="$Core.property.add_folder_interior_ns(this, event)">+Thêm PA</a>
							</div>
						</div>
						<hr />
					{/if}
				{elseif $property_type=='_AGENCY'}
					{if !empty($list_folder_price_sheets)}<hr />
						{foreach name=k from=$list_folder_price_sheets key = _oK item = _oP}
						<div class="form-group form-row group_price_sheets">
							
							<label class="col-md-2 text-right col-form-label required">Folder PTG
								{if $smarty.foreach.k.last}
								<a href="javascript:void(0);" onClick="$Core.property.add_folder_price_sheets(this,event)">+Thêm</a>
								{/if}
							</label>
							<div class="col-md-1 text-center">
								<div class="p-2">
									<input type="hidden" name="folder_price_sheets[{$_oK}][status]" value="0" />
									<div class="checkbox">
										<input type="checkbox" class="checkitem" name="folder_price_sheets[{$_oK}][status]" 
											value="1" value="1"{if $_oP.status eq '1'} checked{/if}>
										<label></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets[{$_oK}][title]" value="{$_oP.title}" placeholder="Tên folder" maxlength="255">
							</div>
							<div class="col-md-5">
								<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets[{$_oK}][link]" value="{$_oP.link}" placeholder="Link folder" maxlength="255">
							</div>
						</div>
						{/foreach}
						<hr />
					{/if}
					<div class="group_info_agency">
						<div class="form-group form-row ">							
							<label class="col-md-2 text-right col-form-label required">Văn phòng trụ sở</label>
							<div class="col-md-10">
								<input type="text" class="form-control" onClick="this.select();" name="info_agency[head_office]" value="{$info_agency.head_office}" placeholder="Trụ sở chính" maxlength="255">
							</div>
						</div>
						<hr>
						<div class="form-group group_item">	
							{if !empty($info_agency.branch_office)}
								{foreach from=$info_agency.branch_office item=branch_office key=key name=i}
								<div class="form-row mb-2 item">
									<label class="col-md-2 text-right col-form-label required">Văn phòng chi nhánh
										{if $smarty.foreach.i.last}
										<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="branch_office" >+Thêm</a>
										{/if}
									</label>
									<div class="col-md-9">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[branch_office][]" value="{$branch_office}" placeholder="Chi nhánh" maxlength="255">
									</div>
									<div class="col-md-1">
										{if !$smarty.foreach.i.first}
										<button class="btn btn-default" onClick="$Core.property.delete_info_agency(this,event)" tp="branch_office" type="button" ><i class="fa fa-trash"></i></button>
										{/if}
									</div>
								</div>
								{/foreach}
							{else}
							<div class="form-row mb-2 item">
								<label class="col-md-2 text-right col-form-label required">Văn phòng chi nhánh
									<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="branch_office" >+Thêm</a>
								</label>
								<div class="col-md-9">
									<input type="text" class="form-control" onClick="this.select();" name="info_agency[branch_office][]" value="" placeholder="Chi nhánh" maxlength="255">
								</div>
							</div>
							{/if}
						</div>
						<hr>
						<div class="form-group group_item">	
							{if !empty($info_agency.project)}
								{foreach from=$info_agency.project item=_oProject key=key name=i}
								<div class="form-row mb-2 item">
									<label class="col-md-2 text-right col-form-label required">Dự án bán
										{if $smarty.foreach.i.last}
										<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="project" >+Thêm</a>
										{/if}
									</label>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][{$key}][title]" value="{$_oProject.title}" placeholder="Tên dự án" maxlength="255">
									</div>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][{$key}][project_manager]" value="{$_oProject.project_manager}" placeholder="Giám đốc dự án" maxlength="255">
									</div>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][{$key}][project_admin]" value="{$_oProject.project_admin}" placeholder="Giám đốc dự án" maxlength="255">
									</div>
									<div class="col-md-1">
										{if !$smarty.foreach.i.first}
										<button class="btn btn-default" onClick="$Core.property.delete_info_agency(this,event)" tp="project" type="button" ><i class="fa fa-trash"></i></button>
										{/if}
									</div>
								</div>
								{/foreach}
							{else}
								{assign var=gId value=$clsISO->getUniqid()}
								<div class="form-row mb-2 item">
									<label class="col-md-2 text-right col-form-label required">Dự án bán
										<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="project" >+Thêm</a>
									</label>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][{$gId}][title]" value="" placeholder="Tên dự án" maxlength="255">
									</div>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][{$gId}][project_manager]" value="" placeholder="Giám đốc dự án" maxlength="255">
									</div>
									<div class="col-md-3">
										<input type="text" class="form-control" onClick="this.select();" name="info_agency[project][{$gId}][project_admin]" value="" placeholder="Admin dự án" maxlength="255">
									</div>
								</div>
							{/if}
						</div>
						<hr>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label required">Group Zalo CT</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="https://zalo.me/g/xxx" 
							name="group_zalo" onClick="this.select();" value="{if !empty($more_information.group_zalo)}{$more_information.group_zalo}{/if}">
						</div>
						<label class="col-md-2 text-right col-form-label required">Group Zalo TT</label>
						<div class="col-md-4">
							<input type="text" class="form-control required" placeholder="https://zalo.me/g/xxx" 
							name="group_zalo_lowrise" onClick="this.select();" value="{if !empty($more_information.group_zalo_lowrise)}{$more_information.group_zalo_lowrise}{/if}">
						</div>
					</div>
					<hr />
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label required">Spreadsheet ID</label>
						<div class="col-md-10">
							<input type="text" class="form-control required" placeholder="Spreadsheet ID" name="spreadsheetId" value="{if !empty($more_information.spreadsheetId)}{$more_information.spreadsheetId}{/if}">
							<div class="alert alert-warning mt-2 mb-0">
								<strong>Spreadsheet ID</strong> là phần bôi đậm trong đường dẫn Gooogle Sheet. https://docs.google.com/spreadsheets/d/<strong>143xVs9lPopFSF4eJQWloDYAndMor</strong>/edit
							</div>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Status')}</label>
						<div class="col-md-3">
							<select class="form-control required" name="stock_status_id">
								{$clsProperty->getSelectSingleProperty('_STATUS', 0, $more_information.stock_status_id)}
							</select>
						</div>
						<label class="col-md-3 text-right col-form-label">Ẩn khỏi User.FH</label>
						<div class="col-md-3">
							<label class="switch">
								<input type="checkbox"{if isset($more_information.hide_stock_globe) && $more_information.hide_stock_globe eq '1'} checked{/if} name="hide_stock_globe" value="1" />
								<span class="slider round"></span>
							</label>
						</div>
					</div>
				{/if}
				{if $property_type eq '_REPORT_TEMPLATE'}
				<div class="form-group form-row">
					<label class="col-xs-12 col-md-2 text-right col-form-label">Vai trò</label>
					<div class="col-xs-12 col-md-10">
						<select class="form-control iso-select2" name="permiss_role[]" multiple="true">
							{foreach from=$list_roles item = _oProperty}
							<option value="{$_oProperty.property_id}"{if $clsISO->checkInArray($permiss_role, $_oProperty.property_id)} selected{/if}>{$_oProperty.title}</option>
							{/foreach}
						</select>
					</div>
				</div>
				{/if}
				{if $property_type eq '_OPS_COST_CAT'}
					<div class="form-group form-row">
						<label class="col-xs-12 col-md-2 text-right col-form-label">Văn phòng</label>
						<div class="col-xs-12 col-md-4">
							<select Class="form-control" name="office_id">
								<option value="0">Văn phòng</option>
								{if !empty($arr_offices)}
									{foreach from=$arr_offices item = _oI}
									<option{if $more_information.office_id eq $_oI.setting_id} selected{/if} value="{$_oI.setting_id}">{$_oI.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						<label class="col-xs-12 col-md-2 text-right col-form-label">Hạng mục</label>
						<div class="col-xs-12 col-md-4">
							<select Class="form-control" name="office_cost_cat_id">
								<option value="0">Hạng mục</option>
								{if !empty($arr_categories)}
									{foreach from=$arr_categories item = _oI}
									<option{if $more_information.office_cost_cat_id eq $_oI.setting_id} selected{/if} value="{$_oI.setting_id}">{$_oI.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
					</div>
				{/if}
				{if $property_type ne '_PRICE_RANGE_SOP' 
					&& $property_type ne '_PRICE_RANGE_LEASING' 
					&& $property_type ne '_AREA_RANGE' 
					&& $property_type ne '_ULTILITIES'}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Mô tả</label>
					<div class="col-md-10">
						<textarea class="form-control isoTextArea" id="{$clsISO->getUniqid()}" cols="255" placeholder="Nhập giới thiệu" data-name="intro" rows="3">{if $property_id gt '0'}{$oneProperty.intro}{/if}</textarea>
					</div>
				</div>
				{/if}
				
				
				{if $property_type eq '_OPS_COST_CAT'}
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Tồn quỹ T4/2025</label>
					<div class="col-md-10">
						<input type="text" class="form-control numberonly price-In required" placeholder="0.00" name="ms_value" 
						onClick="this.select();" value="{if !empty($oneProperty.ms_value)}{$oneProperty.ms_value}{/if}">
					</div>
				</div>
				{/if}
				{if $property_type eq '_AGENCY'}
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label">{$core->get_Lang('Content')}</label>
					<div class="col-md-10">
						<textarea class="form-control isoTextArea" id="{$clsISO->getUniqid()}" cols="255" placeholder="Nhập giới thiệu" data-name="MOC_content" rows="3">{if $property_id gt '0'}{$more_information.MOC_content}{/if}</textarea>
					</div>
				</div>
				{/if}
				{if $property_type eq '_GROUP_RANGE_VHGG'}
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label"></label>
					<div class="col-md-10">
						<select class="form-control iso-select2" name="list_ranges[]" data-width="100%" multiple="true">
							{$html_options_range}
						</select>
					</div>
				</div>
				{/if}
				{if $property_type eq 'CUSTOMER_STATUS'}
				<div class="form-group form-row">
					<label for="" class="col-md-2 text-right col-form-label">Hiển thị</label>
					<div class="col-md-10">
						<input type="hidden" name="is_funnel_active" value="0" />
						<div class="d-flex gap-2 align-items-center">
							<label class="switch">
								<input type="checkbox"{if $more_information.is_funnel_active eq '1'} checked="checked"{/if} name="is_funnel_active" value="1">
								<span class="slider round"></span>
							</label>
							<span>Cho phép hiển thị phễu</span>
						</div>
					</div>
				</div>
				{/if}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" onClick="save_property(this)" toId="{$toId}" _reload="{$_reload}" 
					property_id="{$property_id}" property_type="{$property_type}">
					{$core->makeIcon('check', $core->get_Lang('Save'))}
				</button>
			</div>
		</form>
	</div>
</div>
{else}
<div class="table-property text-nowrap overflow-x-auto">
	<table class="table table-hover table-vertical table-striped table-responsive TableListProperty_{$property_type}" width="100%">
		<thead><tr>
			<th class="text-center" width="5%"></th>
			<th class="text-left" width="5%">No.</th>
			{if $property_type eq '_DEPARTMENT'}
				<th class="text-left" width="50%">{$core->get_Lang('Name')}</th>
				<th class="text-left" width="20%">{$core->get_Lang('Code')}</th>
				<th class="text-left" width="15%">{$core->get_Lang('Role')}</th>
				<th class="text-center">Lịch họp</th>
				<th class="text-center">Tình trạng</th>
				<th class="text-left" width="25%">{$core->get_Lang('Actions')}</th>
			{else}
				<th class="text-left" width="45%">{$core->get_Lang('Name')}</th>
				<th class="text-left" width="20%">{$core->get_Lang('Code')}</th>
				{if $property_type eq '_AGENCY'}
				<!-- MOC -->
				{*<th class="text-left" width="15%">Ẩn MWF MOC</th>
				<th class="text-left" width="15%">VHOP MOC</th>
				<th class="text-left" width="15%">LSB MOC</th>
				<th class="text-left" width="15%">MLS MOC</th>
				<th class="text-left" width="15%">MGA MOC</th>
				<th class="text-left" width="15%">MTS MOC</th>
				<!-- user.FH -->
				<th class="text-left" width="15%">MWF FH</th>
				<th class="text-left" width="15%">VHOP FH</th>
				<th class="text-left" width="15%">LSB FH</th>
				<th class="text-left" width="15%">MLS FH</th>
				<th class="text-left" width="15%">MGA FH</th>*}
				<th class="text-left" width="15%">Ẩn crawl excel</th>
				{/if}
				{if $property_type eq '_PACKAGE' || $property_type eq '_ROLE'}
				<th class="text-left" width="15%">{$core->get_Lang('Permiss')}</th>{/if} 
				{if $property_type eq '_ROLE'}
					<th class="text-center" width="15%" colspan="2">Tính năng</th>
				{/if} 
				{if $property_type eq '_PACKAGE'}
				<th class="text-center">MOC Point</th>{/if}
				<th class="text-center">Tình trạng</th>
				<th class="text-left" width="10%">{$core->get_Lang('Actions')}</th>
			{/if}
		</tr></thead>
		<tbody>
		{if $lstProperty[0].property_id ne ''}
			{section name=i loop=$lstProperty}
			{assign var = property_id value = $lstProperty[i].property_id}
			{assign var = more_information value = $lstProperty[i].more_information}
			<tr class="bold" id="{$lstProperty[i].property_id}">
				<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">
					{$core->makeIcon('bars')}
				</td>
				<td data-label="No.">{$smarty.section.i.iteration}</td>
				<td class="text-nowrap" data-label="{$core->get_Lang('Name')}">{$clsProperty->getTitle($property_id)}
					<a href="javascript:void(0);" onclick="open_property(this)" parent_id="{$property_id}" property_type="{$property_type}" property_id="0"><img src="{$URL_IMAGES}/add.png" width="25px" /></a>
				</td>
				<td data-label="{$core->get_Lang('Name')}">
					{if !empty($lstProperty[i].property_code)}
						{$lstProperty[i].property_code}
					{else}
					--
					{/if}
				</td>
				{if $property_type eq '_AGENCY'}
				<!-- MOC -->
				{*<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_mwf_MOC) && $more_information.hide_stock_mwf_MOC eq '1'} checked{/if} to_field="hide_stock_mwf_MOC" property_id="{$property_id}" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_vin_MOC) && $more_information.hide_stock_vin_MOC eq '1'} checked{/if} to_field="hide_stock_vin_MOC" property_id="{$property_id}" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_lsb_MOC) && $more_information.hide_stock_lsb_MOC eq '1'} checked{/if} to_field="hide_stock_lsb_MOC" property_id="{$property_id}" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_mls_MOC) && $more_information.hide_stock_mls_MOC eq '1'} checked{/if} to_field="hide_stock_mls_MOC" property_id="{$property_id}" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_mga_MOC) && $more_information.hide_stock_mga_MOC eq '1'} checked{/if} to_field="hide_stock_mga_MOC" property_id="{$property_id}" value="1" /> <span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_mts_MOC) && $more_information.hide_stock_mts_MOC eq '1'} checked{/if} to_field="hide_stock_mts_MOC" property_id="{$property_id}" value="1" /> <span class="slider round"></span>
					</label>
				</td>
				<!-- user.FH -->
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_mwf) && $more_information.hide_stock_mwf eq '1'} checked{/if} to_field="hide_stock_mwf" property_id="{$property_id}" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_vin) && $more_information.hide_stock_vin eq '1'} checked{/if} to_field="hide_stock_vin" property_id="{$property_id}" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_lsb) && $more_information.hide_stock_lsb eq '1'} checked{/if} to_field="hide_stock_lsb" property_id="{$property_id}" value="1" /> <span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_mls) && $more_information.hide_stock_mls eq '1'} checked{/if} to_field="hide_stock_mls" property_id="{$property_id}" value="1" /> <span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_mga) && $more_information.hide_stock_mga eq '1'} checked{/if} to_field="hide_stock_mga" property_id="{$property_id}" value="1" /> <span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_stock_mts) && $more_information.hide_stock_mts eq '1'} checked{/if} to_field="hide_stock_mts" property_id="{$property_id}" value="1" /> <span class="slider round"></span>
					</label>
				</td>*}
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" onChange="hide_stock_globe(this, event)"{if isset($more_information.hide_crawl_excel) && $more_information.hide_crawl_excel eq '1'} checked{/if} to_field="hide_crawl_excel" property_id="{$property_id}" value="1" /> <span class="slider round"></span>
					</label>
				</td>
				<!-- End -->
				{/if}
				{if $property_type eq '_DEPARTMENT'}
				<th class="text-left" width="65%">
					{if $lstProperty[i].for_id gt '0'}
						{$clsProperty->getTitle($lstProperty[i].for_id)}
					{else}
					 -- 
					{/if}
				</th>
				{/if}
				{if $property_type eq '_PACKAGE' || $property_type eq '_ROLE'}
				<td data-label="{$core->get_Lang('Active')}">
					<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="{if $property_type eq '_PACKAGE'}MOC{else}user.fh{/if}" for_id="{$lstProperty[i].property_id}">Phân quyền</a>
				</td>
				{/if}
				{if $property_type eq '_ROLE'}
				<td data-label="{$core->get_Lang('Active')}">
					--
				</td>
				<td data-label="{$core->get_Lang('Active')}">
					--
				</td>
				{/if}
				{if $property_type eq '_PACKAGE'}
				<td class="text-center">
					<label class="switch">
						<input type="checkbox"{if $more_information.status_moc_point eq '1'} checked{/if} onChange="$Core.property.set_status_moc_point(this, event)" 
							property_id="{$lstProperty[i].property_id}" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				{/if}
				{if $property_type eq '_DEPARTMENT'}
					<td class="text-center">
						<label class="switch">
							<input type="checkbox"{if $more_information.is_calendar eq '1'} checked{/if} onChange="$Core.property.set_show_calendar(this, event)" 
								property_id="{$lstProperty[i].property_id}" value="1" />
							<span class="slider round"></span>
						</label>
					</td>
				{/if}
				<td class="text-center">
					<label class="switch">
						<input type="checkbox"{if $lstProperty[i].is_trash eq '0'} checked{/if} onChange="$Core.property.set_status(this, event)" 
							property_id="{$lstProperty[i].property_id}" value="1" />
						<span class="slider round"></span>
					</label>
				</td>
				<td data-label="{$core->get_Lang('Actions')}">
					<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
						<button class="btn btn-default" onClick="open_property(this)" property_id="{$lstProperty[i].property_id}" property_type="{$property_type}">{$core->makeIcon('pencil')}</button>
						<button class="btn btn-default" onClick="delete_property(this)" property_id="{$lstProperty[i].property_id}" property_type="{$property_type}">{$core->makeIcon('trash')}</button>
					</div>
				</td>
			</tr>
			{assign var = lstChild value = $clsProperty->getItems($property_type, $lstProperty[i].property_id)}
			{if $lstChild[0].property_id ne ''}
				{section name=j loop=$lstChild}
				{assign var=moreInformation value=$clsISO->to_array_json($lstChild[j].more_information)}
				<tr id="{$lstChild[j].property_id}">
					<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
					<td data-label="No.">{$smarty.section.i.iteration}.{$smarty.section.j.iteration}</td>
					<td data-label="{$core->get_Lang('Name')}">+&nbsp;{$clsProperty->getTitle($lstChild[j].property_id)}
						{if $lstChild[j].image ne ''}
						<span class="label label-default">Icon</span>
						{/if}
					</td>
					<td data-label="{$core->get_Lang('Name')}">
						{if !empty($lstChild[j].property_code)}
							{$lstChild[j].property_code}
						{else}
						--
						{/if}
					</td>
					{if $property_type eq '_DEPARTMENT'}
					<th class="text-left" width="65%">
						{if $lstChild[j].for_id gt '0'}
							{$clsProperty->getTitle($lstChild[j].for_id)}
						{else}
						 -- 
						{/if}
					</th>
					{/if}
					{if $property_type eq '_PACKAGE' || $property_type eq '_ROLE'}
					<td data-label="{$core->get_Lang('Active')}">
						<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="{if $property_type eq '_PACKAGE'}MOC{else}user.fh{/if}" for_id="{$lstChild[j].property_id}">Phân quyền</a>
					</td>
					{/if}
					{if $property_type eq '_ROLE'}
					<td data-label="{$core->get_Lang('Active')}">
						<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_choose" for_id="{$lstChild[j].property_id}">Lựa chọn</a>
					</td>
					<td data-label="{$core->get_Lang('Active')}">
						<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_sort"  for_id="{$lstChild[j].property_id}">Sắp xếp</a>
					</td>
					{/if}
					{if $property_type eq '_DEPARTMENT'}
					<td class="text-center">
						<label class="switch">
							<input type="checkbox"{if $moreInformation.is_calendar eq '1'} checked{/if} onChange="$Core.property.set_show_calendar(this, event)" 
								property_id="{$lstChild[j].property_id}" value="1"  />
							<span class="slider round"></span>
						</label>
					</td>
					{/if}
					<td class="text-center">
						<label class="switch">
							<input type="checkbox"{if $lstChild[j].is_trash eq '0'} checked{/if} onChange="$Core.property.set_status(this, event)" 
								property_id="{$lstChild[j].property_id}" value="1" />
							<span class="slider round"></span>
						</label>
					</td>
					<td data-label="{$core->get_Lang('Actions')}">
						<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
							<button class="btn btn-default" onClick="open_property(this)" property_id="{$lstChild[j].property_id}" property_type="{$property_type}">{$core->makeIcon('pencil')}</button>
							<button class="btn btn-default" onClick="delete_property(this)" property_id="{$lstChild[j].property_id}" property_type="{$property_type}">{$core->makeIcon('trash')}</button>
						</div>
					</td>
				</tr>
				{assign var = lstSubChild value = $clsProperty->getItems($property_type, $lstChild[j].property_id)}
				{if !empty($lstSubChild)}
					{section name=k loop=$lstSubChild}
					<tr id="{$lstSubChild[k].property_id}">
						<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
						<td data-label="No." class="text-left">{$smarty.section.i.iteration}.{$smarty.section.j.iteration}.{$smarty.section.k.iteration}</td>
						<td data-label="{$core->get_Lang('Name')}">++&nbsp;{$clsProperty->getTitle($lstSubChild[k].property_id)}</td>
						<td data-label="{$core->get_Lang('Name')}">
							{if !empty($lstSubChild[k].property_code)}
								{$lstSubChild[k].property_code}
							{else}
							--
							{/if}
						</td>
						{if $property_type eq '_DEPARTMENT'}
						<th class="text-left" width="65%">
							{if $lstSubChild[k].for_id gt '0'}
								{$clsProperty->getTitle($lstSubChild[k].for_id)}
							{else}
							 -- 
							{/if}
						</th>
						{/if}
						{if $property_type eq '_PACKAGE' || $property_type eq '_ROLE'}
						<td data-label="hân quyền">
							<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="{if $property_type eq '_PACKAGE'}MOC{else}user.fh{/if}" for_id="{$lstSubChild[k].property_id}">Phân quyền</a>
						</td>
						{/if}
						{if $property_type eq '_ROLE'}
						<td data-label="{$core->get_Lang('Active')}">
							<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)"v for_id="{$lstSubChild[k].property_id}">Lựa chọn</a>
						</td>
						<td data-label="{$core->get_Lang('Active')}">
							<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_sort"  for_id="{$lstSubChild[k].property_id}">Sắp xếp</a>
						</td>
						{/if}
						{if $property_type eq '_DEPARTMENT'}
						<td class="text-center">
							<label class="switch">
								<input type="checkbox"{if $more_information.is_calendar eq '1'} checked{/if} onChange="$Core.property.set_show_calendar(this, event)" 
									property_id="{$lstSubChild[k].property_id}" value="1" />
								<span class="slider round"></span>
							</label>
						</td>
						{/if}
						<td class="text-center">
							<label class="switch">
								<input type="checkbox"{if $lstSubChild[k].is_trash eq '0'} checked{/if} onChange="$Core.property.set_status(this, event)" 
									property_id="{$lstSubChild[k].property_id}" value="1" />
								<span class="slider round"></span>
							</label>
						</td>
						<td data-label="{$core->get_Lang('Actions')}">
							<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
								<button class="btn btn-default" onClick="open_property(this)" property_id="{$lstSubChild[k].property_id}" property_type="{$property_type}">{$core->makeIcon('pencil')}</button>
								<button class="btn btn-default" onClick="delete_property(this)" property_id="{$lstSubChild[k].property_id}" property_type="{$property_type}">{$core->makeIcon('trash')}</button>
							</div>
						</td>
					</tr>
					{assign var = list_items_4 value = $clsProperty->getItems($property_type, $lstSubChild[k].property_id)}
					{if !empty($list_items_4)}
						{foreach name=n from=$list_items_4 item = _oItem4}
						<tr id="{$_oItem4.property_id}">
							<td class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
							<td>{$smarty.section.i.iteration}.{$smarty.section.j.iteration}.{$smarty.section.k.iteration}.{$smarty.section.n.iteration}</td>
							<td>+++&nbsp;{$clsProperty->getTitle($_oItem4.property_id, $_oItem4)}</td>
							<td>
								{if !empty($_oItem4.property_code)}
									{$_oItem4.property_code}
								{else}
								--
								{/if}
							</td>
							{if $property_type eq '_DEPARTMENT'}
							<th class="text-left" width="65%">
								{if $_oItem4.for_id gt '0'}
									{$clsProperty->getTitle($_oItem4.for_id)}
								{else}
								 -- 
								{/if}
							</th>
							{/if}
							{if $property_type eq '_PACKAGE' || $property_type eq '_ROLE'}
							<td data-label="{$core->get_Lang('Active')}">
								<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="{if $property_type eq '_PACKAGE'}MOC{else}user.fh{/if}" for_id="{$_oItem4.property_id}">Phân quyền</a>
							</td>
							{/if}
							{if $property_type eq '_ROLE'}
							<td data-label="{$core->get_Lang('Active')}">
								<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_choose"  for_id="{$_oItem4.property_id}">Lựa chọn</a>
							</td>
							<td data-label="{$core->get_Lang('Active')}">
								<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_sort"  for_id="{$_oItem4.property_id}">Sắp xếp</a>
							</td>
							{/if}
							{if $property_type eq '_DEPARTMENT'}
							<td class="text-center">
								<label class="switch">
									<input type="checkbox"{if $more_information.is_calendar eq '1'} checked{/if} onChange="$Core.property.set_show_calendar(this, event)" property_id="{$_oItem4.property_id}" value="1" />
									<span class="slider round"></span>
								</label>
							</td>
							{/if}
							<td class="text-center">
								<label class="switch">
									<input type="checkbox"{if $_oItem4.is_trash eq '0'} checked{/if} onChange="$Core.property.set_status(this, event)" 
										property_id="{$_oItem4.property_id}" value="1" />
									<span class="slider round"></span>
								</label>
							</td>
							<td>
								<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
									<button class="btn btn-default" onClick="open_property(this)" property_id="{$_oItem4.property_id}" property_type="{$property_type}">{$core->makeIcon('pencil')}</button>
									<button class="btn btn-default" onClick="delete_property(this)" property_id="{$_oItem4.property_id}" property_type="{$property_type}">{$core->makeIcon('trash')}</button>
								</div>
							</td>
						</tr>
						{assign var = list_items_5 value = $clsProperty->getItems($property_type, $_oItem4.property_id)}
						{if !empty($list_items_5)}
							{foreach from = $list_items_5 item = _oItem5}
							<tr id="{$list_items_5[m].property_id}">
								<td class="text-center mySortableHandler" style="color:#2A5F8B">{$core->makeIcon('bars')}</td>
								<td>{$smarty.section.i.iteration}.{$smarty.section.j.iteration}.{$smarty.section.k.iteration}.{$smarty.section.n.iteration}</td>
								<td>++++&nbsp;{$clsProperty->getTitle($list_items_5[n].property_id, $_oItem5)}</td>
								<td>
									{if !empty($_oItem5.property_code)}
										{$_oItem5.property_code}
									{else}
									--
									{/if}
								</td>
								{if $property_type eq '_DEPARTMENT'}
								<th class="text-left" width="65%">
									{if $_oItem5.for_id gt '0'}
										{$clsProperty->getTitle($_oItem5.for_id)}
									{else}
									 -- 
									{/if}
								</th>
								{/if}
								{if $property_type eq '_PACKAGE' || $property_type eq '_ROLE'}
								<td data-label="{$core->get_Lang('Active')}">
									<a href="javascript:void(0);" onClick="$Core.permiss.open(this, event)" profile_type="{if $property_type eq '_PACKAGE'}MOC{else}user.fh{/if}" for_id="{$_oItem5.property_id}">Phân quyền</a>
								</td>
								{/if}
								{if $property_type eq '_ROLE'}
								<td data-label="{$core->get_Lang('Active')}">
									<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_choose" 
										for_id="{$_oItem5.property_id}">Lựa chọn</a>
								</td>
								<td data-label="{$core->get_Lang('Active')}">
									<a href="javascript:void(0);" onClick="$Core.property.open_ultilities(this, event)" _type="_sort" 
										for_id="{$_oItem5.property_id}">Sắp xếp</a>
								</td>
								{/if}
								{if $property_type eq '_DEPARTMENT'}
								<td class="text-center">
									<label class="switch">
										<input type="checkbox"{if $more_information.is_calendar eq '1'} checked{/if} onChange="$Core.property.set_show_calendar(this, event)" property_id="{$_oItem5.property_id}" value="1" />
										<span class="slider round"></span>
									</label>
								</td>
								{/if}
								<td class="text-center">
									<label class="switch">
										<input type="checkbox"{if $_oItem5.is_trash eq '0'} checked{/if} onChange="$Core.property.set_status(this, event)" 
											property_id="{$_oItem5.property_id}" value="1" />
										<span class="slider round"></span>
									</label>
								</td>
								<td data-label="{$core->get_Lang('Actions')}">
									<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
										<button class="btn btn-default" onClick="open_property(this, event)" property_id="{$_oItem5.property_id}" 
											property_type="{$property_type}">{$core->makeIcon('pencil')}</button>
										<button class="btn btn-default" onClick="delete_property(this, event)" property_id="{$_oItem5.property_id}" 
											property_type="{$property_type}">{$core->makeIcon('trash')}</button>
									</div>
								</td>
							</tr>
							{/foreach}
						{/if}
						{/foreach}
					{/if}
					{/section}
				{/if}
				{/section}
			{/if}
			{/section}
		{else}
			<tr>
				<td colspan="7" class="text-center">
					{$clsISO->renderHTMLNoDocument($core->get_Lang('Not any records(s) here'))}
				</td>
			</tr>
		{/if}
		</tbody>
	</table>
</div>
{/if}
