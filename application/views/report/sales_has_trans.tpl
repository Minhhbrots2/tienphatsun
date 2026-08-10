<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-lg-10 offset-lg-1 col-xxxl-8 offset-xxxl-2">
			<div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
				<div class="gonjJyjZjd mb-2 mb-lg-0">
					<h4 class="fw-bold mb-0">
						<span>Báo cáo Sales có GD</span>
					</h4>
					<p class="text-muted mb-0">Tổng hợp kết quả bán hàng {$smarty.const.BRAND_NAME}</p>
				</div>
				<div class="pjgNnfxUhT">
					<div class="input-group">
						<select data-field="billing_type" call_from="sales_has_trans" class="search_field form-select" 
							onChange="$Core.report.do_change(this, event)">
							{$clsISO->getSelectByPropertyTypeTitle('_BILLING_TYPE',0,'Loại hình')}
						</select>
						<select data-field="department_id" call_from="sales_has_trans" class="search_field form-select" 
							onChange="$Core.report.do_change(this, event)">
							{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$department_id,'Phòng ban')}
						</select>
						<select data-field="date_type" name="date_type" call_from="sales_has_trans" class="search_field form-select" 
							onChange="$Core.report.do_change(this, event)">
							<option value="_month">Theo Tháng</option>
							<option value="_quater">Theo Quý</option>
							<option value="_half_year">Theo 1/2 năm</option>
						</select>
						<select data-field="month" name="month" call_from="sales_has_trans" onChange="$Core.report.do_change(this, event)" class="form-control search_field form-select slb_Month" >
							<option>Chọn Tháng</option>
							{foreach name=i from=$list_months item = _oT}
							<option{if $current_month eq $_oT} selected{/if} value="{$_oT}">Tháng {$_oT}</option>
							{/foreach}
						</select>
						<select data-field="year" name="year" call_from="sales_has_trans" class="form-control search_field form-select slb_Year" onChange="$Core.report.do_change(this, event)">
							<option>Chọn năm</option>
							{foreach name=i from=$list_years item = _oY}
							<option{if $current_year eq $_oY} selected{/if} value="{$_oY}">Năm {$_oY}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
			<div class="card mb-3">
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
								<th class="align-center bg-lighter h-px-35 text-center">Doanh số</th>
								<th class="align-center bg-lighter h-px-35 text-center">Số lượng</th>
								<th class="align-center bg-lighter h-px-35 text-center">Doanh số F1</th>
								<th class="align-center bg-lighter h-px-35 text-center">Quỹ F1</th>
								<th class="align-center bg-lighter h-px-35 text-center">Đã ký</th>
								<th class="align-center bg-lighter h-px-35 text-center">Đã hủy</th>
							</tr></thead>
							<tbody class="holder_reports_sales_has_trans">
								{section name = i loop = $list_preloaders max=30}
								<tr>
									{if $deviceType ne 'phone'}
									<td class="text-center">{$smarty.section.i.iteration}</td>{/if}
									<td class="align-center"><div class="animate-bg w-100 rounded-3 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg w-100 rounded-3 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg w-100 rounded-3 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg w-100 rounded-3 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg w-100 rounded-3 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg w-100 rounded-3 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg w-100 rounded-3 h-px-15"></div></td>
									<td class="align-center"><div class="animate-bg w-100 rounded-3 h-px-15"></div></td>
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