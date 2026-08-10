<?php
/* Smarty version 3.1.33, created on 2026-08-07 15:51:52
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.trans_to_dept.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a759ca8bdb526_09397053',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8108ba26ea6c9b16b63d84eb100fed90a61ab29f' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.trans_to_dept.tpl',
      1 => 1786085971,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a759ca8bdb526_09397053 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['template_type']->value == '_modal') {?>

<div class="modal-dialog">

	<form method="POST" class="modal-content" enctype="multipart/form-data">

		<div class="modal-header">

			<h5 class="modal-title">Danh sách nhóm nhân viên</h5>	

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<div class="table-wrapper dragscroll text-nowrap">

				<table class="table table-striped table-borderd">

					<thead><tr>

						<th class="algin-center h-px-40" width="3%">STT</th>

						<th class="algin-center h-px-40 text-left">Tên nhóm</th>

						<th class="algin-center h-px-40 text-left">Nhân viên</th>

						<th width="40px"></th>

					</tr></thead>

					<tbody class="table-border-bottom-0 lst_group_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"> </tbody>

				</table>

			</div>

		</div>

		<div class="modal-footer">

			<button class="btn btn-primary" type="button" onClick="$Core.member.open_trans_to_dept(this,event)">Thêm mới</button>

		</div>

	</form>

</div>

<?php } elseif ($_smarty_tpl->tpl_vars['template_type']->value == '_form') {?>

<div class="modal-dialog">

	<form method="POST" class="modal-content" enctype="multipart/form-data">

		<div class="modal-header">

			<h5 class="modal-title">Thêm chuyển phòng</h5>	

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<div class="form-group row mb-2">

				<div class="col-6">

					<label class="form-label mb-1">Từ phòng</label>

					<select class="form-control form-select" name="from_dep_id" 

						onchange="$Core.member.hande_dep_changed(this, event)">

						<option>Từ phòng ban</option>

						<?php if (!empty($_smarty_tpl->tpl_vars['arr_departments']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_departments']->value, '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

							<option value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['title'];?>
</option>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php }?>

					</select>

				</div>

				<div class="col-6">

					<label class="form-label mb-1">Tới phòng</label>

					<select class="form-control form-select" name="to_dep_id">

						<option value="0">Tới phòng ban</option>

						<?php if (!empty($_smarty_tpl->tpl_vars['arr_departments']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_departments']->value, '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>

							<option value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['property_id'];?>
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

			<div class="bg-lighter rounded-2 p-2 mb-2">

				<div class="form-group">

					<div class="col-6">

						<label class="form-label mb-1">Ngày chuyển</label>

						<input type="date" class="form-control required" name="action_date" />

					</div>

				</div>

			</div>

			<div class="form-group row mb-2">

				<div class="col-6">

					<label class="form-label mb-1">Số lượng</label>

					<input type="text" readonly class="form-control required" name="trans_count" value="0" />

				</div>

				<div class="col-6">

					<label class="form-label mb-1">Doanh số</label>

					<input type="doanh số" readonly class="form-control price-In required" 

						name="trans_amount" placeholder="0.00 <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>
" />

				</div>

			</div>

		</div>

		<div class="modal-footer">

			<button class="btn btn-secondary" type="button" data-bs-dismiss="modal" aria-label="Close">Hủy bỏ</button>

			<button type="button" class="btn btn-primary" onClick="$Core.member.save_trans_to_dept(this,event)">Lưu lại</button>

		</div>

	</form>

</div>

<?php }?>



<?php }
}
