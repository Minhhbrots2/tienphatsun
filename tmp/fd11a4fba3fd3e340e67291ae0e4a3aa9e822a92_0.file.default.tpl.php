<?php
/* Smarty version 3.1.33, created on 2026-07-31 11:38:13
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/training/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c26b5410a23_19760702',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fd11a4fba3fd3e340e67291ae0e4a3aa9e822a92' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/training/default.tpl',
      1 => 1784299680,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c26b5410a23_19760702 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">

<?php if ($_smarty_tpl->tpl_vars['view_training']->value == 'grid' || $_smarty_tpl->tpl_vars['deviceType']->value != "computer") {?>

	<div class="d-flex justify-content-between align-items-center mb-3">

		<div class="kYlZoryVmS">

			<h4 class="fw-bold mb-1">Trung tâm đào tạo </h4>

			<i class="text-muted">Có <span class="text-main fw-bold"><?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
</span> khóa học đào tạo tại <?php echo @constant('BRAND_NAME');?>
</i>

		</div>

		<div class="d-flex justify-content-end gap-2">					

			<form action="" method="post">

				<input type="hidden" name="submit" value="search">

				<div class="input-group">

					<div class="dropdown">

						<button type="button" class="btn btn-icon btn-outline-primary hide-arrow dropdown-toggle" 

						data-bs-toggle="dropdown" data-toggle="ripple" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">

							<i class="bx bx-search"></i>

						</button>

						<div class="dropdown-menu mega-dropdown-menu dropdown-menu-arrow dropdown-menu-end w-px-300" 

						data-popper-placement="bottom-end">

							<div class="p-3">

								<div class="form-row mb-2">

									<div class="col-12 mb-2">

										<div class="form-label mb-1">Từ khóa</div>

										<input type="text" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['key_search']->value;?>
" class="form-control no-focus" placeholder="Tìm kiếm" />

									</div>

									<div class="col-12">

										<div class="form-label mb-1">Danh mục</div>

										<select data-width="100%" data-placeholder="Danh mục" data-allow-clear="false" 

										name="cat_ids[]" class="iso-select2" multiple>

											<option value="0">Danh mục</option>

											<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_TRAINING_CAT',$_smarty_tpl->tpl_vars['cat_ids']->value,'Danh mục');?>


										</select>

									</div>

								</div>

								<hr class="my-2">

								<div class="form-group">

									<button type="submit" class="btn btn-primary">Tìm kiếm</button>

								</div>

							</div>

						</div> 

					</div>

				</div>

			</form>

			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

				<div class="input-group">

					<div class="border btn btn-icon">

						<input type="radio" name="view" value="grid" hidden id="view_grid" onchange="$Core.training.set_view(this,event)" <?php if ($_smarty_tpl->tpl_vars['view_training']->value == 'grid') {?>checked<?php }?>>

						<label class="btn_view cursor-pointer" for="view_grid"><i class='bx bxs-grid-alt' ></i></label>

					</div>

					<div class="border btn btn-icon">

						<input type="radio" name="view" value="list" hidden id="view_table" onchange="$Core.training.set_view(this,event)" <?php if ($_smarty_tpl->tpl_vars['view_training']->value == 'list') {?>checked<?php }?>>

						<label class="btn_view cursor-pointer" for="view_table"><i class='bx bx-list-ul fs-22' ></i></label>

					</div>

				</div>

			<?php }?>

		</div>

	</div>

	<div class="alert alert-warning fs-4 text-center">

		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

		<div class="d-flex flex-column justify-content-center lh-xs text-main">

			<div class="mb-2">

				<i class='bx bxs-quote-alt-left mt-n2'></i> 

				<i>Đầu tư vào tri thức luôn mang lại lợi nhuận cao nhất.</i>

				<i class='bx bxs-quote-alt-right'></i>

			</div>

			<span class="text-center text-muted text-fs-12">-- Benjamin Franklin --</span>

		</div>

		<?php } else { ?>

		<div class="d-flex justify-content-center text-main">

			<div class="d-inline-flex flex-column">

				<div class="d-flex gap-1 align-items-center">

					<i class='bx bxs-quote-alt-left mt-n2'></i> 

					<i>Đầu tư vào tri thức luôn mang lại lợi nhuận cao nhất.</i>

					<i class='bx bxs-quote-alt-right'></i>

				</div>

				<span class="text-right text-fs-12 text-muted">-- Benjamin Franklin</span>

			</div>

		</div>

		<?php }?>

	</div>

	<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-xxl-5">

		<?php if (!empty($_smarty_tpl->tpl_vars['lstTraining']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstTraining']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

			<div class="col mb-4">

				<div class="item_training h-100 card no-shadow overflow-hidden cursor-pointer" onclick="$Core.global.training.open(this,event)" training_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['training_id'];?>
">

					<div class="box_image image-scale">

						<img class="w-100 h-auto" src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->resize_image_url($_smarty_tpl->tpl_vars['_oItem']->value['image'],480,320);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
" width="340" height="225">

					</div>

					<div class="box_imfo">

						<div class="p-3">

							<h3 class="title_training mb-2 text-dark limit_1line"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</h3>

							<div class="author mb-2">bởi <strong><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['author'];?>
</strong></div>

							<div class="d-flex flex-wrap justify-content-between">

								<div class="mb-1 flex-fill">

									<i class='bx bx-video me-1' ></i><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_lesson'];?>
 bài học

								</div>	

								<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['time_training'])) {?>

								<div class="mb-1 time flex-fill">

									<i class='bx bx-time me-1' ></i><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeMinute($_smarty_tpl->tpl_vars['_oItem']->value['time_training']);?>


								</div>

								<?php }?>

								<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['cat_name'])) {?>

									<div class="mb-1 time flex-fill">

										<i class='bx bx-book-content me-1'></i><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['cat_name'];?>


									</div>

								<?php }?>

								<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['total_profile_learning'])) {?>

									<div class="mb-1 time w-auto flex-fill">

										<i class='bx bx-user me-1' ></i><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_profile_learning'];?>
 người đã học

									</div>

								<?php }?>

							</div>

						</div>

						<div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top gap-2">

							<div class=""><i class="material-icons-outlined me-1">visibility</i><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_view'];?>
 lượt xem</div>	

							
							<div class="done_ratio_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['training_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsTraining']->value->getProgress($_smarty_tpl->tpl_vars['_oItem']->value['training_id'],$_smarty_tpl->tpl_vars['_oItem']->value);?>
</div>

						</div>

					</div>

				</div>

			</div>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php } else { ?>

		<div class="empty w-100 rounded-3">

			<div class="p-5 text-center bg-white">

				<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg" />

				<p>Danh sách trống</p>

			</div>

		</div>

		<?php }?>

	</div>	

	<?php if (!empty($_smarty_tpl->tpl_vars['html_pager']->value)) {?>

		<div class="pagination justify-content-center"><?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>
</div>

	<?php }?>

<?php } else { ?>

	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

		<div class="kYlZoryVmS">

			<h4 class="fw-bold mb-0">Khóa học đào tạo</h4>

			<span class="text-muted">Các khóa học đào tạo tại <?php echo @constant('BRAND_NAME');?>
</span>

		</div>		

		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

		<div class="d-flex justify-content-end gap-2">

			<form action="" method="post">

				<input type="hidden" name="submit" value="search">

				<div class="input-group input-group-merge w-px-200">

					<span class="input-group-text" id="keyword"><i class="bx bx-search"></i></span>

					<input type="text" class="form-control" placeholder="Tìm kiếm" name="keyword" aria-label="Tìm kiếm" aria-describedby="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
">

				</div>

			</form>

			<div class="input-group <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>d-none<?php }?>">

				<div class="border btn btn-icon">

					<input type="radio" name="view" value="grid" hidden id="view_grid" onchange="$Core.training.set_view(this,event)" <?php if ($_smarty_tpl->tpl_vars['view_training']->value == 'grid') {?>checked<?php }?>>

					<label class="btn_view" for="view_grid"><i class='bx bxs-grid-alt' ></i></label>

				</div>

				<div class="border btn btn-icon">

					<input type="radio" name="view" value="list" hidden id="view_table" onchange="$Core.training.set_view(this,event)" <?php if ($_smarty_tpl->tpl_vars['view_training']->value == 'list') {?>checked<?php }?>>

					<label class="btn_view" for="view_table"><i class='bx bx-list-ul fs-22' ></i></label>

				</div>

			</div>

		</div>

		<?php } else { ?>

		<form action="" method="post">

			<div class="d-flex align-items-center justify-content-end box_search">

				<input type="text" class="inp_search form-control top_search_keyword shadow-none" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="Tìm kiếm"  autocomplete="off" onKeyUp="$Core.training.search(this,event)">

				<button class="btn btn-outline-none btn_search btn-icon border" type="button" onclick="$Core.training.show_search(this,event)">

					<i class="bx bx-search fs-4 lh-0"></i>

				</button>

			</div>			

			<input type="hidden" name="submit" value="search">

		</form>

		<?php }?>

	</div>

	<div class="card no-shadow">

		<div class="card-body">

			<div class="table-container no-shadow overflow-x-auto">

				<table class="table dragable" cellpadding="0" cellspacing="0" border="0" width="100%">

					<thead><tr>

						<th class="align-center h-px-40 bg-lighter text-left">Tiêu đề</th>

						<th class="align-center h-px-40 bg-lighter text-center" width="100px">Bài học</th>

						<th class="align-center h-px-40 bg-lighter bg-lighter text-center" width="100px">Thời lượng</th>

						<th class="align-center h-px-40 bg-lighter bg-lighter text-center" width="80px">Số lượt xem</th>

						<th class="align-center h-px-40 bg-lighter bg-lighter text-center" width="80px">Số người đã học</th>

						<th class="align-center h-px-40 bg-lighter text-center" width="100px">Hoàn thành</th>

					</tr></thead>

					<tbody>

						<?php if (!empty($_smarty_tpl->tpl_vars['lstTraining']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstTraining']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

								<tr class="tr">

									<td class="text-left" style="min-width:200px">

										<div class="d-flex align-items-center gap-2">

											<div class="box_image image-scale rounded-3 d-flex align-items-center">

												<img class="" src="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['image'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
" width="100" height="50" loading="lazy">

											</div>

											<div class="d-flex flex-column flex-fill w-50">

												<a class="fs-15 fw-semibold" href="javascript:void(0);" onclick="$Core.global.training.open(this,event)" title="Xem ngay" training_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['training_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</a> 								

												<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['author']) {?><div class="text-dark"><span class="fw-bold">Tác giả:</span> <?php echo $_smarty_tpl->tpl_vars['_oItem']->value['author'];?>
</div><?php }?>

											</div>

										</div>

									</td>

									<td class="text-nowrap text-center"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_lesson'];?>
</td>

									<td class="text-nowrap text-center"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeMinute($_smarty_tpl->tpl_vars['_oItem']->value['time_training']);?>
</td>

									<td class="text-nowrap text-center"><i class="material-icons-outlined me-1">visibility</i><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_view'];?>
 lượt</td>

									<td class="text-nowrap text-center"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_profile_learning'];?>
</td>

									<td class="text-nowrap text-center">

										<div class="metadata-row-viewer d-flex justify-content-center mt-2 done_ratio_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['training_id'];?>
">

											<?php echo $_smarty_tpl->tpl_vars['clsTraining']->value->getProgress($_smarty_tpl->tpl_vars['_oItem']->value['training_id'],$_smarty_tpl->tpl_vars['_oItem']->value);?>


										</div>

									</td>

								</tr>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?> 

						<?php } else { ?>

							<tr class="tr">

								<td colspan="4" class="text-center">Danh sách trống</td>

							</tr>

						<?php }?>

					</tbody>

				</table>

			</div>

			<?php if (!empty($_smarty_tpl->tpl_vars['html_pager']->value)) {?>

				<div class="pagination justify-content-center mt-3"><?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>
</div>

			<?php }?>

		</div>

	</div>

<?php }?>

</div><?php }
}
