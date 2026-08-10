<?php
/* Smarty version 3.1.33, created on 2026-08-07 16:17:54
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/mailconfig.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75a2c2322a24_97693806',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4cc7be8333e836012d236a984e8080afd5389262' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/mailconfig.tpl',
      1 => 1784691722,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75a2c2322a24_97693806 (Smarty_Internal_Template $_smarty_tpl) {
?><header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default ui-breadcrumb">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>

					<span class="ui-breadcrumb__item"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Setting');?>
</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('mailconfig');?>
</h1>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<?php $_smarty_tpl->_assignInScope('mail_type', $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_type'));?>
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
										<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('SMTP');?>
</h2>
									</div>
									<div class="ui-annotated-section__description">
										Cấu hình được sử dụng cho phép hệ thống gửi email thông qua hệ thống SMTP của Google. <a href="https://kungfuphp.com/tong-hop-php/gui-email-trong-php-su-dung-google-smtp.html" target="_blank">Bắt đầu tìm hiểu thêm</a>
									</div>
									<?php if ($_smarty_tpl->tpl_vars['mail_type']->value == 'smtp') {?>
									<button class="btn btn-success" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actived');?>
</button>
									<?php } else { ?>
									<button class="btn btn-default" onClick="mailconfig_active('smtp')" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
</button>
									<?php }?>
								</div>
								<div class="col-md-8">
									<div class="ui-annotated-section__content" >
										<div class="next-card"><div class="section-content">
											<form method="post" action="" enctype="multipart/form-data">
												<div class="next-card__section">
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Mail Encoding');?>
</label>
														<div class="col-md-8">
															<select class="form-control" name="iso-SiteMailEncoding">
																<option <?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_encoding') == '8bit') {
}?> value="8bit">8bit</option>
																<option <?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_encoding') == '7bit') {
}?> value="7bit">7bit</option>
																<option <?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_encoding') == 'binary') {
}?> value="binary">binary</option>
																<option <?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_encoding') == 'base64') {?>selected"<?php }?> value="base64">base64 </option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('SMTP Host');?>
</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_smtp_host" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_host');?>
" placeholder="smtp.gmail.com" /> 
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('The host your mail server uses');?>
</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('SMTP Port');?>
</label>
														<div class="col-md-8">
															<input type="number" class="form-control" name="iso-mail_smtp_port" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_port');?>
" placeholder="465 [OR] 587" /> 
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('The port your mail server uses');?>
</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('SMTP Authentication');?>
</label>
														<div class="col-md-8">
															<label class="switch">
																<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_authentication') == '1') {?>checked<?php }?> name="mail_smtp_authentication" value="1">
																<span class="slider round"></span> 
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('SMTP Username');?>
</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_smtp_username" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_username');?>
" placeholder="example@gmail.com" /> 
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('SMTP Password');?>
</label>
														<div class="col-md-8">
															<div class="input-group">
																<input type="password" class="form-control" name="mail_smtp_password" /> 
																<span class="input-group-addon<?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_password') != '') {?> text-green<?php }?>"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check-circle');?>
</span>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('SMTP Secure');?>
</label>
														<div class="col-md-8">
															<select class="form-control" name="iso-mail_smtp_secure">
																<option<?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_secure') == 'none') {?> selected<?php }?> value="none">None</option>
																<option<?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_secure') == 'ssl') {?> selected<?php }?> value="ssl">SSL</option>
																<option<?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_smtp_secure') == 'tls') {?> selected<?php }?> value="tls">TLS</option>
															</select>
														</div>
													</div>
												</div>
												<div class="next-card__section text-right">
													<input type="hidden" name="mail_type" value="smtp" />
													<input type="hidden" name="submit" value="UpdateConfiguration">
													<?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>

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
										<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('SENDGRID');?>
</h2>
									</div>
									<div class="ui-annotated-section__description">
										Cấu hình được sử dụng cho phép hệ thống gửi email thông qua hệ thống SMTP của SendGrid.Com. <a href="https://sendgrid.com" target="_blank">Bắt đầu tìm hiểu thêm</a>
									</div>
									<?php if ($_smarty_tpl->tpl_vars['mail_type']->value == 'sendgrid') {?>
									<button class="btn btn-success" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actived');?>
</button>
									<?php } else { ?>
									<button class="btn btn-default" onClick="mailconfig_active('sendgrid')" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
</button>
									<?php }?>
								</div>
								<div class="col-md-8">
									<div class="ui-annotated-section__content">
										<div class="next-card"><div class="section-content">
											<form method="post" action="" enctype="multipart/form-data">
												<div class="next-card__section">
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sengrid API URL Active');?>
</label>
														<div class="col-md-8">
															<label class="switch">
																<input type="checkbox" name="mail_sendgrid_api_enable" <?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_sendgrid_api_enable') == '1') {?>checked<?php }?> value="1">
																<span class="slider round"></span> 
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sengrid API URL');?>
</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_sendgrid_api_url" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_sendgrid_api_url');?>
">
															<span class="help-block"> [v2] https://api.sendgrid.com/api/mail.send.json</span>
															<span class="help-block"> [v3] https://api.sendgrid.com/v3/mail/send</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sengrid API Key');?>
</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_sendgrid_api_key" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_sendgrid_api_key');?>
">
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sengrid API Key.');?>
</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sengrid Username');?>
</label>
														<div class="col-md-8">
															<input type="text" class="form-control text full" name="iso-mail_sendgrid_username" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_sendgrid_username');?>
" />
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sengrid Username.');?>
</span>
														</div>
													</div>
													<div class="form-group mb-0">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sengrid Password');?>
</label>
														<div class="col-md-8">
															<div class="input-group">
																<input type="password"  class="form-control text full" name="mail_sendgrid_password" /> 
																<span class="input-group-addon<?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_sendgrid_password') != '') {?> text-green<?php }?>"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check-circle');?>
</span>
															</div>
															
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sengrid Password.');?>
</span>
														</div>
													</div>
												</div>
												<div class="next-card__section text-right">
													<input type="hidden" name="mail_type" value="sendgrid" />
													<input type="hidden" name="submit" value="UpdateConfiguration">
													<?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>

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
										<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo');?>
</h2>
									</div>
									<div class="ui-annotated-section__description">
										Cấu hình được sử dụng cho phép hệ thống gửi email thông qua hệ thống SMTP của Brevo.Com. <a href="https://brevo.com" target="_blank">Bắt đầu tìm hiểu thêm</a>
									</div>
									<?php if ($_smarty_tpl->tpl_vars['mail_type']->value == 'brevo') {?>
									<button class="btn btn-success" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actived');?>
</button>
									<?php } else { ?>
									<button class="btn btn-default" onClick="mailconfig_active('brevo')" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Active');?>
</button>
									<?php }?>
								</div>
								<div class="col-md-8">
									<div class="ui-annotated-section__content">
										<div class="next-card"><div class="section-content">
											<form method="post" action="" enctype="multipart/form-data">
												<div class="next-card__section">
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo API URL');?>
</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_brevo_api_url" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_brevo_api_url');?>
">
															<span class="help-block"> https://api.sendgrid.com/v3/mail/send</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo API Key');?>
</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_brevo_api_key" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_brevo_api_key');?>
">
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo API Key.');?>
</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo Host');?>
</label>
														<div class="col-md-8">
															<input type="text" class="form-control" name="iso-mail_brevo_host" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_brevo_host');?>
" placeholder="smtp-relay.brevo.com" /> 
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('The host your mail server uses');?>
</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo Port');?>
</label>
														<div class="col-md-8">
															<input type="number" class="form-control" name="iso-mail_brevo_port" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_brevo_port');?>
" placeholder="465 [OR] 587" /> 
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('The port your mail server uses');?>
</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo Username');?>
</label>
														<div class="col-md-8">
															<input type="text" class="form-control text full" name="iso-mail_brevo_username" 
																value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_brevo_username');?>
" />
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo Username.');?>
</span>
														</div>
													</div>
													<div class="form-group mb-0">
														<label class="col-md-4 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo Password');?>
</label>
														<div class="col-md-8">
															<div class="input-group">
																<input type="password" class="form-control text full" name="mail_brevo_password" /> 
																<span class="input-group-addon<?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_brevo_password') != '') {?> text-green<?php }?>"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check-circle');?>
</span>
															</div>
															
															<span class="help-block"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brevo Password.');?>
</span>
														</div>
													</div>
												</div>
												<div class="next-card__section text-right">
													<input type="hidden" name="mail_type" value="brevo" />
													<input type="hidden" name="submit" value="UpdateConfiguration">
													<?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>

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

<?php echo '<script'; ?>
 type="text/javascript">
	$(function(){
		$('select[name^=iso]').each(function(){
			var $_this = $(this);
			if($_this.val()==1){
				$_this.css({'border-color':'#0C0', 'background':'#e9ffd9'});
			}
		});
	});
<?php echo '</script'; ?>
>
<?php }
}
