{* _ajax.mt_tables.tpl — Box bảng hiệu suất: theo Phòng Kinh doanh + theo nhân viên. Dữ liệu: mt_dept_performance, mt_rows *}
<!-- ===== Bảng hiệu suất theo Phòng Kinh doanh ===== -->
<div class="crm-ld-panel mb-3">
	<div class="crm-ld-panel-h"><h4><i class="bx bx-buildings"></i> Hiệu suất chăm sóc theo Phòng Kinh doanh</h4><span class="crm-ld-hint">tổng hợp theo nhóm</span></div>
	{if $mt_dept_performance}
	<div class="crm-ld-tablescroll">
		<table class="table table-hover table-sm table-middle mb-0 align-middle crm-ld-teamtable">
			<thead><tr class="fs-12 text-muted">
				<th class="text-center" width="50">#</th>
				<th class="text-left">Phòng Kinh doanh</th>
				<th class="text-left">Trưởng phòng</th>
				<th class="text-center">Số nhân sự</th>
				<th class="text-center">Tổng active</th>
				<th class="text-center">Đang chăm</th>
				<th class="text-center">Bỏ quên</th>
				<th class="text-center">Deal kỳ</th>
				<th class="text-center">Tỷ lệ chuyển đổi</th>
			</tr></thead>
			{foreach from=$mt_dept_performance item=_d name=dt}
			<tr>
				<td class="text-center text-muted fs-12">{$smarty.foreach.dt.iteration}</td>
				<td class="text-left text-nowrap"><span class="fs-12 fw-bold text-primary">{$_d.dept_name|escape}</span></td>
				<td class="text-left text-nowrap"><span class="fs-12 fw-semibold">{$_d.manager_name|escape}</span></td>
				<td class="text-center fs-12">{$_d.rep_count}</td>
				<td class="text-center fs-12 fw-bold{if $_d.dept_id != 9999} js__mt-drill{/if}" data-metric="active" data-dept="{$_d.dept_id}">{$_d.active}</td>
				<td class="text-center fs-12{if $_d.dept_id != 9999} js__mt-drill{/if}" data-metric="dang_cham" data-dept="{$_d.dept_id}">{if $_d.dang_cham > 0}<span class="text-success fw-bold">{$_d.dang_cham}</span>{else}<span class="text-muted">0</span>{/if}</td>
				<td class="text-center fs-12{if $_d.dept_id != 9999} js__mt-drill{/if}" data-metric="bo_quen" data-dept="{$_d.dept_id}">{if $_d.bo_quen > 0}<span class="badge bg-label-danger">{$_d.bo_quen}</span>{else}<span class="text-muted">0</span>{/if}</td>
				<td class="text-center fs-12{if $_d.dept_id != 9999} js__mt-drill{/if}" data-metric="deal_period" data-dept="{$_d.dept_id}">{if $_d.chot_period > 0}<span class="badge bg-label-success">{$_d.chot_period}</span>{else}<span class="text-muted">0</span>{/if}</td>
				<td class="text-center fs-12">{if $_d.conv_rate > 0}<span class="fw-bold">{$_d.conv_rate}%</span>{else}<span class="text-muted">0%</span>{/if}</td>
			</tr>
			{/foreach}
		</table>
	</div>
	{else}
	<div class="border text-center rounded-2 p-3 m-3 border-dashed text-muted">Chưa có dữ liệu phòng ban.</div>
	{/if}
