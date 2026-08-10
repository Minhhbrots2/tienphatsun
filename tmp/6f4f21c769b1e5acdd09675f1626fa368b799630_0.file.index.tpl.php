<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:51:33
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/banner_stock/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315b5058308_61408128',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6f4f21c769b1e5acdd09675f1626fa368b799630' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/banner_stock/index.tpl',
      1 => 1785927027,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315b5058308_61408128 (Smarty_Internal_Template $_smarty_tpl) {
?><section class="banner_stock section_banner banner_page d-flex justify-content-center mb-3 position-relative zindex-3" style="background-image:url('<?php echo $_smarty_tpl->tpl_vars['oneProject']->value['image'];?>
')">
	<div class="w-100 mx-auto d-flex flex-column justify-content-between"> 
		<div class="top_content_banner text-white d-flex justify-content-between align-items-center w-100 zindex-2">
			<div class="eblWgTAbDi">
				<?php if (!empty($_smarty_tpl->tpl_vars['lstBuildingBl']->value)) {?>
					<div class="dropdown">
						<button data-toggle="ripple" class="btn bg-warning rounded-pill text-white dropdown-toggle<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm<?php }?>" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" data-popper-placement="top-start" aria-haspopup="true" aria-expanded="false">Bảng hàng</button>
						<div class="dropdown-menu  dropdown-menu-stock dropdown-menu-stock_project" style="max-width: 350px">
							<div class="p-3">
								<div class="block-one mt-0 mt-lg-2">
									<div class="divider my-2">
										<div class="divider-text">Phân khu <?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
</div>
									</div>
									<div class="d-flex gap-2 align-items-center justify-content-center">
										<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUrlImageFH($_smarty_tpl->tpl_vars['oneProject']->value['logo'],0,30);?>
" class="h-px-30" />
										<h3 class="fs-6 mb-0 text-upper"><?php echo $_smarty_tpl->tpl_vars['oneProject']->value['title'];?>
</h3>
									</div>
									<ul class="mb-0 list-unstyled d-flex flex-wrap gap-2 mt-2">
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstBuildingBl']->value, '_oBuilding', false, 'k_block', 'n_building', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_block']->value => $_smarty_tpl->tpl_vars['_oBuilding']->value) {
?>
										<li class="flex-fill ">
											<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
" <?php echo $_smarty_tpl->tpl_vars['block_information']->value['bgcolor'];?>
 class="btn btn-sm btn-outline-primary building-name w-100" data-color="<?php echo $_smarty_tpl->tpl_vars['block_information']->value['bgcolor'];?>
" style="border-color: <?php echo $_smarty_tpl->tpl_vars['block_information']->value['bgcolor'];?>
 !important; background-color: <?php echo $_smarty_tpl->tpl_vars['block_information']->value['bgcolor'];?>
 !important; color: <?php echo $_smarty_tpl->tpl_vars['block_information']->value['textcolor'];?>
 !important;" ><?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
</a>
										</li>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</ul>
								</div>
								
							</div>
						</div>
					</div>
				<?php } else { ?>
					<a class="btn bg-warning rounded-pill text-white<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm<?php }?> js__dropdown-stock" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('stock');?>
">Bảng hàng</a>
				<?php }?>
							
			</div>
			<div class="d-flex justify-content-end align-items-center gap-1">
				<?php if (!empty($_smarty_tpl->tpl_vars['is_model']->value)) {?>
					<button class="btn btn-info text-white pulse position-relative rounded-pill <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm px-1<?php }?>" type="button" onClick="$Core.project.open_model(this,event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" _type="is_model">Nhà mẫu</button>
				<?php }?>
				<?php if (!empty($_smarty_tpl->tpl_vars['is_handoverSpecs']->value)) {?>
					<button class="btn btn-success text-white pulse position-relative rounded-pill <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm px-1<?php }?>" type="button" onClick="$Core.project.open_model(this,event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" _type="is_handoverSpecs">TC Bàn giao</button>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['show']->value == 'project' || $_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>
					<?php if (!empty($_smarty_tpl->tpl_vars['oneProject']->value['is_menu'])) {?>
						<span class="btn<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm<?php }?> bg-success rounded-pill text-white">Mở bán</span>
					<?php } else { ?>
						<span class="btn<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm<?php }?> bg-main rounded-pill text-white">Chưa mở bán</span>
					<?php }?>
				<?php } else { ?>
					<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['on_sale'])) {?>
						<span class="btn<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm<?php }?> bg-success rounded-pill text-white">Mở bán</span>	
					<?php }?>
				<?php }?>
			</div>
		</div>
		<div class="content_banner d-flex flex-column justify-content-between align-items-start w-100 zindex-1">
			<div class="mb-3 text-white">
				<?php if (!empty($_smarty_tpl->tpl_vars['oneBuilding']->value)) {?>					
					<h1 class="title_project fw-semibold mb-1 fs-4"><?php if (!empty($_smarty_tpl->tpl_vars['ms_code']->value)) {
echo $_smarty_tpl->tpl_vars['ms_code']->value;
} else {
echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];
}?> <?php if ($_smarty_tpl->tpl_vars['act']->value == 'stock' || $_smarty_tpl->tpl_vars['act']->value == 'map') {?><span class="fs-16 fw-normal">(<span class="total_stock text-danger fw-bold"><?php if (!empty($_smarty_tpl->tpl_vars['total_stocks']->value)) {
echo $_smarty_tpl->tpl_vars['total_stocks']->value;
} else { ?>0<?php }?></span> căn)</span><?php }?></h1>
				<?php } elseif (!empty($_smarty_tpl->tpl_vars['block_is_project']->value)) {?>					
					<h1 class="title_project fw-semibold mb-1 fs-4"><?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
 <?php if ($_smarty_tpl->tpl_vars['act']->value == 'stock' || $_smarty_tpl->tpl_vars['act']->value == 'layout') {?><span class="fs-16 fw-normal">(<span class="total_stock text-danger fw-bold"><?php if (!empty($_smarty_tpl->tpl_vars['total_stocks']->value)) {
echo $_smarty_tpl->tpl_vars['total_stocks']->value;
} else { ?>0<?php }?></span> căn)</span><?php }?></h1>
				<?php } else { ?>
					<h1 class="title_project fw-semibold mb-1 fs-4"><?php echo $_smarty_tpl->tpl_vars['oneProject']->value['title'];?>
 <?php if ($_smarty_tpl->tpl_vars['act']->value == 'stock' || $_smarty_tpl->tpl_vars['act']->value == 'layout') {?><span class="fs-16 fw-normal">(<span class="total_stock text-danger fw-bold"><?php if (!empty($_smarty_tpl->tpl_vars['total_stocks']->value)) {
echo $_smarty_tpl->tpl_vars['total_stocks']->value;
} else { ?>0<?php }?></span> căn)</span><?php }?></h1>
				<?php }?>
				<p class="text-white mb-0 fs-12">
					<?php if (!empty($_smarty_tpl->tpl_vars['oneBlock']->value)) {?><a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['block_id']->value,0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
" class="text-white">Phân khu <?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
</a>, <?php }?><a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,0,0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
" class="text-white"><?php echo $_smarty_tpl->tpl_vars['oneProject']->value['title'];?>
</a>
				</p>
			</div>
			<div class="d-flex w-100 gap-1 gap-lg-3 overflow-x-auto">
				<?php if ($_smarty_tpl->tpl_vars['show']->value == 'building' || (!empty($_smarty_tpl->tpl_vars['block_is_project']->value)) || (($_smarty_tpl->tpl_vars['show']->value == 'project' || $_smarty_tpl->tpl_vars['show']->value == 'map' || $_smarty_tpl->tpl_vars['act']->value == 'stock' || $_smarty_tpl->tpl_vars['act']->value == 'layout') && !empty($_smarty_tpl->tpl_vars['is_lowfloor']->value))) {?>
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray(@constant('_BLOCK_TYPE_HIGHLEVEL_SALE'),$_smarty_tpl->tpl_vars['arr_block_type']->value) && ($_smarty_tpl->tpl_vars['show']->value == 'project' || ($_smarty_tpl->tpl_vars['show']->value == 'map' && empty($_smarty_tpl->tpl_vars['building_id']->value)) || $_smarty_tpl->tpl_vars['act']->value == 'stock' || $_smarty_tpl->tpl_vars['act']->value == 'layout')) {?>
						<a data-toggle="ripple" href="javascript:void()" class="item_option d-flex flex-column align-items-center <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>py-2<?php } else { ?>py-4<?php }?> px-2 rounded-3 flex-flow text-white <?php if ($_smarty_tpl->tpl_vars['act']->value == 'stock' || $_smarty_tpl->tpl_vars['act']->value == 'layout') {?>active<?php }?>" onClick="$Core.project.chooseListStock(this,event)">
							<i class="bx bx-table mb-1"></i>
							<span class="txt_option">Bảng hàng</span>
						</a>
					<?php } else { ?>
						<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['link_stock']->value;?>
" class="item_option d-flex flex-column align-items-center <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>py-2<?php } else { ?>py-4<?php }?> px-2 rounded-3 flex-flow text-white <?php if ($_smarty_tpl->tpl_vars['act']->value == 'stock' || $_smarty_tpl->tpl_vars['act']->value == 'layout') {?>active<?php }?>">
							<i class="bx bx-table mb-1"></i>
							<span class="txt_option">Bảng hàng</span>
						</a>
					<?php }?>
					
				<?php }?>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_category_docs']->value, '_oCatDocs', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oCatDocs']->value) {
?>
				<a data-toggle="ripple" class="item_option d-flex flex-column text-center align-items-center justify-content-center <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>py-2<?php } else { ?>py-4<?php }?> px-2 rounded-3 flex-flow text-white <?php if ($_smarty_tpl->tpl_vars['root_id']->value == $_smarty_tpl->tpl_vars['_oCatDocs']->value['property_id'] && $_smarty_tpl->tpl_vars['show']->value != 'map') {?> active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkInfo($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['block_id']->value,$_smarty_tpl->tpl_vars['building_id']->value,$_smarty_tpl->tpl_vars['_oCatDocs']->value['property_id']);?>
">
					<i class="<?php echo $_smarty_tpl->tpl_vars['_oCatDocs']->value['image'];?>
 mb-2"></i>
					<span class="txt_option"><?php echo $_smarty_tpl->tpl_vars['_oCatDocs']->value['title'];?>
</span>
				</a>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkInfo($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['block_id']->value,$_smarty_tpl->tpl_vars['building_id']->value,'_utility');?>
" class="item_option d-flex flex-column align-items-center <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>py-2<?php } else { ?>py-4<?php }?> px-2 rounded-3 flex-flow text-white<?php if ($_smarty_tpl->tpl_vars['cat_id']->value == @constant('_PROJECT_DOCS_UTILITY_CATID')) {?> active<?php }?>">
					<i class="bx bx-category mb-2"></i>
					<span class="txt_option">Tiện ích</span>
				</a> 
				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone' && !empty($_smarty_tpl->tpl_vars['vr_link']->value)) {?>
				<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['vr_link']->value;?>
