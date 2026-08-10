<div class="modal-dialog modal-ipad">
	<form class="modal-content">
		<div class="modal-header position-relative">
			<h5 class="modal-title">Liên hệ phòng ban</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="table-container overflow-x-auto no-shadow mb-2" >
				<table cellpadding="0" cellspacing="0" class="table mb-0 ContactFooter table-bordered min-w-px-500">
					<thead><tr>
						<th class="align-center h-px-35 bg-lighter"><i class="bx bx-move"></i></th>
						<th class="align-center h-px-35 bg-lighter">Phòng ban</th>
						<th class="align-center h-px-35 bg-lighter">Họ và tên</th>
						<th class="align-center h-px-35 bg-lighter">SĐT</th>
						<th class="align-center h-px-35 bg-lighter" width="45px"></th>
					</tr></thead>
					<tbody>
						{if !empty($ContactFooter)}
							{foreach from=$ContactFooter item = _oContact}
							{assign var = _rowId value = $clsISO->getUniqid()}
							<tr class="contact_row">
								<td class="align-center text-center">
									<a class="mySortableHandler"><i class="bx bx-move"></i></a>
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Tên phòng ban" 
										name="ContactFooter[{$_rowId}][title]" value="{$_oContact.title}" />
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Họ và tên" 
										name="ContactFooter[{$_rowId}][name]" value="{$_oContact.name}" />
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Số điện thoại" 
										name="ContactFooter[{$_rowId}][phone]" value="{$_oContact.phone}" />
								</td>
								<td class="align-center text-center">
									<button onClick="$Core.contact.delete_row(this, event)" class="btn btn-icon btn-sm btn-outline-default">
										<i class="bx bx-trash"></i>
									</button>
								</td>
							</tr>
							{/foreach}
						{else}
							{assign var = _rowId value = $clsISO->getUniqid()}
							<tr class="contact_row">
								<td class="align-center text-center">
									<a class="mySortableHandler"><i class="bx bx-move"></i></a>
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Tên phòng ban" 
										name="ContactFooter[{$_rowId}][title]" />
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Họ và tên" 
										name="ContactFooter[{$_rowId}][name]" />
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Số điện thoại" 
										name="ContactFooter[{$_rowId}][phone]" />
								</td>
								<td class="align-center text-center">
									<button onclick="$Core.contact.delete_row(this, event)" class="btn btn-icon btn-sm btn-outline-default">
										<i class="bx bx-trash"></i>
									</button>
								</td>
							</tr>
						{/if}
						<tr>
							<td class="bg-lighter" colspan="5">
								<button type="button" onClick="$Core.contact.add_row(this, event)" class="btn btn-outline-default btn-sm">
									<i class="bx bx-plus"></i> Thêm dòng</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
			<button data-toggle="ripple" type="button" onClick="$Core.contact.save(this, event)" class="btn btn-primary">Lưu & thêm</button>
		</div>
	</form>
</div>