<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	{assign var = gId value = $clsISO->getUniqid()}
	<div class="d-flex justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Tổng quan giá các phân khu thấp tầng</span></h4>
			<p class="text-muted mb-0">Hệ thống hỗ trợ bán hàng</p>
		</div>
		<div class="p__right">
			<div class="input-group w-px-300">
				<select name="project_id" id="" class="form-control form-select search_field" data-field="project_id" onChange="$Core.report.load_block_by_type(this,event);" block_type="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}" gId="{$gId}" toId="slt_block">
					{foreach from=$list_projects item=_oItem key=key name=i}
					<option value="{$_oItem.project_id}" {if $_oItem.project_id eq $smarty.const._PROJECT_VHOP2_ID}selected{/if}>{$_oItem.title}</option>
					{/foreach}
				</select>
				<select name="block_id" id="slt_block" class="form-control form-select search_field" data-field="block_id" onChange="$Core.report.reload(this,event)" gId="{$gId}">
					{foreach from=$list_blocks item=_oItem key=key name=i}
					<option value="{$_oItem.property_id}">{$_oItem.title}</option>
					{/foreach}
				</select>
					
			</div>
			
		</div>
	</div>
	<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
		<div class="panel border-0 no-shadow panel-white mb-0 card p-3">
			<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center border-0 px-0 pt-0">
				<h3 class="panel-title mb-0 text-main" style="white-space: break-spaces">Tổng quan giá </h3>
			</div>
			<div class="panel-body p-0">
				<div class="text-nowrap fixedTable overflow-x-auto" data-options='{ldelim}"building_id":{$_oB.property_id}{rdelim}'>
					<table cellpadding="0" cellspacing="0" class="table table_report_price table-bordered dragable mb-0">
						<thead class="position-sticky top-0 zindex-3"><tr style="background:#edae74">
							<th class="bg-lighter text-center sticky_fixed h-px-30 phead zindex-3" rowspan="2">Loại căn</th>
							<th class="bg-lighter text-center h-px-30 phead" rowspan="2">Diện tích</th>
							<th class="bg-lighter text-center h-px-30 phead" colspan="6">Giá thấp nhất</th>
							<th class="bg-lighter text-center h-px-30 phead" colspan="6">Giá cao nhất</th>
						</tr>
						<tr class="nohover" style="background:#edae74">
							<th class="phead h-px-30 text-center" style="background: #cbffcb">TTS/m<sup>2</sup></th>
							<th class="phead h-px-30 text-center" style="background: #cbffcb">Vay/m<sup>2</sup></th>
							<th class="phead h-px-30 text-center" class="bg-lighter">VAT</th>
							<th class="phead h-px-30 text-center" class="bg-lighter">TTS</th>
							<th class="phead h-px-30 text-center" class="bg-lighter">TTTĐ</th>
							<th class="phead h-px-30 text-center" class="bg-lighter">Vay</th>
							<th class="phead h-px-30 text-center" style="background: #fff4f6">TTS/m<sup>2</sup></th>
							<th class="phead h-px-30 text-center" style="background: #fff4f6">Vay/m<sup>2</sup></th>
							<th class="phead h-px-30 text-center" class="bg-lighter">VAT</th>
							<th class="phead h-px-30 text-center" class="bg-lighter">TTS</th>
							<th class="phead h-px-30 text-center" class="bg-lighter">TTTĐ</th>
							<th class="phead h-px-30 text-center" class="bg-lighter" style="position: unset">Vay</th>
						</tr></thead>
						<tbody class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=project&act=load_report_price_lowfloor" data-options='{ldelim}{rdelim}'>
							{section loop=10 start=0 step=1 name=i}
								<tr>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
									<td class="text-nowrap"><div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div></td>
								</tr>
							{/section}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<style>
.fixedTable{
	border:1px solid #DDD;
	overflow:hidden;
	border-radius:4px;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
}
.fixedTable .table {
	border-collapse: separate;
}
.table_report_price .pheading{
	background:#fced63;
	border-width: 0 0 1px 0;
	border-color:#ce8f55;
}
.table_report_price .phead{
	border-left: 1px solid #ce8f55;
	border-bottom: 1px solid #ce8f55;
	border-right: 0;
	border-top: 0;
	background:inherit !important
}
.table_report_price tr td{
	border-width:0px 1px 1px 0;
	border-style:solid;
	border-color:#DDD;
}
.table_report_price tr td:not(.th_range):last-child{
	border-right:0 !important;
}
.table_report_price tbody:last-child tr:last-child td{
	border-bottom:0 !important
}
.fixedTable .th_range,
.fixedTable th.sticky_fixed	{
	z-index: 1;
	position: sticky;
	left:0; top:0;
}
.fixedTable .th_ut {
	position: sticky;
	left: 66px;
	z-index: 1;
}
.table_report_price th,
.table_report_price td{
	color: rgb(0,0,0) !important;		
}
.table_report_price td > i.triagle{
	display: block;
	position: absolute;
	left: 0; top: 0;
	width: 0; height: 0;
	border-left: 12px solid #9f223a;
	border-bottom: 12px solid transparent;
}
@media screen and (min-width:1400px) {
	.table_report_price{ 
		table-layout:fixed;
	}
	.table_report_price th,
	.table_report_price td{
		font-size:12px;
		padding:.125rem .325rem; 
	}
}
</style>
{/literal}