{if $clsISO->checkPermission('company_overview')}
<div class="form-row">
	<div class="col-12 col-lg-3 mb-2">
		<div class="card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card-body">
				<div class="d-flex align-items-center mb-3 justify-content-between">
					<div class="p-left">
						<h5 class="card-title mb-0 text-nowrap">Doanh số</h5>
						<small class="d-block text-nowrap">Doanh số bán hàng</small>
					</div>
					<div class="p-right">
						{if $deviceType ne 'phone'}
						<div class="input-group w-px-250 d-flex align-items-center" role="group">
							<select class="form-control form-select" name="date_type" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this,event)"> 
								<option value="_month">Tháng</option>
								<option value="_quarter">Quý</option>
							</select>
							<select class="form-control form-select" name="month" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this,event)"> 
								<option value="">Chọn tháng</option>			
								{foreach from=$list_months item = _month}
								<option value="{$_month}">T{$_month}</option>
								{/foreach}
							</select>
							<select class="form-control form-select" name="year" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this,event)">
								{foreach from=$list_years item = _year}
								<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
								{/foreach}
							</select>
						</div>
						{else}
						<div class="dropdown">
							<button class="btn btn-icon p-0 h-auto" type="button" data-bs-toggle="dropdown">
								<i class="bx bx-dots-vertical-rounded"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
								<div class="p-3">
									<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
										<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
											{foreach from=$list_months item = _month}
											<option{if $_month eq $smarty.now|date_format:"%m"} selected{/if} value="{$_month}">Tháng {$_month}</option>
											{/foreach}	
										</select>
									</div>
									<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
										<select class="form-control form-select" name="quarter" gId="{$gId}" 
											onChange="$Core.dashboard.reload(this,event)"> 
											<option value="">Quý</option>			
											{section loop=4 start=0 step=1 name=i}
											<option value="{$smarty.section.i.iteration}">Quý {$smarty.section.i.iteration}</option>
											{/section}
										</select>
									</div>
									<div class="form-group  mb-2" role="group" aria-label="Sắp xếp">	
										<select class="form-control form-select" name="year" gId="{$gId}" onchange="$Core.dashboard.reload(this,event)">
											{foreach from=$list_years item = _year}
											<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
											{/foreach}
										</select>
									</div>
								</div>
							</div>
						</div>
						{/if}
					</div>
				</div>
				<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_sales_overview">
					<div class="p-4 text-center">
						<div class="py-1">Loading...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-6 mb-2">
		<div class="card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card-body">
				<div class="w-100 d-flex align-items-center justify-content-between mb-3">
					<div class="card-header-left">
						<h5 class="card-title mb-0 text-nowrap">Loại hình giao dịch</h5>
						<small class="d-block text-nowrap">Thống kê loại hình giao dịch</small>
					</div>
					<div class="card-header-right">
						{if $deviceType ne 'phone'}
							<div class="input-group w-px-250 d-flex align-items-center" role="group">
								<select class="form-control form-select" name="date_type" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this,event)"> 
									<option value="_month">Tháng</option>
									<option value="_quarter">Quý</option>
								</select>
								<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
									<option value="">Chọn tháng</option>			
									{foreach from=$list_months item = _month}
									<option value="{$_month}">T{$_month}</option>
									{/foreach}
								</select>
								<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
							</div>
						{else}
							<div class="dropdown">
								<button class="btn btn-icon p-0 h-auto" type="button" data-bs-toggle="dropdown">
									<i class="bx bx-dots-vertical-rounded"></i>
								</button>
								<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
									<div class="p-3">
										<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
											<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
												{foreach from=$list_months item = _month}
												<option{if $_month eq $smarty.now|date_format:"%m"} selected{/if} value="{$_month}">Tháng {$_month}</option>
												{/foreach}	
											</select>
										</div>
										<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
											<select class="form-control form-select" name="quarter" gId="{$gId}" 
												onChange="$Core.dashboard.reload(this,event)"> 
												<option value="">Quý</option>			
												{section loop=4 start=0 step=1 name=i}
												<option value="{$smarty.section.i.iteration}">Quý {$smarty.section.i.iteration}</option>
												{/section}
											</select>
										</div>
										<div class="form-group  mb-2" role="group" aria-label="Sắp xếp">	
											<select class="form-control form-select" name="year" gId="{$gId}" onchange="$Core.dashboard.reload(this,event)">
												{foreach from=$list_years item = _year}
												<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
												{/foreach}
											</select>
										</div>
									</div>
								</div>
							</div>
						{/if}
					</div>
				</div>
				<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_group_sale_overview">
					<div class="p-4 text-center">
						<div class="py-1">Loading...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-3 mb-2">
		<div class="card h-100">
			<div class="d-flex align-items-end">
				<div class="card-body row ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_info_staff" 
				data-options='{ldelim}{rdelim}'>
					<div class="p-5 text-center">
						<div class="p-2">Loading...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{/if}
