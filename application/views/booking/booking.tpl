<script type="text/javascript"> var booking_type = '{$booking_type}'; </script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y booking_page">
	<form method="POST">
		<div class="d-flex justify-content-between align-items-start mb-2">
			<div class="vRHjwnrkGa d-flex gap-2 mb-2 mb-lg-0">
				<a href="{$clsISO->getLink('booking')}" class="back mr-1">
					<img src="{$smarty.const.ICON_BACK}"></a>
				<div class="ysXwpeiJqL d-flex flex-column">
					<h4 class="fw-bold mb-0">Bảng booking {$oneItem.title}</h4>
					<span class="text-muted">Tổng cộng <strong class="text-main">{$total_record}</strong> Booking {$smarty.const.BRAND_NAME}</span>
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
	{if $clsISO->checkPermissionGroup('SALE_DIRECTOR') 
		|| $clsISO->checkPermissionGroup('DIRECTOR') 
		|| $clsISO->checkPermissionGroup('ADMIN_PROJECT') 
		|| $clsISO->_DEV()}
	<div class="card mb-2">
		<div class="card-body">
			<div class="text-nowrap fixedTable overflow-x-auto">
				<table class="table table-stock notFixedTable" cellpadding="0" cellspacing="0">
					<thead>
						<tr class="nohover">
							<th class="align-center pheading text-center h-px-50" colspan="{$arr_bedrrom|@count + 2}">
								<strong class="fs-20 text-main text-upper">Báo cáo tổng hợp</strong>
							</th>
						</tr> 
						<tr class="nohover" style="background: #edae74">
							<th class="text-center phead h-px-30" width="60px"></th>
							{foreach name=k from=$arr_bedrrom item = _oItem}
							<th class="text-center phead h-px-30">{$_oItem.title}</th>
							{/foreach}
							<th class="text-center phead h-px-30">Tổng BK</th>
						</tr>
						<tr class="nohover h-px-30" style="background: #edae74">
							<th class="text-center phead cursor-pointer">PKD</th>
							{foreach name=k from=$arr_bedrrom item = _oBedroom}
							<th class="text-center h-px-30 phead notFixedTable">
								{if !empty($list_total_booking_bed[$_oBedroom.property_id])}
									{$list_total_booking_bed[$_oBedroom.property_id]}
								{else}
									0
								{/if}
							</th>
							{/foreach}									
							<th class="text-center h-px-30 phead notFixedTable">{$total_booking_bed}</th>
						</tr>
					</thead>
					{foreach from=$list_total_booking_dep item=_oItem key=k_layout name=i_layout}	
						{assign var=total_bedroom value=$_oItem.bedroom}
						{assign var = bgcolor value = '#fffaed'}
						{if $smarty.foreach.i_layout.index % 2 == 0}
							{assign var = bgcolor value = '#FFF'}
						{/if}
						<tbody style='background:{$bgcolor}'>
							<tr class="nohover">
								<td class="text-center th_range" style="background:{$bgcolor};">{$_oItem.title}</td>
								{foreach name=k from=$arr_bedrrom item = _oBedroom}
									<td class="text-center h-px-30">{if !empty($total_bedroom[$_oBedroom.property_id])}{$total_bedroom[$_oBedroom.property_id]}{else}0{/if}</td>
								{/foreach}									
								<td class="text-center h-px-30">{$_oItem.total}</td>
							</tr>
						</tbody>
					{/foreach}
				</table>
			</div>
		</div>
	</div>
	{/if}
    <!-- Basic Bootstrap Table -->
	{if !empty($list_buildings)}
		{foreach from=$list_buildings item=_oBuilding key=key name=i}
		{assign var=more_building value=$_oBuilding.more_information}
		{assign var=arr_booking value=$_oBuilding.arr_booking}
		{assign var=layout_range value=$_oBuilding.layout_range}
		{assign var=arr_stocks value=$_oBuilding.arr_stocks}
		{assign var=arr_cols value=$_oBuilding.arr_cols}
		{assign var=arr_row_range value=$_oBuilding.arr_row_range}
		<div class="card mb-2">
			<div class="card-body">
				<div class="text-nowrap fixedTable overflow-x-auto">
					<table class="table dragable table-stock" cellpadding="0" cellspacing="0">
						<thead><tr class="nohover">
							<th class="align-center text-center pheading h-px-50" colspan="{$more_building.template|@count+2}">
								<strong class="text-fs-20 xs:text-fs-18 text-main text-upper">Bảng booking ưu tiên Tòa {$_oBuilding.title}</strong> <span class="text-dark fw-normal">({$clsISO->formatDate($_oBuilding.last_upd,4)})</span>
							</th>
						</tr> 
						<tr class="nohover" style="background:#edae74">
							<th class="text-center sticky_fixed h-px-30 phead" width="60px" colspan="2">Khoảng<br>tầng/Căn</th>
							{if !empty($more_building.template)}
							{foreach from=$more_building.template item=_oTemplate key=k_temp name=i_temp}
							<th width="60px" class="text-center phead h-px-30">{$_oTemplate.code}</th>
							{/foreach}
							{/if}
						</tr>
						<tr class="nohover" style="background: #edae74">
							<th class="text-center sticky_fixed h-px-30 phead" colspan="2">L. Căn</th>
							{foreach name=k from=$arr_stocks item = _code}
							<th class="text-center phead">{$clsProject->getFieldInStock($_code, $arr_cols, 'bedroom_id')}</th>
							{/foreach}
						</tr>
						<tr class="nohover" style="background: #edae74">
							<th class="text-center sticky_fixed h-px-30 phead" colspan="2">DT.Tim</th>
							{foreach name=k from=$arr_stocks item = _code}
							<th class="text-center phead">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_Tim')}</th>
							{/foreach}
						</tr>
						<tr class="nohover" style="background: #edae74">
							<th class="text-center sticky_fixed h-px-30 phead" colspan="2">DT.TT</th>
							{foreach name=k from=$arr_stocks item = _code}
							<th class="text-center phead">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_TT')}</th>
							{/foreach}
						</tr></thead>
						{foreach from=$layout_range item=ranges key=k_layout name=i_layout}	
							{foreach from=$ranges item=_range key=k_range name=i_range}	
							{assign var = bgcolor value = '#fffaed'}
							{if $smarty.foreach.i_range.index % 2 == 0}
								{assign var = bgcolor value = '#FFF'}
							{/if}
							<tbody style="background:{$bgcolor}">
								{assign var=arr_booking value=$_range.arr_booking}
								{assign var=row_ut value=$_range.row_ut}
								<tr class="nohover">
									<td class="text-center th_range{if $smarty.foreach.i_layout.last && $smarty.foreach.i_range.last} border-bottom-0{/if}" style="background:{$bgcolor}" rowspan="{$row_ut+1}">{$_range.label}</td>
								</tr>
								{section loop=$row_ut start=0 step=1 name=row}
								<tr class="nohover">
									<td style="background:{$bgcolor}" class="text-center h-px-30 th_ut">UT{$smarty.section.row.iteration}</td>
									{foreach from=$more_building.template item=_oTemplate key=key name=i}
										{if !empty($arr_booking[$_oTemplate.code]) && !empty($arr_booking[$_oTemplate.code][$smarty.section.row.index])}
										{assign var = oBooking value=$arr_booking[$_oTemplate.code][$smarty.section.row.index]}
										<td style="background:{$arr_color[$smarty.section.row.index]}" class="text-center position-relative">
											{if $oBooking.staff_id eq $profile_id}
												<img class="avatar rounded-pill avatar-xxs" src="{$clsProfile->getAvatar($profile_id, $oneProfile, 30, 30)}" />
											{/if}
											{if $oBooking.department_id eq $oneProfile.department_id}
												<i class="triagle"></i>
											{/if}
											<span class="text-nowrap cursor-pointer" onClick="$Core.booking.view_booking(this, event)" booking_id="{$oBooking.booking_id}" booking_type="{$oBooking.booking_type}"> {$oBooking.department_name}</span>
										</td>
										{else}
										<td></td>
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
	.table-stock .pheading{
		background:#fced63;
		border-width: 0 0 1px 0;
		border-color:#ce8f55;
	}
	.table-stock .phead{
		border-left: 1px solid #ce8f55;
		border-bottom: 1px solid #ce8f55;
		border-right: 0;
		border-top: 0;
		background:inherit !important
	}
	.table-stock tr td{
		border-width:0px 1px 1px 0;
		border-style:solid;
		border-color:#DDD;
	}
	.table-stock tr td:not(.th_range):last-child{
		border-right:0 !important;
	}
	.table-stock tbody:last-child tr:last-child td{
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
	.table-stock th,
	.table-stock td{
		color: rgb(0,0,0) !important;		
	}
	.table-stock td > i.triagle{
		display: block;
		position: absolute;
		left: 0; top: 0;
		width: 0; height: 0;
		border-left: 12px solid #9f223a;
		border-bottom: 12px solid transparent;
	}
	@media screen and (min-width:1400px) {
		.table-stock{ 
			table-layout:fixed;
		}
		.table-stock th,
		.table-stock td{
			font-size:12px;
			padding:.125rem .325rem; 
		}
	}
</style>
<script type="text/javascript">
	$(() => {
		$Core.booking.sticky_fixed();
		$(window).on('resize', function(){
			$Core.booking.sticky_fixed();
		});
	});
</script>
{/literal}