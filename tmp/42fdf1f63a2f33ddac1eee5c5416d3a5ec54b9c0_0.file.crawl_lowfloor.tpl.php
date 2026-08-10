<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:52:55
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/lowfloor/crawl_lowfloor.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ad8a76f2659_84505012',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '42fdf1f63a2f33ddac1eee5c5416d3a5ec54b9c0' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/lowfloor/crawl_lowfloor.tpl',
      1 => 1784300219,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ad8a76f2659_84505012 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.math.php','function'=>'smarty_function_math',),));
?>

<?php echo '<script'; ?>
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
		<div class="p__left mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1 <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>fs-16<?php }?>">Danh sách cập nhật thấp tầng đại lý</h4>
			<span class="text-muted">Tổng hợp danh sách cập nhật thấp tầng đại lý</span>
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
			<button class="btn btn-default btn-icon" type="button" onclick="$Core.crawl.open_config_update_stock(this,event)" stock_type="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
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
							<th class="align-center h-px-40 text-center" width="20%" colspan="<?php echo count($_smarty_tpl->tpl_vars['lst_project']->value);?>
">
								<div class="d-flex align-items-center justify-content-center gap-1">
									Dự án
									<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['totalStock']->value;?>
</span>
								</div>
							</th>
													</tr>
						<tr>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_project']->value, '_oProject', false, 'key', 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProject']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>
								<th class="align-center h-px-40 text-center no-sticky" width="20%" <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?>style="border-right: 1px solid #d9dee3"<?php }?>>
									<div class="d-flex align-items-center justify-content-center gap-1">
										<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_code'];?>

										<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['arr_total_project']->value[$_smarty_tpl->tpl_vars['key']->value];?>
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
								<?php $_smarty_tpl->_assignInScope('crawl_lowfloor', $_smarty_tpl->tpl_vars['_oItem']->value['crawl_lowfloor']);?>
								<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>
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
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_project']->value, '_oProject', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProject']->value) {
?>
										<td class="text-center <?php if ($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['is_success'] == '0') {?>not_success<?php } elseif (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['is_crawl']) && !empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['is_changed'])) {?>is_changed<?php }?>" <?php if ($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['is_success'] == '0') {?>style="background:#ffe1e1"<?php } elseif (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['is_crawl']) && !empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['is_changed'])) {?>style="background:#ffff9c !important"<?php }?>>
											<div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
												<?php if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['is_crawl'])) {?>
													<div class="d-flex gap-1 align-items-center justify-content-between w-100">
														<div class="fs-11 d-flex flex-column flex-wrap gap-1 lst_action_crawl">
															<div class="d-flex align-items-center gap-1">
																<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
																<button agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" type="button" class="btn btn-outline-primary btn-sm text-nowrap btn_crawl" onClick="$Core.crawl.crawl_agency_lowfloor(this,event)" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
">
																	<i class="fa fa-play"></i> 
																	<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
																	<span>Thực hiện</span>
																</button>
																<button agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" type="button" class="btn btn-success btn-sm text-nowrap" onclick="$Core.global.stock.start_import(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" spreadsheetId="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['spreadsheetId'];?>
">
																	<i class="fa fa-play"></i> 
																	<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
																	<span>Crawl</span>
																</button>
															</div>
															<div class="d-flex gap-1 align-items-center flex-wrap gap-1">
																<div class="text-nowrap"><a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->genGoogleURL($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['sheetID'],'spreadsheets');?>
" target="_blank" class="limit_1line">Bảng hàng nguồn <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap"><a href="<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['link_stock'];?>
" target="_blank" class="limit_1line text-main">Bảng hàng <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap text-left w-100">Cập nhật: <strong class="text-main"><?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['time'];?>
</strong><?php if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['html_result'])) {?> <?php if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['user_upd'])) {?>bởi: <?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['user_upd'];
}?> (<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['html_result'];?>
)<?php }?></div>
															</div>
														</div>
														<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['total_stock'];?>
</span>
													</div>
												<?php } else { ?>
													<div class="d-flex gap-1 align-items-center justify-content-between w-100">
														<div class="fs-11 d-flex flex-column flex-wrap gap-1 lst_action_crawl">
															<div class="d-flex align-items-center gap-1"> 															
																<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
																<button agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" type="button" class="btn btn-success btn-sm text-nowrap" onclick="$Core.global.stock.start_import(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" spreadsheetId="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['spreadsheetId'];?>
">
																	<i class="fa fa-play"></i> 
																	<i class="fa loading d-none fa-circle-o-notch fa-spin fa-fw"></i> 
																	<span>Crawl</span>
																</button>
															</div>
															<div class="d-flex gap-1 align-items-center flex-wrap gap-1">
																<div class="text-nowrap"><a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->genGoogleURL($_smarty_tpl->tpl_vars['_oProject']->value['spreadsheetId'],'spreadsheets');?>
" target="_blank" class="limit_1line">Link cập nhật <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap"><a href="<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['link_stock'];?>
" target="_blank" class="limit_1line text-main">Bảng hàng <i class='bx bx-link-external fs-11'></i></a></div>
																<div class="text-nowrap text-left w-100">Cập nhật: <strong class="text-main"><?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['time'];?>
</strong><?php if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['html_result'])) {?> <?php if (!empty($_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['user_upd'])) {?>bởi: <?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['user_upd'];
}?> (<?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['html_result'];?>
)<?php }?></div>
															</div>
														</div>
														<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['crawl_lowfloor']->value[$_smarty_tpl->tpl_vars['key']->value]['total_stock'];?>
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
