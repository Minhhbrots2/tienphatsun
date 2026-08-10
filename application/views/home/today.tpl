{$scriptJs}
<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1">Căn hộ nổi bật</h4>
			<span class="text-muted total_record">0</span>
		</div>
		<div class="p__right d-flex">			
			{if $permiss_add eq 1}
			<div class="d-flex justify-content-end">
				<a href="javascript:void(0)" onClick="$Core.today.edit_stock_today(this, event);" data-action="_open" class="btn btn-outline-primary mr-2">Thêm mới </a>
				<div class="btn-group">
					<button type="button" class="btn btn-icon btn-outline-default dropdown-toggle hide-arrow" 
					data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
						<i class="bx bx-search"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">
						<div class="p-4">
							<div class="input-group input-group-merge mb-3">
								<span class="input-group-text"><i class="bx bx-search"></i></span>
								<input type="text" class="form-control search_field" data-field="keySearch" placeholder="Tìm kiếm" />
							</div>
							<div class="input-group input-date-picker mb-2">
								<i class="ico ico-calendar"></i>
								<input type="text" class="form-control from_date search_field w-px-100" 
									placeholder="Từ ngày" data-field="start_date">
								<input type="text" class="form-control to_date search_field w-px-100" 
									placeholder="Đến ngày" data-field="end_date">
							</div>
							<div class="form-group">
								<button type="button" class="btn btn-success" onClick="$Core.today.list({})">
									<i class="bx bx-search"></i> Tìm kiếm
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>	
			{/if}
		</div>
	</div>
	<div class="card page_list holder_today" id="holder_today">
		<div class="card-header d-flex flex-wrap justify-content-start align-items-center"></div>
		<div class="card-body holder_today pt-3" >
			<table border="0" cellpadding="0" cellspacing="0" class="table" width="100%" >
				<thead><tr>
					<th class="align-center text-left" width="25%">Tiêu đề</th>
					<th class="align-center text-left" width="20%">Mã căn</th>
					<th class="align-center text-left" width="30%">Bắt đầu</th>
					<th class="align-center text-left" width="10%">Kết thúc</th>
					<th class="align-center text-left" width="160px">Ngày tạo</th>
					<th class="align-center text-left" width="160px">Hiển thị</th>
					<th class="align-center text-left" width="160px">Lặp lại</th>
					<th class="align-center text-left" width="45px"></th>
				</tr></thead>
				{section name=i loop=$list_preloaders max = 15}
				<tr>
					<td class="text-center">
						<div class="animate-bg w-100 h-px-15 rounded-2 mb-1"></div>
						<div class="animate-bg w-50 h-px-15 rounded-2"></div>
					</td>
					<td class="text-center">
						<div class="animate-bg w-100 h-px-15 mb-1 rounded-2"></div>
						<div class="animate-bg w-50 h-px-15 rounded-1"></div>
					</td>
					<td class="text-center">
						<div class="animate-bg w-100 h-px-15 mb-1 rounded-2"></div>
						<div class="animate-bg w-50 h-px-15 rounded-1"></div>
					</td>
					<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
					<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
					<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
					<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
					<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
				</tr>
				{/section}
			</table>
		</div>
		<div id="pager" class="simple-pagination"></div>
	</div>
</div>
{literal}
<style type="text/css">
	.ui-datepicker{ z-index:9999 !important;}
</style>
<script type="text/javascript">
	$(function(){
		$Core.today.list({}, true);
	});
</script>
{/literal}