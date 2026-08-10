<div class="container-xxl flex-grow-1 container-p-y">
	<div class="d-flex justify-content-between align-items-center py-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>OKRs</span></h4>
			<p class="text-muted mb-0">Quản trị hiệu suất công việc</p>
		</div>
		<div class="p__right">
			<button type="button" title="Thêm mới" onClick="$Core.okrs.open(this, event)" okrs_id="0" class="btn btn-outline-primary mr-2">Thêm mục tiêu</button>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-md-6">
			<div class="dashboard-panel-item dashboard-panel-item--full">
				<div class="panel panel-default no-shadow border-0 mb-0">
					<div class="panel-heading">
						<h3 class="panel-title">{$clsISO->makeIcon('bx-group','Okrs công ty')}</h3>
					</div>
					<div class="panel-body border rounded-2 no-easyui">
						
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="dashboard-panel-item dashboard-panel-item--full">
				<div class="panel panel-default no-shadow border-0 mb-0">
					<div class="panel-heading">
						<h3 class="panel-title">{$clsISO->makeIcon('bx-user-circle','Okrs của tôi')}</h3>
					</div>
					<div class="panel-body border rounded-2 no-easyui">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<table id="basic" class="table" cellpadding="0" cellspacing="0" width="100%">
				<thead><tr>
					<th width="35%" class="align-center">Mục tiêu</th>
					<th width="10%" class="align-center text-center">Kết quả</th>
					<th class="align-center w-px-100">Thay đổi</th>
					<th class="align-center text-center">Tiến triển</th>
					<!-- <th class="align-center">Nhóm</th> -->
					<th class="align-center">Loại</th>
					<th class="align-center">Tình trạng</th>
					<th class="align-center w-px-50"></th>
				</tr></thead>
				<tbody class="holder_okrs">
					<tr>
						<td class="text-center" colspan="10">
							<div class="p-5">
								Loading...
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$Core.okrs.list({}, false);
	});
</script>
{/literal}