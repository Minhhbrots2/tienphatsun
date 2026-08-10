<script type="text/javascript"> var booking_type = '{$booking_type}'; </script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y booking_page">
	<form action="#" method="POST" onsubmit="return false;">
		<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
			<div class="p__left mb-2 mb-lg-0">
				<h4 class="fw-bold mb-1">Quản lý Booking</h4>
				<span class="text-muted">Tổng cộng <strong class="text-main">{$total_record}</strong> Booking {$smarty.const.BRAND_NAME}</span>
			</div>
			{if $permiss_full == 1}
			<div class="d-none d-lg-block">
				<div class="d-flex align-items-center">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="btn-group flex-fill d-flex align-items-center" aria-label="Sắp xếp" bis_skin_checked="1">
						{foreach from=$type_arrs key = _oKey item = _oVal}
						<input type="radio"{if $get_booking_type eq $_oKey} checked{/if} 
						class="btn-check" uid="{$uid}" id="{$_oKey}_{$uid}" value="{$_oKey}" onChange="$Core.booking.select_type(this, event)">
						<label data-toggle="ripple" class="btn btn-outline-default px-5 text-upper text-nowrap" for="{$_oKey}_{$uid}">{$_oVal}</label>
						{/foreach}
					</div>
				</div>
			</div>
			{/if}
			<div class="d-flex align-items-center gap-1{if !$clsISO->checkSale()} xs:w-100{/if}">
				{if $permiss_full == 1}
				<div class="d-lg-none flex-fill">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="btn-group d-flex align-items-center" aria-label="Sắp xếp" bis_skin_checked="1">
						{foreach from=$type_arrs key = _oKey item = _oVal}
						<input type="radio"{if $get_booking_type eq $_oKey} checked{/if} 
						class="btn-check" uid="{$uid}" id="{$_oKey}_{$uid}" value="{$_oKey}" onChange="$Core.booking.select_type(this, event)">
						<label data-toggle="ripple" class="btn btn-outline-default text-nowrap" for="{$_oKey}_{$uid}">{$_oVal}</label>
						{/foreach}
					</div>
				</div>
				{/if}
				{if $permiss_add eq '1'}
				<button type="button" data-toggle="ripple" onClick="$Core.booking.open(this, event)" booking_id="0" 
					booking_type="{$booking_type}" project_id="{$get_project_id}" block_id="{$get_block_id}" building_id="{$get_building_id}" 
					class="btn btn-outline-danger js__create-booking text-nowrap">Thêm mới</button>
				{/if}
				{if $deviceType eq 'phone'}
					<button type="button" class="btn{if $deviceType eq 'phone'} btn-icon{/if} btn-default dropdown-toggle hide-arrow" 
					data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
						<i class="bx bx-filter-alt"></i>{if $deviceType ne 'phone'} Bộ lọc{/if}
					</button>
					<div class="dropdown-menu dropdown-menu-end w-px-{if $deviceType eq 'phone'}300{else}500{/if}" data-popper-placement="top-end">
						{$core->getBlock('booking_search', ['arr_projects' => $arr_projects])}
					</div>
				{/if}
			</div>
		</div>
	</form>
    <!-- Basic Bootstrap Table -->
    <div class="card">
		<div class="card-body">
			{if $deviceType ne 'phone'}
				<form action="#" method="POST" onsubmit="return false;">
					{assign var=uid value=$clsISO->getUniqid()}
					<div class="form-group form-row flex-wrap search-block pb-3 border-bottom">
						<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Từ khóa</label>
							<input type="text" class="form-control search_field" placeholder="Nhập từ khóa tìm kiếm..." onChange="$Core.booking.do_search(this, event)" data-field="keysearch" />
						</div>
						<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Tình trạng</label>
							<select class="form-control search_field multiselect w-100" uid="{$uid}" call_from="search" placeholder="Tình trạng" onChange="$Core.booking.do_search(this, event)" name="status_id" data-field="status_id">
								<option value="">Tình trạng</option>
								{$clsProperty->getSelectByProperty('_BOOKING_STATUS',0, "Tình trạng")}
							</select>
						</div>
						{if $mod == 'booking' && $act == 'default'}
						<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Phòng ban</label>
							<select class="form-control search_field multiselect w-100" uid="{$uid}" call_from="search" placeholder="Phòng ban" onChange="$Core.booking.handle_dep_change(this, event);$Core.booking.do_search(this, event)" name="department_id" data-field="department_id">
								<option value="">Phòng ban</option>
								{$clsProperty->getSelectByProperty('_DEPARTMENT', 0, "Phòng ban")}
							</select>
						</div>
						<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Nhân viên</label>
							<select class="iso-selectizeImageSync search_field w-100" placeholder="Nhân viên" name="staff_id" data-field="staff_id" onChange="$Core.booking.do_search(this, event)">
								<option value="">Nhân viên</option>
							</select>
						</div>
						{/if}
						<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Dự án</label>
							<select class="form-control search_field multiselect slb_project" onChange="$Core.booking.load_block(this, event);$Core.booking.do_search(this, event)" data-placeholder="Dự án" data-width="100%" data-header="true" data-filter="true" uid="{$uid}" data-field="project_id">
								<option value="">Dự án</option>
								{if !empty($arr_projects)}
									{foreach from=$arr_projects item = _oProject}
									<option{if $get_project_id eq $_oProject.project_id} selected{/if} value="{$_oProject.project_id}">{$_oProject.project_name}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Phân khu/Block</label>
							<select class="form-control search_field multiselect slb_block slb_block_{$uid}" onChange="$Core.booking.load_building(this, event);$Core.booking.do_search(this, event)" data-placeholder="Phân khu" data-width="100%" data-header="true" data-filter="true"  uid="{$uid}" id="slb_Block_Id" toId="slb_Building_Id" data-field="block_id">
								{if !empty($arr_projects)}
									{foreach from=$arr_projects item = _oBlock}
									<option{if $get_block_id eq $_oBlock.block_id} selected{/if} value="{$_oBlock.block_id}">{$_oBlock.block_name}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Tòa nhà</label>
							<select class="form-control search_field multiselect slb_building_{$uid}" data-placeholder="Tòa căn hộ" data-width="100%" data-header="true" data-filter="true" onChange="$Core.booking.do_search(this, event)" data-field="building_id">							
							</select>
						</div>
						{*<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Thời gian</label>
							<div class="input-group-date w-100">
								<input type="text" class="form-control isodaterangepicker search_field"  onChange="$Core.booking.do_search(this, event)" name="time_range" data-field="time_range" />
							</div>
						</div>*}
						<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Trạng thái</label>
							<select class="form-control search_field multiselect w-100" uid="{$uid}" call_from="search" placeholder="Trạng thái" onChange="$Core.booking.do_search(this, event)" name="state_id" data-field="state_id">
								<option value="">Trạng thái</option>
								{$clsProperty->getSelectByProperty('_BOOKING_STATE', $get_state_id, "Trạng thái")}
							</select>
						</div>
					</div>
				</form>
			{/if}
			<ul class="nav nav-tabs nav-tabs-bordered mb-2" role="tablist">
				{foreach name=i from=$arr_projects item = _oProject}
					<li class="nav-item" role="presentation">
						<div class="d-flex align-items-center gap-1 position-relative">
							<button onClick="$Core.booking.select_project(this, event)" project_id="{$_oProject.project_id}" block_id="{$_oProject.block_id}" class="nav-link tab_project{if $get_project_id eq $_oProject.project_id && $get_block_id eq $_oProject.block_id} active{/if}" role="tab">{$_oProject.block_name}</button>
							<div class="dropdown position-absolute right-0">
								<button type="button" data-bs-toggle="dropdown" class="btn btn-sm btn-icon btn-link text-muted dropdown-toggle hide-arrow rounded-pill"><i class="bx bx-dots-vertical-rounded"></i></button>
								<ul class="dropdown-menu dropdown-menu-end">
									{if $booking_type eq $smarty.const._BOOKING_TYPE_INTERNAL_ID}
									<li><a href="{$clsBooking->getLinkBooking($_oProject.project_id,$_oProject.block_id)}" class="dropdown-item text-link"><i class='bx bx-link-external'></i> Bảng booking</a></li>{/if}
									{if $permiss_full eq '1'}
									<li><a href="/booking/thong-ke.html" class="dropdown-item text-link">
										<i class='bx bx-line-chart-down'></i> Thống kê</a>
									</li>{/if}
								</ul>
							</div>
						</div>
					</li>
				{/foreach}
			</ul>
			<div class="briefs mb-2 gap-2 gap-lg-2 d-flex flex-wrap">
				<div class="brief-item a1a bg-orange clickable">
					<p class="text-fs-13 mb-0">Tổng booking</p>
					<hr class="w-px-50 my-2" />
					<h3 class="text-fs-16 mb-0 text-white">0 BK</h3>
					<h3 class="text-fs-16 mb-0 text-white">0 {$clsISO->getRate()}</h3>
				</div>
				<div class="brief-item a2a brief-item-clickable bg-azure">
					<p class="text-fs-13 mb-0">Khớp cọc</p>
					<hr class="w-px-50 my-2" />
					<h3 class="text-fs-16 mb-0 text-white">0 BK</h3>
					<h3 class="text-fs-16 mb-0 text-white">0 {$clsISO->getRate()}</h3>
				</div>
				<div class="brief-item a6a brief-item-clickable bg-solid">
					<p class="text-fs-13 mb-0">Hoàn cọc</p>
					<hr class="w-px-50 my-2" />
					<h3 class="text-fs-16 mb-0 text-white">0 BK</h3>
					<h3 class="text-fs-16 mb-0 text-white">0 {$clsISO->getRate()}</h3>
				</div>
				<div class="brief-item a3a brief-item-clickable bg-cyan">
					<p class="text-fs-13 mb-0">10% vào {$smarty.const.BRAND_NAME}</p>
					<hr class="w-px-50 my-2" />
					<h3 class="text-fs-16 mb-0 text-white">0 BK</h3>
					<h3 class="text-fs-16 mb-0 text-white">0 {$clsISO->getRate()}</h3>
				</div>
				<div class="brief-item a5a brief-item-clickable bg-green">
					<p class="text-fs-13 mb-0">Tổng tồn 10%</p>
					<hr class="w-px-50 my-2" />
					<h3 class="text-fs-16 mb-0 text-white">0 BK</h3>
					<h3 class="text-fs-16 mb-0 text-white">0 {$clsISO->getRate()}</h3>
				</div>
				<div class="brief-item a5a brief-item-clickable bg-purple">
					<p class="text-fs-13 mb-0">Tổng cọc tồn</p>
					<hr class="w-px-50 my-2" />
					<h3 class="text-fs-16 mb-0 text-white">0 BK</h3>
					<h3 class="text-fs-16 mb-0 text-white">0 {$clsISO->getRate()}</h3>
				</div>
			</div>
			<div class="table-container overflow-x-auto text-nowrap no-shadow">
				<table cellpadding="0" cellspacing="0" class="table table-striped table-booking table-bordered dragable mb-0" width="100%">
					<thead><tr>
						<th class="align-center bg-lighter h-px-40">Mã BK</th>
						<th class="align-center bg-lighter h-px-40">Ngày Booking</th>
						{if $booking_type eq $smarty.const._BOOKING_TYPE_INTERNAL_ID}
						<th class="align-center bg-lighter h-px-40">Họ tên KH / UNC</th>
						<th class="align-center bg-lighter h-px-40">Nội dung UNC</th>
						<th class="align-center bg-lighter h-px-40">Họ tên Sales</th>
						<th class="align-center bg-lighter h-px-40">K.Tầng</th>
						<th class="align-center bg-lighter h-px-40">Trục</th>
						<th class="align-center bg-lighter h-px-40">U.tiên</th>
						{else}
						<th class="align-center bg-lighter h-px-40">Họ và tên</th>
						<th class="align-center bg-lighter h-px-40">Số PN</th>
						<th class="align-center bg-lighter h-px-40">Trục căn</th>
						<th class="align-center bg-lighter h-px-40">Khoảng tầng</th>
						<th class="align-center bg-lighter h-px-40">Loại tầng</th>
						<th class="align-center bg-lighter h-px-40">Mã FT</th>
						{/if}
						<th class="align-center bg-lighter h-px-40">Dự án / Phân khu</th>
						<th class="align-center bg-lighter h-px-40 text-right">Số tiền</th>
						<th class="align-center bg-lighter h-px-40 text-center">T.Trạng</th>
						{if $booking_type eq $smarty.const._BOOKING_TYPE_INTERNAL_ID}
						<th class="align-center bg-lighter h-px-40 text-center">Trạng thái</th>{/if}
						<th class="align-center bg-lighter h-px-40" width="150px">Ngày tạo</th>
						<th class="align-center bg-lighter h-px-40 text-center" width="35px"></th>
					</tr> </thead>
					<tbody class="holder_bookings">
						{section name=i loop=$list_preloaders max = 30}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							{if $booking_type eq $smarty.const._BOOKING_TYPE_INTERNAL_ID}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							{else}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							{/if}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							{if $booking_type eq $smarty.const._BOOKING_TYPE_INTERNAL_ID}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							{/if}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
		</div>
    </div>
</div>
{literal}
<style type="text/css">
	.tab_project{
		padding-right:2rem !important;
	}
	tr.bg-cancel td{
		background:#fbe9e9 !important;
		--bs-table-accent-bg:#fbe9e9 !important;
	}
	.no-radius-right .selectize-input{
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		border-right: 0px !important;
	}
	.selectize-input,
	.selectize-control.single .selectize-input.focus{
		padding:7px !important;
		min-height:36px !important;
	}
	.table-booking tr th:nth-child(1),
	.table-booking tr td:nth-child(1){
		min-width:115px;
	}
	.selectize-input{
		white-space: nowrap;
   	 	overflow: hidden;
	}
	@media screen and (min-width:1400px){
		.col-xxxl-1\/10{
			width:10%;
		}
	}
</style>
{/literal}