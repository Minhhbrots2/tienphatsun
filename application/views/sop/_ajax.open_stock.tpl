<div class="modal-dialog modal-dialog-centered modal-standard">
	<div class="modal-content">
		<div class="modal-header border-bottom position-relative">
			<h5 class="modal-title fs-4 text-main text-upper">
				[{$more_information.product_code}] {$oneStock.stock_code}
			</h5>
			<div class="mb-0 text-main fw-bold fs-{if $deviceType eq 'phone'}5{else}4{/if}">
				<span class="re__srp-stock-price">{$clsISO->shortNumber($oneStock.price_owner,2)}</span>
			</div>
			<a type="button" class="btn-close closeEv" data-bs-dismiss="modal"></a>
		</div>
		<div class="modal-body py-0">
			<div class="row">
				<div class="col-12 col-md-8 py-3 mb-2 mb-lg-0">
					{if !empty($list_images)}
					<div class="tEzTuYtLau min-height-350 bg-grayter position-relative rounded-2 w-100 overflow-hidden">
						<div id="{$clsISO->getUniqid()}" class="slideshow{if $total_images gt '1'} owl-carousel{/if}">
							{foreach name=ii from=$list_images item = _oMedia}
							<div class="slideshow-item w-100 h-100 position-relative overflow-hidden">
								<img class="sop_slider_overlay position-absolute zindex-1" src="{$_oMedia}" />
									<div class="sop_slider_image d-flex justify-content-center position-relative zindex-2 cursor-zoomIn" data-fancybox="gallery" href="{$_oMedia}">
									<img src="{$_oMedia}" alt="{$oneStock.stock_code}" />
								</div>
								<span class="sop_slider-pagination d-inline-block position-absolute">
									{$smarty.foreach.ii.iteration}/{$total_images}
								</span>
							</div>
							{/foreach}
						</div>
					</div>
					{else}
					<div class="tEzTuYtLau min-height-350 d-flex align-items-center justify-content-center bg-grayter position-relative rounded-2 w-100 overflow-hidden">
						<img class="min-height-350" src="{$URL_IMAGES}/no-image.jpg" alt="{$oneSop.title}" />
					</div>
					{/if}
					<hr class="my-3" />
					<div class="block">
						<div class="p-3 mb-2 bg-lighter rounded-2">
							<h4 class="mb-2 text-fs-16">Thông tin telesale <a><i class="bx bx-pencil"></i></a></h4>
							<div class="tinyContent">
								{if !empty($more_information.sales_contact_info)}
									{$more_information.sales_contact_info|nl2br}
								{/if}
							</div>
						</div>
					</div>
					<hr class="my-3" />
					<div class="block">
						<h4 class="mb-2 text-fs-16"><i class='bx bx-phone-outgoing'></i> Liên hệ với khách hàng</h4>
						<form class="mb-3 p-3 radius-2 bg-lighter {$uid}" method="post" enctype="multipart/form-data">
							<textarea uid="{$uid}" class="form-control autosize required" name="content" placeholder="Nhập nội dung liên hệ" rows="2"></textarea>
							<div class="checklist-add-controls d-flex align-items-center gap-2 mt-2 u-clearfix">
								<button type="button" class="btn btn-primary js-ripple" onclick="$Core.sop.save_contact(this, event)" telesale_id="{$telesale_id}" contact_id="0" uid="{$uid}">Thêm</button>
							</div>
						</form>
						<div class="holder_contact-{$telesale_id}">
							Chưa có liên hệ nào
						</div>
					</div>
					<!-- <hr class="my-3" />
					<div class="block">
						<h4 class="mb-2 text-fs-16"><i class='bx bx-note' ></i> Ghi chú</h4>
						<form id="{$toId}" class="frmIssue p-3 rounded-2 bg-lighter mb-3" action="POST">
							<textarea class="form-control autosize" name="content" rows="1" placeholder="Nhập ghi chú"></textarea>
							<div class="clearfix mt-2">
								<button type="button" tp="_create" class="btn btn-outline-primary" 
								for_id="{$telesale_id}" clsTable="Telesale" note_id="" onClick="$Core.helper.save_notes(this,event)">Thêm</button>
							</div>
						</form>
						<div class="holder_notes_{$telesale_id}">
							<div class="loader p-5 text-center">
								Loading...
							</div>
						</div>
					</div> -->
				</div>
				<div class="col-12 col-md-4 bg-lighter py-3">
					<h4 class="mb-2 text-fs-16">Thông tin</h4>
					<p class="my-1 text-fs-12"><i class="material-icons-outlined">update</i>  
					Ngày tạo: {$clsISO->convertTimeToText($oneStock.reg_date, true)}</p>
					<p class="my-1 text-fs-12"><i class="material-icons-outlined">update</i>  Liên hệ cuối: {$clsISO->convertTimeToText($oneStock.upd_date, true)}</p>
					<hr class="my-3" />
					<div class="d-flex mb-1 align-items-center gap-2">
						<span class="d-flex align-items-center gap-2 text-muted">
							<i class="d-inline-block re__icon-bedroom--sm"></i> 
							Loại căn
						</span>
						<span>
							{if $oneStock.stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
								{$clsProperty->getTitle($oneStock.bedroom_id)}
							{else}
								{$clsProperty->getTitle($oneStock.type_id)}
							{/if}
						</span>
					</div>
					<div class="d-flex mb-1 align-items-center gap-2">
						<span class="d-flex align-items-center gap-2 text-muted">
							<i class="d-inline-block re__icon-size--sm"></i> 
							Diện tích
						</span>
						<span>
							{if !empty($more_information.DT_TT)}
								{$more_information.DT_TT} m<sup>2</sup>
							{else}
								{if !empty($stock_information.DT_TT)}
									{$stock_information.DT_TT} m<sup>2</sup>
								{else}
									N/A
								{/if}
							{/if}
						</span>
					</div>
					<div class="d-flex mb-1 align-items-center gap-2">
						<span class="d-flex align-items-center gap-2 text-muted">
							<i class="d-inline-block re__icon-document--sm"></i> 
							Pháp lý
						</span>
						{if !empty($more_information.juridical_id)}
							<span class="fw-bold text-main">{$more_information.juridical_name}</span>
						{else}								
							<span class="text-muted">N/A</span>
						{/if}
					</div>
					<div class="d-flex mb-1 align-items-center gap-2">
						<span class="d-flex align-items-center gap-2 text-muted">
							<i class="d-inline-block re__icon-private-house--sm"></i> Hướng
						</span>
						{if !empty($oneStock.home_direction_id)}
						<span class="fw-bold text-main">{$clsProperty->getTitleQR($oneStock.home_direction_id)}</span>
						{else}
						<span class="text-muted">N/A</span>
						{/if}
					</div>
					<div class="d-flex mb-1 align-items-center gap-2">
						<span class="d-flex align-items-center gap-12 text-muted">
							<i class="d-inline-block re__icon-radio-checked--sm"></i> Tình trạng
						</span>
						<span class="fw-bold text-main">{$clsSetting->getTitle($oneStock.status_id)}</span>
					</div>
					<hr class="my-3" />
					<h4 class="mb-2 text-fs-16">
						<span class="d-flex align-items-center gap-2">
							<i class='bx bx-user'></i> Sales chăm sóc
						</span>
					</h4>
					{if !empty($oneStock.sale_care_id)}
						<div class="d-flex align-items-center justify-content-between">
							{$clsProfile->getIndentityV4($oneStock.sale_care_id)}
							<a onClick="$Core.sop.open_care(this, event)" class="text-link">Thay đổi</a>
						</div>
					{else}
						<span class="text-muted">Chưa có Sales chăm sóc</span>
					{/if}
					<hr class="my-3" />
					<h4 class="mb-2 text-fs-16"><i class='bx bx-user'></i> Liên hệ chủ nhà</h4>
					<p class="mb-2">Họ và tên: 
						<strong>{$more_information.contact_name}</strong>
					</p>
					<div class="d-flex align-items-center gap-2">
						<a href="tel:{$more_information.contact_phone}" type="phone" sop_id="{$sop_id}" 
						class="btn flex-fill btn-outline-default qqfSRjPFMr px-2">
						<i class="material-icons-outlined">call</i> 
						{$more_information.contact_phone}</a>
						<a href="https://zalo.me/{$more_information.contact_phone}" type="zalo" target="_blank" sop_id="{$sop_id}" 
							class="btn flex-fill btn-outline-default qqfSRjPFMr text-nowrap over px-2">
							<img src="{$URL_IMAGES}/zalo_chat.png" width="20px" /> 
							{$more_information.contact_phone}
						</a>
					</div>
					<hr class="my-3" />
					<h4 class="mb-2 text-fs-16">
						<span class="d-flex align-items-center gap-2">
							<i class='bx bx-user'></i> Nhắc nhở
						</span>
					</h4>
					<div class="holder_reminders-{$telesale_id}"></div>
				</div>
			</div>
		</div>
	</div>
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
	.owl-dot{width:12px;height:12px;border-radius:100%;background:#ddd;margin:3px}
	.owl-dot.active{background:#ffeb3b}
</style>
{/literal}