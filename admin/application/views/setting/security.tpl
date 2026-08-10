<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Setting')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$core->get_Lang('Security')}</h1>
			</div>
		</div>
	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>
</header>
<div class="clearfix"></div>
<form method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="ui-annotated-section__content"><div class="ui-form__section form-horizontal ui-card__section">
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="row">
						<div class="col-md-4">
							<div class="ui-annotated-section__title">
								<h2 class="ui-heading">{$core->get_Lang('Security')}</h2>
							</div>
							<div class="ui-annotated-section__description">
								Cấu hình được sử dụng cho sẽ bắt buộc khách hàng phải xác mình trước khi tiến hành <strong>Submit FORM</strong> nhập liệu có trên website. Google ReCaptcha là một công cụ của Google.<a href="https://developers.google.com/recaptcha" target="_blank">Tìm hiểu Google reCAPTCHA</a>.
							</div>
						</div>
						<div class="col-md-8">
							<div class="ui-annotated-section__content" >
								<div class="next-card">
									<div class="next-card__header">
										<h2 class="next-heading">{$core->get_Lang('Captcha')}</h2>
									</div>
									<div class="section-content">
										<div class="next-card__section">
											{assign var = captcha_type value = $clsConfiguration->getValue('captcha_type')}
											{if $captcha_type eq ''}
												{assign var = captcha_type value = 'IMG'}
											{/if}
											<div class="form-group mt-half">
												<label class="col-md-3 text-right col-form-label">{$core->get_Lang('Captcha_Type')}</label>
												<div class="col-md-9">
													<div class="custom-radio-wrapper core-radio-custom">
														<label>
															<input onChange="handler_captcha_change(this)" class="captcha_type" name="captcha_type"{if $captcha_type eq 'IMG'} checked="checked"{/if} value="IMG" type="radio">
															<span class="custom-radio custom-icon"></span>
														</label> {$core->get_Lang('Security_Code')}
													</div>
													<div class="custom-radio-wrapper mt-half core-radio-custom">
														<label>
															<input onChange="handler_captcha_change(this)" class="captcha_type" name="captcha_type"{if $captcha_type eq 'reCAPTCHA'} checked="checked"{/if} value="reCAPTCHA" type="radio">
															<span class="custom-radio custom-icon"></span>
														</label> {$core->get_Lang('Google_ReCaptcha')}
													</div>
												</div>
											</div>
										</div>
										<div class="next-card__section recaptcha__group{if $captcha_type ne 'reCAPTCHA'} hide{/if}">
											<div class="form-group">
												<label class="col-md-3 text-right col-form-label">{$core->get_Lang('CAPTCHA_KEY')}</label>
												<div class="col-md-9">
													<input type="text" name="iso-reCAPTCHA_KEY" value="{$clsConfiguration->getValue('reCAPTCHA_KEY')}" class="form-control" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-3 text-right col-form-label">{$core->get_Lang('CAPTCHA_SECRET')}</label>
												<div class="col-md-9">
													<input type="text" name="iso-reCAPTCHA_SECRET" value="{$clsConfiguration->getValue('reCAPTCHA_SECRET')}" class="form-control" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-3 text-right col-form-label">{$core->get_Lang('CAPTCHA_APIURL')}</label>
												<div class="col-md-9">
													<input type="text" name="iso-reCAPTCHA_APIURL" value="{$clsConfiguration->getValue('reCAPTCHA_APIURL')}" class="form-control" />
												</div>
											</div>
										</div>
										<div class="next-card__section text-right">
											<input type="hidden" name="submit" value="UpdateConfiguration">
											<button type="submit" name="button"{if $google_login eq '1'} disabled{/if} class="btn btn-success">
												{$core->makeIcon('check', $core->get_Lang('SaveSetting'))}
											</button>
										</div>
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
								<h2 class="ui-heading">{$core->get_Lang('Status_Website')}</h2>
							</div>
							<div class="ui-annotated-section__description">
								Khi bật chế độ hiển thị nâng cấp, khách hàng sẽ thấy website của bạn đang ở trạng thái bảo trì. Nhập mật khẩu để truy cập được vào website.
							</div>
						</div>
						<div class="col-md-8">
							<div class="ui-annotated-section__content" >
								<div class="next-card">
									<div class="next-card__header">
										<h2 class="next-heading">{$core->get_Lang('Status_Website')}</h2>
									</div>
									<div class="section-content">
										<div class="next-card__section">
											<div class="form-group mt-half">
												<label class="col-md-3 text-right col-form-label">{$core->get_Lang('Status')}</label>
												<div class="col-md-9">
													{assign var = site_status value = $clsConfiguration->getValue('site_status')}
													<div class="custom-radio-wrapper core-radio-custom">
														<label>
															<input name="site_status"{if $site_status eq 'ON'} checked{/if} value="ON" type="radio">
															<span class="custom-radio custom-icon"></span>
														</label> {$core->get_Lang('On')}
													</div>
													<div class="custom-radio-wrapper mt-half core-radio-custom">
														<label>
															<input name="site_status"{if $site_status eq 'OFF'||$site_status eq ''} checked{/if} value="OFF" type="radio">
															<span class="custom-radio custom-icon"></span>
														</label> {$core->get_Lang('Off')}
													</div>
												</div>
											</div>
										</div>
										<div class="next-card__section text-right">
											<input type="hidden" name="submit" value="UpdateConfiguration">
											<button type="submit" name="button"{if $google_login eq '1'} disabled{/if} class="btn btn-success">
												{$core->makeIcon('check', $core->get_Lang('SaveSetting'))}
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div></div>
	</div>
</form>