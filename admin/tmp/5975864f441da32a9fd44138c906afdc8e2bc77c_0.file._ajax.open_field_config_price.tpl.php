<?php
/* Smarty version 3.1.33, created on 2026-08-06 19:00:01
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_ajax.open_field_config_price.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a74774156f267_89196681',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5975864f441da32a9fd44138c906afdc8e2bc77c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_ajax.open_field_config_price.tpl',
      1 => 1784691753,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a74774156f267_89196681 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-md" style="max-width: 600px">

	<div class="modal-content">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 

			<h3 class="modal-title"><strong><?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</strong></h3>

		</div>

		<form method="post" action="" enctype="multipart/form-data">

			<div class="modal-body">

				<div class="form-row">

					<div class="col-md-6">

						<div class="form-group">

							<label class="form-label">Dự án</label>

							<select name="project_id" class="form-select form-control" onChange="$Core.property.select_block(this,event)" toId="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">

								<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getSelectOptions($_smarty_tpl->tpl_vars['project_id']->value);?>


							</select>

						</div>

					</div>			

					<div class="col-md-6">						

						<div class="form-group">

							<label class="form-label">Phân khu</label>

							<select name="block_id" class="form-select form-control" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">

								<?php if (!empty($_smarty_tpl->tpl_vars['project_id']->value)) {?>

									<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyOrigin("_BLOCK",$_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['oneItem']->value['block_id'],"Phân khu/Block");?>


								<?php } else { ?>								

									<option value="0">Phân khu/Block</option>

								<?php }?>

							</select>

						</div>

					</div>

				</div>

				<div class="form-row">		

					<div class="col-md-4">						

						<div class="form-group">

							<label class="form-label">Loại hình</label>

							<select name="stock_type" class="form-select form-control" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">

								<option value="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['stock_type'] == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>selected<?php }?>>Cao tầng</option>

								<option value="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['stock_type'] == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>selected<?php }?>>Thấp tầng</option>

							</select>

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group">

							<label class="form-label">Giá min</label>

							<input type="text" class="form-control price-In" name="min" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['min'];?>
">

						</div>

					</div>			

					<div class="col-md-4">						

						<div class="form-group">

							<label class="form-label">Giá max</label>

							<input type="text" class="form-control price-In" name="max" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['max'];?>
">

						</div>

					</div>

				</div>

			</div>

			<div class="modal-footer">

				<button type="button" onClick="$Core.property.save_field_config_price(this, event)" field_config_price_id="<?php echo $_smarty_tpl->tpl_vars['field_config_price_id']->value;?>
" class="btn btn-success" data-action="save">

					<span>Lưu lại</span>

				</button>

				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">

					<span>Đóng</span>

				</button>

			</div>

		</form>

	</div>

</div>

<?php }
}
