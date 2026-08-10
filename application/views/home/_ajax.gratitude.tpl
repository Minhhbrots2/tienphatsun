{if $action eq '_detail'}
<div class="modal-dialog modal-dialog-scrollable{if $deviceType ne 'phone'} modal-ipad-xl{else} modal-sm{/if}">
	<div class="modal-content overflow-y">
		<div class="modal-header">
			<h5 class="modal-title text-upper mb-0">{$oneNews.title}</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="awe__post-profile d-flex">
				<div class="awe__post-avatar">
					<img class="rounded" src="{$oneNews.db_profile.avatar}" width="44" height="44" /> 
				</div>
				<div class="awe__post-profile-body">
					<p class="awe__post-name">{$oneNews.db_profile.name}</p>
					<span class="awe__post-time text-muted">{$clsISO->getTimeAgo($oneNews.reg_date)}</span>
				</div>
			</div>
			<div class="tinyContent mt-3">
				{$clsNews->formatHTML($oneNews.content)}
			</div>
			{if !empty($lstDepartment)}
				<div class="box_list_gratitude d-flex flex-wrap align-items-center">
					<label for="" class="lbl_gratitude mb-2">Tri ân đến phòng:</label>
					<div class="lst_gratitude ml-2">
						{foreach from=$lstDepartment item=department}
							<span class="badge px-2 bg-label-primary me-2 mb-2">{$department.title}</span>
						{/foreach}
					</div>
				</div>
			{/if}
			{if !empty($lstStaff)}
				<div class="box_list_gratitude d-flex flex-wrap align-items-center">
					<label for="" class="lbl_gratitude mb-2">Tri ân đến:</label>
					<div class="lst_gratitude ml-2">
						{foreach from=$lstStaff item=staff}
							<span class="badge px-2 bg-label-primary me-2 mb-2" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$staff.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400">{$staff.full_name}</span>
						{/foreach}
					</div>
				</div>
			{/if}
			<div class="awe__post-item-body">
				{if !empty($oneNews.images)}
				<div class="awe__post-gallery gallery mb-3">
					<div id="gallery-grid-{$news_id}"></div>
				</div>
				{/if}
			</div>
			<div class="awe__post-cmd my-3">
				<div class="d-flex justify-content-center">
					<a gid="{$gid}" href="javascript:void(0);" news_id="{$news_id}" class="awe__post-action awe__post-like-action{if $status_liked eq '1'} liked{/if}" onClick="$Core.news.like(this, event)" data-name="like" data-clsTable="News" data-table_id="{$news_id}">
						{if $status_liked eq '1'}
							{$clsISO->makeIcon('bxs-heart','Thích')}
						{else}
							{$clsISO->makeIcon('bx-heart','Thích')}
						{/if}
					</a>
					<a href="javascript:void(0);" class="awe__post-action awe__post-comment-action" action="_detail" news_id="{$news_id}">
						{if $oneNews.total_comments gt '0'}
							{$clsISO->makeIcon('bx-comment',$oneNews.total_comments|cat:' Bình luận')}
						{else}
							{$clsISO->makeIcon('bx-comment','Bình luận')}
						{/if}
					</a>
					<a href="javascript:void(0);" class="awe__post-action awe__post-share-action" data-link="{$_link}" data-title="{$_title}" data-toggle="modal" data-target="#sharer">{$clsISO->makeIcon('bx-share', 'Chia sẻ')}</a>
				</div>
			</div>
			{$core->getBlock('comment', ['table_id' => $news_id, 'clsTable' => 'News'])}
		</div> 
	</div>
