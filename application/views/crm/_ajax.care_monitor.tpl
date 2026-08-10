{* T1 — bảng tổng hợp per-Sale. Render bởi default_load_care_monitor(). Bấm dòng → xổ chi tiết (T2). data-label cho mobile (thẻ gọn). *}
<div class="crm-ld-panel">
	<div class="crm-ld-panel-h">
		<h4><i class="bx bx-pulse"></i> Chăm sóc theo tư vấn viên <span class="ff-num text-muted fw-normal" style="font-size:12px">· {$cm_total} người · {$cm_period_label}</span></h4>
		<span class="crm-ld-hint"><i class="bx bx-phone"></i> gọi · <i class="bx bx-message-rounded"></i> Zalo · TTFt = phản hồi đầu · xếp theo tồn đọng</span>
	</div>
	{if $cm_rows}
	<div class="table-responsive">
		<table class="table table-hover table-sm table-middle align-middle mb-0 crm-cm-table" width="100%">
			<thead><tr class="fs-12 text-muted">
				<th width="28"></th>
				<th class="text-left">Tư vấn viên</th>
				<th class="text-end">Khách</th>
				<th class="text-end">Lần chăm</th>
				<th>Chạm gần nhất</th>
				<th class="text-end">Quá hạn</th>
				<th class="text-end">Chưa chạm</th>
				<th class="text-end">TTFt</th>
			</tr></thead>
			<tbody>
			{foreach from=$cm_rows item=_r}
			<tr class="cm-row" data-rep="{$_r.rep_id}" onClick="$Core.crm.care_monitor_detail(this)">
				<td class="text-center cm-c-chevron"><i class="bx bx-chevron-right cm-chevron"></i></td>
				<td class="text-left text-nowrap cm-c-name"><img class="avatar avatar-xs rounded-pill me-1" src="{$_r.rep_avatar|escape}"><span class="fs-12 fw-bold">{$_r.rep_name|escape}</span></td>
				<td class="text-end fs-12 text-nowrap" data-label="Khách">{$_r.total_cus} · <b>{$_r.active}</b></td>
				<td class="text-end fs-12 text-nowrap" data-label="Lần chăm">{$_r.act_total} <span class="text-muted">· <i class="bx bx-phone"></i>{$_r.act_call} <i class="bx bx-message-rounded"></i>{$_r.act_zalo}</span></td>
				<td class="fs-11 text-nowrap" data-label="Chạm gần nhất">{if $_r.days_idle < 0}<span class="text-muted">Chưa có</span>{else}<span class="{if $_r.days_idle >= 7}text-danger fw-bold{elseif $_r.days_idle >= 3}text-warning{/if}">{$_r.last_text}</span>{/if}</td>
				<td class="text-end fs-12" data-label="Quá hạn">{if $_r.overdue > 0}<span class="badge bg-label-danger">{$_r.overdue}</span>{else}<span class="text-muted">0</span>{/if}</td>
				<td class="text-end fs-12" data-label="Chưa chạm">{if $_r.no_touch > 0}<span class="text-warning fw-bold">{$_r.no_touch}</span>{else}<span class="text-muted">0</span>{/if}</td>
				<td class="text-end fs-11" data-label="TTFt">{$_r.ttft_text}</td>
			</tr>
			<tr class="cm-detail d-none" data-rep="{$_r.rep_id}">
				<td colspan="8" class="p-0"><div class="cm-detail-box" id="cm_detail_{$_r.rep_id}"></div></td>
			</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
	{else}
	<div class="border text-center rounded-2 p-3 border-dashed text-muted">Chưa có tư vấn viên nào trong phạm vi của bạn.</div>
	{/if}
</div>
