<?php
/* Smarty version 3.1.33, created on 2026-08-08 15:32:35
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.sync_sold.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76e9a346c3b5_97228247',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a709755fe89d5bb18c7618aaddada81f40d7ae07' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.sync_sold.tpl',
      1 => 1784299654,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76e9a346c3b5_97228247 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['template_type']->value == "_form") {?>

<div class="modal-dialog modal-dialog-centered">

	<form class="d-none" enctype="multipart/form-data">

		<input id="select_image_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" accept="image/jpeg,image/jpg,image/png,application/pdf" 

		type="file" tp="gd" p_id="<?php echo $_smarty_tpl->tpl_vars['p_id']->value;?>
" p_field="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
" onchange="$Core.billing.upload_image(this,event)" name="image" />

	</form>

	<form class="modal-content" method="POST" >

		<div class="modal-header">

			<h5 class="modal-title"><?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</h5>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<div class="table table-wrapper mb-0">

				<table width="100%" class="table table-bordered m-0">

					<thead><tr>

						<th class="align-center bg-lighter h-px-35">Tiêu đề</th>

						<th class="align-center bg-lighter h-px-35">File Upload</th>

						<th class="w-px-50 bg-lighter h-px-30"></th>

					</tr></thead>

					<tbody class="holder_<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">

						<?php if (!empty($_smarty_tpl->tpl_vars['list_items']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_items']->value, '_oI', false, 'gId');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['gId']->value => $_smarty_tpl->tpl_vars['_oI']->value) {
?>

							<tr class="tr_<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
 tr_<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">

								<td class="text-left">

									<input type="text" placeholder="Tiêu đề" name="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
[<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
][title]" 

									class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
" />

								</td>

								<td class="text-left">

									<div class="input-group">

										<input type="text" placeholder="Nhập ảnh..." name="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
[<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
][image]" 

										class="form-control required <?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" p_field="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
" p_id="<?php echo $_smarty_tpl->tpl_vars['p_id']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onPaste="$Core.billing.upload_clipboard(this, event)" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['image'];?>
" />

										<button type="button" toId="select_image_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onClick="$Core.billing.select_image(this, event)" class="btn btn-icon btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload');?>
</button>

									</div>

								</td>

								<td class="text-left w-px-50">

									<button type="button" onClick="$Core.billing.delete_sp_file(this, event)" uid="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" p_field="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" class="btn p-2 btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-trash-alt');?>
</button>

								</td>

							</tr>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php } else { ?>

						<tr class="tr_<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
 tr_<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">

							<td class="text-left">

								<input type="text" placeholder="Tiêu đề" name="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][title]" class="form-control required" />

							</td>

							<td class="text-left">

								<div class="input-group">

									<input type="text" placeholder="Nhập ảnh..." p_field="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
" class="form-control required <?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" 

									p_id="<?php echo $_smarty_tpl->tpl_vars['p_id']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onPaste="$Core.billing.upload_clipboard(this, event)" name="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][image]" />

									<button type="button" toId="select_image_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.helper.select_image(this, event)" class="btn btn-icon btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload');?>
</button>

								</div>

							</td>

							<td  class="text-left w-px-50"></td>

						</tr>

						<?php }?>

					</tbody>

				</table>

			</div>

		</div>

		<div class="modal-footer">

			<div class="w-100 d-flex justify-content-between">

				<div class="p__left">

					<button type="button" toId="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" p_id="<?php echo $_smarty_tpl->tpl_vars['p_id']->value;?>
" p_field="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
" onClick="$Core.billing.add_sp_file(this, event)" class="btn btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-plus','Thêm dòng');?>
</button>

				</div>

				<div class="p__right">

					<button type="button" onClick="$Core.billing.save_sp_file(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" p_id="<?php echo $_smarty_tpl->tpl_vars['p_id']->value;?>
" p_field="<?php echo $_smarty_tpl->tpl_vars['p_field']->value;?>
" class="btn btn-primary">Cập nhật</button>

				</div>

			</div>

		</div>

	</form>

</div>

<?php } elseif ($_smarty_tpl->tpl_vars['template_type']->value == '_deposit_paid') {?>

<div class="modal-dialog">

	<form method="POST" class="modal-content">

		<div class="modal-header">

			<h5 class="modal-title">Cập nhật <?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</h5>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<?php if ($_smarty_tpl->tpl_vars['holderG']->value == 'deposit_paid') {?>

			<div class="form-row mb-2">

				<div class="col-6 mb-2 mb-lg-0">

					<label for="action_date" class="form-label mb-1">Ngày thực hiện</label>

					<input type="datetime-local" id="action_date" name="action_date" class="form-control required" 

					placeholder="dd/mm/yy" lang="vi-VN" value="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToISOString($_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['action_date']);?>
">

				</div>

				<div class="col-6">

					<label for="deposit_paid_amount" class="form-label mb-1">Số tiền</label>

					<div class="input-group input-group-merge mb-1">

						<input type="text" id="deposit_paid_amount" name="deposit_paid_amount" class="form-control numberonly price-In required" autocomplete="off" placeholder="0.00" value="<?php echo $_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['deposit_paid_amount'];?>
">

						<span class="input-group-text">.đ</span>

					</div>

				</div>

			</div>

			<div class="form-row mb-2">

				<div class="col-6">

					<label for="payer_name" class="form-label mb-1">Tên KH/UNC</label>

					<input type="text" id="payer_name" name="payer_name" class="form-control required" placeholder="Nguyễn Văn A" 

					value="<?php echo $_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['payer_name'];?>
">

				</div>

				<div class="col-6">

					<label for="trans_code" class="form-label mb-1">Mã FT</label>

					<input type="text" id="trans_code" name="trans_code" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['trans_code'];?>
" placeholder="Mã giao dịch">

				</div>

			</div>

			<div class="form-group mb-2">

				<label for="trans_code" class="form-label mb-1">Ghi chú</label>

				<textarea class="form-control" placeholder="Viết ghi chú" name="notes" rows="2"><?php echo $_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['notes'];?>
</textarea>

			</div>

			<?php } else { ?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['loop_arrs']->value, '_oText', false, '_oKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oKey']->value => $_smarty_tpl->tpl_vars['_oText']->value) {
?>

				<div class="mb-2 border rounded-2 p-3 interest_accrued_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">

					<input type="hidden" name="interest_accrued[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][status]" value="0" />

					<label class="d-flex align-items-center gap-2 mb-2 ant-checkbox">

						<input uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" type="checkbox" class="js__interest_accrued-status" value="1" 

							name="interest_accrued[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][status]"<?php if ($_smarty_tpl->tpl_vars['interest_accrued']->value[$_smarty_tpl->tpl_vars['_oKey']->value]['status'] == '1') {?> checked<?php }?>>

						<div class="d-flex flex-column gap text-nowrap"><?php echo $_smarty_tpl->tpl_vars['_oText']->value;?>
</div>

					</label>

					<div class="form-row mb-2">

						<div class="col-6 mb-2 mb-lg-0">

							<label class="form-label mb-1">Số tiền</label>

							<div class="input-group input-group-merge mb-1">

								<input uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" type="text" name="interest_accrued[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][amount]" autocomplete="off" placeholder="0.00" 

									class="form-control numberonly price-In required_field" value="<?php echo $_smarty_tpl->tpl_vars['interest_accrued']->value[$_smarty_tpl->tpl_vars['_oKey']->value]['amount'];?>
">

								<span class="input-group-text">.đ</span>

							</div>

						</div>

						<div class="col-6">

							<label class="form-label mb-1">Ngày phát sinh</label>

							<input uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" type="date" name="interest_accrued[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][accrual_date]" autocomplete="off" placeholder="dd/mm/YYYY" class="form-control required_field" value="<?php echo $_smarty_tpl->tpl_vars['interest_accrued']->value[$_smarty_tpl->tpl_vars['_oKey']->value]['accrual_date'];?>
">

						</div>

					</div>

					<div class="form-group mb-2">

						<label for="trans_code" class="form-label mb-1">Ghi chú</label>

						<textarea class="form-control" name="interest_accrued[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][notes]" placeholder="Viết ghi chú" rows="2"><?php echo $_smarty_tpl->tpl_vars['interest_accrued']->value[$_smarty_tpl->tpl_vars['_oKey']->value]['notes'];?>
</textarea>

					</div>

					<div class="p-3 bg-lighter rounded-2">

						<div class="d-flex gap-2 align-items-center">

							<input type="hidden" name="interest_accrued[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][is_completed]" value="0" />

							<label class="switch">

								<input type="checkbox" value="1"<?php if ($_smarty_tpl->tpl_vars['interest_accrued']->value[$_smarty_tpl->tpl_vars['_oKey']->value]['is_completed'] == '1') {?> checked<?php }?> 

									name="interest_accrued[<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
][is_completed]">

								<span class="slider round"></span>

							</label>

							<span class="text-muted">Hoàn thành <?php echo $_smarty_tpl->tpl_vars['_oText']->value;?>
</span>

						</div>

					</div>

				</div>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

		</div>

		<div class="modal-footer">

			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>

			<button type="button" class="btn btn-primary flex-fill" holderG="<?php echo $_smarty_tpl->tpl_vars['holderG']->value;?>
" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" 

				onClick="$Core.global.billing.save_activity(this, event)" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Cập nhật</button>

		</div>

	</form>

</div>

<?php } elseif ($_smarty_tpl->tpl_vars['template_type']->value == '_add_info') {?>

<div class="modal-dialog modal-ipad">

	<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

	<form class="d-none" method="POST" enctype="multipart/form-data">

		<input type="hidden" name="hid" value="upload" />

		<input type="file" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="upload_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onChange="$Core.billing.upload_sp_file(this, event)" 

		name="upload_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" />

	</form>

	<form method="POST" class="modal-content">

		<div class="modal-header">

			<h5 class="modal-title">Cập nhật thông tin căn bán</h5>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<div class="widget-block mb-3">

				<div class="widget-header" onClick="$Core.helper.toggle_block(this, event)">A. Thông tin khách hàng</div>

				<div class="widget-content">

					<div class="form-group form-row mb-2">

						<div class="col-6 col-md-4 mb-2 mb-lg-0">

							<label class="form-label mb-1">Họ & tên</label>

							<div class="input-group input-group-merge">

								<span class="input-group-text"><i class='bx bx-user'></i></span>

								<input type="text" class="form-control" name="customer_name" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_name'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_name'];
}?>" placeholder="Họ và tên" />

							</div>

						</div>

						<div class="col-6 col-md-4 mb-2 mb-lg-0">

							<label class="form-label mb-1">Số điện thoại</label>

							<div class="input-group input-group-merge">

								<span class="input-group-text"><i class='bx bx-phone-call'></i></span>

								<input type="text" class="form-control" name="customer_phone" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_phone'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_phone'];
}?>" placeholder="+84" />

							</div>

						</div>

						<div class="col-12 col-md-4">

							<label class="form-label mb-1">Email</label>

							<div class="input-group input-group-merge">

								<span class="input-group-text"><i class='bx bx-envelope'></i></span>

								<input type="text" class="form-control" name="customer_email" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_email'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_email'];
}?>" placeholder="example@gmail.com" />

							</div>

						</div>

					</div>

					<div class="form-group form-row mb-3">

						<div class="col-6 col-md-3 mb-2 mb-lg-0">

							<label class="form-label mb-1">CMT/CCID</label>

							<div class="input-group input-group-merge">

								<span class="input-group-text"><i class='bx bx-barcode-reader' ></i></span>

								<input type="text" class="form-control" name="identity_card" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['identity_card'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['identity_card'];
}?>" placeholder="CMT/CCID" />

							</div>

						</div>

						<div class="col-6 col-md-3 mb-2 mb-lg-0">

							<label class="form-label mb-1">Ngày cấp</label>

							<input type="datetime" class="form-control datepick" name="issuance_date" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['issuance_date'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['issuance_date'];
}?>" placeholder="dd/mm/yyyy" />

						</div>

						<div class="col-12 col-md-6">

							<label class="form-label mb-1">Nơi cấp</label>

							<div class="input-group input-group-merge">

								<span class="input-group-text"><i class='bx bx-map'></i></span>

								<input type="text" class="form-control" name="issuance_location" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['issuance_location'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['issuance_location'];
}?>" placeholder="Nơi cấp..." />

							</div>

						</div>

					</div>

					<div class="form-group form-row mb-2">

						<div class="col-6 mb-2 mb-lg-0">

							<div class="form-label mb-1 d-flex align-items-center justify-content-between">

								<label class="mb-0">Mặt trước</label>

								<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" onClick="select_sp_file(this,event);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="ccid_front" title="Tải ảnh nên"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload');?>
</a>

							</div>

							<div class="cursor-pointer">

								<div id="ccid_front_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="w-100 overflow-hidden d-flex align-items-center justify-content-center border rounded-1 bg-lighter h-px-<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>100<?php } else { ?>175<?php }?>">

									<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['ccid_front'])) {?>

									<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['ccid_front']);?>
" class="w-100 h-100 rounded-1" />

									<?php } else { ?>

									<div class="text-muted text-center">

										<i class='bx bx-camera'></i> Ảnh mặt trước

									</div>

									<?php }?>

								</div>

							</div>

						</div>

						<div class="col-6">

							<div class="form-label mb-1 d-flex align-items-center justify-content-between">

								<label class="mb-0">Mặt sau</label>

								<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" onClick="select_sp_file(this,event);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" to_field="ccid_back" title="Tải ảnh nên"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload');?>
</a>

							</div>

							<div class="cursor-pointer">

								<div id="ccid_back_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="w-100 overflow-hidden border d-flex align-items-center justify-content-center rounded-1 bg-lighter h-px-<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>100<?php } else { ?>175<?php }?>">

									<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['ccid_back'])) {?>

									<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['ccid_back']);?>
" class="w-100 h-100 rounded-1" />

									<?php } else { ?>

									<div class="text-muted text-center">

										<i class='bx bx-camera' ></i> Ảnh mặt sau

									</div>

									<?php }?>

								</div>

							</div>

						</div>

					</div>

					<div class="form-group form-row mb-2">

						<div class="col-12 col-md-6 mb-2 mb-lg-0">

							<label class="form-label mb-1">Địa chỉ thường trú 

								<a class="text-muted" title="Nhập giống trong sổ hộ khẩu">

									<i class="bx bx-help-circle fs-11"></i>

								</a>

							</label>

							<div class="input-group input-group-merge mb-1">

								<span class="input-group-text"><i class='bx bx-map'></i></span>

								<input type="text" class="form-control" name="permanent_address" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['permanent_address'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['permanent_address'];
}?>" placeholder="Nhập địa chỉ" />

							</div>

							<small class="form-text"></small>

						</div>

						<div class="col-12 col-md-6">

							<label class="form-label mb-1">Địa chỉ liên hệ</label>

							<div class="input-group input-group-merge">

								<span class="input-group-text"><i class='bx bx-map'></i></span>

								<input type="text" class="form-control" name="contact_address" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['contact_address'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['contact_address'];
}?>" placeholder="Nhập địa chỉ" />

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="widget-block mb-2">

				<div class="widget-header " onClick="$Core.helper.toggle_block(this, event)">B. Thông tin giao dịch</div>

				<div class="widget-content">

					<div class="form-group form-row mb-2">

						<div class="col-6 col-md-3 mb-2 mb-lg-0">

							<label class="form-label mb-1">Phương án TT</label>

							<select class="form-control form-select" name="billing_method">

								<option value="0">Lựa chọn phương án TT</option>

								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_BILLING_METHOD',$_smarty_tpl->tpl_vars['oBilling']->value['billing_method']);?>


							</select>

						</div>

						<div class="col-6 col-md-3 mb-2 mb-lg-0">

							<label class="form-label mb-1">Bảo lãnh NH</label>

							<select class="form-control form-select" name="bank_guarantee_id">

								<option value="0">Lựa chọn bảo lãnh NH</option>

								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_BANK_GUARANTEE',$_smarty_tpl->tpl_vars['more_information']->value['bank_guarantee_id']);?>


							</select>

						</div>

						<div class="col-12 col-md-3">

							<label class="form-label mb-1">Ngày ký HĐMB</label>

							<input type="datetime-local" class="form-control" name="contract_date" value="<?php if (!empty($_smarty_tpl->tpl_vars['oBilling']->value['contract_date'])) {
echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['oBilling']->value['contract_date'],5);
}?>" placeholder="dd/mm/yyyy" toCls="note_contract" onchange="$Core.billing.toggle_rescheduling_reason(this,event)"/>

						</div>

						<div class="col-3 col-md-3">

							<label for="totalgrand" class="form-label mb-1">Số tiền</label>

							<div class="input-group input-group-merge">

								<input type="text" id="totalgrand" name="totalgrand" class="form-control numberonly price-In required" autocomplete="off" 

									placeholder="0.00" value="<?php echo $_smarty_tpl->tpl_vars['oBilling']->value['totalgrand'];?>
">

								<span class="input-group-text">.đ</span>

							</div>

						</div>

					</div>

					<div class="alert alert-danger">Vui lòng nhập giá tiền chính xác của căn hộ</div>

					 <div class="form-group note_contract d-none mb-2">

						<label class="col-form-label pb-1">Lý do đổi lịch ký</label> 

						<textarea class="form-control" cols="5" name="rescheduling_reason" ></textarea>

					</div>

					<div class="form-group mb-2">

						<div class="d-flex align-items-center justify-content-between form-label">

							<label>Xác nhận cư trú/định danh mức 2</label>

							<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" p_field="residence_info" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onCLick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default text-none"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

						</div>

						<div class="clearfix"></div>

						<div id="residence_info_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['residence_info'])) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['residence_info'], '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

								<a class="download" data-fancybox="true" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_oI']->value['image']);?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-download',$_smarty_tpl->tpl_vars['_oI']->value['title']);?>
<a/>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php } else { ?>

								<span class="text-muted fs-12">Chưa có xác nhận cư chú</span>

							<?php }?>

						</div>

					</div>

					<div class="form-group mb-2">

						<div class="d-flex align-items-center justify-content-between form-label">

							<label>File CSBH</label>

							<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="select_sp_file(this, event);" to_field="sale_policy_file" class="btn btn-sm btn-outline-default text-none"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

						</div>

						<div class="clearfix"></div>

						<div id="sale_policy_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file'])) {?>

								<a class="download" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file'];?>
"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file'];?>
<a/>

							<?php } else { ?>

								<span class="text-muted fs-12">Chưa có File CSBH</span>

							<?php }?>

						</div>

					</div>

					<div class="form-group mb-2">

						<div class="d-flex align-items-center justify-content-between form-label">

							<label>File PTG</label>

							<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" p_field="price_sheet_file" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onCLick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default text-none"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

						</div>

						<div class="clearfix"></div>

						<div id="price_sheet_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['price_sheet_file'])) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['price_sheet_file'], '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

								<a class="download" data-fancybox="true" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_oI']->value['image']);?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-download',$_smarty_tpl->tpl_vars['_oI']->value['title']);?>
<a/>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php } else { ?>

								<span class="text-muted fs-12">Chưa có File PTG</span>

							<?php }?>

						</div>

					</div>

					<div class="form-group mb-2">

						<div class="d-flex align-items-center justify-content-between form-label">

							<label>Ủy nhiệm chi</label>

							<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" p_field="payment_order" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onClick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default text-none"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

						</div>

						<div class="clearfix"></div>

						<div class="payment_order_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['payment_order'])) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['payment_order'], '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

								<a class="download" data-fancybox="true" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_oI']->value['image']);?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-download',$_smarty_tpl->tpl_vars['_oI']->value['title']);?>
<a/>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php } else { ?>

								<span class="text-muted fs-12">Ủy nhiệm chi</span>

							<?php }?>

						</div>

					</div>

					<div class="form-group mb-2">

						<div class="d-flex align-items-center justify-content-between form-label">

							<label>Hợp đồng ký</label>

							<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" p_field="contract_files" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onClick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default text-none"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

						</div>

						<div class="clearfix"></div>

						<div class="contract_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['contract_files'])) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['contract_files'], '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

								<a class="download" data-fancybox="true" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_oI']->value['image']);?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-download',$_smarty_tpl->tpl_vars['_oI']->value['title']);?>
<a/>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php } else { ?>

								<span class="text-muted fs-12">Hợp đồng ký</span>

							<?php }?>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="modal-footer">

			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>

			<button type="button" class="btn btn-primary flex-fill js__save-billing" onClick="$Core.billing.save_info(this, event)" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" is_ignore_confirmed="0" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
">Cập nhật</button>

		</div>

	</form>

</div>

<?php } else { ?>

<tr>

	<td width="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>35<?php } else { ?>25<?php }?>%" class="text-right">Ngày bán</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="date" placeholder="dd/mm/YYYY" class="form-control text-muted form-control-none" onchange="$Core.billing.autosave_inline_field(this, {p_field:'sale_date', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="sale_date" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_date'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['sale_date'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right">CSBH căn bán</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="date" placeholder="dd/mm/YYYY" class="form-control form-control-none" onchange="$Core.billing.autosave_inline_field(this, {p_field:'sale_policy_date', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="sale_policy_date" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_policy_date'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['sale_policy_date'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Dự kiến ký HĐMB</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="datetime-local" placeholder="dd/mm/YYYY" class="form-control form-control-none" onchange="$Core.billing.autosave_inline_field(this, {p_field:'estimate_date', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="estimate_date" value="<?php if (!empty($_smarty_tpl->tpl_vars['oneBilling']->value['estimate_date'])) {
echo $_smarty_tpl->tpl_vars['oneBilling']->value['estimate_date'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Ngày ký HĐMB</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="date" placeholder="dd/mm/YYYY" class="form-control form-control-none" onchange="$Core.billing.autosave_inline_field(this, {p_field:'contract_date', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="contract_date" value="<?php if (!empty($_smarty_tpl->tpl_vars['oneBilling']->value['contract_date'])) {
echo $_smarty_tpl->tpl_vars['oneBilling']->value['contract_date'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Khách hàng</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" placeholder="Nhập..." class="form-control form-control-none" onchange="$Core.billing.autosave_inline_field(this, {p_field:'customer_name', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="customer_name" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_name'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_name'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Điện thoại</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'customer_phone', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nhập..." class="form-control form-control-none" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="customer_phone" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_phone'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_phone'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Email</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'customer_email', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nhập..." class="form-control form-control-none" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="customer_email" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_email'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_email'];
}?>" />

	</td>

</tr>

<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

<tr>

	<td class="text-right text-nowrap">CMND/CCID<br /><small>(Mặt trước)</small></td>

	<td class="InputCRMHandler" colspan="3">

		<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['ccid_front'])) {?>

			<a class="download" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['ccid_front'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatFileName($_smarty_tpl->tpl_vars['more_information']->value['ccid_front'],15);?>
<a/>

		<?php } else { ?>

			<span class="text-muted fs-12">Chưa có CMND/CCID</span>

		<?php }?>

		<div class="clearfix"></div>

		<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

		<form class="d-none" method="POST" enctype="multipart/form-data">

			<input type="hidden" name="hid" value="upload" />

			<input type="file" class="upload_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
 upload_sp_file" to_field="ccid_front" name="upload_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" />

		</form>

		<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="select_sp_file(this, event);" to_field="ccid_front" class="btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

	</td>

</tr>

<tr>

	<td class="text-right">CMND/CCID<br /><small>(Mặt sau)</small></td>

	<td class="InputCRMHandler" colspan="3">

		<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['ccid_back'])) {?>

			<a class="download" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['ccid_back'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatFileName($_smarty_tpl->tpl_vars['more_information']->value['ccid_back'],15);?>
<a/>

		<?php } else { ?>

			<span class="text-muted fs-12">Chưa có CMND/CCID</span>

		<?php }?>

		<div class="clearfix"></div>

		<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

		<form class="d-none" method="POST" enctype="multipart/form-data">

			<input type="hidden" name="hid" value="upload" />

			<input type="file" class="upload_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
 upload_sp_file" to_field="ccid_back" name="upload_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" />

		</form>

		<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="select_sp_file(this, event);" to_field="ccid_back" class="btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

	</td>

</tr>

<?php } else { ?>

<tr>

	<td class="text-right">CMND/CCID<br /><small>(Mặt trước)</small></td>

	<td class="InputCRMHandler border-end">

		<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['ccid_front'])) {?>

			<a class="download" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['ccid_front'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatFileName($_smarty_tpl->tpl_vars['more_information']->value['ccid_front'],15);?>
<a/>

		<?php } else { ?>

			<span class="text-muted fs-12">Chưa có CMND/CCID</span>

		<?php }?>

		<div class="clearfix"></div>

		<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

		<form class="d-none" method="POST" enctype="multipart/form-data">

			<input type="hidden" name="hid" value="upload" />

			<input type="file" class="upload_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
 upload_sp_file" to_field="ccid_front" name="upload_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" />

		</form>

		<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="select_sp_file(this, event);" to_field="ccid_front" class="btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

	</td>

	<td class="text-right">CMND/CCID<br /><small>(Mặt sau)</small></td>

	<td class="InputCRMHandler">

		<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['ccid_back'])) {?>

			<a class="download" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['ccid_back'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatFileName($_smarty_tpl->tpl_vars['more_information']->value['ccid_back'],15);?>
<a/>

		<?php } else { ?>

			<span class="text-muted fs-12">Chưa có CMND/CCID</span>

		<?php }?>

		<div class="clearfix"></div>

		<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

		<form class="d-none" method="POST" enctype="multipart/form-data">

			<input type="hidden" name="hid" value="upload" />

			<input type="file" class="upload_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
 upload_sp_file" to_field="ccid_back" name="upload_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" />

		</form>

		<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="select_sp_file(this, event);" to_field="ccid_back" class="btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

	</td>

</tr>

<?php }?>

<tr>

	<td class="text-right text-nowrap">File CSBH</td>

	<td class="InputCRMHandler" colspan="3">

		<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file'])) {?>

			<a class="download" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file'];?>
"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file'];?>
<a/>

		<?php } else { ?>

			<span class="text-muted fs-12">Chưa có File CSBH</span>

		<?php }?>

		<div class="clearfix"></div>

		<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

		<form class="d-none" method="POST" enctype="multipart/form-data">

			<input type="hidden" name="hid" value="upload" />

			<input type="file" class="upload_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
 upload_sp_file" to_field="sale_policy_file" name="upload_file" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" />

		</form>

		<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="select_sp_file(this, event);" to_field="sale_policy_file" class="btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-upload','Tải file');?>
</a>

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">File PTG</td>

	<td colspan="3">

		<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['price_sheet_file'])) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['price_sheet_file'], '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

			<a class="download" data-fancybox="true" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_oI']->value['image']);?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-download',$_smarty_tpl->tpl_vars['_oI']->value['title']);?>
<a/>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php } else { ?>

			<span class="text-muted fs-12">Chưa có File PTG</span>

		<?php }?>

		<div class="clearfix"></div>

		<a href="javascript:void(0);" p_field="price_sheet_file" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onClick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-check','Tải file');?>
</a>

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Ủy nhiệm chi</td>

	<td colspan="3">

		<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['payment_order'])) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['payment_order'], '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

			<a class="download" data-fancybox="true" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_oI']->value['image']);?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-download',$_smarty_tpl->tpl_vars['_oI']->value['title']);?>
<a/>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php } else { ?>

			<span class="text-muted fs-12">Ủy nhiệm chi</span>

		<?php }?>

		<div class="clearfix"></div>

		<a href="javascript:void(0);" p_field="payment_order" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onCLick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-check','Tải file');?>
</a>

	</td>

</tr>

<tr>

	<td colspan="4" class="bg-lighter fw-bold text-upper">A. Phần HHMG nhận về</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Nguồn căn</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<div class="selectize-none">

			<select class="iso-selectizeNotSearch w-100" onchange="$Core.billing.autosave_inline_field(this,{p_field:'stock_resource', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nguồn gốc" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="stock_resource" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=ajax&sub=helper&act=get_property&property_type=_AGENCY" name="stock_resource" data-optgroup="false">

				<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['stock_resource']) && $_smarty_tpl->tpl_vars['more_information']->value['stock_resource'] > '0') {?>

				<option value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['stock_resource'];?>
" selected="selected"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['more_information']->value['stock_resource']);?>
</option>

				<?php }?>

			</select>

		</div>

		<!-- <input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'stock_resource', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nhập..." class="form-control form-control-none" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="stock_resource" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['stock_resource'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['stock_resource'];
}?>" /> -->

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Hoa hồng</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'commission', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" class="form-control form-control-none" placeholder="Nhập..." p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="commission" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['commission'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['commission'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Thưởng đại lý</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'agency_bonus', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nhập..." class="form-control form-control-none" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="agency_bonus" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['agency_bonus'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['agency_bonus'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Thưởng Marketing</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'marketing_bonus', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nhập..." class="form-control form-control-none" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="marketing_bonus" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['marketing_bonus'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['marketing_bonus'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Thưởng sale</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'sale_ps_bonus', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nhập..." class="form-control form-control-none" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="sale_ps_bonus" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_ps_bonus'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['sale_ps_bonus'];
}?>" />

	</td>

</tr>

<tr>

	<td colspan="4" class="bg-lighter fw-bold text-upper">B. Phần HHMG phải trả (HH=Hoa hồng)</td>

</tr>

<tr>

	<td class="text-right text-nowrap">HH PKD/Đối tác</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'commission_agency', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nhập..." class="form-control form-control-none" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="commission_agency" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['commission_agency'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['commission_agency'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">HH sale</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'commission_sale', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nhập..." class="form-control form-control-none" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="commission_sale" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['commission_sale'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['commission_sale'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Thưởng sale</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'sale_bonus', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" class="form-control form-control-none" placeholder="Nhập..." p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="sale_bonus" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_bonus'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['sale_bonus'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Hỗ trợ sale(Nếu có)</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'support_sale', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" placeholder="Nhập..." class="form-control form-control-none" p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="support_sale" value="<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['support_sale'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['support_sale'];
}?>" />

	</td>

</tr>

<tr>

	<td class="text-right text-nowrap">Ghi chú</td>

	<td class="InputCRMHandler p-px-1" colspan="3">

		<textarea type="text" onchange="$Core.billing.autosave_inline_field(this,{p_field:'notes', 'p_id':<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
})" class="form-control form-control-none" placeholder="Nhập..." p_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" p_field="notes"><?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['notes'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['notes'];
}?></textarea>

	</td>

</tr>

<?php }
}
}
