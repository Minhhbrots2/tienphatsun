<?php
/* Smarty version 3.1.33, created on 2026-08-07 15:52:59
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/report_crawl.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a759ceb368f28_24245542',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd88c5ca3bbf6ebf4d0a0866e099effa4ad61437c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/report_crawl.tpl',
      1 => 1784299639,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a759ceb368f28_24245542 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between py-2">
		<div class="lycYJcfXJY">
			<h4 class="fw-bold mb-1">Thống kê cập nhật bảng hàng</h4>
			<span class="text-muted">Tổng quan cập nhật bảng hàng</span>
		</div>
			</div>
	<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
	<div class="form-row ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=ajax_get_total_report" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{}'>
		<div class="col-12 col-md-6">
			<div class="mb-2 card">
				<div class="card-header">
					<h3 class="card-title">Bảng hàng cao tầng</h3>
				</div>
				<div class="card-body">
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<div class="d-flex briefStockHug flex-wrap gap-3 mb-2 align-items-center">
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Tổng quỹ</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Nhập mới</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Đã bán</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="mb-2 card">
				<div class="card-header">
					<h3 class="card-title">Bảng hàng thấp tầng</h3>
				</div>
				<div class="card-body">
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<div class="d-flex briefStockHug flex-wrap gap-3 mb-2 align-items-center">
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Tổng quỹ</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Nhập mới</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Đã bán</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-md-6">
			<div class="mb-2 card card_load_time">
				<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Tình trạng cập nhật cao tầng đại lý</h5>
					<div class="w-px-150">
						<input type="date" class="form-control" name="date" value=<?php echo smarty_modifier_date_format(time(),"%Y-%m-%d");?>
 max="<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d');?>
" onChange="$Core.crawl.reload(this,event)" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_agency"}'>
					</div>
				</div>
				<div class="card-body ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=ajax_load_status_log_stock&stock_type=<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_agency"}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 500px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
								<tr>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 zindex-3 text-center" colspan="3">Phân khu</th>
								</tr>
								<tr>
									<th class="align-center h-px-40 text-center" width="20%">Cập nhật</th>
									<th class="align-center h-px-40 text-center" width="20%">Chưa cập nhật</th>
									<th class="align-center h-px-40 text-center" width="20%">Không cập nhật</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 16);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
									<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >									
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								<?php
}
}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="mb-2 card card_load_time">
				<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Tình trạng cập nhật thấp tầng đại lý</h5>
					<div class="w-px-150">
						<input type="date" class="form-control" name="date" value=<?php echo smarty_modifier_date_format(time(),"%Y-%m-%d");?>
 max="<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d');?>
" onChange="$Core.crawl.reload(this,event)" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_agency"}'>
					</div>
				</div>
				<div class="card-body ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=ajax_load_status_log_stock&stock_type=<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_agency"}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 500px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
								<tr>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 zindex-3 text-center" colspan="3">Phân khu</th>
								</tr>
								<tr>
									<th class="align-center h-px-40 text-center" width="20%">Cập nhật</th>
									<th class="align-center h-px-40 text-center" width="20%">Chưa cập nhật</th>
									<th class="align-center h-px-40 text-center" width="20%">Không cập nhật</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = min(($__section_i_1_loop - 0), 16);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
									<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >									
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								<?php
}
}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-md-6">
			<div class="mb-2 card card_load_time">
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Tình trạng link cập nhật tự động cao tầng</h5>
					<div class="w-px-200">
						<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
						<select gid="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-control form-control-sm form-select" data-placeholder="Chọn phân khu" name="block_id" onchange="$Core.crawl.reload(this, event)">
							<option value="0">Chọn phân khu</option>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_group_block']->value, '_oGroupBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oGroupBlock']->value) {
?>
								<?php $_smarty_tpl->_assignInScope('listBlocks', $_smarty_tpl->tpl_vars['_oGroupBlock']->value['listBlocks']);?>
								<optgroup label="<?php echo $_smarty_tpl->tpl_vars['_oGroupBlock']->value['title'];?>
">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['listBlocks']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
">[<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_code'];?>
] <?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</optgroup>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</select>
					</div>
				</div>
				<div class="card-body ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=ajax_load_agency_link&stock_type=<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_agency"}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 300px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
								<tr>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 zindex-3 text-center" colspan="2">Phân khu</th>
								</tr>
								<tr>
									<th class="align-center h-px-40 text-center" width="20%">Có link</th>
									<th class="align-center h-px-40 text-center" width="20%">Chưa có link</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								<?php
$__section_i_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_2_total = min(($__section_i_2_loop - 0), 16);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_2_total !== 0) {
for ($__section_i_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_2_iteration <= $__section_i_2_total; $__section_i_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
									<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >									
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								<?php
}
}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="mb-2 card card_load_time">
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Tình trạng link cập nhật tự động thấp tầng</h5>
					<div class="w-px-150">
						<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
						<select gid="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-control form-control-sm form-select" data-placeholder="Chọn dự án" name="project_id" onchange="$Core.crawl.reload(this, event)">
								<option value="0">Chọn dự án</option>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstProjects']->value, '_oProject');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</select>
					</div>
				</div>
				<div class="card-body ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=ajax_load_agency_link&stock_type=<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_agency"}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 300px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
								<tr>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 zindex-3 text-center" colspan="2">Phân khu</th>
								</tr>
								<tr>
									<th class="align-center h-px-40 text-center" width="20%">Có link</th>
									<th class="align-center h-px-40 text-center" width="20%">Chưa có link</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								<?php
$__section_i_3_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_3_total = min(($__section_i_3_loop - 0), 16);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_3_total !== 0) {
for ($__section_i_3_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_3_iteration <= $__section_i_3_total; $__section_i_3_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
									<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >									
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								<?php
}
}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="nav-align-top nav-tabs-shadow">
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button type="button" class="nav-link tab_report  active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-highfloor" aria-controls="navs-highfloor" aria-selected="true">Cao tầng</button>
			</li>
			<li class="nav-item" role="presentation">
				<button type="button" class="nav-link tab_report " role="tab" data-bs-toggle="tab" data-bs-target="#navs-lowfloor" aria-controls="navs-lowfloor" aria-selected="false" tabindex="-1">Thấp tầng</button>
			</li>
		</ul>
		<div class="tab-content p-0">
			<div class="tab-pane card_load_time fade active show" id="navs-highfloor" role="tabpanel">
				<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Bảng theo dõi tình trạng cao tầng</h5>
					<div class="w-px-150">
						<input type="date" class="form-control" name="date" value=<?php echo smarty_modifier_date_format(time(),"%Y-%m-%d");?>
 max="<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d');?>
" onChange="$Core.crawl.reload(this,event)" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_project"}'>
					</div>
				</div>
				<div class="card-body ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=ajax_load_status_log_stock&stock_type=<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_project"}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 500px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-5" style="background: #F5F7F8 !important">
								<tr>
									<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
										<th class="align-center h-px-40 zindex-3" width="3%" rowspan="2">No.</th>
									<?php }?>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 text-center" width="20%" colspan="4">Phân khu</th>
								</tr>
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								<?php
$__section_i_4_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_4_total = min(($__section_i_4_loop - 0), 16);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_4_total !== 0) {
for ($__section_i_4_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_4_iteration <= $__section_i_4_total; $__section_i_4_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
									<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >
										<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
											<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<?php }?>										
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								<?php
}
}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="tab-pane fade card_load_time" id="navs-lowfloor" role="tabpanel">
				<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Bảng theo dõi tình trạng thấp tầng</h5>
					<div class="w-px-150">
						<input type="date" class="form-control" name="date" value=<?php echo smarty_modifier_date_format(time(),"%Y-%m-%d");?>
 max="<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d');?>
" onChange="$Core.crawl.reload(this,event)" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_project"}'>
					</div>
				</div>
				<div class="card-body ajax" data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=ajax_load_status_log_stock&stock_type=<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" data-options='{"_type":"_project"}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2"  style="height: 500px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-5" style="background: #F5F7F8 !important">
								<tr>
									<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
										<th class="align-center h-px-40 zindex-3" width="3%" rowspan="2">No.</th>
									<?php }?>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 text-center" width="20%" colspan="4">Dự án</th>
								</tr>
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								<?php
$__section_i_5_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_5_total = min(($__section_i_5_loop - 0), 16);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_5_total !== 0) {
for ($__section_i_5_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_5_iteration <= $__section_i_5_total; $__section_i_5_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
									<tr class="tr_agency tr_agency_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['property_id'];?>
" >
										<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
											<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<?php }?>										
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								<?php
}
}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>
<?php }
}
