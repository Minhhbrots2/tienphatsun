<?php
/* Smarty version 3.1.33, created on 2026-08-08 15:06:32
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.contact_footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76e3883a1994_73482324',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9a905519a18a533b906e4803d9d78e5ff5911619' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.contact_footer.tpl',
      1 => 1784300214,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76e3883a1994_73482324 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-ipad">
	<form class="modal-content">
		<div class="modal-header position-relative">
			<h5 class="modal-title">Liên hệ phòng ban</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="table-container overflow-x-auto no-shadow mb-2" >
				<table cellpadding="0" cellspacing="0" class="table mb-0 ContactFooter table-bordered min-w-px-500">
					<thead><tr>
						<th class="align-center h-px-35 bg-lighter"><i class="bx bx-move"></i></th>
						<th class="align-center h-px-35 bg-lighter">Phòng ban</th>
						<th class="align-center h-px-35 bg-lighter">Họ và tên</th>
						<th class="align-center h-px-35 bg-lighter">SĐT</th>
						<th class="align-center h-px-35 bg-lighter" width="45px"></th>
					</tr></thead>
					<tbody>
						<?php if (!empty($_smarty_tpl->tpl_vars['ContactFooter']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ContactFooter']->value, '_oContact');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oContact']->value) {
?>
							<?php $_smarty_tpl->_assignInScope('_rowId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
							<tr class="contact_row">
								<td class="align-center text-center">
									<a class="mySortableHandler"><i class="bx bx-move"></i></a>
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Tên phòng ban" 
										name="ContactFooter[<?php echo $_smarty_tpl->tpl_vars['_rowId']->value;?>
][title]" value="<?php echo $_smarty_tpl->tpl_vars['_oContact']->value['title'];?>
" />
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Họ và tên" 
										name="ContactFooter[<?php echo $_smarty_tpl->tpl_vars['_rowId']->value;?>
][name]" value="<?php echo $_smarty_tpl->tpl_vars['_oContact']->value['name'];?>
" />
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Số điện thoại" 
										name="ContactFooter[<?php echo $_smarty_tpl->tpl_vars['_rowId']->value;?>
][phone]" value="<?php echo $_smarty_tpl->tpl_vars['_oContact']->value['phone'];?>
" />
								</td>
								<td class="align-center text-center">
									<button onClick="$Core.contact.delete_row(this, event)" class="btn btn-icon btn-sm btn-outline-default">
										<i class="bx bx-trash"></i>
									</button>
								</td>
							</tr>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php } else { ?>
							<?php $_smarty_tpl->_assignInScope('_rowId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
							<tr class="contact_row">
								<td class="align-center text-center">
									<a class="mySortableHandler"><i class="bx bx-move"></i></a>
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Tên phòng ban" 
										name="ContactFooter[<?php echo $_smarty_tpl->tpl_vars['_rowId']->value;?>
][title]" />
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Họ và tên" 
										name="ContactFooter[<?php echo $_smarty_tpl->tpl_vars['_rowId']->value;?>
][name]" />
								</td>
								<td class="align-center text-left">
									<input type="text" class="form-control required" placeholder="Số điện thoại" 
										name="ContactFooter[<?php echo $_smarty_tpl->tpl_vars['_rowId']->value;?>
][phone]" />
								</td>
								<td class="align-center text-center">
									<button onclick="$Core.contact.delete_row(this, event)" class="btn btn-icon btn-sm btn-outline-default">
										<i class="bx bx-trash"></i>
									</button>
								</td>
							</tr>
						<?php }?>
						<tr>
							<td class="bg-lighter" colspan="5">
								<button type="button" onClick="$Core.contact.add_row(this, event)" class="btn btn-outline-default btn-sm">
									<i class="bx bx-plus"></i> Thêm dòng</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
			<button data-toggle="ripple" type="button" onClick="$Core.contact.save(this, event)" class="btn btn-primary">Lưu & thêm</button>
		</div>
	</form>
</div><?php }
}
