<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:42:31
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/policy/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ad6373ddf85_81058782',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b8d72a056bdc62eaba4253a6e5c47418eb6157d8' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/policy/default.tpl',
      1 => 1784691715,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ad6373ddf85_81058782 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<header class="ui-title-bar-container">

	<div class="ui-title-bar ui-title-bar--separator">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title">Chính sách bán hàng</h1>

			</div>

		</div>

		<div class="action-bar">

			<div class="ui-title-bar__mobile-primary-actions">

				<div class="ui-title-bar__actions">

					<div class="btn-group">

						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">

							+ <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
 <span class="caret"></span>

						</button>

						<ul class="dropdown-menu">

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_block_type']->value, '_oA', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oA']->value) {
?>

							<li><a href="javascript:void(0)" onClick="open_policy(this, event)" block_type="<?php echo $_smarty_tpl->tpl_vars['_oA']->value['property_id'];?>
" policy_id="0" class="policy_<?php echo $_smarty_tpl->tpl_vars['_oA']->value['property_id'];?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" >+ <?php echo $_smarty_tpl->tpl_vars['_oA']->value['title'];?>
</a></li>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</ul>

					</div>

				</div>

			</div>

		</div>

	</div>

</header>

<div class="clearfix"></div>

<form method="post" action="" enctype="multipart/form-data">

	<div class="ui-layout">

		<div class="ui-layout__sections">

			<div class="ui-layout__section">

				<div class="ui-layout__item">

					<div class="ui-card">

						<div class="next-tab__container">

							<ul class="next-tab__list filter-tab-list">

								<li class="filter-tab-item" data-tab-index="1">

									<a href="javascript:void(0);" target="ads" class="filter-tab filter-tab-active next-tab next-tab--is-active">Danh sách</a>

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

								<div class="pull-right">

									<a href="javascript:void(0)" class="btn text-white btn-danger btn-delete-all" clsTable="Policy" style="display: none"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('times',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Delete Options'));?>
 </a>

								</div>	

							</div>

							<div class="hastable">

								<table cellspacing="0" class="table table-vertical table-striped" width="100%">

									<thead><tr>

										<th class="text-center" width="5%">No.</th>

										<th class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Title');?>
</th>

										<th class="text-left" width="15%">Ngày áp dụng</th>

										<th class="text-left" width="15%">Phạm vi áp dụng</th>

										<th>Loại hình</th>

										<th>Loại quỹ</th>

										<th class="text-left" width="15%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('update');?>
</th>

										<th class="text-left" width="100px"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Action');?>
</th>

									</tr></thead>

									<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['allItem']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_0_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

									<?php $_smarty_tpl->_assignInScope('block_type', $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['block_type']);?>

									<tr class="<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)%2 == 0) {?>row1<?php } else { ?>row2<?php }?>">

										<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
</td>

										<td class="text-left"><a href="javascript:void(0);" class="bold" onClick="open_policy(this, event)" policy_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['policy_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getTitle($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['policy_id']);?>
</a></td>

										<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('clock-o',smarty_modifier_date_format($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['ms_date'],"%d/%m/%Y"));?>
</td>

										<td class="text-left">---</td>

										<td><?php echo $_smarty_tpl->tpl_vars['arr_property_cached']->value[$_smarty_tpl->tpl_vars['block_type']->value];?>
</td>

										<td><?php if ($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['applicable_fund_type'] == '0') {?>Sơ cấp<?php } else { ?>Thứ cấp<?php }?></td>

										<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('clock-o',smarty_modifier_date_format($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['upd_date'],"%d/%m/%Y %H:%M"));?>
</td>

										<td class="text-center" style="white-space:nowrap;">

											<div class="btn-group dropdown">

												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">

													<i class="icon-cog"></i> 

													<span class="caret"></span>

												</button>

												<ul class="dropdown-menu" style="right:0px !important; left: auto">

													<li><a href="javascript:void(0);" title="Chỉnh sửa" onClick="open_policy(this, event)" block_type="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['block_type'];?>
" policy_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['policy_id'];?>
">

														<i class="icon-edit"></i> 

														<span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
</span>

													</a></li>

													<li><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&policy_id=<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['policy_id'];?>
" title="Xóa" class="confirm_delete">

														<i class="icon-remove"></i> 

														<span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
</span>

													</a></li>

												</ul>

											</div>

										</td>

									</tr>

									<?php
}
}
?>

								</table>

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
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['listPageNumber']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = $__section_i_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_1_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
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

												</div>

											</td>

										</tr>

									</table>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</form>

<?php echo $_smarty_tpl->tpl_vars['script']->value;
}
}
