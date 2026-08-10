<?php
/* Smarty version 3.1.33, created on 2026-08-08 20:30:55
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/competition/_ajax.detail.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a772f8f2b1824_79993887',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f03e11606233efdf8d94fcaf218dc8638d71ace3' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/competition/_ajax.detail.tpl',
      1 => 1786190297,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a772f8f2b1824_79993887 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title mb-0">
				Chi tiết điểm — <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['staff_name']->value, ENT_QUOTES, 'UTF-8', true);?>

				<?php if (!empty($_smarty_tpl->tpl_vars['program']->value)) {?><br /><small class="text-muted" style="font-size:11px"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['program']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</small><?php }?>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<table class="table table-sm align-middle">
				<thead>
					<tr>
						<th>Căn</th>
						<th>Loại</th>
						<th class="text-end">Giá trị</th>
						<th class="text-center">Ngày</th>
						<th class="text-end">Điểm</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($_smarty_tpl->tpl_vars['breakdown']->value)) {?>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['breakdown']->value, 'd');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['d']->value) {
?>
						<tr>
							<td><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value['stock_code'], ENT_QUOTES, 'UTF-8', true);?>
</strong></td>
							<td><span class="badge <?php if ($_smarty_tpl->tpl_vars['d']->value['type'] == 'Độc quyền') {?>bg-label-danger<?php } else { ?>bg-label-warning<?php }?>"><?php echo $_smarty_tpl->tpl_vars['d']->value['type'];?>
</span></td>
							<td class="text-end"><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['d']->value['value']/1000000000));?>
 tỷ</td>
							<td class="text-center"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['d']->value['date'],"%d/%m/%Y");?>
</td>
							<td class="text-end fw-bold"><?php echo $_smarty_tpl->tpl_vars['d']->value['score'];?>
</td>
						</tr>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<?php } else { ?>
						<tr><td colspan="5" class="text-center text-muted py-3">Chưa có giao dịch tạo điểm trong kỳ.</td></tr>
					<?php }?>
				</tbody>
				<tfoot>
					<tr class="table-active">
						<td colspan="4" class="text-end fw-bold">Tổng điểm</td>
						<td class="text-end fw-bold"><?php echo $_smarty_tpl->tpl_vars['total_score']->value;?>
</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php }
}
