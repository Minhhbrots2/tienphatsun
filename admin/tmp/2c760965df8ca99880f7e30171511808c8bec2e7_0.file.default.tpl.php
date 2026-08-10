<?php
/* Smarty version 3.1.33, created on 2026-07-30 10:25:44
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ac438eae043_66291666',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2c760965df8ca99880f7e30171511808c8bec2e7' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/default.tpl',
      1 => 1784691719,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ac438eae043_66291666 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<header class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Dự án</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0)" onClick="$Core.project.sync_price_sheet(this, event)" project_id="0" class="ui-button mr-2 ui-button--transparent ui-title-bar__action" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sync Block');?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sync Block');?>
</a>
					<a href="javascript:void(0)" onClick="sync_search(this, event)" project_id="0" class="ui-button mr-2 ui-button--transparent ui-title-bar__action" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Sync search');?>
</a>
					<a href="javascript:void(0)" onClick="open_project(this, event)" project_id="0" class="ui-button ui-button--primary ui-title-bar__action" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
</a>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<form method="post" action="" enctype="multipart/form-data">
	<div class="ui-layout ui-layout--full-width">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<div class="ui-layout__item">
					<div class="ui-card">
						<div class="next-tab__container">
							<ul class="next-tab__list filter-tab-list">
								<li class="filter-tab-item">
									<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="filter-tab next-tab<?php if ($_smarty_tpl->tpl_vars['type_list']->value != 'Trash') {?> filter-tab-active next-tab--is-active<?php }?>">Danh sách dự án</a>
								</li>
								<li class="filter-tab-item">
									<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&type_list=Trash" class="filter-tab next-tab<?php if ($_smarty_tpl->tpl_vars['type_list']->value == 'Trash') {?> filter-tab-active next-tab--is-active<?php }?>">Thùng rác</a>
								</li>
							</ul>
						</div>
						<div class="clearfix"></div>
						<div id="project" class="ui-card__section has-bulk-actions">
							<div class="form-search form-inline">
								<div class="form-group">
									<div class="input-group">
										<input type="text" class="form-control" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('search');?>
" />
									</div>
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('search','Search');?>
</button>
							</div>
							<div class="hastable">
								<table cellspacing="0" cellpadding="0" class="table table-striped table-vertical table_project" style="width:100%">
									<thead><tr>
										<th class="text-center" width="5%">No.</th>
										<th class="text-left">Tên dự án</th>
										<th class="text-left" width="160px"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('update');?>
</th>
										<th class="text-center" width="220px"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Action');?>
</th>
									</tr></thead>
									<tbody>
										<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['allItem']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
										<?php $_smarty_tpl->_assignInScope('project_id', $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['project_id']);?>
										<tr class="<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)%2 == 0) {?>row1<?php } else { ?>row2<?php }?>">
											<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)+1;?>
</td>
											<td class="text-left">
												<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=overview&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"><strong class="font16"><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'];?>
</strong></a>
											</td>
											<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('clock-o',smarty_modifier_date_format($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['reg_date'],"%d/%m/%Y %H:%M"));?>
</td>
											<td class="text-center">
												<div class="fh-actions">
												<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=overview&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="btn btn-sm btn-primary" style="color:#fff !important"><i class="fa fa-th-large"></i> Tổng quan</a>
												<div class="btn-group">
													<button class="btn btn-sm iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"><i class="icon-cog"></i> <span class="caret"></span></button>
													<ul class="dropdown-menu" style="right:0px !important; left: auto">
														<?php if ($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_trash'] == '0') {?>
														<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"><i class="icon-edit"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Edit');?>
</span></a></li>
														<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('trash');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=trash&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;
echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
"><i class="icon-trash"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Trash');?>
</span></a></li>
														<li><a title="Xóa vĩnh viễn dự án và dữ liệu liên quan" class="js_delete_project" data-title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'], ENT_QUOTES, 'UTF-8', true);?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;
echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
"><i class="icon-remove"></i> <span>Xóa vĩnh viễn</span></a></li>
														<?php } else { ?>
														<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('restore');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=restore&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;
echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
"><i class="icon-refresh"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Restore');?>
</span></a></li>
														<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
" class="js_delete_project" data-title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'], ENT_QUOTES, 'UTF-8', true);?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;
echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
"><i class="icon-remove"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Delete');?>
</span></a></li>
														<?php }?>
													</ul>
												</div>
											</div>
											</td>
										</tr>
										<?php
}
}
?>
									</tbody>
								</table>
								<?php if ($_smarty_tpl->tpl_vars['totalPage']->value > 1) {?>
								<div class="statistical">
									<table width="100%" border="0" cellpadding="2" cellspacing="0">
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
											<td width="50%" class="text-right">
												<div class="d-inline-flex align-items-center">
													<span class="mr-2"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('gotopage');?>
:</span>
													<select name="page" class="form-control w-40" onchange="window.location = this.options[this.selectedIndex].value">
														<?php
$__section_p_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['listPageNumber']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_p_1_total = $__section_p_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_p'] = new Smarty_Variable(array());
if ($__section_p_1_total !== 0) {
for ($__section_p_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_p']->value['index'] = 0; $__section_p_1_iteration <= $__section_p_1_total; $__section_p_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_p']->value['index']++){
?>
														<option <?php if ($_smarty_tpl->tpl_vars['listPageNumber']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_p']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_p']->value['index'] : null)] == $_smarty_tpl->tpl_vars['currentPage']->value) {?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['link_page_current']->value;?>
&page=<?php echo $_smarty_tpl->tpl_vars['listPageNumber']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_p']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_p']->value['index'] : null)];?>
"><?php echo $_smarty_tpl->tpl_vars['listPageNumber']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_p']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_p']->value['index'] : null)];?>
</option>
														<?php
}
}
?>
													</select>
												</div>
											</td>
										</tr>
									</table>
								</div>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php }
}
