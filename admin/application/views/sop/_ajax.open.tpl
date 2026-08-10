<div class="modal_sop modal-dialog modal-dialog-centered modal-ipad"> 
	<div class="modal-content">
		<div class="modal-header position-relative d-block">
			<div class="box_header_modal_sop d-flex m-n1 align-items-center justify-content-between">
				<h5 class="modal-title fs-3 text-main">{$clsSop->getCode($oneSop)}</h5>
				<h4 class="text_price mb-0 text-main fw-bold fs-5">
					{if $oneSop.is_locked eq '1'}
						<i data-bs-toggle="tooltip" data-bs-trigger="hover" title="Đã khóa" class="material-icons-outlined fs-small text-danger">lock</i>
					{/if}
					
					{$clsISO->priceFormatV2($oneSop.price,4)} tỷ
				</h4>
			</div>
			<div class="d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center p_left">
					<span class="text-muted fs-12 mr-1">
						<img class="avatar avatar-xs" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" src="{$clsProfile->getAvatar($oneSop.user_id , 30, 30)}" />
					</span>
					<span class="text-muted fs-12">
						<i class="fa fa-clock-o" aria-hidden="true"></i> {$clsISO->getTimeAgo($oneSop.upd_date)} 
					</span>
				</div>
				<div class="d-flex align-items-center p_right">
					<div class="approve verified d-inline-flex {if $oneSop.is_online eq 2} bg-red text-red{else if $oneSop.is_online eq 1} bg-green text-green{else} bg-yellow text-yellow{/if} fs-12 py-1 px-2 align-items-center gap-1 w-max rounded-pill mr-2">
						<svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" class="Property_verifiedIcon__6IIBo mr-2"><path d="M8.12 15.23c-.19 0-.38-.07-.53-.22l-1.65-1.65H3.62c-.41 0-.75-.34-.75-.75v-2.33L1.22 8.65a.75.75 0 0 1 0-1.06l1.65-1.65V3.61c0-.41.34-.75.75-.75h2.33l1.64-1.64c.29-.29.77-.29 1.06 0l1.65 1.65h2.33c.41 0 .75.34.75.75v2.33l1.65 1.65c.29.29.29.77 0 1.06l-1.65 1.65v2.33c0 .41-.34.75-.75.75H10.3l-1.65 1.65c-.15.15-.34.19-.53.19Zm-3.75-3.36h1.89c.2 0 .39.08.53.22l1.33 1.33 1.33-1.33a.75.75 0 0 1 .53-.22h1.89V9.98c0-.2.08-.39.22-.53l1.33-1.33-1.33-1.33a.75.75 0 0 1-.22-.53V4.37H9.98a.75.75 0 0 1-.53-.22L8.12 2.82 6.79 4.15a.75.75 0 0 1-.53.22H4.37v1.89c0 .2-.08.39-.22.53L2.82 8.12l1.33 1.33c.14.14.22.33.22.53v1.89Zm3.99-2.03 2.38-2.38c.29-.29.29-.77 0-1.06a.754.754 0 0 0-1.06 0L7.83 8.25l-.9-.9a.754.754 0 0 0-1.06 0c-.29.29-.29.77 0 1.06L7.3 9.84c.15.15.34.22.53.22s.38-.07.53-.22Z" fill="rgb(9,121,54)"></path></svg>
						{if $oneSop.is_online eq 2}
							Không kiểm duyệt
						{else if $oneSop.is_online eq 1}
							Đã kiểm duyệt
						{else} 
							Chờ kiểm duyệt
						{/if}
					</div>
					{if $oneSop.fee_included eq '1'}
					<div class="verified d-inline-flex align-items-center bg-primary text-white fs-12 py-1 px-2 align-items-center gap-1 w-max rounded-pill">
						<i class="fa fa-usd mr-2" aria-hidden="true"></i> 
						<span>Bao phí</span>
					</div>
					{else}
					<div class="verified d-inline-flex align-items-center bg-danger opacity-50 text-white fs-12 py-1 px-2 align-items-center gap-1 w-max rounded-pill">
						<i class="fa fa-usd mr-2" aria-hidden="true"></i>
						<span>Không bao phí</span>
					</div>
					{/if}
				</div>
			</div>
			<!-- <button type="button" class="btn-close mvZcooMzbH" data-bs-dismiss="modal" aria-label="Close"></button> -->
		</div>
		<form id="frmIssue"  action="POST" enctype="multipart/form-data" charset="UTF-8">
		    <div class="modal-body">
				<div class="rounded-2 mb-2 overflow-hidden">
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2 mb-0">
						<div class=" text-center flex-fill">
							<p class="lbl_highlight mb-1 text-muted lh-base"><i class="fa fa-bed" aria-hidden="true"></i> Loại căn</p>
							<div class="txt_highlight fw-bold text-main">{$clsProperty->getTitle($oneSop.bedroom_id)}</div>
						</div>
						<div class=" text-center flex-fill"> 
							<p class="lbl_highlight mb-1 lh-base"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 15px;height:15px;vertical-align: middle;fill: #a1acb8;"><path d="M256 64c53 0 96 43 96 96s-43 96-96 96s-96 43-96 96s43 96 96 96C150 448 64 362 64 256S150 64 256 64zm0 448A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm32-352a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg> Hướng ban công</p>
							<div class="txt_highlight fw-bold text-main">{$clsProperty->getTitleQR($oneSop.home_direction_id)}</div>
						</div>
						<div class=" text-center flex-fill">
							<p class="lbl_highlight mb-1 lh-base"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 15px;height:15px;vertical-align: middle;fill: #a1acb8;"><path d="M64 64c0-17.7-14.3-32-32-32S0 46.3 0 64V400c0 44.2 35.8 80 80 80H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H80c-8.8 0-16-7.2-16-16V64zm96 288H448c17.7 0 32-14.3 32-32V251.8c0-7.6-2.7-15-7.7-20.8l-65.8-76.8c-12.1-14.2-33.7-15-46.9-1.8l-21 21c-10 10-26.4 9.2-35.4-1.6l-39.2-47c-12.6-15.1-35.7-15.4-48.7-.6L135.9 215c-5.1 5.8-7.9 13.3-7.9 21.1v84c0 17.7 14.3 32 32 32z"/></svg> Thông thuỷ</p>
							<div class="txt_highlight fw-bold text-main">{$stock_information.DT_TT} m<sup>2</sup></div>
						</div>
						<div class=" text-center flex-fill d-lg-block">
							<p class="lbl_highlight mb-1 lh-base"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 15px;height:15px;vertical-align: middle;fill: #a1acb8;"><path d="M64 64c0-17.7-14.3-32-32-32S0 46.3 0 64V400c0 44.2 35.8 80 80 80H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H80c-8.8 0-16-7.2-16-16V64zm96 288H448c17.7 0 32-14.3 32-32V251.8c0-7.6-2.7-15-7.7-20.8l-65.8-76.8c-12.1-14.2-33.7-15-46.9-1.8l-21 21c-10 10-26.4 9.2-35.4-1.6l-39.2-47c-12.6-15.1-35.7-15.4-48.7-.6L135.9 215c-5.1 5.8-7.9 13.3-7.9 21.1v84c0 17.7 14.3 32 32 32z"/></svg> Tim tường</p>
							<div class="txt_highlight fw-bold text-main">{$stock_information.DT_Tim} m<sup>2</sup></div>
						</div>
					</div>
					<div class="re_overlay js_overlay"></div>
					{if !empty($list_medias)}
					<div id="{$clsISO->getUniqid()}" class="slideshow owl-carousel mb-0">
						{foreach from=$list_medias item = _oMedia}
						<div class="d-flex overflow-hidden slideshow-item align-items-center justify-content-center">
							<img src="{$domain_myocean}{$_oMedia.image}" alt="{$oneSop.stock_code}" />
						</div>
						{/foreach}
					</div>
					{/if}
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2">
						{if $deviceType eq 'phone'}
						<div class="text-center flex-fill">
							<p class="mb-1 lh-base"><i class="d-inline-block re__icon-sun--sm"></i> Khoảng tầng</p>
							<div class="fw-bold">
								{$clsSop->getRangeFloor($oneSop.floor)}
							</div>
						</div>
						{else}
						<div class="text-center flex-fill">
							<p class="mb-1 text-muted lh-base"><i class="fa fa-building" aria-hidden="true"></i> Phân khu</p>
							<div class="txt_highlight d-flex justify-content-center align-items-center fw-bold text-main">
								{$clsProperty->getTitle($oneSop.block_id)}
							</div>
						</div>
						{/if}
						<div class="text-center flex-fill">
							<p class="mb-1 lh-base"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="width: 15px;height:15px;vertical-align: middle;fill: #a1acb8;"><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg> Toà nhà</p>
							<div class="txt_highlight d-flex justify-content-center align-items-center fw-bold text-main">
								{$clsProperty->getTitle($oneSop.building_id)}
							</div>
						</div>
						<div class="text-center flex-fill">
							<p class="mb-1 lh-base"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="width: 15px;height:15px;vertical-align: middle;fill: #a1acb8;"><path d="M112 112c0 35.3-28.7 64-64 64V336c35.3 0 64 28.7 64 64H464c0-35.3 28.7-64 64-64V176c-35.3 0-64-28.7-64-64H112zM0 128C0 92.7 28.7 64 64 64H512c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128zM176 256a112 112 0 1 1 224 0 112 112 0 1 1 -224 0zm80-48c0 8.8 7.2 16 16 16v64h-8c-8.8 0-16 7.2-16 16s7.2 16 16 16h24 24c8.8 0 16-7.2 16-16s-7.2-16-16-16h-8V208c0-8.8-7.2-16-16-16H272c-8.8 0-16 7.2-16 16z"/></svg> Giá/m<sup>2</sup></p>
							<div class="txt_highlight fw-bold text-main">{$clsISO->shortNumber($price_m2)}</div>
						</div>
						<div class="text-center flex-fill d-lg-block">
							<p class="mb-1 lh-base"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 15px;height:15px;vertical-align: middle;fill: #a1acb8;"><path d="M246.6 150.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l96-96c12.5-12.5 32.8-12.5 45.3 0l96 96c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L352 109.3V384c0 35.3 28.7 64 64 64h64c17.7 0 32 14.3 32 32s-14.3 32-32 32H416c-70.7 0-128-57.3-128-128c0-35.3-28.7-64-64-64H109.3l41.4 41.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0l-96-96c-12.5-12.5-12.5-32.8 0-45.3l96-96c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3L109.3 256H224c23.3 0 45.2 6.2 64 17.1V109.3l-41.4 41.4z"/></svg> Khoảng tầng</p>
							<div class="txt_highlight fw-bold">
								{$clsSop->getRangeFloor($oneSop.floor)}
							</div>
						</div>
					</div>
				</div>
				<div class="content tinyContent">
					{$more_information.content|nl2br}
				</div>
			</div>
			<!-- <hr class="my-2" /> -->
			<div class="modal-footer">
				<div class="w-100 d-flex flex-wrap ox-sm:col-4 align-items-center gap-2">
					{if $profile_id eq $oneSop.user_id}
					<div class="btn-group">
						<button type="button" class="btn btn-outline-default dropdown-toggle" data-bs-toggle="dropdown">Dành cho chủ nhà</button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="{$PCMS_URL}/cn/edit/{$sop_id}" class="btn flex-fill btn-outline-default">
								<i class="material-icons-outlined">border_color</i> Sửa</a></li>
							{if isset($more_information.is_locked) && $more_information.is_locked eq '1'}
							<li><a class="dropdown-item" onClick="$Core.sop.mark_lock(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">lock_open</i> Mở Khoá</a></li>
							{else}
							<li><a class="dropdown-item" onClick="$Core.sop.mark_lock(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">lock</i> Khoá</a></li>
							{/if}
							{if isset($more_information.is_solded) && $more_information.is_solded eq '1'}
							<li><a class="dropdown-item text-muted" sop_id="{$sop_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">storefront</i> Đã bán</a></li>
							{else}
							<li><a class="dropdown-item" onClick="$Core.sop.mark_sold(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">storefront</i> Báo bán</a></li>
							{/if}
							<li><a class="dropdown-item" onClick="$Core.sop.delete(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
								<i class="material-icons-outlined">delete</i> Xoá</a></li>
						</ul>
					</div>
					{/if}
					<a href="tel:{$oneSop.contact_phone}" class="btn flex-fill btn-outline-default">
						<i class="fa fa-phone" aria-hidden="true"></i> {$oneSop.contact_phone}</a>
					<a href="https://zalo.me/{$oneSop.contact_phone}" class="btn flex-fill btn-outline-default">
						<img src="{$URL_IMAGES}/zalo_chat.png" width="20px" /> Chat Zalo</a>
					<a type="button" class="btn flex-fill btn-outline-default close js_close_pop_{$sop_id}" data-dismiss="modal" >Đóng cửa sổ</a>
				</div>
			</div>
		</form>
	</form>
</div>
{literal}
<style type="text/css">
	.slideshow{width:100%;background:#DDD; overflow:hidden;}
	.slideshow-item > img{ max-height:400px;}
	.slideshow-item:after{ content: ""; width: 100%; height: 100%; position: absolute; left: 0; top: 0; }
	@media screen and (max-width:575px){
		.slideshow{margin-left: -1.05rem; margin-right: -1.05rem; width: calc(100% + 2.1rem);}
		.slideshow-item > img{ max-height:225px;}
		.mvZcooMzbH{ position: absolute; top: 20px; right:-10px; width: .4rem; height: .4rem;}
	}
	.owl-dots{display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;
	display:flex;flex-wrap:wrap;position:absolute;left:0;width:100%;bottom:12px;justify-content:center}
	.owl-dot{width:12px;height:12px;border-radius:100%;background:#ddd;margin:6px}
	.owl-dot.active{background:#ffeb3b}
</style>
{/literal}