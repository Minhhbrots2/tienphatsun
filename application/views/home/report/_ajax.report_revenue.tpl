<div class="card mb-2">
	<div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
		<h5 class="card-title mb-2 mb-lg-0 me-2">Biểu đồ thống kê doanh số</h5>
	</div>
	<div class="card-body">
		<div id="{$uid}" class="chartContainer h-px-300"></div>
	</div>
</div>
<div class="card mb-2">
	<div class="card-header d-flex align-items-center justify-content-between">
		<h5 class="mb-0">Thống kê doanh số</h5>
		<a href="javascript:void(0);" class="text-muted help_pop openHelp" title="Trợ giúp">
			<i class="fa fa-question-circle"></i>
		</a>
	</div>
	<div class="card-body holder_revenue_reports">
		<div class="table-container">
			<table border="0" cellspacing="0" cellpadding="0" class="table w-100">
				<thead><tr>
					{if $deviceType ne 'phone'}
					<th class="align-center h-px-40 bg-lighter" width="40">STT</th>
					{/if}
					<th class="align-center h-px-40 bg-lighter">Dự án</th>
					<th class="align-center h-px-40 bg-lighter text-center" width="150">Doanh số</th>
					<th class="align-center h-px-40 bg-lighter text-center" width="100">Giao dịch</th>
				</tr></thead>
				<tbody>
					{if !empty($arr_project)}
						{foreach from=$arr_project item=_oItem key=key name=i}
							<tr>
								{if $deviceType ne 'phone'}
								<td class="text-center">{$smarty.foreach.i.iteration}</td>
								{/if}
								<td class="">{$_oItem.title}</td>
								<td class="text-center text-main">{$clsISO->priceFormat($_oItem.total_sale,0)} đ</td>
								<td class="text-center text-info">{$_oItem.total_billing}</td>
							</tr>
						{/foreach}
						<tr>
							<td class="text-center bg-lighter text-main fw-bold text-upper" colspan="{if $deviceType ne 'phone'}2{else}1{/if}">Tổng</td>
							<td class="text-center bg-lighter text-main fw-bold">{$clsISO->priceFormat($total_sales,0)} đ</td>
							<td class="text-center bg-lighter text-info fw-bold">{$total_billings}</td>
						</tr>
					{/if}
				</tbody>
			</table>
		</div>
	</div>
</div>