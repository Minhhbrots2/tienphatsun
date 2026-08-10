<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Kết quả bán hàng</span></h4>
			<p class="text-muted mb-0">Tổng hợp kết quả kinh doanh từng nhân viên</p>
		</div>
		<div class="p__right">
			<div class="search-block w-full d-flex align-item-center">
				<div class="input-group">
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-search"></i></span>
						<input type="text" name="keyword" value="{$keyword}" class="form-control search_field no-radius-right" 
						placeholder="Nhập từ khoá & nhấn Enter..." data-field="keyword" />
					</div>
				</div>
				<div class="btn-group dropdown">
					<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow no-radius-left no-border-left" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
					<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="top-end">
						<div class="p-3">
							<div class="form-group mb-2">
								<label class="form-label mb-1">Chọn năm</label>
								<select class="iso-select2 search_field" data-width="100%" data-field="year" 
									placeholder="Nhân viên" name="year">
									{foreach from=$list_years item = _year}
									<option{if $curr_year eq $_year} selected{/if} value="{$_year}">Năm {$_year}</option>
									{/foreach}
								</select>
							</div>
							<div class="form-group mb-3">
								<label class="form-label mb-1">Phòng ban</label>
								<select class="iso-select2 search_field" data-width="100%" data-field="department_id" 
									placeholder="Nhân viên" name="department_id">
									{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$smarty.const._DEPARTMENT_SALE_ID,'Phòng ban')}
								</select>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body{if $deviceType eq 'phone'} p-2{/if}">
			<div class="iKuJnjIFyr template_3">
				<div id="holder_report_sale_month" class="hJsiGEcCOJ overflow-x-auto template_3">
					<table class="table table-campaign template_3">
						<thead><tr>
							<th class="p_header text-center text-upper" colspan="16">
								<div class="mb-2">
									<img src="{$clsConfiguration->getValue('LogoWhite')}" width="{$clsConfiguration->getImageWidth('LogoWhite')}" height="{$clsConfiguration->getImageHeight('LogoWhite')}" alt="{$header_configs.CompanyName}" />
								</div>
								<strong>Bảng tổng hợp cá nhân {$curr_year}</strong><br>
								(01/01/{$curr_year}-31/12/{$curr_year})
							</th>
						</tr><tr>
							<th width="3%" class="p_head text-center">STT</th>
							<th class="p_head text-left">Họ và tên</th>
							{foreach from=$list_months item = _oMonth}
							<th width="6%" class="p_head text-center">T{$_oMonth}</th>
							{/foreach}
							<th class="p_head text-center">Tổng</th>
							<th class="p_head text-center d-none">H.suất</th>
						</tr></thead>
						{section name=i loop=$list_preloaders max=30}
						<tr class="p_row">
							<td class="p_cell text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
							<td class="p_cell text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
							{foreach from=$list_months item = _oMonth}
							<td class="p_cell text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
							{/foreach}
							<td class="p_cell text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
							<td class="p_cell text-center d-none">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
						</tr>
						{/section}
						<tfoot><tr>
							<th colspan="2" class="p_head text-center">Tổng cộng</th>
							{foreach from=$list_months item = _oMonth}
							<th width="6%" class="p_head text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</th>
							{/foreach}
							<th class="p_head text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</th>
							<th class="p_head text-center d-none">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</th>
						</tr></tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	@media screen and (max-width:768px){
		.card .table{ min-width: 1100px; }
	}
</style>
<script type="text/javascript">
	$(function(){
		setTimeout(() => {
			$Core.report.load_sale_month({});
		}, 500);
		$('.search_field').on('change', function(){
			$Core.report.load_sale_month({});
		});
	});
</script>
{/literal}