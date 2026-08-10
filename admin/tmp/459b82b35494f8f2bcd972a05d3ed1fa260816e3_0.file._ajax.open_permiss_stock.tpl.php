<?php
/* Smarty version 3.1.33, created on 2026-08-08 10:55:15
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/ajax/user/_ajax.open_permiss_stock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76a8a35e2496_22303600',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '459b82b35494f8f2bcd972a05d3ed1fa260816e3' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/ajax/user/_ajax.open_permiss_stock.tpl',
      1 => 1784691580,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76a8a35e2496_22303600 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog">

	<div class="modal-content">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 

			<h3 class="modal-title"><strong>Chọn phân khu phụ trách <?php echo $_smarty_tpl->tpl_vars['oneUser']->value['first_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['oneUser']->value['last_name'];?>
</strong></h3>

		</div>

		<form action="" method="post" id="frmPermiss" encrupt="miltipart/form-data">

			<div class="modal-body">

				<fieldset class="box_check">

					<legend>Tài khoản CA phụ trách</legend>

					<div class="">

						<select placeholder="Giám đốc dự án" name="staff_permiss_id" class="form-control iso-select2">

							<option value="0">--Chọn--</option>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_profile']->value, '_oProfile', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProfile']->value) {
?>

								<option value="<?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['profile_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['more_information']->value['staff_permiss_id'] == $_smarty_tpl->tpl_vars['_oProfile']->value['profile_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['full_name'];?>
</option>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</select>

					</div>

				</fieldset>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_project']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

					<?php $_smarty_tpl->_assignInScope('arr_block', $_smarty_tpl->tpl_vars['_oItem']->value['arr_block']);?>

					<fieldset class="box_check">

						<legend><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</legend>

						<div class="form-check">

							<input class="form-check-input check_all" type="checkbox" value="all" id="all_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.user.checkedAll(this,event)">

							<label class="form-check-label" for="all_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Chọn tất cả</label>

						</div>

						<div class="row">

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_block']->value, '_oBlock', false, 'k_block', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_block']->value => $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>

								<div class="col-md-4">

									<div class="form-check">

										<input class="form-check-input chkitem" type="checkbox" name="block_permiss[]" value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
" id="block_<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.user.loadList()" <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oBlock']->value['property_id'],$_smarty_tpl->tpl_vars['block_permiss']->value)) {?>checked<?php }?> >

										<label class="form-check-label" for="block_<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</label>

									</div>

								</div>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</div>

					</fieldset>	

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>			

			</div>

			<input type="hidden" name="user_id" value="<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
" />

			<div class="modal-footer">

				<button class="btn btn-primary" onClick="$Core.user.save_permiss_stock(this,event)">Lưu</button>

			</div>

		</form>

	</div>

</div><?php }
}
