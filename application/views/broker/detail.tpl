<link rel="stylesheet" href="{$URL_CSS}/swiper.bundle.min.css?v={$upd_verson}">
<div class="container-xxl flex-grow-1 container-p-y page_detail_broker detail_broker pt-2">
	<div class="row">
		<div class="col-xxl-10 mx-auto">
			<section class="section_banner relative">
				<a href="{$clsISO->getLink('broker')}" class="btn_back"><i class='bx bxs-share'></i></a>
				{if $deviceType eq 'phone'}
					<img class="w-100 rounded-top" src="{$more_information.banner}" onerror="this.src='{$URL_IMAGES}/banner_default.jpg'" alt="Banner" height="200"> 
				{else}
					<img class="w-100 rounded-top" src="{$more_information.banner}" onerror="this.src='{$URL_IMAGES}/banner_default.jpg'" alt="Banner" height="400"> 
				{/if}
			</section>
			<div class="box_content">
				<div class="box_content_top rounded-bottom d-flex flex-wrap justify-content-between p-3 pb-4 mb-3">
					<div class="box_left_head d-flex flex-wrap relative">
						<div class="box_image">
							<img class="rounded-circle" src="{$FH_URL}/{$oneItem.avatar}" height="150" width="150" alt="User avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'" style="object-fit: cover">
							<span class="level"><svg class="" width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_136:8783)">
								<path opacity="0.4" d="M13.2148 25.9395C20.1184 25.9395 25.7148 20.343 25.7148 13.4395C25.7148 6.53589 20.1184 0.939453 13.2148 0.939453C6.31128 0.939453 0.714844 6.53589 0.714844 13.4395C0.714844 20.343 6.31128 25.9395 13.2148 25.9395Z" fill="#16C784"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M13.2135 3.02271C7.46979 3.02271 2.79688 7.69562 2.79688 13.4394C2.79688 19.1831 7.46979 23.856 13.2135 23.856C18.9573 23.856 23.6302 19.1831 23.6302 13.4394C23.6302 7.69562 18.9573 3.02271 13.2135 3.02271Z" fill="#16C784"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M12.9414 6.5498C13.1174 6.47689 13.3133 6.47689 13.4893 6.5498L18.3508 8.63314C18.606 8.74251 18.7716 8.9946 18.7716 9.27168V12.0498C18.7716 15.8758 17.3591 18.1092 13.5612 20.2915C13.4549 20.3529 13.3352 20.3831 13.2154 20.3831C13.0966 20.3831 12.9768 20.3519 12.8695 20.2915C9.07266 18.104 7.66016 15.8706 7.66016 12.0498V9.27168C7.66016 8.9946 7.82578 8.74251 8.07995 8.63314L12.9414 6.5498ZM14.757 10.9206L12.5737 13.6508L11.7102 12.3592C11.4945 12.0404 11.0633 11.955 10.7477 12.1665C10.4299 12.379 10.3414 12.8113 10.5549 13.129L11.9435 15.2123C12.0674 15.3967 12.2695 15.5113 12.4924 15.5217H12.5216C12.731 15.5217 12.931 15.4279 13.0643 15.2613L15.8424 11.7883C16.081 11.4883 16.0341 11.0529 15.7341 10.8123C15.4352 10.5758 14.9987 10.6217 14.757 10.9206V10.9206Z" fill="white"/>
								</g>
								<defs>
								<clipPath id="clip0_136:8783">
								<rect width="25" height="25" fill="white" transform="translate(0.714844 0.939453)"/>
								</clipPath>
								</defs>
							</svg></span> 
						</div>
						<div class="info-right px-3">
							<div class="box_name_user">
								<h1 class="fs-4 mb-1 fw-bold name_user">{$oneItem.full_name}</h4>
								<span>{$clsProperty->getTitle($oneItem.role_id)}</span>
								{if $oneItem.profile_id ne $profile_id}
									<div>	
										<button class="btn btn-outline-default follow_profile follow_detail mt-1 {if $clsISO->checkItemInArray($oneItem.profile_id,$array_follow)}followed{/if}" type="button" onClick="$Core.broker.follow_broker(this,{$oneItem.profile_id})" title="{if $clsISO->checkItemInArray($oneItem.profile_id,$array_follow)}Bỏ theo dõi{else}Theo dõi{/if}" trigger="hover" data-bs-toggle="tooltip">
											<i class='bx bx-user-check mr-1'></i><span>{if $clsISO->checkItemInArray($oneItem.profile_id,$array_follow)}Bỏ theo dõi{else}Theo dõi{/if}</span>
										</button>
									</div>
								{/if}
							</div>	
						</div>
					</div>
					<div class="box_right_head d-flex flex-wrap justify-content-end">
						<div class="d-flex flex-wrap">
							<div class="me-2"><a href="mailto:{$oneItem.phone}" class="btn btn-outline-primary"><i class="bx bx-phone fs-5 me-2 align-text-bottom"></i>{$clsClassTable->mask($oneItem.phone,1)}</a></div>
							<div class="me-2"><a href="mailto:{$oneItem.email}" class="btn btn-outline-primary"><i class="bx bx-envelope fs-5 me-2 align-text-bottom"></i>Email</a></div>
							<div class="btn-group">
								<button class="btn btn-primary btn-icon dropdown-toggle hide-arrow" type="button" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true"><i class="bx bx-dots-vertical-rounded"></i></button>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuClickableInside">
									{if $oneItem.profile_id eq $profile_id}
										<li><a class="dropdown-item" href="/MOC/profile/me/"><i class="bx bx-user mr-1"></i>Quản lý tài khoản</a></li>
									{/if}
									<li><a class="dropdown-item" href="javascript:void(0)" onclick="$Core.broker.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="" data-bs-trigger="click" data-link="{$DOMAIN_URL}{$clsProfile->getLink($oneItem.profile_id,$oneItem)}"  data-bs-original-title="Đã sao chép link" aria-label="Đã sao chép link"><i class="bx bx-link mr-1"></i>Sao chép link</a></li>
								</ul>
							</div>
						</div>
					</div>	
				</div>
			</div>	
			<div class="row">
				<!-- Customer-detail Sidebar -->
				<div class="col-xxl-4 col-lg-5">
					<div class="sticky">
						<!-- Customer-detail Card -->
						<div class="card mb-2 no-shadow">
							<div class="card-body">
								<div class="info-container">
									<small class="text-muted text-uppercase">Thông tin</small>
									<ul class="list-unstyled mb-4 mt-3">
										{if !empty($oneItem.birthday)}
											<li class="d-flex align-items-center mb-2"><i class="bx bx-cake fs-5"></i><span class="fw-semibold mx-2">Ngày sinh:</span> <span class="fs-6">{$clsISO->formatDate($oneItem.birthday,5)}</span></li> 
										{/if}
										{if !empty($oneItem.address)}
											<li class="d-flex align-items-center mb-2"><i class="bx bx-flag fs-5"></i><span class="fw-semibold mx-2">Địa chỉ:</span> <span class="fs-6">{if $oneItem.address ne ""}{$oneItem.address}{else}[Chưa cập nhật]{/if}</span></li> 
										{/if}
										{if !empty($more_information.experience)}
											<li class="d-flex align-items-center mb-2"><i class="bx bx-briefcase fs-5"></i><span class="fw-semibold mx-2">Kinh nghiệm:</span> <span class="fs-6">{$more_information.experience}</span></li>
										{/if}
										{if !empty($more_information.agency)}
											<li class="d-flex align-items-center mb-2"><i class="bx bx-package fs-5"></i><span class="fw-semibold mx-2">Đại lý:</span> <span class="fs-6">{$more_information.agency}</span></li>
										{/if}
										{if !empty($more_information.number_sale)}
											<li class="d-flex align-items-center mb-2"><i class="bx bx-building-house fs-5"></i><span class="fw-semibold mx-2">Giao dịch thành công:</span> <span class="fs-6">{$more_information.number_sale}</span></li>
										{/if}
									</ul>
									<small class="text-muted text-uppercase d-none">Liên hệ</small>
									<ul class="list-unstyled mb-4 mt-3 d-none">
										{if !empty($oneItem.phone)}
											<li class="d-flex align-items-center mb-2"><i class="bx bx-phone fs-5"></i><span class="fw-semibold mx-2">Điện thoại:</span> <a class="px-1 py-0 btn btn-outline-default rounded-pill" href="tel:{$oneItem.phone}" >{$clsClassTable->mask($oneItem.phone,1)}</a></li>
										{/if}
										{if !empty($oneItem.email)}
											<li class="d-flex align-items-center mb-2"><i class="bx bx-envelope fs-5"></i><span class="fw-semibold mx-2">Email:</span> <a href="mailto:{$oneItem.email}" class="px-1 py-0 btn btn-outline-default rounded-pill">{$clsClassTable->mask($oneItem.email,1)}</a></li>
										{/if}
									</ul>
									{if !empty($more_information.facebook) || !empty($more_information.twitter) || !empty($more_information.linkedin) || !empty($more_information.twitter) }
									<div class="share">
										<small class="text-muted text-uppercase">Follow us</small>
										<div class="social mt-3">
											{if !empty($more_information.facebook)}
												<a href="{$more_information.facebook}" class="fb">
													<i class="fa fa-brands fa-facebook fs-5"></i>
												</a>
											{/if}
											{if !empty($more_information.twitter)}
												<a href="{$more_information.twitter}" class="ins">
													<i class="bx bxl-instagram fs-5"></i>
												</a>
											{/if}
											{if !empty($more_information.linkedin)}
												<a href="{$more_information.linkedin}" class="tk">
													<i class='bx bxl-linkedin fs-5'></i>
												</a>
											{/if}
											{if !empty($more_information.twitter)}
												<a href="{$more_information.twitter}" class="tw">
													<i class="bx bxl-twitter fs-5"></i>
												</a>
											{/if}
										</div>
									</div>
									{/if}
								</div>
							</div>
						</div>
						{if $deviceType ne 'phone'}
							{if !empty($more_information.image)}
							<div class="card mb-2 no-shadow">
								<h5 class="card-header">Hình ảnh</h5>
								<div class="card-body">
									<div class="form-row row">
										{foreach from=$more_information.image item=image}
											{if $more_information.image|@count eq 1}
												<div class="item" data-fancybox="gallery" href="{$image}">
													<div class="rounded img_scale">
														<img class="drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;max-height:250px">
													</div>
												</div>
											{else}
												<div class="item col-4 mb-2" data-fancybox="gallery" href="{$image}">
													<div class="rounded img_scale">
														<img class="drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;height:94px">
													</div>
												</div>
											{/if}
										{/foreach}
									</div>
								</div>
							</div>
							{/if}
							{if $more_information.link_video ne ''}
							<div class="card no-shadow">
								<h5 class="card-header">Video</h5>
								<div class="card-body">
									<div class="box_body_video rounded">
										{$clsISO->getEmbedVideo($more_information.link_video)}
									</div>
								</div>
							</div>	
							{/if}
						{/if}
					</div>
				</div>
				<!--/ Customer Sidebar -->
				<!-- Customer Content -->
				<div class="col-xxl-8 col-lg-7">
					{if $deviceType ne 'phone'}
					<ul class="nav nav-pills flex-md-row mb-4">
						<li class="nav-item" role="presentation">
							<button class="btn nav-link active" id="pills-about-tab" data-bs-toggle="pill" data-bs-target="#pills-about" type="button" role="tab" aria-controls="pills-about" aria-selected="true"><i class='bx bx-info-circle mr-2' ></i>Giới thiệu</button>
						</li>
						<li class="nav-item d-none" role="presentation">
							<button class="btn nav-link" id="pills-review-tab" data-bs-toggle="pill" data-bs-target="#pills-review" type="button" role="tab" aria-controls="pills-review" aria-selected="false"><i class='bx bx-chat mr-2'></i>Nhận xét của khách hàng</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="btn nav-link" id="pills-history-sale-tab" data-bs-toggle="pill" data-bs-target="#pills-history-sale" type="button" role="tab" aria-controls="pills-history-sale" aria-selected="false"><i class='bx bx-cart-alt mr-2' ></i>Lịch sử bán hàng</button>
						</li>
					</ul>
					{/if}
					<div class="box_content_broker mb-4">
						<div class="tab-pane fade show active" id="pills-about" role="tabpanel" aria-labelledby="pills-about-tab">
							{if $more_information.about} 
							<div class="card mb-4 no-shadow">
								{if $deviceType eq "phone"}
									<div class="card-header">
										<h5 class="card-title fw-bold mb-0 title_color">Giới thiệu</h5> 
									</div>	
								{/if}
								<div class="card-body">
									<div class="tinymce_content content_about fs-5">
										{$more_information.about|html_entity_decode} 
									</div>				
								</div>
							</div>	
							{/if}
							{if $more_information.achievements}
								<section class="section_achievements card mb-3 {if $deviceType eq 'phone'}px-3{else}px-5{/if} py-5 no-shadow" style="background: url('{$URL_IMAGES}/bg_achievements2.jpg') no-repeat;background-size: cover;background-attachment: fixed">
									<div class="card-header">
										<h5 class="card-title fs-3 text-white fw-bold">Thành tích cá nhân</h5> 
									</div>								
									<div class="card-body text-white list-type-cup fs-6">
										{$more_information.achievements|html_entity_decode}
									</div>
								</section>	
							{/if}
							{if $more_information.certificate}
								<section class="section_certificate card mb-3 no-shadow">
									<div class="card-header mb-2">
										<h5 class="card-title mb-0 fw-bold title_color {if $deviceType ne 'phone'}fs-4{/if}">Bằng cấp, chứng chỉ</h5> 
									</div>								
									<div class="card-body">
										{if $deviceType eq 'phone'}
											{if $more_information.certificate|@count gt 1}
												<div class="box_certificate owl-carousel" id="owl-certificate">
													{foreach from=$more_information.certificate item=item key=key}
														<div class="box_item_certificate relative">
															<div class="image_certificate img_scale" data-fancybox="gallery-certificate" src="{$item.image}" data-caption="{$item.title}"><img src="{$item.image}" alt="" width="200" height="300"></div>
															<h3 class="title_certificate mb-0 p-3 text-white lh-sm fs-6">{$item.title}</h3>
														</div>
													{/foreach}
												</div>
											{else}
												<div class="box_certificate">
													{foreach from=$more_information.certificate item=item key=key}
														<div class="box_item_certificate relative">
															<div class="image_certificate img_scale" data-fancybox="gallery-certificate" src="{$item.image}" data-caption="{$item.title}"><img src="{$item.image}" alt="" width="200" height="300"></div>
															<h3 class="title_certificate mb-0 p-3 text-white lh-sm fs-6">{$item.title}</h3>
														</div>
													{/foreach}
												</div>
											{/if}
										{else}
											<div class="box_certificate owl-carousel" id="owl-certificate">
												{foreach from=$more_information.certificate item=item key=key}
													<div class="box_item_certificate relative">
														<div class="image_certificate img_scale" data-fancybox="gallery-certificate" src="{$item.image}" data-caption="{$item.title}"><img src="{$item.image}" alt="" width="200" height="300"></div>
														<h3 class="title_certificate mb-0 p-3 text-white lh-sm fs-6">{$item.title}</h3>
													</div>
												{/foreach}
											</div>
										{/if}
									</div>
								</section>
							{/if}
							{if $more_information.dictum_live}
								<section class="section_dictum_live text-white card mb-3 no-shadow">
									<div class="card-body">									
										<div class="box_dictum_live relative mt-2 px-3">
											<div class="absolute_dictum_live">
												<i class='bx bxs-quote-alt-left fs-1'></i>
												<span class="card-title pl-4 d-none">Châm ngôn sống</span>
											</div>
											<div class="content_dictum_live fs-3 pl-4 mt-n1">{$more_information.dictum_live|html_entity_decode}</div>
										</div>
									</div>
								</section>
							{/if}
							{if $more_information.project}
								<section class="section_certificate card mb-3 no-shadow">
									<div class="card-header mb-2">
										<h5 class="card-title mb-0 fw-bold title_color {if $deviceType ne 'phone'}fs-4{/if}">Dự án đã tham gia</h5> 
									</div>								
									<div class="card-body">
										{if $deviceType eq 'phone'}
											{if $more_information.project|@count gt 1}
												<div class="box_certificate owl-carousel" id="owl-da">
													{foreach from=$more_information.project item=item key=key}
														<div class="box_item_certificate item_da relative">
															<div class="image_certificate img_scale" data-fancybox="gallery-project" src="{$item.image}" data-caption="{$item.title}"><img src="{$item.image}" alt="" width="200" height="300"></div>
															<div class="title_certificate text-center px-3 py-2">
																<h3 class="text-upper mb-1 lh-sm fs-6 text-white">{$item.title}</h3>
																<p class="text-upper mb-0 lh-sm text-white fs-tiny">Giao dịch: {$item.total_sale_project}</p>
															</div>
														</div>
													{/foreach}
												</div>
											{else}
												<div class="box_certificate">
													{foreach from=$more_information.project item=item key=key}
														<div class="box_item_certificate item_da relative">
															<div class="image_certificate img_scale" data-fancybox="gallery-project" src="{$item.image}" data-caption="{$item.title}"><img class="w-100" src="{$item.image}" alt="" width="200" height="300"></div>
															<div class="title_certificate text-center px-3 py-2">
																<h3 class="text-upper mb-1 lh-sm fs-6 text-white">{$item.title}</h3>
																<p class="text-upper mb-0 lh-sm text-white fs-tiny">Giao dịch: {$item.total_sale_project}</p>
															</div>
														</div>
													{/foreach}
												</div>
											{/if}
										{else}
											<div class="box_certificate owl-carousel" id="owl-da">
												{foreach from=$more_information.project item=item key=key}
													<div class="box_item_certificate item_da relative">
														<div class="image_certificate img_scale" data-fancybox="gallery-project" src="{$item.image}" data-caption="{$item.title}"><img src="{$item.image}" alt="" width="200" height="300"></div>
														<div class="title_certificate text-center px-3 py-2">
															<h3 class="text-upper mb-1 lh-sm fs-6 text-white">{$item.title}</h3>
															<p class="text-upper mb-0 lh-sm text-white fs-tiny">Giao dịch: {$item.total_sale_project}</p>
														</div>
													</div>
												{/foreach}
											</div>
										{/if}
									</div>
								</section>
							{/if}
							{if !empty($more_information.customer_review)}
								<section class="section_customer_review card mb-3 no-shadow"  style="background:url('/application/themes/images/bg_achievements.jpg') no-repeat;background-position: top;">
									<div class="card-header d-flex justify-content-between align-items-center">
										<h5 class="card-title mb-0 text-white fw-bold {if $deviceType ne 'phone'}fs-4{/if}">Nhận xét của khách hàng</h5> 
										<div class="nav_swiper text-white">
											<div class="swiper-prev"><i class='bx bx-chevron-left'></i></div>
											<div class="swiper-next"><i class='bx bx-chevron-right'></i></div>
										</div>	
									</div>								
									<div class="card-body">
										<div class="swiper box_customer_review py-3" id="swiper-review">
											<div class="swiper-wrapper">
												{foreach from=$more_information.customer_review item=item key=key}
													<div class="swiper-slide tes_item d-flex flex-column rounded p-3 ">
														<div class="box_customer d-flex justify-content-center mb-2">
															<div class="avatar-lg">
																<img width="60" height="60" src="{$item.image}" alt="{$item.title}" class="img_customer avatar-lg w-100 rounded-pill img100" style="object-fit:cover">
															</div>
														</div>														
														<div class="box_head_body mb-1">
															<p class="fw-semibold mb-1 fs-6 text-center text-black">{$item.title}</p>
															<div class="d-flex flex-wrap align-items-center justify-content-center">
																<p class="p_country mb-0 mr-2">
																{section name=i loop=5 start=0 step=1}
																	<i class="bx bxs-star {if $item.star gt $smarty.section.i.index}text-warning{/if}"></i>
																{/section}
															</p>			

															</div>
														</div>
														<div class="tes_bodypanel-default">

															<div class="item__intro mb-1">
																{$item.content|html_entity_decode}
															</div>
														</div>
													</div>
												{/foreach}
											</div>

										</div>
									</div>
								</section>
							{/if}
						</div>	
						<div class="tab-pane fade" id="pills-review" role="tabpanel" aria-labelledby="pills-review-tab" >
							<div class="card mb-4">
								<h5 class="card-header">Nhận xét của khách hàng</h5>
								<div class="card-body">
									<ul class="timeline timeline-center">
										{foreach from=$more_information.customer_review item=item key=key}
											<li class="timeline-item">
												<span class="timeline-indicator timeline-indicator-primary aos-init aos-animate" data-aos="zoom-in" data-aos-delay="200">
												<i class="bx bx-paint"></i>
												</span>
												<div class="timeline-event card p-0 aos-init aos-animate" data-aos="{if $smarty.section.i.index % 2 eq 0}fade-right{else}fade-left{/if}">
													<div class="card-body">
														<div class="d-flex flex-sm-row flex-column">
															<img src="{$item.image}" class="rounded me-3 mb-sm-0 mb-2" alt="doughnut" height="64" width="64" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
															<div>
																<h6 class="mb-2">{$item.title}</h6>
																<p class="mb-2">
																	{$item.content}
																</p>
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		{section name=i loop=5 start=0 step=1}
																		<i class="bx bxs-star {if $item.star gt $smarty.section.i.index}text-warning{/if}"></i>
																		{/section}
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="timeline-event-time">{$item.date}</div>
												</div>
											</li>
										{/foreach}
									</ul>
								</div>
							</div>
						</div>
						{if $deviceType eq 'phone'}
							{if !empty($more_information.image)}
								<div class="card mb-4">
									<h5 class="card-header">Hình ảnh</h5>
									<div class="card-body">
										<div class="form-row row">
											{foreach from=$more_information.image item=image}
												{if $more_information.image|@count eq 1}
													<div class="item" data-fancybox="gallery" href="{$image}">
														<div class="rounded img_scale">
															<img class="drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;max-height:250px">
														</div>
													</div>
												{else}
													<div class="item col-6 col-md-4 mb-2 img_scale" data-fancybox="gallery" href="{$image}">
														<img class="rounded drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;height:100px">
													</div>
												{/if}
												
											{/foreach}
										</div>
									</div>
								</div>
							{/if}
							{if $more_information.link_video ne ''}
								<div class="card mb-4">
									<h5 class="card-header">Video</h5>
									<div class="card-body">
										<div class="box_body_video rounded">	
											{$clsISO->getEmbedVideo($more_information.link_video)}
										</div>
									</div>
								</div>
							{/if}
						{/if}
						<div class="history-sale tab-pane fade {if $deviceType eq 'phone'}show{/if}" id="pills-history-sale" role="tabpanel" aria-labelledby="pills-history-sale-tab"> 
							<div class="card mb-4">
								<div class="mb-3">
									<div class="dataTables_wrapper dt-bootstrap5 no-footer">
										<div class="card-header d-flex flex-wrap py-3 py-sm-2">
											<div class="head-label text-center me-4 ms-1">
												<h5 class="card-title mb-0 text-nowrap">Lịch sử bán hàng ({$total_bill} GD)</h5>
											</div>
											<div id="DataTables_Table_0_filter" class="dataTables_filter my-0 {if $deviceType eq 'phone'}mt-2 w-100{/if}"><div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-search"></i></span>
												<input type="text" class="form-control search_field"  data-per_page="{$per_page}" onkeyup="$Core.broker.search({$oneItem.profile_id},this)" placeholder="Tìm kiếm...">
											</div></div>
											
										</div>
										<table class="table datatables-customer-order border-top dataTable no-footer dtr-column" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 917px;">
											<thead>
												<tr>
													{if $deviceType ne 'phone'}
														<th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 0px; display: none;" aria-label=""></th>
													{/if}
													<th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 90px;" aria-label="Mã giao dịch" aria-sort="descending">Mã căn</th>
													{if $deviceType ne 'phone'}
														<th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 148px;" aria-label="Ngày giao dịch">Dự án</th>
														<th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 192px;" aria-label="Trạng thái">Số tiền</th>
														<th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 97px;" aria-label="Tổng doanh thu">Tên khách hàng</th>
													{/if}
													<th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 148px;" aria-label="Ngày giao dịch">Ngày giao dịch</th>
												</tr>
											</thead>
											<tbody id="LstBillingSales">	
												{foreach from=$more_information.history_sale key=key item=item}
													<tr>
														<td>{$item.stock_code}</td>
														<td>{$item.project}</td>
														<td>{$clsISO->shortNumber($item.price)}</td>
														<td>{$item.customer_name}</td>
														<td>{$item.date_trading}</td>
													</tr>
												{/foreach}
											</tbody>
										</table>
										<div class="row mx-4">
											<div class="col-md-12 col-xl-6 text-center text-xl-start pb-2 pb-lg-0 pe-0">
												
											</div>
											<div class="col-md-12 col-xl-6 d-flex justify-content-center justify-content-xl-end">
												<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
													<ul class="pagination" id="pagination-container">

													</ul>
												</div>
											</div>
										</div>
										<div style="width: 1%;"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<script>
	var total_number = '{$total_bill}';
	var perPage = '{$per_page}';
	var member_id = '{$oneItem.profile_id}';
</script>
<script src="{$URL_JS}/swiper-bundle.min.js?v={$upd_verson}"></script>
{literal}
<script>
	if(total_number > perPage){
		$('#pagination-container').pagination({
			items: total_number,
			itemsOnPage: perPage,
			prevText: "&laquo;",
			nextText: "&raquo;",
			onPageClick: function (pageNumber) {
				$Core.broker.load_sales(member_id,{page:pageNumber,perPage:perPage})
			}
		});
	}
	
</script>
{/literal}