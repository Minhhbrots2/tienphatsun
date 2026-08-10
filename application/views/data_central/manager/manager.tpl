<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
		<div class="d-flex align-items-center gap-2">
			<div class="kYlZoryVmS">
				<h4 class="fw-bold mb-0 fs-5">Quản lý chăm sóc</h4>
			</div>
			<a href="{$clsISO->getLink('data_central')}" class="btn btn-sm btn-outline-warning">
				<i class="bx bx-redo"></i> Danh sách data cư dân
			</a>
		</div>
	</div>
	<div class="ajax" gId="{$gId}" data-url="{$PCMS}?mod={$mod}&sub={$sub}&act=load_total_chart" data-options="{}">
		<div class="form-row">
			<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
				<div class="box_item_statistic h-100 p-3 bg-danger text-white rounded-2"> 
					<p class="title_statistic fs-6 mb-2">Tổng số</p>
					<div class="number_total fs-3">1000<span class="fs-14 ml-1">khách hàng</span></div>
				</div>
			</div>
			<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
				<div class="box_item_statistic h-100 p-3 text-white rounded-2" style="background:#1d6a01"> 
					<p class="title_statistic fs-6 mb-2">Đã gọi</p>
					<div class="number_total fs-3">500<span class="fs-14 ml-1">khách hàng</span></div>
				</div>
			</div>
			<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
				<div class="box_item_statistic h-100 p-3 text-white rounded-2" style="background:#eba000"> 
					<p class="title_statistic fs-6 mb-2">Hôm nay</p>
					<div class="number_total fs-3">20<span class="fs-14 ml-1">cuộc gọi</span></div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="form-row">
		<div class="col-12 col-md-12 col-lg-6 mb-2">
			<div class="card no-shadow item_load_ajax h-100 pb-4">
				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="card-title mb-0 fs-6 text-dark">Biểu đồ thống kê số cuộc gọi</h3>
					<div class="p_top ">
						<div class="btn-group d-flex" role="group">
							<div class="input-group d-flex ox:w-100 w-px-150" role="group">
								<input type="date" class="form-control search_field" onchange="$Core.manager_central.do_search(this,event)" name="date" value="{$smarty.now|date_format:'%Y-%m-%d'}">
							</div>
						</div>
					</div>
				</div>						
				<div id="tableReport" class="card-body pb-0 freeze-table dragscroll text-nowrap">
					<div class="ajax" gId="{$gId}" data-url="{$PCMS}?mod={$mod}&sub={$sub}&act=load_chart_call" data-options="{}">
					
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-12 col-lg-6 mb-2">
			<div class="card no-shadow item_load_ajax h-100 pb-4">
				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
					<h3 class="card-title mb-0 fs-6 text-dark">Biểu đồ thống kê số cuộc gọi nhân viên</h3>
					<div class="p_top ">
						<div class="input-group d-flex ox:w-100{if $deviceType ne 'phone'} w-px-250{/if}" role="group">
							<input type="date" class="form-control search_field" onchange="$Core.manager_central.do_search(this,event)" 
								name="start_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
							<input type="date" class="form-control search_field" onchange="$Core.manager_central.do_search(this,event)" 
								name="due_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
						</div>
					</div>
				</div>						
				<div id="tableReport" class="card-body pb-0 freeze-table dragscroll text-nowrap">
					<div class="ajax" gId="{$gId}" data-url="{$PCMS}?mod={$mod}&sub={$sub}&act=load_chart_call_staff" data-options="{}">
					
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-md-12 col-lg-6 mb-2">
			<div class="card no-shadow item_load_ajax h-100 pb-4">
				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
					<div class="d-flex flex-column gap-1">
						<h3 class="card-title mb-0 fs-6 text-dark">Danh sách chuyển đổi CRM</h3>
						<p class="mb-0 text-muted">Tổng số <span class="text-main fw-bold total_record">0</span> khách hàng</p>
					</div>
					<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
						<div class="search-top d-flex{if $deviceType eq 'phone'} w-100{/if} gap-1 align-items-center">
							<label class="text-nowrap d-none d-lg-block">Lọc theo:</label>
							<div class="d-flex gap-2 align-items-center">
								<div class="w-px-150">
									<select name="staff_id" id="" class="form-select iso-select2 search_field" onchange="$Core.manager_central.do_search(this,event)" data-placeholder="Nhân viên" data-width="100%">
										<option value="0">Nhân viên</option>
										{if !empty($lstUser)}
											{foreach from=$lstUser item=item name=item}
												<option value="{$item.profile_id}">
													{$clsProfile->getFullName($item.profile_id,$item)}
												</option>
											{/foreach}
										{/if}
									</select>
								</div>
								<div class="input-group d-flex ox:w-100{if $deviceType ne 'phone'} w-px-250{/if}" role="group">
									<input type="date" class="form-control search_field" onchange="$Core.manager_central.do_search(this,event)" 
										name="start_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
									<input type="date" class="form-control search_field" onchange="$Core.manager_central.do_search(this,event)" 
										name="due_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="tableReport" class="card-body pb-0 freeze-table dragscroll text-nowrap" style="max-height: 300px;overflow-y: auto">
					<table class="table table-border table-ilooca text-nowrap" width="100%">
						<thead><tr>
							{if $deviceType ne 'phone'}
							<th width="4%" class="align-center text-dark text-center">STT</th>{/if}
							<th class="align-center text-left w-px-125">Khách hàng</th>
							<th class="align-center border-end text-left">Điện thoại</th>
							<th class="align-center text-left w-px-200">Người thực hiện</th>
							<th class="align-center text-left w-px-175">Thời gian</th>
						</tr></thead>
						<tbody id="holder_stock_resource_logs" class="ajax" gId="{$gId}" data-url="{$PCMS}?mod={$mod}&sub={$sub}&act=load_list_log&type=potential_CRM_log" data-options="{}">
							{section name=i loop=$list_preloaders}
								<tr>
									{if $deviceType ne 'phone'}<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>{/if}
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								</tr>
							{/section}
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-12 col-lg-6 mb-2">
			<div class="card no-shadow item_load_ajax h-100 pb-4">
				<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
					<div class="d-flex flex-column gap-1">
						<h3 class="card-title mb-0 fs-6 text-dark">Danh sách thống kê chăm sóc</h3>
						<p class="mb-0 text-muted">Tổng số <span class="text-main fw-bold total_record">0</span> cuộc gọi</p>
					</div>
					<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
						<div class="search-top d-flex{if $deviceType eq 'phone'} w-100{/if} gap-1 align-items-center">
							<label class="text-nowrap d-none d-lg-block">Lọc theo:</label>
							<div class="d-flex gap-2 align-items-center">
								<div class="w-px-150">
									<select name="staff_id" id="" class="form-select iso-select2 search_field" onchange="$Core.manager_central.do_search(this,event)" data-placeholder="Nhân viên" data-width="100%">
										<option value="0">Nhân viên</option>
										{if !empty($lstUser)}
											{foreach from=$lstUser item=item name=item}
												<option value="{$item.profile_id}">
													{$clsProfile->getFullName($item.profile_id,$item)}
												</option>
											{/foreach}
										{/if}
									</select>
								</div>
								<div class="input-group d-flex ox:w-100{if $deviceType ne 'phone'} w-px-250{/if}" role="group">
									<input type="date" class="form-control search_field" onchange="$Core.manager_central.do_search(this,event)" 
										name="start_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
									<input type="date" class="form-control search_field" onchange="$Core.manager_central.do_search(this,event)" 
										name="due_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="" class="card-body pb-0 freeze-table dragscroll text-nowrap" style="max-height: 300px;overflow-y: auto">
					<table class="table table-border table-ilooca text-nowrap" width="100%">
						<thead><tr>
							{if $deviceType ne 'phone'}
							<th width="4%" class="align-center text-dark text-center">STT</th>{/if}
							<th class="align-center text-left w-px-125">Khách hàng</th>
							<th class="align-center border-end text-left">Điện thoại</th>
							<th class="align-center text-left w-px-200">Người thực hiện</th>
							<th class="align-center text-left w-px-175">Thời gian</th>
						</tr></thead>
						<tbody id="holder_stock_resource_logs" class="ajax" gId="{$gId}" data-url="{$PCMS}?mod={$mod}&sub={$sub}&act=load_list_log&type=call_success" data-options="{}">
							{section name=i loop=$list_preloaders}
							<tr>
								{if $deviceType ne 'phone'}
								<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								{/if}
								<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							</tr>
							{/section}
						</tbody>
					</table>
				</div>
			</div>			
		</div>
	</div>
	
</div>