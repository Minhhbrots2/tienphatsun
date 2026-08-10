<?php
/* Smarty version 3.1.33, created on 2026-08-07 17:30:40
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75b3d04f4be8_32392676',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '27ee3ed266b9a0fa411c8ae07d1e7f0964bb92f6' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/default.tpl',
      1 => 1786085969,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75b3d04f4be8_32392676 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" type="text/css" href="<?php echo @constant('DOMAIN_URL');?>
/cropper/cropper.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" media="all" />
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('DOMAIN_URL');?>
/cropper/html2canvas.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('DOMAIN_URL');?>
/cropper/cropper.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['oneProfile']->value['more_information']);?>
<main class="content-wrapper"> <div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="pagetitle">
		<h1>Profile</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
">Trang chủ</a></li>
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
								<img id="avatar" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value,120,120);?>
" alt="Profile" class="rounded-circle">
								<a href="javascript:void(0);" profile_id="<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
" class="camera" onclick="file_explorer(this,event)" toId="selectFile" toImg="avatar"><i class="bx bx-camera"></i></a>
								<input type="file" id="selectFile" class="d-none" maxlength="255" name="avatar" >
							</div>
							<h2><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['full_name'];?>
</h2>
							<h5 class="fs-14"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneProfile']->value['department_id']);?>
 (<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneProfile']->value['role_id']);?>
)</h4>
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
								<button href="/profile.html" class="nav-link gotoLink<?php if ($_smarty_tpl->tpl_vars['tabpanel']->value == 'overview') {?> active<?php }?>" role="tab">Tổng quan</button>
							</li>
							<li class="nav-item" role="presentation">
								<button href="/profile/edit.html" class="nav-link gotoLink<?php if ($_smarty_tpl->tpl_vars['tabpanel']->value == 'edit') {?> active<?php }?>" role="tab">Chỉnh sửa hồ sơ</button>
							</li>
							<li class="nav-item" role="presentation">
								<button href="/profile/bank.html" class="nav-link gotoLink<?php if ($_smarty_tpl->tpl_vars['tabpanel']->value == 'bank') {?> active<?php }?>" role="tab">TK ngân hàng</button>
							</li>
							<li class="nav-item" role="presentation">
								<button href="/profile/password.html" class="nav-link gotoLink<?php if ($_smarty_tpl->tpl_vars['tabpanel']->value == 'password') {?> active<?php }?>" role="tab" tabindex="-1">Đổi mật khẩu</button>
							</li>
						</ul>
						<div class="tab-content pt-3">
							<?php if ($_smarty_tpl->tpl_vars['tabpanel']->value == 'overview') {?>
							<?php if ($_smarty_tpl->tpl_vars['_ss_update_sucuess']->value == '1') {?>
							<div class="alert alert-success alert-dismissible" role="alert">
								Cập nhật thành công !
								<button type="button" class="btn-close" data-bs-dismiss="alert" 
								aria-label="Close"></button>
							</div>
							<?php }?>
							<div class="tab-pane fade active show" role="tabpanel">
								<h5 class="card-title">Giới thiệu</h5>
								<p class="small fst-italic">
									<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['about'])) {?>
										<?php echo $_smarty_tpl->tpl_vars['more_information']->value['about'];?>

									<?php } else { ?>
										Chưa cập nhật...
									<?php }?>
								</p>
								<hr />
								<h5 class="card-title">Thông tin hồ sơ</h5>
								<div class="row mb-2">
									<div class="col-lg-3 col-md-4 label">Full Name</div>
									<div class="col-lg-9 col-md-8"><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['full_name'];?>
</div>
								</div>
								<div class="row mb-2">
									<div class="col-lg-3 col-md-4 label">Address</div>
									<div class="col-lg-9 col-md-8"><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['address'];?>
</div>
								</div>
								<div class="row mb-2">
									<div class="col-lg-3 col-md-4 label">Phone</div>
									<div class="col-lg-9 col-md-8"><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['phone'];?>
</div>
								</div>
								<div class="row mb-2">
									<div class="col-lg-3 col-md-4 label">Email</div>
									<div class="col-lg-9 col-md-8"><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['email'];?>
</div>
								</div>
								<?php if (!empty($_smarty_tpl->tpl_vars['banks_info']->value)) {?>
								<hr />
								<h5 class="card-title">Tài khoản ngân hàng</h5>
								<ul class="list-banks">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['banks_info']->value, '_obank', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_obank']->value) {
?>
									<li>
										<p class="mb-1"><?php echo $_smarty_tpl->tpl_vars['_obank']->value['account_person'];?>
 - <?php echo $_smarty_tpl->tpl_vars['_obank']->value['bank_name'];?>
</p>
										<strong><?php echo $_smarty_tpl->tpl_vars['_obank']->value['account_number'];?>
</strong>
									</li>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</ul>
								<?php } else { ?>
									<div class="empty-result text-center">
										<img src="<?php echo @constant('_IMG_NODOCUMENT');?>
" width="40px" />
										<p>Không có dữ liệu</p>
									</div>
								<?php }?>
							</div>
							<?php } elseif ($_smarty_tpl->tpl_vars['tabpanel']->value == 'edit') {?>
							<div class="tab-pane fade active show pt-3" role="tabpanel">
								<!-- Profile Edit Form -->
								<form method="post">
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3">Mã nhân viên</label>
										<div class="col-md-8 col-lg-9"><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['code'];?>
</div>
									</div>
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3">Email</label>
										<div class="col-md-8 col-lg-9"><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['email'];?>
</div>
									</div>
									<div class="row mb-3">
										<label for="fullName" class="col-md-4 col-lg-3 col-form-label">Họ và tên</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-user"></i></span>
												<input type="text" name="full_name" class="form-control" id="basic-icon-default-fullname" value="<?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['full_name'];?>
" placeholder="John Doe" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Address"
											class="col-md-4 col-lg-3 col-form-label">Địa chỉ</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bx-map"></i></span>
												<input type="text" name="address" value="<?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['address'];?>
" class="form-control" placeholder="Địa chỉ" autocomplete="off" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Address" class="col-md-4 col-lg-3 col-form-label">Tỉnh/Thành phố</label>
										<div class="col-md-8 col-lg-9">
											<div class="row">
												<div class="col-6 col-md-6">
													<select name="city_id" id="slb_City_Id" toId="slb_District_Id" class="form-control form-select slb_City">
														<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_cities']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
														<option value="<?php echo $_smarty_tpl->tpl_vars['list_cities']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['city_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['oneProfile']->value['city_id'] == $_smarty_tpl->tpl_vars['list_cities']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['city_id']) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['list_cities']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'];?>
</option>
														<?php
}
}
?>
													</select>
												</div>
												<div class="col-6 col-md-6">
													<select class="form-control form-select slb_District" id="slb_District_Id" name="district_id">
													<?php if (!empty($_smarty_tpl->tpl_vars['list_districts']->value)) {?>
														<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_districts']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = $__section_i_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
														<option value="<?php echo $_smarty_tpl->tpl_vars['list_districts']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['district_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['oneProfile']->value['district_id'] == $_smarty_tpl->tpl_vars['list_districts']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['district_id']) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['list_districts']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'];?>
</option>
														<?php
}
}
?>
													<?php }?>
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
												<input name="phone" type="text" class="form-control" placeholder="Số điện thoại" autocomplete="off" value="<?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['phone'];?>
" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Phone" class="col-md-4 col-lg-3 col-form-label">CCID</label>
										<div class="col-md-8 col-lg-9">
											 <div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxs-barcode"></i></span>
												<input name="CCID" type="text" class="form-control" placeholder="CCID" autocomplete="off" value="<?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['CCID'];?>
" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3 col-form-label">Ngày sinh</label>
										<div class="col-md-8 col-lg-9">
										   <input type="date" name="birthday" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->toYMD($_smarty_tpl->tpl_vars['oneProfile']->value['birthday']);?>
">
										</div>
									</div>
									<div class="row mb-3">
										<label for="Email" class="col-md-4 col-lg-3 col-form-label">Ngày bắt đầu</label>
										<div class="col-md-8 col-lg-9">
										   <input type="date" name="start_date" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->toYMD($_smarty_tpl->tpl_vars['oneProfile']->value['start_date']);?>
">
										</div>
									</div>
									<div class="row mb-3">
										<label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-twitter"></i></span>
												<input name="twitter" type="text" id="Twitter" class="form-control" placeholder="https://twitter.com/#" autocomplete="off" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['twitter'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['twitter'];
}?>" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-facebook"></i></span>
												<input type="text" name="facebook" id="Facebook" class="form-control" placeholder="https://www.facebook.com/#" autocomplete="off" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['facebook'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['facebook'];
}?>" />
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-instagram"></i></span>
												<input name="instagram" type="text" placeholder="https://instagram.com/#" class="form-control" id="Instagram" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['instagram'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['instagram'];
}?>">
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin</label>
										<div class="col-md-8 col-lg-9">
											<div class="input-group input-group-merge">
												<span class="input-group-text"><i class="bx bxl-linkedin"></i></span>
												<input name="linkedin" type="text" placeholder="https://linkedin.com/#" class="form-control" id="Linkedin" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['linkedin'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['linkedin'];
}?>">
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
							<?php } elseif ($_smarty_tpl->tpl_vars['tabpanel']->value == 'bank') {?>
							<div class="tab-pane fade active show pt-3" role="tabpanel">
								<!-- Settings Form -->
								<form method="POST">
									<div class="banks mb-3">
										<div class="holder_banks">
											<?php if (!empty($_smarty_tpl->tpl_vars['banks_info']->value)) {?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['banks_info']->value, '_oBank', false, 'uid');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['uid']->value => $_smarty_tpl->tpl_vars['_oBank']->value) {
?>
												<div class="bank-item">
													<a class="remove_bank" onclick="remove_bank(this, event)"></a>
													<div class="mb-3">
														<label class="colf-form-label">Số tài khoản</label>
														<input type="text" class="form-control account_number numberonly" value="<?php echo $_smarty_tpl->tpl_vars['_oBank']->value['account_number'];?>
" name="banks_info[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][account_number]" placeholder="Số tài khoản">
													</div>
													<div class="mb-3">
														<label class="colf-form-label">Chủ tài khoản</label>
														<input type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oBank']->value['account_person'];?>
"
															name="banks_info[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][account_person]" placeholder="Chủ tài khoản">
													</div>
													<div class="mb-3 row">
														<div class="col-xs-12 col-md-6 pr-0">
															<label class="colf-form-label">Ngân hàng</label>
															<input type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oBank']->value['bank_name'];?>
"
																name="banks_info[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][bank_name]" placeholder="Tên ngân hàng">
														</div>
														<div class="col-xs-12 col-md-6">
															<label class="colf-form-label">Chi nhánh</label>
															<input type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oBank']->value['location'];?>
"
																name="banks_info[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][location]" placeholder="Tên chi nhánh (nếu có)">
														</div>
													</div>
												</div>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											<?php } else { ?>
											<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
											<div class="bank-item">
												<a class="remove_bank" onclick="remove_bank(this, event)"></a>
												<div class="mb-3">
													<label class="colf-form-label">Số tài khoản</label>
													<input type="text" class="form-control account_number numberonly"
														name="banks_info[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][account_number]" placeholder="Số tài khoản">
												</div>
												<div class="mb-3">
													<label class="colf-form-label">Chủ tài khoản</label>
													<input type="text" class="form-control" name="banks_info[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][account_person]"
														placeholder="Chủ tài khoản">
												</div>
												<div class="mb-3 row">
													<div class="col-xs-12 col-md-6 pr-0">
														<label class="colf-form-label">Ngân hàng</label>
														<input type="text" class="form-control" name="banks_info[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][bank_name]" placeholder="Tên ngân hàng">
													</div>
													<div class="col-xs-12 col-md-6">
														<label class="colf-form-label">Chi nhánh</label>
														<input type="text" class="form-control" name="banks_info[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][location]"
															placeholder="Tên chi nhánh (nếu có)">
													</div>
												</div>
											</div>
											<?php }?>
										</div>
									</div>
									<div class="text-center">
										<input type="hidden" name="submit" value="update_bank" />	
										<button type="button" id="add_branch_bank" onclick="add_bank(this, event)"
											class="btn btn-outline-primary text-bold">
											<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-plus-circle','Thêm mới');?>

										</button>
										<button type="submit" class="btn btn-primary"> Lưu lại</button>
									</div>
								</form>
								<!-- End settings Form -->
							</div>
							<?php } elseif ($_smarty_tpl->tpl_vars['tabpanel']->value == 'password') {?>
							<div class="tab-pane active show fade pt-3" role="tabpanel">
								<?php if (!empty($_smarty_tpl->tpl_vars['error_msg']->value)) {?>
								<div class="alert alert-danger alert-dismissible" role="alert">
									<?php echo $_smarty_tpl->tpl_vars['error_msg']->value;?>

									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								<?php }?>
								<!-- Change Password Form -->
								 <form method="POST">
									<?php if ($_smarty_tpl->tpl_vars['oneProfile']->value['oauth_provider'] == '_register') {?>
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
									<?php }?>
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
							<?php }?>
						</div>
						<!-- End Bordered Tabs -->
					</div>
				</div>
			</div>
		</div>
	</section>
</div></main><?php }
}
