<?php
/* Smarty version 3.1.33, created on 2026-08-07 13:12:29
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/sale_month.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75774d6644a2_48019195',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '08ae3822d0d04a582e0bdf05b594901f79ab040c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/sale_month.tpl',
      1 => 1786083121,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75774d6644a2_48019195 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Kết quả bán hàng</span></h4>
			<p class="text-muted mb-0">Tổng hợp kết quả kinh doanh từng nhân viên</p>
		</div>
		<div class="p__right">
			<div class="search-block w-full d-flex align-item-center">
				<div class="input-group">
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-search"></i></span>
						<input type="text" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" class="form-control search_field no-radius-right" 
						placeholder="Nhập từ khoá & nhấn Enter..." data-field="keyword" />
					</div>
				</div>
				<div class="btn-group dropdown">
					<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow no-radius-left no-border-left" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
					<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="top-end">
						<div class="p-3">
							<div class="form-group mb-2">
								<label class="form-label mb-1">Chọn năm</label>
								<select class="iso-select2 search_field" data-width="100%" data-field="year" 
									placeholder="Nhân viên" name="year">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_years']->value, '_year');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_year']->value) {
?>
									<option<?php if ($_smarty_tpl->tpl_vars['curr_year']->value == $_smarty_tpl->tpl_vars['_year']->value) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
">Năm <?php echo $_smarty_tpl->tpl_vars['_year']->value;?>
</option>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</select>
							</div>
							<div class="form-group mb-3">
								<label class="form-label mb-1">Phòng ban</label>
								<select class="iso-select2 search_field" data-width="100%" data-field="department_id" 
									placeholder="Nhân viên" name="department_id">
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_DEPARTMENT',@constant('_DEPARTMENT_SALE_ID'),'Phòng ban');?>

								</select>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> p-2<?php }?>">
			<div class="iKuJnjIFyr template_3">
				<div id="holder_report_sale_month" class="hJsiGEcCOJ overflow-x-auto template_3">
					<table class="table table-campaign template_3">
						<thead><tr>
							<th class="p_header text-center text-upper" colspan="16">
								<div class="mb-2">
									<img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageWidth('LogoWhite');?>
" height="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageHeight('LogoWhite');?>
" alt="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['CompanyName'];?>
" />
								</div>
								<strong>Bảng tổng hợp cá nhân <?php echo $_smarty_tpl->tpl_vars['curr_year']->value;?>
</strong><br>
								(01/01/<?php echo $_smarty_tpl->tpl_vars['curr_year']->value;?>
-31/12/<?php echo $_smarty_tpl->tpl_vars['curr_year']->value;?>
)
							</th>
						</tr><tr>
							<th width="3%" class="p_head text-center">STT</th>
							<th class="p_head text-left">Họ và tên</th>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_months']->value, '_oMonth');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oMonth']->value) {
?>
							<th width="6%" class="p_head text-center">T<?php echo $_smarty_tpl->tpl_vars['_oMonth']->value;?>
</th>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<th class="p_head text-center">Tổng</th>
							<th class="p_head text-center d-none">H.suất</th>
						</tr></thead>
						<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 30);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<tr class="p_row">
							<td class="p_cell text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
							<td class="p_cell text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_months']->value, '_oMonth');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oMonth']->value) {
?>
							<td class="p_cell text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<td class="p_cell text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
							<td class="p_cell text-center d-none">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</td>
						</tr>
						<?php
}
}
?>
						<tfoot><tr>
							<th colspan="2" class="p_head text-center">Tổng cộng</th>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_months']->value, '_oMonth');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oMonth']->value) {
?>
							<th width="6%" class="p_head text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</th>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<th class="p_head text-center">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</th>
							<th class="p_head text-center d-none">
								<div class="animate-bg w-100 h-px-15 rounded-pill"></div>
							</th>
						</tr></tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	@media screen and (max-width:768px){
		.card .table{ min-width: 1100px; }
	}
</style>
<?php echo '<script'; ?>
 type="text/javascript">
	$(function(){
		setTimeout(() => {
			$Core.report.load_sale_month({});
		}, 500);
		$('.search_field').on('change', function(){
			$Core.report.load_sale_month({});
		});
	});
<?php echo '</script'; ?>
>
<?php }
}
