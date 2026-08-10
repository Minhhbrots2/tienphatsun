<div class="form-row om-sm:mb-2">
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
		<label class="form-text d-none d-lg-block mb-1">Dự án</label>
		<select class="form-control search_field multiselect" onChange="$Core.tool.select_block(this, event)" name="project_id" 
		id="slb_Project_Id" toId="slb_Block_Id" data-width="100%" data-field="project_id" stock_type="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}">
			<option value="0">Chọn dự án</option>
			{foreach name=i from=$list_projects item=project}
			<option{if $project.project_id eq $_ss_project_id} selected{/if} value="{$project.project_id}">{$project.title}</option>
			{/foreach}
		</select>
	</div>
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
		<label class="form-text d-none d-lg-block mb-1">Phân khu</label>
		<select class="form-control search_field multiselect" onChange="$Core.tool.select_building(this, event)" 
		data-placeholder="Phân khu" data-width="100%" data-header="true" data-filter="true" multiple id="slb_Block_Id" 
		toId="slb_Building_Id" data-field="blocks_ids[]">
			{if !empty($list_blocks)}
				{foreach name=i from=$list_blocks item=block}
				<option{if $clsISO->checkInArray($_ss_blocks_ids, $block.property_id)} selected{/if} value="{$block.property_id}">{$block.title}</option>
				{/foreach}
			{/if}
		</select>
	</div>
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
		<label class="form-text d-none d-lg-block mb-1">Tòa căn hộ</label>
		<select class="form-control search_field multiselect" data-placeholder="Tòa căn hộ" data-width="100%" data-header="true" data-filter="true" onChange="$Core.tool.loadFundType(this,event)" toId="fund_type_search" multiple id="slb_Building_Id" data-field="building_ids[]">
			{if !empty($_ss_blocks_ids)}
				{foreach from=$list_ss_buildings key = block_id item = list_buildings}
				<optgroup label="{$clsProperty->getTitle($block_id)}">
					{if !empty($list_buildings)}
						{foreach name=i from=$list_buildings item=building}
						<option{if $clsISO->checkInArray($_ss_building_ids,$building.property_id)} selected{/if} value="{$building.property_id}">{$building.title}</option>
						{/foreach}
					{/if}
				</optgroup>
				{/foreach}
			{else}
				{if !empty($list_buildings)}
					{foreach name=i from=$list_buildings item=building}
					<option value="{$building.property_id}">{$building.title}</option>
					{/foreach}
				{/if}
			{/if}
		</select>
	</div>
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1 {if empty($is_fund_type)}d-none{/if}" id="fund_type_search">
		<label class="form-text d-none d-lg-block mb-1">Loại quỹ</label>
		<select class="form-control search_field multiselect" onChange="$Core.tool.do_search()" name="fund_type[]" multiple data-placeholder="Loại quỹ" 
		 data-width="100%" data-field="fund_type">
			<option value="0">Sơ cấp</option>
			<option value="1">Thứ cấp</option>
		</select>
	</div>
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
		<label class="form-text d-none d-lg-block mb-1">Loại căn</label>
		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Loại căn" data-width="100%" data-allowclear="true" onChange="$Core.tool.do_search()" multiple data-field="bedroom_ids[]">
			{$clsProperty->getSelectByPropertyV2('_BEDROOM', $_ss_bedroom_ids)}
		</select>
	</div>
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
		<label class="form-text d-none d-lg-block mb-1">Khoảng tầng</label>
		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Khoảng tầng" data-width="100%" data-allowclear="true" onChange="$Core.tool.do_search()" multiple data-field="floor_range[]">
			{foreach from=$list_range_floors item = _oRange}
			<option{if $clsISO->checkInArray($_ss_floor_range,$_oRange)} selected{/if} value="{$_oRange}">{$_oRange}</option>
			{/foreach}
		</select>
	</div>
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
		<label class="form-text d-none d-lg-block mb-1">Hướng BC</label>
		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Hướng BC" data-width="100%" data-allowclear="true" multiple onChange="$Core.tool.do_search()" data-field="direction_ids[]">
			{$clsProperty->getSelectByPropertyV2('_DIRECTION',$_ss_direction_ids)}
		</select>
	</div>
	{if ($clsISO->checkPermissStock() || $profile_id eq 289 || $profile_id eq 1124) && $mod ne 'stock'}
	<div class="col-6 col-lg-6 col-xxxl-1/10 flex-fill om-xs:mb-1">
		<label class="form-text d-none d-lg-block mb-1">Đại lý</label>
		<select class="form-control search_field multiselect_info" data-header="true" data-filter="true" data-placeholder="Đại lý" 
		data-width="100%" onChange="$Core.tool.do_search()" multiple data-field="agency_ids[]" data-width="320" data-field_name="agency_id" data-url="/index.php?mod=ajax&sub=helper&act=load_info_agency">
			{foreach from=$lstAgency item=_oItem key=key name=i}
				<option value="{$_oItem.property_id}" data-info="{$_oItem.is_info}" >{$_oItem.title}</option>
			{/foreach}
		</select>
	</div>
	{else}
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
		<label class="form-text d-none d-lg-block mb-1">Loại hình</label>
		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Loại hình" data-width="100%" data-allowclear="true" multiple onChange="$Core.tool.do_search()" data-field="type_ids[]">
			{$clsProperty->getSelectByPropertyV2('_TYPE',$_ss_type_ids)}
		</select>
	</div>
	{/if}
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill">
		<label class="form-text d-none d-lg-block mb-1">Trục căn</label>
		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Trục căn" data-width="100%" data-allowclear="true" multiple onChange="$Core.tool.do_search()" data-field="axis_ids[]">
			{foreach from=$list_range_axis item = axis}
			<option{if $clsISO->checkInArray($_ss_axis_ids, $axis)} selected{/if} value="{$axis}">{$axis}</option>
			{/foreach}
		</select>
	</div>
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill">
		<label class="form-text d-none d-lg-block mb-1">Khoảng giá (tỷ)</label>
		<div class="btn-group w-full js__block-price-list">
			<button type="button" data-toggle="ripple" class="multiselect js__block-price-drowndown btn btn-outline-default w-100 hide-arrow dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
				<span class="multiselect-selected-text" text="Khoảng giá">Khoảng giá</span>
				<b class="caret"></b>
			</button>
			<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-start">
				<div class="px-2 py-2">
					<div class="form-group mb-3">
						<label class="col-form-label">Mức giá</label>
						<div class="slider-container">
							<div id="price-range"></div>
						</div>
					</div>
					<label class="form-label">Hoặc nhập khoảng (đơn vị VNĐ)</label>
					<div class="form-group form-row">
						<div class="col-6">
							<div class="form-floating">
								<input type="text" class="form-control numberonly search_field price-In no-focus" data-field="price_min" name="price_min" placeholder="Giá từ" maxlength="255" readonly value="{$_ss_price_min}">
								<label for="floatingInput">Giá từ</label>
							  </div>
						</div>
						<div class="col-6">
							<div class="form-floating">
								<input type="text" class="form-control numberonly search_field price-In no-focus" data-field="price_max" name="price_max" placeholder="Giá đến" maxlength="255" readonly value="{$_ss_price_max}">
								<label for="floatingInput">Giá đến</label>
							  </div>
						</div>
					</div>
				</div>
				<hr class="my-2" />
				<div class="d-flex align-items-center p-2 justify-content-between">
					<button type="button" onClick="$Core.tool.clear_search(this, event)" class="btn btn-outline-default" data-field="price">Đặt lại</button>
					<button type="button" onClick="$Core.tool.start_search(this, event)" class="btn btn-primary" data-field="price">Áp dụng</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill">
		<label class="form-text d-none d-lg-block mb-1">Diện tích (m<sup>2</sup>)</label>
		<div class="btn-group w-full js__block-area-list">
			<button type="button" data-toggle="ripple" class="multiselect js__block-area-drowndown btn btn-outline-default w-100 hide-arrow dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
				<span class="multiselect-selected-text" text="Diện tích">Diện tích</span>
				<b class="caret"></b>
			</button>
			<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-start">
				<div class="px-2 py-2">
					<div class="form-group mb-3">
						<label class="col-form-label">Diện tích</label>
						<div class="slider-container">
							<div id="area-range"></div>
						</div>
					</div>
					<label class="form-label">Hoặc nhập khoảng (đơn vị m<sup>2</sup>)</label>
					<div class="form-group form-row">
						<div class="col-6">
							<div class="form-floating">
								<input type="text" class="form-control numberonly search_field price-In no-focus" data-field="area_min" name="area_min" placeholder="Diện tích từ" maxlength="255" readonly value="{$_ss_area_min}">
								<label for="floatingInput">Diện tích từ</label>
							  </div>
						</div>
						<div class="col-6">
							<div class="form-floating">
								<input type="text" class="form-control numberonly search_field price-In no-focus" data-field="area_max" name="area_max" placeholder="Diện tích đến" maxlength="255" readonly value="{$_ss_area_max}">
								<label for="floatingInput">Diện tích đến</label>
							  </div>
						</div>
					</div>
				</div>
				<hr class="my-2" />
				<div class="d-flex align-items-center p-2 justify-content-between">
					<button type="button" onClick="$Core.tool.clear_search(this, event)" class="btn btn-outline-default" data-field="area">Đặt lại</button>
					<button type="button" onClick="$Core.tool.start_search(this, event)" class="btn btn-primary" data-field="area">Áp dụng</button>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.btn-outline-default{
		background:var(--bs-white);
	}
	.multiselect_info .multiselect-container{
		min-width: 300px;
	}
	.multiselect-container label {
		pointer-events: auto !important;
	}
	@media screen and (min-width:1400px){
		.col-xxxl-1\/10{
			width:10%;
		}
	}
