<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-xxxl-12">
			<div class="card">
				<div class="card-header">
					<div class="d-flex  align-items-center justify-content-between">
						<h5 class="chat-title mb-0">Chat Logs</h5>
						<button onClick="$Core.zalo.reload(this, event)" class="btn btn-sm btn-icon btn-outline-default">
							<i class="bx bx-refresh"></i>
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="table-container no-shadow overflow-x-auto">
						<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped dragable table-bordered">
							<thead><tr>
								<th class="align-center h-px-35 bg-lighter">Nhóm Zalo</th>
								<th class="align-center h-px-35 bg-lighter">Tên Zalo</th>
								<th class="align-center h-px-35 bg-lighter" style="width:40%">Nội dung</th>
								<th class="align-center h-px-35 bg-lighter">Thời gian</th>
								<th class="align-center h-px-35 bg-lighter">Tình trạng</th>
								<th class="align-center h-px-35 bg-lighter" width="40px"></th>
							</tr></thead>
							<tbody class="holder_chatlogs">
								{section name=i loop=$load_preloaders max=20}
								<tr>
									<td class="align-center">
										<div class="animate-bg rounded-pill w-100 h-px-15"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg rounded-pill w-100 h-px-15"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg rounded-pill w-100 h-px-15"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg rounded-pill w-100 h-px-15"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg rounded-pill w-100 h-px-15"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg rounded-pill w-100 h-px-15"></div>
									</td>
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
	$(() => { $Core.zalo.load_chatlogs({});});
</script>
{/literal}