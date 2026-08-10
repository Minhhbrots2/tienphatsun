<div class="container-xxl flex-grow-1 container-p-y page_detail_broker pt-2">
    <h4 class="py-2 mb-3">
        <a href="/c/"><i class='bx bx-chevron-left fs-4' style="vertical-align: text-bottom;"></i></a> {$oneItem.full_name}
    </h4>
    <div class="row">
        <!-- Customer-detail Sidebar -->
        <div class="col-xxl-4 col-lg-5">
            <!-- Customer-detail Card -->
            <div class="card mb-4 no-shadow">
                <div class="card-body">
                    <div class="customer-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                            <div class="box_image my-3">
								<img class="rounded-circle" src="{$FH_URL}/{$oneItem.avatar}" height="150" width="150" alt="User avatar" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'" style="object-fit: cover">
								<span class="level">PRO</span> 
							</div>
                            <div class="customer-info text-center">
                                <h4 class="mb-1">{$oneItem.full_name}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around flex-wrap mt-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar {$deviceType}">
                                <div class="avatar-initial rounded bg-label-primary"><i class="bx bx-cart-alt bx-sm"></i>
                                </div>
                            </div>
                            <div>
								{if !empty($more_information.number_sale)}
									<h5 class="mb-0">{$more_information.number_sale}</h5>
								{else}
                                	<h5 class="mb-0">{$clsISO->formatNumberToEasyRead($total_billings)}</h5>
                                {/if}
								{if $deviceType eq 'phone'}
                                	<span style="font-size: 11px">GD thành công</span>
								{else}
                                	<span>GD thành công</span>
								{/if}
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar {$deviceType}">
                                <div class="avatar-initial rounded bg-label-primary"><i class="bx bx-dollar bx-sm"></i>
                                </div>
                            </div>
                            <div>
								{if !empty($more_information.total_sales)}
									<h5 class="mb-0">{$more_information.total_sales}</h5>
								{else}
                                	<h5 class="mb-0">{$clsISO->shortNumber($total_sales)}</h5>
                                {/if}
                                {if $deviceType eq 'phone'}
                                	<span style="font-size: 11px">DS bán hàng</span> 
								{else}
                                	<span>DS bán hàng</span> 
								{/if}
                            </div>
                        </div>
						{if $oneProfile.profile_id ne $oneItem.profile_id && $oneProfile.profile_id ne '118'}
                        <div class="d-flex align-items-center gap-2">
							<button class="btn btn-icon btn-outline-default follow_profile fs-5 {if $clsISO->checkItemInArray($oneItem.profile_id,$array_follow)}followed{/if}" type="button" onClick="$Core.broker.follow_broker(this,{$oneItem.profile_id})" title="{if $clsISO->checkItemInArray($oneItem.profile_id,$array_follow)}Bỏ theo dõi{else}Theo dõi{/if}" trigger="hover" data-bs-toggle="tooltip">
								<i class='bx bx-user-check'></i>
							</button> 
                        </div>
						{/if}
                    </div>
                    <div class="info-container">
                        <small class="text-muted text-uppercase">Thông tin</small>
						<ul class="list-unstyled mb-4 mt-3">
							{*<li class="d-flex align-items-center mb-3"><i class="bx bx-check fs-5"></i><span class="fw-medium mx-2">Trạng thái:</span> <span>{if $oneItem.is_verified eq 1}Đã kích hoạt{else}Chưa kích hoạt{/if}</span></li>
							<li class="d-flex align-items-center mb-3"><i class="bx bx-star fs-5"></i><span class="fw-medium mx-2">Chức danh:</span> <span>{$clsProperty->getTitle($oneItem.role_id)}</span></li>*}
							<li class="d-flex align-items-center mb-3"><i class="bx bx-flag fs-5"></i><span class="fw-medium mx-2">Địa chỉ:</span> <span>{if $oneItem.address ne ""}{$oneItem.address}{else}[Chưa cập nhật]{/if}</span></li> 
							<li class="d-flex align-items-center mb-3"><i class="bx bx-detail fs-5"></i><span class="fw-medium mx-2">Ngôn ngữ:</span> <span>Tiếng Việt</span></li>
						</ul>
						<small class="text-muted text-uppercase">Liên hệ</small>
						<ul class="list-unstyled mb-4 mt-3">
							<li class="d-flex align-items-center mb-3"><i class="bx bx-phone fs-5"></i><span class="fw-medium mx-2">Điện thoại:</span> <span>{$clsClassTable->mask($oneItem.phone,1)}</span></li>
							<li class="d-flex align-items-center mb-3"><i class="bx bx-envelope fs-5" style="color: #a22940"></i><span class="fw-medium mx-2">Email:</span> <span>{$clsClassTable->mask($oneItem.email,1)}</span></li>
							{if !empty($more_information.linkedin)}
								<li class="d-flex align-items-center mb-3"><i class='bx bxl-linkedin-square fs-5' style="color:#0270ad"></i><span class="fw-medium mx-2">LinkedIn:</span> <span>{$more_information.linkedin}</span></li>
							{/if}
							{if !empty($more_information.instagram)}
								<li class="d-flex align-items-center mb-3"><i class='bx bxl-instagram fs-5' style="padding: 0.01rem;background: #f09433;background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);background: -webkit-linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);color: #fff"></i><span class="fw-medium mx-2">Instagram:</span> <span>{$more_information.instagram}</span></li>
							{/if}
							{if !empty($more_information.facebook)}
								<li class="d-flex align-items-center mb-3"><i class='bx bxl-facebook-circle fs-5' style="color:#0863f7"></i><span class="fw-medium mx-2">Facebook:</span> <span>{$more_information.facebook}</span></li>
							{/if}
							{if !empty($more_information.twitter)}
								<li class="d-flex align-items-center mb-3"><i class='bx bxl-twitter fs-5' style="color:#2593e9"></i><span class="fw-medium mx-2">Twitter:</span> <span>{$more_information.twitter}</span></li>
							{/if}
						</ul>
                    </div>
                </div>
            </div>
			{if $deviceType ne 'phone'}
				<div class="card mb-4 no-shadow">
					<h5 class="card-header">Hình ảnh</h5>
					<div class="card-body">
						<div class="form-row row">
							{if !empty($more_information.image)}
								{foreach from=$more_information.image item=image}
									<div class="item col-3 mb-2" data-fancybox="gallery" href="{$image}">
										<img class="rounded drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;height: auto">
									</div>
								{/foreach}
							{/if}
						</div>
					</div>
				</div>
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
						<div class="card-body">
							<h5 class="card-title">Giới thiệu</h5> 
							<div class="tinymce_content content_about">
								{$more_information.about|html_entity_decode} 
							</div>				
						</div>
					</div>	
					{/if}
					{if $more_information.certificate} 
					<div class="card mb-4 no-shadow">
						<div class="card-body">
							<h5 class="card-title">Bằng cấp, chứng chỉ</h5> 
							<div class="tinymce_content content_about">
								{$more_information.certificate|html_entity_decode}
							</div>
						</div>
					</div>	
					{/if}
					{if $more_information.dictum_live} 
					<div class="card mb-4 no-shadow">		
						<div class="card-body">
							<h5 class="card-title">Châm ngôn sống</h5> 
							<div class="tinymce_content content_about">								
								{$more_information.dictum_live|html_entity_decode}
							</div>
						</div>
					</div>	
					{/if}
					{if $more_information.project_joined} 
					<div class="card mb-4 no-shadow">		
						<div class="card-body">
							<h5 class="card-title">Dự án tham gia</h5> 
							<div class="tinymce_content content_about">								
								{$more_information.project_joined|html_entity_decode}
							</div>
						</div>
					</div>	
					{/if}
					{if $more_information.work_process} 
					<div class="card mb-4 no-shadow d-none">		
						<div class="card-body">
							<h5 class="card-title">Quá trình công tác</h5> 
							<div class="tinymce_content content_about">								
								{$more_information.work_process|html_entity_decode}
							</div>
						</div>
					</div>	
					{/if}
				</div>	
				<div class="tab-pane fade {if $deviceType eq 'phone'}show{/if}" id="pills-review" role="tabpanel" aria-labelledby="pills-review-tab">
					<div class="card mb-4">
						<h5 class="card-header">Nhận xét của khách hàng</h5>
						<div class="card-body">
							<ul class="timeline timeline-center mt-5">
								<li class="timeline-item">
									<span class="timeline-indicator timeline-indicator-primary aos-init aos-animate" data-aos="zoom-in" data-aos-delay="200">
									<i class="bx bx-paint"></i>
									</span>
									<div class="timeline-event card p-0 aos-init aos-animate" data-aos="fade-right">
										<h6 class="card-header">Snacks</h6>
										<div class="card-body">
											<div class="d-flex flex-sm-row flex-column">
												<img src="../../assets/img/elements/13.jpg" class="rounded me-3 mb-sm-0 mb-2" alt="doughnut" height="64" width="64" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
												<div>
													<h6 class="mb-2">
														A Donut which straight gone to Your Tummy
													</h6>
													<p class="mb-2">
														I gaze longingly at the beautiful, perfect, plump donut. This
														is a delicately crafted piece of art. The mouthwatering mound
														of miraculous mush isn't able to escape my sight...<a href="javascript:void(0)">read more</a>
													</p>
													<div class="d-flex justify-content-between align-items-center">
														<div>
															<i class="bx bxs-star text-warning"></i>
															<i class="bx bxs-star text-warning"></i>
															<i class="bx bxs-star text-warning"></i>
															<i class="bx bxs-star text-warning"></i>
															<i class="bx bx-star"></i>
														</div>
														<div>
															<span class="fw-medium">$5.00</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="timeline-event-time">10th January</div>
									</div>
								</li>
								<li class="timeline-item">
									<span class="timeline-indicator timeline-indicator-primary aos-init aos-animate" data-aos="zoom-in" data-aos-delay="200">
									<i class="bx bx-paint"></i>
									</span>
									<div class="timeline-event card p-0 aos-init aos-animate" data-aos="fade-left">
										<h6 class="card-header">Snacks</h6>
										<div class="card-body">
											<div class="d-flex flex-sm-row flex-column">
												<img src="../../assets/img/elements/13.jpg" class="rounded me-3 mb-sm-0 mb-2" alt="doughnut" height="64" width="64" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
												<div>
													<h6 class="mb-2">
														A Donut which straight gone to Your Tummy
													</h6>
													<p class="mb-2">
														I gaze longingly at the beautiful, perfect, plump donut. This
														is a delicately crafted piece of art. The mouthwatering mound
														of miraculous mush isn't able to escape my sight...<a href="javascript:void(0)">read more</a>
													</p>
													<div class="d-flex justify-content-between align-items-center">
														<div>
															<i class="bx bxs-star text-warning"></i>
															<i class="bx bxs-star text-warning"></i>
															<i class="bx bxs-star text-warning"></i>
															<i class="bx bxs-star text-warning"></i>
															<i class="bx bx-star"></i>
														</div>
														<div>
															<span class="fw-medium">$5.00</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="timeline-event-time">10th January</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="tab-pane fade {if $deviceType eq 'phone'}show{/if}" id="pills-history-sale" role="tabpanel" aria-labelledby="pills-history-sale-tab"> 
					<div class="card mb-4">
						<div class="table-responsive mb-3">
							<div class="dataTables_wrapper dt-bootstrap5 no-footer">
								<div class="card-header d-flex flex-wrap py-3 py-sm-2">
									<div class="head-label text-center me-4 ms-1">
										<h5 class="card-title mb-0 text-nowrap">Lịch sử bán hàng</h5>
									</div>
									<div id="DataTables_Table_0_filter" class="dataTables_filter"><label><input type="search" class="form-control" placeholder="Search order" aria-controls="DataTables_Table_0" data-per_page="{$per_page}" onkeyup="$Core.broker.search({$oneItem.profile_id},this)" ></label></div>
								</div>
								<table class="table datatables-customer-order border-top dataTable no-footer dtr-column" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 917px;">
									<thead>
										<tr>
											<th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 0px; display: none;" aria-label=""></th>
											<th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 90px;" aria-label="Mã giao dịch" aria-sort="descending">Mã giao dịch</th>
											<th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 148px;" aria-label="Ngày giao dịch">Ngày giao dịch</th>
											{*<th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 192px;" aria-label="Trạng thái">Trạng thái</th>*}
											<th tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 97px;" aria-label="Tổng doanh thu">Tổng doanh thu</th>
										</tr>
									</thead>
									<tbody id="LstBillingSales">
										
									</tbody>
								</table>
								<div class="row mx-4">
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
								<div style="width: 1%;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
            
			{if $deviceType eq 'phone'}
				<div class="card mb-4">
					<h5 class="card-header">Images</h5>
					<div class="card-body">
						<div class="form-row row">
							<div class="col-3 mb-2" data-fancybox="gallery" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png">
								<img class="rounded drag-item cursor-pointer" src="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png" alt="avatar" style="width: 100%;height: auto">
							</div>
							<div class="col-3 mb-2" data-fancybox="gallery" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png">
								<img class="rounded drag-item cursor-pointer" src="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png" alt="avatar" style="width: 100%;height: auto">
							</div>
							<div class="col-3 mb-2" data-fancybox="gallery" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png">
								<img class="rounded drag-item cursor-pointer" src="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png" alt="avatar" style="width: 100%;height: auto">
							</div>
							<div class="col-3 mb-2" data-fancybox="gallery" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png">
								<img class="rounded drag-item cursor-pointer" src="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png" alt="avatar" style="width: 100%;height: auto">
							</div>
							<div class="col-3 mb-2" data-fancybox="gallery" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png">
								<img class="rounded drag-item cursor-pointer" src="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png" alt="avatar" style="width: 100%;height: auto">
							</div>
							<div class="col-3 mb-2" data-fancybox="gallery" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png">
								<img class="rounded drag-item cursor-pointer" src="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png" alt="avatar" style="width: 100%;height: auto">
							</div>
							<div class="col-3 mb-2" data-fancybox="gallery" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png">
								<img class="rounded drag-item cursor-pointer" src="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png" alt="avatar" style="width: 100%;height: auto">
							</div>
							<div class="col-3 mb-2" data-fancybox="gallery" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png">
								<img class="rounded drag-item cursor-pointer" src="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/avatars/1.png" alt="avatar" style="width: 100%;height: auto">
							</div>
						</div>
					</div>
				</div>
				{if $more_information.link_video ne ''}
					<div class="card">
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
</div>
<script>
	var total_number = '{$total_bill}';
	var perPage = '{$per_page}';
	var member_id = '{$oneItem.profile_id}';
</script>
{literal}
<script>
	$('#pagination-container').pagination({
        items: total_number,
        itemsOnPage: perPage,
        prevText: "&laquo;",
        nextText: "&raquo;",
        onPageClick: function (pageNumber) {
			$Core.broker.load_list_billing(member_id,{page:pageNumber,perPage:perPage})
        }
    });
</script>
{/literal}