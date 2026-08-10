<?php
/* Smarty version 3.1.33, created on 2026-08-08 15:03:57
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/stock/_ajax_stock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76e2ed99f098_92727098',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bb2aa43d57e76b6545e66ed027d9b32aea999f5f' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/stock/_ajax_stock.tpl',
      1 => 1784299676,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76e2ed99f098_92727098 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['total_all_stocks']->value > '0' && !empty($_smarty_tpl->tpl_vars['lst_projects']->value)) {?>

	<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BUSINESS_AREA') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('PROJECT_DIRECTOR') || $_smarty_tpl->tpl_vars['profile_id']->value == 289) {?>

		<div class="card mt-2">

			<div class="d-flex flex-wrap gap-2 ">

				<div class="table-freeze border-<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_code'];?>
 overflow-x-auto text-nowrap w-100">

					<table cellpadding="0" cellspacing="0" class="table mb-0 dragable table-stock table-stock_<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
 table-stock-color table-stock-<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_code'];?>
" width="100%">

						<thead class="bg-main text-white">

							<tr class="nohover" style="background: inherit !important">

								<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

								<th class="pheader  align-center text-center">STT</th>

								<?php }?>

								<th class="pheader  align-center text-left">Dự án</th>

								<th class="pheader  align-center text-center">14 ngày qua</th>

								<th class="pheader  align-center text-center">7 ngày qua</th>

								<th class="pheader  align-center text-center">3 ngày qua</th>

								<th class="pheader  align-center text-center">Tổng căn</th>

								<th class="pheader  align-center text-center">Tổng VAT</th>

								<th class="pheader  align-center text-center">Tỷ lệ</th>

							</tr>

						</thead>

						<?php if (!empty($_smarty_tpl->tpl_vars['arr_total_project']->value)) {?>

						<tbody>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_total_project']->value, '_oItem', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>	

								<tr class="rowspan building_<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
">

									<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

									<td class="text-center" width="40px"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

									<?php }?>

									<td  class="text-left text-break"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['project_name'];?>
</td>

									<td  class="text-center text-break"><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_sold'];?>
</strong> căn bán</td>

									<td  class="text-center text-break"><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_sold_7'];?>
</strong> căn bán</td>

									<td  class="text-center text-break"><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_sold_3'];?>
</strong> căn bán</td>

									<td  class="text-center text-break" ><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_dq'];?>
</strong> căn</td>

									<td  class="text-center text-break" ><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormatV3($_smarty_tpl->tpl_vars['_oItem']->value['total_grand'],1);?>
</strong> tỷ</td>

									<td  class="text-center text-break" ><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getPercent($_smarty_tpl->tpl_vars['_oItem']->value['total_dq'],$_smarty_tpl->tpl_vars['total_dq']->value);?>
</strong>%</td>

								</tr>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<tr class="rowspan building_<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
">

								<td class="pheader text-center text-upper fw-bold fs-18 h-px-40 bg-lighter" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>colspan="2"<?php }?>>Tổng</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['total_sold']->value;?>
</strong> căn bán</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['total_sold_7']->value;?>
</strong> căn bán</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['total_sold_3']->value;?>
</strong> căn bán</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['total_dq']->value;?>
</strong> căn</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormatV3($_smarty_tpl->tpl_vars['total_grand']->value,1);?>
</strong> tỷ</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ></td>

							</tr>

						</tbody>

						<?php }?>

					</table>

				</div>

			</div>

		</div>

	<?php }?>

	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_projects']->value, '_oProject');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
?>

	<?php $_smarty_tpl->_assignInScope('list_blocks', $_smarty_tpl->tpl_vars['_oProject']->value['list_blocks']);?>

	<div class="w-100 my-2">

		<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>

			<?php $_smarty_tpl->_assignInScope('block_id', $_smarty_tpl->tpl_vars['_oBlock']->value['property_id']);?>

			<?php if ($_smarty_tpl->tpl_vars['_oBlock']->value['total_stocks'] > '0') {?>

			<div class="table-freeze border-<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_code'];?>
 overflow-x-auto text-nowrap mb-2" style="border-color:<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['bgcolor'];?>
 ">

				<table cellpadding="0" cellspacing="0" class="table mb-0 dragable table-stock table-stock_<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
 table-stock-color table-stock-<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_code'];?>
" width="100%">

					<?php $_smarty_tpl->_assignInScope('list_buildings', $_smarty_tpl->tpl_vars['_oBlock']->value['list_buildings']);?>

					<?php $_smarty_tpl->_assignInScope('list_price_field', $_smarty_tpl->tpl_vars['_oBlock']->value['list_price_field']);?>

					<thead style="background: <?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['bgcolor'];?>
;color: <?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['textcolor'];?>
"><tr>

						<th class="pheader text-left text-upper" colspan="20">

							<div class="d-flex align-items-center justify-content-between">

								<strong class="fs-6">Quỹ căn <?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</strong>

								<span></span>

							</div>

						</th>

					</tr>

					<tr class="nohover" style="background: inherit !important">

						<th class="pcell align-center text-center" width="40" style="max-width: 100px !important">Tòa</th>

						<th class="pcell align-center text-left">Mã căn</th>

						<th class="pcell align-center text-center">Giá VAT</th>

						<?php if (!empty($_smarty_tpl->tpl_vars['list_price_field']->value)) {?>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_price_field']->value, '_oField');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oField']->value) {
?>

							<th class="pcell align-center text-center"><?php echo $_smarty_tpl->tpl_vars['_oField']->value;?>
</th>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						<?php }?>

						<th class="pcell align-center text-center">Vẽ View</th>

						<th class="pcell align-center text-left">Phiếu TG</th>

						<th class="pcell align-center text-center">Loại căn</th>

						<th class="pcell align-center text-center">Hướng</th>

						<th class="pcell align-center text-center">View</th>

						<th class="pcell align-center text-center">DT_TT</th>

						<th class="pcell align-center text-center">Loại hình</th>

						<?php if ($_smarty_tpl->tpl_vars['block_id']->value == @constant('_PROJECT_BLOCK_PARKLAND_ID')) {?>

						<th class="pcell align-center text-center">CSBH ngày</th>

						<th class="pcell align-center text-center">Thưởng sale</th>

						<th class="pcell align-center text-center">Tình trạng</th>

						<?php } else { ?>

						<th class="pcell align-center text-center">Thưởng sale</th>

						<th class="pcell align-center text-center">CSBH ngày</th>

						<?php }?>

					</tr></thead>

					<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>

					<tbody style="color:<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['bgcolor'];?>
">

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oBuilding', false, NULL, 'kk', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBuilding']->value) {
?>

							<?php $_smarty_tpl->_assignInScope('building_id', $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id']);?>

							<?php $_smarty_tpl->_assignInScope('list_stocks', $_smarty_tpl->tpl_vars['_oBuilding']->value['list_stocks']);?>

							<?php if (!empty($_smarty_tpl->tpl_vars['list_stocks']->value)) {?>

								<tr class="rowspan building_<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
">

									<td width="40px" class="text-center text-break" rowspan="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['total_stocks'];?>
" style="max-width: 100px !important">

										<?php if (!empty($_smarty_tpl->tpl_vars['_oBuilding']->value['title_vn'])) {?>

											<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title_vn'];?>


										<?php } else { ?>

											<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>


										<?php }?>

									</td>

								</tr>

								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_stocks']->value, '_oStock', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStock']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

								<?php $_smarty_tpl->_assignInScope('stock_id', $_smarty_tpl->tpl_vars['_oStock']->value['stock_id']);?>

								<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oStock']->value['more_information']);?>

								<tr class="building_<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];
if (isset($_smarty_tpl->tpl_vars['more_information']->value['markup_price']) && $_smarty_tpl->tpl_vars['more_information']->value['markup_price'] == '1') {?> stock_mark<?php }
if ($_smarty_tpl->tpl_vars['_oStock']->value['is_sp_mech'] == '1') {?> is_sp_mech<?php }?>"  style="background-color: <?php echo $_smarty_tpl->tpl_vars['_oStock']->value['bg_stock_dq'];?>
">

									<td class="text-left"><?php if ($_smarty_tpl->tpl_vars['_oStock']->value['is_sp_mech'] == '1') {?><span title="Cơ chế đặc biệt">⭐</span><?php }?><a href="javascript:void(0);" class="cursor-pointer fw-bold text-link" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>onClick="$Core.helper.open_stock(<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
);"<?php } else { ?>data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-toggle="webui-popover" data-placement="auto" data-trigger="click" data-width="350"<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['ms_code'];
if (!empty($_smarty_tpl->tpl_vars['_oStock']->value['is_fund_type'])) {?><span class="text_fund_type" title="Thứ cấp"></span><?php }?></a></td>

									<td class="text-center<?php if ($_smarty_tpl->tpl_vars['_oProject']->value['project_id'] == @constant('_PROJECT_VHOP2_ID')) {?> fw-bold text-red<?php }?>"><?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['total_price_vat'])) {
echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormatV3($_smarty_tpl->tpl_vars['more_information']->value['total_price_vat'],3);
} else { ?>--<?php }?></td>

									<?php if (!empty($_smarty_tpl->tpl_vars['list_price_field']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_price_field']->value, '_oText', false, '_oField');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oField']->value => $_smarty_tpl->tpl_vars['_oText']->value) {
?>

										<td class="<?php echo $_smarty_tpl->tpl_vars['_oField']->value;?>
 price align-center text-center">

											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value[$_smarty_tpl->tpl_vars['_oField']->value])) {?>

												<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormatV3($_smarty_tpl->tpl_vars['more_information']->value[$_smarty_tpl->tpl_vars['_oField']->value],3);?>


											<?php } else { ?>

												--

											<?php }?>

										</td>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php }?>

									<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['html_stock_posters'];?>
</td>

									<td class="text-nowrap"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['html_image_sheets'];?>
</td>

									<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['bedroom_name'];?>
</td>

									<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['home_direction_name'];?>
</td>

									<td class="text-center">

										<?php if (!empty($_smarty_tpl->tpl_vars['_oStock']->value['view_id'])) {?>

											<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['view_name'];?>


										<?php } else { ?>

											--

										<?php }?>

									</td>

									<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['DT_TT'];?>
</td>

									<td class="text-center">

										<?php if (!empty($_smarty_tpl->tpl_vars['_oStock']->value['is_fund_type'])) {?>

											<span class="text-success" title="Thứ cấp">Thứ cấp</span>

										<?php } else { ?>

											<?php echo $_smarty_tpl->tpl_vars['_oStock']->value['type_name'];?>


										<?php }?>

									</td>

									<?php if ($_smarty_tpl->tpl_vars['block_id']->value == @constant('_PROJECT_BLOCK_PARKLAND_ID')) {?>

									<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['csbh'];?>
</td>

									<td class="text-center">

										<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_bonus'])) {?>

											<?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_bonus'];?>


										<?php } else { ?>

											--

										<?php }?>

									</td>

									<td class="text-center">

										<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['first_payment_amount'])) {?>

											<?php echo $_smarty_tpl->tpl_vars['more_information']->value['first_payment_amount'];?>


										<?php } else { ?>

											--

										<?php }?>

									</td>

									<?php } else { ?>

									<td class="text-center">

										<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_bonus'])) {?>

											<?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_bonus'];?>


										<?php } else { ?>

											--

										<?php }?>

									</td>

									<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['_oStock']->value['csbh'];?>
</td>

									<?php }?>

								</tr>

								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

							<?php }?>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</tbody>

						<!-- End Building -->

					<?php }?>

				</table>

			</div>

			<?php }?>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<!-- End Block -->

	<?php }?>	

	</div>

	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

</div>

<?php } else { ?>

	<div class="empty bg-white rounded-2">

		<div class="p-5 text-center">

			<img class="w-px-100" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg">

			<p class="text-muted">Không có kết quả nào phù hợp</p>

		</div>

	</div> 

<?php }
}
}
