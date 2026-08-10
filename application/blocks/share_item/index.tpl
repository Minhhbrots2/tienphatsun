{assign var = share_id value = $_oShare.share_id}
{assign var = _more_information value = $_oShare.more_information}
{assign var = _title value = $clsShare->getTitle($share_id, $_oShare)}
{if $clsISO->_DEV() || 1 eq 1}
	<div class="col-12 col-md-6 mb-2 awe__share-item" reg_date="{$_oShare.reg_date}" id="post_item_{$share_id}">
		<div class="post_item card h-100">
			<div class="card-body pb-1">
				<div class="w-100 d-flex align-items-center justify-content-between mb-3">
					<div class="awe__post-profile d-flex align-items-center">
						<div class="awe__post-avatar position-relative">
							{$clsProfile->get_icon_verified($_oShare.user_id, $_oShare.db_profile.more_information)}
							<img class="rounded-pill" src="{$_oShare.db_profile.avatar}" 
								onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="44" height="44" /> 
						</div>
						<div class="awe__post-profile-body">
							<p class="awe__post-name">{$_oShare.db_profile.name}
								<span class="text-success cursor-pointer share_confirm hover-tooltip share_confirm_{$share_id}">
									{if !empty($_more_information.is_confirm)}
										<i class='bx bxs-badge-check fs-20' style="vertical-align:sub"></i>Đã xác thực
										{if !empty($_more_information.confirm)}
											<span class="tooltip-box">
												<strong>Xác thực</strong><br>
												Bởi: <strong>{$_more_information.confirm.full_name}</strong><br>
												<span class="tooltip-time">{$_more_information.confirm.time}</span>
											</span>
										{/if}
									{/if}
								</span>
							</p>
							<div class="d-flex gap-2 align-items-center">
								{if !empty($_oShare.db_profile.level)}
								<span class="awe__post-level text-muted">{$_oShare.db_profile.level}</span>
								{/if}
								<span class="awe__post-star text-muted">{$_oShare.db_profile.more_information.department_name}</span>  • 
								<span class="awe__post-time text-muted">{$clsISO->getTimeAgo($_oShare.reg_date)}</span>
							</div>
						</div>
					</div>
					{if (($clsISO->checkHeadSale($oneProfile.role_id) || $clsISO->checkDEV()) && $_more_information.is_confirm eq '0') || ($_oShare.user_id eq $profile_id && $clsShare->checkCanEdit($share_id, $_oShare) eq '1')}
					<div class="dropdown">
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true">
							<i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-end w-px-125" data-popper-placement="bottom-end">
							{if ($clsISO->checkHeadSale($oneProfile.role_id) || $clsISO->checkDEV()) && $_more_information.is_confirm eq '0'}
							<a class="dropdown-item cursor-pointer text-success" onClick="$Core.share.confirm_share(this, event)" share_id="{$share_id}" 
							share_type="{$_oShare.share_type}" action="_edit">{$clsISO->makeIcon('bx bx-check-circle me-1', 'Xác thực')}</a>{/if}
							{if $_oShare.user_id eq $profile_id && $clsShare->checkCanEdit($share_id, $_oShare) eq '1' || $clsISO->checkDEV()}
							<a class="dropdown-item cursor-pointer" onClick="$Core.share.open(this, event)" 
								share_id="{$share_id}" share_type="{$_oShare.share_type}" action="_edit">{$clsISO->makeIcon('bx-edit-alt me-1', 'Sửa')}</a>
							<a class="dropdown-item cursor-pointer" onClick="$Core.share.delete_share(this, event)" 
								share_id="{$share_id}" >{$clsISO->makeIcon('bx-trash me-1', 'Xóa')}</a>
							{/if}
						</div>
					</div>
					{/if}
				</div>
				<div class="awe__post-item-body mb-2" onClick="$Core.share.open_share(this,event)" share_id="{$share_id}" >
					<div class="awe__post-title mb-2" share_id="{$share_id}" action="_detail">
						<a class="fs-16 font-normal awe__post-link" title="{$_title}">{$_title}</a>
					</div>
					{if !empty($_oShare.images)}
					<div class="awe__post-gallery gallery rounded-3 overflow-hidden">
						<div class="imgs-grid imgs-grid-1">
							<div class="imgs-grid-image">
								<div class="image-wrap">
									<img src="{$_oShare.image}" alt="{$_title}">
								</div>
							</div>
						</div>
					</div>
					{/if} 
				</div>
			</div>			
			<div class="card-body d-flex align-items-center justify-content-between pt-0">
				<div class="d-flex align-items-center gap-3">
					<a href="javascript:void(0);" share_id="{$share_id}" _type="home" class="awe__share-like-action awe__share-like_short_{$share_id} text-dark">
						{if $clsShare->checkLiked($share_id, $_oShare)}
							{if $_oShare.total_liked gt '0'}
								{$clsISO->makeIcon('bxs-heart text-main',$_oShare.total_liked)}
							{else}
								{$clsISO->makeIcon('bx-heart')}
							{/if}
						{else}
							{if $_oShare.total_liked gt '0'}
								{$clsISO->makeIcon('bx-heart',$_oShare.total_liked)}
							{else}
								{$clsISO->makeIcon('bx-heart')}
							{/if}
						{/if}
					</a>
					<a href="javascript:void(0);" class="awe__post-comment-action text-dark" onClick="$Core.share.open_share(this,event)" share_id="{$share_id}">
						<i class="bx bx-comment"></i> 
						<span class="txt_comment_{$share_id}">{if !empty($_oShare.total_comments)}{$_oShare.total_comments}{/if}</span>
					</a>
				</div>
				<div class="txt_like_{$share_id}">
					{if !empty($_oShare.total_liked)}
					<a href="javascript:void(0)" data-url="/index.php?mod=home&act=load_list_like&share_id={$share_id}" 
						data-toggle="webui-popover" data-trigger="hover" data-width="300" >
						{if $clsShare->checkLiked($share_id, $_oShare)}
							{if $_oShare.total_liked gt 1}
								Bạn và {$_oShare.total_liked - 1} người đã thích
							{else}
								Bạn đã thích bài viết này
							{/if}
						{elseif !empty($_oShare.total_liked)}
							{$_oShare.total_liked} người đã thích
						{/if}
					</a>
					{/if}
				</div>
			</div>
		</div>
	</div>
{else}
	<div class="awe__post-item awe__share-item" reg_date="{$_oShare.reg_date}" id="post_item_{$share_id}">
		<div class="w-100 d-flex align-items-center justify-content-between mb-3">
			<div class="awe__post-profile d-flex align-items-center">
				<div class="awe__post-avatar position-relative">
					{$clsProfile->get_icon_verified($_oShare.user_id, $_oShare.db_profile.more_information)}
					<img class="rounded-pill" src="{$_oShare.db_profile.avatar}" 
						onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="44" height="44" /> 
				</div>
				<div class="awe__post-profile-body">
					<p class="awe__post-name">{$_oShare.db_profile.name}
						<span class="text-success cursor-pointer share_confirm " title="Đã xác nhận">{if !empty($_more_information.is_confirm)}<i class='bx bxs-badge-check fs-20' style="vertical-align: sub" ></i>Đã xác thực{/if}</span>
					</p>
					<div class="d-flex gap-2 align-items-center">
						{if !empty($_oShare.db_profile.level)}
						<span class="awe__post-level text-muted">{$_oShare.db_profile.level}</span>
						{/if}
						<span class="awe__post-star text-muted">{$_oShare.db_profile.html_star}</span>
						<span class="awe__post-time text-muted">{$clsISO->getTimeAgo($_oShare.reg_date)}</span>
					</div>
				</div>

			</div>
			{if ($_oShare.user_id eq $profile_id && $clsShare->checkCanEdit($share_id, $_oShare) eq '1') || 
			(empty($_more_information.is_confirm) && ($clsISO->checkPermissionGroup("HEAD_SALE") || $clsISO->checkPermissionGroup("SALE_DIRECTOR") 
				|| $clsISO->checkPermissionGroup("SALE_DIRECTOR_ONLY") || $clsISO->checkPermissionGroup("BUSINESS_AREA")) )}		
				<div class="dropdown">
					<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true">
						<i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-end w-px-125" data-popper-placement="bottom-end">
						{if empty($_more_information.is_confirm)}
							<a class="dropdown-item cursor-pointer text-success" onClick="$Core.share.confirm_share(this, event)" 
							share_id="{$share_id}" share_type="{$_oShare.share_type}" action="_edit">{$clsISO->makeIcon('bx bx-check-circle me-1', 'Xác thực')}</a>
						{/if}
						{if $_oShare.user_id eq $profile_id && $clsShare->checkCanEdit($share_id, $_oShare) eq '1' || $clsISO->checkDEV()}
						<a class="dropdown-item cursor-pointer" onClick="$Core.share.open(this, event)" 
							share_id="{$share_id}" share_type="{$_oShare.share_type}" action="_edit">{$clsISO->makeIcon('bx-edit-alt me-1', 'Sửa')}</a>
						<a class="dropdown-item cursor-pointer" onClick="$Core.share.delete_share(this, event)" 
							share_id="{$share_id}" >{$clsISO->makeIcon('bx-trash me-1', 'Xóa')}</a>
						{/if}
					</div>
				</div>
			{/if}
		</div>
		<div class="awe__post-item-body mb-2">
			<div class="awe__post-title mb-2" share_id="{$share_id}" action="_detail">
				<a class="fs-16 font-normal awe__post-link" title="{$_title}">{$_title}</a>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-8 mb-2 mb-lg-0 flex-fill">
					{if !empty($_oShare.images)}
					<div class="awe__post-gallery gallery rounded-3 overflow-hidden">
						{$clsShare->getImageGrid($share_id, $_oShare.images)}
					</div>
					{/if} 
				</div>
				{if !empty($_oShare.has_info)}
				<div class="col-12 col-md-4">
					{if $clsISO->_DEV() && $deviceType eq 'phone'}
						<div class="text-fs-13 d-flex flex-wrap mb-2 gap-1">
							{if !empty($_more_information.customer_name)}
							<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
								<span class="text-muted text-nowrap"><i class="bx bx-user text-main"></i> Khách hàng:</span>
								<span class="lh-xs">{$_more_information.customer_name}</span>
							</div>
							{/if}
							{if !empty($_more_information.customer_phone)}
							<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
								<span class="text-muted text-nowrap"><i class="bx bx-phone text-primary"></i> Điện thoại:</span>
								<span class="lh-xs">
									{$clsShare->maskPhone($_more_information.customer_phone)}
								</span>
							</div>
							{/if}
							{if !empty($_more_information.location)}
							<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
								<span class="text-muted text-nowrap"><i class="bx bx-map text-danger"></i> Địa điểm:</span>
								<span class="lh-xs">{$_more_information.location}</span>
							</div>
							{/if}
							{if !empty($_more_information.guest_count)}
							<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
								<span class="text-muted text-nowrap"><i class="bx bx-group text-warning"></i> Số khách:</span>
								<span class="lh-xs">{$_more_information.guest_count} khách</span>
							</div>
							{/if}
							{if !empty($_more_information.target_ids)}
							<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
								<span class="text-muted text-nowrap"><i class='bx bx-target-lock text-warning'></i> Mục tiêu:</span>
								<span class="lh-xs">{$_more_information.target_name}</span>
							</div>
							{/if}
							{if !empty($_more_information.interest_ids)}
							<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
								<span class="text-muted text-nowrap"><i class='bx bx-anchor text-warning'></i> Quan tâm:</span>
								<span class="lh-xs">{$_more_information.interest_name}</span>
							</div>
							{/if}
							{if !empty($_more_information.status_name)}
							<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
								<span class="text-muted text-nowrap"><i class='bx bx-refresh text-warning'></i> Trạng thái:</span>
								<span class="lh-xs">{$_more_information.status_name}</span>
							</div>
							{/if}
						</div>
						{if !empty($_more_information.content)} 
						<div class="p-3 bg-label-warning rounded-2 mb-2">				
							{$_more_information.content} 
						</div>
						{/if}
					{else}
						<div class="bg-lighter text-fs-15 d-flex flex-column rounded-2 gap-2 p-3 mb-2">
							<div class="d-flex gap-2">
								<span class="text-muted text-nowrap"><i class="bx bx-user text-main"></i> Họ tên khách:</span>
								{if !empty($_more_information.customer_id) && ($_oShare.user_id eq $profile_id || $clsISO->_DEV())}
									<span class="lh-xs">{if !empty($_more_information.customer_name)}{$_more_information.customer_name}{else}--{/if} <a class="lh-xs text-nowrap fs-10" href="/crm/{$_more_information.customer_id}" target="_blank" >CRM <i class='bx bx-link-external fs-10'></i></a></span>
								{else}
									<span class="lh-xs">{if !empty($_more_information.customer_name)}{$_more_information.customer_name}{else}--{/if}</span>
								{/if}
							</div>
							<div class="d-flex gap-2">
								<span class="text-muted text-nowrap"><i class="bx bx-phone text-primary"></i> Điện thoại:</span>
								{if !empty($_more_information.customer_id) && ($_oShare.user_id eq $profile_id || $clsISO->_DEV())}
									<span class="lh-xs">{if !empty($_more_information.customer_phone)}
										{$clsShare->maskPhone($_more_information.customer_phone)}{else}--{/if} <a class="lh-xs text-nowrap fs-10" href="/crm/{$_more_information.customer_id}" target="_blank">CRM <i class='bx bx-link-external fs-10'></i></a>
									</span>
								{else}
									<span class="lh-xs">{if !empty($_more_information.customer_phone)}
										{$clsShare->maskPhone($_more_information.customer_phone)}{else}--{/if}
									</span>
								{/if}

							</div>
							<div class="d-flex align-items-center gap-2">
								<span class="text-muted text-nowrap"><i class="bx bx-map text-danger"></i> Địa điểm:</span>
								<span class="lh-xs">{if !empty($_more_information.location)}{$_more_information.location}{else}--{/if}</span>
							</div>
							<div class="d-flex align-items-center gap-2">
								<span class="text-muted text-nowrap"><i class="bx bx-group text-warning"></i> Số khách:</span>
								<span class="lh-xs">{if !empty($_more_information.guest_count)}{$_more_information.guest_count}{else}--{/if} khách</span>
							</div>
							<div class="d-flex align-items-start gap-2">
								<span class="text-muted text-nowrap"><i class='bx bx-target-lock text-warning'></i> Mục tiêu:</span>
								<span class="lh-xs">{if !empty($_more_information.target_ids)}{$_more_information.target_name}{else}--{/if}</span>
							</div>
							<div class="d-flex align-items-center gap-2">
								<span class="text-muted text-nowrap"><i class='bx bx-anchor text-warning'></i> Quan tâm:</span>
								<span class="lh-xs">{if !empty($_more_information.interest_ids)}{$_more_information.interest_name}{else}--{/if}</span>
							</div>
							<div class="d-flex align-items-center gap-2">
								<span class="text-muted text-nowrap"><i class='bx bx-refresh text-warning'></i> Trạng thái:</span>
								<span class="lh-xs">{if !empty($_more_information.status_name)}{$_more_information.status_name}{else}--{/if}</span>
							</div>
						</div>
						{if !empty($_more_information.content)} 
						<div class="p-3 bg-label-warning rounded-2 mb-2">
							<p class="text-muted mb-1 text-nowrap">Mô tả:</p>					
								{$_more_information.content} 
						</div>
						{/if}
					{/if}


				</div>
				{/if}
			</div>
		</div>
		{if $clsISO->_DEV()}
			<a href="javascript:void(0)" class="txt_like" onClick="$Core.share.open_like(this,event)" share_id="{$share_id}" >
				{if $clsShare->checkLiked($share_id, $_oShare)}
					{if $_oShare.total_liked gt 1}
						Bạn và {$_oShare.total_liked - 1} người đã thích bài viết này
					{else}
						Bạn đã thích bài viết này
					{/if}
				{elseif !empty($_oShare.total_liked)}
					{$_oShare.total_liked} người đã thích bài viết này
				{/if}
			</a>
		{/if}
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
		{$core->getBlock('comment', [
			'table_id' => $share_id, 
			'clsTable' => 'Share'
		])}
		{literal}
		<script type="text/javascript">
			$(function(){
				$Core.news.load_comments({/literal}{$share_id}{literal},'Share',{'action':'reload'});
			});
		</script>
		{/literal}
	</div>
{/if}
