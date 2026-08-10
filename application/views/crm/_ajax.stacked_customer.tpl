{foreach from=$list_boxs key = _oKey item = _oBox}
	{assign var = lst_data value = $_oBox.lst_data}
	{assign var = list_customers value = $_oBox.list_customers}
	<div class="card mb-2">
		<div class="card-header ">
			<h5 class="card-title text-fs-18 mb-0">📋 {$_oBox.title}</h5>
		</div>
		<div class="card-body">
			<div class="holder_plan_{$_oKey}">
				{if $deviceType eq "phone"}
					{if !empty($list_customers)}
						{foreach from=$list_customers item=_oItem name=i}
						<div class="awe__customer-item border-bottom pointer-event py-2" route="/activity/{$_oItem.customer_id}" onDblClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}">
							<div class="d-flex align-items-center justify-content-between">
								<div class="d-flex align-items-center">
									{$_oItem.status_name} <a href="javascript:void(0);" onClick="$Core.crm.open_customer(this,event)" class="link goLink font-bold view_customer text-nowrap" route="/customer/{$_oItem.customer_id}/overview" customer_id="{$_oItem.customer_id}">{$_oItem.name}</a>
								</div>
								<div class="btn-group">
									<span class="btn btn-icon btn-sm btn-outline-default text-main">{$_oItem.total_followups}</span>
									<a href="javascript:void(0);" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default text-muted view_customer" onclick="$Core.crm.open_customer(this,event)" customer_id="{$_oItem.customer_id}" route="/customer/{$_oItem.customer_id}/overview"><i class='bx bx-edit-alt' ></i></a>
									<a href="javascript:void(0);" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.set_archived(this, event);" customer_id="{$_oItem.customer_id}">{$_oItem.icon_archived}</a>
									<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-default text-muted" title="Follow-ups" onClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}"><i class="bx bx-bell"></i></a>
								</div>
							</div>
							<div class="d-flex justify-content-between mb-1 align-items-center">
								<div class="d-flex align-items-center">
									<a href="javascript:void(0);" onClick="$Core.crm.change_assigned(this, event)" customer_id="{$_oItem.customer_id}" class="user-avatar">
										<img class="avatar avatar-xxs rounded-pill" src="{$clsProfile->getAvatar($_oItem.admin_id)}">
										<span class="cre">{$core->makeIcon($_oItem.icon)}</span>
									</a> 
									<div class="d-flex gap-1 align-items-center text-nowrap fs-11">
										<span class="d-flex gap-1 align-items-center text-muted">
											<i class="material-icons-outlined text-fs-12 no-translate">more_time</i>
											{$clsISO->getTimeAgo($_oItem.reg_date)}
										</span>
										<span class="d-flex gap-1 align-items-center text-warning">
											<i class="material-icons-outlined text-fs-12 no-translate">alarm</i>
											{$clsISO->getTimeAgo($_oItem.upd_date)}
										</span>
									</div>
								</div>
								<div class="d-flex align-items-center pt-1 gap-1">
									{if !empty($_oItem.phone)}
										<a href="https://zalo.me/{$_oItem.phone}" target="_blank" class="zalo_chat"></a>
										<a href="tel:{$_oItem.phone}" class="js_clicktocall text-nowrap">
										<img class="me-1" src="{$URL_IMAGES}/phone-icon.png">{$clsCustomer->mask($_oItem.phone, true)}</a>
									{/if}
								</div>
							</div>	
							{if !empty($_oItem.title_purpose)}
							<div class="d-flex my-1 align-items-center">
								<span class="text-muted fs-12">Mục đích:</span>{$_oItem.title_purpose}
							</div>
							{/if}
							{if !empty($_oItem.html_follow_ups) || !empty($_oItem.html_tags) || !empty($_oItem.html_stocks) }
								<div class="d-block">
									{$_oItem.html_stocks}{$_oItem.html_follow_ups}{$_oItem.html_tags}
								</div>
							{/if}
						</div>
						{/foreach}
					{else}
						<div class="text-center">
							<div class="border border-dashed p-3">
								Bạn chưa có <strong>{$_oBox.title|lower}</strong>
							</div>
						</div>
					{/if}
				{else}
				<div class="table-crm no-shadow overflow-x-auto">
					<table border="0" cellpadding="0" cellspacing="0" class="table dragable mb-0" width="100%">
						<thead><tr>
							{foreach from=$arr_columns item=_oItem}
								{if $_oItem.property_code eq 'admin' || $_oItem.property_code eq 'list_share'}
								<!-- Ignore -->
								{elseif $_oItem.property_code eq "reg_date"}
								<th class="align-center border-0 bg-grayter h-px-40">{$_oItem.title}</th>
								{else}
								<th class="align-center border-0 overflow-visible text-{if $_oItem.property_code eq 'admin'}center{else}left{/if} bg-grayter h-px-40"
									{if $_oItem.property_code eq "follow-ups"} colspan="2"{/if}>
									<div class="d-flex align-items-center justify-content-between">{$_oItem.title}</div>
								</th>
								{/if}
							{/foreach}
							<th class="align-center text-right bg-grayter h-px-40 position-sticky top-0 right-0 border-0" width="60px"></th>
						</tr></thead>
						<tbody>
						{if !empty($lst_data)}
							{foreach from=$lst_data item=_oItem name=i}
							{assign var=data_html value=$_oItem.data_html}
							<tr class="pointer-event  trCustomer" route="/activity/{$_oItem.customer_id}" 
								onDblClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}">
								{foreach from=$data_html key=field item=td_html}
									{$td_html}
								{/foreach}
								<td class="text-center bg-white position-sticky right-0">
									<div class="btn-group">
										<a title="Cập nhật" class="btn btn-icon btn-sm cursor-pointer btn-outline-default dropdown-button">
											<i class='bx bx-plus'></i></a>
										</a>
										<div class="dropdown-menu">
											{$clsCustomer->getMenuStask($_oItem.customer_id, $_oKey)}
										</div>
										<a href="javascript:void(0);" title="Cập nhật" class="btn btn-icon btn-sm btn-outline-default view_customer" 
											onclick="$Core.crm.open_customer(this,event)" customer_id="{$_oItem.customer_id}" route="/customer/{$_oItem.customer_id}/overview">
											<i class='bx bx-edit-alt'></i></a>
										<a href="javascript:void(0);" title="Follow-ups" class="btn btn-icon btn-sm btn-outline-default" 
											onClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}"><i class="bx bx-bell"></i></a>
									</div>
								</td>
							</tr>
							{/foreach}
						{else}
							<tr>
								<td class="empty" colspan="12">
									<div class="text-center">
										Bạn chưa có <strong>{$_oBox.title|lower}</strong>
									</div>
								</td>
							</tr>
						{/if}
						</tbody>
					</table>
				</div>
				{/if}
			</div>
		</div>
	</div>
{/foreach}