<?php
/* Smarty version 3.1.33, created on 2026-08-05 13:28:57
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/docs/_ajax.docs.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72d82916c703_64028509',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f01d166c6483d1b57abad7ae0e360a1da9d07956' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/docs/_ajax.docs.tpl',
      1 => 1784691611,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72d82916c703_64028509 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['list_docs']->value)) {?>

	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_docs']->value, '_odocs', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_odocs']->value) {
?>

	<?php $_smarty_tpl->_assignInScope('list_tags', $_smarty_tpl->tpl_vars['_odocs']->value['list_tags']);?>

	<tr>

		<td class="text-center">

			<div class="checkbox">

				<input type="checkbox" name="p_key[]" class="chkitem styled" 

				value="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" />

				<label></label>

			</div>

		</td>

		<td class="text-center"><img src="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['image'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.jpg'" class="border radius-3" style="width:40px;height:40px;object-fit:cover" /></td>

		<td><strong class="font-bold"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['title'];?>
</strong><?php if ($_smarty_tpl->tpl_vars['_odocs']->value['file_type']) {?> <span class="label label-info fs-tiny"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['file_type'];?>
</span><?php }?><br />

			<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['title_search'];?>


			<?php if (!empty($_smarty_tpl->tpl_vars['list_tags']->value)) {?>

			<div class="mt-2">

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_tags']->value, 'tag');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['tag']->value) {
?>

					<span class="label label-primary p-1 fs-tiny"><?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
</span>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php }?>

			</div>

		</td>

		<td><a href="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['content'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['content'];?>
" data-toggle="tooltip" target="_blank">Link</a></td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['type'];?>
</td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['project_name'];?>
</td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['block_name'];?>
</td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['building_name'];?>
</td>

		<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['cat_name'];?>
</td>

		<td class="text-left text-nowrap"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_odocs']->value['upd_date'],4);?>
</td>

		<td class="text-center">

			<div class="d-flex gap-2 align-items-center">

				<?php if ($_smarty_tpl->tpl_vars['type_list']->value == 'trash') {?>

				<button onClick="$Core.docs.restore(this, event)" project_meta_id="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" class="btn btn-icon btn-sm btn-default" title="Khôi phục"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('undo');?>
</button>

				<button onClick="$Core.docs.force_delete(this, event)" project_meta_id="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" class="btn btn-icon btn-sm btn-danger" title="Xoá vĩnh viễn"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

				<?php } else { ?>

				<button onClick="$Core.docs.open(this, event)" project_meta_id="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" class="btn btn-icon btn-sm btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>

				<button onClick="$Core.docs.delete(this, event)" project_meta_id="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" class="btn btn-icon btn-sm btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

				<?php }?>

			</div>

			

		</td>

	</tr>

	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<?php }
}
}
