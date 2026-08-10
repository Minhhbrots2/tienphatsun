<script type="text/javascript">
	var _NEWS_EVENT_CAT_ID = '{$smarty.const._NEWS_EVENT_CAT_ID}';
</script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="oMABKutTId mb-2">
		<h4 class="fw-bold mb-1">Bản tin {$smarty.const.BRAND_NAME}</h4>
		<span class="text-muted">Tổng hợp các bản tin nội bộ</span>
	</div>
	<section class="section section-xxs">
		<div class="row flex-row flex-wrap">
			<div class="col-md-2 mb-3 mb-lg-0">
				<div class="leftbar sticky">
					{$core->getBlock('news_category')}
				</div>
			</div>
			<div class="col-md-6">
				<div class="awe__post-page">
					<div class="awe__post-form bg-white radius-4 mb-4">
						<div class="d-flex align-items-center w-100">
							<div class="awe__post-avatar position-relative">
								{if $loggedIn eq '1'}
								<img class="rounded" src="{$clsProfile->getAvatar($profile_id,$oneProfile,44,44)}" width="44" height="44" />
								{$clsProfile->get_icon_verified($profile_id, $oneProfile.more_information)}
								{else}
								<img class="rounded" src="{$URL_IMAGES}/no-avatar.jpg?v={$upd_version}" width="44" height="44" />
								{/if}
							</div>
							<div class="textarea">
								<a class="awe__post-input-link open_add_news" href="javascript:void(0);" onClick="open_news(this, event)" news_id="0" action="_add" cat_docs_id="{$cat_docs_id}" project_id="{$project_id}" block_id="{$block_id}" >Viết bài, chia sẻ, đặt câu hỏi...</a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					{if !empty($list_post)}
					<div class="awe__list-post"> 
						{foreach name=i from=$list_post item = _oNews}
						{assign var = news_id value = $_oNews.news_id}
						{assign var = _link value = $_oNews.link}
						{assign var = events_config value = $_oNews.events_config}
						{assign var = _title value = $clsNews->getTitle($news_id, $_oNews)}
						{assign var = gid value = $clsISO->getUniqid()}
						<div class="awe__post-item" order_no="{$_oNews.order_no}" id="post_item_{$news_id}">
							<div class="w-100 d-flex align-items-start justify-content-between mb-3">
								<div class="d-flex gap-2 flex-wrap align-items-center">
									<div class="awe__post-profile d-flex" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oNews.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="350">
										<div class="awe__post-avatar position-relative">
											<img class="rounded" src="{$_oNews.db_profile.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="44" height="44" /> 
											{$clsProfile->get_icon_verified($_oNews.user_id, $_oNews.db_profile.more_information)}
										</div>
										<div class="awe__post-profile-body">
											<p class="awe__post-name">{$_oNews.db_profile.name}</p>
											<div class="d-flex align-items-center">
												{if !empty($_oNews.db_profile.role)}
												<span class="awe__post-level mr-2 text-muted">
													{$_oNews.db_profile.role}
												</span>
												{/if}
												<span class="awe__post-star mr-2 text-muted">
													{$_oNews.db_profile.html_star}
												</span>
												<span class="awe__post-time text-muted">
													{$clsISO->getTimeAgo($_oNews.reg_date)}
												</span>
											</div>
										</div>
									</div>
									{if !empty($_oNews.project_tags)}
										<div class="d-flex flex-wrap gap-1">
											{foreach from=$_oNews.project_tags item=project_tag}
												<a href="{$_oTag.link}" title="{$project_tag}" class="btn btn-xs rounded-pill btn-outline-primary">{$project_tag}</a>
											{/foreach}
										</div>
									{/if}
								</div>
								{if $_oNews.user_id eq $profile_id || $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->_DEV()}
								<div class="dropdown">
									<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
										<i class="bx bx-dots-vertical-rounded"></i>
									</button>
									<div class="dropdown-menu" data-popper-placement="bottom-end">
										<a class="dropdown-item" href="javascript:void(0);" onClick="open_news(this, event)" gid="{$gid}" news_id="{$news_id}" action="_edit">{$clsISO->makeIcon('bx-edit-alt me-1', 'Sửa')}</a>
										<a class="dropdown-item" href="javascript:void(0);" onClick="delete_news(this, event)" news_id="{$news_id}">{$clsISO->makeIcon('bx-trash me-1', 'Xóa')}</a>
									</div>
								</div>
								{/if}
							</div>
							<div class="awe__post-item-body">
								<h3 class="awe__post-title mb-3" onClick="open_news(this, event)" gid="{$gid}" 
									news_id="{$news_id}" action="_detail">
									<a class="fs-16 awe__post-link" title="{$_title}">{$_title}</a>
								</h3>
								<div class="awe__post-description mb-3" data-height="10px" onClick="open_news(this, event)" 
									gid="{$gid}" news_id="{$news_id}" action="_detail">
									{$clsNews->getIntro($news_id, $_oNews)}
								</div>
								{if !empty($_oNews.attachments)}
									{$clsNews->get_attachment_html($_oNews.attachments)}
								{/if}
								{if !empty($_oNews.images)}
								<div class="awe__post-gallery gallery mb-3">
									{$clsNews->getImageGrid($news_id, $_oNews.images)}
								</div>
								{/if}
							</div>
							{assign var = total_comments value = $_oNews.total_comments}
							<div gid="{$gid}" class="x1n2onr6{if $_oNews.total_actions eq '0'} d-none{/if} py-1">
								<div class="d-flex justify-content-between align-items-center">
									<div gid="{$gid}" class="reactions-total d-flex align-items-center gap-1">{$clsNews->genTotalLike($news_id,$_oNews)}</div>
									<div class="d-flex align-items-center comment gap-2">
										<span><i class="material-icons-outlined">visibility</i> {$_oNews.view_num}</span>
										<span><i class="bx bx-comment"></i> {$total_comments}</span>
									</div>
								</div>
							</div>
							<div class="awe__post-cmd">
								<div class="d-flex justify-content-center">
									<a gid="{$gid}" href="javascript:void(0);" news_id="{$news_id}" class="awe__post-action flex-fill awe__post-like-action{if $_oNews.status_liked eq '1'} liked{/if}" onClick="$Core.news.like(this, event)" data-name="like" data-clsTable="News" data-table_id="{$news_id}">
										{if $_oNews.status_liked eq '1'}
											{$clsISO->makeIcon('bxs-heart text-main','Thích')}
										{else}
											{$clsISO->makeIcon('bx-heart','Thích')}
										{/if}
									</a>
									<a href="{$_link}" class="awe__post-action flex-fill awe__post-comment-action" 
									onClick="open_news(this, event)" gid="{$gid}" action="_detail" news_id="{$news_id}">
										{$clsISO->makeIcon('bx-comment','Bình luận')}
									</a>
								</div>
							</div>
						</div>
						{/foreach} 
					</div>
					{/if}
					<div class="clearfix"></div>
					{if $total_record gt $per_page}
					<div class="d-flex justify-content-center mb-3">
						<button onClick="load_post_more(this, event)" data-toggle="ripple" class="btn btn-block btn-lg btn-link bg-white text-dark font-weight-bold" cat_id="{$cat_id}" total_loaded="{$per_page}" total_record="{$total_record}"><i class="fa fa-angle-down"></i> Xem thêm</button>
					</div>
					{/if}
			   </div>
			</div>
			<div class="col-md-4">
				<div class="sticky">
					<div class="card ranking">
						<div class="card-body">
							{$core->getBlock('top_ranking')}
						</div>
					</div>
					<div class="card no-shadow sticky mt-3">
						{$core->getBlock('top_staff')}
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
{$scriptJs}
{$script}
<script type="text/javascript">
	$(function(){
		if($('.awe__post-description:not(.collapsed)').length){
			$('.awe__post-description:not(.collapsed)').each((_i, _elem) => {
				var _height = $(_elem).outerHeight();
				if(_height > 200){
					$(_elem).addClass('collapsed').append('<a class="awe__link-more">Xem thêm...</a>');
				}
			});
		}
	});
</script>
