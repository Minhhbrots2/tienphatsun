{$scriptJs}
<div class="container-xxl flex-grow-1 pt-0 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-3">
		<div class="p__left">
			{assign var = Overtime_Notes value = $clsConfiguration->getValue('SiteMsg_Overtime_Notes')}
			<h4 class="fw-bold mb-1">Đăng ký tăng ca{if !empty($Overtime_Notes)} <a class="text-body" data-toggle="webui-popover"
			 data-title="Hướng dẫn đăng ký tăng ca" title="Hướng dẫn đăng ký tăng ca" data-trigger="click" data-url="/index.php?mod={$mod}&act=load_config&setting=Overtime_Notes" href="javascript:void(0);"><i class='bx bx-help-circle'></i></a>{/if}</h4>
			<p class="text-muted mb-0">Hiện có <strong class="total_record text-main">0</strong> đăng ký tăng ca</p>
		</div>
		<div class="p__right d-flex gap-1">
			<button type="button" onClick="$Core.overtime.open(this, event)" overtime_id="0" class="d-flex align-items-center btn btn-outline-primary">Thêm mới</button>
			<div class="btn-group dropdown">
				<button type="button" class="btn btn-icon bg-white hide-arrow btn-outline-default dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">{$clsISO->makeIcon('bx-search')}</button>
				<div class="dropdown-menu mega-dropdown-menu  dropdown-menu-end w-px-350" data-popper-placement="top-end">
					<div class="p-3">
						{if $permiss_view_all_overtime eq '1'}
						<div class="form-group mb-2">
							<label class="form-label mb-1">Nhân viên</label>
							<select data-field="staff_id" onchange="$Core.overtime.do_search(this, event)" class="search_field form-control form-select">
								<option value="0">Lựa chọn nhân viên</option>
								{if !empty($list_staffs)}
									{foreach from=$list_staffs item = _oStaff}
									<option value="{$_oStaff.profile_id}">{$clsProfile->getFullName($_oStaff.profile_id, $_oStaff)}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						{/if}
						<div class="form-group form-row mb-3">
							<div class="col-6">
								<label class="form-label mb-1">Từ ngày</label>
								<input data-field="start_date" onchange="$Core.overtime.do_search(this, event)" type="date" class="search_field form-control" />
							</div>
							<div class="col-6">
								<label class="form-label mb-1">Tới ngày</label>
								<input data-field="end_date" onchange="$Core.overtime.do_search(this, event)" type="date" class="search_field form-control" />
							</div>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div id="tableOvertime" class="freeze-table dragscroll text-nowrap">
				<table class="table table-iloocal table-{$deviceType} table-bordered">
					<thead><tr>
						<th class="align-item">Mã đăng ký</th>
						{if $permiss_view_all_overtime eq '1'}{/if}
						<th class="align-item">Tên nhân viên</th>
						<th class="align-item">Phòng ban</th>
						<th class="align-item">Ngày tăng ca</th>
						<th class="align-item">Bắt đầu</th>
						<th class="align-item">Kết thúc</th>
						<th class="align-item">Lý do tăng ca</th>
						<th class="align-item">Tình trạng</th>
						<th class="align-item">Ngày tạo</th>
						<th class="align-item" width="45px">....</th>
					</tr></thead>
					{section name=i loop=$list_preloaders max=20}
					<tr>
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
						{if $permiss_view_all_overtime eq '1'}{/if}
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
						<td><div class="animate-bg w-100 h-px-20 rounded-3"></div></td>
					</tr>
					{/section}
				</table>
			</div>
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
		min-width:100vh;
		max-width:100%;
	}
	tr.tr_confirmed td{
		background:#d2ffd4;
	}
</style>
<script type="text/javascript">
	$(function(){
		$Core.overtime.list({});
		$('#tableOvertime').freezeTable({
			'columnNum': {/literal}{$columnNum}{literal},
			'scrollable': false,
			'columnKeep': false,
			'scrollBar': true,
		});
	});
</script>
{/literal}
