<?php
/* Smarty version 3.1.33, created on 2026-08-08 14:56:31
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/quote/_ajax.open.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76e12f8cb470_70298260',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c9510a9101f2c901d41f091cb1be98f85f3eab2a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/quote/_ajax.open.tpl',
      1 => 1784299669,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76e12f8cb470_70298260 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog  modal-md">

	<form class="modal-content">

		<div class="modal-header">				

			<?php if (!empty($_smarty_tpl->tpl_vars['id']->value)) {?>

				<h5 class="modal-title fs-<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>5<?php } else { ?>4<?php }?> text-main">Chỉnh sửa</h5>

			<?php } else { ?>

				<h5 class="modal-title fs-<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>5<?php } else { ?>4<?php }?> text-main">Thêm mới</h5>

			<?php }?>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body pt-0">	

			<div class="form-group mb-2">

				<label class="w-100 form-label mb-1">Nội dung trích dẫn</label>

				<textarea name="content" id="" cols="30" rows="5" class="form-control required"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['content'];?>
</textarea>	

			</div>

			<div class="form-row">

				<div class="col-12 col-md-4 flex-fill">

					<div class="form-group mb-2">

						<label class="w-100  form-label mb-1">Tác giả</label>

						<input type="text" name="author" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['author'];?>
">

					</div>

				</div>

				<div class="col-12 col-md-4 flex-fill">

					<div class="form-group mb-2">

						<label class="w-100  form-label mb-1">Áp dụng cho</label>

						<select name="apply_to" id="" class="form-select form-control" onChange="$Core.quote.loadObject(this,event)" toId="toObject_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" share_ids="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['share_ids'];?>
">

							<option value="all" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['apply_to'] == 'all' || empty($_smarty_tpl->tpl_vars['oneItem']->value['apply_to'])) {?>selected<?php }?>>Tất cả</option>

							<option value="department" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['apply_to'] == 'department') {?>selected<?php }?>>Phòng ban</option>

							<option value="group" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['apply_to'] == 'group') {?>selected<?php }?>>Nhóm</option>

							<option value="profile" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['apply_to'] == 'profile') {?>selected<?php }?>>Nhân viên</option>

						</select>

					</div>

				</div>

				<div class="col-12 col-md-4 flex-fill <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['apply_to'] == 'all') {?>d-none<?php }?>" id="toObject_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">												

					<div class="form-group mb-2">

						<label class="w-100  form-label mb-1">Phòng</label>

						<select name="share_ids[]" class="form-select form-control">



						</select>

					</div>

				</div>

			</div>			

		</div>

		<div class="modal-footer justify-content-end">	

			<div class="d-flex align-items-center">

				<input type="hidden" name="submit" value="Update" />

				<input type="hidden" name="table_id" value="<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" />

				<button type="button" onClick="$Core.quote.save(this, event)" class="btn btn-primary">

					<?php if (!empty($_smarty_tpl->tpl_vars['table_id']->value)) {?>

						Cập nhật

					<?php } else { ?>

						Thêm

					<?php }?>

				</button>

			</div>

		</div>

	</form>

</div><?php }
}
