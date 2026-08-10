<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:52:33
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/home_billing_confirm/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315f18473a6_11020620',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0a8e2eb707738141ed1a6b95e23181639451391a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/home_billing_confirm/index.tpl',
      1 => 1785927036,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315f18473a6_11020620 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['total_billing_changing_confirms']->value > '0') {?>

	<div class="card mb-2">

		<div class="card-header d-flex align-items-center justify-content-between">

			<h5 class="card-title mb-0">Giao dịch thay đổi</h5>

			<span class="badge badge-center rounded-pill bg-label-danger"><?php echo $_smarty_tpl->tpl_vars['total_billing_changing_confirms']->value;?>
</span>

		</div>

		<div class="card-body billing_changing_confirms ajax" data-options="{}" 

			data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_billing_changing_confirms">

			<div class="animate-bg w-100 h-px-15 rounded-2 mb-2"></div>

			<div class="w-100 d-flex align-items-center justify-content-between mb-2 gap-3">

				<div class="animate-bg w-100 h-px-15 rounded-2"></div>

				<div class="animate-bg w-100 h-px-15 rounded-2"></div>

			</div>

			<div class="animate-bg w-100 h-px-15 rounded-2 mb-2"></div>

			<div class="w-100 d-flex align-items-center justify-content-between gap-3">

				<div class="animate-bg w-100 h-px-15 rounded-2"></div>

				<div class="animate-bg w-100 h-px-15 rounded-2"></div>

			</div>

		</div>

	</div>

<?php }
}
}
