{* /crm/assigned/ (act=my_assigned) — Vỏ trang "Quản lý gán số"; KPI + toolbar + bảng nạp AJAX qua $Core.crm.load_assigned(). *}
<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="row">
		<div class="col-12 col-xxl-10 offset-xxl-1">
			<!-- ===== Header ===== -->
			<div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
				<div>
					<h4 class="fw-bold mb-1 crm-page-title">Quản lý gán số</h4>
					<div class="text-muted crm-page-desc">Theo dõi các số bạn đã giao — ai đã nhận, ai còn chờ, ai quá hạn. Phân lại hoặc thu hồi khi cần.</div>
				</div>
				<div class="d-flex gap-2 align-items-center flex-wrap">
					<button type="button" class="btn btn-sm btn-outline-default text-nowrap" onClick="$Core.crm.export_assigned()"><i class="bx bx-export me-1"></i> Xuất Excel</button>
					<a href="/crm/" class="btn btn-sm btn-outline-default text-nowrap"><i class="bx bx-arrow-back me-1"></i> Danh sách</a>
				</div>
			</div>
			<!-- ===== Nội dung (AJAX) ===== -->
			<div id="box_assigned" data-status="{$ma_status}">
				<div class="text-center p-5">
					<span class="spinner-border spinner-border-sm text-primary"></span>
					<span class="text-muted ms-2">Đang tải…</span>
				</div>
			</div>
		</div>
	</div>
</div>
{$scriptJs}
{literal}
<script>
	$(function () {
		setTimeout(function () {
			$Core.crm.load_assigned($('#box_assigned').data('status') || 'all');
		}, 300);
	});
</script>
{/literal}
