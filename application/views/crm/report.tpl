<!-- Style -->
<link rel="stylesheet" type="text/css" href="{$URL_JS}/easyui/themes/gray/easyui.css" media="all" />
<script type="text/javascript" src="{$URL_JS}/easyui/jquery.easyui.min.js?v={$upd_version}"></script>
<script type="text/javascript">
    var crm_date_format="dd/mm/yy",
		crm_datepicker_format = {
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat :crm_date_format,
			yearRange: "1900:2050"
		};
</script>
<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="card no-shaddow">
		<div class="card-header">
			<a href="{$PCMS_URL}/crm/" class="back mr-2" title="{$core->get_Lang('Back')}">
				<img src="{$smarty.const.ICON_BACK}" />
			</a>
			<span class="text-upper">{$core->get_Lang('Statistics')} Khách hàng</span>
		</div>
		<div class="card-body">
			<div class="p-2 mb-2 bg-grayter rounded-2">
				<div class="d-flex w-100 align-items-center justify-content-between">
					<div class="p_left">
						<div class="form-inline d-flex align-items-center">
							<div class="form-group w-px-150 mr-2">
								<select name="department_id" data-field="department_id" onChange="$Core.crm.get_select_staff(this, event)" 
									class="form-control search_report_field iso-select2" data-width="100%" data-placeholder="Tất cả công ty">
									<option value="0">Tất cả công ty</option>
									{foreach from=$list_departments key=department_id item = _text }
									<option value="{$department_id}">{$_text}</option>
									{/foreach}
								</select>
							</div>
							<div class="form-group w-px-200">
								<input type="hidden" name="time_type" data-field="time_type" class="search_report_field" value="THIS_MONTH"  />
								<select name="staff_id" data-field="staff_id" onChange="$Core.crm.do_crm_search(this, event)" 
									class="form-control search_report_field iso-select2" data-width="100%" data-placeholder="Lựa chọn nhân viên">
									<option>Lựa chọn nhân viên</option>
								</select>
							</div>
						</div>
					</div>
					<div class="p_right">
						<ul class="nav list pull-right">
							{foreach from = $list_filters key = key item = text}
							<li class="nav-item">
								<a href="javascript:void(0);" onclick="$Core.crm.timer_click(this, event);"{if $key eq 'OTHERS'} data-url="{$PCMS_URL}/index.php?mod=crm&act=aj_open_rangedate_custom&datatype={$datatype}"{/if} data-time="{$key}" class="js_choose-time{if $key eq 'THIS_MONTH'} active{/if}{if $key eq 'OTHERS'} js_choice-rangedate-custom-{$datatype} notClick{/if}" holderG="{$key}">{$text}</a>
							</li>
							{/foreach}
						</ul>
					</div>
				</div>
			</div>
			<div class="form-group mb-3">
				<table class="table-kanban" width="100%">
					<thead><tr>
						<th class="w-25 text-center">
							<div class="stage-lbl">
								<div class="stage-lbl-txt">Khách hàng</div>
							</div>
						</th>
						<th class="w-25 text-center">
							<div class="pipeline-arrow">
								<div class="arrow-inner"></div>
							</div>
							<div class="stage-lbl">
								<div class="stage-lbl-txt">Tương tác</div>
							</div>
						</th> 
						<th class="w-25 text-center">
							<div class="pipeline-arrow">
								<div class="arrow-inner"></div>
							</div>
							<div class="stage-lbl">
								<div class="stage-lbl-txt">Giao dịch</div>
							</div>
						</th>
						<th class="w-25 text-center">
							<div class="pipeline-arrow">
								<div class="arrow-inner"></div>
							</div>
							<div class="stage-lbl">
								<div class="stage-lbl-txt">Doanh sô</div>
							</div>
						</th>
					</tr></thead>
					<tr>
						<td class="bordered-left text-center h-50px position-relative bold">
							<span class="lead-3 total_cell_1">0</span>
							<span class="pentagon percent_cell_1">0%</span>
						</td>
						<td class="bold h-50px text-center position-relative">
							<span class="lead-3 total_cell_2">0</span>
							<span class="pentagon percent_cell_2">0%</span>
						</td>
						<td class="bold h-50px text-center position-relative">
							<span class="lead-3 total_cell_4">0</span>
							<span class="pentagon percent_cell_4">0%</span>
						</td>
						<td class="bordered-right text-center">
							<span class="lead-3 total_cell_5">0</span>
						</td>
					</tr>
					<tr>
						<td class="bordered tableReportSummaryStaff" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_cell_crm_report&cell=1" data-delay="0" valign="top">{$clsISO->renderHTMLLoading()}</td>
						<td class="bordered tableReportSummaryStaff" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_cell_crm_report&cell=2" data-delay="200" valign="top">{$clsISO->renderHTMLLoading()}</td>
						<td class="bordered tableReportSummaryStaff" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_cell_crm_report&cell=4" data-delay="600" valign="top">{$clsISO->renderHTMLLoading()}</td>
						<td class="bordered tableReportSummaryStaff" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_cell_crm_report&cell=5" data-delay="800" valign="top">{$clsISO->renderHTMLLoading()}</td>
					</tr>
				</table>
			</div>
			<div class="form-row">
				<div class="col-md-4">
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel no-shaddow panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Thống kê nguồn gốc</h3>
							</div>
							<div class="panel-body">
								<div id="ChartCrmResource" style="width:100%; height:300px;">
									<div class="p-5 text-center">
										<div class="p-5 text-muted">Loading...</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel no-shaddow panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Thống kê Trạng thái</h3>
							</div>
							<div class="panel-body">
								<div id="ChartCrmStatus" style="width:100%; min-height:300px;">
									<div class="p-5 text-center">
										<div class="p-5 text-muted">Loading...</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-12">
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel panel-default radius-3">
							<div class="panel-header rounded-2 mb-2 d-flex justify-content-end">
								<div class="dropdown">
									{assign var = gId value = $clsISO->getUniqid()}
									<button id="{$gId}" type="button" class="btn btn-icon btn-default hide-arrow dropdown-toggle" 
									data-bs-toggle="dropdown" data-bs-auto-close="outside">{$clsISO->makeIcon('bx-search')}</button>
									<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-350" data-popper-placement="top-end">
										{$core->getBlock('customer_search',['gId'=>$gId, 'holderG' => '_report'])}
									</div> 
								</div>
							</div>
							<div class="panel-body no-easyui">
								{if $hide_help eq '0'}
								<div class="alert alert-info text-center alert-dismissible" role="alert">
									Click đúp chuột vào dòng để xem hoạt động chăm sóc khác hàng.
									<button onClick="$Core.crm.hide_help(this, event)" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								{/if}
								<div class="holder_customer overflow-x-auto">
									{$htmlLoading}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.w-25{ width:25%;}
</style>
<script type="text/javascript">
	$(function(){
		var _D = new Date(),
			_month = _D.getMonth()+1,
			_year = _D.getFullYear();
		$Core.crm.load_cell_crm_report();
		$Core.crm.loadChartCrmResource();
		$Core.crm.loadChartCrmStatus();
		//$Core.crm.loadDataChartCRMContactByClient();
		// $Core.crm.loadDataChartCRMContactInYear(_year);
		$Core.crm.load_customers('_report', {});
	});
</script>
{/literal}