<div class="form-row mb-2">
	<div class="col-12 col-lg-4 mb-2 mb-lg-0">
		<div class="card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card-body">
				<div class="d-flex align-items-center mb-3 justify-content-between">
					<div class="p-left">
						<h5 class="card-title mb-0 text-nowrap">Doanh số</h5>
						<small class="d-block text-nowrap">Doanh số bán hàng <span class="txt_block_code">{$oneBLock.property_code}</span></small>
					</div>
					<div class="p-right">
						<div class="input-group w-px-175 d-flex align-items-center" role="group">
							<select class="form-control form-select" name="month" gId="{$gId}" 
								onChange="$Core.admin.reload(this,event)"> 
								<option value="">Tháng</option>			
								{foreach from=$list_months item = _month}
								<option value="{$_month}">T{$_month}</option>
								{/foreach}
							</select>
							<select class="form-control form-select" name="year" gId="{$gId}" 
								onChange="$Core.admin.reload(this,event)">
								{foreach from=$list_years item = _year}
								<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
								{/foreach}
							</select>
						</div>
					</div>
				</div>
				<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_sales_overview_admin">
					<div class="p-4 text-center">
						<div class="py-1">Loading...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4 mb-2 mb-lg-0">
		<div class="card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card-body">
				<div class="w-100 d-flex align-items-center justify-content-between mb-3">
					<div class="card-header-left">
						<h5 class="card-title mb-0 text-nowrap">Giao dịch ký HĐMB</h5>
						<small class="d-block text-nowrap">Thống kê giao dịch ký HĐMB</small>
					</div>
					<div class="card-header-right">
						<div class="input-group w-px-175 d-flex" role="group">
							<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.admin.reload(this,event)"> 
								<option value="">Tháng</option>			
								{foreach from=$list_months item = _month}
								<option value="{$_month}">T{$_month}</option>
								{/foreach}
							</select>
							<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.admin.reload(this,event)">
								{foreach from=$list_years item = _year}
								<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
								{/foreach}
							</select>
						</div>
					</div>
				</div>
				<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing_overview_admin">
					<div class="p-4 text-center">
						<div class="py-1">Loading...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4">
		<div class="card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="w-100 d-flex card-header align-items-center justify-content-between">
				<h5 class="card-title mb-0">Thống kê quỹ ôm MWF</h5>
				<a class="text-main" href="/billing/report/mwf.html">{$clsISO->makeIcon('bx-line-chart-down', 'Báo cáo')}</a>
			</div>
			<div class="card-body ajax" data-options='{ldelim}{rdelim}' 
				data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=admin&act=load_stock_hug">
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
			</div>
		</div>
	</div>
</div>
<div class="form-row mb-2">
	<div class="col-12 col-lg-8 mb-2 mb-lg-0">
		<div class="card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
				<div class="d-flex flex-column gap-1">
					<h5 class="card-title mb-0">Biểu đồ doanh số</h5>
					<small class="text-muted">Biểu đồ tăng trưởng doanh số</small>
				</div>
				<div class="p-right">
					<div class="input-group w-px-150 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
						<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
							<option value="">Tháng</option>			
							{foreach from=$list_months item = _month}
							<option value="{$_month}">T{$_month}</option>
							{/foreach}
						</select>
						<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
							{foreach from=$list_years item = _year}
							<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
							{/foreach}
						</select>
						{if $deviceType ne 'phone' && 1==2}
						<button type="button" onClick="$Core.dashboard.open_full(this, event)" tp="load_billing_chart" class="btn d-none d-lg-block btn-icon btn-outline-default"><i class='bx bx-windows'></i></button>
						{/if}
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing_chart" data-options='{ldelim}{rdelim}'>
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
	<div class="col-12 col-lg-4">
		<div class="card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card-header w-100 d-flex align-items-center justify-content-between">
				<div class="card-header-left">
					<h5 class="card-title mb-0">Thống kê bán hàng</h5>
					<small class="text-muted">Thống kê hiệu quả bán hàng</small>
				</div>
				<div class="card-header-right">
					<div class="input-group d-flex w-px-150 ox:w-100" role="group">
						<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
							<option value="">Tháng</option>			
							{foreach from=$list_months item = _month}
							<option{if $_month eq $current_month} selected{/if} value="{$_month}">T{$_month}</option>
							{/foreach}
						</select>
						<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
							{foreach from=$list_years item = _year}
							<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
			<div class="card-body ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing_chart_admin" 
				data-options='{ldelim}"month":"{$current_month}"{rdelim}'>
			</div>
		</div>
	</div>
