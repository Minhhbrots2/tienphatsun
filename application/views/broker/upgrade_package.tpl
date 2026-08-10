<div class="container-xxl flex-grow-1 my-5">
	<h2 class="text-center mb-2 fs-2">Nâng cấp gói tài khoản</h2>
	<p class="text-center mb-3 pb-2">All plans include 40+ advanced tools and features to boost your product.<br> Choose the best plan to fit your needs.</p>
	<!--<label class="switch switch-sm ms-sm-12 ps-sm-12 me-0">
			<span class="switch-label">Hàng tháng</span>
			<input type="checkbox" onchange="$Core.broker.handle_price_duration(this, event)" class="switch-input price-duration-toggler">
			<span class="switch-toggle-slider">
				<span class="switch-on"></span>
				<span class="switch-off"></span>
			</span>
			<span class="switch-label">Hàng năm</span>
		</label>-->
	<div class="d-flex align-items-center justify-content-center flex-wrap gap-2 pb-4 pt-3 mb-0 mb-md-4">
		<div class="btn_switch btn-group" role="group" aria-label="Basic radio toggle button group">
		  	<input type="radio" class="btn-check" name="time_package" id="btnradio1" autocomplete="off" checked onchange="$Core.broker.handle_price_duration(this, event)" value="price_month">
		  	<label class="btn btn-outline-primary" for="btnradio1">1 tháng</label>

		 	<input type="radio" class="btn-check" name="time_package" id="btnradio2" autocomplete="off" onchange="$Core.broker.handle_price_duration(this, event)" value="price_3month">
		  	<label class="btn btn-outline-primary" for="btnradio2">3 tháng</label>

		  	<input type="radio" class="btn-check" name="time_package" id="btnradio3" autocomplete="off" onchange="$Core.broker.handle_price_duration(this, event)" value="price_year">
		  	<label class="btn btn-outline-primary" for="btnradio3">12 tháng</label>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-lg-10 offset-lg-1">
			<div class="form-row flex-wrap mx-0 gy-3 px-lg-5">
				{foreach from=$lstPackage item=item}
				<div class="col-12 col-lg-4">
					<div id="package_{$item.property_id}" class="card border rounded{if $smarty.const._MEMBER_PARKAGE_VIP_ID eq $item.property_id} border-primary{/if} shadow-none">
						<div class="card-body pb-5">
							<div class="my-3 pt-2 text-center">
								<img src="{$item.image}" alt="{$item.title}" height="80">
							</div>
							<h3 class="card-title text-center text-capitalize mb-1 fs-3">{$item.title}</h3>
							<p class="text-center">{$item.package_intro|html_entity_decode}</p>
							<div class="text-center">
								{if empty($item.price_month)}
								<div class="d-flex justify-content-center">
									<h1 class="price-toggle display-4 mb-0 text-primary" price_month="0" price_3month="0" price_year="0">0</h1>
									<sub class="h6 pricing-duration mt-auto mb-2 text-muted fw-normal">/tháng</sub>
								</div>
								{else}
								<div class="d-flex justify-content-center">
									<h1 class="price-toggle display-4 text-primary mb-0" price_month="{$clsISO->shortNumber($item.price_month)}" price_3month="{$clsISO->shortNumber($item.price_3month)}" price_year="{$clsISO->shortNumber($item.price_year)}">
										{$clsISO->shortNumber($item.price_month)}
									</h1>
									<sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/tháng</sub>
								</div>
								{/if}  
							</div>
							<div class="package_intro">
								{$item.intro|html_entity_decode}
							</div>
							<div class="d-flex align-items-center justify-content-center">
								{if $curent_pagekage eq $item.property_id}
								<a class="btn disabled btn-lg btn-outline-default rounded-pill w-50">Đang sử dụng</a> 
								{else}
									{if $item.property_id eq $smarty.const._MEMBER_PARKAGE_FREE_ID}
									<a href="javascript:void(0)" data-package_id="{$item.property_id}" class="btn btn-lg rounded-pill btn-warning w-50{if $clsProfile->check_member_vip($oneProfile) eq '1'} disabled preventDefault{/if}">Sử dụng</a>
									{else}
									<a href="javascript:void(0)" onClick="$Core.broker.upgrade_package(this,event)" data-package_id="{$item.property_id}" class="btn btn-lg rounded-pill {if $item.property_id eq $smarty.const._MEMBER_PARKAGE_VVIP_ID}btn-primary{else}btn-warning{/if}  w-50 btn_upgrade">
										{if $curent_pagekage eq $smarty.const._MEMBER_PARKAGE_VVIP_ID}
											Sử dụng
										{else}
											Nâng cấp
										{/if}
									</a>
									{/if}
								{/if}
							</div>
						</div>
					</div>
				</div>
				{/foreach}
			</div>
			<div class="w-50 border-bottom my-5 mx-auto"></div>
			{if !empty($list_FAQs)}
			<div class="faqs_wrapper">
				<div class="row flex-wrap mx-0 gy-3 px-lg-5">
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
