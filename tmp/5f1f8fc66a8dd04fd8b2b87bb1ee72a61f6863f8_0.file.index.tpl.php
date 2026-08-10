<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:52:33
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_billing/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315f1833b23_16399151',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5f1f8fc66a8dd04fd8b2b87bb1ee72a61f6863f8' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_billing/index.tpl',
      1 => 1785927055,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315f1833b23_16399151 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="dbx-card h-100">
    <div class="dbx-card__head">
        <span class="dbx-card__ic"><i class="bx bx-transfer-alt"></i></span>
        <h5 class="dbx-card__title">Giao dịch mới nhất</h5>
    </div>
    <div class="dbx-card__body dbx-card__body--flush ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=dashboard&tp=top_billing"
    data-options='{"skin":"dbx"}'>
        <div class="loader text-center py-8">
            <img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/loading.gif" />
            <p>Loading...</p>
        </div>
    </div>
</div><?php }
}
