<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="card no-shaddow">
		<div class="card-header d-flex flex-wrap w-100 justify-content-between align-items-center">
			<div class="mb-2 mb-lg-0">
				<div class="d-flex align-items-center gap-2">
					<a href="{$PCMS_URL}/report/report_moc.html" class="back" title="Quay lại"><img src="{$smarty.const.ICON_BACK}" /></a>
					<span class="text-upper fw-bold">Thống kê check nguồn căn</span>
				</div>
				<p class="mb-0 text-muted">Tổng cộng <strong class="text-main total_record">0</strong> lượt check</p>
			</div>
			<div class="search-top d-flex{if $deviceType eq 'phone'} w-100{/if} gap-1 align-items-center">
				<label class="text-nowrap d-none d-lg-block">Lọc theo:</label> 
				<div class="input-group d-flex ox:w-100{if $deviceType ne 'phone'} w-px-250{/if}" role="group">
					<input type="date" class="form-control search_field" onChange="$Core.report.do_sr_search(this, event)" 
						name="start_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
					<input type="date" class="form-control search_field" onChange="$Core.report.do_sr_search(this, event)" 
						name="due_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
				</div>
			</div>
		</div>
		<div class="card-body">
			<div id="tableReport" class="freeze-table dragscroll text-nowrap">
				<table class="table table-border table-ilooca text-nowrap" width="100%">
					<thead><tr>
						{if $deviceType ne 'phone'}
						<th width="4%" class="align-center text-dark text-center">STT</th>{/if}
						<!-- <th class="align-center text-left w-px-100">Dự án</th>
						<th class="align-center text-left w-px-150">Phân khu</th> -->
						<th class="align-center border-end text-left w-px-125">Mã căn</th>
						<th class="align-center text-center w-px-75">L.check</th>
						<th class="align-center text-left w-px-175">TG check L.Cuối</th>
						<th class="align-center text-left">Người check L.Cuối</th>
					</tr></thead>
					<tbody id="holder_stock_resource_logs">
						{section name=i loop=$list_preloaders}
						<tr>
							<!-- <td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td> -->
							{if $deviceType ne 'phone'}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							{/if}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
						</tr>
						{/section}
					</tbody>
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
	.freeze-table .table th{
		line-height:30px;
		vertical-align:middle;
	}
	@media screen and (max-width:648px){
		.freeze-table .table tr>th:nth-child(1),
		.freeze-table .table tr>td:nth-child(1){
			border-right:1px solid #DDD;
		}
	}
	@media screen and (min-width:648px){
		.freeze-table .table{
			margin-bottom:0;
			min-width:1200px;
			max-width:16000px;
		}
		.freeze-table .table tr>th:nth-child(3),
		.freeze-table .table tr>td:nth-child(3){
			border-right:1px solid #DDD;
		}
	}
</style>
{/literal}