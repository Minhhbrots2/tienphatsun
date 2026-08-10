<?php
/* Smarty version 3.1.33, created on 2026-08-08 15:32:09
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.billing.view.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76e9898f29f9_96334261',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2a04d21a12868d3a4348b1ded6b01f587859bc6f' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.billing.view.tpl',
      1 => 1786018265,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76e9898f29f9_96334261 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-ipad-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Thông tin giao dịch <?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['billing_code'];?>
</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="highlights px-3 mb-3">
			<div class="highlight-panel border border-gray rounded-3">
				<div class="highlight-list">
					<div class="highlight-item d-flex gap-2 align-items-center">
						<div class="highlight-icon p-2 rounded-2">
							<i class="bx bx-user"></i>
						</div>
						<div class="d-flex flex-column">
							<div class="highlight-label">Sale bán</div>
							<div class="metadata-row-viewer text-nowrap">
								<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getIndentity($_smarty_tpl->tpl_vars['oneBilling']->value['staff_id']);?>

							</div>
						</div>
					</div>
					<div class="highlight-item d-flex gap-2 align-items-center">
						<div class="highlight-icon p-2 rounded-2">
							<i class="bx bx-code"></i>
						</div>
						<div class="d-flex flex-column">
							<div class="highlight-label">Mã căn</div>
							<div class="metadata-row-viewer text-bold"><?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['stock_code'];?>
</div>
						</div>
					</div>
					<div class="highlight-item d-flex gap-2 align-items-center">
						<div class="highlight-icon p-2 rounded-2">
							<i class="bx bx-calendar"></i>
						</div>
						<div class="d-flex flex-column">
							<div class="highlight-label">Ngày cọc</div>
							<div class="metadata-row-viewer text-bold">
								<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['oneBilling']->value['deposit_date']);?>

							</div>
						</div>
					</div>
					<div class="highlight-item d-flex gap-2 align-items-center">
						<div class="highlight-icon p-2 rounded-2">
							<i class="bx bx-dollar"></i>
						</div>
						<div class="d-flex flex-column">
							<div class="highlight-label">Doanh số</div>
							<div class="metadata-row-viewer text-bold">
								<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['oneBilling']->value['totalgrand']);?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-body border-top border-gray bg-lightest">
			<ul class="nav nav-tabs nav-tabs-bordered mb-3" role="tablist">
				<li class="nav-item">
					<button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tabhome_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Chi tiết</button>
				</li>
				<li class="nav-item">
					<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabnotes_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Ghi chú</button>
				</li>
				<li class="nav-item">
					<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabfile_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">File đính kèm</button>
				</li>
			</ul>
			<div class="tab-content p-0">
				<div class="tab-pane fade show active" id="tabhome_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
					<?php if ($_smarty_tpl->tpl_vars['is_content_changing']->value == '1' && !empty($_smarty_tpl->tpl_vars['arr_change_logs']->value)) {?>
					<div class="alert alert-warning">
						<h3 class="mb-2 text-fs-18"><i class="bx bx-bell"></i> Thay đổi đang chờ được chấp nhận</h3>
						<div class="form-row">
							<div class="col-12 col-md-9 mb-2 mb-lg-0">
								<p class="mb-1"><strong>Lý do:</strong> <?php echo $_smarty_tpl->tpl_vars['arr_change_logs']->value['reason'];?>
</p>
								<p class="mb-1"><strong>Thời gian:</strong> <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['arr_change_logs']->value['reg_date'],true);?>
</p>
								<b>Chi tiết: </b>
								<ul class="mb-0">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_change_logs']->value['content_change'], '_oValue', false, '_oField');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oField']->value => $_smarty_tpl->tpl_vars['_oValue']->value) {
?>
										<?php if ($_smarty_tpl->tpl_vars['_oField']->value == 'totalgrand') {?>
										<li><?php echo $_smarty_tpl->tpl_vars['clsBilling']->value->getFieldName($_smarty_tpl->tpl_vars['_oField']->value);?>
 : <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatPrice($_smarty_tpl->tpl_vars['_oValue']->value);?>
</li>
										<?php } elseif ($_smarty_tpl->tpl_vars['_oField']->value == 'staff_id') {?>
										<li><?php echo $_smarty_tpl->tpl_vars['clsBilling']->value->getFieldName($_smarty_tpl->tpl_vars['_oField']->value);?>
 : <?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['_oValue']->value);?>
</li>
										<?php } elseif ($_smarty_tpl->tpl_vars['_oField']->value == 'stock_code') {?>
										<li><?php echo $_smarty_tpl->tpl_vars['clsBilling']->value->getFieldName($_smarty_tpl->tpl_vars['_oField']->value);?>
 : <?php echo $_smarty_tpl->tpl_vars['_oValue']->value;?>
</li>
										<?php }?>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</ul>
							</div>
							<?php if ($_smarty_tpl->tpl_vars['staff_confirm_id']->value == $_smarty_tpl->tpl_vars['profile_id']->value) {?>
							<div class="col-12 col-md-3">
								<div class="d-flex flex-column xs:flex-row gap-2 align-items-center">
									<button onClick="$Core.global.billing.do_change_confirmed(this, event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" 
										tp="approved" class="btn xs:flex-fill btn-block btn-outline-success">
										<i class="bx bx-check"></i> Chấp nhận</button>
									<button onClick="$Core.global.billing.do_change_confirmed(this, event)" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" 
										tp="rejected" class="btn xs:flex-fill btn-block btn-outline-secondary">
										<i class="bx bx-x"></i> Từ chối
									</button>
								</div>
							</div>
							<?php }?>
						</div>
					</div>
					<?php }?>
					<div class="form-row mb-2">
						<div class="col-12 col-md-6 mb-2 mb-lg-0">
							<div class="card no-shadow h-100">
								<div class="card-header">
									<h4 class="card-title mb-0">Thông tin giao dịch</h4>
								</div>
								<div class="card-body">
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-code text-fs-15"></i>
											</div>
											<span class="text-muted">Mã căn</span>
										</div>
										<strong class="text-dark"><?php echo $_smarty_tpl->tpl_vars['oneBilling']->value['stock_code'];?>
</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-calendar text-fs-15"></i>
											</div>
											<span class="text-muted">Ngày cọc</span>
										</div>
										<strong class="text-dark">
											<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['oneBilling']->value['deposit_date']);?>

										</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-dollar text-fs-15"></i>
											</div>
											<span class="text-muted">Doanh số</span>
										</div>
										<strong class="text-dark">
											<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatPrice($_smarty_tpl->tpl_vars['oneBilling']->value['totalgrand']);?>

										</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-chart text-fs-15"></i>
											</div>
											<span class="text-muted">Loại hình</span>
										</div>
										<?php if ($_smarty_tpl->tpl_vars['oneBilling']->value['billing_type'] > '0') {?>
										<strong class="text-dark">
											<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneBilling']->value['billing_type']);?>

										</strong>
										<?php } else { ?>
										<span class="text-muted d-flex align-items-center">
											<i class="bx bx-error text-muted"></i> Chưa có
										</span>
										<?php }?>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-home text-fs-15"></i>
											</div>
											<span class="text-muted">Dự án</span>
										</div>
										<strong class="text-dark">
											<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getTitle($_smarty_tpl->tpl_vars['oneBilling']->value['project_id']);?>

										</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-user text-fs-15"></i>
											</div>
											<span class="text-muted">Sale bán</span>
										</div>
										<strong class="text-dark">
											<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getIndentity($_smarty_tpl->tpl_vars['oneBilling']->value['staff_id']);?>

										</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-user text-fs-15"></i>
											</div>
											<span class="text-muted">Khách hàng</span>
										</div>
										<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['customer_name']) && !empty($_smarty_tpl->tpl_vars['more_information']->value['customer_name'])) {?>
											<strong class="text-dark">
												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['customer_name'];?>

											</strong>
										<?php } else { ?>
											<span class="badge bg-label-danger rounded-pill">
												<i class="bx bx-x text-fs-12"></i> Chưa có
											</span>
										<?php }?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="card no-shadow h-100">
								<div class="card-header">
									<h4 class="card-title mb-0">Thông tin hồ sơ</h4>
								</div>
								<div class="card-body">
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-calendar text-fs-15"></i>
											</div>
											<span class="text-muted">Ngày ký VBTT</span>
										</div>
										<?php if ($_smarty_tpl->tpl_vars['oneBilling']->value['agree_date'] > '0') {?>
											<span><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['oneBilling']->value['agree_date']);?>
<span>
										<?php } else { ?>
											<span class="badge bg-label-danger rounded-pill">
												<i class="bx bx-x text-fs-12"></i> Chưa có
											</span> 
										<?php }?>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-calendar text-fs-15"></i>
											</div>
											<span class="text-muted">Ngày ký HĐMB</span>
										</div>
										<?php if ($_smarty_tpl->tpl_vars['oneBilling']->value['contract_date'] > '0') {?>
											<span><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['oneBilling']->value['contract_date']);?>
<span>
										<?php } else { ?>
											<span class="badge bg-label-danger rounded-pill">
												<i class="bx bx-x text-fs-12"></i> Chưa có
											</span> 
										<?php }?>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-calendar text-fs-15"></i>
											</div>
											<span class="text-muted">Phương án thanh toán</span>
										</div>
										<?php if ($_smarty_tpl->tpl_vars['more_information']->value['billing_method'] > '0') {?>
											<span><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['more_information']->value['billing_method']);?>
<span>
										<?php } else { ?>
											<span class="badge bg-label-danger rounded-pill">
												<i class="bx bx-x text-fs-12"></i> Chưa rõ
											</span> 
										<?php }?>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-check-circle text-fs-15"></i>
											</div>
											<span class="text-muted">File CSBH</span>
										</div>
										<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file'])) {?>
										<a class="badge bg-label-primary rounded-pill" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['sale_policy_file'];?>
">
											<i class="bx bx-link-external text-fs-12"></i> Xem CSBH
										</a>
										<?php } else { ?>
										<span class="badge bg-label-danger rounded-pill">
											<i class="bx bx-x text-fs-12"></i> Chưa có
										</span>
										<?php }?>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-table text-fs-15"></i>
											</div>
											<span class="text-muted">File PTG:</span>
										</div>
										<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['price_sheet_file'])) {?>
										<div class="d-flex align-items-center gap-1">
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['price_sheet_file'], '_olink');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_olink']->value) {
?>
											<a class="badge bg-label-primary rounded-pill" target="_blank" data-fancybox href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_olink']->value['image']);?>
">
												<i class="bx bx-link-external text-fs-12"></i> <?php echo $_smarty_tpl->tpl_vars['_olink']->value['title'];?>

											</a>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										</div>
										<?php } else { ?>
										<span class="badge bg-label-danger rounded-pill">
											<i class="bx bx-x text-fs-12"></i> Chưa có
										</span>
										<?php }?>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-wallet-alt text-fs-15"></i>
											</div>
											<span class="text-muted">Ủy nhiệm chi</span>
										</div>
										<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['payment_order'])) {?>
										<div class="d-flex align-items-center gap-1">
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['payment_order'], '_olink');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_olink']->value) {
?>
											<a class="badge bg-label-primary  rounded-pill" data-fancybox target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_olink']->value['image']);?>
">
												<i class="bx bx-link-external text-fs-12"></i> <?php echo $_smarty_tpl->tpl_vars['_olink']->value['title'];?>

											</a>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										</div>
										<?php } else { ?>
										<span class="badge bg-label-danger rounded-pill">
											<i class="bx bx-x text-fs-12"></i> Chưa có
										</span>
										<?php }?>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-revision text-fs-15"></i>
											</div>
											<span class="text-muted">File HĐMB</span>
										</div>
										<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['contract_files'])) {?>
										<div class="d-flex align-items-center gap-1">
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['contract_files'], '_olink');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_olink']->value) {
?>
											<a class="badge bg-label-primary rounded-pill" target="_blank"  href="<?php echo $_smarty_tpl->tpl_vars['_olink']->value['image'];?>
">
												<i class="bx bx-link-external text-fs-12"></i> <?php echo $_smarty_tpl->tpl_vars['_olink']->value['title'];?>

											</a>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										</div>
										<?php } else { ?>
										<span class="badge bg-label-danger rounded-pill">
											<i class="bx bx-x text-fs-12"></i> Chưa có
										</span>
										<?php }?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if ($_smarty_tpl->tpl_vars['oneBilling']->value['is_interest_accrued'] == '1') {?>
					<div class="box rounded-2 overflow-hidden">
						<div class="box-header bg-lighter px-3 py-2">
							<h5 class="box-title text-fs-16 mb-0">Lãi phát sinh</h5>
						</div>
						<div class="box-body bg-white p-2">
							<div class="form-row">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['oneBilling']->value['interest_accrued'], '_OI', false, '_OK', 'ii', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_OK']->value => $_smarty_tpl->tpl_vars['_OI']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_ii']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_ii']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_ii']->value['index'];
?>
								<div class="col-12 col-md-6<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_ii']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_ii']->value['first'] : null)) {?> mb-2 mb-lg-0<?php }?>">
									<div class="bg-label-<?php if ($_smarty_tpl->tpl_vars['_OI']->value['is_completed'] == '1') {?>success<?php } else { ?>info<?php }?> rounded-2 p-3 mb-2 h-100">
										<h4 class="mb-2 text-fs-16"><?php if ($_smarty_tpl->tpl_vars['_OK']->value == 'first_interest') {?>Lãi phát sinh L1<?php } else { ?>Lãi phát sinh L2<?php }?></h4>
										<div class="p-3 rounded-2 bg-white">
											<div class="d-flex text-fs-28 mb-1">Số tiền: <strong><?php echo $_smarty_tpl->tpl_vars['_OI']->value['amount'];?>
</strong></div>
											<div class="d-flex align-items-center mb-1">Ngày tính: <?php echo $_smarty_tpl->tpl_vars['_OI']->value['accrual_date'];?>
</div>
											<div class="d-flex align-items-center">Ghi chú: <?php echo $_smarty_tpl->tpl_vars['_OI']->value['notes'];?>
</div>
										</div>
									</div>
								</div>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</div>
						</div>
					</div>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['oneBilling']->value['is_deposit_paid'] == '1') {?>
					<div class="rounded-2 mb-2" style="background:rgb(244,247,244);">
						<div class="d-flex align-items-center gap-2 p-3 pb-2">
							<i class="bx bxs-check-circle text-success text-fs-26"></i>
							<strong class="text-fs-16">Thông tin đóng 10%</strong>
						</div>
						<div class="p-3 pt-0">
							<div class="p-2 rounded-3 bg-white">
								<table class="clientssummarystats rounded-2 overflow-hidden" width="100%">
									<tr>
										<td width="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>40<?php } else { ?>25<?php }?>%" class="text-right">Ngày khớp</td>
										<td colspan="3"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['action_date'],true);?>
</td>
									</tr>
									<tr>
										<td class="text-right">Số tiền</td>
										<td colspan="3"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatPrice($_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['deposit_paid_amount']);?>
 <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>
</td>
									</tr>
									<tr>
										<td  class="text-right">Họ và tên/UNC</td>
										<td colspan="3"><?php echo $_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['payer_name'];?>
</td>
									</tr>
									<tr>
										<td  class="text-right">Mã FT</td>
										<td colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['trans_code'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['deposit_paid_to_company']->value['trans_code'];?>

											<?php } else { ?>
												---
											<?php }?>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('PROJECT_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('ADMIN_PROJECT')) {?>
					<div class="box rounded-2 overflow-hidden">
						<div class="box-header bg-lighter px-3 pt-3 pb-2">
							<h5 class="box-title text-fs-16 mb-0">Lịch sử giao dịch</h5>
						</div>
						<div class="box-body bg-white p-2">
							<div class="logs_<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
">
								<div class="loader p-5 text-center">Loading...</div>
							</div>
						</div>
					</div>
					<?php }?>
				</div>
				<div class="tab-pane fade" id="tabnotes_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
					<div class="widget-block mb-2">
						<div class="widget-header">Thêm ghi chú</div>
						<div class="widget-content">
							<form class="frmIssue" name="" action="">
								<textarea class="form-control" name="content" rows="2" placeholder="Nhập ghi chú"></textarea>
								<div class="clearfix mt-2">
									<button type="button" tp="_create" class="btn btn-outline-primary" 
									for_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" clsTable="Billing" note_id="" onClick="$Core.helper.save_notes(this,event)">Thêm</button>
								</div>
							</form>
						</div>
					</div>
					<div class="widget-block">
						<div class="widget-header">Ghi chú</div>
						<div class="widget-content">
							<div class="holder_notes_<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
">
								<div class="loader p-5 text-center">Loading...</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="tabfile_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
					<div class="widget-block">
						<div class="widget-header">Thêm File đính kèm</div>
						<div class="widget-content">
							<form class="frmIssue" enctype="multipart/form-data" name="" action="">
								<div class="form-group mb-2">
									<textarea class="form-control" name="description" cols="255" rows="2" placeholder="Nhập nội dung"></textarea>
								</div>
								<div class="form-group mb-2">
									<input type="file" class="form-control" name="attachment" />
								</div>
								<div class="clearfix">
									<input type="hidden" name="submit" value="Insert" />
									<button type="button" class="btn btn-outline-primary" 
									for_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" clsTable="Billing" onClick="$Core.member.ms_save_file(this,event)">Thêm</button>
								</div>
							</form>
						</div>
					</div>
					<div class="widget-block mt-2">
						<div class="widget-header">Danh sách file đính kèm</div>
						<div class="widget-content">
							<div class="holder_files_<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
">
								<div class="loader p-5 text-center">Loading...</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if ($_smarty_tpl->tpl_vars['oneBilling']->value['admin_id'] == $_smarty_tpl->tpl_vars['profile_id']->value || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkDEV()) {?>
			<div class="d-flex flex-wrap p-3 bg-label-light rounded-2 mt-2 gap-2">
				<button data-toggle="ripple" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onClick="$Core.billing.open_billing(this,event)" 
					class="btn flex-fill btn-sm btn-outline-default"><i class="bx bx-edit"></i> Sửa</button>
				<button data-toggle="ripple" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onClick="$Core.billing.add_info(this,event)" 
					class="btn flex-fill btn-sm btn-outline-default"><i class="bx bx-upload"></i> Thêm thông tin</button>
				<button data-toggle="ripple" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onClick="$Core.billing.open_activity(this,event)" 
					holderG="deposit_paid" class="btn flex-fill btn-sm btn-outline-default"><i class="bx bx-dollar"></i> Thêm 10%</button>
				<button data-toggle="ripple" billing_id="<?php echo $_smarty_tpl->tpl_vars['billing_id']->value;?>
" onClick="$Core.billing.open_activity(this,event)" 
					holderG="interest_accrued" class="btn flex-fill btn-sm btn-outline-default"><i class="bx bx-money"></i> Cập nhật lãi</button>
			</div>
			<?php }?>
		</div>
	</div>
</div>
<?php }
}
