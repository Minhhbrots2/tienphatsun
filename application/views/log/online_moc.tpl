<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
		<div class="nlApYyxOPs mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1">Danh sách online My Ocean City</h4>
			<p class="mb-0 text-muted">Có tổng <strong class="total_record text-danger">0</strong> người truy cập
			</p>
		</div>
	</div>
    <div class="card">
		<div class="card-body">
			<div class="table-container no-shadow overflow-x-auto text-nowrap">
				<table border="0" cellpadding="0" cellspacing="0" class="table table-striped mb-0" width="100%">
					<thead><tr>
						<th width="15%" class="align-center bg-lighter h-px-35">Họ và tên</th>
						<th class="align-center  bg-lighter h-px-35">Điện thoại</th>
						<th class="align-center  bg-lighter h-px-35">Link truy cập cuối</th>
						<th class="align-center  bg-lighter h-px-35 w-px-50">Lịch sử</th>
					</tr></thead>
					<tbody class="online-list_MOC">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
		</div>
    </div>
</div>

<script>
	var arr_member_cached = `{$arr_member_cached}`;
</script>