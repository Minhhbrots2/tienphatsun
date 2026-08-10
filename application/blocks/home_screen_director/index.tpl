<div class="form-row mb-2">
	<div class="col-12 col-lg-4 mb-2 mb-lg-0">
		<div class="dbx-card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="dbx-card__head">
				<span class="dbx-card__ic"><i class="bx bx-dollar-circle"></i></span>
				<div class="dbx-card__ttl">
					<h5 class="dbx-card__title mb-0 text-nowrap">Doanh số</h5>
					<small class="dbx-card__sub" id="time_search_{$gId}">Bán hàng tới {$smarty.now|date_format:"%d/%m/%Y"}</small>
				</div>
					<div class="p-right">
						<div class="dropdown">
							<button type="button" class="btn btn-icon btn-sm btn-link rounded-pill" 
								data-bs-toggle="dropdown" data-toggle="ripple">
								<i class="bx bx-dots-vertical-rounded text-muted"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
								<div class="p-3">
									<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
										<select class="form-control form-control-sm form-select" name="date_type" gId="{$gId}" 
											onChange="$Core.dashboard.reload(this,event)"> 
											<option{if $curr_date_type eq '_month'} selected{/if} value="_month">Tháng</option>
											<option{if $curr_date_type eq '_quarter'} selected{/if} value="_quarter">Quý</option>
											<option{if $curr_date_type eq '_half_year'} selected{/if} value="_half_year">Nửa năm</option>
										</select>
									</div>
									<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
										<select class="form-control form-control-sm form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
											<option value="0">Tháng</option>
											{foreach from=$list_months item = _month}
											<option{if $_month eq $smarty.now|date_format:"%m"} selected{/if} value="{$_month}">Tháng {$_month}</option>
											{/foreach}	
										</select>
									</div>
									<div class="form-group " role="group" aria-label="Sắp xếp">	
										<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" onchange="$Core.dashboard.reload(this,event)">
											{foreach from=$list_years item = _year}
											<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
											{/foreach}
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="dbx-card__body">
				<div class="ajax change_time" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_sales_overview" 
					{if $curr_date_type eq '_quarter'}data-options='{ldelim}"date_type":"{$curr_date_type}","month":"{$current_quater}"{rdelim}'{else}data-options='{ldelim}{rdelim}'{/if} toId="time_search_{$gId}" >
					<div class="w-100 h-px-15 rounded-2 animate-bg mb-2"></div>
					<div class="w-100 h-px-15 rounded-2 animate-bg mb-2"></div>
					<div class="w-100 h-px-15 rounded-2 animate-bg mb-0"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4 mb-2 mb-lg-0">
		<div class="dbx-card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="dbx-card__head">
				<span class="dbx-card__ic"><i class="bx bx-category-alt"></i></span>
				<div class="dbx-card__ttl">
					<h5 class="dbx-card__title mb-0 text-nowrap">Loại hình giao dịch</h5>
					<small class="dbx-card__sub">Thống kê loại hình giao dịch tới {$smarty.now|date_format:"%d/%m/%Y"}</small>
				</div>
					<div class="card-header-right">
						<div class="dropdown">
							<button class="btn btn-icon btn-sm rounded-pill btn-link" type="button" 
								data-bs-toggle="dropdown" data-toggle="ripple">
								<i class="bx bx-dots-vertical-rounded text-muted"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
								<div class="p-3">
									<div class="form-group mb-2">
										<select class="form-control form-control-sm form-select" name="date_type" 
											gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
											<option value="_month">Tháng</option>
											<option value="_quarter">Quý</option>
											<option value="_half_year">Nửa năm</option>
										</select>
									</div>
									<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
										<select class="form-control form-control-sm form-select" name="month" 
											gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
											<option value="0">Tháng</option>
											{foreach from=$list_months item = _month}
											<option{if $_month eq $smarty.now|date_format:"%m"} selected{/if} value="{$_month}">Tháng {$_month}</option>
											{/foreach}	
										</select>
									</div>
									<div class="form-group  mb-2" role="group" aria-label="Sắp xếp">	
										<select class="form-control form-control-sm form-select" name="year" 
											gId="{$gId}" onchange="$Core.dashboard.reload(this,event)">
											{foreach from=$list_years item = _year}
											<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
											{/foreach}
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="dbx-card__body">
				<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_group_sale_overview">
					<div class="p-4 text-center">
						<div class="py-1">Loading...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4">
		<div class="dbx-card h-100">
			<div class="dbx-card__body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_info_staff" 
			data-options='{ldelim}{rdelim}'>
				<div class="p-5 text-center">
					<div class="p-2">Loading...</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Tiền về -->
