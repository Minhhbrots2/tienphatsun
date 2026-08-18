<link rel="stylesheet" type="text/css" href="{$URL_CSS}/staff.css?v={$upd_version}" />
<div class="container-xxl flex-grow-1 pt-2 container-p-y stf-page">
	<form method="POST" class="w-100" id="staff-filter-form">
		<div class="stf-head">
			<div class="stf-head__titles">
				<h4 class="stf-head__title">Danh sách nhân viên</h4>
				{if $is_access_full eq '1'}
					<p class="stf-head__sub">Quản lý hồ sơ, phòng ban và trạng thái làm việc của toàn bộ nhân sự.</p>
				{else}
					<p class="stf-head__sub">Tổng cộng <strong class="text-main total_record">{$total_record}</strong> nhân viên</p>
				{/if}
			</div>
			<div class="stf-head__actions">
				{if $clsISO->checkPermission('add_staff')}
				<button type="button" class="btn stf-btn stf-btn--brand js__staff-new text-nowrap" onClick="$Core.member.open(this,event)" data-type="open" data-group_id="0"><i class="bx bx-plus"></i>{if $deviceType ne 'phone'} Thêm nhân viên{/if}</button>
				{/if}
				<div class="btn-group dropdown">
					<button data-toggle="ripple" type="button" class="btn stf-btn stf-btn--icon hide-arrow dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-cog"></i></button>
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
		{if $is_access_full eq '1'}
		<div class="stf-stats">
			<div class="stf-stat">
				<span class="stf-stat__icon"><i class="bx bx-group"></i></span>
				<div><p class="stf-stat__label">Tổng nhân viên</p><p class="stf-stat__value">{$arr_totals.total_staff}</p></div>
			</div>
			<div class="stf-stat">
				<span class="stf-stat__icon"><i class="bx bx-user-check"></i></span>
				<div><p class="stf-stat__label">Đang làm việc</p><p class="stf-stat__value">{$arr_totals.total_on}</p></div>
			</div>
			<div class="stf-stat">
				<span class="stf-stat__icon"><i class="bx bx-user-x"></i></span>
				<div><p class="stf-stat__label">Đã nghỉ</p><p class="stf-stat__value">{$arr_totals.total_off}</p></div>
			</div>
			<div class="stf-stat">
				<span class="stf-stat__icon"><i class="bx bx-briefcase"></i></span>
				<div><p class="stf-stat__label">Khối kinh doanh</p><p class="stf-stat__value">{$arr_totals.total_sale}</p></div>
			</div>
			<div class="stf-stat">
				<span class="stf-stat__icon"><i class="bx bx-buildings"></i></span>
				<div><p class="stf-stat__label">Khối văn phòng</p><p class="stf-stat__value">{$arr_totals.total_bo}</p></div>
			</div>
			<div class="stf-stat">
				<span class="stf-stat__icon"><i class="bx bx-trending-up"></i></span>
				<div><p class="stf-stat__label">Gần nhất</p><p class="stf-stat__value">{$arr_totals.total_growth}</p></div>
			</div>
		</div>
		{/if}
		<div class="stf-toolbar">
			<div class="stf-toolbar__search">
				<i class="bx bx-search"></i>
				<input type="text" name="keyword" value="{$keyword}" class="form-control no-focus" placeholder="Tìm theo tên, mã NV..." />
			</div>
			<div class="stf-seg" id="stf-seg-status">
				<button type="button" data-st="all">Tất cả</button>
				<button type="button" data-st="on">Đang làm việc</button>
				<button type="button" data-st="off">Đã nghỉ</button>
			</div>
			<div class="stf-toolbar__spacer"></div>
			{if $is_access_full eq '1'}
			{* doi phong ban khong goi AJAX/loading nua - da co nut tim kiem rieng *}
			<div class="stf-toolbar__dept">
				<select name="department_id" class="iso-select2" data-width="100%"
					data-placeholder="Phòng ban" data-allow-clear="false">
					{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$department_id,'Phòng ban')}
				</select>
			</div>
			{/if}
			<button type="button" class="btn stf-btn" id="stf-btn-adv"><i class="bx bx-filter-alt"></i><span class="d-none d-md-inline"> Bộ lọc</span>{if !empty($role_ids) || !empty($start_date) || !empty($to_date)}<span class="stf-btn__dot" title="Đang có bộ lọc nâng cao"></span>{/if}</button>
			<button data-toggle="ripple" type="submit" class="btn stf-btn stf-btn--icon" title="Tìm kiếm"><i class="bx bx-search"></i></button>
		</div>
		<input type="hidden" name="filter" value="filter" />
		{* panel KHONG bao gio tu mo (chuyen tab hay reload deu dong);
		   dang co loc nang cao thi bao bang cham do tren nut Bo loc *}
		<div class="stf-advfilter" id="stf-advfilter">
			<div class="stf-advfilter__grid">
				{if $is_access_full eq '1'}
					<div class="d-none">
						<div class="stf-advfilter__label">Đội/Nhóm</div>
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
					{if !empty($lstGroupProfile)}
					<div>
						<div class="stf-advfilter__label">Nhóm nhân viên</div>
						<select name="group_profile_id" class="iso-select2" data-width="100%"
							data-placeholder="Nhóm nhân viên" data-allow-clear="false">
							<option value="0">Nhóm nhân viên</option>
							{foreach from=$lstGroupProfile item=_oGroup}
								<option value="{$_oGroup.group_profile_id}">{$_oGroup.title}</option>
							{/foreach}
						</select>
					</div>
					{/if}
					<div>
						<div class="stf-advfilter__label">Vai trò/ Quyền hạn</div>
						<select data-width="100%" data-placeholder="Vai trò/ Quyền hạn"
							data-allow-clear="false" name="role_ids[]" class="iso-select2" multiple>
							<option value="0">Vai trò/ Quyền hạn</option>
							{$clsProperty->getSelectByPropertyV2('_ROLE',$role_ids,'Vai trò/ Quyền hạn')}
						</select>
					</div>
				{elseif $is_dir_sales eq '1'}
					<div class="d-none">
						<div class="stf-advfilter__label">Đội/Nhóm</div>
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
				<div>
					<div class="stf-advfilter__label">Tình trạng</div>
					<select name="status_ids[]" class="iso-select2" data-width="100%" data-placeholder="Tình trạng" data-allow-clear="false" multiple>
						{$clsProperty->getSelectByPropertyV2('_STATUS_STAFF',$status_ids,'Tình trạng')}
					</select>
				</div>
				<div>
					<div class="stf-advfilter__label">Lựa chọn ngày</div>
					<select class="form-control search_field form-select" data-field="date_field" name="date_field">
						<option{if $date_field eq 'reg_date'} selected{/if} value="reg_date">Ngày tạo</option>
						<option{if $date_field eq 'start_date'} selected{/if} value="start_date">Ngày vào làm việc</option>
						<option{if $date_field eq 'birthday'} selected{/if} value="birthday">Ngày sinh nhật</option>
					</select>
				</div>
				{assign var = uid value = $clsISO->getUniqid()}
				<div>
					<div class="stf-advfilter__label">Từ ngày</div>
					<input type="date" class="form-control search_field" id="{$uid}"
						data-field="start_date" value="{$start_date}" name="start_date" placeholder="dd/mm/yy" aria-describedby="uid">
				</div>
				<div>
					<div class="stf-advfilter__label">Tới ngày</div>
					<input type="date" class="form-control search_field" data-field="to_date"
						id="{$uid}" name="to_date" value="{$to_date}" placeholder="dd/mm/yy" aria-describedby="{$uid}">
				</div>
				<div class="stf-advfilter__actions">
					<div class="stf-advfilter__label">&nbsp;</div>
					<div class="stf-advfilter__btns">
						<button type="submit" class="btn stf-btn stf-btn--brand"><i class="bx bx-search"></i> Tìm kiếm</button>
						<button type="reset" class="btn stf-btn"><i class="bx bx-refresh"></i> Xóa</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="stf-tablecard">
		<div class="stf-scroll">
			<table cellpadding="0" cellspacing="0" class="stf-tbl">
				<thead>
					<tr>
						<th>Nhân viên</th>
						<th>Phòng ban</th>
						<th>Vai trò</th>
						<th>Ngày vào</th>
						<th>Trạng thái</th>
						<th>Ngày nghỉ việc</th>
						{if $permiss_login eq '1'}
						<th class="text-center">Login</th>
						{/if}
						<th>Ngày tạo</th>
						<th class="stf-tbl__actions-th">Thao tác</th>
					</tr>
				</thead>
				<tbody>
					{section name=i loop=$list_staffs}
					{assign var = _oneStaff value = $list_staffs[i]}
					{assign var = _profile_id value = $_oneStaff.profile_id}
					{assign var = _more_information value = $_oneStaff.more_information}
					<tr class="trUser" profile_id="{$_profile_id}">
						<td>
							<a href="javascript:void(0);" onClick="$Core.member.view_profile(this, event)"
								profile_id="{$_profile_id}" class="stf-emp">
								<span class="stf-emp__avatar avatar position-relative">
									<img src="{$clsProfile->getAvatar($_profile_id,$_oneStaff,40,40)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" />
									{$clsProfile->get_icon_verified($_profile_id, $_oneStaff.more_information)}
								</span>
								<span class="stf-emp__meta">
									<span class="stf-emp__namerow"><strong class="stf-emp__name">{$list_staffs[i].full_name}</strong> {$list_staffs[i].state_name}</span>
									<span class="stf-emp__sub">{$list_staffs[i].code}</span>
								</span>
							</a>
						</td>
						<td>{if !empty($_more_information.department_name)}<span class="stf-chip">{$_more_information.department_name}</span>{else}--{/if}</td>
						<td class="stf-muted">{$_more_information.role_name}</td>
						<td class="stf-dt">
							{if !empty($list_staffs[i].start_date)}
								<i class="bx bx-calendar"></i>
								{$clsISO->convertTimeToText($list_staffs[i].start_date)}
							{else}
								--
							{/if}
						</td>
						<td>
							<span class="stf-badge {if $list_staffs[i].status_id eq $smarty.const._STATUS_STAFF_OFF_ID}stf-badge--off{else}stf-badge--on{/if}">
								{$list_staffs[i].status_name}
							</span>
						</td>
						<td class="stf-dt">
							{if !empty($list_staffs[i].end_date) && $list_staffs[i].status_id eq $smarty.const._STATUS_STAFF_OFF_ID}
								<i class="bx bx-calendar-x"></i>
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
								<button{if $list_staffs[i].status_id eq $smarty.const._STATUS_STAFF_OFF_ID} disabled{/if} class="btn stf-btn stf-btn--ghost" title="Đăng nhập với tài khoản này">{$clsISO->makeIcon('bx-log-in-circle', 'Đăng nhập')}</button>
							</form>
						</td>
						{/if}
						<td class="stf-dt">
							<i class="bx bx-time-five"></i>
							{$clsISO->convertTimeToText($list_staffs[i].reg_date, true)}
						</td>
						<td class="stf-tbl__actions">
							<a class="btn stf-btn stf-btn--ghost" title="Xem hồ sơ"
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
		<div class="stf-tfoot">
			<span class="stf-tfoot__total">Hiển thị <span id="stf-range">--</span> / <strong class="total_record">{$total_record}</strong> nhân viên</span>
			<select class="form-select stf-perpage" id="stf-perpage">
				<option value="25" selected>25 / trang</option>
				<option value="50">50 / trang</option>
				<option value="100">100 / trang</option>
				<option value="0">Tất cả</option>
			</select>
			<div class="stf-pager" id="stf-pager"></div>
		</div>
		{if $total_page gt '1'}
		<div class="stf-tfoot stf-tfoot--server" id="pager">
			<ul class="pagination">{$html_pager}</ul>
		</div>
		{/if}
	</div>
