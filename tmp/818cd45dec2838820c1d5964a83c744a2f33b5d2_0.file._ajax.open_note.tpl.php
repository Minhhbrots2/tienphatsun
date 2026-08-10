<?php
/* Smarty version 3.1.33, created on 2026-08-07 16:55:45
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.open_note.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75aba1a85e83_29286186',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '818cd45dec2838820c1d5964a83c744a2f33b5d2' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.open_note.tpl',
      1 => 1784300214,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75aba1a85e83_29286186 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-dialog-centered">

	<form action="#" class="modal-content" method="POST" enctype="multipart/form-data" onsubmit="return false;">

		<div class="modal-header">

			<h5 class="modal-title">Lịch hẹn</h5>

		</div>

		<div class="modal-body">

			<div class="form-group mb-2">

				<label class="form-label mb-1">Loại lịch hẹn</label>

				<div class="btn-group d-flex" role="group">

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_activity']->value, '_oActivity');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oActivity']->value) {
?>

					<input type="radio" class="btn-check" name="type_id" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_oActivity']->value['property_id'];?>
" 

						value="<?php echo $_smarty_tpl->tpl_vars['_oActivity']->value['property_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['type_id'] == $_smarty_tpl->tpl_vars['_oActivity']->value['property_id']) {?> checked="checked"<?php }?>>

					<label data-toggle="ripple" class="btn btn-outline-default" for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_oActivity']->value['property_id'];?>
">

						<i class="bx <?php echo $_smarty_tpl->tpl_vars['_oActivity']->value['image'];?>
"></i> 

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?><div class="clearfix"></div><?php }?> <?php echo $_smarty_tpl->tpl_vars['_oActivity']->value['title'];?>


					</label>					

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<input type="radio" class="btn-check" name="type_id" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
_notes" value="0"<?php if ($_smarty_tpl->tpl_vars['_type']->value == '_note') {?> checked="checked"<?php }?>>

					<label data-toggle="ripple" class="btn btn-outline-default" for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
_notes">

						<i class="bx bx-note"></i> 

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?><div class="clearfix"></div><?php }?> Ghi chú

					</label>						

				</div>

			</div>

			<div class="form-group mb-2">

				<label class="form-label mb-1">Thời gian</label>

				<div class="clearfix"></div>

				<div class="form-row">

					<div class="col-8 col-xxl-9">

						<input type="date" class="form-control required" placeholder="dd/mm/yy" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
" name="date_id">

					</div>

					<div class="col-4 col-xxl-3">

						<input type="text" class="form-control max-w-px-200 timepicker" toId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" readonly placeholder="hh:ss" name="time_id" value="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatTime($_smarty_tpl->tpl_vars['oneItem']->value['date_id']);?>
">

					</div>

				</div>

			</div>

			<div class="form-group mb-2">

				<label class="form-label mb-1">Nội dung ghi chú</label>

				<textarea name="intro" rows="4" cols="255" class="form-control autosize required" placeholder="Nội dung"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['intro'];?>
</textarea>

			</div>

			<div class="divider text-start my-2">

				<div class="divider-text text-uppercase">Nhắc nhở</div>

			</div>

			<div class="form-group">

				<div class="d-flex align-items-center gap-2 mb-2">

					<div class="form-check">

						<input type="hidden" name="is_reminder" value="0" />

						<input class="form-check-input cursor-pointer" name="is_reminder" value="1" type="checkbox" id="is_reminder_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" 

							toId="group_reminder_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onchange="$Core.note_calendar.set_reminder(this, event)"<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_reminder'] == 1) {?> checked="checked"<?php }?>> 

						<label class="form-check-label" for="is_reminder_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Nhắc nhở tôi </label>

					</div>

					<select data-type="before_time" name="before_time" disabled toid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="form-control w-px-100 form-select">

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_times']->value, '_item', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_item']->value) {
?>

						<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['reminder_before'] == $_smarty_tpl->tpl_vars['key']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_item']->value;?>
</option>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					</select>

				</div>

				<div class="form-check">

					<input type="hidden" name="is_send_zalo" value="0" />

					<input type="checkbox" disabled class="form-check-input me-2 cursor-pointer" name="is_send_zalo" 

						value="1" id="is_send_zalo_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_send_zalo'] == 1) {?> checked="checked"<?php }?>> 

					<label class="form-check-label" for="is_send_zalo_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Thông báo qua Zalo</label>

				</div>

			</div>	

		</div>

		<div class="modal-footer">

			<input type="hidden" name="date" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
">

			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>

			<button type="button" <?php echo $_smarty_tpl->tpl_vars['props']->value;?>
 onClick="$Core.note_calendar.save_note(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" date="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
" 

				note_id="<?php echo $_smarty_tpl->tpl_vars['note_id']->value;?>
" class="btn btn-primary"><?php if ($_smarty_tpl->tpl_vars['action']->value == '_add') {?>Thêm mới<?php } else { ?>Lưu lại<?php }?></button>

		</div>

	</form>

</div><?php }
}
