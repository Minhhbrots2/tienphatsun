{if !empty($list_events)}
<div class="lstCourse owl owl-carousel" data-lg-slide="1" data-md-slide="1" data-xs-slide="1"  data-sm-slide="1" data-loop="false" data-nav="false" data-dots="true">
	{foreach from = $list_events item = oneEvent key=k}
	{assign var = course_id value = $oneEvent.course_id}
	{assign var = toId value= $clsISO->getUniqid()}
	<div class="cursor-pointer text-white pr-1">
		<div class="form-row row">
			<div class="col-xxl-9 col-md-8 l-event{if $deviceType eq 'phone'} mb-2{/if}">
				<div class="d-flex flex-column">
					<span class="py-1 fs-13 fst-italic">-- {$oneEvent.cat_name} --- {$oneEvent.status}</span>
					<a onClick="$Core.course.open(this, event)" course_id="{$course_id}" class="text-white line-clamp-1 fw-bold {if $oneEvent.cat_id eq $smarty.const._MEDIA_DISSEMINATION && empty($oneEvent.is_joined)}item_media_dissemination{/if}" href="javascript:void(0)">{$oneEvent.title}</a>
					<span class="time mb-1 text-white">
						<i class="material-icons-outlined">schedule</i> 
						{$clsCourse->getTimeStartCourse($oneEvent.start_date)} 
					</span>
					<span class="time mb-1 text-white">
						<i class="material-icons-outlined">timer_off</i> 
						{$clsCourse->getTimeStartCourse($oneEvent.due_date)} 
					</span>
					{if $oneEvent.location}
					<p class="limit_1line text-white mb-1">
						<i class="material-icons-outlined">location_on</i> 
						{$oneEvent.location}
					</p>
					{/if}
					<div onClick="{if $clsISO->checkPermission('view_all_course') || $oneEvent.user_id eq $profile_id || $clsISO->checkPermissionGroup('SALE_DIRECTOR')}$Core.course.openJoin(this, event){/if}" course_id="{$course_id}" class="d-flex gap-1 align-item-center">
						<i class="bx bx-user"></i>
						<u class="text-white">{$oneEvent.total_joined} người tham gia</u>
					</div>
				</div>							
			</div>
			{if $oneEvent.cat_id eq $smarty.const._MEDIA_DISSEMINATION}
				{if !$oneEvent.is_expired}
				<div class="col-md-3">
					{if $oneEvent.is_joined eq '0'}
						<button type="buton" class="btn btn-block btn-sm btn-outline-primary" onClick="$Core.course.open_report(this,event)" course_id="{$oneEvent.course_id}" openFrom="_pop" toId="{$toId}"><i class='bx bx-user-voice'></i> Báo cáo</button>							
					{else}
						<form action="" class="w-100" enctype="multipart/form-data">
							<div class="ui-stack ui-stack--wrap d-flex justify-content-end position-relative">
								<span type="buton" class="btn btn-sm btn-primary btn_checkin text-nowrap">
									<i class='bx bx-user-check'></i> Đã báo cáo</span>
							</div>
						</form>	
					{/if}
					{if $oneEvent.total_joined gt 0}
						{assign var=upload_share value=$oneEvent.upload_share}
						{if $deviceType eq 'phone'}
						<div class="form-row mt-3">
							{foreach from=$upload_share name=i item=upload}
								{if $smarty.foreach.i.index lt 4}
								<div class="col-3" data-fancybox="gallery-course" href="{$upload.image}">
									<img src="{$upload.image}" alt="" width="" height="90" style="object-fit: contain">
								</div>
								{else}
								<div class="d-none" data-fancybox="gallery-course" href="{$upload.image}">
									<img src="{$upload.image}">
								</div>
								{/if}
							{/foreach}
						</div>
						{else}
							{foreach from=$upload_share name=i item=upload}
								{if $smarty.foreach.i.index == 0}
								<div class="img mt-2" data-fancybox="gallery-course" href="{$upload.image}">
									<img src="{$upload.image}" width="70" height="70" style="object-fit: contain">
								</div>
								{else}
								<div class="d-none" data-fancybox="gallery-course" href="{$upload.image}">
									<img src="{$upload.image}">
								</div>
								{/if}
							{/foreach}
						{/if}
					{/if}
				</div>
				{/if}
			{else}
			<div class="col-xxl-3 col-md-4">
				<div class="d-flex gap-2 align-items-center{if $deviceType ne 'phone'} flex-column{/if} justify-content-end w-100">
					{if !$oneEvent.is_expired}
						{if $oneEvent.is_joined eq '0'}
							<button type="button" class="btn btn-{if $deviceType eq 'computer'}sm{else}xs{/if} btn-block btn-outline-default bg-white btn_checkin" onClick="$Core.course.checkin(this,event)" holderG="confirm" openFrom="_desktop" course_id="{$course_id}"><i class='bx bx-user-check'></i> Xác nhận</button>
							{*<button type="button" openFrom="_desktop" toId="{$toId}" disabled class="btn btn-block btn-{if $deviceType eq 'computer'}sm{else}xs{/if} btn-outline-warning"><i class="bx bx-user-check"></i> Check-In</button>*}
						{else}
							<button type="button" class="btn btn-block btn-primary btn-{if $deviceType eq 'computer'}sm{else}xs{/if} btn_checkin{if $oneEvent.is_checked_in eq '1'} checked_in{/if}" onClick="$Core.course.checkin(this,event)" course_id="{$course_id}" holderG="cancel" openFrom="_desktop" staff_id="{$profile_id}"><i class='bx bx-user-check'></i> Đã xác nhận</button>
							{*{if empty($oneEvent.is_time_checkin)}
								<button type="button" openFrom="_desktop" toId="{$toId}" disabled class="btn btn-block btn-{if $deviceType eq 'computer'}sm{else}xs{/if} btn-outline-warning"><i class="bx bx-user-check"></i> Check-In</button>
							{else}
								{if $oneEvent.is_checked_in eq '1'}
								<button type="button" class="btn btn-block btn-{if $deviceType eq 'computer'}sm{else}xs{/if} btn-outline-success{if $deviceType ne 'phone'} mt-2{else} ml-2{/if}"><i class="bx bx-check-double"></i> Đã check-In</button>
								{else}
								<form class="w-100" action="" method="POST" enctype="multipart/form-data">
									<input type="hidden" name="hid" value="hid" />
									<input type="file" class="d-none" id="upload_image_{$toId}" name="image" onChange="$Core.course.upload_image(this,event)" data-course_id="{$course_id}" 
									openFrom="_desktop" data-type="checkin">
									<button type="buton" onClick="$Core.course.select_image(this, event)" openFrom="_desktop" toId="{$toId}" class="btn btn-block btn-{if $deviceType eq 'computer'}sm{else}xs{/if} btn-outline-warning">
										<i class="bx bx-user-check"></i> Check-In
									</button>
								</form>
								{/if}
							{/if}*}
						{/if}
					{else}
						<button type="button" class="btn btn-sm btn-primary btn_checkin {if $oneEvent.have_cancel eq 1}have_cancel{/if} checked" onClick="$Core.course.checkin(this,event)" course_id="{$course_id}" staff_id="{$profile_id}">Đã tham gia</button>	
					{/if}
					{if $oneEvent.isCheckIn eq '1'}{else}
						{if !$oneEvent.check_overTime}
							<!-- <button type="button" class="btn btn-sm btn-outline-default btn_checkin {if $oneEvent.have_cancel eq 1}have_cancel{/if}" onClick="$Core.course.checkin(this,event)" course_id="{$oneEvent.course_id}" staff_id="{$profile_id}">Tham gia</button> -->
						{/if}
					{/if}
				</div>
			</div>
			{/if}
		</div>
	</div>
	{/foreach}
</div>
{else}
	<div class="text-center p-2">
		<div class="mb-2">
			<img src="{$clsConfiguration->getValue('LogoWhite')}" width="{$clsConfiguration->getImageWidth('LogoWhite')}" height="{$clsConfiguration->getImageHeight('LogoWhite')}" alt="{$header_configs.CompanyName}" />
		</div>
		<p class="text-white mb-0"><strong>Hiện chưa có sự kiện & đào tạo!</strong><br />
		{$smarty.const.BRAND_NAME} đang chuẩn bị những nội dung giá trị dành cho bạn.</p>
	</div>
{/if}