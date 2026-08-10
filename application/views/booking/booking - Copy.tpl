<script type="text/javascript"> var booking_type = '{$booking_type}'; </script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y booking_page">
	<form method="POST">
		<div class="d-flex justify-content-between align-items-start mb-2">
			<div class="vRHjwnrkGa d-flex gap-2 mb-2 mb-lg-0">
				<a href="{$clsISO->getLink('booking')}" class="back mr-1">
					<img src="{$smarty.const.ICON_BACK}"></a>
				<div class="ysXwpeiJqL d-flex flex-column">
					<h4 class="fw-bold mb-0">Bảng booking {$oneItem.title}</h4>
					<span class="text-muted">Tổng cộng <strong class="text-main">{$total_record}</strong> Booking FH</span>
				</div>
			</div>
			{if !empty($arr_projects)}
				<div class="btn-group">
					{if $deviceType eq 'phone'}
					<button type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" 
						data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-filter-alt"></i></button>
					{else}
					<button type="button" class="btn btn-outline-default dropdown-toggle" data-bs-toggle="dropdown" 
						aria-expanded="false"><i class='bx bx-link-external'></i> Chọn dự án</button>
					{/if}
					<ul class="dropdown-menu dropdown-menu-end w-px-300">
						{foreach from=$arr_projects item=_oItem key=key name=i}
						{if $_oItem.project_id eq $project_id && $_oItem.block_id eq $block_id }
						<li><a class="dropdown-item active" href="javascript:void(0)">
							<div class="d-flex align-items-center justify-content-between">
								<span> {$_oItem.block_name}</span>
								<i class='bx bx-chevron-right'></i>
							</div>
						</a></li>
						{else}
						<li><a class="dropdown-item" href="{$clsBooking->getLinkBooking($_oItem.project_id,$_oItem.block_id)}">
							<div class="d-flex align-items-center justify-content-between">
								<span>{$_oItem.block_name}</span>
								<i class='bx bx-chevron-right'></i>
							</div>
						</a></li>
						{/if}
						{/foreach}
					</ul>
				</div>
			{/if}
		</div>
	</form>
    <!-- Basic Bootstrap Table -->
	{if !empty($lstBuilding)}
	{foreach from=$lstBuilding item=_oBuilding key=key name=i}
		{assign var=more_building value=$_oBuilding.more_information}
		{assign var=arr_booking value=$_oBuilding.arr_booking}
		{assign var=layout_range value=$_oBuilding.layout_range}
		{assign var=arr_stocks value=$_oBuilding.arr_stocks}
		{assign var=arr_cols value=$_oBuilding.arr_cols}
		{assign var=arr_row_range value=$_oBuilding.arr_row_range}
		<div class="card mb-2">
			<div class="card-body">
				<div class="freeze-table text-nowrap">
					<table class="table fixedTable table-stock table-bordered border-label-dark">
						<thead style="background: {$more_information.bgcolor}">
							<tr>
								<th class="align-center text-black text-center h-px-50" colspan="{$more_building.template|@count + 2}" 
								style="min-width:60px;background:#f5ea57"><strong class="fs-24 text-upper">Bảng booking ưu tiên Tòa {$_oBuilding.title}</strong></th>
							</tr> 
							<tr style="background: inherit">
								<th class="text-white text-center h-px-30" width="60px" colspan="2"  style="background: inherit">Khoảng <br> tầng/Căn</th>
								{foreach from=$more_building.template item=_oTemplate key=k_temp name=i_temp}
									<th width="60px" class="text-center js__stock-cell-focus text-white h-px-30" code="{$_oTemplate.code}" >{$_oTemplate.code}</th>
								{/foreach}
							</tr>
							<tr style="background: inherit">
								<th class="text-white text-center h-px-30" colspan="2" style="background: inherit">Loại Căn</th>
								{foreach name=k from=$arr_stocks item = _code}
								{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
								<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'bedroom_id')}</th>
								{/foreach}
							</tr>
							<tr style="background: inherit">
								<th class="text-white text-center h-px-30" colspan="2" style="background: inherit">Tim tường</th>
								{foreach name=k from=$arr_stocks item = _code}
								{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
								<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_Tim')}</th>
								{/foreach}
							</tr>
							<tr style="background: inherit">
								<th class="text-white text-center h-px-30" colspan="2" style="background: inherit">Thông thủy</th>
								{foreach name=k from=$arr_stocks item = _code}
								{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
								<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_TT')}</th>
								{/foreach}
							</tr>
						</thead>
						{foreach from=$layout_range item=ranges key=k_layout name=i_layout}	
							{foreach from=$ranges item=_range key=k_range name=i_range}	
							<tbody {*style='background:{cycle values="#fff2ce,#f3cccf"}'*} style="background:#FFF" class=''>
									{assign var=arr_booking value=$_range.arr_booking}
									{assign var=row_ut value=$_range.row_ut}
									<tr style="background: inherit">
										<th class="text-center cursor-pointer" rowspan="{$row_ut + 1}" style="background: inherit">{$_range.label}</th>
									</tr>
									{section loop=$row_ut start=0 step=1 name=row}
									<tr>
										<td class="text-center h-px-30 th_ut">UT{$smarty.section.row.iteration}</td>
										{foreach from=$more_building.template item=_oTemplate key=key name=i}
											{if !empty($arr_booking[$_oTemplate.code]) && !empty($arr_booking[$_oTemplate.code][$smarty.section.row.index])}
											{assign var = oBooking value=$arr_booking[$_oTemplate.code][$smarty.section.row.index]}
											{assign var = oStaff value=$arr_booking[$_oTemplate.code][$smarty.section.row.index].oStaff}
											<td style="background:{$arr_color[$smarty.section.row.index]};" code="{$_oTemplate.code}" floor="38" class="js__stock-cell-focus text-center">
												<span class="text-nowrap cursor-pointer" onclick="$Core.booking.view_booking(this, event)" booking_id="{$oBooking.booking_id}" booking_type="{$oBooking.booking_type}">{$oStaff.department_name}</span>
											</td>
											{else}
											<td code="{$_oTemplate.code}" floor="38" class="js__stock-cell-focus"></td>
											{/if}
										{/foreach}
									</tr>
									{/section}
							</tbody>
							{/foreach}
						{/foreach}
					</table>
				</div>
			</div>
		</div>
	{/foreach}
	{/if}
</div>
{literal}
<style type="text/css">
	tr.bg-cancel td{
		background:#fbe9e9 !important;
		--bs-table-accent-bg:#fbe9e9 !important;
	}
	.no-radius-right .selectize-input{
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		border-right: 0px !important;
	}
	.selectize-input,
	.selectize-control.single .selectize-input.focus{
		padding:7px !important;
		min-height:37.5px !important;
	}
	.table-booking tr th:nth-child(1),
	.table-booking tr td:nth-child(1){
		min-width:115px;
	}
	.table-booking tr th:nth-child(2),
	.table-booking tr td:nth-child(2){
		min-width:135px;
		max-width:135px;
	}
	@media screen and (min-width:576px){
		.table-booking tr th:nth-child(2){
			position:sticky;
			left:134px; top:0;
		}
		.table-booking tr td:nth-child(2){
			position:sticky;
			left:134px; top:0;
			background:var(--bs-white);
		}
	}
	@media screen and (max-width:645px){
		.highlight-panel.mobile > div{
			min-width:135%;
		}
	}
	.freeze-table{
		user-select:none;
		-moz-user-select:none;
		-khtml-user-select:none;
		-webkit-user-select:none;
		-o-user-select:none;
		overflow-x:auto;
		-webkit-overflow-scrolling:touch
	}
	.table-stock .cell{
		position:relative;
		background:inherit!important;
	}
	.table-tooltip th,.table-tooltip td{
		padding:.325rem .625rem
	}
	.table-grid td{
		padding: 0.325rem 0.325rem
	}
	.table-grid th{
		line-height:16px; 
		background:rgb(245 247 248);
		text-overflow: inherit
	}
	.table-stock th{
		text-overflow: inherit
	}
	.table-stock th,.table-stock td{
		color: #000
	}
	@media screen and (min-width:1400px) {
		.table-stock{ table-layout:fixed;}
		.table-stock th,
		.table-stock td{
			font-size:12px;
			padding:.125rem .325rem; 
			height:16px; 
			line-height:16px;
			
		}
		.table-grid th{
			padding:0.625rem 0.325rem
		}
	}
	@media screen and (max-width:1400px) {
		.table-stock th,
		.table-stock td{font-size:8px; padding:.125rem .2rem}
	}
	@media screen and (max-width:575px) {
		.om-xs\:mb-1{ margin-bottom:10px;}
	}
	.box_tool_tip {
		font-weight: bold;
		font-size: 11px;
		color:var(--bs-white);
		background: #FFF0;
		border: none !important;
		box-shadow: none !important;
		border-radius:10px 0 10px 0;
	}
	.box_tool_tip:before,
	.box_tool_tip .leaflet-tooltip-arrow {
		/* display: none;*/
	}
	.item_tooltip {
		width: 130px;
		text-align: center;
		background: #eadcc1;
		border: 1px solid #eadcc1;
		border-radius: 10px;
		overflow: hidden;
	}
	.item_tooltip .box_code {
		padding: 3px 5px;
		font-weight: bold;
		font-size: 16px;
		background: #966334;
		color: #f5fac0;
		text-shadow: 2px 1px black;
	}
	.item_tooltip .body_tooltip {
		padding: 5px;
	}
	.item_tooltip .box_text {
		font-size: 9px;
		margin-bottom: 3px;
		color: #342828;
	}
	.item_tooltip .text-value {
		font-weight: 700;
		font-size: 11px;
	}
	.item_tooltip .text-price {
		padding: 3px;
		font-size: 14px;
		background: #966334;
		color: #f5fac0;
		text-shadow: 2px 1px #51341b;
		margin-top: 3px;
		border-radius: 5px;
	}
</style>
{/literal}