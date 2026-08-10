{foreach from=$arr_type item=_oItem key=type_id name=i}
	{assign var=lst_area value=$_oItem.lst_area}
	<tr class="nohover">
		<td class="text-center th_range  fs-14 fw-bold bg-white h-px-30" rowspan="{$lst_area|@count + 1}">{$_oItem.type_name}</td>
	</tr>
	{foreach from=$lst_area item=_oArea key=DT_TT name=i}
		<tr class="nohover">
			<td class="text-nowrap h-px-30 text-center">{if !empty($DT_TT)}<span class="cursor-pointer text-link" onClick="$Core.report.load_pop_stock(this,event)" DT_TT="{$DT_TT}" type_id="{$type_id}">{$DT_TT}m<sup>2</sup></span> {else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center" style="background: #cbffcb">{if !empty($_oArea.tts_min)}{$clsISO->shortNumber($_oArea.tts_min/$DT_TT)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center" style="background: #cbffcb">{if !empty($_oArea.bank_min)}{$clsISO->shortNumber($_oArea.bank_min/$DT_TT)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center">{if !empty($_oArea.price_min)}{$clsISO->shortNumber($_oArea.price_min)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center">{if !empty($_oArea.tts_min)}{$clsISO->shortNumber($_oArea.tts_min)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center">{if !empty($_oArea.tttd_min)}{$clsISO->shortNumber($_oArea.tttd_min)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center">{if !empty($_oArea.bank_min)}{$clsISO->shortNumber($_oArea.bank_min)}{else}--{/if}</td>
			
			<td class="text-nowrap h-px-30 text-center" style="background: #fff4f6">{if !empty($_oArea.tts_max)}{$clsISO->shortNumber($_oArea.tts_max/$DT_TT)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center" style="background: #fff4f6">{if !empty($_oArea.bank_max)}{$clsISO->shortNumber($_oArea.bank_max/$DT_TT)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center">{if !empty($_oArea.price_max)}{$clsISO->shortNumber($_oArea.price_max)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center">{if !empty($_oArea.tts_max)}{$clsISO->shortNumber($_oArea.tts_max)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center">{if !empty($_oArea.tttd_max)}{$clsISO->shortNumber($_oArea.tttd_max)}{else}--{/if}</td>
			<td class="text-nowrap h-px-30 text-center">{if !empty($_oArea.bank_max)}{$clsISO->shortNumber($_oArea.bank_max)}{else}--{/if}</td>
		</tr>
	{/foreach}
	
{/foreach}