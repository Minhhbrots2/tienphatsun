<?php
/* Smarty version 3.1.33, created on 2026-08-07 15:50:35
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.list_group.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a759c5b8e0549_83332007',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '281415a4d5e59b84b34de872986113c5b753d344' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.list_group.tpl',
      1 => 1786085971,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a759c5b8e0549_83332007 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog">

	<form method="POST" class="modal-content" enctype="multipart/form-data">

		<div class="modal-header">

			<h5 class="modal-title" id="modalTopTitle">Danh sách nhóm nhân viên</h5>	

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body scroller">

			<div class="table-wrapper dragscroll text-nowrap">

				<table class="table table-striped table-borderd">

					<thead><tr>

						<th class="algin-center h-px-40" width="3%">STT</th>

						<th class="algin-center h-px-40 text-left">Tên nhóm</th>

						<th class="algin-center h-px-40 text-left">Nhân viên</th>

						<th width="40px"></th>

					</tr></thead>

					<tbody class="table-border-bottom-0 lst_group_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"> 

					<?php if (!empty($_smarty_tpl->tpl_vars['lstGroupProfile']->value)) {?>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstGroupProfile']->value, '_oGroupProfile', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oGroupProfile']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

						<?php $_smarty_tpl->_assignInScope('lstProfile', $_smarty_tpl->tpl_vars['_oGroupProfile']->value['lstProfile']);?>

						<tr class="trUser">

							<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

							<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oGroupProfile']->value['title'];?>
</td>

							<td class="text-left">

								<?php if (!empty($_smarty_tpl->tpl_vars['_oGroupProfile']->value['list_profile_id'])) {?>

									<div data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=load_list_profile_popover&group_id=<?php echo $_smarty_tpl->tpl_vars['_oGroupProfile']->value['group_profile_id'];?>
" data-toggle="webui-popover" data-trigger="hover" data-width="300" class="awe__post-profile d-flex"><?php echo $_smarty_tpl->tpl_vars['_oGroupProfile']->value['total'];?>
 nhân viên</div>

								<?php } else { ?>

									Chưa cập nhật

								<?php }?>

							</td>

							<td class="text-center">

								<div class="dropdown">

									<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">

										<i class="bx bx-dots-vertical-rounded"></i>

									</button>

									<?php if ($_smarty_tpl->tpl_vars['_oGroupProfile']->value['user_id'] == $_smarty_tpl->tpl_vars['profile_id']->value || $_smarty_tpl->tpl_vars['clsISO']->value->checkSupper() || $_smarty_tpl->tpl_vars['clsISO']->value->checkDEV() || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('manager_group')) {?>

									<div class="dropdown-menu w-px-100" data-popper-placement="bottom-end">

										<a class="dropdown-item" onclick="$Core.member.add_group(this,event)" data-type="open" data-group_id="<?php echo $_smarty_tpl->tpl_vars['_oGroupProfile']->value['group_profile_id'];?>
" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>

										<a class="dropdown-item" onclick="$Core.member.delete_group(this,event)" data-group_id="<?php echo $_smarty_tpl->tpl_vars['_oGroupProfile']->value['group_profile_id'];?>
" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>

									</div>

									<?php }?>	

								</div>							

							</td>

						</tr>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<?php } else { ?>

					<tr>

						<td class="text-center" colspan="4">

							<p class="text-muted">Danh sách trống</p>

						</td>

					</tr>

					<?php }?>

					</tbody>

				</table>

			</div>

		</div>

		<div class="modal-footer">

			<button class="btn btn-primary" type="button" onClick="$Core.member.add_group(this,event)" data-type="open" data-group_id="0">Tạo nhóm</button>

		</div>

	</form>

</div>





<?php }
}
