<div class="modal fade show modal-today" id="modalToday" tabindex="-1" aria-modal="true" role="dialog"> 
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title fs-{if $deviceType eq 'phone'}5{else}4{/if} text-main">{$oneToday.title}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="rounded-2 mb-3 overflow-hidden"> 
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2 mb-0">
						<div class=" text-center flex-fill">
							<p class="mb-0 text-muted lh-base">
								<i class="d-inline-block re__icon-house--sm"></i> Mã căn
							</p>
							<div class="fw-bold">
								<a href="javascript:void(0);" class="text-main" onClick="$Core.helper.open_stock({$oneToday.stock_id})">{$oneToday.stock_code} <i class="bx bx-link-external"></i></a>
							</div>
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="re__icon-ying-yang--xl"></i> Ban công</p>
							<div class="fw-bold text-main">{$oneToday.home_direction_name}</div>
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ</p>
							<div class="fw-bold text-main">{$oneToday.DT_TT}m<sup>2</sup></div>
						</div>
						<div class=" text-center flex-fill d-none d-lg-block">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-money--sm"></i> Giá/m2</p>
							<div class="fw-bold text-main">{$oneToday.price_m2}tr</div>
						</div>
					</div>
					{if !empty($list_images)}
					<div class="box_image"{if $total_images gt '1'} id="slider-images"{/if} style="max-height:400px">
						{foreach from=$list_images name=i item=img}
						<div class="slideshow-item w-100 h-100 position-relative overflow-hidden" style="max-height:400px">
							<img class="sop_slider_overlay position-absolute zindex-1" class="owl-lazy" data-src="{$img}" src="{$URL_IMAGES}/no-image.jpg" />
							<div class="sop_slider_image d-flex justify-content-center position-relative zindex-2">
								<img {if $total_images gt '1'}class="owl-lazy" data-src="{$img}" src="{$URL_IMAGES}/no-image.jpg"{else} src="{$img}" class="w-100"{/if} alt="{$oneToday.title}" height="400"/>
							</div>
							<span class="sop_slider-pagination d-inline-block position-absolute">
								{$smarty.foreach.i.iteration}/{$list_images|@count}
							</span>
						</div>
						{/foreach}
					</div>
					{/if}
				</div>
				<div class="content tinyContent my-2 content-copy">
					<div class="d-flex justify-content-between">						
						<div class="content tinyContent text-black">
							{$oneToday.content|nl2br}
						</div>
						<a href="javascript:void(0)" onclick="$Core.today.copyContentToClipboard(this, event)" 
							data-bs-toggle="tooltip" data-bs-trigger="click" class="sop_copy text-dark rounded-pill fs-6" 
							title="Đã sao chép nội dung mô tả"><i class="bx bx-copy"></i>
						</a>
					</div>
				</div>
				<button type="button" class="btn btn-block btn-lg btn-warning" data-bs-dismiss="modal">Đóng cửa sổ</button> 
			</div>
		</div>
	</div>
</div>