</style>
<script type="text/javascript">
	var _rsSlider;
	$('.js__block-price-drowndown').on('shown.bs.dropdown', () => {
		var _min_price = $('input[name=price_min]').val(),
			_max_price = $('input[name=price_max]').val(),
			_min = $Core.util.toNumber(_min_price)/1000000000,
			_max = $Core.util.toNumber(_max_price)/1000000000;
		$("#price-range").slider({
			min: 0,
			max: 15,
			range: true,
			values: [parseInt(_min), parseInt(_max)],
			slide: function( event, ui ) {
				var _min = ui.values[0],
					_max = ui.values[1],
					_min_price =  $Core.chart.formatPrice(_min*1000000000),
					_max_price =  $Core.chart.formatPrice(_max*1000000000);
				$('input[name=price_min]').val(_min_price);
				$('input[name=price_max]').val(_max_price);
			}
		});	
	}).on('hidden.bs.dropdown', () => {
		$("#price-range").slider("destroy");
	});
	$('.js__block-area-drowndown').on('shown.bs.dropdown', () => {
		var _min_area = $('input[name=area_min]').val(),
			_max_area = $('input[name=area_max]').val(),
			_min = $Core.util.toNumber(_min_area),
			_max = $Core.util.toNumber(_max_area);
		$("#area-range").slider({
			min: 0,
			max: 500,
			range: true,
			values: [parseInt(_min), parseInt(_max)],
			slide: function( event, ui ) {
				var _min = ui.values[0],
					_max = ui.values[1],
					_min_area =  $Core.chart.formatPrice(_min),
					_max_area =  $Core.chart.formatPrice(_max);
				$('input[name=area_min]').val(_min_area);
				$('input[name=area_max]').val(_max_area);
			}
		});	
	}).on('hidden.bs.dropdown', () => {
		$("#area-range").slider("destroy");
	});
</script>
{/literal}