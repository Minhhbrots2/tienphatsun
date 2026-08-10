<?php
/* Smarty version 3.1.33, created on 2026-07-31 11:42:03
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.open_import_logs.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c279b26acc2_87537288',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bd543b745bdee86c19ad877f12e28388fcea894c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.open_import_logs.tpl',
      1 => 1784299639,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c279b26acc2_87537288 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
			<h3 class="modal-title"><strong>Lịch sử cập nhật <?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['agency_id']->value);?>
</strong></h3>
		</div>
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="table-container no-shadow overflow-auto" style="max-height:calc(100vh - 120px)">
					<table class="table table-bordered dragable installed" cellpadding="0" cellspacing="0" width="100%">
						<thead class="position-sticky top-0 zindex-3 bg-lighter" style="background: #f5f7f8 !important">
							<tr>
								<th class="align-center bg-lighter h-px-40 zindex-3" class="text-center" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>4<?php } else { ?>3<?php }?>" style="border-right: 0">	
									<div class="d-flex align-items-center gap-1 justify-content-center">
										<div class="d-flex align-items-center gap-1 text-center">
											<span class="d-block border rounded-pill w-px-15 h-px-15 bg-danger"></span>
											<span class="fs-11">Lỗi không đọc được file</span>
										</div>
										<div class="d-flex align-items-center gap-1 text-center">
											<span class="d-block border rounded-pill w-px-15 h-px-15 bg-warning"></span>
											<span class="fs-11">File thay đổi</span>
										</div>
										<div class="d-flex align-items-center gap-1 text-center">
											<span class="d-block border rounded-pill w-px-15 h-px-15 bg-success"></span>
											<span class="fs-11">Thành công</span>
										</div>
									</div>
								</th>
							</tr>
							<tr>
								<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
								<th class="align-center bg-lighter h-px-40 zindex-3" width="5%">No.</th>
								<?php }?>
								<th class="align-center bg-lighter h-px-40 zindex-3" width="25%">Ngày</th>
								<th class="align-center bg-lighter h-px-40 zindex-3">Người cập nhật</th>
								<th class="align-center bg-lighter h-px-40 zindex-3" class="text-center">Nguồn cập nhật</th>
							</tr>
						</thead>
						<?php if (!empty($_smarty_tpl->tpl_vars['list_logs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_logs']->value, '_oLog', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oLog']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
								<tr <?php if ($_smarty_tpl->tpl_vars['_oLog']->value['result_type'] == "read_speadsheet" || $_smarty_tpl->tpl_vars['_oLog']->value['result_type'] == 'copy_speadsheet') {?>
										class="bg-danger text-white nohover cursor-pointer"
									<?php } elseif ($_smarty_tpl->tpl_vars['_oLog']->value['result_type'] == 'change_field') {?>
										class="bg-warning text-white nohover cursor-pointer"
									<?php } else { ?>
										class="bg-success text-white nohover cursor-pointer"
									<?php }?> title='<?php echo $_smarty_tpl->tpl_vars['_oLog']->value['title_log'];?>
'>
									<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
										<td class="text-center h-px-40" style="background: inherit !important"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>
									<?php }?>
									<td class="text-left h-px-40 text-nowrap" style="background: inherit !important"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['_oLog']->value['time'],true);?>
</td>
									<td class="text-left h-px-40"><?php echo $_smarty_tpl->tpl_vars['_oLog']->value['full_name'];?>
</td>
									<td class="text-center h-px-40"><?php echo $_smarty_tpl->tpl_vars['_oLog']->value['type_name'];?>
</td>
								</tr>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php } else { ?>
							<tr>
								<td colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>4<?php } else { ?>3<?php }?>" class="text-center">
									<p>Chưa có lịch sử cập nhật nào!</p>
								</td>
							</tr>
						<?php }?>
					</table>				
				</div>
			</div>
		</form>
	</div>
</div><?php }
}
