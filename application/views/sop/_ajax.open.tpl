<div class="modal-dialog modal-dialog-centered modal-ipad">
	<div class="modal-content">
		{assign var = stock_code value = $clsSop->getCode($oneSop)}
		<div class="modal-header position-relative d-block">
			<div class="d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center">
					<h5 class="modal-title fs-4 text-main mr-2 text-upper">
						{$more_information.sop_code} - {$stock_code}</h5>
					<a href="javascript:void(0)" onClick="$Core.sop.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="Đã sao chép link căn hộ" data-bs-trigger="click" data-link="{$PCMS_URL}{$clsSop->getLink($sop_id, $stock_code)}" class="sop_link text-dark rounded-pill fs-6">{$clsISO->makeIcon('bx-copy')}</a>
				</div>
				<h4 class="mb-0 text-main fw-bold fs-{if $deviceType eq 'phone'}5{else}3{/if}">
					<span class="sop__icon-{$sop_id}">{$clsSop->getIcon($sop_id, $type_list,'_detail')}</span>
					<span class="re__srp-stock-price">{$clsISO->shortNumber($oneSop.price,2)}</span>
				</h4>
			</div>
			<div class="d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center p_left">
					<span class="text-muted fs-12 mr-1">
						<a onClick="$Core.sop.add_filter(this, event)" tp="user" user_id="{$oProfile.profile_id}" title="{$clsProfile->getFullName($oProfile.profile_id, $oProfile)}" full_name="{$clsProfile->getFullName($oProfile.profile_id, $oProfile)}" href="javascript:;">
							<img class="avatar rounded-pill avatar-xs" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" src="{$clsProfile->getAvatar($oProfile.profile_id,$oProfile,30,30)}" alt="{$clsProfile->getFullName($oProfile.profile_id, $oProfile)}" />
						</a>
					</span>
					<span class="text-muted text-nowrap fs-12">
						<i class="material-icons-outlined">update</i> 
						{$clsISO->getTimeAgo($oneSop.upd_date)}
					</span>
					{if $deviceType ne 'phone'}
					<span class="text-muted fs-12 ml-2">
						<i class="material-icons-outlined">visibility</i>
						{$clsISO->formatNumber($more_information.number_view)} lượt xem
					</span>
					{/if}
				</div>
				<div class="d-flex align-items-center p_right text-nowrap">
					{if $oneSop.is_verified eq '1'}
					<div class="verified d-inline-flex bg-green text-green fs-11 py-1 px-2 align-items-center gap-1 w-max rounded-pill mr-1">
						<svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" class="Property_verifiedIcon__6IIBo"><path d="M8.12 15.23c-.19 0-.38-.07-.53-.22l-1.65-1.65H3.62c-.41 0-.75-.34-.75-.75v-2.33L1.22 8.65a.75.75 0 0 1 0-1.06l1.65-1.65V3.61c0-.41.34-.75.75-.75h2.33l1.64-1.64c.29-.29.77-.29 1.06 0l1.65 1.65h2.33c.41 0 .75.34.75.75v2.33l1.65 1.65c.29.29.29.77 0 1.06l-1.65 1.65v2.33c0 .41-.34.75-.75.75H10.3l-1.65 1.65c-.15.15-.34.19-.53.19Zm-3.75-3.36h1.89c.2 0 .39.08.53.22l1.33 1.33 1.33-1.33a.75.75 0 0 1 .53-.22h1.89V9.98c0-.2.08-.39.22-.53l1.33-1.33-1.33-1.33a.75.75 0 0 1-.22-.53V4.37H9.98a.75.75 0 0 1-.53-.22L8.12 2.82 6.79 4.15a.75.75 0 0 1-.53.22H4.37v1.89c0 .2-.08.39-.22.53L2.82 8.12l1.33 1.33c.14.14.22.33.22.53v1.89Zm3.99-2.03 2.38-2.38c.29-.29.29-.77 0-1.06a.754.754 0 0 0-1.06 0L7.83 8.25l-.9-.9a.754.754 0 0 0-1.06 0c-.29.29-.29.77 0 1.06L7.3 9.84c.15.15.34.22.53.22s.38-.07.53-.22Z" fill="rgb(9,121,54)"></path></svg>
						Đã xác minh
					</div>
					{/if}
					{$clsSop->getFeeLabel($oneSop.fee_included)}
				</div>
			</div>
		</div>
		<form id="frmIssue"  action="POST" enctype="multipart/form-data" charset="UTF-8">
		    <div class="modal-body">
				<div class="rounded-2 mb-2{if $deviceType ne 'phone'} overflow-hidden{/if}">
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2 mb-0">
						<div class=" text-center flex-fill">
							<p class="mb-0 text-muted lh-base">
								<i class="d-inline-block re__icon-bedroom--sm"></i> Loại căn
							</p>
							<div class="d-flex align-items-center justify-content-center fw-bold text-main">
								{if $oneSop.sop_type eq $smarty.const._SOP_TYPE_HIGHLEVEL}
									{$clsProperty->getTitle($oneSop.bedroom_id)}
								{else}
									Liền kề
								{/if}
							</div>
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="re__icon-mall--sm"></i> Pháp lý</p>
							{if $oneSop.juridical_id gt '0'}
								<div class="fw-bold text-main">{$clsProperty->getTitle($oneSop.juridical_id)}</div>
							{else}								
								<div class="fw-bold text-main">--</div>
							{/if}
							
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-size--sm"></i> Diện tích</p>
							{if !empty($more_information.DT_TT)}
								<div class="fw-bold text-main">{$more_information.DT_TT} m<sup>2</sup></div>
							{else}
								<div class="fw-bold text-main">--</div>
							{/if}
						</div>
						<div class=" text-center flex-fill d-none d-lg-block">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-ying-yang--xl"></i> Ban công</p>
							{if !empty($oneSop.home_direction_id)}
							<div class="fw-bold text-main">{$clsProperty->getTitleQR($oneSop.home_direction_id)}</div>
							{else}
							<div class="fw-bold text-main">--</div>
							{/if}
						</div>
					</div>
					<div class="position-relative">
						{assign var = html_video value = $clsSop->getIframeVideo($more_information)}
						{if !empty($list_medias)}
						<div id="{$clsISO->getUniqid()}" class="slideshow{if $total_medias gt '1'} owl-carousel{/if} mb-0">
							{if !empty($html_video)}
							<div class="slideshow-item w-100 h-100 position-relative overflow-hidden" data-fancybox="video" href="{$clsSop->getIframeVideo($more_information,'link')}">
								{$html_video}
							</div>
							{/if}
							{foreach name=ii from=$list_medias item = _oMedia}
							<div class="slideshow-item w-100 h-100 position-relative overflow-hidden">
								<img class="sop_slider_overlay position-absolute zindex-1" src="{$_oMedia.image}" />
								<div class="sop_slider_image d-flex justify-content-center position-relative zindex-2 cursor-zoomIn" data-fancybox="gallery" href="{$_oMedia.image}">
									<img src="{$clsSop->getIMG($_oMedia.image,0,400)}" alt="{$oneSop.stock_code}" />
								</div>
								<span class="sop_slider-pagination d-inline-block position-absolute">
									{$smarty.foreach.ii.iteration}/{$list_medias|@count}
								</span>
							</div>
							{/foreach}
						</div>
						{else}
							{if !empty($html_video)}
								{$html_video}
							{else}
								<img class="w-100" src="{$URL_IMAGES}/no-image.jpg" alt="{$oneSop.title}" />
							{/if}
						{/if}
						{if !empty($more_information.is_exclusive)}
						<div class="exclusive_detail position-absolute p-1 rounded-pill">
							<div class="exclusive_text py-1 px-2 rounded-pill text-upper text-center">Độc quyền</div>
						</div>
						{/if}						
						{if $oneSop.is_solded eq 1}
						<div class="box_solded box_solded_detail position-absolute p-1 rounded-pill">
							<div class="is_solded_text py-1 px-1 rounded-pill text-upper text-center">Đã bán</div>
						</div>
						{/if}
					</div>
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2">
						{if $more_information.sop_type ne $smarty.const._SOP_TYPE_HIGHLEVEL}
							{if !empty({$more_information.floor_total})}
								<div class="text-center flex-fill">
									<p class="mb-1 lh-base"><i class="d-inline-block re__icon-sun--sm"></i> Số tầng</p>
									<div class="fw-bold text-main">{$more_information.floor_total} tầng</div>
								</div>
							{/if}
						{else}
							<div class="text-center flex-fill">
								<p class="mb-1 lh-base"><i class="d-inline-block re__icon-sun--sm"></i> Khoảng tầng</p>
								<div class="fw-bold text-main">{$clsSop->getRangeFloor($oneSop.floor)}</div>
							</div>
						{/if}
						<div class="text-center flex-fill d-none d-lg-block">
							<p class="mb-1 lh-base"><i class="d-inline-block re__icon-bedroom--sm"></i> Nội thất</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								{$clsProperty->getTitle($oneSop.interior_id)}&nbsp;
								{if !empty($list_devices)}
								<a class="text-main ml-1" data-bs-toggle="tooltip" title="Nội thất, trang thiết bị" sop_id="{$sop_id}" onClick="$Core.sop.open_utilities(this, event)" href="javascript:void(0);"> <i class="material-icons-outlined no-translate">unarchive</i></a>
								{/if}
							</div>
						</div>
						<div class="text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-money--sm"></i> Giá/m<sup>2</sup></p>
							{if empty($price_m2)}
							<div class="fw-bold text-main">--</div>
							{else}
							<div class="fw-bold text-main">{$clsISO->shortNumber($price_m2)}</div>
							{/if}
						</div>
						<div class="text-center flex-fill">
							<p class="mb-1 lh-base"><i class="d-inline-block re__icon-money--sm"></i> Tổng giá</p>
							{if empty($oneSop.price)}
							<div class="fw-bold text-main">--</div>
							{else}
							<div class="fw-bold text-main">{$clsISO->shortNumber($oneSop.price, 2)}</div>
							{/if}
						</div>
					</div>
				</div>
				<div class="content tinyContent pt-2 content-copy">
					<div class="box_infomation mb-2">
						<div class="d-flex flex-wrap justify-content-between">	
							{if !empty($oneSop.home_direction_id)}
							<div class="d-flex flex-wrap pr-2 fs-14 align-items-center d-lg-none">
								<p class="mb-0 text-muted lh-base">
									<i class="d-inline-block re__icon-ying-yang--xl"></i> Ban công: </p>
								<div class="pl-2 fw-bold text-main">
									{$clsProperty->getTitleQR($oneSop.home_direction_id)}
								</div>
							</div>
							{/if}
							{if !empty($oneSop.interior_id)}
							<div class="d-flex flex-wrap pr-2 fs-14 align-items-center d-lg-none">
								<p class="mb-0 text-muted lh-base">
									<i class="d-inline-block re__icon-bedroom--sm"></i> Nội thất: 
								</p>
								<div class="pl-2 fw-bold text-main">
									{$clsProperty->getTitle($oneSop.interior_id)}
									{if !empty($list_devices)}
									<a class="text-main ml-1" data-bs-toggle="tooltip" title="Nội thất, trang thiết bị" sop_id="{$sop_id}" onClick="$Core.sop.open_utilities(this, event)" href="javascript:void(0);"> <i class="material-icons-outlined no-translate">unarchive</i></a>
									{/if}
								</div>
							</div>
							{/if}
							{if !empty($more_information.bedroom_count)}
							<div class="{if $deviceType eq 'phone'}mb-1{else}w-50 mb-2{/if} d-flex flex-wrap pr-2 fs-14 align-items-center">
								<p class="mb-0 text-muted lh-base"><i class="re__icon-bedroom--sm fs-5"></i> Phòng ngủ: </p>
								<div class="pl-2 fw-bold text-main">
									{$more_information.bedroom_count} phòng
								</div>
							</div>
							{/if}
							{if !empty($more_information.bathroom_count)}
							<div class="{if $deviceType eq 'phone'}mb-1{else}w-50 mb-2{/if} d-flex flex-wrap pr-2 fs-14 align-items-center">
								<p class="mb-0 text-muted lh-base"><i class="bx bx-bath align-text-bottom fs-5"></i> Phòng tắm: </p>
								<div class="pl-2 fw-bold text-main">{$more_information.bathroom_count} phòng</div>
							</div>
							{/if}
							{if !empty($more_information.balcony_count)}
							<div class="{if $deviceType eq 'phone'}mb-1{else}w-50 mb-2{/if} d-flex flex-wrap pr-2 fs-14 align-items-center">
								<p class="mb-0 text-muted lh-base">
									<i class="bx bx-border-radius align-text-bottom fs-5"></i> Ban công: </p>
								<div class="pl-2 fw-bold text-main">{$more_information.balcony_count} ban công</div>
							</div>
							{/if}
						</div>
					</div>
					<div class="d-flex justify-content-between">
						<h4 class="text-dark">{$oneSop.title}</h4>
						{if $more_information.content}<a href="javascript:void(0)" onclick="$Core.sop.copyContentToClipboard(this, event)" data-bs-toggle="tooltip" title="Đã sao chép nội dung mô tả" data-bs-trigger="click" data-link="{$PCMS_URL}{$clsSop->getLink($sop_id, $stock_code)}" class="sop_copy text-dark rounded-pill fs-6"><i class='bx bx-copy'></i></a>{/if}
					</div>
					<div class=".tinyContent">
						<div class="content_sop">{$more_information.content|nl2br}</div>						
						{if !empty($advantage_ids)}
						<div class="pt-3">
							<label for="" class="mb-0 fw-semibold lh-base text-dark">Ưu điểm căn hộ</label>
							<ul class="list-group pl-2">
								{foreach from=$advantage_ids item=_oAdvantage}
								<li class="d-flex align-items-center">
								  <i class='bx bx-check me-2 fs-24 text-success'></i>
								  {$_oAdvantage.title}
								</li>
								{/foreach}
							</ul>
						</div>
						{/if}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="w-100 d-flex flex-wrap ox-sm:col-4 align-items-center gap-2{if $loggedIn ne '1'} oDimQHEQij{/if}">
					{if $profile_id eq $oneSop.user_id}
					<div class="btn-group flex-fill">
						<button type="button" class="btn btn-outline-default dropdown-toggle px-2" data-bs-toggle="dropdown">Dành cho chủ nhà</button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="{$PCMS_URL}/cn/edit/{$sop_id}" class="btn flex-fill btn-outline-default">
								<i class="material-icons-outlined">border_color</i> Sửa</a></li>
							{if isset($more_information.is_locked) && $more_information.is_locked eq '1'}
							<li><a class="dropdown-item sop__menu-lock-{$sop_id}{if $clsSop->checkSolded($sop_id,$more_information) || $clsSop->checkDeleted($sop_id,$more_information)} preventDefault{/if}" onClick="$Core.sop.mark_lock(this, event)" sop_id="{$sop_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock_open</i> Mở Khoá</a></li>
							{else}
							<li><a class="dropdown-item sop__menu-lock-{$sop_id}{if $clsSop->checkSolded($sop_id,$more_information) || $clsSop->checkDeleted($sop_id, $more_information)} preventDefault{/if}" onClick="$Core.sop.mark_lock(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">lock</i> Khoá</a></li>
							{/if}
							{if isset($more_information.is_solded) && $more_information.is_solded eq '1'}
							<li><a class="dropdown-item sop__menu-sold-{$sop_id}{if $clsSop->checkDeleted($sop_id, $more_information)} preventDefault{/if}" sop_id="{$sop_id}" onClick="$Core.sop.mark_sold(this, event)" href="javascript:void(0);">
								<i class="material-icons-outlined">add_business</i> Mở bán</a></li>
							{else}
							<li><a class="dropdown-item sop__menu-sold-{$sop_id}{if $clsSop->checkDeleted($sop_id, $more_information)} preventDefault{/if}" onClick="$Core.sop.mark_sold(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">storefront</i> Báo bán</a></li>
							{/if}
							{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'}
							<li><a class="dropdown-item sop__menu-delete-{$sop_id}{if $oneSop.is_locked eq '1'} preventDefault{/if}" onClick="$Core.sop.delete(this, event)" sop_id="{$sop_id}" href="javascript:void(0);"><i class="material-icons-outlined">settings_backup_restore</i> Khôi phục</a></li>
							{else}
							<li><a class="dropdown-item sop__menu-delete-{$sop_id}{if $oneSop.is_locked eq '1'} preventDefault{/if}" onClick="$Core.sop.delete(this, event)" sop_id="{$sop_id}" href="javascript:void(0);"><i class="material-icons-outlined">delete</i> Xoá</a></li>
							{/if}
						</ul>
					</div>
					{/if}
					<!-- onClick="$Core.sop.addLog(this,event)" -->
					<a href="tel:{$oneSop.contact_phone}" type="phone" sop_id="{$sop_id}" 
						class="btn flex-fill btn-outline-default qqfSRjPFMr px-2">
						<i class="material-icons-outlined">call</i> 
						{$clsProfile->mask($smarty.const.SOP_CONTACT_PHONE, 1)}
					</a>
					<a href="https://zalo.me/{$smarty.const.SOP_CONTACT_PHONE}" type="zalo" target="_blank" sop_id="{$sop_id}" 
						class="btn flex-fill btn-outline-default qqfSRjPFMr text-nowrap over px-2">
						<img src="{$URL_IMAGES}/zalo_chat.png" width="20px" /> 
						{if $deviceType eq 'phone'}
							{$clsISO->truncate($smarty.const.SOP_CONTACT_NAME,16)}
						{else}
							{$clsSop->getContactName($smarty.const.SOP_CONTACT_NAME)}
						{/if}
					</a>
					{if $loggedIn ne '1'}
						<a href="{$PCMS_URL}/cn/add" title="Đăng tin miễn phí" class="btn flex-fill btn-outline-default qqfSRjPFMr text-nowrap px-2"> Đăng tin <img src="{$URL_IMAGES}/free.png" width="46px" /></a>
						{if $deviceType eq 'phone'}
						<a type="button" class="btn d-flex align-items-center justify-content-center flex-fill btn-outline-default qqfSRjPFMr" ret_url="{$ret_url}" onClick="$Core.sop.close_pop(this, event)" data-bs-dismiss="modal"><i class='bx bx-x fs-4'></i> Đóng cửa sổ</a>
						{else}
						<a type="button" class="btn btn-icon btn-outline-default qqfSRjPFMr" ret_url="{$ret_url}" onClick="$Core.sop.close_pop(this, event)" data-bs-dismiss="modal"><i class='bx bx-x fs-2'></i></a>
						{/if}
					{else}			
						{if $profile_id eq $oneSop.user_id}
							<a type="button" class="btn btn-icon btn-outline-default qqfSRjPFMr" ret_url="{$ret_url}" 
							onClick="$Core.sop.close_pop(this, event)" data-bs-dismiss="modal"><i class='bx bx-x fs-2'></i></a>
						{else}
							{if $deviceType eq 'phone'}
							<a type="button" class="btn d-flex align-items-center justify-content-center flex-fill btn-outline-default qqfSRjPFMr" onClick="$Core.sop.close_pop(this, event)" ret_url="{$ret_url}" data-bs-dismiss="modal"><i class='bx bx-x fs-4'></i> Đóng cửa sổ</a>
							{else}
							<a type="button" class="btn btn-icon btn-outline-default qqfSRjPFMr" ret_url="{$ret_url}" onClick="$Core.sop.close_pop(this, event)" data-bs-dismiss="modal"><i class='bx bx-x fs-2'></i></a>
							{/if}
						{/if}
					{/if}
				</div>
			</div>
		</form>
	</form>
</div>
{literal}
<style type="text/css">
	.slideshow{width:100%; overflow:hidden;}
	@media screen and (max-width:575px){
		.slideshow{margin-left: -1.05rem; margin-right: -1.05rem; width: calc(100% + 2.1rem);}
		.mvZcooMzbH{ position: absolute; top: 20px; right:-10px; width: .4rem; height: .4rem;}
	}
	.owl-dots{display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;
	display:flex;flex-wrap:wrap;position:absolute;left:0;width:100%;bottom:22px;justify-content:center}
	.owl-dot{width:12px;height:12px;border-radius:100%;background:#ddd;margin:6px}
	.owl-dot.active{background:#ffeb3b}
</style>
{/literal}