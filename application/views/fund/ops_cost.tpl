<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between mb-2">
		<div class="p__left">
			<div class="d-flex align-items-center gap-2">
				<a href="{$clsISO->getLink('chart_operation_fee')}" class="back" title="Quay lại">
					<img src="{$smarty.const.ICON_BACK}" />
				</a>
				<span class="text-upper fw-bold">Danh sách chi vận hành</span>
			</div>
			<div class="text-muted">Danh sách tổng hợp chi vận hành</div>
		</div>
		<div class="p__right mt-3 mt-lg-0 {if $deviceType eq 'phone'}flex-fill{/if}">
			<form class="w-100" action="" method="POST">
				<div class="d-flex gap-2 algin-items-center">
					<div class="search-block w-full d-flex align-item-center">
						<div class="input-group">
							<div class="input-group input-group-merge w-px-150">
								<span class="input-group-text"><i class="bx bx-search"></i></span>
								<input type="text" name="keyword" data-field="keyword" onchange="$Core.ops_cost.do_search()" class="form-control search_field no-radius-right" placeholder="Nhập từ khoá">
							</div>
						</div>
						<div class="dropdown">
							<button type="button" class="btn btn-icon btn-default no-border-left dropdown-toggle hide-arrow border no-radius-left" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>				
							<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-menu-end w-px-300" 
								data-popper-placement="top-end">
								<div class="p-3">
									<div class="form-group mb-2">
										<label class="form-label">Lựa chọn khoảng thời gian</label>
										<div class="input-group-date">
											<input type="text" class="form-control search_field isodaterangepicker" 
											onChange="$Core.ops_cost.do_search(this, event)" data-field="date_range" />
										</div>
									</div>
									<hr class="my-2" />
									<div class="form-group">
										<button gid="{$gId}" type="button" onclick="$Core.ops_cost.toggle_search(this, event)" class="btn btn-outline-primary">Tìm kiếm</button>
									</div>
								</div>
							</div> 
						</div>
					</div>
					<button data-toggle="ripple" type="button" class="btn btn-icon btn-outline-default no-wrap" onClick="$Core.ops_cost.do_search(this,event)">
						<i class="bx bx-search"></i> 
					</button>
				</div>
			</form>
		</div>
	</div>
	<div class="card no-shadow">
		<div class="card-body">
			<div class="table-container overflow-x-auto text-nowrap no-shadow table-container2">
				<table class="table dragable" cellpadding="0" cellspacing="0" 
					   style="width:{if $deviceType eq 'phone'}calc(100% + 100px){else}100%{/if}">
					<thead><tr>
						<th class="align-center bg-lighter h-px-35" width="120px">Ngày chi</th>
						<th class="align-center bg-lighter text-left h-px-35">Mã chi phí</th>
						<th class="align-center bg-lighter bg-lighter h-px-35" width="200px">Nội dung chi</th>
						<th class="align-center bg-lighter bg-lighter h-px-35">Loại chi phí</th>
						<th class="align-center bg-lighter h-px-35" width="10%">Phòng ban</th>
						<th class="align-center bg-lighter text-left h-px-35">Người đề xuất</th>
						<th class="align-center bg-lighter text-left h-px-35">Người duyệt</th>
						<th class="align-center bg-lighter text-right h-px-35" width="10%">Số tiền</th>
						<th class="align-center bg-lighter h-px-35" width="10%">PT. thanh toán</th>
						<th class="align-center bg-lighter h-px-35" width="10%">TK Nhận</th>
						<th class="align-center bg-lighter text-center h-px-35" width="10%">Tình trạng</th>
						<!-- <th class="align-center bg-lighter h-px-35" width="10%">Ghi chú</th> -->
					</tr>
					</thead>
					<tbody class="holder_ops_cost">
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
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<!-- <td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td> -->
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div id="pager_ops_cost"></div>
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
<script type="text/javascript">
	$(function(){
		$('.isodaterangepicker').daterangepicker({
			timePicker: false,
			"drops": "auto",
			"autoApply": true,
			alwaysShowCalendars: true,
			startDate: moment().startOf('month'),
			endDate: moment().endOf('month'),
			ranges: {
			   'Hôm nay': [moment(), moment()],
			   'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   '7 ngày qua': [moment().subtract(6, 'days'), moment()],
			   '30 ngày qua': [moment().subtract(29, 'days'), moment()],
			   'Thánh này': [moment().startOf('month'), moment().endOf('month')],
			   'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			opens: (deviceType=='phone'?'center':'left'),
			locale: {format: 'DD/MM/YYYY'}
		});
		$('.freeze-table').freezeTable({
			'columnNum': 2,
			'scrollable': true,
			'columnKeep': false,
		});
	});
</script>
{/literal}

