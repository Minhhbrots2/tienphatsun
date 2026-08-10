{if $deviceType eq 'phone'}
{if !empty($list_sop_type)}
<div id="js__dropdown-type_group" class="form-group mb-2">
	<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
		{foreach name=i from=$list_sop_type item=_oType}
		<input type="radio" onchange="$Core.sop.select_type(this, event)" name="sop_type" 
		class="btn-check js__option-type" id="{$_oType.property_id}" value="{$_oType.property_id}"{if $_oType.property_id eq $get_sop_type_id} checked{/if} toId="js__dropdown-project" tp="radio">
		<label data-toggle="ripple" class="btn btn-outline-default w-50" for="{$_oType.property_id}">{$_oType.title}</label>
		{/foreach}
	</div>
</div>
{/if}
<div class="w-100 d-flex">
	<button type="button" class="btn btn-outline-default w-50 mr-2" data-bs-toggle="modal" 
		data-bs-target="#js__search-form-modal">
		<i class="material-icons-outlined">tune</i> Bộ lọc
	</button> 
	<div class="dropdown w-50">
		{assign var = _toId value = $clsISO->getUniqid()}
		<button type="button" class="btn btn-outline-default dropdown-toggle hide-arrow w-100" data-bs-toggle="dropdown">
			<span id="{$_toId}">{$clsISO->makeIcon('bx-sort-up', 'Mới nhất')}</span>
			<i class="material-icons-outlined">arrow_drop_down</i>
		</button>
		<ul class="dropdown-menu dropdown-menu-arrow">
			<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId} active" toId="{$_toId}" title="Mới nhất" onClick="$Core.sop.do_sort(this, event)" holderG="date_desc">{$clsISO->makeIcon('bx-sort-up', 'Mới nhất')}</a></li>
			<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Mới nhất" onClick="$Core.sop.do_sort(this, event)" holderG="date_asc">{$clsISO->makeIcon('bx-sort-down', 'Cũ nhất')}</a></li>
			<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Giá tăng dần" onClick="$Core.sop.do_sort(this, event)" holderG="price_asc">{$clsISO->makeIcon('bx-sort-up', 'Giá tăng dần')}</a></li>
			<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Giá giảm dần" onClick="$Core.sop.do_sort(this, event)" holderG="price_desc">{$clsISO->makeIcon('bx-sort-down', 'Giá giảm dần')}</a></li>
			<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Giá tăng dần" onClick="$Core.sop.do_sort(this, event)" holderG="price_m2_asc">{$clsISO->makeIcon('bx-sort-up', 'Giá m2 tăng dần')}</a></li>
			<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Giá giảm dần" onClick="$Core.sop.do_sort(this, event)" holderG="price_m2_desc">{$clsISO->makeIcon('bx-sort-down', 'Giá m2 giảm dần')}</a></li>
		</ul>
	</div>
