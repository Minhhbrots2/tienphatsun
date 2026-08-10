<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between py-2">
		<div class="p__left">
			<h4 class="fw-bold mb-0">Thu chi nội bộ</h4>
			<div class="text-muted">Danh sách phiếu thu/chi nội bộ</div>
		</div>
		{assign var = ref_Id value = $clsISO->getUniqid()}
		<form class="frmIssue d-none" enctype="multipart/form-data">
			<input type="hidden" name="hid_upload" value="hid_upload" />
			<input type="file" id="upload_file_{$ref_Id}" accept=".xlsx,.xls" name="attachment" onChange="$Core.fund.open_import(this, event)" />
		</form>
		<div class="p__right mt-3 mt-lg-0 {if $deviceType eq 'phone'}flex-fill{/if}">
			<div class="d-flex gap-2 algin-items-center">
				{if $permiss_add eq '1'}
				<div class="btn-group dropdown {if $deviceType eq 'phone'}flex-fill{/if}">
					<a href="javascript:;" data-toggle="ripple" onclick="$Core.fund.open(this, event)" fund_id="0" gr="THUCTHU" class="btn btn-outline-default text-danger{if $deviceType eq 'phone'} btn-sm text-center d-flex align-items-center{/if}">{$clsISO->makeIcon('bx-receipt me-1','Phiếu thu')}</a>
					<a href="javascript:;" data-toggle="ripple" onclick="$Core.fund.open(this, event)" fund_id="0" gr="THUCCHI" class="btn btn-outline-default text-primary{if $deviceType eq 'phone'} btn-sm text-center d-flex align-items-center{/if}">{$clsISO->makeIcon('bx-receipt me-1','Phiếu chi')}</a>
					{if $deviceType ne 'phone'}<a href="javascript:;" data-toggle="ripple" onclick="$Core.global.fund.open_cash(this, event)" class="btn btn-outline-default text-warning{if $deviceType eq 'phone'} btn-sm text-center d-flex align-items-center{/if}">{$clsISO->makeIcon('bx-wallet me-1','Tiền mặt')}</a>{/if}
					<button class="btn dropdown-toggle  btn-outline-danger" data-bs-toggle="dropdown">Thêm</button>
					<div class="dropdown-menu">
						<a href="javascript:void(0)" toId="{$ref_Id}" onClick="$Core.fund.select_file(this, event)" class="dropdown-item">{$clsISO->makeIcon('bx-cloud-upload me-1','Import Excel')}</a>
						<a href="javascript:void(0)" onClick="$Core.fund.export(this, event)" class="dropdown-item">{$clsISO->makeIcon('bx-cloud-download me-1','Xuất Excel')}</a>
						<hr class="my-2" />
						<a href="/fund/transfer.html" class="dropdown-item">{$clsISO->makeIcon('bx-transfer me-1','Chuyển quỹ')}</a>
						<a href="javascript:void(0);" onClick="$Core.fund.open_manage(this, event)" class="dropdown-item" title="TK Quỹ/Ngân hàng">{$clsISO->makeIcon('bx-wallet-alt me-1','TK Quỹ/Ngân hàng')}</a>
						{if $clsISO->checkPermission('setting_fund')}
						<hr class="my-2" />
						<a href="javascript:void(0);" onClick="$Core.fund.open_setting(this, event)" class="dropdown-item" title="Cài đặt">{$clsISO->makeIcon('bx-cog me-1','Cài đặt')}</a>
						{/if}
					</div>
				</div>
				{/if}
				<div class="btn-group">
					{assign var = gId value = $clsISO->getUniqid()}
					<button id="{$gId}" data-toggle="ripple" type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-haspopup="true" aria-expanded="true">
						<i class="bx bx-filter-alt"></i>
					</button>
					<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-menu-end w-px-300" 
						data-popper-placement="top-end">
						<div class="p-3">
							<div class="form-group mb-2">
								<label class="form-label">Loại phiếu</label>
								{assign var = uid value = $clsISO->getUniqid()}
								<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
									<input onchange="$Core.fund.do_search(this, event)" type="radio" class="btn-check js__fund-filter-list" name="gr" id="ALL_{$uid}" value="_all" autocomplete="off" checked>
									<label data-toggle="ripple" class="btn text-nowrap btn-outline-default" for="ALL_{$uid}">All</label>
									<input onchange="$Core.fund.do_search(this, event)" type="radio" class="btn-check js__fund-filter-list" name="gr" id="THUCTHU_{$uid}" value="THUCTHU" autocomplete="off">
									<label data-toggle="ripple" class="btn text-nowrap btn-outline-default" for="THUCTHU_{$uid}">Phiếu thu</label>
									<input onchange="$Core.fund.do_search(this, event)" type="radio" class="btn-check js__fund-filter-list" name="gr" id="THUCCHI_{$uid}" value="THUCCHI" autocomplete="off">
									<label data-toggle="ripple" class="btn text-nowrap btn-outline-default" for="THUCCHI_{$uid}">Phiếu chi</label>
								</div>
							</div>
							<div class="form-group mb-2">
								<label class="form-label">Tìm kiếm theo từ khóa</label>
								<input type="text" placeholder="Nhập từ khóa tìm kiếm" class="form-control search_field" 
									data-field="keysearch" onchange="$Core.fund.do_search(this, event)" />
							</div>
							<div class="form-group mb-2">
								<label class="form-label">Lựa chọn khoảng thời gian</label>
								<div class="input-group-date">
									<input type="text" class="form-control search_field isodaterangepicker" 
									onChange="$Core.fund.do_search(this, event)" data-field="date_range" />
								</div>
							</div>
							<hr class="my-2" />
							<div class="form-group">
								<button gid="{$gId}" type="button" onclick="$Core.fund.toggle_search(this, event)" class="btn btn-outline-primary">Tìm kiếm</button>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
	<div class="my-2 w-100 overflow-x-auto">
		<div class="gap-2 autoload w-full d-flex" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_desktop_bank_accounts" data-options='{ldelim}"block":"bank"{rdelim}'>
			{section name=i loop=$list_preloaders}
			<div class="obank pointer-event text-center bg-white">
				<i class="bx bx-wallet-alt"></i>
				<div class="fs-12">Loading...</div>
			</div>
			{/section}
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="bg-grayter rounded-4 p-2 mb-2">
				<div class="briefs gap-2 d-flex flex-wrap">
					{section name=i loop=$list_preloaders max=4}
					<div class="brief-item bg-white">
						<div class="fs-16 mb-2 text-dark">
							<div class="animate-bg w-25 h-px-15 rounded-pill"></div>
						</div>
						<h3 class="fs-18 mb-0">
							<div class="animate-bg w-50 h-px-15 rounded-pill"></div>
						</h3>
					</div>
					{/section}
				</div>
			</div>
			<div id="tableIssue" class="freeze-table dragscroll text-nowrap">
				<table class="table table-bordered mb-0" width="100%">
					<thead><tr>
						<th width="120px" rowspan="2" class="align-center bg-lighter">Ngày T.Toán</th>
						<th rowspan="2" class="align-center bg-lighter text-center">Số hiệu</th>
						<th rowspan="2" class="align-center bg-lighter bg-lighter">Diễn giải</th>
						<th width="10%" rowspan="2" class="align-center bg-lighter">Tài khoản/Quỹ</th>
						<th colspan="2" class="align-center bg-lighter text-center">Số phát sinh</th>
						<th width="10%" class="bg-lighter" rowspan="2">Số tồn</th>
						<th rowspan="2" width="40px" class="align-center bg-lighter"></th>
					</tr>
					<tr>
						<!-- <th width="6%" class="text-center align-center bg-lighter">Thu</th>
						<th width="6%" class="text-center align-center bg-lighter">Chi</th> -->
						<th width="10%" class="text-center align-center bg-lighter">Thu</th>
						<th width="10%" class="text-center align-center bg-lighter">Chi</th>
					</tr></thead>
					<tbody class="holder_fund">
						{section name=i loop=$list_preloaders max=30}
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
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div id="pager_fund"></div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.input-group-date:before{
		top:8px;
	}
	.input-group-date > .isodaterangepicker{
		line-height: 1.83;
	}
	.freeze-table {
        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
	}
	.freeze-table .table{
		margin-bottom:0;
		min-width:1200px;
		max-width:16000px;
	}
	.freeze-table .table th{
		line-height:16px;
		vertical-align:middle;
	}
	@media screen and (min-width:648px){
		.freeze-table .table tr>th:nth-child(3),
		.freeze-table .trBilling td:nth-child(3){
			border-right:1px solid #DDD;
		}
	}
	@media screen and (max-width:648px){
		.freeze-table .table tr>th:nth-child(1),
		.freeze-table .trBilling td:nth-child(1){
			border-right:1px solid #DDD;
		}
	}
	.textbox{
		border-radius:4px;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		-khtml-border-radius:4px;
	}
	.ui-autocomplete{
		z-index:9 !important;
		background:var(--bs-white);
		max-height:400px;
		overflow-y:auto;
		border-radius:4px;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		-khtml-border-radius:4px;
	}
	.ui-menu-item .ui-menu-item-wrapper{
		padding: 5px 10px !important;
	}
</style>
{/literal}

