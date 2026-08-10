<?php
/* Smarty version 3.1.33, created on 2026-08-06 14:51:51
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/social.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a743d17c94583_45586721',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '199a021b42c16e2a2689ddc155ca40e882159503' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/social.tpl',
      1 => 1784691752,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a743d17c94583_45586721 (Smarty_Internal_Template $_smarty_tpl) {
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
				<h1 class="ui-title-bar__title"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Social');?>
</h1>
			</div>
		</div>
	</div>
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
									<h2 class="ui-heading">Facebook</h2>
								</div>
								<div class="ui-annotated-section__description">
									Nhập đường link liên kết với Mạng xã hội Facebook.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">Facebook Link</label>
															<input type="text" class="form-control" placeholder="Nhập liên kết facebook tại đây" name="iso-facebook_link" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('facebook_link');?>
" />
														</div>
													</div>
												</div>
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
									<h2 class="ui-heading">Twitter</h2>
								</div>
								<div class="ui-annotated-section__description">
									Nhập đường liên kết với Mạng xã hội Twitter.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">Twitter</label>
															<input type="text" class="form-control" name="iso-twitter_link" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('twitter_link');?>
" placeholder="Nhập liên kết Twitter tại đây" />
														</div>
													</div>
												</div>
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
									<h2 class="ui-heading">LinkedIn</h2>
								</div>
								<div class="ui-annotated-section__description">
									Nhập đường liên kết với Mạng xã hội LinkedIn.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">LinkedIn</label>
															<input type="text" class="form-control" name="iso-linkedin_link" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('linkedin_link');?>
" placeholder="Nhập đương dẫn LinkedIn tại đây" />
														</div>
													</div>
												</div>
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
									<h2 class="ui-heading">Google</h2>
								</div>
								<div class="ui-annotated-section__description">
									Nhập đường liên kết với Mạng xã hội Google.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Google Plus');?>
</label>
															<input type="text" class="form-control" name="iso-google_link" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('google_link');?>
" placeholder="Nhập liên kết với Google Plus tại đây" />
														</div>
													</div>
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Youtube');?>
</label>
															<input type="text" class="form-control" name="iso-youtube_link" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('youtube_link');?>
" placeholder="Nhập liên kết với Youtube tại đây" />
														</div>
													</div>
													<?php if ($_smarty_tpl->tpl_vars['clsConfig']->value->get('video_homepage',0) == '1') {?>
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">Video giới thiệu(Video giới thiệu công ty, tổ chức....)</label>
															<input type="text" class="form-control" name="iso-video_homepage" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('video_homepage');?>
" placeholder="Nhập đường dẫn video  Youtube tại đây" />
														</div>
													</div>
													<?php }?>
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
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group"><?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>
</div>
			</div>
		</div>
	</div>
</form><?php }
}
