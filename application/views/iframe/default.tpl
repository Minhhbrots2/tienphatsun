{literal}
<style type="text/css">
	.freeze-table{user-select:none;-moz-user-select:none;-khtml-user-select:none;
	-webkit-user-select:none;-o-user-select:none;overflow-x:auto;-webkit-overflow-scrolling:touch}
	.table-stock{overflow:hidden;margin-bottom:0}
	.table-stock .cell{position:relative;background:#287e3f!important;color:rgba(255,255,255,1)!important}
	.table-tooltip th,.table-tooltip td{padding:.325rem .625rem}
	@media screen and (min-width:1366px) {
		.table-stock th,.table-stock td{font-size:10px;padding:0 .325rem}
	}
	@media screen and (max-width:1366px) {
		.table-stock th,.table-stock td{font-size:8px;padding:.125rem .2rem}
	}
</style>
{/literal}
<div class="freeze-table dragscroll text-nowrap">
	<table class="table table-stock table-bordered"{if $deviceType ne 'phone'} style="table-layout:fixed"{/if}>
		<thead>
			<tr>
				<th class="text-center" colspan="2" width="110px">
					<strong class="fs-18">{$oneBuilding.property_code}</strong>
				</th>
				{foreach name=i from=$list_status item = _oProp}
				{if $smarty.const._STOCK_STATUS_GENERAL_ID eq $_oProp.property_id}
					{assign var = text_color value = 'danger'}
				{else}
					{assign var = text_color value = 'white'}
				{/if}
				<th colspan="2" class="text-center" style="background:{$_oProp.bgcolor}">
					<span class="text-{$text_color}">{$_oProp.title}</span>
				</th>
				{/foreach}
				<th class="text-center" colspan="{$total_rowspan-1}">
					{if !empty($more_information.title_ts)}
						{$more_information.title_ts}
					{else}
						Note: Giá đã bao gồm VAT & KPBT
					{/if}
				</th>
			</tr>
			<tr>
				<th class="cell">T/C</th>
				{foreach name=k from=$arr_stocks item = _code}
				{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
				<th width="60px" class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$_code}</th>
				{/foreach}
			</tr>
			<tr>
				<th class="cell">DT.Tim</th>
				{foreach name=k from=$arr_stocks item = _code}
				{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
				<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_Tim')}</th>
				{/foreach}
			</tr>
			<tr>
				<th class="cell">DT.TT</th>
				{foreach name=k from=$arr_stocks item = _code}
				{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
				<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_TT')}</th>
				{/foreach}
			</tr>
			<tr>
				<th class="cell">H.BC</th>
				{foreach name=k from=$arr_stocks item = _code}
				{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
				<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'home_direction_id')} </th>
				{/foreach}
			</tr>
			<tr>
				<th class="cell">L. Căn</th>
				{foreach name=k from=$arr_stocks item = _code}
				{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
				<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'bedroom_id')}</th>
				{/foreach}
			</tr>
			<tr>
				<th class="cell">View</th>
				{foreach name=k from=$arr_stocks item = _code}
				{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
				<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code,$arr_cols,'view_id',true)}</th>
				{/foreach}
			</tr>
		</thead>
		<tbody>
			{foreach name=i from=$arr_floors item = _floor}
			<tr>
				<th class="text-center" style="background:#ffe59a">{$_floor}</th>
				{foreach name=k from=$arr_stocks item = _code}
					{assign var = _stock_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'stock_id')}
					{assign var = _status_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'status_id')}
					{assign var = _total_price_vat value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'total_price_vat')}
					{if $_stock_id gt 0}
						{if $_status_id gt '0'}
							{assign var = _textcolor value = $status_textcolor_arrs.$_status_id}
							<td class="text-center" style="cursor:pointer; background:{$status_bgcolor_arrs.$_status_id}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}{else} data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-placement="auto" data-trigger="click" data-width="350"{/if}><a href="javascript:void(0)" style="color:{$_textcolor}">
								{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}
									<!-- Sold -->	
								{else}
									{if !empty($_total_price_vat)}{$_total_price_vat}{else}{/if}
								{/if}
							</a></td>
						{else}
							{if !empty($_total_price_vat)}
								{assign var = _bgcolor value = '#FFF'}
							{else}
								{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
								{assign var = _bgcolor value = $status_bgcolor_arrs.$_status_id}
								{assign var = _textcolor value = $status_textcolor_arrs.$_status_id}
							{/if}
							<td style="cursor:pointer; background:{$_bgcolor}" data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="350" class="text-center"><a href="javascript:void(0)" style="color:{$_textcolor}">{if !empty($_total_price_vat)}{$_total_price_vat}{else}{/if}</a></td>
						{/if}
					{else}
						{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
						<td style="background:{$status_bgcolor_arrs.$_status_id};" title="Không tồn tại trong CSDL"></td>
					{/if}
				{/foreach}
			</tr>
			{/foreach}
		</tbody>
	</table>
	{if !empty($onePolicy)}
	<div class="py-2 d-flex align-items-center justify-content-center">
		<a href="{$onePolicy.link_ns}" class="btn btn-outline-default mr-2" target="_blank">{$clsISO->makeIcon('bx-check-shield','Chính sách bán hàng')} {$oneBuilding.title}</a> 
		<a href="{$onePolicy.link_ms}" class="btn btn-outline-default" target="_blank">{$clsISO->makeIcon('bx-spreadsheet','Phiếu tính giá')} {$oneBuilding.title}</a>
	</div>
	{/if}
	{if !empty($list_help_links)}
	<div class="d-flex py-2 flex-wrap align-items-center justify-content-center">
		{foreach from=$list_help_links item = link}
		<a class="mx-1" target="_blank" data-bs-toggle="tooltip" title="{$link.title}" href="{$link.link}">&bull; {$link.title} {$oneBuilding.title}</a>
		{/foreach}
	</div>
	{/if}
</div>
<script type="text/javascript" src="{$URL_JS}/iframeResizer.contentWindow.min.js?v={$upd_version}"></script>