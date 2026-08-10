<?php
/* Smarty version 3.1.33, created on 2026-08-07 19:34:50
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/email_template/edit.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75d0ea821d16_57086168',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c158fa31670311690a4c990c4ea2fcf1d648b40d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/email_template/edit.tpl',
      1 => 1786106088,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75d0ea821d16_57086168 (Smarty_Internal_Template $_smarty_tpl) {
?><header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default ui-breadcrumb">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>

					<span class="ui-breadcrumb__item"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Email Templates');?>
</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title"><?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {?>Cập nhật<?php } else { ?>Thêm<?php }?> Email Templates #<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
</h1>
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
							<div class="col-md-3">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Email Templates</h2>
								</div>
								<div class="ui-annotated-section__description">
									Những email này được gửi tự động tới bạn hoặc khách hàng. Click vào tên mẫu email để chỉnh sửa.
								</div>
							</div>
							<div class="col-md-9">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('TemplateName');?>
</label>
														<input class="form-control" name="iso-title" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
" placeholder="Nhập tên mẫu ở đây" maxlength="255" type="text" >
													</div>
												</div>
												<div class="form-group">
													<div class="col-xs-12 col-md-6">
														<label class="col-form-label">From name</label>
														<input type="text" class="form-control" placeholder="From name" name="iso-fromname" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['fromname'];?>
" />
													</div>
													<div class="col-xs-12 col-md-6">
														<label class="col-form-label">From email</label>
														<input type="text" class="form-control" name="iso-fromemail" placeholder="example@gmail.com" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['fromemail'];?>
" />
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Copy To');?>
</label>
														<input class="form-control" name="iso-copyto" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['copyto'];?>
" placeholder="Nhập email tách nhau bằng dấu (,)" maxlength="255" type="text" >
														<small class="text-muted">Nhập email tách nhau bằng dấu (,)</small>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Subject');?>
</label>
														<input class="form-control" name="iso-subject" placeholder="Nhập subject ở đây" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['subject'];?>
" maxlength="255" type="text" >
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Header');?>
</label>
														<textarea class="form-control isoTextArea" rows="3" cols="255" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" name="iso-header"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['header'];?>
</textarea>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Content');?>
</label>
														<textarea class="form-control isoTextArea" rows="10" cols="255" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" name="iso-content"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['content'];?>
</textarea>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Footer');?>
</label>
														<textarea class="form-control isoTextArea" rows="3" cols="255" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" name="iso-footer"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['footer'];?>
</textarea>
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
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Email Templates'));?>

				</a>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group"><?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>
</div>
			</div>
		</div>
	</div>
</form>

<?php }
}
