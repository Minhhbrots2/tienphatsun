<?php
/* Smarty version 3.1.33, created on 2026-08-08 08:45:25
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/email_template/email.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a768a35df4055_05143792',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '96a282587ec8f813271616599f3b1a81c3105e94' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/email_template/email.tpl',
      1 => 1784691622,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a768a35df4055_05143792 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['template']->value == '_list') {?>
	<?php if ($_smarty_tpl->tpl_vars['lstEmail']->value) {?>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstEmail']->value, 'email', false, 'email_id');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['email_id']->value => $_smarty_tpl->tpl_vars['email']->value) {
?>
		<?php $_smarty_tpl->_assignInScope('email_type', $_smarty_tpl->tpl_vars['email']->value['email_type']);?>
		<tr>
			<td class="text-left">Gửi email đến - <strong><?php if ($_smarty_tpl->tpl_vars['email_type']->value == 'email') {
echo $_smarty_tpl->tpl_vars['email']->value['email_address'];
} else {
echo $_smarty_tpl->tpl_vars['email']->value['email_name'];?>
 - <?php echo $_smarty_tpl->tpl_vars['email']->value['email_address'];
}?></strong></td>
			<td class="text-center">
				<?php if ($_smarty_tpl->tpl_vars['email']->value['status'] == '1') {?>
				<button type="button" class="btn btn-warning" onClick="status_email_notifier(this)" status="0" holderG="<?php echo $_smarty_tpl->tpl_vars['holderG']->value;?>
" email_id="<?php echo $_smarty_tpl->tpl_vars['email_id']->value;?>
">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pause',$_smarty_tpl->tpl_vars['core']->value->get_Lang('TurnOff'));?>

				</button>
				<?php } else { ?>
				<button type="button" class="btn btn-success" onClick="status_email_notifier(this)" status="1" holderG="<?php echo $_smarty_tpl->tpl_vars['holderG']->value;?>
" email_id="<?php echo $_smarty_tpl->tpl_vars['email_id']->value;?>
">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('play',$_smarty_tpl->tpl_vars['core']->value->get_Lang('TurnOn'));?>

				</button>
				<?php }?>
			</td>
			<td class="text-center">
				<button type="button" class="btn btn-default" onClick="stop_email_notifier(this)" holderG="<?php echo $_smarty_tpl->tpl_vars['holderG']->value;?>
" email_id="<?php echo $_smarty_tpl->tpl_vars['email_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Delete'));?>
</button>
			</td>
		</tr>
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php } else { ?>
	<tr>
		<td class="text-center" colspan="3">
			<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->renderHTMLNoDocument('Chưa có địa chỉ E-Mail');?>

		</td>
	</tr>
	<?php }
} else { ?>
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Thêm thông báo <?php if ($_smarty_tpl->tpl_vars['holderG']->value == 'contact') {?>liên hệ<?php } else { ?>đơn hàng<?php }?></h5>
				<button type="button" class="close close_pop" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post">
				<div class="modal-body">
					<div class="form-group lines">
						<label for="" class="form-control-label">Phương thức thông báo</label>
						<select class="iso-selectize required" name="email_type" onChange="hanlder_email_type_change(this)">
							<optgroup label="Phương thức thông báo">
								<option selected value="email">Địa chỉ email</option>
							</optgroup>
							<optgroup label="hoặc gửi email cho nhân viên">
								<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstUser']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['lstUser']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsUser']->value->getFullName($_smarty_tpl->tpl_vars['lstUser']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_id']);?>
</option>
								<?php
}
}
?>
							</optgroup>
						</select>
					</div>
					<div class="form-group email__address-group">
						<label for="" class="form-control-label">Địa chỉ email</label>
						<input type="text" class="form-control required" id="ipn__email-address" autocomplete="off" name="email_address" placeholder="Nhập địa chỉ email" />
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="submit" value="Update" />
					<button type="button" class="btn btn-secondary close_pop" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Close');?>
</button>
					<button type="button" class="btn btn-success" holderG="<?php echo $_smarty_tpl->tpl_vars['holderG']->value;?>
" onClick="add_email_notifier(this)"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Save');?>
</button>
				</div>
			</form>
		</div>
	</div>
<?php }
}
}
