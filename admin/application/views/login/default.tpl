<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  	<!-- BEGIN: Head-->
	<head>
		<title>{$clsConfiguration->getValue('site_name')}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="author" content="VietISO">
		<meta name='robots' content='noindex,nofollow' />
		{assign var=faviconUrl value=$clsConfiguration->getValue('Favicon')}
		<link rel="shortcut icon" href="{if $faviconUrl}{$faviconUrl|escape}{else}{$DOMAIN_URL}/favicon.ico{/if}?v={$upd_version}" type="image/x-icon" />
		<link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">
		<!-- BEGIN: Vendor CSS-->
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/vendors.min.css?v={$upd_version}">
		<!-- END: Vendor CSS-->
		<!-- BEGIN: Theme CSS-->
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/bootstrap.min.css?v={$upd_version}">  
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/bootstrap-extended.min.css">
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/components.min.css?v={$upd_version}">
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/colors.min.css">
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/dark-layout.min.css">
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/semi-dark-layout.min.css">
		<!-- END: Theme CSS-->
		<!-- BEGIN: Page CSS-->
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/authentication.css">
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
										<img src="{$header_configs.HeaderLogo}" 
										width="80px" alt="{$PAGE_NAME}" />
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
		<script type="text/javascript" src="{$URL_JS}/jquery-1.9.1.min.js"></script>
		{literal}
		<script type="text/javascript">
			$(document).ready(function(){
				$('input[type=text]:first').focus();
			});
		</script>
		{/literal}
		<!-- BEGIN: Theme JS-->
  </body>
  <!-- END: Body-->
</html>