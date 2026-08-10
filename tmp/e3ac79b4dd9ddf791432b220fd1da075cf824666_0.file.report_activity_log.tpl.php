<?php
/* Smarty version 3.1.33, created on 2026-08-06 09:50:21
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/report_activity_log.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a73f66deafa67_68629224',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e3ac79b4dd9ddf791432b220fd1da075cf824666' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/report_activity_log.tpl',
      1 => 1784299670,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a73f66deafa67_68629224 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="container-xxl flex-grow-1 container-p-y pt-2">	

	<form method="POST">

		<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2 mb-lg-0">

			<div class="title mb-2 mb-lg-0">

				<h4 class="fw-bold mb-1">Log hệ thống</span></h4>

				<span class="text-muted">Danh sách hành động trong hệ thống</span>

			</div>

			<div class="search d-flex flex-wrap align-items-center gap-1">

				<div class="input-group <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>w-100<?php } else { ?>w-auto<?php }?>">

					<select onchange="$Core.report.load_activity_log({})" data-field="_from" 

						class="form-control js__search-department-field search_field <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>flex-fill<?php } else { ?>w-px-100<?php }?> form-select">

						<option value="" >Nguồn</option>

						<option value="admin">Admin</option>

						<option value="front">Website</option>

					</select>

					<select onchange="$Core.report.load_activity_log({})" data-field="profile_id" 

						class="form-control js__search-department-field search_field <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>flex-fill<?php } else { ?>w-px-100<?php }?> form-select">

						<option value="0">Tất cả</option>

						<?php if (!empty($_smarty_tpl->tpl_vars['arr_cache_profile']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_cache_profile']->value, '_oProfile');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProfile']->value) {
?>

							<option value="<?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oProfile']->value['full_name'];?>
</option>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php }?>

					</select>

					<select onchange="$Core.report.load_activity_log({})" data-field="tbl" 

						class="form-control js__search-tbl-field search_field <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>flex-fill<?php } else { ?>w-px-100<?php }?> form-select">

						<option value="" >Chọn loại</option>

					</select>

				</div>

				<div class="input-group <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>w-100<?php } else { ?>w-auto<?php }?>">

					<input type="date" onchange="$Core.report.load_activity_log({})" class="form-control js__search-start_date-field 

					js__search-date-field search_field <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>w-50<?php } else { ?>w-px-125<?php }?>" data-field="start_date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" max="<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d');?>
"/>

					<input type="date" onchange="$Core.report.load_activity_log({})" class="form-control js__search-end_date-field 

					js__search-date-field search_field <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>w-50<?php } else { ?>w-px-125<?php }?>" data-field="end_date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" max="<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d');?>
" />

				</div>

			</div>

		</div>

	</form>

	<div class="card no-shadow mb-2">

		<div class="card-body">

			<div class="table-container no-shadow overflow-x-auto">

				<table cellpadding="0" cellspacing="0" class="table mb-0" width="100%">

					<thead><tr>

						<th width="3%" class="align-center bg-lighter h-px-35 text-center">No.</th>

						<th class="align-center bg-lighter h-px-35 text-left">Tiêu đề</th>

						<th class="align-center bg-lighter h-px-35 text-left">Nội dung</th>

					</tr></thead>

					<tbody class="holder_reports_activity_log">

						<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 10);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

						<tr>

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

			<div class="clearfix mb-1"></div>

			<div id="pager" class="simple-pagination"></div>

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

		.table-container .table tr th:nth-child(2){

			background:#F5F7F8 !important

		}

		.table-container .table tr th:nth-child(2),

		.table-container .table tr td:nth-child(2){

			z-index:2;

			position:sticky;

			left:0px; top:0;

			background:var(--bs-white);

			border-right: 1px solid #d9dee3;

		}

	}

</style>

<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		$Core.report.load_activity_log({});

	});

<?php echo '</script'; ?>
>

<?php }
}
