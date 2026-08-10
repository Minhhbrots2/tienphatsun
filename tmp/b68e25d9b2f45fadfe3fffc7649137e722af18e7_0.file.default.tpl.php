<?php
/* Smarty version 3.1.33, created on 2026-07-30 13:42:34
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/stock/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6af25aefa717_43182471',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b68e25d9b2f45fadfe3fffc7649137e722af18e7' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/stock/default.tpl',
      1 => 1784299676,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6af25aefa717_43182471 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="content-wrapper">

	<div class="container-md flex-grow-1 pt-2 container-p-y">

		<div class="eznyDbxTuI mt-10">

		<div class="duGrSSZjHd d-flex flex-column align-items-center justify-content-center my-2">

			<h3 class="mb-1 fs-4 text-main text-upper">Độc quyền <?php echo @constant('BRAND_NAME');?>
</h3>

			<p class="text-muted"><i class='bx bx-time'></i> cập nhật: <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText(time(),true);?>
</p>

		</div>	

		<div class="bg-lighter p-3 rounded-2">

			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('exclusive_search');?>


		</div>

		<div class="holder_stock">

			<div class="table-freeze no-freeze overflow-x-auto text-nowrap mb-2">

				<table cellpadding="0" cellspacing="0" class="table mb-0 dragable table-stock" width="100%">

					<thead><tr>

						<th class="pheader text-left text-upper" colspan="20">

							<div class="d-flex align-items-center justify-content-between">

								<strong class="fs-6">Quỹ căn</strong>

							</div>

						</th>

					</tr>

					<tr class="nohover">

						<th class="pcell align-center text-center">Tòa</th>

						<th class="pcell align-center text-left">Mã căn</th>

						<th class="pcell align-center text-center">Vẽ View</th>

						<th class="pcell align-center text-left">Phiếu TG</th>

						<th class="pcell align-center text-center">Loại căn</th>

						<th class="pcell align-center text-center">Hướng</th>

						<th class="pcell align-center text-center">View</th>

						<th class="pcell align-center text-center">DT_TT</th>

						<th class="pcell align-center text-center">Giá VAT</th>

						<th class="pcell align-center text-center">Loại hình</th>

						<th class="pcell align-center text-center">Thưởng sale</th>

						<th class="pcell align-center text-center">CSBH ngày</th>

					</tr></thead>

					<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 20);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

					<tr>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

					</tr>

					<?php
}
}
?>

				</table>

			</div>

			<div class="table-freeze no-freeze overflow-x-auto text-nowrap mb-2">

				<table cellpadding="0" cellspacing="0" class="table mb-0 dragable table-stock" width="100%">

					<thead><tr>

						<th class="pheader text-left text-upper" colspan="20">

							<div class="d-flex align-items-center justify-content-between">

								<strong class="fs-6">Quỹ căn</strong>

							</div>

						</th>

					</tr>

					<tr class="nohover">

						<th class="pcell align-center text-center">Tòa</th>

						<th class="pcell align-center text-left">Mã căn</th>

						<th class="pcell align-center text-center">Vẽ View</th>

						<th class="pcell align-center text-left">Phiếu TG</th>

						<th class="pcell align-center text-center">Loại căn</th>

						<th class="pcell align-center text-center">Hướng</th>

						<th class="pcell align-center text-center">View</th>

						<th class="pcell align-center text-center">DT_TT</th>

						<th class="pcell align-center text-center">Giá VAT</th>

						<th class="pcell align-center text-center">Loại hình</th>

						<th class="pcell align-center text-center">Thưởng sale</th>

						<th class="pcell align-center text-center">CSBH ngày</th>

					</tr></thead>

					<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = min(($__section_i_1_loop - 0), 20);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

					<tr>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

						<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

					</tr>

					<?php
}
}
?>

				</table>

			</div>

		</div>

	</div>

</div><?php }
}
