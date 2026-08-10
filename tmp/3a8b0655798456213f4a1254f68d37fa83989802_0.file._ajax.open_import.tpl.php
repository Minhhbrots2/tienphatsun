<?php
/* Smarty version 3.1.33, created on 2026-08-10 10:06:49
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/stock/_ajax.open_import.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7940493055a5_79055111',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3a8b0655798456213f4a1254f68d37fa83989802' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/stock/_ajax.open_import.tpl',
      1 => 1784300215,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7940493055a5_79055111 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog">

	<form method="POST" class="modal-content">

		<div class="modal-header">

			<h5 class="modal-title">Cập nhật bảng hàng</h5>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<div class="table-wrapper">

				<table width="100%" class="table table-bordered">

				<?php if (!empty($_smarty_tpl->tpl_vars['list_group_projects']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_group_projects']->value, '_oProject', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

					<?php $_smarty_tpl->_assignInScope('list_groups', $_smarty_tpl->tpl_vars['_oProject']->value['list_groups']);?>

					<?php if (!empty($_smarty_tpl->tpl_vars['list_groups']->value)) {?>

						<tr>

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

							<td width="10%" class="bg-lighter text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td><?php }?>

							<td class="bg-lighter" colspan="3">

								<strong><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</strong>

							</td>

						</tr>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_groups']->value, '_oI', false, NULL, 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>

						<tr class="text-nowrap">

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

							<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
. <?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration'] : null);?>
</td><?php }?>

							<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
</td>

							<td class="text-center" width="25%"><a target="_blank" class="btn btn-outline-default" href="https://docs.google.com/spreadsheets/d/<?php echo $_smarty_tpl->tpl_vars['_oI']->value['spreadsheetId'];?>
/edit#gid=0">Link <i class="bx bx-link-external fs-12"></i></a></td>

							<td width="10%" class="text-center"><button<?php if (!empty($_smarty_tpl->tpl_vars['_oI']->value['spreadsheetId'])) {
} else { ?> disabled<?php }?> stock_type="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['type_id'];?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
" spreadsheetId="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['spreadsheetId'];?>
" onClick="$Core.global.stock.start_import(this, event)" class="btn btn-primary">Crawl</button></td>

						</tr>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<?php }?>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php }?>

				</table>

			</div>

		</div>

	</form>

</div>

<?php }
}
