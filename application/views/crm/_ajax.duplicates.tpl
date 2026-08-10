{* F3 — Panel cảnh báo khách TRÙNG SĐT (read-only). Render bởi default_load_duplicates(). Rỗng khi total=0 → panel tự ẩn. *}
{if $total_dup > 0}
<div class="alert alert-warning d-flex align-items-start gap-2 py-2 px-2 mb-2" role="alert">
	<i class="bx bx-error-circle fs-5 mt-1"></i>
	<div class="flex-grow-1">
		<div class="fw-bold mb-1">Phát hiện {$total_dup} khách khác cùng số điện thoại</div>
		<div class="table-responsive">
			<table class="table table-sm table-borderless table-middle mb-0">
				<thead>
					<tr class="fs-11 text-muted">
						<th>Tên</th>
						<th>SĐT</th>
						<th>Trạng thái</th>
						<th>Phụ trách</th>
						<th>Nguồn</th>
						<th>Ngày tạo</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				{foreach from=$dup_rows item=_d}
				<tr>
					<td class="fs-12 fw-bold">{$_d.name|escape}</td>
					<td class="fs-12 text-nowrap">{$_d.phone_mask}</td>
					<td class="fs-12">{$_d.status_title|escape}</td>
					<td class="fs-12 text-nowrap">{$_d.owner_name}</td>
					<td class="fs-12">{$_d.source_title|escape}</td>
					<td class="fs-11 text-muted text-nowrap">{$_d.reg_text}</td>
					<td class="text-end">
						{if $_d.can_open}<button type="button" class="btn btn-xs btn-outline-danger me-1" data-merge-to="{$customer_id}" data-merge-from="{$_d.customer_id}" data-merge-name="{$_d.name|escape}" onClick="$Core.crm.merge_duplicate(this, event)" title="Gộp hồ sơ này vào hồ sơ đang xem"><i class="bx bx-git-merge"></i> Gộp</button><a href="javascript:void(0);" class="btn btn-xs btn-outline-secondary" customer_id="{$_d.customer_id}" onClick="$Core.crm.view_customer({$_d.customer_id})"><i class="bx bx-link-external"></i> Mở</a>{else}<span class="badge bg-label-secondary" title="Khách thuộc nhân sự khác">khác</span>{/if}
					</td>
				</tr>
				{/foreach}
				</tbody>
			</table>
		</div>
		<div class="fs-11 text-muted mt-1">So khớp theo số điện thoại (đã chuẩn hoá). Kiểm tra trước khi chăm sóc để tránh trùng lặp.</div>
	</div>
</div>
{/if}
