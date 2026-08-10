<script type="text/javascript" src="{$URL_JS}/fullcalendar-6.1.15/index.global.min.js?v={$upd_version}"></script>

<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>

<div class="container-xxl flex-grow-1 pt-2 container-p-y calendar_page" style="overflow-y: auto">

	{assign var=uid value=$clsISO->getUniqid()}

	<form method="POST" class="frm_search_calendar">

		<input type="hidden" name="current_time" value="0" id="current_time" class="search_calender">

		<input type="hidden" name="start" value="0" id="start_time" class="search_calender">

		<input type="hidden" name="end" value="0" id="end_time" class="search_calender">

		<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">

			<div class="vRHjwnrkGa d-flex gap-2 mb-2 mb-lg-0 xs:w-100">

				<a href="/giao-dich.html" class="back mr-1 goToPage">

					<img src="{$smarty.const.ICON_BACK}" />

				</a>

				<div class="ysXwpeiJqL d-flex flex-column">

					<h4 class="fw-bold mb-0">Lịch ký HĐMB & VBTT</h4>

					<span class="text-muted">Tổng hợp lịch ký giao dịch</span>

				</div>

			</div>

			<div class="d-flex gap-2 align-items-center flex-wrap justify-content-end">	

				<div class="btn-group d-flex xs:w-100" role="group" aria-label="Sắp xếp">

					<input type="radio" class="btn-check js__filter-date" uid="{$uid}" onChange="$Core.calendar.do_reload(this, event)" 

						name="date_type" id="all_{$uid}" value="_all" checked="checked">

					<label data-toggle="ripple" class="btn btn-outline-default" for="all_{$uid}">Tất cả</label>

					<input type="radio" class="btn-check js__filter-date" uid="{$uid}" onChange="$Core.calendar.do_reload(this, event)" 

						name="date_type" id="HDMB_{$uid}" value="_contract">

					<label data-toggle="ripple" class="btn btn-outline-default" for="HDMB_{$uid}">HĐMB</label>

					<input type="radio" class="btn-check js__filter-date" uid="{$uid}" onChange="$Core.calendar.do_reload(this, event)" 

						name="date_type" id="VBTT_{$uid}" value="_text">

					<label data-toggle="ripple" class="btn btn-outline-default" for="VBTT_{$uid}">VBTT</label>

				</div>

				<div class="btn-group dropdown" bis_skin_checked="1">

					<button type="button" class="btn btn-icon btn-outline-default dropdown-toggle hide-arrow" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>

					<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">

						<div class="p-3" bis_skin_checked="1">

							<div class="form-group mb-2">

								<label class="form-label mb-1">Dự án</label>

								<div class="clearfix"></div>

								<select type="select" placeholder="Chọn dự án" class="form-control search_calender iso-select2" name="project_id" 

								onChange="$Core.calendar.do_reload(this,event);" is_project_dir="{$is_project_dir}" uid="{$uid}" data-width="100%">

									<option value="0">Chọn dự án</option>

									{if !empty($arr_projects)}

										{foreach from=$arr_projects item = _oI}

										<option value="{$_oI.project_id}">{$_oI.title}</option>

										{/foreach}

									{/if}

								</select>

							</div>		

							<div class="form-group">

								<label class="form-label mb-1">Loại hình</label>

								<select type="select" class="form-control search_calender iso-select2" name="billing_type" 

								onChange="$Core.calendar.do_reload(this,event);" uid="{$uid}" data-width="100%">

									<option value="0">Chọn loại hình</option>

									{if !empty($arr_billing_type)}

										{foreach from=$arr_billing_type item = _oI}

										<option value="{$_oI.property_id}">{$_oI.title}</option>

										{/foreach}

									{/if}

								</select>

							</div>

							{if $clsISO->checkPermissionGroup('ADMIN_PROJECT')}

							<div class="form-group mt-2">

								<label class="form-label mb-1">Lịch ký của tôi</label>

								<div class="form-check form-switch">

									<input class="form-check-input search_calender w-px-40 mr-2" type="checkbox" id="{$uid}" onChange="$Core.calendar.do_reload(this,event);" name="is_me" value="1">

									<label class="form-check-label" for="{$uid}">Hiển thị lịch ký của tôi</label>

								</div>

							</div>

							{/if}

						</div>

					</div>

				</div>

			</div>

		</div>

	</form>

    <!-- Basic Bootstrap Table -->

	{assign var=gId value = $clsISO->getUniqid()}

	<div class="form-row">

		<div class="col-12 col-lg-8 col-xxl-9 order-lg-2">

			{if $deviceType eq 'phone'}

			<div class="dashboard-panel-item dashboard-panel-item--full">

				<div class="panel border-0 no-shadow panel-default">

					<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">

						<h3 class="panel-title">Lịch ký <span class="text-muted fs-11">(<strong class="text-main number_total">0</strong> lịch ký)</span></h3>

						<div class="w-px-125">

							<input gId="{$gId}" type="text" data-bind="change" class="form-control search_field datepick is_icon text-fs-11" 

							holderg="_desktop" name="sign_date" data-field="sign_date" onchange="$Core.dashboard.reload(this, event)" 

							value="{$smarty.now|date_format:'%d/%m/%Y'}" readonly />

						</div>

					</div>						

					<div class="panel-body px-0 scroll-y-auto table-container no-shadow overflow-x-auto" >

						<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%" >

							<thead><tr>

								<th class="align-center" style="background:#FFF !important;">Mã căn</th>

								<th class="align-center">Admin</th>

								<th class="align-center text-right">T.Trạng</th>

								<th class="align-center text-right">Loại</th>

								<th class="align-center text-right">Ghi chú</th>

							</tr></thead>

							<tbody gId="{$gId}" class="table-border-bottom-0 ajax loaded billing_calendar" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=calendar&act=load_calendar_today" data-options="{}">

								{section name=i loop=$list_preloaders max=10}

								<tr>

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

			{/if}

			<div class="sticky">

				<div class="card mb-2">

					<div class="card-body">

						<div id="calendar" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=calendar&act=load_calendar" 

						data-options='{ldelim}"gid":"{$uid}"{rdelim}'>

							<div class="table-wrapper">

								<table width="100%" class="table table-bordered">

									<thead><tr>

										<th class="align-center text-center h-px-40">CN</th>

										<th class="align-center text-center h-px-40">T2</th>

										<th class="align-center text-center h-px-40">T3</th>

										<th class="align-center text-center h-px-40">T4</th>

										<th class="align-center text-center h-px-40">T5</th>

										<th class="align-center text-center h-px-40">T6</th>

										<th class="align-center text-center h-px-40">T7</th>

									</tr></thead>

									{section name=i loop=$list_preloaders max=5}

									<tr>

										{section name=k loop=$list_preloaders max=7}

										<td class="h-px-150">

											<div class="animate-bg h-px-20 mb-1 w-100 rounded-2"></div>

											<div class="animate-bg h-px-20 mb-1 w-100 rounded-2"></div>

											<div class="animate-bg h-px-20 w-100 rounded-2"></div>

										</td>

										{/section}

									</tr>

									{/section}

								</table>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="col-12 col-lg-4 col-xxl-3 order-sm-2 order-lg-1" >

			{if $deviceType ne 'phone'}

			<div class="dashboard-panel-item dashboard-panel-item--full">

				<div class="panel border-0 no-shadow panel-default">

					<div class="panel-heading d-flex justify-content-between align-items-center">

						<h3 class="panel-title">Lịch ký <span class="text-muted text-fs-11">(<strong class="text-main number_total">0</strong> lịch ký)</span></h3>

						<div class="w-px-125">

							<input type="text" gId="{$gId}" data-bind="change" class="form-control search_field datepick is_icon text-fs-11" 

							holderg="_desktop" name="sign_date" data-field="sign_date" onchange="$Core.dashboard.reload(this, event)" 

							value="{$smarty.now|date_format:'%d/%m/%Y'}" readonly>

						</div>

					</div>						

					<div class="panel-body px-0 scroll-y-auto">

						<div class="table-container no-shadow overflow-x-auto">

							<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%" >

								<thead><tr>

									<th class="align-center" style="background: #FFF !important;">Mã căn</th>

									<th class="align-center">Admin</th>

									<th class="align-center text-right">T.Trạng</th>

									<th class="align-center text-right">Loại</th>

									<th class="align-center text-right">Ghi chú</th>

								</tr></thead>

								<tbody gId="{$gId}" class="table-border-bottom-0 ajax loaded billing_calendar" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=calendar&act=load_calendar_today" data-options="{}">

									{section name=i loop=$list_preloaders max=10}

									<tr>

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

			</div>	

			{/if}

			<div class="dashboard-panel-item dashboard-panel-item--full">

				<div class="panel border-0 no-shadow panel-default">

					<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">

						<h3 class="panel-title">Thống kê admin</h3>

						<div class="dropdown">

							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">

								<i class="bx bx-dots-vertical-rounded"></i>

							</button>

							<div class="dropdown-menu lst_time">

								{foreach from = $list_filters key = key item = text}

								<a href="javascript:void(0);" onclick="$Core.calendar.timer_click(this,event);" holderG="{$key}" class="dropdown-item js_choose-time{if $key eq 'THIS_WEEK'} active{/if}">{$text}</a>

								{/foreach}

							</div>

						</div>

					</div>						

					<div class="panel-body px-0">

						<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">

							<thead><tr>

								<th class="align-center">Admin</th>

								<th class="align-center text-center">HĐMB</th>

								<th class="align-center text-center">VBTT</th>

							</tr></thead>

							<tbody class="table-border-bottom-0 billing_filter ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=calendar&act=load_total_calendar" data-options="{}">

							</tbody>

						</table>

					</div>

				</div>

			</div>			

			<div class="dashboard-panel-item dashboard-panel-item--full mb-2">

				<div class="panel border-0 no-shadow panel-default">

					<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">

						<h3 class="panel-title">Thống kê lịch ký</h3>						

						<div class="dropdown">

							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">

								<i class="bx bx-dots-vertical-rounded"></i>

							</button>

							<div class="dropdown-menu lst_time">

								{foreach from = $list_filters key = key item = text}

								<a href="javascript:void(0);" onclick="$Core.calendar.timer_click(this,event);" holderG="{$key}" 

									class="dropdown-item js_choose-time{if $key eq 'THIS_WEEK'} active{/if}">{$text}</a>

								{/foreach}

							</div>

						</div>

					</div>						

					<div class="panel-body px-0">

						<div id="chart_billing_type" class="billing_filter h-px-300 w-100 ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=calendar&act=load_chart_billing" data-options='{ldelim}{rdelim}'>

							<div class="p-5 text-center">

								<div class="p-5 text-muted">Loading...</div>

							</div>

						</div>

					</div>

				</div>

			</div>	

		</div>

	</div>

</div>

{if $clsISO->_DEV()}

	<script>

		var DEV=1;

	</script>

{/if}

{literal}

<style type="text/css">

	.fc .fc-daygrid-event{

		z-index:auto !important;

	}

	.fc .fc-daygrid-day-number {

		width: 100%;

	}

</style>

<script type="text/javascript">

	$(function(){

		$Core.calendar.load_calendar('{/literal}{$clsISO->getUniqid()}{literal}',{});

	});

</script>

{/literal}