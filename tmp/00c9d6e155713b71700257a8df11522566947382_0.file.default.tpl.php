<?php
/* Smarty version 3.1.33, created on 2026-07-31 14:44:20
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/search/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c5254f0cd25_05283197',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '00c9d6e155713b71700257a8df11522566947382' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/search/default.tpl',
      1 => 1784299675,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c5254f0cd25_05283197 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
?>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">

	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

		<h4 class="fw-bold mb-0">Kết quả "<strong class="text-main fs-22"><?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
</strong>"</span></h4>

		<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('view_stock_resource')) {?>

		<a class="btn btn-sm btn-primary text-white <?php if ($_smarty_tpl->tpl_vars['hide_agent']->value == '1') {?> hide-agent<?php }?>" t1="Ẩn ĐL" t2="Hiện ĐL" onClick="$Core.search.toggle_agent(this, event)"><?php if ($_smarty_tpl->tpl_vars['hide_agent']->value == '1') {?>Hiện ĐL<?php } else { ?>Ẩn ĐL<?php }?></a>

		<?php }?>

	</div>

	<div class="card no-shadow">

		<div class="card-body">

			<?php if (!empty($_smarty_tpl->tpl_vars['lst_stocks']->value) || !empty($_smarty_tpl->tpl_vars['lst_results']->value) || !empty($_smarty_tpl->tpl_vars['lst_projects']->value) || !empty($_smarty_tpl->tpl_vars['list_agent_stocks']->value)) {?>

				<div class="list-results sssssssss">

					<?php if (!empty($_smarty_tpl->tpl_vars['lst_stocks']->value)) {?>

						<h4 class="position-relative search-header my-3">Căn hộ</h4>

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?><div class="row"><?php }?>

                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_stocks']->value, '_oStock', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStock']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

							<?php $_smarty_tpl->_assignInScope('block_id', $_smarty_tpl->tpl_vars['_oStock']->value['block_id']);?>

							<?php $_smarty_tpl->_assignInScope('building_id', $_smarty_tpl->tpl_vars['_oStock']->value['building_id']);?>

							<?php $_smarty_tpl->_assignInScope('project_id', $_smarty_tpl->tpl_vars['_oStock']->value['project_id']);?>

							<?php $_smarty_tpl->_assignInScope('bedroom_id', $_smarty_tpl->tpl_vars['_oStock']->value['bedroom_id']);?>

							<?php $_smarty_tpl->_assignInScope('agency_id', $_smarty_tpl->tpl_vars['_oStock']->value['agency_id']);?>

							<?php $_smarty_tpl->_assignInScope('home_direction_id', $_smarty_tpl->tpl_vars['_oStock']->value['home_direction_id']);?>

							<?php $_smarty_tpl->_assignInScope('oneStatus', $_smarty_tpl->tpl_vars['_oStock']->value['oneStatus']);?>

							<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oStock']->value['more_information']);?>

                            <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

							<div class="list-result-item<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?> border-0<?php }?>">

                                <h4 class="fs-6 d-flex justify-content-between mb-2">

									<a onClick="$Core.helper.open_stock(<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
)" href="javascript:void(0)"><?php if ($_smarty_tpl->tpl_vars['_oStock']->value['is_fund_type']) {?><span class="badge bg-green py-1 mr-1" title="Thứ cấp">TC</span><?php }?> <?php echo $_smarty_tpl->tpl_vars['core']->value->replaceString($_smarty_tpl->tpl_vars['_oStock']->value['ms_code'],$_smarty_tpl->tpl_vars['keyword']->value);?>
, <?php if ($_smarty_tpl->tpl_vars['_oStock']->value['stock_type'] == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>Dãy<?php } else { ?>Tòa<?php }?> <?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['building_id']->value];?>
-<?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['block_id']->value];?>
-<?php echo $_smarty_tpl->tpl_vars['arr_cached_project']->value[$_smarty_tpl->tpl_vars['project_id']->value];?>
</a>

									<span class="text-nowrap"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormatV2($_smarty_tpl->tpl_vars['more_information']->value['total_price_vat'],3);?>
 tỷ</span>

								</h4>

								<div class="d-flex align-items-center justify-content-between">

									<div class="fs-13">

										<span class="label d-inline-block mr-1" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['textcolor'];?>
 ;background:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['bgcolor'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['status_name'];?>
</span>

										<span class="gdwYosCzip  mr-1">

											<i class="re__icon-size--sm"></i>

											<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_TT'];?>
 m2

										</span>

										<span class="gdwYosCzip  mr-1">

											<i class="re__icon-bedroom--sm"></i> 

											<?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['bedroom_id']->value];?>


										</span>

										<span class="gdwYosCzip">

											<i class="re__icon-ying-yang--xl"></i>

											<?php echo $_smarty_tpl->tpl_vars['arr_cached_query']->value[$_smarty_tpl->tpl_vars['home_direction_id']->value];?>


										</span>

									</div>

									<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('edit_stock_advanced') && $_smarty_tpl->tpl_vars['_oStock']->value['status_id'] != @constant('_STOCK_STATUS_SOLD_ID')) {?>

									<div class="d-flex gap-1">

										<span class="badge re__label-agency<?php if ($_smarty_tpl->tpl_vars['hide_agent']->value == '1') {?> d-none<?php }?> text-nowrap bg-label-secondary">

											<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->truncate($_smarty_tpl->tpl_vars['arr_cached_query']->value[$_smarty_tpl->tpl_vars['agency_id']->value],2,'');?>


										</span>

										<?php if ($_smarty_tpl->tpl_vars['hide_stock_globe']->value == '1') {?><button stock_id="<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
" class="btn btn-sm px-1 py-0 <?php if ($_smarty_tpl->tpl_vars['clsStock']->value->checkShow($_smarty_tpl->tpl_vars['_oStock']->value['show_website'],'MOC')) {?>btn-outline-primary<?php } else { ?>btn-outline-default<?php }?>" onClick="$Core.helper.hide_stock_MOC(this,event)" type="button">MOC</button><?php }?>

									</div>

									<?php }?>

								</div>

							</div>

                            <?php } else { ?>

							<?php $_smarty_tpl->_assignInScope('list_price_configs', $_smarty_tpl->tpl_vars['_oStock']->value['list_price_configs']);?>

                            <div class="col-12">

                                <div class="list-result-item">

                                    <h4 class="fs-6 mb-2">

                                        <a onClick="$Core.helper.open_stock(<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
)" href="javascript:void(0)"><?php if ($_smarty_tpl->tpl_vars['_oStock']->value['is_fund_type']) {?><span class="badge bg-green py-1 mr-1" title="Thứ cấp">TC</span><?php }
echo $_smarty_tpl->tpl_vars['core']->value->replaceString($_smarty_tpl->tpl_vars['_oStock']->value['ms_code'],$_smarty_tpl->tpl_vars['keyword']->value);?>
, <?php if ($_smarty_tpl->tpl_vars['_oStock']->value['stock_type'] == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>Dãy<?php } else { ?>Tòa<?php }?> <?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['building_id']->value];?>
-<?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['block_id']->value];?>
-<?php echo $_smarty_tpl->tpl_vars['arr_cached_project']->value[$_smarty_tpl->tpl_vars['project_id']->value];?>
</a>

                                    </h4>

									<div class="d-flex align-items-center justify-content-between">

										<div class="d-flex align-items-center gap-2 fs-13">

											<span class="label d-inline-block" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['textcolor'];?>
; background:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['bgcolor'];?>
">

												<?php if ($_smarty_tpl->tpl_vars['_oStock']->value['status_id'] == @constant('_STOCK_STATUS_SOLD_ID')) {?>

													Đã bán

												<?php } else { ?>

													<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['total_price_vat'])) {?>

														<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormatV2($_smarty_tpl->tpl_vars['more_information']->value['total_price_vat'],3);?>
 tỷ

													<?php } else { ?>

														Check

													<?php }?>

												<?php }?>

											</span>

											<?php if (!empty($_smarty_tpl->tpl_vars['list_price_configs']->value) && $_smarty_tpl->tpl_vars['_oStock']->value['status_id'] != @constant('_STOCK_STATUS_SOLD_ID')) {?>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_price_configs']->value, '_oPrice', false, NULL, 'k', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oPrice']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['index'];
?>

												<span class="label d-inline-block" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['textcolor'];?>
; background:<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['first'] : null)) {
echo $_smarty_tpl->tpl_vars['oneStatus']->value['bgcolor'];
} else {
echo $_smarty_tpl->tpl_vars['_oPrice']->value['bgcolor'];?>
; color:var(--bs-white)<?php }?>"><?php echo $_smarty_tpl->tpl_vars['_oPrice']->value['title'];?>
 : <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormatV2($_smarty_tpl->tpl_vars['_oPrice']->value['price'],3);?>
 tỷ</span>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php }?>

											<span class="gdwYosCzip">

												<i class="re__icon-size--sm"></i>

												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_TT'];?>
 m2

											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-bedroom--sm"></i> 

												<?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['bedroom_id']->value];?>


											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-ying-yang--xl"></i>

												<?php echo $_smarty_tpl->tpl_vars['arr_cached_query']->value[$_smarty_tpl->tpl_vars['home_direction_id']->value];?>


											</span>

										</div>

										<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('edit_stock_advanced') && $_smarty_tpl->tpl_vars['_oStock']->value['status_id'] != @constant('_STOCK_STATUS_SOLD_ID')) {?>

										<div class="d-flex gap-2">

											<span class="badge<?php if ($_smarty_tpl->tpl_vars['hide_agent']->value == '1') {?> d-none<?php }?> re__label-agency bg-label-secondary"><?php echo $_smarty_tpl->tpl_vars['arr_cached_query']->value[$_smarty_tpl->tpl_vars['agency_id']->value];?>
</span>

											<?php if ($_smarty_tpl->tpl_vars['hide_stock_globe']->value == '1') {?><button stock_id="<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
" class="btn btn-sm py-1/2 <?php if ($_smarty_tpl->tpl_vars['clsStock']->value->checkShow($_smarty_tpl->tpl_vars['_oStock']->value['show_website'],'MOC')) {?>btn-outline-primary<?php } else { ?>btn-outline-default<?php }?>" onClick="$Core.helper.hide_stock_MOC(this,event)" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check-square-o','MOC');?>
</button><?php }?>

										</div>

										<?php }?>

									</div>

                                </div>

                            </div>

                            <?php }?>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                        <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?></div><?php }?>

					<?php }?>

					<?php if (!empty($_smarty_tpl->tpl_vars['list_agent_stocks']->value)) {?>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_agent_stocks']->value, '_oG');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oG']->value) {
?>

						<?php $_smarty_tpl->_assignInScope('list_stocks', $_smarty_tpl->tpl_vars['_oG']->value['list_stocks']);?>

						<?php if (!empty($_smarty_tpl->tpl_vars['list_stocks']->value)) {?>

							<h4 class="position-relative search-header my-3"><?php echo $_smarty_tpl->tpl_vars['_oG']->value['title'];?>
(<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_code'];?>
)</h4>

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?><div class="row"><?php }?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_stocks']->value, '_oStock', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStock']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

								<?php $_smarty_tpl->_assignInScope('project_id', $_smarty_tpl->tpl_vars['_oStock']->value['project_id']);?>

								<?php $_smarty_tpl->_assignInScope('block_id', $_smarty_tpl->tpl_vars['_oStock']->value['block_id']);?>

								<?php $_smarty_tpl->_assignInScope('building_id', $_smarty_tpl->tpl_vars['_oStock']->value['building_id']);?>

								<?php $_smarty_tpl->_assignInScope('bedroom_id', $_smarty_tpl->tpl_vars['_oStock']->value['bedroom_id']);?>

								<?php $_smarty_tpl->_assignInScope('agency_id', $_smarty_tpl->tpl_vars['_oStock']->value['agency_id']);?>

								<?php $_smarty_tpl->_assignInScope('home_direction_id', $_smarty_tpl->tpl_vars['_oStock']->value['home_direction_id']);?>

								<?php $_smarty_tpl->_assignInScope('oneStatus', $_smarty_tpl->tpl_vars['_oStock']->value['oneStatus']);?>

								<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oStock']->value['more_information']);?>

								<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

								<div class="list-result-item<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?> border-0<?php }?>">

									<h4 class="fs-6 d-flex justify-content-between mb-2">

										<a onClick="$Core.helper.open_stock(<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
)" href="javascript:void(0)"><?php if ($_smarty_tpl->tpl_vars['_oStock']->value['is_fund_type']) {?><span class="badge bg-green py-1 mr-1" title="Thứ cấp">TC</span><?php }
echo $_smarty_tpl->tpl_vars['core']->value->replaceString($_smarty_tpl->tpl_vars['_oStock']->value['ms_code'],$_smarty_tpl->tpl_vars['keyword']->value);?>
, <?php if ($_smarty_tpl->tpl_vars['_oStock']->value['stock_type'] == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>Dãy<?php } else { ?>Tòa<?php }?> <?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['building_id']->value];?>
-<?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['block_id']->value];?>
-<?php echo $_smarty_tpl->tpl_vars['arr_cached_project']->value[$_smarty_tpl->tpl_vars['project_id']->value];?>
</a>

										<span><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormatV2($_smarty_tpl->tpl_vars['more_information']->value['total_price_vat'],3);?>
 tỷ</span>

									</h4>

									<div class="d-flex align-items-center justify-content-between">

										<div class="fs-13">

											<span class="label d-inline-block mr-1" style="transform:translateY(-2px); -moz-transform:translateY(-2px); -webkit-transform:translateY(-2px); color:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['textcolor'];?>
 ;background:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['bgcolor'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['status_name'];?>
</span>

											<span class="gdwYosCzip mr-1">

												<i class="re__icon-size--sm"></i>

												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_TT'];?>
 m2

											</span>

											<span class="gdwYosCzip mr-1">

												<i class="re__icon-bedroom--sm"></i> 

												<?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['bedroom_id']->value];?>


											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-ying-yang--xl"></i>

												<?php echo $_smarty_tpl->tpl_vars['arr_cached_query']->value[$_smarty_tpl->tpl_vars['home_direction_id']->value];?>


											</span>

										</div>

										<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('edit_stock_advanced') && $_smarty_tpl->tpl_vars['_oStock']->value['status_id'] != @constant('_STOCK_STATUS_SOLD_ID')) {?>

										<div class="d-flex gap-1">

											<span class="badge<?php if ($_smarty_tpl->tpl_vars['hide_agent']->value == '1') {?> d-none<?php }?> re__label-agency bg-label-secondary">

												<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->truncate($_smarty_tpl->tpl_vars['arr_cached_query']->value[$_smarty_tpl->tpl_vars['agency_id']->value],2,'');?>


											</span>

											<?php if ($_smarty_tpl->tpl_vars['hide_stock_globe']->value == '1') {?><button type="button" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
" class="btn btn-sm px-1 py-0 <?php if ($_smarty_tpl->tpl_vars['clsStock']->value->checkShow($_smarty_tpl->tpl_vars['_oStock']->value['show_website'],'MOC')) {?>btn-outline-primary<?php } else { ?>btn-outline-default<?php }?>" onClick="$Core.helper.hide_stock_MOC(this,event)">MOC</button><?php }?>

										</div>

										<?php }?>

									</div>

								</div>

								<?php } else { ?>

								<?php $_smarty_tpl->_assignInScope('list_price_configs', $_smarty_tpl->tpl_vars['_oStock']->value['list_price_configs']);?>

								<div class="col-12">

									<div class="list-result-item">

										<h4 class="fs-6 mb-1">

											<a onClick="$Core.helper.open_stock(<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
)" href="javascript:void(0)"><?php if ($_smarty_tpl->tpl_vars['_oStock']->value['is_fund_type']) {?><span class="badge bg-green py-1 mr-1" title="Thứ cấp">TC</span><?php }
echo $_smarty_tpl->tpl_vars['core']->value->replaceString($_smarty_tpl->tpl_vars['_oStock']->value['ms_code'],$_smarty_tpl->tpl_vars['keyword']->value);?>
, <?php if ($_smarty_tpl->tpl_vars['_oStock']->value['stock_type'] == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>Dãy<?php } else { ?>Tòa<?php }?> <?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['building_id']->value];?>
-<?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['block_id']->value];?>
-<?php echo $_smarty_tpl->tpl_vars['arr_cached_project']->value[$_smarty_tpl->tpl_vars['project_id']->value];?>
</a>

										</h4>

										<div class="d-flex align-items-center justify-content-between gap-2">

											<div class="d-flex align-items-center gap-2 fs-13">

											<?php if (!empty($_smarty_tpl->tpl_vars['list_price_configs']->value) && $_smarty_tpl->tpl_vars['_oStock']->value['status_id'] != @constant('_STOCK_STATUS_SOLD_ID')) {?>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_price_configs']->value, '_oPrice', false, NULL, 'k', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oPrice']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['index'];
?>

												<span class="label d-inline-block" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['textcolor'];?>
; background:<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['first'] : null)) {
echo $_smarty_tpl->tpl_vars['oneStatus']->value['bgcolor'];
} else {
echo $_smarty_tpl->tpl_vars['_oPrice']->value['bgcolor'];?>
; color:var(--bs-white)<?php }?>"><?php echo $_smarty_tpl->tpl_vars['_oPrice']->value['title'];?>
 : <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->shortNumber($_smarty_tpl->tpl_vars['_oPrice']->value['price']);?>
</span>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php } else { ?>

												<span class="label d-inline-block" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['textcolor'];?>
 ;background:<?php echo $_smarty_tpl->tpl_vars['oneStatus']->value['bgcolor'];?>
">

													<?php if ($_smarty_tpl->tpl_vars['_oStock']->value['status_id'] == @constant('_STOCK_STATUS_SOLD_ID')) {?>

														Đã bán

													<?php } else { ?>

														<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['total_price_vat'])) {?>

															<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->shortNumber($_smarty_tpl->tpl_vars['more_information']->value['total_price_vat']);?>


														<?php } else { ?>

															Check

														<?php }?>

													<?php }?>

												</span>

											<?php }?>

											<span class="gdwYosCzip">

												<i class="re__icon-size--sm"></i>

												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_TT'];?>
 m2

											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-bedroom--sm"></i> 

												<?php echo $_smarty_tpl->tpl_vars['arr_cached_property']->value[$_smarty_tpl->tpl_vars['bedroom_id']->value];?>


											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-ying-yang--xl"></i>

												<?php echo $_smarty_tpl->tpl_vars['arr_cached_query']->value[$_smarty_tpl->tpl_vars['home_direction_id']->value];?>


											</span></div>

											<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('edit_stock_advanced') && $_smarty_tpl->tpl_vars['_oStock']->value['status_id'] != @constant('_STOCK_STATUS_SOLD_ID')) {?>

											<div class="d-flex gap-2">

												<span class="badge<?php if ($_smarty_tpl->tpl_vars['hide_agent']->value == '1') {?> d-none<?php }?> re__label-agency bg-label-secondary"><?php echo $_smarty_tpl->tpl_vars['arr_cached_query']->value[$_smarty_tpl->tpl_vars['agency_id']->value];?>
</span>

												<?php if ($_smarty_tpl->tpl_vars['hide_stock_globe']->value == '1') {?><button stock_id="<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['stock_id'];?>
" class="btn btn-sm py-1/2 <?php if ($_smarty_tpl->tpl_vars['clsStock']->value->checkShow($_smarty_tpl->tpl_vars['_oStock']->value['show_website'],'MOC')) {?>btn-outline-primary<?php } else { ?>btn-outline-default<?php }?>" onClick="$Core.helper.hide_stock_MOC(this,event)" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check-square-o','MOC');?>
</button><?php }?>

											</div>

											<?php }?>

										</div>

									</div>

								</div>

								<?php }?>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>	

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?></div><?php }?>

						<?php }?>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<?php }?>

					<?php if (!empty($_smarty_tpl->tpl_vars['lst_projects']->value)) {?>

						<h4 class="position-relative search-header my-3">Tòa nhà, phân khu</h4>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_projects']->value, '_oPro');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oPro']->value) {
?>

						<?php $_smarty_tpl->_assignInScope('list_props', $_smarty_tpl->tpl_vars['_oPro']->value['list_props']);?>

                        <?php $_smarty_tpl->_assignInScope('list_attrs', $_smarty_tpl->tpl_vars['_oPro']->value['list_attrs']);?>

						<div class="list-result-item">

							<h4 class="fs-6 mb-1">

                                <a target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['_oPro']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->replaceString($_smarty_tpl->tpl_vars['_oPro']->value['title'],$_smarty_tpl->tpl_vars['keyword']->value);?>
</a>

                                <?php if (!empty($_smarty_tpl->tpl_vars['list_attrs']->value)) {?>

                                <div class="d-flex flex-wrap my-1 gap-1">

                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_attrs']->value, '_oAttr');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oAttr']->value) {
?>

                                    <span class="badge bg-label-default mb-1 xs:mb-1" style="color:#696cff"><?php echo $_smarty_tpl->tpl_vars['_oAttr']->value['title'];?>
: <?php echo $_smarty_tpl->tpl_vars['_oAttr']->value['content'];?>
</span>

                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                                </div>

                                <?php }?>

                            </h4>

							<a class="text-muted"><?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['_oPro']->value['intro']),100);?>
</a>

                            <?php if (!empty($_smarty_tpl->tpl_vars['list_props']->value)) {?>

							<div class="d-flex my-2 gap-1">

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_props']->value, '_oProp');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProp']->value) {
?>

								<a href="<?php echo $_smarty_tpl->tpl_vars['_oProp']->value['link'];?>
" data-fancybox<?php if ($_smarty_tpl->tpl_vars['_oProp']->value['is_driver'] == '1') {?> data-type="iframe"<?php }?> class="badge bg-label-primary"><?php echo $_smarty_tpl->tpl_vars['_oProp']->value['title'];?>
</a>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							</div>

							<?php }?>

						</div>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<?php }?>

					<?php if (!empty($_smarty_tpl->tpl_vars['lst_results']->value)) {?>

						<h4 class="position-relative search-header my-3">Thông tin <span class="fs-13 text-muted">(<span class="text-main fs-bold"><?php echo count($_smarty_tpl->tpl_vars['lst_results']->value);?>
</span> kết quả)</span></h4>

						<div class="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>form-<?php }?>row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-5">

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_results']->value, '_oResult');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oResult']->value) {
?> 

								<?php $_smarty_tpl->_assignInScope('list_docs', $_smarty_tpl->tpl_vars['_oResult']->value['list_docs']);?>

								<?php $_smarty_tpl->_assignInScope('result_id', $_smarty_tpl->tpl_vars['_oResult']->value['id']);?>							

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_docs']->value, '_oDoc', false, 'k_doc', 'n_doc', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_doc']->value => $_smarty_tpl->tpl_vars['_oDoc']->value) {
?>

									<div class="col mb-4">

										<?php $_smarty_tpl->_assignInScope('oneItem', $_smarty_tpl->tpl_vars['_oDoc']->value);?>

										<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('item_doc',array('_type'=>"search",'oneItem'=>$_smarty_tpl->tpl_vars['oneItem']->value,'result_id'=>$_smarty_tpl->tpl_vars['result_id']->value,'keyword'=>$_smarty_tpl->tpl_vars['keyword']->value,'_oResult'=>$_smarty_tpl->tpl_vars['_oResult']->value));?>


									</div>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</div>

					<?php }?>

				</div>

			<?php } else { ?>

				<div class="p-0 p-lg-5 text-center">

					<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/illustration-empty-results.svg"<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> width="100%"<?php }?> loading="lazy" />

					<p>Không tìm thấy kết quả phù hợp<p>

				</div>

			<?php }?>

		</div>

	</div>

</div><?php }
}
