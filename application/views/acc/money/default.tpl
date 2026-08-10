<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-xxxl-10 offset-xxxl-1">
			<div class="d-flex flex-wrap justify-content-between align-items-center py-1 mb-2">
				<div class="mb-2 mb-lg-0">
					<h4 class="fw-bold mb-1">Logs nhập kế toán</span></h4>
					<span class="text-muted">Lịch sử import kế toán</span>
				</div>
				<div class="btn-groups d-flex justify-content-end flex-fill gap-2">
					<button type="button" data-toggle="ripple" title="Thêm mới" 
						onClick="$Core.money.open_import(this, event)" class="btn btn-outline-primary">
						<i class='bx bx-plus'></i> Import
					</button>
				</div>
			</div>
			<div class="card">
				<div class="card-header"></div>
				<div class="card-body">
					<div class="table-container no-shadow overflow-x-auto">	
						<table cellpadding="0" cellspacing="0" class="table table-bordered text-nowrap">
							<thead><tr>
								<th class="align-center w-px-50 h-px-35 bg-lighter">No.</th>
								<th class="align-center w-px-100 h-px-35 bg-lighter">Thời gian</th>
								<th class="align-center h-px-35 bg-lighter">Bảng tính</th>
								<th class="align-center w-px-200 text-left h-px-35 bg-lighter">Người thực hiện</th>
								<th class="align-center text-center w-px-150 h-px-35 bg-lighter">Vào lúc</th>
							</tr></thead>
							<tbody class="holder_import_logs">
								{section name=i loop=$list_preloaders}
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function() { $Core.money.load_import_logs(); });
</script>
{/literal}