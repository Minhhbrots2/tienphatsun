<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:53:39
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/stock_list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ad8d300f9a2_29366099',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ce58c63e0d55000a7d84b1f4bd0b36863a559926' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/stock_list.tpl',
      1 => 1784300231,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ad8d300f9a2_29366099 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="content-wrapper">
	<div class="container-sm flex-grow-1 container-p-y pt-1">
		<?php if (!empty($_smarty_tpl->tpl_vars['lstArea']->value)) {?>
			<div class="row">
				<div class="col-12 col-xxl-10 mx-auto">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstArea']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
						<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['list_blocks']) || !empty($_smarty_tpl->tpl_vars['_oItem']->value['arr_projects']) || !empty($_smarty_tpl->tpl_vars['_oItem']->value['arr_menu_blocks'])) {?>
							<?php $_smarty_tpl->_assignInScope('list_blocks', $_smarty_tpl->tpl_vars['_oItem']->value['list_blocks']);?>
							<?php $_smarty_tpl->_assignInScope('arr_projects', $_smarty_tpl->tpl_vars['_oItem']->value['arr_projects']);?>
							<?php $_smarty_tpl->_assignInScope('arr_menu_blocks', $_smarty_tpl->tpl_vars['_oItem']->value['arr_menu_blocks']);?>
							<div class="divider my-2">
								<div class="divider-text text-upper fs-3 fw-semibold text-main">
									<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>

								</div>
							</div>
							<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>
								<div class="card mb-2 project_highfloor">
									<div class="card-header d-flex justify-content-between align-items-center">
										<h3 class="card-title mb-0 fs-5">1 - Chung cư cao tầng</h3>
										<button type="button" class="btn btn-icon bg-white btn-default <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>btn-sm<?php }?>" data-bs-toggle="tooltip" onclick="$Core.project.dragBlock(this,event)" title="Chỉnh sửa"><i class="bx bx-pencil"><i></i></i></button>
									</div>
									<div class="card-body " data-profile="<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
">
										<div class="row drag_project" area_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['setting_id'];?>
">
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock', false, 'k_block', 'n_block', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_block']->value => $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
												<?php if (!empty($_smarty_tpl->tpl_vars['_oBlock']->value['list_menu_buildings'])) {?>
												<div class="col-12 col-md-6 col-lg-4 mb-2 project-item position-relative" id="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
">
													<span class="btn btn-icon btn-default position-absolute top-0 right-0 zindex-1 bg-white btn_drag d-none" title="Giữ vào kéo thả" type="button" style="right:calc(var(--bs-gutter-x) * 0.5)"><i class='bx bx-transfer-alt' ></i></span>
													<?php $_smarty_tpl->_assignInScope('more_information_block', $_smarty_tpl->tpl_vars['_oBlock']->value['more_information']);?>
													<?php $_smarty_tpl->_assignInScope('_oProject', $_smarty_tpl->tpl_vars['_oBlock']->value['project_info']);?>
													<div class="block-one mt-0 mt-lg-2">
														<div class="divider my-2">
															<div class="divider-text text-upper fs-6 fw-semibold" style="color:<?php echo $_smarty_tpl->tpl_vars['more_information_block']->value['bgcolor'];?>
">
																<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>
																<a class="btn btn-xs btn-icon btn-link rounded-pill" onClick="$Core.stock.config_block(this, event)" 
																	title="Cấu hình" block_id="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
" data-bs-toggle="tooltip"><i class="bx bx-cog"></i></a>
																<?php }?>
																PK <?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
 
																<?php if (!empty($_smarty_tpl->tpl_vars['more_information_block']->value['is_hot'])) {?>
																<span class="hot-dot">HOT</span>
																<?php }?>
															</div>
														</div>
														<div class="d-flex gap-2 align-items-center justify-content-center">
															<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getImageWH($_smarty_tpl->tpl_vars['_oProject']->value['logo'],0,30);?>
