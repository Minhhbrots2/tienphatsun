<?php
/* Smarty version 3.1.33, created on 2026-07-30 19:06:18
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/login/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6b3e3aa92e49_09400440',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '76a5856adabd60d6ca8cc80e87dd290e953cacfc' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/login/default.tpl',
      1 => 1785413164,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6b3e3aa92e49_09400440 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  	<!-- BEGIN: Head-->
	<head>
		<title><?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('site_name');?>
</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="author" content="VietISO">
		<meta name='robots' content='noindex,nofollow' />
		<?php $_smarty_tpl->_assignInScope('faviconUrl', $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('Favicon'));?>
		<link rel="shortcut icon" href="<?php if ($_smarty_tpl->tpl_vars['faviconUrl']->value) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['faviconUrl']->value, ENT_QUOTES, 'UTF-8', true);
} else {
echo $_smarty_tpl->tpl_vars['DOMAIN_URL']->value;?>
/favicon.ico<?php }?>?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="image/x-icon" />
		<link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">
		<!-- BEGIN: Vendor CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/vendors.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
">
		<!-- END: Vendor CSS-->
		<!-- BEGIN: Theme CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/bootstrap.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
">  
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/bootstrap-extended.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/components.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
">
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/colors.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/dark-layout.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/semi-dark-layout.min.css">
		<!-- END: Theme CSS-->
		<!-- BEGIN: Page CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/authentication.css">
		<!-- END: Page CSS-->
	</head>
  	<body class="vertical-layout vertical-menu-modern 1-column bg-full-screen-image blank-page">
		<!-- BEGIN: Content-->
		<div class="app-content content">
			<div class="content-overlay"></div>
			<div class="content-wrapper">
				<div class="content-header row"></div>
				<div class="content-body">
					<!-- login page start -->
					<section id="auth-login" class="flexbox-container">
						<div class="card bg-authentication mb-0">
							<div class="card mb-0 p-3 h-100 d-flex justify-content-center">
								<div class="card-header pb-1">
									<div class="card-title text-center">
										<img src="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['HeaderLogo'];?>
" 
										width="80px" alt="<?php echo $_smarty_tpl->tpl_vars['PAGE_NAME']->value;?>
" />
									</div>
								</div>
								<div class="card-content">
									<div class="divider">
										<div class="divider-text text-uppercase text-muted"><small>Sign In</small></div>
									</div>
									<form id="gaia_loginform" method="post" action="" enctype="multipart/form-data">
										<div class="form-group mb-50">
											<label class="text-bold-600" for="exampleInputUsername">Username</label>
											<input type="text" class="form-control" required="true" name="txtUsername" id="exampleInputUsername"
												placeholder="Username" />
										</div>
										<div class="form-group">
											<label class="text-bold-600" for="exampleInputPassword1">Password</label>
											<input type="password" class="form-control" required="true" id="exampleInputPassword1"
												placeholder="Password" name="txtPassword" />
										</div>
										<div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
											<div class="text-left">
												<div class="checkbox checkbox-sm">
													<input type="checkbox" class="form-check-input" id="exampleCheck1">
													<label class="checkboxsmall" for="exampleCheck1">
														<small>Keep me logged in</small>
													</label>
												</div>
											</div>
											<div class="text-right">
												<a href="https://www.vietiso.com/contact" class="card-link">
													<small>Forgot Password?</small>
												</a>
											</div>
										</div>
										<input type="hidden" name="btnLogin" value="btnLogin" />
										<button type="submit" class="btn btn-primary glow w-100 position-relative">Login <i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
										</button>
									</form>
									<hr>
									<div class="text-center">
										<small class="mr-25">Don't have an account?</small>
										<a><small>Sign up</small></a>
									</div>
								</div>
							</div>
						</div>
					</section>
					<!-- login page ends -->
				</div>
			</div>
		</div>
		<!-- END: Content-->
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery-1.9.1.min.js"><?php echo '</script'; ?>
>
		
		<?php echo '<script'; ?>
 type="text/javascript">
			$(document).ready(function(){
				$('input[type=text]:first').focus();
			});
		<?php echo '</script'; ?>
>
		
		<!-- BEGIN: Theme JS-->
  </body>
  <!-- END: Body-->
</html><?php }
}
