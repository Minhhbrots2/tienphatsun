<link rel="stylesheet" type="text/css" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$upd_version}" media="all" />
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/html2canvas.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.js?v={$upd_version}"></script>
<main class="content-wrapper"> <div class="container-xxl flex-grow-1 container-p-y">
	<div class="pagetitle d-flex align-items-center justify-content-between">
		<div class="p__left">
			<h1>Chỉnh sửa hồ sơ nhân viên</h1>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{$PCMS_URL}">Trang chủ</a></li>
					<li class="breadcrumb-item active">Nhân viên</li>
					<li class="breadcrumb-item active">{$oneEditProfile.full_name}</li>
				</ol>
			</nav>
		</div>
		<div class="p__right">
			<a  class="btn btn-outline-primary" href="{$PCMS_URL}/staff.html">
				<span>Quay lại</span>
			</a>
		</div>
	</div>
    <!-- End Page Title -->
	<section class="section profile">
		<div class="row">
			<div class="col-xl-4">
				<form method="POST" id="frmIssue" enctype="multipart/form-data">
					<div class="card">
						<div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
							<div class="db_avatar position-relative">
								<img id="avatar" src="{$oneEditProfile.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" 
								alt="Profile" class="rounded-circle">
								<a href="javascript:void(0);" profile_id="{$profile_id}" class="camera" onclick="file_explorer(this,event)" toId="selectFile" toImg="avatar"><i class="bx bx-camera"></i></a>
								<input type="file" id="selectFile" class="d-none" maxlength="255" name="avatar" >
							</div>
							<h2>{$oneEditProfile.full_name}</h2>
							<h5 class="fs-14">{$clsProperty->getTitle($oneEditProfile.department_id)} ({$clsProperty->getTitle($oneEditProfile.role_id)})</h4>
						</div>
					</div>
				</form>
			</div>
			<div class="col-xl-8">
				<div class="card">
					<div class="card-body pt-3">
						<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
							<li class="nav-item" role="presentation">
								<button href="{$clsProfile->getLinkEdit($profile_id,'edit')}" class="nav-link gotoLink{if $tabpanel eq 'edit'} active{/if}" role="tab">Chỉnh sửa hồ sơ</button>
							</li>
							<li class="nav-item" role="presentation">
								<button href="{$clsProfile->getLinkEdit($profile_id,'bank')}" class="nav-link gotoLink{if $tabpanel eq 'bank'} active{/if}" role="tab">TK ngân hàng</button>
							</li>
							<li class="nav-item" role="presentation">
								<button href="{$clsProfile->getLinkEdit($profile_id,'password')}" class="nav-link gotoLink{if $tabpanel eq 'password'} active{/if}" role="tab" tabindex="-1">Đổi mật khẩu</button>
							</li>
						</ul>
						<div class="tab-content pt-3">
							{if $tabpanel eq 'edit'}
							<div class="tab-pane fade active show pt-3" role="tabpanel">
								<!-- Profile Edit Form -->
								<form method="post">
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3">Email</label>
										<div class="col-md-8 col-lg-9">{$oneEditProfile.email}</div>
									</div>
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3 col-form-label">Mã nhân viên</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-code"></i></span>
												<input type="text" name="code" value="{$oneEditProfile.code}" class="form-control" placeholder="Mã nhân viên" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="fullName" class="col-md-4 col-lg-3 col-form-label">Họ và tên</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-user"></i></span>
												<input type="text" name="full_name" class="form-control" id="basic-icon-default-fullname" value="{$oneEditProfile.full_name}" placeholder="John Doe" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Address"
											class="col-md-4 col-lg-3 col-form-label">Địa chỉ</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-map"></i></span>
												<input type="text" name="address" value="{$oneEditProfile.address}" class="form-control" placeholder="Địa chỉ" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Address" class="col-md-4 col-lg-3 col-form-label">Tỉnh/Thành phố</label>
										<div class="col-md-8 col-lg-9">
											<div class="row">
												<div class="col-6 col-md-6">
													<select name="city_id" id="slb_City_Id" toId="slb_District_Id" class="form-control form-select slb_City">
														<option value="0">Tỉnh/Thành phố</option>
														{section name=i loop=$list_cities}
														<option value="{$list_cities[i].city_id}"{if $oneEditProfile.city_id eq $list_cities[i].city_id} selected{/if}>{$list_cities[i].title}</option>
														{/section}
													</select>
												</div>
												<div class="col-6 col-md-6">
													<select class="form-control form-select slb_District" id="slb_District_Id" name="district_id">
													{if !empty($list_districts)}
														{section name=i loop=$list_districts}
														<option value="{$list_districts[i].district_id}"{if $oneEditProfile.district_id eq $list_districts[i].district_id} selected{/if}>{$list_districts[i].title}</option>
														{/section}
													{else}
														<option value="0">Quận/Huyện</option>
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
												<input name="phone" type="text" class="form-control" placeholder="Số điện thoại" autocomplete="off" value="{$oneEditProfile.phone}" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Phone" class="col-md-4 col-lg-3 col-form-label">CCID</label>
										<div class="col-md-8 col-lg-9">
											 <div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxs-barcode"></i></span>
												<input name="CCID" type="text" class="form-control" placeholder="CCID" autocomplete="off" value="{$oneEditProfile.CCID}" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3 col-form-label">Ngày sinh</label>
										<div class="col-md-8 col-lg-9">
										   <input type="date" name="birthday" class="form-control" value="{$clsISO->toYMD($oneEditProfile.birthday)}">
										</div>
									</div>
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3 col-form-label">Ngày bắt đầu</label>
										<div class="col-md-8 col-lg-9">
										   <input type="date" name="start_date" class="form-control" value="{$clsISO->toYMD($oneEditProfile.start_date)}">
										</div>
									</div>
									<div class="row mb-3">
										<label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-twitter"></i></span>
												<input name="twitter" type="text" id="Twitter" class="form-control" placeholder="https://twitter.com/#" autocomplete="off" value="{if !empty($more_info.twitter)}{$more_info.twitter}{/if}" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-facebook"></i></span>
												<input type="text" name="facebook" id="Facebook" class="form-control" placeholder="https://www.facebook.com/#" autocomplete="off" value="{if !empty($more_info.facebook)}{$more_info.facebook}{/if}" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-instagram"></i></span>
												<input name="instagram" type="text" placeholder="https://instagram.com/#" class="form-control" id="Instagram" value="{if !empty($more_info.instagram)}{$more_info.instagram}{/if}">
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-linkedin"></i></span>
												<input name="linkedin" type="text" placeholder="https://linkedin.com/#" class="form-control" id="Linkedin" value="{if !empty($more_info.linkedin)}{$more_info.linkedin}{/if}">
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
									{if $oneEditProfile.oauth_provider eq '_register'}
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