" class="h-px-30" />
															<h3 class="fs-12 mb-0 text-upper"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</h3>
														</div>
														<ul class="mb-0 list-unstyled d-flex flex-wrap gap-2 mt-2">
															<?php $_smarty_tpl->_assignInScope('lstBuilding', $_smarty_tpl->tpl_vars['_oBlock']->value['list_menu_buildings']);?>
															<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstBuilding']->value, '_oBuilding', false, 'k_block', 'n_building', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_block']->value => $_smarty_tpl->tpl_vars['_oBuilding']->value) {
?>
															<li class="flex-fill ">
																<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
" <?php echo $_smarty_tpl->tpl_vars['more_information_block']->value['bgcolor'];?>
 class="btn <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm<?php }?> btn-outline-primary building-name w-100" data-color="<?php echo $_smarty_tpl->tpl_vars['more_information_block']->value['bgcolor'];?>
" style="border-color: <?php echo $_smarty_tpl->tpl_vars['more_information_block']->value['bgcolor'];?>
 !important; background-color: <?php echo $_smarty_tpl->tpl_vars['more_information_block']->value['bgcolor'];?>
 !important; color: <?php echo $_smarty_tpl->tpl_vars['more_information_block']->value['textcolor'];?>
 !important;"><?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
</a>
															</li>
															<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
														</ul>
													</div>
													</div>
												<?php }?>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										</div>
									</div>
								</div>
							<?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['arr_projects']->value) || !empty($_smarty_tpl->tpl_vars['arr_menu_blocks']->value)) {?>
								<div class="card">
									<div class="card-header">
										<h3 class="card-title mb-0 fs-5">2 - Biệt thự thấp tầng</h3>
									</div>
									<div class="card-body">
										<div class="form-row row-cols-2 rows-col-lg-3 row-cols-lg-4 row-cols-xxl-5">
											<?php if (!empty($_smarty_tpl->tpl_vars['arr_projects']->value)) {?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_projects']->value, '_oProject', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProject']->value) {
?>
													<?php if ($_smarty_tpl->tpl_vars['clsProject']->value->isLowFloor($_smarty_tpl->tpl_vars['_oProject']->value['project_id'],$_smarty_tpl->tpl_vars['_oProject']->value['list_block_type'])) {?>
														<div class="col mb-2">
															<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['link'];?>
" class="btn h-100 btn-outline-primary w-100" style="border-color: <?php echo $_smarty_tpl->tpl_vars['_oProject']->value['bgcolor'];?>
 !important; background-color:<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['bgcolor'];?>
 !important; color:<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['textcolor'];?>
 !important;order:<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['order_no'];?>
">
																<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getImageWH($_smarty_tpl->tpl_vars['_oProject']->value['logo'],0,30);?>
" class="h-px-30" style="filter:brightness(0) invert(1);">
																<div class="clearfix my-1"></div>
																<span ><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['code'];?>
</span>
															</a>
														</div>
													<?php }?>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											<?php }?>
											<?php if (!empty($_smarty_tpl->tpl_vars['arr_menu_blocks']->value)) {?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_menu_blocks']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
												<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oBlock']->value['more_information']);?>
												<div class="col mb-2">
													<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['link'];?>
" class="btn h-100 btn-outline-primary w-100" style="border-color: <?php echo $_smarty_tpl->tpl_vars['_more_information']->value['bgcolor'];?>
 !important; background-color:<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['bgcolor'];?>
 !important; color:<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['textcolor'];?>
 !important;">
														<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getImageWH($_smarty_tpl->tpl_vars['_oBlock']->value['image'],0,30);?>
" class="h-px-30" style="filter:brightness(0) invert(1);">
														<div class="clearfix my-1"></div>
														<span ><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</span>
													</a>
												</div>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											<?php }?>
										</div>
									</div>
								</div>
							<?php }?>
						<?php }?>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>								
				</div>
			</div>
		<?php }?>
	</div>
</div><?php }
}
