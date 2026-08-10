<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:20:29
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/share.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76926d2bdc13_47116239',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cf76042a37aadcc6147ff2a0ecdd62c3d6001697' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/share.tpl',
      1 => 1784299651,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76926d2bdc13_47116239 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">

	<div class="row">

		<div class="col-12 col-xxl-10 mb-2 offset-xxl-1">

			<?php if ($_smarty_tpl->tpl_vars['share_type']->value != 'secret') {?>

			<h4 class="fw-bold mb-1"><span><?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</span></h4>

			<p class="text-muted mb-0">Ghi nhận những hoạt động nổi bật tại <?php echo @constant('BRAND_NAME');?>
</p>

			<?php } else { ?>

			<h4 class="fw-bold mb-1"><span><?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</span></h4>

			<p class="text-muted mb-0">Ghi nhận những hoạt động nổi bật tại <?php echo @constant('BRAND_NAME');?>
</p>

			<?php }?>

		</div>

	</div>

	<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone' && $_smarty_tpl->tpl_vars['clsISO']->value->checkSale()) {?>

		<div id="holder_report_chart" class="mb-2">

			<div class="card">

				<div class="card-header d-flex align-items-center gap-2">

					<i class='bx bxs-check-circle text-success fs-30' ></i>

					<div class="d-flex flex-wrap align-items-end gap-1">

						<h3 class="card-title mb-0">Hoạt động của bạn</h3>

					</div>

				</div>

				<div class="card-body">

					<div class="card p-2 mb-3">

						<div class="form-row mb-2" style="row-gap: 10px">

							<div class="col-6">

								<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">

									<i class='bx bxs-group text-info'></i>

									<span class="">Lượt tiếp khách</span>

									<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

								</div>

							</div>

							<div class="col-6">

								<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">

									<i class='bx bxs-group text-success'></i>

									<span class="">Tổng khách</span>

									<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

								</div>

							</div>

							<div class="col-6">

								<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">

									<i class='bx bxs-map text-success ' ></i>

									<span class="">Sale có hoạt động</span>

									<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

								</div>

							</div>

							<div class="col-6">

								<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">

									<i class='bx bx-building text-info' ></i>

									<span class="">Phòng KD hoạt động</span>

									<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

								</div>

							</div>

						</div>

						<div class="alert alert-success py-2 mb-0"><i class='bx bx-trending-up'></i> <div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></div>

					</div>



					<div class="box_progess">

						<h3 class="">Phòng kinh doanh</h3>

						<div class="mb-3">

							<div class="d-flex gap-2 align-items-center">

								<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

								<div class="progress w-70" style="height: 15px;">

								  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>

								</div>

								<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

							</div>

							<div class="d-flex gap-2 align-items-center">

								<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

								<div class="progress w-70" style="height: 15px;">

								  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>

								</div>

								<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

							</div>

							<div class="d-flex gap-2 align-items-center">

								<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

								<div class="progress w-70" style="height: 15px;">

								  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>

								</div>

								<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

							</div>

						</div>

						<div class="alert alert-warning py-2 mb-0 text-main"><i class='bx bx-info-circle' ></i> 18 sale chưa tiếp khách</div>

					</div>

				</div>

			</div>

		</div>

	<?php }?>

	<div class="row">

		<div class="col-12 col-lg-8 col-xxl-7 offset-xxl-1 mb-2 mb-lg-0">

			<div class="awe__post-page awe__post-page_<?php echo $_smarty_tpl->tpl_vars['share_type']->value;?>
">

				<div class="awe__post-form bg-white radius-4 mb-3">

					<div class="d-flex align-items-center w-100">

						<div class="awe__post-avatar position-relative">

							<?php if ($_smarty_tpl->tpl_vars['loggedIn']->value == '1') {?>

							<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value);?>
" width="44" height="44" />

							<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->get_icon_verified($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value['more_information']);?>


							<?php } else { ?>

							<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" width="44" height="44" />

							<?php }?>

						</div>

						<div class="textarea">

							<a class="awe__post-input-link cursor-pointer" data-toggle="ripple" onClick="$Core.share.open(this, event)" share_type="<?php echo $_smarty_tpl->tpl_vars['share_type']->value;?>
