<?php
/* Smarty version 3.1.33, created on 2026-07-30 18:00:05
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/_ajax.total_department.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6b2eb575c083_48422758',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0e2fc2413b351e0607274d9c6785969e21475d28' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/_ajax.total_department.tpl',
      1 => 1784299680,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6b2eb575c083_48422758 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
if ($_smarty_tpl->tpl_vars['type']->value == "_OPEN") {?>

<div class="modal-dialog modal-dialog-centered">

	<form method="POST" class="modal-content">

		<div class="modal-header">

			<h5 class="modal-title">Thống kê phòng ban đăng ký phòng họp</h5>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<div class="d-flex justify-content-end w-100 mb-2">

				<div class="input-group d-flex <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>w-px-250<?php }?>" role="group" aria-label="Sắp xếp">

					<select name="month" class="form-control form-select" style="width:100px" onChange="$Core.calendar.loadTotal_calendar(this,event)" data-type="_SEARCH"> 

						<option value="">Tháng</option>

						<?php $_smarty_tpl->_assignInScope('curent_month', smarty_modifier_date_format(time(),"m"));?>

						<?php
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if (true) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= 12; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

							<option value="<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
">Tháng <?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
</option>

						<?php
}
}
?>

					</select>



						<?php $_smarty_tpl->_assignInScope('curent_year', smarty_modifier_date_format(time(),"Y"));?>

					<select name="year"  class="form-control form-select" style="width:80px" onChange="$Core.calendar.loadTotal_calendar(this,event)" data-type="_SEARCH">

						<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['curent_year']->value+1) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_start = min(2024, $__section_i_1_loop);
$__section_i_1_total = min(($__section_i_1_loop - $__section_i_1_start), $__section_i_1_loop);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = $__section_i_1_start; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_1_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

							<option value="<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null);?>
"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null);?>
</option>

						<?php
}
}
?>

					</select>

				</div>

			</div>

			<div class="overflow-auto" >

				<table class="table mb-0 text-center border">

					<thead>

						<tr>

							<th class="bg-lighter text-center border-1" width="60">STT</th>

							<th class="bg-lighter text-left border-1">Phòng ban</th>

							<th class="bg-lighter text-center border-1" width="100">Lượt đăng ký</th>

						</tr>

					</thead>

					<tbody class="lst_department">	

						<?php if (!empty($_smarty_tpl->tpl_vars['arr_data']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_data']->value, '_oItem', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

								<tr>

									<td class="text-nowrap border-1"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

									<td class="text-left border-1"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['department_name'];?>
</td>

									<td class="text-nowrap border-1 fw-bold <?php if (empty($_smarty_tpl->tpl_vars['_oItem']->value['total'])) {?>text-main<?php } else { ?>text-success<?php }?>"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total'];?>
</td>

								</tr>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<tr>

								<td class="text-nowrap border-1 bg-lighter" colspan="2">Tổng</td>

								<td class="text-nowrap border-1"><?php echo $_smarty_tpl->tpl_vars['total']->value;?>
</td>

							</tr>

						<?php } else { ?>

							<tr>

								<td class="text-center border-1" colspan="3">

									<div class="">

										<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg" />

										<p>Danh sách trống</p>

									</div>

								</td>

							</tr>

						<?php }?>

					</tbody>

				</table>

			</div>

		</div>

	</form>

</div>

<?php } else { ?>

	<?php if (!empty($_smarty_tpl->tpl_vars['arr_data']->value)) {?>

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_data']->value, '_oItem', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

			<tr>

				<td class="text-nowrap border-1"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

				<td class="text-left border-1"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['department_name'];?>
</td>

				<td class="text-nowrap border-1 fw-bold <?php if (empty($_smarty_tpl->tpl_vars['_oItem']->value['total'])) {?>text-main<?php } else { ?>text-success<?php }?>"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total'];?>
</td>

			</tr>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<tr>

			<td class="text-nowrap bg-lighter border-1" colspan="2">Tổng</td>

			<td class="text-nowrap border-1"><?php echo $_smarty_tpl->tpl_vars['total']->value;?>
</td>

		</tr>

	<?php } else { ?>

		<tr>

			<td class="text-center border-1" colspan="3">

				<div class="">

					<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg" />

					<p>Danh sách trống</p>

				</div>

			</td>

		</tr>

	<?php }?>

<?php }?>

<?php }
}
