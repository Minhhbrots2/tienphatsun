<?php
/* Smarty version 3.1.33, created on 2026-08-10 11:21:30
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/_ajax.load_calendar_today.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7951caa3ac69_97112535',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b7fae71e15a8617fca354b23b60a932b920ebf86' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/calendar/_ajax.load_calendar_today.tpl',
      1 => 1786325814,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7951caa3ac69_97112535 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['list_billings']->value)) {?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_billings']->value, '_oBilling');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBilling']->value) {
?>
	<?php $_smarty_tpl->_assignInScope('_oAdmin', $_smarty_tpl->tpl_vars['_oBilling']->value['admin']);?>
	<?php $_smarty_tpl->_assignInScope('_oStaff', $_smarty_tpl->tpl_vars['_oBilling']->value['staff']);?>
	<tr class="trBilling">
		<td class="text-left text-nowrap">
			<div class="d-flex flex-column gap-1">
				<div class="d-flex align-items-center justify-content-between">
					<a href="javascript:void(0)" onclick="view_billing(this,event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['stock_code'];?>
</a>
					<?php if (!empty($_smarty_tpl->tpl_vars['is_edit']->value)) {?>
					<a class="text-link" title="chỉnh sửa thông tin" onclick="$Core.billing.add_info(this,event)" 
						billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
"><i class="bx bx-edit fs-14"></i></a>
					<?php }?>
				</div>
				<h4 class="text-fs-12 mb-0 fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['depart_name'];?>
-<?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['full_name'];?>
</h4>
			</div>
		</td>
		<td class="text-left">
			<div class="d-flex align-items-center gap-1 justify-content-start">
				<img class="rounded-pill avatar avatar-xs" src="<?php if (!empty($_smarty_tpl->tpl_vars['_oAdmin']->value)) {
echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oAdmin']->value['profile_id'],$_smarty_tpl->tpl_vars['_oAdmin']->value);
}?>" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'">
				<div class="d-flex flex-column">
					<h4 class="text-fs-12 mb-0 fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oAdmin']->value['full_name'];?>
</h4>
					<div class="d-flex align-items-center gap-1 text-fs-11 text-muted text-nowrap">
						<i class="material-icons-outlined fs-13 no-translate">more_time</i>
						<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['time'];?>

					</div>
				</div>
			</div>
		</td>
		<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['status'];?>
</td>
		<td class="text-right"><?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['text_type'];?>
</td>
		<?php if ($_smarty_tpl->tpl_vars['call_from']->value == '_sign_page') {?>
		<td class="text-center">
			<?php if (!empty($_smarty_tpl->tpl_vars['_oBilling']->value['is_note'])) {?>
			<a href="javascript:void(0)" class="btn btn-icon btn-outline-default btn-sm" data-toggle="webui-popover" data-trigger="click" data-type="async" billing_id="<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
" data-placement="left-bottom" data-closeable="false" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS']->value;?>
/index.php?mod=home&sub=calendar&act=load_rescheduling_reason&billing_id=<?php echo $_smarty_tpl->tpl_vars['_oBilling']->value['billing_id'];?>
">
				<i class='bx bx-notepad'></i>
			</a>
			<?php } else { ?>
			--
			<?php }?>
		</td>
		<?php }?>
	</tr>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
} else { ?>
	<tr class="trBilling">
		<td class="border-0 bg-transparent zindex-1" colspan="12">
			<div class="dbx-empty">
				<span class="dbx-empty__ic"><i class="bx bx-calendar-x"></i></span>
				<div class="dbx-empty__t">Chưa có lịch ký nào</div>
				<div class="dbx-empty__s">Lịch ký VBTT/HĐMB sẽ hiện ở đây</div>
			</div>
		</td>
	</tr>
<?php }
}
}