</div>
<script type="text/javascript">
	var _STF_STATUS_OFF = '{$smarty.const._STATUS_STAFF_OFF_ID}';
	var _STF_STATUS_ON = '{$smarty.const._STATUS_STAFF_ON_ID}';
</script>
{literal}
<script type="text/javascript">
$(function(){
	/* Nut "Bo loc": mo/dong panel loc nang cao */
	$('#stf-btn-adv').on('click', function(){
		$('#stf-advfilter').toggleClass('open');
		$(this).toggleClass('stf-btn--brand');
	});

	/* Chip trang thai = shortcut set gia tri cho select status_ids[] roi submit,
	   khong them tham so nao ngoai co che loc san co. */
	var $status = $('select[name="status_ids[]"]');

	function currentSeg(){
		var vals = $status.val() || [];
		if(!vals.length){
			return 'all';
		}
		if(vals.length === 1 && String(vals[0]) === String(_STF_STATUS_OFF)){
			return 'off';
		}
		if(vals.length === 1 && String(vals[0]) === String(_STF_STATUS_ON)){
			return 'on';
		}
		/* dang loc to hop tuy chinh trong panel -> khong sang chip nao */
		return '';
	}

	$('#stf-seg-status button[data-st="' + currentSeg() + '"]').addClass('on');

	/* Skeleton shimmer thay noi dung bang trong luc reload - dong bo voi
	   loading cua /tool.html; khong goi trigger('change') tranh select2 giat. */
	function stfLoading(){
		var $tb = $('.stf-tbl tbody');
		if($tb.find('.stf-skel-tr').length){
			return;
		}
		var _cols = $('.stf-tbl thead th').length || 8;
		var _cells = '';
		for(var _c = 0; _c < _cols; _c++){
			_cells += '<td><span class="stf-skel-bar"></span></td>';
		}
		var _rowsHtml = '';
		for(var _r = 0; _r < 10; _r++){
			_rowsHtml += '<tr class="stf-skel-tr">' + _cells + '</tr>';
		}
		$tb.find('tr').hide();
		$tb.append(_rowsHtml);
		$('#stf-pager').empty();
	}
	$('#staff-filter-form').on('submit', function(){
		stfLoading();
	});

	$('#stf-seg-status button').on('click', function(){
		var _st = $(this).attr('data-st');
		var $form = $('#staff-filter-form');
		$status.find('option').prop('selected', false);
		$form.find('input.stf-status-all').remove();
		if(_st === 'off'){
			$status.find('option[value="' + _STF_STATUS_OFF + '"]').prop('selected', true);
		} else if(_st === 'on'){
			/* chi dung 1 trang thai DANG LAM VIEC - khop voi cach KPI dem,
			   khong gom thu viec / thai san / cong tac vien */
			$status.find('option[value="' + _STF_STATUS_ON + '"]').prop('selected', true);
		} else {
			/* "Tat ca": neu POST khong co status_ids server se ep mac dinh ve
			   DANG LAM VIEC - gui 1 phan tu rong de redirect thanh ?status_ids=
			   va handler hieu la khong loc trang thai. */
			$form.append('<input type="hidden" class="stf-status-all" name="status_ids[]" value="" />');
		}
		stfLoading();
		$form.submit();
	});

	/* ---- Phan trang phia client: du lieu da tai san trong bang,
	   chi an/hien dong theo trang de danh sach dai de theo doi hon. ---- */
	var $rows = $('.stf-tbl tbody tr');
	var _page = 1;

	function stfPerPage(){
		var _v = parseInt($('#stf-perpage').val(), 10);
		return _v > 0 ? _v : ($rows.length || 1);
	}

	function stfRenderPager(_totalPages){
		var $p = $('#stf-pager');
		if(_totalPages <= 1){
			$p.empty();
			return;
		}
		var html = '<button type="button" data-p="prev" title="Trang trước"><i class="bx bx-chevron-left"></i></button>';
		var _dotL = false, _dotR = false;
		for(var i = 1; i <= _totalPages; i++){
			if(_totalPages > 9 && i > 2 && i < _totalPages - 1 && Math.abs(i - _page) > 1){
				if(i < _page && !_dotL){
					html += '<button type="button" disabled>…</button>';
					_dotL = true;
				}
				if(i > _page && !_dotR){
					html += '<button type="button" disabled>…</button>';
					_dotR = true;
				}
				continue;
			}
			html += '<button type="button" data-p="' + i + '"' + (i === _page ? ' class="cur"' : '') + '>' + i + '</button>';
		}
		html += '<button type="button" data-p="next" title="Trang sau"><i class="bx bx-chevron-right"></i></button>';
		$p.html(html);
	}

	function stfPaginate(){
		var _per = stfPerPage();
		var _total = $rows.length;
		var _totalPages = Math.max(1, Math.ceil(_total / _per));
		if(_page > _totalPages){
			_page = _totalPages;
		}
		if(_page < 1){
			_page = 1;
		}
		var _from = (_page - 1) * _per;
		var _to = Math.min(_from + _per, _total);
		$rows.hide().slice(_from, _to).show();
		$('.stf-tbl').addClass('stf-ready');
		$('#stf-range').text(_total ? (_from + 1) + '–' + _to : '0');
		stfRenderPager(_totalPages);
	}

	try {
		var _saved = localStorage.getItem('stf_per_page');
		if(_saved !== null && $('#stf-perpage option[value="' + _saved + '"]').length){
			$('#stf-perpage').val(_saved);
		}
	} catch(e){}

	$('#stf-perpage').on('change', function(){
		_page = 1;
		try {
			localStorage.setItem('stf_per_page', $(this).val());
		} catch(e){}
		stfPaginate();
	});

	$('#stf-pager').on('click', 'button[data-p]', function(){
		var _p = $(this).attr('data-p');
		if(_p === 'prev'){
			_page = _page - 1;
		} else if(_p === 'next'){
			_page = _page + 1;
		} else {
			_page = parseInt(_p, 10) || 1;
		}
		stfPaginate();
		$('html,body').animate({ scrollTop: $('.stf-tablecard').offset().top - 80 }, 150);
	});

	stfPaginate();
});
</script>
{/literal}
