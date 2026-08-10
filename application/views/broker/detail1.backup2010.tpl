{if ($action eq 'preview' && $template eq '1') || ($action eq '' && $current_template eq '1')}	
	<div class="container-xxl flex-grow-1 container-p-y page_detail_broker detail_broker detail_broker_theme_1 pt-2 text-dark">
		<div class="row">
			<div class="col-12 col-lg-12 col-xxl-12 col-xxxl-10 mx-auto">
				<div class="bg-broker rounded-3 overflow-hidden">
					<section class="banner_profile hero d-flex rounded-3 overflow-hidden position-relative" style="height: 400px">
						{if $action eq 'preview'}
							<div class="position-absolute gap-1 right-10 top-10 zindex-1" style="top: 10px">
								<a href="{$clsISO->getLink('edit_profile')}" class="btn_back btn-icon btn bg-white text-dark fs-5 border rounded-3 top-0 left-0" style="position: unset"><i class='bx bxs-share'></i></a>
								<button class="btn btn-primary" type="button" onClick="$Core.broker.save_template(this,event)" template_id="{$template}">Lưu mẫu</button>
							</div>
						{else}
							{if !empty($loggedIn) && $profile_id eq $oneItem.profile_id}
								<div class="btn-group position-absolute gap-1 right-10 top-10 zindex-1" style="top: 10px">
									<a href="{$clsISO->getLink('broker')}" class="btn_back btn-icon btn bg-white text-dark fs-5 border rounded-3 top-0 left-0"><i class='bx bxs-share'></i></a>
									<a class="btn btn-icon btn-outline-default rounded-3" href="javascript:void(0)" onMouseMove="$Core.broker.setTooltip(this,'Sao chép link')" onclick="$Core.broker.copyToClipboard(this, event)" title="Sao chép link" data-link="{$DOMAIN_URL}{$clsProfile->getLink($oneItem.profile_id,$oneItem)}" ><i class="bx bx-link mr-1"></i></a>
									<a class="btn btn-icon btn-outline-default rounded-3" href="{$clsISO->getLink('edit_profile')}" title="Chỉnh sửa" onMouseMove="$Core.broker.setTooltip(this,'Chỉnh sửa')"><i class='bx bx-edit'></i></a>
								</div>
							{else}
								<div class="btn-group position-absolute gap-1 right-10 top-10 zindex-1" style="top: 10px">
									<a href="{$clsISO->getLink('broker')}" class="btn_back btn-icon btn bg-white text-dark fs-5 border rounded-3 top-0 left-0"><i class='bx bxs-share'></i></a>
									<a class="btn btn-icon btn-outline-default rounded-3" href="javascript:void(0)" onMouseMove="$Core.broker.setTooltip(this,'Sao chép link')" onclick="$Core.broker.copyToClipboard(this, event)" title="Sao chép link" data-link="{$DOMAIN_URL}{$clsProfile->getLink($oneItem.profile_id,$oneItem)}" ><i class="bx bx-link mr-1"></i></a>
								</div>
							{/if}
						{/if}
						<div class="hero-content d-flex align-items-start {if $deviceType eq 'phone'} justify-content-between gap-3 px-2 py-4{else} p-4 flex-column justify-content-center{/if}">
							<div class="position-relative {if $deviceType eq 'phone'}w-px-100 h-px-100{else}w-px-150 h-px-150{/if} p-1 border bg-white rounded-pill mb-3">
								<img class="rounded-circle border w-100 h-100" src="{$clsProfile->getAvatar($oneItem.profile_id,$oneItem,150,150)}" height="150" width="150" alt="User avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'" style="object-fit: cover">
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
							<div class="flex-fill d-flex flex-column">
								<h1 class="text-upper text-dark fs-3">{$oneItem.full_name}</h1>
								{if !empty($more_information.level_sales)}<p class="title text-main2">{$more_information.level_sales}</p>{else}<p class="title text-main2">Chuyên gia tư vấn bất động sản</p>{/if}
								{if !empty($more_information.experience)}<h2 class="text-dark fs-4">{$more_information.experience}</h2>{/if}
								<a href="tel:{$oneItem.phone}" class="btn btn-main btn-lg">Đặt lịch tư vấn</a>
							</div>
						</div>
						<div class="bg_banner flex-fill h-100 position-relative" style="background-image: URL('{if !empty($more_information.banner)}{$more_information.banner}{else}{$URL_IMAGES}/banner_default.jpg{/if}')">		
							{if !empty($more_information.dictum_live)}
								<div class="content_dictum_live fs-2 pe-4 mt-n1 position-absolute bottom-0 right-0 text-right w-80" style="text-shadow: 3px 1px black">{$more_information.dictum_live|html_entity_decode}</div>
							{/if}
						</div>
					</section>
					<div class="row py-3 bg-broker2 text-center align-items-center">
						<div class="col-12 col-md-3 mb-2">
							<h2 class="mb-0 text-upper text-dark">Dịch vụ</h2>
						</div>
						<div class="col-12 col-md-9">
							<div class="form-row">
								<div class="item_service col-4">
									<img src="{$URL_IMAGES}/icons/broker/icon_1.png" alt="Môi giới" width="80" height="80" class="">
									<h3 class="text-upper mb-0 text-dark fs-6 mt-2">Môi giới</h3>
								</div>
								<div class="item_service col-4">
									<img src="{$URL_IMAGES}/icons/broker/icon_2.png" alt="Tư vấn đầu tư" width="80" height="80" class="">
									<h3 class="text-upper mb-0 text-dark fs-6 mt-2">Tư vấn đầu tư</h3>
								</div>
								<div class="item_service col-4">
									<img src="{$URL_IMAGES}/icons/broker/icon_3.png" alt="Pháp lý" width="80" height="80" class="">
									<h3 class="text-upper mb-0 text-dark fs-6 mt-2">Pháp lý</h3>
								</div>
							</div>
						</div>
					</div>	
					{if !empty($more_information.project)}
						<section class="section_certificate no-shadow pt-4 pb-2">
							<div class="row">
								<div class="col-12 col-xxl-10 mx-auto">
									<div class="card-header pb-0">
										<h2 class="card-title mb-0 fw-bold text-center text-upper text-dark">Dự án tiêu biểu</h2> 
									</div>								
									<div class="card-body">
										<div class="owl owl-carousel" data-lg-slide="3" data-md-slide="3" data-sm-slide="2" data-xs-slide="1" data-margin="20" data-nav="true" data-loop="true" data-autoplay="true">
											{foreach from=$more_information.project item=item key=key}
												<div class="box_item_certificate item_da relative">
													<div class="image_certificate img_scale rounded-3" data-fancybox="gallery-project" data-src="{$item.image}" data-caption="{$item.title}"><img src="{$item.image}" alt="" width="200" height="200"></div>
													<div class="text-center px-3 py-2">
														<h3 class="text-upper mb-1 lh-sm fs-6 text-dark">{$item.title}</h3>
														<p class="text-upper mb-0 lh-sm text-dark fs-tiny">Giao dịch: {$item.total_sale_project}</p>
													</div>
												</div>
											{/foreach}
										</div>
										{if $deviceType eq 'phone'}
											<div class="row text-center">
												<div class="col-4 col-sm-4 col-md-4">
													<div class="d-flex flex-column justify-content-center">
														<span class="fs-4 fw-bold">{$more_information.number_sale} GD</span>
														<span class="fs-12">GD thành công</span>
													</div>
												</div>
												<div class="col-4 col-sm-4 col-md-4">
													<div class="d-flex flex-column justify-content-center">
														<span class="fs-4 fw-bold">{$more_information.success_rate}</span>
														<span class="fs-12">Tỷ lệ giao dịch thành công</span>
													</div>
												</div>										
												<div class="col-4 col-sm-4 col-md-4">
													<div class="d-flex flex-column justify-content-center">
														<span class="fs-4 fw-bold">{$more_information.average_rate}</span>
														<span class="fs-12">Đánh giá trung bình</span>
													</div>
												</div>
											</div>
										{else}
											<div class="row text-center">
												<div class="col-4 col-sm-4 col-md-4">
													<div class="d-flex flex-column justify-content-center">
														<span class="fs-3">{$more_information.number_sale} giao dịch</span>
														<span class="fs-6">Giao dịch thành công</span>
													</div>
												</div>
												<div class="col-4 col-sm-4 col-md-4">
													<div class="d-flex flex-column justify-content-center">
														<span class="{if $deviceType eq 'phone'}fs-6{else}fs-3{/if}">{$more_information.success_rate}</span>
														<span class="fs-6">Tỷ lệ giao dịch thành công</span>
													</div>
												</div>										
												<div class="col-4 col-sm-4 col-md-4">
													<div class="d-flex flex-column justify-content-center">
														<span class="{if $deviceType eq 'phone'}fs-6{else}fs-3{/if}">{$more_information.average_rate}</span>
														<span class="fs-6">Đánh giá trung bình</span>
													</div>
												</div>
											</div>
										{/if}
									</div>
								</div>
							</div>
						</section>
					{/if}
					<div class="{if $deviceType eq 'phone'}px-2{else}p-4{/if}">
						<div class="row">
							<!-- Customer-detail Sidebar -->
							<div class="col-xxl-4 col-lg-5 col-md-5 col-12">
								<div class="sticky">
									<!-- Customer-detail Card -->
									<div class="card mb-2 no-shadow bg-broker2">
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
												<small class="text-muted text-uppercase">Liên hệ</small>
												<ul class="list-unstyled mb-4 mt-3">
													{if !empty($oneItem.phone)}
														<li class="d-flex align-items-center mb-2"><i class="bx bx-phone fs-5"></i><span class="fw-semibold mx-2">Điện thoại:</span> <a class="px-1 py-0" href="tel:{$oneItem.phone}" >{$clsClassTable->mask($oneItem.phone,1)}</a></li>
													{/if}
													{if !empty($oneItem.email)}
														<li class="d-flex align-items-center mb-2"><i class="bx bx-envelope fs-5"></i><span class="fw-semibold mx-2">Email:</span> <a href="mailto:{$oneItem.email}" class="px-1 py-0">{$clsClassTable->mask($oneItem.email,1)}</a></li>
													{/if}
												</ul>
												{if !empty($more_information.facebook) || !empty($more_information.instagram) || !empty($more_information.linkedin) || !empty($more_information.twitter) }
												<div class="share">
													<small class="text-muted text-uppercase">Follow us</small>
													<ul class="list-unstyled mb-4 mt-3">
														{if !empty($more_information.facebook)}
															<li class="d-flex align-items-center mb-2">
																<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.facebook}" rel="nofollow,noindex">
																<i class='bx bxl-facebook-circle fs-5 mr-2'></i>{$more_information.facebook}</a>
															</li>
														{/if}
														{if !empty($more_information.instagram)}
															<li class="d-flex align-items-center mb-2">
																<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.instagram}" rel="nofollow,noindex" >
																<i class="bx bxl-instagram fs-5 mr-2"></i>{$more_information.instagram}</a>
															</li>
														{/if}
														{if !empty($more_information.linkedin)}
															<li class="d-flex align-items-center mb-2">
																<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.linkedin}" rel="nofollow,noindex" >
																<i class="bx bxl-linkedin fs-5 mr-2"></i>{$more_information.linkedin}</a>
															</li>
														{/if}
														{if !empty($more_information.twitter)}
															<li class="d-flex align-items-center mb-2">
																<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.twitter}" rel="nofollow,noindex" >
																<i class="bx bxl-twitter fs-5 mr-2"></i>{$more_information.twitter}</a>
															</li>
														{/if}
													</ul>
												</div>
												{/if}
											</div>
											<div class="d-flex justify-content-end nav_sidebar">
												<ul class="nav nav-pills">
													<li class="nav-item">
														<a class="nav-link btn_tab d-flex align-items-center" id="pills-about-tab" data-bs-toggle="pill" data-bs-target="#pills-about" type="button" role="tab" aria-controls="pills-about" aria-selected="false" onClick="$Core.broker.tab(this,event)">Giới thiệu<i class='bx bx-right-arrow-alt' ></i></a>
													</li>
												</ul>
											</div>
										</div>
									</div>
									{if $deviceType ne 'phone'}
										{if !empty($image_billing)}
										<div class="card mb-2 no-shadow bg-broker2">
											<div class="card-header d-flex justify-content-between align-items-center">
												<h5 class="card-title text-dark mb-0">Danh sách giao dịch</h5>
												<ul class="nav nav-pills">
													<li class="nav-item">
														<a class="nav-link btn_tab d-flex align-items-center" id="pills-history-sale-tab" data-bs-toggle="pill" data-bs-target="#pills-history-sale" type="button" role="tab" aria-controls="pills-history-sale" aria-selected="false" onClick="$Core.broker.tab(this,event)">Xem tất cả<i class='bx bx-right-arrow-alt' ></i></a>
													</li>
												</ul>
											</div>

											<div class="card-body">
												<div class="form-row row">
													{section loop=$image_billing name=i max=6}
														<div class="item col-4 mb-2" data-fancybox="gallery_billing" href="{$image_billing[i]}">
															<div class="rounded img_scale">
																<img class="drag-item cursor-pointer" src="{$image_billing[i]}" alt="avatar" style="width: 100%;height:94px">
															</div>
														</div>
													{/section}
												</div>
											</div>
										</div>
										{/if}
										{if !empty($lstShare)}
											<div class="card mb-2 no-shadow bg-broker2">
												<div class="card-header d-flex justify-content-between align-items-center">
													<h5 class="card-title text-dark mb-0">Hoạt động tiếp khách</h5>
													<ul class="nav nav-pills">
														<li class="nav-item">
															<a class="nav-link btn_tab d-flex align-items-center" id="pills-share-tab" data-bs-toggle="pill" data-bs-target="#pills-share" type="button" role="tab" aria-controls="pills-share" aria-selected="false" onClick="$Core.broker.tab(this,event)">Xem tất cả<i class='bx bx-right-arrow-alt' ></i></a>
														</li>
													</ul>
												</div>										
												<div class="card-body">
													<div class="form-row row">
														{section loop=$lstShare name=i max=6}
															{assign var=lstImage value=$lstShare[i].lstImage}
															<div class="item col-4 mb-2">
																<div class="rounded img_scale" data-fancybox="gallery_share" href="{$FH_URL}{$lstShare[i].avatar}" data-caption="{$lstShare[i].title}">
																	<img class="drag-item cursor-pointer" src="{$FH_URL}{$lstShare[i].avatar}" alt="avatar" style="width: 100%;height:94px">
																</div>
																{if !empty($lstImage)}
																	{foreach from=$lstImage item=image}
																		<a class="d-none"  data-fancybox="gallery_share" href="{$FH_URL}{$image}" data-caption="{$lstShare[i].title}"></a>
																	{/foreach}
																{/if}
															</div>
														{/section}
													</div>
												</div>
											</div>
										{/if}
										{if !empty($more_information.image)}
											<div class="card mb-2 no-shadow bg-broker2">
												<div class="card-header d-flex justify-content-between align-items-center">
													<h5 class="card-title text-dark mb-0">Hình ảnh</h5>
													<ul class="nav nav-pills">
														<li class="nav-item">
															<a class="nav-link btn_tab d-flex align-items-center" id="pills-image-tab" data-bs-toggle="pill" data-bs-target="#pills-image" type="button" role="tab" aria-controls="pills-image" aria-selected="false" onClick="$Core.broker.tab(this,event)">Xem tất cả<i class='bx bx-right-arrow-alt' ></i></a>
														</li>
													</ul>
												</div>										
												<div class="card-body">
													<div class="form-row row">
														{assign var=lstImage value=$more_information.image}
														{section loop=$lstImage name=i max=9}
															<div class="item col-4 mb-2">
																<div class="rounded img_scale" data-fancybox="gallery_image" href="{$lstImage[i]}" data-caption="Hình ảnh">
																	<img class="drag-item cursor-pointer" src="{$lstImage[i]}" alt="avatar" style="width: 100%;height:94px">
																</div>
															</div>
														{/section}
													</div>
												</div>
											</div>	
										{/if}
										{if $more_information.link_video ne ''}
										<div class="card no-shadow bg-broker2">
											<h5 class="card-header text-dark">Video</h5>
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
							<div class="col-xxl-8 col-lg-7 col-md-7 col-12">
								<div class="box_content_broker mb-4 h-100">
									{assign var=check_active value=0}
									{if !empty($more_information.about) || !empty($more_information.certificate) || !empty($more_information.working_process)} 
										{assign var=check_active value=1}
										<div class="tab-pane fade show active {if $deviceType ne 'phone'}h-100{/if}" id="pills-about" role="tabpanel" aria-labelledby="pills-about-tab">
											<div class="card no-shadow bg-broker2">
												<div class="card-body">
													{if !empty($more_information.about)} 
													<div class="my-4 box_about">
														<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
															<h2 class="card-title mb-0 text-nowrap fs-4 text-dark text-upper">Giới thiệu</h2>
															<hr class="flex-fill" style="color: #33509e;height: 2px">
														</div>	
														<div class="tinymce_content content_about fs-5">
															{$more_information.about|html_entity_decode} 
														</div>	
													</div>	
													{/if}
													{if !empty($more_information.certificate)}
														<section class="section_certificate mb-4 no-shadow pt-3">
															<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
																<h2 class="card-title mb-0 text-nowrap fs-4 text-dark text-upper">Bằng cấp, chứng chỉ</h2>
																<hr class="flex-fill" style="color: #33509e;height: 2px">
															</div>								
															<div class="box_certificate form-row row-cols-1 row-cols-lg-2 row-cols-xl-2 mb-2" >
																{foreach from=$more_information.certificate item=item key=key}
																	<div class="col mb-2">
																		<div class="box_item_certificate relative">
																			<div class="image_certificate img_scale" data-fancybox="gallery-certificate" src="{$item.image}" data-caption="{$item.title}"><img src="{$item.image}" alt="" width="200" height="260"></div>
																			<h3 class="title_certificate mb-0 p-3 text-white lh-sm fs-6">{$item.title}</h3>
																		</div>
																	</div>
																{/foreach}
															</div>
														</section>
													{/if}
													{if !empty($more_information.working_process)}
														<section class="section_timeline mb-4 no-shadow pt-3">
															<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
																<h2 class="card-title mb-0 text-nowrap fs-4 text-dark text-upper">Quá trình làm việc</h2>
																<hr class="flex-fill" style="color: #33509e;height: 2px">
															</div>								
															<div class="">
																<ul class="timeline mb-0 list-unstyled">
																	{foreach from=$more_information.working_process|@array_reverse key=key item=item name=i}		
																	<li class="timeline-item timeline-item-transparent position-relative mb-4">
																		{if $smarty.foreach.i.first}
																			<div class="position-absolute left-0 top-0 zindex-1">
																				<img src="{$clsISO->getGoogleUrl($item.image,80)}" alt="" width="80" height="50" onerror="this.src='{$URL_IMAGES}/no-image.png'" class="p-2 bg-white rounded-3" loading="lazy" style="object-fit: contain">
																			</div>
																		{/if}
																		<div class="timeline-event">
																			<div class="timeline-header mb-2">
																				<h6 class="mb-0 text-dark fs-5 {if $smarty.foreach.i.first}fw-bold{else}fw-semibold{/if}">{$item.company_name}</h6>
																			</div>
																			<p class="mb-1 text-black">{$item.position}</p>
																			<p class="mb-0 text-muted">{$item.time}</p>
																		</div>
																	</li>
																	{/foreach}
																</ul>
															</div>
														</section>
													{/if}
												</div>
											</div>
										</div>
									{/if}
									{if !empty($image_billing)}
										<div class="history-sale tab-pane fade {if $deviceType eq 'phone' || empty($check_active)} show active{else} h-100{/if}" id="pills-history-sale" role="tabpanel" aria-labelledby="pills-history-sale-tab"> 
											{assign var=check_active value=1}
											<div class="card bg-broker2 no-shadow">
												<div class="card-body">
													<div class="dataTables_wrapper dt-bootstrap5 no-footer pt-3">
														<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
															<h2 class="card-title mb-0 text-nowrap fs-4 text-dark text-upper">Danh sách giao dịch ({$total_bill} GD)</h2>
															<hr class="flex-fill" style="color: #33509e;height: 2px">
														</div>	
														<div class="form-row row-cols-2 row-cols-lg-2 row-cols-xl-2">
															{foreach from=$image_billing item=image}
																<div class="col mb-2">
																	<div class="awe__doc-item position-relative img_scale rounded-3 cursor-pointer" data-preload="false" data-fancybox="img_billing" href="{$image}">
																		<img class="w-100 img-cover h-auto" src="{$image}" alt="" width="200" height="300">
																	</div>
																</div>
															{/foreach}
														</div>
													</div>
												</div>
											</div>										
											{if !empty($lstPostMeta)}
												<section class="section_post py-4">
													<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
														<hr class="flex-fill" style="color: #33509e;height: 2px">
														<h2 class="card-title mb-0 text-nowrap fs-4 text-dark text-upper">Bài viết chia sẻ</h2>
														<hr class="flex-fill" style="color: #33509e;height: 2px">
													</div>	
													<div class="lst_post">
														{foreach from=$lstPostMeta item=oneMeta}
															<div class="card no-shadow rounded-3 bg-broker2 overflow-hidden">
																<div class="form-row">
																	{if !empty($oneMeta.image)}
																		<div class="col-12 col-md-12 col-lg-4">
																			<div class="box_img_test img_scale">
																				<img width="508" height="267" src="{$oneMeta.image}" alt="" class="img_customer w-100 h-100" onerror="this.src='{$URL_IMAGES}/no-image.png'">
																			</div>
																		</div>
																		<div class="col-12 col-md-12 col-lg-8">
																			<div class="body_tes position-relative flex-flow p-4 h-100">
																				<h3 class="title limit_1line fs-4 text-dark">{$oneMeta.title}</h3>
																				<div class="item__intro fs-15">{$oneMeta.content|html_entity_decode}</div>
																			</div>
																		</div>
																	{else}
																		<div class="col-12 col-md-12 col-lg-12">
																			<div class="body_tes position-relative flex-flow p-4 h-100">
																				<h3 class="title limit_1line fs-4 text-dark">{$oneMeta.title}</h3>
																				<div class="item__intro fs-15">{$oneMeta.content|html_entity_decode}</div>
																			</div>
																		</div>
																	{/if}
																</div>
															</div>
														{/foreach}
													</div>
												</section>
											{/if}
										</div>
									{/if}
									{if !empty($lstShare)}
										<div class="share tab-pane fade {if $deviceType eq 'phone' || empty($check_active)} show active{else} h-100{/if}" id="pills-share" role="tabpanel" aria-labelledby="pills-share-tab"> 
											{assign var=check_active value=1}
											<div class="card no-shadow bg-broker2 no-shadow h-100">
												<div class="card-body">
													<div class="dataTables_wrapper dt-bootstrap5 no-footer pt-3">
														<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
															<h2 class="card-title mb-0 text-nowrap fs-4 text-dark text-upper">Hoạt động tiếp khách</h2>
															<hr class="flex-fill" style="color: #33509e;height: 2px">
														</div>	
														<div class="form-row row-cols-2 row-cols-lg-2 row-cols-xl-2">
															{foreach from=$lstShare item=oneShare}
																<div class="col mb-2">
																	<div class="rounded img_scale position-relative h-100" data-fancybox="gallery1_share" href="{$FH_URL}{$oneShare.avatar}" data-caption="{$oneShare.title}">
																		<img class="w-100 img-cover h-100" src="{$FH_URL}{$oneShare.avatar}" alt="" width="200" height="300">
																		<div class="position-absolute bottom-0 left-0 py-2 px-3 w-100" style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 0.9) 100%)">
																			<h3 class="text-white mb-0 fs-6 {if $deviceType ne 'phone'}text-center{/if}">{$oneShare.title}</h3>
																		</div>
																	</div>
																	{if !empty($oneShare.lstImage)}
																		{foreach from=$oneShare.lstImage item=image}
																			<a class="d-none"  data-fancybox="gallery1_share" href="{$FH_URL}{$image}" data-caption="{$oneShare.title}"></a>
																		{/foreach}
																	{/if}
																</div>
															{/foreach}
														</div>
													</div>
												</div>
											</div>
										</div>
									{/if}
									{if !empty($more_information.image)}
										<div class="share tab-pane fade {if $deviceType eq 'phone' || empty($check_active)} show active{else} h-100{/if}" id="pills-image" role="tabpanel" aria-labelledby="pills-image-tab"> 
											{assign var=check_active value=1}
											<div class="card no-shadow bg-broker2 no-shadow h-100">
												<div class="card-body">
													<div class="dataTables_wrapper dt-bootstrap5 no-footer pt-3">
														<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
															<h2 class="card-title mb-0 text-nowrap fs-4 text-dark text-upper">Hình ảnh</h2>
															<hr class="flex-fill" style="color: #33509e;height: 2px">
														</div>	
														<div class="form-row row-cols-2 row-cols-lg-2 row-cols-xl-2">
															{foreach from=$more_information.image item=image}
																<div class="col mb-2">
																	<div class="rounded img_scale position-relative" data-fancybox="gallery1_image" href="{$image}" data-caption="Hình ảnh">
																		<img class="w-100 img-cover h-auto" src="{$image}" alt="" width="200" height="200">
																	</div>
																</div>
															{/foreach}
														</div>
													</div>
												</div>
											</div>
										</div>
									{/if}
									{if $deviceType eq 'phone'}
										{if $more_information.link_video ne ''}
											<div class="card no-shadow bg-broker2 mb-4">	
												<div class="card-body">
													<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
														<h2 class="card-title mb-0 text-nowrap fs-4 text-dark text-upper">Video</h2>
														<hr class="flex-fill" style="color: #33509e;height: 2px">
													</div>
													<div class="box_body_video rounded">	
														{$clsISO->getEmbedVideo($more_information.link_video)}
													</div>
												</div>
											</div>
										{/if}
									{/if}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{elseif ($action eq 'preview' && $template eq '2') || ($action eq '' && $current_template eq '2')}
	<div class="container-xxl flex-grow-1 container-p-y page_detail_broker detail_broker pt-2 text-dark detail_broker_theme_2" style="{if empty($is_profile_domain)}max-width: 1200px{else}max-width:1300px{/if}">
		<div class="form-row">
			<div class="col-12 col-md-7 col-lg-8 col-xxl-8">
				<section class="section_banner relative">
					<div class="d-flex justify-content-between align-items-center position-absolute w-100 p-2">				
						{if $action eq 'preview'}
							<a href="{$clsISO->getLink('edit_profile')}" class="btn_back"><i class='bx bxs-share'></i></a>	
							<button class="btn btn-primary" type="button" onClick="$Core.broker.save_template(this,event)" template_id="{$template}">Lưu mẫu</button>
						{else}
							<a href="{$clsISO->getLink('broker')}" class="btn_back"><i class='bx bxs-share'></i></a>	
							{if !empty($loggedIn) && $profile_id eq $oneItem.profile_id}
								<div class="btn-group position-absolute gap-1 right-10 top-10 zindex-1" style="top: 10px">
									<a class="btn btn-icon btn-outline-default rounded-3" href="{$clsISO->getLink('edit_profile')}" title="Chỉnh sửa" onMouseMove="$Core.broker.setTooltip(this,'Chỉnh sửa')"><i class='bx bx-edit'></i></a>
								</div>
							{/if}
						{/if}
					</div>
					<img class="w-100 rounded-top img-cover" src="{$more_information.banner}" onerror="this.src='{$URL_IMAGES}/banner_default.jpg'" alt="Banner" height="200">
					{if !empty($more_information.dictum_live)}
						<div class="content_dictum_live fs-2 pe-4 mt-n1 position-absolute {if $deviceType eq 'computer'}bottom-0{else}top-0{/if} right-0 text-right" style="text-shadow: 3px 1px black">{$more_information.dictum_live|html_entity_decode}</div>
					{/if}
				</section>
				<div class="card no-shadow box_content mb-2">
					<div class="card-body rounded-bottom d-flex flex-wrap justify-content-between align-items-end p-3 pb-2 gap-2">
						<div class="box_left_head d-flex flex-wrap relative pt-4 flex-fill">
							<div class="box_image rounded-3 bg-white">
								<img class="rounded-3" src="{$clsProfile->getAvatar($oneItem.profile_id,$oneItem,100,100)}" height="100" width="100" alt="User avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'" style="object-fit: cover">
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
							<div class="info-right">
								<div class="box_name_user">
									<h1 class="fs-4 mb-1 fw-bold name_user">{$oneItem.full_name}</h4>
									{if !empty($more_information.level_sales)}<p class="title text-main2 mb-2">{$more_information.level_sales}</p>{else}<p class="title text-main2 mb-2">Chuyên gia tư vấn bất động sản</p>{/if}
									{if !empty($more_information.experience)}<h2 class="text-dark fs-4 mb-0">{$more_information.experience}</h2>{/if}
								</div>	
							</div>
						</div>
						<div class="box_right_head d-flex flex-wrap flex-fill">
							<div class="d-flex flex-wrap">
								<div class="me-2"><a href="tel:{$oneItem.phone}" class="btn btn-outline-primary"><i class="bx bx-phone fs-5 me-2 align-text-bottom"></i>Gọi điện</a></div>
								<div class="me-2"><a href="mailto:{$oneItem.email}" class="btn btn-outline-primary"><i class="bx bx-envelope fs-5 me-2 align-text-bottom"></i>Email</a></div>
								<a class="btn btn-icon btn-outline-primary" href="javascript:void(0)" onMouseMove="$Core.broker.setTooltip(this,'Sao chép link')" onclick="$Core.broker.copyToClipboard(this, event)" title="Sao chép link" data-link="{$DOMAIN_URL}{$clsProfile->getLink($oneItem.profile_id,$oneItem)}" ><i class="bx bx-link mr-1"></i></a>								
							</div>
						</div>	
					</div>
					<div class="pt-3 border-top px-2 d-flex justify-content-between align-items-center menu_tab {if !empty($is_profile_domain)}profile_domain{/if}" id="menu_detail">
						<ul class="nav nav-pills flex-md-row ">
							{if !empty($lstMenu)}
								{foreach from=$lstMenu item=title key=key name=i}
									<li class="nav-item" role="presentation">
										<a class="btn nav-link {if $smarty.foreach.i.first}active{/if}" id="{$key}-tab" onClick="$Core.broker.scrollTo(this,event)" data-href="#{$key}" >{$title}</a>
									</li>
								{/foreach}
							{/if}
						</ul>
						{if !empty($lstMenuHidden)}
							<div class="btn-group">
								<button class="btn btn-icon dropdown-toggle hide-arrow" type="button" id="dropdownMenuClickable" data-bs-toggle="dropdown" aria-expanded="true"><i class="bx bx-dots-vertical-rounded"></i></button>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuClickableInside">										
									{foreach from=$lstMenuHidden item=title key=key name=i}
										<li class="nav-item" role="presentation">
											<a class="dropdown-item" id="{$key}-tab" onClick="$Core.broker.scrollTo(this,event)" data-href="#{$key}" >{$title}</a>
										</li>
									{/foreach}
								</ul>
							</div>
						{/if}
					</div>
				</div>	
				<div class="tab-content p-0">
					{assign var=check_active value=0}
					{if !empty($more_information.about)} 
						{assign var=check_active value=1}
						<div class="mb-2 item_tab" id="pills-about" role="tabpanel" aria-labelledby="pills-about-tab">
							<div class="card no-shadow mb-2">
								<div class="card-body">
									<div class="my-2 box_about">
										<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
											<h2 class="card-title mb-0 text-nowrap fs-4 text-dark">Giới thiệu</h2>
										</div>	
										<div class="tinymce_content content_about fs-6 text-black">
											{$more_information.about|html_entity_decode} 
										</div>	
									</div>	
								</div>	
							</div>	
						</div>
					{/if}
					{if !empty($more_information.certificate)} 
						{assign var=check_active value=1}
						<div class="mb-2 item_tab" id="pills-certificate" role="tabpanel" aria-labelledby="pills-certificate-tab">
							<section class="section_certificate mb-2 no-shadow">
								<div class="card no-shadow">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
											<h2 class="card-title mb-0 text-nowrap fs-4 text-dark">Bằng cấp, chứng chỉ</h2>
										</div>								
										<div class="box_certificate form-row row-cols-1 row-cols-lg-2 row-cols-xl-2 mb-2" >
											{foreach from=$more_information.certificate item=item key=key}
												<div class="col mb-2">
													<div class="box_item_certificate relative">
														<div class="image_certificate img_scale" data-fancybox="gallery-certificate" src="{$item.image}" data-caption="{$item.title}"><img src="{$item.image}" alt="" width="200" height="260"></div>
														<h3 class="title_certificate mb-0 p-3 text-white lh-sm fs-6">{$item.title}</h3>
													</div>
												</div>
											{/foreach}
										</div>
									</div>	
								</div>
							</section>
						</div>
					{/if}
					{if !empty($more_information.working_process)} 
						{assign var=check_active value=1}
						<div class="mb-2 item_tab" id="pills-working_process" role="tabpanel" aria-labelledby="pills-working_process-tab">
							<section class="section_timeline no-shadow">
								<div class="card no-shadow">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center box_head mb-4 gap-4">
											<h2 class="card-title mb-0 text-nowrap fs-4 text-dark">Quá trình làm việc</h2>
										</div>								
										<div class="">
											<ul class="timeline mb-0 list-unstyled">
												{foreach from=$more_information.working_process|@array_reverse key=key item=item name=i}		
												<li class="timeline-item timeline-item-transparent position-relative mb-4">
													{if $smarty.foreach.i.first}
														<img src="{$clsISO->getGoogleUrl($item.image,80)}" alt="" width="80" height="50" onerror="this.src='{$URL_IMAGES}/no-image.png'" class="position-absolute h-auto img-cover" loading="lazy">
													{/if}
													<div class="timeline-event">
														<div class="timeline-header mb-2">
															<h6 class="mb-0 text-dark {if $smarty.foreach.i.first}fw-bold{else}fw-semibold{/if}">{$item.company_name}</h6>
														</div>
														<p class="mb-1 text-black">{$item.position}</p>
														<p class="mb-0 text-muted">{$item.time}</p>
													</div>
												</li>
												{/foreach}
											</ul>
										</div>
									</div>	
								</div>
							</section>
						</div>
					{/if}
					{if !empty($more_information.project)}
						<div class="mb-2 item_tab" id="pills-project" role="tabpanel" aria-labelledby="pills-project-tab">
							<section class="card section_certificate no-shadow pt-4 pb-2">
								<div class="card-header">
									<h2 class="card-title mb-0 text-nowrap fs-4 text-dark">Dự án tiêu biểu</h2>
								</div>								
								<div class="card-body">
									<div class="owl owl-carousel" data-lg-slide="3" data-md-slide="3" data-sm-slide="2" data-xs-slide="1" data-margin="20" data-nav="true" data-loop="true" data-autoplay="true">
										{foreach from=$more_information.project item=item key=key}
											<div class="box_item_certificate item_da relative">
												<div class="image_certificate img_scale rounded-3" data-fancybox="gallery-project" data-src="{$item.image}" data-caption="{$item.title}"><img src="{$item.image}" alt="" width="200" height="200"></div>
												<div class="text-center px-3 py-2">
													<h3 class="text-upper mb-1 lh-sm fs-6 text-dark">{$item.title}</h3>
													<p class="text-upper mb-0 lh-sm text-dark fs-tiny">Giao dịch: {$item.total_sale_project}</p>
												</div>
											</div>
										{/foreach}
									</div>
								</div>
							</section>
						</div>
					{/if}
					{if !empty($image_billing)}
						<div class="history-sale mb-2 item_tab" id="pills-history-sale"> 
							{assign var=check_active value=1}
							<div class="card no-shadow">
								<div class="card-body">
									<div class="dataTables_wrapper dt-bootstrap5 no-footer">
										<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
											<h2 class="card-title mb-0 text-nowrap fs-4 text-dark">Danh sách giao dịch ({$total_bill} GD)</h2>
										</div>	
										<div class="form-row row-cols-2 row-cols-lg-2 row-cols-xl-2">
											{foreach from=$image_billing item=image}
												<div class="col mb-2">
													<div class="awe__doc-item position-relative img_scale rounded-3 cursor-pointer" data-preload="false" data-fancybox="img_billing" href="{$image}">
														<img class="w-100 img-cover h-auto" src="{$image}" alt="" width="200" height="300">
													</div>
												</div>
											{/foreach}
										</div>
									</div>
								</div>
							</div>	
						</div>
					{/if}
					{if !empty($lstPostMeta)}
						<div class="card share mb-2 item_tab" id="pills-share-sale"> 
							{assign var=check_active value=1}										
							{if !empty($lstPostMeta)}
								<section class="section_post">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
											<h2 class="card-title mb-0 text-nowrap fs-4 text-dark">Bài viết chia sẻ</h2>
										</div>	
										<div class="lst_post">
											{foreach from=$lstPostMeta item=oneMeta}
												<div class="card rounded-3 overflow-hidden">
													<div class="form-row">
														{if !empty($oneMeta.image)}
															<div class="col-12 col-md-12 col-lg-4">
																<div class="box_img_test img_scale">
																	<img width="508" height="267" src="{$oneMeta.image}" alt="" class="img_customer w-100 h-100" onerror="this.src='{$URL_IMAGES}/no-image.png'">
																</div>
															</div>
															<div class="col-12 col-md-12 col-lg-8">
																<div class="body_tes position-relative flex-flow p-4 h-100">
																	<h3 class="title limit_1line fs-4 text-dark">{$oneMeta.title}</h3>
																	<div class="item__intro fs-15">{$oneMeta.content|html_entity_decode}</div>
																</div>
															</div>
														{else}
															<div class="col-12 col-md-12 col-lg-12">
																<div class="body_tes position-relative flex-flow p-4 h-100">
																	<h3 class="title limit_1line fs-4 text-dark">{$oneMeta.title}</h3>
																	<div class="item__intro fs-15">{$oneMeta.content|html_entity_decode}</div>
																</div>
															</div>
														{/if}
													</div>
												</div>
											{/foreach}
										</div>
									</div>
								</section>
							{/if}
						</div>
					{/if}
					{if !empty($lstShare)}
						<div class="share mb-2 item_tab" id="pills-share"> 
							{assign var=check_active value=1}
							<div class="card no-shadow no-shadow h-100">
								<div class="card-body">
									<div class="dataTables_wrapper dt-bootstrap5 no-footer">
										<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
											<h2 class="card-title mb-0 text-nowrap fs-4 text-dark">Hoạt động tiếp khách</h2>
										</div>	
										<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-3">
											{foreach from=$lstShare item=oneShare}
												<div class="col mb-2">
													<div class="rounded img_scale position-relative h-100" data-fancybox="gallery1_share" href="{$FH_URL}{$oneShare.avatar}" data-caption="{$oneShare.title}">
														<img class="w-100 img-cover h-100" src="{$FH_URL}{$oneShare.avatar}" alt="" width="200" height="300">
														<div class="position-absolute bottom-0 left-0 py-2 px-3 w-100" style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 0.9) 100%)">
															<h3 class="text-white mb-0 fs-6 {if $deviceType ne 'phone'}text-center{/if}">{$oneShare.title}</h3>
														</div>
													</div>
													{if !empty($oneShare.lstImage)}
														{foreach from=$oneShare.lstImage item=image}
															<a class="d-none"  data-fancybox="gallery1_share" href="{$FH_URL}{$image}" data-caption="{$oneShare.title}"></a>
														{/foreach}
													{/if}
												</div>
											{/foreach}
										</div>
									</div>
								</div>
							</div>
						</div>
					{/if}
					{if !empty($more_information.image)}
						<div class="image mb-2 item_tab" id="pills-image"> 
							{assign var=check_active value=1}
							<div class="card no-shadow no-shadow h-100">
								<div class="card-body">
									<div class="dataTables_wrapper dt-bootstrap5 no-footer">
										<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
											<h2 class="card-title mb-0 text-nowrap fs-4 text-dark">Hình ảnh</h2>
										</div>	
										<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4">
											{foreach from=$more_information.image item=image}
												<div class="col mb-2">
													<div class="rounded img_scale position-relative h-100" data-fancybox="gallery1_image" href="{$image}" data-caption="Hình ảnh">
														<img class="w-100 img-cover h-100" src="{$image}" alt="" width="200" height="200">
													</div>
												</div>
											{/foreach}
										</div>
									</div>
								</div>
							</div>
						</div>
					{/if}
					{if !empty($more_information.link_video)}
						<div class="video mb-2 item_tab" id="pills-video"> 
							{assign var=check_active value=1}
							<div class="card no-shadow no-shadow">
								<div class="card-body">
									<div class="dataTables_wrapper dt-bootstrap5 no-footer">
										<div class="d-flex justify-content-between align-items-center box_head mb-3 gap-4">
											<h2 class="card-title mb-0 text-nowrap fs-4 text-dark">Video</h2>
										</div>	
										<div class="box_body_video rounded">
											{$clsISO->getEmbedVideo($more_information.link_video)}
										</div>
									</div>
								</div>
							</div>
						</div>
					{/if}
				</div>
			</div>
			<div class="col-12 col-md-5 col-lg-4 col-xxl-4">
				<div class="position-sticky" style="{if empty($is_profile_domain)}top: 70px{else}top: 0px{/if}">
					<div class="card mb-2 no-shadow">
						<div class="card-body">
							<div class="info-container">
								<small class="text-muted text-uppercase">Thông tin</small>
								<ul class="list-unstyled mb-4 mt-3">
									{if !empty($oneItem.birthday)}
										<li class="d-flex align-items-start mb-2"><i class="bx bx-cake fs-5"></i><span class="fw-semibold mx-2 text-nowrap">Ngày sinh:</span> <span class="">{$clsISO->formatDate($oneItem.birthday,5)}</span></li> 
									{/if}
									{if !empty($oneItem.address)}
										<li class="d-flex align-items-start mb-2"><i class="bx bx-flag fs-5"></i><span class="fw-semibold mx-2 text-nowrap">Địa chỉ:</span> <span class="">{if $oneItem.address ne ""}{$oneItem.address}{else}[Chưa cập nhật]{/if}</span></li> 
									{/if}
									{if !empty($more_information.experience)}
										<li class="d-flex align-items-start mb-2"><i class="bx bx-briefcase fs-5"></i><span class="fw-semibold mx-2 text-nowrap">Kinh nghiệm:</span> <span class="">{$more_information.experience}</span></li>
									{/if}
									{if !empty($more_information.agency)}
										<li class="d-flex align-items-start mb-2"><i class="bx bx-package fs-5"></i><span class="fw-semibold mx-2 text-nowrap">Đại lý:</span> <span class="">{$more_information.agency}</span></li>
									{/if}
									{if !empty($more_information.number_sale)}
										<li class="d-flex align-items-start mb-2"><i class="bx bx-building-house fs-5"></i><span class="fw-semibold mx-2 text-nowrap">Giao dịch thành công:</span> <span class="">{$more_information.number_sale}</span></li>
									{/if}
								</ul>
							</div>
							{if !empty($oneItem.phone) || !empty($oneItem.email)}
								<hr>
								<div class="info-container">
									<small class="text-muted text-uppercase">Liên hệ</small>
									<ul class="list-unstyled mb-4 mt-3">
										{if !empty($oneItem.phone)}
											<li class="d-flex align-items-center mb-2"><i class="bx bx-phone fs-5"></i><span class="fw-semibold mx-2">Điện thoại:</span> <a class="px-1 py-0" href="tel:{$oneItem.phone}" >{$clsClassTable->mask($oneItem.phone,1)}</a></li>
										{/if}
										{if !empty($oneItem.email)}
											<li class="d-flex align-items-center mb-2"><i class="bx bx-envelope fs-5"></i><span class="fw-semibold mx-2">Email:</span> <a href="mailto:{$oneItem.email}" class="px-1 py-0">{$clsClassTable->mask($oneItem.email,1)}</a></li>
										{/if}
									</ul>
								</div>
							{/if}
							{if !empty($more_information.facebook) || !empty($more_information.instagram) || !empty($more_information.linkedin) || !empty($more_information.twitter) }
								<hr>	
								<div class="info-container">
									<div class="share">
										<small class="text-muted text-uppercase">Follow us</small>
										<ul class="list-unstyled mb-4 mt-3">
											{if !empty($more_information.facebook)}
												<li class="d-flex align-items-center mb-2">
													<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.facebook}" rel="nofollow,noindex">
													<i class='bx bxl-facebook-circle fs-5 mr-2'></i>{$more_information.facebook}</a>
												</li>
											{/if}
											{if !empty($more_information.instagram)}
												<li class="d-flex align-items-center mb-2">
													<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.instagram}" rel="nofollow,noindex" >
													<i class="bx bxl-instagram fs-5 mr-2"></i>{$more_information.instagram}</a>
												</li>
											{/if}
											{if !empty($more_information.linkedin)}
												<li class="d-flex align-items-center mb-2">
													<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.linkedin}" rel="nofollow,noindex" >
													<i class="bx bxl-linkedin fs-5 mr-2"></i>{$more_information.linkedin}</a>
												</li>
											{/if}
											{if !empty($more_information.twitter)}
												<li class="d-flex align-items-center mb-2">
													<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.twitter}" rel="nofollow,noindex" >
													<i class="bx bxl-twitter fs-5 mr-2"></i>{$more_information.twitter}</a>
												</li>
											{/if}
										</ul>
									</div>
								</div>
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{elseif ($action eq 'preview' && $template eq '3') || ($action eq '' && $current_template eq '3')}	
	<div class="container-xxl flex-grow-1 container-p-y page_detail_broker detail_broker detail_broker_theme_3 pt-2" {if empty($is_profile_domain)}style="max-width: 1200px"{/if}>
		<div class="row">
			<div class="col-xxl-10 mx-auto">
				<section class="section_banner relative">
					<div class="d-flex justify-content-between align-items-center position-absolute w-100 p-2">				
						{if $action eq 'preview'}
							<a href="{$clsISO->getLink('edit_profile')}" class="btn_back"><i class='bx bxs-share'></i></a>	
							<button class="btn btn-primary" type="button" onClick="$Core.broker.save_template(this,event)" template_id="{$template}">Lưu mẫu</button>
						{else}
							<a href="{$clsISO->getLink('broker')}" class="btn_back"><i class='bx bxs-share'></i></a>
						{/if}
					</div>
					{if $deviceType eq 'phone'}
						<img class="w-100 rounded-top" src="{$more_information.banner}" onerror="this.src='{$URL_IMAGES}/banner_default.jpg'" alt="Banner" height="200"> 
					{else}
						<img class="w-100 rounded-top" src="{$more_information.banner}" onerror="this.src='{$URL_IMAGES}/banner_default.jpg'" alt="Banner" height="300"> 
					{/if}
				</section>
				<div class="box_content">
					<div class="box_content_top rounded-bottom d-flex flex-wrap justify-content-between p-3 pb-4 mb-3">
						<div class="box_left_head d-flex flex-wrap relative">
							<div class="box_image">
								<img class="rounded-circle" src="{$clsProfile->getAvatar($oneItem.profile_id,$oneItem,150,150)}" height="150" width="150" alt="User avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'" style="object-fit: cover">
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
									{if !empty($more_information.level_sales)}<p class="title text-main2 mb-2">{$more_information.level_sales}</p>{else}<p class="title text-main2 mb-2">Chuyên gia tư vấn bất động sản</p>{/if}
								</div>	
							</div>
						</div>
						<div class="box_right_head d-flex flex-wrap justify-content-end">
							<div class="d-flex flex-wrap">
								<div class="me-2"><a href="mailto:{$oneItem.phone}" class="btn btn-outline-primary"><i class="bx bx-phone fs-5 me-2 align-text-bottom"></i>{$clsClassTable->mask($oneItem.phone,1)}</a></div>
								<div class="me-2"><a href="mailto:{$oneItem.email}" class="btn btn-outline-primary"><i class="bx bx-envelope fs-5 me-2 align-text-bottom"></i>Email</a></div>
								{if $action eq 'preview' || $oneItem.profile_id ne $profile_id}
									<a class="btn btn-icon btn-outline-primary" href="javascript:void(0)" onMouseMove="$Core.broker.setTooltip(this,'Sao chép link')" onclick="$Core.broker.copyToClipboard(this, event)" title="Sao chép link" data-link="{$DOMAIN_URL}{$clsProfile->getLink($oneItem.profile_id,$oneItem)}" ><i class="bx bx-link mr-1"></i></a>
								{else}
									<div class="btn-group">
										<button class="btn btn-primary btn-icon dropdown-toggle hide-arrow" type="button" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true"><i class="bx bx-dots-vertical-rounded"></i></button>
										<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuClickableInside">
											{if $oneItem.profile_id eq $profile_id}
												<li><a class="dropdown-item" href="/MOC/profile/me/"><i class="bx bx-user mr-1"></i>Quản lý tài khoản</a></li>
											{/if}
											<li><a class="dropdown-item" href="javascript:void(0)" onclick="$Core.broker.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="" data-bs-trigger="click" data-link="{$DOMAIN_URL}{$clsProfile->getLink($oneItem.profile_id,$oneItem)}"  data-bs-original-title="Đã sao chép link" aria-label="Đã sao chép link"><i class="bx bx-link mr-1"></i>Sao chép link</a></li>
										</ul>
									</div>
								{/if}
							</div>
						</div>	
					</div>
				</div>	
				<div class="row">
					<!-- Customer-detail Sidebar -->
					<div class="col-xxl-4 col-lg-5 mb-2">
						<div class="sticky" style="top:70px">
							{if !empty($more_information.image)}
								<div class="mb-2 position-relative rounded-3 overflow-hidden">
									{foreach from=$more_information.image item=image name=i}
										{if $smarty.foreach.i.first}
											<div class="rounded-3 overflow-hidden img_scale" data-fancybox="image" href="{$image}"><img src="{$image}" alt="" class="w-100" width="200" height="300"></div>
										{/if} 
										<div class="image_thumb {if $smarty.foreach.i.index gt 0}d-none{/if}" data-fancybox="image" href="{$image}" style="background-image: url('{$image}')">
											{if $smarty.foreach.i.first && $more_information.image|@count gt 1}
												<span class="number_image text-white fs-14">+{$more_information.image|@count - 2} hình ảnh</span>
											{/if}
										</div>
									{/foreach}
								</div>
							{/if}
							<!-- Customer-detail Card -->
							<div class="card mb-2 no-shadow">
								<div class="card-body">
									<div class="info-container">
										<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-0 fst-italic">Thông tin</h2>
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
									</div>
								</div>
							</div>
							<div class="card mb-2 no-shadow">
								<div class="card-body">
									<div class="info-container">
										{if !empty($more_information.facebook) || !empty($more_information.twitter) || !empty($more_information.linkedin) || !empty($more_information.twitter) }
										<div class="share">
											<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-0 fst-italic">Follow us</h2>
											<ul class="list-unstyled mt-3 mb-0">
												{if !empty($more_information.facebook)}
													<li class="d-flex align-items-center mb-2">
														<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.facebook}" rel="nofollow,noindex">
														<i class='bx bxl-facebook-circle fs-5 mr-2'></i>{$more_information.facebook}</a>
													</li>
												{/if}
												{if !empty($more_information.instagram)}
													<li class="d-flex align-items-center mb-2">
														<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.instagram}" rel="nofollow,noindex" >
														<i class="bx bxl-instagram fs-5 mr-2"></i>{$more_information.instagram}</a>
													</li>
												{/if}
												{if !empty($more_information.linkedin)}
													<li class="d-flex align-items-center mb-2">
														<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.linkedin}" rel="nofollow,noindex" >
														<i class="bx bxl-linkedin fs-5 mr-2"></i>{$more_information.linkedin}</a>
													</li>
												{/if}
												{if !empty($more_information.twitter)}
													<li class="d-flex align-items-center mb-2">
														<a class="px-1 py-0 d-flex align-items-center" target="_blank" href="{$more_information.twitter}" rel="nofollow,noindex" >
														<i class="bx bxl-twitter fs-5 mr-2"></i>{$more_information.twitter}</a>
													</li>
												{/if}
											</ul>
										</div>
										{/if}
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/ Customer Sidebar -->
					<!-- Customer Content -->
					<div class="col-xxl-8 col-lg-7">
						<div class="box_content_broker mb-2">
							<div class="" id="pills-about">
								{if $more_information.about} 
								<div class="card mb-2 no-shadow">
									<div class="card-body">
										<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-3 fst-italic">Giới thiệu</h2>
										<div class="tinymce_content content_about fs-5">
											{$more_information.about|html_entity_decode} 
										</div>				
									</div>
								</div>	
								{/if}
								{if $more_information.certificate}
									<section class="section_certificate card mb-2 no-shadow">							
										<div class="card-body">
											<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-3 fst-italic">Bằng cấp, chứng chỉ</h2>
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
								{if $more_information.project}
									<section class="section_certificate card mb-2 no-shadow">							
										<div class="card-body">
											<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-3 fst-italic">Dự án tiêu biểu</h2>
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
								{if !empty($more_information.working_process)} 
									{assign var=check_active value=1}
									<div class="mb-2" id="pills-working_process" role="tabpanel" aria-labelledby="pills-working_process-tab">
										<section class="section_timeline no-shadow">
											<div class="card no-shadow">
												<div class="card-body">		
													<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-3 fst-italic">Quá trình làm việc</h2>
													<div class="">
														<ul class="timeline mb-0 list-unstyled">
															{foreach from=$more_information.working_process|@array_reverse key=key item=item name=i}		
															<li class="timeline-item timeline-item-transparent position-relative mb-4">
																{if $smarty.foreach.i.first}
																	<img src="{$clsISO->getGoogleUrl($item.image,80)}" alt="" width="80" height="50" onerror="this.src='{$URL_IMAGES}/no-image.png'" class="position-absolute h-auto img-cover" loading="lazy">
																{/if}
																<div class="timeline-event">
																	<div class="timeline-header mb-2">
																		<h6 class="mb-0 text-dark {if $smarty.foreach.i.first}fw-bold{else}fw-semibold{/if}">{$item.company_name}</h6>
																	</div>
																	<p class="mb-1 text-black">{$item.position}</p>
																	<p class="mb-0 text-muted">{$item.time}</p>
																</div>
															</li>
															{/foreach}
														</ul>
													</div>
												</div>	
											</div>
										</section>
									</div>
								{/if}
								{if !empty($image_billing)}
									<div class="card mb-2 no-shadow">
										<div class="card-body">
											<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-3 fst-italic">Danh sách giao dịch</h2>
											<div class="owl owl-carousel" data-lg-slide="2" data-md-slide="2" data-sm-slide="2" data-xs-slide="1" data-loop="true" data-margin="20" data-nav="true">
												{section loop=$image_billing name=i max=6}
													<div class="item mb-2" data-fancybox="gallery_billing" href="{$image_billing[i]}">
														<div class="rounded img_scale">
															<img class="drag-item cursor-pointer w-100 h-auto" src="{$image_billing[i]}" alt="Giao dịch" width="200" height="400">
														</div>
													</div>
												{/section}
											</div>
										</div>
									</div>
								{/if}
								{if !empty($lstPostMeta)}
									<div class="card no-shadow share mb-2" id="pills-share-sale"> 
										{assign var=check_active value=1}	
										<section class="section_post">
											<div class="card-body">	
												<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-3 fst-italic">Bài viết chia sẻ</h2>
												<div class="lst_post">
													{foreach from=$lstPostMeta item=oneMeta}
														<div class="item_post overflow-hidden border p-3 rounded-3 mb-2">
															<div class="header_post d-flex gap-2 align-items-center mb-3">
																<div class="avatar-md rounded-3 overflow-hidden"><img src="{$oneItem.avatar}" alt="" class="w-100 h-100 img-cover" width="60" height="60"></div>
																<div class="d-flex flex-column">
																	<span class="text-dark fw-bold fs-16">{$oneItem.full_name}</span>
																	<span class="text-muted fs-12">{$clsISO->getTimeAgo($oneMeta.upd_date)}</span>
																</div>
															</div>
															<div class="body_post">
																<h3 class="title_post text-dark fs-5">{$oneMeta.title}</h3>
																<div class="content_post mb-3" content="{$oneMeta.content|html_entity_decode}">
																	{$clsISO->truncateWord($oneMeta.content, 50,'... <a href="javascript:void(0)" onClick="$Core.broker.view_more(this,event)">Xem thêm</a>')}
																</div>
																{if !empty($oneMeta.image)}
																	<div class="image">
																		<img width="508" height="267" src="{$oneMeta.image}" alt="" class="img_customer w-100 h-auto rounded-3" onerror="this.src='{$URL_IMAGES}/no-image.png'">
																	</div>
																{/if}
															</div>
														</div>
													{/foreach}
												</div>
											</div>
										</section>
									</div>
								{/if}
								{if !empty($lstShare)}
									<div class="card mb-2 no-shadow">									
										<div class="card-body">
											<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-3 fst-italic">Hoạt động tiếp khách</h2>
											<div class="owl owl-carousel owl_share" data-lg-slide="3" data-md-slide="2" data-sm-slide="2" data-xs-slide="1" data-loop="true" data-margin="10" data-nav="true">
												{foreach from=$lstShare item=oneShare}
													<div class="item h-100">
														<div class="rounded img_scale position-relative h-100" data-fancybox="gallery1_share" href="{$FH_URL}{$oneShare.avatar}" data-caption="{$oneShare.title}">
															<img class="w-100 img-cover h-100" src="{$FH_URL}{$oneShare.avatar}" alt="" width="200" height="300">
															<div class="position-absolute bottom-0 left-0 py-2 px-3 w-100" style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 0.9) 100%)">
																<h3 class="text-white mb-0 fs-14">{$oneShare.title}</h3>
															</div>
														</div>
														{if !empty($oneShare.lstImage)}
															{foreach from=$oneShare.lstImage item=image}
																<a class="d-none"  data-fancybox="gallery1_share" href="{$FH_URL}{$image}" data-caption="{$oneShare.title}"></a>
															{/foreach}
														{/if}
													</div>
												{/foreach}
											</div>
										</div>
									</div>
								{/if}
								
								{if $more_information.link_video ne ''}
								<div class="card no-shadow">
									<div class="card-body">
										<h2 class="text-upper rounded-pill bg-warning px-3 py-2 w-auto d-inline-block fw-bold text-dark box fs-6 mb-3 fst-italic">Video</h2>
										<div class="box_body_video rounded">
											{$clsISO->getEmbedVideo($more_information.link_video)}
										</div>
									</div>
								</div>	
								{/if}
							</div>	
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
{/if}