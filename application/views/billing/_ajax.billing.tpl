<div class="modal-dialog modal-dialog-scrollable">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Thông tin giao dịch {$oneBilling.stock_code} <br />
				<div class="d-flex align-items-center gap-1 text-fs-12">
					<span class="d-flex align-items-center gap-1 text-main">
						<i class="bx bx-user"></i> {$clsProfile->getFullName($oneBilling.staff_id)}
					</span>
					<span>-</span>
					<span class="d-flex align-items-center gap-1 text-muted">
						<i class="bx bx-time"></i> {$clsISO->convertTimeToText($oneBilling.deposit_date)}
					</span>
				</div>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="alert alert-primary text-center">
				Tình trạng: Đã TT
			</div>
			<table class="clientssummarystats">
				{$htmlTable}
			</table>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng lại</button>
		</div>
	</div>
</div>