<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:31:21
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.load_agency_link.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7694f91f5f91_19656031',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '595e4fd45a8deb9c363bdfc151e4dad314b118ec' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.load_agency_link.tpl',
      1 => 1784299639,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7694f91f5f91_19656031 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="table-container overflow-auto text-nowrap no-shadow table-container2 " style="max-height: 300px">
	<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
		<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
			<tr>
				<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
				<th class="align-center h-px-40 zindex-3 text-center" colspan="2"><?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>Phân khu<?php } else { ?>Dự án<?php }?></th>
			</tr>
			<tr>
				<th class="align-center h-px-40 text-center">Có link</th>
				<th class="align-center h-px-40 text-center">Chưa có link</th>
			</tr>
		</thead>
		<tbody class="table-border-bottom-0">
			<?php if (!empty($_smarty_tpl->tpl_vars['list_agency']->value)) {?>
				<?php $_smarty_tpl->_assignInScope('index', 0);?>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_agency']->value, '_oItem', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
					<?php $_smarty_tpl->_assignInScope('lstHasLink', $_smarty_tpl->tpl_vars['_oItem']->value['lstHasLink']);?>
					<?php $_smarty_tpl->_assignInScope('lstNotHasLink', $_smarty_tpl->tpl_vars['_oItem']->value['lstNotHasLink']);?>
					<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >
						<td class="text-nowrap" data-label="Tiêu đề" width="100px"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>
						<td class="text-center fw-bold">
							<div class="d-flex flex-wrap gap-1">
								<?php if (!empty($_smarty_tpl->tpl_vars['lstHasLink']->value)) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstHasLink']->value, '_oItemLink', false, NULL, 'i_y', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItemLink']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i_y']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i_y']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i_y']->value['index'];
?>
										<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_y']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_y']->value['first'] : null)) {?>|<?php }?><a href="<?php echo $_smarty_tpl->tpl_vars['_oItemLink']->value['link'];?>
" target="_blank" class="text-link text-decoration-underline"><?php echo $_smarty_tpl->tpl_vars['_oItemLink']->value['title'];?>
</a>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
							</div>
						</td>
						<td class="text-center fw-bold">
							<div class="d-flex flex-wrap gap-1">
								<?php if (!empty($_smarty_tpl->tpl_vars['lstNotHasLink']->value)) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstNotHasLink']->value, '_oItemNotLink', false, NULL, 'i_n', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItemNotLink']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i_n']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i_n']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i_n']->value['index'];
?>
										<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_i_n']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i_n']->value['first'] : null)) {?>|<?php }?><a class="text-danger"><?php echo $_smarty_tpl->tpl_vars['_oItemNotLink']->value['title'];?>
</a>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
							</div>
						</td>
					</tr>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			<?php } else { ?>
				<tr>
					<td class="text-center" colspan="3">
						Danh sách trống!
					</td>
				</tr>
			<?php }?>
		</tbody>
	</table>
</div><?php }
}
