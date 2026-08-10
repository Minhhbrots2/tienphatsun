<script type="text/javascript">
	var type_list = '{$type_list}', 
		current_page = '{$current_page}';
</script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex align-items-center flex-wrap justify-content-between py-2">
		<div class="sop_left mb-2 mb-lg-0">
			<h5 class="fw-bold mb-1">Dịch vụ chuyển nhượng</h5>
			<span class="srp-total-count text-muted">Hiện có <strong class="total-stock text-main">0</strong> tin chuyển nhượng.</span>
		</div>
		<div class="d-flex align-items-center xs:w-100 gap-2">
			<button onClick="$Core.sop.do_crawl(this,event)" title="Import bảng hàng cao tầng" 
				class="btn flex-fill btn-outline-primary"><i class='bx bx-upload'></i> Import <span class="badge">CT</span></button>
			<button onClick="$Core.sop.do_tt_crawl(this,event)" title="Import bảng hàng thấp tầng" 
				class="btn flex-fill btn-outline-danger"><i class='bx bx-upload'></i> Import <span class="badge">TT</span></button>
			<a class="btn btn-icon btn-outline-default" target="_blank" title="File quản lý" 
			href="https://docs.google.com/spreadsheets/d/1vRLE9rKm3A2vSpv-6eHI6rYMRkZ3340RwCYjdV2Rf5U/edit?gid=0#gid=0">
				<i class='bx bx-link'></i></a>
		</div>
	</div>
    <div class="card">
		<div class="card-header border-bottom om-xs:p-2">
			<form method="POST">
				{$core->getBlock('sop_search')}
				<input class="search_field" type="hidden" data-field="page" 
					name="page" value="{$page}">
			</form>
		</div>
		<div class="card-body pt-3"><div class="table-container no-shadow overflow-x-auto">
			<table width="100%" cellpadding="0" cellspacing="0" 
				class="table holder_sop table-{$deviceType} table-bordered">
				<!-- dragable -->
				<thead><tr>
					<th width="6%" class="align-center bg-lighter">Mã căn</th>
					<th class="align-center h-px-35 bg-lighter">Loại căn</th>
					<th class="align-center h-px-35 bg-lighter">Hướng BC</th>
					<th class="align-center h-px-35 bg-lighter">khoảng tầng</th> 
					<th class="align-center h-px-35 bg-lighter">Giá chủ</th>
					<th class="align-center h-px-35 bg-lighter">Giá bán</th>
					<th class="align-center h-px-35 bg-lighter">Giá/m2</th>
					<th class="align-center h-px-35 text-left bg-lighter">Người liên hệ</th>
					<th class="align-center h-px-35 text-left bg-lighter">Điện thoại</th>
					<th class="align-center h-px-35 text-left bg-lighter">Ngày đăng</th>
					<th class="align-center h-px-35 text-center bg-lighter w-px-75">T.Trạng</th>
					{if $clsISO->checkPermission('view_sop_source')}
					<th class="align-center h-px-35 text-center bg-lighter w-px-75">Nguồn</th>
					{/if}
					<th class="align-center h-px-35 text-center bg-lighter w-px-75">HOT</th>
					<th class="align-center h-px-35 text-center bg-lighter w-px-75">Xác minh</th>
					<th class="align-center h-px-35 text-center bg-lighter w-px-75">Duyệt</th>
					<th class="align-center h-px-35 text-center bg-lighter w-px-50">
						<i class="bx bx-cog"></i>
					</th>
				</tr></thead>
				<tbody>
					{section name=i loop=$list_preloaders}
					<tr>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						{if $clsISO->checkPermission('view_sop_source')}
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						{/if}
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
						<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
					</tr>
					{/section}
				</tbody>
			</table></div>
			<div id="pagination-container" class="pagination d-flex justify-content-center mt-3"></div>
		</div>
	</div>
</div>
<script>
	var _SOP_TYPE_LOWFLOOR = `{$smarty.const._SOP_TYPE_LOWFLOOR}`,
		_SOP_TYPE_HIGHLEVEL = `{$smarty.const._SOP_TYPE_HIGHLEVEL}`;
</script>
