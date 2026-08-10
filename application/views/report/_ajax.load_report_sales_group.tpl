{if !empty($arr_satffs)}

	{foreach name=i from=$arr_satffs name=i key=key item=_oItem}

	{assign var = _more_information value = $_oItem.more_information}

	<tr class="trBilling">

		{if $deviceType eq 'computer'}

		<td class="text-center" rowspan={$_oItem.rowspan}>

			{if $smarty.foreach.i.iteration eq '1'}

			<img src="{$URL_IMAGES}/top-1.png" class="w-px-15" />

			{elseif $smarty.foreach.i.iteration eq '2'}

			<img src="{$URL_IMAGES}/top-2.png" class="w-px-15" />

			{elseif $smarty.foreach.i.iteration eq '3'}

			<img src="{$URL_IMAGES}/top-3.png" class="w-px-15" />

			{else}

				{$smarty.foreach.i.iteration}

			{/if}

		</td>

		{/if}

		<td class="text-left">{$clsProfile->getIndentityV2($_oItem.profile_id, $_oItem, true)}</td>
		<td class="text-center">
			{if !empty($_oItem.total_social)}
				<a class="text-link" data-trigger="hover" style="button" data-width="300" data-url="/index.php?mod=report&act=load_social_profile&user_id={$_oItem.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-width="300">{$_oItem.total_social} link</a>
			{else}
				{$_oItem.total_social} link
			{/if}
		</td>
		<td class="text-left">{$_more_information.department_name}</td>

		<td class="text-center">

			<strong class="text-success">{$_oItem.total_share}</strong> lượt

		</td>

		<td class="text-center">

			<strong class="text-warning">{$_oItem.total_search}</strong> lượt

		</td>

		<td class="text-center">

			<strong class="text-{if !empty($_oItem.total_billings)}main{else}muted{/if}">{$_oItem.total_billings}</strong> GD

		</td>

		<td class="text-right fw-bold text-{if !empty($_oItem.total_sales)}primary{else}muted{/if}">

			{$clsISO->shortNumber($_oItem.total_sales,2)}

		</td>

		<td class="text-center">

			<strong class="text-danger">{$_oItem.total_post}</strong> share

		</td>

		<td class="text-center">

			<strong class="text-info">{$_oItem.total_customer}</strong> KH

		</td>

		<td class="text-center">

			<strong class="text-warning">{$_oItem.total_work_unit}</strong>/26</td>

	</tr>

	{/foreach}

{else}

	<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}5{else}3{/if}">Dữ liệu trống</td></tr>

{/if}