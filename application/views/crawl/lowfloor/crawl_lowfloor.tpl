
<script src="{$URL_JS}/jspreadsheet/jexcel.js?v={$upd_version}"></script>
<script src="{$URL_JS}/jspreadsheet/jsuites.js?v={$upd_version}"></script>
<link rel="stylesheet" href="{$URL_JS}/jspreadsheet/jsuites.css?v={$upd_version}" type="text/css">
<link rel="stylesheet" href="{$URL_JS}/jspreadsheet/jexcel.css?v={$upd_version}" type="text/css">
<div class="container-xxl flex-grow-1 pt-2 container-p-y" >
	<div class="d-flex align-items-center mb-2 flex-wrap gap-2 justify-content-between">
		<div class="p__left mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1 {if $deviceType eq 'phone'}fs-16{/if}">Danh sách cập nhật thấp tầng đại lý</h4>
			<span class="text-muted">Tổng hợp danh sách cập nhật thấp tầng đại lý</span>
		</div>
		<div class="p__right d-flex gap-1 {if $deviceType eq 'phone'}justify-content-end flex-fill{/if}">
			<button agency_id="{$_oItem.property_id}" type="button" class="btn px-1 border rounded-3 button_general " onClick="$Core.crawl.crawl_general(this,event)" title="Thực hiện">
				<i class="fa fa-play fs-18"></i> 
				<span>Thực hiện</span>
			</button>			
			<a class="btn btn-default btn-icon" href="/thong-ke-cap-nhat-bang-hang.html">
				<i class="bx bx-bar-chart-alt"></i>
			</a>
			<button class="btn btn-default btn-icon" type="button" onclick="$Core.crawl.open_config_update_stock(this,event)" stock_type="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}">
				<i class="fa fa-cogs" aria-hidden="true"></i>
			</button>
			<button title="Hướng dẫn sử dụng" onclick="$Core.crawl.open_help(this, event)" data-toggle="ripple" class="btn btn-icon btn-outline-default"><span class="ripple-ink animate" style="height: 37.3438px; width: 37.3438px; top: -4.6719px; left: -0.32815px;"></span><i class="bx bx-help-circle"></i></button>
		</div>
	</div>
	<div class="card mb-4">
		<div class="card-header">
			<div class="d-flex gap-2 {if $deviceType eq 'phone'}flex-wrap{else} justify-content-center{/if}">
				<div class="d-flex align-items-center gap-1">
					<span style="background:#ffe1e1" class="d-block border w-px-30 h-px-30"></span>
					<span class="fs-6"><span class="fw-bold text-main number_error">0</span> lỗi cập nhật</span>
				</div>
				<div class="d-flex align-items-center gap-1">
					<span style="background:#ffff9c" class="d-block border w-px-30 h-px-30"></span>
					<span class="fs-6"><span class="fw-bold text-main number_changed">0</span> cảnh báo đại lý thay đổi link bảng hàng</span>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-container no-shadow overflow-auto" style="max-height: calc(100vh - 200px)">
				<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0">
					<thead class="position-sticky top-0 zindex-3" style="background: #f5f7f8">
						<tr>
							{if $deviceType ne 'phone'}
								<th class="align-center h-px-40 zindex-3" width="3%" rowspan="2">No.</th>
							{/if}
							<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
							<th class="align-center h-px-40 text-center" width="20%" colspan="{$lst_project|@count}">
								<div class="d-flex align-items-center justify-content-center gap-1">
									Dự án
									<span class="fw-bold fs-20 text-warning">{$totalStock}</span>
								</div>
							</th>
							{*<th class="align-center h-px-40" width="60px" rowspan="2"></th>*}
						</tr>
						<tr>
							{foreach from=$lst_project item = _oProject key=key name=i}
								<th class="align-center h-px-40 text-center no-sticky" width="20%" {if $smarty.foreach.i.last}style="border-right: 1px solid #d9dee3"{/if}>
									<div class="d-flex align-items-center justify-content-center gap-1">
										{$_oProject.project_code}
										<span class="fw-bold fs-20 text-warning">{$arr_total_project[$key]}</span>
									</div>
								</th>
							{/foreach}
						</tr>
					</thead>
					<tbody class="table-border-bottom-0">
						{if !empty($list_agency) }
							{assign var=index value=0}
							{foreach from=$list_agency item=_oItem name=i }
								{assign var = crawl_lowfloor value = $_oItem.crawl_lowfloor}
								{assign var = more_information value = $_oItem.more_information}
								{math equation="x+1" x=$index assign="index"}
								<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
									{if $deviceType ne 'phone'}
									<td class="align-center text-center">{$index}</td>
									{/if}
									<td class="text-nowrap" data-label="Tiêu đề" width="100px">
										<div class="d-flex align-items-center justify-content-between gap-1">
											{$_oItem.title}
											<span class="fw-bold fs-20 text-warning">{$_oItem.total_stock}</span>
										</div>
									</td>
									{foreach from=$lst_project item=_oProject key=key}
										<td class="text-center {if $crawl_lowfloor[$key].is_success eq '0'}not_success{elseif !empty($crawl_lowfloor[$key].is_crawl) && !empty($crawl_lowfloor[$key].is_changed)}is_changed{/if}" {if $crawl_lowfloor[$key].is_success eq '0'}style="background:#ffe1e1"{elseif !empty($crawl_lowfloor[$key].is_crawl) && !empty($crawl_lowfloor[$key].is_changed)}style="background:#ffff9c !important"{/if}>
											<div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
												{if !empty($crawl_lowfloor[$key].is_crawl)}
													<div class="d-flex gap-1 align-items-center justify-content-between w-100">
														<div class="fs-11 d-flex flex-column flex-wrap gap-1 lst_action_crawl">
															<div class="d-flex align-items-center gap-1">
																<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="{$_oItem.property_id}" stock_type="{$stock_type}" target_id="{$key}" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
																<button agency_id="{$_oItem.property_id}" type="button" class="btn btn-outline-primary btn-sm text-nowrap btn_crawl" onClick="$Core.crawl.crawl_agency_lowfloor(this,event)" target_id="{$key}" stock_type="{$stock_type}">
																	<i class="fa fa-play"></i> 
																	<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
																	<span>Thực hiện</span>
																</button>
																<button agency_id="{$_oItem.property_id}" type="button" class="btn btn-success btn-sm text-nowrap" onclick="$Core.global.stock.start_import(this, event)" project_id="{$key}" stock_type="{$stock_type}" spreadsheetId="{$_oProject.spreadsheetId}">
																	<i class="fa fa-play"></i> 
																	<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
																	<span>Crawl</span>
																</button>
															</div>
															<div class="d-flex gap-1 align-items-center flex-wrap gap-1">
																<div class="text-nowrap"><a href="{$clsISO->genGoogleURL($crawl_lowfloor[$key].sheetID,'spreadsheets')}" target="_blank" class="limit_1line">Bảng hàng nguồn <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap"><a href="{$crawl_lowfloor[$key].link_stock}" target="_blank" class="limit_1line text-main">Bảng hàng <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap text-left w-100">Cập nhật: <strong class="text-main">{$crawl_lowfloor[$key].time}</strong>{if !empty($crawl_lowfloor[$key].html_result)} {if !empty($crawl_lowfloor[$key].user_upd)}bởi: {$crawl_lowfloor[$key].user_upd}{/if} ({$crawl_lowfloor[$key].html_result}){/if}</div>
															</div>
														</div>
														<span class="fw-bold fs-20 text-warning">{$crawl_lowfloor[$key].total_stock}</span>
													</div>
												{else}
													<div class="d-flex gap-1 align-items-center justify-content-between w-100">
														<div class="fs-11 d-flex flex-column flex-wrap gap-1 lst_action_crawl">
															<div class="d-flex align-items-center gap-1"> 															
																<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="{$_oItem.property_id}" stock_type="{$stock_type}" target_id="{$key}" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
																<button agency_id="{$_oItem.property_id}" type="button" class="btn btn-success btn-sm text-nowrap" onclick="$Core.global.stock.start_import(this, event)" project_id="{$key}" stock_type="{$stock_type}" spreadsheetId="{$_oProject.spreadsheetId}">
																	<i class="fa fa-play"></i> 
																	<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
																	<span>Crawl</span>
																</button>
															</div>
															<div class="d-flex gap-1 align-items-center flex-wrap gap-1">
																<div class="text-nowrap"><a href="{$clsISO->genGoogleURL($_oProject.spreadsheetId,'spreadsheets')}" target="_blank" class="limit_1line">Link cập nhật <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap"><a href="{$crawl_lowfloor[$key].link_stock}" target="_blank" class="limit_1line text-main">Bảng hàng <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap text-left w-100">Cập nhật: <strong class="text-main">{$crawl_lowfloor[$key].time}</strong>{if !empty($crawl_lowfloor[$key].html_result)} {if !empty($crawl_lowfloor[$key].user_upd)}bởi: {$crawl_lowfloor[$key].user_upd}{/if} ({$crawl_lowfloor[$key].html_result}){/if}</div>
															</div>
														</div>
														<span class="fw-bold fs-20 text-warning">{$crawl_lowfloor[$key].total_stock}</span>
													</div>										
												{/if}										
											</div>	
										</td>
									{/foreach}
									{*<td class="text-center" data-label="{$core->get_Lang('Actions')}">
										<button class="btn btn-icon btn-default" onClick="$Core.crawl.open_agency(this,event)" stock_type="{$stock_type}" class="btn btn-default" agency_id="{$_oItem.property_id}">{$core->makeIcon('pencil')}</button>
									</td>*}
								</tr>
							{/foreach}
						{else}
							<tr>
								<td class="text-center" colspan="{if $deviceType eq 'phone'}4{else}5{/if}">
									Danh sách trống !
								</td>
							</tr>
						{/if}
					</tbody>
				</table>
			</div>
		</div>
    </div>
</div>
{literal}
<style>
	.crawl-page{
		-moz-user-select: none !important;
		-webkit-touch-callout: none!important;
		-webkit-user-select: none!important;
		-khtml-user-select: none!important;
		-moz-user-select: none!important;
		-ms-user-select: none!important;
		user-select: none!important;
	}
</style>
{/literal}