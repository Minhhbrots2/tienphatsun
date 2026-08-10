<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
		<div class="sLXazhNQJU mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1">Quản lý Quỹ ôm</h4>
			<span class="text-muted">Hiện có tổng cộng <strong class="text-main total-record">0</strong> quỹ ôm</span>
		</div>
		{assign var = uid value= $clsISO->getUniqid()}
		<div class="sBwcBlvAwX d-flex gap-1 align-items-center{if $deviceType ne 'phone'} bg-grayter rounded-2 p-2{/if}">
			<div class="d-none d-lg-flex gap-1 align-items-center">
				<div class="form-group">
					<select placeholder="Chọn dự án" class="iso-selectizeSync search_field w-px-250" uid="{$uid}" 
						name="project_id" data-field="project_id" onChange="$Core.stock_hug.do_search(this, event)" >
						<option value="0">Dự án</option>
						{if !empty($arr_projects)}
							{foreach from=$arr_projects item = _oProject}
							<option{if $get_project_id eq $_oProject.setting_id} selected{/if} value="{$_oProject.setting_id}">{$_oProject.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>
			{if $deviceType eq 'phone'}
			<button data-toggle="ripple" type="button" title="Thêm nhanh" onClick="$Core.stock_hug.open(this, event)" 
				stock_hug_id="0" class="btn btn-icon btn-outline-primary create_quick_stock_hug">{$clsISO->makeIcon('bx-plus')}</button>
			{else}
			<button data-toggle="ripple" type="button" title="Thêm nhanh" onClick="$Core.stock_hug.open(this, event)" 
				stock_hug_id="0" class="btn flex-fill btn-outline-primary create_quick_stock_hug">{$clsISO->makeIcon('bx-plus', 'Thêm mới')}</button>
			{/if}
			{if $clsISO->checkPermission('stock_hug_import')}
			<button data-toggle="ripple" type="button" title="Thêm nhanh" onClick="$Core.stock_hug.open_import(this, event)" 
				stock_hug_id="0" class="btn btn-icon btn-outline-default">{$clsISO->makeIcon('bx-import')}</button>
			{/if}
			<div class="btn-group dropdown">
				<button id="{$uid}" type="button" class="btn btn-icon btn-outline-default hide-arrow dropdown-toggle" 
				data-bs-toggle="dropdown" data-toggle="ripple" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">{$clsISO->makeIcon('bx-search')}</button>
				<div class="dropdown-menu mega-dropdown-menu dropdown-stock-search dropdown-menu-end w-px-350" data-popper-placement="top-end">
					<div class="p-3">{$core->getBlock('stock_hug_search', ['uid' => $uid])}</div>
				</div> 
			</div>
		</div>
	</div>
    <!-- Basic Bootstrap Table -->
    <div class="card no-shadow">
		<div class="card-body">
			
			<div class="d-flex briefStockHug flex-wrap gap-3 mb-2 align-items-center">
				{foreach from=$list_briefs item = _oItem}
				<div class="border flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">{$_oItem.title}</h5>
						<a class="panel-help help_pop openHelp" title="{$_oItem.subtitle}">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">0.000</strong>
						</li>
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Tổng tiền:</span>
							<strong class="fs-5 text-main">0.000</strong>
						</li>
					</ul>
				</div>
				{/foreach}
			</div>
			<div class="alert mb-2 alert-warning text-center alert-dismissible">
				Click đúp chuột vào dòng để xem sửa nhanh thông tin đã nhập.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<div id="tableStockHug" class="table-container no-shadow dragscroll ">
				<table cellpadding="0" cellspacing="0" class="table table-hug table-bordered">
					<thead><tr>
						<th width="3%" class="align-center h-px-40 bg-lighter">STT</th>
						<th class="align-center h-px-40 bg-lighter">Mã căn</th>
						<th class="align-center h-px-40 bg-lighter">Tên XNĐK</th>
						<th class="align-center h-px-40 bg-lighter text-center">Ngày ký<br />XNĐK</th>
						<th class="align-center h-px-40 bg-lighter text-left">Link ký XNĐK</th>
						<th class="align-center h-px-40 bg-lighter text-left">Tiền cọc<br /> vào CĐT</th>
						<th class="align-center h-px-40 bg-lighter">Tình trạng</th>
						<th class="align-center h-px-40 bg-lighter">Sales bán</th>
						<th class="align-center h-px-40 bg-lighter">Trạng thái</th>
						<th class="align-center h-px-40 bg-lighter text-right">Giá bán</th>
						<th class="align-center h-px-40 bg-lighter">Ngày cọc</th>
						<th class="align-center h-px-40 bg-lighter text-right">Tiền cọc</th>
						<th class="align-center h-px-40 bg-lighter">Quỹ</th>
						<th class="align-center h-px-40 bg-lighter">N.Căn</th>
						<th class="align-center h-px-40 w-px-150 bg-lighter text-center">Ngày<br />thu hồi</th>
						<th class="align-center h-px-40 w-px-150 bg-lighter text-center">Ngày ký<br />VBTT</th>
						<th class="align-center h-px-40 w-px-150 bg-lighter text-center">Ngày ký<br />HĐMB</th>
						<th class="align-center h-px-40 bg-lighter">%HH</th>
						<th class="align-center h-px-40 bg-lighter text-center">Thưởng<br />Sales</th>
						<th class="align-center h-px-40 bg-lighter">Notes</th>
						<th class="align-center h-px-40 bg-lighter text-center w-px-50"></th>
					</tr></thead>
					<tbody class="holderStockHug text-nowrap">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
						</tr>
						{/section}
					</div>
				</table>
			</div>
			<input type="hidden" name="per_page" value="1" />
			<input type="hidden" name="current_page" value="1" />
			<div id="pager_stock_hug" class="easyui-pagination"></div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.table-hug tr th:nth-child(2){
		position:sticky;
		left:47px; top:0;
	}
	.table-hug tr td:nth-child(2){
		position:sticky;
		left:47px; top:0;
		background:var(--bs-white);
	}
	.selectize-control.single .selectize-input>.item{
		white-space: nowrap;
	}
</style>
{/literal}