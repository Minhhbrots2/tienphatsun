<?php
/* Smarty version 3.1.33, created on 2026-08-07 10:32:48
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/sale.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7551e09c2452_93796449',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0f26bcf67781dd0da0da4f1e76d9fea35f57285b' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/sale.tpl',
      1 => 1784299671,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7551e09c2452_93796449 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/daterangepicker/daterangepicker.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />

<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/daterangepicker/moment.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/daterangepicker/daterangepicker.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>

<div class="container-xxl flex-grow-1 pt-2 container-p-y">

	<div class="d-flex justify-content-between align-items-center mb-2">

		<div class="p__left">

			<h4 class="fw-bold mb-1"><span>Báo cáo kết quả kinh doanh</span></h4>

			<p class="text-muted fs-6 mb-0">Phòng ban kinh doanh <?php echo @constant('BRAND_NAME');?>
</p>

		</div>

		<div class="p__right">

			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

			<div class="d-flex form-inline">

				<div class="input-group-date w-px-200 mr-2">

					<input type="text" class="form-control search_field isodaterangepicker1" data-field="date_range" style="padding-left: 40px"/>

				</div>

				<div class="form-group w-px-150">

					<select class="form-control search_field iso-select2" data-field="department_id" 

					data-width="100%" data-allow-clear="true">

						<option value="0">Phòng ban</option>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_departments']->value, '_text', false, 'department_id');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['department_id']->value => $_smarty_tpl->tpl_vars['_text']->value) {
?>

						<option value="<?php echo $_smarty_tpl->tpl_vars['department_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_text']->value;?>
</option>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					</select>

				</div>

			</div>

			<?php }?>

		</div>

	</div>

	<div class="clearfix"></div>

	<div class="card">

		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

		<div class="card-header position-relative border-bottom d-flex justify-content-center mb-2">

			<div class="input-group-date w-px-200 mr-2">

				<input type="text" class="form-control search_field isodaterangepicker1" data-field="date_range" onChange="$Core.report.do_share_search(this, event)" name="date_range" style="padding-left: 40px" />

			</div>

			<select data-field="department_id" class="form-control search_field iso-select2" 

			onChange="$Core.report.do_share_search(this, event)">

				<option value="0">Phòng ban</option>

				<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_departments']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

				<option value="<?php echo $_smarty_tpl->tpl_vars['list_departments']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['list_departments']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'];?>
</option>

				<?php
}
}
?>

			</select>

		</div>

		<?php }?>

		<div id="holder_report_sales" class="card-body<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> p-2<?php }?>">

			<div class="p-5 text-center">

				<p>Loading...</p>

			</div>

		</div>

	</div>

</div>



<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		$('.isodaterangepicker1').daterangepicker({

			timePicker: false,

			"drops": "auto",

			"autoApply": true,

			alwaysShowCalendars: true,

			startDate: moment().startOf('year'),

			endDate: moment().endOf('year'),

			autoUpdateInput: true,

			ranges: {

			   'Hôm nay': [moment(), moment()],

			   'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

			   '7 ngày qua': [moment().subtract(6, 'days'), moment()],

			   '30 ngày qua': [moment().subtract(29, 'days'), moment()],

			   'Tháng này': [moment().startOf('month'), moment().endOf('month')],

			   'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

			},

			opens: (deviceType=='phone'?'center':'left'),

			locale: {format: 'DD/MM/YYYY'}

		});

		setTimeout(() => {

			$Core.report.load_report_sales({});

		}, 500);

		$('.search_field').on('change', function(){

			$Core.report.load_report_sales({});

		});

	});

<?php echo '</script'; ?>
>

<?php }
}
