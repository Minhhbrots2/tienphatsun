<?php
/* Smarty version 3.1.33, created on 2026-08-07 17:38:13
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.share.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75b5958e0728_49295380',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '99a76bb127bb10c5c80192ff79a10f3b06e05e41' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.share.tpl',
      1 => 1784299673,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75b5958e0728_49295380 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['lstDepartment']->value)) {?>

	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstDepartment']->value, '_oItem', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

		<div class="table-container no-shadow overflow-x-auto text-nowrap mb-2">		

			<table class="table table-iloocal table-computer table-bordered" width="100%" cellpadding="0" cellspacing="0">

				<thead>

					<?php if (empty($_smarty_tpl->tpl_vars['_oItem']->value['is_not_area'])) {?>

						<tr><th class="align-center text-center bg-lighter fs-16 text-main fw-bold" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>6<?php } else { ?>4<?php }?>"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</th></tr>

					<?php } else { ?>

						<tr><th class="align-center text-center bg-lighter fs-16 text-main fw-bold" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>6<?php } else { ?>4<?php }?>">PKD <?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</th></tr>

					<?php }?>

					<tr>

						<th width="30px" class="align-center text-center bg-lighter">STT</th>

						<th class="align-center bg-lighter" width="30%">Họ và tên</th>

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

						<th width="15%" class="align-center text-center bg-lighter">Mã nhóm</th>

						<th class="align-center bg-lighter" width="20%">Vị trí</th>

						<?php }?>

						<th width="68px" class="align-center text-center bg-lighter">Số ảnh</th>

						<th class="align-center text-right bg-lighter" width="20%">Số tiền</th>

					</tr>

				</thead>

				<?php $_smarty_tpl->_assignInScope('no', 0);?>

				<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['listStaff'])) {?>

					<?php $_smarty_tpl->_assignInScope('total_staffs', count($_smarty_tpl->tpl_vars['_oItem']->value['listStaff']));?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oItem']->value['listStaff'], '_oStaff', false, NULL, 'k', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
?>

					<?php $_smarty_tpl->_assignInScope('no', $_smarty_tpl->tpl_vars['no']->value+1);?>

					<tr>

						<td width="30px" class="text-center"><?php echo $_smarty_tpl->tpl_vars['no']->value;?>
</td>

						<td class="text-left">

							<span<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkHeadSale($_smarty_tpl->tpl_vars['_oStaff']->value['role_id'])) {?> class="text-danger font-bold"<?php }?>><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'],$_smarty_tpl->tpl_vars['_oStaff']->value);?>
</span>

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

							<br /><span class="text-muted fs-12"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['title'];?>
</span>

							<?php }?>

						</td>

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

						<td class=" text-center"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>

						<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['role'];?>
</td>

						<?php }?>

						<td class="text-center">

						<?php if (empty($_smarty_tpl->tpl_vars['_oStaff']->value['total_share'])) {?>

							<span class="text-main fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['total_share'];?>
</span>/<span class="text-info fw-bold">8</span>

						<?php } elseif ($_smarty_tpl->tpl_vars['_oStaff']->value['total_share'] >= 8) {?>

							<span class="text-success fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['total_share'];?>
</span>/<span class="text-info fw-bold">8</span>

						<?php } else { ?>

							<span class="text-warning fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['total_share'];?>
</span>/<span class="text-info fw-bold">8</span>

						<?php }?>

						</td>

						<td class="text-right">							

							<?php if ($_smarty_tpl->tpl_vars['_oStaff']->value['total_fines'] > '0') {?>

								-<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['_oStaff']->value['total_fines']);?>
đ

							<?php } else { ?>

								0đ

							<?php }?>

						</td>

					</tr>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup("BUSINESS_AREA") || (count($_smarty_tpl->tpl_vars['lstDepartment']->value) == 1 && $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup("SALE_DIRECTOR")) || !empty($_smarty_tpl->tpl_vars['_oItem']->value['is_not_area'])) {?>

						<tr>

							<td colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>2<?php } else { ?>4<?php }?>" class="text-center bg-lighter <?php if ((count($_smarty_tpl->tpl_vars['lstDepartment']->value) == 1 && $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR')) || !empty($_smarty_tpl->tpl_vars['_oItem']->value['is_not_area'])) {?>fs-16 text-main<?php }?>">

								<strong class="text-upper">Tổng phòng</strong>

							</td>

							<td class="bg-lighter text-center <?php if ((count($_smarty_tpl->tpl_vars['lstDepartment']->value) == 1 && $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR')) || !empty($_smarty_tpl->tpl_vars['_oItem']->value['is_not_area'])) {?>fs-16 text-main<?php }?>">

								<span class="fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_shares'];?>
</span>

							</td>

							<td class="bg-lighter text-right fw-bold <?php if ((count($_smarty_tpl->tpl_vars['lstDepartment']->value) == 1 && $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR')) || !empty($_smarty_tpl->tpl_vars['_oItem']->value['is_not_area'])) {?>fs-16 text-main<?php }?>">

								<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['total_price'] > '0') {?>

									-<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['_oItem']->value['total_price']);?>
đ

								<?php } else { ?>

									0đ

								<?php }?>						

							</td>

						</tr>

					<?php }?>					

				<?php }?>

				<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['department_child'])) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oItem']->value['department_child'], '_oItemChild', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItemChild']->value) {
?>

						<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup("BUSINESS_AREA") || count($_smarty_tpl->tpl_vars['lstDepartment']->value) == 1) {?>

							<tr><th class="align-center text-center bg-lighter fs-14" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>6<?php } else { ?>4<?php }?>">PKD <?php echo $_smarty_tpl->tpl_vars['_oItemChild']->value['title'];?>
</th></tr>

						<?php }?>

						<?php $_smarty_tpl->_assignInScope('total_staffs', count($_smarty_tpl->tpl_vars['_oItemChild']->value['listStaff']));?>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oItemChild']->value['listStaff'], '_oStaffChild', false, NULL, 'k', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaffChild']->value) {
?>

							<?php $_smarty_tpl->_assignInScope('no', $_smarty_tpl->tpl_vars['no']->value+1);?>

							<tr>

								<td width="30px" class="text-center"><?php echo $_smarty_tpl->tpl_vars['no']->value;?>
</td>

								<td class="text-left">

									<span<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkHeadSale($_smarty_tpl->tpl_vars['_oStaffChild']->value['role_id'])) {?> class="text-danger font-bold"<?php }?>><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['_oStaffChild']->value['profile_id'],$_smarty_tpl->tpl_vars['_oStaffChild']->value);?>
</span>

									<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

									<br /><span class="text-muted fs-12"><?php echo $_smarty_tpl->tpl_vars['_oStaffChild']->value['title'];?>
</span>

									<?php }?>

								</td>

								<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

								<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
-<?php echo $_smarty_tpl->tpl_vars['_oItemChild']->value['title'];?>
</td>

								<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oStaffChild']->value['role'];?>
</td>

								<?php }?>

								<td class="text-center">

								<?php if (empty($_smarty_tpl->tpl_vars['_oStaffChild']->value['total_share'])) {?>

									<span class="text-main fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oStaffChild']->value['total_share'];?>
</span>/<span class="text-info fw-bold">8</span>

								<?php } elseif ($_smarty_tpl->tpl_vars['_oStaffChild']->value['total_share'] >= 8) {?>

									<span class="text-success fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oStaffChild']->value['total_share'];?>
</span>/<span class="text-info fw-bold">8</span>

								<?php } else { ?>

									<span class="text-warning fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oStaffChild']->value['total_share'];?>
</span>/<span class="text-info fw-bold">8</span>

								<?php }?>

								</td>

								<td class="text-right fw-bold">							

									<?php if ($_smarty_tpl->tpl_vars['_oStaffChild']->value['total_fines'] > '0') {?>

										-<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['_oStaffChild']->value['total_fines']);?>
đ

									<?php } else { ?>

										0đ

									<?php }?>

								</td>

							</tr>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup("BUSINESS_AREA") || count($_smarty_tpl->tpl_vars['lstDepartment']->value) == 1) {?>

							<tr>

								<td colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>2<?php } else { ?>4<?php }?>" class="text-center bg-lighter">

									<strong class="text-upper">Tổng phòng</strong>

								</td>

								<td class="bg-lighter text-center">

									<span class="fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oItemChild']->value['total_shares'];?>
</span>

								</td>

								<td class="bg-lighter text-right">

									<?php if ($_smarty_tpl->tpl_vars['_oItemChild']->value['total_price'] > '0') {?>

										-<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['_oItemChild']->value['total_price']);?>
đ

									<?php } else { ?>

										0đ

									<?php }?>						

								</td>

							</tr>

						<?php }?>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php }?>

				<?php if (count($_smarty_tpl->tpl_vars['lstDepartment']->value) > 1 || (!$_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR') && empty($_smarty_tpl->tpl_vars['_oItem']->value['is_not_area']))) {?>

				<tr>

					<td colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>2<?php } else { ?>4<?php }?>" class="text-center bg-grayter fs-16 text-main">

						<strong class="text-upper">Tổng cộng</strong>

					</td>

					<td class="bg-grayter text-center fs-16 text-main">

						<span class="fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_share_area'];?>
</span>

					</td>

					<td class="bg-grayter text-right fs-16 text-main fw-bold">

						<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['total_price_area'] > '0') {?>

							-<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['_oItem']->value['total_price_area']);?>
đ

						<?php } else { ?>

							0đ

						<?php }?>						

					</td>

				</tr>

				<?php }?>

				</table>

			</div>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	<?php }?>



<style type="text/css">

	@media screen and (max-width:575px){

		.table th,

		.table td{ padding:0.525rem 0.325rem; }

	}

</style>

<?php }
}
