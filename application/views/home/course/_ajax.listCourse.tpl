{if $deviceType ne "phone"}
<div class="table-container no-shadow overflow-x-auto">
	<table class="table dragable" cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead><tr>
			<th class="align-center h-px-40 text-left">Tiêu đề</th>
			<th class="align-center h-px-40 bg-lighter text-left">Loại sự kiện</th>
			<th class="align-center h-px-40 bg-lighter bg-lighter text-left">Tham gia</th>
			<th class="align-center h-px-40 bg-lighter text-left">Đã tham gia</th>
			<th class="align-center h-px-40 bg-lighter bg-lighter text-left" width="160px">Ngày tạo</th>
			{if $permiss_edit eq '1'}
			<th class="align-center h-px-40 bg-lighter text-left" width="45px"></th>
			{/if}
		</tr></thead>
		<tbody>
			{if !empty($list_courses)}
			{foreach from=$list_courses name=i item=course}
			<tr class="tr">
				<td class="text-left" style="min-width:500px">
					<div class="d-flex flex-column">
						<a class="fs-14 fw-semibold" href="javascript:void(0);" onclick="$Core.course.open(this, event)" title="Xem ngay" course_id="{$course.course_id}">{$course.title} {$course.status}</a> 								
						<div class="d-flex align-items-center gap-2">
							<div class="d-flex align-items-center gap-1 text-muted">
								<img class="avatar avatar-xxs rounded-pill" src="{$clsProfile->getAvatar($course.oneUser.profile_id,$course.oneUser,40,40)}" /> 
								<span class="text-muted">{$course.oneUser.full_name}</span>
							</div>
							{if $course.location}
							<span class="text-muted d-none">
								<i class="bx bxs-map me-1 fs-12"></i> {$course.location}</span>
							{/if}
							<span class="text-muted fs-12">
								<i class="bx bx-time-five fs-12"></i> {$course.start_date} - {$course.due_date}</span>
						</div>
					</div>
				</td>
				<td class="text-nowrap">{$course.cat_name}</td>
				<td class="text-nowrap">
					{if $course.is_all_staff eq 1}
						Tất cả thành viên
					{else if $course.is_all_staff eq 2}
						<div class="d-flex flex-column">
							{if $course.group_profile}<span>{$course.group_profile}</span>{/if}
						</div>
					{else}
						<div class="d-flex flex-column">
							{if $course.department}<span>{$course.department}</span>{/if}
							{if $course.profile}<span>{$course.profile}</span>{/if}
						</div>
					{/if} 
					<p class="mb-0"><i class='bx bx-user mr-1 align-top'></i>{$course.total_profile} <span data-url="/index.php?mod=home&sub=course&act=load_list_user&course_id={$course.course_id}&type='all'" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile d-none" ><i class='bx bxs-show'></i></span></p>
				</td>
				<td class="text-nowrap text-center">
					<p class="mb-0">{$course.txt_join} <span data-url="/index.php?mod=home&sub=course&act=load_list_user&course_id={$course.course_id}&type=accept" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile" ><i class='bx bx-user align-top'></i></span></p>
				</td>
				<td class="text-left text-nowrap">
					<i class="material-icons-outlined">more_time</i> 
					{$clsISO->convertTimeToText($course.reg_date, true)}
				</td>
				{if $permiss_edit eq '1'}
				<td class="text-center text-nowrap">
					{if $course.user_id eq $profile_id}{/if}
					<div class="dropdown">
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu w-px-100" style="">
							<a class="dropdown-item" onclick="$Core.course.open_course(this,event)" data-type="open" {if $course.cat_id eq $smarty.const._CAT_EVENT_ID}_tp="event" ssss{/if} data-course_id="{$course.course_id}" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
							<a class="dropdown-item" onclick="$Core.course.delete(this,event)" data-course_id="{$course.course_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
						</div>
					</div>
					{/if}
				</td>
			</tr>
			{/foreach} 
		{else}
			<tr class="tr">
				{if $clsISO->checkPermission("create_course")}
				<td colspan="6" class="text-center">Danh sách trống</td>
				{else}
				<td colspan="5" class="text-center">Danh sách trống</td>
				{/if}
			</tr>
		{/if}
		</tbody>
	</table>
</div>
{else}
	{if !empty($list_courses)}
	{foreach from=$list_courses name=i item=course}
	<div class="py-3 {if !$smarty.foreach.i.last}border-bottom{/if}">
		<h5 class="fw-semibold mb-0 lh-base"><a class="fs-16 fw-semibold" href="javascript:void(0);" onclick="$Core.course.open(this, event)" title="Xem ngay" course_id="{$course.course_id}">{$course.title}</a></h5>
		<div class="d-flex flex-column">
			<p class="mb-1"><span class="text-main fst-italic fs-12">Thời gian: {$course.start_date} - {$course.due_date}</span> {$course.status}</p>
			{if $course.location}<p class="text-muted mb-1"><i class='bx bxs-map mr-1'></i>{$course.location}</p>{/if}
			{if $course.is_all_staff eq 1}
				<p class="mb-1"><span>Tham gia: Tất cả thành viên</span></p>
			{else}
				{if $course.department}<p class="mb-1"><span>Phòng ban: {$course.department}</span></p>{/if}
				{if $course.profile}<p class="mb-1"><span>Nhân viên: {$course.profile}</span></p>{/if}
			{/if} 
			<p class="mb-1 d-none">Dự kiến tham gia: {$course.total_profile} <span data-url="/index.php?mod=home&sub=course&act=load_list_user&course_id={$course.course_id}&type='all'" data-toggle="webui-popover" data-trigger="hover" data-width="300" class="awe__post-profile" ><i class='bx bxs-show'></i></span></p>
			<div class="d-flex justify-content-between alignt-items-center">
				<p class="mb-0">Đã tham gia: {$course.txt_join} {if $course.count_accept gt 0}<span data-url="/index.php?mod=home&sub=course&act=load_list_user&course_id={$course.course_id}&type=accept" data-toggle="webui-popover" data-trigger="hover" data-width="300" class="awe__post-profile" ><i class='bx bxs-show'></i></span>{/if}</p>
				{if $clsISO->checkPermission("create_course") || $course.user_id eq $profile_id}
					<div class="dropdown d-flex justify-content-end">
						<button type="button" class="btn btn-icon btn-sm btn-link text-muted dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-end w-px-100">
							<a class="dropdown-item" onclick="$Core.course.addCourse(this,event)" data-type="open" data-course_id="{$course.course_id}" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
							<a class="dropdown-item" onclick="$Core.course.delete(this,event)" data-course_id="{$course.course_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
						</div>
					</div>
				{/if}
			</div>

		</div>
	</div>
	{/foreach}
	{/if}
{/if}