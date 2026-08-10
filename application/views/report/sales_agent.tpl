<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-md-8 offset-md-2">
			<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
				<div class="dVgkOVaEyp mb-2 mb-lg-0">
					<h4 class="fw-bold mb-1"><span>Kết quả đại lý bán hàng</span></h4>
					<p class="text-muted mb-0">Tổng hợp kết quả kinh doanh dại lý</p>
				</div>
				<div class="bYyoClJPFA xs:w-100">
					<div class="input-group w-px-300 xs:w-100">
						<select onChange="$Core.report._do(this, event)" data-field="sold_type" 
							class="form-control form-select search_field">
							<option selected value="partner_development">{$smarty.const.BRAND_NAME} bán</option>
							<option value="cross">Đại lý bán</option>
						</select>
						<select id="slb_Month" onChange="$Core.report._do(this, event)" data-field="month" 
							class="form-control form-select search_field">
							<option value="0">Tháng</option>
							{foreach name=i from=$list_months item = _oMonth}
							<option value="{$_oMonth}">Tháng {$_oMonth}</option>
							{/foreach}
						</select>
						<select onChange="$Core.report._do(this, event)" 
							data-field="year" class="form-control form-select search_field">
							<option value="0">Năm</option>
							{foreach name=i from=$list_years item = _oYear}
							<option{if $current_year eq $_oYear} selected{/if} value="{$_oYear}">Năm {$_oYear}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
			<div class="card no-shadow">
				<div class="card-body">
					<div class="table-container{if $deviceType eq 'phone'} overflow-x-auto text-nowrap{/if} no-shadow">
						<table cellspacing="0" cellpadding="0" width="100%" class="table table-bordered">
							<thead><tr>
								{if $deviceType ne 'phone'}
								<th width="5%" class="align-center text-center h-px-35 bg-lighter">No</th>
								<th class="align-center h-px-35 bg-lighter">Tên đại lý</th>
								<th class="align-center w-px-100 text-center h-px-35 bg-lighter">Số GD</th>
								<th class="align-center w-px-150 text-right h-px-35 bg-lighter">Doanh số</th>
								<th class="align-center w-px-150 text-right h-px-3 bg-lighter">Ngày cọc mới</th>
								{else}
								<th class="align-center h-px-35 bg-lighter">Tên đại lý</th>
								<th class="align-center w-px-75 text-center h-px-35 bg-lighter">Số GD</th>
								<th class="align-center w-px-125 text-right h-px-35 bg-lighter">Doanh số</th>
								<th class="align-center w-px-150 text-right h-px-3 bg-lighter">Ngày cọc mới</th>
								{/if}
								<th class="align-center text-center h-px-35 w-px-100 bg-lighter">
									<i class='bx bx-link-external text-muted'></i>
								</th>
							</tr></thead>
							<tbody class="holder_sales_agent">
								{section name=i loop=$list_preloaders}
								<tr>
									{if $deviceType ne 'phone'}
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									{/if}
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					<div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){ 
		$Core.report.load_sales_agent({}); 
	});
</script>
{/literal}