{* _ajax.mt_feed.tpl — Box feed hoạt động gần nhất của phòng. *}
{* Dữ liệu: mt_feed *}
<div class="crm-ld-panel h-100">
	<div class="crm-ld-panel-h"><h4><i class="bx bx-history"></i> Hoạt động gần nhất</h4></div>
	<div class="px-3 pb-2 pt-1">
		{if $mt_feed}
		{foreach from=$mt_feed item=_f}
		<div class="crm-ld-urgent"><div class="avatar crm-ld-urgent-av"><span class="avatar-initial rounded-circle bg-label-primary">{$_f.initials|escape}</span></div><div class="min-w-0 flex-grow-1"><div class="crm-ld-urgent-nm"><b>{$_f.sale|escape}</b> · {$_f.type|escape}</div><div class="crm-ld-urgent-sub"><i class="bx bx-user"></i> <a class="crm-ld-cuslink text-truncate" customer_id="{$_f.customer_id}" onclick="$Core.crm.open_customer(this,event)" title="Xem chi tiết & hoạt động">{$_f.cus|escape}</a> <span class="crm-ld-urgent-sep">·</span> <i class="bx bx-time-five"></i> {$_f.time}</div></div><a class="crm-ld-urgent-act" customer_id="{$_f.customer_id}" onclick="$Core.crm.view_activity(this,event)" title="Xem hoạt động"><i class="bx bx-bell"></i></a><span class="badge bg-label-{$_f.tone} flex-shrink-0">{$_f.badge|escape}</span></div>
		{/foreach}
		{else}
		<div class="text-center text-muted fs-12 py-2">Chưa có hoạt động.</div>
		{/if}
	</div>
</div>
