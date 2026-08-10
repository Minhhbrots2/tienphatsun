{if !empty($listItem)}
	{foreach from=$listItem name = i item = _oI}
	{assign var = _more_information value = $_oI.more_information}
	<tr>
		<td class="text-center">{$_oI.stt}</td>
		<td class="align-center">
			<a href="javascript:void(0)" class="text-link" onClick="$Core.sop.open_stock(this, event)" stock_id="{$_oI.stock_id}" telesale_id="{$_oI.id}" stock_code="{$_oI.stock_code}">{$_oI.stock_code}</a>
		</td>
		<td class="align-center">
			{if $_oI.stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
				{$_oI.type_name}
			{else}
				{$_oI.bedroom_name}
			{/if}
		</td>
		<td class="align-center">
			{$_more_information.contact_name}
		</td>
		<td class="align-center text-right">
			{$clsISO->shortNumber($_oI.price_owner)}
		</td>
		<td class="align-center">
			{$_oI.fee_included_name}
		</td>
		<td class="align-center text-right">
			{$clsISO->shortNumber($_oI.price)}
		</td>
		<td class="align-center text-right">
			{$clsISO->shortNumber($_oI.price_m2)}
		</td>
		<td class="align-center col:finish_status d-none">
			{if !empty($_more_information.finish_status_name)}
				{$_more_information.finish_status_name}
			{else}
				<div class="d-flex w-100 justify-content-center text-muted">---</div>
			{/if}
		</td>
		<td class="align-center text-left">
			{if !empty($_more_information.interior_name)}
				{$_more_information.interior_name}
			{else}
				<div class="d-flex w-100 justify-content-center text-muted">---</div>
			{/if}
		</td>
		<td class="align-center text-left">
			{if !empty($_more_information.juridical_name)}
				{$_more_information.juridical_name}
			{else}
				<div class="d-flex w-100 justify-content-center text-muted">---</div>
			{/if}
		</td>
		<td class="align-center text-left">
			{if !empty($_more_information.status_viewing)}
				{$_more_information.status_viewing}
			{else}
				<div class="d-flex w-100 justify-content-center text-muted">---</div>
			{/if}
		</td>
		<td class="align-center">{$_oI.status_name}</td>
		<td class="align-center">{$clsISO->convertTimeToText($_oI.upd_date)}</td>
	</tr>
	{/foreach}
	{if !empty($list_blanks)}
		{section name=i loop=$list_blanks}
		<tr>
			<td class="text-center">{$smarty.section.i.iteration + $total_items}</td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter col:finish_status d-none"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
			<td class="bg-lighter"></td>
		</tr>
		{/section}
	{/if}
{else}
<tr class="nohover">
	<td class="text-center border-0 p-5" colspan="20">
		<div class="p-3">
			<img src="{$URL_IMAGES}/listing-empty.svg" class="w-px-150" />
			<p class="text-muted">Chưa có dữ liệu</p>
		</div>
	</td>
</tr>
{/if}