</div>
<div class="modal fade modal_top right js__search-form-modal" id="js__search-form-modal" 
	tabindex="-1" role="dialog" aria-bs-modal="true" aria-bs-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-xs w-100 mw-100">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title align-items-center fs-4 text-dark">
					<i class="material-icons-outlined">tune</i> 
					<span>Bộ lọc tìm kiếm</span>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body pt-0">
				<div id="js__dropdown-project_group" class="form-group mb-3 {if $get_sop_type_id eq 0}d-none{/if} w-100">
					<label class="form-text mb-2 text-dark fs-5">Dự án</label>
					<div class="d-flex gap-2 flex-wrap" id="js__dropdown-project-list">
					{if !empty($list_project)}
						{foreach from=$list_project item = _oProject}
						<label class="we-radio we-radio-search" for="rdo_{$_oProject.project_id}">
							<input type="radio" onChange="$Core.sop.select_project(this, event)" class="js__option-project" toId="js__dropdown-block" tp="radio" id="rdo_{$_oProject.project_id}" name="project_id" value="{$_oProject.project_id}" {if $_oProject.project_id eq $get_project_id }checked{/if}>
							<span class="text-dark">{$_oProject.title}</span>
						</label>
						{/foreach}
					{/if}
					</div>	
				</div>
				<div id="js__dropdown-block_group" class="form-group mb-3 {if $get_project_id eq 0}d-none{/if} w-100">
					<label class="form-text mb-2 text-dark fs-5">Phân khu</label>
					<div class="d-flex gap-2 flex-wrap" id="js__dropdown-block-list">
					{if !empty($list_blocks)}
						{foreach from=$list_blocks item = block}
							<label class="we-radio we-radio-search" for="rdo_{$block.property_id}">
								<input type="radio" onChange="$Core.sop.select_block(this, event)" class="js__option-block" toId="js__dropdown-building" tp="radio" id="rdo_{$block.property_id}" name="block_id" value="{$block.property_id}" {if $block.property_id eq $get_block_id }checked{/if}>
								<span class="text-dark">{$block.title}</span>
							</label>
						{/foreach}
					{/if}
					</div>	
				</div>
				<div id="js__dropdown-building_group" class="form-group mb-3 {if $get_block_id eq 0}d-none{/if} w-100">
					<label class="form-text mb-2 text-dark fs-5">Tòa/Dãy</label>
					<div class="d-flex gap-2 flex-wrap">
						<div class="d-flex flex-wrap w-100" id="js__dropdown-building-list">
							{if !empty($list_buildings)}
								{foreach name=i from=$list_buildings item=building}
									<label class="form-check form-check-dark w-50 mb-2" for="chk_{$building.property_id}">
										<input class="form-check-input js__option-building" type="checkbox" name="building_id[]" value="{$building.property_id}" id="chk_{$building.property_id}" {if $clsISO->checkItemInArray($building.property_id, $get_building_ids)}checked{/if}>
										<span class="form-check-label text-dark ml-1">{$building.title}</span>
									</label>
								{/foreach}
							{/if}
						</div>
					</div>	
				</div>
				<div class="form-group mb-3">
					<label class="form-text mb-2 text-dark fs-5">Khoảng giá (tỷ)</label>
					<input type="hidden" class="search_field" name="price_min" data-field="price_min" value="{$get_price_min}" />
					<input type="hidden" class="search_field" name="price_max" data-field="price_max" value="{$get_price_max}" />
					<div class="d-flex gap-2  flex-wrap w-100">
						{if !empty($lstPriceRangeSop)}
							{foreach name=i from=$lstPriceRangeSop item=_oItem}
								<label class="we-radio we-radio-search" for="rdo_{$_oItem.property_id}">
									<input type="radio" onChange="$Core.sop.select_price_range(this, event)"  id="rdo_{$_oItem.property_id}" name="price_range" value="{$_oItem.property_id}" data-min="{$_oItem.min}" data-max="{$_oItem.max}" {if $_oItem.property_id eq $get_price_range }checked{/if}>
									<span class="text-dark">{$_oItem.title}</span>
								</label>
							{/foreach}
						{/if}
					</div>
				</div>
				<div class="form-group mb-3">
					<label class="form-text mb-2 text-dark fs-5">Hướng ban công</label>
					<div class="d-flex w-100 flex-wrap">
					{if !empty($list_directions)}
						{foreach from=$list_directions item = _oP}
							<label class="form-check form-check-dark w-50 mb-2" for="chk_{$_oP.property_id}">
								<input class="form-check-input js__option-home_direction" type="checkbox" value="{$_oP.property_id}" id="chk_{$_oP.property_id}" {if $clsISO->checkItemInArray($_oP.property_id,$get_direction_ids)}checked{/if}>
								<span class="form-check-label text-dark ml-1">{$_oP.title}</span>
							</label>
						{/foreach}
					{/if}
					</div>
				</div>
				<div class="form-group js__block-bedroom-list {if $get_type_id eq $smarty.const._TYPE_LOWFLOOR}d-none{/if}">
					<label class="form-text mb-2 text-dark fs-5">Khoảng tầng</label>
					<div class="d-flex w-100 flex-wrap">
						{if !empty($list_directions)}
							{foreach from=$list_range_floors item = _oRange}
								<label class="form-check form-check-dark w-50 mb-2" for="chk_{$_oRange}">
									<input class="form-check-input js__option-floor_range" type="checkbox" name="floor_range[]" value="{$_oRange}" id="chk_{$_oRange}" {if $clsISO->checkItemInArray($_oRange,$get_floor_ranges)}checked{/if}>
									<span class="form-check-label text-dark ml-1">{$_oRange}</span>
								</label>
							{/foreach}
						{/if}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="d-flex gap-2 w-100 align-items-center justify-content-center">
					<button data-toggle="ripple" type="button" tp="_modal" onClick="$Core.sop.do_search(this,event)" 
						class="btn flex-fill btn-primary">Tìm kiếm</button>
					<button data-toggle="ripple" type="button" tp="_modal" onClick="$Core.sop.clear_search(this,event)" 
						class="btn flex-fill btn-outline-default">Đặt lại</button>
				</div>
			</div>
		</div>
	</div>
