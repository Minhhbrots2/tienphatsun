<link rel="stylesheet" type="text/css" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$upd_version}" media="all" />
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/html2canvas.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.js?v={$upd_version}"></script>
{assign var = more_information value= $oneProfile.more_information}
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-2 mb-3">
            <span class="text-muted fw-light">Hồ sơ cá nhân</span>
        </h4>
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner relative">
                        <img src="{$more_information.banner}" alt="Banner image" class="rounded-top" id="image_banner" onerror="this.src='{$URL_IMAGES}/profile-banner-default.png'">
						<input type="file" name="avatar" value="" hidden id="banner">
						<button class="btn_upload_avatar " onclick="$Core.broker.uploadImage(this,event);" data-type="banner" toId="banner" toImg="image_banner" profile_id="{$oneProfile.profile_id}"><i class='bx bx-camera'></i></button> 
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="box_avatar flex-shrink-0 mt-n2 mx-sm-0 ms-sm-4 mx-auto">
                            <img src="{$FH_URL}/{$oneProfile.avatar}" alt="user image" class="d-block h-auto ms-0 rounded user-profile-img" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'" id="image_avatar">
							<input type="file" name="avatar" value="" hidden id="avatar">
							<button class="btn_upload_avatar d-none" onclick="$Core.broker.uploadImage(this,event);" data-type="avatar" toId="avatar" toImg="image_avatar" profile_id="{$oneProfile.profile_id}"><i class='bx bx-camera'></i></button>
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>{$oneProfile.full_name}</h4>
                                    <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                        <li class="list-inline-item fw-medium">
                                            <a href="{$clsISO->getLink('pricing')}"><i class="bx bx-pen"></i>
											{if $oneProfile.profile_type eq '_user'}
												{$clsProperty->getTitle($oneProfile.role_sale_id)}
											{else}
												{$clsProperty->getTitle($oneProfile.role_id)}
											{/if}</a>
                                        </li>
										{if $txt_address ne ""}
                                        <li class="list-inline-item fw-medium">
                                            <i class="bx bx-map"></i> {$txt_address}
                                        </li>
										{/if}
                                        <li class="list-inline-item fw-medium">
                                            <i class="bx bx-calendar-alt"></i> {$clsISO->formatTimeDate($oneProfile.reg_date)}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->
        <!-- Navbar pills -->
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                    <li class="nav-item">
						<button class="btn nav-link active" id="pills-Profile-tab" data-bs-toggle="pill" data-bs-target="#pills-Profile" type="button" role="tab" aria-controls="pills-Profile" aria-selected="true"><i class="bx bx-user me-1"></i>Thông tin cá nhân</button>
					</li>
                    <li class="nav-item" style="display: none">
						<button class="btn nav-link" id="pills-Teams-tab" data-bs-toggle="pill" data-bs-target="#pills-Teams" type="button" role="tab" aria-controls="pills-Teams" aria-selected="false"><i class="bx bx-group me-1"></i>Teams</button>
					</li>
                    <li class="nav-item" style="display: none">
						<button class="btn nav-link" id="pills-Projects-tab" data-bs-toggle="pill" data-bs-target="#pills-Projects" type="button" role="tab" aria-controls="pills-Projects" aria-selected="false"><i class="bx bx-grid-alt me-1"></i> Projects</button>
					</li>
                    <li class="nav-item" style="display: none">
						<button class="btn nav-link" id="pills-Connections-tab" data-bs-toggle="pill" data-bs-target="#pills-Connections" type="button" role="tab" aria-controls="pills-Connections" aria-selected="false"><i class="bx bx-link-alt me-1"></i> Connections</button>
					</li>
                </ul>
            </div>
        </div>
        <!--/ Navbar pills -->
        <!-- User Profile Content -->
		<div class="box_content_broker mb-4">
			<div class="tab-pane fade show active" id="pills-Profile" role="tabpanel" aria-labelledby="pills-Profile-tab">
				<div class="row">
					<div class="col-xl-4 col-lg-5 col-md-5">
						<!-- About User -->
						<div class="card mb-4">
							<div class="card-body">
								<small class="text-muted text-uppercase">Thông tin</small>
								<ul class="list-unstyled mb-4 mt-3">
									<li class="d-flex align-items-center mb-3">
										<i class="bx bx-user fs-5"></i><span class="fw-medium mx-2">Họ và tên:</span> 
										<span class="InputCRMHandler">
											{$oneProfile.full_name}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'full_name', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="full_name" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span>
									</li>
									{*<li class="d-flex align-items-center mb-3"><i class="bx bx-check fs-5"></i><span class="fw-medium mx-2">Trạng thái:</span> <span>{if $oneProfile.is_verified eq 1}Đã kích hoạt{else}Chưa kích hoạt{/if}</span></li>
									<li class="d-flex align-items-center mb-3"><i class="bx bx-star fs-5"></i><span class="fw-medium mx-2">Chức danh:</span> <span>{$clsProperty->getTitle($oneProfile.role_id)}</span></li>*}
									<li class="d-flex align-items-center mb-3"><i class="bx bx-flag fs-5"></i><span class="fw-medium mx-2">Địa chỉ:</span> 
										<span class="InputCRMHandler">
											{$oneProfile.address}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'address', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="address" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span>
									</li> 
									<li class="d-flex align-items-center mb-3"><i class="bx bx-detail fs-5"></i><span class="fw-medium mx-2">Ngôn ngữ:</span> <span>Tiếng Việt</span></li>
									<li class="d-flex align-items-center mb-3">
										<i class="bx bx-flag fs-5"></i><span class="fw-medium mx-2">Số giao dịch thành công:</span> 
										<span class="InputCRMHandler">
											{$more_information.number_sale}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'number_sale', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="number_sale" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span>
									</li> 
									<li class="d-flex align-items-center mb-3">
										<i class="bx bx-flag fs-5"></i><span class="fw-medium mx-2">Doanh số bán hàng:</span> 
										<span class="InputCRMHandler">
											{$more_information.total_sales}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'total_sales', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="total_sales" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span>
									</li> 
								</ul>
								<small class="text-muted text-uppercase">Liên hệ</small>
								<ul class="list-unstyled mb-4 mt-3">
									<li class="d-flex align-items-center mb-3"><i class="bx bx-phone fs-5"></i><span class="fw-medium mx-2">Điện thoại:</span> 
										<span class="InputCRMHandler">
											{$oneProfile.phone}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'phone', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="phone" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span>
									</li>
									<li class="d-flex align-items-center mb-3"><i class="bx bx-envelope fs-5" style="color: #a22940"></i><span class="fw-medium mx-2">Email:</span> 
										<span class="InputCRMHandler">
											{$oneProfile.email}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'email', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="email" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>  
										</span>
									</li> 
									<li class="d-flex align-items-center mb-3"><i class='bx bxl-linkedin-square fs-5' style="color:#0270ad"></i><span class="fw-medium mx-2">LinkedIn:</span> 
										<span class="InputCRMHandler">
											{$more_information.linkedin}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'linkedin', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="linkedin" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span>
									</li>
									<li class="d-flex align-items-center mb-3"><i class='bx bxl-instagram fs-5' style="padding: 0.01rem;background: #f09433;background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);background: -webkit-linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);color: #fff"></i><span class="fw-medium mx-2">Instagram:</span> 
										<span class="InputCRMHandler">
											{$more_information.instagram}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'instagram', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="instagram" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span> 
									</li>
									<li class="d-flex align-items-center mb-3"><i class='bx bxl-facebook-circle fs-5' style="color:#0863f7"></i><span class="fw-medium mx-2">Facebook:</span> 
										<span class="InputCRMHandler">
											{$more_information.facebook}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'facebook', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="facebook" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span>
									</li>
									<li class="d-flex align-items-center mb-3"><i class='bx bxl-twitter fs-5' style="color:#2593e9"></i><span class="fw-medium mx-2">Twitter:</span> 
										<span class="InputCRMHandler">
											{$more_information.twitter}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'twitter', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="twitter" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span>
									</li>
								</ul>
							</div>
						</div>
						<!--/ About User -->
						<div class="card mb-4">
							<div class="card-header d-flex justify-content-between align-items-center">
								<h5 class="mb-0">Images</h5>
								<div class="box_upload_image">
									<form action="" method="post" enctype="multipart/form-data">
										<input type="file" name="images[]" multiple hidden id="images">
										<button type="button" class="btn btn-primary text-nowrap" onclick="$Core.broker.uploadImage(this,event);" toId="images" toImg="list_image" data-type="images" profile_id="{$oneProfile.profile_id}">
											<i class='bx bx-image-add' ></i>Upload
										</button>
									</form>
								</div>
								
							</div>
							
							<div class="card-body">
								<div class="form-row row" id="list_image">
									{if !empty($more_information.image)}
										{foreach from=$more_information.image item=image}
											<div class="item col-3 mb-2" data-fancybox="gallery" href="{$image}">
												<img class="rounded drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;height: auto">
											</div>
										{/foreach}
									{else}
										Thư viện trống
									{/if}									
								</div>
							</div>
						</div>
						<div class="card mb-4">
							<h5 class="card-header">Video</h5>
							<div class="card-body">
								<div id="DataTables_Table_0_filter" class="dataTables_filter mb-2">
									<div class="input-group box_form">
										<input type="text" class="form-control" name="link_video" placeholder="Link video" value="{$more_information.link_video}" aria-label="Search" aria-describedby="button-addon2" style=" width: calc(100% - 97px);margin: 0"> 
										<button class="btn btn-outline-primary" type="button" onClick="$Core.broker.addVideo(this,{$oneProfile.profile_id})">Thay đổi</button>  
									</div>
								</div>								
								<div class="box_body_video rounded" id="box_video">					
									{if $more_information.link_video ne ''}
										{$clsISO->getEmbedVideo($more_information.link_video)}
									{else}
										Chưa có video
									{/if}
									
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-8 col-lg-7 col-md-7">
						<!-- Activity Timeline -->
						<div class="card card-action mb-4">
							<div class="card-body">
								<h5 class="card-title">Giới thiệu <a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'about',p_element:'textarea', 'p_id':{$oneProfile.profile_id}{rdelim})" p_element="textarea" p_field="about" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> </h5>  
								<div class="tinymce_content content_about">
									{$more_information.about|html_entity_decode}
								</div>				
							</div>
						</div>
						<div class="card card-action mb-4">
							<div class="card-body">
								<h5 class="card-title">Bằng cấp, chứng chỉ<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'certificate',p_element:'textarea', 'p_id':{$oneProfile.profile_id}{rdelim})" p_element="textarea" p_field="certificate" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> </h5>  
								<div class="tinymce_content content_about">
									{$more_information.certificate|html_entity_decode}
								</div>				
							</div>
						</div>
						<div class="card card-action mb-4">
							<div class="card-body">
								<h5 class="card-title">Châm ngôn sống<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'dictum_live',p_element:'textarea', 'p_id':{$oneProfile.profile_id}{rdelim})" p_element="textarea" p_field="dictum_live" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> </h5>  
								<div class="tinymce_content content_about">
									{$more_information.dictum_live|html_entity_decode}
								</div>				
							</div>
						</div>
						<div class="card card-action mb-4">
							<div class="card-body">
								<h5 class="card-title">Dự án tham gia<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'project_joined',p_element:'textarea', 'p_id':{$oneProfile.profile_id}{rdelim})" p_element="textarea" p_field="project_joined" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> </h5>  
								<div class="tinymce_content content_about">
									{$more_information.project_joined|html_entity_decode}
								</div>				
							</div>
						</div>
						<div class="card card-action mb-4 d-none">
							<div class="card-body">
								<h5 class="card-title">Quá trình làm việc<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'work_process',p_element:'textarea', 'p_id':{$oneProfile.profile_id}{rdelim})" p_element="textarea" p_field="work_process" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> </h5>  
								<div class="tinymce_content content_about">
									{$more_information.work_process|html_entity_decode}
								</div>				
							</div>
						</div>
						<div class="card card-action mb-4" style="display: none">
							<div class="card-header align-items-center">
								<h5 class="card-action-title mb-0"><i class="bx bx-list-ul me-2"></i>Activity Timeline</h5>
								<div class="card-action-element">
									<div class="dropdown">
										<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
										<ul class="dropdown-menu dropdown-menu-end">
											<li><a class="dropdown-item" href="javascript:void(0);">Share timeline</a></li>
											<li><a class="dropdown-item" href="javascript:void(0);">Suggest edits</a></li>
											<li>
												<hr class="dropdown-divider">
											</li>
											<li><a class="dropdown-item" href="javascript:void(0);">Report bug</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="card-body">
								<ul class="timeline ms-2">
									<li class="timeline-item timeline-item-transparent">
										<span class="timeline-point-wrapper"><span class="timeline-point timeline-point-warning"></span></span>
										<div class="timeline-event">
											<div class="timeline-header mb-1">
												<h6 class="mb-0">Client Meeting</h6>
												<small class="text-muted">Today</small>
											</div>
											<p class="mb-2">Project meeting with john @10:15am</p>
											<div class="d-flex flex-wrap">
												<div class="avatar me-3">
													<img src="../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
												</div>
												<div>
													<h6 class="mb-0">Lester McCarthy (Client)</h6>
													<span>CEO of Infibeam</span>
												</div>
											</div>
										</div>
									</li>
									<li class="timeline-item timeline-item-transparent">
										<span class="timeline-point-wrapper"><span class="timeline-point timeline-point-info"></span></span>
										<div class="timeline-event">
											<div class="timeline-header mb-1">
												<h6 class="mb-0">Create a new project for client</h6>
												<small class="text-muted">2 Day Ago</small>
											</div>
											<p class="mb-0">Add files to new design folder</p>
										</div>
									</li>
									<li class="timeline-item timeline-item-transparent">
										<span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
										<div class="timeline-event">
											<div class="timeline-header mb-1">
												<h6 class="mb-0">Shared 2 New Project Files</h6>
												<small class="text-muted">6 Day Ago</small>
											</div>
											<p class="mb-2">Sent by Mollie Dixon <img src="../../assets/img/avatars/4.png" class="rounded-circle ms-3" alt="avatar" height="20" width="20" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'"></p>
											<div class="d-flex flex-wrap gap-2">
												<a href="javascript:void(0)" class="me-3">
												<img src="{$URL_IMAGES}/icons/broker/pdf.png" alt="Document image" width="20" class="me-2">
												<span class="h6">App Guidelines</span>
												</a>
												<a href="javascript:void(0)">
												<img src="{$URL_IMAGES}/icons/broker/doc.png" alt="Excel image" width="20" class="me-2">
												<span class="h6">Testing Results</span>
												</a>
											</div>
										</div>
									</li>
									<li class="timeline-item timeline-item-transparent">
										<span class="timeline-point-wrapper"><span class="timeline-point timeline-point-success"></span></span>
										<div class="timeline-event pb-0">
											<div class="timeline-header mb-1">
												<h6 class="mb-0">Project status updated</h6>
												<small class="text-muted">10 Day Ago</small>
											</div>
											<p class="mb-0">Woocommerce iOS App Completed</p>
										</div>
									</li>
									<li class="timeline-end-indicator">
										<i class="bx bx-check-circle"></i>
									</li>
								</ul>
							</div>
						</div>
						<!--/ Activity Timeline -->
						<div class="row" style="display: none">
							<!-- Connections -->
							<div class="col-lg-12 col-xl-6">
								<div class="card card-action mb-4">
									<div class="card-header align-items-center">
										<h5 class="card-action-title mb-0">Connections</h5>
										<div class="card-action-element">
											<div class="dropdown">
												<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
												<ul class="dropdown-menu dropdown-menu-end">
													<li><a class="dropdown-item" href="javascript:void(0);">Share connections</a></li>
													<li><a class="dropdown-item" href="javascript:void(0);">Suggest edits</a></li>
													<li>
														<hr class="dropdown-divider">
													</li>
													<li><a class="dropdown-item" href="javascript:void(0);">Report bug</a></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="card-body">
										<ul class="list-unstyled mb-0">
											<li class="mb-3">
												<div class="d-flex align-items-start">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/avatars/2.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-2">
															<h6 class="mb-0">Cecilia Payne</h6>
															<small class="text-muted">45 Connections</small>
														</div>
													</div>
													<div class="ms-auto">
														<button class="btn btn-label-primary btn-icon btn-sm"><i class="bx bx-user"></i></button>
													</div>
												</div>
											</li>
											<li class="mb-3">
												<div class="d-flex align-items-start">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-2">
															<h6 class="mb-0">Curtis Fletcher</h6>
															<small class="text-muted">1.32k Connections</small>
														</div>
													</div>
													<div class="ms-auto">
														<button class="btn btn-primary btn-icon btn-sm"><i class="bx bx-user"></i></button>
													</div>
												</div>
											</li>
											<li class="mb-3">
												<div class="d-flex align-items-start">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/avatars/10.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-2">
															<h6 class="mb-0">Alice Stone</h6>
															<small class="text-muted">125 Connections</small>
														</div>
													</div>
													<div class="ms-auto">
														<button class="btn btn-primary btn-icon btn-sm"><i class="bx bx-user"></i></button>
													</div>
												</div>
											</li>
											<li class="mb-3">
												<div class="d-flex align-items-start">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/avatars/7.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-2">
															<h6 class="mb-0">Darrell Barnes</h6>
															<small class="text-muted">456 Connections</small>
														</div>
													</div>
													<div class="ms-auto">
														<button class="btn btn-label-primary btn-icon btn-sm"><i class="bx bx-user"></i></button>
													</div>
												</div>
											</li>
											<li class="mb-3">
												<div class="d-flex align-items-start">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/avatars/12.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-2">
															<h6 class="mb-0">Eugenia Moore</h6>
															<small class="text-muted">1.2k Connections</small>
														</div>
													</div>
													<div class="ms-auto">
														<button class="btn btn-label-primary btn-icon btn-sm"><i class="bx bx-user"></i></button>
													</div>
												</div>
											</li>
											<li class="text-center">
												<a href="javascript:;">View all connections</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<!--/ Connections -->
							<!-- Teams -->
							<div class="col-lg-12 col-xl-6"  style="display: none">
								<div class="card card-action mb-4">
									<div class="card-header align-items-center">
										<h5 class="card-action-title mb-0">Teams</h5>
										<div class="card-action-element">
											<div class="dropdown">
												<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
												<ul class="dropdown-menu dropdown-menu-end">
													<li><a class="dropdown-item" href="javascript:void(0);">Share teams</a></li>
													<li><a class="dropdown-item" href="javascript:void(0);">Suggest edits</a></li>
													<li>
														<hr class="dropdown-divider">
													</li>
													<li><a class="dropdown-item" href="javascript:void(0);">Report bug</a></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="card-body">
										<ul class="list-unstyled mb-0">
											<li class="mb-3">
												<div class="d-flex align-items-center">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/icons/brands/react-label.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-2">
															<h6 class="mb-0">React Developers</h6>
															<small class="text-muted">72 Members</small>
														</div>
													</div>
													<div class="ms-auto">
														<a href="javascript:;"><span class="badge bg-label-danger">Developer</span></a>
													</div>
												</div>
											</li>
											<li class="mb-3">
												<div class="d-flex align-items-center">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/icons/brands/support-label.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-2">
															<h6 class="mb-0">Support Team</h6>
															<small class="text-muted">122 Members</small>
														</div>
													</div>
													<div class="ms-auto">
														<a href="javascript:;"><span class="badge bg-label-primary">Support</span></a>
													</div>
												</div>
											</li>
											<li class="mb-3">
												<div class="d-flex align-items-center">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/icons/brands/figma-label.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-2">
															<h6 class="mb-0">UI Designers</h6>
															<small class="text-muted">7 Members</small>
														</div>
													</div>
													<div class="ms-auto">
														<a href="javascript:;"><span class="badge bg-label-info">Designer</span></a>
													</div>
												</div>
											</li>
											<li class="mb-3">
												<div class="d-flex align-items-center">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/icons/brands/vue-label.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-2">
															<h6 class="mb-0">Vue.js Developers</h6>
															<small class="text-muted">289 Members</small>
														</div>
													</div>
													<div class="ms-auto">
														<a href="javascript:;"><span class="badge bg-label-danger">Developer</span></a>
													</div>
												</div>
											</li>
											<li class="mb-3">
												<div class="d-flex align-items-center">
													<div class="d-flex align-items-start">
														<div class="avatar me-3">
															<img src="../../assets/img/icons/brands/twitter-label.png" alt="Avatar" class="rounded-circle">
														</div>
														<div class="me-w">
															<h6 class="mb-0">Digital Marketing</h6>
															<small class="text-muted">24 Members</small>
														</div>
													</div>
													<div class="ms-auto">
														<a href="javascript:;"><span class="badge bg-label-secondary">Marketing</span></a>
													</div>
												</div>
											</li>
											<li class="text-center">
												<a href="javascript:;">View all teams</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<!--/ Teams -->
						</div>
						<!-- Projects table -->
						<div class="card mb-4"  style="display: none">
							<h5 class="card-header">Projects List</h5>
							<div class="table-responsive mb-3">
								<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
									<div class="d-flex justify-content-between align-items-center flex-column flex-sm-row mx-4 row">
										<div class="col-sm-4 col-12 d-flex align-items-center justify-content-sm-start justify-content-center">
											<div class="dataTables_length" id="DataTables_Table_0_length">
												<label>
													Show 
													<select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
														<option value="7">7</option>
														<option value="10">10</option>
														<option value="25">25</option>
														<option value="50">50</option>
														<option value="75">75</option>
														<option value="100">100</option>
													</select>
												</label>
											</div>
										</div>
										<div class="col-sm-8 col-12 d-flex align-items-center justify-content-sm-end justify-content-center">
											<div id="DataTables_Table_0_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control" placeholder="Search Project" aria-controls="DataTables_Table_0"></label></div>
										</div>
									</div>
									<table class="table datatable-project dataTable no-footer dtr-column" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 918px;">
										<thead class="table-light">
											<tr>
												<th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 0px; display: none;" aria-label=""></th>
												<th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1" colspan="1" style="width: 18px;" data-col="1" aria-label=""><input type="checkbox" class="form-check-input"></th>
												<th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 324px;" aria-label="Project: activate to sort column ascending" aria-sort="descending">Project</th>
												<th class="text-nowrap sorting_disabled" rowspan="1" colspan="1" style="width: 137px;" aria-label="Total Task">Total Task</th>
												<th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 128px;" aria-label="Progress: activate to sort column ascending">Progress</th>
												<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 99px;" aria-label="Hours">Hours</th>
											</tr>
										</thead>
										<tbody>
											<tr class="odd">
												<td class="  control" tabindex="0" style="display: none;"></td>
												<td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td>
												<td class="sorting_1">
													<div class="d-flex justify-content-left align-items-center">
														<div class="avatar-wrapper">
															<div class="avatar avatar-sm me-3"><img src="../../assets/img/icons/brands/vue-label.png" alt="Project Image" class="rounded-circle"></div>
														</div>
														<div class="d-flex flex-column"><span class="text-truncate fw-medium">Vue Admin template</span><small class="text-muted">Vuejs Project</small></div>
													</div>
												</td>
												<td>214/627</td>
												<td>
													<div class="d-flex flex-column">
														<small class="mb-1">78%</small>
														<div class="progress w-100 me-3" style="height: 6px;">
															<div class="progress-bar bg-success" style="width: 78%" aria-valuenow="78%" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
												</td>
												<td>88:19h</td>
											</tr>
											<tr class="even">
												<td class="  control" tabindex="0" style="display: none;"></td>
												<td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td>
												<td class="sorting_1">
													<div class="d-flex justify-content-left align-items-center">
														<div class="avatar-wrapper">
															<div class="avatar avatar-sm me-3"><img src="../../assets/img/icons/brands/event-label.png" alt="Project Image" class="rounded-circle"></div>
														</div>
														<div class="d-flex flex-column"><span class="text-truncate fw-medium">Online Webinar</span><small class="text-muted">Official Event</small></div>
													</div>
												</td>
												<td>12/20</td>
												<td>
													<div class="d-flex flex-column">
														<small class="mb-1">69%</small>
														<div class="progress w-100 me-3" style="height: 6px;">
															<div class="progress-bar bg-info" style="width: 69%" aria-valuenow="69%" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
												</td>
												<td>12:12h</td>
											</tr>
											<tr class="odd">
												<td class="  control" tabindex="0" style="display: none;"></td>
												<td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td>
												<td class="sorting_1">
													<div class="d-flex justify-content-left align-items-center">
														<div class="avatar-wrapper">
															<div class="avatar avatar-sm me-3"><img src="../../assets/img/icons/brands/html-label.png" alt="Project Image" class="rounded-circle"></div>
														</div>
														<div class="d-flex flex-column"><span class="text-truncate fw-medium">Hoffman Website</span><small class="text-muted">HTML Project</small></div>
													</div>
												</td>
												<td>56/183</td>
												<td>
													<div class="d-flex flex-column">
														<small class="mb-1">43%</small>
														<div class="progress w-100 me-3" style="height: 6px;">
															<div class="progress-bar bg-warning" style="width: 43%" aria-valuenow="43%" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
												</td>
												<td>76h</td>
											</tr>
											<tr class="even">
												<td class="  control" tabindex="0" style="display: none;"></td>
												<td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td>
												<td class="sorting_1">
													<div class="d-flex justify-content-left align-items-center">
														<div class="avatar-wrapper">
															<div class="avatar avatar-sm me-3"><img src="../../assets/img/icons/brands/sketch-label.png" alt="Project Image" class="rounded-circle"></div>
														</div>
														<div class="d-flex flex-column"><span class="text-truncate fw-medium">Foodista mobile app</span><small class="text-muted">iPhone Project</small></div>
													</div>
												</td>
												<td>12/86</td>
												<td>
													<div class="d-flex flex-column">
														<small class="mb-1">49%</small>
														<div class="progress w-100 me-3" style="height: 6px;">
															<div class="progress-bar bg-warning" style="width: 49%" aria-valuenow="49%" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
												</td>
												<td>45h</td>
											</tr>
											<tr class="odd">
												<td class="  control" tabindex="0" style="display: none;"></td>
												<td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td>
												<td class="sorting_1">
													<div class="d-flex justify-content-left align-items-center">
														<div class="avatar-wrapper">
															<div class="avatar avatar-sm me-3"><img src="../../assets/img/icons/brands/xd-label.png" alt="Project Image" class="rounded-circle"></div>
														</div>
														<div class="d-flex flex-column"><span class="text-truncate fw-medium">Falcon Logo Design</span><small class="text-muted">UI/UX Project</small></div>
													</div>
												</td>
												<td>9/50</td>
												<td>
													<div class="d-flex flex-column">
														<small class="mb-1">15%</small>
														<div class="progress w-100 me-3" style="height: 6px;">
															<div class="progress-bar bg-danger" style="width: 15%" aria-valuenow="15%" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
												</td>
												<td>89h</td>
											</tr>
											<tr class="even">
												<td class="  control" tabindex="0" style="display: none;"></td>
												<td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td>
												<td class="sorting_1">
													<div class="d-flex justify-content-left align-items-center">
														<div class="avatar-wrapper">
															<div class="avatar avatar-sm me-3"><img src="../../assets/img/icons/brands/react-label.png" alt="Project Image" class="rounded-circle"></div>
														</div>
														<div class="d-flex flex-column"><span class="text-truncate fw-medium">Dojo React Project</span><small class="text-muted">React Project</small></div>
													</div>
												</td>
												<td>234/378</td>
												<td>
													<div class="d-flex flex-column">
														<small class="mb-1">73%</small>
														<div class="progress w-100 me-3" style="height: 6px;">
															<div class="progress-bar bg-info" style="width: 73%" aria-valuenow="73%" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
												</td>
												<td>67:10h</td>
											</tr>
											<tr class="odd">
												<td class="  control" tabindex="0" style="display: none;"></td>
												<td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td>
												<td class="sorting_1">
													<div class="d-flex justify-content-left align-items-center">
														<div class="avatar-wrapper">
															<div class="avatar avatar-sm me-3"><img src="../../assets/img/icons/brands/vue-label.png" alt="Project Image" class="rounded-circle"></div>
														</div>
														<div class="d-flex flex-column"><span class="text-truncate fw-medium">Dashboard Design</span><small class="text-muted">Vuejs Project</small></div>
													</div>
												</td>
												<td>100/190</td>
												<td>
													<div class="d-flex flex-column">
														<small class="mb-1">90%</small>
														<div class="progress w-100 me-3" style="height: 6px;">
															<div class="progress-bar bg-success" style="width: 90%" aria-valuenow="90%" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
												</td>
												<td>129:45h</td>
											</tr>
										</tbody>
									</table>
									<div class="d-flex justify-content-between mx-4 row">
										<div class="col-sm-12 col-md-6">
											<div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 7 of 11 entries</div>
										</div>
										<div class="col-sm-12 col-md-6">
											<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
												<ul class="pagination">
													<li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous"><a aria-controls="DataTables_Table_0" aria-disabled="true" role="link" data-dt-idx="previous" tabindex="-1" class="page-link">Previous</a></li>
													<li class="paginate_button page-item active"><a href="#" aria-controls="DataTables_Table_0" role="link" aria-current="page" data-dt-idx="0" tabindex="0" class="page-link">1</a></li>
													<li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" role="link" data-dt-idx="1" tabindex="0" class="page-link">2</a></li>
													<li class="paginate_button page-item next" id="DataTables_Table_0_next"><a href="#" aria-controls="DataTables_Table_0" role="link" data-dt-idx="next" tabindex="0" class="page-link">Next</a></li>
												</ul>
											</div>
										</div>
									</div>
									<div style="width: 1%;"></div>
								</div>
							</div>
						</div>
						<!--/ Projects table -->
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="pills-Teams" role="tabpanel" aria-labelledby="pills-Teams-tab">
				<div class="row g-4">
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center mb-3">
									<a href="javascript:;" class="d-flex align-items-center">
										<div class="avatar avatar-sm me-2">
											<img src="../../assets/img/icons/brands/react-label.png" alt="Avatar" class="rounded-circle" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
										</div>
										<div class="me-2 text-body h5 mb-0">
											React Developers
										</div>
									</a>
									<div class="ms-auto">
										<ul class="list-inline mb-0 d-flex align-items-center">
											<li class="list-inline-item me-0"><a href="javascript:void(0);" class="d-flex align-self-center text-body"><i class="bx bx-star"></i></a></li>
											<li class="list-inline-item">
												<div class="dropdown">
													<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
													<ul class="dropdown-menu dropdown-menu-end">
														<li><a class="dropdown-item" href="javascript:void(0);">Rename Team</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">View Details</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete Team</a></li>
													</ul>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<p>We don’t make assumptions about the rest of your technology stack, so you can develop new features in React.</p>
								<div class="d-flex align-items-center flex-wrap">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Vinnie Mostowy" data-bs-original-title="Vinnie Mostowy">
												<img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Allen Rieske" data-bs-original-title="Allen Rieske">
												<img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Julee Rossignol" data-bs-original-title="Julee Rossignol">
												<img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="George Burrill" data-bs-original-title="George Burrill">
												<img class="rounded-circle" src="../../assets/img/avatars/7.png" alt="Avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li>
												<small class="text-muted ms-1">+254</small>
											</li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:;" class="me-2"><span class="badge bg-label-primary">React</span></a>
										<a href="javascript:;"><span class="badge bg-label-warning">Vue.JS</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center mb-3">
									<a href="javascript:;" class="d-flex align-items-center">
										<div class="avatar avatar-sm me-2">
											<img src="../../assets/img/icons/brands/vue-label.png" alt="Avatar" class="rounded-circle"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
										</div>
										<div class="me-2 text-body h5 mb-0">
											Vue.js Dev Team
										</div>
									</a>
									<div class="ms-auto">
										<ul class="list-inline mb-0 d-flex align-items-center">
											<li class="list-inline-item me-0"><a href="javascript:void(0);" class="d-flex align-self-center text-body"><i class="bx bx-star"></i></a></li>
											<li class="list-inline-item">
												<div class="dropdown">
													<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
													<ul class="dropdown-menu dropdown-menu-end">
														<li><a class="dropdown-item" href="javascript:void(0);">Rename Team</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">View Details</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete Team</a></li>
													</ul>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<p>The development of Vue and its ecosystem is guided by an international team, some of whom have chosen to be featured below.</p>
								<div class="d-flex align-items-center flex-wrap">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kaith D'souza" data-bs-original-title="Kaith D'souza">
												<img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="John Doe" data-bs-original-title="John Doe">
												<img class="rounded-circle" src="../../assets/img/avatars/1.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Alan Walker" data-bs-original-title="Alan Walker">
												<img class="rounded-circle" src="../../assets/img/avatars/16.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Calvin Middleton" data-bs-original-title="Calvin Middleton">
												<img class="rounded-circle" src="../../assets/img/avatars/17.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li>
												<small class="text-muted ms-1">+153</small>
											</li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:;"><span class="badge bg-label-danger">Developer</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center mb-3">
									<a href="javascript:;" class="d-flex align-items-center">
										<div class="avatar avatar-sm me-2">
											<img src="../../assets/img/icons/brands/xd-label.png" alt="Avatar" class="rounded-circle"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
										</div>
										<div class="me-2 text-body h5 mb-0">
											Creative Designers
										</div>
									</a>
									<div class="ms-auto">
										<ul class="list-inline mb-0 d-flex align-items-center">
											<li class="list-inline-item me-0"><a href="javascript:void(0);" class="d-flex align-self-center text-body"><i class="bx bx-star"></i></a></li>
											<li class="list-inline-item">
												<div class="dropdown">
													<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
													<ul class="dropdown-menu dropdown-menu-end">
														<li><a class="dropdown-item" href="javascript:void(0);">Rename Team</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">View Details</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete Team</a></li>
													</ul>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<p>A design or product team is more than just the people on it. A team includes the people, the roles they play.</p>
								<div class="d-flex align-items-center flex-wrap">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Jimmy Ressula" data-bs-original-title="Jimmy Ressula">
												<img class="rounded-circle" src="../../assets/img/avatars/4.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kristi Lawker" data-bs-original-title="Kristi Lawker">
												<img class="rounded-circle" src="../../assets/img/avatars/2.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Danny Paul" data-bs-original-title="Danny Paul">
												<img class="rounded-circle" src="../../assets/img/avatars/7.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Alicia Littleton" data-bs-original-title="Alicia Littleton">
												<img class="rounded-circle" src="../../assets/img/avatars/8.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li>
												<small class="text-muted ms-1">+55</small>
											</li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:;" class="me-2"><span class="badge bg-label-warning">Sketch</span></a>
										<a href="javascript:;"><span class="badge bg-label-danger">XD</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center mb-3">
									<a href="javascript:;" class="d-flex align-items-center">
										<div class="avatar avatar-sm me-2">
											<img src="../../assets/img/icons/brands/support-label.png" alt="Avatar" class="rounded-circle"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
										</div>
										<div class="me-2 text-body h5 mb-0">
											Support Team
										</div>
									</a>
									<div class="ms-auto">
										<ul class="list-inline mb-0 d-flex align-items-center">
											<li class="list-inline-item me-0"><a href="javascript:void(0);" class="d-flex align-self-center text-body"><i class="bx bx-star"></i></a></li>
											<li class="list-inline-item">
												<div class="dropdown">
													<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
													<ul class="dropdown-menu dropdown-menu-end">
														<li><a class="dropdown-item" href="javascript:void(0);">Rename Team</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">View Details</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete Team</a></li>
													</ul>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<p>Support your team. Your customer support team is fielding the good, the bad, and the ugly day in and day out.</p>
								<div class="d-flex align-items-center flex-wrap">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Andrew Tye" data-bs-original-title="Andrew Tye">
												<img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Rishi Swaat" data-bs-original-title="Rishi Swaat">
												<img class="rounded-circle" src="../../assets/img/avatars/9.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Rossie Kim" data-bs-original-title="Rossie Kim">
												<img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Mary Hunter" data-bs-original-title="Mary Hunter">
												<img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li>
												<small class="text-muted ms-1">+350</small>
											</li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:;"><span class="badge bg-label-info">Zendesk</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center mb-3">
									<a href="javascript:;" class="d-flex align-items-center">
										<div class="avatar avatar-sm me-2">
											<img src="../../assets/img/icons/brands/social-label.png" alt="Avatar" class="rounded-circle"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
										</div>
										<div class="me-2 text-body h5 mb-0">
											Digital Marketing
										</div>
									</a>
									<div class="ms-auto">
										<ul class="list-inline mb-0 d-flex align-items-center">
											<li class="list-inline-item me-0"><a href="javascript:void(0);" class="d-flex align-self-center text-body"><i class="bx bx-star"></i></a></li>
											<li class="list-inline-item">
												<div class="dropdown">
													<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
													<ul class="dropdown-menu dropdown-menu-end">
														<li><a class="dropdown-item" href="javascript:void(0);">Rename Team</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">View Details</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete Team</a></li>
													</ul>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<p>Digital marketing refers to advertising delivered through digital channels such as search engines, websites…</p>
								<div class="d-flex align-items-center flex-wrap">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kim Merchent" data-bs-original-title="Kim Merchent">
												<img class="rounded-circle" src="../../assets/img/avatars/10.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Sam D'souza" data-bs-original-title="Sam D'souza">
												<img class="rounded-circle" src="../../assets/img/avatars/13.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Nurvi Karlos" data-bs-original-title="Nurvi Karlos">
												<img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Margorie Whitmire" data-bs-original-title="Margorie Whitmire">
												<img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li>
												<small class="text-muted ms-1">+195</small>
											</li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:;" class="me-2"><span class="badge bg-label-primary">Twitter</span></a>
										<a href="javascript:;"><span class="badge bg-label-success">Email</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center mb-3">
									<a href="javascript:;" class="d-flex align-items-center">
										<div class="avatar avatar-sm me-2">
											<img src="../../assets/img/icons/brands/event-label.png" alt="Avatar" class="rounded-circle">
										</div>
										<div class="me-2 text-body h5 mb-0">
											Event
										</div>
									</a>
									<div class="ms-auto">
										<ul class="list-inline mb-0 d-flex align-items-center">
											<li class="list-inline-item me-0"><a href="javascript:void(0);" class="d-flex align-self-center text-body"><i class="bx bx-star"></i></a></li>
											<li class="list-inline-item">
												<div class="dropdown">
													<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
													<ul class="dropdown-menu dropdown-menu-end">
														<li><a class="dropdown-item" href="javascript:void(0);">Rename Team</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">View Details</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete Team</a></li>
													</ul>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<p>Event is defined as a particular contest which is part of a program of contests. An example of an event is the long…</p>
								<div class="d-flex align-items-center flex-wrap">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Vinnie Mostowy" data-bs-original-title="Vinnie Mostowy">
												<img class="rounded-circle" src="../../assets/img/avatars/17.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Allen Rieske" data-bs-original-title="Allen Rieske">
												<img class="rounded-circle" src="../../assets/img/avatars/8.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Julee Rossignol" data-bs-original-title="Julee Rossignol">
												<img class="rounded-circle" src="../../assets/img/avatars/7.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Daniel Long" data-bs-original-title="Daniel Long">
												<img class="rounded-circle" src="../../assets/img/avatars/9.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li><small class="text-muted ms-1">+550</small></li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:;"><span class="badge bg-label-success">Hubilo</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center mb-3">
									<a href="javascript:;" class="d-flex align-items-center">
										<div class="avatar avatar-sm me-2">
											<img src="../../assets/img/icons/brands/figma-label.png" alt="Avatar" class="rounded-circle">
										</div>
										<div class="me-2 text-body h5 mb-0">
											Figma Resources
										</div>
									</a>
									<div class="ms-auto">
										<ul class="list-inline mb-0 d-flex align-items-center">
											<li class="list-inline-item me-0"><a href="javascript:void(0);" class="d-flex align-self-center text-body"><i class="bx bx-star"></i></a></li>
											<li class="list-inline-item">
												<div class="dropdown">
													<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
													<ul class="dropdown-menu dropdown-menu-end">
														<li><a class="dropdown-item" href="javascript:void(0);">Rename Team</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">View Details</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete Team</a></li>
													</ul>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<p>Explore, install, use, and remix thousands of plugins and files published to the Figma Community by designers and developers.</p>
								<div class="d-flex align-items-center flex-wrap">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Andrew Mostowy" data-bs-original-title="Andrew Mostowy">
												<img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Micky Ressula" data-bs-original-title="Micky Ressula">
												<img class="rounded-circle" src="../../assets/img/avatars/1.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Michel Pal" data-bs-original-title="Michel Pal">
												<img class="rounded-circle" src="../../assets/img/avatars/16.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Herman Lockard" data-bs-original-title="Herman Lockard">
												<img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li>
												<small class="text-muted ms-1">+45</small>
											</li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:;" class="me-2"><span class="badge bg-label-success">UI/UX</span></a>
										<a href="javascript:;"><span class="badge bg-label-secondary">Figma</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center mb-3">
									<a href="javascript:;" class="d-flex align-items-center">
										<div class="avatar avatar-sm me-2">
											<img src="../../assets/img/icons/brands/html-label.png" alt="Avatar" class="rounded-circle">
										</div>
										<div class="me-2 text-body h5 mb-0">
											Only Beginners
										</div>
									</a>
									<div class="ms-auto">
										<ul class="list-inline mb-0 d-flex align-items-center">
											<li class="list-inline-item me-0"><a href="javascript:void(0);" class="d-flex align-self-center text-body"><i class="bx bx-star"></i></a></li>
											<li class="list-inline-item">
												<div class="dropdown">
													<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
													<ul class="dropdown-menu dropdown-menu-end">
														<li><a class="dropdown-item" href="javascript:void(0);">Rename Team</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">View Details</a></li>
														<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete Team</a></li>
													</ul>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<p>Learn the basics of how websites work, front-end vs back-end, and using a code editor. Learn basic HTML, CSS, and…</p>
								<div class="d-flex align-items-center flex-wrap">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kim Karlos" data-bs-original-title="Kim Karlos">
												<img class="rounded-circle" src="../../assets/img/avatars/3.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Katy Turner" data-bs-original-title="Katy Turner">
												<img class="rounded-circle" src="../../assets/img/avatars/9.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Peter Adward" data-bs-original-title="Peter Adward">
												<img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Leona Miller" data-bs-original-title="Leona Miller">
												<img class="rounded-circle" src="../../assets/img/avatars/20.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li>
												<small class="text-muted ms-1">+550</small>
											</li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:;" class="me-2"><span class="badge bg-label-info">CSS</span></a>
										<a href="javascript:;"><span class="badge bg-label-warning">HTML</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
        	</div>
			<div class="tab-pane fade" id="pills-Projects" role="tabpanel" aria-labelledby="pills-Projects-tab">
				<div class="row g-4">
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-header">
								<div class="d-flex align-items-start">
									<div class="d-flex align-items-start">
										<div class="avatar me-3">
											<img src="../../assets/img/icons/brands/social-label.png" alt="Avatar" class="rounded-circle">
										</div>
										<div class="me-2">
											<h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Social Banners</a></h5>
											<div class="client-info d-flex align-items-center">
												<h6 class="mb-0 me-1">Client:</h6>
												<span>Christian Jimenez</span>
											</div>
										</div>
									</div>
									<div class="ms-auto">
										<div class="dropdown z-2">
											<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
											<ul class="dropdown-menu dropdown-menu-end">
												<li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
												<li>
													<hr class="dropdown-divider">
												</li>
												<li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave Project</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="d-flex align-items-center flex-wrap">
									<div class="bg-lighter p-2 rounded me-auto mb-3">
										<h6 class="mb-1">$24.8k <span class="text-body fw-normal">/ $18.2k</span></h6>
										<span>Total Budget</span>
									</div>
									<div class="text-end mb-3">
										<h6 class="mb-1">Start Date: <span class="text-body fw-normal">14/2/21</span></h6>
										<h6 class="mb-1">Deadline: <span class="text-body fw-normal">28/2/22</span></h6>
									</div>
								</div>
								<p class="mb-0">We are Consulting, Software Development and Web Development Services.</p>
							</div>
							<div class="card-body border-top">
								<div class="d-flex align-items-center mb-3">
									<h6 class="mb-1">All Hours: <span class="text-body fw-normal">380/244</span></h6>
									<span class="badge bg-label-success ms-auto">28 Days left</span>
								</div>
								<div class="d-flex justify-content-between align-items-center mb-1">
									<small>Task: 290/344</small>
									<small>95% Completed</small>
								</div>
								<div class="progress mb-3" style="height: 8px;">
									<div class="progress-bar" role="progressbar" style="width: 95%;" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Vinnie Mostowy" data-bs-original-title="Vinnie Mostowy">
												<img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Allen Rieske" data-bs-original-title="Allen Rieske">
												<img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up me-2" aria-label="Julee Rossignol" data-bs-original-title="Julee Rossignol">
												<img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li><small class="text-muted">280 Members</small></li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 15</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-header">
								<div class="d-flex align-items-start">
									<div class="d-flex align-items-start">
										<div class="avatar me-3">
											<img src="../../assets/img/icons/brands/react-label.png" alt="Avatar" class="rounded-circle">
										</div>
										<div class="me-2">
											<h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Admin Template</a></h5>
											<div class="client-info d-flex align-items-center">
												<h6 class="mb-0 me-1">Client: </h6>
												<span>Jeffrey Phillips</span>
											</div>
										</div>
									</div>
									<div class="ms-auto">
										<div class="dropdown z-2">
											<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
											<ul class="dropdown-menu dropdown-menu-end">
												<li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
												<li>
													<hr class="dropdown-divider">
												</li>
												<li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave Project</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="d-flex align-items-center flex-wrap">
									<div class="bg-lighter p-2 rounded me-auto mb-3">
										<h6 class="mb-1">$2.4k <span class="text-body fw-normal">/ 1.8k</span></h6>
										<span>Total Budget</span>
									</div>
									<div class="text-end mb-3">
										<h6 class="mb-1">Start Date: <span class="text-body fw-normal">18/8/21</span></h6>
										<h6 class="mb-1">Deadline: <span class="text-body fw-normal">21/6/22</span></h6>
									</div>
								</div>
								<p class="mb-0">Time is our most valuable asset, that's why we want to help you save it by creating…</p>
							</div>
							<div class="card-body border-top">
								<div class="d-flex align-items-center mb-3">
									<h6 class="mb-1">All Hours: <span class="text-body fw-normal">98/135</span></h6>
									<span class="badge bg-label-warning ms-auto">15 Days left</span>
								</div>
								<div class="d-flex justify-content-between align-items-center mb-1">
									<small>Task: 12/90</small>
									<small>42% Completed</small>
								</div>
								<div class="progress mb-3" style="height: 8px;">
									<div class="progress-bar" role="progressbar" style="width: 42%;" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kaith D'souza" data-bs-original-title="Kaith D'souza">
												<img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="John Doe" data-bs-original-title="John Doe">
												<img class="rounded-circle" src="../../assets/img/avatars/1.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up me-2" aria-label="Alan Walker" data-bs-original-title="Alan Walker">
												<img class="rounded-circle" src="../../assets/img/avatars/16.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li><small class="text-muted">1.1k Members</small></li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 236</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-header">
								<div class="d-flex align-items-start">
									<div class="d-flex align-items-start">
										<div class="avatar me-3">
											<img src="../../assets/img/icons/brands/vue-label.png" alt="Avatar" class="rounded-circle">
										</div>
										<div class="me-2">
											<h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">App Design</a></h5>
											<div class="client-info d-flex align-items-center">
												<h6 class="mb-0 me-1">Client: </h6>
												<span>Ricky McDonald</span>
											</div>
										</div>
									</div>
									<div class="ms-auto">
										<div class="dropdown z-2">
											<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
											<ul class="dropdown-menu dropdown-menu-end">
												<li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
												<li>
													<hr class="dropdown-divider">

												</li>
												<li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave Project</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="d-flex align-items-center flex-wrap">
									<div class="bg-lighter p-2 rounded me-auto mb-3">
										<h6 class="mb-1">$980 <span class="text-body fw-normal">/ $420</span></h6>
										<span>Total Budget</span>
									</div>
									<div class="text-end mb-3">
										<h6 class="mb-1">Start Date: <span class="text-body fw-normal">24/7/21</span></h6>
										<h6 class="mb-1">Deadline: <span class="text-body fw-normal">8/10/21</span></h6>
									</div>
								</div>
								<p class="mb-0">App design combines the user interface (UI) and user experience (UX).</p>
							</div>
							<div class="card-body border-top">
								<div class="d-flex align-items-center mb-3">
									<h6 class="mb-1">All Hours: <span class="text-body fw-normal">880/421</span></h6>
									<span class="badge bg-label-danger ms-auto">45 Days left</span>
								</div>
								<div class="d-flex justify-content-between align-items-center mb-1">
									<small>Task: 22/140</small>
									<small>68% Completed</small>
								</div>
								<div class="progress mb-3" style="height: 8px;">
									<div class="progress-bar" role="progressbar" style="width: 68%;" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Jimmy Ressula" data-bs-original-title="Jimmy Ressula">
												<img class="rounded-circle" src="../../assets/img/avatars/4.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kristi Lawker" data-bs-original-title="Kristi Lawker">
												<img class="rounded-circle" src="../../assets/img/avatars/2.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up me-2" aria-label="Danny Paul" data-bs-original-title="Danny Paul">
												<img class="rounded-circle" src="../../assets/img/avatars/7.png" alt="Avatar"onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
											</li>
											<li><small class="text-muted">458 Members</small></li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 98</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-header">
								<div class="d-flex align-items-start">
									<div class="d-flex align-items-start">
										<div class="avatar me-3">
											<img src="../../assets/img/icons/brands/html-label.png" alt="Avatar" class="rounded-circle">
										</div>
										<div class="me-2">
											<h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Create Website</a></h5>
											<div class="client-info d-flex align-items-center">
												<h6 class="mb-0 me-1">Client:</h6>
												<span>Hulda Wright</span>
											</div>
										</div>
									</div>
									<div class="ms-auto">
										<div class="dropdown z-2">
											<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
											<ul class="dropdown-menu dropdown-menu-end">
												<li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
												<li>
													<hr class="dropdown-divider">
												</li>
												<li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave Project</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="d-flex align-items-center flex-wrap">
									<div class="bg-lighter p-2 rounded me-auto mb-3">
										<h6 class="mb-1">$8.5k <span class="text-body fw-normal">/ $2.43k</span></h6>
										<span>Total Budget</span>
									</div>
									<div class="text-end mb-3">
										<h6 class="mb-1">Start Date: <span class="text-body fw-normal">10/2/19</span></h6>
										<h6 class="mb-1">Deadline: <span class="text-body fw-normal">12/9/22</span></h6>
									</div>
								</div>
								<p class="mb-0">Your domain name should reflect your products or services so that your...</p>
							</div>
							<div class="card-body border-top">
								<div class="d-flex align-items-center mb-3">
									<h6 class="mb-1">All Hours: <span class="text-body fw-normal">1.2k/820</span></h6>
									<span class="badge bg-label-warning ms-auto">126 Days left</span>
								</div>
								<div class="d-flex justify-content-between align-items-center mb-1">
									<small>Task: 237/420</small>
									<small>72% Completed</small>
								</div>
								<div class="progress mb-3" style="height: 8px;">
									<div class="progress-bar" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Andrew Tye" data-bs-original-title="Andrew Tye">
												<img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Rishi Swaat" data-bs-original-title="Rishi Swaat">
												<img class="rounded-circle" src="../../assets/img/avatars/9.png" alt="Avatar">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up me-2" aria-label="Rossie Kim" data-bs-original-title="Rossie Kim">
												<img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar">
											</li>
											<li><small class="text-muted">137 Members</small></li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 120</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-header">
								<div class="d-flex align-items-start">
									<div class="d-flex align-items-start">
										<div class="avatar me-3">
											<img src="../../assets/img/icons/brands/figma-label.png" alt="Avatar" class="rounded-circle">
										</div>
										<div class="me-2">
											<h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Figma Dashboard</a></h5>
											<div class="client-info d-flex align-items-center">
												<h6 class="mb-0 me-1">Client: </h6>
												<span>Jerry Greene</span>
											</div>
										</div>
									</div>
									<div class="ms-auto">
										<div class="dropdown z-2">
											<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
											<ul class="dropdown-menu dropdown-menu-end">
												<li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
												<li>
													<hr class="dropdown-divider">
												</li>
												<li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave Project</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="d-flex align-items-center flex-wrap">
									<div class="bg-lighter p-2 rounded me-auto mb-3">
										<h6 class="mb-1">$52.7k <span class="text-body fw-normal">/ $28.4k</span></h6>
										<span>Total Budget</span>
									</div>
									<div class="text-end mb-3">
										<h6 class="mb-1">Start Date: <span class="text-body fw-normal">12/12/20</span></h6>
										<h6 class="mb-1">Deadline: <span class="text-body fw-normal">25/12/21</span></h6>
									</div>
								</div>
								<p class="mb-0">Use this template to organize your design project. Some of the key features are…</p>
							</div>
							<div class="card-body border-top">
								<div class="d-flex align-items-center mb-3">
									<h6 class="mb-1">All Hours: <span class="text-body fw-normal">142/420</span></h6>
									<span class="badge bg-label-danger ms-auto">5 Days left</span>
								</div>
								<div class="d-flex justify-content-between align-items-center mb-1">
									<small>Task: 29/285</small>
									<small>35% Completed</small>
								</div>
								<div class="progress mb-3" style="height: 8px;">
									<div class="progress-bar" role="progressbar" style="width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kim Merchent" data-bs-original-title="Kim Merchent">
												<img class="rounded-circle" src="../../assets/img/avatars/10.png" alt="Avatar">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Sam D'souza" data-bs-original-title="Sam D'souza">
												<img class="rounded-circle" src="../../assets/img/avatars/13.png" alt="Avatar">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up me-2" aria-label="Nurvi Karlos" data-bs-original-title="Nurvi Karlos">
												<img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar">
											</li>
											<li><small class="text-muted">82 Members</small></li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 20</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-header">
								<div class="d-flex align-items-start">
									<div class="d-flex align-items-start">
										<div class="avatar me-3">
											<img src="../../assets/img/icons/brands/xd-label.png" alt="Avatar" class="rounded-circle">
										</div>
										<div class="me-2">
											<h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Logo Design</a></h5>
											<div class="client-info d-flex align-items-center">
												<h6 class="mb-0 me-1">Client:</h6>
												<span>Olive Strickland</span>
											</div>
										</div>
									</div>
									<div class="ms-auto">
										<div class="dropdown z-2">
											<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
											<ul class="dropdown-menu dropdown-menu-end">
												<li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
												<li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
												<li>
													<hr class="dropdown-divider">
												</li>
												<li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave Project</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="d-flex align-items-center flex-wrap">
									<div class="bg-lighter p-2 rounded me-auto mb-3">
										<h6 class="mb-1">$1.3k <span class="text-body fw-normal">/ $655</span></h6>
										<span>Total Budget</span>
									</div>
									<div class="text-end mb-3">
										<h6 class="mb-1">Start Date: <span class="text-body fw-normal">17/8/21</span></h6>
										<h6 class="mb-1">Deadline: <span class="text-body fw-normal">02/11/21</span></h6>
									</div>
								</div>
								<p class="mb-0">Premium logo designs created by top logo designers. Create the branding of business.</p>
							</div>
							<div class="card-body border-top">
								<div class="d-flex align-items-center mb-3">
									<h6 class="mb-1">All Hours: <span class="text-body fw-normal">580/445</span></h6>
									<span class="badge bg-label-success ms-auto">4 Days left</span>
								</div>
								<div class="d-flex justify-content-between align-items-center mb-1">
									<small>Task: 290/290</small>
									<small>100% Completed</small>
								</div>
								<div class="progress mb-3" style="height: 8px;">
									<div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center">
									<div class="d-flex align-items-center">
										<ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kim Karlos" data-bs-original-title="Kim Karlos">
												<img class="rounded-circle" src="../../assets/img/avatars/3.png" alt="Avatar">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Katy Turner" data-bs-original-title="Katy Turner">
												<img class="rounded-circle" src="../../assets/img/avatars/9.png" alt="Avatar">
											</li>
											<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up me-2" aria-label="Peter Adward" data-bs-original-title="Peter Adward">
												<img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar">
											</li>
											<li><small class="text-muted">16 Members</small></li>
										</ul>
									</div>
									<div class="ms-auto">
										<a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 37</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
        	</div>
			<div class="tab-pane fade" id="pills-Connections" role="tabpanel" aria-labelledby="pills-Connections-tab">
				<div class="row g-4"> 
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body text-center">
								<div class="dropdown btn-pinned">
									<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);">Share connection</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Block connection</a></li>
										<li>
											<hr class="dropdown-divider">
										</li>
										<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
								<div class="mx-auto mb-3">
									<img src="../../assets/img/avatars/3.png" alt="Avatar Image" class="rounded-circle w-px-100">
								</div>
								<h5 class="mb-1 card-title">Mark Gilbert</h5>
								<span>UI Designer</span>
								<div class="d-flex align-items-center justify-content-center my-3 gap-2">
									<a href="javascript:;" class="me-1"><span class="badge bg-label-secondary">Figma</span></a>
									<a href="javascript:;"><span class="badge bg-label-warning">Sketch</span></a>
								</div>
								<div class="d-flex align-items-center justify-content-around my-4 py-2">
									<div>
										<h4 class="mb-1">18</h4>
										<span>Projects</span>
									</div>
									<div>
										<h4 class="mb-1">834</h4>
										<span>Tasks</span>
									</div>
									<div>
										<h4 class="mb-1">129</h4>
										<span>Connections</span>
									</div>
								</div>
								<div class="d-flex align-items-center justify-content-center">
									<a href="javascript:;" class="btn btn-primary d-flex align-items-center me-3"><i class="bx bx-user-check me-1"></i>Connected</a>
									<a href="javascript:;" class="btn btn-label-secondary btn-icon"><i class="bx bx-envelope"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body text-center">
								<div class="dropdown btn-pinned">
									<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);">Share connection</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Block connection</a></li>
										<li>
											<hr class="dropdown-divider">
										</li>
										<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
								<div class="mx-auto mb-3">
									<img src="../../assets/img/avatars/12.png" alt="Avatar Image" class="rounded-circle w-px-100">
								</div>
								<h5 class="mb-1 card-title">Eugenia Parsons</h5>
								<span>Developer</span>
								<div class="d-flex align-items-center justify-content-center my-3 gap-2">
									<a href="javascript:;" class="me-1"><span class="badge bg-label-danger">Angular</span></a>
									<a href="javascript:;"><span class="badge bg-label-info">React</span></a>
								</div>
								<div class="d-flex align-items-center justify-content-around my-4 py-2">
									<div>
										<h4 class="mb-1">112</h4>
										<span>Projects</span>
									</div>
									<div>
										<h4 class="mb-1">23.1k</h4>
										<span>Tasks</span>
									</div>
									<div>
										<h4 class="mb-1">1.28k</h4>
										<span>Connections</span>
									</div>
								</div>
								<div class="d-flex align-items-center justify-content-center">
									<a href="javascript:;" class="btn btn-label-primary d-flex align-items-center me-3"><i class="bx bx-user-plus me-1"></i>Connect</a>
									<a href="javascript:;" class="btn btn-label-secondary btn-icon"><i class="bx bx-envelope"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body text-center">
								<div class="dropdown btn-pinned">
									<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);">Share connection</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Block connection</a></li>
										<li>
											<hr class="dropdown-divider">
										</li>
										<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
								<div class="mx-auto mb-3">
									<img src="../../assets/img/avatars/5.png" alt="Avatar Image" class="rounded-circle w-px-100">
								</div>
								<h5 class="mb-1 card-title">Francis Byrd</h5>
								<span>Developer</span>
								<div class="d-flex align-items-center justify-content-center my-3 gap-2">
									<a href="javascript:;" class="me-1"><span class="badge bg-label-info">React</span></a>
									<a href="javascript:;"><span class="badge bg-label-primary">HTML</span></a>
								</div>
								<div class="d-flex align-items-center justify-content-around my-4 py-2">
									<div>
										<h4 class="mb-1">32</h4>
										<span>Projects</span>
									</div>
									<div>
										<h4 class="mb-1">1.25k</h4>
										<span>Tasks</span>
									</div>
									<div>
										<h4 class="mb-1">890</h4>
										<span>Connections</span>
									</div>
								</div>
								<div class="d-flex align-items-center justify-content-center">
									<a href="javascript:;" class="btn btn-label-primary d-flex align-items-center me-3"><i class="bx bx-user-plus me-1"></i>Connect</a>
									<a href="javascript:;" class="btn btn-label-secondary btn-icon"><i class="bx bx-envelope"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body text-center">
								<div class="dropdown btn-pinned">
									<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);">Share connection</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Block connection</a></li>
										<li>
											<hr class="dropdown-divider">
										</li>
										<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
								<div class="mx-auto mb-3">
									<img src="../../assets/img/avatars/18.png" alt="Avatar Image" class="rounded-circle w-px-100">
								</div>
								<h5 class="mb-1 card-title">Leon Lucas</h5>
								<span>UI/UX Designer</span>
								<div class="d-flex align-items-center justify-content-center my-3 gap-2">
									<a href="javascript:;" class="me-1"><span class="badge bg-label-secondary">Figma</span></a>
									<a href="javascript:;" class="me-1"><span class="badge bg-label-warning">Sketch</span></a>
									<a href="javascript:;"><span class="badge bg-label-primary">Photoshop</span></a>
								</div>
								<div class="d-flex align-items-center justify-content-around my-4 py-2">
									<div>
										<h4 class="mb-1">86</h4>
										<span>Projects</span>
									</div>
									<div>
										<h4 class="mb-1">12.4k</h4>
										<span>Tasks</span>
									</div>
									<div>
										<h4 class="mb-1">890</h4>
										<span>Connections</span>
									</div>
								</div>
								<div class="d-flex align-items-center justify-content-center">
									<a href="javascript:;" class="btn btn-label-primary d-flex align-items-center me-3"><i class="bx bx-user-plus me-1"></i>Connect</a>
									<a href="javascript:;" class="btn btn-label-secondary btn-icon"><i class="bx bx-envelope"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body text-center">
								<div class="dropdown btn-pinned">
									<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);">Share connection</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Block connection</a></li>
										<li>
											<hr class="dropdown-divider">
										</li>
										<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
								<div class="mx-auto mb-3">
									<img src="../../assets/img/avatars/9.png" alt="Avatar Image" class="rounded-circle w-px-100">
								</div>
								<h5 class="mb-1 card-title">Jayden Rogers</h5>
								<span>Full Stack Developer</span>
								<div class="d-flex align-items-center justify-content-center my-3 gap-2">
									<a href="javascript:;" class="me-1"><span class="badge bg-label-info">React</span></a>
									<a href="javascript:;" class="me-1"><span class="badge bg-label-danger">Angular</span></a>
									<a href="javascript:;"><span class="badge bg-label-primary">HTML</span></a>
								</div>
								<div class="d-flex align-items-center justify-content-around my-4 py-2">
									<div>
										<h4 class="mb-1">244</h4>
										<span>Projects</span>
									</div>
									<div>
										<h4 class="mb-1">23.8k</h4>
										<span>Tasks</span>
									</div>
									<div>
										<h4 class="mb-1">2.14k</h4>
										<span>Connections</span>
									</div>
								</div>
								<div class="d-flex align-items-center justify-content-center">
									<a href="javascript:;" class="btn btn-primary d-flex align-items-center me-3"><i class="bx bx-user-check me-1"></i>Connected</a>
									<a href="javascript:;" class="btn btn-label-secondary btn-icon"><i class="bx bx-envelope"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="card">
							<div class="card-body text-center">
								<div class="dropdown btn-pinned">
									<button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);">Share connection</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Block connection</a></li>
										<li>
											<hr class="dropdown-divider">
										</li>
										<li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
								<div class="mx-auto mb-3">
									<img src="../../assets/img/avatars/10.png" alt="Avatar Image" class="rounded-circle w-px-100">
								</div>
								<h5 class="mb-1 card-title">Jeanette Powell</h5>
								<span>SEO</span>
								<div class="d-flex align-items-center justify-content-center my-3 gap-2">
									<a href="javascript:;" class="me-1"><span class="badge bg-label-success">Writing</span></a>
									<a href="javascript:;"><span class="badge bg-label-secondary">Analysis</span></a>
								</div>
								<div class="d-flex align-items-center justify-content-around my-4 py-2">
									<div>
										<h4 class="mb-1">32</h4>
										<span>Projects</span>
									</div>
									<div>
										<h4 class="mb-1">1.28k</h4>
										<span>Tasks</span>
									</div>
									<div>
										<h4 class="mb-1">1.27k</h4>
										<span>Connections</span>
									</div>
								</div>
								<div class="d-flex align-items-center justify-content-center">
									<a href="javascript:;" class="btn btn-label-primary d-flex align-items-center me-3"><i class="bx bx-user-plus me-1"></i>Connect</a>
									<a href="javascript:;" class="btn btn-label-secondary btn-icon"><i class="bx bx-envelope"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
        	</div>
        </div>
        <!--/ User Profile Content -->
    </div>
    <!-- / Content -->
    <!-- Footer -->
    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
            <div class="mb-2 mb-md-0">
                © <script>
                    document.write(new Date().getFullYear())
                    
                </script>2024, made with ❤️ by <a href="https://themeselection.com" target="_blank" class="footer-link fw-medium">ThemeSelection</a>
            </div>
            <div class="d-none d-lg-inline-block">
                <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>
                <a href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>
                <a href="https://themeselection.com/support/" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>
            </div>
        </div>
    </footer>
    <!-- / Footer -->
    <div class="content-backdrop fade"></div>
</div>