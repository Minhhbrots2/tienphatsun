{* _ajax.mt_urgent.tpl — Box khách cần xử lý ngay (active quá hạn > 2 ngày). *}
{* Dữ liệu: mt_urgent *}
<div class="crm-ld-panel h-100">
	<div class="crm-ld-panel-h"><h4><i class="bx bx-phone-call"></i> Khách cần xử lý ngay</h4></div>
	<div class="px-3 pb-2 pt-1">
		{if $mt_urgent}
		{foreach from=$mt_urgent item=_u}
		<div class="crm-ld-urgent"><div class="avatar crm-ld-urgent-av"><span class="avatar-initial rounded-circle bg-label-primary">{$_u.initials|escape}</span></div><div class="min-w-0 flex-grow-1"><div class="crm-ld-urgent-nm"><a class="crm-ld-cuslink" customer_id="{$_u.customer_id}" onclick="$Core.crm.open_customer(this,event)" title="Xem chi tiết & hoạt động">{$_u.name|escape}</a></div><div class="crm-ld-urgent-sub"><i class="bx bx-user"></i> <span class="text-truncate">{$_u.sale|escape}</span></div></div><span class="crm-ld-urgent-d sev-{$_u.tone}">{$_u.days} ngày</span><a class="crm-ld-urgent-act" customer_id="{$_u.customer_id}" onclick="$Core.crm.view_activity(this,event)" title="Xem hoạt động"><i class="bx bx-bell"></i></a>{if $_u.phone}<a href="tel:{$_u.phone|escape}" class="crm-ld-urgent-call" title="Gọi {$_u.name|escape}"><i class="bx bx-phone"></i></a>{/if}</div>
		{/foreach}
		{else}
		<div class="text-center text-muted fs-12 py-3">Không có khách tồn đọng quá hạn.</div>
		{/if}
	</div>
</div>