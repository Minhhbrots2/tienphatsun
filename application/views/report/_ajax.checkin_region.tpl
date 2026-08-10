{* Bảng "Theo dõi Check-in theo Vùng/đơn vị" — rc_mode: unit (tổng hợp) | person (nhân viên lá) *}
{assign var=RC_COLOR value=['success'=>'#71dd37','warning'=>'#ffab00','orange'=>'#ff6b1a','danger'=>'#ff3e1d']}
{assign var=RC_SOFT value=['success'=>'#e9fae0','warning'=>'#fff3d6','orange'=>'#ffe8dc','danger'=>'#ffe1dc']}
<div class="card" id="card_checkin_region">
	<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
		<h5 class="mb-0"><i class="bx bx-map-alt text-primary me-1"></i>Theo dõi Check-in theo Vùng</h5>
	</div>
	<div class="card-body">
	{if $rc_mode eq 'person'}
		{* ---------- Lá: danh sách nhân viên + trạng thái ---------- *}
		{if empty($rc_people)}
		<div class="text-center text-muted py-4">Không có nhân sự trong đơn vị</div>
		{else}
		<div class="table-container overflow-x-auto no-shadow text-nowrap">
			<table cellpadding="0" cellspacing="0" class="table table-sort table-bordered table_sortable rc-table">
				<thead>
					<tr>
						<th class="align-center h-px-35 bg-lighter">Nhân viên</th>
						<th class="align-center h-px-35 bg-lighter text-center">Giờ vào</th>
						<th class="align-center h-px-35 bg-lighter text-center">Giờ ra</th>
						<th class="align-center h-px-35 bg-lighter text-center">Trạng thái</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$rc_people item=_p}
					<tr>
						<td>
							<a href="javascript:void(0);" onclick="$Core.member.view_profile(this, event)" profile_id="{$_p.profile_id}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
								<img class="rounded-pill object-fit-cover" src="{$_p.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="34" height="34" alt="">
								<span><span class="fw-semibold d-block text-truncate" style="max-width:200px" title="{$_p.full_name}">{$_p.full_name}</span><small class="text-muted">{$_p.department_name}</small></span>
							</a>
						</td>
						<td class="text-center">{if $_p.time}<span class="fw-semibold">{$_p.time}</span>{else}<span class="text-muted">--</span>{/if}</td>
							<td class="text-center">{if $_p.time_out eq 'Chưa check-out'}<span class="text-warning fw-semibold">Chưa check-out</span>{elseif $_p.time_out && $_p.time_out neq '--'}<span class="fw-semibold">{$_p.time_out}</span>{else}<span class="text-muted">--</span>{/if}</td>
						<td class="text-center">
							{if $_p.status eq 'dung'}<span class="badge bg-label-success">Đúng giờ</span>
							{elseif $_p.status eq 'muon'}<span class="badge bg-label-warning">Đi muộn</span>
							{else}<span class="badge bg-label-danger">Chưa check-in</span>{/if}
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
		{/if}
	{else}
		{* ---------- Bảng tổng hợp đơn vị ---------- *}
		{if empty($rc_units)}
		<div class="text-center text-muted py-4">Không có đơn vị</div>
		{else}
		<div class="table-container overflow-x-auto no-shadow text-nowrap">
			<table cellpadding="0" cellspacing="0" class="table table-sort table-bordered table_sortable rc-table">
				<thead>
					<tr>
						<th class="align-center h-px-35 bg-lighter">Vùng kinh doanh</th>
						<th class="align-center h-px-35 bg-lighter text-center">Tổng nhân sự</th>
						<th class="align-center h-px-35 bg-lighter text-center">Check-In (Vào)</th>
						<th class="align-center h-px-35 bg-lighter text-center">Đúng giờ</th>
						<th class="align-center h-px-35 bg-lighter text-center">Đi muộn</th>
						<th class="align-center h-px-35 bg-lighter text-center">Chưa check-in</th>
						<th class="align-center h-px-35 bg-lighter text-center">Check-out (Ra)</th>
						<th class="align-center h-px-35 bg-lighter text-center">Về sớm</th>
						<th class="align-center h-px-35 bg-lighter text-center">Tỷ lệ</th>
						<th class="align-center h-px-35 bg-lighter">Leader</th>
						<th class="align-center h-px-35 bg-lighter text-center">Chi tiết</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$rc_units item=_u}
					<tr>
						<td><span class="rc-dot" style="background:{$RC_COLOR[$_u.tier]}"></span> <span class="fw-semibold">{$_u.title}</span></td>
						<td class="text-center">{if $_u.total > 0}<a href="javascript:void(0);" class="rc-lnk fw-semibold text-dark" title="Danh sách nhân sự" data-type="all" data-department="{$_u.id}" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)">{$_u.total}<i class="bx bx-link-external rc-ic"></i></a>{else}<span class="text-muted">0</span>{/if}</td>
						<td class="text-center">{if $_u.dacI > 0}<a href="javascript:void(0);" class="rc-lnk fw-semibold text-success" title="Danh sách đã check-in" data-type="has_checkin" data-department="{$_u.id}" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)">{$_u.dacI}<i class="bx bx-link-external rc-ic"></i></a>{else}<span class="text-muted">0</span>{/if}</td>
						<td class="text-center">{if $_u.dung > 0}<a href="javascript:void(0);" class="rc-lnk fw-semibold text-success" title="Danh sách đúng giờ" data-type="on_time" data-department="{$_u.id}" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)">{$_u.dung}<i class="bx bx-link-external rc-ic"></i></a>{else}<span class="text-muted">0</span>{/if}</td>
						<td class="text-center">{if $_u.muon > 0}<a href="javascript:void(0);" class="rc-lnk fw-semibold" style="color:#ff9f43" title="Danh sách đi muộn" data-type="late" data-department="{$_u.id}" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)">{$_u.muon}<i class="bx bx-link-external rc-ic"></i></a>{else}<span class="text-muted">0</span>{/if}</td>
						<td class="text-center">{if $_u.chua > 0}<a href="javascript:void(0);" class="rc-lnk fw-semibold text-danger" title="Danh sách chưa check-in" data-type="not_checkin" data-department="{$_u.id}" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)">{$_u.chua}<i class="bx bx-link-external rc-ic"></i></a>{else}<span class="text-muted">0</span>{/if}</td>
						<td class="text-center">{if $_u.cout > 0}<a href="javascript:void(0);" class="rc-lnk fw-semibold text-info" title="Danh sách đã check-out" data-type="checked_out" data-department="{$_u.id}" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)">{$_u.cout}<i class="bx bx-link-external rc-ic"></i></a>{else}<span class="text-muted">0</span>{/if}</td>
						<td class="text-center">{if $_u.early > 0}<a href="javascript:void(0);" class="rc-lnk fw-semibold" style="color:#ff6b1a" title="Danh sách về sớm" data-type="early_leave" data-department="{$_u.id}" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)">{$_u.early}<i class="bx bx-link-external rc-ic"></i></a>{else}<span class="text-muted">0</span>{/if}</td>
						<td class="text-center"><span class="badge" style="color:{$RC_COLOR[$_u.tier]};background:{$RC_SOFT[$_u.tier]}">{$_u.rate}%</span></td>
						<td>{if $_u.leader_name}<a href="javascript:void(0);" onclick="$Core.member.view_profile(this, event)" profile_id="{$_u.head}" class="d-flex align-items-center gap-2 text-decoration-none text-dark"><img class="rounded-pill object-fit-cover" src="{$_u.leader_avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="30" height="30" alt=""><span class="fw-semibold text-truncate" style="max-width:130px" title="{$_u.leader_name}">{$_u.leader_name}</span></a>{else}<span class="text-muted">--</span>{/if}</td>
						<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-secondary" title="Danh sách nhân sự" data-type="all" data-department="{$_u.id}" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)"><i class="bx bx-chevron-right"></i></a></td>
					</tr>
					{/foreach}
				</tbody>
				{if $rc_total}
				<tfoot>
					<tr class="fw-bold border-top">
						<td class="text-primary">Tổng</td>
						<td class="text-center text-primary">{$rc_total.total}</td>
						<td class="text-center text-primary">{$rc_total.dacI}</td>
						<td class="text-center text-primary">{$rc_total.dung}</td>
						<td class="text-center" style="color:#ff9f43">{$rc_total.muon}</td>
						<td class="text-center text-danger">{$rc_total.chua}</td>
						<td class="text-center text-info">{$rc_total.cout}</td>
						<td class="text-center" style="color:#ff6b1a">{$rc_total.early}</td>
						<td class="text-center"><span class="fw-bold" style="color:{$RC_COLOR[$rc_total.tier]}">{$rc_total.rate}%</span></td>
						<td colspan="2"></td>
					</tr>
				</tfoot>
				{/if}
			</table>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-3 mt-3 fs-12 text-muted">
			<span class="fw-semibold">Chú thích:</span>
			<span><span class="rc-dot" style="background:#71dd37"></span> &gt; 95%</span>
			<span><span class="rc-dot" style="background:#ffab00"></span> 85% - 95%</span>
			<span><span class="rc-dot" style="background:#ff6b1a"></span> 70% - 85%</span>
			<span><span class="rc-dot" style="background:#ff3e1d"></span> &lt; 70%</span>
		</div>
		{/if}
	{/if}
	</div>
</div>
{literal}
<style>
#card_checkin_region .rc-dot{display:inline-block;width:10px;height:10px;border-radius:50%;vertical-align:middle;margin-right:3px}
#card_checkin_region .rc-table th{font-size:12px;font-weight:600;color:#6f7b8a;white-space:nowrap}
#card_checkin_region .rc-table td{font-size:13px}
#card_checkin_region .rc-lnk{text-decoration:none;cursor:pointer}
#card_checkin_region .rc-lnk:hover{text-decoration:underline}
#card_checkin_region .rc-ic{font-size:11px;opacity:.5;margin-left:3px;vertical-align:middle}
</style>
{/literal}
