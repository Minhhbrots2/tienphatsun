<?php
/* Smarty version 3.1.33, created on 2026-07-30 14:13:35
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/user/edit.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6af99fdd0df5_85536196',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4ac5aadc12bfdba55ce577e729bd7d84b40e0497' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/user/edit.tpl',
      1 => 1784691759,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6af99fdd0df5_85536196 (Smarty_Internal_Template $_smarty_tpl) {
?><header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default ui-breadcrumb">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>

					<span class="ui-breadcrumb__item"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Administrators');?>
</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title"><?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {?>Cập nhật<?php } else { ?>Thêm<?php }?> tài khoản quản trị</h1>
			</div>
		</div>
	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>
</header>
<div class="clearfix"></div>
<form method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Tài khoản quản trị</h2>
								</div>
								<div class="ui-annotated-section__description">
									Tất cả thông tin liên quan đến tài khoản, bao gồm cả Quyền truy cập các tính năng trong trang quản trị.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="form-group">
													<div class="col-md-6">
														<label class="col-form-label">Họ <span class="text-red">*</span></label>
														<input type="text" class="form-control required" required="true" placeholder="Nhập tên website" name="first_name" id="first_name" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['first_name'];?>
" aria-required="true" placeholder="Nhập Họ" />
													</div>
													<div class="col-md-6">
														<label class="col-form-label">Tên <span class="text-red">*</span></label>
														<input type="text" class="form-control required" required="true" placeholder="Nhập tên website" name="last_name" id="last_name" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['last_name'];?>
" aria-required="true" placeholder="Nhập tên" />
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-6">
														<label class="col-form-label">Tên đăng nhập<span class="text-red">*</span></label>
														<input type="text" class="form-control required" required="true" name="user_name" id="user_name" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['user_name'];?>
" aria-required="true" placeholder="Nhập Username" />
													</div>
													<div class="col-md-6">
														<label class="col-form-label">Điện thoại <span class="text-gray">(tùy chọn)</span></label>
														<input type="text" class="form-control" name="phone" id="phone" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['phone'];?>
" aria-required="true" placeholder="Nhập Điện thoại" />
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-6">
														<label class="col-form-label">E-Mail<span class="text-red">*</span></label>
														<input type="text" class="form-control required" required="true" name="email" id="email" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['email'];?>
" aria-required="true" placeholder="Nhập E-Mail" />
													</div>
													<div class="col-md-6"></div>
												</div>
												<div class="form-group">
													<div class="col-md-12">
														<label class="col-form-label">Thông tin giới thiệu <span class="text-gray">(tùy chọn)</span></label>
														<textarea rows="3" class="form-control" placeholder="Nhập thông tin giới thiệu" name="about"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['about'];?>
</textarea>
													</div>
												</div>
												<div class="form-group lines">
													<div class="col-md-6">
														<label class="col-form-label">Mật khẩu<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value == '0') {?><span class="text-red">*</span><?php }?></label>
														<input type="password" <?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {?>class="form-control"<?php } else { ?>class="form-control required" required<?php }?> placeholder="Nhập mật khẩu" name="pass1" id="pass1" aria-required="true" />
														<span class="help-block text-gray"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Enter only if you want to change the password');?>
</span>
													</div>
													<div class="col-md-6">
														<label class="col-form-label">Xác nhận mật khẩu<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value == '0') {?><span class="text-red">*</span><?php }?></label>
														<input type="password" <?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {?>class="form-control"<?php } else { ?>class="form-control required" required<?php }?> placeholder="Xác nhận mật khẩu" name="pass2" id="pass2" aria-required="true" />
														<span class="help-block text-gray"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Enter only if you want to change the password');?>
</span>
													</div>
												</div>
												<div class="form-group lines">
													<div class="col-md-12">
														<label class="col-form-label">Toàn quyền <span class="text-gray">(tùy chọn)</span></label>
														<input type="hidden" name="is_super" value="0" />
														<label class="switch pull-right">
															<input type="checkbox" value="1" name="is_super"<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_super'] == '1') {?> checked="checked"<?php }?>>
															<span class="slider round"></span>
														</label>
														<span class="help-block">Cho phép quản trị viên truy suất tất cả chức năng trong hệ thống</span>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-12">
														<label class="col-form-label">Kích hoạt tài khoản.</label>
														<input type="hidden" name="is_active" value="0" />
														<label class="switch pull-right">
															<input type="checkbox" value="1" name="is_active"<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_active'] == '1') {?> checked="checked"<?php }?>>
															<span class="slider round"></span>
														</label>
														<span class="help-block">Cho phép quản được phép truy cập vào trong hệ thống</span>
													</div>
												</div>
											</div>
										</div>
										<div class="next-card__section d-none">
											<div class="next-grid next-grid--no-outside-padding">
												<div class="next-grid__cell">
													<div class="next-grid next-grid--no-outside-padding">
														<div class="next-grid__cell">
															<h3 class="next-heading next-heading--small next-heading--half-margin">Chọn các chức năng tài khoản này có thể sử dụng</h3>
														</div>
													</div>
												</div>
											</div>
											<div class="ui-form__section">
												<div class="row">
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['listModule']->value, 'module');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['module']->value) {
?>
													<div class="col-md-6 col-xs-12 mb-1">
														<div class="custom-checkbox-wrapper core-checkbox-custom">
															<label>
																<input<?php if ($_smarty_tpl->tpl_vars['core']->value->checkActiveModule($_smarty_tpl->tpl_vars['module']->value['name'])) {?> disabled="disabled"<?php }?> name="permiss_mod[]" value="<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
"<?php if ($_smarty_tpl->tpl_vars['core']->value->checkPermission($_smarty_tpl->tpl_vars['pvalTable']->value,$_smarty_tpl->tpl_vars['module']->value['name'])) {?> checked="checked"<?php }?> type="checkbox"> 
																<span class="custom-checkbox custom-icon"></span>
															</label> <?php echo $_smarty_tpl->tpl_vars['module']->value['Display_Name'];?>

														</div>
													</div>
													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<!-- End section -->
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Administrators'));?>

				</a>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group"><?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>
</div>
			</div>
		</div>
	</div>
</form><?php }
}
