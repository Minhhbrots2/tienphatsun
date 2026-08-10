<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:20:31
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.share_report.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76926f4a9108_25151067',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ffb25500eaa03027f33440aef1b5c199d30e3966' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.share_report.tpl',
      1 => 1784299673,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76926f4a9108_25151067 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="card <?php if ($_smarty_tpl->tpl_vars['show']->value == 'report_department') {?>h-100 no-shadow<?php }?>">

	<?php if (!empty($_smarty_tpl->tpl_vars['is_sale']->value)) {?>

	<div class="card-header">

		<div class="d-flex align-items-start gap-2">

			<i class='bx bxs-check-circle text-success text-fs-26 mt-1'></i>

			<div class="d-flex flex-column flex-wrap gap-1">

				<h3 class="card-title mb-0">Hoạt động của bạn</h3>

				<?php if ($_smarty_tpl->tpl_vars['show']->value == 'home_share') {?><span class="text-muted fs-12">(7 ngày gần nhất)</span><?php }?>

			</div>

		</div>

	</div>

	<?php } else { ?>

	<div class="card-header">

		<div class="d-flex align-items-start gap-2">

			<i class='bx bxs-check-circle text-success text-fs-26 mt-1'></i>

			<div class="d-flex flex-column flex-wrap gap-1">

				<h3 class="card-title mb-0">Hoạt động tiếp khách<?php if (!empty($_smarty_tpl->tpl_vars['oneDep']->value)) {?>- <?php if (!empty($_smarty_tpl->tpl_vars['oneDep']->value['is_not_area'])) {?>PKD <?php }
echo $_smarty_tpl->tpl_vars['oneDep']->value['title'];
}?></h3>

				<span class="text-muted text-fs-12"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['start_time']->value);?>
 tới <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['end_time']->value);?>
</span>

			</div>

		</div>

	</div>

	<?php }?>

	<div class="card-body">

		<div class="card p-2 mb-3">

			<div class="form-row mb-2" style="row-gap: 10px">

				<div class="col-6 flex-fill">

					<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">

						<div class="fs-12">

							<i class='bx bxs-group text-info fs-24'></i>

							<span class="">Tiếp khách</span>

						</div>

						<span class="fs-12"><strong class="fs-16"><?php echo $_smarty_tpl->tpl_vars['total_shares']->value;?>
</strong> lượt</span>

					</div>

				</div>

				<div class="col-6 flex-fill">

					<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">

						<div class="fs-12">

							<i class='bx bxs-group text-success fs-24'></i>

							<span class="">Tổng khách</span>

						</div>

						<span class="fs-12"><strong class="fs-16"><?php echo $_smarty_tpl->tpl_vars['total_guest_count']->value;?>
