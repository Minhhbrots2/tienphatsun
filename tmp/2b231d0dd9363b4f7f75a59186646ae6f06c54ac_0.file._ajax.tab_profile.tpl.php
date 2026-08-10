<?php
/* Smarty version 3.1.33, created on 2026-08-07 15:51:24
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.tab_profile.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a759c8c94e000_76524965',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b231d0dd9363b4f7f75a59186646ae6f06c54ac' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.tab_profile.tpl',
      1 => 1786085971,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a759c8c94e000_76524965 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['_type']->value == 'stock_logs') {?>

<div id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="table-container no-shadow overflow-x-auto text-nowrap">

	<table border="0" cellpadding="0" cellspacing="0" class="table mb-0" width="100%">

		<thead><tr>

			<th class="align-center bg-lighter h-px-35">Hành động</th>

			<th class="align-center bg-lighter h-px-35">Nội dung</th>

			<th class="align-center bg-lighter h-px-35">Thời gian</th>

		</tr></thead>

		<?php if (!empty($_smarty_tpl->tpl_vars['lstItem']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

			<tr class="trBilling<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_cancel'] == '1') {?> bg-cancel<?php }?>">

				

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['action'];?>
</td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['contentHTML'];?>
</td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['time'];?>
</td>

			</tr>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php } else { ?>

			<tr><td class="text-center" colspan="3">

				<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/illustration-empty-results.svg" class="w-px-200" />

				<p class="text-muted">Chưa có tra cứu</p>

			</td></tr>

		<?php }?>

	</table>

</div>

<?php } elseif ($_smarty_tpl->tpl_vars['_type']->value == 'report_share') {?>

	<div id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="table-container no-shadow overflow-x-auto text-nowrap">

		<table border="0" cellpadding="0" cellspacing="0" class="table dragable mb-0" width="100%">

			<thead><tr>

				<th class="align-center bg-lighter h-px-35">Khách hàng</th>

				<th class="align-center bg-lighter h-px-35">Điện thoại</th>

				<th class="align-center bg-lighter h-px-35">Địa điểm</th>

				<th class="align-center bg-lighter h-px-35">Số khách</th>

				<th class="align-center bg-lighter h-px-35">Mục tiêu</th>

				<th class="align-center bg-lighter h-px-35">Quan tâm</th>

				<th class="align-center bg-lighter h-px-35">Trạng thái</th>

				<th class="align-center bg-lighter h-px-35">Nội dung</th>

				<th class="align-center bg-lighter h-px-35">Thời gian</th>

			</tr></thead>

			<?php if (!empty($_smarty_tpl->tpl_vars['lstItem']->value)) {?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

					<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>

					<tr class="trBilling<?php if ($_smarty_tpl->tpl_vars['_oBilling']->value['is_cancel'] == '1') {?> bg-cancel<?php }?>">

						<td class="text-left">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_id']) && ($_smarty_tpl->tpl_vars['_oItem']->value['user_id'] == $_smarty_tpl->tpl_vars['profile_id']->value || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV())) {?>

								<span class="lh-xs"><?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_name'])) {
echo $_smarty_tpl->tpl_vars['more_information']->value['customer_name'];
} else { ?>--<?php }?> 

								<a class="badge bg-label-warning text-nowrap fs-10" href="/crm/<?php echo $_smarty_tpl->tpl_vars['more_information']->value['customer_id'];?>
" target="_blank" >CRM <i class='bx bx-link-external fs-10'></i></a></span>

							<?php } else { ?>

								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_name'])) {?>

									<span class="lh-xs"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['customer_name'];?>
</span>

								<?php } else { ?>

									<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>

								<?php }?>

							<?php }?>

						</td>

						<td class="text-left">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_id']) && ($_smarty_tpl->tpl_vars['_oItem']->value['user_id'] == $_smarty_tpl->tpl_vars['profile_id']->value || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV())) {?>

								<span class="lh-xs "><?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_phone'])) {?>

									<?php echo $_smarty_tpl->tpl_vars['clsShare']->value->maskPhone($_smarty_tpl->tpl_vars['more_information']->value['customer_phone']);
} else { ?>--<?php }?> <a class="badge bg-label-warning text-nowrap fs-10" href="/crm/<?php echo $_smarty_tpl->tpl_vars['more_information']->value['customer_id'];?>
" target="_blank">CRM <i class='bx bx-link-external fs-10'></i></a>

								</span>

							<?php } else { ?>

								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['customer_phone'])) {?>

									<span class="lh-xs"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['customer_phone'];?>
</span>

								<?php } else { ?>

									<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>

								<?php }?>

							<?php }?>

						</td>

						<td class="text-left">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['location'])) {?>

								<span class="lh-xs"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['location'];?>
</span>

							<?php } else { ?>

								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>

							<?php }?>

						</td>

						<td class="text-left">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['guest_count'])) {?>

								<span class="lh-xs"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['guest_count'];?>
 khách</span>

							<?php } else { ?>

								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>

							<?php }?>

						</td>

						<td class="text-left">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['target_name'])) {?>

								<span class="lh-xs"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['target_name'];?>
 khách</span>

							<?php } else { ?>

								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>

							<?php }?>

						</td>

						<td class="text-left">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['interest_name'])) {?>

								<span class="lh-xs"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['interest_name'];?>
</span>

							<?php } else { ?>

								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>

							<?php }?>

						</td>

						<td class="text-left">

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['status_name'])) {?>

								<span class="lh-xs"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['status_name'];?>
 khách</span>

							<?php } else { ?>

								<span class="lh-xs text-muted fs-12 text-decoration-underline">Chưa cập nhật</span>

							<?php }?>

						</td>

						<td class="text-left">

							<?php echo $_smarty_tpl->tpl_vars['more_information']->value['content'];?>


						</td>

						<td class="text-left">

							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToTextFormat($_smarty_tpl->tpl_vars['_oItem']->value['reg_date'],"H:i • d/m/Y");?>


						</td>

					</tr>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php } else { ?>

				<tr><td class="text-center" colspan="9">

					<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/illustration-empty-results.svg" class="w-px-200" />

					<p class="text-muted">Chưa có hoạt động</p>

				</td></tr>

			<?php }?>

		</table>

	</div>

<?php }?>

<div id="pager_<?php echo $_smarty_tpl->tpl_vars['_type']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"></div><?php }
}
