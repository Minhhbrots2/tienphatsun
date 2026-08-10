<form action="" method="POST" id="form_search" onsubmit="return false">
	<div class="form_search position-relative">
		<div class="input-group input-group-merge rounded-pill {if $deviceType ne 'phone'}mb-3{/if}">
			<span class="input-group-text" id="basic-addon-search31"><i class="icon-base bx bx-search"></i></span>
			<input type="text" class="form-control form-control-lg input_search search_field" placeholder="Tìm kiếm văn bản" name="keyword" data-field="keyword"  aria-label="Tìm kiếm văn bản" aria-describedby="basic-addon-search31" value="{$keyword}" onkeyup="$Core.docs.search_suggest(this, event);$Core.docs.search_doc(this,event)" onfocus="$Core.docs.search_suggest(this, event)" autocomplete="off">
		</div>
		<div class="box_search_suggestion dropdown-menu border position-absolute p-3 w-100">
			<div class="btn-group d-flex gap-2 mb-2" role="group" aria-label="Lọc gợi ý">
				<div class="btn-group">
					<input type="radio" class="btn-check btn_suggest_docs" name="is_important" id="suggest_all" value="0" autocomplete="off" checked onChange="$Core.docs.search_suggest(this, event)">
					<label class="fs-14 cursor-pointer" data-toggle="ripple" for="suggest_all">Tất cả</label>
				</div>
				<div class="btn-group">
					<input type="radio" class="btn-check btn_suggest_docs" name="is_important" id="suggest_important" value="1" autocomplete="off" onChange="$Core.docs.search_suggest(this, event)">
					<label class="fs-14 cursor-pointer" data-toggle="ripple" for="suggest_important">Quan trọng</label>
				</div>
			</div>
			<div class="lst_suggest_docs overflow-y-auto">
				
			</div>
		</div>
		{if $deviceType eq 'phone'}
			<div class="btn-group btn_slider_search">
				<button type="button" class="btn btn-icon dropdown-toggle hide-arrow rounded-pill" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false"><i class='bx bx-slider-alt'></i></button>
				<div class="dropdown-menu dropdown-menu-end w-px-300 p-2" style="">
					<div class="w-100 mb-2">
						<select class="form-select border-0 rounded-1 bg-lighter text-dark w-100 search_field mb-2" data-width="100%" placeholder="Danh mục" name="cat_id" data-field="cat_id" onChange="$Core.docs.search_doc(this,event)">
							<option value="">Chọn thư mục</option>
							{if !empty($lstCategory_doc)}
								{foreach from=$lstCategory_doc item=item}
									<option value="{$item.folder_id}" {if $cat_id eq $item.folder_id}selected{/if}>{$item.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
					<div class="w-100">
						<select class="form-select border-0 rounded-1 bg-lighter text-dark w-100 search_field mb-2" data-width="100%" placeholder="Người ban hành" name="authorized_person" data-field="authorized_person" onChange="$Core.docs.search_doc(this,event)">
							<option value="">Chọn người ban hành</option>
							{if !empty($list_staffs)}
								{foreach from=$list_staffs item=item}
									<option value="{$item.profile_id}" {if $authorized_person eq $item.profile_id}selected{/if}>{$item.full_name}</option>
								{/foreach}
							{/if}
						</select>
					</div>
					<input class="form-control w-100 rounded-1 border-0 bg-lighter text-dark search_field" type="date" name="effective_date" data-field="effective_date" value="{$effective_date}" placeholder="" onChange="$Core.docs.search_doc(this,event)">
				</div>
			</div>
		{/if}
		{if $deviceType ne 'phone'}
			<div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
				<div class="w-px-150">
					<select class="form-select border-0 rounded-pill bg-lighter text-dark w-100 search_field" data-width="100%" placeholder="Danh mục" name="cat_id" data-field="cat_id" onChange="$Core.docs.search_doc(this,event)">
						<option value="">Chọn thư mục</option>
						{if !empty($lstCategory_doc)}
							{foreach from=$lstCategory_doc item=item}
								<option value="{$item.folder_id}" {if $cat_id eq $item.folder_id}selected{/if}>{$item.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="w-auto">
					<select class="form-select border-0 rounded-pill bg-lighter text-dark w-100 search_field" data-width="100%" placeholder="Người ban hành" name="authorized_person" data-field="authorized_person" onChange="$Core.docs.search_doc(this,event)">
						<option value="">Chọn người ban hành</option>
						{if !empty($list_staffs)}
							{foreach from=$list_staffs item=item}
								<option value="{$item.profile_id}" {if $authorized_person eq $item.profile_id}selected{/if}>{$item.full_name}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<input class="form-control w-px-150 rounded-pill border-0 bg-lighter text-dark search_field" type="date" name="effective_date" data-field="effective_date" value="{$effective_date}" placeholder="" onChange="$Core.docs.search_doc(this,event)">
			</div>
		{/if}
	</div>
	<input type="hidden" name="submit" value="search">
</form>