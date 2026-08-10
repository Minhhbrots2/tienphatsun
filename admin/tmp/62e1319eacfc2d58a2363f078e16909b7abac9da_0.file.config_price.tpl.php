<?php
/* Smarty version 3.1.33, created on 2026-08-06 18:59:58
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/config_price.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a74773ebc1ce7_95723271',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '62e1319eacfc2d58a2363f078e16909b7abac9da' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/config_price.tpl',
      1 => 1784691722,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a74773ebc1ce7_95723271 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="ui-title-bar-container ui-title-bar-container--full-width">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title w-100">Cấu hình giá Min-Max</h1>

				<p class="type--subdued"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('This system allows you to manage & edit static pages in Systems');?>
</p>

			</div>

		</div>

		<div class="action-bar">

			<div class="ui-title-bar__mobile-primary-actions">

				<div class="ui-title-bar__actions">

					<button type="button" onClick="$Core.property.open_field_config_price(this,event)" class="btn btn-default" field_config_price_id=""><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew'));?>
</button>

				</div>

			</div>

		</div>

	</div>

</div>

<div class="ui-layout ui-layout--full-width">

	<div class="ui-layout__sections">

		<div class="ui-layout__section">

			<div class="ui-layout__item">

				<div class="ui-card">

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

						<div class="hastable">

							<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">

								<thead><tr>

									<th class="text-left" width="5%">No.</th>

									<th class="text-left">Dự án</th>

									<th class="text-left" width="20%">Phân khu</th>

									<th class="text-left" width="20%">Loại hình</th>

									<th class="text-left" width="20%">Giá min</th>

									<th class="text-left" width="20%">Giá max</th>

									<th class="text-left" width="10%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
</th>

								</tr></thead>

								<tbody>

									<?php if (!empty($_smarty_tpl->tpl_vars['field_config_price']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['field_config_price']->value, '_oItem', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

											<tr>

												<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

												<td class="text-nowrap" data-label="Dự án"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['project_name'];?>
</td>

												<td class="text-nowrap" data-label="Phân khu"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['block_name'];?>
</td>

												<td class="text-nowrap" data-label="Phân khu"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_type_name'];?>
</td>

												<td class="text-nowrap" data-label="Giá min"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormat($_smarty_tpl->tpl_vars['_oItem']->value['min']);?>
</td>

												<td class="text-nowrap" data-label="Giá max"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormat($_smarty_tpl->tpl_vars['_oItem']->value['max']);?>
</td>

												<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">

													<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">

														<button class="btn btn-default" onClick="$Core.property.open_field_config_price(this,event)" class="btn btn-default" field_config_price_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>

														<button class="btn btn-default" onClick="$Core.property.save_field_config_price(this,event)" field_config_price_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-action="delete"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

													</div>

												</td>

											</tr>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php } else { ?>

										<tr><td class="text-center" colspan="5">Danh sách trống</td></tr>

									<?php }?>

								</tbody>

							</table>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div><?php }
}
