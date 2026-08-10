{if !empty($list_leasing)}
	{foreach from =$list_leasing item = _oLeasing}
		{assign var = more_information value = $_oLeasing.more_information}
		{assign var = stock_code value = $clsLeasing->getCode($_oLeasing)}
		<tr class="awe__leasing-item awe__leasing-item-{$_oLeasing.leasing_id} text-nowrap{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'} deleted{/if}">
			<td class="text-left">
				<a class="fw-bold" href="{$clsLeasing->getLink($_oLeasing.leasing_id,$stock_code)}" target="_blank" >
					{if $type_list eq 'publish'}
						{$clsLeasing->getCode($_oLeasing)}
					{else}
						{$_oLeasing.stock_code}
					{/if}
				 </a>
				<span class="leasing__icon-{$_oLeasing.leasing_id}">{$clsLeasing->getIcon($_oLeasing.leasing_id,$type_list)}</span>
			</td>
			{if $deviceType eq 'phone'}
			<td>
				{if !empty($_oLeasing.bedroom) || !empty($more_information.bedroom_num)}
					{if $_oLeasing.bedroom}{$_oLeasing.bedroom}{else}{$more_information.bedroom_num}PN{/if}
				{else}
					-
				{/if}
				{if !empty($more_information.DT_TT)}
					/{$more_information.DT_TT}<span class="text-muted">m<sup>2</sup></span>
				{else}
					-
				{/if}
			</td>
			<td>
				{if !empty($_oLeasing.price)}
					<strong class="fw-bold text-main">{$clsISO->formatPriceV2($_oLeasing.price,3)} triệu</strong>
				{else}
					Check
				{/if}
			</td>
			{else}
				<td class="text-left">
					<span class="d-none d-xxl-block fw-bold text-main">
						{$clsISO->priceFormat($_oLeasing.price)}đ
					</span>
					<span class="d-none d-md-block d-xxl-none fw-bold text-main">
						{$clsISO->formatPriceV2($_oLeasing.price,3)} triệu
					</span>
				</td>
				<td>
					{if !empty($_oLeasing.bedroom) || !empty($more_information.bedroom_num)}
						{if $_oLeasing.bedroom}{$_oLeasing.bedroom}{else}{$more_information.bedroom_num}PN{/if}
					{/if} / 
					{if !empty($more_information.DT_TT)}
						{$more_information.DT_TT}m<sup>2</sup>
					{/if}
				</td>
				<td>
					{if !empty($_oLeasing.home_direction)}
						{$_oLeasing.home_direction}
					{else}
						-
					{/if}
				</td>
				<td>{$clsLeasing->getRangeFloor($_oLeasing.floor)}</td>
				<td>{$_oLeasing.contact_name}</td>
				<td>{$_oLeasing.contact_phone}</td>
			{/if}
			<td class="text-center">
				<label class="switch">
				  <input type="checkbox" onChange="$Core.leasing.verified(this, event)" 
					leasing_id="{$_oLeasing.leasing_id}"{if $_oLeasing.is_verified eq '1'} checked="checked"{/if} value="1" />
				  <span class="slider round"></span>
				</label>
			</td>
			<td class="text-center">
				<label class="switch">
				  <input type="checkbox" onChange="$Core.leasing.approved(this, event)" leasing_id="{$_oLeasing.leasing_id}" {if $_oLeasing.is_online eq '1'} checked="checked"{/if} value="1" />
				  <span class="slider round"></span>
				</label>
			</td>
			<td class="text-center">
				<div class="dropdown">
					<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true" {$_oLeasing.is_solded}>
						<i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu" data-popper-placement="bottom-start">
						<a class="dropdown-item" href="{$clsLeasing->getLinkEdit($_oLeasing.leasing_id)}" target="_blank" 
						class="btn flex-fill btn-outline-default"><i class="material-icons-outlined">border_color</i> Sửa</a>
						{if isset($more_information.is_locked) && $more_information.is_locked eq '1'}
						<a class="dropdown-item leasing__menu-lock-{$_oLeasing.leasing_id}{if $clsLeasing->checkSolded($_oLeasing.leasing_id, $more_information) eq '1' || $clsLeasing->checkDeleted($_oLeasing.leasing_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.leasing.mark_lock(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock_open</i> Mở Khoá</a>
						{else}
						<a class="dropdown-item leasing__menu-lock-{$_oLeasing.leasing_id}{if $clsLeasing->checkSolded($_oLeasing.leasing_id, $more_information) eq '1' || $clsLeasing->checkDeleted($_oLeasing.leasing_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.leasing.mark_lock(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock</i> Khoá</a>
						{/if}
						{if isset($more_information.is_solded) && $more_information.is_solded eq '1'}
						<a class="dropdown-item leasing__menu-sold-{$_oLeasing.leasing_id}{if $clsLeasing->checkDeleted($_oLeasing.leasing_id, $more_information) eq '1'} preventDefault{/if}" leasing_id="{$_oLeasing.leasing_id}" onClick="$Core.leasing.mark_sold(this, event)" href="javascript:void(0);">
							<i class="material-icons-outlined">add_business</i> Mở cho thuê</a>
						{else}
						<a class="dropdown-item leasing__menu-sold-{$_oLeasing.leasing_id}{if $clsLeasing->checkDeleted($_oLeasing.leasing_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.leasing.mark_sold(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);">
							<i class="material-icons-outlined">storefront</i> Báo cho thuê</a></li>
						{/if}
						{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'}
						<a class="dropdown-item leasing__menu-delete-{$_oLeasing.leasing_id}{if $_oLeasing.is_locked eq '1'} preventDefault{/if}" onClick="$Core.leasing.delete(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);">
							<i class="material-icons-outlined">delete</i> Khôi phục</a>
						{else}
						<a class="dropdown-item leasing__menu-delete-{$_oLeasing.leasing_id}{if $_oLeasing.is_locked eq '1'} preventDefault{/if}" onClick="$Core.leasing.delete(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);">
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
				<img src="{$URL_IMAGES}/listing-empty.svg" />
				<p>Không có kết quả nào phù hợp</p>
			</div>
		</td>
	</tr>
{/if}