<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Quản lý nhóm</h5>
				<div class="d-flex align-items-center gap-2">
					<button class="btn btn-icon btn-outline-default"><i class="bx bx-search"></i></button>
					<button class="btn btn-icon btn-outline-default" onClick="$Core.zalo.open_group(this, event)">
						<i class="bx bx-plus"></i>
					</button>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="table-container no-shadow overflow-x-auto">
					<table cellpadding="0" cellspacing="0" width="100%" class="table dragable table-bordered mb-0">
						<thead><tr>
							<th class="align-center h-px-35 bg-lighter">Tên nhóm</th>
							<th class="align-center h-px-35 bg-lighter" width="80px">Công cụ</th>
						</tr></thead>
						<tbody class="holder_zalo_group">
							{section name=i loop=$load_preloaders max=25}
							<tr>
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