</div>
{else}
<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
	<div class="d-flex flex-wrap gap-2 align-items-center">
		{if !empty($list_sop_type)}
		<div id="js__dropdown-type_group" class="form-group">
			<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
				{foreach name=i from=$list_sop_type item=_oType}
				<input type="radio" onchange="$Core.sop.select_type(this, event)" name="sop_type" 
				class="btn-check js__option-type" id="{$_oType.property_id}" value="{$_oType.property_id}"{if $_oType.property_id eq $get_sop_type_id} checked{/if} toId="js__dropdown-project" tp="radio">
				<label data-toggle="ripple" class="btn btn-outline-default" for="{$_oType.property_id}">{$_oType.title}</label>
				{/foreach}
			</div>
		</div>
		{/if}
		<div class="form-group">
			<div class="input-group input-group-merge">
				 <span class="input-group-text"><i class="bx bx-search"></i></span>
				<input type="text" class="form-control search_field" onChange="$Core.sop.do_search(this, event)" 
					data-field="keyword" placeholder="Tìm kiếm..." >
			</div>
		</div>	
		{assign var = gId value = $clsISO->getUniqid()}
		<div class="btn-group js__block-area-list js__block-rs_slider">
			<button type="button" data-toggle="ripple" id="btn{$gId}" data-target="area" class="d-flex align-items-center btn btn-outline-default dropdown-toggle w-px-125 dropdown-toggle_area{if $is_search_area eq '1'} is-active{/if}" data-bs-toggle="dropdown" data-bs-auto-close="outside">
				<span class="select-text-content" text="Diện tích">Diện tích</span>
			</button>
			<div class="dropdown-menu w-px-350" data-popper-placement="bottom-start">
				<div class="px-3 py-2">
					<div class="form-group mb-3">
						<label class="col-form-label">Diện tích</label>
						<div class="slider-container">
							<input type="text" id="area-range" class="slider-range" />
						</div>
					</div>
					<label class="form-label">Hoặc nhập khoảng (đơn vị m2)</label>
					<div class="form-group form-row">
						<div class="col-6">
							<div class="form-floating">
								<input type="text" class="form-control numberonly search_field no-focus" data-field="area_min" name="area_min" placeholder="Giá từ" maxlength="255" readonly value="{$get_area_min}">
								<label for="floatingInput">Diện tích từ(m2)</label>
							  </div>
						</div>
						<div class="col-6">
							<div class="form-floating">
								<input type="text" class="form-control numberonly search_field no-focus" data-field="area_max" name="area_max" placeholder="Giá đến" maxlength="255" readonly value="{$get_area_max}">
								<label for="floatingInput">Diện tích đến(m2)</label>
							  </div>
						</div>
					</div>
				</div>
				<hr class="my-2" />
				{if !empty($lstAreaRange)}
				<div class="overflow-y-auto mh-50">
					{foreach from=$lstAreaRange item=_oItem}
						<div class="dropdown-item item_area item_area_{$_oItem.property_id} w-100 text-black mb-1 cursor-pointer" onClick="$Core.sop.search_sidebar(this,event)" data-id="{$_oItem.property_id}" data-target="area" data-min="{$_oItem.min}" data-max="{$_oItem.max}">{$_oItem.title}m<sup>2</sup></div>
					{/foreach}		
				</div>								
				<hr class="my-2" />
				{/if}
				<div class="d-flex align-items-center p-2 justify-content-between">
					<button type="button" tp="_dropdown" onClick="$Core.sop.clear_search(this, event)" class="btn btn-outline-default">Đặt lại</button>
					<button type="button" tp="_dropdown" onClick="$Core.sop.do_search(this, event)" class="btn btn-primary btn_do_search">Áp dụng</button>
				</div>
			</div>
		</div>
		{assign var = gId value = $clsISO->getUniqid()}
		<div class="btn-group js__block-price-list js__block-rs_slider">
			<button type="button" data-toggle="ripple" id="btn{$gId}" data-target="price" class="d-flex align-items-center btn btn-outline-default dropdown-toggle w-px-125 dropdown-toggle_price{if $is_search_price eq '1'} is-active{/if}" data-bs-toggle="dropdown" data-bs-auto-close="outside">
				<span class="select-text-content" text="Mức giá">Mức giá</span>
			</button>
			<div class="dropdown-menu w-px-350" data-popper-placement="bottom-start">
				<div class="px-3 py-2">
					<div class="form-group mb-3">
						<label class="col-form-label">Mức giá</label>
						<div class="slider-container">
							<input type="text" id="price-range" class="slider-range" />
						</div>
					</div>
					<label class="form-label">Hoặc nhập khoảng (đơn vị VNĐ)</label>
					<div class="form-group form-row">
						<div class="col-6">
							<div class="form-floating">
								<input type="text" class="form-control numberonly search_field price-In no-focus" data-field="price_min" name="price_min" placeholder="Giá từ" maxlength="255" readonly value="{$get_price_min}">
								<label for="floatingInput">Giá từ</label>
							  </div>
						</div>
						<div class="col-6">
							<div class="form-floating">
								<input type="text" class="form-control numberonly search_field price-In no-focus" data-field="price_max" name="price_max" placeholder="Giá đến" maxlength="255" readonly value="{$get_price_max}">
								<label for="floatingInput">Giá đến</label>
							  </div>
						</div>
					</div>
				</div>				
				<hr class="my-2" />
				{if !empty($lstPriceRangeSop)}
				<div class="overflow-y-auto mh-50">
					{foreach from=$lstPriceRangeSop item=_oItem}
						<div class="dropdown-item item_price item_price_{$_oItem.property_id} w-100 text-black mb-1 cursor-pointer" onClick="$Core.sop.search_sidebar(this,event)" data-id="{$_oItem.property_id}" data-target="price" data-min="{$_oItem.min}" data-max="{$_oItem.max}">{$_oItem.title}</div>
					{/foreach}
				</div>				
				<hr class="my-2" />
				{/if}
				<div class="d-flex align-items-center p-2 justify-content-between">
					<button type="button" tp="_dropdown" onClick="$Core.sop.clear_search(this, event)" class="btn btn-outline-default">Đặt lại</button>
					<button type="button" tp="_dropdown" onClick="$Core.sop.do_search(this, event)" class="btn btn-primary btn_do_search">Áp dụng</button>
				</div>
			</div>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-icon btn-outline-default" 
			data-bs-toggle="modal" data-bs-target="#js__search-form-modal">
				<i class="bx bx-filter-alt"></i>
			</button> 		
		</div>
		<div class="modal fade modal_top right js__search-form-modal" id="js__search-form-modal" 
			tabindex="-1" role="dialog" aria-bs-modal="true" aria-bs-hidden="true">
			<div class="modal-dialog modal-dialog-scrollable modal-xs modal-w-450">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title align-items-center fs-4 text-dark">
							<button data-toggle="ripple" type="button" class="btn fs-4 btn-outline-none px-0" 
								data-bs-dismiss="modal"><i class="bx bx-x"></i></button>
							<span>Bộ lọc tìm kiếm</span>
						</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body overflow-y-auto pt-0">
						
						<div id="js__dropdown-project_group" class="form-group mb-3{if $get_sop_type_id eq '0'} d-none{/if} w-100">
							<label class="form-text mb-2 text-dark fs-6">Dự án</label>
							<div class="d-flex gap-2 flex-wrap" id="js__dropdown-project-list">
							{if !empty($list_project)}
								{foreach from=$list_project item = _oProject}
								<label class="we-radio we-radio-search" for="rdo_{$_oProject.project_id}">
									<input type="radio" onChange="$Core.sop.select_project(this, event)" class="js__option-project" toId="js__dropdown-block" tp="radio" id="rdo_{$_oProject.project_id}" name="project_id" value="{$_oProject.project_id}" {if $_oProject.project_id eq $get_project_id }checked{/if}>
									<span class="text-dark">{$_oProject.title}</span>
								</label>
								{/foreach}
							{/if}
							</div>	
						</div>
						<div id="js__dropdown-block_group" class="form-group mb-3{if $get_project_id eq 0} d-none{/if} w-100">
							<label class="form-text mb-2 text-dark fs-6">Phân khu</label>
							<div class="d-flex gap-2 flex-wrap" id="js__dropdown-block-list">
							{if !empty($list_blocks)}
								{foreach from=$list_blocks item = block}
								<label class="we-radio we-radio-search" for="rdo_{$block.property_id}">
									<input type="radio" onChange="$Core.sop.select_block(this, event)" class="js__option-block" toId="js__dropdown-building" tp="radio" id="rdo_{$block.property_id}" name="block_id" value="{$block.property_id}" {if $block.property_id eq $get_block_id }checked{/if}>
									<span class="text-dark">{$block.title}</span>
								</label>
								{/foreach}
							{/if}
							</div>	
						</div>
						<div id="js__dropdown-building_group" class="form-group mb-3{if $get_block_id eq 0} d-none{/if} w-100">
							<label class="form-text mb-2 text-dark fs-6">Tòa/Dãy</label>
							<div class="d-flex gap-2 flex-wrap">
								<div class="d-flex flex-wrap w-100" id="js__dropdown-building-list">
									{if !empty($list_buildings)}
										{foreach name=i from=$list_buildings item=building}
										<label class="form-check form-check-dark w-50 mb-2" for="chk_{$building.property_id}">
											<input class="form-check-input js__option-building" type="checkbox" name="building_id[]" value="{$building.property_id}" id="chk_{$building.property_id}" {if $clsISO->checkItemInArray($building.property_id, $get_building_ids)}checked{/if}>
											<span class="form-check-label ml-1">{$building.title}</span>
										</label>
										{/foreach}
									{/if}
								</div>
							</div>	
						</div>
						{if !empty($list_sop_source)}
						<div class="form-group mb-3 js__block-source-list">
							<label class="form-text mb-2 text-dark fs-6">Nguồn</label>
							<div class="d-flex w-100 gap-2 flex-wrap">
								{foreach from=$list_sop_source item = _oP}
								<label class="we-radio we-radio-search" for="source_{$_oP.property_id}">
									<input type="radio" class="js__option-source" tp="radio" id="source_{$_oP.property_id}" name="agency_id" 
										value="{$_oP.property_id}"{if $_oP.property_id eq $get_source_id } checked{/if}>
									<span class="text-dark">{$_oP.title}</span>
								</label>
								{/foreach}
							</div>
						</div>
						{/if}
						<div class="form-group mb-3 js__block-bedroom-list{if $get_sop_type eq $smarty.const._SOP_TYPE_LOWFLOOR}d-none{/if}">
							<label class="form-text mb-2 text-dark fs-6">Loại căn</label>
							<div class="d-flex w-100 flex-wrap">
								{if !empty($list_bedrooms)}
									{foreach from=$list_bedrooms item = _oP}
									<label class="form-check form-check-dark w-50 mb-2" for="chk_{$_oP.property_id}">
										<input class="form-check-input js__option-bedroom" type="checkbox" value="{$_oP.property_id}" id="chk_{$_oP.property_id}" {if $clsISO->checkItemInArray($_oP.property_id,$get_bedroom_ids)}checked{/if}>
										<span class="form-check-label text-dark ml-1">{$_oP.title}</span>
									</label>
									{/foreach}
								{/if}
							</div>
						</div>
						<div class="form-group js__block-floor-range-list{if $get_sop_type eq $smarty.const._SOP_TYPE_LOWFLOOR} d-none{/if}">
							<label class="form-text mb-2 text-dark fs-6">Khoảng tầng</label>
							<div class="d-flex w-100 flex-wrap">
								{if !empty($list_directions)}
									{foreach from=$list_range_floors item = _oRange}
									<label class="form-check form-check-dark w-50 mb-2" for="chk_{$_oRange}">
										<input class="form-check-input js__option-floor_range" name="floor_range[]" type="checkbox" value="{$_oRange}" id="chk_{$_oRange}" {if $clsISO->checkItemInArray($_oRange,$get_floor_ranges)}checked{/if}>
										<span class="form-check-label text-dark ml-1">{$_oRange}</span>
									</label>
									{/foreach}
								{/if}
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="d-flex gap-2 w-100 align-items-center justify-content-center">
							<button type="button" tp="_modal" onClick="$Core.sop.do_search(this,event)" 
								class="btn flex-fill btn-primary">Tìm kiếm</button>
							<button type="button" tp="_modal" onClick="$Core.sop.clear_search(this,event)" 
								class="btn flex-fill btn-outline-default text-dark border-dark">Đặt lại</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="d-flex align-items-center gap-2">
		<div class="dropdown">
			{assign var = _toId value = $clsISO->getUniqid()}
			<button type="button" data-toggle="ripple" class="btn btn-outline-default dropdown-toggle" data-bs-toggle="dropdown">
				<span id="{$_toId}">{$txt_sort}</span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk active {$_toId}" toId="{$_toId}" title="Mới nhất" onClick="$Core.sop.do_sort(this, event)" holderG="date_desc">{$clsISO->makeIcon('bx-sort-up', 'Mới nhất')}</a></li>
				<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Mới nhất" onClick="$Core.sop.do_sort(this, event)" holderG="date_asc">{$clsISO->makeIcon('bx-sort-down', 'Cũ nhất')}</a></li>
				<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Giá tăng dần" onClick="$Core.sop.do_sort(this, event)" holderG="price_asc">{$clsISO->makeIcon('bx-sort-up', 'Giá tăng dần')}</a></li>
				<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Giá giảm dần" onClick="$Core.sop.do_sort(this, event)" holderG="price_desc">{$clsISO->makeIcon('bx-sort-down', 'Giá giảm dần')}</a></li>
				<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Giá tăng dần" onClick="$Core.sop.do_sort(this, event)" holderG="price_m2_asc">{$clsISO->makeIcon('bx-sort-up', 'Giá m2 tăng dần')}</a></li>
				<li><a href="javascript:void(0);" class="dropdown-item zHXOaDjwDk {$_toId}" toId="{$_toId}" title="Giá giảm dần" onClick="$Core.sop.do_sort(this, event)" holderG="price_m2_desc">{$clsISO->makeIcon('bx-sort-down', 'Giá m2 giảm dần')}</a></li>
			</ul>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.re__icon-sun--sm{ 
		transform: translateY(1px);
		-ms-transform: translateY(1px);
		-moz-transform: translateY(1px);
		-webkit-transform: translateY(1px);
		-khtml-transform: translateY(1px);
	}
	.select-text-content{
		display:inline-block;
		overflow:hidden;
		text-align:left;
		text-overflow:ellipsis;
		width:calc(100% - 10px)
	}
</style>
{/literal}
{/if}