<div class="content-wrapper">
	<div class="container-md flex-grow-1 pt-2 container-p-y">
		<div class="eznyDbxTuI mt-10">
		<div class="duGrSSZjHd d-flex flex-column align-items-center justify-content-center my-2">
			<h3 class="mb-1 fs-4 text-main text-upper">Độc quyền {$smarty.const.BRAND_NAME}</h3>
			<p class="text-muted"><i class='bx bx-time'></i> cập nhật: {$clsISO->convertTimeToText($smarty.now, true)}</p>
		</div>	
		<div class="bg-lighter p-3 rounded-2">
			{$core->getBlock('exclusive_search')}
		</div>
		<div class="holder_stock">
			<div class="table-freeze no-freeze overflow-x-auto text-nowrap mb-2">
				<table cellpadding="0" cellspacing="0" class="table mb-0 dragable table-stock" width="100%">
					<thead><tr>
						<th class="pheader text-left text-upper" colspan="20">
							<div class="d-flex align-items-center justify-content-between">
								<strong class="fs-6">Quỹ căn</strong>
							</div>
						</th>
					</tr>
					<tr class="nohover">
						<th class="pcell align-center text-center">Tòa</th>
						<th class="pcell align-center text-left">Mã căn</th>
						<th class="pcell align-center text-center">Vẽ View</th>
						<th class="pcell align-center text-left">Phiếu TG</th>
						<th class="pcell align-center text-center">Loại căn</th>
						<th class="pcell align-center text-center">Hướng</th>
						<th class="pcell align-center text-center">View</th>
						<th class="pcell align-center text-center">DT_TT</th>
						<th class="pcell align-center text-center">Giá VAT</th>
						<th class="pcell align-center text-center">Loại hình</th>
						<th class="pcell align-center text-center">Thưởng sale</th>
						<th class="pcell align-center text-center">CSBH ngày</th>
					</tr></thead>
					{section name=i loop=$list_preloaders max=20}
					<tr>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					</tr>
					{/section}
				</table>
			</div>
			<div class="table-freeze no-freeze overflow-x-auto text-nowrap mb-2">
				<table cellpadding="0" cellspacing="0" class="table mb-0 dragable table-stock" width="100%">
					<thead><tr>
						<th class="pheader text-left text-upper" colspan="20">
							<div class="d-flex align-items-center justify-content-between">
								<strong class="fs-6">Quỹ căn</strong>
							</div>
						</th>
					</tr>
					<tr class="nohover">
						<th class="pcell align-center text-center">Tòa</th>
						<th class="pcell align-center text-left">Mã căn</th>
						<th class="pcell align-center text-center">Vẽ View</th>
						<th class="pcell align-center text-left">Phiếu TG</th>
						<th class="pcell align-center text-center">Loại căn</th>
						<th class="pcell align-center text-center">Hướng</th>
						<th class="pcell align-center text-center">View</th>
						<th class="pcell align-center text-center">DT_TT</th>
						<th class="pcell align-center text-center">Giá VAT</th>
						<th class="pcell align-center text-center">Loại hình</th>
						<th class="pcell align-center text-center">Thưởng sale</th>
						<th class="pcell align-center text-center">CSBH ngày</th>
					</tr></thead>
					{section name=i loop=$list_preloaders max=20}
					<tr>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					</tr>
					{/section}
				</table>
			</div>
		</div>
	</div>
</div>