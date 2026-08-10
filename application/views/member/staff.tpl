<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<form method="POST" class="w-100">
		<div class="d-flex flex-wrap mb-2 align-items-center justify-content-between">
			<div class="mb-2 mb-lg-0">
				<h4 class="fw-bold mb-1">Danh sách nhân viên</h4>
				<p class="text-muted mb-0">Tộng cộng 
					<strong class="text-main total_record">{$total_record}</strong> nhân viên </p>
			</div>
			<div class="d-flex justify-content-end xs:w-100 gap-1 align-items-center">
				<div class="input-group">
					<input type="text" name="keyword" value="{$keyword}" class="form-control no-focus" 
					placeholder="Nhập từ khoá..." />
					<div class="btn-group dropdown">
						<button type="button" class="btn btn-icon btn-outline-default hide-arrow dropdown-toggle no-radius-left no-border-left" data-bs-toggle="dropdown" data-toggle="ripple" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
						<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-menu-end w-px-350" 
						data-popper-placement="bottom-end">
							<div class="p-3">
								{if $is_access_full eq '1'}
									<div class="form-froup form-row mb-2">
										<div class="col-6 flex-fill">
											<div class="form-label mb-1">Phòng ban</div>
											<select name="department_id" class="iso-select2" data-width="100%" 
											data-placeholder="Phòng ban" data-allow-clear="false" onChange="$Core.member.select_team(this, event)">
												{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$department_id,'Phòng ban')}
											</select>
										</div>
										<div class="col-6 d-none">
											<div class="form-label mb-1">Đội/Nhóm</div>
											<select name="team_id" class="iso-select2" data-width="100%" 
											data-placeholder="Đội nhóm" data-allow-clear="false">
												<option value="0">Đội nhóm</option>
												{if !empty($department_id) && !empty($list_teams)}
													{foreach from=$list_teams item = _oTeam}
													<option value="{$_oTeam.property_id}"{if $_oTeam.property_id eq $team_id} selected{/if}>{$_oTeam.title}</option>
													{/foreach}
												{/if}
											</select>
										</div>
									</div>
									{if !empty($lstGroupProfile)}
									<div class="form-froup mb-2">
										<div class="form-label mb-1">Nhóm nhân viên</div>
										<select name="group_profile_id" class="iso-select2" data-width="100%" 
										data-placeholder="Nhóm nhân viên" data-allow-clear="false">
											<option value="0">Nhóm nhân viên</option>
											{foreach from=$lstGroupProfile item=_oGroup}
												<option value="{$_oGroup.group_profile_id}">{$_oGroup.title}</option>
											{/foreach}
										</select>
									</div>
									{/if}
									<div class="form-froup mb-2">
										<div class="form-label mb-1">Vai trò/ Quyền hạn</div>
										<select data-width="100%" data-placeholder="Vai trò/ Quyền hạn" 
										data-allow-clear="false" name="role_ids[]" class="iso-select2" multiple>
											<option value="0">Vai trò/ Quyền hạn</option>
											{$clsProperty->getSelectByPropertyV2('_ROLE',$role_ids,'Vai trò/ Quyền hạn')}
										</select>
									</div>
								{elseif $is_dir_sales eq '1'}
									<div class="form-froup mb-2 d-none">
										<div class="form-label mb-1">Đội/Nhóm</div>
										<select name="team_id" class="iso-select2" data-width="100%" 
										data-placeholder="Đội nhóm" data-allow-clear="false">
											<option value="0">Đội nhóm</option>
											{if !empty($list_teams)}
												{foreach from=$list_teams item = _oTeam}
												<option value="{$_oTeam.property_id}"{if $_oTeam.property_id eq $team_id} selected{/if}>{$_oTeam.title}</option>
												{/foreach}
											{/if}
										</select>
									</div>
								{/if}
								<div class="form-row">
									<div class="col-12 mb-2">
										<div class="form-label mb-1">Tình trạng</div>
										<select name="status_ids[]" class="iso-select2" data-width="100%" data-placeholder="Tình trạng" data-allow-clear="false" multiple >
											{$clsProperty->getSelectByPropertyV2('_STATUS_STAFF',$status_ids,'Tình trạng')}
										</select>
										
									</div>
									<div class="col-12 mb-2">
										<div class="form-label mb-1">Lựa chọn ngày</div>
										<select class="form-control search_field form-select" data-field="date_field" name="date_field">
											<option{if $date_field eq 'reg_date'} selected{/if} value="reg_date">Ngày tạo</option>
											<option{if $date_field eq 'start_date'} selected{/if} value="start_date">Ngày vào làm việc</option>
											<option{if $date_field eq 'birthday'} selected{/if} value="birthday">Ngày sinh nhật</option>
										</select>
									</div>
								</div>
								<hr class="my-2">
								<div class="form-row mb-2">
									{assign var = uid value = $clsISO->getUniqid()}
									<div class="col-6 col-md-6">
										<div class="form-floating">
											<input type="date" class="form-control search_field" id="{$uid}" 
											data-field="start_date" value="{$start_date}" name="start_date" placeholder="dd/mm/yy" aria-describedby="uid">
											<label for="uid">Từ ngày</label>
										</div>
									</div>
									<div class="col-6 col-md-6">
										<div class="form-floating">
											<input type="date" class="form-control search_field" data-field="to_date" 
											id="{$uid}" name="to_date" value="{$to_date}" placeholder="dd/mm/yy" aria-describedby="{$uid}" >
											<label for="{$uid}">Tới ngày</label>
										</div>
									</div>
								</div>
								<hr class="my-2">
								<div class="form-group">
									<button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
									<button type="reset" class="btn btn-warning"><i class="bx bx-refresh"></i> Xóa</button>
								</div>
							</div>
						</div> 
					</div>
				</div>
				<input type="hidden" name="filter" value="filter" />
				<button data-toggle="ripple" type="submit" class="btn btn-icon btn-outline-default no-wrap">
					<i class="bx bx-search"></i> 
				</button>
				{if $clsISO->checkPermission('add_staff')}
				<button type="button" class="btn btn-success{if $deviceType eq 'phone'} btn-icon{/if} js__staff-new text-nowrap" onClick="$Core.member.open(this,event)" data-type="open" data-group_id="0"><i class="bx bx-plus"></i>{if $deviceType ne 'phone'} Thêm{/if}</button>
				{/if}
				<div  class="btn-group dropdown">
					<button data-toggle="ripple" type="button" class="btn btn-icon btn-outline-default hide-arrow dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-cog"></i></button>
					<ul class="dropdown-menu" data-popper-placement="bottom-end">
						<li><a class="dropdown-item cursor-pointer js__group-manager" onClick="$Core.member.manage_group(this,event)" 
							data-type="open" data-group_id="0">
							<div class="d-flex gap-2">
								<i class="bx bx-group mt-2"></i>
								<div class="d-flex flex-column">
									<strong>Quản lý nhóm</strong>
									<span class="text-muted text-fs-12">Đội nhóm nội bộ</span>
								</div>
							</div>
						</a></li>
						<li><a class="dropdown-item cursor-pointer" onClick="$Core.member.trans_to_dept(this,event)">
							<div class="d-flex gap-2">
								<i class="bx bx-move-horizontal mt-2"></i>
								<div class="d-flex flex-column">
									<strong>Chuyển phòng ban</strong>
									<span class="text-muted text-fs-12">Chuyển nhân sự các phòng ban</span>
								</div>
							</div>
						</a></li>
						<!-- <hr class="dropdown-divider" />
						<li><a class="dropdown-item cursor-pointer" href="/member/report.html">
							<div class="d-flex gap-2">
								<i class="bx bx-bar-chart-alt mt-2"></i>
								<div class="d-flex flex-column">
									<strong>Báo cáo nhân sự</strong>
									<span class="text-muted text-fs-12">Báo cáo tổng hợp nhân sự</span>
								</div>
							</div>
						</a></li>
						<hr class="dropdown-divider" />
						<li><a class="dropdown-item cursor-pointer" href="/member/export-loyalty.html">
							<div class="d-flex gap-2">
								<i class="bx bx-bar-chart-alt mt-2"></i>
								<div class="d-flex flex-column">
									<strong>Export</strong>
									<span class="text-muted text-fs-12">Điểm Loyalty</span>
								</div>
							</div>
						</a></li> -->
					</ul>
				</div>
			</div>
		</div>
	</form>
    <!-- Basic Bootstrap Table -->
    <div class="card">
		<div class="card-body">
			{if $is_access_full eq '1'}
			<div class="briefs mb-3 gap-2 gap-xxl-3 d-flex flex-wrap">
				<div class="brief-item a1a bg-orange">
					<p class="fs-16 mb-2">Tổng nhân viên</p>
					<h3 class="fs-32 mb-1 text-white">{$arr_totals.total_staff}</h3>
				</div>
				<div class="brief-item a2a bg-azure">
					<p class="fs-16 mb-2">Đang làm việc</p>
					<h3 class="fs-32 mb-0 text-white">{$arr_totals.total_on}</h3>
				</div>
				<div class="brief-item a3a bg-cyan">
					<p class="fs-16 mb-2">Đã nghỉ</p>
					<h3 class="fs-32 mb-0 text-white">{$arr_totals.total_off}</h3>
				</div>
				<div class="brief-item a4a bg-danger">
					<p class="fs-16 mb-2">Khối kinh doanh</p>
					<h3 class="fs-32 mb-0 text-white">{$arr_totals.total_sale}</h3>
				</div>
				<div class="brief-item a5a bg-purple">
					<p class="fs-16 mb-2">Khối văn phòng</p>
					<h3 class="fs-32 mb-0 text-white">{$arr_totals.total_bo}</h3>
				</div>
				<div class="brief-item a6a bg-green">
					<p class="fs-16 mb-2">Gần nhất</p>
					<h3 class="fs-32 mb-0 text-white">{$arr_totals.total_growth}</h3>
				</div>
			</div>
			<hr class="my-3" />
			{/if}
			<div class="table-container overflow-x-auto text-nowrap no-shadow">
				<table cellpadding="0" cellspacing="0" class="table dragable table-staff table-striped table-borderd">
					<thead><tr>
						{if $deviceType ne 'phone'}
						<th class="algin-center h-px-35 bg-lighter" width="50px">Mã NV</th>{/if}
						<th class="algin-center h-px-35 bg-lighter text-left">Họ và tên</th>
						<th class="algin-center h-px-35 bg-lighter text-left">Phòng ban</th>
						<th class="algin-center h-px-35 bg-lighter text-left">Vai trò</th>
						<!--<th class="algin-center h-px-35 bg-lighter text-right w-px-200">Doanh số</th>
						<th class="algin-center h-px-35 bg-lighter text-left">GD gần nhất</th>-->
						<!-- <th class="algin-center h-px-35 bg-lighter text-left">FPoint</th> -->
						<th class="algin-center h-px-35 bg-lighter text-left">Ngày vào</th>
						<th class="algin-center h-px-35 bg-lighter text-left">T.Trạng</th>
						<th class="algin-center h-px-35 bg-lighter text-left">Ngày nghỉ việc</th>
						{if $permiss_login eq '1'}
						<th class="algin-center h-px-35 bg-lighter text-center">Login</th>
						{/if}
						<th class="algin-center h-px-35 bg-lighter text-left">Ngày tạo</th>
						{if $clsISO->_DEV()}
						<th class="algin-center h-px-35 bg-lighter text-left">Version</th>
						{/if}
						<th class="algin-center h-px-35 bg-lighter text-left w-px-50"></th>
					</tr></thead>
					<tbody class="table-border-bottom-0">
						{section name=i loop=$list_staffs}
						{assign var = _oneStaff value = $list_staffs[i]}
						{assign var = _profile_id value = $_oneStaff.profile_id}
						{assign var = _more_information value = $_oneStaff.more_information}
						<tr class="trUser" profile_id="{$_profile_id}">

							{if $deviceType ne 'phone'}
							<td class="text-center">{$list_staffs[i].code}</td>{/if}
							<td class="text-left">
								<a href="javascript:void(0);" onClick="$Core.member.view_profile(this, event)" 
								profile_id="{$_profile_id}" class="d-flex gap-1 align-items-center">
									<div class="d-none d-lg-block avatar avatar-xxs position-relative rounded-pill">
										<img class="rounded-pill" src="{$clsProfile->getAvatar($_profile_id,$_oneStaff,40,40)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" />
										{$clsProfile->get_icon_verified($_profile_id, $_oneStaff.more_information)}
									</div>
									<strong>{$list_staffs[i].full_name}</strong> {$list_staffs[i].state_name}
								</a>
							</td>
							<td class="text-left">{$_more_information.department_name}
							</td>
							<td class="text-left">{$_more_information.role_name}</td>
							<!-- <td class="text-right">
								{if $list_staffs[i].total_billings ne '0'}
									{$list_staffs[i].total_billings}
								{else}
									{$list_staffs[i].total_billings} {$clsISO->getRate()}
								{/if}
							</td>
							<td class="text-left">{$list_staffs[i].deposit_date}</td>
							{if $clsISO->checkInArray($smarty.const._PROFILE_SUPPER_ID, $_profile_id)}
							<td staff_id="{$_profile_id}" class="text-left cursor-pointer text-danger">
								<img src="{$URL_IMAGES}/point.png" width="12px" />
								<strong>Ultimate</strong>
							</td>
							{else}
							<td onClick="$Core.global.open_Lpoint(this, event)" staff_id="{$_profile_id}" 
								class="text-left cursor-pointer text-danger">
								<img src="{$URL_IMAGES}/point.png" width="12px" />
								<strong>{$list_staffs[i].total_Lpoint}</strong>
							</td>
							{/if}-->	
							<td class="text-left">
								{if !empty($list_staffs[i].start_date)}
									<i class="material-icons-outlined">schedule</i>
									{$clsISO->convertTimeToText($list_staffs[i].start_date)}
								{else}
									--
								{/if}
							</td>
							<td class="text-left">
								<span class="badge bg-label-{if $list_staffs[i].status_id eq $smarty.const._STATUS_STAFF_OFF_ID}danger{else}primary{/if} me-1">
									{$list_staffs[i].status_name}
								</span>
							</td>
							<td class="text-left">
								{if !empty($list_staffs[i].end_date) && $list_staffs[i].status_id eq $smarty.const._STATUS_STAFF_OFF_ID}
									<i class="material-icons-outlined">schedule</i>
									{$clsISO->convertTimeToText($list_staffs[i].end_date)}
								{else}
									--
								{/if}
							</td>
							{if $permiss_login eq '1' || $profile_id eq 289}
							<td class="text-center">
								<form action="{$PCMS_URL}/login-redirect.html" target="_blank" method="post" enctype="multipart/form-data">
									<input type="hidden" name="login_step" value="login">
									<input type="hidden" name="USER" value="{$list_staffs[i].user_name}">
									<input type="hidden" name="PASSWORD" value="{$list_staffs[i].user_pass}">
									<button{if $list_staffs[i].status_id eq $smarty.const._STATUS_STAFF_OFF_ID} disabled{/if} class="btn btn-xs btn-outline-success">{$clsISO->makeIcon('bx-play', 'Đăng nhập')}</button>
								</form>
							</td>
							{/if}
							<td class="text-left">
								<i class="material-icons-outlined">more_time</i>
								{$clsISO->convertTimeToText($list_staffs[i].reg_date, true)}
							</td>
							{if $clsISO->_DEV()}
							<td class="text-center">
								<label class="switch">
									<input type="checkbox"{if $_more_information.is_active_new_version eq '1'} checked="checked"{/if} onChange="$Core.member.active_new_version(this, event)" profile_id="{$_profile_id}" value="1">
									<span class="slider round"></span>
								</label>
							</td>
							{/if}
							<td class="text-center w-px-50">
								<a class="btn d-flex align-items-center justify-content-center btn-outline-default btn-icon btn-sm" 
									onClick="$Core.member.view_profile(this, event)" profile_id="{$_profile_id}" href="javascript:void(0);">
									<i class="bx bxs-show"></i>
								</a>
								{if $clsISO->checkInArray($smarty.const._PROFILE_SUPPER_ID, $profile_id)}
								<!-- <a class="dropdown-item" onClick="$Core.member.recalc_loyalty(this, event)" profile_id="{$_profile_id}" href="javascript:void(0);">
									<i class="bx bx-refresh me-1"></i>
								</a> -->
								{/if}
							</td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			{if $total_page gt '1'}
			<div id="pager" class="d-flex justify-content-center mt-3">
				<ul class="pagination">{$html_pager}</ul>
			</div>
			{/if}
		</div>
    </div>
    <!--/ Basic Bootstrap Table -->
</div>
{literal}
<style type="text/css">
	@media screen and (min-width:648px){
		.table-container .table-staff tr td:nth-child(2){
			position:sticky;
			left:64px; top:0;
		}
		.table-container .table-staff tr:nth-child(even) td:nth-child(2){
			background:var(--bs-white) !important;
		}
		.table-container .table-staff tr:nth-child(odd) td:nth-child(2){
			background:rgb(249,250,251);
		}
	}
	.modal-header>.modal-header__left .modal-title:before{
		left:-61px;
	}
</style>
<script type="text/javascript">
$(function(){
	$('select[name=department_id]').change(function(){
		var $_this = $(this);
		$.post(path_ajax_script+"/index.php?mod="+MOD+"&act=load_option_role", {
			'department_id' : $_this.val(),"type":"show_all"
		}, function(html){
			$('select[name="role_ids[]"]').html(html);
		});
	});
});
</script>
{/literal}
