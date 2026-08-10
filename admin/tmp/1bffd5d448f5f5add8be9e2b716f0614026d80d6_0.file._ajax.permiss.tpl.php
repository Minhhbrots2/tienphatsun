<?php
/* Smarty version 3.1.33, created on 2026-08-06 16:40:55
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/profile/_ajax.permiss.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7456a7eab3e7_65869455',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1bffd5d448f5f5add8be9e2b716f0614026d80d6' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/profile/_ajax.permiss.tpl',
      1 => 1784691718,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7456a7eab3e7_65869455 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-md">

	<form class="modal-content" method="POST">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 

			<h3 class="modal-title"><strong>Phân quyền</strong></h3>

		</div>

		<div class="modal-body modal-body-scrollable">

			<?php if (!empty($_smarty_tpl->tpl_vars['list_permiss']->value)) {?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_permiss']->value, '_oGroup');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oGroup']->value) {
?>

				<?php $_smarty_tpl->_assignInScope('list_items', $_smarty_tpl->tpl_vars['_oGroup']->value['list_items']);?>

				<fieldset>

					<legend><?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['title'];?>
</legend>

					<div class="row">

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_items']->value, '_oItem');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

						<div class="col-12 col-md-3 mb-2">

							<div class="d-flex align-items-center" style="height: 40px">

								<input type='hidden' value='0' name='permiss_mod[<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['code'];?>
]'>

								<label class="switch mr-2">

									<input type="checkbox" name="permiss_mod[<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['code'];?>
]"<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['checked'] == '1') {?> checked<?php }?> value="1"  />

									<span class="slider round"></span>

								</label>

								<span><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</span>

							</div>

						</div>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					</div>

					<?php if ($_smarty_tpl->tpl_vars['_oGroup']->value['code'] == 'group_billing') {?>

						<?php if (!empty($_smarty_tpl->tpl_vars['list_projects']->value)) {?>

						<fieldset>

							<legend>Phân quyền <?php echo $_smarty_tpl->tpl_vars['title_permiss']->value;?>
</legend>

							<div class="has-table table-wrapper">

								<table class="table-curved">

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, '_oProject', false, NULL, 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>

									<?php $_smarty_tpl->_assignInScope('_projectId', $_smarty_tpl->tpl_vars['_oProject']->value['project_id']);?>

									<?php $_smarty_tpl->_assignInScope('_permiss_billing', $_smarty_tpl->tpl_vars['_oProject']->value['permiss_billing']);?>

									<tr>

										<td width="5%" class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration'] : null);?>
</td>

										<td width="20%"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</td>

										<td>

											<select name="permiss_billing[<?php echo $_smarty_tpl->tpl_vars['_projectId']->value;?>
][]" multiple="true" class="form-control iso-select2">

												<?php if (!empty($_smarty_tpl->tpl_vars['list_billing_types']->value)) {?>

													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_billing_types']->value, '_oP');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oP']->value) {
?>

													<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oP']->value['property_id'],$_smarty_tpl->tpl_vars['_permiss_billing']->value)) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oP']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oP']->value['title'];?>
</option>

													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

												<?php }?>

											</select>

										</td>

									</tr>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								</table>

							</div>

						</fieldset>

						<?php }?>

					<?php }?>

				</fieldset>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

		</div>

		<div class="modal-footer">

			<button type="button" class="btn btn-success" profile_id="<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
" profile_type="<?php echo $_smarty_tpl->tpl_vars['profile_type']->value;?>
" 

				onClick="$Core.member.storage(this, event)">

				<span>Lưu lại</span>

			</button>

		</div>

	</form>

</div>

	<?php }
}
