{assign var = uid value = $clsISO->getUniqid()}
<script type="text/javascript"> var booking_type = '{$smarty.const._BOOKING_TYPE_INTERNAL_ID}'; </script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y my_booking">
	<form action="#" method="POST" onsubmit="return false;">
		<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
			<div class="d-flex flex-column mb-2 mb-lg-0">
				<h4 class="fw-bold mb-1">Quản lý Booking</h4>
				<span class="text-muted">Tổng cộng <strong class="text-main">0</strong> Booking {$smarty.const.BRAND_NAME}</span>
			</div>
			<div class="d-flex align-items-center gap-2">
				<button type="button" data-toggle="ripple" onClick="$Core.booking.open(this, event)" booking_id="0" 
					booking_type="{$smarty.const._BOOKING_TYPE_INTERNAL_ID}" project_id="{$get_project_id}" block_id="{$get_block_id}" building_id="{$get_building_id}" class="btn btn-outline-danger{if $deviceType eq 'phone'} btn-icon{/if} js__create-booking"><i class="bx bx-plus"></i>{if $deviceType ne 'phone'} Thêm mới{/if}</button>
				<div class="dropdown">
					<button type="button" class="btn{if $deviceType eq 'phone'} btn-icon{/if} btn-default dropdown-toggle{if $deviceType eq 'phone'} hide-arrow{/if}" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-haspopup="true" aria-expanded="true">
						<i class="bx bx-filter-alt"></i>{if $deviceType ne 'phone'} Bộ lọc{/if}
					</button>
					<div class="dropdown-menu dropdown-menu-end w-px-{if $deviceType eq 'phone'}300{else}400{/if}" data-popper-placement="top-end">{$core->getBlock('booking_search', ['uid' => $uid, 'arr_projects' => $arr_projects])}</div>
				</div>
				<div class="btn-group d-flex align-items-center" aria-label="Sắp xếp">
					{foreach from=$view_arrs key = _oKey item = _oVal}
					<input type="radio" class="btn-check" name="view_by" uid="{$uid}" onchange="$Core.booking.do_search(this, event)" 
						id="{$_oKey}_{$uid}"{if $_oKey eq $get_view_by} checked{/if} value="{$_oKey}">
					<label data-toggle="ripple" class="btn btn-icon btn-outline-default" for="{$_oKey}_{$uid}">
						<i class="bx {$_oVal}"></i>
					</label>
					{/foreach}
				</div>
			</div>
		</div>
	</form>
	<div class="card">
		<div class="card-body">
			<div class="briefs mb-3 gap-2 gap-lg-2 d-flex flex-wrap">
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
			<!-- table-striped -->
			<div class="table-container holder_mybookings overflow-x-auto text-nowrap no-shadow">
				<table cellpadding="0" cellspacing="0" class="table table-booking table-bordered dragable mb-0" width="100%">
					<thead><tr>
						<th class="text-center bg-lighter h-px-40">Mã BK</th>
						<th class="align-center bg-lighter h-px-40">Ngày Booking</th>
						<th class="align-center bg-lighter h-px-40">Họ tên KH / UNC</th>
						<th class="align-center bg-lighter h-px-40">Nội dung UNC</th>
						<th class="align-center bg-lighter h-px-40">K.Tầng</th>
						<th class="align-center bg-lighter h-px-40">Trục</th>
						<th class="align-center bg-lighter h-px-40 text-right">Số tiền</th>
						<th class="align-center bg-lighter h-px-40 text-center">T.Trạng</th>
						<th class="align-center bg-lighter h-px-40 text-center">T.thái</th>
						<th class="align-center bg-lighter h-px-40 text-center" width="35px"></th>
					</tr> </thead>
					<tbody>
						{section name=i loop=$list_preloaders max = 30}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
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