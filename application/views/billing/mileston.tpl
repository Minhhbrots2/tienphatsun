<div class="container-xxl flex-grow-1 pt-2{if $deviceType eq 'phone'} px-0{/if} overflow-hidden">
	<div class="form-row">
		<div class="col-12 col-xxl-10 mx-auto pt-3 bg_mileston">
			<div class="row mx-auto" style="max-width:1200px">
				<div class="header_page text-center mb-3 text-dark">
					<h1 class="text-center title_page text-main fw-bold mb-2">Milestone {$year}</h1>
					<p class="text-upper txt_title fw-semibold">Dấu ấn vinh quang</p>
					<div class="mb-2 content_header lh-base">Tổng hợp hành trình chiến thắng của các chiến binh <br> {$smarty.const.BRAND_NAME} trong năm {$year}</div>
					<div class="box_border_items mb-3">
						<div class="box_items d-flex justify-content-center fs-14">
							<div class="item_billing d-flex flex-column align-items-center gap-1 flex-flow">
								<img src="{$URL_IMAGES}/icons/icon_cup.png" alt="" class="icon_item" width="40" height="40">
								<span class="text-black fs-6">Giao dịch</span>
								<span class="total_record text-main fs-3 lh-1 fw-bold">0</span>
							</div>
							<div class="item_billing d-flex flex-column align-items-center gap-1 flex-flow">
								<img src="{$URL_IMAGES}/icons/icon_user.png" alt="" class="icon_item" width="40" height="40">
								<span class="text-black fs-6">Chiến binh</span>
								<span class="total_sale text-main fs-3 lh-1 fw-bold">0</span>
							</div>
							<div class="item_billing d-flex flex-column align-items-center gap-1 flex-flow">
								<img src="{$URL_IMAGES}/icons/icon_money.png" alt="" class="icon_item" width="40" height="40">
								<span class="text-black fs-6">Doanh số</span>
								<span class="total_bill text-main fs-3 lh-1 fw-bold">0</span>
							</div>
						</div>
					</div>
					<div class="d-flex gap-2 align-items-center justify-content-center content_header">Timeline giao dịch bên dưới <img src="{$URL_IMAGES}/icons/icon_arrow.png" width="20" class="h-auto"></div>
				</div>
				<ul class="timeline timeline-center mt-12 holder_mileston px-2">
					<div class="item_mileston item_big text-dark mb-3 animate-bg"></div>
					<li class="timeline-item timeline_item_first no-border">
						<div class="timeline-event card p-0 aos-init aos-animate" data-aos="fade-right">
							<div class="item_mileston item_small text-dark h-100 animate-bg"></div>	
						</div>
					</li>
						<div class="timeline-event card p-0 aos-init aos-animate" data-aos="fade-left">
							<div class="item_mileston item_small text-dark h-100 animate-bg"></div>	
						</div>
					</li>
				</ul>
			</div>				
			<div class="d-flex justify-content-between pt-2 text-center" id="showmorethisresult">
				<button type="button" class="showmorethisresult" onClick="$Core.mileston.load_more(this, event)" page="2"> 
					<span>Xem thêm</span> 
					<img src="{$URL_IMAGES}/loading_48.gif" width="24px"> 
				</button> 
			</div>
		</div>
	</div>
</div>
<script> var year=`{$year}`; </script>