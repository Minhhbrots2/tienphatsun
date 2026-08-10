<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:54:02
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/docs/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ad8eab13560_39131602',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '67685dcf4faca6902e4fa48543f7b6187a5cd03c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/docs/default.tpl',
      1 => 1784691611,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ad8eab13560_39131602 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

<div class="ui-title-bar-container ui-title-bar-container--full-width">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title w-100"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Kho tài liệu dự án');?>
</h1>

				<p class="type--subdued"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Quản lý toàn bộ tài liệu liên quan tới dự án');?>
</p>

			</div>

		</div>

		<div class="action-bar">

			<div class="ui-title-bar__mobile-primary-actions">

				<div class="ui-title-bar__actions">

					<a href="javascript:void(0);" onClick="$Core.docs.open(this,event)" project_meta_id="0" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" cat_id="<?php echo $_smarty_tpl->tpl_vars['cat_id']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
" 

					   class="ui-button ui-button--transparent js_create_add ui-title-bar__action mr-2"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Add'));?>
</a>

					<a href="javascript:void(0);" onClick="$Core.docs.select_file(this, event)" project_meta_id="0" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Import');?>
" 

					   class="ui-button ui-button--transparent js_start_select_file ui-title-bar__action" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Import'));?>
</a>

					   <a href="javascript:void(0);" onClick="$Core.docs.cleanup(this, event)" class="ui-button ui-button--transparent ui-title-bar__action ml-2" title="Làm sạch dữ liệu"><i class="fa fa-magic"></i> Làm sạch</a>

					   <span class="cleanup_progress text-muted ml-2" style="display:none"></span>

				</div>

			</div>

		</div>

	</div>

</div>

<form class="d-none" method="post" enctype="multipart/form-data">

	<input type="file" class="select_file select_file_<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" name="import_file" />

</form>

<div class="ui-layout ui-layout--full-width">

	<div class="ui-layout__sections"><div class="ui-layout__section">

		<div class="ui-layout__item"><div class="ui-card">

			<div class="next-tab__container">

				<ul class="next-tab__list filter-tab-list">

					<li class="filter-tab-item" data-tab-index="1">

						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="filter-tab show-all-items next-tab<?php if ($_smarty_tpl->tpl_vars['type_list']->value != 'trash') {?> filter-tab-active next-tab--is-active<?php }?>"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Qũy Căn Hộ Dự Án');?>
</a>

					</li>

					<li class="filter-tab-item" data-tab-index="2">

						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&type_list=trash" class="filter-tab next-tab<?php if ($_smarty_tpl->tpl_vars['type_list']->value == 'trash') {?> filter-tab-active next-tab--is-active<?php }?>">Thùng rác</a>

					</li>

				</ul>

			</div>

			<div class="ui-card__section has-bulk-actions pages">

				<form method="post">

					<div class="form-search form-inline">

						<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

						<div class="form-group" style="width: 200px">

							<select data-field="project_id" name="project_id" onChange="$Core.docs.select_block(this, event)" toId="slb_block_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-control search_field iso-select2" data-width="100%">

								<option value="0">Chọn dự án</option>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, '_project', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_project']->value) {
?>

								<option value="<?php echo $_smarty_tpl->tpl_vars['_project']->value['project_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['project_id']->value == $_smarty_tpl->tpl_vars['_project']->value['project_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_project']->value['title'];?>
