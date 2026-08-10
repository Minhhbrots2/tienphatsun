<?php
/* Smarty version 3.1.33, created on 2026-08-07 17:36:04
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/course/_ajax.open_course.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75b5147a6f21_37623359',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a5c1e7f985852f72c363bd975d4fd44ba474b84c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/course/_ajax.open_course.tpl',
      1 => 1784300228,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75b5147a6f21_37623359 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="modal-dialog modal-dialog-scrollable modal-ipad-xl">

	<form method="POST" enctype="multipart/form-data" class="modal-content">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title"><?php if ($_smarty_tpl->tpl_vars['course_id']->value > '0') {?>Sửa<?php } else { ?>Thêm<?php }?> mới sự kiện</h5>

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

			</div>

			<div class="modal-body modal-body-scrollable">									

				
				<?php if ($_smarty_tpl->tpl_vars['_tp']->value == "event") {?>

				<div class="form-group mb-2">

					<label for="title" class="form-label mb-1">Tên sự kiện</label>

					<input type="text" class="form-control required form-field" name="title" placeholder="Tên sự kiện" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
">

					<input type="hidden" class="form-field" name="cat_id" value="<?php echo $_smarty_tpl->tpl_vars['cat_id']->value;?>
">

				</div>

				<div class="form-group form-row mb-2">

					<div class="col-12 col-xxl-6">

						<label for="title" class="form-label mb-1">Dự án</label>

						<select class="form-control form-select form-field" onChange="$Core.course.load_block(this, event)" name="project_id" 

						id="slb_Project_Id" toId="slb_Block_Id_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-width="100%" data-field="project_id" block_id="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['block_id'];?>
">

							<option value="0">Chọn dự án</option>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, 'project', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['project']->value) {
?>

							<option<?php if ($_smarty_tpl->tpl_vars['project']->value['project_id'] == $_smarty_tpl->tpl_vars['more_information']->value['project_id']) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['project']->value['code'];?>
</option>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</select>

					</div>

					<div class="col-12 col-xxl-6">

						<label for="title" class="form-label mb-1">Phân khu</label>

						<select class="form-control form-select form-field" onChange="$Core.data_central.select_building(this, event)" data-placeholder="Phân khu" data-width="100%" data-header="true" data-filter="true" id="slb_Block_Id_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" toId="slb_Building_Id" data-field="blocks_ids[]">

							<?php if (!empty($_smarty_tpl->tpl_vars['list_ss_blocks']->value)) {?>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_ss_blocks']->value, 'block', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['block']->value) {
?>

								<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['more_information']->value['block_id'],$_smarty_tpl->tpl_vars['block']->value['property_id'])) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['block']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['block']->value['title'];?>
</option>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php }?>

						</select>

					</div>

				</div>

				<?php } else { ?>

				<div class="form-group form-row mb-2">

					<div class="col-12 col-xxl-8 mb-2 mb-lg-0">

						<label for="title" class="form-label mb-1">Tên sự kiện</label>

						<input type="text" class="form-control required form-field" name="title" placeholder="Tên sự kiện" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
">

					</div>

					<div class="col-12 col-xxl-4">

						<label for="phone" class="form-label mb-1">Danh mục</label>

						<div class="w-100">

							<select name="cat_id" class="form-control form-field iso-select2 required w-100" data-width="100%">

								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByProperty('_FAQs',$_smarty_tpl->tpl_vars['oneItem']->value['cat_id'],'',0,1);?>


							</select>

						</div>

					</div>

				</div>

				<?php }?>

				<div class="form-group form-row mb-2">

					<div class="col-6 col-xxl-3 mb-2 mb-lg-0">

						<label class="form-label mb-1">Bắt đầu</label>

						<input class="form-control form-field w-100 required" type="datetime-local"  name="start_date" min="<?php echo smarty_modifier_date_format(time(),"%Y-%m-%d");?>
T<?php echo smarty_modifier_date_format(time(),"%H:%M");?>
" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
"/>

					</div>

					<div class="col-6 col-xxl-3 mb-2 mb-lg-0">

						<label class="form-label mb-1">Kết thúc</label>

						<input class="form-control form-field w-100 <?php if ($_smarty_tpl->tpl_vars['_tp']->value != 'event') {?>required<?php }?>" type="datetime-local"  name="due_date" min="<?php echo smarty_modifier_date_format(time(),"%Y-%m-%d");?>
T<?php echo smarty_modifier_date_format(time(),"%H:%M");?>
" value="<?php echo $_smarty_tpl->tpl_vars['due_date']->value;?>
"/>

					</div>

					<div class="col-12 col-xxl-6">

						<label class="form-label mb-1">Địa điểm</label>

						<input type="text" class="form-control form-field" name="location" required maxlength="255" placeholder="HA02-211 Vinhomes Ocean Park 1" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['location'];?>
" />

					</div>

				</div>

				<div class="d-flex align-items-center mb-2">

					<a class="text-muted cursor-pointer" onclick="$Core.util.toggle_block(this, event)" toId="more_info_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">

						<i class="bx bx-chevron-down"></i> Thêm thông tin

					</a>

				</div>

				<div id="more_info_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="d-none mb-2">

					<div class="form-group mb-2">

						<label class="form-label mb-1">Link chia sẻ</label>

						<input class="form-control form-field w-100" type="text" placeholder="https://" name="link" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['link'];?>
"/>

					</div>

					<div class="form-group">

						<label class="form-label mb-1">Người quản lý</label>

						<select class="iso-selectizeImageSearch form-field w-100" placeholder="Thêm người quản lý" multiple="multiple" name="manager_ids" 

						 data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=home&act=list_staff&holderG=permiss" data-optgroup="false">

						<?php if (!empty($_smarty_tpl->tpl_vars['oneItem']->value['manager_ids'])) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['oneItem']->value['manager_ids'], '_profile_id');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_profile_id']->value) {
?>

							<option value="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['_profile_id']->value);?>
</option>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php }?>

						 </select>

					</div>

				</div>

				<div class="form-group form-row mb-2">

					<div class="col-12">

						<label class="form-label mb-1">Nội dung</label>

						<textarea class="form-control form-field isoTextArea" cols="255" rows="3" placeholder="Kết quả thực tế" 

							name="content" data-field="content" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['content'];?>
</textarea>

					</div>

				</div>

				<div class="form-group">

					<label class="form-label mb-1">Thành phần tham gia</label>

					<fieldset class="p-3 bg-lightest rounded-2">

						<div class="form-group">

							<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

							<div class="radio mb-2">

								<input name="is_all_staff" class="form-check-input" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" type="radio" 

									value="1" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] == '1' || !isset($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'])) {?> checked<?php }?>>

								<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Tất cả nhân viên</label>

							</div>

							<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

							<div class="radio mb-2">

								<input name="is_all_staff" class="form-check-input" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" type="radio" 

									value="0" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] == '0') {?> checked<?php }?>>

								<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Phòng ban hoặc nhân viên</label>

							</div>	

							<div class="pl-4 unit_staff mb-2"<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] != '0') {?> style="display: none"<?php }?>>

								<div class="form-group">

									<div class="w-100 mb-2">

										<label class="form-label mb-1"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Phòng ban');?>
</label>

										<div class="w-100 clearfix"></div>

										<select name="list_department_id" class="form-control form-field iso-select2 select2 w-100" data-width="100%" 

											multiple="true" data-placeholder="Chọn phòng ban tham gia"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_DEPARTMENT',$_smarty_tpl->tpl_vars['clsISO']->value->getArrayByTextSlash($_smarty_tpl->tpl_vars['oneItem']->value['list_department_id']),'Phòng ban');?>


										</select>

									</div>

									<div class="w-100 mb-0">

										<label class="form-label mb-1"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Nhân viên');?>
</label>

										<div class="w-100 clearfix"></div>

										<select placeholder="Lựa chọn nhân viên" name="list_profile_id" class="form-control form-field iso-select2 select2 w-100" multiple="true" data-width="100%" data-placeholder="Chọn nhân viên tham gia">

											<?php $_smarty_tpl->_assignInScope('arr_profile_ids', $_smarty_tpl->tpl_vars['clsISO']->value->getArrayByTextSlash($_smarty_tpl->tpl_vars['oneItem']->value['list_profile_id']));?>

											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_profiles']->value, 'prof', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['prof']->value) {
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

							</div>						

							<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

							<div class="radio mb-2">

								<input name="is_all_staff" class="form-check-input" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" type="radio" 

									value="2"<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] == '2') {?> checked<?php }?>>

								<label for="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Nhóm nhân viên</label>

							</div>

							<div class="pl-4 unit_group_staff" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_all_staff'] != '2') {?> style="display: none"<?php }?>>

								<div class="form-group">

									<div class="w-100">

										<select name="list_group_profile_id" class="form-control form-field iso-select2 select2 w-100" data-width="100%" multiple="true" data-placeholder="Chọn nhóm nhân viên">

											<?php $_smarty_tpl->_assignInScope('arr_group_id', $_smarty_tpl->tpl_vars['clsISO']->value->getArrayByTextSlash($_smarty_tpl->tpl_vars['oneItem']->value['list_group_profile_id']));?>

											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstGroupProfile']->value, 'group', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
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

					</fieldset>

				</div>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>

				<button type="button" class="btn btn-outline-primary" onClick="$Core.course.open_course(this,event)" 

					data-course_id="<?php echo $_smarty_tpl->tpl_vars['course_id']->value;?>
" _tp="<?php echo $_smarty_tpl->tpl_vars['_tp']->value;?>
" data-type="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">Lưu lại</button>

				<button type="button" class="btn btn-outline-success" onClick="$Core.course.open_course(this,event)" 

					data-course_id="<?php echo $_smarty_tpl->tpl_vars['course_id']->value;?>
" _tp="_ZALO" data-type="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">Lưu & Gửi Zalo</button>

			</div>

		</div>

	</form>

</div>



<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		$_document.on('change', 'input[name=is_all_staff]', function(){

			var _this = $(this);

			var form = _this.closest("form");

			var is_all_staff = $('input[name=is_all_staff]:checked',form).val();

			if(is_all_staff == 1){

				console.log(1);

				$('.unit_staff',form).hide();

				$('.unit_group_staff',form).hide();

			}else if(is_all_staff == 2){

				console.log(2);

				$('.unit_staff',form).hide();

				$('.unit_group_staff',form).show();

			} else {

				console.log(3);

				$('.unit_staff',form).show();

				$('.unit_group_staff',form).hide();									

			}

		});

	});

<?php echo '</script'; ?>
>

<?php }
}