</strong></span>

					</div>

				</div>

				<?php if (empty($_smarty_tpl->tpl_vars['is_sale']->value)) {?>

					<div class="col-6 flex-fill">

						<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap cursor-pointer" onClick="$Core.global.share.load_share_waiting(this,event)" is_confirm="0" show="<?php echo $_smarty_tpl->tpl_vars['show']->value;?>
">

							<div class="fs-12">

								<i class='bx bx-sad text-warning fs-24'></i>

								<span class="">Chờ duyệt</span>

							</div>

							<span class="fs-12"><strong class="fs-16"><?php echo $_smarty_tpl->tpl_vars['total_share_waiting']->value;?>
</strong> lượt</span>

						</div>

					</div>

					<div class="col-6 flex-fill">

						<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">

							<div class="fs-12">

								<i class='bx bxs-map text-success fs-24 ' ></i>

								<span class="">Sale h.động</span>

							</div>

							<span class="fs-12"><strong class="fs-16"><?php echo $_smarty_tpl->tpl_vars['total_sale_active']->value;?>
</strong>/<?php echo $_smarty_tpl->tpl_vars['total_sale']->value;?>
</span>

						</div>

					</div>

					<?php if (!empty($_smarty_tpl->tpl_vars['oneDep']->value)) {?>

						<?php if (empty($_smarty_tpl->tpl_vars['oneDep']->value['is_not_area'])) {?>

							<div class="col-6 flex-fill">

								<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">

									<div class="fs-12">

										<i class='bx bx-building text-info fs-24' ></i>

										<span class="">Phòng KD</span>

									</div>

									<span class="fs-12"><strong class="fs-16"><?php echo $_smarty_tpl->tpl_vars['total_dep_active']->value;?>
</strong>/<?php echo $_smarty_tpl->tpl_vars['total_dep']->value;?>
</span>

								</div>

							</div>

						<?php }?>

					<?php } else { ?>

					<div class="col-6 flex-fill">

						<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">

							<div class="fs-12">

								<i class='bx bx-building text-info fs-24' ></i>

								<span class="">Vùng KD</span>

							</div>

							<span class="fs-12"><strong class="fs-16"><?php echo $_smarty_tpl->tpl_vars['total_area_active']->value;?>
/<?php echo count($_smarty_tpl->tpl_vars['lstDepartment']->value);?>
</strong></span>

						</div>

					</div>

					<?php }?>

				<?php }?>

			</div>

			<?php if (!empty($_smarty_tpl->tpl_vars['total_share_prev']->value)) {?>

				<?php if ($_smarty_tpl->tpl_vars['ratio']->value >= 0) {?>

					<div class="alert alert-success py-2 mb-0"><i class='bx bx-trending-up'></i> + <?php echo $_smarty_tpl->tpl_vars['ratio']->value;?>
% lượt tiếp khách so với tháng trước</div>

				<?php } elseif ($_smarty_tpl->tpl_vars['total_shares']->value == 0) {?>

					<div class="alert alert-warning py-2 mb-0"><i class='bx bx-meh-alt' ></i> Chưa có hoạt động tiếp khách nào trong tháng này </div>

				<?php } else { ?>

					<div class="alert alert-danger py-2 mb-0"><i class='bx bx-trending-down'></i> <?php echo $_smarty_tpl->tpl_vars['ratio']->value;?>
% lượt tiếp khách so với tháng trước</div>

				<?php }?>

			<?php } else { ?>

				<?php if ($_smarty_tpl->tpl_vars['ratio']->value == 0) {?>

					<div class="alert alert-warning py-2 mb-0"><i class='bx bx-meh-alt' ></i> Chưa có hoạt động tiếp khách nào trong tháng này </div>

				<?php } else { ?>

					<div class="alert alert-success py-2 mb-0"><i class='bx bx-trending-up'></i> + <?php echo $_smarty_tpl->tpl_vars['ratio']->value;?>
 lượt tiếp khách so với tháng trước</div>

				<?php }?>

			<?php }?>

			<?php if (!empty($_smarty_tpl->tpl_vars['is_sale']->value) && !empty($_smarty_tpl->tpl_vars['time_last']->value)) {?>

				<div class="py-2 mb-0 d-flex align-items-center gap-1"><i class='bx bx-time-five' ></i>Lần gần nhất: <strong><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['time_last']->value,3);?>
