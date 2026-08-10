<?php
/* Smarty version 3.1.33, created on 2026-08-08 14:35:08
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.open_day.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76dc2cba7fe0_42320171',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c518ae0b3a057545d07dbcf8814d97d8f9ea863e' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.open_day.tpl',
      1 => 1784300214,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76dc2cba7fe0_42320171 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

	<form class="modal-content" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>style="border-radius:0"<?php }?> >

		<div class="modal-header">

			<div class="d-flex flex-column">

				<h5 class="modal-title text-main fw-bold">Ngày <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToTextFormat($_smarty_tpl->tpl_vars['time']->value,"d/m/Y");?>
 

					<span class="text-dark fs-14 fw-normal">(<?php echo $_smarty_tpl->tpl_vars['lunar_text']->value;?>
 âm lịch)</span>

				</h5>

				<div class="txt_canchi text-main fst-italic"></div>

			</div>

			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body pt-0 text-dark">	

			<input type="hidden" name="total" value="<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
">

			<input type="hidden" name="total_birthday" value="<?php echo $_smarty_tpl->tpl_vars['total_birthday']->value;?>
">

			<div class="d-flex gap-1 align-items-center justify-content-center position-relative px-4 day_special py-2 mx-auto fs-16 text-warning d-none" style="width: fit-content">

				<i class="bx bxs-quote-alt-left"></i> 

				<i class="text-center lh-lg xs:text-left xs:text-fs-16 txt_special"></i>

				<i class="bx bxs-quote-alt-right"></i>

			</div>

			
			<div class="py-2 border-top">

				<strong class="fs-16">Giờ hoàng đạo</strong>

				<div class="gio_hoang_dao d-flex flex-wrap gap-1 mt-2"></div>

			</div>

			<?php if (!empty($_smarty_tpl->tpl_vars['list_billings']->value)) {?>

			<div class="py-2 border-top">

				<strong class="fs-16">Lịch ký</strong>

				<div class="contract_calendar mt-2">

					<div class="table-container no-shadow overflow-x-auto">

						<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">

							<thead><tr>

								<th class="align-center" style="background: #FFF !important;">Mã căn</th>

								<th class="align-center">Admin</th>

								<th class="align-center text-right">T.Trạng</th>

								<th class="align-center text-right">Ghi chú</th>

							</tr></thead>

							<tbody class="table-border-bottom-0 billing_calendar" >			

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_billings']->value, '_oBilling');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBilling']->value) {
?>

								<?php $_smarty_tpl->_assignInScope('_oAdmin', $_smarty_tpl->tpl_vars['_oBilling']->value['admin']);?>

								<?php $_smarty_tpl->_assignInScope('_oStaff', $_smarty_tpl->tpl_vars['_oBilling']->value['staff']);?>

								<tr class="trBilling">

									<td class="text-left text-nowrap">

										<div class="d-flex flex-column gap-1">

											<div class="d-flex align-items-center justify-content-between">

												<a href="javascript:void(0)" onclick="view_billing(this,event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
"><strong><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['stock_code'];?>
</strong> <sup>[<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['text_type'];?>
]</sup></a>

												<?php if (!empty($_smarty_tpl->tpl_vars['is_edit']->value)) {?>

												<a class="text-link" title="chỉnh sửa thông tin" onclick="$Core.billing.add_info(this,event)" 

													billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
"><i class="bx bx-edit fs-14"></i></a>

												<?php }?>

											</div>

										</div>

									</td>

									<td class="text-left">

										<div class="d-flex align-items-center gap-1 justify-content-start">

											<img class="rounded-pill avatar avatar-xs" src="<?php if (!empty($_smarty_tpl->tpl_vars['_oAdmin']->value)) {
echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oAdmin']->value['profile_id'],$_smarty_tpl->tpl_vars['_oAdmin']->value);
}?>" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'">

											<div class="d-flex flex-column">

												<h4 class="text-fs-12 mb-0 fw-bold text-nowrap"><?php echo $_smarty_tpl->tpl_vars['_oAdmin']->value['full_name'];?>
</h4>

												<div class="d-flex align-items-center gap-1 text-fs-11 text-muted text-nowrap">

													<i class="material-icons-outlined fs-13 no-translate">more_time</i>

													<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['time'];?>


												</div>

											</div>

										</div>

									</td>

									<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['status'];?>
</td>

									<td class="text-center">

										<?php if (!empty($_smarty_tpl->tpl_vars['_oBilling']->value['is_note'])) {?>

										<a href="javascript:void(0)" class="btn btn-icon btn-outline-default btn-sm" data-toggle="webui-popover" data-trigger="click" data-type="async" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
" data-placement="left-bottom" data-closeable="false" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS']->value;?>
/index.php?mod=home&sub=calendar&act=load_rescheduling_reason&billing_id=<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
">

											<i class='bx bx-notepad'></i>

										</a>

										<?php } else { ?>

										--

										<?php }?>

									</td>

								</tr>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</tbody>

						</table>

					</div>

				</div>

			</div>

			<?php }?>

			<?php if (!empty($_smarty_tpl->tpl_vars['lstNoteCRM']->value)) {?>

			<div class="py-2 border-top">

				<h5 class="fs-16 fw-bold mb-2">Cuộc hẹn khách hàng</h5>

				<div class="note_customer parent_more mt-2" data-max="3">

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstNoteCRM']->value, '_oItem', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

					<div class="d-flex flex-column justify-content-between align-items-start p-2 rounded-2 position-relative item item_note bg-lighter mb-1"  >

						<div class="note-item"><strong><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['time'];?>
</strong> — <strong><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['followup_type_name'];?>
</strong> với khách hàng <strong><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['name'];?>
</strong></div>

						<div class="d-flex gap-1 align-items-center">

							<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['intro'];?>


						</div>						

					</div>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				</div>

			</div>

			<?php }?>

			<?php if (!empty($_smarty_tpl->tpl_vars['lstCustomer']->value)) {?>

			<div class="py-2 border-top">

				<strong class="fs-16">Sinh nhật khách hàng</strong>

				<div class="birthday_customer mt-2">

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstCustomer']->value, '_oItem');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

					<div class="d-flex align-items-start gap-1 bg-lighter mb-1 p-2 rounded-2"  >

						<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icons/icon_birthday.png" alt="" width="20">

						<div class="d-flex gap-1 align-items-center">Khách hàng <strong><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['name'];?>
</strong></div>						

					</div>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				</div>

			</div>

			<?php }?>

			<?php if (!empty($_smarty_tpl->tpl_vars['lstProfileBirthday']->value)) {?>

			<div class="py-2 border-top">

				<strong class="fs-16">Sinh nhật thành viên <?php echo @constant('BRAND_NAME');?>
</strong>

				<div class="birthday_FH parent_more form-row row-cols-1 row-cols-md-2 mt-2" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>data-max="3"<?php } else { ?>data-max="6"<?php }?>>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstProfileBirthday']->value, '_oItem');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

						<div class="col item">

							<div class="d-inline-flex align-items-center bg-lighter mb-1 p-2 rounded-2 w-100">

								<img class="avatar avatar-sm mr-2 rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oItem']->value['profile_id'],$_smarty_tpl->tpl_vars['_oItem']->value);?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'" />

								<div class="line-height-0">

									<p class="mb-n1 text-nowrap fw-bold mb-1"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['_oItem']->value['profile_id'],$_smarty_tpl->tpl_vars['_oItem']->value);?>
</p>

									<small class="text-muted"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['depart_name'];?>
</small>

								</div>

							</div>

						</div>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				</div>

			</div>

			<?php }?>

			<?php if (!empty($_smarty_tpl->tpl_vars['list_events']->value)) {?>

			<div class="py-2 border-top">

				<strong class="fs-16">Sự kiện + Đào tạo</strong>

				<div class="birthday_FH parent_more mt-2" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>data-max="3"<?php } else { ?>data-max="6"<?php }?>>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_events']->value, '_oItem');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

						<div class="d-flex align-items-start gap-1 bg-lighter mb-1 p-2 rounded-2"  >

							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icons/icon_event.png" alt="" width="20">

							<div class="d-flex flex-column">				

								<div class="fw-semibold"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];
echo $_smarty_tpl->tpl_vars['_oItem']->value['status'];?>
</div>

								<span class="time mb-1 text-muted fs-11">

									<?php echo $_smarty_tpl->tpl_vars['clsCourse']->value->getTimeStartCourse($_smarty_tpl->tpl_vars['_oItem']->value['start_date']);?>
 

								</span>

							</div>						

						</div>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				</div>

			</div>

			<?php }?>

			<div class="py-2 border-top">

				<div class="d-flex justify-content-between align-items-center">

					<strong class="fs-16">Ghi chú</strong>

					<button class="btn btn-primary btn-sm" type="button" onClick="$Core.note_calendar.open_note(this,event)" note_id="0"  date="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
" _type="_note" toId="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" >Thêm ghi chú</button>

				</div>

				<div class="lst_note pb-2" id="lst_note_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">					

					<div class="text-muted">Chưa có ghi chú</div>

				</div>

			</div>

		</div>

	</form>

</div><?php }
}
