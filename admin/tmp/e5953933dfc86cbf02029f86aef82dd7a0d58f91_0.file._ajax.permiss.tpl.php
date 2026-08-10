<?php
/* Smarty version 3.1.33, created on 2026-08-07 10:16:03
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_ajax.permiss.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a754df34a84b5_15789417',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e5953933dfc86cbf02029f86aef82dd7a0d58f91' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_ajax.permiss.tpl',
      1 => 1784691753,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a754df34a84b5_15789417 (Smarty_Internal_Template $_smarty_tpl) {
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

						<div class="col-12 col-md-4 mb-2">

							<div class="d-flex align-items-center" style="height: 40px">

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

				</fieldset>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

		</div>

		<div class="modal-footer">

			<button type="button" class="btn btn-success" for_id="<?php echo $_smarty_tpl->tpl_vars['for_id']->value;?>
" profile_type="<?php echo $_smarty_tpl->tpl_vars['profile_type']->value;?>
" 

				onClick="$Core.permiss.storage(this, event)">

				<span>Lưu lại</span>

			</button>

		</div>

	</form>

</div>

	<?php }
}
