<div class="modal-dialog modal-dialog-centered modal-ipad">
	<div class="modal-content">
		{assign var = stock_code value = $clsLeasing->getCode($oneLeasing)}
		<div class="modal-header position-relative d-block">
			<div class="d-flex mb-1 align-items-center justify-content-between">
				<div class="d-flex align-items-center">
					{if $oneLeasing.is_solded eq '1'}
					<div class="re__srp-stock-sold re__srp-stock-sold-{$leasing_id}">
						<span class="label fs-11 fw-light bg-warning mr-1">Đã cho thuê</span>
					</div>
					{/if}
					<h5 class="modal-title  fs-{if $deviceType eq 'phone'}5{else}4{/if} text-main mr-2">{if $more_information.sop_type ne $smarty.const._TYPE_HIGHLEVEL}{$stock_code}{else}{$stock_code}-{$clsProperty->getTitle($oneLeasing.bedroom_id)}{/if}</h5>
					<a href="javascript:void(0)" onClick="$Core.leasing.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="Đã sao chép link căn hộ" data-bs-trigger="click" data-link="{$PCMS_URL}{$clsLeasing->getLink($leasing_id, $stock_code)}" class="leasing_link text-dark rounded-pill fs-6">{$clsISO->makeIcon('bx-link')}</a>
				</div>
				<h4 class="mb-0 text-main fw-bold fs-5 text-right">
					<span class="leasing__icon-{$leasing_id}">
						{$clsLeasing->getIcon($leasing_id, $type_list, "_detail")}
					</span>
					<span class="re__srp-stock-price">
						{$clsISO->shortNumber($oneLeasing.price)}<br />
						<span class="text-muted fs-12">/1 tháng</span>
					</span>
				</h4>
			</div>
			<div class="d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center p_left">
					<span class="text-muted fs-12 mr-1">
						<img class="avatar rounded-pill avatar-xs" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" src="{$smarty.const.FH_URL}/{$clsProfile->getAvatar($oProfile.profile_id,30,30,$oProfile)}" title="{$clsProfile->getFullName($oProfile.profile_id, $oProfile)}" />
					</span>
					<span class="text-muted fs-12">
						<i class="material-icons-outlined">update</i> 
						{$clsISO->getTimeAgo($oneLeasing.upd_date)}
					</span>
					
					<span class="text-muted fs-12 ml-2">
						<i class="material-icons-outlined">visibility</i>
						{$clsISO->formatNumber($more_information.number_view)}{if $deviceType ne 'phone'} lượt xem{/if}
					</span>
					
				</div>
				<div class="d-flex align-items-center p_right text-nowrap" {$oneLeasing.is_verified}>
					{if $oneLeasing.is_verified eq '1'}
					<div class="verified d-inline-flex bg-green text-green fs-11 py-1 px-2 align-items-center gap-1 w-max rounded-pill mr-1">
						<svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" class="Property_verifiedIcon__6IIBo"><path d="M8.12 15.23c-.19 0-.38-.07-.53-.22l-1.65-1.65H3.62c-.41 0-.75-.34-.75-.75v-2.33L1.22 8.65a.75.75 0 0 1 0-1.06l1.65-1.65V3.61c0-.41.34-.75.75-.75h2.33l1.64-1.64c.29-.29.77-.29 1.06 0l1.65 1.65h2.33c.41 0 .75.34.75.75v2.33l1.65 1.65c.29.29.29.77 0 1.06l-1.65 1.65v2.33c0 .41-.34.75-.75.75H10.3l-1.65 1.65c-.15.15-.34.19-.53.19Zm-3.75-3.36h1.89c.2 0 .39.08.53.22l1.33 1.33 1.33-1.33a.75.75 0 0 1 .53-.22h1.89V9.98c0-.2.08-.39.22-.53l1.33-1.33-1.33-1.33a.75.75 0 0 1-.22-.53V4.37H9.98a.75.75 0 0 1-.53-.22L8.12 2.82 6.79 4.15a.75.75 0 0 1-.53.22H4.37v1.89c0 .2-.08.39-.22.53L2.82 8.12l1.33 1.33c.14.14.22.33.22.53v1.89Zm3.99-2.03 2.38-2.38c.29-.29.29-.77 0-1.06a.754.754 0 0 0-1.06 0L7.83 8.25l-.9-.9a.754.754 0 0 0-1.06 0c-.29.29-.29.77 0 1.06L7.3 9.84c.15.15.34.22.53.22s.38-.07.53-.22Z" fill="rgb(9,121,54)"></path></svg>
						Đã xác minh
					</div>
					{/if}
					{$clsLeasing->getFeeLabel($oneLeasing.fee_included)}
				</div>
			</div>
		</div>
		<form id="frmIssue"  action="POST" enctype="multipart/form-data" charset="UTF-8">
		    <div class="modal-body">
				<div class="rounded-2 mb-2{if $deviceType ne 'phone'} overflow-hidden{/if}">
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2 mb-0">
						<div class=" text-center flex-fill">
							<p class="mb-0 text-muted lh-base"><i class="d-inline-block re__icon-bedroom--sm"></i> Nội thất</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								{$clsProperty->getTitle($oneLeasing.interior_id)}&nbsp;
								{if $more_information.device_leasing && $more_information.check_utilities eq 1}
									<a class="text-main ml-1" data-bs-toggle="tooltip" title="Nội thất, trang thiết bị" leasing_id="{$leasing_id}" onClick="$Core.leasing.open_utilities(this, event)" href="javascript:void(0);"> <i class="material-icons-outlined no-translate">unarchive</i></a>
								{/if}
							</div>
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-ying-yang--xl"></i> Ban công</p>
							{if !empty($oneLeasing.home_direction_id)}
								<div class="fw-bold text-main">{$clsProperty->getTitleQR($oneLeasing.home_direction_id)}</div>
							{else}
								<div class="fw-bold text-muted">--</div>
							{/if}
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-money--sm"></i> Thanh toán</p>
							{if !empty($more_information.txt_rental_term)}
							<div class="fw-bold text-main">{$more_information.txt_rental_term}</div>
							{else}
							<div class="fw-bold text-muted">--</div>
							{/if}
						</div>
						<div class=" text-center flex-fill d-none d-lg-block">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ</p>
							{if !empty($more_information.DT_TT)}
							<div class="fw-bold text-main">{$more_information.DT_TT} m<sup>2</sup></div>
							{else}
							<div class="fw-bold text-muted">--</div>
							{/if}
						</div>
					</div>
					<div class="relative">
						{if $clsISO->checkItemInArray($leasing_id,$like_leasing)}
							{assign var=liked value=1}
						{else}
							{assign var=liked value=0}
						{/if}
						{if $profile_id != 118}
						<a onclick="javascript:$Core.leasing.handleLike(this, {$leasing_id},'detail')" data-like_id="like_{$leasing_id}" data-bs-toggle="tooltip" data-placement="bottom" class="like {if $liked eq 1}liked{/if}" {if $liked eq 1}title="Bỏ thích"{else}title="Thích"{/if}><i class="fa fa-heart-o" aria-hidden="true"></i></a>
						{/if}
						{assign var = html_video value = $clsLeasing->getIframeVideo($more_information)}
						{if !empty($list_medias)}
						<div id="{$clsISO->getUniqid()}" class="slideshow owl-carousel mb-0">
							{if !empty($html_video)}
							<div class="slideshow-item w-100 h-100 position-relative overflow-hidden cursor-zoomIn" data-fancybox="gallery" href="{$clsLeasing->getIframeVideo($more_information,'link')}?v={$upd_version}">
								{$html_video}
							</div>
							{/if}
							{foreach name=ii from=$list_medias item = _oMedia}
							<div class="slideshow-item w-100 h-100 position-relative overflow-hidden">
								<img class="leasing_slider_overlay position-absolute zindex-1" src="{$_oMedia.image}" />
								<div class="leasing_slider_image d-flex justify-content-center position-relative zindex-2 cursor-zoomIn" data-fancybox="gallery" href="{$_oMedia.image}?v={$upd_version}">
									<img src="{$clsISO->resizeImageFromUrl($_oMedia.image,0,400)}?v={$upd_version}" alt="{$oneLeasing.stock_code}" />
								</div>
								<span class="leasing_slider-pagination d-inline-block position-absolute">
									{$smarty.foreach.ii.iteration}/{$list_medias|@count}
								</span>
							</div>
							{/foreach}
						</div>
						{else}
						<img class="w-100" src="{$URL_IMAGES}/no-image.jpg" alt="{$oneLeasing.title}" />
						{/if}
						{if !empty($more_information.having_dq)}
						<div class="exclusive_detail position-absolute p-1 rounded-pill">
							<div class="exclusive_text py-1 px-2 rounded-pill text-upper text-center">Độc quyền</div>
						</div>
						{/if}
						{if $oneLeasing.is_solded eq 1}
							<div class="box_solded box_solded_detail position-absolute p-1 rounded-pill">
								<div class="is_solded_text p-1 rounded-pill fs-14 text-center">Đã cho thuê</div>
							</div>
						{/if}
					</div>
					
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2">
						<div class="text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-house--sm"></i> Toà/Dãy</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								{if $deviceType eq 'phone'}
									{$clsProperty->getTitle($oneLeasing.building_id)},
									{$clsProperty->getCode($oneLeasing.block_id)}
								{else}
									{$clsProperty->getTitle($oneLeasing.building_id)},
									{$clsProperty->getTitle($oneLeasing.block_id)}
								{/if}
								{if $total_shops gt '0'}
									<a class="text-main ml-1" data-bs-toggle="tooltip" title="Cửa hàng/dịch vụ, tiện ích" building_id="{$oneLeasing.building_id}" onClick="$Core.leasing.open_shop(this, event)" href="javascript:void(0);"> <i class="material-icons-outlined no-translate">storefront</i></a>
								{/if}
							</div>
						</div>
						<div class="text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block bx bx-book-bookmark align-text-bottom fs-5"></i> Đồ cơ bản</p>
							<div class="fw-bold text-main">
								{$clsProperty->getTitle($more_information.base_utensils_id)}
							</div>
						</div>
						<div class="text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-sun--sm"></i> Khoảng tầng</p>
							<div class="fw-bold">
								{$clsLeasing->getRangeFloor($oneLeasing.floor)}
							</div>
						</div>
						<div class="text-center flex-fill d-none d-lg-block">
							<p class="mb-0 lh-base"><i class='bx bx-time-five align-text-bottom fs-5' ></i> Thời hạn thuê</p>
							<div class="fw-bold text-main">
								{$clsProperty->getTitle($more_information.rental_term_leasing_id)}
							</div>
						</div>
					</div>
				</div>
				<div class="content tinyContent pt-2">		
					<div class="box_information_leasing">	
						<div class="box_infomation mb-2">
							<div class="d-flex flex-wrap">
								{if $more_information.sop_type ne $smarty.const._TYPE_HIGHLEVEL && !empty($more_information.type_villa)}
									<div class="{if $deviceType eq 'phone'}mb-1{else}w-50 mb-2{/if} d-flex flex-wrap pr-2 fs-14 align-items-center">
										<p class="mb-0 text-muted lh-base"><i class='bx bx-book-bookmark align-text-bottom fs-5'></i> Loại hình thấp tầng: </p>
										<div class="pl-2 fw-bold text-main">
											{$clsProperty->getTitle($more_information.type_villa)}
										</div>
									</div>
								{/if}
								{if $more_information.rental_period}
									<div class="{if $deviceType eq 'phone'}mb-1{else}w-50 mb-2{/if} d-flex flex-wrap pr-2 fs-14 align-items-center">
										<p class="mb-0 text-muted lh-base"><i class='bx bx-log-in-circle align-text-bottom fs-5'></i> Thời gian vào được: </p>
										<div class="pl-2 fw-bold text-main">
											{$more_information.rental_period}
										</div>
									</div>
								{/if}
								{if !empty($more_information.bedroom_num)}
									<div class="{if $deviceType eq 'phone'}mb-1{else}w-50 mb-2{/if} d-flex flex-wrap pr-2 fs-14 align-items-center">
										<p class="mb-0 text-muted lh-base"><i class="re__icon-bedroom--sm fs-5"></i> Phòng ngủ: </p>
										<div class="pl-2 fw-bold text-main">
											{$more_information.bedroom_num} phòng
										</div>
									</div>
								{/if}
								{if !empty($more_information.bathroom_num)}
									<div class="{if $deviceType eq 'phone'}mb-1{else}w-50 mb-2{/if} d-flex flex-wrap pr-2 fs-14 align-items-center">
										<p class="mb-0 text-muted lh-base"><i class="bx bx-bath align-text-bottom fs-5"></i> Phòng tắm: </p>
										<div class="pl-2 fw-bold text-main">
											{$more_information.bathroom_num} phòng
										</div>
									</div>
								{/if}
								{if !empty($more_information.balcony_num)}
									<div class="{if $deviceType eq 'phone'}mb-1{else}w-50 mb-2{/if} d-flex flex-wrap pr-2 fs-14 align-items-center">
										<p class="mb-0 text-muted lh-base"><i class="bx bx-border-radius align-text-bottom fs-5"></i> Ban công: </p>
										<div class="pl-2 fw-bold text-main">
											{$more_information.balcony_num} ban công
										</div>
									</div>
								{/if}
								{if $deviceType eq 'phone'}
									<div class="{if $deviceType eq 'phone' }w-100 {else}w-50{/if} d-flex flex-wrap pr-2 mb-2 fs-6">
										<p class="mb-0 text-muted lh-base"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ: </p>
										<div class="pl-2 fw-bold text-main">{$more_information.DT_TT} m<sup>2</sup></div>
									</div>
									<div class="{if $deviceType eq 'phone' }w-100 {else}w-50{/if} d-flex flex-wrap pr-2 mb-2 fs-6">
									<p class="mb-0 text-muted lh-base"><i class='bx bx-time-five align-text-bottom fs-5' ></i> Thời hạn thuê: </p>
										<div class="pl-2 fw-bold text-main">
											{$clsProperty->getTitle($more_information.rental_term_leasing_id)}
										</div>
									</div>
								{/if}
							</div>
						</div>	
						<div class="d-flex justify-content-between">
							<h4>{$oneLeasing.title}</h4>
							<a href="javascript:void(0)" onclick="$Core.leasing.copyContentToClipboard(this, event)" data-bs-toggle="tooltip" title="Đã sao chép nội dung mô tả" data-bs-trigger="click" data-link="{$PCMS_URL}{$clsLeasing->getLink($leasing_id, $oneLeasing.stock_code)}" class="leasing_copy text-dark rounded-pill fs-6"><i class='bx bx-copy'></i></a>
						</div>
						
						{if $more_information.content}
						<div class="tinyContentx">
							<div class="content-copy">{$more_information.content|nl2br}</div>						
							{if !empty($advantage_ids)}
								<div class="pt-3">
									<label for="" class="mb-0 fw-semibold lh-base text-dark">Ưu điểm căn hộ: </label>
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
						{/if}
						
					</div>	
				</div>
			</div>
			<!-- <hr class="my-2" /> -->
			<div class="modal-footer">
				<div class="w-100 d-flex flex-wrap ox-sm:col-4 align-items-center gap-2{if $loggedIn ne '1'} oDimQHEQij{/if}">
					{if $profile_id eq $oneLeasing.user_id}
					<div class="btn-group flex-fill">
						<button type="button" class="btn btn-outline-default dropdown-toggle px-2" data-bs-toggle="dropdown">Dành cho chủ nhà</button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="{$PCMS_URL}/ct/edit/{$leasing_id}" class="btn flex-fill btn-outline-default">
								<i class="material-icons-outlined">border_color</i> Sửa</a></li>
							{if isset($more_information.is_locked) && $more_information.is_locked eq '1'}
							<li><a class="dropdown-item leasing__menu-lock-{$leasing_id}{if $clsLeasing->checkSolded($leasing_id,$more_information) || $clsLeasing->checkDeleted($leasing_id,$more_information)} preventDefault{/if}" onClick="$Core.leasing.mark_lock(this, event)" leasing_id="{$leasing_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock_open</i> Mở Khoá</a></li>
							{else}
							<li><a class="dropdown-item leasing__menu-lock-{$leasing_id}{if $clsLeasing->checkSolded($leasing_id,$more_information) || $clsLeasing->checkDeleted($leasing_id, $more_information)} preventDefault{/if}" onClick="$Core.leasing.mark_lock(this, event)" leasing_id="{$leasing_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">lock</i> Khoá</a></li>
							{/if}
							{if isset($more_information.is_solded) && $more_information.is_solded eq '1'}
							<li><a class="dropdown-item leasing__menu-sold-{$leasing_id}{if $clsLeasing->checkDeleted($leasing_id, $more_information)} preventDefault{/if}" leasing_id="{$leasing_id}" onClick="$Core.leasing.mark_sold(this, event)" href="javascript:void(0);">
								<i class="material-icons-outlined">add_business</i> Mở cho thuê</a></li>
							{else}
							<li><a class="dropdown-item leasing__menu-sold-{$leasing_id}{if $clsLeasing->checkDeleted($leasing_id, $more_information)} preventDefault{/if}" onClick="$Core.leasing.mark_sold(this, event)" leasing_id="{$leasing_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">storefront</i> Báo cho thuê</a></li>
							{/if}
							{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'}
							<li><a class="dropdown-item leasing__menu-delete-{$leasing_id}{if $oneLeasing.is_locked eq '1'} preventDefault{/if}" onClick="$Core.leasing.delete(this, event)" leasing_id="{$leasing_id}" href="javascript:void(0);"><i class="material-icons-outlined">settings_backup_restore</i> Khôi phục</a></li>
							{else}
							<li><a class="dropdown-item leasing__menu-delete-{$leasing_id}{if $oneLeasing.is_locked eq '1'} preventDefault{/if}" onClick="$Core.leasing.delete(this, event)" leasing_id="{$leasing_id}" href="javascript:void(0);"><i class="material-icons-outlined">delete</i> Xoá</a></li>
							{/if}
						</ul>
					</div>
					{/if}					
					<a {if $loggedIn eq '1'}data-href="tel:{$oneLeasing.contact_phone}"{else} data-href=""{/if} type="phone" leasing_id="{$leasing_id}"  onClick="$Core.leasing.addLog(this,event)" class="btn flex-fill btn-outline-default px-2"><i class="material-icons-outlined">call</i> {$clsProfile->mask($oneLeasing.contact_phone, 1)}</a>
					<a  {if $loggedIn eq '1'}data-href="https://zalo.me/{$oneLeasing.contact_phone}"{else} data-href=""{/if} type="zalo" leasing_id="{$leasing_id}"  onClick="$Core.leasing.addLog(this,event)" class="btn flex-fill btn-outline-default text-nowrap over px-2"><img src="{$URL_IMAGES}/zalo_chat.png" width="20px" /> 
						{if $deviceType eq 'phone'}
							{$clsISO->truncate($oneLeasing.contact_name,16)}
						{else}
							{$clsLeasing->getContactName($oneLeasing.contact_name)}
						{/if}</a>
					{if $loggedIn ne '1'}
						<a href="{$PCMS_URL}/ct/add" title="Đăng tin miễn phí" class="btn flex-fill btn-outline-default qqfSRjPFMr text-nowrap px-2"> 
							Đăng tin <img src="{$URL_IMAGES}/free.png" width="46px" />
						</a>
						{if $deviceType eq 'phone'}
							<a type="button" class="btn d-flex align-items-center justify-content-center flex-fill btn-outline-default qqfSRjPFMr" return_url="{$return_url}" onClick="$Core.leasing.close_pop(this, event)" data-bs-dismiss="modal"><i class='bx bx-x fs-4'></i> Đóng cửa sổ</a>
						{else}
							<a type="button" class="btn btn-icon btn-outline-default qqfSRjPFMr" return_url="{$return_url}" onClick="$Core.leasing.close_pop(this, event)" data-bs-dismiss="modal"><i class='bx bx-x fs-2'></i></a>
						{/if}
					{else}
						{if $profile_id eq $oneSop.user_id}
							<a type="button" class="btn btn-icon btn-outline-default qqfSRjPFMr" return_url="{$return_url}" onClick="$Core.leasing.close_pop(this, event)" data-bs-dismiss="modal"><i class='bx bx-x fs-2'></i></a>
						{else}
							{if $deviceType eq 'phone'}
							<a type="button" class="btn d-flex align-items-center justify-content-center flex-fill btn-outline-default qqfSRjPFMr" onClick="$Core.leasing.close_pop(this, event)" return_url="{$return_url}" data-bs-dismiss="modal"><i class='bx bx-x fs-4'></i> Đóng cửa sổ</a>
							{else}
							<a type="button" class="btn btn-icon btn-outline-default qqfSRjPFMr" return_url="{$return_url}" onClick="$Core.leasing.close_pop(this, event)" data-bs-dismiss="modal"><i class='bx bx-x fs-2'></i></a>
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
	display:flex;flex-wrap:wrap;position:absolute;left:0;width:100%;bottom:12px;justify-content:center}
	.owl-dot{width:12px;height:12px;border-radius:100%;background:#ddd;margin:6px}
	.owl-dot.active{background:#ffeb3b}
</style>
{/literal}