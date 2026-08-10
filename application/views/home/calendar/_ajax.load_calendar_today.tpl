{if !empty($list_billings)}
	{foreach from=$list_billings item=_oBilling}
	{assign var=_oAdmin value=$_oBilling.admin}
	{assign var=_oStaff value=$_oBilling.staff}
	<tr class="trBilling">
		<td class="text-left text-nowrap">
			<div class="d-flex flex-column gap-1">
				<div class="d-flex align-items-center justify-content-between">
					<a href="javascript:void(0)" onclick="view_billing(this,event)" billing_id="{$_oBilling.billing_id}">{$_oBilling.stock_code}</a>
					{if !empty($is_edit)}
					<a class="text-link" title="chỉnh sửa thông tin" onclick="$Core.billing.add_info(this,event)" 
						billing_id="{$_oBilling.billing_id}"><i class="bx bx-edit fs-14"></i></a>
					{/if}
				</div>
				<h4 class="text-fs-12 mb-0 fw-bold">{$_oStaff.depart_name}-{$_oStaff.full_name}</h4>
			</div>
		</td>
		<td class="text-left">
			<div class="d-flex align-items-center gap-1 justify-content-start">
				<img class="rounded-pill avatar avatar-xs" src="{if !empty($_oAdmin)}{$clsProfile->getAvatar($_oAdmin.profile_id,$_oAdmin)}{/if}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'">
				<div class="d-flex flex-column">
					<h4 class="text-fs-12 mb-0 fw-bold">{$_oAdmin.full_name}</h4>
					<div class="d-flex align-items-center gap-1 text-fs-11 text-muted text-nowrap">
						<i class="material-icons-outlined fs-13 no-translate">more_time</i>
						{$_oBilling.time}
					</div>
				</div>
			</div>
		</td>
		<td class="text-center">{$_oBilling.status}</td>
		<td class="text-right">{$_oBilling.text_type}</td>
		{if $call_from eq '_sign_page'}
		<td class="text-center">
			{if !empty($_oBilling.is_note)}
			<a href="javascript:void(0)" class="btn btn-icon btn-outline-default btn-sm" data-toggle="webui-popover" data-trigger="click" data-type="async" billing_id="{$_oBilling.billing_id}" data-placement="left-bottom" data-closeable="false" data-url="{$PCMS}/index.php?mod=home&sub=calendar&act=load_rescheduling_reason&billing_id={$_oBilling.billing_id}">
				<i class='bx bx-notepad'></i>
			</a>
			{else}
			--
			{/if}
		</td>
		{/if}
	</tr>
	{/foreach}
{else}
	<tr class="trBilling">
		<td class="border-0 bg-transparent zindex-1" colspan="12">
			<div class="dbx-empty">
				<span class="dbx-empty__ic"><i class="bx bx-calendar-x"></i></span>
				<div class="dbx-empty__t">Chưa có lịch ký nào</div>
				<div class="dbx-empty__s">Lịch ký VBTT/HĐMB sẽ hiện ở đây</div>
			</div>
		</td>
	</tr>
{/if}