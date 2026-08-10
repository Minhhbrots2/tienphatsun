{if $deviceType eq 'phone'}
	{$core->getBlock("home_mobile")}
{/if}
<div class="content-wrapper {if $deviceType eq 'phone'}content_mobile_wrapper{/if}">
    <div class="container-xxl flex-grow-1 py-2 container-p-y">
		{$core->getBlock('banner')}
		{if $deviceType eq 'phone'}
			{$core->getBlock('block_honor')}
		{/if}
		{if $deviceType eq 'phone'}
			{$core->getBlock('note_calendar')}
		{/if}
		{if $deviceType eq 'phone'}
			{if !empty($oneTraining)}
			<div class="card mb-2">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="card-title m-0"><span>Có thể có ích cho bạn</span></h5>
					<a href="{$clsISO->getLink('training')}" class="btn btn-icon btn-sm btn-link rounded-pill">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
				</div>
				<div class="card-body mt-0">
					<div class="item_training h-100 card no-shadow overflow-hidden cursor-pointer position-relative overflow-hidden" 
						onclick="$Core.global.training.open(this,event)" training_id="{$oneTraining.training_id}">
						<div class="image-scale">
							<img class="w-100 h-auto" src="{$oneTraining.image}" alt="{$oneTraining.title}" width="340" height="250">
						</div>
						<span class="position-absolute zindex-2 top-50 left-50 fs-50"  style="transform: translate(-50%,-50%);left: 50%;width: 60px" >
							<svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
								<path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f03"></path>
								<path d="M 45,24 27,14 27,34" fill="#fff"></path>
							</svg>
						</span>
						<div class="box_info position-absolute w-100 left-0 bottom-0 text-white zindex-1 {$deviceType}">
							<div class="p-3">
								<h3 class="title_training mb-2 fs-18 limit_1line">{$oneTraining.title}</h3>
								<div class="author fs-12">bởi <strong>{$oneTraining.author}</strong></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			{/if}
			{$core->getBlock('top_ranker_25')}
			<div class="ranking-box mb-2">
				{assign var = _gId value = $clsISO->getUniqid()}
				<div class="card ranking mb-2">
					<div class="card-body">
						{$core->getBlock('top_ranking')}
					</div>
				</div>
			</div>
			<div class="ranking-box mb-2">
				{assign var = gId value = $clsISO->getUniqid()}
				{$core->getBlock('ranking_dept', ['gId' => $gId])}
			</div>
			<div class="ranking-box mb-2">
				{assign var = gId value = $clsISO->getUniqid()}
				{$core->getBlock('top_ranker', ['gId' => $gId])}
			</div>
		{/if}
		{if $deviceType ne 'phone' && !$clsISO->checkPermissionGroup('DIRECTOR')}
		<div class="card mb-2">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h5 class="card-title mb-0">Truy cập nhanh</h5>
				<button type="button" data-toggle="ripple" class="btn btn-sm btn-link btn-icon btn_config_menu rounded-pill text-muted" 
					title="Cấu hình" data-bs-toggle="tooltip" onClick="$Core.mobile.open_config_menu(this,event)" data-for="pc">
					<i class='bx bx-cog'></i>
				</button>
			</div>
			<div class="card-body">
				<div id="list_menu_active" class="computer">
					<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
						{section loop=12 start=0 step=1 name=i}
						<div class="item_menu_grid w-px-65">
							<a class="text-dark text-center text-center d-block">
								<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
									<div class="w-px-30 animate-bg h-px-30 rounded-2"></div>
								</span>
								<span class="text-dark fw-semibold fs-12">Đang tải..</span>
							</a>
						</div>
						{/section}
					</div>
				</div>
			</div>
		</div>
		{/if}
		{if !($deviceType eq 'phone')}
			{*$core->getBlock('home_news')*}
		{/if}
		<div class="clearfix"></div>
	{if $deviceType ne 'phone'}
		{$core->getBlock('block_honor')}
	{/if}
	<div class="clearfix"></div>
	{* Bản đồ vai trò → màn hình nằm ở nhóm "Màn hình trang chủ" trong Cấu hình hệ thống;
	   ISO::getHomeScreen() chốt thứ tự ưu tiên, trả rỗng nghĩa là màn hình mặc định. *}
	{assign var = home_screen value = $clsISO->getHomeScreen()}
	{if $home_screen ne ''}
		{$core->getBlock($home_screen)}
	{else}
		{if $clsISO->checkSale() && 1==2}
			{$core->getBlock('ranking-staff')}
		{/if}
		<div class="form-row mb-2">
			<div class="col-12 col-lg-8 mb-2 mb-lg-0 order-0">
				<div class="dbx-card h-100">
					<div class="dbx-card__head">
						<span class="dbx-card__ic"><i class="bx bx-group"></i></span>
						<h5 class="dbx-card__title mb-0">Hoạt động check-in</h5>
						<a href="/net-dep-lao-dong.html" title="Xem tất cả" class="btn btn-icon btn-sm btn-link rounded-pill">
							<i class="bx bx-link-external text-fs-14 text-muted"></i>
						</a>
					</div>
					<div class="card-body ajax" data-bind="{$uid}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_checkin_activity" data-options="{ldelim}{rdelim}">
						<div class="form-row">
						{if $deviceType eq 'phone'}
							{section name=i loop=3}
							<div class="col-4">
								<div class="animate-bg rounded-2 w-100 h-px-100"></div>
							</div>
							{/section}
						{else}
							{section name=i loop=6}
							<div class="col-2">
								<div class="animate-bg rounded-2 w-100 h-px-175"></div>
							</div>
							{/section}
						{/if}
						</div>
					</div>
				</div>
			</div>
			{if $deviceType ne 'phone'}
			<div class="col-12 col-lg-4 order-0 sss">
				{$core->getBlock('home_course')}
			</div>
			{/if}
		</div>	
		
		<div class="form-row">
			<!-- Start Left Col -->
			<div class="col-12 col-lg-8 order-0">
				<div class="sticky">
					{*{if $clsISO->checkSale()}
						{$core->getBlock("crm_home")}
					{/if}*}
					{if $oneProfile.role_id eq $smarty.const._ROLE_HEAD_HR_BO}
					<div class="card mb-2">
						<div class="d-flex align-items-end">
							<div class="card-body row ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_info_staff" 
							data-options='{ldelim}{rdelim}'>
								<div class="p-5 text-center">
									<div class="p-2">Đang tải...</div>
								</div>
							</div>
						</div>
					</div>
					{/if}
					{if $deviceType eq 'phone' && $clsISO->checkSale()}
						{$core->getBlock("target_sales")}	
					{/if}
					<div class="form-row mb-2">
						<div class="col-12 col-lg-6 mb-2 mb-lg-0 flex-fill">
							{assign var = gId value = $clsISO->getUniqid()}
							<div class="spb-hero h-100">
								<div class="spb-hero__head">
									<h5 class="spb-hero__title">Thống kê giao dịch <span class="spb-hero__yr">{$smarty.now|date_format:'%Y'}</span></h5>
									<div class="spb-hero__meta">
										<div>Giao dịch cuối: <b>{$transactions_configs.last_deposit_date}</b></div>
										<div>Bao lâu bạn chưa có giao dịch? <span class="spb-hero__badge"><i class="bx bx-time-five"></i> {$transactions_configs.days_since_sold} ngày</span></div>
									</div>
								</div>
								<div class="spb-hero__body ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_person_billing" data-options="{ldelim}{rdelim}">
									<div class="row">
										<div class="col-12 col-md-6">
											<div class="animate-bg rounded-2 w-100 h-px-20 mb-2"></div>
											<div class="d-flex gap-5 align-items-center justify-content-between mb-2">
												<div class="animate-bg rounded-2 flex-fill h-px-20"></div>
												<div class="animate-bg rounded-2 flex-fill h-px-20"></div>
											</div>
											<div class="animate-bg rounded-2 w-100 h-px-20 mb-2"></div>
										</div>
										<div class="col-12 col-md-6">
											<div class="animate-bg rounded-2 w-100 h-px-20 mb-2"></div>
											<div class="d-flex gap-5 align-items-center justify-content-between mb-2">
												<div class="animate-bg rounded-2 flex-fill h-px-20"></div>
												<div class="animate-bg rounded-2 flex-fill h-px-20"></div>
											</div>
											<div class="animate-bg rounded-2 w-100 h-px-20 mb-2"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>					
					<!-- Giỏ hàng -->
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="dbx-card mb-2">
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-bar-chart-alt-2"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title">Thống kê bán hàng</h5>
								<small class="dbx-card__sub">Doanh số theo tháng</small>
							</div>
							<div class="dbx-card__filter">
								<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
									{foreach from=$list_years item = _year}
									<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">Năm {$_year}</option>
									{/foreach}
								</select>
							</div>
						</div>
						<div class="dbx-card__body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_person_chart" 
						data-options="{ldelim}{rdelim}" gId="{$gId}">
							<div class="d-flex align-items-end justify-content-center gap-5 w-100 px-3 h-px-250">
								<div class="animate-bg w-px-50 rounded-2 h-px-200"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-200"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-150"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-100"></div>
								{if $deviceType ne 'phone'}
								<div class="animate-bg w-px-50 rounded-2 h-px-50"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-150"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-200"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-50"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-100"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-150"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-100"></div>
								<div class="animate-bg w-px-50 rounded-2 h-px-250"></div>
								{/if}
							</div>
						</div>
					</div>
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="dbx-card mb-2">
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-receipt"></i></span>
							<h5 class="dbx-card__title">Giao dịch gần đây</h5>
							<a href="/giao-dich.html" class="dbx-card__link" title="Xem tất cả giao dịch"><i class="bx bx-link-external"></i></a>
						</div>
						<div class="dbx-card__body dbx-card__body--flush">
							<div class="overflow-x-auto text-nowrap">
								<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">
									<thead><tr>
										<th>Mã căn</th>
										<th>Ngày cọc</th>
										<th>Dự án</th>
										<th>Phân khu</th>
										<th class="text-center">Loại</th>
										<th class="text-end">Giá trị</th>
										<th class="text-center">Trạng thái</th>
									</tr></thead>
									<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=billing_me" data-options='{ldelim}{rdelim}'>
										{section name=i loop=$list_preloaders max=5}
										<tr>
											<td colspan="7"><div class="animate-bg h-px-15 w-100 rounded-2"></div></td>
										</tr>
										{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="dbx-card mb-2">
						<div class="dbx-card__head">
							<span class="dbx-card__ic"><i class="bx bx-history"></i></span>
							<div class="dbx-card__ttl">
								<h5 class="dbx-card__title">Lịch sử tra cứu</h5>
								<small class="dbx-card__sub">10 căn bạn xem gần nhất</small>
							</div>
							<a href="{$PCMS_URL}/report/stock.html" class="dbx-card__link" title="Tra cứu căn"><i class="bx bx-search-alt-2"></i></a>
						</div>
						<div class="dbx-card__body dbx-card__body--flush">
							<div class="overflow-x-auto text-nowrap">
								<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">
									<thead><tr>
										<th>Mã căn</th>
										<th>Phân khu</th>
										<th>PN · Hướng</th>
										<th class="text-end">Giá (VAT)</th>
										<th class="text-center">Trạng thái</th>
										<th class="text-end">Xem lúc</th>
									</tr></thead>
									<tbody class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_search_history" data-options='{ldelim}{rdelim}'>
										{section name=i loop=$list_preloaders max=5}
										<tr>
											<td colspan="6"><div class="animate-bg h-px-15 w-100 rounded-2"></div></td>
										</tr>
										{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-row mb-2">
						<div class="col-12 col-md-6 mb-2 mb-lg-0">
							{$core->getBlock('top_billing')}
						</div>
						<div class="col-12 col-md-6">
							{$core->getBlock('top_staff')}
						</div>
					</div>
				</div>
			</div>
			<!-- End Left Col -->
			<!-- Start Right Col -->
			<div class="col-12 col-md-12 col-lg-4 order-1">				
				<!-- Xác nhận thay đổi GD. -->
				{$core->getBlock("home_billing_confirm")}
			{if $deviceType ne 'phone'}		
				{if !empty($oneTraining)}
				<div class="card mb-2">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="card-title m-0"><span>Có thể có ích cho bạn</span></h5>
						<a href="{$clsISO->getLink('training')}" class="text-decoration-underline" title="Xem tất cả">Xem tất cả</a>
					</div>
					<div class="card-body mt-0">
						<div class="item_training h-100 card no-shadow overflow-hidden cursor-pointer position-relative overflow-hidden" onclick="$Core.global.training.open(this,event)" training_id="{$oneTraining.training_id}">
							<div class="image-scale">
								<img class="w-100 h-auto" src="{$oneTraining.image}" alt="{$oneTraining.title}" width="340" height="250">
							</div>
							<span class="position-absolute zindex-2 top-50 left-50 fs-50"  style="transform: translate(-50%,-50%);left: 50%;width: 60px" >
								<svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
									<path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f03"></path>
									<path d="M 45,24 27,14 27,34" fill="#fff"></path>
								</svg>
							</span>
							<div class="box_info position-absolute w-100 left-0 bottom-0 text-white zindex-1">
								<div class="p-3">
									<h3 class="title_training mb-2 limit_1line">{$oneTraining.title}</h3>
									<div class="author mb-2">bởi <strong>{$oneTraining.author}</strong></div>
									<div class="d-flex flex-wrap justify-content-between">
										<div class="mb-1 w-50 flex-fill">
											<i class='bx bx-video me-1' ></i>{$oneTraining.total_lesson} bài học
										</div>	
										<div class="mb-1 time w-50 flex-fill">
											<i class='bx bx-time me-1' ></i>{$clsISO->convertTimeMinute($oneTraining.time_training)}
										</div>		
										{if !empty($oneTraining.cat_name)}
											<div class="mb-1 time flex-fill">
												<i class='bx bx-book-content me-1'></i>{$oneTraining.cat_name}
											</div>
										{/if}
										{if !empty($oneTraining.total_profile_learning)}
											<div class="mb-1 time w-auto flex-fill">
												<i class='bx bx-user me-1' ></i>{$oneTraining.total_profile_learning} người đã học
											</div>
										{/if}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				{/if}				
				{$core->getBlock('note_calendar')}
				{if $oneProfile.role_id eq $smarty.const._ROLE_HEAD_HR_BO}
					<div class="card mb-2">
						<div class="card-header">
							<h5 class="d-flex align-items-center">
								<img class="mr-2" src="{$URL_IMAGES}/birthday.png" width="20" /> 
								<span>Chúc mừng sinh nhật</span>
							</h5>
							{assign var = toId value = $clsISO->getUniqid()}
							<ul class="nav nav-pills" role="tablist">
								<li class="nav-item js__birthday-tab-item">
									<button onClick="$Core.birthday.set_time(this, event)" toId="{$toId}" holderG="7days" type="button" class="nav-link js__birthday-tab-link" role="tab" 
									data-bs-toggle="tab" aria-selected="true">7 ngày</button>
								</li>
								<li class="nav-item js__birthday-tab-item">
									<button type="button" onClick="$Core.birthday.set_time(this, event)" toId="{$toId}" holderG="30days" class="nav-link js__birthday-tab-link active" role="tab">30 ngày</button>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content p-0">
								<div class="tab-pane fade show active" role="tabpanel">
									<div class="ajax" data-bind="{$toId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=staff_birthday" data-options="{ldelim}{rdelim}">
										<div class="loader text-center py-3">
											<img src="{$URL_IMAGES}/loading.gif" width="66px" />
											<p>Loading...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				{/if}
				{if $clsISO->checkPermissionGroup("HEAD_SALE")}
				<div class="log_stock ajax mb-2" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_top_search_stock" 
					data-options="{ldelim}{rdelim}">
					<div class="card h-100">
						<div class="card-header">
							<h5 class="card-title mb-0">Thống kê lượt tra cứu 24h qua</h5>
						</div>
						<div class="card-body" >
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
							<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
						</div>
					</div>
				</div>
				{/if}
				{if $clsISO->checkSale() || $clsISO->_DEV()}
					{$core->getBlock("target_sales")}				
				{/if}
				<div class="mb-2">
					{assign var = gId value = $clsISO->getUniqid()}
					{*{$core->getBlock('ranking_group', ['gId' => $gId])}*}
					<div class="card ranking h-100">
						<div class="card-body">
							{$core->getBlock('top_ranking')}
						</div>
					</div>
				</div>
				{assign var = gId value = $clsISO->getUniqid()}
				{$core->getBlock('ranking_dept', ['gId' => $gId])}
				<!-- {assign var = gId value = $clsISO->getUniqid()}
				{$core->getBlock('top_ranker', ['gId' => $gId])} -->
			{/if}
			</div>
		</div>
		{/if}
    </div>
</div>
{$scriptJs}