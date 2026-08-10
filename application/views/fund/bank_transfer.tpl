<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between py-1 mb-2">
		<div class="vRHjwnrkGa d-flex">
			<a href="{$clsISO->getLink('fund')}" class="back mr-2 goToPage" title="Quay lại">
				<img src="{$smarty.const.ICON_BACK}" />
			</a>
			<div class="ysXwpeiJqL">
				<h4 class="fw-bold mb-1">Chuyển Quỹ</h4>
				<small class="text-muted fs-13">
					Danh sách <strong class="total_record text-danger">0</strong> chuyển quỹ thu chi nội bộ
				</small>
			</div>
		</div>
		<div class="mUWwbDvWBi mt-2 mt-lg-0">
			<div class="d-flex gap-1 align-items-center"><div class="search d-flex gap-1">
				<div class="input-group input-group-merge">
					<span class="input-group-text"><i class="bx bx-search"></i></span>
					<input type="text" onChange="$Core.fund.do_search_bank_transfer(this, event)" data-field="keyword" 
					class="form-control search_field" placeholder="Tìm kiếm..." />
				</div>
				<div class="btn-group">
					{assign var = gId value = $clsISO->getUniqid()}
					<button id="{$gId}" data-toggle="ripple" type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-haspopup="true" aria-expanded="true">
						<i class='bx bx-filter-alt'></i>
					</button>
					<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-menu-end w-px-300" 
						data-popper-placement="top-end">
						<div class="p-3">
							<div class="form-group mb-2">
								<label class="form-label">Loại phiếu</label>
								{assign var = uid value = $clsISO->getUniqid()}
								<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
									<input onchange="$Core.fund.do_search_bank_transfer(this, event)" type="radio" class="btn-check js__fund-filter-list" name="action" id="ALL_{$uid}" value="_all" autocomplete="off" checked>
									<label data-toggle="ripple" class="btn text-nowrap btn-outline-default" for="ALL_{$uid}">All</label>
									<input onchange="$Core.fund.do_search_bank_transfer(this, event)" type="radio" class="btn-check js__fund-filter-list" name="action" id="IN_{$uid}" value="_in" autocomplete="off">
									<label data-toggle="ripple" class="btn text-nowrap btn-outline-default" for="IN_{$uid}">Chuyển vào</label>
									<input onchange="$Core.fund.do_search_bank_transfer(this, event)" type="radio" class="btn-check js__fund-filter-list" name="action" id="OUT_{$uid}" value="_out" autocomplete="off">
									<label data-toggle="ripple" class="btn text-nowrap btn-outline-default" for="OUT_{$uid}">Chuyển ra</label>
								</div>
							</div>
							<div class="form-group mb-2">
								<label class="form-label">Tài khoản</label>
								<select class="form-control form-select search_field" data-field="bank_account_id" onchange="$Core.fund.do_search_bank_transfer(this, event)">
									<option value="0">Chọn tài khoản</option>
									{foreach from=$list_bank_accounts item = _oBT}
									<option value="{$_oBT.property_id}">{$_oBT.title}</option>
									{/foreach}
								</select>
							</div>
							<div class="form-group mb-2">
								<label class="form-label">Lựa chọn khoảng thời gian</label>
								<div class="input-group-date">
									<input type="text" class="form-control search_field isodaterangepicker" 
									onChange="$Core.fund.do_search_bank_transfer(this, event)" data-field="date_range" />
								</div>
							</div>
							<hr class="my-2" />
							<div class="form-group">
								<button gid="{$gId}" type="button" onclick="$Core.fund.toggle_search(this, event)" class="btn btn-outline-primary">Tìm kiếm</button>
							</div>
						</div>
					</div> 
				</div></div>
				{if $clsISO->checkPermission('create_fund') eq '1'}
				<button onclick="$Core.fund.open_bank_transfer(this, event)" bank_transfer_id="0" class="btn text-nowrap btn-primary">{$clsISO->makeIcon('bx-plus','Thêm mới')}</button>
				{/if}
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="card">
		<div class="card-body">
			<div id="tableIssue" class="freeze-table table-container overflow-x-auto text-nowrap">
				<table cellpadding="0" cellspacing="0" class="table table-bordered mb-0" width="100%">
					<thead><tr>
						<th width="9%" class="align-center h-px-40 bg-lighter">Mã phiếu</th>
						<th width="140px" class="align-center h-px-40 bg-lighter">Ngày chuyển</th>
						<th width="140px" class="align-center h-px-40 bg-lighter text-center">N.Hạch toán</th>
						<th class="align-center text-left bg-lighter h-px-40">Diễn giải</th>
						<th width="15%" class="align-center bg-lighter h-px-40 text-left">TK. nguồn</th>
						<th width="15%" class="align-center bg-lighter h-px-40 text-left">TK. đích</th>
						<th width="12%" class="align-center bg-lighter h-px-40">Số tiền</th>
						<th width="40px" class="align-center bg-lighter h-px-40"></th>
					</tr></thead>
					<tbody class="holder_bank_transfer">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div id="pager_bank_transfer"></div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.input-group-date:before{top:8px;}
	.input-group-date > .isodaterangepicker{line-height: 1.83;}
</style>
<script type="text/javascript">
	$(function(){
		$('.isodaterangepicker').daterangepicker({
			timePicker: false,
			"drops": "auto",
			"autoApply": true,
			alwaysShowCalendars: true,
			startDate: moment().subtract(1, 'year'),
			endDate: moment(),
			ranges: {'Hôm nay': [moment(), moment()],
			   'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   '7 ngày qua': [moment().subtract(6, 'days'), moment()],
			   '30 ngày qua': [moment().subtract(29, 'days'), moment()],
			   'Thánh này': [moment().startOf('month'), moment().endOf('month')],
			   'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}, opens: (deviceType=='phone'?'center':'left'),
			locale: {format: 'DD/MM/YYYY'}
		});
		
		$Core.fund.list_bank_transfer({}, false);
	});
</script>
{/literal}