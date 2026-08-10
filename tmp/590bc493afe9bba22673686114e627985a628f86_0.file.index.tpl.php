<?php
/* Smarty version 3.1.33, created on 2026-08-07 09:17:03
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/target_sales/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75401fcab5e3_69355045',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '590bc493afe9bba22673686114e627985a628f86' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/target_sales/index.tpl',
      1 => 1785927054,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75401fcab5e3_69355045 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="mb-2">

	<div class="card h-100 mb-2 target_sale">

		<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

		<div class="card-header d-flex justify-content-between flex-wrap gap-1 align-items-center">

			<h5 class="card-title mb-0 title_box_target">Mục tiêu của tôi</h5>

			<div class="d-flex align-items-center gap-1 flex-fill justify-content-end ">

				<button type="button" class="btn btn-outline-default btn-icon btn-sm" title="Cài đặt" onClick="$Core.dashboard.addTargetSales(this,event)" action="_OPEN">

					<i class='bx bx-list-plus'></i>

				</button>

				<?php if (($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BUSINESS_AREA') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR'))) {?>

					<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

						<div class="btn-group">

							<button class="btn btn-icon hide-arrow btn-outline-default btn-sm dropdown-toggle" data-bs-toggle="dropdown">

								<i class='bx bx-filter-alt'></i>

							</button>

							<div class="dropdown-menu dropdown-menu-end w-px-150 p-2" data-popper-placement="bottom-end">

								<select class="form-control w-100 mb-2 form-select search_field" name="department_id" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onChange="$Core.dashboard.reload(this,event)">

									<option value="">Cá nhân</option>

									<option value="<?php echo $_smarty_tpl->tpl_vars['oneDepartment']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['oneDepartment']->value['title'];?>
</option>

									<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BUSINESS_AREA') && !empty($_smarty_tpl->tpl_vars['oneDepartment']->value['children'])) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['oneDepartment']->value['children'], '_oChild', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oChild']->value) {
?>

											<option value="<?php echo $_smarty_tpl->tpl_vars['_oChild']->value['property_id'];?>
">PKD <?php echo $_smarty_tpl->tpl_vars['_oChild']->value['title'];?>
</option>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php }?>

								</select>

								<input type="month" class="form-control  search_field w-100" name="month" value="<?php echo smarty_modifier_date_format(time(),'%Y-%m');?>
" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onchange="$Core.dashboard.reload(this,event)">

							</div>

						</div>

					<?php } else { ?>

						<div class="input-group w-px-250">

							<select class="form-control w-px-100 form-control-sm form-select search_field" name="department_id" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onChange="$Core.dashboard.reload(this,event)">

								<option value="">Cá nhân</option>

								<?php if ($_smarty_tpl->tpl_vars['oneDepartment']->value['more_information']['head_of_dep_id'] == $_smarty_tpl->tpl_vars['profile_id']->value) {?>

								<option value="<?php echo $_smarty_tpl->tpl_vars['oneDepartment']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['oneDepartment']->value['title'];?>
</option>

								<?php }?>

								<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BUSINESS_AREA') && !empty($_smarty_tpl->tpl_vars['oneDepartment']->value['children'])) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['oneDepartment']->value['children'], '_oChild', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oChild']->value) {
?>

										<option value="<?php echo $_smarty_tpl->tpl_vars['_oChild']->value['property_id'];?>
">PKD <?php echo $_smarty_tpl->tpl_vars['_oChild']->value['title'];?>
</option>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php }?>

							</select>

							<input type="month" class="form-control form-control-sm search_field w-px-150" name="month" value="<?php echo smarty_modifier_date_format(time(),'%Y-%m');?>
" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onchange="$Core.dashboard.reload(this,event)">

						</div>

					<?php }?>

				<?php } else { ?>

					<div class="w-px-150">

						<input type="month" class="form-control form-control-sm search_field" name="month" value="<?php echo smarty_modifier_date_format(time(),'%Y-%m');?>
" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onchange="$Core.dashboard.reload(this,event)">

					</div>

				<?php }?>



			</div>							

		</div>

		<div class="card-body">

			<div class="box_target_sale ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=dashboard&act=load_target_sales" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options="{}"></div>

		</div>

	</div>

</div><?php }
}
