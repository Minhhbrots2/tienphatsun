<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1">
				<a href="{$PCMS_URL}/kpi.html" class="fs-18 mr-2">{$core->makeIcon('angle-left')}</a> 
				Cài đặt chỉ tiêu
			</h4>
			<span class="text-muted mb-0">Thiết lập chỉ tiêu chi tiết</span>
		</div>
		<div class="p__right">
			<button type="button" title="Thêm nhanh" onClick="$Core.kpi.open(this, event)" 
				kpi_id="0" class="btn btn-outline-danger">+ Thêm chỉ tiêu</button>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="card">
		<div class="card-body">
			<div class="table-container text-nowrap overflow-x-auto">
				<table class="table table-striped mb-0" cellpadding="0" cellspacing="0" width="100%">
					<thead><tr>
						<th class="align-center bg-lighter h-px-35 text-center" width="3%">No.</th>
						<th class="align-center bg-lighter h-px-35 text-left">Tiêu đề</th>
						<th class="align-center bg-lighter h-px-35 text-left">Năm</th>
						<th class="align-center bg-lighter h-px-35 text-left">Lặp lại theo</th>
						<th class="align-center bg-lighter h-px-35 text-left">Áp dụng các tháng</th>
						<th class="align-center bg-lighter h-px-35" width="40px"></th>
					</tr></thead>
					{if !empty($list_kpis)}
						{foreach name=i from=$list_kpis item = _oKPI}
						<tr>
							<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
							<td class="align-center text-left">{$_oKPI.title}</td>
							<td class="align-center text-left">{$_oKPI.year_period}</td>
							<td class="align-center text-left">
								{if $_oKPI.period eq 'ALLMONTH'}Lặp lại hàng tháng{else}Lặp lại theo tháng{/if}
							</td>
							<td class="align-center text-left">
								{if $_oKPI.period eq 'ALLMONTH'}
									<span class="text-muted">Áp dụng cả năm</span>
								{else}
								
								{/if}
							</td>
							<td class="align-center text-center">
								<div class="btn-group">
									<button type="button" onClick="$Core.kpi.open(this, event)" kpi_id="{$_oKPI.kpi_id}" 
										class="btn btn-icon btn-sm btn-outline-default">{$clsISO->makeIcon('bx-pencil')}</button>
									<button type="button" onClick="$Core.kpi.delete(this, event)" kpi_id="{$_oKPI.kpi_id}" 
										class="btn btn-icon btn-sm btn-outline-default">{$clsISO->makeIcon('bx-trash')}</button>
								</div>
							</td>
						</tr>
						{/foreach}
					{/if}
				</table>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.avatar-group .user-plus{
		width:32px;
		height:32px;
		line-height:28px;
		padding:2px;
		font-size:12px;
	}
	@media screen and (max-width:767px) {
		.table-container .table tr th:nth-child(2){
			background:#F5F7F8 !important
		}
		.table-container .table tr th:nth-child(2),
		.table-container .table tr td:nth-child(2){
			z-index:2;
			position:sticky;
			left:0px; top:0;
			background:var(--bs-white);
			border-right: 1px solid #d9dee3;
		}
	}
</style>
{/literal}
