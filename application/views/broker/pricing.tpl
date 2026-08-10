<div class="container-xxl flex-grow-1 my-5">
	<div class="row">
		<div class="col-12 col-md-12 col-xxl-10 offset-xxl-1">
			<div class="banner relative overlay overflow-hidden mb-4 d-flex align-items-center justify-content-center" style="background-image: url('{$URL_IMAGES}/backgrounds/ocean-city.jpg')">
				<div class="banner-content zindex-2 text-white">
					<h2 class="text-center text-white mb-2 fs-2">Lựa chọn gói tài khoản</h2>
					<p class="text-center mb-3">Nâng cấp gói nền tảng của bạn để tận hưởng trải nghiệm không giới hạn với các tính năng độc quyền.<br> Tối ưu hóa hiệu quả công việc của bạn ngay hôm nay với MyOceanCIty!</p>
					<div class="d-flex align-items-center justify-content-center flex-wrap py-2">
						{assign var = uid value = $clsISO->getUniqid()}
						<div class="p-2 rounded-pill bg-white">
							<div class="btn-switch btn-group" role="group" aria-label="">
								<input type="radio" class="btn-check" name="time_package" id="time_package_1_{$uid}" autocomplete="off" checked onchange="$Core.broker.handle_price_duration(this, event)" value="price_month">
								<label class="btn" for="time_package_1_{$uid}">1 tháng</label>
								<input type="radio" class="btn-check" name="time_package" id="time_package_2_{$uid}" autocomplete="off" onchange="$Core.broker.handle_price_duration(this, event)" value="price_6month">
								<label class="btn" for="time_package_2_{$uid}">6 tháng</label>
								<input type="radio" class="btn-check" name="time_package" id="time_package_3_{$uid}" autocomplete="off" onchange="$Core.broker.handle_price_duration(this, event)" value="price_year">
								<label class="btn" for="time_package_3_{$uid}">12 tháng</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row gy-3 align-items-center mt-3">
				{foreach from=$lstPackage item=item name=i}
				<div class="col-12 col-lg-4">
					<div id="package_{$item.property_id}" class="card border rounded{if $smarty.const._MEMBER_PARKAGE_PRO_ID eq $item.property_id} border-primary{/if} shadow-none">
						<div class="card-body pb-5">
							<div class="my-3 pt-2 text-center">
								<img src="{$item.image}" alt="{$item.title}" height="80">
							</div>
							<h3 class="card-title text-center text-capitalize mb-1 fs-3">{$item.title}</h3>
							<p class="text-center">{$item.package_intro|html_entity_decode}</p>
							<div class="text-center">
								{if empty($item.price_month)}
								<div class="d-flex justify-content-center">
									<h1 class="price-toggle display-4 mb-0 text-primary fs-2" price_month="0" price_6month="0" price_year="0">Miễn phí</h1>
									<sub class="h6 pricing-duration mt-auto mb-2 text-muted fw-normal"></sub>
								</div>
								{else}
								<div class="d-flex justify-content-center">
									<h1 class="price-toggle display-4 text-primary mb-0 fs-2" price_month="{$clsISO->priceFormat($item.price_month)}{$clsISO->getRate()}" price_6month="{$clsISO->priceFormat($item.price_6month)}{$clsISO->getRate()}" price_year="{$clsISO->priceFormat($item.price_year)}{$clsISO->getRate()}">
										{$clsISO->priceFormat($item.price_month)}{$clsISO->getRate()}<sub class="h6 text-body pricing-duration mt-auto mb-1">/1 tháng</sub>
									</h1>
									<sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal"></sub>
								</div>
								{/if}  
							</div>
							<div class="package_intro">
								{$item.intro|html_entity_decode}
								{if $smarty.const._MEMBER_PARKAGE_PRO_ID eq $item.property_id 
									&& $current_package eq $smarty.const._MEMBER_PARKAGE_PRO_ID}
								<div class="alert alert-warning text-center">
									{if $is_tried eq '1'}
										Dùng thử đến hết ngày {$clsISO->formatDate($due_date_package,5)}
									{else}
										Sử dụng đến hết ngày {$clsISO->formatDate($due_date_package,5)}
									{/if}
								</div>
								{/if}
							</div>
							<div class="d-flex align-items-center gap-1 justify-content-center flex-wrap">
							{if $loggedIn eq '1'}
								{if $current_package eq $item.property_id}
									{if $check_extension eq 1 && $item.property_id ne $smarty.const._MEMBER_PARKAGE_FREE_ID}
										<a href="javascript:void(0)" onClick="$Core.broker.upgrade_package(this,event)" data-package_id="{$item.property_id}" class="btn btn-lg rounded-pill {if $item.property_id eq $smarty.const._MEMBER_PARKAGE_VIP_ID}btn-primary{else}btn-warning{/if}  w-50 btn_upgrade" time_package="price_month">Gia hạn ngay</a>
									{else}
										{if $smarty.const._MEMBER_PARKAGE_PRO_ID eq $item.property_id && $is_tried eq '1'}
											<a href="javascript:void(0)" onClick="$Core.broker.upgrade_package(this,event)" data-package_id="{$item.property_id}" class="btn btn-lg rounded-pill w-50{if $item.property_id eq $smarty.const._MEMBER_PARKAGE_VIP_ID} btn-primary{else} btn-warning{/if} w-50 btn_upgrade" time_package="price_month">Nâng cấp</a>
										{else}
											<a class="btn disabled btn-lg btn-outline-default rounded-pill w-50">Đang sử dụng</a> 
										{/if}
									{/if}
								{else}
									{if $item.property_id eq $smarty.const._MEMBER_PARKAGE_VIP_ID 
										&& $current_package ne $smarty.const._MEMBER_PARKAGE_VIP_ID}
										<a href="javascript:void(0)" data-package_id="{$item.property_id}" onClick="$Core.broker.help_upgrade_package(this,event)" class="btn btn-lg rounded-pill {if $item.property_id eq $smarty.const._MEMBER_PARKAGE_VIP_ID}btn-primary{else}btn-warning{/if}  w-50 btn_upgrade" time_package="price_month">Liên hệ</a>
									{else}
										{if $smarty.const._MEMBER_PARKAGE_PRO_ID eq $item.property_id 
										&& $current_package eq $smarty.const._MEMBER_PARKAGE_FREE_ID && $item.day_trial gt '0' && $is_tried eq '0'}
										<a href="javascript:void(0)" onClick="$Core.broker.trial_package(this,event)" data-package_name="{$item.title}" data-day_trial="{$item.day_trial}" data-package_id="{$item.property_id}" class="btn btn-lg text-nowrap rounded-pill btn-success btn_upgrade" time_package="price_month">Dùng thử {$item.day_trial} ngày</a>
										{/if}
										{if $item.property_id eq $smarty.const._MEMBER_PARKAGE_FREE_ID}
											<a href="javascript:void(0)" class="btn btn-lg disabled rounded-pill {if $item.property_id eq $smarty.const._MEMBER_PARKAGE_VIP_ID}btn-primary{else}btn-warning{/if} btn_upgrade w-50" >Sử dụng</a>
										{else}
											<a href="javascript:void(0)" onClick="$Core.broker.upgrade_package(this,event)" data-package_id="{$item.property_id}" class="btn btn-lg rounded-pill w-50 {if $item.property_id eq $smarty.const._MEMBER_PARKAGE_VIP_ID}btn-primary{else}btn-warning{/if} btn_upgrade" time_package="price_month">Nâng cấp</a>
										{/if}
									{/if}
								{/if}
							{else}
								<a href="{$clsISO->getLink('login')}" class="btn btn-lg rounded-pill btn-warning w-50">Đăng ký</a>
							{/if}
							</div>
						</div>
					</div>
				</div>
				{/foreach}
			</div>
			<div class="py-4 d-flex justify-content-center">
				<a href="{$smarty.const.SUPPORT_ZALO}" class="d-flex justify-content-center fs-16">
					<div class="mt-4 d-flex bg-white rounded-pill overflow-hidden py-3 px-4 align-items-center ">
						<img src="{$URL_IMAGES}/support-online.png" class="w-px-50">
						<div class="pl-3">
							<h3 class="text-main text-upper fw-bold fs-6 mb-1">Bạn cần trợ giúp</h3>
							<p class="text-muted mb-0">Liên hệ ngay với chúng tôi</p>
						</div>
					</div>
				</a>
			</div>
			<div class="w-50 border-bottom my-5 mx-auto"></div>
			{if !empty($list_FAQs)}
			<div class="faqs_wrapper">
				<div class="row gy-3">
					<div class="col-12 col-md-5">
						<div class="d-flex align-items-center justify-content-center">
							<img src="{$URL_IMAGES}/FAQs.png" class="img-fluid w-80" />
						</div>
					</div>
					<div class="col-12 col-md-7">
						<div class="accordion" id="faqs">	
							{foreach from=$list_FAQs name=i item = _oFAQ}
							{assign var = gId value = 'faq_'|cat:$clsISO->getUniqid()}
							<div class="accordion-item mb-2">
								<h2 class="accordion-header rounded-2 overflow-hidden">
									<div type="button" class="accordion-button fs-5 bg-white{if !$smarty.foreach.i.first} collapsed{/if}" data-bs-toggle="collapse" data-bs-target="#{$gId}" aria-expanded="true" aria-controls="{$gId}">{$_oFAQ.title}</div>
								</h2>
								<div id="{$gId}" class="accordion-collapse collapse{if $smarty.foreach.i.first} show{/if}" data-bs-parent="#faqs">
									<div class="accordion-body">
										<div class="tinyContent">{$_oFAQ.content}</div>
									</div>
								</div>
							</div>
							{/foreach}
						</div>
					</div>
				</div>
			</div>
			{/if}
		</div>
	</div>
</div>

<div class="modal fade" id="trialSuccess" tabindex="-1" aria-modal="true" role="dialog">
	<div class="modal-dialog modal-xs {if $deviceType eq 'phone'} {/if} modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="d-flex py-3 flex-column justify-content-center">
					<div class="awe__reviews-success text-center"> 
						<img class="mb-3 w-px-300" src="https://ilooca.com/isocms/templates/default/skin/images/thankyou.svg">
						<h3 class="fs-5 mb-3 title_package"></h3>
						<div class="button-wrap"> 
							<button type="button" class="btn close_pop btn-outline-default" data-bs-dismiss="modal">Đóng lại</a> 
						</div> 
					</div> 
				</div> 
			</div>
		</div>
	</div>
</div>