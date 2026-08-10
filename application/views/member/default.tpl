<link rel="stylesheet" type="text/css" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$upd_version}" media="all" />
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/html2canvas.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.js?v={$upd_version}"></script>
{assign var = more_information value= $oneProfile.more_information}
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
    <!-- End Page Title -->
	<section class="section profile">
		<div class="row">
			<div class="col-xl-3">
				<form method="POST" id="frmIssue" enctype="multipart/form-data">
					<div class="card mb-2">
						<div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
							<div class="db_avatar position-relative">
								<img id="avatar" src="{$clsProfile->getAvatar($profile_id,$oneProfile,120,120)}" alt="Profile" class="rounded-circle">
								<a href="javascript:void(0);" profile_id="{$profile_id}" class="camera" onclick="file_explorer(this,event)" toId="selectFile" toImg="avatar"><i class="bx bx-camera"></i></a>
								<input type="file" id="selectFile" class="d-none" maxlength="255" name="avatar" >
							</div>
							<h2>{$oneProfile.full_name}</h2>
							<h5 class="fs-14">{$clsProperty->getTitle($oneProfile.department_id)} ({$clsProperty->getTitle($oneProfile.role_id)})</h4>
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="text-light small fw-semibold mb-1">Default</div>
								<div class="progress mb-3">
									<div class="progress-bar bg-primary shadow-none" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="text-light small fw-semibold mb-1">Default</div>
								<div class="progress mb-3">
									<div class="progress-bar bg-primary shadow-none" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="text-light small fw-semibold mb-1">Default</div>
								<div class="progress mb-3">
									<div class="progress-bar bg-primary shadow-none" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="text-light small fw-semibold mb-1">Default</div>
								<div class="progress mb-3">
									<div class="progress-bar bg-primary shadow-none" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
						</div>
					</div>
					
				</form>
			</div>
			<div class="col-xl-9">
				<div class="card">
					<div class="card-body pt-3">
						<!-- Bordered Tabs -->
						<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
							<li class="nav-item" role="presentation">
								<button href="/profile.html" class="nav-link gotoLink{if $tabpanel eq 'overview'} active{/if}" role="tab">Tổng quan</button>
							</li>
							<li class="nav-item" role="presentation">
								<button href="/profile/edit.html" class="nav-link gotoLink{if $tabpanel eq 'edit'} active{/if}" role="tab">Chỉnh sửa hồ sơ</button>
							</li>
							<li class="nav-item" role="presentation">
								<button href="/profile/bank.html" class="nav-link gotoLink{if $tabpanel eq 'bank'} active{/if}" role="tab">TK ngân hàng</button>
							</li>
							<li class="nav-item" role="presentation">
								<button href="/profile/password.html" class="nav-link gotoLink{if $tabpanel eq 'password'} active{/if}" role="tab" tabindex="-1">Đổi mật khẩu</button>
							</li>
						</ul>
						<div class="tab-content pt-3">
							{if $tabpanel eq 'overview'}
							{if $_ss_update_sucuess eq '1'}
							<div class="alert alert-success alert-dismissible" role="alert">
								Cập nhật thành công !
								<button type="button" class="btn-close" data-bs-dismiss="alert" 
								aria-label="Close"></button>
							</div>
							{/if}
							<div class="tab-pane fade active show" role="tabpanel">
								<h5 class="card-title">Giới thiệu</h5>
								<p class="small fst-italic">
									{if !empty($more_information.about)}
										{$more_information.about}
									{else}
										Chưa cập nhật...
									{/if}
								</p>
								<hr />
								<h5 class="card-title">Thông tin hồ sơ</h5>
								<div class="row mb-2">
									<div class="col-lg-3 col-md-4 label">Full Name</div>
									<div class="col-lg-9 col-md-8">{$oneProfile.full_name}</div>
								</div>
								<div class="row mb-2">
									<div class="col-lg-3 col-md-4 label">Address</div>
									<div class="col-lg-9 col-md-8">{$oneProfile.address}</div>
								</div>
								<div class="row mb-2">
									<div class="col-lg-3 col-md-4 label">Phone</div>
									<div class="col-lg-9 col-md-8">{$oneProfile.phone}</div>
								</div>
								<div class="row mb-2">
									<div class="col-lg-3 col-md-4 label">Email</div>
									<div class="col-lg-9 col-md-8">{$oneProfile.email}</div>
								</div>
								{if !empty($banks_info)}
								<hr />
								<h5 class="card-title">Tài khoản ngân hàng</h5>
								<ul class="list-banks">
									{foreach name=i from=$banks_info item = _obank}
									<li>
										<p class="mb-1">{$_obank.account_person} - {$_obank.bank_name}</p>
										<strong>{$_obank.account_number}</strong>
									</li>
									{/foreach}
								</ul>
								{else}
									<div class="empty-result text-center">
										<img src="{$smarty.const._IMG_NODOCUMENT}" width="40px" />
										<p>Không có dữ liệu</p>
									</div>
								{/if}
							</div>
							{elseif $tabpanel eq 'edit'}
							<div class="tab-pane fade active show pt-3" role="tabpanel">
								<!-- Profile Edit Form -->
								<form method="post">
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3">Mã nhân viên</label>
										<div class="col-md-8 col-lg-9">{$oneProfile.code}</div>
									</div>
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3">Email</label>
										<div class="col-md-8 col-lg-9">{$oneProfile.email}</div>
									</div>
									<div class="row mb-3">
										<label for="fullName" class="col-md-4 col-lg-3 col-form-label">Họ và tên</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-user"></i></span>
												<input type="text" name="full_name" class="form-control" id="basic-icon-default-fullname" value="{$oneProfile.full_name}" placeholder="John Doe" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Address"
											class="col-md-4 col-lg-3 col-form-label">Địa chỉ</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-map"></i></span>
												<input type="text" name="address" value="{$oneProfile.address}" class="form-control" placeholder="Địa chỉ" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Address" class="col-md-4 col-lg-3 col-form-label">Tỉnh/Thành phố</label>
										<div class="col-md-8 col-lg-9">
											<div class="row">
												<div class="col-6 col-md-6">
													<select name="city_id" id="slb_City_Id" toId="slb_District_Id" class="form-control form-select slb_City">
														{section name=i loop=$list_cities}
														<option value="{$list_cities[i].city_id}"{if $oneProfile.city_id eq $list_cities[i].city_id} selected{/if}>{$list_cities[i].title}</option>
														{/section}
													</select>
												</div>
												<div class="col-6 col-md-6">
													<select class="form-control form-select slb_District" id="slb_District_Id" name="district_id">
													{if !empty($list_districts)}
														{section name=i loop=$list_districts}
														<option value="{$list_districts[i].district_id}"{if $oneProfile.district_id eq $list_districts[i].district_id} selected{/if}>{$list_districts[i].title}</option>
														{/section}
													{/if}
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Phone" class="col-md-4 col-lg-3 col-form-label">Điện thoại</label>
										<div class="col-md-8 col-lg-9">
											 <div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-phone"></i></span>
												<input name="phone" type="text" class="form-control" placeholder="Số điện thoại" autocomplete="off" value="{$oneProfile.phone}" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Phone" class="col-md-4 col-lg-3 col-form-label">CCID</label>
										<div class="col-md-8 col-lg-9">
											 <div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxs-barcode"></i></span>
												<input name="CCID" type="text" class="form-control" placeholder="CCID" autocomplete="off" value="{$oneProfile.CCID}" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3 col-form-label">Ngày sinh</label>
										<div class="col-md-8 col-lg-9">
										   <input type="date" name="birthday" class="form-control" value="{$clsISO->toYMD($oneProfile.birthday)}">
										</div>
									</div>
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3 col-form-label">Ngày bắt đầu</label>
										<div class="col-md-8 col-lg-9">
										   <input type="date" name="start_date" class="form-control" value="{$clsISO->toYMD($oneProfile.start_date)}">
										</div>
									</div>
									<div class="row mb-3">
										<label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-twitter"></i></span>
												<input name="twitter" type="text" id="Twitter" class="form-control" placeholder="https://twitter.com/#" autocomplete="off" value="{if !empty($more_information.twitter)}{$more_information.twitter}{/if}" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-facebook"></i></span>
												<input type="text" name="facebook" id="Facebook" class="form-control" placeholder="https://www.facebook.com/#" autocomplete="off" value="{if !empty($more_information.facebook)}{$more_information.facebook}{/if}" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-instagram"></i></span>
												<input name="instagram" type="text" placeholder="https://instagram.com/#" class="form-control" id="Instagram" value="{if !empty($more_information.instagram)}{$more_information.instagram}{/if}">
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-linkedin"></i></span>
												<input name="linkedin" type="text" placeholder="https://linkedin.com/#" class="form-control" id="Linkedin" value="{if !empty($more_information.linkedin)}{$more_information.linkedin}{/if}">
											</div>
										</div>
									</div>
									<div class="text-center">
										<input type="hidden" name="tabpanel" value="update_profile" />
										<input type="hidden" name="submit" value="update_profile" />
										<button type="submit" class="btn btn-primary">Lưu lại</button>
									</div>
								</form>
								<!-- End Profile Edit Form -->
							</div>
							{elseif $tabpanel eq 'bank'}
							<div class="tab-pane fade active show pt-3" role="tabpanel">
								<!-- Settings Form -->
								<form method="POST">
									<div class="banks mb-3">
										<div class="holder_banks">
											{if !empty($banks_info)}
												{foreach from = $banks_info key = uid item=_oBank}
												<div class="bank-item">
													<a class="remove_bank" onclick="remove_bank(this, event)"></a>
													<div class="mb-3">
														<label class="colf-form-label">Số tài khoản</label>
														<input type="text" class="form-control account_number numberonly" value="{$_oBank.account_number}" name="banks_info[{$uid}][account_number]" placeholder="Số tài khoản">
													</div>
													<div class="mb-3">
														<label class="colf-form-label">Chủ tài khoản</label>
														<input type="text" class="form-control" value="{$_oBank.account_person}"
															name="banks_info[{$uid}][account_person]" placeholder="Chủ tài khoản">
													</div>
													<div class="mb-3 row">
														<div class="col-xs-12 col-md-6 pr-0">
															<label class="colf-form-label">Ngân hàng</label>
															<input type="text" class="form-control" value="{$_oBank.bank_name}"
																name="banks_info[{$uid}][bank_name]" placeholder="Tên ngân hàng">
														</div>
														<div class="col-xs-12 col-md-6">
															<label class="colf-form-label">Chi nhánh</label>
															<input type="text" class="form-control" value="{$_oBank.location}"
																name="banks_info[{$uid}][location]" placeholder="Tên chi nhánh (nếu có)">
														</div>
													</div>
												</div>
												{/foreach}
											{else}
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="bank-item">
												<a class="remove_bank" onclick="remove_bank(this, event)"></a>
												<div class="mb-3">
													<label class="colf-form-label">Số tài khoản</label>
													<input type="text" class="form-control account_number numberonly"
														name="banks_info[{$uid}][account_number]" placeholder="Số tài khoản">
												</div>
												<div class="mb-3">
													<label class="colf-form-label">Chủ tài khoản</label>
													<input type="text" class="form-control" name="banks_info[{$uid}][account_person]"
														placeholder="Chủ tài khoản">
												</div>
												<div class="mb-3 row">
													<div class="col-xs-12 col-md-6 pr-0">
														<label class="colf-form-label">Ngân hàng</label>
														<input type="text" class="form-control" name="banks_info[{$uid}][bank_name]" placeholder="Tên ngân hàng">
													</div>
													<div class="col-xs-12 col-md-6">
														<label class="colf-form-label">Chi nhánh</label>
														<input type="text" class="form-control" name="banks_info[{$uid}][location]"
															placeholder="Tên chi nhánh (nếu có)">
													</div>
												</div>
											</div>
											{/if}
										</div>
									</div>
									<div class="text-center">
										<input type="hidden" name="submit" value="update_bank" />	
										<button type="button" id="add_branch_bank" onclick="add_bank(this, event)"
											class="btn btn-outline-primary text-bold">
											{$clsISO->makeIcon('bx-plus-circle', 'Thêm mới')}
										</button>
										<button type="submit" class="btn btn-primary"> Lưu lại</button>
									</div>
								</form>
								<!-- End settings Form -->
							</div>
							{elseif $tabpanel eq 'password'}
							<div class="tab-pane active show fade pt-3" role="tabpanel">
								{if !empty($error_msg)}
								<div class="alert alert-danger alert-dismissible" role="alert">
									{$error_msg}
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								{/if}
								<!-- Change Password Form -->
								 <form method="POST">
									{if $oneProfile.oauth_provider eq '_register'}
									<div class="row mb-3 form-password-toggle">
										<label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Mật khẩu hiện tại</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-lock"></i></span>
												<input name="current_password" required type="password" class="form-control"
												id="currentPassword">
												<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
											</div>
										</div>
									</div>
									{/if}
									<div class="row mb-3 form-password-toggle">
										<label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Mật khẩu mới</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-lock"></i></span>
												<input name="new_password" type="password" required class="form-control"
												id="newPassword">
												<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
											</div>
										</div>
									</div>
									<div class="row mb-3 form-password-toggle">
										<label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Xác nhận MK</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-lock"></i></span>
												<input name="confirm_password" type="password" required class="form-control"
												id="renewPassword">
												<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
											</div>
										</div>
									</div>
									<div class="text-center">
										<input type="hidden" name="tabpanel" value="update_pass" />
										<input type="hidden" name="submit" value="update_pass" />
										<button type="submit" class="btn btn-primary">Cập nhật</button>
									</div>
								</form>
								<!-- End Change Password Form -->
							</div>
							{/if}
						</div>
						<!-- End Bordered Tabs -->
					</div>
				</div>
			</div>
		</div>
	</section>
</div></main>