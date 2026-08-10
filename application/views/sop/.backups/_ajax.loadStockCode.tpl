<form class="p-2" onsubmit="return false">
	<div class="form-row">
		<div class="col-6 mb-2">
			<label class="form-text d-none d-lg-block mb-1">Phân khu*</label>
			<select class="form-control form-select search_field no-focus required" onChange="$Core.sop.loadStockCode(this, event)" name="block_id" data-placeholder="Phân khu" data-width="100%" data-allowclear="true" id="slb_Block_Id" toId="slb_Building_Id" data-field="building_id">
				<option value="">Phân khu</option>
				{if !empty($list_blocks)}
					{foreach name=i from=$list_blocks item=block}
					<option{if $clsISO->checkInArray($_ss_blocks_ids, $block.property_id)} selected{/if} value="{$block.property_id}">{$block.title}</option>
					{/foreach}
				{/if}
			</select>
		</div>
		<div class="col-6 mb-2">
			<label class="form-text d-none d-lg-block mb-1">Tòa căn hộ*</label>
			<select class="form-control form-select search_field no-focus required" data-placeholder="Tòa căn hộ" data-width="100%" name="building_id" data-allowclear="true" onChange="$Core.sop.loadStockCode(this,event)" id="slb_Building_Id" data-field="floor_range">
				<option value="">Toà nhà</option>
				{if !empty($list_buildings)}
					{foreach name=i from=$list_buildings item=building}
					<option value="{$building.property_id}">{$building.title}</option>
					{/foreach}
				{/if}
			</select>
		</div>
		<div class="col-6 mb-2">
			<label class="form-text d-none d-lg-block mb-1">Tầng*</label>
			<select class="form-control form-select search_field no-focus required" data-allow-clear="true" data-placeholder="Tầng"  name="floor_range" data-width="100%" data-allowclear="true" data-field="floor_range">
				<option value="">Tầng</option>
				{foreach from=$list_range_floors item = _oRange}
				<option{if $clsISO->checkInArray($_ss_floor_range,$_oRange)} selected{/if} value="{$_oRange}">{$_oRange}</option>
				{/foreach}			
			</select>
		</div>
		<div class="col-6 mb-2">
			<label class="form-text d-none d-lg-block mb-1">Mã căn*</label>
			<input type="text" class="form-control no-focus required" name="stockCode" value="">
		</div>
	</div>		
	<div class="d-flex align-items-center p-2 justify-content-center">
		<button type="button" onclick="$Core.sop.addStockCode(this,event)" field="block" tp="dropdown" class="btn btn-primary">Áp dụng</button>
	</div>
</form>
