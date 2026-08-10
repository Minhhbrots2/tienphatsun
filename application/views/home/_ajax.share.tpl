{if $template_type eq '_form'}
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable{if $share_type eq 'secret' || $share_type eq 'honor'} modal-ipad{/if}">
		<form method="POST" class="modal-content" enctype="multipart/form-data">
			<div class="modal-header">
				<h5 class="modal-title">{if $action eq '_edit'}Sửa{else}Thêm{/if} {$titlePage}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-group mb-2">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="form-floating">
						<textarea rows="3" class="form-control required" id="{$uid}" name="title" maxlength="255" 
						placeholder="Nhập tiêu đề bản tin">{$oneShare.title}</textarea>
						<label for="{$uid}">Tiêu đề</label>
					</div>
				</div>
				{if $share_type eq 'secret'}
				<div class="form-group form-row mb-2">
					<div class="col-12 col-lg-7">
						<div class="form-row">
							<div class="col-6">
								<div class="form-floating">
									<input type="date" class="form-control required" id="{$uid}" name="start_date" maxlength="255" placeholder="dd-mm-yyyy" 
									onChange="$Core.share.handle_date(this, event)" value="{$more_information.end_date|date_format:'%Y-%m-%d'}" />
									<label for="{$uid}">Từ ngày</label>
								</div>
							</div>
							<div class="col-6">
								<div class="form-floating">
									<input type="date" class="form-control js__share-end_date-field required" id="{$uid}" 
									name="end_date" maxlength="255" placeholder="dd-mm-yyyy" value="{$more_information.end_date|date_format:'%Y-%m-%d'}" />
									<label for="{$uid}">Tới ngày</label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-5">
						<div class="form-floating">
							{assign var = uid value = $clsISO->getUniqid()}
							<select id="{$uid}" name="project_id" class="form-control form-select">
								<option value="0">Lựa chọn dự án</option>
								{foreach name=i from=$list_all_projects item = _oProject}
								<option{if $more_information.project_id eq $_oProject.project_id} selected{/if} value="{$_oProject.project_id}">{$_oProject.title}</option>
								{/foreach}
							</select>
							<label for="{$uid}">Dự án</label>
						</div>
					</div>
				</div>
				<div class="form-group mb-2">
					<label class="form-label mb-1">Nội dung</label>
					<textarea class="form-control isoTextArea" data-name="content" rows="2" cols="255" 
						id="{$clsISO->getUniqid()}">{$more_information.content}</textarea>
				</div>
				{else if $share_type eq 'share'}
				<div class="form-group form-row mb-2">
					<div class="col-6">
						<div class="form-floating">
							<input uid="{$uid}" type="hidden" name="customer_id" value="{$more_information.customer_id}" />
							<input uid="{$uid}" type="text" class="form-control required" name="customer_name" maxlength="255" 
							placeholder="Nguyễn Văn A"{if $action eq '_edit'} value="{$more_information.customer_name}"{/if} />
							{if $deviceType eq 'phone'}
							<a data-toggle="ripple" onClick="$Core.global.crm.open_select(this, event)" class="btn btn-sm btn-icon btn-link rounded-pill position-absolute top-px-20 right-px-10" call_from="share" uid="{$uid}"><i class="bx bx-search"></i></a>
							{else}
							<a data-toggle="ripple" onClick="$Core.global.crm.open_select(this, event)" class="btn btn-sm btn-link rounded-pill position-absolute top-px-20 right-px-10" call_from="share" uid="{$uid}"><i class="bx bx-search"></i> Lựa chọn</a>
							{/if}
							<label class="d-flex">Họ tên khách</label>
						</div>
					</div>
					<div class="col-6">
						<div class="form-floating">
							<input uid="{$uid}" type="text" class="form-control required" name="customer_phone" maxlength="255" 
							placeholder="4 số cuối SĐT"{if $action eq '_edit'} value="{$more_information.customer_phone}"{/if} />
							<label>Điện thoại</label>
						</div>
					</div>
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-6">
						<div class="form-floating">
							<input type="text" class="form-control required" name="location" maxlength="255" 
							placeholder="Địa điểm gặp khách"{if $action eq '_edit'} value="{$more_information.location}"{/if} />
							<label>Địa điểm</label>
						</div>
					</div>
					<div class="col-6">
						<div class="form-floating">
							<input type="number" class="form-control required" name="guest_count" maxlength="255" 
							placeholder="Số lượng khách tham gia"{if $action eq '_edit'} value="{$more_information.guest_count}"{/if} />
							<label>Số khách</label>
						</div>
					</div>
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-6">
						<div class="form-multiselect">
							<label>Mục tiêu</label>
							<select class="form-control required multiselect" name="target_ids[]" multiple="multiple">
								{$clsProperty->getSelectByPropertyV2('PURPOSE', $more_information.target_ids, "", true)}
							</select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-multiselect">
							<label>Quan tâm</label>
							<select class="form-control required multiselect" name="interest_ids[]" multiple="multiple">
								{$clsProperty->getSelectByPropertyV2('_BEDROOM', $more_information.interest_ids, "", true)}
								{$clsProperty->getSelectByPropertyV2('_TYPE_VILLA', $more_information.interest_ids, "", true)}
							</select>
						</div>
					</div>
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-6">
						<div class="form-floating">
							<input type="text" class="form-control numberonly price-In" name="budget_amount" maxlength="255" 
							placeholder="Ngân sách"{if $action eq '_edit'} value="{$more_information.budget_amount}"{/if} />
							<label>Ngân sách</label>
						</div>
					</div>
					<div class="col-6">
						<div class="form-multiselect">
							<label>Dự án quan tâm</label>
							<select class="form-control multiselect" name="project_id">
								{$clsSetting->getSelectBySettingOrigin('_PROJECT', 0, $more_information.project_id, "Chọn dự án")}
							</select>
						</div>
					</div>
				</div>
				<div class="form-group mb-2">
					<div class="form-multiselect">
						<label>Tình trạng</label>
						<div class="d-flex align-items-center gap-1 flex-wrap">
							{foreach from=$arr_status item = _oStatus}
							<label class="we-radio small no-crm">
								<input type="radio" {if $more_information.status_id eq $_oStatus.property_id} checked="checked"{/if} 
									name="status_id" value="{$_oStatus.property_id}" />
								<span>{$_oStatus.title}</span>
							</label>
							{/foreach}
						</div>
					</div>
				</div>
				<div class="form-floating mb-2">
					<textarea class="form-control required" name="content" rows="2" cols="255" 
						placeholder="Mô tả ngắn về cuộc gặp với khách hàng">{if $action eq '_edit'}{$more_information.content}{/if}</textarea>
					<label>Mô tả ngắn về cuộc gặp</label>
				</div>
				{elseif $share_type ne 'share'}
				<div class="form-group form-row mb-2">
					<div class="col-7 col-lg-8">
						<small>Người được vinh danh</small>
						<div class="selecttize-lg mt-1">
							<select class="form-control multiselect" data-placeholder="Nhân viên" data-width="100%" data-header="true" 
								data-filter="true" data-selected_text="người" multiple id="slb_Building_Id" name="staff_id[]" data-field="staff_id[]">
								{foreach from=$list_staffs item=_oStaff}
								<option value="{$_oStaff.profile_id}"{if $clsISO->checkItemInArray($_oStaff.profile_id,$lst_staff_id)}selected{/if} >{$_oStaff.full_name}</option>
								{/foreach}
							</select>
						</div>
					</div>
					<div class="col-5 col-lg-4">
						<div class="form-floating">
							<input class="form-control" name="stock_code" value="{$more_information.stock_code}" placeholder="Ghi căn hộ đã bán nếu có" />
							<label for="{$uid}">Căn hộ(nếu có)</label>
						</div>
					</div>
				</div>
				{/if}
				<div class="form-group">
					{if $share_type eq 'share'}
						<div class="d-flex justify-content-between align-items-center gap-2">
							<label class="form-label mb-1">Hình ảnh</label>
							<a href="javascript:void(0)" class="text-link text-decoration-underline" data-fancybox="standard_{$uid}" data-src="{$URL_IMAGES}/quy-chuan-tiep-khach.png">Quy chuẩn tiếp khách</a>
						</div>
					{else}
						<label class="form-label mb-1">Hình ảnh</label>
					{/if}
					<div class="we-filedrop-wrapper mt-1">
						{assign var = uid value = $clsISO->getUniqid()}
						{if $share_id gt '0' && !empty($list_images)}
							{foreach name=i from=$list_images item = _oImage}
							<input type="hidden" name="images[]" value="{$_oImage}" />
							<div uid="{$uid}" class="we-filedrop__image d-flex align-items-center justify-content-center">
								<a class="delete" src="{$_oImage}" uid="{$uid}" onclick="$Core.global.share.re_upload_share(this, event)"></a>
								<img class="img-responsive" style="max-width:100%" src="{$_oImage}">
							</div>
							{/foreach}
						{/if}
						<input class="d-none upload_image_share" onChange="$Core.global.share.upload_image_share(this, event)" 
							name="image" id="{$uid}" accept="image/*" type="file">
						<div uid="{$uid}" class="we-filedrop cursor-pointer{if $share_id gt '0' && !empty($list_images)} d-none{/if}"{if $deviceType eq 'phone'} style="padding:50px 15px"{else} style="padding:80px 15px"{/if} onclick="$Core.global.share.upload_share(this,event);"> 
							<svg class="mb-2" width="70" height="70" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M118.42 75.84C118.43 83.2392 116.894 90.5589 113.91 97.33H16.09C12.8944 90.0546 11.3622 82.1579 11.6049 74.2154C11.8477 66.2728 13.8593 58.4844 17.4932 51.4177C21.1271 44.3511 26.2918 38.1841 32.6109 33.3662C38.93 28.5483 46.2443 25.2008 54.0209 23.5676C61.7976 21.9345 69.8406 22.0568 77.564 23.9257C85.2873 25.7946 92.4965 29.363 98.6661 34.3709C104.836 39.3787 109.81 45.6999 113.228 52.8739C116.645 60.0478 118.419 67.8937 118.42 75.84Z" fill="#F2F2F2"></path><path d="M5.54 97.33H126.37" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M97 97.33H49.91V34.65C49.91 34.3848 50.0154 34.1305 50.2029 33.9429C50.3904 33.7554 50.6448 33.65 50.91 33.65H84.18C84.6167 33.6541 85.0483 33.7445 85.4499 33.9162C85.8515 34.0878 86.2152 34.3372 86.52 34.65L96.02 44.15C96.3321 44.4533 96.5811 44.8153 96.7527 45.2151C96.9243 45.615 97.0152 46.0449 97.02 46.48L97 97.33Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" fill="white"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M88.97 52.42H77.33V40.77L88.97 52.42Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M27.32 65.49V70.6" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M29.88 68.04H24.76" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M110.49 32.5601V39.9901" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M114.2 36.27H106.77" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M34.07 14.58V25.59" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M39.57 20.08H28.57" stroke="#D7D7D7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M67 115.86V67.12" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" fill="white"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" stroke="#63666A" stroke-width="1" stroke-miterlimit="10"></path>
							</svg> 
							<p class="mb-0">Bấm để chọn một hoặc nhiều hình ảnh cần tải lên !!!</p>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-toggle="ripple" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">Hủy bỏ</button>
				<button type="button" data-toggle="ripple" class="btn btn-primary flex-fill" share_type="{$share_type}" share_id="{$share_id}" 
					onClick="$Core.global.share.pop_save_share(this, event)">Lưu lại</button>
			</div>
		</form>
	</div>               
{else}
	{if !empty($list_shares)}
	{foreach name=i from=$list_shares item = _oShare}
		{if $share_type eq 'share'}
			{$core->getBlock('share_item', ['_oShare' => $_oShare])}
		{else}
			<div class="awe__post-item awe__share-item" reg_date="{$_oShare.reg_date}">
				{assign var = share_id value = $_oShare.share_id}
				{assign var = _title value = $clsShare->getTitle($share_id, $_oShare)}
				<div class="w-100 d-flex align-items-center justify-content-between mb-3">
					<div class="awe__post-profile d-flex" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oShare.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="350">
						<div class="awe__post-avatar position-relative">
							{$clsProfile->get_icon_verified($_oShare.user_id, $_oShare.db_profile.more_information)}
							<img class="rounded" src="{$_oShare.db_profile.avatar}" 
								onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="44" height="44" /> 
						</div>
						<div class="awe__post-profile-body">
							<p class="awe__post-name">{$_oShare.db_profile.name}</p>
							<div class="d-flex gap-2 align-items-center">
								{if !empty($_oShare.db_profile.level)}
								<span class="awe__post-level text-muted">
									{$_oShare.db_profile.level}
								</span>
								{/if}
								<span class="awe__post-star text-muted">
									{$_oShare.db_profile.html_star}
								</span>
								<span class="awe__post-time text-muted">
									{$clsISO->getTimeAgo($_oShare.reg_date)}
								</span>
							</div>
						</div>
					</div>
					<div class="dropdown">
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true"><i class="bx bx-dots-vertical-rounded"></i></button>
						<div class="dropdown-menu" data-popper-placement="bottom-end">
							<div class="dropdown-menu" data-popper-placement="bottom-end">
								{if $_oShare.user_id eq $profile_id and $clsShare->checkCanEdit($share_id, $_oShare) eq '1'}
								<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.share.open(this, event)" share_id="{$share_id}" share_type="{$_oShare.share_type}" action="_edit">{$clsISO->makeIcon('bx-edit-alt me-1', 'Chỉnh sửa')}</a>
								<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.share.delete_share(this, event)" share_id="{$share_id}">{$clsISO->makeIcon('bx-trash me-1', 'Xóa')}</a>
								{/if}
							</div>
						</div>
					</div>
				</div>
				<div class="awe__post-item-body">
					<div class="awe__post-title mb-2" share_id="{$share_id}" action="_detail">
						<a class="fs-16 font-normal awe__post-link" title="{$_title}">{$_title}</a>
					</div>
					{if $share_type eq 'secret'}
					<div class="awe__post-meta mb-2 text-muted">
						<p class="mb-1">Từ ngày: {$_more_information.start_date|date_format:'%d/%m/%Y'} - Tới ngày:  {$_more_information.end_date|date_format:'%d/%m/%Y'}</p>
						<p>Dự án: {if !empty($_more_information.project_id)} 
							{$_more_information.project_name}
						{else}--{/if}</p>
					</div>
					{/if}
					{if !empty($_oShare.images)}
					<div class="awe__post-gallery gallery mb-3">
						{$clsShare->getImageGrid($share_id, $_oShare.images)}
					</div>
					{/if} 
				</div>
				<div class="awe__post-cmd border-bottom mb-2">
					<div class="d-flex justify-content-center">
						<a href="javascript:void(0);" share_id="{$share_id}" 
						class="awe__post-action awe__share-like-action">
							{assign var = total_liked value = $_oShare.total_liked}
							{if $clsShare->checkLiked($share_id, $_oShare)}
								{if $total_liked gt '0'}
									{$clsISO->makeIcon('bxs-heart',$total_liked|cat:' Thích')}
								{else}
									{$clsISO->makeIcon('bx-heart', 'Thích')}
								{/if}
							{else}
								{if $total_liked gt '0'}
									{$clsISO->makeIcon('bx-heart',$total_liked|cat:' Thích')}
								{else}
									{$clsISO->makeIcon('bx-heart','Thích')}
								{/if}
							{/if}
						</a>
						<a href="javascript:void(0);" class="awe__post-action awe__post-share-action" 
						data-toggle="modal" data-target="#sharer"><i class="bx bx-share"></i> Bình luận</a>
						<a href="javascript:void(0);" class="awe__post-action awe__post-share-action" 
						data-toggle="modal" data-target="#sharer"><i class="bx bx-share"></i> Chia sẻ</a>
					</div>
				</div>
				{$core->getBlock('comment', ['table_id' => $share_id, 'clsTable' => 'Share', 'autoload' => 1])}
			</div>
		{/if}
	{/foreach} 
	{/if}
{/if}
{literal}
<style>
	.form-multiselect {
		position: relative;
		border: 1px solid #ced4da;
		border-radius: .375rem;
		padding: 25px 10px 8px;
		background: var(--bs-white);
		min-height:58px;
	}
	/* label nổi bên trong */
	.form-multiselect > label {
		position: absolute;
		top: 4px;
		left: 10px;
		font-size: 12px;
		color: #6c757d;
		background: var(--bs-white);
		padding: 0 4px;
	}
	/* bỏ viền button của multiselect */
	.form-multiselect .btn-group,
	.form-multiselect .multiselect {
		width: 100%;
		background:var(--bs-white) !important;
	}
	.form-multiselect .multiselect {
		border: none !important;
		box-shadow: none !important;
		padding: 0;
	}
	/* focus */
	.form-multiselect:focus-within {
		border-color: #86b7fe;
		box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
	}
</style>
{/literal}