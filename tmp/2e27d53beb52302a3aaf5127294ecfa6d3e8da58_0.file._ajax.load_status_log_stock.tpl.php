<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:31:21
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.load_status_log_stock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7694f9ec4f74_45680435',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2e27d53beb52302a3aaf5127294ecfa6d3e8da58' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.load_status_log_stock.tpl',
      1 => 1784299639,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7694f9ec4f74_45680435 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.math.php','function'=>'smarty_function_math',),));
if ($_smarty_tpl->tpl_vars['_type']->value == "_agency") {?>
	<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>
		<div class="table-container overflow-auto text-nowrap no-shadow table-container2 " style="max-height: 500px">
			<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
				<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
					<tr>
						<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
						<th class="align-center h-px-40 zindex-3 text-center" colspan="3">Cập nhật phân khu</th>
					</tr>
					<tr>
						<th class="align-center h-px-40 text-center" width="20%">Thành công</th>
						<th class="align-center h-px-40 text-center" width="20%">Lỗi</th>
						<th class="align-center h-px-40 text-center" width="20%">Không cập nhật</th>
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
							<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>
							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['spreadsheetId'])) {?>
								<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >
									<td class="text-nowrap" data-label="Tiêu đề" width="100px"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>
									<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['total_upd'] > 0) {?>
									<td class="text-center text-success fw-bold fs-16"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_upd'];?>
</td>
									<?php } else { ?>
									<td class="text-center text-muted"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_upd'];?>
</td>
									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['total_not_upd'] > 0) {?>
									<td class="text-center text-danger fw-bold fs-16"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_not_upd'];?>
</td>
									<?php } else { ?>
									<td class="text-center text-muted"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_not_upd'];?>
</td>
									<?php }?>
									<td class="text-center text-muted fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_dont_upd'];?>
</td>
								</tr>
							<?php }?>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<?php } else { ?>
						<tr>
							<td class="text-center" colspan="4">
								Danh sách trống!
							</td>
						</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	<?php } else { ?>
		<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="max-height: 500px">
			<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
				<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
					<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
					<tr>
						<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
						<th class="align-center h-px-40 zindex-3 text-center" colspan="3">Cập nhật dự án</th>
					</tr>
					<tr>
						<th class="align-center h-px-40 text-center" width="20%">Thành công</th>
						<th class="align-center h-px-40 text-center" width="20%">Lỗi</th>
						<th class="align-center h-px-40 text-center" width="20%">Không cập nhật</th>
					</tr>
				</thead>
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
							<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >
								<td class="text-nowrap" data-label="Tiêu đề" width="100px"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>
								<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['total_upd'] > 0) {?>
								<td class="text-center text-success fw-bold fs-16"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_upd'];?>
</td>
								<?php } else { ?>
								<td class="text-center text-muted"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_upd'];?>
</td>
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['total_not_upd'] > 0) {?>
								<td class="text-center text-danger fw-bold fs-16"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_not_upd'];?>
</td>
								<?php } else { ?>
								<td class="text-center text-muted"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_not_upd'];?>
</td>
								<?php }?>
								<td class="text-center text-muted fw-bold"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_dont_upd'];?>
