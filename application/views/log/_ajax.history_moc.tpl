<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Lịch sử truy cập bảng hàng {$oneMember.full_name}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="table-container no-shadow overflow-auto text-nowrap" style="max-height: calc(100vh - 100px)">
					<table border="0" cellpadding="0" cellspacing="0" class="table table-striped mb-0" width="100%">
						<thead class="position-sticky top-0 zindex-3"><tr>
							<th width="10%" class="align-center text-center h-px-40">No.</th>
							<th class="align-center bg-lighter h-px-40">Dự án</th>
							<th class="align-center bg-lighter h-px-40">Thời gian</th>
							<th class="align-center bg-lighter h-px-40">Link</th>
						</tr></thead>
						<tbody id="tbody_{$uid}">
							{foreach from=$lstLog item=_oItem key=key name=i}
							<tr>
								<td class="text-center">{$smarty.foreach.i.iteration}</td>
								<td>{$_oItem.project_name}</td>
								<td>{$clsISO->formatDate($_oItem.reg_date,4)}</td>
								<td><a href="{$_oItem.link}" target="_blank" >Xem</a></td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>