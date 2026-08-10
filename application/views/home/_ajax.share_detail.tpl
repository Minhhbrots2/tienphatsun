<div class="modal-dialog modal-dialog-scrollable{if $deviceType ne 'phone'} modal-ipad-xl{else} modal-sm{/if}">
	<div class="modal-content overflow-y">
		<div class="modal-header">
			{if $oneShare.share_type eq 'share'}				
			<div class="w-100 d-flex align-items-center justify-content-between">
				<div class="awe__post-profile d-flex align-items-center">
					<div class="awe__post-avatar position-relative">
						{$clsProfile->get_icon_verified($oneShare.user_id, $oneShare.db_profile.more_information)}
						<img class="rounded-pill" src="{$oneShare.db_profile.avatar}" 
							onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="44" height="44" /> 
					</div>
					<div class="awe__post-profile-body">
						<p class="awe__post-name">{$oneShare.db_profile.name}
							{if (empty($more_information.is_confirm) && ($clsISO->checkHeadSale($oneProfile.role_id) || $clsISO->_DEV()) )}		
							<a class="btn btn-success text-white rounded-pill btn-xs" onClick="$Core.global.share.confirm_share(this, event)" 
								share_id="{$share_id}" share_type="{$oneShare.share_type}" _type="pop">{$clsISO->makeIcon('bx bx-check-circle text-fs-12','Xác thực')}
							</a>
							{/if}
							<span class="text-success cursor-pointer share_confirm share_confirm_{$share_id} hover-tooltip">
							{if !empty($more_information.is_confirm)}
								<i class='bx bxs-badge-check fs-20' style="vertical-align:sub" ></i> Đã xác thực
								{if !empty($more_information.confirm)}
								<span class="tooltip-box">
									<strong>Xác thực</strong><br>
									Bởi: <strong>{$more_information.confirm.full_name}</strong><br>
									<span class="tooltip-time">{$more_information.confirm.time}</span>
								</span>
								{/if}
							{/if}
							</span>
						</p>
						<div class="d-flex gap-2 align-items-center">
							<span class="awe__post-star text-muted">{$oneShare.db_profile.more_information.department_name}</span>  • 
							<span class="awe__post-time text-muted">{$clsISO->getTimeAgo($oneShare.reg_date)}</span>
						</div>
					</div>
				</div>
			</div>
			{else}
			<h5 class="modal-title text-upper mb-0">{$oneShare.title}</h5>
			{/if}
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if $oneShare.share_type eq 'share'}
			<div class="awe__share-item item_detail" reg_date="{$oneShare.reg_date}" id="post_item_{$share_id}">
				<div class="awe__post-item-body mb-2">
					<div class="awe__post-title mb-2" share_id="{$share_id}" action="_detail">
						<a class="fs-16 font-normal awe__post-link" title="{$oneShare.title}">{$oneShare.title}</a>
					</div>
					<div class="form-row">
						<div class="col-12 col-md-7 mb-2 mb-lg-0 flex-fill">
							{if !empty($oneShare.images)}
							<div class="awe__post-gallery gallery rounded-3 overflow-hidden">
								{$clsShare->getImageGrid($share_id, $oneShare.images)}
							</div>
							{/if} 
						</div>
						{if !empty($oneShare.has_info)}
						<div class="col-12 col-md-5">
							{if $deviceType eq 'phone'}
								<div class="text-fs-13 d-flex flex-wrap mb-2 gap-1">
									{if !empty($more_information.customer_name)}
									<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
										<span class="text-muted text-nowrap"><i class="bx bx-user text-main"></i> KH:</span>
										{if !empty($more_information.customer_id) && ($oneShare.user_id eq $profile_id || $clsISO->_DEV())}
											<span class="lh-xs">{$more_information.customer_name}
											<a class="badge bg-label-warning text-nowrap fs-9 p-1" href="/crm/{$more_information.customer_id}" target="_blank" >CRM <i class='bx bx-link-external fs-9'></i></a></span>
										{else}
											<span class="lh-xs">{$more_information.customer_name}</span>
										{/if}
									</div>
									{/if}
									{if !empty($more_information.customer_phone)}
									<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
										<span class="text-muted text-nowrap"><i class="bx bx-phone text-primary"></i> Đ.thoại:</span>
										{if !empty($more_information.customer_id) && ($oneShare.user_id eq $profile_id || $clsISO->_DEV())}
											<span class="lh-xs ">{$clsShare->maskPhone($more_information.customer_phone)} <a class="badge bg-label-warning text-nowrap fs-9 p-1" href="/crm/{$more_information.customer_id}" target="_blank">CRM <i class='bx bx-link-external fs-9'></i></a>
											</span>
										{else}											
											<span class="lh-xs">
												{$clsShare->maskPhone($more_information.customer_phone)}
											</span>
										{/if}
									</div>
									{/if}
									{if !empty($more_information.location)}
									<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
										<span class="text-muted text-nowrap"><i class="bx bx-map text-danger"></i> Địa điểm:</span>
										<span class="lh-xs">{$more_information.location}</span>
									</div>
									{/if}
									{if !empty($more_information.guest_count)}
									<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
										<span class="text-muted text-nowrap"><i class="bx bx-group text-warning"></i> Số khách:</span>
										<span class="lh-xs">{$more_information.guest_count} khách</span>
									</div>
									{/if}
									{if !empty($more_information.target_ids)}
									<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
										<span class="text-muted text-nowrap"><i class='bx bx-target-lock text-warning'></i> Mục tiêu:</span>
										<span class="lh-xs">{$more_information.target_name}</span>
									</div>
									{/if}
									{if !empty($more_information.interest_ids)}
									<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
										<span class="text-muted text-nowrap"><i class='bx bx-anchor text-warning'></i> Quan tâm:</span>
										<span class="lh-xs">{$more_information.interest_name}</span>
									</div>
									{/if}
									{if !empty($more_information.status_name)}
									<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
										<span class="text-muted text-nowrap"><i class='bx bx-refresh text-warning'></i> Trạng thái:</span>
										<span class="lh-xs">{$more_information.status_name}</span>
									</div>
									{/if}
									{if !empty($more_information.budget_share)}
									<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
										<span class="text-muted text-nowrap"><i class='bx bx-refresh text-warning'></i> Ngân sách:</span>
										<span class="lh-xs">{$clsISO->shortNumber($more_information.budget_share)}</span>
									</div>
									{/if}
									{if !empty($more_information.project_budget_name)}
									<div class="d-flex align-items-center gap-2 bg-lighter p-2 rounded-2 flex-fill">
										<span class="text-muted text-nowrap"><i class='bx bx-refresh text-warning'></i> Dự án:</span>
										<span class="lh-xs">{$more_information.project_budget_name}</span>
									</div>
									{/if}
								</div>
								{if !empty($more_information.content)} 
								<div class="p-3 bg-label-warning rounded-2 mb-2">				
									{$more_information.content} 
								</div>
								{/if}
							{else}
								<div class="bg-lighter text-fs-15 d-flex flex-column rounded-2 gap-2 p-3 mb-2">
									<div class="d-flex align-items-center gap-2">
										<span class="text-muted text-nowrap"><i class="bx bx-user text-main"></i> Họ tên khách:</span>
										{if !empty($more_information.customer_id) && ($oneShare.user_id eq $profile_id || $clsISO->_DEV())}
											<span class="lh-xs">{if !empty($more_information.customer_name)}{$more_information.customer_name}{else}--{/if} 
											<a class="badge bg-label-warning text-nowrap fs-10" href="/crm/{$more_information.customer_id}" target="_blank" >CRM <i class='bx bx-link-external fs-10'></i></a></span>
										{else}
											{if !empty($more_information.customer_name)}
												<span class="lh-xs">{$more_information.customer_name}</span>
											{else}
												<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
											{/if}
										{/if}
									</div>
									<div class="d-flex align-items-center gap-2">
										<span class="text-muted text-nowrap"><i class="bx bx-phone text-primary"></i> Điện thoại:</span>
										{if !empty($more_information.customer_id) && ($oneShare.user_id eq $profile_id || $clsISO->_DEV())}
											<span class="lh-xs ">{if !empty($more_information.customer_phone)}
												{$clsShare->maskPhone($more_information.customer_phone)}{else}--{/if} <a class="badge bg-label-warning text-nowrap fs-10" href="/crm/{$more_information.customer_id}" target="_blank">CRM <i class='bx bx-link-external fs-10'></i></a>
											</span>
										{else}
											{if !empty($more_information.customer_phone)}
												<span class="lh-xs">{$more_information.customer_phone}</span>
											{else}
												<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
											{/if}
										{/if}
									</div>
									<div class="d-flex align-items-center gap-2">
										<span class="text-muted text-nowrap"><i class="bx bx-map text-danger"></i> Địa điểm:</span>
										{if !empty($more_information.location)}
											<span class="lh-xs">{$more_information.location}</span>
										{else}
											<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
										{/if}
									</div>
									<div class="d-flex align-items-center gap-2">
										<span class="text-muted text-nowrap"><i class="bx bx-group text-warning"></i> Số khách:</span>
										{if !empty($more_information.guest_count)}
											<span class="lh-xs">{$more_information.guest_count} khách</span>
										{else}
											<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
										{/if}
									</div>
									<div class="d-flex align-items-start gap-2">
										<span class="text-muted text-nowrap"><i class='bx bx-target-lock text-warning'></i> Mục tiêu:</span>
										{if !empty($more_information.target_name)}
											<span class="lh-xs">{$more_information.target_name} khách</span>
										{else}
											<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
										{/if}
									</div>
									<div class="d-flex align-items-center gap-2">
										<span class="text-muted text-nowrap"><i class='bx bx-anchor text-warning'></i> Quan tâm:</span>
										{if !empty($more_information.interest_name)}
											<span class="lh-xs">{$more_information.interest_name}</span>
										{else}
											<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
										{/if}
									</div>
									<div class="d-flex align-items-center gap-2">
										<span class="text-muted text-nowrap"><i class='bx bx-refresh text-warning'></i> Trạng thái:</span>
										{if !empty($more_information.status_name)}
											<span class="lh-xs">{$more_information.status_name} khách</span>
										{else}
											<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>
										{/if}
									</div>
									{if !empty($more_information.budget_share)}
										<div class="d-flex align-items-center gap-2">
											<span class="text-muted text-nowrap"><i class='bx bx-money text-warning'></i> Ngân sách:</span>
											<span class="lh-xs">{$clsISO->shortNumber($more_information.budget_share)}</span>
										</div>
									{/if}
									{if !empty($more_information.project_budget_name)}
										<div class="d-flex align-items-center gap-2">
											<span class="text-muted text-nowrap"><i class='bx bx-building text-warning'></i> Dự án:</span>
											<span class="lh-xs">{$more_information.project_budget_name}</span>
										</div>
									{/if}
								</div>
								{if !empty($more_information.content)} 
								<div class="p-3 bg-label-warning rounded-2 mb-2">
									<p class="text-muted mb-1 text-nowrap">Mô tả:</p>					
										{$more_information.content} 
								</div>
								{/if}
							{/if}
						</div>
						{/if}
					</div>
				</div>
				<div class="txt_like_{$share_id} mb-2">
					<a href="javascript:void(0)" class="" data-url="/index.php?mod=home&act=load_list_like&share_id={$share_id}" 
						data-toggle="webui-popover" data-trigger="hover" data-width="300" >
						{if $clsShare->checkLiked($share_id, $oneShare)}
							{if $oneShare.total_liked gt 1}
								Bạn và {$oneShare.total_liked - 1} người đã thích bài viết này
							{else}
								Bạn đã thích bài viết này
							{/if}
						{elseif !empty($oneShare.total_liked)}
							{$oneShare.total_liked} người đã thích bài viết này
						{/if}
					</a>
				</div>
				<div class="awe__post-cmd border-bottom mb-3">
					<div class="d-flex justify-content-center">
						{assign var = total_liked value = $oneShare.total_liked}
						<a href="javascript:void(0);" share_id="{$share_id}" class="awe__post-action awe__share-like_{$share_id} awe__share-like-action">
							{if $clsShare->checkLiked($share_id, $oneShare)}
								{if $total_liked gt '0'}
									{$clsISO->makeIcon('bxs-heart text-main',$total_liked|cat:' Thích')}
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
			{else}
			<div class="awe__post-item awe__share-item" reg_date="{$oneShare.reg_date}" id="post_item_{$share_id}">
				<div class="w-100 d-flex align-items-center justify-content-between mb-3">
					<div class="awe__post-profile d-flex" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$oneShare.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400">
						<div class="awe__post-avatar">
							<img class="rounded" src="{$oneShare.db_profile.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="44" height="44" /> 
						</div>
						<div class="awe__post-profile-body">
							<p class="awe__post-name">{$oneShare.db_profile.name}</p>
							<div class="d-flex align-items-center">
								{if !empty($oneShare.db_profile.level)}
								<span class="awe__post-level mr-2 text-muted">
									{$oneShare.db_profile.level}
								</span>
								{/if}
								<span class="awe__post-star mr-2 text-muted">
									{$oneShare.db_profile.html_star}
								</span>
								<span class="awe__post-time text-muted">
									{$clsISO->getTimeAgo($oneShare.reg_date)}
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="awe__post-item-body">
					{if !empty($oneShare.images)}
					<div class="awe__post-gallery gallery mb-3">
						<div id="gallery-grid-{$share_id}"></div>
					</div>
					{/if} 
					{if $oneShare.share_type ne 'secret'}
						<div class="awe__post-title mb-2 d-flex flex-column" share_id="{$share_id}" action="_detail">
							<a class="fs-16 font-normal awe__post-link flex-fill" title="{$_title}">{$_title}</a>
							{if !empty($oneShare.lst_staff_id)}
							<div class="d-flex flex-wrap align-items-center">
								<span class="text-muted fw-normal fs-11">cùng với</span>
								{foreach from=$oneShare.lst_staff_id item=staff_id key=k}
								<div class="awe__post-avatar position-relative ml-1 fs-6" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$staff_id}" data-toggle="webui-popover" data-trigger="hover" data-width="320">
									<img class="rounded-pill" width="20" height="20" 
									src="{$clsProfile->getAvatar($staff_id)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" /> 
									{$clsProfile->getFullName($staff_id)}
								</div>
								{/foreach}
							</div>
							{/if}
						</div>
					{/if}
					{if !empty($more_information.content)}
						<div class="awe__post-description mb-2" action="_detail">
							{$more_information.content}
						</div>
					{/if}
					{if !empty($list_images)}
					<div class="awe__post-gallery gallery mb-2">
						{$clsShare->getImageGrid($share_id, $list_images)}
					</div>
					{/if} 
				</div>
				<div class="awe__post-cmd">
					<div class="d-flex justify-content-center">
						<a href="javascript:void(0);" share_id="{$share_id}" 
						class="awe__post-action awe__share-like-action">
							{assign var = total_liked value = $oneShare.total_liked}
							{if $clsShare->checkLiked($news_id, $oneShare)}
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
						<a href="javascript:void(0);" class="awe__post-action awe__post-share-action" 
						data-toggle="modal" data-target="#sharer"><i class="bx bx-share"></i> Chia sẻ</a>
					</div>
				</div>
			</div>
			{/if}
		</div> 
	</div>
</div>