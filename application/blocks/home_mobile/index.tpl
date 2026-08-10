<div class="body_mobile overflow-hidden">
	<div class="mh-hero">
		<div class="mh-hero__ava dropdown">
			<a class="nav-link dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown">
				<div class="avatar avatar-online rounded-circle overflow-hidden">
					<img src="{$clsProfile->getAvatar($profile_id,$oneProfile,60,60)}" onerror="this.src='{$URL_IMAGES}/avatars/1.png'" 
						class="rounded-circle" alt="{$oneProfile.full_name|escape}" />
				</div>
			</a>
			<div class="dropdown-menu dropdown-menu-end overflow-hidden w-px-300 py-0">
				{$core->getBlock('menu_profile')}
			</div>
		</div>
		<div class="mh-hero__hi">Xin chào,</div>
		<h1 class="mh-hero__name">{$oneProfile.full_name|escape} 👑</h1>
		<span class="mh-badge">💎 {$clsProperty->getTitle($oneProfile.role_id)|escape}</span>
		<div class="mh-pills">
			<span class="mh-pill" data-bs-toggle="modal" data-bs-target="#online-modal" ><span class="g"></span> <span class="total_online">0</span> online</span>
			<span class="mh-pill"><i class="bx bx-buildings"></i> {$header_configs.CompanyName}</span>
		</div>
	</div>
	<div class="mh-sheet">
		<div class="mh-stats">
			<div class="mh-stat">
				<div class="mh-stat__ic mh-ic-blue"><i class="bx bx-check-square"></i></div>
				<div class="mh-stat__num">{$mh_checkin}</div>
				<div class="mh-stat__lbl">Check-ins</div>
				<div class="mh-stat__sub">Hôm nay</div>
				<div class="mh-stat__delta"><i class="bx bx-up-arrow-alt"></i>12%</div>
			</div>
			<div class="mh-stat">
				<div class="mh-stat__ic mh-ic-green"><i class="bx bx-user-plus"></i></div>
				<div class="mh-stat__num">{$mh_new_staff}</div>
				<div class="mh-stat__lbl">Nhân sự mới</div>
				<div class="mh-stat__sub">Tháng này</div>
				<div class="mh-stat__delta"><i class="bx bx-up-arrow-alt"></i>8%</div>
			</div>
			<div class="mh-stat">
				<div class="mh-stat__ic mh-ic-orange"><i class="bx bx-transfer-alt"></i></div>
				<div class="mh-stat__num">{$mh_deal_closed}</div>
				<div class="mh-stat__lbl">Giao dịch chốt</div>
				<div class="mh-stat__sub">Tháng này</div>
				<div class="mh-stat__delta"><i class="bx bx-up-arrow-alt"></i>20%</div>
			</div>
		</div>
		<div class="mh-sec">
			<h2 class="text-upper">Truy cập nhanh</h2>
			<a onClick="$Core.mobile.open_config_menu(this,event)" data-for="mb">Tùy chỉnh <i class="bx bx-slider-alt"></i></a>
		</div>
		<div class="list_menu_grid position-relative" id="list_menu_active">
			<div class="form-row row-cols-4">
				<div class="col mb-3 item_menu_grid">
					<a class="text-dark text-center d-block" href="/tool.html">
						<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
							<img src="{$URL_IMAGES}/loading.gif" width="30" height="30" />
						</span>
						<span class="text-dark fw-semibold fs-12">Đang tải..</span>
					</a>
				</div>
				<div class="col mb-3 item_menu_grid">
					<a class="text-dark text-center d-block" href="/tool.html">
						<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
							<img src="{$URL_IMAGES}/loading.gif" width="30" height="30" />
						</span>
						<span class="text-dark fw-semibold fs-12">Đang tải..</span>
					</a>
				</div>
				<div class="col mb-3 item_menu_grid">
					<a class="text-dark text-center d-block" href="/tool.html">
						<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
							<img src="{$URL_IMAGES}/loading.gif" width="30" height="30" />
						</span>
						<span class="text-dark fw-semibold fs-12">Đang tải..</span>
					</a>
				</div>
				<div class="col mb-3 item_menu_grid">
					<a class="text-dark text-center d-block" href="/tool.html">
						<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
							<img src="{$URL_IMAGES}/loading.gif" width="30" height="30" />
						</span>
						<span class="text-dark fw-semibold fs-12">Đang tải..</span>
					</a>
				</div>
			</div>
			<button type="button" data-toggle="ripple" class="btn btn-icon position-absolute btn_menu_more rounded-pill card hide" 
				onclick="$Core.mobile.view_menu(this,event)"><i class="bx bxs-chevrons-down"></i></button>
		</div>
		<a class="mh-milestone" href="/m-{$smarty.now|date_format:'%Y'}/" title="Dấu ấn vinh quang">
			<div class="mh-milestone__tr">🏆</div>
			<div class="mh-milestone__b">
				<div class="mh-milestone__tag">MILESTONE {$smarty.now|date_format:"%Y"}</div>
				<div class="mh-milestone__t">DẤU ẤN VINH QUANG</div>
				<div class="mh-milestone__s">Cùng nhau chinh phục những cột mốc mới!</div>
			</div>
			<i class="bx bx-chevron-right mh-milestone__go"></i>
		</a>
		{if !empty($list_news) || !empty($list_events)}
		<div class="box_slide_option mt-3">
			<div class="owl-carousel owl_slide_option">
				{if !empty($list_news)}
					{foreach from=$list_news item = _oNews}
					<a onClick="open_news(this, event)" action="_detail" news_id="{$_oNews.news_id}" class="item_option item_news cursor-pointer d-flex gap-2 align-items-center rounded-2 p-3 h-100" title="{$_oNews.title|escape}" style="background:#ba8d34">
						<div class="img_news">
							<img class="object-fit-cover rounded-1" src="{$clsISO->resize_image_url($_oNews.image, 80, 80)}" alt="{$_oNews.title|escape}" width="80" height="80" onerror="this.src='{$URL_IMAGES}/no-image.png'" >
						</div>
						<div class="content_news">
							<h3 class="title_news lh-xs fs-14 mb-1 limit_2line text-white text-upper">{$_oNews.title|escape}</h3>
							<div class="d-flex align-items-center flex-wrap">
								<span class="fs-11 text-white me-2 mb-1 text-nowrap"><i class="bx bx-folder-open fs-14 me-1"></i>{$_oNews.cat_name|escape}</span>
								<span class="fs-11 text-white me-2 mb-1"><i class="bx bx-timer fs-14 me-1"></i>{$clsISO->getTimeAgo($_oNews.reg_date)}</span>
							</div>
						</div>
					</a>
					{/foreach}
				{/if}
				{if !empty($list_events)}
					{foreach from = $list_events item = oneEvent key=k}
					{assign var = course_id value = $oneEvent.course_id}
					<div class="cursor-pointer text-white pr-1 p-2 rounded-2 bg-main h-100">
						<div class="form-row row">
							<div class="col-xxl-9 col-md-8 l-event">
								<div class="d-flex flex-column">
									<div class="d-flex justify-content-between align-items-center">
										<span class="py-1 fs-13 fst-italic">-- {$oneEvent.cat_name|escape} --- {$oneEvent.status}</span>
										<span class="fs-10 fst-italic">{$oneEvent.total_joined} người tham gia</span>
									</div>
									<a onClick="$Core.course.open(this, event)" course_id="{$course_id}" href="javascript:void(0)" 
										class="text-white limit_1line fw-bold {if $oneEvent.cat_id eq $smarty.const._MEDIA_DISSEMINATION && empty($oneEvent.is_joined)}item_media_dissemination{/if}">{$oneEvent.title|escape}</a>
									<span class="time fs-12 text-white">
										<i class="material-icons-outlined">schedule</i>
										{$clsISO->formatDate($oneEvent.start_date,4)} - {$clsISO->formatDate($oneEvent.due_date,4)}
									</span>
									{if $oneEvent.location}
									<p class="limit_1line text-white mb-0 fs-12">
										<i class="material-icons-outlined">location_on</i>
										{$oneEvent.location|escape}
									</p>
									{/if}
								</div>
							</div>
						</div>
					</div>
					{/foreach}
				{/if}
			</div>
		</div>
		{/if}
	</div>
</div>
{literal}
<style>
	.header-mobile,
	.body_mobile {
		padding-top: env(safe-area-inset-top) !important;
	}
</style>
<script>
	$Core.mobile.loadMenu();
	if($(".owl_slide_option").length > 0) {
		$(".owl_slide_option").owlCarousel({
			center: true,
			items:1.5,
			loop:true,
			margin:10,
		});
	}
</script>
{/literal}