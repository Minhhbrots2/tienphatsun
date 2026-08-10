<div class="modal-dialog modal-dialog-centered modal-ipad">
	<div class="modal-content">
		{assign var = stock_code value = $clsLeasing->getCode($oneLeasing)}
		<div class="modal-header position-relative d-block">
			<div class="d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center">
					{if $oneLeasing.is_solded eq '1'}
					<div class="re__srp-stock-sold re__srp-stock-sold-{$leasing_id}">
						<span class="label fs-11 fw-light bg-warning mr-1">Đã cho thuê</span>
					</div>
					{/if}
					<h5 class="modal-title fs-3 text-main mr-2">{$stock_code} - {$clsProperty->getTitle($oneLeasing.bedroom_id)}</h5>
					<a href="javascript:void(0)" onClick="$Core.leasing.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="Đã sao chép link căn hộ" data-bs-trigger="click" data-link="{$PCMS_URL}{$clsLeasing->getLink($leasing_id, $stock_code)}" class="sop_link text-dark rounded-pill fs-6">{$clsISO->makeIcon('bx-link')}</a>
				</div>
				<h4 class="mb-0 text-main fw-bold fs-5">
					<span class="re__srp-stock-lock re__srp-stock-lock-{$leasing_id}">
						{if $oneLeasing.is_solded eq 0 && $oneLeasing.is_locked eq '1'}
						<i class="material-icons-outlined fs-small text-danger" data-bs-toggle="tooltip" 
						   data-bs-trigger="hover" title="Đã khóa">lock</i>
						{/if}
					</span>
					<span class="re__srp-stock-price" {$oneLeasing.price}>{$clsISO->shortNumber($oneLeasing.price)}/1 tháng</span>
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
				</div>
				<div class="d-flex align-items-center p_right text-nowrap">
					<div class="verified d-inline-flex bg-green text-green fs-11 py-1 px-2 align-items-center gap-1 w-max rounded-pill mr-1">
						<svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" class="Property_verifiedIcon__6IIBo"><path d="M8.12 15.23c-.19 0-.38-.07-.53-.22l-1.65-1.65H3.62c-.41 0-.75-.34-.75-.75v-2.33L1.22 8.65a.75.75 0 0 1 0-1.06l1.65-1.65V3.61c0-.41.34-.75.75-.75h2.33l1.64-1.64c.29-.29.77-.29 1.06 0l1.65 1.65h2.33c.41 0 .75.34.75.75v2.33l1.65 1.65c.29.29.29.77 0 1.06l-1.65 1.65v2.33c0 .41-.34.75-.75.75H10.3l-1.65 1.65c-.15.15-.34.19-.53.19Zm-3.75-3.36h1.89c.2 0 .39.08.53.22l1.33 1.33 1.33-1.33a.75.75 0 0 1 .53-.22h1.89V9.98c0-.2.08-.39.22-.53l1.33-1.33-1.33-1.33a.75.75 0 0 1-.22-.53V4.37H9.98a.75.75 0 0 1-.53-.22L8.12 2.82 6.79 4.15a.75.75 0 0 1-.53.22H4.37v1.89c0 .2-.08.39-.22.53L2.82 8.12l1.33 1.33c.14.14.22.33.22.53v1.89Zm3.99-2.03 2.38-2.38c.29-.29.29-.77 0-1.06a.754.754 0 0 0-1.06 0L7.83 8.25l-.9-.9a.754.754 0 0 0-1.06 0c-.29.29-.29.77 0 1.06L7.3 9.84c.15.15.34.22.53.22s.38-.07.53-.22Z" fill="rgb(9,121,54)"></path></svg>
						{if $deviceType ne 'phone'}Đã kiểm duyệt{else}Đã duyệt{/if}
					</div>
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
							<div class="pt-1">
								{$clsLeasing->getInteriorLabel($oneLeasing.interior_id)}
							</div>
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-ying-yang--xl"></i> Hướng ban công</p>
							<div class="fw-bold text-main">{$clsProperty->getTitleQR($oneLeasing.home_direction_id)}</div>
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-money--sm"></i> Thanh toán</p>
							<div class="fw-bold text-main">{$more_information.txt_rental_term}</div>
						</div>
						<div class=" text-center flex-fill d-none d-lg-block">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-size--sm"></i> Tim tường</p>
							<div class="fw-bold text-main">{$stock_information.DT_Tim} m<sup>2</sup></div>
						</div>
					</div>
					{if !empty($list_medias)}
					<div id="{$clsISO->getUniqid()}" class="slideshow owl-carousel mb-0">
						{foreach name=ii from=$list_medias item = _oMedia}
						<div class="slideshow-item w-100 h-100 position-relative overflow-hidden">
							<img class="sop_slider_overlay position-absolute zindex-1" src="{$_oMedia.image}" />
							<div class="sop_slider_image d-flex justify-content-center position-relative zindex-2">
								<img src="{$_oMedia.image}?v={$upd_version}" alt="{$oneLeasing.stock_code}" />
							</div>
							<span class="sop_slider-pagination d-inline-block position-absolute">
								{$smarty.foreach.ii.iteration}/{$list_medias|@count}
							</span>
						</div>
						{/foreach}
					</div>
					{else}
					<img class="w-100" src="{$URL_IMAGES}/no-image.jpg" alt="{$oneLeasing.title}" />
					{/if}
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2">
						{if $deviceType eq 'phone'}
						<div class="text-center flex-fill">
							<p class="mb-1 lh-base"><i class="d-inline-block re__icon-sun--sm"></i> Khoảng tầng</p>
							<div class="fw-bold">
								{$clsLeasing->getRangeFloor($oneLeasing.floor)}
							</div>
						</div>
						{else}
						<div class="text-center flex-fill">
							<p class="mb-0 text-muted lh-base"><i class="d-inline-block re__icon-building--sm"></i> Phân khu</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								{$clsProperty->getTitle($oneLeasing.block_id)}&nbsp;
								{if $oneLeasing.project_id gt '0' && $oneLeasing.block_id gt '0'}
								<a href="{$clsProject->getLinkBl($oneLeasing.project_id, $oneLeasing.block_id)}" target="_blank"><i class="material-icons-outlined no-translate">info</i></a>
								{/if}
							</div>
						</div>
						{/if}
						<div class="text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-house--sm"></i> Toà nhà</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								{$clsProperty->getTitle($oneLeasing.building_id)}&nbsp;
								{if $oneLeasing.project_id gt '0' && $oneLeasing.block_id gt '0' && $oneLeasing.building_id gt '0'}
								<a href="{$clsProject->getLinkBu($oneLeasing.project_id, $oneLeasing.block_id,$oneLeasing.building_id)}" target="_blank"><i class="material-icons-outlined no-translate">info</i></a>
								{/if}
							</div>
						</div>
						<div class="text-center flex-fill d-none d-lg-block">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-sun--sm"></i> Khoảng tầng</p>
							<div class="fw-bold">
								{$clsLeasing->getRangeFloor($oneLeasing.floor)}
							</div>
						</div>
					</div>
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2">							
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class='bx bx-book-bookmark align-text-bottom fs-5'></i> Đồ cơ bản</p>
							<div class="fw-bold text-main">{$clsProperty->getTitle($more_information.base_utensils_id)}</div>
						</div>						
						<div class="text-center flex-fill">
							<p class="mb-0 text-muted lh-base"><i class='bx bx-log-in-circle align-text-bottom fs-5'></i> Thời gian vào được</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								{$more_information.rental_period}
							</div>
						</div>				
						<div class="text-center flex-fill">
							<p class="mb-0 text-muted lh-base"><i class='bx bx-time-five align-text-bottom fs-5' ></i> Thời hạn thuê</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								<div class="fw-bold text-main">{$clsProperty->getTitle($more_information.rental_term_leasing_id)}</div>
							</div>
						</div>	
					</div>
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2 d-none">											
						<div class="text-center flex-fill">
							<p class="mb-0 text-muted lh-base"><i class='bx bx-door-open align-text-bottom fs-5' ></i> Tình trạng</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								<div class="fw-bold text-main">{$clsProperty->getTitle($more_information.status_leasing_id)}</div>
							</div>
						</div>						
						<div class="text-center flex-fill">
							<p class="mb-0 text-muted lh-base"><i class='bx bx-log-out-circle align-text-bottom fs-5' ></i> Hạn hđ ký với chủ nhà tới</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								{$more_information.validity_period}
							</div>
						</div>					
					</div>
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2 d-none">							
						<div class="text-center flex-fill">
							<p class="mb-0 text-muted lh-base"><i class="d-inline-block re__icon-money--sm"></i> Thanh toán điện</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								<div class="fw-bold text-main">{$clsProperty->getTitle($more_information.payment_electric_id)}</div>
							</div>
						</div>						
						<div class="text-center flex-fill">
							<p class="mb-0 text-muted lh-base"><i class="d-inline-block re__icon-money--sm"></i> Thanh toán nước</p>
							<div class="d-flex justify-content-center align-items-center fw-bold text-main">
								<div class="fw-bold text-main">{$clsProperty->getTitle($more_information.payment_water_id)}</div>
							</div>
						</div>						
					</div>
				</div>
				<div class="content tinyContent pt-2">
					<h4>{$oneLeasing.title}</h4>
					{$more_information.content|nl2br}
				</div>
			</div>
			<!-- <hr class="my-2" /> -->
			<div class="modal-footer">
				<div class="w-100 d-flex flex-wrap ox-sm:col-4 align-items-center gap-2">
					{if $profile_id eq $oneLeasing.user_id}
					<div class="btn-group">
						<button type="button" class="btn btn-outline-default dropdown-toggle" data-bs-toggle="dropdown">Dành cho chủ nhà</button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="{$PCMS_URL}/ct/edit/{$leasing_id}" class="btn flex-fill btn-outline-default">
								<i class="material-icons-outlined">border_color</i> Sửa</a></li>
							{if isset($more_information.is_locked) && $more_information.is_locked eq '1'}
							<li><a class="dropdown-item" onClick="$Core.leasing.mark_lock(this, event)" leasing_id="{$leasing_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">lock_open</i> Mở Khoá</a></li>
							{else}
							<li><a class="dropdown-item" onClick="$Core.leasing.mark_lock(this, event)" leasing_id="{$leasing_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">lock</i> Khoá</a></li>
							{/if}
							{if isset($more_information.is_solded) && $more_information.is_solded eq '1'}
							<li><a class="dropdown-item" leasing_id="{$leasing_id}" onClick="$Core.leasing.mark_sold(this, event)" href="javascript:void(0);" is_solded="1">
								<i class="material-icons-outlined">add_business</i> Mở cho thuê</a></li>
							{else}
							<li><a class="dropdown-item" onClick="$Core.leasing.mark_sold(this, event)" leasing_id="{$leasing_id}" href="javascript:void(0);" is_solded="0">
								<i class="material-icons-outlined">storefront</i> Báo đã cho thuê</a></li>
							{/if}
							<li><a class="dropdown-item" onClick="$Core.leasing.delete(this, event)" leasing_id="{$leasing_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">delete</i> Xoá</a></li>
						</ul>
					</div>
					{/if}					
					<a {if $loggedIn eq '1'}href="tel:{$oneLeasing.contact_phone}" onClick="$Core.leasing.update_click({$leasing_id},'call')" {else} href="javascript:;" onClick="$Core.leasing.alert(this,event)"{/if} 
					   class="btn flex-fill btn-outline-default"><i class="material-icons-outlined">call</i> {$clsProfile->mask($oneLeasing.contact_phone, 1)}</a>
					<a {if $loggedIn eq '1'}href="https://zalo.me/{$oneLeasing.contact_phone}"  onClick="$Core.leasing.update_click({$leasing_id},'zalo')"{else} href="javascript:;" onClick="$Core.leasing.alert(this,event)"{/if} 
					   class="btn flex-fill btn-outline-default"><img src="{$URL_IMAGES}/zalo_chat.png" width="20px" /> Chat Zalo</a>
					{if $loggedIn eq '1'}
					<a type="button" class="btn btn-icon btn-outline-default" onClick="$Core.leasing.close_pop(this, event)" data-bs-dismiss="modal">
						<i class='bx bx-x fs-2'></i>
					</a>
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