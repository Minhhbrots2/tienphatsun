{* T2 — drill-down từng khách của 1 tư vấn viên. Render bởi default_load_care_monitor_detail(). data-label cho mobile. *}
<div class="cm-detail-inner p-2">
	<div class="d-flex align-items-center justify-content-between mb-1 px-1">
		<span class="fs-11 text-muted"><i class="bx bx-subdirectory-right"></i> Chi tiết khách đang chăm</span>
		<span class="fs-11 text-muted">{if $cd_total > $cd_limit}Hiển thị {$cd_limit}/{$cd_total} khách của tư vấn viên{else}{$cd_total} khách của tư vấn viên{/if}</span>
	</div>
	{if $cd_rows}
	<div class="table-responsive">
		<table class="table table-sm align-middle mb-0 crm-cm-detail-table" width="100%">
			<thead><tr class="fs-11 text-muted">
				<th class="text-left">Khách</th>
				<th>Trạng thái</th>
				<th>Ngày vào/giao</th>
				<th>Chăm gần nhất</th>
				<th class="text-end">Lần chăm</th>
				<th class="text-end">Idle</th>
				<th class="text-end">Quá hạn</th>
				<th class="text-end">TTFt</th>
			</tr></thead>
			<tbody>
			{foreach from=$cd_rows item=_c}
			<tr>
				<td class="text-left cm-d-name"><span class="fs-12 fw-semibold">{$_c.name|escape}</span>{if $_c.phone_mask} <span class="fs-11 text-muted">{$_c.phone_mask|escape}</span>{/if}</td>
				<td data-label="Trạng thái">{if $_c.status_title}<span class="badge" style="background:{$_c.status_bg|escape};color:{$_c.status_color|escape}">{$_c.status_title|escape}</span>{else}<span class="text-muted fs-11">—</span>{/if}</td>
				<td class="fs-11 text-nowrap" data-label="Ngày vào/giao">{if $_c.assigned_text eq '—'}<span class="text-muted">—</span>{else}{if $_c.assigned_is_proxy}<span class="text-muted" title="Ước lượng theo ngày tạo khách">~</span> {/if}{$_c.assigned_text}{/if}</td>
				<td class="fs-11 text-nowrap" data-label="Chăm gần nhất">{if $_c.days_idle >= 7}<span class="text-danger fw-bold">{$_c.last_contact}</span>{elseif $_c.days_idle >= 3}<span class="text-warning">{$_c.last_contact}</span>{else}{$_c.last_contact}{/if}</td>
				<td class="text-end fs-12 text-nowrap" data-label="Lần chăm">{$_c.fu_count} <span class="text-muted">· <i class="bx bx-phone"></i>{$_c.fu_call} <i class="bx bx-message-rounded"></i>{$_c.fu_zalo}</span></td>
				<td class="text-end fs-12" data-label="Idle">{if $_c.days_idle < 0}<span class="text-muted">—</span>{else}<span class="{if $_c.days_idle >= 7}text-danger fw-bold{/if}">{$_c.days_idle}</span>{/if}</td>
				<td class="text-end fs-12" data-label="Quá hạn">{if $_c.overdue > 0}<span class="badge bg-label-danger">{$_c.overdue}</span>{else}<span class="text-muted">0</span>{/if}</td>
				<td class="text-end fs-11" data-label="TTFt">{$_c.ttft_text}</td>
			</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
	{else}
	<div class="text-center text-muted fs-12 py-3">Không có khách phù hợp bộ lọc.</div>
	{/if}
</div>
