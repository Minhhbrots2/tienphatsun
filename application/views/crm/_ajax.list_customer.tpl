{if $deviceType eq "phone"}
{if !empty($list_customers)}
<div class="crm-mb-list cmc-list">
	{foreach from=$list_customers item=_oItem name=i}
	{assign var=_ls value=$_oItem.lead_score}
	<div class="cmc" style="--st-bg:{$_oItem.bgcolor|default:'#8a93a2'};--st-c:{$_oItem.textcolor|default:'#566a7f'}"
		customer_id="{$_oItem.customer_id}">
		<div class="cmc-top">
			<span class="cmc-status">{$_oItem.status_title}</span>
			{if $_oItem.can_change_status}
			<div class="dropdown cmc-chuyen-wrap" onClick="event.stopPropagation()">
				<button type="button" class="cmc-chuyen" data-bs-toggle="dropdown" data-bs-auto-close="true"
					aria-expanded="false">Chuyển <i class="bx bx-chevron-down"></i></button>
				<div class="dropdown-menu cmc-chuyen-menu">{$_oItem.status_menu_html}</div>
			</div>
			{/if}
			{if $_oItem.project_text}<span class="cmc-project"
				title="{$_oItem.project_text|escape}">{$_oItem.project_text|escape}</span>{/if}
			<div class="dropdown cmc-more-wrap" onClick="event.stopPropagation()">
				<button type="button" class="cmc-more" data-bs-toggle="dropdown" aria-expanded="false"
					aria-label="Thêm"><i class="bx bx-dots-horizontal-rounded"></i></button>
				<div class="dropdown-menu dropdown-menu-end">
					<a class="dropdown-item" href="javascript:void(0)" customer_id="{$_oItem.customer_id}"
						route="/customer/{$_oItem.customer_id}/overview"
						onClick="$Core.crm.open_customer(this, event)"><i class="bx bx-user me-1"></i> Xem chi tiết</a>
					{if !$team_readonly}
					<a class="dropdown-item" href="javascript:void(0)" customer_id="{$_oItem.customer_id}"
						onClick="$Core.crm.set_archived(this, event)"><i class="bx bx-archive me-1"></i> Lưu trữ</a>
					<a class="dropdown-item" href="javascript:void(0)" customer_id="{$_oItem.customer_id}"
						onClick="$Core.crm.change_assigned(this, event)"><i class="bx bx-transfer-alt me-1"></i> Bàn
						giao / Chia sẻ</a>
					{/if}
				</div>
			</div>
		</div>
		<div class="cmc-name" route="/customer/{$_oItem.customer_id}/overview"
			onClick="$Core.crm.open_customer(this, event)">
			<span class="cmc-nm">{$_oItem.name|escape}</span>
			{if $_ls >= $smarty.const._CRM_LEAD_SCORE_HOT}<span class="cmc-dot" style="background:#ff3e1d"
				title="Hot"></span>{elseif $_ls >= 30}<span class="cmc-dot" style="background:#ffab00"
				title="Warm"></span>{elseif $_ls > 0}<span class="cmc-dot" style="background:#03c3ec"
				title="Cold"></span>{/if}
			{if $_oItem.gender_sign eq 'f'}<span class="cmc-g" style="color:#ea7fae">&#9792;</span>{elseif
			$_oItem.gender_sign eq 'm'}<span class="cmc-g" style="color:#5b9bd5">&#9794;</span>{/if}
			{if !$team_readonly}<a class="cmc-bell ms-auto" href="javascript:void(0)" title="Thêm tương tác"
				customer_id="{$_oItem.customer_id}" tp="follow-ups"
				onClick="event.stopPropagation(); $Core.crm.view_activity(this, event)"><i
					class="bx bx-bell"></i></a>{/if}
		</div>
		<div class="cmc-body">
			<div class="cmc-info">
				<div><i class="bx bx-purchase-tag-alt cmc-ic"></i> <span
						class="cmc-v">{$_oItem.resource_name|default:'--'}</span></div>
				{if $_oItem.reg_date_text}<div><i class="bx bx-calendar cmc-ic"></i> <span
						class="cmc-date">{$_oItem.reg_date_text}</span></div>{/if}
				{if $_oItem.need_text || $_oItem.campaign_text}<div>{if $_oItem.need_text}<i
						class="bx bx-home-alt cmc-ic"></i> <span
						class="cmc-need">{$_oItem.need_text|escape}</span>{/if}{if $_oItem.campaign_text}{if
					$_oItem.need_text} {/if}<i class="bx bx-target-lock cmc-ic"></i> <span
						class="cmc-camp">{$_oItem.campaign_text|escape}</span>{/if}</div>{/if}
				{if !empty($_oItem.phone)}<div><i class="bx bx-phone cmc-ic"></i> {if $_oItem.is_owner_phone}<span
						class="crm-phone-wrap js__reveal-phone cmc-ph" data-full="{$_oItem.phone}"
						onClick="event.stopPropagation(); $Core.crm.reveal_phone(this, event)"
						title="Xem số đầy đủ"><span class="js__ph-text">{$clsCustomer->mask($_oItem.phone, true)}</span>
						<i class="bx bx-show js__ph-eye"></i></span>{else}<span
						class="cmc-v">{$clsCustomer->mask($_oItem.phone, true)}</span>{/if}</div>{/if}
			</div>
			<div class="cmc-side">
				{if $_oItem.owner_name}<div class="cmc-owner">
					<img class="avatar avatar-sm rounded-pill" src="{$_oItem.owner_avatar}"
						onerror="this.onerror=null;this.src='{$URL_IMAGES}/no-avatar.jpg'" alt="">
					<div class="cmc-owner-tx">
						<div class="cmc-owner-nm">{$_oItem.owner_name|escape}</div>{if $_oItem.owner_dept}<div
							class="cmc-owner-dept">{$_oItem.owner_dept|escape}</div>{/if}
					</div>
				</div>{else}<div class="cmc-owner cmc-owner--none"><i class="bx bx-user-x"></i> Chưa giao</div>{/if}
				{if $_oItem.last_act_text}<div class="cmc-last"><i class="bx bx-time-five"></i> Tương tác lần cuối
					<b>{$_oItem.last_act_text}</b></div>{/if}
			</div>
		</div>
		{if !empty($_oItem.task_next_text)}
		<div class="cmc-next d-flex flex-wrap justify-content-between align-items-center{if $_oItem.task_next_overdue} cmc-next--over{/if}">
			<div class="d-flex align-items-center gap-1">
				<i class="bx bx-chevrons-right cmc-next-ic"></i>
				<span class="cmc-next-lb">Tác nghiệp tiếp:</span>
				<span class="cmc-next-task">{$_oItem.task_next_text|escape}</span>{$_oItem.task_attempt_badge}
			</div>
			<div class="d-flex align-items-center gap-1">
				{if $_oItem.task_next_time}
				<div class="d-flex flex-column">
					<span class="cmc-next-time">
						<i class="bx bx-time-five"></i> {$_oItem.task_next_time|escape}
					</span>
					<div class="tn-go-hint"><i class="bx bx-up-arrow-alt"></i> bấm để thực hiện</div>
				</div>
				{/if}
				{if !$team_readonly && !$_oItem.is_pending}
				<button type="button" class="cmc-next-go" customer_id="{$_oItem.customer_id}" 
					onClick="event.stopPropagation(); $Core.crm.crm_task_move_next(this, event)" title="Chuyển sang thực hiện tác nghiệp này"><i class="bx bx-up-arrow-circle"></i></button>
				{/if}
			</div>
		</div>
		{/if}
		<div class="cmc-acts">
			{if $_oItem.is_pending}
			<a class="cmc-act cmc-recv" customer_id="{$_oItem.customer_id}"
				onClick="event.stopPropagation(); $Core.crm.confirm_receipt(this, event)"><i
					class="bx bx-check-shield"></i> Xác nhận đã nhận khách</a>
			{else}
			<a class="cmc-act is-call" href="tel:{$_oItem.phone}" onClick="event.stopPropagation()"><i
				class="bx bx-phone-call"></i></a>
			<a class="cmc-act is-zalo" href="https://zalo.me/{$_oItem.phone}" target="_blank"
				onClick="event.stopPropagation()">
				<img src="{$URL_IMAGES}/zalo_logo.png" width="20px" />
			</a>
			{if !empty($_oItem.facebook_url)}<a class="cmc-act is-fb cmc-act-ico" href="{$_oItem.facebook_url|escape}"
				target="_blank" onClick="event.stopPropagation()" title="Facebook" aria-label="Facebook"><i
					class="bx bxl-facebook-circle"></i></a>{/if}
			{if !$team_readonly}<a class="cmc-act is-add" customer_id="{$_oItem.customer_id}" tp="follow-ups"
				onClick="event.stopPropagation(); $Core.crm.open_activity(this, event)"><i class="bx bx-plus"></i> Thêm
				tương tác</a>{/if}
			{if !$team_readonly && !empty($_oItem.task_current_id)}<a class="cmc-act is-result"
				customer_id="{$_oItem.customer_id}"
				onClick="event.stopPropagation(); $Core.crm.crm_task_result_sheet(this, event)"><i
					class="bx bx-list-check"></i> Tác nghiệp</a>{/if}
			{/if}
		</div>
		{if !empty($_oItem.recent_acts) || $_oItem.total_followups > 0}
		<div class="cmc-log">
			{foreach from=$_oItem.recent_acts item=_act}
			<div class="cmc-log-row"><i class="bx {$_act.icon}" style="color:{$_act.color}"></i> <span
					class="cmc-log-d">{$_act.date_text}:</span> <span class="cmc-log-t">{$_act.intro|escape}</span>
			</div>
			{/foreach}
			<a class="cmc-log-all" customer_id="{$_oItem.customer_id}"
				onClick="event.stopPropagation(); $Core.crm.view_activity(this, event)"><i class="bx bx-transfer"></i>
				Xem tất cả tương tác ({$_oItem.total_followups}) <i class="bx bx-chevron-right"></i></a>
		</div>
		{/if}
	</div>
	{/foreach}