</td>
							</tr>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<?php } else { ?>
						<tr>
							<td class="text-center fs-12" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>4<?php } else { ?>5<?php }?>">
								Danh sách trống!
							</td>
						</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	<?php }
} else { ?>
	<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>
		<div class="table-container overflow-auto text-nowrap no-shadow table-container2 " style="max-height: 80vh">
			<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
				<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
					<tr>
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
							<th class="align-center h-px-40 zindex-3 " width="3%" rowspan="2">No.</th>
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
								<div class="d-flex flex-column">									
									<div  class="d-flex align-items-center gap-1 justify-content-center"><?php echo $_smarty_tpl->tpl_vars['_block_name']->value;?>
<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['arr_total_block']->value[$_smarty_tpl->tpl_vars['key']->value];?>
</span></div>
									<div class="d-flex gap-1 text-none fw-normal fs-10">
										<div class="d-flex gap-1 align-items-center flex-fill text-success">
											<span class="">Cập nhật:</span><span class="text-dark"><?php echo $_smarty_tpl->tpl_vars['lstBlockTotalUpd']->value[$_smarty_tpl->tpl_vars['key']->value]['agency_upd'];?>
</span>
										</div>
										<div class="d-flex gap-1 align-items-center flex-fill text-danger">
											<span class="">Chưa cập nhật:</span><span class="text-dark"><?php echo $_smarty_tpl->tpl_vars['lstBlockTotalUpd']->value[$_smarty_tpl->tpl_vars['key']->value]['agency_not_upd'];?>
</span>
										</div>
									</div>
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
							<?php $_smarty_tpl->_assignInScope('list_block', $_smarty_tpl->tpl_vars['_oItem']->value['list_block']);?>
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
										<?php if (!empty($_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['is_crawl'])) {?>
											<td class="text-center" <?php if (empty($_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['is_success'])) {?>style="background:#ffe1e1"<?php } else { ?>style="background:#e6ffd8"<?php }?>>
												<div class="fs-11 d-flex flex-wrap gap-1 align-items-center lst_action_crawl">
													<div class="d-flex flex-wrap gap-1 fs-12" title="<?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['title_log'];?>
">														
														<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
														<div class="d-flex gap-1 align-items-center flex-fill">
															<span class="">Cập nhật:</span><span class="text-dark"><?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['total_upd'];?>
</span>
														</div>
														<div class="d-flex gap-1 align-items-center text-success flex-fill">
															<span class="">Thành công:</span><span class="text-success"><?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['total_success'];?>
</span>
														</div>
														<div class="d-flex gap-1 align-items-center text-danger flex-fill">
															<span class="">Thất bại:</span><span class="text-danger"><?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['total_fail'];?>
</span>
														</div>	
														<div class="d-flex gap-1 align-items-center text-muted flex-fill">
															<span class="">Lần cuối:</span><?php if (!empty($_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['time'])) {
echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['time'];
} else { ?>--<?php }?>
														</div>													
													</div>
													<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['total_stock'];?>
</span>
												</div>
											</td>
										<?php } else { ?>
											<td class="text-center text-muted fw-bold fs-12">Không cập nhật</td>
										<?php }?>							
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
							<td class="text-center fs-12" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>4<?php } else { ?>5<?php }?>">
								Danh sách trống!
							</td>
						</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	<?php } else { ?>
		<div class="table-container overflow-auto text-nowrap no-shadow table-container2 " style="max-height: 80vh">
			<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
				<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
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
								<div class="d-flex flex-column">
									<div  class="d-flex align-items-center gap-1 justify-content-center"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_code'];?>
<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['arr_total_project']->value[$_smarty_tpl->tpl_vars['key']->value];?>
</span></div>
									<div class="d-flex gap-1 text-none fw-normal fs-10">
										<div class="d-flex gap-1 align-items-center flex-fill text-success">
											<span class="">Cập nhật:</span><span class="text-dark"><?php echo $_smarty_tpl->tpl_vars['lstBlockTotalUpd']->value[$_smarty_tpl->tpl_vars['key']->value]['agency_upd'];?>
</span>
										</div>
										<div class="d-flex gap-1 align-items-center flex-fill text-danger">
											<span class="">Chưa cập nhật:</span><span class="text-dark"><?php echo $_smarty_tpl->tpl_vars['lstBlockTotalUpd']->value[$_smarty_tpl->tpl_vars['key']->value]['agency_not_upd'];?>
</span>
										</div>
									</div>
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
							<?php $_smarty_tpl->_assignInScope('list_block', $_smarty_tpl->tpl_vars['_oItem']->value['list_block']);?>
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
									<?php if (!empty($_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['is_crawl'])) {?>
										<td class="text-center" <?php if (empty($_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['is_success'])) {?>style="background:#ffe1e1"<?php } else { ?>style="background:#e6ffd8"<?php }?>>
											<div class="fs-11 d-flex flex-wrap gap-1 align-items-center lst_action_crawl">
												<div class="d-flex flex-wrap gap-1 fs-12" title="<?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['title_log'];?>
">	
													<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" target_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
													<div class="d-flex gap-1 align-items-center flex-fill">
														<span class="">Cập nhật:</span><span class="text-dark"><?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['total_upd'];?>
</span>
													</div>
													<div class="d-flex gap-1 align-items-center text-success flex-fill">
														<span class="">Thành công:</span><span class="text-success"><?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['total_success'];?>
</span>
													</div>
													<div class="d-flex gap-1 align-items-center text-danger flex-fill">
														<span class="">Thất bại:</span><span class="text-danger"><?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['total_fail'];?>
</span>
													</div>	
													<div class="d-flex gap-1 align-items-center text-muted flex-fill">
														<span class="">Lần cuối:</span><?php if (!empty($_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['time'])) {
echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['time'];
} else { ?>--<?php }?>
													</div>													
												</div>
												<span class="fw-bold fs-20 text-warning"><?php echo $_smarty_tpl->tpl_vars['list_block']->value[$_smarty_tpl->tpl_vars['key']->value]['total_stock'];?>
</span>													
											</div>
										</td>
									<?php } else { ?>
										<td class="text-center text-muted fw-bold fs-12">Không cập nhật</td>
									<?php }?>
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
							<td class="text-center fs-12" colspan="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>4<?php } else { ?>5<?php }?>">
								Danh sách trống!
							</td>
						</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	<?php }
}
}
}
