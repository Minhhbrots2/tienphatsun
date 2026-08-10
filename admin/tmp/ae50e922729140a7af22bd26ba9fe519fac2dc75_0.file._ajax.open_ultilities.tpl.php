<?php
/* Smarty version 3.1.33, created on 2026-08-07 10:34:58
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_ajax.open_ultilities.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75526251b658_66908557',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ae50e922729140a7af22bd26ba9fe519fac2dc75' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_ajax.open_ultilities.tpl',
      1 => 1784691753,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75526251b658_66908557 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-xs">

	<form class="modal-content" method="POST">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 

			<h3 class="modal-title"><strong>Chọn tiện ích</strong></h3>

		</div>

		<div class="modal-body modal-body-scrollable">

			<?php if (!empty($_smarty_tpl->tpl_vars['list_ultilites']->value)) {?>

				<div class="checkbox py-2">

					<input type="checkbox" id="check_all_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="check_all styled" toId="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="1" onChange="$Core.global.select_checkbox(this,event)" _type="_all" >

					<label for="check_all_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Chọn tất cả</label>

				</div>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_ultilites']->value, '_oItem');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

				<div class="border radius-3 p-3 mb-2">

					<div class="checkbox">

						<input type="checkbox" name="utility_id[]" class="chkitem_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
 styled" value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
"  onChange="$Core.global.select_checkbox(this,event)" _type="_item" toId="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oItem']->value['property_id'],$_smarty_tpl->tpl_vars['permiss_ultilities']->value)) {?>checked<?php }?> >

						<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];
if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['role'])) {?><span class="fs-tiny">(<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['role'];?>
)</span><?php }?></label>

					</div>

				</div>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

		</div>

		<div class="modal-footer">

			<button type="button" class="btn btn-success" for_id="<?php echo $_smarty_tpl->tpl_vars['for_id']->value;?>
" 

				profile_type="<?php echo $_smarty_tpl->tpl_vars['profile_type']->value;?>
" onClick="$Core.property.save_list_ultilities(this, event)">

				<span>Lưu lại</span>

			</button>

		</div>

	</form>

</div>

	<?php }
}
