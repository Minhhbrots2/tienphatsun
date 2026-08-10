<?php
/* Smarty version 3.1.33, created on 2026-08-07 17:36:01
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/course/_ajax.listCourse.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75b511942667_29472768',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cff1f19401463040c17c1c467b01178e35331be9' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/course/_ajax.listCourse.tpl',
      1 => 1784300227,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75b511942667_29472768 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['deviceType']->value != "phone") {?>

<div class="table-container no-shadow overflow-x-auto">

	<table class="table dragable" cellpadding="0" cellspacing="0" border="0" width="100%">

		<thead><tr>

			<th class="align-center h-px-40 text-left">Tiêu đề</th>

			<th class="align-center h-px-40 bg-lighter text-left">Loại sự kiện</th>

			<th class="align-center h-px-40 bg-lighter bg-lighter text-left">Tham gia</th>

			<th class="align-center h-px-40 bg-lighter text-left">Đã tham gia</th>

			<th class="align-center h-px-40 bg-lighter bg-lighter text-left" width="160px">Ngày tạo</th>

			<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1') {?>

			<th class="align-center h-px-40 bg-lighter text-left" width="45px"></th>

			<?php }?>

		</tr></thead>

		<tbody>

			<?php if (!empty($_smarty_tpl->tpl_vars['list_courses']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_courses']->value, 'course', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['course']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

			<tr class="tr">

				<td class="text-left" style="min-width:500px">

					<div class="d-flex flex-column">

						<a class="fs-14 fw-semibold" href="javascript:void(0);" onclick="$Core.course.open(this, event)" title="Xem ngay" course_id="<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['course']->value['title'];?>
 <?php echo $_smarty_tpl->tpl_vars['course']->value['status'];?>
</a> 								

						<div class="d-flex align-items-center gap-2">

							<div class="d-flex align-items-center gap-1 text-muted">

								<img class="avatar avatar-xxs rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['course']->value['oneUser']['profile_id'],$_smarty_tpl->tpl_vars['course']->value['oneUser'],40,40);?>
" /> 

								<span class="text-muted"><?php echo $_smarty_tpl->tpl_vars['course']->value['oneUser']['full_name'];?>
</span>

							</div>

							<?php if ($_smarty_tpl->tpl_vars['course']->value['location']) {?>

							<span class="text-muted d-none">

								<i class="bx bxs-map me-1 fs-12"></i> <?php echo $_smarty_tpl->tpl_vars['course']->value['location'];?>
</span>

							<?php }?>

							<span class="text-muted fs-12">

								<i class="bx bx-time-five fs-12"></i> <?php echo $_smarty_tpl->tpl_vars['course']->value['start_date'];?>
 - <?php echo $_smarty_tpl->tpl_vars['course']->value['due_date'];?>
</span>

						</div>

					</div>

				</td>

				<td class="text-nowrap"><?php echo $_smarty_tpl->tpl_vars['course']->value['cat_name'];?>
</td>

				<td class="text-nowrap">

					<?php if ($_smarty_tpl->tpl_vars['course']->value['is_all_staff'] == 1) {?>

						Tất cả thành viên

					<?php } elseif ($_smarty_tpl->tpl_vars['course']->value['is_all_staff'] == 2) {?>

						<div class="d-flex flex-column">

							<?php if ($_smarty_tpl->tpl_vars['course']->value['group_profile']) {?><span><?php echo $_smarty_tpl->tpl_vars['course']->value['group_profile'];?>
</span><?php }?>

						</div>

					<?php } else { ?>

						<div class="d-flex flex-column">

							<?php if ($_smarty_tpl->tpl_vars['course']->value['department']) {?><span><?php echo $_smarty_tpl->tpl_vars['course']->value['department'];?>
</span><?php }?>

							<?php if ($_smarty_tpl->tpl_vars['course']->value['profile']) {?><span><?php echo $_smarty_tpl->tpl_vars['course']->value['profile'];?>
</span><?php }?>

						</div>

					<?php }?> 

					<p class="mb-0"><i class='bx bx-user mr-1 align-top'></i><?php echo $_smarty_tpl->tpl_vars['course']->value['total_profile'];?>
 <span data-url="/index.php?mod=home&sub=course&act=load_list_user&course_id=<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
&type='all'" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile d-none" ><i class='bx bxs-show'></i></span></p>

				</td>

				<td class="text-nowrap text-center">

					<p class="mb-0"><?php echo $_smarty_tpl->tpl_vars['course']->value['txt_join'];?>
 <span data-url="/index.php?mod=home&sub=course&act=load_list_user&course_id=<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
&type=accept" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile" ><i class='bx bx-user align-top'></i></span></p>

				</td>

				<td class="text-left text-nowrap">

					<i class="material-icons-outlined">more_time</i> 

					<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['course']->value['reg_date'],true);?>


				</td>

				<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1') {?>

				<td class="text-center text-nowrap">

					<?php if ($_smarty_tpl->tpl_vars['course']->value['user_id'] == $_smarty_tpl->tpl_vars['profile_id']->value) {
}?>

					<div class="dropdown">

						<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>

						</button>

						<div class="dropdown-menu w-px-100" style="">

							<a class="dropdown-item" onclick="$Core.course.open_course(this,event)" data-type="open" <?php if ($_smarty_tpl->tpl_vars['course']->value['cat_id'] == @constant('_CAT_EVENT_ID')) {?>_tp="event" ssss<?php }?> data-course_id="<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>

							<a class="dropdown-item" onclick="$Core.course.delete(this,event)" data-course_id="<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>

						</div>

					</div>

					<?php }?>

				</td>

			</tr>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?> 

		<?php } else { ?>

			<tr class="tr">

				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission("create_course")) {?>

				<td colspan="6" class="text-center">Danh sách trống</td>

				<?php } else { ?>

				<td colspan="5" class="text-center">Danh sách trống</td>

				<?php }?>

			</tr>

		<?php }?>

		</tbody>

	</table>

</div>

<?php } else { ?>

	<?php if (!empty($_smarty_tpl->tpl_vars['list_courses']->value)) {?>

	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_courses']->value, 'course', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['course']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

	<div class="py-3 <?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?>border-bottom<?php }?>">

		<h5 class="fw-semibold mb-0 lh-base"><a class="fs-16 fw-semibold" href="javascript:void(0);" onclick="$Core.course.open(this, event)" title="Xem ngay" course_id="<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['course']->value['title'];?>
</a></h5>

		<div class="d-flex flex-column">

			<p class="mb-1"><span class="text-main fst-italic fs-12">Thời gian: <?php echo $_smarty_tpl->tpl_vars['course']->value['start_date'];?>
 - <?php echo $_smarty_tpl->tpl_vars['course']->value['due_date'];?>
</span> <?php echo $_smarty_tpl->tpl_vars['course']->value['status'];?>
</p>

			<?php if ($_smarty_tpl->tpl_vars['course']->value['location']) {?><p class="text-muted mb-1"><i class='bx bxs-map mr-1'></i><?php echo $_smarty_tpl->tpl_vars['course']->value['location'];?>
</p><?php }?>

			<?php if ($_smarty_tpl->tpl_vars['course']->value['is_all_staff'] == 1) {?>

				<p class="mb-1"><span>Tham gia: Tất cả thành viên</span></p>

			<?php } else { ?>

				<?php if ($_smarty_tpl->tpl_vars['course']->value['department']) {?><p class="mb-1"><span>Phòng ban: <?php echo $_smarty_tpl->tpl_vars['course']->value['department'];?>
</span></p><?php }?>

				<?php if ($_smarty_tpl->tpl_vars['course']->value['profile']) {?><p class="mb-1"><span>Nhân viên: <?php echo $_smarty_tpl->tpl_vars['course']->value['profile'];?>
</span></p><?php }?>

			<?php }?> 

			<p class="mb-1 d-none">Dự kiến tham gia: <?php echo $_smarty_tpl->tpl_vars['course']->value['total_profile'];?>
 <span data-url="/index.php?mod=home&sub=course&act=load_list_user&course_id=<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
&type='all'" data-toggle="webui-popover" data-trigger="hover" data-width="300" class="awe__post-profile" ><i class='bx bxs-show'></i></span></p>

			<div class="d-flex justify-content-between alignt-items-center">

				<p class="mb-0">Đã tham gia: <?php echo $_smarty_tpl->tpl_vars['course']->value['txt_join'];?>
 <?php if ($_smarty_tpl->tpl_vars['course']->value['count_accept'] > 0) {?><span data-url="/index.php?mod=home&sub=course&act=load_list_user&course_id=<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
&type=accept" data-toggle="webui-popover" data-trigger="hover" data-width="300" class="awe__post-profile" ><i class='bx bxs-show'></i></span><?php }?></p>

				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission("create_course") || $_smarty_tpl->tpl_vars['course']->value['user_id'] == $_smarty_tpl->tpl_vars['profile_id']->value) {?>

					<div class="dropdown d-flex justify-content-end">

						<button type="button" class="btn btn-icon btn-sm btn-link text-muted dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>

						</button>

						<div class="dropdown-menu dropdown-menu-end w-px-100">

							<a class="dropdown-item" onclick="$Core.course.addCourse(this,event)" data-type="open" data-course_id="<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>

							<a class="dropdown-item" onclick="$Core.course.delete(this,event)" data-course_id="<?php echo $_smarty_tpl->tpl_vars['course']->value['course_id'];?>
" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>

						</div>

					</div>

				<?php }?>

			</div>



		</div>

	</div>

	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	<?php }?>

<?php }
}
}
