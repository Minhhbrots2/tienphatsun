{$scriptJs}
<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1">Sự kiện chủ đầu tư</h4>
			<span class="text-muted">Các sự kiện chủ đầu tư</span>
		</div>
		<div class="p__right d-flex">			
			{if $permiss_add eq 1}
			<div class="d-flex justify-content-end">
				{if $deviceType eq 'phone'}
				<a href="javascript:void(0)" onClick="$Core.course.open_course(this,event)" data-type="open" _tp="event" cat_id="{$cat_id}" data-course_id="0" class="btn btn-icon btn-outline-primary mr-2"><i class='bx bx-plus'></i></a>
				{else}
				<a href="javascript:void(0)" onClick="$Core.course.open_course(this,event)" data-type="open" _tp="event" cat_id="{$cat_id}" data-course_id="0" class="btn btn-outline-primary mr-2">Thêm mới </a>
				{/if}
				<div class="btn-group">
					<button type="button" class="btn btn-icon btn-outline-default dropdown-toggle hide-arrow" 
					data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
						<i class="bx bx-filter-alt"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-end w-px-350" data-popper-placement="bottom-end"><div class="p-3">
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
							<button type="button" class="btn btn-success" onClick="$Core.course.do_search(this, event)">
								<i class="bx bx-search"></i> Tìm kiếm
							</button>
						</div>
					</div></div>
				</div>
			</div>	
			{/if}
		</div>
	</div>
	<div class="card page_list">
		<div class="card-body holder_courses pt-3" id="holder_courses">
			<table class="table" width="100%" cellpadding="0">
				<thead><tr>
					<th class="align-center text-left" width="25%">Tiêu đề</th>
					<th class="align-center text-left" width="20%">Loại sự kiện</th>
					<th class="align-center text-left" width="30%">Tham gia</th>
					<th class="align-center text-left" width="10%">Đã tham gia</th>
					<th class="align-center text-left" width="160px">Ngày tạo</th>
					{if $permiss_edit eq '1'}
					<th class="align-center text-left" width="45px"></th>
					{/if}
				</tr></thead>
				{section name=i loop=$list_preloaders max = 15}
				<tr>
					<td class="text-center">
						<div class="animate-bg w-100 h-px-20 rounded-1 mb-1"></div>
						<div class="animate-bg w-50 h-px-20 rounded-1"></div>
					</td>
					<td class="text-center">
						<div class="animate-bg w-100 h-px-20 mb-1 rounded-1"></div>
						<div class="animate-bg w-50 h-px-20 rounded-1"></div>
					</td>
					<td class="text-center">
						<div class="animate-bg w-100 h-px-20 mb-1 rounded-1"></div>
						<div class="animate-bg w-50 h-px-20 rounded-1"></div>
					</td>
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
<script>
	var cat_id = `{$cat_id}`;
</script>
{literal}
<style type="text/css">
	.ui-datepicker{ z-index:9999 !important;}
</style>
<script type="text/javascript">
	$(function(){
		$Core.course.list({"cat_id":cat_id}, true);
	});
</script>
{/literal}