<!-- 
{assign var = gId value = $clsISO->getUniqid()}
<div class="w-100">
	<div class="ajax briefs mb-2 gap-2 d-flex flex-wrap briefs_accountant" gId="{$gId}" data-url="/index.php?mod=fund&act=load_apec_fund" data-options={ldelim}{rdelim}>
		<div class="brief-item bg-orange p-3 clickable">
			<p class="fs-16 mb-3">Quỹ đầu kỳ(₫)</p>
			<h3 class="fs-32 mb-0 text-white">
				<div class="animate-bg w-px-125 h-px-20 rounded-2"></div>
			</h3>
		</div>
		<div class="brief-item p-3 bg-azure">
			<p class="fs-16 mb-3">Tổng thu(₫)</p>
			<h3 class="fs-32 mb-0 text-white">
				<div class="animate-bg w-px-125 h-px-20 rounded-2"></div>
			</h3>
		</div>
		<div class="brief-item p-3 bg-cyan">
			<p class="fs-16 mb-3">Tổng chi(₫)</p>
			<h3 class="fs-32 mb-0 text-white">
				<div class="animate-bg w-px-125 h-px-20 rounded-2"></div>
			</h3>
		</div>
		<div class="brief-item p-3 bg-green">
			<p class="fs-16 mb-3">Tồn quỹ(₫)</p>
			<h3 class="fs-32 mb-0 text-white">
				<div class="animate-bg w-px-125 h-px-20 rounded-2"></div>
			</h3>
		</div>
	</div>
</div> -->
{assign var = gId value = $clsISO->getUniqid()}
<div class="card mb-2">
	<div class="card-header">
		<h5 class="card-title mb-1">Chi phí vận hành </h5>
		<small class="text-muted">Chi phí vận hành 01/01/{$smarty.now|date_format:"%Y"} tới {$smarty.now|date_format:"%d/%m/%Y"}</small>
	</div>
	<div class="card-body">
		<div class="form-row row-cols-lg-5 ajax mb-2" data-url="{$PCMS_URL}/index.php?mod=fund&act=get_opscost_total" 
			gId="{$gId}" data-options='{ldelim}"call_from":"dashboard"{rdelim}'>
			{foreach from=$list_opscost_blocks item = _OI key = _OK}
			<div class="col mb-2 mb-lg-0">
				<div class="p-3 rounded-2 fund_box fund_{$_OK} relative" style="background-color:{$_OI.bgcolor}">
					<h5 class="mb-1 fs-4 fw-bold text-white">0.000 {$clsISO->getRate()}</h5>
					<hr class="w-px-100 my-2" />
					<span class="text-white text-nowrap">{$_OI.title}</span>		
				</div>
			</div>
			{/foreach}
		</div>
	</div>
</div>
{assign var = gId value = $clsISO->getUniqid()}
<div class="form-row ajax mb-2" data-url="{$PCMS_URL}/index.php?mod=home&sub=dashboard&act=load_desktop_bank_accounts" 
	data-options='{ldelim}{rdelim}' gId="{$gId}">
	{$html_bank_account_preloader}
