<script type="text/javascript">
	var type_list = '{$type_list}', 
		current_page = '{$current_page}';
</script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap align-items-center justify-content-between py-2">
		<div class="leasing_left">
			<h5 class="fw-bold mb-1">Dịch vụ cho thuê</h5>
			<span class="srp-total-count text-muted">Hiện có <strong class="total-stock text-main">0</strong> căn hộ đang cho thuê.</span>
		</div>		
	</div>
    <div class="card">
		<div class="card-header border-bottom om-xs:p-2">
			<form method="POST">
				{$core->getBlock('leasing_search')}
				<input type="hidden" class="search_field" data-field="page" name="page" value="{$page}">
			</form>
		</div>
		<div class="card-body pt-3">
			<div class="overflow-x-auto">
				<table class="table table-iloocal table-{$deviceType} table-bordered">
					<thead><tr>
						{if $deviceType eq 'phone'}
						<th class="align-center bg-lighter"><a class="sort_click asc">Mã căn</a></th>
						<th class="align-center bg-lighter">Loại căn</th>
						<th class="align-center text-left bg-lighter">Giá</th>
						{else}
						<th width="10%" class="align-center bg-lighter">Mã căn</th>
						<th class="align-center bg-lighter"><a class="sort_click asc">Giá VAT</a></th>
						<th class="align-center bg-lighter">Loại căn</th>
						<th class="align-center bg-lighter">Hướng BC</th>
						<th class="align-center bg-lighter">khoảng tầng</th> 
						<th class="align-center text-left bg-lighter">Người liên hệ</th>
						<th class="align-center text-left bg-lighter">Điện thoại</th>
						{/if}
						<th class="align-center text-left bg-lighter w-px-75">Xác minh</th>
						<th class="align-center text-left bg-lighter w-px-75">Duyệt</th>
						<th class="align-center text-left bg-lighter w-px-40"></th>
					</tr></thead>
					<tbody class="holder_leasing">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							{if $deviceType ne 'phone'}
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							{/if}								
							<th class="align-center text-center bg-lighter w-px-75">Xác minh</th>
							<th class="align-center text-center bg-lighter w-px-75">Duyệt</th>
							<th class="align-center text-left bg-lighter w-px-40"></th>
						</tr>
						{/section}
					</tbody>
				</table>	
			</div>		
			<div id="pagination-container" class="pagination d-flex justify-content-center mt-3"></div>
		</div>
	</div>
</div>
<script>
	var _TYPE_LOWFLOOR = `{$smarty.const._TYPE_LOWFLOOR}`;
</script>
