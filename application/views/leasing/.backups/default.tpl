<script type="text/javascript">
	var type_list = '{$type_list}', 
		current_page = '{$current_page}';
</script>
<link rel="stylesheet" href="{$URL_CSS}/swiper-bundle.min.css?v={$upd_version}" media="all" />
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap align-items-center justify-content-between py-2">
		<div class="leasing_left">
			<h5 class="fw-bold mb-1">Thuê và cho thuê căn hộ tại Vinhomes Ocean Park</h5>
			<span class="srp-total-count text-muted">Hiện có <strong class="total-stock text-main">0</strong> căn hộ đang cho thuê.</span>
		</div>
		<div class="d-flex d-flex flex-wrap box_btn_leasing">
			{if $clsISO->checkPermission('manager_transfer') && $type_list ne 'manager' && $profile_id != 118 && 1==2}
				<a href="{$PCMS_URL}/ct/manager/" title="Quản trị tin đăng" 
			   class="d-flex flex-fill align-items-center btn text-white {if $deviceType eq 'phone'}mb-2 w-100{else} me-2{/if}" style="background: rgb(159,34,58)">
					{$clsISO->makeIcon('bx-user-check', '&nbsp;Quản trị tin đăng')}
				</a>
			{/if}
			{if $clsISO->checkPermission('create_transfer') && $type_list ne 'manager' && $profile_id != 118}
				{if $oneProfile.is_verified eq '1'}
					<a href="{$PCMS_URL}/ct/me/" title="Quản lý tin cho thuê" class="d-flex align-items-center btn btn-info me-2">
				{else}
					<a href="javascript:void(0)" onClick="$Core.showValidVertify(this,event)" title="Quản lý tin cho thuê" class="d-flex align-items-center btn btn-info me-2">
				{/if}
					{if $deviceType eq 'phone'}
						{$clsISO->makeIcon('bx-user-check', '&nbsp;Quản lý tin đăng')}
					{else}
						{$clsISO->makeIcon('bx-user-check', '&nbsp;Quản lý tin cho thuê')}
					{/if}
				</a>
			{/if}
			{if $clsISO->checkPermission('create_transfer') && $type_list ne 'manager' && $profile_id != 118}
				{if $oneProfile.is_verified eq '1'}
					<a href="{$PCMS_URL}/ct/add" title="Đăng tin cho thuê" class="d-flex align-items-center btn btn-primary">
				{else}
					<a href="javascript:void(0)" onClick="$Core.showValidVertify(this,event)" title="Đăng tin cho thuê" class="d-flex align-items-center btn btn-primary">
				{/if}
					{if $deviceType eq 'phone'}
						{$clsISO->makeIcon('bx-plus', '&nbsp;Đăng tin cho thuê')}
					{else}
						{$clsISO->makeIcon('bx-plus', '&nbsp;Đăng tin cho thuê')}
					{/if}				
				</a>
			{/if}
		</div>
		
	</div>
    <div class="card">
		<div class="card-header border-bottom om-xs:p-2">
			<form method="POST">
				{$core->getBlock('leasing_search')}
				<input type="hidden" class="search_field" data-field="page" name="page" value="{$page}">
			</form>
		</div>
		<div class="card-body pt-3">
			{if $deviceType eq 'phone'}
				{$core->getBlock('leasing_hot')}
				{if !empty($leasing_first)}
					{if $clsISO->checkItemInArray($leasing_first.leasing_id,$like_leasing)}
						{assign var=liked value=1}
					{else}
						{assign var=liked value=0}
					{/if}
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
						<div class="awe__leasing-item">
							<div class="awe__leasing-compact awe__leasing-compact-web">
								<div class="awe__leasing-media position-relative">
									{if !empty($leasing_first.label_status)}
										{$leasing_first.label_status}
									{/if}
									{if !empty($leasing_first.images)}
									<div id="slick_slider_{$clsISO->getUniqid()}" class="slick-slider awe__leasing-slider">
										{foreach name=k from=$leasing_first.images item = _oImg}
										{if $smarty.foreach.k.index == 8}
											{break}
										{/if}
										<div class="slick-slide position-relative  cursor-pointer" onClick="$Core.leasing.open_leasing(this, event)" 
											stock_code="{$clsLeasing->getCode($leasing_first)}" stock_id="{$leasing_first.stock_id}" leasing_id="{$leasing_first.leasing_id}">
											<img class="awe__leasing-slider-overlay position-absolute zindex-1" src="{$_oImg}" />
											<div class="awe__leasing-slider-image w-100 h-100 d-flex justify-content-center align-items-center position-relative zindex-2">
												<img src="{$_oImg}" alt="{$leasing_first.title}" /> 
											</div>
										</div>
										{/foreach}
									</div>
									{else}
									<div class="awe__leasing-image cursor-pointer" onClick="$Core.leasing.open_leasing(this, event)" 
										stock_code="{$clsLeasing->getCode($leasing_first)}" stock_id="{$leasing_first.stock_id}" leasing_id="{$leasing_first.leasing_id}">
										<img class="awe__leasing-img" src="{$URL_IMAGES}/no-image.jpg" />
									</div>
									{/if}
									<a onclick="$Core.leasing.handleLike(this, {$leasing_first.leasing_id},'list')" id="like_{$leasing_first.leasing_id}" data-like_id="like_{$leasing_first.leasing_id}" data-bs-toggle="tooltip" data-placement="bottom" class="like {if $liked eq 1}liked{/if}" {if $liked eq 1}title="Bỏ thích"{else}title="Thích"{/if}><i class="fa fa-heart-o" aria-hidden="true"></i></a>
								</div>
								<div class="awe__leasing-body pointer-cursor" onClick="$Core.leasing.open_leasing(this, event)" 
									 stock_code="{$clsLeasing->getCode($leasing_first)}" stock_id="{$leasing_first.stock_id}" leasing_id="{$leasing_first.leasing_id}">
									<h3 class="awe__leasing-title mb-1">
										{if $leasing_first.is_locked eq '1'} 
										<i data-bs-toggle="tooltip" data-bs-trigger="hover" title="Đã khóa" class="material-icons-outlined fs-small text-danger">lock</i>
										{/if}
										<a href="javascript:void(0)" class="awe__leasing-link fs-5 text-dark" title="{$_title}">{$leasing_first.title}</a>
									</h3>
									<div class="d-flex align-items-center fs-12 awe__leasing-location py-1">
										<i class="re__icon-location--sm mr-1"></i>
										<span class="text-main">{$clsLeasing->getCode($leasing_first)}
											{if !empty($leasing_first.building_name)}
												, Tòa {$leasing_first.building_name}
											{/if}
											{if !empty($leasing_first.block_name)}
												, {$leasing_first.block_name}
											{/if}
										</span>
									</div>
									<div class="d-flex align-items-center awe__leasing-meta awe__leasing-config mb-1">
										<div class="mr-3 text_ellipsis d-flex align-items-center">
											<i class="re__icon-bedroom--sm mr-1"></i>
											<span class="text-main">{$leasing_first.bedroom}</span>
										</div>
										<div class="mr-3 text_ellipsis d-flex align-items-center">
											<i class="re__icon-size--sm mr-1"></i>
											<span class="text-main">{$stock_information.DT_TT}m<sup>2</sup></span>
										</div>
										<div class="mr-3 text_ellipsis d-flex align-items-center">
											<i class="re__icon-ying-yang--xl mr-1"></i>
											<span class="text-main">{$leasing_first.home_direction}</span>
										</div>
									</div>  
									<div class="d-flex flex-wrap align-items-center justify-content-between awe__leasing-meta awe__product-config">
										<div class="d-flex align-items-center fw-bold text_ellipsis">
											<i class="re__icon-money--sm mr-1"></i>
											<span class="text-main fs-18">{$clsISO->shortNumber($leasing_first.price)}/<span class="fs-12">1 tháng</span></span>
										</div>
										<div class="awe__leasing-date fs-11 text-muted">
											<i class="material-icons-outlined">update</i>
											{$clsISO->getTimeAgo($leasing_first.upd_date)}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			{literal}
				<script>
					$(document).ready(function(){
						$Core.leasing.init_slide();
					});					
				</script>
			{/literal}
				{/if}
				<div id="tableStock" class="overflow-x-auto mt-1 dragscroll text-nowrap" style="clear: both">
					<table class="table table-iloocal table-{$deviceType} table-bordered">
						<thead><tr>
							<th class="align-center bg-lighter"><a class="sort_click asc">Mã căn</a></th>
							<th class="align-center bg-lighter">Loại căn</th>
							<th class="align-center text-left bg-lighter">Giá</th>
							{if $type_list eq 'manager'}
								<th class="align-center text-left bg-lighter w-px-75">Xác minh</th>
								<th class="align-center text-left bg-lighter w-px-75">Duyệt</th>
							{/if}
							{if $type_list eq 'manager' or $type_list eq 'me'}
								<th class="align-center text-left bg-lighter w-px-50">Thống kê</th>
								<th class="align-center text-left bg-lighter w-px-40"></th>
							{/if}
						</tr></thead>
						<tbody class="holder_leasing">
							{section name=i loop=$list_preloaders start=2}
							<tr>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								{if $type_list eq 'manager'}
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								{/if}
							</tr>
							{/section}
						</tbody>
					</table>	
				</div>
				<div id="pagination-container" class="pagination d-flex justify-content-center mt-3"></div>
			{else}
				{if $_ss_view eq 'grid' and $type_list eq 'publish'}
					<div class="row">
						<div class="col-12 col-lg-9 col-xxl-9">
							{$core->getBlock('leasing_filter')}
							<div class="holder_leasing awe__leasing-list row">
								{section name=i loop=$list_preloaders}
								<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
									<div class="awe__leasing-item-preloader w-100 mb-3">
										<div class="logo-box mb-2">
											<div class="preloader h-px-175 rounded-3 animate-bg"></div>
										</div>
										<div class="col-content">
											<div class="animate-bg mb-2 rounded-1 h-px-20 w-60"></div>
											<div class="job-title mb-2">
												<div class="animate-bg rounded-1 h-px-20 mb-2"></div>
												<div class="animate-bg rounded-1 h-px-15"></div>
											</div>
											<div class="mb-2 text_ellipsis">
												<div class="animate-bg rounded-1 h-px-15 w-100"></div>
											</div>
											<div class="w-100 w-full d-flex align-items-center justify-content-between table-item">
												<div class="w-40">
													<div class="animate-bg rounded-1 h-px-15 w-100"></div>
												</div>
												<div class="w-40">
													<div class="animate-bg rounded-1 h-px-15 w-50"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								{/section}
							</div>
							<div id="pagination-container" class="pagination d-flex justify-content-center mt-3"></div>
						</div>
						<div class="col-12 col-lg-3 col-xxl-3">
							{*{$core->getBlock('sidebar-search')}*}
							<div id="box_sidebar_search"></div>
							{$core->getBlock('sidebar-hotline')}
							{$core->getBlock('ads')}
							<div class="sticky">
								{$core->getBlock('leasing_hot')}
							</div>
						</div>
					</div>
				{else}
					{$core->getBlock('leasing_filter')}
					<div class="overflow-x-auto">
						<table class="table table-iloocal table-{$deviceType} table-bordered">
							<thead><tr>
								{if $deviceType eq 'phone'}
								<th class="align-center bg-lighter"><a class="sort_click asc">Mã căn</a></th>
								<th class="align-center bg-lighter">Loại căn</th>
								<th class="align-center text-left bg-lighter">Giá</th>
								{else}
								<th width="10%" class="align-center bg-lighter">Mã căn</th>
								<th class="align-center bg-lighter"><a class="sort_click asc">Giá VAT</a></th>
								{if $type_list eq 'manager'}
								<th class="align-center bg-lighter">Loại căn</th>
								{else}
								<th class="align-center text-left bg-lighter">Thông thủy</th>
								<th class="align-center bg-lighter">Loại căn</th>
								{/if}
								<th class="align-center bg-lighter">Hướng BC</th>
								<th class="align-center bg-lighter">khoảng tầng</th> 
								<th class="align-center text-left bg-lighter">Người liên hệ</th>
								<th class="align-center text-left bg-lighter">Điện thoại</th>
								{/if}
								{if $type_list eq 'manager'}
									<th class="align-center text-left bg-lighter w-px-75">Xác minh</th>
									<th class="align-center text-left bg-lighter w-px-75">Duyệt</th>
								{/if}
								{if $type_list eq 'manager' or $type_list eq 'me'}
									<th class="align-center text-left bg-lighter w-px-50">Thống kê</th>
									<th class="align-center text-left bg-lighter w-px-40"></th>
								{/if}
							</tr></thead>
							<tbody class="holder_leasing">
								{section name=i loop=$list_preloaders}
								<tr>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									{if $deviceType ne 'phone'}
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
									{/if}								
									{if $type_list eq 'manager'}
									<th class="align-center text-center bg-lighter w-px-75">Xác minh</th>
									<th class="align-center text-center bg-lighter w-px-75">Duyệt</th>
									{/if}
									{if $type_list eq 'manager' || $type_list eq 'me'}						
									<th class="align-center text-left bg-lighter w-px-50">Thống kê</th>
									<th class="align-center text-left bg-lighter w-px-40"></th>
									{/if}
								</tr>
								{/section}
							</tbody>
						</table>	
					</div>		
					<div id="pagination-container" class="pagination d-flex justify-content-center mt-3"></div>
				{/if}
			{/if}
		</div>
	</div>
</div>
{if $_ss_add_stock eq '1' || $_ss_add_stock eq '2'}
<div class="modal fade show" style="display: block" id="modalTop" tabindex="-1" aria-modal="true" role="dialog">
	<div class="modal-dialog modal-xs {if $deviceType eq 'phone'} {/if} modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="d-flex py-3 flex-column justify-content-center">
					<div class="awe__reviews-success text-center"> 
						<img class="mb-3 w-px-300" src="https://ilooca.com/isocms/templates/default/skin/images/thankyou.svg">
						<h3 class="fs-5 mb-3">{$clsProfile->getFullName($profile_id, $oneProfile)} {if $_ss_add_stock eq '1'}đăng tin{else}sửa tin{/if} thành công!</h3>
						<div class="button-wrap"> 
							<button type="button" class="btn close_pop btn-outline-default" data-bs-dismiss="modal">Đóng lại</a> 
						</div> 
					</div> 
				</div> 
			</div>
		</div>
	</div>
</div>
{/if}
<script>
	var _TYPE_LOWFLOOR = `{$smarty.const._TYPE_LOWFLOOR}`;
</script>
<script src="{$URL_JS}/jquery.sharer.js?v={$upd_version}"></script>
{$scriptJs}
