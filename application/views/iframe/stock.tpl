<div class="w-100 overflow-x-auto">
	<table class="table table-stock" width="100%">
		{if !empty($list_blocks)}
			{foreach from=$list_blocks item = _oBlock}
			{assign var = block_id value = $_oBlock.property_id}
			{assign var = list_stocks value = $_oBlock.list_stocks}
			{assign var = list_price_field value = $_oBlock.list_price_field}
			<thead>
				<tr>
					<th class="pheader text-left text-upper" colspan="20">
						<div class="d-flex align-items-center justify-content-between">
							<strong class="fs-6">Quỹ căn độc quyền {$_oBlock.title}</strong>
							<span>Cập nhật: {$clsISO->convertTimeToText($smarty.now, true)}</span>
						</div>
					</th>
				</tr>	
				<tr>
					{if $deviceType eq 'phone'}
					<th width="10%" class="pcell align-center text-center">Mã căn</th>
					<th width="10%" class="pcell align-center text-center">Loại căn</th>
					<th width="10%" class="pcell align-center text-center">DT_TT</th>
					<th class="pcell align-center text-center">Giá VAT</th>
					{else}
					<th class="pcell align-center text-center">STT</th>
					<th class="pcell align-center text-center">Mã căn</th>
					<th class="pcell align-center text-center">Loại căn</th>
					<th class="pcell align-center text-center">Hướng BC</th>
					<th class="pcell align-center text-center">DT_TT</th>
					<th class="pcell align-center text-center">Giá Full VAT</th>
					{if !empty($list_price_field)}
						{foreach from = $list_price_field item = _oField}
						<th class="pcell align-center text-center">{$_oField}</th>
						{/foreach}
					{/if}
					<th class="pcell align-center text-center">Loại hình</th>
					<!-- <th class="pcell align-center text-center">Thưởng sale</th>
					<th class="pcell align-center text-center">Ngày ký TTĐC</th> -->
					<th class="pcell align-center text-center">CSBH ngày</th>
					{/if}
				</tr></thead>
				{if !empty($list_stocks)}
					{foreach name=i from=$list_stocks item = _oStock}
					{assign var = stock_id value = $_oStock.stock_id}
					{assign var = more_information value = $_oStock.more_information}
					<tr>
						{if $deviceType eq 'phone'}
						<td class="text-center"><a href="javascript:;" class="cursor-pointer fw-bold text-link" data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$stock_id}" data-toggle="webui-popover" data-placement="auto" data-trigger="click" data-width="350">{$_oStock.ms_code}</a></td>
						<td class="text-center">{$_oStock.bedroom_name}</td>
						<td class="text-center">{$_oStock.DT_TT}</td>
						<td class="text-center">{$_oStock.total_price_vat}</td>
						{else}
						<td class="text-center">{$smarty.foreach.i.iteration}</td>
						<td class="text-center"><a href="javascript:;" class="cursor-pointer fw-bold text-link" data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$stock_id}" data-toggle="webui-popover" data-placement="auto" data-trigger="click" data-width="350">{$_oStock.ms_code}</a></td>
						<td class="text-center">{$_oStock.bedroom_name}</td>
						<td class="text-center">{$_oStock.home_direction_name}</td>
						<td class="text-center">{$_oStock.DT_TT}</td>
						<td class="text-center">{$_oStock.total_price_vat}</td>
						{if !empty($list_price_field)}
							{foreach from = $list_price_field key = _oField item = _oText}
							<td class="bg-price align-center text-center">
								{if !empty($more_information[$_oField])}
									{$clsISO->priceFormatV2($more_information[$_oField],3)}
								{else}
									-
								{/if}
							</td>
							{/foreach}
						{/if}
						<td class="text-center">{$_oStock.type_name}</td>
						<!-- <td class="text-center">{$more_information.sale_bonus}</td>
						<td class="text-center">{$more_information.date_deposit_sign}</td> -->
						<td class="text-center">{$_oStock.csbh}</td>
						<!-- <td class="text-center">{$_oStock.html_sales_policy}</td> -->
						{/if}
					</tr>
					{/foreach}
				{/if}
			{/foreach}
		{/if}
	</table>
</div>
{literal}
<style type="text/css">
	body{
		background: transparent;
	}
	.text-link{
		color:rgb(255 249 204) !important	
	}
	.table-stock th{
		color:#000 !important;
		border:1px solid #7c3c10 !important;
	}
	.table-stock th.pheader{
		padding: 15px;
		background: #eaddb0;
		color: #7c3c10 !important;
	}
	.table-stock tr:hover td{
		color:#7c3c10 !important
	}
	.table-stock tr:hover .text-link{
		color:#7c3c10 !important
	}
	.table-stock td{
		color:#f1ecc4;
		background: #7c3c10;
		padding: 0.325rem 0.625rem;
		border: 1px solid #fff0c4;
	}
	.table-stock th.pcell{
		color: #9a7241 !important;
		background: #eaddb0 !important;
	}
	.table-stock td.bg-price{
		background: #7c3c10;
	}
	@media screen and (max-width:575px){
		.table-stock td{
			font-size:10px;
			padding:0.325rem;
		}
	}
</style>
{/literal}
<script type="text/javascript" src="{$URL_JS}/iframeResizer.contentWindow.min.js?v={$upd_version}"></script>