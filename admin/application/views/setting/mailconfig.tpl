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
				<h1 class="ui-title-bar__title">{$core->get_Lang('mailconfig')}</h1>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
{assign var = mail_type value = $clsConfiguration->getValue('mail_type')}
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-annotated-section__content">
				<div class="ui-form__section form-horizontal">
					<section class="ui-annotated-section-container">
						<div class="ui-annotated-section">
							<div class="row">
								<div class="col-md-4">
									<div class="ui-annotated-section__title">
										<h2 class="ui-heading">{$core->get_Lang(SMTP)}</h2>
									</div>
									<div class="ui-annotated-section__description">
										Cấu hình được sử dụng cho phép hệ thống gửi email thông qua hệ thống SMTP của Google. <a href="https://kungfuphp.com/tong-hop-php/gui-email-trong-php-su-dung-google-smtp.html" target="_blank">Bắt đầu tìm hiểu thêm</a>
									</div>
									{if $mail_type eq 'smtp'}
									<button class="btn btn-success" type="button">{$core->get_Lang('Actived')}</button>
									{else}
									<button class="btn btn-default" onClick="mailconfig_active('smtp')" type="button">{$core->get_Lang('Active')}</button>
									{/if}
								</div>
								<div class="col-md-8">
									<div class="ui-annotated-section__content" >
										<div class="next-card"><div class="section-content">
											<form method="post" action="" enctype="multipart/form-data">
												<div class="next-card__section">
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Mail Encoding')}</label>
														<div class="col-md-8">
															<select class="form-control" name="iso-SiteMailEncoding">
																<option {if $clsConfiguration->getValue('mail_smtp_encoding') eq '8bit'}{/if} value="8bit">8bit</option>
																<option {if $clsConfiguration->getValue('mail_smtp_encoding') eq '7bit'}{/if} value="7bit">7bit</option>
																<option {if $clsConfiguration->getValue('mail_smtp_encoding') eq 'binary'}{/if} value="binary">binary</option>
																<option {if $clsConfiguration->getValue('mail_smtp_encoding') eq 'base64'}selected"{/if} value="base64">base64 </option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('SMTP Host')}</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_smtp_host" value="{$clsConfiguration->getValue('mail_smtp_host')}" placeholder="smtp.gmail.com" /> 
															<span class="help-block">{$core->get_Lang('The host your mail server uses')}</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('SMTP Port')}</label>
														<div class="col-md-8">
															<input type="number" class="form-control" name="iso-mail_smtp_port" value="{$clsConfiguration->getValue('mail_smtp_port')}" placeholder="465 [OR] 587" /> 
															<span class="help-block">{$core->get_Lang('The port your mail server uses')}</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('SMTP Authentication')}</label>
														<div class="col-md-8">
															<label class="switch">
																<input type="checkbox"{if $clsConfiguration->getValue('mail_smtp_authentication') eq '1'}checked{/if} name="mail_smtp_authentication" value="1">
																<span class="slider round"></span> 
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('SMTP Username')}</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_smtp_username" value="{$clsConfiguration->getValue('mail_smtp_username')}" placeholder="example@gmail.com" /> 
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('SMTP Password')}</label>
														<div class="col-md-8">
															<div class="input-group">
																<input type="password" class="form-control" name="mail_smtp_password" /> 
																<span class="input-group-addon{if $clsConfiguration->getValue('mail_smtp_password') ne ''} text-green{/if}">{$core->makeIcon('check-circle')}</span>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('SMTP Secure')}</label>
														<div class="col-md-8">
															<select class="form-control" name="iso-mail_smtp_secure">
																<option{if $clsConfiguration->getValue('mail_smtp_secure') eq 'none'} selected{/if} value="none">None</option>
																<option{if $clsConfiguration->getValue('mail_smtp_secure') eq 'ssl'} selected{/if} value="ssl">SSL</option>
																<option{if $clsConfiguration->getValue('mail_smtp_secure') eq 'tls'} selected{/if} value="tls">TLS</option>
															</select>
														</div>
													</div>
												</div>
												<div class="next-card__section text-right">
													<input type="hidden" name="mail_type" value="smtp" />
													<input type="hidden" name="submit" value="UpdateConfiguration">
													{$saveBtn}
												</div>
											</form>
										</div></div>
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
										<h2 class="ui-heading">{$core->get_Lang('SENDGRID')}</h2>
									</div>
									<div class="ui-annotated-section__description">
										Cấu hình được sử dụng cho phép hệ thống gửi email thông qua hệ thống SMTP của SendGrid.Com. <a href="https://sendgrid.com" target="_blank">Bắt đầu tìm hiểu thêm</a>
									</div>
									{if $mail_type eq 'sendgrid'}
									<button class="btn btn-success" type="button">{$core->get_Lang('Actived')}</button>
									{else}
									<button class="btn btn-default" onClick="mailconfig_active('sendgrid')" type="button">{$core->get_Lang('Active')}</button>
									{/if}
								</div>
								<div class="col-md-8">
									<div class="ui-annotated-section__content">
										<div class="next-card"><div class="section-content">
											<form method="post" action="" enctype="multipart/form-data">
												<div class="next-card__section">
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Sengrid API URL Active')}</label>
														<div class="col-md-8">
															<label class="switch">
																<input type="checkbox" name="mail_sendgrid_api_enable" {if $clsConfiguration->getValue('mail_sendgrid_api_enable') eq '1'}checked{/if} value="1">
																<span class="slider round"></span> 
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Sengrid API URL')}</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_sendgrid_api_url" value="{$clsConfiguration->getValue('mail_sendgrid_api_url')}">
															<span class="help-block"> [v2] https://api.sendgrid.com/api/mail.send.json</span>
															<span class="help-block"> [v3] https://api.sendgrid.com/v3/mail/send</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Sengrid API Key')}</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_sendgrid_api_key" value="{$clsConfiguration->getValue('mail_sendgrid_api_key')}">
															<span class="help-block">{$core->get_Lang('Sengrid API Key.')}</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Sengrid Username')}</label>
														<div class="col-md-8">
															<input type="text" class="form-control text full" name="iso-mail_sendgrid_username" value="{$clsConfiguration->getValue('mail_sendgrid_username')}" />
															<span class="help-block">{$core->get_Lang('Sengrid Username.')}</span>
														</div>
													</div>
													<div class="form-group mb-0">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Sengrid Password')}</label>
														<div class="col-md-8">
															<div class="input-group">
																<input type="password"  class="form-control text full" name="mail_sendgrid_password" /> 
																<span class="input-group-addon{if $clsConfiguration->getValue('mail_sendgrid_password') ne ''} text-green{/if}">{$core->makeIcon('check-circle')}</span>
															</div>
															
															<span class="help-block">{$core->get_Lang('Sengrid Password.')}</span>
														</div>
													</div>
												</div>
												<div class="next-card__section text-right">
													<input type="hidden" name="mail_type" value="sendgrid" />
													<input type="hidden" name="submit" value="UpdateConfiguration">
													{$saveBtn}
												</div>
											</form>
										</div></div>
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
										<h2 class="ui-heading">{$core->get_Lang('Brevo')}</h2>
									</div>
									<div class="ui-annotated-section__description">
										Cấu hình được sử dụng cho phép hệ thống gửi email thông qua hệ thống SMTP của Brevo.Com. <a href="https://brevo.com" target="_blank">Bắt đầu tìm hiểu thêm</a>
									</div>
									{if $mail_type eq 'brevo'}
									<button class="btn btn-success" type="button">{$core->get_Lang('Actived')}</button>
									{else}
									<button class="btn btn-default" onClick="mailconfig_active('brevo')" type="button">{$core->get_Lang('Active')}</button>
									{/if}
								</div>
								<div class="col-md-8">
									<div class="ui-annotated-section__content">
										<div class="next-card"><div class="section-content">
											<form method="post" action="" enctype="multipart/form-data">
												<div class="next-card__section">
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Brevo API URL')}</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_brevo_api_url" value="{$clsConfiguration->getValue('mail_brevo_api_url')}">
															<span class="help-block"> https://api.sendgrid.com/v3/mail/send</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Brevo API Key')}</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_brevo_api_key" value="{$clsConfiguration->getValue('mail_brevo_api_key')}">
															<span class="help-block">{$core->get_Lang('Brevo API Key.')}</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Brevo Host')}</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_brevo_host" value="{$clsConfiguration->getValue('mail_brevo_host')}" placeholder="smtp-relay.brevo.com" /> 
															<span class="help-block">{$core->get_Lang('The host your mail server uses')}</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Brevo Port')}</label>
														<div class="col-md-8">
															<input type="number" class="form-control" name="iso-mail_brevo_port" value="{$clsConfiguration->getValue('mail_brevo_port')}" placeholder="465 [OR] 587" /> 
															<span class="help-block">{$core->get_Lang('The port your mail server uses')}</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Brevo Username')}</label>
														<div class="col-md-8">
															<input type="text" class="form-control text full" name="iso-mail_brevo_username" 
																value="{$clsConfiguration->getValue('mail_brevo_username')}" />
															<span class="help-block">{$core->get_Lang('Brevo Username.')}</span>
														</div>
													</div>
													<div class="form-group mb-0">
														<label class="col-md-4 text-right col-form-label">{$core->get_Lang('Brevo Password')}</label>
														<div class="col-md-8">
															<div class="input-group">
																<input type="password" class="form-control text full" name="mail_brevo_password" /> 
																<span class="input-group-addon{if $clsConfiguration->getValue('mail_brevo_password') ne ''} text-green{/if}">{$core->makeIcon('check-circle')}</span>
															</div>
															
															<span class="help-block">{$core->get_Lang('Brevo Password.')}</span>
														</div>
													</div>
												</div>
												<div class="next-card__section text-right">
													<input type="hidden" name="mail_type" value="brevo" />
													<input type="hidden" name="submit" value="UpdateConfiguration">
													{$saveBtn}
												</div>
											</form>
										</div></div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
{literal}
<script type="text/javascript">
	$(function(){
		$('select[name^=iso]').each(function(){
			var $_this = $(this);
			if($_this.val()==1){
				$_this.css({'border-color':'#0C0', 'background':'#e9ffd9'});
			}
		});
	});
</script>
{/literal}