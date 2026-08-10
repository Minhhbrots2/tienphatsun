{if !empty($lstCatFee)}
	{foreach from=$lstCatFee item = _oItem name=i}
		{assign var = lstChild value = $_oItem.lstChild}
		{foreach from=$lstChild item=_oChild name=chl}
			<tr>
				{if $smarty.foreach.chl.first}
				<td class="text-left" rowspan="{$_oItem.rowspan}">
					{$_oItem.group_name}
				</td>
				{/if}
				<td class="text-left">
					{$_oChild.cat_name}
				</td>
				<td class="text-center">
					{$_oChild.total_amount}
				</td>
			</tr>
		{/foreach}
	{/foreach}
{else}
	<tr class="nohover">
		<td colspan="3" class="text-center">
			<div class="py-3">
				<img src="{$URL_IMAGES}/table-no-data.png" width="150" />
				<p class="my-2 text-muted">Không có dữ liệu</p>
			</div>
		</td>
	</tr>
{/if}