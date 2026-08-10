<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-xxl-10 mb-2 offset-xxl-1">
			{if $share_type ne 'secret'}
			<h4 class="fw-bold mb-1"><span>{$titlePage}</span></h4>
			<p class="text-muted mb-0">Ghi nhận những hoạt động nổi bật tại {$smarty.const.BRAND_NAME}</p>
			{else}
			<h4 class="fw-bold mb-1"><span>{$titlePage}</span></h4>
			<p class="text-muted mb-0">Ghi nhận những hoạt động nổi bật tại {$smarty.const.BRAND_NAME}</p>
			{/if}
		</div>
	</div>
	{if $deviceType eq 'phone' && $clsISO->checkSale()}
		<div id="holder_report_chart" class="mb-2">
			<div class="card">
				<div class="card-header d-flex align-items-center gap-2">
					<i class='bx bxs-check-circle text-success fs-30' ></i>
					<div class="d-flex flex-wrap align-items-end gap-1">
						<h3 class="card-title mb-0">Hoạt động của bạn</h3>
					</div>
				</div>
				<div class="card-body">
					<div class="card p-2 mb-3">
						<div class="form-row mb-2" style="row-gap: 10px">
							<div class="col-6">
								<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
									<i class='bx bxs-group text-info'></i>
									<span class="">Lượt tiếp khách</span>
									<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
								</div>
							</div>
							<div class="col-6">
								<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
									<i class='bx bxs-group text-success'></i>
									<span class="">Tổng khách</span>
									<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
								</div>
							</div>
							<div class="col-6">
								<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
									<i class='bx bxs-map text-success ' ></i>
									<span class="">Sale có hoạt động</span>
									<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
								</div>
							</div>
							<div class="col-6">
								<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
									<i class='bx bx-building text-info' ></i>
									<span class="">Phòng KD hoạt động</span>
									<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
								</div>
							</div>
						</div>
						<div class="alert alert-success py-2 mb-0"><i class='bx bx-trending-up'></i> <div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></div>
					</div>

					<div class="box_progess">
						<h3 class="">Phòng kinh doanh</h3>
						<div class="mb-3">
							<div class="d-flex gap-2 align-items-center">
								<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
								<div class="progress w-70" style="height: 15px;">
								  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
								</div>
								<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
							</div>
							<div class="d-flex gap-2 align-items-center">
								<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
								<div class="progress w-70" style="height: 15px;">
								  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
								</div>
								<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
							</div>
							<div class="d-flex gap-2 align-items-center">
								<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
								<div class="progress w-70" style="height: 15px;">
								  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
								</div>
								<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
							</div>
						</div>
						<div class="alert alert-warning py-2 mb-0 text-main"><i class='bx bx-info-circle' ></i> 18 sale chưa tiếp khách</div>
					</div>
				</div>
			</div>
		</div>
	{/if}
	<div class="row">
		<div class="col-12 col-lg-8 col-xxl-7 offset-xxl-1 mb-2 mb-lg-0">
			<div class="awe__post-page awe__post-page_{$share_type}">
				<div class="awe__post-form bg-white radius-4 mb-3">
					<div class="d-flex align-items-center w-100">
						<div class="awe__post-avatar position-relative">
							{if $loggedIn eq '1'}
							<img class="rounded-pill" src="{$clsProfile->getAvatar($profile_id,$oneProfile)}" width="44" height="44" />
							{$clsProfile->get_icon_verified($profile_id, $oneProfile.more_information)}
							{else}
							<img class="rounded-pill" src="{$URL_IMAGES}/no-avatar.jpg?v={$upd_version}" width="44" height="44" />
							{/if}
						</div>
						<div class="textarea">
							<a class="awe__post-input-link cursor-pointer" data-toggle="ripple" onClick="$Core.share.open(this, event)" share_type="{$share_type}" share_id="0" action="_add">{if $share_type eq 'share'}Tạo mới nét đẹp lao động{elseif $share_type eq 'secret'}Tạo mới thông tin mật {else}Tạo mới vinh danh chiến binh FH{/if}</a>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				{if !empty($list_shares)}
				<div class="awe__list-post awe__list-share">
					{if $share_type eq 'share'}
						<div class="form-row">
							{foreach name=i from=$list_shares item = _oShare}
								{$core->getBlock('share_item', ['_oShare' => $_oShare])}
							{/foreach} 
						</div>
					{else}
						{foreach name=i from=$list_shares item = _oShare}
							{if $share_type eq 'share'}
								{$core->getBlock('share_item', ['_oShare' => $_oShare])}
							{else}
								{assign var = share_id value = $_oShare.share_id}
								{assign var = _more_information value = $_oShare.more_information}
								{assign var = _title value = $clsShare->getTitle($share_id, $_oShare)}
								<div class="awe__post-item awe__share-item" reg_date="{$_oShare.reg_date}" id="post_item_{$share_id}">
									<div class="w-100 d-flex align-items-center justify-content-between mb-3 post_header">
										<div class="awe__post-profile d-flex" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oShare.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="300">
											<div class="awe__post-avatar position-relative">
												<img class="rounded-pill" width="44" height="44" src="{$_oShare.db_profile.avatar}" 
												onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" /> 
												{$clsProfile->get_icon_verified($_oShare.user_id, $_oShare.db_profile.more_information)}
											</div>
											<div class="awe__post-profile-body">
												<p class="awe__post-name">[{$_oShare.db_profile.department_name}]{$_oShare.db_profile.name}</p>
												<div class="d-flex align-items-center">
													{if !empty($_oShare.db_profile.level)}
													<span class="awe__post-level mr-2 text-muted">{$_oShare.db_profile.level}</span>
													{/if}
													<span class="awe__post-star mr-2 text-muted">{$_oShare.db_profile.html_star}</span>
													<span class="awe__post-time text-muted">{$clsISO->getTimeAgo($_oShare.reg_date)}</span>
												</div>
											</div>
										</div>
										<div class="dropdown">
											<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
												<i class="bx bx-dots-vertical-rounded"></i>
											</button>
											<div class="dropdown-menu dropdown-menu-end w-px-100" data-popper-placement="bottom-end">
												{if $_oShare.user_id eq $profile_id and $clsShare->checkCanEdit($share_id, $_oShare) eq '1' || $clsISO->checkDEV()}
												<a class="dropdown-item cursor-pointer" onClick="$Core.global.share.open(this, event)" share_id="{$share_id}" share_type="{$_oShare.share_type}" action="_edit">{$clsISO->makeIcon('bx-edit-alt me-1', 'Sửa')}</a>
												<a class="dropdown-item cursor-pointer" onClick="$Core.share.delete_share(this, event)" share_type="{$_oShare.share_type}" share_id="{$share_id}">{$clsISO->makeIcon('bx-trash me-1', 'Xóa')}</a>
												{/if}
											</div>
										  </div>
									</div>
									<div class="awe__post-item-body">
										{if $share_type ne 'secret'}
										<div class="awe__post-title mb-2 d-flex flex-column" share_id="{$share_id}" action="_detail">
											<a class="fs-16 font-normal awe__post-link flex-fill" title="{$_title}">{$_title}</a>
											{if !empty($_oShare.lst_staff_id)}
											<div class="d-flex flex-wrap align-items-center">
												<span class="text-muted fw-normal fs-11">cùng với</span>
												{foreach from=$_oShare.lst_staff_id item=staff_id key=k}
												<div class="awe__post-avatar position-relative ml-1 fs-6" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$staff_id}" data-toggle="webui-popover" 
													data-trigger="hover" data-width="300">
													<img class="rounded-pill" width="20" height="20" 
													src="{$clsProfile->getAvatar($staff_id)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" /> 
													{$clsProfile->getFullName($staff_id)}
												</div>
												{/foreach}
											</div>
											{/if}
										</div>
										{/if}
										{if $share_type eq 'secret'}
										<div class="awe__post-meta mb-2 text-muted">
											<p class="mb-1">Từ ngày: {$_more_information.start_date|date_format:'%d/%m/%Y'} - Tới ngày:  {$_more_information.end_date|date_format:'%d/%m/%Y'}</p>
											<p class="mb-0">Dự án: {if !empty($_more_information.project_id)} 
												{$_more_information.project_name}
											{else}--{/if}</p>
										</div>
										{/if}
										{if !empty($_more_information.content)}
											<div class="awe__post-description mb-2" action="_detail">
												{$_more_information.content}
											</div>
										{/if}
										{if !empty($_oShare.images)}
										<div class="awe__post-gallery gallery mb-2">
											{$clsShare->getImageGrid($share_id, $_oShare.images)}
										</div>
										{/if} 
									</div>
									<div class="awe__post-cmd border-bottom mb-3">
										<div class="d-flex justify-content-center">
											{assign var = total_liked value = $_oShare.total_liked}
											<a href="javascript:void(0);" share_id="{$share_id}" class="awe__post-action awe__share-like-action">
												{if $clsShare->checkLiked($share_id, $_oShare)}
													{if $total_liked gt '0'}
														{$clsISO->makeIcon('bxs-heart',$total_liked|cat:' Thích')}
													{else}
														{$clsISO->makeIcon('bx-heart', 'Thích')}
													{/if}
												{else}
													{if $total_liked gt '0'}
														{$clsISO->makeIcon('bx-heart',$total_liked|cat:' Thích')}
													{else}
														{$clsISO->makeIcon('bx-heart','Thích')}
													{/if}
												{/if}
											</a>
											<a href="javascript:void(0);" class="awe__post-action awe__post-comment-action" 
											data-toggle="modal" data-target="#sharer"><i class="bx bx-comment"></i> Bình luận</a>
											<a href="javascript:void(0);" class="awe__post-action awe__post-share-action" 
											data-toggle="modal" data-target="#sharer"><i class="bx bx-share"></i> Chia sẻ</a>
										</div>
									</div>
									{$core->getBlock('comment', ['table_id' => $share_id, 'clsTable' => 'Share'])}
									{literal}
									<script type="text/javascript">
										$(function(){
											$Core.news.load_comments({/literal}{$share_id}{literal},'Share',{'action':'reload'});
										});
									</script>
									{/literal}
								</div>
							{/if}
						{/foreach} 
					{/if}
					
				</div>
				{else}
					<div class="p-6 bg- no-result text-center">
						<img src="{$URL_IMAGES}/empty.svg?v={$upd_version}" width="100px" />
						<p class="text-muted mt-3">Không có ghi chú nào được tạo</p>
					</div>
				{/if}
				<div class="clearfix"></div>
				{if $total_record gt $per_page}
				<div class="d-flex justify-content-center">
					<button data-toggle="ripple" onClick="$Core.share.load_more(this, event)" share_type="{$share_type}" total_loaded="{$per_page}" 
						class="btn btn-block btn-lg btn-link bg-white font-weight-bold" total_record="{$total_record}">Xem thêm</button>
				</div>
				{/if}
		   </div>
		</div>
		{if $deviceType ne 'phone'}
		<div class="col-12 col-xxl-3 col-md-4">
			<div class="sticky d-none d-lg-block">
				<div class="card mb-2">
					<div class="card-body">
						<div class="box_standard mb-2" data-fancybox="standard" data-src="{$URL_IMAGES}/quy-chuan-tiep-khach.png">
						<img src="{$URL_IMAGES}/quy-chuan-tiep-khach.png" alt="" class="w-100 h-auto">
					</div>
					</div>
				</div>
				{if $clsISO->checkSale()}
					<div id="holder_report_chart" class="mb-2">
						<div class="card">
							<div class="card-header d-flex align-items-center gap-2">
								<i class='bx bxs-check-circle text-success fs-30' ></i>
								<div class="d-flex flex-wrap align-items-end gap-1">
									<h3 class="card-title mb-0">Hoạt động của bạn</h3>
								</div>
							</div>
							<div class="card-body">
								<div class="card p-2 mb-3">
									<div class="form-row mb-2" style="row-gap: 10px">
										<div class="col-6">
											<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
												<i class='bx bxs-group text-info'></i>
												<span class="">Lượt tiếp khách</span>
												<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
											</div>
										</div>
										<div class="col-6">
											<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
												<i class='bx bxs-group text-success'></i>
												<span class="">Tổng khách</span>
												<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
											</div>
										</div>
										<div class="col-6">
											<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
												<i class='bx bxs-map text-success ' ></i>
												<span class="">Sale có hoạt động</span>
												<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
											</div>
										</div>
										<div class="col-6">
											<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">
												<i class='bx bx-building text-info' ></i>
												<span class="">Phòng KD hoạt động</span>
												<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
											</div>
										</div>
									</div>
									<div class="alert alert-success py-2 mb-0"><i class='bx bx-trending-up'></i> <div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></div>
								</div>

								<div class="box_progess">
									<h3 class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></h3>
									<div class="mb-3">
										<div class="d-flex gap-2 align-items-center">
											<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
											<div class="progress w-70" style="height: 15px;">
											  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
											</div>
											<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
										</div>
										<div class="d-flex gap-2 align-items-center">
											<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
											<div class="progress w-70" style="height: 15px;">
											  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
											</div>
											<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
										</div>
										<div class="d-flex gap-2 align-items-center">
											<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
											<div class="progress w-70" style="height: 15px;">
											  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
											</div>
											<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>
										</div>
									</div>
									<div class="alert alert-warning py-2 mb-0 text-main"><i class='bx bx-info-circle' ></i> <div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></div>
								</div>
							</div>
						</div>
					</div>				
				{/if}
				
				<div id="holder_report_top_share" class="mb-2">
					<div class="card h-100 {if $mod eq 'home' && $act eq 'news'} no-shadow{/if}{$class}">
						{assign var = uid value = $clsISO->getUniqid()}
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title m-0 me-2">Top 10 sale tiếp khách nhiều nhất</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div class="card-body">
							<ul class="p-0 m-0">
								{section name = i loop=10 start=0 step=1}
								<li class="d-flex mb-3 pb-1">
									<div class="avatar flex-shrink-0 me-2">
										<div class="animate-bg w-100 h-100 rounded"></div>
									</div>
									<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100">
										<div class="me-2">
											<div class="animate-bg radius-2 w-50 mb-1" style="height:10px">FH000</div>
											<div class="animate-bg radius-2 w-100" style="height:15px">{$oneProfile.full_name}</div>
										</div>
										<div class="user-progress d-flex align-items-center gap-1">
											<h6 class="d-flex align-items-center mb-0">
												<span class="animate-bg mr-2" style="height:15px">00</span> tỷ
											</h6>
										</div>
									</div>
								</li>
								{/section}
							</ul>
						</div>
					</div>						
				</div>
				{*<div class="card mb-2">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="card-title m-0 me-2">Giao dịch mới</h5>
					</div>
					<div class="card-body ajax load" data-bind="{$uid}" 
						data-url="{$PCMS_URL}/index.php?mod={$mod}&act=dashboard&tp=top_billing" data-options='{ldelim}{rdelim}'>
						<ul class="p-0 m-0">
							{section name = i loop=$arr_preloaders}
							<li class="d-flex mb-2 pb-1">
								<div class="avatar flex-shrink-0 me-2">
									<div class="animate-bg w-100 h-100 rounded-pill"></div>
								</div>
								<div class="d-flex align-items-center justify-content-between gap-2 w-100">
									<div class="me-1">
										<div class="animate-bg radius-2 w-50 mb-1 h-px-15">FH000</div>
										<div class="animate-bg radius-2 w-100 h-px-15">{$oneProfile.full_name}</div>
									</div>
									<div class="user-progress d-flex align-items-center gap-1">
										<span class="animate-bg fs-13 mr-1  h-px-15">00</span>lần
									</div>
								</div>
							</li>
							{/section}
						</ul>
					</div>	
				</div>
				<div class="mb-0">
					{$core->getBlock('top_staff')}
				</div>*}
			</div>
		</div>
		{/if}
	</div>
</div>
{$scriptJs}
{literal}
<style type="text/css">
	.awe__post-form,
	.awe__post-item{
		box-shadow:0px 0px 6px rgb(173 168 168 / 20%);
		-moz-box-shadow:0px 0px 6px rgb(173 168 168 / 20%);
		-webkit-box-shadow:0px 0px 6px rgb(173 168 168 / 20%);
		-khtml-box-shadow:0px 0px 6px rgb(173 168 168 / 20%);
	}
	.selecttize-lg .selectize-dropdown, 
	.selecttize-lg .selectize-input, 
	.selecttize-lg .selectize-input input{
		line-height:36px !important;
	}
	@media screen and (min-width:1200px){
		.sticky{ top:86px;}
	}
	.imgs-grid .imgs-grid-image .image-wrap img{
		max-height:400px;
	}
	.post_item .imgs-grid .imgs-grid-image .image-wrap img {
		max-height: 250px;
		height: 250px;
		width: 100%;
		object-fit: cover;
	}
	.item_detail .imgs-grid .imgs-grid-image .image-wrap img {
		max-height: 370px;
		width: 100%;
		object-fit: cover;
	}
</style>
{/literal}