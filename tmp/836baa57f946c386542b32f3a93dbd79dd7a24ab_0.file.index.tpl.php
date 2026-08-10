<?php
/* Smarty version 3.1.33, created on 2026-08-07 15:18:20
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/home_mobile/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7594ccdb9b36_59287114',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '836baa57f946c386542b32f3a93dbd79dd7a24ab' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/home_mobile/index.tpl',
      1 => 1786073106,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7594ccdb9b36_59287114 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="body_mobile overflow-hidden">
	<div class="mh-hero">
		<div class="mh-hero__ava dropdown">
			<a class="nav-link dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown">
				<div class="avatar avatar-online rounded-circle overflow-hidden">
					<img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value,60,60);?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/avatars/1.png'" 
						class="rounded-circle" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oneProfile']->value['full_name'], ENT_QUOTES, 'UTF-8', true);?>
" />
				</div>
			</a>
			<div class="dropdown-menu dropdown-menu-end overflow-hidden w-px-300 py-0">
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('menu_profile');?>

			</div>
		</div>
		<div class="mh-hero__hi">Xin chào,</div>
		<h1 class="mh-hero__name"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oneProfile']->value['full_name'], ENT_QUOTES, 'UTF-8', true);?>
 👑</h1>
		<span class="mh-badge">💎 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneProfile']->value['role_id']), ENT_QUOTES, 'UTF-8', true);?>
</span>
		<div class="mh-pills">
			<span class="mh-pill" data-bs-toggle="modal" data-bs-target="#online-modal" ><span class="g"></span> <span class="total_online">0</span> online</span>
			<span class="mh-pill"><i class="bx bx-buildings"></i> <?php echo $_smarty_tpl->tpl_vars['header_configs']->value['CompanyName'];?>
</span>
		</div>
	</div>
	<div class="mh-sheet">
		<div class="mh-stats">
			<div class="mh-stat">
				<div class="mh-stat__ic mh-ic-blue"><i class="bx bx-check-square"></i></div>
				<div class="mh-stat__num"><?php echo $_smarty_tpl->tpl_vars['mh_checkin']->value;?>
</div>
				<div class="mh-stat__lbl">Check-ins</div>
				<div class="mh-stat__sub">Hôm nay</div>
				<div class="mh-stat__delta"><i class="bx bx-up-arrow-alt"></i>12%</div>
			</div>
			<div class="mh-stat">
				<div class="mh-stat__ic mh-ic-green"><i class="bx bx-user-plus"></i></div>
				<div class="mh-stat__num"><?php echo $_smarty_tpl->tpl_vars['mh_new_staff']->value;?>
</div>
				<div class="mh-stat__lbl">Nhân sự mới</div>
				<div class="mh-stat__sub">Tháng này</div>
				<div class="mh-stat__delta"><i class="bx bx-up-arrow-alt"></i>8%</div>
			</div>
			<div class="mh-stat">
				<div class="mh-stat__ic mh-ic-orange"><i class="bx bx-transfer-alt"></i></div>
				<div class="mh-stat__num"><?php echo $_smarty_tpl->tpl_vars['mh_deal_closed']->value;?>
</div>
				<div class="mh-stat__lbl">Giao dịch chốt</div>
				<div class="mh-stat__sub">Tháng này</div>
				<div class="mh-stat__delta"><i class="bx bx-up-arrow-alt"></i>20%</div>
			</div>
		</div>
		<div class="mh-sec">
			<h2 class="text-upper">Truy cập nhanh</h2>
			<a onClick="$Core.mobile.open_config_menu(this,event)" data-for="mb">Tùy chỉnh <i class="bx bx-slider-alt"></i></a>
		</div>
		<div class="list_menu_grid position-relative" id="list_menu_active">
			<div class="form-row row-cols-4">
				<div class="col mb-3 item_menu_grid">
					<a class="text-dark text-center d-block" href="/tool.html">
						<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/loading.gif" width="30" height="30" />
						</span>
						<span class="text-dark fw-semibold fs-12">Đang tải..</span>
					</a>
				</div>
				<div class="col mb-3 item_menu_grid">
					<a class="text-dark text-center d-block" href="/tool.html">
						<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/loading.gif" width="30" height="30" />
						</span>
						<span class="text-dark fw-semibold fs-12">Đang tải..</span>
					</a>
				</div>
				<div class="col mb-3 item_menu_grid">
					<a class="text-dark text-center d-block" href="/tool.html">
						<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/loading.gif" width="30" height="30" />
						</span>
						<span class="text-dark fw-semibold fs-12">Đang tải..</span>
					</a>
				</div>
				<div class="col mb-3 item_menu_grid">
					<a class="text-dark text-center d-block" href="/tool.html">
						<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/loading.gif" width="30" height="30" />
						</span>
						<span class="text-dark fw-semibold fs-12">Đang tải..</span>
					</a>
				</div>
			</div>
			<button type="button" data-toggle="ripple" class="btn btn-icon position-absolute btn_menu_more rounded-pill card hide" 
				onclick="$Core.mobile.view_menu(this,event)"><i class="bx bxs-chevrons-down"></i></button>
		</div>
		<a class="mh-milestone" href="/m-<?php echo smarty_modifier_date_format(time(),'%Y');?>
/" title="Dấu ấn vinh quang">
			<div class="mh-milestone__tr">🏆</div>
			<div class="mh-milestone__b">
				<div class="mh-milestone__tag">MILESTONE <?php echo smarty_modifier_date_format(time(),"%Y");?>
</div>
				<div class="mh-milestone__t">DẤU ẤN VINH QUANG</div>
				<div class="mh-milestone__s">Cùng nhau chinh phục những cột mốc mới!</div>
			</div>
			<i class="bx bx-chevron-right mh-milestone__go"></i>
		</a>
		<?php if (!empty($_smarty_tpl->tpl_vars['list_news']->value) || !empty($_smarty_tpl->tpl_vars['list_events']->value)) {?>
		<div class="box_slide_option mt-3">
			<div class="owl-carousel owl_slide_option">
				<?php if (!empty($_smarty_tpl->tpl_vars['list_news']->value)) {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_news']->value, '_oNews');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oNews']->value) {
?>
					<a onClick="open_news(this, event)" action="_detail" news_id="<?php echo $_smarty_tpl->tpl_vars['_oNews']->value['news_id'];?>
" class="item_option item_news cursor-pointer d-flex gap-2 align-items-center rounded-2 p-3 h-100" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oNews']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" style="background:#ba8d34">
						<div class="img_news">
							<img class="object-fit-cover rounded-1" src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->resize_image_url($_smarty_tpl->tpl_vars['_oNews']->value['image'],80,80);?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oNews']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" width="80" height="80" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'" >
						</div>
						<div class="content_news">
							<h3 class="title_news lh-xs fs-14 mb-1 limit_2line text-white text-upper"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oNews']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</h3>
							<div class="d-flex align-items-center flex-wrap">
								<span class="fs-11 text-white me-2 mb-1 text-nowrap"><i class="bx bx-folder-open fs-14 me-1"></i><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oNews']->value['cat_name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
								<span class="fs-11 text-white me-2 mb-1"><i class="bx bx-timer fs-14 me-1"></i><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getTimeAgo($_smarty_tpl->tpl_vars['_oNews']->value['reg_date']);?>
</span>
							</div>
						</div>
					</a>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php }?>
				<?php if (!empty($_smarty_tpl->tpl_vars['list_events']->value)) {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_events']->value, 'oneEvent', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['oneEvent']->value) {
?>
					<?php $_smarty_tpl->_assignInScope('course_id', $_smarty_tpl->tpl_vars['oneEvent']->value['course_id']);?>
					<div class="cursor-pointer text-white pr-1 p-2 rounded-2 bg-main h-100">
						<div class="form-row row">
							<div class="col-xxl-9 col-md-8 l-event">
								<div class="d-flex flex-column">
									<div class="d-flex justify-content-between align-items-center">
										<span class="py-1 fs-13 fst-italic">-- <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oneEvent']->value['cat_name'], ENT_QUOTES, 'UTF-8', true);?>
 --- <?php echo $_smarty_tpl->tpl_vars['oneEvent']->value['status'];?>
</span>
										<span class="fs-10 fst-italic"><?php echo $_smarty_tpl->tpl_vars['oneEvent']->value['total_joined'];?>
 người tham gia</span>
									</div>
									<a onClick="$Core.course.open(this, event)" course_id="<?php echo $_smarty_tpl->tpl_vars['course_id']->value;?>
" href="javascript:void(0)" 
										class="text-white limit_1line fw-bold <?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['cat_id'] == @constant('_MEDIA_DISSEMINATION') && empty($_smarty_tpl->tpl_vars['oneEvent']->value['is_joined'])) {?>item_media_dissemination<?php }?>"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oneEvent']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a>
									<span class="time fs-12 text-white">
										<i class="material-icons-outlined">schedule</i>
										<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['oneEvent']->value['start_date'],4);?>
 - <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['oneEvent']->value['due_date'],4);?>

									</span>
									<?php if ($_smarty_tpl->tpl_vars['oneEvent']->value['location']) {?>
									<p class="limit_1line text-white mb-0 fs-12">
										<i class="material-icons-outlined">location_on</i>
										<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oneEvent']->value['location'], ENT_QUOTES, 'UTF-8', true);?>

									</p>
									<?php }?>
								</div>
							</div>
						</div>
					</div>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php }?>
			</div>
		</div>
		<?php }?>
	</div>
</div>

<style>
	.header-mobile,
	.body_mobile {
		padding-top: env(safe-area-inset-top) !important;
	}
</style>
<?php echo '<script'; ?>
>
	$Core.mobile.loadMenu();
	if($(".owl_slide_option").length > 0) {
		$(".owl_slide_option").owlCarousel({
			center: true,
			items:1.5,
			loop:true,
			margin:10,
		});
	}
<?php echo '</script'; ?>
>
<?php }
}