</div>
{/if}
{else}
{if $action eq 'load_more'}
{if !empty($lst_data)}
{foreach from=$lst_data item=_oItem name=i}
{assign var="data_html" value=$_oItem.data_html}
{math equation="x + 1" x=$index assign=index}
<tr class="pointer-event trCustomer" onDblClick="$Core.crm.view_activity(this, event);"
	customer_id="{$_oItem.customer_id}" route="/activity/{$_oItem.customer_id}">
	<td class="text-center">
		<input {if empty($is_checkbox)}disabled{/if} type="checkbox" tp="item"
			onChange="$Core.crm.check_item(this, event)" value="{$_oItem.customer_id}"
			class="form-check-input chk_customer" />
	</td>
	{foreach from=$data_html key=field item=td_html}
	{$td_html}
	{/foreach}
	<td width="60px" class="text-center bg-white position-sticky right-0">
		<div class="btn-group">
			{if $_oItem.is_pending}
			<a href="javascript:void(0);" title="Xác nhận đã nhận khách này để bắt đầu thao tác"
				class="btn btn-sm btn-warning crm-confirm-recv" onClick="$Core.crm.confirm_receipt(this, event);"
				customer_id="{$_oItem.customer_id}"><i class="bx bx-check-shield"></i> Nhận</a>
			{else}
			{if !$team_readonly}
			<a title="Ghi nhận cuộc gọi" class="btn btn-icon btn-sm cursor-pointer btn-outline-success" tp="follow-ups"
				customer_id="{$_oItem.customer_id}" onclick="$Core.crm.open_activity(this, event)"
				type_id="{$smarty.const._FOLLOWUP_CALL_ID}"><i class='bx bxs-phone-call'></i></a>
			<a href="javascript:void(0);" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default"
				onClick="$Core.crm.set_archived(this, event);"
				customer_id="{$_oItem.customer_id}">{$_oItem.icon_archived}</a>
			{/if}
			<a href="javascript:void(0);" title="Follow-ups" class="btn btn-icon btn-sm btn-outline-default"
				onClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}"><i
					class="bx bx-bell"></i></a>
			<!-- <a href="javascript:void(0);" title="Follow-ups" onClick="$Core.crm.view_activity(this, event);" 
							customer_id="{$_oItem.customer_id}" class="btn btn-icon btn-sm btn-outline-default text-main">{$_oItem.total_followups_next}</a> -->
			{/if}
		</div>
	</td>
