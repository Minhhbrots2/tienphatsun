<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:31:19
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.load_total_stock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7694f7284ed4_37993418',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '795a14601037e7cc71e7d548fef0d7960b385945' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/crawl/_ajax.load_total_stock.tpl',
      1 => 1784299639,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7694f7284ed4_37993418 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-12 col-md-6">
	<div class="mb-2 card">
		<div class="card-header">
			<h3 class="card-title">Bảng hàng cao tầng</h3>
		</div>
		<div class="card-body">
			<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
			<div class="d-flex briefStockHug flex-wrap gap-2 mb-2 align-items-center">
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
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_highfloor']->value;?>
</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Dự án</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_block']->value;?>
</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Đại lý</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_agency_highfloor']->value;?>
</strong>
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
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_new_highfloor']->value;?>
</strong>
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
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_sold_highfloor']->value;?>
</strong>
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
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_lowfloor']->value;?>
</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Dự án</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_project']->value;?>
</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Đại lý</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_agency_lowfloor']->value;?>
</strong>
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
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_new_lowfloor']->value;?>
</strong>
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
							<strong class="fs-5 text-main"><?php echo $_smarty_tpl->tpl_vars['total_sold_lowfloor']->value;?>
</strong>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div><?php }
}