</strong></div>

			<?php }?>

		</div>		

		<?php if (!empty($_smarty_tpl->tpl_vars['oneDep']->value)) {?>

			<?php if (empty($_smarty_tpl->tpl_vars['oneDep']->value['is_not_area'])) {?>

				<?php $_smarty_tpl->_assignInScope('department_child', $_smarty_tpl->tpl_vars['oneDep']->value['department_child']);?>

				<div class="box_progess">

					<div class="d-flex justify-content-between align-items-center mb-2">

						<h3 class="mb-0">Phòng kinh doanh</h3>

						<?php if ($_smarty_tpl->tpl_vars['show']->value == 'home_share') {?>

							<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('report_share');?>
" class="btn btn-info btn-sm fs-10 d-flex align-items-center">Xem chi tiết<i class='bx bx-chevron-right fs-14'></i></a>

						<?php }?>

					</div>

					<div class="mb-3">

						<?php $_smarty_tpl->_assignInScope('rate', $_smarty_tpl->tpl_vars['clsISO']->value->getRateNumber($_smarty_tpl->tpl_vars['oneDep']->value['total_share_dep'],($_smarty_tpl->tpl_vars['oneDep']->value['total_sale_dep']*8)));?>

						<div class="d-flex gap-2 align-items-center">

							<span class="fs-14 text-right w-px-75 text-nowrap">PKD</span>

							<div class="d-flex flex-column" style="width:calc(100% - 150px)">

								<div class="progress w-100" style="height:12px;">

								  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" 

									style="width:<?php echo $_smarty_tpl->tpl_vars['oneDep']->value['total_share_dep']*100/$_smarty_tpl->tpl_vars['oneDep']->value['total_sale_dep'];?>
%;"></div>

								</div>

							</div>

							<span class="fs-12 text-nowrap w-px-75 text-right">

							<strong class="text-fs-18 text-main"><?php echo $_smarty_tpl->tpl_vars['oneDep']->value['total_share_dep'];?>
</strong>/<?php echo ($_smarty_tpl->tpl_vars['oneDep']->value['total_sale_dep']*8);?>
 <?php if (!empty($_smarty_tpl->tpl_vars['rate']->value)) {?><small class="text-success text-fs-13">(<?php echo $_smarty_tpl->tpl_vars['rate']->value;?>
%)</small><?php }?></span>

						</div>

						<?php if (!empty($_smarty_tpl->tpl_vars['oneDep']->value['department_child'])) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['oneDep']->value['department_child'], '_oDepChild', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oDepChild']->value) {
?>

								<?php $_smarty_tpl->_assignInScope('rate', $_smarty_tpl->tpl_vars['clsISO']->value->getRateNumber($_smarty_tpl->tpl_vars['_oDepChild']->value['total_share_dep'],($_smarty_tpl->tpl_vars['_oDepChild']->value['total_sale_dep']*8)));?>

								<div class="d-flex gap-2 align-items-center">

									<span class="fs-14 text-right w-px-75 text-nowrap">PKD <?php echo $_smarty_tpl->tpl_vars['_oDepChild']->value['title'];?>
</span>

									<div class="d-flex flex-column" style="width:calc(100% - 150px)">

										<div class="progress w-100" style="height:12px;">

										  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" 

											style="width:<?php echo $_smarty_tpl->tpl_vars['_oDepChild']->value['total_share_dep']*100/($_smarty_tpl->tpl_vars['_oDepChild']->value['total_sale_dep']*8);?>
%;"></div>

										</div>

									</div>

									<span class="fs-12 text-nowrap w-px-75 text-right">

									<strong class="text-fs-18 text-main"><?php echo $_smarty_tpl->tpl_vars['_oDepChild']->value['total_share_dep'];?>
</strong>/<?php echo ($_smarty_tpl->tpl_vars['_oDepChild']->value['total_sale_dep']*8);?>
 <?php if (!empty($_smarty_tpl->tpl_vars['rate']->value)) {?><small class="text-success">(<?php echo $_smarty_tpl->tpl_vars['rate']->value;?>
%)</small><?php }?></span>

								</div>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php }?>

					</div>

					<div class="alert alert-warning py-2 mb-0 text-main d-flex align-items-center gap-1"><i class='bx bx-info-circle' ></i> <?php echo $_smarty_tpl->tpl_vars['total_sale']->value-$_smarty_tpl->tpl_vars['total_sale_active']->value;?>
 sale chưa tiếp khách</div>

				</div>

			<?php } elseif ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup("SALE_DIRECTOR")) {?>

				<?php if (!empty($_smarty_tpl->tpl_vars['arr_top_share']->value)) {?>

					<div class="box_progess">

						<div class="d-flex justify-content-between align-items-center mb-2">

							<h3 class="mb-0">Top hoạt động</h3>

							<?php if ($_smarty_tpl->tpl_vars['show']->value == 'home_share') {?>

								<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('report_share');?>
" class="btn btn-info btn-sm fs-10 d-flex align-items-center">Xem chi tiết<i class='bx bx-chevron-right fs-14'></i></a>

							<?php }?>

						</div>

						<div class="mb-3">

							<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['arr_top_share']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 3);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

								<div class="d-flex gap-2 align-items-center top_item">

									<span class="icon_top_share"></span>

									<span class=""><?php echo $_smarty_tpl->tpl_vars['arr_top_share']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['full_name'];?>
</span>

									<span class="total_share_item d-flex align-items-center gap-2"><?php echo $_smarty_tpl->tpl_vars['arr_top_share']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['total'];?>
 lượt</span>

								</div>

							<?php
}
}
?>

						</div>

					</div>

				<?php }?>

				<div class="alert alert-warning py-2 mb-0 text-main d-flex align-items-center gap-1"><i class='bx bx-info-circle' ></i> <?php echo $_smarty_tpl->tpl_vars['total_sale']->value-$_smarty_tpl->tpl_vars['total_sale_active']->value;?>
 sale chưa tiếp khách</div>

			<?php }?>

		<?php } else { ?>

			<div class="box_progess mb-2">

				<h3 class="text-fs-18">Vùng kinh doanh</h3>

				<div class="mb-3">

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstDepartment']->value, '_oArea', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oArea']->value) {
?>

						<?php $_smarty_tpl->_assignInScope('rate', $_smarty_tpl->tpl_vars['clsISO']->value->getRateNumber($_smarty_tpl->tpl_vars['_oArea']->value['total_share_area'],($_smarty_tpl->tpl_vars['_oArea']->value['total_sale_area']*8)));?>

						<div class="card px-2 py-1 mb-2">

							<div class="d-flex gap-2 align-items-center w-100">

								<span class="fs-14 text-right w-px-75 text-nowrap"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->replace($_smarty_tpl->tpl_vars['_oArea']->value['title'],'kinh doanh','');?>
</span>

								<div class="d-flex flex-column" style="width:calc(100% - 175px)">

									<div class="progress w-100" style="height:12px;">

									  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" 

										style="width:<?php echo $_smarty_tpl->tpl_vars['_oArea']->value['total_share_area']*100/($_smarty_tpl->tpl_vars['_oArea']->value['total_sale_area']*8);?>
%;"></div>

									</div>

								</div>

								<span class="fs-12 text-nowrap w-px-100 text-right">

								<strong class="text-fs-18 text-main"><?php echo $_smarty_tpl->tpl_vars['_oArea']->value['total_share_area'];?>
</strong>/<?php echo ($_smarty_tpl->tpl_vars['_oArea']->value['total_sale_area']*8);?>
 <?php if (!empty($_smarty_tpl->tpl_vars['rate']->value)) {?><small class="text-success  text-fs-13">(<?php echo $_smarty_tpl->tpl_vars['rate']->value;?>
%)</small><?php }?></span>

							</div>

							<div class="d-flex gap-2 align-items-center justify-content-start">

								<span class="fs-14 text-right w-px-75 text-nowrap"></span><span class="fs-10">PKD: <strong class="text-main"><?php echo $_smarty_tpl->tpl_vars['_oArea']->value['total_share_dep'];?>
</strong></span>

								<?php if (!empty($_smarty_tpl->tpl_vars['_oArea']->value['department_child'])) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oArea']->value['department_child'], '_oDepChild', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oDepChild']->value) {
?>

										<span class="fs-10"><?php echo $_smarty_tpl->tpl_vars['_oDepChild']->value['title'];?>
: <strong class="text-main"><?php echo $_smarty_tpl->tpl_vars['_oDepChild']->value['total_share_dep'];?>
</strong></span>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php }?>

							</div>

						</div>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				</div>

			</div>

		<?php }?>

	</div>

</div><?php }
}
