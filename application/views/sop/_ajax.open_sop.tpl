<div class="modal-dialog js__modal-sop modal-ipad modal-dialog-centered">
	<form method="post" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header border-bottom"> 
			<h5 class="modal-title">Cửa hàng/ Tiện ích</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body border-bottom">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Nhu cầu</label>
				{assign var=ref_id value = $clsISO->getUniqid()}
				<div ref_id="{$ref_id}" class="d-flex sop__checkbox-radio gap-1 flex-wrap">
				{if !empty($list_needs)}
					{foreach from=$list_needs item = _oItem}
					<label class="we-radio" for="rdo_{$_oItem.property_id}">
						<input type="radio" ref_id="{$ref_id}" id="rdo_{$_oItem.property_id}" onChange="$Core.global.sop.fire_event(this, event)" 
						name="need_id"{if $oneSop.need_id eq $_oItem.property_id} checked{/if} value="{$_oItem.property_id}">
						<span>{$_oItem.title}</span>
					</label>
					{/foreach}
					<a data-bs-toggle="tooltip" id="{$ref_id}" ref_id="{$ref_id}" onClick="$Core.global.sop.clear_checked(this, event)" 
						title="Xóa chọn" class="btn btn-icon d-none btn-default"><i class="bx bx-x"></i></a>
				{/if}
				</div>
			</div>
			<div class="form-group mb-2 form-row">
				<div class="col-12 col-sm-6 mb-2 mb-lg-0">
					<label class="form-label mb-1 text-main">* Mã căn</label>
					<div class="relative">
						<input type="hidden" name="stock_id" value="{$oneSop.stock_id}" />
						<input type="text" required="true" placeholder="Nhập mã căn" name="stock_code" maxlength="255" 
						class="form-control no-focus" value="{$oneSop.stock_code}" id="stock_code" />
					</div>
				</div>
				<div class="col-12 col-sm-6 flex-fill">
					<div class="d-flex align-items-center justify-content-between form-label mb-1">
						<span class="text-main">* Giá bán</span>
						<span class="text-capitalize js-price-text font-medium">
							<span class="js-price-text-view"></span>
						</span>
					</div>
					<input type="text" required="true" onkeyup="$Core.sop._handleInputPrice(this, event)" placeholder="" name="price" class="form-control price-In numberonly no-focus" value="{$oneSop.price}" data-bind="true"/>
				</div>
			</div>					
			<div class="form-group mb-2">
				<label class="form-label mb-1 text-main">* Tiêu đề</label>
				<input type="text" placeholder="Căn siêu đẹp 3PN sẵn sổ, full đồ tuyệt đẹp tòa S2.08" name="title" maxlength="80" charet="UTF-8" class="form-control no-focus required" value="{$oneSop.title}" />
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-lg-6">
					<label class="form-label mb-1">Phí chuyển nhượng</label>
					<select name="fee_included" class="form-control no-focus form-select">
						{$clsProperty->getSelectByProperty('_FEE_TYPE',$oneSop.fee_included)}
					</select>
				</div>
				<div class="col-12 col-lg-6">
					<label class="form-label  mb-1">Nội thất</label>
					<div class="clearfix"></div>
					<select name="interior_id" class="form-control no-focus form-select">
						{$clsProperty->getSelectByProperty('_INTERIOR_TYPE',$oneSop.interior_id)}
					</select>
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Pháp lý</label>
				{assign var=ref_id value = $clsISO->getUniqid()}
				<div ref_id="{$ref_id}" class="d-flex gap-1 sop__checkbox-radio flex-wrap">
				{if !empty($list_phaply)}
					{foreach from=$list_phaply item = _oItem}
					<label class="we-radio" for="rdo_{$_oItem.property_id}">
						<input type="radio" ref_id="{$ref_id}" id="rdo_{$_oItem.property_id}" onChange="$Core.global.sop.fire_event(this, event)" name="juridical_id"{if $oneSop.juridical_id eq $_oItem.property_id} checked{/if} value="{$_oItem.property_id}">
						<span>{$_oItem.title}</span>
					</label>
					{/foreach}
					<a data-bs-toggle="tooltip" id="{$ref_id}" ref_id="{$ref_id}" onClick="$Core.global.sop.clear_checked(this, event)" 
						title="Xóa chọn" class="btn btn-icon d-none btn-default"><i class="bx bx-x"></i></a>
				{/if}
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Điểm nổi bật</label>
				<textarea id="textarea" class="form-control no-focus" maxlength="250" rows="6" name="content" placeholder="Nhập mô tả chung về bất động sản của bạn. Ví dụ: Khu nhà có vị trí thuận lợi, gần công viên, gần trường học ... ">{$more_information.content|nl2br}</textarea>
			</div>
			<div class="form-group form-row row-gap-1 mb-2">							
				<div class="col-12 box_sop_type mb-1">
					<div class="form-group d-flex flex-wrap gap-2 align-items-center">
						<label class="col-form-label">Loại hình căn hộ</label>
						{assign var=gid value = $clsISO->getUniqid()}
						<div class="btn-group d-flex gap-2" role="group" aria-label="Loại hình căn hộ">
							{foreach from=$list_sop_type item=_oSopType}
							<label class="we-radio" for="sop_type_{$_oSopType.property_id}">
								<input type="radio" id="sop_type_{$_oSopType.property_id}"{if $oneSop.sop_type eq $_oSopType.property_id} checked{/if} 
								name="sop_type" value="{$_oSopType.property_id}" onChange="$Core.global.sop.load_soptype(this, event)">
								<span>{$_oSopType.title}</span>
							</label>
							{/foreach}
						</div>
					</div>
				</div>
				<div class="col-6 flex-fill">
					<label class="col-form-label text-main">* Dự án</label>
					<select class="form-control no-focus form-select iso-select2 required" onChange="$Core.global.sop.load_form_field(this, event,{})" 
					name="project_id" data-placeholder="Dự án" data-width="100%" id="slb_Project_Id_{$uid}" toId="slb_Block_Id_{$uid}" data-field="block_id">
						<option value="">Chọn dự án</option>
						{if !empty($list_sop_projects)}
							{foreach name=i from=$list_sop_projects item=_oProject}
							<option{if $oneSop.project_id eq $_oProject.project_id} selected{/if} value="{$_oProject.project_id}">{$_oProject.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-6 flex-fill box_block">
					<label class="col-form-label text-main">* Phân khu</label>
					<select onChange="$Core.global.sop.load_form_field(this, event,{})" name="block_id" data-placeholder="Phân khu" 
					class="form-control no-focus form-select iso-select2 required" data-width="100%" id="slb_Block_Id_{$uid}" toId="slb_Building_Id" 
					data-field="building_id">
						<option value="">Phân khu</option>
						{if !empty($list_sop_blocks)}
							{foreach name=i from=$list_sop_blocks item=block}
							<option {if $oneSop.block_id eq $block.property_id} selected{/if} value="{$block.property_id}">{$block.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-6 flex-fill box_building">
					<label class="col-form-label lbl_building text-main">* Toà/Dãy</label>
					<select class="form-control no-focus form-select iso-select2 required" data-placeholder="Tòa/Dãy" 
					data-width="100%" name="building_id" data-allowclear="true" id="slb_Building_Id" data-field="floor_range">
						<option value="">Toà/Dãy</option>
						{if !empty($list_sop_buildings)}
							{foreach name=i from=$list_sop_buildings item=building}
							<option value="{$building.property_id}" {if $oneSop.building_id eq $building.property_id} selected{/if}>{$building.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-6 flex-fill box_type_villa box_lowrise{if $oneSop.sop_type eq $smarty.const._SOP_TYPE_HIGHLEVEL} d-none{/if}">
					<label class="col-form-label">Loại hình thấp tầng</label>
					<select class="form-control no-focus form-select iso-select2" data-field="block_id" name="type_villa_id" 
						data-placeholder="Loại hình căn hộ" data-width="100%" data-allowclear="true" data-field="sop_type">
						<option value="0">Chọn loại hình</option>
						{foreach from=$list_type_villa item=_oTypeVilla}
							<option value="{$_oTypeVilla.property_id}" {if $more_information.type_villa_id eq $_oSopType.property_id}selected{/if}>{$_oTypeVilla.title}</option>
						{/foreach}
					</select>
				</div>
				<div class="col-6 flex-fill box_bedroom box_high_level{if $oneSop.sop_type eq $smarty.const._SOP_TYPE_LOWFLOOR} d-none{/if}">
					<label class="col-form-label text-main">* Loại phòng</label>
					<div class="clearfix"></div>
					<select name="bedroom_id" class="form-control no-focus form-select iso-select2" 
						data-width="100%" data-placeholder="Loại phòng">
						<option value="0">--Chọn loại phòng--</option>
						{$clsProperty->getSelectByProperty('_BEDROOM',$oneSop.bedroom_id)}
					</select>	
				</div>
				<div class="col-6 flex-fill">
					<label class="col-form-label">Số tầng</label>
					<input type="text" placeholder="Nhập số" name="floor_count" class="form-control no-focus number" value="{$oneSop.floor}" />
				</div>
				<div class="col-6 flex-fill">
					<label class="col-form-label">Số nhà</label>
					<input type="text" placeholder="Nhập số nhà" name="code" class="form-control no-focus" value="{$oneSop.code}" />
				</div>
				<div class="col-6 flex-fill">
					<label class="col-form-label">Hướng ban công</label>
					<div class="clearfix"></div>
					<select name="home_direction_id" data-width="100%" class="form-control no-focus form-select iso-select2">
						<option value="0">--Chọn hướng--</option>
						{$clsProperty->getSelectByProperty('_DIRECTION',$oneSop.home_direction_id)}
					</select>	
				</div>
				<div class="col-6 flex-fill">
					<label class="col-form-label">Diện tích <span class="text-lowercase">(m<sup>2</sup>)</span></label>
					<div class="input-group input-group-merge">
						<input class="form-control numberonly no-focus" type="text" name="DT_TT" 
						step="0.01" min="0" value="{$more_information.DT_TT}">
						<span class="input-group-text py-0">m<sup>2</sup></span>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
			<button type="button" onClick="$Core.global.sop.save_sop(this, event)" sop_id="{$sop_id}" 
				class="btn btn-primary" sop_chatlog_id="{$sop_chatlog_id}">Lưu lại</button>
		</div>
	</form>
</div>