</div>
<!-- ===== Bảng hiệu suất theo cá nhân nhân viên ===== -->
<div class="crm-ld-panel mb-3">
	<div class="crm-ld-panel-h"><h4><i class="bx bx-group"></i> Hiệu suất chăm sóc theo nhân viên</h4><span class="crm-ld-hint">xếp theo tồn đọng</span></div>
	{if $mt_rows}
	<div class="crm-ld-tablescroll">
		<table class="table table-hover table-sm table-middle mb-0 align-middle crm-ld-teamtable">
			<thead><tr class="fs-12 text-muted">
				<th class="text-center" width="50">#</th>
				<th class="text-left js__mt-th-sort">Nhân sự</th>
				<th class="text-center js__mt-th-sort">Tổng active</th>
				<th class="text-center js__mt-th-sort">Đang chăm</th>
				<th class="text-center js__mt-th-sort">Bỏ quên</th>
				<th class="text-center js__mt-th-sort">Deal kỳ</th>
				<th class="text-center js__mt-th-sort">Tỷ lệ chuyển đổi</th>
				<th class="js__mt-th-sort">Hoạt động gần nhất</th>
				<th class="text-center js__mt-th-sort">Sức khoẻ</th>
			</tr></thead>
			{foreach from=$mt_rows item=_r name=mt}
			<tr{if $smarty.foreach.mt.iteration > 10} class="d-none js__mt-rep-more"{/if}>
				<td class="text-center text-muted fs-12">{$smarty.foreach.mt.iteration}</td>
				<td class="text-left text-nowrap" data-sort="{$_r.rep_name|escape}">
					<img class="avatar avatar-xs rounded-pill me-1" src="{$_r.rep_avatar|escape}">
					<a href="/crm/?tab=team&admin_id={$_r.rep_id}" class="fs-12 fw-bold text-dark hover-primary" title="Xem danh sách khách của {$_r.rep_name|escape}">{$_r.rep_name|escape}</a>
				</td>
				<td class="text-center fs-12 fw-bold js__mt-drill" data-metric="active" data-rep="{$_r.rep_id}" data-sort="{$_r.active}">{$_r.active}</td>
				<td class="text-center fs-12 js__mt-drill" data-metric="dang_cham" data-rep="{$_r.rep_id}" data-sort="{$_r.dang_cham}">{if $_r.dang_cham > 0}<span class="text-success fw-bold">{$_r.dang_cham}</span>{else}<span class="text-muted">0</span>{/if}</td>
				<td class="text-center fs-12 js__mt-drill" data-metric="bo_quen" data-rep="{$_r.rep_id}" data-sort="{$_r.bo_quen}">{if $_r.bo_quen > 0}<span class="badge bg-label-danger">{$_r.bo_quen}</span>{else}<span class="text-muted">0</span>{/if}</td>
				<td class="text-center fs-12 js__mt-drill" data-metric="deal_period" data-rep="{$_r.rep_id}" data-sort="{$_r.chot_period}">{if $_r.chot_period > 0}<span class="badge bg-label-success">{$_r.chot_period}</span>{else}<span class="text-muted">0</span>{/if}</td>
				<td class="text-center fs-12" data-sort="{$_r.conv_rate}">{if $_r.conv_rate > 0}<span class="fw-bold">{$_r.conv_rate}%</span>{else}<span class="text-muted">0%</span>{/if}</td>
				<td class="fs-11 text-nowrap" data-sort="{if $_r.days_idle < 0}999999{else}{$_r.days_idle}{/if}">{if $_r.days_idle < 0}<span class="text-muted">Chưa có</span>{else}<span class="{if $_r.days_idle >= 7}text-danger fw-bold{elseif $_r.days_idle >= 3}text-warning{/if}">{$_r.last_text}</span>{/if}</td>
				<td class="text-center text-nowrap" data-sort="{if $_r.htone eq 'danger'}3{elseif $_r.htone eq 'warning'}2{else}1{/if}"><span class="badge bg-label-{$_r.htone}">{$_r.health}</span></td>
			</tr>
			{/foreach}
		</table>
	</div>
	{assign var="_repTotal" value=$mt_rows|@count}
	{if $_repTotal > 10}
	<div class="text-center pb-3 pt-1 js__mt-rep-more-wrap">
		<button type="button" class="btn btn-sm btn-outline-default js__mt-rep-showmore">
			<i class="bx bx-chevron-down"></i> Xem thêm {math equation="x-10" x=$_repTotal} nhân viên
		</button>
	</div>
	{/if}
	{else}
	<div class="border text-center rounded-2 p-3 m-3 border-dashed text-muted">Chưa có nhân sự trong phạm vi.</div>
	{/if}
</div>
