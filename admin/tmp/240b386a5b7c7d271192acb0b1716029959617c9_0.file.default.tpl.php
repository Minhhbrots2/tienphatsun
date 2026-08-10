<?php
/* Smarty version 3.1.33, created on 2026-07-31 14:57:22
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/training/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c5562468725_04509365',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '240b386a5b7c7d271192acb0b1716029959617c9' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/training/default.tpl',
      1 => 1784691758,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c5562468725_04509365 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="ui-title-bar-container">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title w-100">Khóa học đào tạo</h1>

				<p class="type--subdued"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('This system allows you to manage & edit static pages in Systems');?>
</p>

			</div>

		</div>

		<div class="action-bar">

			<div class="ui-title-bar__mobile-primary-actions">

				<div class="ui-title-bar__actions">

					<a href="javascript:void(0)" onClick="$Core.training.addTraining(this,event)" data-type="_OPEN" class="ui-button ui-button--primary ui-title-bar__action"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
</a>

				</div>

			</div>

		</div>

	</div>

</div>

<div class="clearfix"></div>

<div class="ui-layout">

	<div class="ui-layout__sections">

		<div class="ui-layout__section">

			<div class="ui-layout__item">

				<div class="ui-card">

					<div class="next-tab__container">

						<ul class="next-tab__list filter-tab-list">

							<li class="filter-tab-item" data-tab-index="1">

								<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('AllPages');?>
</a>

							</li>

						</ul>

					</div>

					<div class="ui-card__section has-bulk-actions pages">

						<form method="post">

							<div class="form-search form-inline">

								<div class="d-flex">

									<div class="form-group w-400px d-flex gap-1">

										<div class="input-group w-200px double-input">

											<input type="text" class="form-control" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('search');?>
" style="width: 100% !important"/>

										</div>

										<div class="input-group w-200px">

											<select class="gl-reload form-control w-100 custom-select required" name="cat_id">

												<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_TRAINING_CAT',$_smarty_tpl->tpl_vars['cat_id']->value,'Danh mục');?>


											</select>

										</div>									

									</div>

									<input type="hidden" name="filter" value="filter" />

									<button type="submit" class="btn btn-success"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('search','Search');?>
</button>

								</div>

								<div class="form-group pull-right">

									<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn text-white btn-warning">

										<i class="icon-folder-open icon-white"></i> 

										<span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('all');?>
 (<?php echo $_smarty_tpl->tpl_vars['number_all']->value;?>
)</span>

									</a>

									<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&type_list=Trash" class="btn text-white btn-danger">

										<i class="icon-warning-sign icon-white"></i> 

										<span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('trash');?>
 (<?php echo $_smarty_tpl->tpl_vars['number_trash']->value;?>
)</span>

									</a>

									<a href="javascript:void(0)" class="btn btn-danger text-white btn-delete-all" style="display: none" clsTable="Training"> 

                           				<i class="icon-remove icon-white"></i> 

                           				<span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Delete');?>
</span> 

                           			</a>

								</div>

							</div>

							<div class="hastable table-wrapper">

								<input type="hidden" id="list_selected_chkitem" value="" />

								<table cellspacing="0" class="table table-vertical table-striped" width="100%">

									<thead><tr>

										<th class="text-center" width="3%">

											<div class="checkbox">

												<input type="checkbox" id="check_all" class="check_all styled" value="1">

												<label></label>

											</div>

										</th>

										<th class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Title');?>
</th>

										<th class="text-left" style="width:6%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Status');?>
</th>

										<th class="text-left" style="width:6%">Show MOC</th>

										<th class="text-right" style="width:20%;" align="center"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Category');?>
</th>

										<th class="text-right" style="width:12%;" align="center"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('update');?>
</th>

										<th class="text-left" width="100px"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Action');?>
</th>

									</tr></thead>

									<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['allItem']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

									<tr class="<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)%2 == 0) {?>row1<?php } else { ?>row2<?php }?>">

										<td class="text-center">

											<div class="checkbox">

												<input type="checkbox" name="p_key[]" class="chkitem styled" value="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id'];?>
">

												<label></label>

											</div>

										</td>

										<td class="text-left">

											<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&training_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']);?>
"><?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getTitle($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']);?>
</a>

											<?php if ($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_trash'] == '1') {?>

											<span class="fr text-right"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('intrash');?>
</span>

											<?php }?>

										</td>

										<td class="text-center">

											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Training" pkey="training_id" sourse_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id'];?>
" rel="<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getOneField('is_online',$_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']);?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Click to change status');?>
">

												<?php if ($_smarty_tpl->tpl_vars['clsClassTable']->value->getOneField('is_online',$_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']) == '1') {?>

												<i class="fa fa-check-circle green"></i>

												<?php } else { ?>

												<i class="fa fa-minus-circle red"></i>

												<?php }?>

											</a>

										</td>

										<td class="text-center">

											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Training" pkey="training_id" toField="show_MOC" sourse_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id'];?>
" rel="<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getOneField('show_MOC',$_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']);?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Click to change status');?>
">

												<?php if ($_smarty_tpl->tpl_vars['clsClassTable']->value->getOneField('show_MOC',$_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']) == '1') {?>

												<i class="fa fa-check-circle green"></i>

												<?php } else { ?>

												<i class="fa fa-minus-circle red"></i>

												<?php }?>

											</a>

										</td>

										<td class="text-center"><?php if (!empty($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['cat_id'])) {
echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['cat_id']);
} else { ?>--<?php }?></td>

										<td class="text-center"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['reg_date'],"%d/%m/%Y %H:%M");?>
</td>

										<td class="text-center" style="white-space:nowrap;">

											<div class="btn-group dropdown">

												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">

													<i class="icon-cog"></i> 

													<span class="caret"></span>

												</button>

												<ul class="dropdown-menu" style="right:0px !important; left: auto">

													<?php if ($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_trash'] == '0') {?>

													<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&training_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']);?>
"><i class="icon-edit"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
</span></a></li>

													<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('trash');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=trash&training_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']);
echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
"><i class="icon-trash"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('trash');?>
</span></a></li>

													<?php } else { ?>

													<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('restore');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=restore&training_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']);
echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
"><i class="icon-refresh"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('restore');?>
</span></a></li>

													<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
" class="confirm_delete" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&training_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['training_id']);
echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
"><i class="icon-remove"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
</span></a></li>

													<?php }?>

												</ul>

											</div>

										</td>

									</tr>

									<?php
}
}
?>

								</table>

								<div class="statistical mt5">

									<table width="100%" border="0" cellpadding="3" cellspacing="0">

										<tr>

											<td width="50%" align="left">

												<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('statistical');?>
 <strong><?php echo $_smarty_tpl->tpl_vars['totalRecord']->value;?>
</strong> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('records');?>
/<strong><?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
</strong> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('page');?>
. <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('youareonpagenumber');?>
 <strong><?php echo $_smarty_tpl->tpl_vars['currentPage']->value;?>
</strong>

											</td>

											<td width="50%" align="right">

												<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('gotopage');?>
:

												<select name="page" onchange="window.location = this.options[this.selectedIndex].value">

													<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['listPageNumber']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = $__section_i_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

													<option <?php if ($_smarty_tpl->tpl_vars['listPageNumber']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)] == $_smarty_tpl->tpl_vars['currentPage']->value) {?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['link_page_current']->value;?>
&page=<?php echo $_smarty_tpl->tpl_vars['listPageNumber']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)];?>
"><?php echo $_smarty_tpl->tpl_vars['listPageNumber']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)];?>
</option>

													<?php
}
}
?>

												</select>

											</td>

										</tr>

									</table>

								</div>

							</div>

						</form>

					</div>

				</div>

			</div>

		</div>

	</div>

</div><?php }
}
