{if $_type eq 'stock_logs'}
<div id="{$uid}" class="table-container no-shadow overflow-x-auto text-nowrap">
	<table border="0" cellpadding="0" cellspacing="0" class="table mb-0" width="100%">
		<thead><tr>
			<th class="align-center bg-lighter h-px-35">Hành động</th>
			<th class="align-center bg-lighter h-px-35">Nội dung</th>
			<th class="align-center bg-lighter h-px-35">Thời gian</th>
		</tr></thead>
		{if !empty($lstItem)}
			{foreach name=i from=$lstItem item = _oItem}
			<tr class="trBilling{if $_oBilling.is_cancel eq '1'} bg-cancel{/if}">
				
				<td class="text-left">{$_oItem.action}</td>
				<td class="text-left">{$_oItem.contentHTML}</td>
				<td class="text-left">{$_oItem.time}</td>
			</tr>
			{/foreach}
		{else}
			<tr><td class="text-center" colspan="3">
				<img src="{$URL_IMAGES}/illustration-empty-results.svg" class="w-px-200" />
				<p class="text-muted">Chưa có tra cứu</p>
			</td></tr>
		{/if}
	</table>
</div>
{elseif $_type eq 'report_share'}
	<div id="{$uid}" class="table-container no-shadow overflow-x-auto text-nowrap">
		<table border="0" cellpadding="0" cellspacing="0" class="table dragable mb-0" width="100%">
			<thead><tr>
				<th class="align-center bg-lighter h-px-35">Khách hàng</th>
				<th class="align-center bg-lighter h-px-35">Điện thoại</th>
				<th class="align-center bg-lighter h-px-35">Địa điểm</th>
				<th class="align-center bg-lighter h-px-35">Số khách</th>
				<th class="align-center bg-lighter h-px-35">Mục tiêu</th>
				<th class="align-center bg-lighter h-px-35">Quan tâm</th>
				<th class="align-center bg-lighter h-px-35">Trạng thái</th>
				<th class="align-center bg-lighter h-px-35">Nội dung</th>
				<th class="align-center bg-lighter h-px-35">Thời gian</th>
			</tr></thead>
			{if !empty($lstItem)}
				{foreach name=i from=$lstItem item = _oItem}
					{assign var=more_information value=$_oItem.more_information}
					<tr class="trBilling{if $_oBilling.is_cancel eq '1'} bg-cancel{/if}">
						<td class="text-left">
							{if !empty($more_information.customer_id) && ($_oItem.user_id eq $profile_id || $clsISO->_DEV())}
								<span class="lh-xs">{if !empty($more_information.customer_name)}{$more_information.customer_name}{else}--{/if} 
								<a class="badge bg-label-warning text-nowrap fs-10" href="/crm/{$more_information.customer_id}" target="_blank" >CRM <i class='bx bx-link-external fs-10'></i></a></span>
							{else}
								{if !empty($more_information.customer_name)}
									<span class="lh-xs">{$more_information.customer_name}</span>
								{else}
									<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
								{/if}
							{/if}
						</td>
						<td class="text-left">
							{if !empty($more_information.customer_id) && ($_oItem.user_id eq $profile_id || $clsISO->_DEV())}
								<span class="lh-xs ">{if !empty($more_information.customer_phone)}
									{$clsShare->maskPhone($more_information.customer_phone)}{else}--{/if} <a class="badge bg-label-warning text-nowrap fs-10" href="/crm/{$more_information.customer_id}" target="_blank">CRM <i class='bx bx-link-external fs-10'></i></a>
								</span>
							{else}
								{if !empty($more_information.customer_phone)}
									<span class="lh-xs">{$more_information.customer_phone}</span>
								{else}
									<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
								{/if}
							{/if}
						</td>
						<td class="text-left">
							{if !empty($more_information.location)}
								<span class="lh-xs">{$more_information.location}</span>
							{else}
								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
							{/if}
						</td>
						<td class="text-left">
							{if !empty($more_information.guest_count)}
								<span class="lh-xs">{$more_information.guest_count} khách</span>
							{else}
								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
							{/if}
						</td>
						<td class="text-left">
							{if !empty($more_information.target_name)}
								<span class="lh-xs">{$more_information.target_name} khách</span>
							{else}
								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
							{/if}
						</td>
						<td class="text-left">
							{if !empty($more_information.interest_name)}
								<span class="lh-xs">{$more_information.interest_name}</span>
							{else}
								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
							{/if}
						</td>
						<td class="text-left">
							{if !empty($more_information.status_name)}
								<span class="lh-xs">{$more_information.status_name} khách</span>
							{else}
								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
							{/if}
						</td>
						<td class="text-left">
							{$more_information.content}
						</td>
						<td class="text-left">
							{$clsISO->convertTimeToTextFormat($_oItem.reg_date,"H:i • d/m/Y")}
						</td>
					</tr>
				{/foreach}
			{else}
				<tr><td class="text-center" colspan="9">
					<img src="{$URL_IMAGES}/illustration-empty-results.svg" class="w-px-200" />
					<p class="text-muted">Chưa có hoạt động</p>
				</td></tr>
			{/if}
		</table>
	</div>
{/if}
<div id="pager_{$_type}_{$uid}"></div>