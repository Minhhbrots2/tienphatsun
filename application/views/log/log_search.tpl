<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
		<div class="nlApYyxOPs mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1">Lịch sử tìm kiếm</h4>
			<p class="text-muted mb-0">Có <strong class="total_record text-danger">{$total_record}</strong> lịch sử tìm kiếm</p>
		</div>
		<div class="dropdown">
			<button type="button" class="btn btn-icon btn-default hide-arrow dropdown-toggle" data-bs-toggle="dropdown" 
				data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
				<i class="bx bx-filter"></i>
			</button>
			<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">
				<form class="p-4" method="POST">
					<div class="form-group mb-2">
						<div class="input-group input-group-merge">
							<span class="input-group-text"><i class="bx bx-search"></i></span>
							<input type="text" class="form-control search_field" data-field="keySearch" placeholder="{$core->get_Lang('Search')}" />
						</div>
					</div>
					<div class="form-group form-row mb-2">
						<div class="col-6">
							<input type="text" value="{$start_date}" class="form-control search_field" 
								placeholder="Từ ngày" data-field="start_date">
						</div>
						<div class="col-6">
							<input type="text" class="form-control search_field" 
								placeholder="Đến ngày" data-field="end_date">
						</div>
					</div>
					<div class="form-group mb-2">
						<select id="slb_Profile_Id" class="iso-select2 search_field" data-field="user_id" data-width="100%" data-placeholder="Nhân viên" data-allow-clear="true">
							<option value="0">Nhân viên</option>
							{foreach from=$lstUser item=item name=item}
							<option value="{$item.profile_id}">
								{$clsProfile->getFullName($item.profile_id,$item)}
							</option>
							{/foreach}
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" name="stock_id" class="search_field" data-field="stock_id" value="{$stock_id}" />
						<button type="button" class="btn btn-primary" onClick="$Core.log.do_search_key(this, event)"> 
							<i class="bx bx-search"></i> Tìm kiếm
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- Basic Bootstrap Table -->
    <div class="card">
		<div class="card-body">
			<div class="table-container no-shadow mb-2 overflow-x-auto text-nowrap">
				<table border="0" cellpadding="0" cellspacing="0" class="table table-striped mb-0" width="100%">
					<thead><tr>
						<th width="15%" class="align-center h-px-35">Nhân viên</th>
						<th width="300px" class="align-center bg-lighter h-px-35">Key search</th>
						<th width="150px" class="align-center bg-lighter h-px-35">Thời gian</th>
						<th class="align-left bg-lighter h-px-35">User-IP</th> 
					</tr> </thead>
					<tbody class="holder_logs">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div id="pager_search_logs"></div>
		</div>
    </div>
    <!--/ Basic Bootstrap Table -->
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$Core.log.load_logs_search({});
	});
</script>
{/literal}