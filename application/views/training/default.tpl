<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
{if $view_training eq 'grid' || $deviceType ne "computer"}
	<div class="d-flex justify-content-between align-items-center mb-3">
		<div class="kYlZoryVmS">
			<h4 class="fw-bold mb-1">Trung tâm đào tạo </h4>
			<i class="text-muted">Có <span class="text-main fw-bold">{$total_record}</span> khóa học đào tạo tại {$smarty.const.BRAND_NAME}</i>
		</div>
		<div class="d-flex justify-content-end gap-2">					
			<form action="" method="post">
				<input type="hidden" name="submit" value="search">
				<div class="input-group">
					<div class="dropdown">
						<button type="button" class="btn btn-icon btn-outline-primary hide-arrow dropdown-toggle" 
						data-bs-toggle="dropdown" data-toggle="ripple" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
							<i class="bx bx-search"></i>
						</button>
						<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-menu-end w-px-300" 
						data-popper-placement="bottom-end">
							<div class="p-3">
								<div class="form-row mb-2">
									<div class="col-12 mb-2">
										<div class="form-label mb-1">Từ khóa</div>
										<input type="text" name="keyword" value="{$key_search}" class="form-control no-focus" placeholder="Tìm kiếm" />
									</div>
									<div class="col-12">
										<div class="form-label mb-1">Danh mục</div>
										<select data-width="100%" data-placeholder="Danh mục" data-allow-clear="false" 
										name="cat_ids[]" class="iso-select2" multiple>
											<option value="0">Danh mục</option>
											{$clsProperty->getSelectByPropertyV2('_TRAINING_CAT',$cat_ids,'Danh mục')}
										</select>
									</div>
								</div>
								<hr class="my-2">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Tìm kiếm</button>
								</div>
							</div>
						</div> 
					</div>
				</div>
			</form>
			{if $deviceType ne phone}
				<div class="input-group">
					<div class="border btn btn-icon">
						<input type="radio" name="view" value="grid" hidden id="view_grid" onchange="$Core.training.set_view(this,event)" {if $view_training eq 'grid'}checked{/if}>
						<label class="btn_view cursor-pointer" for="view_grid"><i class='bx bxs-grid-alt' ></i></label>
					</div>
					<div class="border btn btn-icon">
						<input type="radio" name="view" value="list" hidden id="view_table" onchange="$Core.training.set_view(this,event)" {if $view_training eq 'list'}checked{/if}>
						<label class="btn_view cursor-pointer" for="view_table"><i class='bx bx-list-ul fs-22' ></i></label>
					</div>
				</div>
			{/if}
		</div>
	</div>
	<div class="alert alert-warning fs-4 text-center">
		{if $deviceType eq 'phone'}
		<div class="d-flex flex-column justify-content-center lh-xs text-main">
			<div class="mb-2">
				<i class='bx bxs-quote-alt-left mt-n2'></i> 
				<i>Đầu tư vào tri thức luôn mang lại lợi nhuận cao nhất.</i>
				<i class='bx bxs-quote-alt-right'></i>
			</div>
			<span class="text-center text-muted text-fs-12">-- Benjamin Franklin --</span>
		</div>
		{else}
		<div class="d-flex justify-content-center text-main">
			<div class="d-inline-flex flex-column">
				<div class="d-flex gap-1 align-items-center">
					<i class='bx bxs-quote-alt-left mt-n2'></i> 
					<i>Đầu tư vào tri thức luôn mang lại lợi nhuận cao nhất.</i>
					<i class='bx bxs-quote-alt-right'></i>
				</div>
				<span class="text-right text-fs-12 text-muted">-- Benjamin Franklin</span>
			</div>
		</div>
		{/if}
	</div>
	<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-xxl-5">
		{if !empty($lstTraining)}
			{foreach from=$lstTraining item=_oItem key=key name=i}
			<div class="col mb-4">
				<div class="item_training h-100 card no-shadow overflow-hidden cursor-pointer" onclick="$Core.global.training.open(this,event)" training_id="{$_oItem.training_id}">
					<div class="box_image image-scale">
						<img class="w-100 h-auto" src="{$clsISO->resize_image_url($_oItem.image,480,320)}" alt="{$_oItem.title}" width="340" height="225">
					</div>
					<div class="box_imfo">
						<div class="p-3">
							<h3 class="title_training mb-2 text-dark limit_1line">{$_oItem.title}</h3>
							<div class="author mb-2">bởi <strong>{$_oItem.author}</strong></div>
							<div class="d-flex flex-wrap justify-content-between">
								<div class="mb-1 flex-fill">
									<i class='bx bx-video me-1' ></i>{$_oItem.total_lesson} bài học
								</div>	
								{if !empty($_oItem.time_training)}
								<div class="mb-1 time flex-fill">
									<i class='bx bx-time me-1' ></i>{$clsISO->convertTimeMinute($_oItem.time_training)}
								</div>
								{/if}
								{if !empty($_oItem.cat_name)}
									<div class="mb-1 time flex-fill">
										<i class='bx bx-book-content me-1'></i>{$_oItem.cat_name}
									</div>
								{/if}
								{if !empty($_oItem.total_profile_learning)}
									<div class="mb-1 time w-auto flex-fill">
										<i class='bx bx-user me-1' ></i>{$_oItem.total_profile_learning} người đã học
									</div>
								{/if}
							</div>
						</div>
						<div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top gap-2">
							<div class=""><i class="material-icons-outlined me-1">visibility</i>{$_oItem.total_view} lượt xem</div>	
							{*<div class="mb-1"><span data-url="/index.php?mod=training&act=load_list_participants&training_id={$_oItem.training_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile" ><i class='bx bx-user mr-1 align-top'></i>{$_oItem.total_profile} </span></div>
							<div class="time">Thời lượng: {$clsISO->convertTimeMinute($_oItem.time_training)}</div>*}
							<div class="done_ratio_{$_oItem.training_id}">{$clsTraining->getProgress($_oItem.training_id, $_oItem)}</div>
						</div>
					</div>
				</div>
			</div>
			{/foreach}
		{else}
		<div class="empty w-100 rounded-3">
			<div class="p-5 text-center bg-white">
				<img src="{$URL_IMAGES}/listing-empty.svg" />
				<p>Danh sách trống</p>
			</div>
		</div>
		{/if}
	</div>	
	{if !empty($html_pager)}
		<div class="pagination justify-content-center">{$html_pager}</div>
	{/if}
{else}
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
		<div class="kYlZoryVmS">
			<h4 class="fw-bold mb-0">Khóa học đào tạo</h4>
			<span class="text-muted">Các khóa học đào tạo tại {$smarty.const.BRAND_NAME}</span>
		</div>		
		{if $deviceType ne phone}
		<div class="d-flex justify-content-end gap-2">
			<form action="" method="post">
				<input type="hidden" name="submit" value="search">
				<div class="input-group input-group-merge w-px-200">
					<span class="input-group-text" id="keyword"><i class="bx bx-search"></i></span>
					<input type="text" class="form-control" placeholder="Tìm kiếm" name="keyword" aria-label="Tìm kiếm" aria-describedby="keyword" value="{$keyword}">
				</div>
			</form>
			<div class="input-group {if $deviceType eq 'phone'}d-none{/if}">
				<div class="border btn btn-icon">
					<input type="radio" name="view" value="grid" hidden id="view_grid" onchange="$Core.training.set_view(this,event)" {if $view_training eq 'grid'}checked{/if}>
					<label class="btn_view" for="view_grid"><i class='bx bxs-grid-alt' ></i></label>
				</div>
				<div class="border btn btn-icon">
					<input type="radio" name="view" value="list" hidden id="view_table" onchange="$Core.training.set_view(this,event)" {if $view_training eq 'list'}checked{/if}>
					<label class="btn_view" for="view_table"><i class='bx bx-list-ul fs-22' ></i></label>
				</div>
			</div>
		</div>
		{else}
		<form action="" method="post">
			<div class="d-flex align-items-center justify-content-end box_search">
				<input type="text" class="inp_search form-control top_search_keyword shadow-none" name="keyword" value="{$keyword}" placeholder="Tìm kiếm"  autocomplete="off" onKeyUp="$Core.training.search(this,event)">
				<button class="btn btn-outline-none btn_search btn-icon border" type="button" onclick="$Core.training.show_search(this,event)">
					<i class="bx bx-search fs-4 lh-0"></i>
				</button>
			</div>			
			<input type="hidden" name="submit" value="search">
		</form>
		{/if}
	</div>
	<div class="card no-shadow">
		<div class="card-body">
			<div class="table-container no-shadow overflow-x-auto">
				<table class="table dragable" cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead><tr>
						<th class="align-center h-px-40 bg-lighter text-left">Tiêu đề</th>
						<th class="align-center h-px-40 bg-lighter text-center" width="100px">Bài học</th>
						<th class="align-center h-px-40 bg-lighter bg-lighter text-center" width="100px">Thời lượng</th>
						<th class="align-center h-px-40 bg-lighter bg-lighter text-center" width="80px">Số lượt xem</th>
						<th class="align-center h-px-40 bg-lighter bg-lighter text-center" width="80px">Số người đã học</th>
						<th class="align-center h-px-40 bg-lighter text-center" width="100px">Hoàn thành</th>
					</tr></thead>
					<tbody>
						{if !empty($lstTraining)}
							{foreach from=$lstTraining item=_oItem key=key name=i}
								<tr class="tr">
									<td class="text-left" style="min-width:200px">
										<div class="d-flex align-items-center gap-2">
											<div class="box_image image-scale rounded-3 d-flex align-items-center">
												<img class="" src="{$_oItem.image}" alt="{$_oItem.title}" width="100" height="50" loading="lazy">
											</div>
											<div class="d-flex flex-column flex-fill w-50">
												<a class="fs-15 fw-semibold" href="javascript:void(0);" onclick="$Core.global.training.open(this,event)" title="Xem ngay" training_id="{$_oItem.training_id}">{$_oItem.title}</a> 								
												{if $_oItem.author}<div class="text-dark"><span class="fw-bold">Tác giả:</span> {$_oItem.author}</div>{/if}
											</div>
										</div>
									</td>
									<td class="text-nowrap text-center">{$_oItem.total_lesson}</td>
									<td class="text-nowrap text-center">{$clsISO->convertTimeMinute($_oItem.time_training)}</td>
									<td class="text-nowrap text-center"><i class="material-icons-outlined me-1">visibility</i>{$_oItem.total_view} lượt</td>
									<td class="text-nowrap text-center">{$_oItem.total_profile_learning}</td>
									<td class="text-nowrap text-center">
										<div class="metadata-row-viewer d-flex justify-content-center mt-2 done_ratio_{$_oItem.training_id}">
											{$clsTraining->getProgress($_oItem.training_id, $_oItem)}
										</div>
									</td>
								</tr>
							{/foreach} 
						{else}
							<tr class="tr">
								<td colspan="4" class="text-center">Danh sách trống</td>
							</tr>
						{/if}
					</tbody>
				</table>
			</div>
			{if !empty($html_pager)}
				<div class="pagination justify-content-center mt-3">{$html_pager}</div>
			{/if}
		</div>
	</div>
{/if}
</div>