" share_id="0" action="_add"><?php if ($_smarty_tpl->tpl_vars['share_type']->value == 'share') {?>Tạo mới nét đẹp lao động<?php } elseif ($_smarty_tpl->tpl_vars['share_type']->value == 'secret') {?>Tạo mới thông tin mật <?php } else { ?>Tạo mới vinh danh chiến binh FH<?php }?></a>

						</div>

					</div>

				</div>

				<div class="clearfix"></div>

				<?php if (!empty($_smarty_tpl->tpl_vars['list_shares']->value)) {?>

				<div class="awe__list-post awe__list-share">

					<?php if ($_smarty_tpl->tpl_vars['share_type']->value == 'share') {?>

						<div class="form-row">

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_shares']->value, '_oShare', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oShare']->value) {
?>

								<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('share_item',array('_oShare'=>$_smarty_tpl->tpl_vars['_oShare']->value));?>


							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?> 

						</div>

					<?php } else { ?>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_shares']->value, '_oShare', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oShare']->value) {
?>

							<?php if ($_smarty_tpl->tpl_vars['share_type']->value == 'share') {?>

								<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('share_item',array('_oShare'=>$_smarty_tpl->tpl_vars['_oShare']->value));?>


							<?php } else { ?>

								<?php $_smarty_tpl->_assignInScope('share_id', $_smarty_tpl->tpl_vars['_oShare']->value['share_id']);?>

								<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oShare']->value['more_information']);?>

								<?php $_smarty_tpl->_assignInScope('_title', $_smarty_tpl->tpl_vars['clsShare']->value->getTitle($_smarty_tpl->tpl_vars['share_id']->value,$_smarty_tpl->tpl_vars['_oShare']->value));?>

								<div class="awe__post-item awe__share-item" reg_date="<?php echo $_smarty_tpl->tpl_vars['_oShare']->value['reg_date'];?>
" id="post_item_<?php echo $_smarty_tpl->tpl_vars['share_id']->value;?>
">

									<div class="w-100 d-flex align-items-center justify-content-between mb-3 post_header">

										<div class="awe__post-profile d-flex" data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['_oShare']->value['user_id'];?>
" data-toggle="webui-popover" data-trigger="hover" data-width="300">

											<div class="awe__post-avatar position-relative">

												<img class="rounded-pill" width="44" height="44" src="<?php echo $_smarty_tpl->tpl_vars['_oShare']->value['db_profile']['avatar'];?>
" 

												onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" /> 

												<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->get_icon_verified($_smarty_tpl->tpl_vars['_oShare']->value['user_id'],$_smarty_tpl->tpl_vars['_oShare']->value['db_profile']['more_information']);?>


											</div>

											<div class="awe__post-profile-body">

												<p class="awe__post-name">[<?php echo $_smarty_tpl->tpl_vars['_oShare']->value['db_profile']['department_name'];?>
]<?php echo $_smarty_tpl->tpl_vars['_oShare']->value['db_profile']['name'];?>
</p>

												<div class="d-flex align-items-center">

													<?php if (!empty($_smarty_tpl->tpl_vars['_oShare']->value['db_profile']['level'])) {?>

													<span class="awe__post-level mr-2 text-muted"><?php echo $_smarty_tpl->tpl_vars['_oShare']->value['db_profile']['level'];?>
</span>

													<?php }?>

													<span class="awe__post-star mr-2 text-muted"><?php echo $_smarty_tpl->tpl_vars['_oShare']->value['db_profile']['html_star'];?>
</span>

													<span class="awe__post-time text-muted"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getTimeAgo($_smarty_tpl->tpl_vars['_oShare']->value['reg_date']);?>
</span>

												</div>

											</div>

										</div>

										<div class="dropdown">

											<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">

												<i class="bx bx-dots-vertical-rounded"></i>

											</button>

											<div class="dropdown-menu dropdown-menu-end w-px-100" data-popper-placement="bottom-end">

												<?php if ($_smarty_tpl->tpl_vars['_oShare']->value['user_id'] == $_smarty_tpl->tpl_vars['profile_id']->value && $_smarty_tpl->tpl_vars['clsShare']->value->checkCanEdit($_smarty_tpl->tpl_vars['share_id']->value,$_smarty_tpl->tpl_vars['_oShare']->value) == '1' || $_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>

												<a class="dropdown-item cursor-pointer" onClick="$Core.global.share.open(this, event)" share_id="<?php echo $_smarty_tpl->tpl_vars['share_id']->value;?>
" share_type="<?php echo $_smarty_tpl->tpl_vars['_oShare']->value['share_type'];?>
" action="_edit"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-edit-alt me-1','Sửa');?>
</a>

												<a class="dropdown-item cursor-pointer" onClick="$Core.share.delete_share(this, event)" share_type="<?php echo $_smarty_tpl->tpl_vars['_oShare']->value['share_type'];?>
" share_id="<?php echo $_smarty_tpl->tpl_vars['share_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-trash me-1','Xóa');?>
</a>

												<?php }?>

											</div>

										  </div>

									</div>

									<div class="awe__post-item-body">

										<?php if ($_smarty_tpl->tpl_vars['share_type']->value != 'secret') {?>

										<div class="awe__post-title mb-2 d-flex flex-column" share_id="<?php echo $_smarty_tpl->tpl_vars['share_id']->value;?>
" action="_detail">

											<a class="fs-16 font-normal awe__post-link flex-fill" title="<?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
</a>

											<?php if (!empty($_smarty_tpl->tpl_vars['_oShare']->value['lst_staff_id'])) {?>

											<div class="d-flex flex-wrap align-items-center">

												<span class="text-muted fw-normal fs-11">cùng với</span>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oShare']->value['lst_staff_id'], 'staff_id', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['staff_id']->value) {
?>

												<div class="awe__post-avatar position-relative ml-1 fs-6" data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['staff_id']->value;?>
" data-toggle="webui-popover" 

													data-trigger="hover" data-width="300">

													<img class="rounded-pill" width="20" height="20" 

													src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['staff_id']->value);?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" /> 

													<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['staff_id']->value);?>


												</div>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											</div>

											<?php }?>

										</div>

										<?php }?>

										<?php if ($_smarty_tpl->tpl_vars['share_type']->value == 'secret') {?>

										<div class="awe__post-meta mb-2 text-muted">

											<p class="mb-1">Từ ngày: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['_more_information']->value['start_date'],'%d/%m/%Y');?>
 - Tới ngày:  <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['_more_information']->value['end_date'],'%d/%m/%Y');?>
