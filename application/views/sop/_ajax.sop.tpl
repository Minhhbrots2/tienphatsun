<thead><tr>
	<th width="6%" class="align-center bg-lighter">Mã căn</th>
	<th class="align-center h-px-35 bg-lighter">Loại căn</th>
	{if $sop_type eq $smarty.const._SOP_TYPE_LOWFLOOR}
	<th class="align-center h-px-35 bg-lighter">Hướng nhà</th>
	{else}
	<th class="align-center h-px-35 bg-lighter">Hướng BC</th>
	<th class="align-center h-px-35 bg-lighter">khoảng tầng</th>
	{/if}
	<th class="align-center h-px-35 bg-lighter">Giá chủ</th>
	<th class="align-center h-px-35 bg-lighter">Giá bán</th>
	<th class="align-center h-px-35 bg-lighter">Giá/m2</th>
	<th class="align-center h-px-35 text-left bg-lighter">Người liên hệ</th>
	<th class="align-center h-px-35 text-left bg-lighter">Điện thoại</th>
	<th class="align-center h-px-35 text-left bg-lighter">Ngày đăng</th>
	<th class="align-center h-px-35 text-center bg-lighter w-px-75">T.Trạng</th>
	{if $clsISO->checkPermission('view_sop_source')}
	<th class="align-center h-px-35 text-center bg-lighter w-px-75">Nguồn</th>
	{/if}
	<th class="align-center h-px-35 text-center bg-lighter w-px-75">HOT</th>
	<th class="align-center h-px-35 text-center bg-lighter w-px-75">Xác minh</th>
	<th class="align-center h-px-35 text-center bg-lighter w-px-75">Duyệt</th>
	<th class="align-center h-px-35 text-center bg-lighter w-px-50">
		<i class="bx bx-cog"></i>
	</th>
</tr></thead>	
{if !empty($list_sop)}
	{foreach from =$list_sop item = _oSop}
	{assign var = sop_id value = $_oSop.sop_id}
	{assign var = stock_code value = $clsSop->getCode($_oSop)}
	{assign var = more_information value = $_oSop.more_information}
	<tr class="awe__sop-item awe__sop-item-{$sop_id} text-nowrap{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'} deleted{/if}{if $more_information.is_solded eq '1'} bg-solded{/if}{if $more_information.is_locked eq '1'} bg-locked{/if}">
		<td class="align-center text-left">
			<a class="fw-bold" href="{$clsSop->getLink($sop_id,$stock_code)}" target="_blank">{$_oSop.stock_code}</a>
		</td>
		<td class="align-center">
			{if $sop_type eq $smarty.const._SOP_TYPE_LOWFLOOR}
				{$_oSop.type_name}
			{else}
				{if !empty($_oSop.bedroom_name) || !empty($more_information.bedroom_count)}
					{if $_oSop.bedroom_name}
						{$_oSop.bedroom_name}
					{else}
						{$more_information.bedroom_count}PN
					{/if}
				{/if} / 
				{if !empty($more_information.DT_TT)}
					{$more_information.DT_TT}m<sup>2</sup>
				{/if}
			{/if}
		</td>
		<td class="align-center">
			{if !empty($_oSop.home_direction)}
				{$_oSop.home_direction}
			{else}
				-
			{/if}
		</td>
		{if $sop_type eq $smarty.const._SOP_TYPE_LOWFLOOR}
		
		{else}
		<td class="align-center">{$clsSop->getRangeFloor($_oSop.floor)}</td>
		{/if}
		<td class="align-center text-left" title="{$clsISO->priceFormat($_oSop.price)}">
			<span class="fw-bold text-main">{$clsISO->shortNumber($_oSop.price_owner,2)}</span>
		</td>
		<td class="align-center text-left" title="{$clsISO->priceFormat($_oSop.price)}">
			<span class="fw-bold text-main">{$clsISO->shortNumber($_oSop.price,2)}</span>
		</td>
		<td class="align-center text-left" title="{$clsISO->priceFormat($_oSop.price_m2)}">
			<span class="fw-bold text-main">{$clsISO->shortNumber($_oSop.price_m2,2)}</span>
		</td>
		<td class="align-center">{$more_information.contact_name}</td>
		<td class="align-center">{$more_information.contact_phone}</td>
		<td class="align-center">{$clsISO->formatDate($_oSop.reg_date,4)}</td>
		<td class="align-center sop__icon-{$sop_id}">{$clsSop->getIcon($sop_id, $type_list)}</td>
		{if $permiss_view_sop_source eq '1'}
		<td class="align-center InputCRMHandler">
			{$_oSop.agency_name} <a href="javascript:void(0);" class="text-muted" onClick="$Core.sop.editInlineField(this, event)" p_id="{$sop_id}" p_field="agency_id">
				<i class="bx bx-pencil"></i></a>
		</td>
		{/if}
		<td class="align-center text-center">
			<label class="switch">
			  <input type="checkbox" onChange="$Core.sop.mark_hot(this, event)" 
				sop_id="{$sop_id}"{if $_oSop.is_hot eq '1'} checked="checked"{/if} value="1" />
			  <span class="slider round"></span>
			</label>
		</td>
		<td class="align-center text-center">
			<label class="switch">
			  <input type="checkbox" onChange="$Core.sop.verified(this, event)" 
				sop_id="{$sop_id}"{if $_oSop.is_verified eq '1'} checked="checked"{/if} value="1" />
			  <span class="slider round"></span>
			</label>
		</td>
		<td class="align-center text-center">
			<label class="switch">
			  <input type="checkbox" onChange="$Core.sop.approved(this, event)" 
				sop_id="{$sop_id}"{if $_oSop.is_online eq '1'} checked="checked"{/if} value="1" />
			  <span class="slider round"></span>
			</label>
		</td>
		<td class="align-center text-center">
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle dropdown-button hide-arrow" 
				aria-expanded="true"><i class="bx bx-dots-vertical-rounded"></i></button>
				<div class="dropdown-menu" data-popper-placement="bottom-start">
					<a class="dropdown-item" href="javascript:void(0)" onClick="$Core.sop.gen_content(this, event);" class="btn flex-fill btn-outline-default" sop_id="{$sop_id}"><i class="material-icons-outlined">refresh</i> Tạo nội dung</a>
					<hr class="dropdown-divider" />
					<a class="dropdown-item" href="{$clsSop->getLinkEdit($sop_id, $return_url)}" target="_blank" 
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

