<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-sm-6 col-xxxl-7">
			<div class="sticky">
				<div class="card">
					<div class="card-header">
						<div class="d-flex  align-items-center justify-content-between">
							<h5 class="chat-title mb-0">Nhóm cho phép check</h5>
							<div class="d-flex align-items-center gap-1">
								<button onClick="$Core.zalo.open_group(this, event)" title="Thêm nhóm" 
									class="btn btn-outline-default"><i class="bx bx-plus"></i> Thêm mới</button>
								<button onClick="$Core.zalo.sync_zalo(this, event)" title="Đồng bộ tài khoản Zalo {$smarty.const.BRAND_NAME}" 
									class="btn d-none d-lg-block btn-outline-default"><i class="bx bx-refresh"></i> Sync</button>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="table-container no-shadow text-nowrap overflow-x-auto">
							<table cellpadding="0" cellspacing="0" width="100%" class="table dragable table-bordered">
								<thead><tr>
									<th class="align-center h-px-35 bg-lighter">Tên nhóm</th>
									<th class="align-center h-px-35 bg-lighter">Thời gian</th>
									<th class="align-center h-px-35 text-center bg-lighter">Rep {$smarty.const.BRAND_NAME}</th>
									<th class="align-center h-px-35 text-center bg-lighter">Check FULL</th>
									<th class="align-center h-px-35 text-center bg-lighter">KT nguồn</th>
									<th class="align-center h-px-35 text-center bg-lighter">T.trạng</th>
									<th class="align-center h-px-35 text-center bg-lighter">Căn bán</th>
									<th class="align-center h-px-35 bg-lighter" width="80px">Công cụ</th>
								</tr></thead>
								<tbody class="holder_zalo_group">
									{section name=i loop=$load_preloaders max=25}
									<tr>
										<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
										<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
										<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
										<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
										<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
										<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
										<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
										<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
									</tr>
									{/section}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-6 col-xxxl-5">
			<div class="card">
				<div class="card-header">
					<div class="d-flex  align-items-center justify-content-between">
						<h5 class="chat-title mb-0">Chat Logs</h5>
						<button onClick="$Core.tool.reload(this, event)" class="btn btn-sm btn-icon btn-outline-default">
							<i class="bx bx-refresh"></i>
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="table-container no-shadow text-nowrap overflow-x-auto">
						<table cellpadding="0" cellspacing="0" width="100%" class="table dragable table-bordered">
							<thead><tr>
								<th class="align-center h-px-35 bg-lighter">Nhóm Zalo</th>
								<th class="align-center h-px-35 bg-lighter">Tên Zalo</th>
								<th class="align-center h-px-35 bg-lighter">Thời gian</th>
								<th class="align-center h-px-35 bg-lighter" width="40px"></th>
							</tr></thead>
							<tbody class="holder_chatlogs">
								{section name=i loop=$load_preloaders max=25}
								<tr>
									<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
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
	$().ready(() => {
		$Core.zalo.load_chatlogs({});
		$Core.zalo.load_manager_group({});
	});
</script>
{/literal}