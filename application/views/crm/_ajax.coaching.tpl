{* P2 — Modal "Kèm cặp" chi tiết 1 rep (read-only). Render bởi default_load_coaching(). Mở bằng $Core.crm.open_coaching. *}
<div class="modal fade" id="crmCoachingModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class="d-flex align-items-center gap-2">
					<img class="avatar avatar-sm rounded-pill" src="{$co_rep_avatar|escape}">
					<div>
						<h5 class="mb-0">Kèm cặp · {$co_rep_name|escape}</h5>
						<small class="text-muted">Sức khỏe rep — số liệu thật</small>
					</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
			</div>
			<div class="modal-body">
				<div class="row g-2 mb-3">
					<div class="col-4"><div class="border rounded-2 p-2 text-center h-100"><div class="fs-11 text-muted">Khách active</div><div class="fs-20 fw-bold">{$co_active}</div></div></div>
					<div class="col-4"><div class="border rounded-2 p-2 text-center h-100"><div class="fs-11 text-muted">Đã chốt</div><div class="fs-20 fw-bold text-success">{$co_chot}</div></div></div>
					<div class="col-4"><div class="border rounded-2 p-2 text-center h-100"><div class="fs-11 text-muted">Chưa chạm</div><div class="fs-20 fw-bold {if $co_no_touch > 0}text-danger{/if}">{$co_no_touch}</div></div></div>
				</div>
				<h6 class="fw-bold mb-2"><i class="bx bx-error-circle text-warning"></i> Khách cần chú ý (quá hạn lâu nhất)</h6>
				{if $co_watch}
				<div class="list-group list-group-flush">
					{foreach from=$co_watch item=_w}
					<div class="list-group-item d-flex align-items-center justify-content-between px-0 py-2">
						<div class="min-w-0">
							<a href="javascript:void(0);" class="fw-bold view_customer" onClick="$Core.crm.open_customer(this,event)" route="/customer/{$_w.customer_id}/overview" customer_id="{$_w.customer_id}">{$_w.name|escape}</a>
							<span class="text-muted fs-12 ms-2">{$_w.phone|escape}</span>
						</div>
						{if $_w.overdue_text}<span class="badge bg-label-danger text-nowrap">Quá hạn {$_w.overdue_text|escape}</span>{/if}
					</div>
					{/foreach}
				</div>
				{else}
				<div class="text-muted text-center py-3">Không có khách quá hạn — rep đang theo sát.</div>
				{/if}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			</div>
		</div>
	</div>
</div>