</div>
<!-- Tiền về -->
{assign var = gId value = $clsISO->getUniqid()}
{$core->getBlock('money_in', ['gid' => $gId])}
<!-- End Tiền về -->
<div class="form-row mb-2">
	<div class="col-12 col-lg-8">
		<div class="sticky">
			<div class="card mb-2">
				<div class="card-header d-flex mb-0 justify-content-between align-items-center">
					<h5 class="card-title text-primary mb-0">Hoạt động tiếp khách</h5>
					<a href="{$PCMS_URL}/net-dep-lao-dong.html" class="btn btn-link">Xem tất cả</a>
				</div>
				<div class="card-body ajax" data-bind="{$uid}" data-url="/index.php?mod={$mod}&act=load_top_shares"></div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-md-6">
					<div class="card h-100">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
							<h5 class="card-title mb-2 mb-lg-0 me-2">Các khoản thu</h5>
							<div class="p-right">
								<div class="input-group w-px-150 d-flex align-items-center" role="group">
									<select class="form-control form-select" name="month" gId="{$gId}" data-type="THUCTHU" onChange="$Core.accountant.reload(this,event)"> 
										<option value="">Tháng</option>			
										{foreach from=$list_months item = _month}
										<option value="{$_month}">T{$_month}</option>
										{/foreach}
									</select>
									<select class="form-control form-select" name="year" gId="{$gId}" data-type="THUCTHU" onChange="$Core.accountant.reload(this,event)">
										{foreach from=$list_years item = _year}
										<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="build {$gId} ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod=fund&act=load_chart_realincome" 
							data-options='{ldelim}"chart_type":"bar"{rdelim}'>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="card h-100">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
							<h5 class="card-title mb-2 mb-lg-0 me-2">Các khoản chi</h5>
							<div class="p-right">
								<div class="input-group w-px-150 d-flex align-items-center" role="group">
									<select class="form-control form-select" name="month" gId="{$gId}" data-type="THUCCHI" onChange="$Core.accountant.reload(this,event)"> 
										<option value="">Tháng</option>			
										{foreach from=$list_months item = _month}
										<option value="{$_month}">T{$_month}</option>
										{/foreach}
									</select>
									<select class="form-control form-select" name="year" gId="{$gId}" data-type="THUCCHI" onChange="$Core.accountant.reload(this,event)">
										{foreach from=$list_years item = _year}
										<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="build {$gId} ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod=fund&act=load_chart_expense" 
							data-options='{ldelim}"chart_type":"bar"{rdelim}'>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Hoạt động tiếp khách -->
			<div class="form-row">
				<div class="col-12 col-lg-6">
					<div class="card h-100">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
							<h5 class="card-title mb-2 mb-lg-0 me-2">Biểu đồ thu chi</h5>
							<div class="p-right ox:w-100">
								<div class="input-group w-px-150 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
									<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.accountant.reload(this,event)"> 
										<option value="">Tháng</option>			
										{foreach from=$list_months item = _month}
										<option value="{$_month}">T{$_month}</option>
										{/foreach}
									</select>
									<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.accountant.reload(this,event)">
										{foreach from=$list_years item = _year}
										<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=accountant&act=load_income_expend" 
								gId="{$gId}" data-options='{ldelim}{rdelim}'>
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
				<!-- End col -->
				<div class="col-12 col-lg-6">
					<div class="card mb-2">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
							<div class="fdEGtYEGtK mb-2 mb-lg-0">
								<h5 class="card-title mb-0">VAT đã xuất</h5>
								<small>Thống kế VAT đã xuất</small>
							</div>
							<div class="nccbSLVgJR">
								<div class="input-group w-px-150 d-flex align-items-center" role="group">
									<select class="form-control form-select" name="month" gId="{$gId}" data-type="THUCCHI" onChange="$Core.accountant.reload(this,event)"> 
										<option value="">Tháng</option>			
										{foreach from=$list_months item = _month}
										<option value="{$_month}">T{$_month}</option>
										{/foreach}
									</select>
									<select class="form-control form-select" name="year" gId="{$gId}" data-type="THUCCHI" onChange="$Core.accountant.reload(this,event)">
										{foreach from=$list_years item = _year}
										<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="ajax {$gId} build" gId="{$gId}" data-options='{ldelim}{rdelim}' 
							data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_VAT&vat_type={$smarty.const._VAT_TYPE_VATOUT}">
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							</div>
						</div>
					</div>
					<div class="card">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
							<div class="fdEGtYEGtK mb-2 mb-lg-0">
								<h5 class="card-title mb-2 mb-lg-0 me-2">VAT đã nhận</h5>
								<small>Thống kế VAT đã nhận</small>
							</div>
							<div class="nccbSLVgJR">
								<div class="input-group w-px-150 d-flex align-items-center" role="group">
									<select class="form-control form-select" name="month" gId="{$gId}" data-type="THUCCHI" onChange="$Core.accountant.reload(this,event)"> 
										<option value="">Tháng</option>			
										{foreach from=$list_months item = _month}
										<option value="{$_month}">T{$_month}</option>
										{/foreach}
									</select>
									<select class="form-control form-select" name="year" gId="{$gId}" data-type="THUCCHI" onChange="$Core.accountant.reload(this,event)">
										{foreach from=$list_years item = _year}
										<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="ajax " gId="{$gId}" data-options='{ldelim}{rdelim}' 
							data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_VAT&vat_type={$smarty.const._VAT_TYPE_VATIN}">
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
		</div>
	</div>
	<div class="col-12 col-lg-4">
		{if $deviceType ne 'phone'}
			{$core->getBlock('note_calendar')}
		{/if}
		<div class="card ranking mb-2 pb-2">
			<div class="card-body">
				{$core->getBlock('top_ranking')}
			</div>
		</div>
		{assign var = gId value = $clsISO->getUniqid()}
		{$core->getBlock('ranking_dept', ['gId' => $gId])}
		{assign var = gId value = $clsISO->getUniqid()}
		{$core->getBlock('top_ranker', ['gId' => $gId])}
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
</style>
{/literal}