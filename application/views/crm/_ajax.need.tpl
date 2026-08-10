{if !empty($lstItem)}
	{foreach name=i from=$lstItem item = _oItem key=key}
	<tr class="text-nowrap bg-white trProject_{$potential_id}">
		{if $deviceType ne 'phone'}
		<td class="text-center" class="text-center">{$smarty.foreach.i.iteration}</td>{/if}
		<td class="text-left"><strong>{$_oItem.title}</strong>{if !empty($_oItem.is_hot)}<span class="badge badge-demo bg-label-danger ms-auto">HOT</span>{/if}</td>
		<td class="text-left">{$_oItem.block_type}</td>
		<td class="text-left">{$_oItem.purpose}</td>
		<td class="text-left">{$_oItem.price_range}</td>
		<td class="text-left">
			<ul class="mb-0 list-unstyled lh-xs">
				{if !empty($_oItem.floor_range)}
					<li class="fs-12 my-0 text-dark"><span class="text-muted">Tầng:</span> {$_oItem.floor_range}</li>
				{/if}
				{if !empty($_oItem.direction)}
					<li class="fs-12 my-0 text-dark"><span class="text-muted">Hướng:</span> {$_oItem.direction}</li>
				{/if}
				{if !empty($_oItem.juridical)}
					<li class="fs-12 my-0 text-dark"><span class="text-muted">Pháp lý:</span> {$_oItem.juridical}</li>
				{/if}
				{if !empty($_oItem.interior_type)}
					<li class="fs-12 my-0 text-dark"><span class="text-muted">Nội thất:</span> {$_oItem.interior_type}</li>
				{/if}
				{if !empty($_oItem.bedroom)}
					<li class="fs-12 my-0 text-dark"><span class="text-muted">Phòng ngủ:</span> {$_oItem.bedroom}</li>
				{/if}
				{if !empty($_oItem.bathroom)}
					<li class="fs-12 my-0 text-dark"><span class="text-muted">Phòng tắm:</span> {$_oItem.bathroom}</li>
				{/if}
			</ul>
		</td>
		<td class="text-left">{$_oItem.content}</td>
		<td class="text-center">
			<div class="d-flex align-items-center gap-1">
				<a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.open_need(this, event)" customer_id="{$customer_id}" need_id="{$key}">{$clsISO->makeIcon('bx-pencil')}</a>
				<a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.delete_need(this, event)" customer_id="{$customer_id}" need_id="{$key}">{$clsISO->makeIcon('bx-trash')}</a>
			</div>
		</td>
	</tr>
	{/foreach}
{else}
	<tr>
		<td colspan="{if $deviceType ne 'phone'}8{else}7{/if}" class="text-center">
			Chưa có thông tin nhu cầu
		</td>
	</tr>
{/if}