</p>

											<p class="mb-0">Dự án: <?php if (!empty($_smarty_tpl->tpl_vars['_more_information']->value['project_id'])) {?> 

												<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['project_name'];?>


											<?php } else { ?>--<?php }?></p>

										</div>

										<?php }?>

										<?php if (!empty($_smarty_tpl->tpl_vars['_more_information']->value['content'])) {?>

											<div class="awe__post-description mb-2" action="_detail">

												<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['content'];?>


											</div>

										<?php }?>

										<?php if (!empty($_smarty_tpl->tpl_vars['_oShare']->value['images'])) {?>

										<div class="awe__post-gallery gallery mb-2">

											<?php echo $_smarty_tpl->tpl_vars['clsShare']->value->getImageGrid($_smarty_tpl->tpl_vars['share_id']->value,$_smarty_tpl->tpl_vars['_oShare']->value['images']);?>


										</div>

										<?php }?> 

									</div>

									<div class="awe__post-cmd border-bottom mb-3">

										<div class="d-flex justify-content-center">

											<?php $_smarty_tpl->_assignInScope('total_liked', $_smarty_tpl->tpl_vars['_oShare']->value['total_liked']);?>

											<a href="javascript:void(0);" share_id="<?php echo $_smarty_tpl->tpl_vars['share_id']->value;?>
