<link rel="stylesheet" type="text/css" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$upd_version}" media="all" />
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/html2canvas.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.js?v={$upd_version}"></script>

<main class="content-wrapper"> <div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="pagetitle">
		<h1>Profile</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{$PCMS_URL}">Trang chủ</a></li>
				<li class="breadcrumb-item">Users</li>
				<li class="breadcrumb-item active">Profile</li>
			</ol>
		</nav>
	</div>
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
		<div class="row">
			<div class="col-xxl-10 mx-auto content_profile">				
				<div class="d-flex justify-content-between align-items-center">
					<h4 class="py-2 mb-3">
						<span class="text-muted fw-light">Hồ sơ cá nhân</span>
					</h4>
					<div class="btn-group">
						<button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">Chọn hiển thị</button>
						<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuClickableInside">
							<li><a class="dropdown-item" href="{$clsProfile->getLink($profile_id,$oneProfile,'1')}">Xem mẫu 1</a></li>
							<li><a class="dropdown-item" href="{$clsProfile->getLink($profile_id,$oneProfile,'2')}">Xem mẫu 2</a></li>
							<li><a class="dropdown-item" href="{$clsProfile->getLink($profile_id,$oneProfile,'3')}">Xem mẫu 3</a></li>
						</ul>
					</div>
				</div>
					<!-- Header -->
					<div class="row">
						<div class="col-12">
							<div class="card mb-4">
								<section class="banner_profile hero d-flex rounded-3 overflow-hidden position-relative" style="height: 400px">
									<div class="hero-content p-4 d-flex justify-content-center align-items-start flex-column">
										<div class="position-relative w-px-150 h-px-150 p-1 border bg-white rounded-pill mb-3">
											<img class="rounded-circle border w-100 h-100" src="{$oneProfile.avatar}" height="150" width="150" alt="User avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'" style="object-fit: cover" id="image_avatar">
											<input type="file" id="select_image_{$uid}" name="avatar" value="" onchange="$Core.broker.upload_image(this, event)"  toImg="image_avatar" hidden >
											<button class="btn_upload_avatar" onclick="$Core.broker.file_explorer(this, event);" uid="{$uid}" data-type="avatar" toId="avatar" toImg="image_avatar" profile_id="{$oneProfile.profile_id}"><i class='bx bx-camera'></i></button>
										</div>
										<h1 class="text-upper text-dark fs-3">
											<span class="InputCRMHandler text-break">
												{$oneProfile.full_name}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'full_name', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="full_name" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
											</span>
										</h1>
										<p class="title text-main2">
											<span class="InputCRMHandler fs-6 text-break">
												{if !empty($more_information.level_sales)}{$more_information.level_sales}{else}Chuyên gia tư vấn bất động sản{/if}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'level_sales', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="level_sales" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
											</span>
										</p>
										<h2 class="text-dark fs-4">
										<span class="InputCRMHandler text-break">
											{$more_information.experience}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'experience', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="experience" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
										</span></h2>
										<a href="tel:{$oneProfile.phone}" class="btn btn-main btn-lg">Đặt lịch tư vấn</a>
									</div>
									<div class="bg_banner flex-fill h-100 position-relative" style="background-image: URL('{if !empty($more_information.banner)}{$more_information.banner}{else}{$URL_IMAGES}/banner_default.jpg{/if}')" id="image_banner">		
										<input type="file" name="avatar" value="" hidden id="banner">
										<button class="btn_upload_avatar " onclick="$Core.broker.uploadImage(this,event);" data-type="banner" toId="banner" toImg="image_banner" profile_id="{$oneProfile.profile_id}"><i class='bx bx-camera'></i></button> 
									</div>
								</section>
							</div>
						</div>
					</div>
					<!--/ Header -->
					<!-- Navbar pills -->
					<div class="row">
						<div class="col-md-12">
							<ul class="nav nav-pills flex-column flex-sm-row mb-4 d-none">
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
								<div class="col-xl-4 col-lg-4 col-md-5">
									<!-- About User -->
									<div class="card mb-4">
										<div class="card-body">
											<small class="text-muted text-uppercase">Thông tin</small>
											<ul class="list-unstyled mb-4 mt-3">
												<li class="d-flex flex-wrap align-items-start justify-content-between mb-2">
													<div class="d-flex flex-wrap align-items-start mb-2">
														<i class="bx bx-cake fs-5"></i><span class="fw-semibold mx-2">Ngày sinh:</span> 
														<span class="InputCRMHandler fs-6 text-break">
															{$clsISO->formatDate($oneProfile.birthday,5)}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'birthday', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="birthday" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
														</span>
													</div>
													{if $profile_id eq 289}
													<div class="input-group w-auto flex-fill justify-content-end">
														<input type="radio" class="btn-check" onchange="$Core.broker.showHideBirthday(this, event)" name="is_show_birthday" value="0" id="is_show_birthday_0" autocomplete="off" {if empty($more_information.is_show_birthday)}checked{/if} >
														<label class="btn btn-default btn-xs label_check" for="is_show_birthday_0">Hiển thị</label>
														<input type="radio" class="btn-check" onchange="$Core.broker.showHideBirthday(this, event)" name="is_show_birthday" value="1" id="is_show_birthday_1" autocomplete="off" {if $more_information.is_show_birthday eq 1}checked{/if}>
														<label class="btn btn-default btn-xs label_check" for="is_show_birthday_1">Ẩn</label>
														<input type="radio" class="btn-check" onchange="$Core.broker.showHideBirthday(this, event)" name="is_show_birthday" value="2" id="is_show_birthday_2" autocomplete="off" {if $more_information.is_show_birthday eq 2}checked{/if}>
														<label class="btn btn-default btn-xs label_check" for="is_show_birthday_2">Ẩn năm</label>
													</div>
													{/if}
												</li>
												<li class="d-flex flex-wrap align-items-start mb-2"><i class="bx bx-flag fs-5"></i><span class="fw-semibold mx-2">Địa chỉ:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$oneProfile.address}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'address', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="address" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li> 
												<li class="d-flex flex-wrap align-items-start mb-2">
													<i class="bx bx-package fs-5"></i><span class="fw-semibold mx-2">Đại lý:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$more_information.agency}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'agency', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="agency" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li>
												<li class="d-flex flex-wrap align-items-start mb-2">
													<i class="bx bx-building-house fs-5"></i><span class="fw-semibold mx-2">Giao dịch thành công:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$more_information.number_sale}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'number_sale', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="number_sale" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li> 
												<li class="d-flex flex-wrap align-items-start mb-2">
													<i class='bx bx-wink-smile fs-5'></i><span class="fw-semibold mx-2">Tỷ lệ thành công:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$more_information.success_rate}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'success_rate', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="success_rate" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li> 
												<li class="d-flex flex-wrap align-items-start mb-2">
													<i class='bx bxs-star-half fs-5' ></i><span class="fw-semibold mx-2">Đánh giá trung bình:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$more_information.average_rate}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'average_rate', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="average_rate" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li> 
												<li class="d-flex flex-wrap align-items-start mb-2">
													<i class="bx bx-flag fs-5"></i><span class="fw-semibold mx-2">Doanh số bán hàng:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$more_information.total_sales}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'total_sales', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="total_sales" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li>
											</ul>
											<small class="text-muted text-uppercase">Liên hệ</small>
											<ul class="list-unstyled mb-4 mt-3">
												<li class="d-flex flex-wrap align-items-start mb-2"><i class="bx bx-phone fs-5"></i><span class="fw-semibold mx-2">Điện thoại:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$oneProfile.phone}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'phone', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="phone" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li>
												<li class="d-flex flex-wrap align-items-start mb-2"><i class="bx bx-envelope fs-5" style="color: #a22940"></i><span class="fw-semibold mx-2">Email:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$oneProfile.email}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'email', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="email" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a>  
													</span>
												</li> 
												<li class="d-flex flex-wrap align-items-start mb-2"><i class='bx bxl-linkedin-square fs-5' style="color:#0270ad"></i><span class="fw-semibold mx-2">LinkedIn:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$more_information.linkedin}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'linkedin', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="linkedin" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li>
												<li class="d-flex flex-wrap align-items-start mb-2"><i class='bx bxl-instagram fs-5' style="padding: 0.01rem;background: #f09433;background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);background: -webkit-linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);color: #fff"></i><span class="fw-semibold mx-2">Instagram:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$more_information.instagram}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'instagram', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="instagram" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span> 
												</li>
												<li class="d-flex flex-wrap align-items-start mb-2"><i class='bx bxl-facebook-circle fs-5' style="color:#0863f7"></i><span class="fw-semibold mx-2">Facebook:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$more_information.facebook}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'facebook', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="facebook" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li>
												<li class="d-flex flex-wrap align-items-start mb-2"><i class='bx bxl-twitter fs-5' style="color:#2593e9"></i><span class="fw-semibold mx-2">Twitter:</span> 
													<span class="InputCRMHandler fs-6 text-break">
														{$more_information.twitter}<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'twitter', 'p_id':{$oneProfile.profile_id}{rdelim})" p_field="twitter" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> 
													</span>
												</li>
											</ul>
										</div>
									</div>
									<div class="card mb-4">
										<div class="card-header d-flex justify-content-between align-items-center">
											<h5 class="mb-0">Chứng chỉ môi giới</h5>
											<div class="box_upload_image">
												<form action="" method="post" enctype="multipart/form-data">
													<input type="file" name="image_certificate[]" multiple hidden id="image_certificate">
													<button type="button" class="btn btn-outline-primary text-nowrap" onclick="$Core.broker.uploadImage(this,event);" toId="image_certificate" toImg="list_image_certificate" data-type="image_certificate" profile_id="{$oneProfile.profile_id}">
														<i class='bx bx-image-add' ></i>Tải ảnh
													</button>
												</form>
											</div>
										</div>
										<div class="card-body">
											<div class="form-row row" id="list_image_certificate">
												{if !empty($more_information.image_certificate)}
													<div class="" data-fancybox="gallery" href="{$more_information.image_certificate}">
														<div class="rounded img_scale">
															<img class="drag-item cursor-pointer" src="{$more_information.image_certificate}" alt="avatar" style="width: 100%;height:200px">
														</div>
													</div>
												{else}
													<div class="">
														<div class="rounded">
															<img class="drag-item" src="{$URL_IMAGES}/no-image.jpg" alt="avatar" style="width: 100%;height:200px">
														</div>
													</div>
												{/if}																					
											</div>
										</div>
									</div>
									<!--/ About User -->
									<div class="card mb-4">
										<div class="card-header d-flex justify-content-between align-items-center">
											<h5 class="mb-0">Danh sách giao dịch</h5>
											<div class="box_upload_image">
												<form action="" method="post" enctype="multipart/form-data">
													<input type="file" name="image_billing[]" multiple hidden id="image_billing">
													<button type="button" class="btn btn-outline-primary text-nowrap" onclick="$Core.broker.uploadImage(this,event);" toId="image_billing" toImg="list_image_billing" data-type="image_billing" profile_id="{$oneProfile.profile_id}">
														<i class='bx bx-image-add' ></i>Tải ảnh
													</button>
												</form>
											</div>

										</div>

										<div class="card-body">
											<div class="form-row row" id="list_image_billing">
												{if !empty($more_information.image_billing)}
													{foreach from=$more_information.image_billing item=image}
														<div class="item col-6 col-sm-4 mb-2" data-fancybox="gallery" href="{$image}">
															<div class="rounded img_scale">
																<img class="drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;height:80px">
															</div>
														</div>
													{/foreach}
												{else}
													{section loop=6 name=i start=0 step=1}
														<div class="item col-6 col-sm-4 mb-2">
															<div class="rounded">
																<img class="drag-item" src="{$URL_IMAGES}/no-image.jpg" alt="avatar" style="width: 100%;height:80px">
															</div>
														</div>
													{/section}
												{/if}																					
											</div>
										</div>
									</div>
									<div class="card mb-4">
										<div class="card-header d-flex justify-content-between align-items-center">
											<h5 class="mb-0">Thư viện ảnh</h5>
											<div class="box_upload_image">
												<form action="" method="post" enctype="multipart/form-data">
													<input type="file" name="images[]" multiple hidden id="images">
													<button type="button" class="btn btn-outline-primary text-nowrap" onclick="$Core.broker.uploadImage(this,event);" toId="images" toImg="list_image" data-type="image" profile_id="{$oneProfile.profile_id}">
														<i class='bx bx-image-add' ></i>Tải ảnh
													</button>
												</form>
											</div>

										</div>

										<div class="card-body">
											<div class="form-row row" id="list_image">
												{if !empty($more_information.image)}
													{foreach from=$more_information.image item=image}
														<div class="item col-6 col-sm-4 mb-2" data-fancybox="gallery" href="{$image}">
															<div class="rounded img_scale">
																<img class="drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;height:80px">
															</div>
														</div>
													{/foreach}
												{else}
													{section loop=6 name=i start=0 step=1}
														<div class="col-6 col-sm-4 mb-2">
															<div class="rounded">
																<img class="drag-item" src="{$URL_IMAGES}/no-image.jpg" alt="avatar" style="width: 100%;height:80px">
															</div>
														</div>
													{/section}
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
													<img src="{$URL_IMAGES}/image_video_default.jpg" alt="" class="w-100" style="height: 200px">
												{/if}
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-8 col-lg-8 col-md-7">
									<!-- Activity Timeline -->
									<div class="card card-action mb-4">
										<div class="card-body">
											<h5 class="card-title mb-2">Giới thiệu <a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'about',p_element:'textarea', 'p_id':{$oneProfile.profile_id}{rdelim})" p_element="textarea" p_field="about" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> </h5>  
											<div class="tinymce_content content_about">
												{$more_information.about|html_entity_decode}
											</div>				
										</div>
									</div>
									<div class="card card-action mb-4">
										<div class="card-body">
											<h5 class="card-title mb-2">Châm ngôn sống<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{ldelim}p_field:'dictum_live',p_element:'textarea', 'p_id':{$oneProfile.profile_id}{rdelim})" p_element="textarea" p_field="dictum_live" p_id="{$oneProfile.profile_id}">{$clsISO->makeIcon('bx-pencil')}</a> </h5>  
											<div class="tinymce_content content_about">
												{$more_information.dictum_live|html_entity_decode}
											</div>				
										</div>
									</div>
									{foreach from=$lstFieldMoreInfomation key=k item=Field}
									<div class="card card-action mb-4">
										<div class="card-body">
											<div class="d-flex justify-content-between align-items-center">
												<h5 class="card-title mb-0">{$Field.title}</h5>
												<button class="btn btn-outline-default" type="button" onClick="$Core.broker.formAddField(this,'open')" data-field="{$k}">{if $deviceType eq 'phone'}Thêm{else}Thêm mới{/if}</button>
											</div>
											<div class="table-profile pt-3">
												<table class="table table-iloocal table-{$deviceType} table-bordered">
													<thead>
														<tr>
															<th class="align-center bg-lighter" style="width:40px">Ảnh</th>
															<th class="align-center text-left bg-lighter">Tiêu đề</th>
															{if $k eq 'project'}
																<th class="align-center text-left bg-lighter w-px-75">Số căn bán</th>
															{/if}
															<th class="align-center text-left bg-lighter w-px-40"></th>
														</tr>
													</thead>
													<tbody class="lst_certificate ajax" id="lst_{$k}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_table&type={$k}">
														{if !empty($more_information.$k)}
															{foreach from=$more_information.$k key=key item=item}
																<tr>
																	<td data-label="Hình ảnh"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>
																	<td data-label="Tiêu đề"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>				
																	<td data-label="Nội dung"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>	
																	<td data-label="Nội dung"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>	
																	<td>
																		<div class="animate-bg rounded-2 flex-fill h-px-15"></div>
																	</td>
																</tr>
															{/foreach}
														{else}
															<tr>
																<td class="text-center" colspan="{if $k eq 'project'}4{else}3{/if}">Chưa có dữ liệu</td>	
															</tr>
														{/if}
													</tbody>
												</table>
											</div>				
										</div>
									</div>
									{/foreach}
									<div class="card card-action mb-4">
										<div class="card-body">
											<div class="d-flex justify-content-between align-items-center">
												<h5 class="card-title mb-0">Quá trình làm việc</h5>
												<button class="btn btn-outline-default" type="button" onClick="$Core.broker.formAddField(this,'open')" data-field="working_process">{if $deviceType eq 'phone'}Thêm{else}Thêm mới{/if}</button>
											</div>
											<div class="table-profile pt-3">
												<table class="table table-iloocal table-{$deviceType} table-bordered">
													<thead>
														<tr>
															<th class="align-center text-left bg-lighter">Thời gian</th>
															<th class="align-center text-left bg-lighter">Tên công ty</th>
															<th class="align-center text-left bg-lighter">Vị trí</th>
															<th class="align-center text-left bg-lighter">Nội dung</th>
															<th class="align-center text-left bg-lighter w-px-40"></th>
														</tr>
													</thead>
													<tbody class="ajax" id="lst_working_process" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_table&type=working_process">	
														{foreach from=$more_information.working_process key=key item=item}
															<tr>
																<td data-label="Hình ảnh"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>
																<td data-label="Tiêu đề"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>				
																<td data-label="Nội dung"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>	
																<td data-label="Nội dung"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>	
																<td>
																	<div class="animate-bg rounded-2 flex-fill h-px-15"></div>
																</td>
															</tr>
														{/foreach}
													</tbody>
												</table>
											</div>
											<div class="row mx-4 d-none">
												<div class="col-md-12 col-xl-6 text-center text-xl-start pb-2 pb-lg-0 pe-0">
													<div class="dataTables_info" id="text_showing_sales" role="status" aria-live="polite">Hiển thị 1 đến 6 của 100 giao dịch</div>
												</div>
												<div class="col-md-12 col-xl-6 d-flex justify-content-center justify-content-xl-end">
													<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
														<ul class="pagination" id="pagination-container">

														</ul>
													</div>
												</div>
											</div>				
										</div>
									</div>
									<div class="card card-action mb-4">
										<div class="card-body">
											{assign var=gId value=$clsISO->getUniqid()}
											<div class="d-flex justify-content-between align-items-center">
												<h5 class="card-title mb-0">Bài viết chia sẻ</h5>
												<button class="btn btn-outline-default" type="button" onClick="$Core.broker.open_meta(this,'open')" data-meta_id="0" gId="{$gId}">{if $deviceType eq 'phone'}Thêm{else}Thêm mới{/if}</button>
											</div>
											<div class="table-profile pt-3">
												<table class="table table-iloocal table-{$deviceType} table-bordered">
													<thead>
														<tr>
															<th class="align-center bg-lighter" style="width:40px">Ảnh</th>
															<th class="align-center text-left bg-lighter">Tiêu đề</th>
															<th class="align-center text-left bg-lighter">Nội dung</th>
															<th class="align-center text-left bg-lighter">Cập nhật</th>
															<th class="align-center text-left bg-lighter w-px-40"></th>
														</tr>
													</thead>
													<tbody class="lst_post_meta ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_table&type=post" id="{$gId}">
														<tr>
															<td data-label="Hình ảnh"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>
															<td data-label="Tiêu đề"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>				
															<td data-label="Nội dung"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>	
															<td data-label="Nội dung"><div class="animate-bg rounded-2 flex-fill h-px-15"></div></td>	
															<td>
																<div class="animate-bg rounded-2 flex-fill h-px-15"></div>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="row mx-4 d-none">
												<div class="col-md-12 col-xl-6 text-center text-xl-start pb-2 pb-lg-0 pe-0">
													<div class="dataTables_info" id="text_showing_sales" role="status" aria-live="polite">Hiển thị 1 đến 6 của 100 giao dịch</div>
												</div>
												<div class="col-md-12 col-xl-6 d-flex justify-content-center justify-content-xl-end">
													<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
														<ul class="pagination" id="pagination-container">

														</ul>
													</div>
												</div>
											</div>				
										</div>
									</div>
									{*<div class="card card-action mb-4">
										<div class="card-body">
											<div class="d-flex justify-content-between align-items-center">
												<h5 class="card-title mb-0">Lịch sử bán hàng</h5>
												<button class="btn btn-outline-default" type="button" onClick="$Core.broker.formAddField(this,'open')" data-field="history_sale">{if $deviceType eq 'phone'}Thêm{else}Thêm mới{/if}</button>
											</div>
											<div class="table-profile pt-3">
												<table class="table table-iloocal table-{$deviceType} table-bordered">
													<thead>
														<tr>
															<th class="align-center bg-lighter" style="width:40px">Mã căn</th>
															<th class="align-center text-left bg-lighter">Dự án</th>
															<th class="align-center text-left bg-lighter">Số tiền</th>
															<th class="align-center text-left bg-lighter">Tên khách hàng</th>
															<th class="align-center text-left bg-lighter">Ngày giao dịch</th>
															<th class="align-center text-left bg-lighter w-px-40"></th>
														</tr>
													</thead>
													<tbody id="lst_history_sale">	
														{foreach from=$more_information.history_sale key=key item=item}
															<tr>
																<td>{$item.stock_code}</td>
																<td>{$item.project}</td>
																<td>{$clsISO->shortNumber($item.price)}</td>
																<td>{$item.customer_name}</td>
																<td>{$item.date_trading}</td>
																<td>
																	<div class="btn-group ml-2">
																		<button type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
																		<ul class="dropdown-menu dropdown-menu-end" style="">
																			<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.formAddField(this,'open')" data-field_id="{$key}"  data-field="history_sale" data-type="edit">Sửa</a></li>
																			<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.deleteField(this,'delete')" data-field_id="{$key}" data-field="history_sale">Xoá</a></li>
																		</ul>
																	</div>
																</td>
															</tr>
														{/foreach}
													</tbody>
												</table>
											</div>
											<div class="row mx-4 d-none">
												<div class="col-md-12 col-xl-6 text-center text-xl-start pb-2 pb-lg-0 pe-0">
													<div class="dataTables_info" id="text_showing_sales" role="status" aria-live="polite">Hiển thị 1 đến 6 của 100 giao dịch</div>
												</div>
												<div class="col-md-12 col-xl-6 d-flex justify-content-center justify-content-xl-end">
													<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
														<ul class="pagination" id="pagination-container">

														</ul>
													</div>
												</div>
											</div>				
										</div>
									</div>
									<div class="card card-action mb-4">
										<div class="card-body">
											<div class="d-flex justify-content-between align-items-center">
												<h5 class="card-title mb-0">Lịch sử bán hàng</h5>
												<button class="btn btn-outline-default" type="button" onClick="$Core.broker.formAddField(this,'open')" data-field="history_sale">{if $deviceType eq 'phone'}Thêm{else}Thêm mới{/if}</button>
											</div>
											<div class="table-profile pt-3">
												<table class="table table-iloocal table-{$deviceType} table-bordered">
													<thead>
														<tr>
															<th class="align-center bg-lighter" style="width:40px">Mã căn</th>
															<th class="align-center text-left bg-lighter">Dự án</th>
															<th class="align-center text-left bg-lighter">Số tiền</th>
															<th class="align-center text-left bg-lighter">Tên khách hàng</th>
															<th class="align-center text-left bg-lighter">Ngày giao dịch</th>
															<th class="align-center text-left bg-lighter w-px-40"></th>
														</tr>
													</thead>
													<tbody id="lst_history_sale">	
														{foreach from=$more_information.history_sale key=key item=item}
															<tr>
																<td>{$item.stock_code}</td>
																<td>{$item.project}</td>
																<td>{$clsISO->shortNumber($item.price)}</td>
																<td>{$item.customer_name}</td>
																<td>{$item.date_trading}</td>
																<td>
																	<div class="btn-group ml-2">
																		<button type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
																		<ul class="dropdown-menu dropdown-menu-end" style="">
																			<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.formAddField(this,'open')" data-field_id="{$key}"  data-field="history_sale" data-type="edit">Sửa</a></li>
																			<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.deleteField(this,'delete')" data-field_id="{$key}" data-field="history_sale">Xoá</a></li>
																		</ul>
																	</div>
																</td>
															</tr>
														{/foreach}
													</tbody>
												</table>
											</div>
											<div class="row mx-4 d-none">
												<div class="col-md-12 col-xl-6 text-center text-xl-start pb-2 pb-lg-0 pe-0">
													<div class="dataTables_info" id="text_showing_sales" role="status" aria-live="polite">Hiển thị 1 đến 6 của 100 giao dịch</div>
												</div>
												<div class="col-md-12 col-xl-6 d-flex justify-content-center justify-content-xl-end">
													<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
														<ul class="pagination" id="pagination-container">

														</ul>
													</div>
												</div>
											</div>				
										</div>
									</div>*}
									<div class="card card-action mb-4">
										<div class="card-body">
											<div class="d-flex justify-content-between align-items-center">
												<h5 class="card-title mb-0">Nhận xét của khách hàng</h5>
												<button class="btn btn-outline-default" type="button" onClick="$Core.broker.formAddField(this,'open')" data-field="customer_review">{if $deviceType eq 'phone'}Thêm{else}Thêm mới{/if}</button>
											</div>
											<div class="table-profile pt-3">
												<table class="table table-iloocal table-{$deviceType} table-bordered">
													<thead>
														<tr>
															<th class="align-center text-left bg-lighter" style="width: 90px">Hình ảnh</th>
															<th class="align-center bg-lighter" style="width:150px">Tiêu đề</th>
															<th class="align-center text-left bg-lighter">Số sao</th>
															<th class="align-center text-left bg-lighter">Tên khách hàng</th>
															<th class="align-center text-left bg-lighter">Nội dung</th>
															<th class="align-center text-left bg-lighter d-none">Ngày đánh giá</th>
															<th class="align-center text-left bg-lighter w-px-40"></th>
														</tr>
													</thead>
													<tbody id="lst_customer_review">	
														{foreach from=$more_information.customer_review key=key item=item}
															<tr>
																<td data-label="Hình ảnh"><img class="rounded" src="{$item.image}" alt="{$item.title}" width="50" height="50"></td>
																<td data-label="Tiêu đề">{$item.title}</td>
																<td class="text-center" data-label="Số sao">{$item.star}</td>
																<td data-label="Tên khách hàng">{$item.customer_name}</td>
																<td data-label="Nội dung"><div class="text_4line">{$item.content|html_entity_decode}</div></td>
																<td class="text-center d-none" data-label="Ngày đánh giá">{$item.date}</td>
																<td>
																	<div class="btn-group ml-2">
																		<button type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
																		<ul class="dropdown-menu dropdown-menu-end" style="">
																			<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.formAddField(this,'open')" data-field_id="{$key}"  data-field="customer_review" data-type="edit">Sửa</a></li>
																			<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.deleteField(this,'delete')" data-field_id="{$key}" data-field="customer_review">Xoá</a></li>
																		</ul>
																	</div>
																</td>
															</tr>
														{/foreach}
													</tbody>
												</table>
											</div>
											<div class="row mx-4 d-none">
												<div class="col-md-12 col-xl-6 text-center text-xl-start pb-2 pb-lg-0 pe-0">
													<div class="dataTables_info" id="text_showing_sales" role="status" aria-live="polite">Hiển thị 1 đến 6 của 100 giao dịch</div>
												</div>
												<div class="col-md-12 col-xl-6 d-flex justify-content-center justify-content-xl-end">
													<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
														<ul class="pagination" id="pagination-container">

														</ul>
													</div>
												</div>
											</div>				
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/ User Profile Content -->
			</div>
		</div>
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</main>
<script type="text/javascript"> 
		current_page = '{$current_page}';
</script>