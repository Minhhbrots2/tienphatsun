<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:26:14
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/course/_ajax.home_events.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7693c6d8c2a6_22721794',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a86765be7ace71443d33be7ccf5a8cf9083fa9f6' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/course/_ajax.home_events.tpl',
      1 => 1786096209,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7693c6d8c2a6_22721794 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['list_events']->value)) {?>
<div class="lstCourse owl owl-carousel" data-lg-slide="1" data-md-slide="1" data-xs-slide="1"  data-sm-slide="1" data-loop="false" data-nav="false" data-dots="true">
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_events']->value, 'oneEvent', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['oneEvent']->value) {
?>
	<?php $_smarty_tpl->_assignInScope('course_id', $_smarty_tpl->tpl_vars['oneEvent']->value['course_id']);?>
	<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
	<div class="cursor-pointer text-white pr-1">
		<div class="form-row row">
			<div class="col-xxl-9 col-md-8 l-event<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> mb-2<?php }?>">
				<div class="d-flex flex-column">
					<span class="py-1 fs-13 fst-italic">-- <?php echo $_smarty_tpl->tpl_vars['oneEvent']->value['cat_name'];?>
 --- <?php echo $_smarty_tpl->tpl_vars['oneEvent']->value['status'];?>
</span>
					<a onClick="$Core.course.open(this, event)" course_id="<?php echo $_smarty_tpl->tpl_vars['course_id']->value;?>
" class="text-white line-clamp-1 fw-bold <?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['cat_id'] == @constant('_MEDIA_DISSEMINATION') && empty($_smarty_tpl->tpl_vars['oneEvent']->value['is_joined'])) {?>item_media_dissemination<?php }?>" href="javascript:void(0)"><?php echo $_smarty_tpl->tpl_vars['oneEvent']->value['title'];?>
</a>
					<span class="time mb-1 text-white">
						<i class="material-icons-outlined">schedule</i> 
						<?php echo $_smarty_tpl->tpl_vars['clsCourse']->value->getTimeStartCourse($_smarty_tpl->tpl_vars['oneEvent']->value['start_date']);?>
 
					</span>
					<span class="time mb-1 text-white">
						<i class="material-icons-outlined">timer_off</i> 
						<?php echo $_smarty_tpl->tpl_vars['clsCourse']->value->getTimeStartCourse($_smarty_tpl->tpl_vars['oneEvent']->value['due_date']);?>
 
					</span>
					<?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['location']) {?>
					<p class="limit_1line text-white mb-1">
						<i class="material-icons-outlined">location_on</i> 
						<?php echo $_smarty_tpl->tpl_vars['oneEvent']->value['location'];?>

					</p>
					<?php }?>
					<div onClick="<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('view_all_course') || $_smarty_tpl->tpl_vars['oneEvent']->value['user_id'] == $_smarty_tpl->tpl_vars['profile_id']->value || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR')) {?>$Core.course.openJoin(this, event)<?php }?>" course_id="<?php echo $_smarty_tpl->tpl_vars['course_id']->value;?>
