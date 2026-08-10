<?php
/* Smarty version 3.1.33, created on 2026-08-05 16:14:34
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/report/report_login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72fefae47121_46412157',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '64737530b70977ff756ea3cf39228cf9e54c6b02' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/report/report_login.tpl',
      1 => 1784300232,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72fefae47121_46412157 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="container-xxl flex-grow-1 container-p-y pt-2">	

	<div class="form-row my-2">

		<div class="col-12 col-md-8 mx-auto">

			<form method="POST">

				<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">

					<div class="title mb-lg-0">

						<h4 class="fw-bold mb-1">Báo cáo tần suất truy cập</span></h4>

						<span class="text-muted fs-12">Danh sách truy cập nhân viên</span>

					</div>

					<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

					<div class="search d-flex flex-wrap align-items-center gap-1">

						<select onchange="$Core.report.do_search(this,event)" data-field="department_id" 

							class="form-control js__search-department-field search_field w-px-100 form-select">

							<option>Tất cả</option>

							<?php if (!empty($_smarty_tpl->tpl_vars['list_sale_departments']->value)) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_sale_departments']->value, '_oG');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oG']->value) {
?>

								<option value="<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oG']->value['title'];?>
</option>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php }?>

						</select>

						<div class="input-group w-px-300">

							<select onchange="$Core.report.do_search(this,event)" data-field="year" 

								class="form-control js__search-year-field search_field form-select">

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_years']->value, '_year');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_year']->value) {
?>

								<option<?php if ($_smarty_tpl->tpl_vars['Current_Year']->value == $_smarty_tpl->tpl_vars['_year']->value) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
">Năm <?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
</option>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</select>

							<select onchange="$Core.report.do_search(this,event)" data-field="month" 

								class="form-control search_field js__search-month-field form-select">

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_months']->value, '_month');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_month']->value) {
?>

								<option<?php if ($_smarty_tpl->tpl_vars['Current_Month']->value == $_smarty_tpl->tpl_vars['_month']->value) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
">Tháng <?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
</option>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</select>

							<select onchange="$Core.report.do_search(this,event)" data-field="date_type" 

								class="form-control search_field js__search-date_type-field form-select">

								<option value="today">Hôm nay</option>

								<option value="yesterday">Hôm qua</option>

								<option value="7days">7 ngày qua</option>

								<option value="15days">15 ngày qua</option>

								<option value="30days">30 ngày qua</option>

							</select>

						</div>

						<div class="input-group w-auto">

							<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-start_date-field 

							js__search-date-field search_field w-px-125" data-field="start_date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" />

							<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-end_date-field 

							js__search-date-field search_field w-px-125" data-field="end_date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" max="<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d');?>
" />

						</div>

					</div>

					<?php }?>

				</div>

				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

				<div class="search d-flex flex-wrap align-items-center mt-2">

					<div class="input-group w-100 mb-1">

						<select onchange="$Core.report.do_search(this,event)" data-field="year" 

							class="form-control js__search-year-field search_field form-select">

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_years']->value, '_year');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_year']->value) {
?>

							<option<?php if ($_smarty_tpl->tpl_vars['Current_Year']->value == $_smarty_tpl->tpl_vars['_year']->value) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
">Năm <?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
</option>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</select>

						<select onchange="$Core.report.do_search(this,event)" data-field="month" 

							class="form-control search_field js__search-month-field form-select">

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_months']->value, '_month');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_month']->value) {
?>

							<option<?php if ($_smarty_tpl->tpl_vars['Current_Month']->value == $_smarty_tpl->tpl_vars['_month']->value) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
">Tháng <?php echo $_smarty_tpl->tpl_vars['_month']->value;?>
</option>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</select>

						<select onchange="$Core.report.do_search(this,event)" data-field="date_type" 

							class="form-control search_field js__search-date_type-field form-select">

							<option value="yesterday">Hôm qua</option>

							<option value="7days">7 ngày qua</option>

							<option value="15days">15 ngày qua</option>

							<option value="30days">30 ngày qua</option>

						</select>

					</div>

					<div class="d-flex align-items-center gap-1 mb-2 w-100">

						<select onchange="$Core.report.do_search(this,event)" data-field="department_id" 

							class="form-control js__search-department-field flex-fill search_field form-select">

							<option>Tất cả</option>

							<?php if (!empty($_smarty_tpl->tpl_vars['list_sale_departments']->value)) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_sale_departments']->value, '_oG');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oG']->value) {
?>

								<option value="<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oG']->value['title'];?>
</option>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php }?>

						</select>

						<div class="input-group flex-fill flex-nowrap">

							<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-start_date-field 

							js__search-date-field search_field w-px-125" data-field="start_date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" />

							<input type="date" onchange="$Core.report.do_search(this,event)" class="form-control js__search-end_date-field 

							js__search-date-field search_field w-px-125" data-field="end_date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" max="this.max=new Date().toISOString().split('T')[0]"/>

						</div>

					</div>

				</div>

				<?php }?>

			</form>

			<div class="card mb-2">

				<div class="card-body">

					<div class="table-container no-shadow overflow-x-auto text-nowrap">

						<table cellpadding="0" cellspacing="0" class="table table-bordered" width="100%">

							<thead><tr>

								<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != "phone") {?>

								<th width="6px" class="align-center text-left">STT</th>

								<?php }?>

								<th class="align-center bg-lighter h-px-35 text-left">Họ và tên</th>

								<th class="align-center bg-lighter h-px-35 text-center" width="150px">Phòng ban</th>

								<th class="align-center bg-lighter h-px-35 text-center" width="120px">Truy cập</th>

								<th class="align-center bg-lighter h-px-35 text-center" width="120px">Tra cứu</th>

							</tr></thead>

							<tbody class="holder_reports_login">

								<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 25);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_0_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

								<tr>

									<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != "phone") {?>

									<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
</td>

									<?php }?>

									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>

								</tr>

								<?php
}
}
?>

							</tbody>

						</table>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>



<style type="text/css">

	.table-responsive td{

		text-align:left;

	}

	.multiselect-native-select{

		width:100%

	}

	.ui-datepicker,

	.select2-container--open{

		z-index:9999 !important;

	}

	.table-iloocal tr td {

		font-weight: 400;

		font-size: 14px;

		line-height: 20px;

		padding: 6px 15px;

		background: var(--bs-white);

		border: 1px solid rgba(0, 0, 0, 0.1);

		height: 40px;

	}

	.table-iloocal thead tr th {

		background: #F9F9F9;

		border: 1px solid rgba(0, 0, 0, 0.1);

		white-space: nowrap;

		font-weight: 600;

		font-size: 14px;

		line-height: 20px;

		padding: 10px 15px

	}

	.table-iloocal .js__add-report:not(.text-muted) {

		font-weight: 600;

		font-size: 14px;

		line-height: 19px;

		color: #1756C8 !important;

		cursor: pointer;

	}

	.table-iloocal .js__add-report span.icon {

		display: inline-block;

		width: 14px;

		height: 14px;

		text-align: center;

		line-height: 12px;

		background: #1756C8;

		border-radius: 2px;

		-moz-border-radius: 2px;

		-webkit-border-radius: 2px;

		color: var(--bs-white);

		padding:3px;

		font-size: 10px;

	}

	@media screen and (max-width:767px) {

		.table-iloocal thead tr th, .table-iloocal tbody tr td {

			padding: 5px;

		}

	}

</style>

<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		$Core.report.load_reports_login({});

	});

<?php echo '</script'; ?>
>

<?php }
}
