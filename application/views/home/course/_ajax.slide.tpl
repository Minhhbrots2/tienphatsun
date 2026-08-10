{if $template_type eq '_form'}
<div class="modal-dialog modal-ipad">
	<form class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">{if $action eq '_add'}Thêm{else}Chỉnh sửa{/if} tài liệu</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-floating mb-3">
				<input type="text" class="form-control required" id="floatingInput" placeholder="John Doe" 
				aria-describedby="floatingInputHelp"{if $action eq '_edit'} value="{$oneSlide.title}"{/if} name="title">
				<label for="floatingInput">Tên tài liệu</label>
			</div>
			<div class="form-floating mb-3">
				<select class="form-control required" name="cat_id">
					{$clsISO->getSelectByPropertyTypeTitle('_LEARN_CAT',$oneSlide.cat_id,'Loại tài liệu')}
				</select>
				<label for="floatingInput">Danh mục tài liệu</label>
			</div>
			<div class="formgroup mb-3">
				<label class="form-label mb-1">Loại tài liệu</label>
				<div class="clearfix"></div>
				<div class="btn-group" role="group">
					{foreach name=i from=$list_type key=_okey item=_oval}
					<input type="radio" class="btn-check" uid="{$uid}"{if $oneSlide.slide_type eq $_okey} checked{/if} 
					name="slide_type" slide_id="{$slide_id}" value="{$_okey}" onchange="$Core.slide.set_content(this, event)" 
					id="{$uid}_{$_okey}" autocomplete="off">
					<label class="btn btn-outline-primary" for="{$uid}_{$_okey}">{$_oval}</label>
					{/foreach}
				</div>
			</div>
			<div id="loadconentdoc_{$uid}" class="loadconentdoc mb-3">
				<div class="p-2 text-center">Loading...</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Tag:</label>
				<input type="text" id="input-tags" class="input-tags" name="tags" placeholder="Nhập tag" value="{$html_tags}" />
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Insert" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" slide_id="{$slide_id}" onClick="$Core.slide.save(this, event)" 
				class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
{else}
	{if !empty($list_slides)}
	<div class="form-row">
		{foreach name=i from=$list_slides item = _oslide}
		<div class="col-12 col-md-3">
			<div class="course-item-wrapper course-item-entry">
				<div class="course-item-component">
					<div class="d-flex flex-column justify-content-between course-body course-item-body course-body-wrapper">
						<div class="p_top">
							<div class="d-flex align-items-center justify-content-between mb-2">
								{$clsProfile->getIndentityV4($_oslide.user_id, $_oslide.oneProfile)}
								{if $_oslide.user_id eq $profile_id}
								<div class="dropdown">
									<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
									<div class="dropdown-menu">
										<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.slide.open(this, event)" slide_id="{$_oslide.slide_id}"><i class="bx bx-edit-alt me-1"></i> Chỉnh sửa</a>
										<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.slide.delete(this, event)" slide_id="{$_oslide.slide_id}"><i class="bx bx-trash me-1"></i> Xóa</a>
									</div>
								</div>
								{/if}
							</div>
							<div class="d-flex align-items-center text-muted fs-12 mb-1">
								{$clsISO->makeIcon('bx-time-five mr-1',$clsISO->convertTimeToText($_oslide.reg_date, true))}
							</div>
							<h3 class="course-item-title course-title mb-3">
								<a title="{$_oslide.title}" class="fs-16 text-black">{$_oslide.title}</a>
							</h3>
							{$clsSlide->getHTMLTag($_oslide.slide_id)}
						</div>
						<div class="d-flex justify-content-start align-items-center">
							{if $_oslide.slide_type eq 'url'}
							<a href="{$_oslide.link}" target="_blank" class="btn btn-sm btn-outline-default" title="Đọc nhanh">
								<i class='bx bx-link-external'></i> Xem nhanh
							</a>
							{elseif $_oslide.slide_type eq 'upload'}
								{if $clsSlide->isPdf($_oslide.link)}
								<a href="{$_oslide.link}" data-fancybox data-type="iframe" class="btn btn-sm btn-outline-default" title="Đọc nhanh"><i class='bx bx-book-reader'></i> Đọc nhanh</a>
								{else}
								<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src={$_oslide.link}" class="btn btn-sm btn-outline-default" title="Đọc nhanh"><i class='bx bx-book-reader'></i> Đọc nhanh</a>
								{/if}
							{elseif $_oslide.slide_type eq 'post' || $_oslide.slide_type eq 'image'}
								<a target="_blank" href="javascript:void()" onClick="$Core.slide.view(this, event)" slide_id="{$_oslide.slide_id}" class="btn btn-sm btn-outline-default" title="Đọc nhanh"><i class='bx bx-book-reader'></i> Đọc nhanh</a>
							{elseif $_oslide.slide_type eq 'video'}
								<a href="{$_oslide.link}" data-fancybox class="btn btn-sm btn-outline-default" title="Xem nhanh"><i class='bx bx-play-circle'></i> Xem nhanh</a>
								
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
		{/foreach}
	</div>
	<div id="pager_slide" class="sample-pagination"></div>
	<input type="hidden" name="current_page" value="{$page}" />
	{else}
		<div class="text-center">
			<img src="{$URL_IMAGES}/book.png" width="200px" />
			<p class="text-center">Chưa có tài liệu nào</p>
		</div>
	{/if}
{/if}