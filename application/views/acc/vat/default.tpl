<!-- Style -->
<link rel="stylesheet" type="text/css" href="{$URL_JS}/easyui/themes/gray/easyui.css" media="all" />
<!-- Script -->
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between py-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1">VAT Đã Xuất</h4>
			<div class="text-muted">Hiện có <strong class="total text-main">0</strong> VAT đã xuất</div>
		</div>
		<div class="p__right mt-3 mt-lg-0">
			<div class="d-flex gap-2 algin-items-center">
				<button type="button" data-toggle="ripple" onclick="$Core.vat.open_VAT(this, event)" fund_id="0" class="btn btn-outline-primary">{$clsISO->makeIcon('bx-plus','Thêm mới')}</button>
				<!-- <button type="button" data-toggle="ripple" onclick="$Core.vat.crawl(this, event)" fund_id="0" class="btn btn-outline-default">{$clsISO->makeIcon('bx-upload','Crawl')}</button> -->
				<div class="btn-group">
					{assign var = gId value = $clsISO->getUniqid()}
					<button id="{$gId}" data-toggle="ripple" type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-search"></i></button>
					<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-menu-end w-px-350" 
						data-popper-placement="top-end">
						<div class="p-3">
							<div class="form-group mb-2">
								<label class="form-label">Tìm kiếm theo từ khóa</label>
								<input type="text" placeholder="Nhập từ khóa tìm kiếm" name="keyword" 
									class="form-control search_field_VAT search_keyword_VAT" onkeyup="$Core.vat.do_enter_search_VAT(this, event)" />
							</div>
							<div class="form-group form-row mb-2">
								<div class="col-6">
									<label class="form-label">Tình trạng</label>
									<select class="form-control search_field_VAT form-select" name="status_id" onchange="$Core.vat.do_search_VAT(this, event)">
										<option value="0">Tình trạng</option>
										{$clsProperty->getSelectByProperty('VAT_STATUS',0)}
									</select>
								</div>
								<div class="col-6">
									<label class="form-label">Loại VAT</label>
									<select class="form-control search_field_VAT form-select" name="vat_type" onchange="$Core.vat.do_search_VAT(this, event)">
										<option value="0">Loại VAT</option>
										{$clsProperty->getSelectByProperty('VAT_TYPE',0)}
									</select>
								</div>
							</div>
							<hr class="my-2" />
							<div class="form-group">
								<button gid="{$gId}" type="button" onclick="$Core.vat.toggle_search_VAT(this,event)" 
									class="btn btn-outline-primary">Tìm kiếm</button>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="bg-grayter rounded-2 p-2 mb-2">
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
			<div id="tableVAT" class="freeze-table dragscroll text-nowrap">
				<table class="table table-bordered mb-0" width="100%">
					<thead><tr>
						{if $deviceType ne 'phone'}
						<th width="3%" rowspan="2" class="align-center text-center bg-lighter">No.</th>
						{/if}
						<th rowspan="2" class="align-center bg-lighter">Ký hiệu</th>
						<th rowspan="2" class="align-center bg-lighter">Số HĐ</th>
						<th rowspan="2" class="align-center bg-lighter">Ngày HĐ</th>
						<th rowspan="2" class="align-center bg-lighter">Đối tác</th>
						<th rowspan="2" class="align-center bg-lighter">Mã căn</th>
						<th rowspan="2" class="align-center bg-lighter">Sale bán</th>
						<th rowspan="2" class="align-center text-right bg-lighter">Số tiền</th>
						<th colspan="2" class="align-center bg-lighter bg-lighter">Tình trạng thanh toán</th>
						<th rowspan="2" class="align-center bg-lighter text-right">Số tiền<br /> chưa TT</th>
						<th rowspan="2" class="bg-lighter">Loại VAT</th>
						<th rowspan="2" class="bg-lighter">Tình trạng</th>
						<th rowspan="2" class="align-center bg-lighter" width="40px"></th>
					</tr>
						<tr>
							<th width="6%" class="text-right align-center bg-lighter">Tạm ứng</th>
							<th width="6%" class="text-right align-center bg-lighter">Tất toán</th>
						</tr>
					</thead>
					<tbody class="holder_VAT">
						{section name=i loop=$list_preloaders max=30}
						<tr>
							{if $deviceType ne 'phone'}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							{/if}
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
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<input type="hidden" name="per_page" value="30" />
			<input type="hidden" name="current_page" value="1" />
			<div id="pager_VAT" class="easyui-pagination"></div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
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
</style>
<script type="text/javascript">
	$(function(){
		$('#tableVAT').freezeTable({
			'columnNum': {/literal}{$columnNum}{literal},
			'scrollable': true,
			'columnKeep': false,
		});
	});
</script>
{/literal}

