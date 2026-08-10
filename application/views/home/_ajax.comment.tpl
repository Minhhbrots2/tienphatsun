{if $list_comments}
	{if $clsTable eq 'News' and $action eq 'reload'}
	<div class="awe__comment-tabs">
		<a class="filter-comment__item{if $sort_by eq 'desc'} active{/if}" href="javascript:void(0);" rel="desc">Mới nhất</a>
		<a class="filter-comment__item{if $sort_by eq 'asc'} active{/if}" href="javascript:void(0);" rel="asc">Cũ nhất</a>
	</div>
	{/if}
	{foreach name=i from=$list_comments item = _oComment}
	{assign var = _comment_id value = $_oComment.comment_id}
	<div class="awe__comment-item">
		<div class="d-flex w-100">
			<div class="awe__profile-avatar">
				<img class="rounded" src="{$_oComment.db_profile.avatar}" width="30" height="30" />
			</div>
			<div class="awe__comment-item-body">
				<div class="awe__comment-profile d-flex mb-1">
					<h4 class="awe__comment-name text-bold mr-2 fs-14">{$_oComment.db_profile.name}</h4>
					<span class="awe__comment-time text-muted fs-12">{$core->makeIcon('clock-o', $clsISO->getTimeAgo($_oComment.reg_date))}</span>
				</div>
				<div class="awe__comment-content">
					{$_oComment.content|html_entity_decode}
				</div>
				<div class="awe__comment-item-action d-flex align-items-center mt-1">
					<a href="javascript:void(0);" class="awe__comment-like-button" table_id="{$table_id}" clsTable="{$clsTable}" comment_id="{$_comment_id}">
						<span class="awe__comment-total-liked">{$clsCommentVote->getTotalVote($_oComment.comment_id)}</span>
					</a>
					<a href="javascript:void(0);" class="awe__comment-reply-button" table_id="{$table_id}" clsTable="{$clsTable}" comment_id="{$_comment_id}">
						<span>{$core->makeIcon('angle-down', $core->get_Lang('Reply'))}</span>
					</a>
				</div>
				<div class="awe__comment-reply-form awe__comment-reply-form-{$_comment_id}"></div>
				<div class="awe__comment-reply-wrapper awe__comment-reply-wrapper-{$_comment_id}"></div>
				{if $_oComment.total_replys gt '0'}
				<div class="awe__comment-reply-cmd py-2">
					<a href="javascript:void(0);" table_id="{$table_id}" clsTable="{$clsTable}" comment_id="{$_comment_id}" class="awe__comment-reply-viewall" data-total="{$_oComment.total_reply}">
						<span class="awe__comment-reply-total">{$_oComment.total_replys}</span> {$core->get_Lang('reply')}
					</a>
				</div>
				{/if}
			</div>
		</div>
	</div>
	{/foreach}
	{if $total_page gt $page}
	<div class="d-flex justify-content-start py-2">
		<a href="javascript:void(0);" rel="nofollow" page="{$page+1}" class="btn-more awe__comment-showmorethis">
			{$core->makeIcon('angle-down', 'Hiển thị thêm bình luận')}
		</a>
	</div>  
	{/if}
{else}
	<div class="text-center no-comment">
		<div class="text-center">
			<!--?xml version="1.0" encoding="UTF-8"?-->
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="60px" height="60px" viewBox="0 0 16 16" version="1.1">
				<title>comment-discussion</title>
				<g id="Octicons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="comment-discussion" fill="#999"><path d="M15 1H6c-.55 0-1 .45-1 1v2H1c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h1v3l3-3h4c.55 0 1-.45 1-1V9h1l3 3V9h1c.55 0 1-.45 1-1V2c0-.55-.45-1-1-1zM9 11H4.5L3 12.5V11H1V5h4v3c0 .55.45 1 1 1h3v2zm6-3h-2v1.5L11.5 8H6V2h9v6z" id="Shape"><path></path></path></g></g>
				<metadata><rdf:rdf xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" xmlns:dc="http://purl.org/dc/elements/1.1/"><rdf:description about="https://iconscout.com/legal#licenses" dc:title="comment,discussion" dc:description="comment,discussion" dc:publisher="Iconscout" dc:date="2017-09-14" dc:format="image/svg+xml" dc:language="en"><dc:creator><rdf:bag>
				<rdf:li>Github</rdf:li></rdf:bag></dc:creator></rdf:description></rdf:rdf>
				</metadata>
			</svg>
			<p class="type--subdued">{if $_LANG_ID eq 'vn'}Chưa có bình luận{else}There are no comments{/if}</p>
		</div>
	</div>
{/if}