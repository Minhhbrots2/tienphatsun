<?php
/* Smarty version 3.1.33, created on 2026-08-08 18:33:04
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/competition/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7713f0697022_06232415',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ea80e870a187ef325efd74c4c88add216f7a6146' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/competition/default.tpl',
      1 => 1786188782,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7713f0697022_06232415 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<header class="ui-title-bar-container">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Thi đua định danh</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__actions">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit" class="btn btn-success">+ Thêm chương trình</a>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
					<table class="table table-hover m-0">
						<thead>
							<tr>
								<th width="50">#</th>
								<th>Tên chương trình</th>
								<th>Thời gian</th>
								<th class="text-center">Phòng ban</th>
								<th class="text-center">Trạng thái</th>
								<th width="120"></th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($_smarty_tpl->tpl_vars['programs']->value)) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['programs']->value, 'p');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
?>
								<tr>
									<td><?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
</td>
									<td><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</strong></td>
									<td>
										<?php if ($_smarty_tpl->tpl_vars['p']->value['period_type'] == 'year') {?>Cả năm<?php } elseif ($_smarty_tpl->tpl_vars['p']->value['period_type'] == 'quarter') {?>Quý<?php } elseif ($_smarty_tpl->tpl_vars['p']->value['period_type'] == 'month') {?>Tháng<?php } else { ?>Khoảng ngày<?php }?>
										<?php if ($_smarty_tpl->tpl_vars['p']->value['start_date']) {?><br /><small class="text-muted"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['p']->value['start_date'],"%d/%m/%Y");?>
 - <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['p']->value['end_date'],"%d/%m/%Y");?>
</small><?php }?>
									</td>
									<td class="text-center"><?php echo count($_smarty_tpl->tpl_vars['p']->value['department_ids']);?>
</td>
									<td class="text-center">
										<?php if ($_smarty_tpl->tpl_vars['p']->value['status']) {?><span class="label label-success">Đang chạy</span><?php } else { ?><span class="label label-default">Tạm dừng</span><?php }?>
									</td>
									<td>
										<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&id=<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" class="btn btn-xs btn-primary text-white">Sửa</a>
										<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&id=<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" class="btn btn-xs btn-danger" onclick="return confirm('Xoá chương trình này?')">Xoá</a>
									</td>
								</tr>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php } else { ?>
								<tr><td colspan="6" class="text-center text-muted p-4">Chưa có chương trình nào. Bấm "+ Thêm chương trình".</td></tr>
							<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }
}
