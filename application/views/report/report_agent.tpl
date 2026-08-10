<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="d-flex justify-content-between align-items-center mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Báo cáo quỹ đại lý </span></h4>
			<p class="text-muted mb-0">Thống kê quỹ căn các dự án</p>
		</div>
	</div>
	<div class="card mb-2">
		<h5 class="card-header">Căn bán 7 ngày qua</h5>
		<div class="holder_chart_stock_sold_7days card-body">
			<div class="p-5 h-px-250 text-muted text-center">
				<div class="p-5">Đang tải...</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-header">
			<h5 class="fw-bold mb-0">Danh sách đại lý</h5>
		</div>
		<div class="card-body">
			<div class="table-container text-nowrap overflow-x-auto no-shadow">
				<table cellpadding="0" cellspacing="0" class="table table-striped table-bordered">
					<thead><tr>
						<th rowspan="3" class="align-center bg-lighter text-center" width="5%">No.</th>
						<th rowspan="3" class="align-center bg-lighter">Đại lý</th>
						<th rowspan="3" onClick="$Core.report.do_sort(this, event)" data-field="total_all" 
							class="align-center bg-lighter sortable desc text-center">Tổng
							<span class="total_all text-primary">(0)</span>
						</th>
						<th colspan="10" class="align-center text-center bg-lighter">Cao tầng</th>
						<th colspan="3" class="align-center text-center bg-lighter">Thấp tầng</th>
					</tr>
					<tr>
						<th class="align-center text-center no-sticky bg-lighter" colspan="7">MAS 
							<span class="total_mas text-danger">(0)</span>
						</th>
						<th rowspan="2" onClick="$Core.report.do_sort(this, event)" data-field="total_mik" 
							class="align-center bg-lighter sortable text-center">MIK<br /> 
							<span class="total_mik text-danger">(0)</span>
						</th>
						<th rowspan="2" onClick="$Core.report.do_sort(this, event)" data-field="total_sun" 
							class="align-center bg-lighter sortable text-center">Sunshine<br /> 
							<span class="total_sun text-success">(0)</span>
						</th>
						<th rowspan="2" onClick="$Core.report.do_sort(this, event)" data-field="total_alu" 
							class="align-center bg-lighter sortable text-center">Alumi<br /> 
							<span class="total_alu text-info">(0)</span>
						</th>
						<th rowspan="2" onClick="$Core.report.do_sort(this, event)" data-field="total_vin" 
							class="align-center bg-lighter sortable text-center"> Vin
							<span class="total_vin text-warning">(0)</span>
						</th>
						<th rowspan="2" onClick="$Core.report.do_sort(this, event)" data-field="total_alc" 
							class="align-center bg-lighter sortable text-center"> Aluvia 
							<span class="total_alc text-warning">(0)</span>
						</th>
						<th rowspan="2" onClick="$Core.report.do_sort(this, event)" data-field="total_np" 
							class="align-center bg-lighter sortable text-center"> Noble 
							<span class="total_np text-warning">(0)</span>
						</th>
					</tr>
					<tr>
						<th onClick="$Core.report.do_sort(this, event)" data-field="total_mgc" 
							class="align-center bg-lighter no-sticky sortable text-center"> MGC
							<span class="total_mgc text-warning">(0)</span>
						</th>
						<th onClick="$Core.report.do_sort(this, event)" data-field="total_mel" 
							class="align-center bg-lighter sortable text-center"> MEL
							<span class="total_mel text-warning">(0)</span>
						</th>
						<th onClick="$Core.report.do_sort(this, event)" data-field="total_lek" 
							class="align-center bg-lighter sortable text-center"> LEK
							<span class="total_lek text-warning">(0)</span>
						</th>
						<th onClick="$Core.report.do_sort(this, event)" data-field="total_lsb" 
							class="align-center bg-lighter sortable text-center"> LSB
							<span class="total_lsb text-warning">(0)</span>
						</th>
						<th onClick="$Core.report.do_sort(this, event)" data-field="total_lop" 
							class="align-center bg-lighter sortable text-center"> LOP
							<span class="total_lop text-warning">(0)</span>
						</th>
						<th onClick="$Core.report.do_sort(this, event)" data-field="total_msq" 
							class="align-center bg-lighter sortable text-center"> MSQ
							<span class="total_msq text-warning">(0)</span>
						</th>
						<th onClick="$Core.report.do_sort(this, event)" data-field="total_tgc" 
							class="align-center border-end bg-lighter sortable text-center"> TGC
							<span class="total_tgc text-warning">(0)</span>
						</th>
						
					</tr></thead>
					<tbody class="holder_report_agent">
						<tr>
							{section name=i loop=$list_preloaders}
							<tr>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="fw-bold text-upper">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="fw-bold text-upper">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="fw-bold text-upper">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								<td class="text-center">
									<div class="animate-bg w-100 h-px-15 rounded-2"></div>
								</td>
								
							</tr>
							{/section}
						</tr>
					</div>
				</table>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){ 
		$Core.report.load_report_agent({}); 
		$Core.report.load_sold_stock_7days({});
	});
</script>
{/literal}