</option>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</select>

						</div>

						<div class="form-group w-150px">

							<select class="form-control search_field mr-2 iso-select2" id="slb_block_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" name="block_id" onChange="$Core.docs.select_building(this, event)" 

									toId="slb_building_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-field="block_id">

								<option value="0">Chọn phân khu</option>

								<?php if (!empty($_smarty_tpl->tpl_vars['project_id']->value) && !empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

										<option value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['block_id']->value == $_smarty_tpl->tpl_vars['_oItem']->value['property_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</option>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php }?>

							</select>

						</div>

						<div class="form-group w-150px">

							<select class="form-control search_field mr-2 iso-select2" id="slb_building_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" name="building_id" data-field="building_id">

								<option value="0">Chọn toà nhà</option>								

								<?php if (!empty($_smarty_tpl->tpl_vars['block_id']->value) && !empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

										<option value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['building_id']->value == $_smarty_tpl->tpl_vars['_oItem']->value['property_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</option>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php }?>

							</select>

						</div>

						<div class="form-group w-150px">

							<select class="form-control search_field mr-2 w-150px iso-select2" data-field="type" name="type">

								<option value="">Chọn loại</option>

								<option value="project">Dự án</option>

								<option value="block">Phân khu</option>

								<option value="building">Tòa</option>

								

							</select>

						</div>

						<div class="form-group w-150px">

							<select class="form-control search_field mr-2 w-150px iso-select2" data-field="cat_id" name="cat_id">

								<option value="0">Chọn danh mục</option>

								<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getListOption('_CATEGORY_DOCS',$_smarty_tpl->tpl_vars['cat_id']->value);?>


							</select>

						</div>

						<div class="form-group">

							<div class="input-group">

								<input type="text" class="form-control search_field" data-field="keyword" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('search');?>
" />

							</div>

						</div>

						<input type="hidden" name="type_list" value="<?php echo $_smarty_tpl->tpl_vars['type_list']->value;?>
" />

						<input type="hidden" name="filter" value="filter" />

						<button type="submit" class="btn btn-success"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('search','Search');?>
</button>

						<div class="form-group pull-right">

							<a href="javascript:void(0)" clsTable="<?php echo $_smarty_tpl->tpl_vars['classTable']->value;?>
" class="btn btn-danger text-white btn-delete-all" style="display:none" onClick="$Core.docs.delete_all(this, event)" type_list="<?php echo $_smarty_tpl->tpl_vars['type_list']->value;?>
"> 

								<i class="icon-remove icon-white"></i> 

								<span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Delete');?>
</span> 

							</a>

						</div>

					</div>

					<div class="hastable">

						<table class="table" width="100%" cellpadding="0" cellspacing="0">

							<thead><tr>

								<th width="5%" class="text-center">

									<div class="checkbox">

										<input type="checkbox" id="check_all" 

										class="check_all styled" value="1" />

										<label></label>

									</div>

								</th>

								<th width="50px" class="text-center">Ảnh</th>

								<th width="" class="text-left">Tiêu đề</th>	

								<th class="text-left">Nội dung</th>

								<th class="text-left">Loại</th>

								<th class="text-left">Dự án</th>

								<th class="text-left">Phân khu</th>

								<th class="text-left">Tòa</th>

								<th class="text-left">Danh mục</th>

								<th class="text-left">Thời gian</th>

								<th width="120px">H.Động</th>	

							</tr></thead>

							<tbody class="holder_docs">

								<?php if (!empty($_smarty_tpl->tpl_vars['list_docs']->value)) {?>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_docs']->value, '_odocs', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_odocs']->value) {
?>

									<?php $_smarty_tpl->_assignInScope('list_tags', $_smarty_tpl->tpl_vars['_odocs']->value['list_tags']);?>

									<tr>

										<td class="text-center">

											<div class="checkbox">

												<input type="checkbox" name="p_key[]" class="chkitem styled" 

												value="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" />

												<label></label>

											</div>

										</td>

										<td class="text-center"><img src="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['image'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.jpg'" class="border radius-3" style="width:40px;height:40px;object-fit:cover" /></td>

										<td><strong class="font-bold"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['title'];?>
</strong><?php if ($_smarty_tpl->tpl_vars['_odocs']->value['file_type']) {?> <span class="label label-info fs-tiny"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['file_type'];?>
</span><?php }?><br />

											<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['title_search'];?>


											<?php if (!empty($_smarty_tpl->tpl_vars['list_tags']->value)) {?>

											<div class="mt-2">

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_tags']->value, 'tag');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['tag']->value) {
?>

													<span class="label label-primary p-1 fs-tiny"><?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
</span>

													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

												<?php }?>

											</div>

										</td>

										<td><a href="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['content'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['content'];?>
" data-toggle="tooltip" target="_blank">Link</a></td>

										<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['type'];?>
</td>

										<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['project_name'];?>
</td>

										<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['block_name'];?>
</td>

										<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['building_name'];?>
</td>

										<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_odocs']->value['cat_name'];?>
</td>

										<td class="text-left text-nowrap"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_odocs']->value['upd_date'],4);?>
</td>

										<td class="text-center">

											<div class="d-flex gap-2 align-items-center">

												<?php if ($_smarty_tpl->tpl_vars['type_list']->value == 'trash') {?>

												<button onClick="$Core.docs.restore(this, event)" project_meta_id="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" class="btn btn-icon btn-sm btn-default" title="Khôi phục"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('undo');?>
</button>

												<button onClick="$Core.docs.force_delete(this, event)" project_meta_id="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" class="btn btn-icon btn-sm btn-danger" title="Xoá vĩnh viễn"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

												<?php } else { ?>

												<button onClick="$Core.docs.open(this, event)" project_meta_id="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" class="btn btn-icon btn-sm btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>

												<button onClick="$Core.docs.delete(this, event)" project_meta_id="<?php echo $_smarty_tpl->tpl_vars['_odocs']->value['id'];?>
" class="btn btn-icon btn-sm btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

												<?php }?>

											</div>



										</td>

									</tr>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								<?php }?>

							</tbody>

						</table>

					</div>

					<div class="t-grid-pager-boder d-flex justify-content-center">

						<ul class="t-pager pagination fix-margin-pager">

							<?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>


						</ul>

					</div>

				</form>

			</div>

		</div></div>

	</div></div>

</div>

<?php echo $_smarty_tpl->tpl_vars['script']->value;
}
}
