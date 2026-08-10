<?php
/* Smarty version 3.1.33, created on 2026-08-07 16:08:18
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/message.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75a082e1f3e0_81664138',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd9d473329f2d141c995f39e6c8b3be2069e7c747' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/message.tpl',
      1 => 1784691723,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75a082e1f3e0_81664138 (Smarty_Internal_Template $_smarty_tpl) {
?><header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default ui-breadcrumb">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>

					<span class="ui-breadcrumb__item"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Settings');?>
</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Thông báo</h1>
			</div>
		</div> 
		<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->_DEV() || 1 == 1) {?>
		<div class="action-bar" style="margin-top: -5px">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="open_message(this)" class="ui-button ui-button--primary ui-title-bar__action"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
</a>
				</div>
			</div>
		</div>
		<?php }?>
	</div>
</header>
<div class="clearfix"></div>
<form action="" method="post" enctype="multipart/form-data">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['listMessage']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang($_smarty_tpl->tpl_vars['listMessage']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting']);?>
</h2>
								</div>
								<div class="ui-annotated-section__description">
									<?php $_smarty_tpl->_assignInScope('DescriptionField', ($_smarty_tpl->tpl_vars['listMessage']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting']).("_Description"));?>
									<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang($_smarty_tpl->tpl_vars['DescriptionField']->value);?>

								</div>
								<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
								<button class="btn btn-default" onClick="delete_message('<?php echo $_smarty_tpl->tpl_vars['listMessage']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting'];?>
')"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Delete');?>
</button>
								<?php }?>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section">
												<div class="p-md-3">
													<?php if ($_smarty_tpl->tpl_vars['listMessage']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting'] == 'SiteMsg_FAN') {?>
													<div class="form-group">
														<label class="col-form-label">Tiêu đề</label>
														<input class="form-control" name="iso-PageFAN_NAME" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('PageFAN_NAME');?>
" />
													</div>
													<div class="form-group">
														<label class="col-form-label">Tiêu đề URL</label>
														<input class="form-control" name="iso-PageFAN_URL_TITLE" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('PageFAN_URL_TITLE');?>
" />
													</div>
													<div class="form-group">
														<label class="col-form-label">URL</label>
														<input class="form-control" name="iso-PageFAN_URL" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('PageFAN_URL');?>
" />
													</div>
													<div class="form-group">
														<label class="col-form-label">Image</label>
														<div class="input-group">
															<input class="form-control" id="isoman_hidden_image" name="iso-PageFAN_IMAGE" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('PageFAN_IMAGE');?>
" />
															<div class="input-group-btn">
																<button type="button" class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('PageFAN_IMAGE');?>
" isoman_name="image"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Change');?>
</button>
															</div>
														</div>
													</div>
													<?php }?>
													<textarea id="textarea_<?php echo $_smarty_tpl->tpl_vars['listMessage']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting'];?>
_editor<?php echo $_smarty_tpl->tpl_vars['now']->value;?>
" class="textarea_intro_editor" name="iso-<?php echo $_smarty_tpl->tpl_vars['listMessage']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting'];?>
" style="width:100%"><?php echo html_entity_decode($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue($_smarty_tpl->tpl_vars['listMessage']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting']));?>
</textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<?php
}
}
?>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="UpdateConfiguration" name="submit" type="hidden">
				<div class="ui-page-actions__button-group"><?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>
</div>
			</div>
		</div>
	</div>
</form><?php }
}
