<form method="post" action="#" class="p-5">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<div class="d-flex align-items-center gap-2">
			<select class="form-control" required="true" name="project_id" toId="slb_BlockId" onChange="$Core.project.select_block(this, event)" stock_type="{$stock_type}">
				{if !empty($list_projects)}
					{foreach from=$list_projects item = _oProject}
					<option{if $project_id eq $_oProject.project_id} selected{/if} 
						value="{$_oProject.project_id}">{$_oProject.title}</option>
					{/foreach}
				{/if}
			</select>
			<select class="form-control" name="block_id" id="slb_BlockId" stock_type="{$stock_type}" 
				onChange="$Core.project.select_building(this, event)" toId="slb_BuildingId">
				<option value="">Lựa chọn phân khu</option>
				{if !empty($list_blocks)}
					{foreach from=$list_blocks item = _oBlock}
					<option{if $block_id eq $_oBlock.property_id} selected{/if} 
						value="{$_oBlock.property_id}">Phân khu {$_oBlock.title}</option>
					{/foreach}
				{/if}
			</select>
			{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
			<select class="form-control" name="building_id" id="slb_BuildingId" stock_type="{$stock_type}">
				<option value="">Lựa chọn toà</option>
				{if !empty($list_buildings)}
					{foreach from=$list_buildings item = _oBuilding}
					<option{if $building_id eq $_oBuilding.property_id} selected{/if} 
						value="{$_oBuilding.property_id}">Phân khu {$_oBuilding.title}</option>
					{/foreach}
				{/if}
			</select>
			{/if}
			<input type="hidden" name="hid" value="hid" />
			<input type="hidden" name="stock_type" value="{$stock_type}" />
			<button type="submit" title="Tải lại" class="btn btn-icon btn-default">
				{$core->makeIcon('refresh')}
			</button>
		</div>
		<div class="d-flex gap-2 align-items-center">
			<button type="button" onClick="$Core.project.save_stock_code_shapes(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" title="Lưu lại" stock_type="{$stock_type}" holderG="stock_FH" class="btn btn_save_all btn-default">{$core->makeIcon('check', 'Lưu lại')}</button>
			<button type="button" onClick="$Core.project.map_FH.addPoint(this, event)" stock_type="{$stock_type}" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" toId="map" title="Lưu lại" _type="_ADD" class="btn btn-default">{$core->makeIcon('plus', 'Thêm điểm')}</button>
		</div>
	</div>
	<div class="box_tooltip d-none">
		<div class="item_tooltip rounded-3">
			<div class="box_code">C3Z2-08-08A</div>
			<div class="body_tooltip">
				<div class="form-row">
					<div class="col-6 col-xs-6">
						<div class="d-flex flex-column box_text">
							<span class="">Thông thủy</span>
							<span class="text-value">45.5m<sup>2</sup></span>
						</div>
						<div class="d-flex flex-column box_text">
							<span class="">Giá TTS</span>
							<span class="text-price">2,6 tỷ</span>
						</div>
					</div>
					<div class="col-6 col-xs-6">
						<div class="d-flex flex-column box_text">
							<span class="">Hướng</span>
							<span class="text-value">TN</span>
						</div>
						<div class="d-flex flex-column box_text">
							<span class="">Giá vay</span>
							<span class="text-price">3,6 tỷ</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="map" class="map"  style="max-width:1000px; margin: auto;position:relative">
		{if !empty($shapes)}
			{foreach from=$shapes item=shape key=key}
				<div id="item_drag_{$key}" shap_id="{$key}" class="draggable item_drag" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" code="{$shape.code}" top="{$shape.top}" left="{$shape.left}" style="top:{$shape.top}%;left:{$shape.left}%;'">{$shape.code}</div>
			{/foreach}
		{/if}
		<img src="{$image_map_src}" alt="" class="w-100 h-auto">
	</div>
</form>
<script type="text/javascript">
	var block_id = '{$block_id}',
		project_id = '{$project_id}',
		stock_type = '{$stock_type}',
		building_id = '{$building_id}',
		has_block = '{$more_information.has_block}';
</script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
{literal}
<style>
	.item_drag {
		width: 20px;
		height: 20px;
		background: #5f9ea099;
		position: absolute;
		border: 1px solid cadetblue;
		border-radius: 50%;
		font-size: 9px;
		line-height: 20px;
		text-align: center;
		color: #FFF;
		cursor: pointer
	}
</style>
<script>
	$(function(){
		// Lấy kích thước container
		var containerWidth = $("#map").width();
		var containerHeight = $("#map").height();
		$(".draggable").draggable({
		// Giới hạn kéo thả bên trong container
			containment: "#map",
			drag: function(event, ui) {
				// Tính toán tọa độ theo phần trăm dựa trên vị trí hiện tại
				var percentX = (ui.position.left / containerWidth) * 100;
				var percentY = (ui.position.top  / containerHeight) * 100;
				$(this).css({"left":percentX,"top":percentY});
				$(this).attr("left",percentX);
				$(this).attr("top",percentY);
			},
			stop: function(event, ui) {
				// Cập nhật hiển thị tọa độ với 2 chữ số thập phân
				$Core.project.map_FH.update_shape();
			}
		});
	});
</script>
{/literal}