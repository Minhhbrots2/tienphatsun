<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:13:24
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/_ajax.wishlist.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7690c4507830_52677340',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9e8605ace03c3ea7262c4750754c3264a658131f' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/_ajax.wishlist.tpl',
      1 => 1784299680,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7690c4507830_52677340 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['list_stocks']->value)) {?>

	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_stocks']->value, '_oStock', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStock']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

	<tr class="iso_search_item">

		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

		<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

		<td class="text-left"><a href="javascript:void(0);" onClick="$Core.helper.open_stock('<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
')"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['ms_code'];?>


			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {
echo $_smarty_tpl->tpl_vars['_oStock']->value['TINH_TRANG'];
}?></a>

		</td>

		<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['TINH_TRANG'];?>


		<?php } else { ?>

		<td class="text-left">

			<a href="javascript:void(0);" onClick="$Core.helper.open_stock('<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
')" ><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['ms_code'];?>
 <?php echo $_smarty_tpl->tpl_vars['_oStock']->value['TINH_TRANG'];?>
</a>

		</td>

		<?php }?>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['total_price_vat'];?>
 tỷ</td>

		<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['DT_TT'];?>
m<sup>2</sup></td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['LOAI_CAN'];?>
</td>

		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['HUONG_BC'];?>
</td>

		<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['PTG_LINK'];?>
</td>

		<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['POLICY_LINK'];?>
</td>

		<td class="text-center">

			<a onClick="$Core.helper.toggle_wishlist(this,event)" data-bs-toggle="tooltip" title="Loại bỏ" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
" class="btn saved p-1"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-heart fs-11');?>
</a>

		</td>

		<?php }?>

	</tr>

	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<?php } else { ?>

<tr>

	<td colspan="10" class="text-center">

		<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg" width="120px" />

		<p>Chưa có căn hộ nào trong danh mục yêu thích..</p>

	</td>

</tr>

<?php }
}
}
