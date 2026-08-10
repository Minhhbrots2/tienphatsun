{if $type eq 'MEMBER'}
<div class="table-wrapper overflow-auto d-flex flex-wrap">
	<table class="table table-bordered" width="100%">
		<thead><tr>
			<th width="30px" class="align-center nosort bg-lighter">STT</th>
			<th class="align-center text-left bg-lighter">Họ và tên</th>
			<th class="align-center nosort text-center bg-lighter" style="width:150px">Gói</th>
			<th class="align-center nosort text-center bg-lighter" style="width:150px">Bắt đầu</th>
			<th class="align-center nosort text-center bg-lighter" style="width:150px">Kết thúc</th>
			<th class="align-center text-left bg-lighter" style="width:30px"></th>
		</tr></thead>
		{if !empty($lst_user)}
			{foreach name=i from=$lst_user name=i key=key item=item}
				<tr>
					<td class="text-center">{$smarty.foreach.i.iteration}</td>
					<td class="text-left">
						<div style="width:max-content">
							<a href="{$clsISO->getLink('log-sale')}?user_id={$item.profile_id}" target="_blank">
								<i class="bx bx-link"></i>{$item.full_name}</a>
						</div>
					</td>	
					<td class="text-center">{$item.package_name}</td>
					<td class="text-center">{if $item.start_date}{$clsISO->convertTimeToText($item.start_date, true)}{else}--{/if}</td>
					<td class="text-center">{if $item.start_date}{$clsISO->convertTimeToText($item.due_date, true)}{else}--{/if}</td>
					<td class="text-center"><a href="https://zalo.me/{$item.phone}" target="_blank">
						<img src="{$URL_IMAGES}/zalo_chat.png" width="20" height="20"></a>
					</td>
				</tr>
			{/foreach}
		{else}
			<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}5{else}3{/if}">Dữ liệu trống</td></tr>
		{/if}
	</table>
</div>
{/if}
