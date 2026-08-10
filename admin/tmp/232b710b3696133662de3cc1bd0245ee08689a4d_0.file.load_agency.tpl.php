<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:47:37
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/load_agency.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7698c9131410_58134979',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '232b710b3696133662de3cc1bd0245ee08689a4d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/load_agency.tpl',
      1 => 1784691722,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7698c9131410_58134979 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['lstProperty']->value)) {?>

	<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstProperty']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_0_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

		<?php $_smarty_tpl->_assignInScope('property_id', $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id']);?>

		<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['more_information']);?>

		<tr class="bold" id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
">

			<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">

				<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>


			</td>

			<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">

				<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">

					<button class="btn btn-default" onClick="open_property(this)" type="button" property_id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>

					<button class="btn btn-default" onClick="delete_property(this)" type="button" property_id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

				</div>

			</td>

			<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
</td>

			<td class="text-nowrap" data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['property_id']->value);?>


				<a href="javascript:void(0);" onclick="open_property(this)" parent_id="<?php echo $_smarty_tpl->tpl_vars['property_id']->value;?>
" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
" property_id="0"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/add.png" width="25px" /></a>

			</td>

			<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">

				<?php if (!empty($_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_code'])) {?>

					<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_code'];?>


				<?php } else { ?>

				--

				<?php }?>

			</td>

			<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">

				<?php if (!empty($_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title_vn'])) {?>

					<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title_vn'];?>


				<?php } else { ?>

				--

				<?php }?>

			</td>

			<!-- MOC -->

				<?php if (!empty($_smarty_tpl->tpl_vars['agency_hidden_stock_MOC']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['agency_hidden_stock_MOC']->value, 'hidden_stock', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['hidden_stock']->value) {
?>

						<?php if (!empty($_smarty_tpl->tpl_vars['hidden_stock']->value['is_vin'])) {?>

							<?php $_smarty_tpl->_assignInScope('key_hide_stock', "MOC_stock_vin");?>

						<?php } else { ?>

							<?php $_smarty_tpl->_assignInScope('key_hide_stock', "MOC_".((string)$_smarty_tpl->tpl_vars['property_id']->value)."_".((string)$_smarty_tpl->tpl_vars['hidden_stock']->value['block_id']));?>

						<?php }?>

						<td class="text-center">

							<label class="switch">

								<input type="checkbox" onChange="hide_stock_globe(this, event)"<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value[$_smarty_tpl->tpl_vars['key_hide_stock']->value]) && $_smarty_tpl->tpl_vars['more_information']->value[$_smarty_tpl->tpl_vars['key_hide_stock']->value] == '1') {?> checked<?php }?> to_field="<?php echo $_smarty_tpl->tpl_vars['key_hide_stock']->value;?>
" property_id="<?php echo $_smarty_tpl->tpl_vars['property_id']->value;?>
" value="1" class="switch_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" />

								<span class="slider round"></span>

							</label>

						</td>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php }?>

				<!-- user.FH -->										

				<?php if (!empty($_smarty_tpl->tpl_vars['agency_hidden_stock_FH']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['agency_hidden_stock_FH']->value, 'hidden_stock', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['hidden_stock']->value) {
?>

						<?php if (!empty($_smarty_tpl->tpl_vars['hidden_stock']->value['is_vin'])) {?>

							<?php $_smarty_tpl->_assignInScope('key_hide_stock', "FH_stock_vin");?>

						<?php } else { ?>

							<?php $_smarty_tpl->_assignInScope('key_hide_stock', "FH_".((string)$_smarty_tpl->tpl_vars['property_id']->value)."_".((string)$_smarty_tpl->tpl_vars['hidden_stock']->value['block_id']));?>

						<?php }?>

						<td class="text-center">

							<label class="switch">

								<input type="checkbox" onChange="hide_stock_globe(this, event)"<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value[$_smarty_tpl->tpl_vars['key_hide_stock']->value]) && $_smarty_tpl->tpl_vars['more_information']->value[$_smarty_tpl->tpl_vars['key_hide_stock']->value] == '1') {?> checked<?php }?> to_field="<?php echo $_smarty_tpl->tpl_vars['key_hide_stock']->value;?>
" property_id="<?php echo $_smarty_tpl->tpl_vars['property_id']->value;?>
" value="1" class="switch_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" />

								<span class="slider round"></span>

							</label>

						</td>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php }?>

			

			<!-- End -->

			<td class="text-center">

				<label class="switch">

					<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.property.set_status(this, event)" 

						property_id="<?php echo $_smarty_tpl->tpl_vars['lstProperty']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
" value="1" />

					<span class="slider round"></span>

				</label>

			</td>

		</tr>

	<?php
}
}
?>

<?php } else { ?>

	<tr>

		<td colspan="7" class="text-center">

			<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->renderHTMLNoDocument($_smarty_tpl->tpl_vars['core']->value->get_Lang('Not any records(s) here'));?>


		</td>

	</tr>

<?php }
}
}