</div>	
<div class="form-row mb-2">
	<div class="col-12 col-lg-8">		
		<div class="sticky">
			<!-- <div class="card mb-2">
				<div class="card-header mb-0">
					<h5 class="card-title mb-0">Danh sách cập nhật đại lý</h5>
					<small class="text-muted">Thống kê cập nhật bảng hàng đại lý</small> 
				</div>
				<div id="list_agent_log" class="w-100 box_loadMore card-body" style="min-height:300px;">
					<div class="p-5 text-center">
						<div class="p-5 text-muted">Loading...</div>
					</div>
				</div>
			</div> -->
			<div class="card mb-2">
				<div class="card-header d-flex mb-0 justify-content-between align-items-center">
					<h5 class="card-title text-primary mb-0">Hoạt động tiếp khách</h5>
					<a href="{$PCMS_URL}/net-dep-lao-dong.html" class="btn btn-link">Xem tất cả</a>
				</div>
				<div class="card-body ajax" data-bind="{$uid}" data-url="/index.php?mod={$mod}&act=load_top_shares"></div>
			</div>
			<!-- End Hoạt động tiếp khách -->
			<div class="form-row mb-2">
				<div class="col-12 col-lg-6 mb-2 mb-lg-0">
					<div class="card h-100">
						<div class="card-header d-flex mb-0 justify-content-between align-items-center">
							<div class="d-flex flex-column gap-1">
								<h5 class="card-title mb-0">Yêu cầu cập nhật PTG</h5>
								<small class="text-muted">Các yêu cầu PTG mới nhất</small>
							</div>
							<a href="{$clsISO->getLink('request_ptg')}" class="btn btn-link">Xem tất cả</a>
						</div>
						<div class="card-body">
							<div class="table-container overflow-x-auto text-nowrap no-shadow">
								<table class="table table-bordered table-borderd" cellpadding="0" cellspacing="0" >
									<thead><tr>
										{if $deviceType ne 'phone'}
										<th width="5%" class="align-center bg-lightest text-center">STT</th>
										{/if}
										<th class="align-center bg-lightest" width="120px">Mã căn</th>
										<th class="align-center bg-lightest text-center">Người yêu cầu</th>
										<th class="align-center bg-lightest text-center" width="100px"></th>
									</tr></thead>
									<tbody class="ajax home_request_ptg" data-url="/index.php?mod=request_ptg&act=load_request_PTG">
										{section name=i loop=$list_preloaders max=12}
										<tr>
											{if $deviceType ne 'phone'}
											<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
											{/if}
											<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
											<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
											<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										</tr>
										{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-6 mb-2 mb-lg-0">
					<div class="card h-100">
						<div class="card-header d-flex flex-wrap mb-0 justify-content-between align-items-center">
							<div class="xs:w-100 mb-lg-0">
								<h5 class="card-title mb-0">Lịch ký HĐMB</h5>
								<small class="text-muted">Giao dịch đã nên lịch ký HĐMB</small>
							</div>
							<div class="xs:w-100">
								<select class="form-control form-select">
									<option>Lựa chọn</option>
								</select>
							</div>
						</div>
						<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_calendar_admin" 
							data-options='{ldelim}{rdelim}'>
							<div class="text-center mb-3 fw-bold">Tháng {$smarty.now|date_format:"%m,%Y"}</div>
							<div class="table-wrapper">
								<table width="100%" class="table table-bordered">
									<thead><tr>
										<th class="align-center text-center">CN</th>
										<th class="align-center text-center">T2</th>
										<th class="align-center text-center">T3</th>
										<th class="align-center text-center">T4</th>
										<th class="align-center text-center">T5</th>
										<th class="align-center text-center">T6</th>
										<th class="align-center text-center">T7</th>
									</tr></thead>
									{section name=i loop=$list_preloaders max=10}
									<tr>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									</tr>
									{/section}
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-6 mb-2 mb-lg-0">
					<div class="card h-100">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Giao dịch mới nhất</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=dashboard&tp=top_billing" 
						data-options='{ldelim}{rdelim}'>
							<div class="loader text-center py-8">
								<img src="{$URL_IMAGES}/loading.gif" />
								<p>Loading...</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					{$core->getBlock('top_staff', ['class' => ' h-100'])}
				</div>
			</div>
			<!-- <div class="form-group mb-2">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">Danh sách đại lý bán MWF</h5>
						<small class="text-muted">Thống kê số lượng căn các đại lý bán MWF</small>
					</div>
					<div class="card-body">
						<div class="table-wrapper">
							<table class="table table-bordered table-borderd">
								<thead><tr>
									<th width="5%" class="align-center bg-lightest text-center">STT</th>
									<th class="align-center bg-lightest">Đại lý</th>
									<th class="align-center bg-lightest text-center">Miani</th>
									<th class="align-center bg-lightest text-center">Hawai</th>
								</tr></thead>
								<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_agent_sell_mwf" 
									data-options='{ldelim}{rdelim}'>
									{section name=i loop=$list_preloaders max=10}
									<tr>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
										<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									</tr>
									{/section}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>-->
		</div>
	</div>
	<!--/ Overview & Sales Activity -->
	<div class="col-12 col-md-12 col-lg-4">
		{if $deviceType ne 'phone'}
		{$core->getBlock('note_calendar')}
		{*{$core->getBlock('ranking_group')}*}
		<div class="card ranking mb-2 pb-2">
			<div class="card-body">
				{$core->getBlock('top_ranking')}
			</div>
		</div>
		{$core->getBlock('ranking_dept')}
		{$core->getBlock('top_ranker', ['gId' => $gId])}
		{/if}
	</div>
</div>	
{literal}
<style type="text/css">
	.card-header{
		position:relative; 
	}
	.card-header::before{
		content: "";
		width: 0px;
		height: 30px;
		position: absolute;
		left: 0px; top: 20px;
		border-left: 5px solid #950b25;
	}
	.sticky{
		top:90px;
	}
</style>
<script>
	$(document).ready(function(){
		// $Core.report.agent_log();
	});
</script>
{/literal}