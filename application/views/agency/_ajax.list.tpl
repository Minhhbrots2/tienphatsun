<table class="table table-bordered dragable" width="100%" cellpadding="0" cellspacing="0">
	<thead><tr>
		<th class="text-left" {if $deviceType eq 'phone'}width="80px"{else}width="45%"{/if} rowspan="2" style="vertical-align: middle;">Tên đại lý</th>
		<!-- MOC -->
		{assign var=total_col value=6}
		{if !empty($agency_hidden_stock_MOC)}
			{foreach from=$agency_hidden_stock_MOC item=hidden_stock}
				{math equation="x+1" x=$total_col assign="total_col"}
				<th class="text-left">{$hidden_stock.title}</th>
			{/foreach}
		{/if}
		<!-- user.FH -->										
		{if !empty($agency_hidden_stock_FH)}
			{foreach from=$agency_hidden_stock_FH item=hidden_stock name=i}
				{math equation="x+1" x=$total_col assign="total_col"}
				<th class="text-left">{$hidden_stock.title}</th>
			{/foreach}
		{/if}										
		<th class="text-center" rowspan="2" style="vertical-align: middle;">T.trạng</th>
	</tr>
	<tr>
		<!-- MOC -->
		{if !empty($agency_hidden_stock_MOC)}
			{foreach from=$agency_hidden_stock_MOC item=hidden_stock key=key}
			<th class="text-left">
				<div class="d-flex align-items-center gap-1">
					<button class="btn btn-sm btn-icon btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="{$key}" onClick="$Core.agency.toggle_sw(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
					<button class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="{$key}" onClick="$Core.agency.toggle_sw(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</div>
			</th>
			{/foreach}
		{/if}
		<!-- user.FH -->										
		{if !empty($agency_hidden_stock_FH)}
			{foreach from=$agency_hidden_stock_FH item=hidden_stock key=key}
			<th class="text-left" {if $smarty.foreach.i.last}style="border-right: 1px solid #d9dee3;"{/if}>
				<div class="d-flex align-items-center gap-1">
					<button class="btn btn-sm btn-icon btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="{$key}" onClick="$Core.agency.toggle_sw(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
					<button class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="{$key}" onClick="$Core.agency.toggle_sw(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</div>
			</th>
			{/foreach}
		{/if}
	</tr></thead>
	<tbody class="holderPropertyType_agency">
		{if !empty($list_items)}
			{foreach from=$list_items item=_oItem key=key name=i}
				{assign var = agency_id value=$_oItem.property_id}
				{assign var = more_information value = $_oItem.more_information}
				<tr class="bold" id="{$agency_id}">
					<td class="text-nowrap column_sticky">{$clsProperty->getTitle($agency_id,$_oItem)}</td>
					<!-- MOC -->
					{if !empty($agency_hidden_stock_MOC)}
						{foreach from=$agency_hidden_stock_MOC item=hidden_stock key=key}
							{if !empty($hidden_stock.is_vin)}
								{assign var="key_hide_stock" value="MOC_stock_vin"}
							{else}
								{assign var="key_hide_stock" value="MOC_`$agency_id`_`$hidden_stock.block_id`"}
							{/if}
							<td class="text-center">
								<label class="switch">
									<input type="checkbox" onChange="$Core.agency.hide_stock_globe(this, event)"{if isset($more_information.$key_hide_stock) && $more_information.$key_hide_stock eq '1'} checked{/if} to_field="{$key_hide_stock}" agency_id="{$agency_id}" value="1" class="switch_{$key}" />
									<span class="slider round"></span>
								</label>
							</td>
						{/foreach}
					{/if}
					<!-- user.FH -->										
					{if !empty($agency_hidden_stock_FH)}
						{foreach from=$agency_hidden_stock_FH item=hidden_stock key=key}
							{if !empty($hidden_stock.is_vin)}
								{assign var="key_hide_stock" value="FH_stock_vin"}
							{else}
								{assign var="key_hide_stock" value="FH_`$agency_id`_`$hidden_stock.block_id`"}
							{/if}
							<td class="text-center">
								<label class="switch">
									<input type="checkbox" onChange="$Core.agency.hide_stock_globe(this, event)"{if isset($more_information.$key_hide_stock) && $more_information.$key_hide_stock eq '1'} checked{/if} to_field="{$key_hide_stock}" agency_id="{$agency_id}" value="1" class="switch_{$key}" />
									<span class="slider round"></span>
								</label>
							</td>
						{/foreach}
					{/if}
					<!-- End -->
					<td class="text-center">
						<label class="switch">
							<input type="checkbox"{if $_oItem.is_trash eq '0'} checked{/if} onChange="$Core.agency.set_status(this, event)" agency_id="{$agency_id}" value="1" />
							<span class="slider round"></span>
						</label>
					</td>
					{*<td data-label="{$core->get_Lang('Actions')}" class=" column_sticky sticky_right">
						<div class="dropdown dropup">
							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>
							</button>
							<div class="dropdown-menu w-px-100" style="">
								<a class="dropdown-item" onclick="$Core.agency.open(this,event)" data-type="open" agency_id="{$agency_id}" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
								<a class="dropdown-item" onclick="$Core.agency.delete(this,event)" agency_id="{$agency_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
							</div>
						</div>
					</td>*}
				</tr>
			{/foreach} 
		{else}
			<tr class="tr">
				<td colspan="{$total_col}" class="text-center">Danh sách trống</td>
			</tr>
		{/if}
	</tbody>
</table>