<div class="form-row mb-2">
	<div class="col-12 col-lg-4 mb-2 mb-lg-0">
		<div class="dbx-card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="dbx-card__head">
				<span class="dbx-card__ic"><i class="bx bx-diamond"></i></span>
				<h5 class="dbx-card__title mb-0">Thống kê quỹ độc quyền</h5>
				<a href="/doc-quyen/" title="Xem tất cả" class="btn btn-icon btn-sm btn-link rounded-pill">
					<i class="bx bx-link-external text-fs-14 text-muted"></i>
				</a>
			</div>
			<div id="{$gId}" class="dbx-card__body ajax" data-options='{ldelim}{rdelim}'
				data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_stock_hug">
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4 log_stock ajax mb-2 mb-lg-0" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_top_search_stock" 
		data-options="{ldelim}{rdelim}">
		<div class="dbx-card h-100">
			<div class="dbx-card__head">
				<span class="dbx-card__ic"><i class="bx bx-search-alt"></i></span>
				<h5 class="dbx-card__title mb-0">Thống kê lượt tra cứu 24h qua</h5>
			</div>
			<div class="dbx-card__body" >
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4 mb-2 mb-lg-0">
		<div class="dbx-card h-100">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="dbx-card__head">
				<span class="dbx-card__ic"><i class="bx bx-calendar-check"></i></span>
				<h5 class="dbx-card__title mb-0">Thống kê check-in</h5>
				<select class="form-control form-control-sm form-select w-auto ms-auto" name="dept" onChange="ckinFilter(this)">
					<option value="0">Toàn công ty</option>
					{foreach from=$lstCheckinDept item=_dep}
					<option value="{$_dep.id}">{if $_dep.depth > 0}{section name=s loop=$_dep.depth}&nbsp;&nbsp;{/section}└ {/if}{$_dep.title}</option>
					{/foreach}
				</select>
			</div>
			<div id="{$gId}" class="dbx-card__body ajax" data-options='{ldelim}{rdelim}'
				data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_checkin_report">
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
			</div>
		</div>
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-md-12 col-lg-8 order-0">	
		<div class="dbx-card mb-2">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="dbx-card__head flex-wrap">
				<span class="dbx-card__ic"><i class="bx bx-line-chart"></i></span>
				<h5 class="dbx-card__title mb-2 mb-lg-0 me-2">Tăng trưởng doanh số</h5>
				<div class="d-flex flex-wrap gap-2">
					{if $deviceType eq 'phone'}
					<div class="dropdown">
						<button class="btn btn-icon p-0 h-auto" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
							<div class="p-3">
								<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
									<select class="form-control form-control-sm form-select" name="department" gId="{$gId}" onChange="$Core.dashboard.load_profile(this,event)"> 
										<option value="">Phòng</option>			
										{foreach from=$lstDepartMent item = _department}
										<option value="{$_department.property_id}">{$_department.title}</option>
										{/foreach}
										<option value="OTHER">Phòng tổng hợp</option>
									</select>
								</div>
								<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
									<select class="form-control form-control-sm form-select" name="profile_id" id="profile_{$gId}" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
										<option value="">Nhân viên</option>			
										{foreach from=$lstProfile item = _oProfile}
										<option value="{$_oProfile.profile_id}">{$_oProfile.full_name}</option>
										{/foreach}
									</select>
								</div>
								<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
									<select class="form-control form-control-sm form-select" name="month" gid="{$gId}" onchange="$Core.dashboard.reload(this,event)"> 
										{foreach from=$list_months item = _month}
										<option{if $_month eq $smarty.now|date_format:"%m"} selected{/if} value="{$_month}">Tháng {$_month}</option>
										{/foreach}	
									</select>
								</div>
								<div class="form-group  mb-2" role="group" aria-label="Sắp xếp">	
									<select class="form-control form-control-sm form-select" name="year" gid="{$gId}" onchange="$Core.dashboard.reload(this,event)">
										{foreach from=$list_years item = _year}
										<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
					</div>
					{else}
					<div class="p-right ox:w-100">
						<div class="input-group w-px-400 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
							<select class="form-control form-control-sm form-select" name="department" gId="{$gId}" 
							onChange="$Core.dashboard.load_profile(this,event)"> 
								<option value="">Phòng</option>			
								{foreach from=$lstDepartMent item = _department}
								<option value="{$_department.property_id}">{$_department.title}</option>
								{/foreach}
								<option value="OTHER">Phòng tổng hợp</option>
							</select>
							<select class="form-control form-control-sm form-select" name="profile_id" id="profile_{$gId}" 
							gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
								<option value="">Nhân viên</option>			
								{foreach from=$lstProfile item = _oProfile}
								<option value="{$_oProfile.profile_id}">{$_oProfile.full_name}</option>
								{/foreach}
							</select>
							<select class="form-control form-control-sm form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
								<option value="">Tháng</option>			
								{foreach from=$list_months item = _month}
								<option value="{$_month}">T{$_month}</option>
								{/foreach}
							</select>
							<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
								{foreach from=$list_years item = _year}
								<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
								{/foreach}
							</select>
							{if $deviceType ne 'phone' && 1==2}
							<button type="button" onClick="$Core.dashboard.open_full(this, event)" tp="load_billing_chart" class="btn d-none d-lg-block btn-icon btn-outline-default"><i class='bx bx-windows'></i></button>
							{/if}
						</div>
					</div>
					{/if}
				</div>
			</div>
			<div class="dbx-card__body pb-0">
				<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing_chart" data-options='{ldelim}{rdelim}'>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
				</div>
			</div>
		</div>
		<div class="sticky">
			<div class="dbx-card h-100 mb-2">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="dbx-card__head justify-content-between flex-wrap">
					<div class="d-flex align-items-center gap-2 mb-2 mb-lg-0 me-2">
						<span class="dbx-card__ic"><i class="bx bx-calendar-check"></i></span>
						<h5 class="card-title mb-0">Doanh số dự án</h5>
					</div>
					<div class="d-flex flex-wrap gap-2">
						{if $deviceType eq 'phone'}
						<div class="dropdown">
							<button class="btn btn-icon p-0 h-auto" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="bx bx-dots-vertical-rounded"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
								<div class="p-3">
									<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
										<select class="form-control form-control-sm form-select" name="department" gId="{$gId}" onChange="$Core.dashboard.load_profile(this,event)"> 
											<option value="">Phòng</option>			
											{foreach from=$lstDepartMent item = _department}
											<option value="{$_department.property_id}">{$_department.title}</option>
											{/foreach}
											<option value="OTHER">Phòng tổng hợp</option>
										</select>
									</div>
									<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
										<select class="form-control form-control-sm form-select" name="profile_id" id="profile_{$gId}" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
											<option value="">Nhân viên</option>			
											{foreach from=$lstProfile item = _oProfile}
											<option value="{$_oProfile.profile_id}">{$_oProfile.full_name}</option>
											{/foreach}
										</select>
									</div>
									<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
										<select class="form-control form-control-sm form-select" name="month" gid="{$gId}" onchange="$Core.dashboard.reload(this,event)"> 
											{foreach from=$list_months item = _month}
											<option{if $_month eq $smarty.now|date_format:"%m"} selected{/if} value="{$_month}">Tháng {$_month}</option>
											{/foreach}	
										</select>
									</div>
									<div class="form-group  mb-2" role="group" aria-label="Sắp xếp">	
										<select class="form-control form-control-sm form-select" name="year" gid="{$gId}" onchange="$Core.dashboard.reload(this,event)">
											{foreach from=$list_years item = _year}
											<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
											{/foreach}
										</select>
									</div>
								</div>
							</div>
						</div>
						{else}
						<div class="p-right ox:w-100">
							<div class="input-group w-px-400 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
								<select class="form-control form-control-sm form-select" name="department" gId="{$gId}" 
								onChange="$Core.dashboard.load_profile(this,event)"> 
									<option value="">Phòng</option>			
									{foreach from=$lstDepartMent item = _department}
									<option value="{$_department.property_id}">{$_department.title}</option>
									{/foreach}
									<option value="OTHER">Phòng tổng hợp</option>
								</select>
								<select class="form-control form-control-sm form-select" name="profile_id" id="profile_{$gId}" 
								gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
									<option value="">Nhân viên</option>			
									{foreach from=$lstProfile item = _oProfile}
									<option value="{$_oProfile.profile_id}">{$_oProfile.full_name}</option>
									{/foreach}
								</select>
								<select class="form-control form-control-sm form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
									<option value="">Tháng</option>			
									{foreach from=$list_months item = _month}
									<option value="{$_month}">T{$_month}</option>
									{/foreach}
								</select>
								<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
								{if $deviceType ne 'phone' && 1==2}
								<button type="button" onClick="$Core.dashboard.open_full(this, event)" tp="load_billing_chart" class="btn d-none d-lg-block btn-icon btn-outline-default"><i class='bx bx-windows'></i></button>
								{/if}
							</div>
						</div>
						{/if}
					</div>
				</div>
				<div class="dbx-card__body">
					<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_revenue_chart" data-options='{ldelim}{rdelim}'>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
						<div class="animate-bg rounded-2 mb-2 h-px-15 w-100"></div>
					</div>
				</div>
			</div>
			<div class="dbx-card mb-2">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="dbx-card__head flex-wrap">
					<span class="dbx-card__ic"><i class="bx bx-calendar-check"></i></span>
					<h5 class="dbx-card__title mb-2 mb-lg-0 me-2">Lịch ký VBTT/HĐMB</h5>
					<div class="sign-sumline mb-2 mb-lg-0 me-2">
						<span class="sign-sum-pill"><b class="sign-sum-total">0</b> tổng</span>
						<span class="sign-sum-pill is-ok"><b class="sign-sum-signed">0</b> đã ký</span>
						<span class="sign-sum-pill is-no"><b class="sign-sum-unsigned">0</b> chưa ký</span>
					</div>
					<div class="d-flex flex-wrap">
						<input type="text" readonly="readonly" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)" name="sign_date" 
						class="datepicker is_icon form-control form-control-sm w-px-125" value="{$smarty.now|date_format:'%d/%m/%Y'}"  />
					</div>
				</div>
				<div class="dbx-card__body dbx-card__body--flush">
					<div class="overflow-x-auto text-nowrap{if $deviceType ne 'phone'} max-height-265{/if}">
						<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%" >
							<thead><tr>
								<th>Mã căn</th>
								<th>Dự án</th>
								<th class="text-center">Loại</th>
								<th>Sale phụ trách</th>
								<th>Admin</th>
								<th class="text-center">Giờ ký</th>
								<th class="text-center">Trạng thái</th>
							</tr></thead>
							<tbody gId="{$gId}" class="table-border-bottom-0 ajax  billing_calendar" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=calendar&act=load_calendar_today&call_from=_dashboard" 
								data-options='{ldelim}"sign_date":"{$smarty.now|date_format:'%d/%m/%Y'}"{rdelim}'>
								{section name=i loop=$list_preloaders max=8}
								<tr>
									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-15 mb-1 w-100 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card mb-2">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="card-title mb-0">
						<svg class="animated-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#C00000" viewBox="0 0 16 16">
							<path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"></path>
						</svg> Hoạt động check-in 
					</h5>
				</div>
				<div class="card-body ajax" data-url="/index.php?mod={$mod}&act=load_checkin_activity">
					<div class="form-row">
					{if $deviceType eq 'phone'}
						{section name=i loop=$list_preloader max=3}
						<div class="col-4">
							<div class="animate-bg rounded-2 w-100 h-px-100"></div>
						</div>
						{/section}
					{else}
						{section name=i loop=$list_preloader max=6}
						<div class="col-2">
							<div class="animate-bg rounded-2 w-100 h-px-175"></div>
						</div>
						{/section}
					{/if}
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-row mb-2">
				<div class="col-12 col-lg-6 mb-2 mb-lg-0">
					<div class="dbx-card h-100">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-trophy"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title">TOP 10 Sales</h5>
								<small class="dbx-card__sub">Người bán hàng tốt nhất</small>
							</div>
							<div class="dbx-card__filter">
								<select class="form-control form-control-sm form-select" name="month" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this, event)"> 
									<option value="">Tháng</option>			
									{foreach from=$list_months item = _month}
									<option value="{$_month}">Tháng {$_month}</option>
									{/foreach}
								</select>
								<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this, event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
							</div>
						</div>
						<div class="dbx-card__body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=top_billing" 
						gId="{$gId}" data-options='{ldelim}{rdelim}'>
							<div class="p-5 text-center w-100 h-100">
								<div class="p-5">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-6 flex-fill">
					<div class="dbx-card h-100">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-bar-chart-alt-2"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title">Thống kê</h5>
								<small class="dbx-card__sub">Theo loại giao dịch</small>
							</div>
							<div class="dbx-card__filter">
								<select class="form-control form-control-sm form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
									<option value="">Tháng</option>			
									{foreach from=$list_months item = _month}
									<option value="{$_month}">Tháng {$_month}</option>
									{/foreach}
								</select>
								<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this,event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
							</div>
						</div>
						<div class="dbx-card__body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=chart_billing_type" 
							gId="{$gId}" data-options='{ldelim}{rdelim}'>
							<div class="p-5 text-center w-100 h-100">
								<div class="p-5">
									<div class="p-5 text-muted">Loading...</div>
								</div>
							</div>
						</div>	
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-row mb-2">
				<div class="col-12 col-md-6 mb-2 mb-lg-0 flex-fill">
					{$core->getBlock('top_billing')}
				</div>
				<div class="col-12 col-md-6">
					{$core->getBlock('top_staff')}
				</div>
			</div>
		</div>
	</div>
	<!--/ Total Revenue -->
	<div class="col-12 col-md-12 col-lg-4 order-3 order-md-2">
		{$core->getBlock("home_billing_confirm")}
		<!-- End commission -->
		{if $deviceType ne 'phone'}
			{$core->getBlock('note_calendar')}
			{assign var = _gId value = $clsISO->getUniqid()}
			<div class="card ranking mb-2">
				<div class="card-body">
					{$core->getBlock('top_ranking', ['_gId' => $_gId])}
				</div>
			</div>
			{assign var = gId value = $clsISO->getUniqid()}
			{$core->getBlock('ranking_dept', ['gId' => $gId])}
			{assign var = gId value = $clsISO->getUniqid()}
			{$core->getBlock('top_ranker', ['gId' => $gId])} 
		{/if}
	</div>
</div>

{literal}<script>function ckinFilter(el){var b=$(el).closest(".dbx-card").find(".dbx-card__body");$Core.util.toggleIndicatior(1);$.post(b.attr("data-url"),{dept:el.value},function(r){$Core.util.toggleIndicatior(0);b.html(r.html);},"json");}</script>{/literal}
