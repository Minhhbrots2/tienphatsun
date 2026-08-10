<table class="table table-bordered mb-0" width="100%">
	<thead><tr>
		{if $deviceType ne 'phone'}
		<th width="30px" class="align-center nosort bg-lighter">STT</th>
		{/if}
		<th class="align-center text-left bg-lighter">Họ và tên</th>
		<th class="align-center text-left bg-lighter" style="width:200px">Email</th>
		<th class="align-center text-left bg-lighter" style="width:150px">Số điện thoại</th>
		<th class="align-center text-center bg-lighter" style="width:200px">Xác thực</th>
	</tr></thead>
	<tbody>
		{if !empty($lst_user)}
			{foreach name=i from=$lst_user name=i key=key item=item}
				<tr>
					{if $deviceType ne 'phone'}
						<td class="text-center">{$smarty.foreach.i.iteration}</td>
					{/if}
					<td class="text-left">
						{$item.full_name}
					</td>			
					<td class="text-left">{$item.email}</td>		
					<td class="text-left">
						{if !empty($item.phone)}
							{$item.phone} <a href="https://zalo.me/{$item.phone}" target="_blank">
							<img src="{$URL_IMAGES}/zalo_chat.png" width="20" height="20"></a>
						{else}
							<span class="text-muted">Chưa cập nhật</span>
						{/if}
					</td>	
					<td class="text-center">
						{if $status eq "active"}
							<span class="text-success">Đã xác thực</span>
						{else}
						<button class="btn_active btn btn-outline-primary" onClick="$Core.report.activeUser(this,event)" data-member_id="{$item.profile_id}">Xác thực</button>
						{/if}
					</td>	
				</tr>
			{/foreach}
		{else}
			<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}5{else}3{/if}">Dữ liệu trống</td></tr>
		{/if}
	</tbody>
</table>
