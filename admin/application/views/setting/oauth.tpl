<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Settings')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$core->get_Lang('Oauth')} 2.0</h1>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
{assign var = google_login value = $clsConfiguration->getValue('google_login')}
{assign var = facebook_login value = $clsConfiguration->getValue('facebook_login')}
<form method="post"><div class="ui-layout"><div class="ui-layout__sections">
	<div class="ui-layout__section">
		<div class="ui-annotated-section__content"><div class="ui-form__section form-horizontal ui-card__section">
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="row">
						<div class="col-md-4">
							<div class="ui-annotated-section__title">
								<h2 class="ui-heading">{$core->get_Lang('Google')}</h2>
							</div>
							<div class="ui-annotated-section__description">
								Cấu hình được sử dụng cho phép khách hàng đăng nhập vào website thông qua Google.
							</div>
							{if $google_login eq '1'}
							<button class="btn btn-success" onClick="handler_oauth_login('google','0')" type="button">{$core->get_Lang('UnActive')}</button>
							{else}
							<button class="btn btn-default" onClick="handler_oauth_login('google','1')" type="button">{$core->get_Lang('Active')}</button>
							{/if}
						</div>
						<div class="col-md-8">
							<div class="ui-annotated-section__content" >
								<div class="next-card">
									<div class="next-card__header">
										<h2 class="next-heading">{$core->get_Lang('Google')}</h2>
									</div>
									<div class="section-content">
										<form method="post" action="" enctype="multipart/form-data">
											<div class="next-card__section">
												<div class="type--subdued">Thiết lập website của bạn kích hoạt chức năng đăng nhập thông qua tài khoản Google, bạn cần điền đầy đủ thông tin yêu câu vào các ô bên dưới và kích hoạt. <a class="underline" href="https://console.developers.google.com/getting-started" target="_blank">Bắt đầu tạo &raquo;</a></div>
												<div class="form-group mt-half">
													<label class="col-md-3 text-right col-form-label">{$core->get_Lang('Client_Id')}</label>
													<div class="col-md-9">
														<input type="text" name="iso-google_client_id" value="{$clsConfiguration->getValue('google_client_id')}" class="form-control" />
														<div class="help-block">Ex:173799401323-ma13fpuj77v0jcsi8m0iqobcdelgva8d.apps.googleusercontent.com</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 text-right col-form-label">{$core->get_Lang('Client_Secret')}</label>
													<div class="col-md-9">
														<input type="text" name="iso-google_client_secret" value="{$clsConfiguration->getValue('google_client_secret')}" class="form-control" />
														<div class="help-block">Ex:qrs51qEdqci6KDUEcIQhp8DN</div>
													</div>
												</div>
											</div>
											<div class="next-card__section text-right">
												<input type="hidden" name="submit" value="UpdateConfiguration">
												<button type="submit" name="button"{if $google_login eq '1'} disabled{/if} class="btn btn-success">
													{$core->makeIcon('check', $core->get_Lang('SaveSetting'))}
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="row">
						<div class="col-md-4">
							<div class="ui-annotated-section__title">
								<h2 class="ui-heading">{$core->get_Lang('FacebookConfig')}</h2>
							</div>
							<div class="ui-annotated-section__description">
								Cấu hình được sử dụng cho phép người dùng đăng nhập vào hệ thống thông qua Facebook.
							</div>
							{if $facebook_login eq '1'}
							<button class="btn btn-success" onClick="handler_oauth_login('facebook','0')" type="button">{$core->get_Lang('UnActive')}</button>
							{else}
							<button class="btn btn-default" onClick="handler_oauth_login('facebook','1')" type="button">{$core->get_Lang('Active')}</button>
							{/if}
						</div>
						<div class="col-md-8">
							<div class="ui-annotated-section__content">
								<div class="next-card">
									<div class="next-card__header">
										<h2 class="next-heading">Facebook</h2>
									</div>
									<div class="section-content">
										<form method="post" action="" enctype="multipart/form-data">
											<div class="next-card__section">
												<div class="type--subdued">Thiết lập website của bạn kích hoạt chức năng đăng nhập thông qua tài khoản Facebook, bạn cần điền đầy đủ thông tin yêu cầu vào các ô bên dưới và kích hoạt. <a class="underline" href="https://developers.facebook.com/" target="_blank">Bắt đầu tạo &raquo;</a></div>
												<div class="form-group mt-half">
													<label class="col-md-4 text-right col-form-label">{$core->get_Lang('App_Id')}</label>
													<div class="col-md-8">
														<input type="text" name="iso-facebook_app_id" value="{$clsConfiguration->getValue('facebook_app_id')}" class="form-control" />
														<div class="help-block">Ex:351844196208298</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 text-right col-form-label">{$core->get_Lang('App_Secret')}</label>
													<div class="col-md-8">
														<input type="text" name="iso-facebook_app_secret" value="{$clsConfiguration->getValue('facebook_app_secret')}" class="form-control" />
														<div class="help-block">Ex:deaa2cb156e2ac5c64133e56343afe19</div>
													</div>
												</div>
											</div>
											<div class="next-card__section text-right">
												<input type="hidden" name="submit" value="UpdateConfiguration">
												<button type="submit" name="button"{if $facebook_login eq '1'} disabled{/if} class="btn btn-success">
													{$core->makeIcon('check', $core->get_Lang('SaveSetting'))}
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div></div>
	</div></div>
</div></form>