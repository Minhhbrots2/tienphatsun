<?php
/* Smarty version 3.1.33, created on 2026-08-08 17:31:40
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_overview_blocks.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a77058c0e8772_52181425',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c07c096101a698331f535e8d61176890d0a6baa6' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_overview_blocks.tpl',
      1 => 1784691721,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a77058c0e8772_52181425 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>
<table cellspacing="0" cellpadding="0" class="table table-striped table-vertical" style="width:100%">
	<thead><tr>
		<th class="text-center" width="60px">Loại</th>
		<th class="text-left">Tên phân khu / tòa</th>
		<th class="text-center" width="70px">Hot/Top</th>
		<th class="text-center" width="90px">Quick Menu</th>
		<th class="text-center" width="70px">Menu</th>
		<th class="text-center" width="80px">Hết hàng</th>
		<th class="text-center" width="130px">Cập nhật</th>
		<th class="text-center" width="150px">Thao tác</th>
	</tr></thead>
	<tbody>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
		<?php $_smarty_tpl->_assignInScope('_block_id', $_smarty_tpl->tpl_vars['_oBlock']->value['property_id']);?>
		<?php $_smarty_tpl->_assignInScope('_block_information', $_smarty_tpl->tpl_vars['_oBlock']->value['more_information']);?>
		<?php $_smarty_tpl->_assignInScope('list_buildings', $_smarty_tpl->tpl_vars['_oBlock']->value['list_buildings']);?>
		<tr>
			<td class="text-center"><span class="label label-primary">PK</span></td>
			<td class="text-left"><strong><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</strong></td>
			<td bgcolor="#F5F5F5" class="text-center">
				<label class="switch">
					<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_hot" tp="_block" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_block_information']->value['is_hot'] == '1') {?> checked<?php }?>>
					<span class="slider round"></span>
				</label>
			</td>
			<td bgcolor="#F5F5F5" class="text-center">
				<?php if (@constant('_BLOCK_TYPE_LOWFLOOR_SALE') == $_smarty_tpl->tpl_vars['_oBlock']->value['parent_id']) {?>
				<label class="switch">
					<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_quick_menu" tp="_block" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_block_information']->value['is_quick_menu'] == '1') {?> checked<?php }?>>
					<span class="slider round"></span>
				</label>
				<?php } else { ?>--<?php }?>
			</td>
			<td bgcolor="#F5F5F5" class="text-center">
				<label class="switch">
					<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_menu" tp="_block" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_block_information']->value['is_menu'] == '1') {?> checked<?php }?>>
					<span class="slider round"></span>
				</label>
			</td>
			<td bgcolor="#F5F5F5" class="text-center">
				<label class="switch">
					<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_out_stock" tp="_block" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_block_information']->value['is_out_stock'] == '1') {?> checked<?php }?>>
					<span class="slider round"></span>
				</label>
			</td>
			<td class="text-center"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['_oBlock']->value['upd_date'],"%d/%m/%Y %H:%M");?>
</td>
			<td class="text-center" style="white-space:nowrap;">
				<a class="btn btn-icon btn-default btn_edit_block_<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
" title="Chỉnh sửa phân khu" href="javascript:void(0);" onClick="open_block(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" _openFrom="_project"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</a>
				<a class="btn btn-icon btn-default" title="Tiến độ" href="javascript:void(0);" onClick="open_progress(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" _openFrom="_project"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('tasks');?>
</a>
				<a class="btn btn-icon btn-default" title="Ảnh căn hộ theo loại (bóc mái / nội thất)" href="javascript:void(0);" onClick="open_interior_ns(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</a>
			</td>
		</tr>
			<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oBuiling');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBuiling']->value) {
?>
				<?php $_smarty_tpl->_assignInScope('_building_id', $_smarty_tpl->tpl_vars['_oBuiling']->value['property_id']);?>
				<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oBuiling']->value['more_information']);?>
				<tr>
					<td class="text-center"><span class="label label-default"><?php if ($_smarty_tpl->tpl_vars['_oBlock']->value['parent_id'] == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>CĂN<?php } else { ?>TÒA<?php }?></span></td>
					<td class="text-left" style="padding-left:28px"><?php echo $_smarty_tpl->tpl_vars['_oBuiling']->value['title'];?>
</td>
					<td bgcolor="#F5F5F5" class="text-center">
						<label class="switch">
							<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_top_menu" tp="_building" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['_building_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['is_top_menu'] == '1') {?> checked<?php }?>>
							<span class="slider round"></span>
						</label>
					</td>
					<td bgcolor="#F5F5F5" class="text-center">
						<label class="switch">
							<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_quick_menu" tp="_building" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['_building_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['is_quick_menu'] == '1') {?> checked<?php }?>>
							<span class="slider round"></span>
						</label>
					</td>
					<td bgcolor="#F5F5F5" class="text-center">
						<label class="switch">
							<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_menu" tp="_building" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['_building_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['is_menu'] == '1') {?> checked<?php }?>>
							<span class="slider round"></span>
						</label>
					</td>
					<td bgcolor="#F5F5F5" class="text-center">
						<label class="switch">
							<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_out_stock" tp="_building" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['_building_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['is_out_stock'] == '1') {?> checked<?php }?>>
							<span class="slider round"></span>
						</label>
					</td>
					<td class="text-center"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['_oBuiling']->value['upd_date'],"%d/%m/%Y %H:%M");?>
</td>
					<td class="text-center" style="white-space:nowrap;">
						<a class="btn btn-icon btn-default btn_edit_building_<?php echo $_smarty_tpl->tpl_vars['_building_id']->value;?>
" title="Chỉnh sửa tòa" href="javascript:void(0);" onClick="open_building(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['_building_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</a>
						<a class="btn btn-icon btn-default" title="Tiến độ" href="javascript:void(0);" onClick="open_progress(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['_building_id']->value;?>
" _openFrom="_project"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('tasks');?>
</a>
						<a class="btn btn-icon btn-default" title="Ảnh căn hộ theo loại (bóc mái / nội thất)" href="javascript:void(0);" onClick="open_interior_ns(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['_building_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('image');?>
</a>
						<a class="btn btn-icon btn-default" title="Quản lý bán hàng" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=stock&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
&block_id=<?php echo $_smarty_tpl->tpl_vars['_block_id']->value;?>
&building_id=<?php echo $_smarty_tpl->tpl_vars['_building_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('shopping-cart');?>
</a>
					</td>
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
	</tbody>
</table>
<?php } else { ?>
<div class="text-center" style="padding:30px 0">
	<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg" width="60px" />
	<p class="text-muted">Chưa có phân khu nào. Bấm "Thêm phân khu" để tạo.</p>
</div>
<?php }
}
}
