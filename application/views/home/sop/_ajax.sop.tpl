{if !empty($list_sop)}
	{foreach from =$list_sop item = _oSop name=i}
	{assign var = sop_id value = $_oSop.sop_id}
	{assign var = stock_code value = $clsSop->getCode($_oSop)}
	{assign var = more_information value = $_oSop.more_information}
	<tr class="awe__sop-item awe__sop-item-{$sop_id} text-nowrap{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'} deleted{/if}">
		{if $deviceType ne 'phone'}
			<td class="text-center">{$smarty.foreach.i.iteration}</td>
		{/if}
		<td class="align-center text-left">
			<a class="fw-bold" href="{$clsSop->getLink($sop_id,$stock_code)}" target="_blank">{$_oSop.stock_code}</a>
		</td>
		<td class="align-center">
			{if !empty($_oSop.bedroom) || !empty($more_information.bedroom_num)}
				{if $_oSop.bedroom}{$_oSop.bedroom}{else}{$more_information.bedroom_num}PN{/if}
			{/if} / 
			{if !empty($more_information.DT_TT)}
				{$more_information.DT_TT}m<sup>2</sup>
			{/if}
		</td>
		<td class="align-center text-left" title="{$clsISO->priceFormat($_oSop.price)}">
			<span class="fw-bold text-main">{$clsISO->shortNumber($_oSop.price_owner,2)}</span>
		</td>
		<td class="align-center text-left" title="{$clsISO->priceFormat($_oSop.price)}">
			<span class="fw-bold text-main">{$clsISO->shortNumber($_oSop.price,2)}</span>
		</td>
		<td class="align-center">{$more_information.contact_name}</td>
		<td class="align-center">{$more_information.contact_phone}</td>
		<td class="align-center">{$clsISO->formatDate($_oSop.reg_date,4)}</td>
		<td class="text-center">
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true"><i class="bx bx-dots-vertical-rounded"></i></button>
				<div class="dropdown-menu" data-popper-placement="bottom-start">
					<a class="dropdown-item" href="{$clsSop->getLinkEdit($sop_id, $PCMS_URL)}" target="_blank" 
					class="btn flex-fill btn-outline-default"><i class="material-icons-outlined">border_color</i> Sửa</a>
					{if isset($more_information.is_locked) && $more_information.is_locked eq '1'}
					<a class="dropdown-item sop__menu-lock-{$sop_id}{if $clsSop->checkSolded($sop_id, $more_information) eq '1' || $clsSop->checkDeleted($sop_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.sop.mark_lock(this, event)" sop_id="{$sop_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock_open</i> Mở Khoá</a>
					{else}
					<a class="dropdown-item sop__menu-lock-{$sop_id}{if $clsSop->checkSolded($sop_id, $more_information) eq '1' || $clsSop->checkDeleted($sop_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.sop.mark_lock(this, event)" sop_id="{$sop_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock</i> Khoá</a>
					{/if}
					{if isset($more_information.is_solded) && $more_information.is_solded eq '1'}
					<a class="dropdown-item sop__menu-sold-{$sop_id}{if $clsSop->checkDeleted($sop_id, $more_information) eq '1'} preventDefault{/if}" sop_id="{$sop_id}" onClick="$Core.sop.mark_sold(this, event)" href="javascript:void(0);">
						<i class="material-icons-outlined">add_business</i> Mở bán</a>
					{else}
					<a class="dropdown-item sop__menu-sold-{$sop_id}{if $clsSop->checkDeleted($sop_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.sop.mark_sold(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
						<i class="material-icons-outlined">storefront</i> Báo bán</a></li>
					{/if}
					{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'}
					<a class="dropdown-item sop__menu-delete-{$sop_id}{if $_oSop.is_locked eq '1'} preventDefault{/if}" onClick="$Core.sop.delete(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
						<i class="material-icons-outlined">settings_backup_restore</i> Khôi phục</a>
					{else}
					<a class="dropdown-item sop__menu-delete-{$sop_id}{if $_oSop.is_locked eq '1'} preventDefault{/if}" onClick="$Core.sop.delete(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
						<i class="material-icons-outlined">delete</i> Xoá</a>
					{/if}
				</div>
			</div>
		</td>
	</tr>
	{/foreach}
{else}
	<tr class="nohover">
		<td class="text-center" colspan="20">
			<div class="">
				<img class="w-px-100" src="{$URL_IMAGES}/listing-empty.svg" />
				<p>Không có kết quả nào phù hợp</p>
			</div>
		</td>
	</tr>
{/if}