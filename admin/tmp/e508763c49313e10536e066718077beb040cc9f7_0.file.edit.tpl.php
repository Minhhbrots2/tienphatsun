<?php
/* Smarty version 3.1.33, created on 2026-07-31 15:23:59
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/training/edit.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c5b9f29bda9_53401513',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e508763c49313e10536e066718077beb040cc9f7' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/training/edit.tpl',
      1 => 1784691758,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c5b9f29bda9_53401513 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="ui-title-bar-container">

	<div class="ui-title-bar">

		<div class="ui-title-bar__navigation">

			<div class="ui-breadcrumbs">

				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default ui-breadcrumb">

					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>


					<span class="ui-breadcrumb__item">Khóa học</span>

				</a>

			</div>

		</div>

	</div>

</div>

<div class="clearfix"></div>

<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">

	<div class="ui-layout">

		<div class="row">

			<div class="col-md-8 col-xs-12">

				<div class="ui-layout__item">

					<div class="ui-card">

						<div class="ui-card__section">

							<div class="ui-type-container">

								<div class="form-group">

									<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Title');?>
*</label>

									<input type="text" class="form-control required" name="iso-title" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
" placeholder="Tên khóa học" required maxlength="255" />

								</div>

								<div class="form-group">

									<label class="col-form-label">Giảng viên</label>

									<input type="text" class="form-control required" name="iso-author" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['author'];?>
" placeholder="Tên giảng viên" required maxlength="255" />

								</div>

								

								<div class="form-group">

									<label class="col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Content');?>
</label>

									<textarea id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" class="form-control isoTextArea edit_profile_field_about" name="content" cols="255" rows="15"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['content'];?>
</textarea>

								</div>

								<div class="form-group">

									<fieldset class="p-3">

										<div class="form-group">

											<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

											<div class="radio mb-2">

												<input name="is_all_staff" class="form-check-input" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" type="radio" value="1" onChange="$Core.training.changeStaff(this,event)" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] == '1' || !isset($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'])) {?> checked<?php }?>>

												<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Tất cả nhân viên</label>

											</div>

											<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

											<div class="radio mb-2">

												<input name="is_all_staff" class="form-check-input" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" type="radio" value="0" onChange="$Core.training.changeStaff(this,event)" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] == '0') {?> checked<?php }?>>

												<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Phòng ban hoặc nhân viên</label>

											</div>	

											<div class="pl-4 unit_staff mb-2" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] != '0') {?> style="display: none"<?php }?>>

												<div class="form-group form-row">

													<div class="col-xs-12">

														<label class="form-label mb-1"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Phòng ban');?>
</label>

														<div class="w-100 mb-2">

															<select<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] == '1') {?> disabled<?php }?> name="list_department_id[]" placeholder="Chọn phòng ban tham gia" class="form-control iso-select2" multiple="true"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_DEPARTMENT',$_smarty_tpl->tpl_vars['arr_departments_ids']->value,'Phòng ban');?>
</select>

														</div>

													</div>

													<div class="col-xs-12">

														<label class="form-label mb-1"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Nhân viên');?>
</label>

														<div class="w-100 mb-2">

															<select<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] == '1') {?> disabled<?php }?> name="list_profile_id[]" placeholder="Lựa chọn nhân viên" class="form-control iso-select2" multiple="true">

																<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_profiles']->value, 'prof', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['prof']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

																	<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['prof']->value['profile_id'],$_smarty_tpl->tpl_vars['arr_profile_ids']->value)) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['prof']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['prof']->value['profile_id'],$_smarty_tpl->tpl_vars['prof']->value);?>
</option>

																<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

															</select>

														</div>

													</div>

													<div class="col-xs-12">

														<label class="form-label mb-1">Nhóm nhân viên</label>

														<div class="w-100 mb-2">

															<select<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] == '2') {?> disabled<?php }?> placeholder="Lựa chọn nhóm" name="list_group_profile_id[]" class="form-control iso-select2" multiple="true">

																<?php $_smarty_tpl->_assignInScope('arr_group_id', $_smarty_tpl->tpl_vars['clsISO']->value->getArrayByTextSlash($_smarty_tpl->tpl_vars['oneItem']->value['list_group_profile_id']));?>

																<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstGroupProfile']->value, 'group', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

																	<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['group']->value['group_profile_id'],$_smarty_tpl->tpl_vars['arr_group_id']->value)) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['group']->value['group_profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsGroupProfile']->value->getTitle($_smarty_tpl->tpl_vars['group']->value['group_profile_id'],$_smarty_tpl->tpl_vars['group']->value);?>
</option>

																<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

															</select>

														</div>

													</div>

												</div>

											</div>	

										</div>

									</fieldset>

								</div>

								<div class="form-group">

									<fieldset>

										<legend>Khóa học</legend>

										<div class="box-title d-flex justify-content-end align-items-center mb-2">

											<button class="btn btn-outline-default" type="button" onClick="$Core.training.open_lesson(this,event)" data-training_id='<?php echo $_smarty_tpl->tpl_vars['training_id']->value;?>
' data-lesson_id=""><?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>Thêm<?php } else { ?>Thêm mới<?php }?></button>

										</div>

										<div class="box-body">

											<table class="table table-hover table-vertical table-striped table-responsive TableListLesson">

												<thead><tr>

													<th class="align-center bg-lighter" style="width:60px"></th>

													<th class="align-center bg-lighter" style="width:60px">STT</th>

													<th class="align-left bg-lighter">Tên khóa học</th>

													<th class="align-center text-left bg-lighter w-px-40" style="width:40px"></th>

												</tr></thead>

												<tbody class="list_lesson" id="list_lesson">

												<?php if (!empty($_smarty_tpl->tpl_vars['lstLesson']->value)) {?>

													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstLesson']->value, 'item', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

														<tr>

															<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

															<td><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</td>

															<td>

																<div class="btn-group">

																	<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>

																	<ul class="dropdown-menu" style="right:0px !important; left: auto">

																		<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.training.open_lesson(this,event)" data-training_id='<?php echo $_smarty_tpl->tpl_vars['training_id']->value;?>
' data-lesson_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"  data-field="history_sale" data-type="edit">Sửa</a></li>

																		<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.training.deleteLesson(this,event)" data-training_id='<?php echo $_smarty_tpl->tpl_vars['training_id']->value;?>
' data-lesson_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-field="history_sale">Xoá</a></li>

																	</ul>

																</div>

															</td>

														</tr>

													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

												<?php } else { ?>

													<tr>

														<td class="text-center" colspan="2">Danh sách bài học trống</td>	

													</tr>

												<?php }?>

												</tbody>

											</table>

										</div>

									</fieldset>

								</div>

							</div>

						</div>

					</div>

				</div>	

			</div>

			<div class="col-md-4 col-xs-12">

				<div class="ui-layout__item">

					<div class="ui-card">

						<header class="ui-card__header">

							<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Status');?>
</h2>

						</header>

						<div class="ui-card__section">

							<div class="ui-type-container">

								<div class="form-group">

									<div class="custom-radio-wrapper core-radio-custom">

										<label class="">

											<input <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_online'] == '1') {?> checked="checked"<?php }?> name="is_online" value="1" type="radio"> <span class="custom-radio custom-icon"></span>

										</label> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Show');?>


									</div>

								</div>

								<div class="form-group">

									<div class="custom-radio-wrapper core-radio-custom">

										<label class="">

											<input<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_online'] != '1') {?> checked="checked"<?php }?> name="is_online" value="0" type="radio"> <span class="custom-radio custom-icon"></span>

										</label> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Hide');?>


									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="ui-card">

						<header class="ui-card__header">

							<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Category');?>
</h2>

						</header>

						<div class="ui-card__section">

							<div class="ui-type-container">

								<div class="form-group">									

									<div class="box-body pt-4">

										<select class="iso-selectize custom-select required" required name="cat_id" id="cat_id">

											<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_TRAINING_CAT',$_smarty_tpl->tpl_vars['oneItem']->value['cat_id'],'Danh mục');?>


										</select>

									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="ui-card mt-half">

						<header class="ui-card__header">

							<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Image');?>
</h2>

						</header>

						<div class="ui-card__section">

							<div class="ui-type-container">

								<div id="article-image-drop" class="article-image-drop">

									<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive">

										<input type="hidden" id="isoman_hidden_image" name="isoman_url_image" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['image'];?>
" />

										<img class="aspect-ratio__content" id="isoman_show_image" src="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['image'];?>
">

									</div>

									<div class="clearfix"></div>

									<div class="ui-stack ui-stack--wrap">

										<div class="ui-stack-item ui-stack-item--fill">

											<button type="button" class="ui-button btn--link ajOpenDialog" isoman_for_id="image" isoman_val="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['image'];?>
" isoman_name="image"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Change');?>
</button>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="ui-page-actions ui-page-actions--has-secondary">

		<input value="Update" name="submit" type="hidden">

		<div class="ui-page-actions__container">

			<div class="ui-page-actions__actions ui-page-actions__actions--secondary">

				<div class="ui-page-actions__button-group">

					

				</div>

			</div>

			<div class="ui-page-actions__actions ui-page-actions__actions--primary">

				<div class="ui-page-actions__button-group">

					<a class="btn btn-default" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Calcel');?>
</a>

					<?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['saveList']->value;?>


				</div>

			</div>

		</div>

	</div>

</form>

<?php echo '<script'; ?>
>

	var training_id = `<?php echo $_smarty_tpl->tpl_vars['training_id']->value;?>
`;

<?php echo '</script'; ?>
><?php }
}
