{* F1 — Hàng đợi việc của tôi (work-queue cockpit). Render bởi default_load_worklist(). *}
<div class="crm-worklist">
	{foreach from=$buckets item=_b}
	<div class="mb-3">
		<div class="d-flex align-items-center gap-2 alert-message bg-label-{$_b.color} p-2 rounded-2 mb-2">
			<div class="w-px-30 p-2"><i class="fa fa-{$_b.icon} text-fs-20"></i></div>
			<div class="d-flex flex-column flex-grow-1">
				<h3 class="mb-0 text-fs-14">{$_b.title} <span class="badge bg-{$_b.color} ms-1">{$_b.count}</span></h3>
			</div>
		</div>
		{if $_b.rows}
		<table class="table table-no-border-end table-middle mb-0" width="100%">
			{foreach from=$_b.rows item=_o}
			<tr customer_id="{$_o.customer_id}">
				<td class="text-left">
					<a href="javascript:void(0);" class="link goLink font-bold view_customer" onClick="$Core.crm.open_customer(this,event)" route="/customer/{$_o.customer_id}/overview" customer_id="{$_o.customer_id}">{$_o.name|escape}</a>
					{if $_o.intro}<div class="fs-11 text-muted text-truncate" style="max-width:260px">{$_o.intro|escape}</div>{/if}
				</td>
				<td class="text-nowrap">{if $_o.phone}{$_o.phone_html}{else}---{/if}</td>
				<td class="text-nowrap fs-12 {$_o.time_class}">{$_o.time_text}</td>
				<td class="text-end">
					<div class="btn-group">
						<a title="Thêm lịch" class="btn btn-icon btn-sm btn-outline-default" customer_id="{$_o.customer_id}" onclick="$Core.crm.open_activity(this, event)" tp="follow-ups" type_id="0"><i class="bx bx-plus"></i></a>
						<a title="Hoạt động" class="btn btn-icon btn-sm btn-outline-default" customer_id="{$_o.customer_id}" onclick="$Core.crm.view_activity(this, event)"><i class="bx bx-bell"></i></a>
					</div>
				</td>
			</tr>
			{/foreach}
		</table>
		{if $_b.count > $_b.limit}<div class="text-center fs-11 text-muted py-1">Hiển thị {$_b.limit}/{$_b.count} — lọc danh sách để xem thêm</div>{/if}
		{else}
		<div class="border text-center rounded-2 p-3 border-dashed text-muted">{$_b.empty}</div>
		{/if}
	</div>
	{/foreach}
</div>
