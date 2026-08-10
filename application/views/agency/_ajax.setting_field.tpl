<div class="table-container overflow-x-auto no-shadow text-nowrap">
	<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">
		<thead><tr>
			{if $deviceType ne 'phone'}
			<th class="text-left" width="5%">No.</th>{/if}
			<th class="text-left">Tiêu đề</th>
			<th class="text-left">Phân khu</th>
			<th class="text-center" width="100px">Ẩn</th>
			<th class="text-left" width="40px"></th>
		</tr></thead>
		<tbody>
			{if !empty($agency_hidden_stock_FH) || !empty($agency_hidden_stock_MOC) }
				{assign var=stt value=1}
				{foreach from=$agency_hidden_stock_FH item=_oItem key=key name=i }
					<tr>
						{if $deviceType ne 'phone'}
						<td class="text-center">{$stt}</td>{/if}
						<td class="text-nowrap">{$_oItem.title}</td>
						<td class="align-center">{$clsProperty->getTitle($_oItem.block_id)}</td>
						<td class="text-center">{if $_oItem.site eq "_FH"}CA{else}MOC{/if}</td>
						<td class="align-center">
							<div class="d-flex align-items-center gap-1">
								<a onClick="$Core.agency.open_setting_field(this,event)" class="btn btn-sm btn-icon btn-default" id="{$key}" data-type="_FH" href="javascript:void(0);"><i class="bx bx-edit-alt"></i></a>
								<a onClick="$Core.agency.delete_setting_field(this,event)" class="btn btn-sm btn-icon btn-default" id="{$key}" data-type="_FH" href="javascript:void(0);"><i class="bx bx-trash"></i></a>
							</div>
						</td>
					</tr>
					{math equation="x+1" x=$stt assign="stt"}
				{/foreach}
				<tr>
					<td colspan="5"></td>
				</tr>
				{foreach from=$agency_hidden_stock_MOC item=_oItem key=key name=i }
					<tr>
						{if $deviceType ne 'phone'}
						<td class="text-center">{$stt}</td>{/if}
						<td class="text-nowrap">{$_oItem.title}</td>
						<td class="align-center">{$clsProperty->getTitle($_oItem.block_id)}</td>
						<td class="text-center">{if $_oItem.site eq "_FH"}CA{else}MOC{/if}</td>
						<td data-label="{$core->get_Lang('Actions')}">
							<div class="d-flex align-items-center gap-1">
								<a onClick="$Core.agency.open_setting_field(this,event)" class="btn btn-sm btn-icon btn-default" id="{$key}" data-type="_MOC" href="javascript:void(0);"><i class="bx bx-edit-alt"></i></a>
								<a onClick="$Core.agency.delete_setting_field(this,event)" class="btn btn-sm btn-icon btn-default" id="{$key}" data-type="_MOC" href="javascript:void(0);"><i class="bx bx-trash"></i></a>
							</div>
						</td>
					</tr>
					{math equation="x+1" x=$stt assign="stt"}
				{/foreach}
			{else}
				<tr><td class="text-center" colspan="5">Danh sách trống</td></tr>
			{/if}
		</tbody>
	</table>
</div>