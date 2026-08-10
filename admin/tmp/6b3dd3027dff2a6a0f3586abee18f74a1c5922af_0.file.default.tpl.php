<?php
/* Smarty version 3.1.33, created on 2026-07-30 14:13:42
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/user/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6af9a6efd5a9_29254180',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6b3dd3027dff2a6a0f3586abee18f74a1c5922af' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/user/default.tpl',
      1 => 1784691759,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6af9a6efd5a9_29254180 (Smarty_Internal_Template $_smarty_tpl) {
?><header class="ui-title-bar-container ">

	<div class="ui-title-bar">

		<div class="ui-title-bar__navigation">

			<div class="ui-breadcrumbs">

				<a class="btn btn-default ui-breadcrumb" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=setting" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Setting');?>
">

					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>


					<span class="ui-breadcrumb__item"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Setting');?>
</span>

				</a>

			</div>

		</div>

	</div>

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Administrators');?>
</h1>

			</div>

		</div>

		<div class="action-bar">

			<div class="ui-title-bar__mobile-primary-actions">

				<div class="ui-title-bar__actions">

					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit" class="ui-button ui-button--primary ui-title-bar__action" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
</a>

				</div>

			</div>

		</div>

	</div>

</header>

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
" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('AllAdministrators');?>
</a>

							</li>

						</ul>

					</div>

					<div class="ui-card__section has-bulk-actions pages">

						<form method="post">

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

								<table class="table table-vertical table-striped" cellspacing="0" cellpadding="0" width="100%">

									<thead><tr>

										<th class="text-center" width="3%">No.</th>

										<th class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('First Name');?>
</th>

										<th class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('First Name');?>
</th>

										<th class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Username/Email');?>
</th>

										<th class="text-center" width="10%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Status');?>
</th>

										<th class="text-center" width="10%">Update bảng hàng</th>

										<th class="text-center" width="40px">Action</th>

									</tr></thead>

									<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['allItem']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_0_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

									<tr class="<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)%2 == 0) {?>row1<?php } else { ?>row2<?php }?>">

										<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
</td>

										<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['first_name'];?>
</td>

										<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['last_name'];?>
</td>

										<td class="text-left"><a title="Edit" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?admin&mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&user_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_id']);?>
" class="row-title"><strong><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_name'];?>
</strong></a></td>

										<td class="text-center">

											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="User" pkey="<?php echo $_smarty_tpl->tpl_vars['pkeyTable']->value;?>
" toField="is_active" sourse_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)][$_smarty_tpl->tpl_vars['pkeyTable']->value];?>
" rel="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_active'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Click to change status');?>
">

												<?php if ($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_active'] == '1') {?>

												<i class="fa fa-check-circle green"></i>

												<?php } else { ?>

												<i class="fa fa-minus-circle red"></i>

												<?php }?>

											</a>

										</td>

										<td class="text-center"><button class="btn btn-default" type="button" onClick="$Core.user.open_permiss_stock(this,event)" data-id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_id'];?>
">Chọn</button></td>

										<td class="text-center" style="white-space:nowrap;">

											<div class="btn-group">

												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">

													<i class="icon-cog"></i> 

													<span class="caret"></span>

												</button>

												<ul class="dropdown-menu" style="right:0px !important; left: auto">

													<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?admin&mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&user_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_id']);?>
"><i class="icon-edit"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
</span></a></li>

													<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
" class="confirm_delete" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?admin&mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&user_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_id']);?>
"><i class="icon-remove"></i> <span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
</span></a></li>

												</ul>

											</div>

										</td>

									</tr>	

									<?php
}
}
?>

								</table>

								<div class="clearfix"></div>

								<div class="t-grid-pager-boder">

									<div class="t-pager t-reset fix-margin-pager">

										<?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>


									</div>

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