" class="d-flex gap-1 align-item-center">
						<i class="bx bx-user"></i>
						<u class="text-white"><?php echo $_smarty_tpl->tpl_vars['oneEvent']->value['total_joined'];?>
 người tham gia</u>
					</div>
				</div>							
			</div>
			<?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['cat_id'] == @constant('_MEDIA_DISSEMINATION')) {?>
				<?php if (!$_smarty_tpl->tpl_vars['oneEvent']->value['is_expired']) {?>
				<div class="col-md-3">
					<?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['is_joined'] == '0') {?>
						<button type="buton" class="btn btn-block btn-sm btn-outline-primary" onClick="$Core.course.open_report(this,event)" course_id="<?php echo $_smarty_tpl->tpl_vars['oneEvent']->value['course_id'];?>
" openFrom="_pop" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"><i class='bx bx-user-voice'></i> Báo cáo</button>							
					<?php } else { ?>
						<form action="" class="w-100" enctype="multipart/form-data">
							<div class="ui-stack ui-stack--wrap d-flex justify-content-end position-relative">
								<span type="buton" class="btn btn-sm btn-primary btn_checkin text-nowrap">
									<i class='bx bx-user-check'></i> Đã báo cáo</span>
							</div>
						</form>	
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['total_joined'] > 0) {?>
						<?php $_smarty_tpl->_assignInScope('upload_share', $_smarty_tpl->tpl_vars['oneEvent']->value['upload_share']);?>
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
						<div class="form-row mt-3">
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['upload_share']->value, 'upload', false, NULL, 'i', array (
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['upload']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
?>
								<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'] : null) < 4) {?>
								<div class="col-3" data-fancybox="gallery-course" href="<?php echo $_smarty_tpl->tpl_vars['upload']->value['image'];?>
">
									<img src="<?php echo $_smarty_tpl->tpl_vars['upload']->value['image'];?>
" alt="" width="" height="90" style="object-fit: contain">
								</div>
								<?php } else { ?>
								<div class="d-none" data-fancybox="gallery-course" href="<?php echo $_smarty_tpl->tpl_vars['upload']->value['image'];?>
">
									<img src="<?php echo $_smarty_tpl->tpl_vars['upload']->value['image'];?>
">
								</div>
								<?php }?>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</div>
						<?php } else { ?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['upload_share']->value, 'upload', false, NULL, 'i', array (
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['upload']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
?>
								<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'] : null) == 0) {?>
								<div class="img mt-2" data-fancybox="gallery-course" href="<?php echo $_smarty_tpl->tpl_vars['upload']->value['image'];?>
">
									<img src="<?php echo $_smarty_tpl->tpl_vars['upload']->value['image'];?>
" width="70" height="70" style="object-fit: contain">
								</div>
								<?php } else { ?>
								<div class="d-none" data-fancybox="gallery-course" href="<?php echo $_smarty_tpl->tpl_vars['upload']->value['image'];?>
">
									<img src="<?php echo $_smarty_tpl->tpl_vars['upload']->value['image'];?>
">
								</div>
								<?php }?>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					<?php }?>
				</div>
				<?php }?>
			<?php } else { ?>
			<div class="col-xxl-3 col-md-4">
				<div class="d-flex gap-2 align-items-center<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?> flex-column<?php }?> justify-content-end w-100">
					<?php if (!$_smarty_tpl->tpl_vars['oneEvent']->value['is_expired']) {?>
						<?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['is_joined'] == '0') {?>
							<button type="button" class="btn btn-<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'computer') {?>sm<?php } else { ?>xs<?php }?> btn-block btn-outline-default bg-white btn_checkin" onClick="$Core.course.checkin(this,event)" holderG="confirm" openFrom="_desktop" course_id="<?php echo $_smarty_tpl->tpl_vars['course_id']->value;?>
"><i class='bx bx-user-check'></i> Xác nhận</button>
													<?php } else { ?>
							<button type="button" class="btn btn-block btn-primary btn-<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'computer') {?>sm<?php } else { ?>xs<?php }?> btn_checkin<?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['is_checked_in'] == '1') {?> checked_in<?php }?>" onClick="$Core.course.checkin(this,event)" course_id="<?php echo $_smarty_tpl->tpl_vars['course_id']->value;?>
" holderG="cancel" openFrom="_desktop" staff_id="<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
"><i class='bx bx-user-check'></i> Đã xác nhận</button>
													<?php }?>
					<?php } else { ?>
						<button type="button" class="btn btn-sm btn-primary btn_checkin <?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['have_cancel'] == 1) {?>have_cancel<?php }?> checked" onClick="$Core.course.checkin(this,event)" course_id="<?php echo $_smarty_tpl->tpl_vars['course_id']->value;?>
" staff_id="<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
">Đã tham gia</button>	
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['isCheckIn'] == '1') {
} else { ?>
						<?php if (!$_smarty_tpl->tpl_vars['oneEvent']->value['check_overTime']) {?>
							<!-- <button type="button" class="btn btn-sm btn-outline-default btn_checkin <?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['have_cancel'] == 1) {?>have_cancel<?php }?>" onClick="$Core.course.checkin(this,event)" course_id="<?php echo $_smarty_tpl->tpl_vars['oneEvent']->value['course_id'];?>
" staff_id="<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
">Tham gia</button> -->
						<?php }?>
					<?php }?>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>
<?php } else { ?>
	<div class="text-center p-2">
		<div class="mb-2">
			<img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageWidth('LogoWhite');?>
" height="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageHeight('LogoWhite');?>
" alt="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['CompanyName'];?>
" />
		</div>
		<p class="text-white mb-0"><strong>Hiện chưa có sự kiện & đào tạo!</strong><br />
		<?php echo @constant('BRAND_NAME');?>
 đang chuẩn bị những nội dung giá trị dành cho bạn.</p>
	</div>
<?php }
}
}