" class="awe__post-action awe__share-like-action">

												<?php if ($_smarty_tpl->tpl_vars['clsShare']->value->checkLiked($_smarty_tpl->tpl_vars['share_id']->value,$_smarty_tpl->tpl_vars['_oShare']->value)) {?>

													<?php if ($_smarty_tpl->tpl_vars['total_liked']->value > '0') {?>

														<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bxs-heart',($_smarty_tpl->tpl_vars['total_liked']->value).(' Thích'));?>


													<?php } else { ?>

														<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-heart','Thích');?>


													<?php }?>

												<?php } else { ?>

													<?php if ($_smarty_tpl->tpl_vars['total_liked']->value > '0') {?>

														<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-heart',($_smarty_tpl->tpl_vars['total_liked']->value).(' Thích'));?>


													<?php } else { ?>

														<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-heart','Thích');?>


													<?php }?>

												<?php }?>

											</a>

											<a href="javascript:void(0);" class="awe__post-action awe__post-comment-action" 

											data-toggle="modal" data-target="#sharer"><i class="bx bx-comment"></i> Bình luận</a>

											<a href="javascript:void(0);" class="awe__post-action awe__post-share-action" 

											data-toggle="modal" data-target="#sharer"><i class="bx bx-share"></i> Chia sẻ</a>

										</div>

									</div>

									<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('comment',array('table_id'=>$_smarty_tpl->tpl_vars['share_id']->value,'clsTable'=>'Share'));?>


									

									<?php echo '<script'; ?>
 type="text/javascript">

										$(function(){

											$Core.news.load_comments(<?php echo $_smarty_tpl->tpl_vars['share_id']->value;?>
,'Share',{'action':'reload'});

										});

									<?php echo '</script'; ?>
>

									

								</div>

							<?php }?>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?> 

					<?php }?>

					

				</div>

				<?php } else { ?>

					<div class="p-6 bg- no-result text-center">

						<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/empty.svg?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" width="100px" />

						<p class="text-muted mt-3">Không có ghi chú nào được tạo</p>

					</div>

				<?php }?>

				<div class="clearfix"></div>

				<?php if ($_smarty_tpl->tpl_vars['total_record']->value > $_smarty_tpl->tpl_vars['per_page']->value) {?>

				<div class="d-flex justify-content-center">

					<button data-toggle="ripple" onClick="$Core.share.load_more(this, event)" share_type="<?php echo $_smarty_tpl->tpl_vars['share_type']->value;?>
" total_loaded="<?php echo $_smarty_tpl->tpl_vars['per_page']->value;?>
" 

						class="btn btn-block btn-lg btn-link bg-white font-weight-bold" total_record="<?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
">Xem thêm</button>

				</div>

				<?php }?>

		   </div>

		</div>

		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

		<div class="col-12 col-xxl-3 col-md-4">

			<div class="sticky d-none d-lg-block">

				<div class="card mb-2">

					<div class="card-body">

						<div class="box_standard mb-2" data-fancybox="standard" data-src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/quy-chuan-tiep-khach.png">

						<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/quy-chuan-tiep-khach.png" alt="" class="w-100 h-auto">

					</div>

					</div>

				</div>

				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkSale()) {?>

					<div id="holder_report_chart" class="mb-2">

						<div class="card">

							<div class="card-header d-flex align-items-center gap-2">

								<i class='bx bxs-check-circle text-success fs-30' ></i>

								<div class="d-flex flex-wrap align-items-end gap-1">

									<h3 class="card-title mb-0">Hoạt động của bạn</h3>

								</div>

							</div>

							<div class="card-body">

								<div class="card p-2 mb-3">

									<div class="form-row mb-2" style="row-gap: 10px">

										<div class="col-6">

											<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">

												<i class='bx bxs-group text-info'></i>

												<span class="">Lượt tiếp khách</span>

												<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

											</div>

										</div>

										<div class="col-6">

											<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">

												<i class='bx bxs-group text-success'></i>

												<span class="">Tổng khách</span>

												<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

											</div>

										</div>

										<div class="col-6">

											<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">

												<i class='bx bxs-map text-success ' ></i>

												<span class="">Sale có hoạt động</span>

												<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

											</div>

										</div>

										<div class="col-6">

											<div class="bg-lighter px-3 py-2 rounded-1 fs-14 h-100">

												<i class='bx bx-building text-info' ></i>

												<span class="">Phòng KD hoạt động</span>

												<span class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

											</div>

										</div>

									</div>

									<div class="alert alert-success py-2 mb-0"><i class='bx bx-trending-up'></i> <div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></div>

								</div>



								<div class="box_progess">

									<h3 class=""><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></h3>

									<div class="mb-3">

										<div class="d-flex gap-2 align-items-center">

											<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

											<div class="progress w-70" style="height: 15px;">

											  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>

											</div>

											<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

										</div>

										<div class="d-flex gap-2 align-items-center">

											<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

											<div class="progress w-70" style="height: 15px;">

											  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>

											</div>

											<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

										</div>

										<div class="d-flex gap-2 align-items-center">

											<span class="fs-16 fw-semibold w-15" style="max-width: 50px"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

											<div class="progress w-70" style="height: 15px;">

											  <div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>

											</div>

											<span class="fs-14"><div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></span>

										</div>

									</div>

									<div class="alert alert-warning py-2 mb-0 text-main"><i class='bx bx-info-circle' ></i> <div class="animate-bg w-px-10 h-px-15 mb-2 rounded-2"></div></div>

								</div>

							</div>

						</div>

					</div>				

				<?php }?>

				

				<div id="holder_report_top_share" class="mb-2">

					<div class="card h-100 <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'news') {?> no-shadow<?php }