" data-fancybox data-caption="<?php echo $_smarty_tpl->tpl_vars['vr_source']->value;?>
" data-type="iframe" data-preload="true" class="item_option d-flex flex-column align-items-center <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>py-2<?php } else { ?>py-4<?php }?> px-2 rounded-3 flex-flow text-white">
					<i class='bx bx-analyse mb-2'></i>
					<span class="txt_option">VR360</span>
				</a>
				<?php }?>
				<?php if (!empty($_smarty_tpl->tpl_vars['map_la']->value) && !empty($_smarty_tpl->tpl_vars['map_lo']->value)) {?>
					<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkInfo($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['block_id']->value,$_smarty_tpl->tpl_vars['building_id']->value,'_map');?>
" class="item_option d-flex flex-column align-items-center <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>py-2<?php } else { ?>py-4<?php }?> px-2 rounded-3 flex-flow text-white<?php if ($_smarty_tpl->tpl_vars['show']->value == 'map') {?> active<?php }?>">
						<i class="bx bx-map mb-2"></i>
						<span class="txt_option">Bản đồ</span>
					</a>
				<?php }?>
			</div>
		</div>
	</div>
</section>
	<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray(@constant('_BLOCK_TYPE_HIGHLEVEL_SALE'),$_smarty_tpl->tpl_vars['arr_block_type']->value) && ($_smarty_tpl->tpl_vars['show']->value == 'project' || $_smarty_tpl->tpl_vars['show']->value == 'map' || $_smarty_tpl->tpl_vars['act']->value == 'stock' || $_smarty_tpl->tpl_vars['act']->value == 'layout')) {?>
		<div class="modal fade modal_stock_picker" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" tabindex="-1" aria-modal="true" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static"> 
			<div class="modal-dialog modal-sm modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title text-center text-dark">Bảng hàng</h3>
						<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="d-flex flex-column justify-content-center">
							<div class="d-flex flex-column gap-2">
								<a class="d-flex align-items-center justify-content-between mb-2 btn fs-5 btn-lg btn-outline-default" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('tool');?>
?project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" >
									<div class="d-flex align-items-center gap-1">
										<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icons/icon_building.png" width="28" height="28">
										<span class="text-dark">Cao tầng</span>
									</div>
									<span class="icon fs-4"><i class='bx bx-chevron-right'></i></span>
								</a>
								<a class="d-flex align-items-center justify-content-between mb-2 btn fs-5 btn-lg btn-outline-default <?php if ($_smarty_tpl->tpl_vars['act']->value == 'stock') {?> active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLink($_smarty_tpl->tpl_vars['project_id']->value);?>
" >
									<div class="d-flex align-items-center gap-1">
										<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icons/icon_house.png" width="28" height="28">
										<span class="text-dark">Thấp tầng</span>
									</div>
									<span class="icon fs-4"><i class='bx bx-chevron-right'></i></span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php }
}
}
