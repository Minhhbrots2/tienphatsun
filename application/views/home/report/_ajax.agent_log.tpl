<div class="table-container no-shadow table-container_agent_log overflow-x-auto  text-nowrap">
	<table cellpadding="0" cellspacing="0" class="table table-bordered  {if $deviceType eq 'phone'}fs-12{/if}" width="100%">
		<thead><tr>
			{if $deviceType ne 'phone'}
			<th width="30px" class="align-center h-px-30 nosort bg-lighter text-center">STT</th>{/if}
			<th class="align-center text-left h-px-30 bg-lighter" style="width:200px">Đại lý</th>
			{foreach from=$arr_time item=time}
			<th class="align-center text-left h-px-30 bg-lighter" width="15%">{$time}</th>
			{/foreach}
		</tr></thead>
		{if !empty($array_data)}
			{foreach name=i from=$array_data name=i key=key item=item}
			<tr{if $smarty.foreach.i.iteration gt '10'} class="d-none"{/if}>
				{if $deviceType ne 'phone'}
				<td class="text-center">{$smarty.foreach.i.iteration}</td>
				{/if}
				<td class="text-left text-nowrap">{$item.title}</td>
				{foreach from=$arr_time item=time}
				<td class="text-left">
					{$clsISO->makeSlashListFromArray($item.$time,", ",0)|nl2br} 
					{if $item.$time|@count gt 0}({$item.$time|@count}){/if}
				</td>
				{/foreach}	
			</tr>
			{/foreach}
		{else}
			<tr>
				<td class="text-center" colspan="{if $deviceType ne 'phone'}6{else}3{/if}">Dữ liệu trống</td>
			</tr>
		{/if}
	</table>
</div>
<div class="d-flex justify-content-center">
	<a href="javascript:void(0)" class="btn btn-outline-default rounded-pill load_more mt-2" onClick="load_more_table(this)">
		Xem thêm <i class='bx bx-chevrons-down ml-1'></i>
	</a>
</div>
