<?php
/* Smarty version 3.1.33, created on 2026-08-06 15:38:28
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/security.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a744804c0ecf3_08205424',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0f83d79b7cd3a8690694820aed6ddf319f5bbafb' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/security.tpl',
      1 => 1784691752,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a744804c0ecf3_08205424 (Smarty_Internal_Template $_smarty_tpl) {
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
				<h1 class="ui-title-bar__title"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Security');?>
</h1>
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
								<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Security');?>
</h2>
							</div>
							<div class="ui-annotated-section__description">
								Cấu hình được sử dụng cho sẽ bắt buộc khách hàng phải xác mình trước khi tiến hành <strong>Submit FORM</strong> nhập liệu có trên website. Google ReCaptcha là một công cụ của Google.<a href="https://developers.google.com/recaptcha" target="_blank">Tìm hiểu Google reCAPTCHA</a>.
							</div>
						</div>
						<div class="col-md-8">
							<div class="ui-annotated-section__content" >
								<div class="next-card">
									<div class="next-card__header">
										<h2 class="next-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Captcha');?>
</h2>
									</div>
									<div class="section-content">
										<div class="next-card__section">
											<?php $_smarty_tpl->_assignInScope('captcha_type', $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('captcha_type'));?>
											<?php if ($_smarty_tpl->tpl_vars['captcha_type']->value == '') {?>
												<?php $_smarty_tpl->_assignInScope('captcha_type', 'IMG');?>
											<?php }?>
											<div class="form-group mt-half">
												<label class="col-md-3 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Captcha_Type');?>
</label>
												<div class="col-md-9">
													<div class="custom-radio-wrapper core-radio-custom">
														<label>
															<input onChange="handler_captcha_change(this)" class="captcha_type" name="captcha_type"<?php if ($_smarty_tpl->tpl_vars['captcha_type']->value == 'IMG') {?> checked="checked"<?php }?> value="IMG" type="radio">
															<span class="custom-radio custom-icon"></span>
														</label> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Security_Code');?>

													</div>
													<div class="custom-radio-wrapper mt-half core-radio-custom">
														<label>
															<input onChange="handler_captcha_change(this)" class="captcha_type" name="captcha_type"<?php if ($_smarty_tpl->tpl_vars['captcha_type']->value == 'reCAPTCHA') {?> checked="checked"<?php }?> value="reCAPTCHA" type="radio">
															<span class="custom-radio custom-icon"></span>
														</label> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Google_ReCaptcha');?>

													</div>
												</div>
											</div>
										</div>
										<div class="next-card__section recaptcha__group<?php if ($_smarty_tpl->tpl_vars['captcha_type']->value != 'reCAPTCHA') {?> hide<?php }?>">
											<div class="form-group">
												<label class="col-md-3 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('CAPTCHA_KEY');?>
</label>
												<div class="col-md-9">
													<input type="text" name="iso-reCAPTCHA_KEY" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('reCAPTCHA_KEY');?>
" class="form-control" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-3 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('CAPTCHA_SECRET');?>
</label>
												<div class="col-md-9">
													<input type="text" name="iso-reCAPTCHA_SECRET" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('reCAPTCHA_SECRET');?>
" class="form-control" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-3 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('CAPTCHA_APIURL');?>
</label>
												<div class="col-md-9">
													<input type="text" name="iso-reCAPTCHA_APIURL" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('reCAPTCHA_APIURL');?>
" class="form-control" />
												</div>
											</div>
										</div>
										<div class="next-card__section text-right">
											<input type="hidden" name="submit" value="UpdateConfiguration">
											<button type="submit" name="button"<?php if ($_smarty_tpl->tpl_vars['google_login']->value == '1') {?> disabled<?php }?> class="btn btn-success">
												<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check',$_smarty_tpl->tpl_vars['core']->value->get_Lang('SaveSetting'));?>

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
								<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Status_Website');?>
</h2>
							</div>
							<div class="ui-annotated-section__description">
								Khi bật chế độ hiển thị nâng cấp, khách hàng sẽ thấy website của bạn đang ở trạng thái bảo trì. Nhập mật khẩu để truy cập được vào website.
							</div>
						</div>
						<div class="col-md-8">
							<div class="ui-annotated-section__content" >
								<div class="next-card">
									<div class="next-card__header">
										<h2 class="next-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Status_Website');?>
</h2>
									</div>
									<div class="section-content">
										<div class="next-card__section">
											<div class="form-group mt-half">
												<label class="col-md-3 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Status');?>
</label>
												<div class="col-md-9">
													<?php $_smarty_tpl->_assignInScope('site_status', $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('site_status'));?>
													<div class="custom-radio-wrapper core-radio-custom">
														<label>
															<input name="site_status"<?php if ($_smarty_tpl->tpl_vars['site_status']->value == 'ON') {?> checked<?php }?> value="ON" type="radio">
															<span class="custom-radio custom-icon"></span>
														</label> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('On');?>

													</div>
													<div class="custom-radio-wrapper mt-half core-radio-custom">
														<label>
															<input name="site_status"<?php if ($_smarty_tpl->tpl_vars['site_status']->value == 'OFF' || $_smarty_tpl->tpl_vars['site_status']->value == '') {?> checked<?php }?> value="OFF" type="radio">
															<span class="custom-radio custom-icon"></span>
														</label> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Off');?>

													</div>
												</div>
											</div>
										</div>
										<div class="next-card__section text-right">
											<input type="hidden" name="submit" value="UpdateConfiguration">
											<button type="submit" name="button"<?php if ($_smarty_tpl->tpl_vars['google_login']->value == '1') {?> disabled<?php }?> class="btn btn-success">
												<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check',$_smarty_tpl->tpl_vars['core']->value->get_Lang('SaveSetting'));?>

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
</form><?php }
}
