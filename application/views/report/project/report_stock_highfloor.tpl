<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	{assign var = gId value = $clsISO->getUniqid()}
	<div class="d-flex justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Bảng giá m2</span></h4>
			<p class="text-muted mb-0">Hệ thống hỗ trợ bán hàng</p>
		</div>
	</div>
	<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
		<div class="panel border-0 no-shadow panel-white mb-0 card p-3">
			<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center border-0 px-0 pt-0">
				<h3 class="panel-title mb-0 text-main" style="white-space: break-spaces">Bảng giá / m<sup>2</sup> các dự án </h3>
			</div>
			<div class="panel-body p-0">
				<div class="text-nowrap fixedTable overflow-x-auto" data-options='{ldelim}"building_id":{$_oB.property_id}{rdelim}'>
					<table cellpadding="0" cellspacing="0" class="table table_report_price table-bordered dragable mb-0">
						<thead class="position-sticky top-0 zindex-3"><tr style="background:#edae74">
							<th class="bg-lighter text-center sticky_fixed h-px-30 phead zindex-3" rowspan="2">Loại căn</th>
							{foreach from=$lstBlock item=title key=key name=i}
								<th class="bg-lighter text-center h-px-30 phead" colspan="3">{$title}</th>
							{/foreach}							
						</tr>
						<tr class="nohover" style="background:#edae74">
							{foreach from=$lstBlock item=title key=key name=i}
								<th class="phead h-px-30 text-center">TTS</th>
								<th class="phead h-px-30 text-center">TTTĐ</th>
								<th class="phead h-px-30 text-center">Vay</th>
							{/foreach}	
						</tr></thead>
						<tbody class="" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=project&act=load_report_price_lowfloor" data-options='{ldelim}{rdelim}'>
							{foreach from=$arr_bedroom item=_oBedroom key=key name=i}
								{assign var=list_block value=$_oBedroom.list_block}	
								<tr>
									<td class="text-nowrap position-sticky start-0 bg-white">{$_oBedroom.title}</td>
									{foreach from=$lstBlock item=_oBlock key=block_id name=n}
										<td class="text-nowrap h-px-30 text-center" style="background-color:#f4ffee !important">{$list_block[$block_id].price_early}</td>
										<td class="text-nowrap h-px-30 text-center" style="background: #fffdee !important">{$list_block[$block_id].price_progress}</td>
										<td class="text-nowrap h-px-30 text-center" style="background: #f2eeff !important">{$list_block[$block_id].price_bank}</td>
									{/foreach}	
								</tr>
							{/foreach}
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
</style>
{/literal}