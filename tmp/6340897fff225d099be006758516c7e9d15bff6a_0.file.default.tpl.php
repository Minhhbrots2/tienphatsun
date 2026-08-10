<?php
/* Smarty version 3.1.33, created on 2026-08-05 16:12:36
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/course/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72fe84697586_18322694',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6340897fff225d099be006758516c7e9d15bff6a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/course/default.tpl',
      1 => 1784300227,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72fe84697586_18322694 (Smarty_Internal_Template $_smarty_tpl) {
echo $_smarty_tpl->tpl_vars['scriptJs']->value;?>


<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">

	<div class="d-flex flex-wrap justify-content-between align-items-center py-2">

		<div class="p__left">

			<h4 class="fw-bold mb-1">Sự kiện</h4>

			<span class="text-muted">Các sự kiện tại <?php echo @constant('BRAND_NAME');?>
</span>

		</div>

		<div class="p__right d-flex">			

			<?php if ($_smarty_tpl->tpl_vars['permiss_add']->value == 1 || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('PROJECT_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('HR')) {?>

			<div class="d-flex justify-content-end">

				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

				<a href="javascript:void(0)" onClick="$Core.course.open_course(this,event)" data-type="open" data-course_id="0" class="btn btn-icon btn-outline-primary mr-2"><i class='bx bx-plus'></i></a>

				<?php } else { ?>

				<a href="javascript:void(0)" onClick="$Core.course.open_course(this,event)" data-type="open" data-course_id="0" class="btn btn-outline-primary mr-2">Thêm mới </a>

				<?php }?>

				<div class="btn-group">

					<button type="button" class="btn btn-icon btn-outline-default dropdown-toggle hide-arrow" 

					data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">

						<i class="bx bx-filter-alt"></i>

					</button>

					<div class="dropdown-menu dropdown-menu-end w-px-350" data-popper-placement="bottom-end"><div class="p-3">

						<div class="input-group input-group-merge mb-3">

							<span class="input-group-text"><i class="bx bx-search"></i></span>

							<input type="text" class="form-control search_field" data-field="keySearch" placeholder="Tìm kiếm" />

						</div>

						<div class="form-floating w-100 mb-2">

							<select class="form-control w-100 form-select search_field" data-field="cat_id">

								<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_FAQs',$_smarty_tpl->tpl_vars['cat_id']->value,'Loại đào tạo');?>


							</select>

							<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Loại đào tạo</label>

						</div>

						<div class="input-group input-date-picker mb-2">

							<i class="ico ico-calendar"></i>

							<input type="text" class="form-control from_date search_field w-px-100" 

								placeholder="Từ ngày" data-field="start_date">

							<input type="text" class="form-control to_date search_field w-px-100" 

								placeholder="Đến ngày" data-field="end_date">

						</div>

						<div class="form-group">

							<button type="button" class="btn btn-success" onClick="$Core.course.do_search(this, event)">

								<i class="bx bx-search"></i> Tìm kiếm

							</button>

						</div>

					</div></div>

				</div>

			</div>	

			<?php }?>

		</div>

	</div>

	<div class="card page_list">

		<div class="card-body pt-3">

			<div id="holder_courses" class="holder_courses mb-2">

				<table class="table" width="100%" cellpadding="0">

					<thead><tr>

						<th class="align-center text-left" width="25%">Tiêu đề</th>

						<th class="align-center text-left" width="20%">Loại sự kiện</th>

						<th class="align-center text-left" width="30%">Tham gia</th>

						<th class="align-center text-left" width="10%">Đã tham gia</th>

						<th class="align-center text-left" width="160px">Ngày tạo</th>

						<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1') {?>

						<th class="align-center text-left" width="45px"></th>

						<?php }?>

					</tr></thead>

					<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 15);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

					<tr>

						<td class="text-center">

							<div class="animate-bg w-100 h-px-15 rounded-2 mb-1"></div>

							<div class="animate-bg w-50 h-px-15 rounded-2"></div>

						</td>

						<td class="text-center">

							<div class="animate-bg w-100 h-px-15 mb-1 rounded-2"></div>

							<div class="animate-bg w-50 h-px-15 rounded-2"></div>

						</td>

						<td class="text-center">

							<div class="animate-bg w-100 h-px-15 mb-2 rounded-1"></div>

							<div class="animate-bg w-50 h-px-15 rounded-2"></div>

						</td>

						<td class="text-center">

							<div class="animate-bg w-100 h-px-15 rounded-2"></div>

						</td>

						<td class="text-center">

							<div class="animate-bg w-100 h-px-15 rounded-2"></div>

						</td>

						<td class="text-center">

							<div class="animate-bg w-100 h-px-15 rounded-2"></div>

						</td>

					</tr>

					<?php
}
}
?>

				</table>

			</div>

			<div id="pager" class="simple-pagination"></div>

		</div>

	</div>

</div>



<style type="text/css">

	.ui-datepicker{ z-index:9999 !important;}

</style>

<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		$Core.course.list({}, true);

	});

<?php echo '</script'; ?>
>

<?php }
}
