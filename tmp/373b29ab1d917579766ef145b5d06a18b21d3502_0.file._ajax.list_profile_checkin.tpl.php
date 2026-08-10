<?php
/* Smarty version 3.1.33, created on 2026-08-07 16:01:01
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.list_profile_checkin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a759ecde25397_40567238',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '373b29ab1d917579766ef145b5d06a18b21d3502' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.list_profile_checkin.tpl',
      1 => 1786093191,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a759ecde25397_40567238 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header"> 
			<h3 class="modal-title"><strong><?php if ($_smarty_tpl->tpl_vars['_type']->value == 'not_checkin') {?>Danh sách chưa check-in<?php } elseif ($_smarty_tpl->tpl_vars['_type']->value == 'all') {?>Danh sách nhân sự<?php } elseif ($_smarty_tpl->tpl_vars['_type']->value == 'on_time') {?>Danh sách đúng giờ<?php } elseif ($_smarty_tpl->tpl_vars['_type']->value == 'late') {?>Danh sách đi muộn<?php } elseif ($_smarty_tpl->tpl_vars['_type']->value == 'checked_out') {?>Danh sách đã check-out<?php } elseif ($_smarty_tpl->tpl_vars['_type']->value == 'early_leave') {?>Danh sách về sớm<?php } else { ?>Danh sách đã check-in<?php }?></strong></h3>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body pt-0">
			<ul class="list-group list-group-flush overflow-y-auto" style="max-height: calc(100vh - 200px)">
				<?php if (!empty($_smarty_tpl->tpl_vars['list_profile_ids']->value)) {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_profile_ids']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
						<li class="list-group-item d-flex align-items-center gap-2 py-2 px-3">
							<a href="javascript:void(0);" onclick="$Core.report_checkin.load_profile_journey(this, event)" data-profile="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['profile_id'];?>
" class="d-flex align-items-center gap-2 text-decoration-none flex-grow-1 text-dark" title="Xem hành trình check-in">
								<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['avatar'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" width="36" height="36" alt="" data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['profile_id'];?>
" data-toggle="webui-popover" data-trigger="hover" data-width="300">
								<div class="flex-grow-1 overflow-hidden">
									<small class="text-muted d-block"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['department_name'];?>
</small>
									<span class="fw-semibold text-truncate d-block" style="max-width:180px" title="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['full_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['full_name'];?>
</span>
									<small class="d-block" style="font-size:11px"><span class="text-muted">Vào:</span> <span class="fw-semibold text-success"><?php if ($_smarty_tpl->tpl_vars['_oItem']->value['time_in']) {
echo $_smarty_tpl->tpl_vars['_oItem']->value['time_in'];
} else { ?>--<?php }?></span> <span class="text-muted ms-2">Ra:</span> <?php if ($_smarty_tpl->tpl_vars['_oItem']->value['time_out'] == 'Chưa check-out') {?><span class="fw-semibold text-warning">Chưa check-out</span><?php } elseif ($_smarty_tpl->tpl_vars['_oItem']->value['time_out'] && $_smarty_tpl->tpl_vars['_oItem']->value['time_out'] != '--') {?><span class="fw-semibold text-danger"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['time_out'];?>
</span><?php } else { ?><span class="text-muted">--</span><?php }?></small>
								</div>
								<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['count']) {?><span class="badge bg-label-primary flex-shrink-0"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['count'];?>
 lần</span><?php }?>
								<i class="bx bx-chevron-right text-muted fs-5 flex-shrink-0"></i>
							</a>
						</li>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php } else { ?>
					<li><div class="text-center p-4 fs-6">Chưa có check-in nào</div></li>
				<?php }?>
			</ul>
		</div>
	</div>
</div><?php }
}
