{if !empty($list_homes_news)}
{foreach name=i from=$list_homes_news item = _oNews}
	{assign var = news_id value = $_oNews.news_id}
	{assign var = _title value = $clsNews->getTitle($news_id, $_oNews)}
	<div class="awe__post-item{if $_oNews.cat_id eq $_NEWS_GRATITUDE_CAT_ID} item-gratitude{/if}" id="post_item_{$news_id}">
		{if $_oNews.cat_id eq $_NEWS_GRATITUDE_CAT_ID}
		<div class='stars_animation'></div>
		<div class='stars_animation2'></div>
		<div class='stars_animation3'></div>
		{/if}
		<div class="w-100 d-flex align-items-center justify-content-between mb-3">
			<div data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oNews.user_id}" data-toggle="webui-popover" 
				data-trigger="hover" data-width="350" class="awe__post-profile d-flex">
				<div class="awe__post-avatar position-relative">
					<img class="rounded" src="{$_oNews.db_profile.avatar}" 
					onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="44" height="44" />
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
			{if $_oNews.user_id eq $profile_id and $clsNews->checkCanEdit($news_id, $_oNews) eq '1'}
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" 
				aria-expanded="true"><i class="bx bx-dots-vertical-rounded"></i></button>
				<div class="dropdown-menu" data-popper-placement="bottom-end">
					<a class="dropdown-item" href="javascript:void(0);" onClick="open_news(this, event)" news_id="{$news_id}" action="_edit">{$clsISO->makeIcon('bx-edit-alt me-1', 'Chỉnh sửa')}</a>
					<a class="dropdown-item" href="javascript:void(0);" onClick="delete_news(this, event)" news_id="{$news_id}">{$clsISO->makeIcon('bx-trash me-1', 'Xóa')}</a>
				</div>
			</div>
			{/if}
		</div>
		<div class="awe__post-item-body">
			<h3 class="awe__post-title mb-3" onClick="open_news(this, event)" 
			news_id="{$news_id}" action="_detail">
				<a class="fs-16 awe__post-link" title="{$_title}">{$_title}</a>
			</h3>
			<div class="awe__post-description mb-3" onClick="open_news(this, event)" 
			news_id="{$news_id}" action="_detail">
				{$clsNews->getIntro($news_id, $_oNews)}
			</div>
			{if $_oNews.cat_id eq $_NEWS_GRATITUDE_CAT_ID}
				<div class="lst_gratitude">
				{if !empty($_oNews.department)}
					{foreach from=$_oNews.department item=department}
					<span class="badge px-2 me-2 mb-2 cursor-pointer" onClick="$Core.gratitude.historyGratitude(this,event)" news_id="{$news_id}" data-type="dep" data-id="{$department.property_id}">{$department.title}</span>
					{/foreach}
				{/if}
				{if !empty($_oNews.staff)}
					{foreach from=$_oNews.staff item=staff}
					<span class="badge px-2 me-2 mb-2 cursor-pointer" onClick="$Core.gratitude.historyGratitude(this,event)" news_id="{$news_id}" data-type="staff" data-id="{$staff.profile_id}">{$staff.full_name}</span>
					{/foreach}
				{/if}
				</div>
			{/if}
			{if !empty($_oNews.images)}
			<div class="awe__post-gallery gallery mb-3">
				{$clsNews->getImageGrid($news_id, $_oNews.images)}
			</div>
			{/if}
		</div>
		{assign var = total_liked value = $_oNews.total_liked}
		{assign var = total_comments value = $_oNews.total_comments}
		{assign var = gid value = $clsISO->getUniqid()}
		<div gid="{$gid}" class="x1n2onr6{if $_oNews.total_actions eq '0'} d-none{/if} py-1">
			<div class="d-flex justify-content-between align-items-center">
				<div gid="{$gid}" class="reactions-total d-flex align-items-center gap-1">{$clsNews->genTotalLike($news_id)}</div>
				<div class="comment"><i class="bx bx-comment"></i> {$total_comments}</div>
			</div>
		</div>
		<div class="awe__post-cmd">
			<div class="d-flex justify-content-center">
				<div class="awe__post-action reactions-wrap">
					<a gid="{$gid}" href="javascript:void(0);" news_id="{$news_id}" class="awe__post-action awe__post-like-action control-action{if $_oNews.status_liked eq '1'} liked{/if}" onClick="$Core.news.like(this, event)" data-name="like" data-clsTable="News" data-table_id="{$news_id}">
						{if $_oNews.status_liked eq '1'}
							{$clsISO->makeIcon('bxs-heart','Thích')}
						{else}
							{$clsISO->makeIcon('bx-heart','Thích')}
						{/if}
					</a>
				</div>											
				{if $_oNews.cat_id eq $_NEWS_GRATITUDE_CAT_ID}
				<div class="awe__post-action reactions-wrap">
					<a href="javascript:void(0)" class="awe__post-action awe__post-comment-action" onClick="$Core.gratitude.open_list_gratitude(this, event)" gid="{$gid}" news_id="{$news_id}">
						{$clsISO->makeIcon('bx-donate-heart','Tri ân')}
					</a>
				</div>
				{/if}
				<div class="awe__post-action reactions-wrap">
					<a href="{$_link}" class="awe__post-action awe__post-comment-action" onClick="open_news(this, event)" 
					action="_detail" news_id="{$news_id}">{$clsISO->makeIcon('bx-comment','Bình luận')}</a>
				</div>
				<div class="awe__post-action reactions-wrap">
					<a href="javascript:void(0);" class="awe__post-action awe__post-share-action" data-link="{$_link}" 
					data-title="{$_title}" data-toggle="modal"><i class="bx bx-share"></i> Chia sẻ</a>
				</div>
			</div>
		</div>
	</div>
{/foreach}
{/if}