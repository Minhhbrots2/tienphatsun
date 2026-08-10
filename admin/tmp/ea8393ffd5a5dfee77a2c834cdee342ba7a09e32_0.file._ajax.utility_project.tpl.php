<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:23:19
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.utility_project.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a730f1754c724_44074126',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ea8393ffd5a5dfee77a2c834cdee342ba7a09e32' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.utility_project.tpl',
      1 => 1784691721,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a730f1754c724_44074126 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-ipad">

	<div class="modal-content">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 

			<h3 class="modal-title"><strong><?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</strong></h3>

		</div>

		<form method="post" action="" enctype="multipart/form-data">

			<div class="modal-body">

				<div class="form-group">

					<label class="col-form-label">Tiêu đề<span class="text-red">*</span></label>

					<input type="text" class="form-control required" placeholder="Nhập tên tài liệu" name="title" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
" />

				</div>

				<div class="form-group form-row">

					<div class="col-md-8">

						<label class="col-form-label">Phân khu</label>

						<div class="input-group d-flex gap-1">

							<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

							<select class="form-control w-50" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" name="block_id" 

								onChange="$Core.project.select_building(this, event)" placeholder="Chọn phân khu">

								<option value="0">Tất cả</option>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstBlock']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>

									<option value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['block_id'] == $_smarty_tpl->tpl_vars['_oBlock']->value['property_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</select>

							<select class="form-control iso-select2 w-50" multiple="multiple" id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" name="building_ids[]" data-placeholder="Chọn tòa" placeholder="Chọn tòa">

								<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

									<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['oneItem']->value['building_ids'],$_smarty_tpl->tpl_vars['_oI']->value['property_id'])) {?> selected<?php }?> 

										value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
</option>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php }?>

							</select>

						</div>

					</div>

					<div class="col-md-4">

						<label class="col-form-label">Danh mục</label>

						<select class="form-control" name="cat_id">

							<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty("_UTILITIES_PROJECT",$_smarty_tpl->tpl_vars['oneItem']->value['cat_id'],"Chọn danh mục");?>


						</select>

					</div>

				</div>

				<div class="form-group">

					<label class="col-form-label">Mô tả</label>

					<textarea class="form-control" name="content" rows="6"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['content'];?>
</textarea>

				</div>

				<div class="form-group">

					<label class="d-block col-form-label">Hình ảnh</label>					

					<div class="input-group">

						<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh đại diện" id="isoman_url_image" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['image'];?>
">

						<div class="input-group-btn"><button class="btn btn-icon btn-default ajOpenDialog" isoman_for_id="image" isoman_val="" isoman_name="image"><i class="fa fa-image"></i></button></div>	

					</div>

					
				</div>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-success pull-right" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" utilities_id="<?php echo $_smarty_tpl->tpl_vars['utilities_id']->value;?>
"  onClick="$Core.utilities.save(this,event)">Lưu lại</button>

				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Close');?>
</button>

			</div>

		</form>

	</div>

</div>







<?php }
}
