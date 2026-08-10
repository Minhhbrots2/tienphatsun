<?php
/* Smarty version 3.1.33, created on 2026-08-08 11:46:10
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/report/_ajax.loadTimeLogin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76b4928d8fb4_20230179',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'af1e2159f5935963df3ebd2684a680c82659a61b' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/report/_ajax.loadTimeLogin.tpl',
      1 => 1784300232,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76b4928d8fb4_20230179 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-dialog-centered">

	<form class="modal-content" id="frmIssue" enctype="multipart/form-data">

		<div class="modal-header">

			<h5 class="modal-title">Lịch sử truy cập</h5>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<?php if (!empty($_smarty_tpl->tpl_vars['list_reports']->value)) {?>

			<div class="form-group mb-2">

				<div class="table-wrapper">

					<table class="table table-bordered">

						<thead><tr>

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

							<th width="3%" class="align-center bg-lighter text-center">No.</th><?php }?>

							<th class="align-center bg-lighter text-left">URL truy cập</th>

							<th class="align-center bg-lighter text-center">Thời gian</th>

						</tr></thead>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_reports']->value, '_oTime', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oTime']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

						<tr>

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

							<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td><?php }?>

							<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oTime']->value['url'];?>
</td>

							<td><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['_oTime']->value['reg_date'],true);?>
</td>

						</tr>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					</table>

				</div>

			</div>

			<?php }?>

		</div>

	</form>

</div>

<?php }
}
