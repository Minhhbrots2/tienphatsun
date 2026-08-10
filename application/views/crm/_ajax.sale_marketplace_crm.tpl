{* AJAX partial — bảng + phân trang Sale thị trường (nạp vào #box_mktpool = thân card; KPI ở partial riêng). Render bởi default_load_sale_marketplace(). *}
{* Pre-render: $r.reg_text (ngày ĐK), $r._ini (chữ tắt avatar), $mkt_img (=URL_IMAGES) — partial KHÔNG có $clsISO/$URL_IMAGES. *}
<div class="table-responsive text-nowrap mktpool-tablewrap">
	<table class="table table-hover align-middle mktpool-table mb-0">
		<thead>
			<tr>
				<th class="text-center mktpool-col-check">
					<input type="checkbox" id="mktpool-all" class="form-check-input" title="Chọn tất cả">
				</th>
				<th>Mã</th>
				<th>Khách hàng</th>
				<th>Liên hệ</th>
				<th class="text-center">Nguồn</th>
				<th class="text-center">Loại TK</th>
				<th class="text-center">Ngày ĐK</th>
				<th class="text-center">Trạng thái</th>
			</tr>
		</thead>
		<tbody>
			{if !empty($rows)}
				{foreach from=$rows item=r}
					<tr class="{if $r._is_conv || $r._is_pdup}mktpool-row-muted{/if}">
						<td class="text-center mktpool-col-check">
							{if $r._is_conv || $r._is_pdup || $r._no_phone}
								<input type="checkbox" class="mktpool-check form-check-input" disabled>
							{else}
								<input type="checkbox" class="mktpool-check form-check-input" value="{$r.profile_id}">
							{/if}
						</td>
						<td class="mkt-c-code"><span class="mktpool-code">{$r.code|escape:'html'}</span></td>
						<td class="mkt-c-name">
							<div class="d-flex align-items-center gap-2">
								<div class="avatar avatar-sm flex-shrink-0">
									{if !empty($r.avatar)}
										<img src="{$r.avatar|escape:'html'}" alt="avatar" class="rounded-circle">
									{else}
										<span class="avatar-initial rounded-circle bg-label-{if $r.profile_type eq 'MF'}info{else}warning{/if}">{$r._ini|escape:'html'}</span>
									{/if}
								</div>
								<span class="fw-medium text-heading text-truncate mktpool-name">{if $r.full_name}{$r.full_name|escape:'html'}{else}<span class="text-muted fst-italic">(chưa có tên)</span>{/if}</span>
							</div>
						</td>
						<td class="mkt-c-contact">
							<div class="d-flex flex-column lh-sm">
								<span class="text-heading mktpool-phone"><i class="bx bx-phone text-muted me-1"></i>{if $r._no_phone}<span class="text-danger fs-13"><i class="bx bx-error-circle"></i> Không có SĐT</span>{else}{$r.phone|escape:'html'}{/if}</span>
								{if $r.email}<small class="text-muted text-truncate mktpool-email"><i class="bx bx-envelope me-1"></i>{$r.email|escape:'html'}</small>{/if}
							</div>
						</td>
						<td class="text-center mkt-c-src">
							{if $r.profile_type eq 'MF'}<span class="badge bg-label-info">MyFuture</span>
							{else}<span class="badge bg-label-warning">MOC</span>{/if}
						</td>
						<td class="text-center mkt-c-type">
							{if $r.type_account_id eq 2}<span class="badge bg-label-primary">Môi giới</span>
							{elseif $r.type_account_id eq 1}<span class="badge bg-label-secondary">Khách</span>
							{else}<span class="text-muted">--</span>{/if}
						</td>
						<td class="text-center mkt-c-date"><span class="text-muted fs-13">{$r.reg_text|escape:'html'}</span></td>
						<td class="text-center mkt-c-status">
							{if $r._is_conv}<span class="badge bg-label-success"><i class="bx bx-check-circle"></i> Đã chuyển</span>
							{elseif $r._is_pdup}<span class="badge bg-label-secondary"><i class="bx bx-link-alt"></i> Trùng SĐT</span>
							{elseif $r._no_phone}<span class="badge bg-label-warning"><i class="bx bx-error-circle"></i> Thiếu SĐT</span>
							{else}<span class="badge bg-label-primary mktpool-badge-ready">Chưa chuyển</span>{/if}
						</td>
					</tr>
				{/foreach}
			{else}
				<tr>
					<td colspan="8" class="text-center py-5">
						<img src="{$mkt_img}/DataEmpty.svg" class="w-px-120 mb-2" alt="">
						<p class="text-heading fw-medium mb-1">Không có tài khoản nào khớp bộ lọc</p>
						<p class="text-muted fs-13 mb-3">Thử nới lỏng từ khoá hoặc xoá bớt điều kiện lọc.</p>
						<a href="javascript:void(0);" class="btn btn-sm btn-label-primary" onclick="$Core.crm.mktpool_reset()"><i class="bx bx-revision me-1"></i> Xoá bộ lọc</a>
					</td>
				</tr>
			{/if}
		</tbody>
	</table>
</div>

{* ===== Phân trang (AJAX) ===== *}
{if $total_page gt 1}
	<div class="card-footer d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 py-3">
		<span class="text-muted fs-13">Trang <strong class="text-heading">{$page}</strong> / {$total_page}</span>
		<nav>
			<ul class="pagination pagination-sm mb-0 justify-content-center flex-wrap">
				{if $page gt 1}
					<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="$Core.crm.mktpool_load(1)"><i class="bx bx-chevrons-left"></i></a></li>
				{/if}
				{foreach from=$page_list item=n}
					<li class="page-item {if $n eq $page}active{/if}">
						<a class="page-link" href="javascript:void(0);" onclick="$Core.crm.mktpool_load({$n})">{$n}</a>
					</li>
				{/foreach}
				{if $page lt $total_page}
					<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="$Core.crm.mktpool_load({$total_page})"><i class="bx bx-chevrons-right"></i></a></li>
				{/if}
			</ul>
		</nav>
	</div>
{/if}
