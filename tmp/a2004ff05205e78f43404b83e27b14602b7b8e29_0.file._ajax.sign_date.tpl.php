<?php
/* Smarty version 3.1.33, created on 2026-08-08 11:53:20
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/_ajax.sign_date.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76b640ae0723_11551776',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a2004ff05205e78f43404b83e27b14602b7b8e29' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/_ajax.sign_date.tpl',
      1 => 1784300226,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76b640ae0723_11551776 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-dialog-centered modal-sm">

	<form method="POST" action="#" enctype="multipart/form-data" class="modal-content">

		<div class="modal-header">

			<h5 class="modal-title"><?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</h5>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<div class="form-group mb-3">

				<label for="stock_code" class="form-label mb-1">Mã căn</label>

				<!-- onChange="$Core.calendar.check_code(this, event)" -->

				<input type="hidden" name="billing_id" value="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" />

				<input type="text" name="stock_code" class="form-control required"<?php if ($_smarty_tpl->tpl_vars['tp']->value == '_cancel') {?> disabled<?php }?> 

				placeholder="Nhập mã căn" value="<?php echo $_smarty_tpl->tpl_vars['stock_code']->value;?>
">

			</div>

			<div class="form-group mb-3">

				<label class="form-label mb-1">Dự án</label>

				<div class="clearfix"></div>

				<select placeholder="Chọn dự án" class="iso-selectizeNotSearch required w-100" name="project_id" 

				data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=home&act=list_project" data-optgroup="false" 

				onChange="$Core.billing.load_block(this,event)" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" toId="block<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" >

					<?php if (!empty($_smarty_tpl->tpl_vars['billing_id']->value)) {?>

					<option value="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" selected="selected">

						<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getTitle($_smarty_tpl->tpl_vars['project_id']->value);?>


					</option>

					<?php }?>

				</select>

			</div>

			<div class="form-group mb-3">

				<label class="form-label mb-1">Phân khu</label>

				<div class="clearfix"></div>

				<select data-placeholder="Chọn phân khu" class="form-control form-select required" 

					name="block_id" data-width="100%" data-allow-clear="true" id="block<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.billing.loadProjectDirector(this,event)" >

					<option value="0">Phân khu</option>	

					<?php if (!empty($_smarty_tpl->tpl_vars['lstBlock']->value)) {?>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstBlock']->value, '_oBlock', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>

							<option value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['_oBlock']->value['property_id'] == $_smarty_tpl->tpl_vars['block_id']->value) {?>selected<?php }?> ><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>	

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<?php }?>

				</select>

			</div>

			<div class="form-group mb-3">

				<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">

					<input type="radio" class="btn-check" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onchange="$Core.calendar.select_this(this, event)" name="date_type" 

					id="VBTT_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['tp']->value == '_cancel') {?> disabled<?php }?> value="_text"<?php if ($_smarty_tpl->tpl_vars['sign_type']->value == '_text') {?> checked="checked"<?php }?> />

					<label data-toggle="ripple" class="btn btn-outline-default" for="VBTT_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">VBTT</label>

					<input type="radio" class="btn-check"<?php if ($_smarty_tpl->tpl_vars['tp']->value == '_cancel') {?> disabled<?php }?> uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="date_type" id="HDMB_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" 

					onchange="$Core.calendar.select_this(this, event)" value="_contract"<?php if ($_smarty_tpl->tpl_vars['sign_type']->value == '_contract') {?> checked="checked"<?php }?> />

					<label data-toggle="ripple" class="btn btn-outline-default" for="HDMB_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">HĐMB</label>

				</div>

			</div>

			<div class="form-group <?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
_text<?php if ($_smarty_tpl->tpl_vars['sign_type']->value == '_text') {
} else { ?> d-none<?php }?> mb-3">

				<label for="agree_date" class="form-label mb-1">Ngày ký VBTT</label>

				<input type="datetime-local" id="agree_date" name="agree_date" class="form-control" placeholder="dd/mm/yy" lang="vi-VN" value="<?php echo $_smarty_tpl->tpl_vars['sign_date']->value;?>
" />

			</div>

			<div class="form-group <?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
_contract<?php if ($_smarty_tpl->tpl_vars['sign_type']->value == '_contract') {
} else { ?> d-none<?php }?> mb-3">

				<label for="estimate_date" class="form-label mb-1">Ngày dự kiến ký HĐMB</label>

				<input type="datetime-local" id="estimate_date" name="estimate_date" class="form-control" placeholder="dd/mm/yy" lang="vi-VN" />

			</div>

			<div class="form-group <?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
_contract<?php if ($_smarty_tpl->tpl_vars['sign_type']->value == '_contract') {
} else { ?> d-none<?php }?>">

				<label for="contract_date" class="form-label mb-1">Ngày ký HĐMB</label>

				<input type="datetime-local" id="contract_date" name="contract_date" class="form-control" placeholder="dd/mm/yy" lang="vi-VN" value="<?php echo $_smarty_tpl->tpl_vars['sign_date']->value;?>
" />

			</div>

		</div>

		<div class="modal-footer">

			<input type="hidden" name="tp" value="<?php echo $_smarty_tpl->tpl_vars['tp']->value;?>
" />

			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>

			<button type="button" class="btn flex-fill btn-primary" cal_id="<?php echo $_smarty_tpl->tpl_vars['cal_id']->value;?>
" 

				onClick="$Core.calendar.save_sign_date(this, event)" tp="<?php echo $_smarty_tpl->tpl_vars['tp']->value;?>
">Cập nhật</button>

		</div>

	</form>

</div>

<?php }
}
