<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-xxl-10 col-xxl-8 mx-auto">
			<div class="card-header d-flex flex-wrap justify-content-between align-items-center mb-0 pb-0">
				<div class="sLXazhNQJU mb-2 mb-lg-0">
					<h4 class="fw-bold mb-1">Danh sách thống kê căn bán</h4>
					<span class="text-muted">Tổng cộng <strong class="text-main total-record">0</strong> căn bán</span>
				</div>
			</div>
			<div class="dashboard-panel-item dashboard-panel-item--full card-body">
				<div class="panel border-0 no-shadow panel-default">
					<div class="panel-heading d-flex flex-wrap justify-content-end align-items-center ">
						<div class="p_top {if $deviceType eq 'phone'}w-100 mt-2{/if}">
							<div class="search-top d-flex flex-wrap{if $deviceType eq 'phone'} w-100{/if} gap-1 align-items-center">
								<label class="text-nowrap d-none d-lg-block">Lọc theo:</label> 
								<div class="flex-fill" style="width: 150px">
									<select placeholder="Chọn dự án" class="form-select iso-select2 search_field" data-width="100%" uid="{$uid}" 
											name="project_id" data-field="project_id" onChange="$Core.report.load_report_stock_sold();" >
										<option value="0">Dự án</option>
										{if !empty($list_projects)}
											{foreach from=$list_projects item = _oItem}
											<option{if $project_id eq $_oItem.project_id} selected{/if} value="{$_oItem.project_id}">{$_oItem.title}</option>
											{/foreach}
										{/if}
									</select>
								</div>
								<div class="flex-fill" style="width: 150px">
									<select placeholder="Chọn đại lý" class="form-select iso-select2 search_field" data-width="100%" uid="{$uid}" 
										name="agency_id" data-field="agency_id" onChange="$Core.report.load_report_stock_sold();" >
										<option value="0">Đại lý</option>
										{if !empty($arr_agency)}
											{foreach from=$arr_agency item = _oItem}
											<option{if $agency_id eq $_oItem.property_id} selected{/if} value="{$_oItem.property_id}">{$_oItem.title}</option>
											{/foreach}
										{/if}
									</select>
								</div>
								
								<div class="input-group d-flex ox:w-100 flex-fill {if $deviceType ne 'phone'} w-px-250{/if}" role="group">							
									<input type="date" class="form-control search_field" onChange="$Core.report.load_report_stock_sold();" 
										name="start_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
									<input type="date" class="form-control search_field" onChange="$Core.report.load_report_stock_sold();" 
										name="due_date" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
								</div>
							</div>
						</div>
					</div>						
					<div class="panel-body px-0">
						<div id="tableReport" class="freeze-table overflow-x-auto dragscroll text-nowrap table-container">
							<table class="table table-border table-ilooca text-nowrap" width="100%" cellpadding="0" cellspacing="0" >
								<thead><tr>
									{if $deviceType ne 'phone'}
									<th width="30px" class="align-center h-px-40 align-center nosort bg-lighter">STT</th>
									{/if}
									<th class="align-center h-px-40 text-left bg-lighter">Mã căn</th>
									<th class="align-center h-px-40 text-left bg-lighter">Dự án</th>
									<th class="align-center h-px-40 text-left bg-lighter">Phân khu</th>
									<th class="align-center h-px-40 text-center bg-lighter">Tòa/dãy</th>
									<th class="align-center h-px-40 text-center bg-lighter">Loại hình</th>
									<th class="align-center h-px-40 text-center bg-lighter">Loại căn</th>
									<th class="align-center h-px-40 text-left bg-lighter">Đại lý</th>
								</tr></thead>
								<tbody id="list_stock_sold">
									{section name=i loop=$list_preloaders}
									<tr>
										{if $deviceType ne 'phone'}
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										{/if}
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
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
		</div>
	</div>
</div>
{literal}
<style>
.panel-body:not(.no-easyui) {
    font-size: 14px;
}
</style>
	<script>
		$(document).ready(function(){
			$Core.report.load_report_stock_sold();
		});
	</script>
{/literal}