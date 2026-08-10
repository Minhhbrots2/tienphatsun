{if !empty(list_news)}
	{foreach name=i from=$list_news item = _oNews}
	{assign var = news_id value = $_oNews.news_id}
	{assign var = _title value = $clsNews->getTitle($news_id, $_oNews)}
	<div class="awe__post-item" order_no="{$_oNews.order_no}" id="post_item_{$post_id}">
		<div class="w-100 d-flex align-items-center justify-content-between mb-3">
			<div class="awe__post-profile d-flex mb-3" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oNews.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400">
				<div class="awe__post-avatar">
					<img class="rounded" src="{$_oNews.db_profile.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="44" height="44" /> 
				</div>
				<div class="awe__post-profile-body">
					<p class="awe__post-name">{$_oNews.db_profile.name}</p>
					<div class="d-flex align-items-center">
						{if !empty($_oNews.db_profile.level)}
						<span class="awe__post-level mr-2 text-muted">
							{$_oNews.db_profile.level}
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
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true"><i class="bx bx-dots-vertical-rounded"></i></button>
				<div class="dropdown-menu" data-popper-placement="bottom-end">
					{if $_oNews.user_id eq $profile_id and $clsNews->checkCanEdit($news_id, $_oNews) eq '1'}{/if}
					<a class="dropdown-item" href="javascript:void(0);" onClick="open_news(this, event)" news_id="{$news_id}" action="_edit">{$clsISO->makeIcon('bx-edit-alt me-1', 'Chỉnh sửa')}</a>
					<a class="dropdown-item" href="javascript:void(0);" onClick="delete_news(this, event)" news_id="{$news_id}">{$clsISO->makeIcon('bx-trash me-1', 'Xóa')}</a>
				</div>
			</div>
		</div>
		<div class="awe__post-item-body">
			<h3 class="awe__post-title mb-3" onClick="open_news(this, event)" action="_detail" news_id="{$news_id}">
				<a class="fs-16 awe__post-link" title="{$_title}">{$_title}</a>
			</h3>
			<div class="awe__post-description mb-2" onClick="open_news(this, event)" action="_detail" news_id="{$news_id}">
				{$clsNews->getIntro($news_id, $_oNews)}
			</div>
			{if !empty($_oNews.images)}
			<div class="awe__post-gallery gallery mb-3">
				{$clsNews->getImageGrid($news_id, $_oNews.images)}
			</div>
			{/if} 
		</div>
		{assign var = total_liked value = $_oNews.total_liked}
		{assign var = total_comments value = $_oNews.total_comments}
		{assign var = gid value = $clsISO->getUniqid()}
		<div gid="{$gid}" class="x1n2onr6{if $total_actions eq '0'} d-none{/if} py-1">
			<div class="d-flex justify-content-between align-items-center">
				<div gid="{$gid}" class="reactions-total d-flex align-items-center gap-1">{$clsNews->genTotalLike($news_id)}</div>
				<div class="comment"><i class="bx bx-comment"></i> {$total_comments}</div>
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
				<a href="{$_link}" class="awe__post-action flex-fill awe__post-comment-action" onClick="open_news(this, event)" 
				action="_detail" news_id="{$news_id}">
					{$clsISO->makeIcon('bx-comment','Bình luận')}
				</a>
				<a href="javascript:void(0);" class="awe__post-action flex-fill awe__post-share-action" data-link="{$_link}" 
				data-title="{$_title}" data-toggle="modal" data-target="#sharer">
					{$clsISO->makeIcon('bx-share', 'Chia sẻ')}
				</a>
			</div>
		</div>
	</div>
	{/foreach} 
{else}
	_empty
{/if}