{* my_team.tpl — /crm/team/ (act=my_team) — Leader Dashboard SHELL. Mỗi box nội dung load AJAX qua .ajax.js__mt-box.
   Shell chỉ render: gate, scope-empty alert, header (title + period pills + daterange).
   Box nội dung: act=mt_overview, mt_lists, mt_donut, mt_urgent, mt_momentum, mt_feed. *}
<div class="container-xxl flex-grow-1 container-p-y pt-2 crm_page crm-ld">
	{if !$team_can}
		<div class="alert alert-warning d-flex align-items-center gap-2 mt-3" role="alert">
			<i class="bx bx-lock-alt fs-4"></i>
			<div>Chỉ <b>Giám đốc Kinh doanh / Giám đốc Vùng / Ban điều hành</b> mới xem được màn Giám sát đội ngũ. <a href="/crm/" class="alert-link">Về danh sách khách</a>.</div>
		</div>
	{else}
		<!-- ===== Header ===== -->
		<div class="d-flex flex-wrap align-items-end justify-content-between mb-3 gap-2 crm-ld-head">
			<div>
				<h4 class="crm-ld-title mb-0">Giám sát đội ngũ</h4>
				<div class="crm-ld-sub">{$mt_total_reps} nhân sự · kỳ: {$mt_period_label} — phạm vi theo phòng ban.</div>
			</div>
			<div class="d-flex gap-2 align-items-center flex-wrap crm-ld-filter">
				{if $mt_show_dept}
				<select class="form-select form-select-sm crm-ld-deptsel js__mt-dept" aria-label="Chọn vùng / phòng kinh doanh">
					<option value="0"{if !$mt_dept_sel} selected{/if}>{if $mt_is_see_all}Tất cả vùng / phòng KD{else}Tất cả phòng KD{/if}</option>
					{foreach from=$mt_departments item=_oDep}
					<option value="{$_oDep.property_id}"{if $mt_dept_sel eq $_oDep.property_id} selected{/if}>{$_oDep.title|escape}</option>
					{if !empty($_oDep.children)}
						{foreach from=$_oDep.children item=_oChild}
						<option value="{$_oChild.property_id}"{if $mt_dept_sel eq $_oChild.property_id} selected{/if}>-- {$_oChild.title|escape}</option>
						{/foreach}
					{/if}
					{/foreach}
				</select>
				{/if}
				<div class="crm-ld-time">
					<div class="crm-ld-pills">
						<button type="button" class="crm-ld-pill-btn js__mt-period{if $mt_period eq 'today'} active{/if}" data-period="today">Hôm nay</button>
						<button type="button" class="crm-ld-pill-btn js__mt-period{if $mt_period eq 'this_week'} active{/if}" data-period="this_week">Tuần này</button>
						<button type="button" class="crm-ld-pill-btn js__mt-period{if $mt_period eq 'this_month'} active{/if}" data-period="this_month">Tháng này</button>
					</div>
					<div class="crm-ld-daterange">
						<i class="bx bx-calendar"></i>
						<input type="date" class="js__mt-dr-from" value="{$mt_from}">
						<i class="bx bx-right-arrow-alt crm-ld-dr-arrow"></i>
						<input type="date" class="js__mt-dr-to" value="{$mt_to}">
						<button type="button" class="crm-ld-dr-go js__mt-daterange-go">Áp dụng</button>
					</div>
				</div>
			</div>
		</div>
		{if $mt_scope_empty}
			<div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
				<i class="bx bx-error-circle fs-5"></i>
				<div>Không tìm thấy nhân sự trong phòng ban của bạn (chưa gán <b>department_id</b> hoặc node không hợp lệ). Liên hệ quản trị.</div>
			</div>
		{else}
			<!-- ===== AJAX box placeholders ===== -->
			<div class="js__mt-box mb-3" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=mt_overview" data-options='{ldelim}{rdelim}'>
				<div class="crm-ld-skeleton">Đang tải tổng quan…</div>
			</div>
			<div class="js__mt-box mb-3" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=mt_lists" data-options='{ldelim}{rdelim}'>
				<div class="crm-ld-skeleton">Đang tải danh sách khách…</div>
			</div>
			<div class="js__mt-box mb-3" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=mt_tables" data-options='{ldelim}{rdelim}'>
				<div class="crm-ld-skeleton">Đang tải bảng hiệu suất…</div>
			</div>
			<div class="row g-3 mb-3">
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<div class="js__mt-box h-100" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=mt_donut" data-options='{ldelim}{rdelim}'>
						<div class="crm-ld-skeleton">Đang tải phân bố…</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="js__mt-box h-100" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=mt_momentum" data-options='{ldelim}{rdelim}'>
						<div class="crm-ld-skeleton">Đang tải chuyển trạng thái…</div>
					</div>
				</div>
			</div>
			<div class="row g-3">
				<div class="col-12 col-md-6">
					<div class="js__mt-box h-100" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=mt_urgent" data-options='{ldelim}{rdelim}'>
						<div class="crm-ld-skeleton">Đang tải khách cần xử lý…</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="js__mt-box h-100" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=mt_feed" data-options='{ldelim}{rdelim}'>
						<div class="crm-ld-skeleton">Đang tải hoạt động…</div>
					</div>
				</div>
			</div>
		{/if}
	{/if}
</div>
{$scriptJs}
