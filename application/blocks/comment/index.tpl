<div class="awe__post-comment{if $clsTable eq 'News'} awe__news-comment{/if}">
	{if $clsTable ne 'Post' && $clsTable ne 'News' && $clsTable ne 'Share'}
	<div class="d-flex align-items-center mb-3">
		<h3 class="text-fs-16 oHHgwgcTnX m-0">Bình luận <span class="total_comments">0</span></h3>
	</div>
	{/if}
	{if $clsTable ne 'News'}
		<div class="awe__comment-form">
			<form method="post" class="d-flex align-items-center" enctype="multipart/form-data">
				<div class="d-flex align-items-center w-100">
					<div class="awe__profile-avatar">
						<img class="rounded-pill" src="{$clsProfile->getAvatar($profile_id, $oneProfile)}" 
						width="30px" height="30px" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" />
					</div>
					<div class="awe__comment-textarea">
						<div class="awe__comment-input rounded-1 position-relative">
							<div class="w-100">
								<textarea onkeyup="$Core.news.fireEvent(this, event)" class="form-control no-focus redactor_editor autosize height-not-auto rounded-1 textarea-emoji" name="message" rows="2" placeholder="Viết bình luận"></textarea>
							</div>
							<div class="awe__comment-button">
								<input type="hidden" name="parent_id" value="0" />
								<input type="hidden" name="submit" value="comment" />
								<input type="hidden" name="table_id" value="{$table_id}" />
								<input type="hidden" name="clsTable" value="{$clsTable}" />
								{if $clsTable eq 'Course'}
								<label for="attach-doc" class="form-label mb-0">
									<i class="bx bx-paperclip bx-sm cursor-pointer mx-1 text-body"></i>
									<input type="file" id="attach-doc" name="image" onChange="$Core.news.add_comment(this, event)" holderG="_comment" comment_id="0" class="d-none">
								</label>
								{/if}
								<a href="javascript:void(0)" onClick="$Core.news.add_comment(this, event)" type="text" class="js-add-comment awe__comment-action disabled" comment_id="0" holderG="_comment"><svg fill="#CCC" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="20"><path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg></a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	{/if}
	<div class="clearfix"></div>
	<div class="holder_comments_{$table_id}{if isset($autoload) && $autoload eq '1'} awe__comment-autoload{/if} comments-list mt-3" table_id="{$table_id}" clsTable="{$clsTable}" >
		{foreach from=$list_preloaders item = _oItem}
		<div class="awe__comment-item mb-2">
			<div class="d-flex gap-2 w-100 align-items-start">
				<div class="avatar animate-bg rounded-pill"></div>
				<div class="bAxZCbpcyR w-100">
					<div class="animate-bg w-50 h-px-15 rounded-2 mb-2"></div>
					<div class="animate-bg w-100 h-px-15 rounded-2"></div>
				</div>
			</div>
		</div>
		{/foreach}
	</div>
</div>
<script type="text/javascript">
	var _LANG_ID = '{$_LANG_ID}',
		table_id = '{$table_id}',
		clsTable = '{$clsTable}';
</script>