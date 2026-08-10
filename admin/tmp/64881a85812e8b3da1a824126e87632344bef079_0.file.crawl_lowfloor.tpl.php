<?php
/* Smarty version 3.1.33, created on 2026-07-31 10:12:55
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/crawl/crawl_lowfloor.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c12b7633cb4_22400423',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '64881a85812e8b3da1a824126e87632344bef079' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/crawl/crawl_lowfloor.tpl',
      1 => 1784691583,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c12b7633cb4_22400423 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="ui-title-bar-container ui-title-bar-container--full-width">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title w-100">Danh sách đại lý cập nhật thấp tầng</h1>

				<p class="type--subdued mb-0"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('This system allows you to manage & edit static pages in Systems');?>
</p>

			</div>

		</div>		

		<button title="Hướng dẫn sử dụng" onclick="$Core.crawl.open_help(this, event)" data-type="lowfloor" data-toggle="ripple" class="btn btn-icon btn-outline-default"><i class="fa fa-info-circle" style="font-size:24px"></i></button>

	</div>

</div>

<div class="ui-layout ui-layout--full-width">

	<div class="ui-layout__sections"><div class="ui-layout__section">

		<div class="ui-layout__item"><div class="ui-card">

			<div class="next-tab__container">

				<ul class="next-tab__list filter-tab-list">

					<li class="filter-tab-item" data-tab-index="1">

						<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">

							<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('AllPages');?>


						</a>

					</li>

				</ul>

			</div>

			<div class="ui-card__section has-bulk-actions pages">

				<div class="hastable table-wrapper">

					<table class="table table-bordered mb-0" cellspacing="0" cellpadding="0" width="100%">

						<thead><tr>

							<th class="align-center text-right align-center" width="60px" rowspan="2"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
</th>

							<th class="align-center text-left" rowspan="2">Tiêu đề</th>

							<th class="align-center text-center" width="20%" colspan="<?php echo count($_smarty_tpl->tpl_vars['lst_project']->value);?>
">Phân khu</th>

						</tr>

						<tr>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_project']->value, '_project_name', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_project_name']->value) {
?>

							<th class="text-center" width="20%"><?php echo $_smarty_tpl->tpl_vars['_project_name']->value;?>
</th>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</tr></thead>

						<tbody>

						<?php if (!empty($_smarty_tpl->tpl_vars['lstAgency']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstAgency']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

							<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>

							<?php $_smarty_tpl->_assignInScope('crawl_lowfloor', $_smarty_tpl->tpl_vars['_oItem']->value['crawl_lowfloor']);?>

								<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
">

									<td class="text-center" data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">

										<button class="btn btn-icon btn-default" onClick="$Core.crawl.open_agency(this,event)" stock_type="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
" class="btn btn-default" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>

									</td>

									<td class="text-nowrap" data-label="Tiêu đề"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>

									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_project']->value, '_project_name', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_project_name']->value) {
?>

										<td class="text-center">

											<label class="switch">

												<input type="checkbox" onchange="$Core.crawl.handle_status(this, event)" stock_type="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" block_id="" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" value="1" class="switch_<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" name="is_crawl" <?php if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['is_crawl'])) {?>checked<?php }?>>

												<span class="slider round"></span>

											</label>

										</td>

									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

								</tr>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php } else { ?>

							<tr><td class="text-center" colspan="4">Danh sách trống</td></tr>

						<?php }?>

						</tbody>

					</table>

				</div>

			</div>

		</div></div>

	</div></div>

</div><?php }
}
