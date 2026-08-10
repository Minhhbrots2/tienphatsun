<div class="awe__comment-reply mt-3">
	<form method="post" class="d-flex align-items-center" enctype="multipart/form-data">
		<div class="awe__comment-form d-flex w-100">
			<div class="awe__profile-avatar">
				<img class="rounded-pill" src="{$clsProfile->getAvatar($profile_id, $oneProfile)}" width="30px" height="30px" />
			</div>
			<div class="awe__comment-textarea">
				<div class="awe__comment-input rounded-2 position-relative">
					<textarea onkeyup="$Core.news.fireEvent(this, event)" id="{$uid}" class="form-control textarea-emoji no-focus redactor_editor autosize height-not-auto rounded-2" rows="2" name="message" lang="{$_LANG_ID}" placeholder="Viết bình luận"></textarea>
					<div class="awe__comment-button">
						<input type="hidden" name="submit" value="comment" />
						<input type="hidden" name="parent_id" value="{$parent_id}" />
						<input type="hidden" name="table_id" value="{$table_id}" />
						<input type="hidden" name="clsTable" value="{$clsTable}" />
						{if $clsTable eq 'Course'}
							<label for="attach-doc_rep_{$parent_id}" class="form-label mb-0">
								<i class="bx bx-paperclip bx-sm cursor-pointer mx-3 text-body"></i>
								<input type="file" id="attach-doc_rep_{$parent_id}" name="image" hidden="" onChange="$Core.news.add_comment(this, event)" holderG="_reply" comment_id="{$parent_id}">
							</label>
						{/if}
						<a onClick="$Core.news.add_comment(this, event)" comment_id="{$parent_id}" holderG="_reply" class="js-add-comment awe__comment-action  disabled" href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="20"><path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg></a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>