<?php
/* Smarty version 3.1.33, created on 2026-08-08 17:53:58
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.billing_import_config.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a770ac6819426_13463834',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b50fe07631fd8f1114ccb09783d9b05c16c711a4' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.billing_import_config.tpl',
      1 => 1786165063,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a770ac6819426_13463834 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-lg">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title mb-0"><i class="bx bx-cog me-1"></i> Map cột Google Sheet → trường giao dịch</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body" style="max-height:65vh;overflow:auto">
			<?php if (empty($_smarty_tpl->tpl_vars['header']->value)) {?>
				<div class="alert alert-warning py-2 mb-0">
					Không đọc được dòng tiêu đề. Kiểm tra Spreadsheet ID, tên sheet và quyền chia sẻ.
				</div>
			<?php } else { ?>
			<p class="text-muted mb-2">
				<small>Chọn trường đích cho từng cột. Bỏ trống nếu không dùng. Mỗi trường (*) là bắt buộc và chỉ gán cho 1 cột.</small>
			</p>
			<table class="table table-sm table-bordered align-middle mb-0">
				<thead>
					<tr class="bg-lighter">
						<th width="55" class="text-center">Cột</th>
						<th>Tiêu đề</th>
						<th>Dữ liệu mẫu</th>
						<th width="230">Trường đích</th>
					</tr>
				</thead>
				<tbody>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['header']->value, 'colTitle', false, 'colIdx');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['colIdx']->value => $_smarty_tpl->tpl_vars['colTitle']->value) {
?>
					<tr>
						<td class="text-center fw-semibold"><?php echo $_smarty_tpl->tpl_vars['col_letters']->value[$_smarty_tpl->tpl_vars['colIdx']->value];?>
</td>
						<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['colTitle']->value, ENT_QUOTES, 'UTF-8', true);?>
</td>
						<td class="text-muted"><small>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['samples']->value, 'srow', false, NULL, 's', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['srow']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['total'];
if (isset($_smarty_tpl->tpl_vars['srow']->value[$_smarty_tpl->tpl_vars['colIdx']->value]) && $_smarty_tpl->tpl_vars['srow']->value[$_smarty_tpl->tpl_vars['colIdx']->value] != '') {
echo htmlspecialchars($_smarty_tpl->tpl_vars['srow']->value[$_smarty_tpl->tpl_vars['colIdx']->value], ENT_QUOTES, 'UTF-8', true);
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['last'] : null)) {?> · <?php }
}
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</small></td>
						<td>
							<select name="columns[<?php echo $_smarty_tpl->tpl_vars['colIdx']->value;?>
]" class="form-select form-select-sm iso-select2" data-width="100%">
								<option value="">— Bỏ qua —</option>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['import_fields']->value, 'fld', false, 'fkey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['fkey']->value => $_smarty_tpl->tpl_vars['fld']->value) {
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['fkey']->value;?>
"<?php if (isset($_smarty_tpl->tpl_vars['saved_config']->value[$_smarty_tpl->tpl_vars['colIdx']->value]) && $_smarty_tpl->tpl_vars['saved_config']->value[$_smarty_tpl->tpl_vars['colIdx']->value] == $_smarty_tpl->tpl_vars['fkey']->value) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['fld']->value['label'];
if ($_smarty_tpl->tpl_vars['fld']->value['required']) {?> *<?php }?></option>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</select>
						</td>
					</tr>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</tbody>
			</table>
			<?php }?>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.billingImport.saveConfig(this,event)">
				<i class="bx bx-save me-1"></i> Lưu cấu hình
			</button>
		</div>
	</form>
</div>
<?php }
}