</tr>
{/foreach}
{else}
<tr>
	<td class="empty text-center" align="center" colspan="99">{$empty}</td>
</tr>
{/if}
{else}
<div class="table-crm no-shadow overflow-x-auto">
	<table border="0" cellpadding="0" cellspacing="0" class="table dragable mb-0" width="100%">
		<thead><tr>
			<th class="align-center bg-grayter text-center h-px-40 border-0" width="40px">
				<input type="checkbox" {if !$is_checkbox}disabled{/if} class="form-check-input" tp="all"
					onChange="$Core.crm.check_item(this, event)" style="font-size:0.85rem !important" />
			</th>
			{foreach from=$arr_columns item=_oItem}
			<th class="align-center border-0 overflow-visible text-{if $_oItem.property_code eq 'admin' or $_oItem.property_code eq 'user_id'}center{else}left{/if} bg-grayter h-px-40"
				{if $_oItem.property_code eq "follow-ups" } colspan="2" {/if}>
				<div class="d-flex align-items-center justify-content-between">
					{$_oItem.title}
					{if $_oItem.property_code eq 'name'}
					<div class="btn-group">
						<button
							class="btn btn-icon rounded-pill btn-xs btn-link dropdown-button hide-arrow text-muted">
							<i class='bx bx-sort-alt-2'></i>
						</button>
						<ul class="dropdown-menu">
							<h6 class="dropdown-header text-uppercase">Liên hệ lần cuối</h6>
							<li><a href="javascript:void(0);" onClick="$Core.crm.do_sorted(this, event)"
									sort_by="last_contact" holderG="{$holderG}"
									class="dropdown-item js__sort-by{if $sort_by eq 'last_contact'} active{/if}">
									<i class='bx bx-sort-up'></i> Gần nhất</a>
							</li>
							<li><a href="javascript:void(0);" onClick="$Core.crm.do_sorted(this, event)"
									sort_by="first_contact" holderG="{$holderG}"
									class="dropdown-item js__sort-by{if $sort_by eq 'first_contact'} active{/if}">
									<i class='bx bx-sort-down'></i> Xa nhất</a>
							</li>
						</ul>
					</div>
					{/if}
				</div>
			</th>
			{/foreach}
			<th class="align-center text-right bg-grayter h-px-40 position-sticky top-0 right-0 border-0"
				width="60px">
				<button type="button" class="btn btn-icon btn-sm btn-link rounded-pill"
					onclick="$Core.crm.setting_field(this,event)" view_by="{$view_by}" action="_OPEN"
					title="Tùy chỉnh cột"><i class="bx bx-cog text-muted"></i></button>
			</th>
		</tr></thead>
		<tbody class="_lst_customer">
			{if !empty($lst_data)}
			{foreach from=$lst_data item=_oItem name=i}
			{assign var=data_html value=$_oItem.data_html}
			<tr class="pointer-event trCustomer" route="/activity/{$_oItem.customer_id}"
				onDblClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}">
				<td class="text-center">
					<input type="checkbox" {if empty($is_checkbox)} disabled{/if}
						onChange="$Core.crm.check_item(this, event)" tp="item" value="{$_oItem.customer_id}"
						class="form-check-input chk_customer" />
				</td>
				{foreach from=$data_html key=field item=td_html}
				{$td_html}
				{/foreach}
				<td class="text-center bg-white position-sticky right-0">
					<div class="btn-group">
						{if $_oItem.is_pending}
						<a href="javascript:void(0);" title="Xác nhận đã nhận khách này để bắt đầu thao tác"
							class="btn btn-sm btn-warning crm-confirm-recv"
							onClick="$Core.crm.confirm_receipt(this, event);" customer_id="{$_oItem.customer_id}"><i
								class="bx bx-check-shield"></i> Nhận</a>
						{else}
						{if !$team_readonly}
						<a title="Ghi nhận cuộc gọi" class="btn btn-icon btn-sm cursor-pointer btn-outline-success"
							customer_id="{$_oItem.customer_id}" onclick="$Core.crm.open_activity(this, event)"
							tp="follow-ups" type_id="{$smarty.const._FOLLOWUP_CALL_ID}"><i
								class='bx bxs-phone-call'></i></a>
						<a href="javascript:void(0);" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default"
							onClick="$Core.crm.set_archived(this, event);"
							customer_id="{$_oItem.customer_id}">{$_oItem.icon_archived}</a>
						{/if}
						<a href="javascript:void(0);" title="Follow-ups" class="btn btn-icon btn-sm btn-outline-default"
							onClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}"><i
								class="bx bx-bell"></i></a>
						{/if}
					</div>
				</td>
			</tr>
			{/foreach}
			{else}
			<tr>
				<td class="empty text-center" align="center" colspan="99">{$html_empty}</td>
			</tr>
			{/if}
		</tbody>
	</table>
</div>
{/if}
{/if}