</div>
{else}
<div class="modal-dialog modal-dialog-scrollable modal-ipad-xl">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">.
			{if $action eq "_edit"}
				<h5 class="modal-title" id="modalTopTitle">Sửa bài tri ân </h5>
			{else}
				<h5 class="modal-title" id="modalTopTitle">Thêm mới bài tri ân </h5>
			{/if}
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="form-group mb-2">
				{assign var = uid value = $clsISO->getUniqid()}
				<div class="form-floating">
					<input type="text" class="form-control required" id="{$uid}" name="title" maxlength="255" 
					placeholder="Nhập tiêu đề bản tin" value="{if $news_id gt '0'}{$oneNews.title}{/if}">
					<label for="{$uid}">Tiêu đề</label>
				</div>
			</div>
			<div class="form-group mb-2">
				<label  class="form-label">Đối tượng được tri ân</label>
				{if $action eq '_add'}
				{*<ul class="nav nav-tabs nav_primary" id="myTab_{$uid}" role="tablist">
					<li class="nav-item" role="presentation">
						<label class="nav-link active" id="department-tab_{$uid}" data-bs-toggle="tab" data-bs-target="#department_{$uid}" type="button" role="tab" aria-controls="department_{$uid}" aria-selected="true" for="is_employ0_{$uid}">Phòng ban</label>
						<input type="radio" class="d-none" name="is_employ" value="dep" id="is_employ0_{$uid}" checked>
					</li>
					<li class="nav-item" role="presentation">
						<label class="nav-link" id="employ-tab_{$uid}" data-bs-toggle="tab" data-bs-target="#employ_{$uid}" type="button" role="tab" aria-controls="employ_{$uid}" aria-selected="false" for="is_employ1_{$uid}">Nhân viên</label>
						<input type="radio" class="d-none" name="is_employ" value="emp" id="is_employ1_{$uid}">
					</li>
				</ul>
				<div class="tab-content px-0 py-2" id="myTabContent_{$uid}">
					<div class="tab-pane fade show active" id="department_{$uid}" role="tabpanel" aria-labelledby="department-tab_{$uid}">
						<div class="selecttize-lg mt-1">
							<select class="iso-selectizeNotSearch w-100" placeholder="Phòng ban" 
							data-url="{$PCMS_URL}/index.php?mod=home&act=list_department" name="department_id[]" data-optgroup="false" multiple onchange="$Core.gratitude.loadTableEmploy(this,event)" data-type="department" data-options="{ldelim}'department_id':{$department_id}{rdelim}">
								{if $action eq '_edit'}
									{foreach from=$lstPartment item=item}
										<option value="{$item.property_id}" selected="selected">{$item.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
					</div>
					<div class="tab-pane fade" id="employ_{$uid}" role="tabpanel" aria-labelledby="employ-tab_{$uid}">
						<div class="selecttize-lg mt-1">
							<select class="iso-selectizeNotSearch w-100" placeholder="Nhân viên" 
							data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" name="staff_id[]" data-optgroup="false" multiple onchange="$Core.gratitude.loadTableEmploy(this,event)" data-type="employ" data-options="{ldelim}'staff_id':{$employ_id}{rdelim}">
								{if $action eq '_edit'}
									{foreach from=$list_staffs item=item}
										<option value="{$item.profile_id}" selected="selected">{$item.full_name}</option>
									{/foreach}
								{/if}
							</select>
						</div>
					</div>
				</div>*}
				{/if}
				<div class="p-3 border radius-4">
					{if $action eq '_add'}
					<div class="form-row">
						<div class="col-md-6 col-sm-12 col-12 mb-2">
							<label  class="form-label">Phòng ban</label>
							<div class="selecttize-lg">
								<select class="iso-selectizeNotSearch w-100" placeholder="Phòng ban" 
								data-url="{$PCMS_URL}/index.php?mod=home&act=list_department" name="department_id[]" data-optgroup="false" multiple onchange="$Core.gratitude.loadTableEmploy(this,event)" data-type="department" data-options="{ldelim}'department_id':{$department_id}{rdelim}">
									{if $action eq '_edit'}
										{foreach from=$lstPartment item=item}
											<option value="{$item.property_id}" selected="selected">{$item.title}</option>
										{/foreach}
									{/if}
								</select>
							</div>
						</div>
						<div class="col-md-6 col-sm-12 col-12 mb-2">
							<label  class="form-label">Nhân viên</label>
							<div class="selecttize-lg">
								<select class="iso-selectizeNotSearch w-100" placeholder="Nhân viên" 
								data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" name="staff_id[]" data-optgroup="false" multiple onchange="$Core.gratitude.loadTableEmploy(this,event)" data-type="employ" data-options="{ldelim}'staff_id':{$employ_id}{rdelim}">
									{if $action eq '_edit'}
										{foreach from=$list_staffs item=item}
											<option value="{$item.profile_id}" selected="selected">{$item.full_name}</option>
										{/foreach}
									{/if}
								</select>
							</div>
						</div>
					</div>
					{/if}
					<div class="lst_employ form-row">	
						{if $action eq '_edit'}
							{if !empty($lstDepartment)}
								<div class="col-md-6 col-sm-12 col-12 flex-fill">
									<table class="table">
										<thead>
											<tr>
												<th class="text-center" scope="col" width="50px">STT</th>
												{if $deviceType eq 'phone'}
													<th scope="col">Phòng</th>
												{else}
													<th scope="col">Phòng được tri ân</th>
												{/if}
												<th class="text-right" scope="col" width="100px">Điểm/1 người</th>
											</tr>
										</thead>
										<tbody>
											{foreach from=$lstDepartment item=department name=i}
												<tr>
													<td class="text-center">{$smarty.foreach.i.iteration}</td>
													<td>{$department.title} ({$department.total_profile})</td>
													<td class="text-right">
														{$department.score}
													</td>
												</tr>
											{/foreach}
										</tbody>
									</table>
								</div>
							{/if}
							{if !empty($lstStaff)}
								<div class="col-md-6 col-sm-12 col-12 flex-fill">
									<table class="table">
										<thead>
											<tr>
												<th class="text-center" scope="col" width="50px">STT</th>
												{if $deviceType eq 'phone'}
													<th scope="col">Người</th>
												{else}
													<th scope="col">Người được tri ân</th>
												{/if}												
												<th class="text-right" scope="col" width="100px">Điểm</th>
											</tr>
										</thead>
										<tbody>
											{foreach from=$lstStaff item=staff name=i}
												<tr>
													<td class="text-center">{$smarty.foreach.i.iteration}</td>
													<td>{$staff.full_name}</td>
													<td class="text-right">
														{$staff.score}
													</td>
												</tr>
											{/foreach}
										</tbody>
									</table>
								</div>
							{/if}
						{/if}
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<label for="nameSlideTop" class="form-label">Nội dung</label>
				<textarea id="{$clsISO->getUniqid()}" class="form-control isoTextArea" cols="255" rows="25" data-name="content">{if $news_id gt '0'}{$oneNews.content}{/if}</textarea>
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
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" onClick="$Core.gratitude.pop_save_gratitude(this, event)" news_id="{$news_id}" 
			class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
{/if}