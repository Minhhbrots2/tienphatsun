<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Bảng giá tổng quan các phân khu</span></h4>
			<p class="text-muted mb-0">Hệ thống hỗ trợ bán hàng Ocean City</p>
		</div>
	</div>
	<div class="box_statistic">
		<div class="form-row mt-2">		
			<div class="col-12 col-xxl-12 mb-2">
				<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
					<div class="panel border-0 no-shadow panel-default mb-0">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
							<h3 class="panel-title">Biểu đồ thống kê trung bình giá TTS, Vay</h3>
						</div>
						<div class="panel-body px-0">
							{assign var = gId value = $clsISO->getUniqid()}
							<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_building_chart" data-options='{ldelim}{rdelim}'>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			{foreach from=$list_block_mas item = _oB}
				{assign var=list_bedroom value=$_oB.list_bedroom}
				{assign var=average_early_min_m2 value=$_oB.average_early_min_m2}
				{assign var=average_bank_min_m2 value=$_oB.average_bank_min_m2}
				{assign var=average_early_max_m2 value=$_oB.average_early_max_m2}
				{assign var=average_bank_max_m2 value=$_oB.average_bank_max_m2}
				<div class="col-12 col-md-12 col-xxl-12 mb-2 flex-fill box_building">
					<div class="dashboard-panel-item dashboard-panel-item--full mb-0 h-100">
						<div class="panel border-0 no-shadow panel-default mb-0 h-100">
							<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
								<h3 class="panel-title mb-0 text-main" style="white-space: break-spaces">{$_oB.block_name} - {$_oB.title}</h3>
								<a href="{$_oB.link}" target="_blank">Xem bảng hàng {if $_oB.total_stock gt 0}({$_oB.total_stock}){/if} <i class='bx bx-link-external'></i></a>
							</div>
							<div class="panel-body px-0">
								<div class="load_report_MWF overflow-auto table-container" data-options='{ldelim}"building_id":{$_oB.property_id}{rdelim}'>
									<table class="table mb-0 text-center">
										<thead>
										<tr>
											<th class="bg-lighter text-center" rowspan="2">Căn</th>
											<th class="bg-lighter text-center" colspan="6">Giá thấp nhất</th>
											<th class="bg-lighter text-center" colspan="6">Giá cao nhất</th>
											<th class="bg-lighter" rowspan="2" style="background:rgb(240 242 245) !important">T.Trạng</th>
										</tr>
											<tr>
											<th class="bg-lighter" style="position:unset">D.Tích</th>
											<th style="background: #cbffcb">TTS/m<sup>2</sup></th>
											<th style="background: #cbffcb">Vay/m<sup>2</sup></th>
											<th class="bg-lighter">TTS</th>
											<th class="bg-lighter">TTTĐ</th>
											<th class="bg-lighter">Vay</th>
											<th class="bg-lighter">D.Tích</th>
											<th style="background: #fff4f6">TTS/m<sup>2</sup></th>
											<th style="background: #fff4f6">Vay/m<sup>2</sup></th>
											<th class="bg-lighter">TTS</th>
											<th class="bg-lighter">TTTĐ</th>
											<th class="bg-lighter" style="position: unset">Vay</th>
										</tr></thead>
										{foreach from=$list_bedroom item=_oItem key=key name=i}
											<tr>
												<td class="text-nowrap">{$_oItem.title}</td>
												<td class="text-nowrap">{if !empty($_oItem.DT_TT_MIN)}{$_oItem.DT_TT_MIN}m<sup>2</sup> {else}--{/if}</td>
												<td class="text-nowrap" style="background: #cbffcb">{$clsISO->shortNumberV2($_oItem.total_price_early_min_m2)}</td>
												<td class="text-nowrap" style="background: #cbffcb">{$clsISO->shortNumberV2($_oItem.total_price_bank_min_m2)}</td>
												<td class="text-nowrap">{$clsISO->shortNumberV2($_oItem.total_price_early_min)}</td>
												<td class="text-nowrap">{if $_oItem.total_price_progress_min > 0}{$clsISO->shortNumberV2($_oItem.total_price_progress_min)}{else}--{/if}</td>
												<td class="text-nowrap">{$clsISO->shortNumberV2($_oItem.total_price_bank_min)}</td>
												
												<td class="text-nowrap">{if !empty($_oItem.DT_TT_MAX)}{$_oItem.DT_TT_MAX}m<sup>2</sup> {else}--{/if}</td>
												<td class="text-nowrap" style="background: #fff4f6">{$clsISO->shortNumberV2($_oItem.total_price_early_max_m2)}</td>
												<td class="text-nowrap" style="background: #fff4f6">{$clsISO->shortNumberV2($_oItem.total_price_bank_max_m2)}</td>
												<td class="text-nowrap">{$clsISO->shortNumberV2($_oItem.total_price_early_max)}</td>
												<td class="text-nowrap">{if $_oItem.total_price_progress_max > 0}{$clsISO->shortNumberV2($_oItem.total_price_progress_max)}{else}--{/if}</td>
												<td class="text-nowrap">{$clsISO->shortNumberV2($_oItem.total_price_bank_max)}</td>
												<td class="text-center">
													{if $_oItem.number_stock gt 0}
														<span class='text-success text-nowrap'>Còn hàng</span>
													{else}
														<span class='text-danger text-nowrap'>Hết hàng</span>
													{/if}
												</td>
											</tr>
										{/foreach}
										<tr>
											{if $deviceType eq phone}
											<td class="bg-lighter text-upper fs-12 fw-semibold text-nowrap" style="background:rgb(240 242 245) !important">Trung bình</td>
											<td class=""></td>
											{else}
											<td class="bg-lighter text-upper fs-12 fw-semibold text-nowrap" colspan="2" style="background:rgb(240 242 245) !important">Trung bình</td>
											{/if}
											<td class="text-nowrap" style="background: #cbffcb">{$clsISO->shortNumberV2($average_early_min_m2)}</td>
											<td class="text-nowrap" style="background: #cbffcb">{$clsISO->shortNumberV2($average_early_max_m2)}</td>
											<td colspan="4"></td>
											<td class="text-nowrap" style="background: #fff4f6">{$clsISO->shortNumberV2($average_bank_min_m2)}</td>
											<td class="text-nowrap" style="background: #fff4f6">{$clsISO->shortNumberV2($average_bank_max_m2)}</td>
											{if $deviceType eq phone}
											<td colspan="3" style="position:unset;border-right: 0;"></td>
											<td class="" style="border-left: 0;"></td>
											{else}
											<td colspan="4" style="position:unset"></td>
											{/if}
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
	</div>
</div>
{literal}
<style>
.load_report_MWF th, .load_report_MWF td {
    padding-left: 3px;
    padding-right: 3px;
}
.load_report_MWF td,.load_report_MWF th {
    border-width: 1px;
}
.table-container .table {
    border-collapse: collapse;
}
.table-container .table thead tr th:last-child, .table-container .table tbody tr td:last-child {
    right: 0;
    z-index: 2;
    position: sticky;
    top: 0;
    background: var(--bs-white);
    border-right: 1px solid #d9dee3;
}
</style>
<script>
	$(document).ready(function(){
		console.log('sss');
		$Core.report.load_report_project();
	});
</script>
{/literal}