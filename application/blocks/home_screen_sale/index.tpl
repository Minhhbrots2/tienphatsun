<div class="form-row">
	<div class="col-12 col-lg-8 mb-2 mb-lg-0">
		<div class="sticky">		
			<div class="card dsx-shell mb-2">
				<div class="dsx">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="dsx-head">
						<span class="dsx-eyebrow"><i class="bx bx-line-chart"></i>Doanh số bán hàng</span>
						<div class="input-group-control">
							<div class="input-group d-flex w-px-175" role="group" aria-label="Lọc thời gian">
								<select class="form-control form-control-sm form-select" name="month" gId="{$gId}"
								onChange="$Core.dashboard.reload(this,event)">
									<option value="">Tháng</option>
									{foreach from=$list_months item = _month}
									<option value="{$_month}">Tháng {$_month}</option>
									{/foreach}
								</select>
								<select class="form-control form-control-sm form-select" name="year" gId="{$gId}"
								onChange="$Core.dashboard.reload(this,event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
							</div>
						</div>
					</div>
					<div class="dsx-grid ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_person_dept_sales"
						data-options='{ldelim}{rdelim}'>
						<div class="dsx-seg">
							<div class="animate-bg rounded-2 h-px-35 w-100 mb-2"></div>
							<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
							<div class="animate-bg rounded-2 h-px-20 w-100"></div>
						</div>
						<div class="dsx-seg">
							<div class="animate-bg rounded-2 h-px-35 w-100 mb-2"></div>
							<div class="animate-bg rounded-2 h-px-20 w-100 mb-2"></div>
							<div class="animate-bg rounded-2 h-px-20 w-100"></div>
						</div>
					</div>
				</div>
			</div>	
			<div class="dbx-card mb-2">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="dbx-card__head">
					<span class="dbx-card__ic"><i class="bx bx-receipt"></i></span>
					<div class="dbx-card__ttl">
						<h5 class="dbx-card__title mb-0">Giao dịch {$department_name}</h5>
						<small class="dbx-card__sub">Tăng trưởng số lượng giao dịch</small>
					</div>
					<div class="input-group-control">
						<div class="input-group d-flex w-px-175" role="group" aria-label="Sắp xếp">
							<select class="form-control form-control-sm form-select" name="month" gId="{$gId}" 
							onChange="$Core.dashboard.reload(this,event)"> 
								<option value="">Tháng</option>			
								{foreach from=$list_months item = _month}
								<option value="{$_month}">Tháng {$_month}</option>
								{/foreach}
							</select>
							<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" 
							onChange="$Core.dashboard.reload(this,event)">
								{foreach from=$list_years item = _year}
								<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
								{/foreach}
							</select>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing_chart" 
						data-options='{ldelim}{rdelim}'>
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
			<div class="dbx-card mb-2">
				<div class="dbx-card__head">
					<span class="dbx-card__ic"><i class="bx bx-group"></i></span>
					<h5 class="dbx-card__title mb-0">Hoạt động check-in</h5>
					<a href="{$PCMS_URL}/net-dep-lao-dong.html" title="Xem tất cả" class="btn btn-icon btn-sm btn-link rounded-pill">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
				</div>
				<div class="card-body ajax" data-bind="{$uid}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_checkin_activity&holderG=_sale_director" data-options="{ldelim}{rdelim}">
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
			<div class="dbx-card mb-2">
				<div class="dbx-card__head">
					<span class="dbx-card__ic"><i class="bx bx-transfer-alt"></i></span>
					<h5 class="dbx-card__title mb-0">Giao dịch mới nhất</h5>
					<a href="{$clsISO->getLink('billing')}" class="btn btn-icon btn-sm btn-link rounded-pill" title="Xem giao dịch">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
				</div>
				<div class="dbx-card__body dbx-card__body--flush">
					<div class="table-container overflow-x-auto no-shadow text-nowrap">
						<table cellpadding="0" cellspacing="0" class="table text-nowrap">
							<thead><tr>
								<th class="align-center h-px-35 bg-lighter">Mã căn</th>
								<th class="align-center h-px-35 bg-lighter">Ngày cọc</th>
								<th class="align-center h-px-35 bg-lighter">Sale bán</th>
								<th class="align-center h-px-35 bg-lighter">Dự án</th>
								<th class="align-center h-px-35 bg-lighter">Phân khu</th>
								<th class="align-center h-px-35 bg-lighter">Loại hình</th>
								<th class="align-center h-px-35 bg-lighter text-right">Tổng tiền</th>
							</tr></thead>
							<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing" 
								data-options='{ldelim}"call_from":"_dashboard"{rdelim}'>
								{section name=i loop=$list_preloader}
								<tr>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-xxl-6 mb-2 mb-xxl-0">
					<div class="dbx-card h-100">
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-history"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title mb-1">Lịch sử tra cứu</h5>
								<small class="dbx-card__sub">Lịch sử tra cứu gần nhất</small>
							</div>
							<a href="{$clsISO->getLink('log-sale')}" class="btn btn-icon btn-sm btn-link rounded-pill">
								<i class="bx bx-link-external text-fs-14 text-muted"></i>
							</a>
						</div>
						<div class="dbx-card__body dbx-card__body--flush">
							<div id="history_search" class="table-container no-shadow overflow-auto max-height-400">
								<table cellpadding="0" cellspacing="0" class="table table-bordered text-nowrap">
									<thead class="position-sticky top-0 zindex-3"><tr>
										<th class="align-center h-px-35 bg-lighter">Nhân viên</th>
										<th class="align-center h-px-35 bg-lighter">Thời gian</th>
										<th class="align-center h-px-35 bg-lighter">Mã căn</th>
									</tr></thead>
									<tbody class="table-border-bottom-0 ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_sale_logs" 
										data-option="{ldelim}{rdelim}">
										{section name=i loop=$list_preloader}
										<tr>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
										</tr>
										{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- <div class="card ">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
							<div class="card-title ox:sm-1">
								<h5 class="mb-1 me-2">Vắng mặt {$department_name}</h5>
								<small class="d-block text-nowrap">Nhấn 
									<a href="javascript:void(0)" onclick="$Core.worktime.open(this, event)"><u>Thêm</u></a> bắt đầu thêm báo cáo
								</small>
							</div>
							<div class="p-right">
								<div class="input-group d-flex w-px-175" role="group" aria-label="Sắp xếp">
									<select class="form-control form-select" name="month" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)"> 
										<option value="">Tháng</option>			
										{foreach from=$list_months item = _month}
										<option value="{$_month}">Tháng {$_month}</option>
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
						<div class="card-body">
							<div class="overflow-y-auto">
								<table class="table" width="100%" cellpadding="0" cellspacing="0">
									<thead><tr>
										<th class="align-center">Nhân viên</th>
										<th width="68px" class="align-center text-center">Tổng</th>
										<td width="30px" class="align-center"></td>
									</tr></thead>
									<tbody class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_report_worktime" data-options="{ldelim}{rdelim}">
										{section name=i loop=$list_preloader}
										<tr>
											<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
										</tr>
										{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div> -->
				</div>
				<div class="col-12 col-xxl-6 mb-2 mb-xxl-0">
					<div class="dbx-card">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-group"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title mb-1 text-nowrap">Nhân viên {$department_name}</h5>
								<small class="d-block text-nowrap">Số nhân viên {$department_name}: 
									(<strong class="text-main total_staffs">0</strong>)
								</small>
							</div>
							<div class="p-right">
								<div class="input-group d-flex w-px-150" role="group" aria-label="Sắp xếp">
									<select class="form-control form-control-sm form-select" name="month" gId="{$gId}" 
									onChange="$Core.dashboard.reload(this,event)"> 
										<option value="">Tháng</option>			
										{foreach from=$list_months item = _month}
										<option value="{$_month}">Tháng {$_month}</option>
										{/foreach}
									</select>
									<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" 
									onChange="$Core.dashboard.reload(this,event)">
										{foreach from=$list_years item = _year}
										<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
						<div class="dbx-card__body dbx-card__body--flush">
							<div class="table-container no-shadow max-height-400 overflow-auto" >
								<table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
									<thead class="position-sticky top-0 zindex-3"><tr>
										<th class="align-center bg-lighter h-px-35">Nhân viên</th>
										<th class="align-center bg-lighter h-px-35 text-center">Số GD</th>
										<th class="align-center bg-lighter h-px-35">Doanh số</th>
									</tr></thead>
									<tbody class="text-nowrap ajax" gId="{$gId}" data-options="{ldelim}{rdelim}" 
										data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_staffs">
										{section name=i loop=$list_preloader max=10}
										<tr>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
											<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
										</tr>
										{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-xxl-6 mb-2 mb-xxl-0">
					{$core->getBlock('top_billing')}
				</div>
				<div class="col-12 col-xxl-6">
					{$core->getBlock('top_staff')}
				</div>
			</div>
		</div>
	</div>
	<!--/ Conversion rate -->
	<div class="col-12 col-lg-4">
		<div class="mb-2">
			{$core->getBlock('home_course')}
		</div>
		<!-- Xác nhận thay đổi GD -->
		{$core->getBlock("home_billing_confirm")}
		<!--- Target -->
		{$core->getBlock("target_sales")}
		<!-- Meta -->
		{if $deviceType ne 'phone'}
			{$core->getBlock('note_calendar')}
			{*{$core->getBlock('ranking_group')}*}
			<div class="card ranking mb-2">
				<div class="card-body">
					{$core->getBlock('top_ranking')}
				</div>
			</div>
			{assign var = gId value = $clsISO->getUniqid()}
			{$core->getBlock('ranking_dept', ['gId' => $gId])}
			<!-- {assign var = gId value = $clsISO->getUniqid()}
			{$core->getBlock('top_ranker', ['gId' => $gId])} -->	
		{/if}
		<div class="dbx-card mt-2">
			<div class="dbx-card__head">
				<h5 class="dbx-card__title d-flex align-items-center mb-0">
					<img class="mr-2" src="{$URL_IMAGES}/birthday.png" width="20" /> 
					<span>Chúc mừng sinh nhật</span>
				</h5>
			</div>
			<div class="card-body">
				<div id="birthday_staffs" class="overflow-hidden">
					<div class="ajax" data-bind="{$toId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=staff_birthday" 
						data-options='{ldelim}"department_id":{$oneProfile.department_id}{rdelim}'>
						<div class="loader text-center py-3">
							<img src="{$URL_IMAGES}/loading.gif" width="66px" />
							<p>Loading...</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.avatar-bg{ background-size: cover;}
</style>
{/literal}