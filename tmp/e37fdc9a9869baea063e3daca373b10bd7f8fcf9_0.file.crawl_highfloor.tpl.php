<?php
/* Smarty version 3.1.33, created on 2026-07-30 13:32:42
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/crawl_highfloor.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6af00a530dc8_92022384',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e37fdc9a9869baea063e3daca373b10bd7f8fcf9' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/crawl_highfloor.tpl',
      1 => 1784299638,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6af00a530dc8_92022384 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.math.php','function'=>'smarty_function_math',),));
echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jspreadsheet/jexcel.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jspreadsheet/jsuites.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jspreadsheet/jsuites.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css">
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jspreadsheet/jexcel.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css">
<div class="container-xxl flex-grow-1 pt-2 container-p-y" >
	<div class="d-flex align-items-center mb-2 flex-wrap gap-2 justify-content-between">
		<div class="p__left mb-lg-0">
			<h4 class="fw-bold mb-1 <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>fs-16<?php }?>">Danh sách cập nhật cao tầng đại lý</h4>
			<span class="text-muted">Tổng hợp danh sách cập nhật cao tầng đại lý</span>
		</div>
		<div class="p__right d-flex gap-1 <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>justify-content-end flex-fill<?php }?>">
			<button agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" type="button" class="btn px-1 border rounded-3 button_general " onClick="$Core.crawl.crawl_general(this,event)" title="Thực hiện">
				<i class="fa fa-play fs-18"></i> 
				<span>Thực hiện</span>
			</button>
			<a class="btn btn-default btn-icon" href="/thong-ke-cap-nhat-bang-hang.html">
				<i class="bx bx-bar-chart-alt"></i>
			</a>
			<button class="btn btn-default btn-icon " type="button" onclick="$Core.crawl.open_config_update_stock(this,event)" stock_type="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
">
				<i class="fa fa-cogs" aria-hidden="true"></i>
			</button>
			<button title="Hướng dẫn sử dụng" onclick="$Core.crawl.open_help(this, event)" data-toggle="ripple" class="btn btn-icon btn-outline-default"><span class="ripple-ink animate" style="height: 37.3438px; width: 37.3438px; top: -4.6719px; left: -0.32815px;"></span><i class="bx bx-help-circle"></i></button>
		</div>
	</div>
	<div class="card mb-4">
		<div class="card-header">
			<div class="d-flex gap-2 <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>flex-wrap<?php } else { ?> justify-content-center<?php }?>">
				<div class="d-flex align-items-center gap-1">
					<span style="background:#ffe1e1" class="d-block border w-px-30 h-px-30"></span>
					<span class="fs-6"><span class="fw-bold text-main number_error">0</span> lỗi cập nhật</span>
				</div>
				<div class="d-flex align-items-center gap-1">
					<span style="background:#ffff9c" class="d-block border w-px-30 h-px-30"></span>
					<span class="fs-6"><span class="fw-bold text-main number_changed">0</span> cảnh báo đại lý thay đổi link bảng hàng</span>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-container no-shadow overflow-auto" style="max-height: calc(100vh - 200px)">
				<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0">
					<thead class="position-sticky top-0 zindex-3" style="background: #f5f7f8">
						<tr>
							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
								<th class="align-center h-px-40 zindex-3" width="3%" rowspan="2">No.</th>
							<?php }?>
							<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
							<th class="align-center h-px-40 text-center" width="20%" colspan="<?php echo count($_smarty_tpl->tpl_vars['list_blocks']->value);?>
">
								<div class="d-flex align-items-center justify-content-center gap-1">
									Phân khu
									<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['totalStock']->value;?>
</span>
								</div>
							</th>
													</tr>
						<tr>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_block_name', false, 'key', 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_block_name']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>
								<th class="align-center h-px-40 text-center no-sticky" width="20%" <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?>style="border-right: 1px solid #d9dee3"<?php }?>>
									<div class="d-flex align-items-center justify-content-center gap-1">
										<?php echo $_smarty_tpl->tpl_vars['_block_name']->value;?>

										<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['arr_total_block']->value[$_smarty_tpl->tpl_vars['key']->value];?>
</span>
									</div>
								</th>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tr>
					</thead>
					<tbody class="table-border-bottom-0">
						<?php if (!empty($_smarty_tpl->tpl_vars['list_agency']->value)) {?>
							<?php $_smarty_tpl->_assignInScope('index', 0);?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_agency']->value, '_oItem', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>
								<?php $_smarty_tpl->_assignInScope('block_crawl', $_smarty_tpl->tpl_vars['_oItem']->value['block_crawl']);?>
								<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>
								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId'])) {?>
								<?php echo smarty_function_math(array('equation'=>"x+1",'x'=>$_smarty_tpl->tpl_vars['index']->value,'assign'=>"index"),$_smarty_tpl);?>

								<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >
									<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
									<td class="align-center text-center"><?php echo $_smarty_tpl->tpl_vars['index']->value;?>
</td>
									<?php }?>
									<td class="text-nowrap" data-label="Tiêu đề" width="100px">
										<div class="d-flex align-items-center justify-content-between gap-1">
											<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>

											<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_stock'];?>
</span>
										</div>
									</td>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_block_name', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_block_name']->value) {
?>
										<td class="text-center <?php if ($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['is_success'] == '0') {?>not_success<?php } elseif (!empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['is_crawl']) && !empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['is_changed'])) {?>is_changed<?php }?>" <?php if ($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['is_success'] == '0') {?>style="background:#ffe1e1"<?php } elseif (!empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['is_crawl']) && !empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['is_changed'])) {?>style="background:#ffff9c !important"<?php }?>>
											<div class="d-flex align-items-center justify-content-start gap-1 flex-wrap">
												<?php if (!empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['is_crawl'])) {?>
													<div class="d-flex gap-1 align-items-center justify-content-between">
														<div class="fs-11 d-flex flex-wrap gap-1 align-items-center lst_action_crawl">
															<div class="d-flex align-items-center gap-1">
																<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" stock_type="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
																<button agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" type="button" class="btn btn-outline-primary btn-sm text-nowrap btn_crawl" onClick="<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>$Core.crawl.crawl_agency1(this,event)<?php } else { ?>$Core.crawl.crawl_agency(this,event)<?php }?>" project_id="" block_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" stock_type="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
">
																	<i class="fa fa-play"></i> 
																	<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
																	<span>Thực hiện</span>
																</button>
																<button agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" type="button" class="btn btn-success btn-sm text-nowrap" onClick="$Core.crawl.start_import(this,event)" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" stock_type="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" spreadsheetId="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId'];?>
">
																	<i class="fa fa-play"></i> 
																	<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
																	<span>Crawl</span>
																</button>
																<form action="" enctype="multipart/form-data">
																	<button onclick="$Core.crawl.choose_image(this,event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" class="btn btn-sm btn-danger text-nowrap"><i class="fa fa-upload"></i> <span>Image</span></button>
																	<input type="file" name="images[]" onchange="$Core.crawl.start_import_image(this,event)" data-type="_IMAGE" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" stock_type="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" class="d-none file_upload" multiple="">
																</form>
															</div>
															<div class="d-flex gap-1 align-items-center flex-wrap gap-1">
																<div class="text-nowrap"><a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->genGoogleURL($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['sheetID'],'spreadsheets');?>
" target="_blank" class="limit_1line">Bảng hàng nguồn <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap"><a href="<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['link_stock'];?>
" target="_blank" class="limit_1line text-main">Bảng hàng <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap text-left w-100">Cập nhật: <strong class="text-main"><?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['time'];?>
</strong><?php if (!empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['html_result'])) {?> <?php if (!empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['user_upd'])) {?>bởi: <?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['user_upd'];
}?> (<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['html_result'];?>
)<?php }?></div>
															</div>
														</div>
														<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['total_stock'];?>
</span>
													</div>
												<?php } else { ?>
													<div class="d-flex gap-1 align-items-center">
														<div class="fs-11 d-flex flex-wrap gap-1 align-items-center lst_action_crawl">
															<div class="d-flex align-items-center gap-1"> 															
																<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" stock_type="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
																<button agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" type="button" class="btn btn-success btn-sm text-nowrap" onClick="$Core.crawl.start_import(this,event)" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" stock_type="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" spreadsheetId="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId'];?>
">
																	<i class="fa fa-play"></i> 
																	<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
																	<span>Crawl</span>
																</button>
																<form action="" enctype="multipart/form-data">
																	<button onclick="$Core.crawl.choose_image(this,event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" class="btn btn-sm btn-danger text-nowrap"><i class="fa fa-upload"></i> <span>Image</span></button>
																	<input type="file" name="images[]" onchange="$Core.crawl.start_import_image(this,event)" data-type="_IMAGE" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" stock_type="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" class="d-none file_upload" multiple="">
																</form>
															</div>
															<div class="d-flex gap-1 align-items-center flex-wrap gap-1">
																<div class="text-nowrap"><a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->genGoogleURL($_smarty_tpl->tpl_vars['_oItem']->value['spreadsheetId'],'spreadsheets');?>
" target="_blank" class="limit_1line">Link cập nhật <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap"><a href="<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['link_stock'];?>
" target="_blank" class="limit_1line text-main">Bảng hàng <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap text-left w-100">Cập nhật: <strong class="text-main"><?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['time'];?>
</strong><?php if (!empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['html_result'])) {?> <?php if (!empty($_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['user_upd'])) {?>bởi: <?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['user_upd'];
}?> (<?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['html_result'];?>
)<?php }?></div>
															</div>
														</div>
														<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['block_crawl']->value[$_smarty_tpl->tpl_vars['key']->value]['total_stock'];?>
</span>
													</div>										
												<?php }?>										
											</div>
										</td>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
																	</tr>
								<?php }?>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php } else { ?>
							<tr>
								<td class="text-center" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>4<?php } else { ?>5<?php }?>">
									Danh sách trống !
								</td>
							</tr>
						<?php }?>
					</tbody>
				</table>
			</div>
		</div>
    </div>
</div>

<style>
	.crawl-page{
		-moz-user-select: none !important;
		-webkit-touch-callout: none!important;
		-webkit-user-select: none!important;
		-khtml-user-select: none!important;
		-moz-user-select: none!important;
		-ms-user-select: none!important;
		user-select: none!important;
	}
</style>
<?php }
}
