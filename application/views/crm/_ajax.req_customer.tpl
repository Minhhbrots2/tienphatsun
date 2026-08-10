{if $template_type eq '_form'}
<div class="modal-dialog modal-dialog-centered modal-xs">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Yêu cầu cấp data khách hàng</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-2">
				<div class="col-5">
					<label class="form-label mb-1">Số lượng data</label>
					<div class="input-group input-group-merge">
						<input type="text" name="amount" class="form-control required numberonly" value="{if $action eq '_edit'}{$oneRequest.amount}{/if}" placeholder="30">
						<span id="basic-icon-default-email2" class="input-group-text">/khách</span>
					</div>
				</div>
				<div class="col-7">
					<label class="form-label mb-1">Dự án</label>
					<input type="text" id="name" name="project_name" class="form-control required" value="{if $action eq '_edit'}{$oneContact.project_name}{/if}" placeholder="Tên dự án">
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Ghi chú</label>
				<textarea class="form-control" placeholder="Ghi chú" name="notes" cols="255" rows="2"></textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" uid="{$uid}" onClick="$Core.crm.save_req_customer(this, event)" class="btn flex-fill btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
{elseif $template_type eq '_modal'}
<div class="modal fade right in" id="{$uid}">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<div class="d-flex align-items-center gap-2">
					<img src="data:image/png;base64,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" class="w-px-40 h-px-40 p-1 border radius-3" />
					<div class="d-flex flex-column">
						<h5 class="modal-title mb-0">Yêu cầu cấp DATA khách hàng</h5>
						<small>Quản lý yêu cầu cấp data khách hàng</small>
					</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body overflow-y-auto">
				<div class="briefs holder_req_customer_briefs_{$uid} d-flex gap-1 gap-lg-2 align-items-center mb-2">
					<div class="brief-item a1a bg-orange flex-fill">
						<p class="text-fs-14 mb-0">Tổng y/c</p>
						<hr class="w-px-50 my-2">
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">0</h3>
					</div>
					<div class="brief-item a2a bg-azure flex-fill">
						<p class="text-fs-14 mb-0">Đã xử lý</p>
						<hr class="w-px-50 my-2">
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">0</h3>
					</div>
					<div class="brief-item a2a bg-purple flex-fill">
						<p class="text-fs-14 mb-0">Chưa xử lý</p>
						<hr class="w-px-50 my-2">
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">0</h3>
					</div>
				</div>
				<div class="table-container text-nowrap no-shadow overflow-x-auto">
					<table cellpadding="0" cellspacing="0" class="table dragable table-bordered mb-0" width="100%">
						<thead><tr>
							<th class="align-center h-px-35 bg-lighter">Ngày</th>
							<th class="align-center h-px-35 bg-lighter">Họ và tên</th>
							<th class="align-center h-px-35 bg-lighter">S.Lượng</th>
							<th class="align-center h-px-35 bg-lighter">Dự án</th>
							<th class="align-center h-px-35 bg-lighter">T.Trạng</th>
							<th class="align-center h-px-35 bg-lighter">Người xử lý</th>
							<th class="align-center h-px-35 bg-lighter">Ngày xử lý</th>
						</tr></thead>
						<tbody class="holder_req_customer_{$uid}">
							{section name=i loop=$list_preloaders}
							<tr>
								<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></td>
								<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></td>
								<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></td>
								<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></td>
								<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></td>
								<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></td>
								<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></td>
							</tr>
							{/section}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
{else}
{/if}