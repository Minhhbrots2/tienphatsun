{if !empty($list_stocks)}

	{foreach name=i from=$list_stocks item = _oStock}

	<tr class="iso_search_item">

		{if $deviceType ne 'phone'}

		<td class="text-center">{$smarty.foreach.i.iteration}</td>

		<td class="text-left"><a href="javascript:void(0);" onClick="$Core.helper.open_stock('{$_oStock.stock_id}')">{$_oStock.ms_code}

			{if $deviceType eq 'phone'}{$_oStock.TINH_TRANG}{/if}</a>

		</td>

		{$_oStock.TINH_TRANG}

		{else}

		<td class="text-left">

			<a href="javascript:void(0);" onClick="$Core.helper.open_stock('{$_oStock.stock_id}')" >{$_oStock.ms_code} {$_oStock.TINH_TRANG}</a>

		</td>

		{/if}

		<td class="text-left">{$_oStock.total_price_vat} tỷ</td>

		<td class="text-center">{$_oStock.DT_TT}m<sup>2</sup></td>

		<td class="text-left">{$_oStock.LOAI_CAN}</td>

		{if $deviceType ne 'phone'}

		<td class="text-left">{$_oStock.HUONG_BC}</td>

		<td class="text-center">{$_oStock.PTG_LINK}</td>

		<td class="text-center">{$_oStock.POLICY_LINK}</td>

		<td class="text-center">

			<a onClick="$Core.helper.toggle_wishlist(this,event)" data-bs-toggle="tooltip" title="Loại bỏ" stock_id="{$_oStock.stock_id}" class="btn saved p-1">{$clsISO->makeIcon('bx-heart fs-11')}</a>

		</td>

		{/if}

	</tr>

	{/foreach}

{else}

<tr>

	<td colspan="10" class="text-center">

		<img src="{$URL_IMAGES}/listing-empty.svg" width="120px" />

		<p>Chưa có căn hộ nào trong danh mục yêu thích..</p>

	</td>

</tr>

{/if}