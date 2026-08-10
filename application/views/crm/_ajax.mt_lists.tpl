{* _ajax.mt_lists.tpl — Box danh sách: Khách mới / Chưa gọi / Quan tâm chưa chốt (Q1/Q2/Q4). *}
{* Dữ liệu: mt_new_list, mt_uncalled_list, mt_interested_list, mt_kpi.new_period/chua_data/quan_tam *}
<div class="row g-3 mb-3">
	<div class="col-12 col-lg-4"><div class="crm-ld-panel h-100">
		<div class="crm-ld-panel-h"><h4><i class="bx bx-user-plus"></i> Khách mới (<span class="js__mt-drill" data-metric="new_period">{$mt_kpi.new_period|default:0}</span>)</h4></div>
		<div class="px-3 pb-2 pt-1">
			{if $mt_new_list}{foreach from=$mt_new_list item=_x}
			<div class="crm-ld-urgent"><div class="avatar crm-ld-urgent-av"><span class="avatar-initial rounded-circle bg-label-primary">{$_x.initials|escape}</span></div><div class="min-w-0 flex-grow-1"><div class="crm-ld-urgent-nm"><a class="crm-ld-cuslink" customer_id="{$_x.customer_id}" onclick="$Core.crm.open_customer(this,event)" title="Xem chi tiết & hoạt động">{$_x.name|escape}</a></div><div class="crm-ld-urgent-sub"><i class="bx bx-user"></i> <span class="text-truncate">{$_x.sale|escape}</span>{if ($_x.tone != 'danger') && $_x.pill} <span class="crm-ld-urgent-sep">·</span> <i class="bx bx-time-five"></i> {$_x.pill|escape}{/if}</div></div>{if ($_x.tone == 'danger') && $_x.pill}<span class="crm-ld-urgent-d sev-{$_x.tone}">{$_x.pill|escape}</span>{/if}<a class="crm-ld-urgent-act" customer_id="{$_x.customer_id}" onclick="$Core.crm.view_activity(this,event)" title="Xem hoạt động"><i class="bx bx-bell"></i></a>{if $_x.phone}<a href="tel:{$_x.phone|escape}" class="crm-ld-urgent-call" title="Gọi {$_x.name|escape}"><i class="bx bx-phone"></i></a>{/if}</div>
			{/foreach}{else}<div class="text-center text-muted fs-12 py-2">Chưa có khách mới trong kỳ.</div>{/if}
		</div>
	</div></div>
	<div class="col-12 col-lg-4"><div class="crm-ld-panel h-100">
		<div class="crm-ld-panel-h"><h4><i class="bx bx-phone-off"></i> Khách chưa gọi (<span class="js__mt-drill" data-metric="chua_data">{$mt_kpi.chua_data|default:0}</span>)</h4></div>
		<div class="px-3 pb-2 pt-1">
			{if $mt_uncalled_list}{foreach from=$mt_uncalled_list item=_x}
			<div class="crm-ld-urgent"><div class="avatar crm-ld-urgent-av"><span class="avatar-initial rounded-circle bg-label-primary">{$_x.initials|escape}</span></div><div class="min-w-0 flex-grow-1"><div class="crm-ld-urgent-nm"><a class="crm-ld-cuslink" customer_id="{$_x.customer_id}" onclick="$Core.crm.open_customer(this,event)" title="Xem chi tiết & hoạt động">{$_x.name|escape}</a></div><div class="crm-ld-urgent-sub"><i class="bx bx-user"></i> <span class="text-truncate">{$_x.sale|escape}</span>{if ($_x.tone != 'danger') && $_x.pill} <span class="crm-ld-urgent-sep">·</span> <i class="bx bx-time-five"></i> {$_x.pill|escape}{/if}</div></div>{if ($_x.tone == 'danger') && $_x.pill}<span class="crm-ld-urgent-d sev-{$_x.tone}">{$_x.pill|escape}</span>{/if}<a class="crm-ld-urgent-act" customer_id="{$_x.customer_id}" onclick="$Core.crm.view_activity(this,event)" title="Xem hoạt động"><i class="bx bx-bell"></i></a>{if $_x.phone}<a href="tel:{$_x.phone|escape}" class="crm-ld-urgent-call" title="Gọi {$_x.name|escape}"><i class="bx bx-phone"></i></a>{/if}</div>
			{/foreach}{else}<div class="text-center text-muted fs-12 py-2">Mọi khách đều đã liên hệ.</div>{/if}
		</div>
	</div></div>
	<div class="col-12 col-lg-4"><div class="crm-ld-panel h-100">
		<div class="crm-ld-panel-h"><h4><i class="bx bx-star"></i> Quan tâm chưa chốt (<span class="js__mt-drill" data-metric="quan_tam">{$mt_kpi.quan_tam|default:0}</span>)</h4></div>
		<div class="px-3 pb-2 pt-1">
			{if $mt_interested_list}{foreach from=$mt_interested_list item=_x}
			<div class="crm-ld-urgent"><div class="avatar crm-ld-urgent-av"><span class="avatar-initial rounded-circle bg-label-primary">{$_x.initials|escape}</span></div><div class="min-w-0 flex-grow-1"><div class="crm-ld-urgent-nm"><a class="crm-ld-cuslink" customer_id="{$_x.customer_id}" onclick="$Core.crm.open_customer(this,event)" title="Xem chi tiết & hoạt động">{$_x.name|escape}</a>{if $_x.is_stuck}<span class="badge bg-label-danger fs-10 py-0 px-1 ms-1 align-middle" title="Khách ở trạng thái Quan tâm quá 10 ngày"><i class="bx bx-error-circle fs-10 align-middle"></i> Nghẽn {$_x.stuck_days}d</span>{/if}</div><div class="crm-ld-urgent-sub"><i class="bx bx-user"></i> <span class="text-truncate">{$_x.sale|escape}</span>{if ($_x.tone != 'danger') && $_x.pill} <span class="crm-ld-urgent-sep">·</span> <i class="bx bx-time-five"></i> {$_x.pill|escape}{/if}</div></div>{if ($_x.tone == 'danger') && $_x.pill}<span class="crm-ld-urgent-d sev-{$_x.tone}">{$_x.pill|escape}</span>{/if}<a class="crm-ld-urgent-act" customer_id="{$_x.customer_id}" onclick="$Core.crm.view_activity(this,event)" title="Xem hoạt động"><i class="bx bx-bell"></i></a>{if $_x.phone}<a href="tel:{$_x.phone|escape}" class="crm-ld-urgent-call" title="Gọi {$_x.name|escape}"><i class="bx bx-phone"></i></a>{/if}</div>
			{/foreach}{else}<div class="text-center text-muted fs-12 py-2">Không có khách Quan tâm tồn.</div>{/if}
		</div>
	</div></div>
</div>
