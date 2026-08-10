<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		
		<div class="col-12 col-lg-9 col-xxl-9 offset-xxl-1 mb-2 mb-lg-0">
			<div class="d-flex justify-content-between align-items-center mb-2">
				<div class="p__left">
					<h4 class="fw-bold mb-1"><span>Báo cáo tiếp khách</span></h4>
					<p class="text-muted mb-0">Phòng ban kinh doanh {$smarty.const.BRAND_NAME}</p>
				</div>
				<div class="p__right">
					{if $deviceType ne 'phone'}
					<div class="d-flex form-inline">
						<div class="mr-2">
							<input type="month" class="form-control search_field" data-field="month" 
								onChange="$Core.report.do_target_search(this, event)" name="month" value="{$current_month}" />
						</div>
						{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO') || $clsISO->checkPermissionGroup('BUSINESS_AREA')}
						<div class="form-group w-px-150">
							<select class="form-control iso-select2 search_field" data-width="100%" name="department_id" 
								data-field="department_id" onchange="$Core.report.do_target_search(this, event)">
								<option value="0">Tất cả</option>
								{if $clsISO->checkPermissionGroup("BUSINESS_AREA")}
									{$clsProperty->getListOption("_DEPARTMENT",0,$oneProfile.department_id)}
								{else}
									{$clsProperty->getListOption("_DEPARTMENT",0,$smarty.const._DEPARTMENT_SALE_ID)}
								{/if}
							</select>
						</div>
						{/if}
					</div>
					{/if}
				</div>
			</div>
			<div class="card">
				{if $deviceType eq 'phone'}
				<div class="card-header position-relative border-bottom mb-2">
					<div class="d-flex justify-content-center">
						<div class="w-px-200 mr-2">
							<input type="month" class="form-control search_field" data-field="month" 
								onChange="$Core.report.do_target_search(this, event)" name="month" value="{$current_month}" />
						</div>
						{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO') || $clsISO->checkPermissionGroup('BUSINESS_AREA')}
						<select class="form-control iso-select2 search_field" data-width="100%" name="department_id" 
							data-field="department_id" onchange="$Core.report.do_target_search(this, event)">
							<option value="0">Tất cả</option>
							{if $clsISO->checkPermissionGroup("BUSINESS_AREA")}
								{$clsProperty->getListOption("_DEPARTMENT",0,$oneProfile.department_id)}
							{else}
								{$clsProperty->getListOption("_DEPARTMENT",0,$smarty.const._DEPARTMENT_SALE_ID)}
							{/if}
						</select>
						{/if}
					</div>
				</div>
				{/if}
				<div id="holder_report_share" class="card-body{if $deviceType eq 'phone'} p-2{/if}">
					<div class="table-container no-shadow overflow-x-auto text-nowrap mb-2">		
						<table class="table table-iloocal table-computer table-bordered" width="100%" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th width="60" class="align-center text-center bg-lighter" rowspan="2">STT</th>
									<th class="align-center bg-lighter" width="30%" rowspan="2">Họ và tên</th>
									<th class="align-center text-center bg-lighter" colspan="2">Chỉ tiêu</th>
									<th class="align-center text-center bg-lighter" colspan="2">Đạt được</th>
								</tr>
								<tr>
									<th class="align-center text-center bg-lighter">Số GD</th>
									<th class="align-center text-center bg-lighter">Doanh số</th>
									<th class="align-center text-center bg-lighter">Số GD</th>
									<th class="align-center text-center bg-lighter">Doanh số</th>
								</tr>
							</thead>
							<tbody>
								{section loop=20 name=i start=1 step=1}
								<tr>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
									<td class="text-left"><div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$Core.report.load_report_target_sales({});
	});
</script>
{/literal}