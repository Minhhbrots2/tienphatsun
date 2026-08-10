<?php
/* Smarty version 3.1.33, created on 2026-07-30 10:09:27
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ac067555940_21476843',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dde646626fed4dd4b321828f72e4bb9f377a6251' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/default.tpl',
      1 => 1784691722,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ac067555940_21476843 (Smarty_Internal_Template $_smarty_tpl) {
?><header class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Cấu hình</h1>
			</div>
		</div>
	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>
</header>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<section class="ui-card">
					<nav>
						<h2 class="helper--visually-hidden">Cấu hình</h2>
						<ul class="area-settings-nav">
							<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_settings']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
							<li class="area-settings-nav__item">
								<a class="area-settings-nav__action" href="<?php echo $_smarty_tpl->tpl_vars['clsAdminButton']->value->getURL($_smarty_tpl->tpl_vars['list_settings']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['adminbutton_id']);?>
" aria-disabled="false">
									<div class="area-settings-nav__media"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon($_smarty_tpl->tpl_vars['list_settings']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['class_iconpage']);?>
</div>
									<div>
										<p class="area-settings-nav__title"> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang($_smarty_tpl->tpl_vars['list_settings']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title_page']);?>
</p>
										<p class="area-settings-nav__description"><?php echo $_smarty_tpl->tpl_vars['list_settings']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['desc_page'];?>
</p>
									</div>
								</a>
							</li>
							<?php
}
}
?>
						</ul>
					</nav>
				</section>
			</div>
		</div>
	</div>
</div>
<div class="modal" data-tg-refresh="modal" id="modal_container" style="display: none;" aria-hidden="true" aria-labelledby="ModalTitle" tabindex="-1"></div>
<div class="modal-bg" data-tg-refresh="modal" id="modal_backdrop"></div>
<?php }
}
