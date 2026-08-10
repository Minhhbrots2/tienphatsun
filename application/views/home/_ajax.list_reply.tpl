{if $list_reply}
	{foreach name=i from=$list_reply item = _oComment}
	{assign var = _comment_id value = $_oComment.comment_id}
	<div class="awe__comment-item awe__comment-reply-group-{$comment_id}">
		<div class="d-flex w-100">
			<div class="awe__profile-avatar" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oComment.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-placement="top" data-width="350">
				<img class="rounded-pill" src="{$_oComment.db_profile.avatar}" width="30" height="30" />
			</div>
			<div class="awe__comment-item-body"> 
				<div class="bg-lighter p-2 rounded-2">
					<div class="awe__comment-profile d-flex align-items-center gap-1 mb-1">
						<h4 class="awe__comment-name mb-0 text-bold fs-14">{$_oComment.db_profile.name}</h4>
						<span class="awe__comment-time text-muted fs-11">{$clsISO->getTimeAgo($_oComment.reg_date)}</span>
					</div>
					<div class="awe__comment-content">
						{$_oComment.content|html_entity_decode}
					</div>
				</div>
				<div class="awe__comment-item-action d-flex align-items-center">
					<a href="javascript:void(0);" class="control-action {$_oComment.liked}" onclick="$Core.comment.like(this, event)" comment_id="{$_comment_id}" data-name="like" ><i class="bx bx-like me-1" style="{if $_oComment.liked}color:#e24b4a{/if}"></i><span class="awe__comment-total-liked">{$clsComment->genTotalLike($_comment_id)}</span></a>	
					</a>
				</div>
			</div>
		</div>
	</div>
	{/foreach}
	{if $total_page gt $page}
	<div class="d-flex justify-content-center mt10">
		<a href="javascript:void(0);" rel="nofollow" page="{$page+1}" class="btn-more show_more_review">
			<span>{$core->get_Lang('Show more comments')}</span>
		</a>
	</div>  
	{/if}
{/if}