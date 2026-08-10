{if $action eq '_detail'}
<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-ipad-xl">
	<div class="modal-content overflow-y{if $oneNews.cat_id eq $_NEWS_GRATITUDE_CAT_ID} item-gratitude{/if}">
		{if $oneNews.cat_id eq $_NEWS_GRATITUDE_CAT_ID}
			<div class='stars_animation'></div>
			<div class='stars_animation2'></div>
			<div class='stars_animation3'></div>
		{/if}
		<div class="modal-header">
			<h5 class="modal-title mb-0">
				<span class="text-upper">{$oneNews.title}</span><br />
				<span class="fs-11 text-muted">Đăng bởi <strong class="text-decoration-underline">{$clsProfile->getFullName($oneNews.user_id)}</strong> vào <u>{$clsISO->getTimeAgo($oneNews.reg_date)}</u></span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body modal-body-news">
			<div class="tinyContent " data-height="200px">
				{$clsNews->formatHTML($oneNews.content)}
			</div>
			{if !empty($oneNews.attachments)}
				{$clsNews->get_attachment_html($oneNews.attachments)}
			{/if}
			{if !empty($oneNews.project_tags)}
			<div class="d-flex flex-wrap my-1 gap-1">
				{foreach from=$oneNews.project_tags item=project_tag}
				<a  title="{$project_tag}" class="btn btn-xs rounded-pill btn-outline-primary">{$project_tag}</a>
				{/foreach}
			</div>
			{/if}
			{if $oneNews.cat_id eq $_NEWS_GRATITUDE_CAT_ID}
				<div class="lst_gratitude mt-3">
					{if !empty($oneNews.department)}
						{foreach from=$oneNews.department item=department}
							<span class="badge px-2 me-2 mb-2 cursor-pointer" onClick="$Core.gratitude.historyGratitude(this,event)" news_id="{$news_id}" data-type="dep" data-id="{$department.property_id}">{$department.title}</span>
						{/foreach}
					{/if}
					{if !empty($oneNews.staff)}
						{foreach from=$oneNews.staff item=staff}
							<span class="badge px-2 me-2 mb-2 cursor-pointer" onClick="$Core.gratitude.historyGratitude(this,event)" news_id="{$news_id}" data-type="staff" data-id="{$staff.profile_id}">{$staff.full_name}</span>
						{/foreach}
					{/if}
				</div>
			{/if}
			{if !empty($oneNews.images)}
			<div class="awe__post-item-body my-2">
				<div class="awe__post-gallery rounded-2 overflow-hidden gallery">
					<div id="gallery-grid-{$news_id}"></div>
				</div>
			</div>
			{/if}
			{assign var = total_comments value = $oneNews.total_comments}
			<div gid="{$gid}" class="x1n2onr6{if $oneNews.total_actions eq '0'} d-none{/if} py-1">
				<div class="d-flex justify-content-between align-items-center">
					<div gid="{$gid}" class="reactions-total d-flex align-items-center gap-1">{$clsNews->genTotalLike($news_id,$oneNews)}</div>
					<div class="d-flex align-items-end comment gap-2">
						<span><i class="material-icons-outlined">visibility</i> {$oneNews.view_num}</span>
						<span><i class="bx bx-comment"></i> {$total_comments}</span>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="awe__post-cmd">
				<div class="d-flex justify-content-center">
					<a gid="{$gid}" href="javascript:void(0);" news_id="{$news_id}" class="awe__post-action awe__post-like-action{if $status_liked eq '1'} liked{/if}" onClick="$Core.news.like(this, event)" data-name="like" data-clsTable="News" data-table_id="{$news_id}">
						{if $status_liked eq '1'}
							{$clsISO->makeIcon('bxs-heart','Thích')}
						{else}
							{$clsISO->makeIcon('bx-heart','Thích')}
						{/if}
					</a>					
					{if $oneNews.cat_id eq $_NEWS_GRATITUDE_CAT_ID}
						<a href="javascript:void(0)" class="awe__post-action awe__post-comment-action" onClick="$Core.gratitude.open_list_gratitude(this, event)" gid="{$gid}" news_id="{$news_id}">
							{$clsISO->makeIcon('bx-donate-heart','Tri ân')}
						</a>
					{/if}
					<a href="javascript:void(0);" class="awe__post-action awe__post-comment-action" action="_detail" news_id="{$news_id}">
						{if $oneNews.total_comments gt '0'}
							{$clsISO->makeIcon('bx-comment',$oneNews.total_comments|cat:' Bình luận')}
						{else}
							{$clsISO->makeIcon('bx-comment','Bình luận')}
						{/if}
					</a>
				</div>
			</div>
			{$core->getBlock('comment', ['table_id' => $news_id, 'clsTable' => 'News'])}
		</div> 
		<div class="awe__news-comment modal-footer p-0 m-0">
			<div class="awe__comment-form m-0">
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
									<input type="hidden" name="table_id" value="{$news_id}" />
									<input type="hidden" name="clsTable" value="News" />
									<a href="javascript:void(0)" onClick="$Core.news.add_comment(this, event)" type="text" class="js-add-comment awe__comment-action disabled" comment_id="0" holderG="_comment"><svg fill="#CCC" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="20"><path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg></a>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{else}
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-ipad-xl">
	{assign var = uid_file value = $clsISO->getUniqid()}
	<form class="d-none" enctype="multipart/form-data">
		<input id="select_file_{$uid_file}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" uid="{$uid_file}" onchange="$Core.global.news.upload_file(this,event)" charset="UTF-8" name="image">
	</form>
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Thêm mới bản tin</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="form-group mb-2">
				{assign var = _uid value = $clsISO->getUniqid()}
				<div class="form-floating">
					<input type="text" class="form-control required" id="{$_uid}" name="title" maxlength="255" 
					placeholder="Nhập tiêu đề bản tin" value="{$oneNews.title|escape:'html'}">
					<label for="{$_uid}">Tiêu đề</label>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-md-6 mb-2 mb-lg-0">
					{assign var = _uid value = $clsISO->getUniqid()}
					<div class="form-floating">
						<select class="form-control form-select required" name="cat_id">
							<option value="0">Chọn danh mục</option>
							{if !empty($arr_ca_cats)}
								{foreach from=$arr_ca_cats item = _oT key = _oK}
								<option{if $oneNews.cat_id eq $_oK} selected{/if} value="{$_oK}">{$_oT.title}</option>
								{/foreach}
							{/if}
						</select>
						<label for="{$_uid}">Danh mục</label>
					</div>
				</div>
				{if !empty($lstProjectTag)}
					<div class="col-12 col-md-6">
						<div class="box_input_tag">
							<label  class="form-label fs-10">Dự án</label>
							<select class="iso-select2 w-100" data-width="100%" data-placeholder="Chọn dự án" name="project_ids[]" data-optgroup="false" multiple >
									{foreach from=$lstProjectTag item=_oItem key=key}
										<option value="{$_oItem.setting_id}" {if $clsISO->checkItemInArray($_oItem.setting_id,$oneNews.listProjectIds) } selected{/if}>{$_oItem.title}</option>
									{/foreach}
							</select>
						</div>
					</div>
				{/if}
			</div>
			<div class="form-group mb-2">
				<label for="nameSlideTop" class="form-label">Nội dung</label>
				<textarea id="{$clsISO->getUniqid()}" class="form-control isoTextArea" cols="255" rows="25" 
					data-name="content">{if $news_id gt '0'}{$oneNews.content}{/if}</textarea>
			</div>
			<div class="form-group">
				<label for="nameSlideTop" class="form-label">Hình ảnh</label>
				<div class="we-filedrop-wrapper mb-2">
					<input id="selectFile_{$uid}" onChange="$Core.upload.file_upload(this, event)" class="d-none" accept="image/*" multiple="multiple" type="file" tabindex="-1">
					<div class="we-filedrop mb-1" toId="selectFile_{$uid}" onclick="$Core.upload.file_explorer(this,event);"> 
						<svg class="mb-2" width="70" height="70" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M118.42 75.84C118.43 83.2392 116.894 90.5589 113.91 97.33H16.09C12.8944 90.0546 11.3622 82.1579 11.6049 74.2154C11.8477 66.2728 13.8593 58.4844 17.4932 51.4177C21.1271 44.3511 26.2918 38.1841 32.6109 33.3662C38.93 28.5483 46.2443 25.2008 54.0209 23.5676C61.7976 21.9345 69.8406 22.0568 77.564 23.9257C85.2873 25.7946 92.4965 29.363 98.6661 34.3709C104.836 39.3787 109.81 45.6999 113.228 52.8739C116.645 60.0478 118.419 67.8937 118.42 75.84Z" fill="#F2F2F2"></path><path d="M5.54 97.33H126.37" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M97 97.33H49.91V34.65C49.91 34.3848 50.0154 34.1305 50.2029 33.9429C50.3904 33.7554 50.6448 33.65 50.91 33.65H84.18C84.6167 33.6541 85.0483 33.7445 85.4499 33.9162C85.8515 34.0878 86.2152 34.3372 86.52 34.65L96.02 44.15C96.3321 44.4533 96.5811 44.8153 96.7527 45.2151C96.9243 45.615 97.0152 46.0449 97.02 46.48L97 97.33Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" fill="white"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M88.97 52.42H77.33V40.77L88.97 52.42Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M27.32 65.49V70.6" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M29.88 68.04H24.76" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M110.49 32.5601V39.9901" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M114.2 36.27H106.77" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M34.07 14.58V25.59" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M39.57 20.08H28.57" stroke="#D7D7D7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M67 115.86V67.12" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" fill="white"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" stroke="#63666A" stroke-width="1" stroke-miterlimit="10"></path></svg> 
						<p class="mb-0">Bấm để chọn một hoặc nhiều hình ảnh cần tải lên !!!</p>
					</div>
					<div id="imageList" class="d-flex flex-wrap box-done-img">
						{if !empty($list_images)}
							{foreach from=$list_images item = _oImage}
							<span class="item">
								<img src="{$_oImage}" />
								<input type="hidden" name="images[]" value="{$_oImage}" />
								<a class="delete" src="{$_oImage}" onClick="$Core.upload.delete(this, event)"></a>
							</span>
							{/foreach}
						{/if}
					</div>
				</div>
			</div>
			<div class="form-group attachments mb-2">
				<label class="form-label mb-1">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
				{if !empty($oneNews.attachments)}
					{foreach name=i from = $oneNews.attachments item = _oFile}
					<div class="MultiFile-label">
						<a class="MultiFile-remove cursor-pointer" onclick="$Core.global.news.removeFile(this,event)" 
							news_id="{$oneNews.news_id}" data-url="{$_oFile}">x</a> 
							<span>
								<span class="MultiFile-label" title="{$_oFile}">
									<span class="MultiFile-title">{$_oFile.url}</span>
								</span>
							</span>
						</a>
					</div>
					{/foreach}
				{/if}
				</div>
				<div class="clearfix"></div>
				<input name="attachments[]" type="file" multiple="multiple" class="maxsize-10240" id="attachments_{$uid}" />
			</div>
			<div class="bg-lighter rounded-2 mb-2 p-3 d-none">
				<div class="form-check text-upper">
					<input class="form-check-input" type="checkbox" name="show_pop" value="1" 
						id="show_pop_{$uid}"{if !empty($oneNews.show_pop)} checked{/if}>
					<label class="form-check-label" for="show_pop_{$uid}"> Hiện popup</label>
				</div>
				<div class="form-check text-upper">
					<input class="form-check-input" type="checkbox" name="is_send_email" value="1" 
						id="send_email_{$uid}"{if !empty($oneNews.is_send_email)} checked{/if}>
					<label class="form-check-label" for="send_email_{$uid}"> Gửi mail toàn bộ</label>
				</div>
				<div id="moc_cat_block_{$uid}" class="pl-4 mb-2 d-none">
					<select class="form-control formm-select" name="cat_moc_id">
						<option value="0">Chọn danh mục</option>
						{if !empty($arr_cat_mocs)}
							{foreach from=$arr_cat_mocs item = _oT key = _oK}
							<option{if $oneNews.cat_moc_id eq $_oK} selected{/if} value="{$_oK}">{$_oT}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>
		</div>
		<div class="modal-footer justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<label class="switch">
					<input type="checkbox" name="is_online"{if $oneNews.is_online eq '1'} checked{/if} value="1">
					<span class="slider round"></span>
				</label>
				<span>Xuất bản</span>
			</div>
			<div class="d-flex gap-2 align-items-center">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" onClick="pop_save_news(this, event)" news_id="{$news_id}" 
				class="btn btn-primary">Lưu lại</button>
			</div>
		</div>
	</form>
</div>
{/if}