<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
		<div class="nlApYyxOPs mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1">Lịch sử tra cứu</h4>
			<p class="mb-0 text-muted">Có tổng <strong class="total_record text-danger">{$total_record}</strong> lượt tra cứu 
				{if $stock_id gt '0'} tra cứu {$clsStock->getOneField('ms_code',$stock_id)}{/if}
			</p>
		</div>
		<div class="buttons d-flex xs:w-100 gap-2 align-items-center">
			<a href="javascript:void(0)" onClick="$Core.log.open_report(this, event)" 
				class="btn flex-fill bg-white btn-outline-default">{$clsISO->makeIcon('bx-chart', 'Thống kê TOP 20')}</a>
			<div class="btn-group">
				<button type="button" class="btn btn-icon hide-arrow btn-outline-default dropdown-toggle" 
				data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
					<i class="bx bx-filter-alt"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">
					<div class="p-3">
						<div class="form-group mb-2">
							<div class="input-group mr-1 input-group-merge">
								<span class="input-group-text"><i class="bx bx-search"></i></span>
								<input type="text" class="form-control search_field" data-field="keySearch" placeholder="{$core->get_Lang('Search')}" />
							</div>
						</div>
						<div class="form-group form-row mb-2">
							<div class="col-6">
								<input type="date" class="form-control search_field" placeholder="Từ ngày" data-field="start_date">
							</div>
							<div class="col-6">
								<input type="date" class="form-control search_field" placeholder="Đến ngày" data-field="end_date">
							</div>
						</div>
						{if $is_full_permiss || $clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')}
						<div class="form-group mb-2">
							<select id="slb_Profile_Id" class="iso-selectizeSync search_field" data-field="user_id" data-width="100%" 
								data-placeholder="Nhân viên" data-allow-clear="true">
								<option value="">Nhân viên</option>
								{foreach from=$lstUser key=_staff_id item=_oI}
								<option value="{$_staff_id}"{if $user_id eq $_staff_id}selected{/if}>{$_oI.code}-{$_oI.full_name}</option>
								{/foreach}
							</select>
						</div>
						{/if}
						<div class="form-group mb-2">
							<button type="button" class="btn btn-success" onClick="$Core.log.do_search(this, event)">
								<i class="bx bx-search"></i> Tìm kiếm
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="card">
		<div class="card-body">
			<div class="table-container no-shadow overflow-x-auto text-nowrap">
				<table border="0" cellpadding="0" cellspacing="0" class="table table-striped mb-0" width="100%">
					<thead><tr>
						<th width="15%" class="align-center bg-lighter h-px-35">Họ và tên</th>
						<th width="10%" class="align-center bg-lighter h-px-35">H.Động</th>
						<th class="align-center  bg-lighter h-px-35">Nội dung</th>
						<th width="150px" class="align-center  bg-lighter h-px-35 border-end">Thời gian</th>
						<th width="150px" class="align-center  bg-lighter h-px-35 border-end">Điện thoại</th>
						<th class="align-center  bg-lighter h-px-35 w-px-50"></th>
					</tr></thead>
					<tbody class="holder_logs">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div id="pager_sale_logs"></div>
		</div>
    </div>
</div>
{literal}
<script type="text/javascript">
	$(function(){ $Core.log.load_logs({}); });
</script>
{/literal}