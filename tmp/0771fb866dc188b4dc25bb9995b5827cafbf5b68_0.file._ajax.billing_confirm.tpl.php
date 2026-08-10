<?php
/* Smarty version 3.1.33, created on 2026-08-06 19:25:44
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.billing_confirm.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a747d4803af72_41868446',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0771fb866dc188b4dc25bb9995b5827cafbf5b68' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.billing_confirm.tpl',
      1 => 1786017473,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a747d4803af72_41868446 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-dialog-centered modal-xs">
	<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
	<form class="d-none" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="hid" value="upload" />
		<input type="file" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="upload_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onChange="$Core.billing.upload_sp_file(this, event)" 
		name="upload_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" />
	</form>
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Xác nhận thay đổi giao dịch <?php echo $_smarty_tpl->tpl_vars['stock_code']->value;?>
</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Lý do thay đổi</label>
				<textarea class="form-control" placeholder="Nhập lý do thay đổi" name="reason" rows="2" cols="255"></textarea>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-12  col-md-6 mb-2 mb-lg-0">
					<div class="form-label mb-1 d-flex align-items-center justify-content-between">
						<label class="mb-0">Xác nhận của GĐKD</label>
						<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" onClick="select_sp_file(this,event);" 
							toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="sales_dir_agree_image" title="Tải ảnh nên"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload');?>
</a>
					</div>
					<div class="cursor-pointer">
						<a id="sales_dir_agree_image_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onpaste="" class="d-block w-100 overflow-hidden d-flex align-items-center justify-content-center border rounded-1 h-px-150 xs:h-px-200">
							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sales_director_approval_image'])) {?>
							<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['sales_director_approval_image']);?>
" class="w-100 h-100 rounded-1" />
							<?php } else { ?>
							<div class="text-muted text-center">
								<i class='bx bx-camera'></i> Hình ảnh tin nhắn
							</div>
							<?php }?>
						</a>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="form-label mb-1 d-flex align-items-center justify-content-between">
						<label class="mb-0">Xác nhận Sale</label>
						<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" onClick="select_sp_file(this,event);" 
							toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="sale_agree_image" title="Tải ảnh nên"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload');?>
</a>
					</div>
					<div class="cursor-pointer">
						<a id="sale_agree_image_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onpaste="" class="d-block w-100 overflow-hidden border d-flex align-items-center justify-content-center rounded-1 h-px-150 xs:h-px-200">
							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['ccid_back'])) {?>
							<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['ccid_back']);?>
" class="w-100 h-100 rounded-1" />
							<?php } else { ?>
							<div class="text-muted text-center">
								<i class='bx bx-camera' ></i> Hình ảnh tin nhắn
							</div>
							<?php }?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_fields_change']->value, '_oV', false, '_oF');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oF']->value => $_smarty_tpl->tpl_vars['_oV']->value) {
?>
				<?php if ($_smarty_tpl->tpl_vars['_oF']->value == 'dep_logs') {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oV']->value, '_osV', false, '_osF');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_osF']->value => $_smarty_tpl->tpl_vars['_osV']->value) {
?>
					<input type="hidden" name="content_change[<?php echo $_smarty_tpl->tpl_vars['_oF']->value;?>
][<?php echo $_smarty_tpl->tpl_vars['_osF']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['_osV']->value;?>
" />
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php } else { ?>
				<input type="hidden" name="content_change[<?php echo $_smarty_tpl->tpl_vars['_oF']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['_oV']->value;?>
" />
				<?php }?>
			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Huỷ bỏ</button>
			<button type="button" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" _uid="<?php echo $_smarty_tpl->tpl_vars['_uid']->value;?>
" onClick="$Core.global.billing.upd_billing_changed(this, event)" 
				class="btn flex-fill btn-primary">Tiếp tục</button>
		</div>
	</form>
</div><?php }
}
