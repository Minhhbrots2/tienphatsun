<?php
/* Smarty version 3.1.33, created on 2026-07-30 13:41:05
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/stock/import_agent.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6af20118ab70_76666734',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1a69525a8c3acebc89ebbb0c58d6b97be50f093a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/stock/import_agent.tpl',
      1 => 1784691757,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6af20118ab70_76666734 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="ui-title-bar-container ui-title-bar-container--full-width">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title w-100"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Import bảng hàng Đại Lý');?>
</h1>

				<p class="type--subdued"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Quản lý toàn bộ Qũy Căn Hộ Dự Án có trong Hệ Thống');?>
</p>

			</div>

		</div>

		<div class="action-bar">

			<div class="btn-group btn-group-lg d-flex">

				<button class="btn bg-lg btn-default bg-white<?php if ($_smarty_tpl->tpl_vars['_ss_view']->value == 'morning') {?> active<?php }?>" onclick="$Core.stock.setView(this,event)" data-type="morning" data-doc_type="top">Sáng (01-11:59)</button>

				<button class="btn bg-lg btn-default<?php if ($_smarty_tpl->tpl_vars['_ss_view']->value == 'afternoon') {?> active<?php }?>" onclick="$Core.stock.setView(this,event)" data-type="afternoon" data-doc_type="top">Chiều (12->23:59)</button>

			</div>

		</div>

	</div>

</div>

<div class="ui-layout ui-layout--full-width">

	<div class="ui-layout__sections"><div class="ui-layout__section">

		<div class="ui-layout__item"><div class="ui-card">

			<div class="next-tab__container">

				<ul class="next-tab__list filter-tab-list">

					<li class="filter-tab-item" data-tab-index="1">

						<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">

							<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Danh sách đại lý');?>


						</a>

					</li>

				</ul>

			</div>

			<div class="ui-card__section has-bulk-actions pages">

				<div class="hastable">

					<table id="tableCall" cellspacing="0" class="table table-vertical table-striped" width="100%">

						<thead><tr>

							<!-- <th width="3%" class="text-center border-end">STT</th>-->

							<th width="10%" class="text-left">Đại lý</th>

							<th width="140px" class="text-right">Thu thập L.Cuối</th>

							<th width="6%" class="text-center">Craw</th>

							<th width="5%" class="text-center">Tổng (<?php echo $_smarty_tpl->tpl_vars['total_stocks']->value;?>
)</th>

							<th width="5%" class="text-center">PTG (<?php echo $_smarty_tpl->tpl_vars['total_price_sheets_mis']->value;?>
)</th>

							<th class="text-left">Link Google Sheet</th>

							<!-- <th width="80px" class="text-center">Tự động</th>-->

						</tr></thead>

						<?php if (!empty($_smarty_tpl->tpl_vars['list_agents']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_agents']->value, '_oG', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oG']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

							<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oG']->value['more_information']);?>

							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId'])) {?>

							<tr class="" <?php if (!empty($_smarty_tpl->tpl_vars['_oG']->value['has_update'])) {?>style="background-color:#deffe3 !important"<?php }?>>

								<!-- <td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td> -->

								<td class="text-left bold border-end"><?php echo $_smarty_tpl->tpl_vars['_oG']->value['title'];?>
</td>

								<td class="text-right border-end">

									<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId']) && !empty($_smarty_tpl->tpl_vars['more_information']->value['last_cronjob_time'])) {?>

										<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['more_information']->value['last_cronjob_time'],true);?>


										<a href="javascript:void(0);" onClick="$Core.stock.open_import_logs(this, event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_id'];?>
" data-toggle="tooltip" title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>

									<?php } else { ?>

										---

									<?php }?>

								</td>

								<td class="text-center border-end">

									<form action="" enctype="multipart/form-data">

										<div class="d-flex align-items-center">

											<button<?php if (empty($_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId'])) {?> disabled<?php }?> onClick="$Core.stock.start_import_agent(this,event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_id'];?>
" class="btn btn-sm btn-success mr-2"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('play','Crawl');?>
</button>

											<button onClick="$Core.stock.start_import_stock(this,event)" data-type="_COPY" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_id'];?>
" class="btn btn-sm btn-danger mr-2"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('play','Copy/Paste Excel');?>
</button>

											<button onClick="$Core.stock.choose_image(this,event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_id'];?>
" class="btn btn-sm btn-danger mr-2"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Upload Image');?>
</button>

											<button onClick="$Core.stock.update_ptg(this,event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_id'];?>
" class="btn btn-sm btn-danger mr-2"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('upload','Update PTG');?>
</button>

											<input type="file" name="images[]" onChange="$Core.stock.start_import_stock(this,event)" data-type="_IMAGE" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_id'];?>
" class="d-none file_upload" multiple>

										</div>

									</form>	

								</td>

								<td class="text-center bold border-end"><?php echo $_smarty_tpl->tpl_vars['_oG']->value['total_stock_in'];?>
</td>

								<td class="text-center bold border-end"><?php echo $_smarty_tpl->tpl_vars['_oG']->value['total_price_sheets_mis_in'];?>
</td>

								<td class="text-left">

									<a href="https://docs.google.com/spreadsheets/d/<?php echo $_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId'];?>
/edit#gid=0" target="_blank">Link cập nhật</a>

									<?php if (!empty($_smarty_tpl->tpl_vars['_oG']->value['intro'])) {?>

									<div class="alert alert-info m-0"><?php echo html_entity_decode($_smarty_tpl->tpl_vars['_oG']->value['intro']);?>
</div>

									<?php }?>

								</td>

								<!-- <td class="text-center">

									<label class="switch">

									  <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['more_information']->value['cron_automation_enable'] == '1') {?> checked<?php }?> name="cron_automation_enable" onChange="$Core.stock.cron_automation_enable(this, event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oG']->value['property_id'];?>
" value="1"  />

									  <span class="slider round"></span>

									</label>

								</td>-->

							</tr>

							<?php }?>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php }?>

					</table>

				</div>

			</div>

		</div></div>

	</div></div>

</div>

<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/stock.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
">

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jspreadsheet/jexcel.js"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jspreadsheet/jsuites.js"><?php echo '</script'; ?>
>

<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jspreadsheet/jsuites.css" type="text/css" />

<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jspreadsheet/jexcel.css" type="text/css" /><?php }
}
