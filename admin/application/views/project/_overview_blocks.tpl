{if !empty($list_blocks)}
<table cellspacing="0" cellpadding="0" class="table table-striped table-vertical" style="width:100%">
	<thead><tr>
		<th class="text-center" width="60px">Loại</th>
		<th class="text-left">Tên phân khu / tòa</th>
		<th class="text-center" width="70px">Hot/Top</th>
		<th class="text-center" width="90px">Quick Menu</th>
		<th class="text-center" width="70px">Menu</th>
		<th class="text-center" width="80px">Hết hàng</th>
		<th class="text-center" width="130px">Cập nhật</th>
		<th class="text-center" width="150px">Thao tác</th>
	</tr></thead>
	<tbody>
		{foreach from=$list_blocks item=_oBlock}
		{assign var = _block_id value = $_oBlock.property_id}
		{assign var = _block_information value = $_oBlock.more_information}
		{assign var = list_buildings value = $_oBlock.list_buildings}
		<tr>
			<td class="text-center"><span class="label label-primary">PK</span></td>
			<td class="text-left"><strong>{$_oBlock.title}</strong></td>
			<td bgcolor="#F5F5F5" class="text-center">
				<label class="switch">
					<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_hot" tp="_block" value="1" for_id="{$_block_id}"{if $_block_information.is_hot eq '1'} checked{/if}>
					<span class="slider round"></span>
				</label>
			</td>
			<td bgcolor="#F5F5F5" class="text-center">
				{if $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE eq $_oBlock.parent_id}
				<label class="switch">
					<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_quick_menu" tp="_block" value="1" for_id="{$_block_id}"{if $_block_information.is_quick_menu eq '1'} checked{/if}>
					<span class="slider round"></span>
				</label>
				{else}--{/if}
			</td>
			<td bgcolor="#F5F5F5" class="text-center">
				<label class="switch">
					<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_menu" tp="_block" value="1" for_id="{$_block_id}"{if $_block_information.is_menu eq '1'} checked{/if}>
					<span class="slider round"></span>
				</label>
			</td>
			<td bgcolor="#F5F5F5" class="text-center">
				<label class="switch">
					<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_out_stock" tp="_block" value="1" for_id="{$_block_id}"{if $_block_information.is_out_stock eq '1'} checked{/if}>
					<span class="slider round"></span>
				</label>
			</td>
			<td class="text-center">{$_oBlock.upd_date|date_format:"%d/%m/%Y %H:%M"}</td>
			<td class="text-center" style="white-space:nowrap;">
				<a class="btn btn-icon btn-default btn_edit_block_{$_block_id}" title="Chỉnh sửa phân khu" href="javascript:void(0);" onClick="open_block(this, event)" block_id="{$_block_id}" project_id="{$project_id}" _openFrom="_project">{$core->makeIcon('pencil')}</a>
				<a class="btn btn-icon btn-default" title="Tiến độ" href="javascript:void(0);" onClick="open_progress(this, event)" block_id="{$_block_id}" project_id="{$project_id}" _openFrom="_project">{$core->makeIcon('tasks')}</a>
				<a class="btn btn-icon btn-default" title="Ảnh căn hộ theo loại (bóc mái / nội thất)" href="javascript:void(0);" onClick="open_interior_ns(this, event)" block_id="{$_block_id}" project_id="{$project_id}">{$core->makeIcon('image')}</a>
			</td>
		</tr>
			{if !empty($list_buildings)}
				{foreach from=$list_buildings item = _oBuiling}
				{assign var = _building_id value = $_oBuiling.property_id}
				{assign var = _more_information value = $_oBuiling.more_information}
				<tr>
					<td class="text-center"><span class="label label-default">{if $_oBlock.parent_id eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}CĂN{else}TÒA{/if}</span></td>
					<td class="text-left" style="padding-left:28px">{$_oBuiling.title}</td>
					<td bgcolor="#F5F5F5" class="text-center">
						<label class="switch">
							<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_top_menu" tp="_building" value="1" for_id="{$_building_id}"{if $_more_information.is_top_menu eq '1'} checked{/if}>
							<span class="slider round"></span>
						</label>
					</td>
					<td bgcolor="#F5F5F5" class="text-center">
						<label class="switch">
							<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_quick_menu" tp="_building" value="1" for_id="{$_building_id}"{if $_more_information.is_quick_menu eq '1'} checked{/if}>
							<span class="slider round"></span>
						</label>
					</td>
					<td bgcolor="#F5F5F5" class="text-center">
						<label class="switch">
							<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_menu" tp="_building" value="1" for_id="{$_building_id}"{if $_more_information.is_menu eq '1'} checked{/if}>
							<span class="slider round"></span>
						</label>
					</td>
					<td bgcolor="#F5F5F5" class="text-center">
						<label class="switch">
							<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_out_stock" tp="_building" value="1" for_id="{$_building_id}"{if $_more_information.is_out_stock eq '1'} checked{/if}>
							<span class="slider round"></span>
						</label>
					</td>
					<td class="text-center">{$_oBuiling.upd_date|date_format:"%d/%m/%Y %H:%M"}</td>
					<td class="text-center" style="white-space:nowrap;">
						<a class="btn btn-icon btn-default btn_edit_building_{$_building_id}" title="Chỉnh sửa tòa" href="javascript:void(0);" onClick="open_building(this, event)" block_id="{$_block_id}" project_id="{$project_id}" building_id="{$_building_id}">{$core->makeIcon('pencil')}</a>
						<a class="btn btn-icon btn-default" title="Tiến độ" href="javascript:void(0);" onClick="open_progress(this, event)" block_id="{$_block_id}" project_id="{$project_id}" building_id="{$_building_id}" _openFrom="_project">{$core->makeIcon('tasks')}</a>
						<a class="btn btn-icon btn-default" title="Ảnh căn hộ theo loại (bóc mái / nội thất)" href="javascript:void(0);" onClick="open_interior_ns(this, event)" block_id="{$_block_id}" project_id="{$project_id}" building_id="{$_building_id}">{$core->makeIcon('image')}</a>
						<a class="btn btn-icon btn-default" title="Quản lý bán hàng" href="{$PCMS_URL}/index.php?mod=stock&project_id={$project_id}&block_id={$_block_id}&building_id={$_building_id}">{$core->makeIcon('shopping-cart')}</a>
					</td>
				</tr>
				{/foreach}
			{/if}
		{/foreach}
	</tbody>
</table>
{else}
<div class="text-center" style="padding:30px 0">
	<img src="{$URL_IMAGES}/listing-empty.svg" width="60px" />
	<p class="text-muted">Chưa có phân khu nào. Bấm "Thêm phân khu" để tạo.</p>
</div>
{/if}
