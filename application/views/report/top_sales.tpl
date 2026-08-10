<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-lg-10 offset-lg-1 col-xxxl-8 offset-xxxl-2">
			<div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
				<div class="oOGXbGhDZt">
					<h4 class="fw-bold mb-0"><span>Báo cáo Sale chưa có GD</span></h4>
					<p class="text-muted mb-0">Tổng hợp kết quả bán hàng {$smarty.const.BRAND_NAME}</p>
				</div>
				<div class="xaQwJlqAyc">
					<div class="input-group">
						<select data-field="department_id" call_from="top_sales" class="search_field form-select" 
							onChange="$Core.report.do_change(this, event)">
							{$clsProperty->getSelectSingleProperty('_DEPARTMENT',$smarty.const._DEPARTMENT_SALE_ID,0,'Phòng kinh doanh')}
						</select>
					</div>
				</div>
			</div>
			<div class="holder_report_top_sales">
				{foreach from=$list_time_points name=i item = _OI}
				<div class="card mb-2">
					<div class="card-header">
						<h3 class="card-title fs-5 mb-0">{$_OI.title}</h3>
					</div>
					<div class="card-body">
						<div class="table-container no-shadow overflow-x-auto text-nowrap">
							<table class="table table-bordered" cellpadding="0" cellspacing="0">
								<thead><tr>
									{if $deviceType ne 'phone'}
									<th width="5%" class="align-center h-px-35 bg-lighter text-center">No.</th>
									{/if}
									<th class="align-center bg-lighter h-px-35">Họ và tên</th>
									<th class="align-center bg-lighter h-px-35">Phòng ban</th>
									<th class="align-center bg-lighter h-px-35" width="30%">Ngày vào</th>
								</tr></thead>
								{section name=i loop=$list_preloaders max=10}
								<tr>
									{if $deviceType ne 'phone'}<td class="text-center">
										<div class="animate-bg w-100 h-px-15 rounded-2"></div>
									</td>{/if}
									<td class="align-center">
										<div class="animate-bg w-100 h-px-15 rounded-2"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg w-100 h-px-15 rounded-2"></div>
									</td>
									<td class="align-center">
										<div class="animate-bg w-100 h-px-15 rounded-2"></div>
									</td>
								</tr>
								{/section}
							</table>
						</div>
					</div>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
</div>