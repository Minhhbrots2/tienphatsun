<div class="container-xxl flex-grow-1 pt-2 container-p-y crm-mktpool">

	{* ===== Header (tái dùng .crm-eyebrow / .crm-page-title / .crm-page-desc) ===== *}
	<div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
		<div class="d-flex flex-column">
			<span class="crm-eyebrow"><i class="bx bx-store-alt me-1"></i>Sale thị trường · Pool tài khoản</span>
			<h4 class="crm-page-title fw-bold mb-1">Sale thị trường → CRM</h4>
			<p class="crm-page-desc text-muted mb-0">Lọc tài khoản <strong>MOC / MyFuture</strong>, chọn nhiều dòng rồi chuyển thẳng vào CRM &amp; gán cho bạn.</p>
		</div>
		<button type="button" class="btn btn-sm btn-outline-secondary text-nowrap" onclick="$Core.crm.mktpool_load()">
			<i class="bx bx-revision me-1"></i> Làm mới
		</button>
	</div>

	{if !empty($denied)}
		<div class="alert alert-danger d-flex align-items-center gap-2 mb-0" role="alert">
			<i class="bx bx-lock-alt fs-4"></i>
			<span>Bạn không có quyền truy cập màn này.</span>
		</div>
	{else}

	{* ===== KPI (nạp AJAX riêng, nằm TRÊN bộ lọc) ===== *}
	<div id="box_mkt_kpi"></div>

	{* ===== Card: bộ lọc (card-header) + bảng + phân trang (#box_mktpool nạp AJAX) ===== *}
	<div class="card">
		<div class="card-header border-bottom pb-3">
			{if empty($has_link_col)}
				<div class="alert alert-warning d-flex align-items-start gap-2 py-2 mb-3 fs-13 mktpool-alert" role="alert">
					<i class="bx bx-info-circle fs-5 lh-1 mt-1"></i>
					<span>Chưa chạy <code>ALTER … ADD member_profile_id</code> (GĐ1) — cờ "Đã chuyển" tạm dựa theo SĐT; bộ lọc "chỉ chưa chuyển" tạm ẩn.</span>
				</div>
			{/if}
			<form class="mktpool-filter" onsubmit="return false;">
				<div class="row g-2 align-items-center">
					<div class="col-12 col-md-3 col-xl-2">
						<div class="mktpool-search">
							<i class="bx bx-search"></i>
							<input type="text" name="kw" value="{$f.kw|escape:'html'}" class="form-control form-control-sm border-0 shadow-none" placeholder="Tìm tên hoặc số điện thoại…" autocomplete="off" oninput="$Core.crm.mktpool_search()">
						</div>
					</div>
					<div class="col-6 col-md-3 col-xl-2">
						<select name="f_ptype" class="form-select form-select-sm" onchange="$Core.crm.mktpool_load(1)">
							<option value="">Nguồn: tất cả</option>
							<option value="MOC" {if $f.f_ptype eq 'MOC'}selected{/if}>MOC</option>
							<option value="MF" {if $f.f_ptype eq 'MF'}selected{/if}>MyFuture</option>
						</select>
					</div>
					<div class="col-6 col-md-3 col-xl-2">
						<select name="f_tacc" class="form-select form-select-sm" onchange="$Core.crm.mktpool_load(1)">
							<option value="">Loại TK: tất cả</option>
							<option value="2" {if $f.f_tacc eq '2'}selected{/if}>Môi giới (seller)</option>
							<option value="1" {if $f.f_tacc eq '1'}selected{/if}>Khách (resident)</option>
							<option value="0" {if $f.f_tacc eq '0'}selected{/if}>Khác</option>
						</select>
					</div>
					<div class="col-6 col-md-3 col-xl-2">
						<input type="date" name="f_from" value="{$f.f_from|escape:'html'}" class="form-control form-control-sm" title="Đăng ký từ" onchange="$Core.crm.mktpool_load(1)">
					</div>
					<div class="col-6 col-md-3 col-xl-2">
						<input type="date" name="f_to" value="{$f.f_to|escape:'html'}" class="form-control form-control-sm" title="Đăng ký đến" onchange="$Core.crm.mktpool_load(1)">
					</div>
					<div class="col-6 col-md-3 col-xl-2">
						{if !empty($has_link_col)}
						<div class="form-check form-switch m-0">
							<input class="form-check-input" type="checkbox" name="f_unconv" value="1" id="f_unconv" {if $f.f_unconv}checked{/if} onchange="$Core.crm.mktpool_load(1)">
							<label class="form-check-label fs-13" for="f_unconv">Chỉ hiện tài khoản <strong>chưa chuyển</strong></label>
						</div>
					{/if}
					<a href="javascript:void(0);" class="btn btn-sm btn-label-secondary" onclick="$Core.crm.mktpool_reset()"><i class="bx bx-x me-1"></i> Xoá lọc</a>
					</div>
				</div>
				
			</form>
		</div>

		<div id="box_mktpool">
			<div class="text-center p-5">
				<span class="spinner-border spinner-border-sm text-primary"></span>
				<span class="text-muted ms-2">Đang tải…</span>
			</div>
		</div>
	</div>

	{* ===== Thanh hành động nổi (ẩn sẵn bằng d-none; JS $Core.crm.mktpool_init toggle) ===== *}
	<div id="mktpool-bulk" class="mktpool-bulk d-none">
		<span class="mktpool-bulk-count">
			<i class="bx bx-check-square"></i>
			Đã chọn <strong id="mktpool-count">0</strong> tài khoản
		</span>
		<span class="mktpool-bulk-sep"></span>
		{if $mkt_can_assign}
			<select id="mktpool-assignee" class="form-select form-select-sm mktpool-assignee-sel" title="Gán khách cho">
				<option value="{$mkt_me}">— Gán cho tôi —</option>
				{foreach from=$mkt_assignees item=a}
					{if $a.id ne $mkt_me}<option value="{$a.id}">{$a.name|escape:'html'}</option>{/if}
				{/foreach}
			</select>
		{/if}
		<button type="button" class="btn btn-sm btn-success mktpool-bulk-btn" onclick="$Core.crm.mktpool_convert(this)">
			<i class="bx bx-transfer-alt me-1"></i> Chuyển vào CRM{if !$mkt_can_assign} &amp; gán cho tôi{/if}
		</button>
	</div>

	{/if}
</div>
{literal}
<script>
	$(function () {
		setTimeout(function () { $Core.crm.mktpool_load(1); }, 300);
	});
</script>
{/literal}
