<?php
/* Smarty version 3.1.33, created on 2026-08-05 09:18:42
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/log/log_sale.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a729d82857b77_71653732',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '299206f71da582d966a87572ab61fa1dac00e271' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/log/log_sale.tpl',
      1 => 1784299659,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a729d82857b77_71653732 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
		<div class="nlApYyxOPs mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1">Lịch sử tra cứu</h4>
			<p class="mb-0 text-muted">Có tổng <strong class="total_record text-danger"><?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
</strong> lượt tra cứu 
				<?php if ($_smarty_tpl->tpl_vars['stock_id']->value > '0') {?> tra cứu <?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getOneField('ms_code',$_smarty_tpl->tpl_vars['stock_id']->value);
}?>
			</p>
		</div>
		<div class="buttons d-flex xs:w-100 gap-2 align-items-center">
			<a href="javascript:void(0)" onClick="$Core.log.open_report(this, event)" 
				class="btn flex-fill bg-white btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-chart','Thống kê TOP 20');?>
</a>
			<div class="btn-group">
				<button type="button" class="btn btn-icon hide-arrow btn-outline-default dropdown-toggle" 
				data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
					<i class="bx bx-filter-alt"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">
					<div class="p-3">
						<div class="form-group mb-2">
							<div class="input-group mr-1 input-group-merge">
								<span class="input-group-text"><i class="bx bx-search"></i></span>
								<input type="text" class="form-control search_field" data-field="keySearch" placeholder="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Search');?>
" />
							</div>
						</div>
						<div class="form-group form-row mb-2">
							<div class="col-6">
								<input type="date" class="form-control search_field" placeholder="Từ ngày" data-field="start_date">
							</div>
							<div class="col-6">
								<input type="date" class="form-control search_field" placeholder="Đến ngày" data-field="end_date">
							</div>
						</div>
						<?php if ($_smarty_tpl->tpl_vars['is_full_permiss']->value || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('REGIONAL_DIRECTOR')) {?>
						<div class="form-group mb-2">
							<select id="slb_Profile_Id" class="iso-selectizeSync search_field" data-field="user_id" data-width="100%" 
								data-placeholder="Nhân viên" data-allow-clear="true">
								<option value="">Nhân viên</option>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstUser']->value, '_oI', false, '_staff_id');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_staff_id']->value => $_smarty_tpl->tpl_vars['_oI']->value) {
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['_staff_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['user_id']->value == $_smarty_tpl->tpl_vars['_staff_id']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oI']->value['code'];?>
-<?php echo $_smarty_tpl->tpl_vars['_oI']->value['full_name'];?>
</option>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</select>
						</div>
						<?php }?>
						<div class="form-group mb-2">
							<button type="button" class="btn btn-success" onClick="$Core.log.do_search(this, event)">
								<i class="bx bx-search"></i> Tìm kiếm
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="card">
		<div class="card-body">
			<div class="table-container no-shadow overflow-x-auto text-nowrap">
				<table border="0" cellpadding="0" cellspacing="0" class="table table-striped mb-0" width="100%">
					<thead><tr>
						<th width="15%" class="align-center bg-lighter h-px-35">Họ và tên</th>
						<th width="10%" class="align-center bg-lighter h-px-35">H.Động</th>
						<th class="align-center  bg-lighter h-px-35">Nội dung</th>
						<th width="150px" class="align-center  bg-lighter h-px-35 border-end">Thời gian</th>
						<th width="150px" class="align-center  bg-lighter h-px-35 border-end">Điện thoại</th>
						<th class="align-center  bg-lighter h-px-35 w-px-50"></th>
					</tr></thead>
					<tbody class="holder_logs">
						<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<tr>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						</tr>
						<?php
}
}
?>
					</tbody>
				</table>
			</div>
			<div id="pager_sale_logs"></div>
		</div>
    </div>
</div>

<?php echo '<script'; ?>
 type="text/javascript">
	$(function(){ $Core.log.load_logs({}); });
<?php echo '</script'; ?>
>
<?php }
}
