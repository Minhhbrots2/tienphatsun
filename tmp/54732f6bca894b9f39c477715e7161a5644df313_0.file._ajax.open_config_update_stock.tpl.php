<?php
/* Smarty version 3.1.33, created on 2026-08-10 10:06:25
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.open_config_update_stock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a794031a61d46_09691849',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '54732f6bca894b9f39c477715e7161a5644df313' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.open_config_update_stock.tpl',
      1 => 1784299639,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a794031a61d46_09691849 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-fullscreen">
	<div class="modal-content">
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-header"> 
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
				<h3 class="modal-title"><strong>Quản lý đại lý không cập nhật bảng hàng</strong></h3>
			</div>
			<div class="modal-body">
				<div class="table-container no-shadow overflow-auto" style="max-height:calc(100vh - 120px)">
					<table class="table table-bordered dragable installed" cellpadding="0" cellspacing="0" width="100%">
						<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>
							<thead class="position-sticky top-0 zindex-5" style="background: #f5f7f8 !important;">
								<tr>
									<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
										<th class="align-center h-px-40 zindex-3" width="3%" rowspan="3">No.</th>
									<?php }?>
									<th class="align-center h-px-40 zindex-3" rowspan="3" width="15%">Đại lý</th>
									<th class="align-center h-px-40 text-center"  colspan="<?php echo count($_smarty_tpl->tpl_vars['listBlocks']->value);?>
">Phân khu</th>
								</tr>
								<tr>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['listBlocks']->value, '_oBlock', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oBlock']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
										<th class="align-center h-px-40 text-center no-sticky"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_code'];?>
</th>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</tr>
								<tr>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['listBlocks']->value, '_oBlock', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oBlock']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
										<th class="text-left">
											<div class="d-flex gap-1 justify-content-center">
												<button class="btn btn-sm btn-icon btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.crawl.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
												<button class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.crawl.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
											</div>
										</th>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</tr>
							</thead>
						<?php } else { ?>
							<thead class="position-sticky top-0 zindex-5" style="background: #f5f7f8 !important;">
								<tr>
									<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
										<th class="align-center h-px-40 zindex-3" width="3%" rowspan="3">No.</th>
									<?php }?>
									<th class="align-center h-px-40 zindex-3" rowspan="3" width="15%">Đại lý</th>
									<th class="align-center h-px-40 text-center"  colspan="<?php echo count($_smarty_tpl->tpl_vars['lstProjects']->value);?>
">Dự án</th>
								</tr>
								<tr>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstProjects']->value, '_oProject', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProject']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
										<th class="align-center h-px-40 text-center no-sticky"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['code'];?>
</th>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</tr>
								<tr>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstProjects']->value, '_oProject', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProject']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
										<th class="text-left">
											<div class="d-flex gap-1 justify-content-center">
												<button class="btn btn-sm btn-icon btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.crawl.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
												<button class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.crawl.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
											</div>
										</th>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</tr>
							</thead>
						<?php }?>
						<tbody class="table-border-bottom-0">
						<?php if (!empty($_smarty_tpl->tpl_vars['list_agency']->value)) {?>
							<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_agency']->value, '_oItem', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
									<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >
										<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
										<td class="align-center text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>
										<?php }?>
										<td class="text-nowrap" data-label="Tiêu đề" width="100px">
											<div class="d-flex align-items-center justify-content-between gap-1"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['listBlocks']->value, '_oBlock', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
											<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
											<td class="text-center" >
												<label class="switch">
													<input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
" class="switch_<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="block_not_upd_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
[]" {} <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oBlock']->value['property_id'],$_smarty_tpl->tpl_vars['_oItem']->value['arr_block_not_upd'])) {?>checked<?php }?> >
													<span class="slider round"></span>
												</label>
											</td>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</tr>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php } else { ?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_agency']->value, '_oItem', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
									<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >
										<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
										<td class="align-center text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>
										<?php }?>
										<td class="text-nowrap" data-label="Tiêu đề" width="100px">
											<div class="d-flex align-items-center justify-content-between gap-1"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstProjects']->value, '_oProject', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProject']->value) {
?>
											<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
											<td class="text-center" >
												<label class="switch">
													<input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
" class="switch_<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="project_not_upd_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
[]" {} <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oProject']->value['project_id'],$_smarty_tpl->tpl_vars['_oItem']->value['arr_project_not_upd'])) {?>checked<?php }?> >
													<span class="slider round"></span>
												</label>
											</td>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</tr>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php }?>
						<?php } else { ?>
							<tr>
								<td class="text-center" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>4<?php } else { ?>5<?php }?>">
									Danh sách trống !
								</td>
							</tr>
						<?php }?>
						</tbody>
					</table>				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
					<button type="button" onclick="$Core.crawl.save_config_update_stock(this,event)" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" class="btn btn-primary">Lưu lại</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div><?php }
}
