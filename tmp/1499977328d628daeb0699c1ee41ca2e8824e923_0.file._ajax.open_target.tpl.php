<?php
/* Smarty version 3.1.33, created on 2026-08-07 19:24:26
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/dashboard/_ajax.open_target.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75ce7a7c3183_92012835',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1499977328d628daeb0699c1ee41ca2e8824e923' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/dashboard/_ajax.open_target.tpl',
      1 => 1784300229,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75ce7a7c3183_92012835 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="modal-dialog modal-dialog-centered modal-sm">

	<form method="POST" class="modal-content">

		<div class="modal-header border-bottom">

			<h5 class="modal-title">Mục tiêu cá nhân <?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</h5>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<div class="alert alert-warning">

				<strong>Ghi chú</strong><br />

				Thiết lập mục tiêu cho cá nhân, phòng ban, vùng kinh doanh

			</div>

			<?php if ($_smarty_tpl->tpl_vars['is_sale_dir']->value == '1' || $_smarty_tpl->tpl_vars['is_regional_dir']->value == '1') {?>

			<div class="divider mb-2">

				<div class="divider-text">Mục tiêu<?php if ($_smarty_tpl->tpl_vars['is_regional_dir']->value == '1') {?> Vùng <?php } else { ?> Phòng<?php }?></div>

			</div>

			<div class="form-group my-2">

				<label for="name" class="form-label mb-1">Mục tiêu cho</label>

				<div class="clearfix"></div>

				<div class="btn-group w-100" role="group" aria-label="Hiển thị">

					<input type="radio" class="btn-check js__target-type" tp="dep" name="target_dep_config[target_type]" value="only_month" id="only_dep_month_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['target_dep_config']->value['target_type'] == 'only_month') {?> checked<?php }?> quantity="<?php echo $_smarty_tpl->tpl_vars['target_dep_month_config']->value['quantity'];?>
" amount="<?php echo $_smarty_tpl->tpl_vars['target_dep_month_config']->value['amount'];?>
">

					<label data-toggle="ripple" class="btn btn-outline-default<?php if ($_smarty_tpl->tpl_vars['target_dep_config']->value['target_type'] == 'only_month') {?> active<?php }?>" for="only_dep_month_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Tháng <?php echo smarty_modifier_date_format(time(),"%m");?>
</label>

					<input type="radio" class="btn-check js__target-type" tp="dep" name="target_dep_config[target_type]" id="all_dep_month_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="all_month"<?php if ($_smarty_tpl->tpl_vars['target_dep_config']->value['target_type'] == 'all_month') {?> checked<?php }?> quantity="<?php echo $_smarty_tpl->tpl_vars['target_dep_config']->value['quantity'];?>
" amount="<?php echo $_smarty_tpl->tpl_vars['target_dep_config']->value['amount'];?>
">

					<label data-toggle="ripple" class="btn btn-outline-default<?php if ($_smarty_tpl->tpl_vars['target_dep_config']->value['target_type'] == 'all_month') {?> active<?php }?>" for="all_dep_month_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Cả năm</label>	

				</div>

			</div>

			<div class="form-group form-row mb-2">

				<div class="col-5">

					<label for="name" class="form-label mb-1">SL. Giao dịch</label>

					<input type="text" autocomplete="off" name="target_dep_config[quantity]" class="form-control numberonly price-In required input_value" placeholder="Nhập mục tiêu" value="<?php echo $_smarty_tpl->tpl_vars['target_dep_config']->value['quantity'];?>
">

				</div>

				<div class="col-7">

					<label for="name" class="form-label mb-1">Doanh số</label>

					<div class="input-group input-group-merge">

						<input type="text" autocomplete="off" name="target_dep_config[amount]" class="form-control required price-In numberonly input_value" placeholder="Nhập doanh số" value="<?php echo $_smarty_tpl->tpl_vars['target_dep_config']->value['amount'];?>
">

						<span class="input-group-text"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>
</span>

					</div>

				</div>

			</div>

			<div class="divider my-2">

				<div class="divider-text">Mục tiêu cá nhân</div>

			</div>

			<?php }?>

			<div class="form-group mb-2">

				<label for="name" class="form-label mb-1">Mục tiêu cho</label>

				<div class="clearfix"></div>

				<div class="btn-group w-100" role="group" aria-label="Hiển thị">

					<input type="radio" class="btn-check js__target-type" tp="personal" name="target_config[target_type]" value="only_month" id="only_month_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['target_config']->value['target_type'] == 'only_month') {?> checked<?php }?> onChange="$Core.dashboard.target_change(this, event)">

					<label data-toggle="ripple" class="btn btn-outline-default<?php if ($_smarty_tpl->tpl_vars['target_config']->value['target_type'] == 'all_month') {?> active<?php }?>" for="only_month_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Tháng <?php echo smarty_modifier_date_format(time(),"%m");?>
</label>

					<input type="radio" class="btn-check js__target-type" tp="personal" name="target_config[target_type]" id="all_month_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="all_month"<?php if ($_smarty_tpl->tpl_vars['target_config']->value['target_type'] == 'all_month') {?> checked<?php }?> onChange="$Core.dashboard.target_change(this, event)">

					<label data-toggle="ripple" class="btn btn-outline-default<?php if ($_smarty_tpl->tpl_vars['target_config']->value['target_type'] == 'all_month') {?> active<?php }?>" for="all_month_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Cả năm</label>	

				</div>

			</div>

			<div class="form-group form-row mb-2">

				<div class="col-5">

					<label for="name" class="form-label mb-1">SL. Giao dịch</label>

					<input type="text" autocomplete="off" name="target_config[quantity]" class="form-control numberonly price-In required input_value" placeholder="Nhập mục tiêu" value="<?php echo $_smarty_tpl->tpl_vars['target_config']->value['quantity'];?>
">

				</div>

				<div class="col-7">

					<label for="name" class="form-label mb-1">Doanh số</label>

					<div class="input-group input-group-merge">

						<input type="text" autocomplete="off" name="target_config[amount]" class="form-control required price-In numberonly input_value" placeholder="Nhập doanh số" value="<?php echo $_smarty_tpl->tpl_vars['target_config']->value['amount'];?>
">

						<span class="input-group-text"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>
</span>

					</div>

				</div>

			</div>

		</div>

		<div class="modal-footer border-top">

			<button type="button" class="btn btn-default" data-bs-dismiss="modal">Đóng</button>

			<button type="button" openFrom="<?php echo $_smarty_tpl->tpl_vars['openFrom']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onClick="$Core.dashboard.addTargetSales(this,event)" 

			action="_SAVE" class="btn btn-primary">Lưu lại</button>	

		</div>

	</form>

</div>

<?php }
}
