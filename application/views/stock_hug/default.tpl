<!-- Style -->
<link rel="stylesheet" type="text/css" href="{$URL_JS}/easyui/themes/gray/easyui.css" media="all" />
<!-- Script -->
<script type="text/javascript" src="{$URL_JS}/easyui/easyloader.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/easyui/jquery.easyui.min.js?v={$upd_version}"></script>
<!-- <script type="text/javascript" src="{$URL_JS}/wickedpicker/src/wickedpicker.js?v={$upd_version}"></script> -->
<div class="container-xxl flex-grow-1 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
		<div class="p__left mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1">Quỹ ôm Masteri</h4>
			<span class="text-muted">Hiện có tổng cộng <strong class="text-main total-record">0</strong> quỹ ôm</span>
		</div>
		{assign var = gId value= $clsISO->getUniqid()}
		<div class="right__buttons{if $deviceType eq 'phone'} w-100{/if} d-flex gap-1 align-items-center">
			<button data-toggle="ripple" type="button" title="Thêm nhanh" onClick="$Core.stock_hug.open(this, event)" 
				stock_hug_id="0" class="btn flex-fill btn-outline-primary create_quick_stock_hug">{$clsISO->makeIcon('bx-plus', 'Thêm mới')}</button>
			<button data-toggle="ripple" type="button" title="Thêm nhanh" onClick="$Core.stock_hug.crawl(this, event)" 
				stock_hug_id="0" class="btn flex-fill d-none btn-outline-default">{$clsISO->makeIcon('bx-import', 'Import Excel')}</button>
			<div class="btn-group dropdown">
				<button id="{$gId}" type="button" class="btn btn-icon btn-outline-default hide-arrow dropdown-toggle" 
				data-bs-toggle="dropdown" data-toggle="ripple" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">{$clsISO->makeIcon('bx-search')}</button>
				<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-stock-search dropdown-menu-end w-px-325" data-popper-placement="top-end">
					<div class="p-3">
						<form id="frmIssue" method="POST" onSubmit="return false;">
							<div class="form-group form-row mb-2">
								<div class="col-6">
									<div class="form-label mb-1">Tìm theo từ khóa</div>
									<div class="input-group input-group-merge mr-2">
										<span class="input-group-text"><i class="bx bx-search"></i></span>
										<input class="form-control search_keyword_field search_field" name="keysearch" data-field="keysearch" onClick="this.select();" placeholder="Nhập từ khóa & enter để tìm kiếm...">
									</div>
								</div>
								<div class="col-6">
									<div class="form-label mb-1">Tình trạng</div>
									<select class="form-control search_field form-select" onchange="$Core.stock_hug.do_search(this, event)" data-field="status_id" name="status_id">
										<option value="0">Tình trạng</option>
										{$clsProperty->getSelectByProperty('_STATUS_STOCK_HUG',0)}
									</select>	
								</div>
							</div>
							<div class="form-group mb-2">
								<div class="form-label mb-1">Lựa chọn ngày</div>
								<select class="form-control search_field form-select" onchange="$Core.stock_hug.do_search(this, event)" data-field="date_field" name="date_field">
									<option value="reg_date">Ngày tạo</option>
									<option value="deposit_date">Ngày cọc</option>
									<option value="otp_date">Ngày ký OTP</option>
								</select>
							</div>
							<hr class="my-2" />
							<div class="form-row mb-2">
								<div class="col-6 col-md-6">
									{assign var = uid value = $clsISO->getUniqid()}
									<div class="form-floating">
										<input type="date" class="form-control search_field" id="{$uid}" data-field="start_date" placeholder="dd/mm/yy" aria-describedby="{$uid}" />
										<label for="{$uid}">Từ ngày</label>
									</div>
								</div>
								<div class="col-6 col-md-6">
									{assign var = uid value = $clsISO->getUniqid()}
									<div class="form-floating">
										<input type="date" class="form-control search_field" data-field="to_date" id="{$uid}" placeholder="dd/mm/yy" aria-describedby="{$uid}" onchange="$Core.stock_hug.do_search(this, event)">
										<label for="{$uid}">Tới ngày</label>
									</div>
								</div>
							</div>
							<hr class="my-2" />
							<div class="form-group">
								<button gId="{$gId}" type="button" onClick="$Core.stock_hug.toggle_search(this, event)" class="btn btn-outline-primary">Tìm kiếm</button>
								<button type="reset" onClick="$Core.stock_hug.do_search(this, event)" holderG="_reset" class="btn btn-warning">{$clsISO->makeIcon('bx-refresh', 'Xóa')}</button>
							</div>
						</form>
					</div>
				</div> 
			</div>
		</div>
	</div>
    <!-- Basic Bootstrap Table -->
    <div class="card">
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
			<div id="tableStockHug" class="freeze-table dragscroll text-nowrap">
				<table class="table table-hug table-bordered">
					<thead><tr>
						<th rowspan="2" width="3%" class="align-center bg-grayter">STT</th>
						<th rowspan="2" class="align-center bg-grayter">Mã căn</th>
						<th rowspan="2" class="align-center bg-grayter w-px-50">Loại căn</th>
						<th rowspan="2" class="align-center bg-grayter">Người cọc</th>
						<th colspan="2" class="align-center bg-grayter text-center">Đại lý đi tiền</th>
						<th colspan="2" class="align-center bg-grayter text-center">Ký cọc</th>
						<th rowspan="2" class="align-center bg-grayter">Tra soát</th>
						<th colspan="3" class="align-center bg-grayter text-center">Đại lý bán</th>
						<!-- <th rowspan="2" class="align-center text-center bg-grayter">Đã hoàn<br />2 bên</th> -->
						<th rowspan="2" class="align-center bg-grayter">Tiền tồn</th>
						<th rowspan="2" class="align-center bg-grayter">Tình trạng</th>
						<th rowspan="2" class="align-center bg-grayter">Ghi chú</th>
						<th rowspan="2" class="align-center bg-grayter">Ngày tạo</th>
						<th rowspan="2" class="align-center bg-grayter text-center w-px-50"></th>
					</tr>
					<tr>
						<th class="align-center bg-grayter text-center w-px-50">Đại lý</th>
						<th class="align-center bg-grayter text-center w-px-100">Số tiền</th>
						<!-- <th class="align-center bg-grayter text-center w-px-100">{$smarty.const.BRAND_NAME}</th>
						<th class="align-center bg-grayter text-center w-px-100">EH & ATD</th> -->
						<th class="align-center bg-grayter text-center w-px-125">Ngày cọc</th>
						<th class="align-center bg-grayter text-center w-px-125">Ngày ký OTP</th>
						<th class="align-center bg-grayter text-center w-px-50">Đại lý</th>
						<th class="align-center bg-grayter text-center w-px-125">Ngày bán</th>
						<th class="align-center bg-grayter text-center w-px-100">Tiền cọc về</th>
					</tr></thead>
					<tbody class="holderStockHug">
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
							<!-- <td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id>
							<td><div class="animate-bg rounded-2 w-100 h-px-15"></div></id> -->
						</tr>
						{/section}
					</div>
				</table>
			</div>
			<input type="hidden" name="per_page" value="1" />
			<input type="hidden" name="current_page" value="1" />
			<div id="pagerStockHug" class="easyui-pagination"></div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.freeze-table {
        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
	}
	.freeze-table .table{
		min-width:1600px;
		max-width:1800px;
	}
	
</style>
<script type="text/javascript">
	$(function(){
		$('#tableStockHug').freezeTable({
			'columnNum': {/literal}{$columnNum}{literal},
			'scrollable': false,
			'columnKeep': false,
			'scrollBar': false,
		});
	});
</script>
{/literal}