echo $_smarty_tpl->tpl_vars['class']->value;?>
">

						<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

						<div class="card-header d-flex align-items-center justify-content-between">

							<h5 class="card-title m-0 me-2">Top 10 sale tiếp khách nhiều nhất</h5>

							<a><i class="bx bx-help-circle"></i></a>

						</div>

						<div class="card-body">

							<ul class="p-0 m-0">

								<?php
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if (true) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= 10; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

								<li class="d-flex mb-3 pb-1">

									<div class="avatar flex-shrink-0 me-2">

										<div class="animate-bg w-100 h-100 rounded"></div>

									</div>

									<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100">

										<div class="me-2">

											<div class="animate-bg radius-2 w-50 mb-1" style="height:10px">FH000</div>

											<div class="animate-bg radius-2 w-100" style="height:15px"><?php echo $_smarty_tpl->tpl_vars['oneProfile']->value['full_name'];?>
</div>

										</div>

										<div class="user-progress d-flex align-items-center gap-1">

											<h6 class="d-flex align-items-center mb-0">

												<span class="animate-bg mr-2" style="height:15px">00</span> tỷ

											</h6>

										</div>

									</div>

								</li>

								<?php
}
}
?>

							</ul>

						</div>

					</div>						

				</div>

				
			</div>

		</div>

		<?php }?>

	</div>

</div>

<?php echo $_smarty_tpl->tpl_vars['scriptJs']->value;?>




<style type="text/css">

	.awe__post-form,

	.awe__post-item{

		box-shadow:0px 0px 6px rgb(173 168 168 / 20%);

		-moz-box-shadow:0px 0px 6px rgb(173 168 168 / 20%);

		-webkit-box-shadow:0px 0px 6px rgb(173 168 168 / 20%);

		-khtml-box-shadow:0px 0px 6px rgb(173 168 168 / 20%);

	}

	.selecttize-lg .selectize-dropdown, 

	.selecttize-lg .selectize-input, 

	.selecttize-lg .selectize-input input{

		line-height:36px !important;

	}

	@media screen and (min-width:1200px){

		.sticky{ top:86px;}

	}

	.imgs-grid .imgs-grid-image .image-wrap img{

		max-height:400px;

	}

	.post_item .imgs-grid .imgs-grid-image .image-wrap img {

		max-height: 250px;

		height: 250px;

		width: 100%;

		object-fit: cover;

	}

	.item_detail .imgs-grid .imgs-grid-image .image-wrap img {

		max-height: 370px;

		width: 100%;

		object-fit: cover;

	}

</style>

<?php }
}
