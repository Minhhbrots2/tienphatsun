<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-xxxl-10 offset-xxxl-1">
			<div class="d-flex flex-wrap justify-content-between align-items-center py-1 mb-2">
				<div class="mb-2 mb-lg-0">
					<h4 class="fw-bold mb-0">Lịch đăng ký phòng họp</span></h4>
					<span class="text-muted">Đăng ký sử dụng phòng họp</span>
				</div>
				<div class="btn-groups d-flex justify-content-end flex-fill gap-2">
					<button type="button" data-toggle="ripple" title="Thêm mới" 
						onClick="$Core.attendance.open_import(this, event)" class="btn btn-outline-primary"><i class='bx bx-import'></i> Import</button>
				</div>
			</div>
			<div class="overflow-x-auto">	
				<div class="fh-calendar" id="fh-calendar">
					<div class="p-5 text-center text-muted">Loading...</div>
				</div>
			</div>
		</div>
	</div>
</div>